<?php
require_once('../../Login/database.php');

header('Content-Type: application/json');

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Start transaction
    $conn->beginTransaction();

    // 1. Insert into tbl_menu
    $menuQuery = "INSERT INTO tbl_menu (productName, menuID, categoryID, productPrice) 
                 VALUES (:productName, :menuID, :categoryID, :productPrice)";
    $menuStmt = $conn->prepare($menuQuery);
    $menuStmt->execute([
        ':productName' => $data['productName'],
        ':menuID' => $data['menuID'],
        ':categoryID' => $data['categoryID'],
        ':productPrice' => $data['defaultPrice']
    ]);

    // Get the last inserted product ID
    $productID = $conn->lastInsertId();

    // 2. Insert variations if any
    if (!empty($data['variations'])) {
        $variationQuery = "INSERT INTO tbl_variations (variationName, variationPrice, productID) 
                          VALUES (:variationName, :variationPrice, :productID)";
        $variationStmt = $conn->prepare($variationQuery);

        foreach ($data['variations'] as $variation) {
            $variationStmt->execute([
                ':variationName' => $variation['name'],
                ':variationPrice' => $variation['price'],
                ':productID' => $productID
            ]);
        }
    }

    // 3. Insert product-addon relationships if any
    if (!empty($data['addons'])) {
        $productAddonQuery = "INSERT INTO tbl_menutoaddons (productID, addonID) 
                            VALUES (:productID, :addonID)";
        $productAddonStmt = $conn->prepare($productAddonQuery);

        foreach ($data['addons'] as $addon) {
            $productAddonStmt->execute([
                ':productID' => $productID,
                ':addonID' => $addon['id']
            ]);
        }
    }

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Product added successfully',
        'productID' => $productID
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Error adding product: ' . $e->getMessage()
    ]);
}
?>