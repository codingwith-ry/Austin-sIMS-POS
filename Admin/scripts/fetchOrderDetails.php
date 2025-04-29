<?php
include '../../Login/database.php'; // make sure this path is correct for your setup

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Get current week's Monday and Sunday
        $monday = date('Y-m-d', strtotime('monday this week'));
        $sunday = date('Y-m-d', strtotime($monday . ' +6 days'));

        $stmt = $conn->prepare("
            SELECT 
                m.productName,
                m.productImage,
                m.productPrice,
                SUM(oi.productQuantity) AS total_quantity,
                SUM(oi.productTotal) AS total_revenue
            FROM tbl_orders o
            JOIN tbl_orderitems oi ON o.orderNumber = oi.orderNumber
            JOIN tbl_menu m ON oi.productID = m.productID
            WHERE o.orderDate BETWEEN :monday_start AND :monday_end
              AND o.orderStatus = 'DONE'
            GROUP BY m.productID
            ORDER BY total_quantity DESC
        ");

        $stmt->bindParam(':monday_start', $monday);
        $stmt->bindParam(':monday_end', $sunday);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $results
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
