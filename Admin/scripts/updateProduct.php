<?php
require_once('../../Login/database.php');

header('Content-Type: application/json');

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Start transaction
    $conn->beginTransaction();

    // 1. Update tbl_menu
    $menuQuery = "UPDATE tbl_menu 
                 SET productName = :productName, 
                     menuID = :menuID, 
                     categoryID = :categoryID, 
                     productPrice = :productPrice
                 WHERE productID = :productID";
    
    $menuStmt = $conn->prepare($menuQuery);
    $menuStmt->execute([
        ':productName' => $data['productName'],
        ':menuID' => $data['menuID'],
        ':categoryID' => $data['categoryID'],
        ':productPrice' => $data['defaultPrice'],
        ':productID' => $data['productID']
    ]);

    // 2. Delete existing variations
    $existingVariationsQuery = "SELECT variationName, variationPrice FROM tbl_variations WHERE productID = :productID";
    $existingVariationsStmt = $conn->prepare($existingVariationsQuery);
    $existingVariationsStmt->execute([':productID' => $data['productID']]);
    $existingVariations = $existingVariationsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Format existing variations for comparison
    $existingVariationsFormatted = array_map(function($v) {
        return [
            'name' => $v['variationName'],
            'price' => $v['variationPrice']
        ];
    }, $existingVariations);

    // Compare variations
    $variationsChanged = false;

    // Check if number of variations changed
    if (count($existingVariationsFormatted) !== count($data['variations'])) {
        $variationsChanged = true;
    } else {
        // Compare each variation
        foreach ($data['variations'] as $index => $newVariation) {
            if (!isset($existingVariationsFormatted[$index]) ||
                $existingVariationsFormatted[$index]['name'] !== $newVariation['name'] ||
                $existingVariationsFormatted[$index]['price'] !== $newVariation['price']) {
                $variationsChanged = true;
                break;
            }
        }
    }

    // Only update variations if changes are detected
    if ($variationsChanged) {
        // Delete existing variations
        $deleteVariationsQuery = "DELETE FROM tbl_variations WHERE productID = :productID";
        $deleteVariationsStmt = $conn->prepare($deleteVariationsQuery);
        $deleteVariationsStmt->execute([':productID' => $data['productID']]);

        // Insert new variations
        if (!empty($data['variations'])) {
            $variationQuery = "INSERT INTO tbl_variations (productID, variationName, variationPrice) 
                            VALUES (:productID, :variationName, :variationPrice)";
            $variationStmt = $conn->prepare($variationQuery);

            foreach ($data['variations'] as $variation) {
                $variationStmt->execute([
                    ':productID' => $data['productID'],
                    ':variationName' => $variation['name'],
                    ':variationPrice' => $variation['price']
                ]);
            }
        }
    }

    // Get existing addons
    $existingAddonsQuery = "SELECT addonID FROM tbl_product_addons WHERE productID = :productID";
    $existingAddonsStmt = $conn->prepare($existingAddonsQuery);
    $existingAddonsStmt->execute([':productID' => $data['productID']]);
    $existingAddons = $existingAddonsStmt->fetchAll(PDO::FETCH_COLUMN);

    // Compare addons
    $addonsChanged = false;
    $newAddonIds = array_map(function($addon) {
        return $addon['id'];
    }, $data['addons']);

    if (count($existingAddons) !== count($newAddonIds)) {
        $addonsChanged = true;
    } else {
        sort($existingAddons);
        sort($newAddonIds);
        if ($existingAddons !== $newAddonIds) {
            $addonsChanged = true;
        }
    }

    // Update addons if changed
    if ($addonsChanged) {
        $deleteProductAddonsQuery = "DELETE FROM tbl_product_addons WHERE productID = :productID";
        $deleteProductAddonsStmt = $conn->prepare($deleteProductAddonsQuery);
        $deleteProductAddonsStmt->execute([':productID' => $data['productID']]);

        if (!empty($data['addons'])) {
            $productAddonQuery = "INSERT INTO tbl_product_addons (productID, addonID) 
                                VALUES (:productID, :addonID)";
            $productAddonStmt = $conn->prepare($productAddonQuery);

            foreach ($data['addons'] as $addon) {
                $productAddonStmt->execute([
                    ':productID' => $data['productID'],
                    ':addonID' => $addon['id']
                ]);
            }
        }
    }

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully'
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'Error updating product: ' . $e->getMessage()
    ]);
}
?>