<?php
include '../../Login/database.php'; // Update path as needed

try {
    $stmt = $conn->prepare("
        SELECT 
    m.productName,
    m.categoryID,
    c.categoryName,
    c.categoryIcon,
    c.menuID, -- add this line
    SUM(oi.productQuantity) AS totalOrders
FROM tbl_orderitems oi
JOIN tbl_menu m ON m.productID = oi.productID
JOIN tbl_orders o ON o.orderNumber = oi.orderNumber
JOIN tbl_categories c ON c.categoryID = m.categoryID
WHERE o.orderStatus = 'DONE'
GROUP BY oi.productID
ORDER BY totalOrders DESC
LIMIT 10

    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "products" => $results
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
