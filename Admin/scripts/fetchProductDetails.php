<?php
require_once('../../Login/database.php');

header('Content-Type: application/json');

try {
    $productId = $_GET['productId'];
    
    // Get product details
    $productQuery = "SELECT * FROM tbl_menu WHERE productID = :productId";
    $productStmt = $conn->prepare($productQuery);
    $productStmt->execute([':productId' => $productId]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    // Get variations
    $variationsQuery = "SELECT * FROM tbl_variations WHERE productID = :productId";
    $variationsStmt = $conn->prepare($variationsQuery);
    $variationsStmt->execute([':productId' => $productId]);
    $variations = $variationsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get addons
    $addonsQuery = "SELECT a.* FROM tbl_addons a 
                    INNER JOIN tbl_menutoaddons pa ON a.addonID = pa.addonID 
                    WHERE pa.productID = :productId";
    $addonsStmt = $conn->prepare($addonsQuery);
    $addonsStmt->execute([':productId' => $productId]);
    $addons = $addonsStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'product' => $product,
        'variations' => $variations,
        'addons' => $addons
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>