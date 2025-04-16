<?php
include '../../Login/database.php'; // make sure this path is correct for your setup

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Get current week's Monday
        $monday = date('Y-m-d', strtotime('monday this week'));
        $sunday = date('Y-m-d', strtotime($monday . ' +6 days'));

        $stmt = $conn->prepare("
    SELECT 
        DAYNAME(o.orderDate) AS day,
        SUM(oi.productQuantity) AS total_sales,
        SUM(oi.productTotal) AS total_revenue
    FROM tbl_orders o
    JOIN tbl_orderitems oi ON o.orderID = oi.orderID
    WHERE o.orderDate BETWEEN :monday_start AND :monday_end
      AND o.orderStatus = 'DONE'
    GROUP BY DAYOFWEEK(o.orderDate)
    ORDER BY FIELD(DAYNAME(o.orderDate), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
");

        $stmt->bindParam(':monday_start', $monday);
        $stmt->bindParam(':monday_end', $sunday);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize Mon-Sun with 0s
        $salesData = [0, 0, 0, 0, 0, 0, 0];
        $revenueData = [0, 0, 0, 0, 0, 0, 0];
        $dayMap = [
            'Monday' => 0,
            'Tuesday' => 1,
            'Wednesday' => 2,
            'Thursday' => 3,
            'Friday' => 4,
            'Saturday' => 5,
            'Sunday' => 6,
        ];

        foreach ($results as $row) {
            $index = $dayMap[$row['day']];
            $salesData[$index] = (int)$row['total_sales'];
            $revenueData[$index] = (float)$row['total_revenue'];
        }

        echo json_encode([
            'success' => true,
            'sales' => $salesData,
            'revenue' => $revenueData
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
