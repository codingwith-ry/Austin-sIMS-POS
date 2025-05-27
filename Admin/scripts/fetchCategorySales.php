<?php
require_once('../../Login/database.php');

header('Content-Type: application/json');

try {
    // Get the menuID from the GET request
    $menuID = isset($_GET['menuID']) ? intval($_GET['menuID']) : 0;

    if ($menuID > 0) {
        // Query to fetch categories and their total orders
        $query = "
            SELECT 
            c.categoryName,
            SUM(oi.productTotal) AS totalOrders
            FROM tbl_categories c
            LEFT JOIN tbl_menu m ON c.categoryID = m.categoryID
            LEFT JOIN tbl_orderitems oi ON m.productID = oi.productID
            WHERE c.menuID = :menuID
            GROUP BY c.categoryName
            ORDER BY totalOrders DESC;
        ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':menuID', $menuID, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid menuID.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>