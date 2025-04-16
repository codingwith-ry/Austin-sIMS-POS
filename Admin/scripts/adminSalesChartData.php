<?php
// Include database connection
include '../../Login/database.php'; // Adjust the path as necessary

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Get the current week's Monday and Sunday dates
        $monday = date('Y-m-d', strtotime('monday this week'));
        $sunday = date('Y-m-d', strtotime($monday . ' +6 days'));

        // Prepare the SQL query to fetch the sales data
        $stmt = $conn->prepare("
            SELECT 
                DAYNAME(o.orderDate) AS day,
                SUM(oi.productQuantity) AS total_sales
            FROM tbl_orders o
            JOIN tbl_orderitems oi ON o.orderID = oi.orderID
            WHERE o.orderDate BETWEEN :monday_start AND :monday_end
                AND o.orderStatus = 'DONE'
            GROUP BY DAYOFWEEK(o.orderDate)
            ORDER BY FIELD(DAYNAME(o.orderDate), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
        ");

        // Bind the Monday and Sunday dates to the SQL query parameters
        $stmt->bindParam(':monday_start', $monday);
        $stmt->bindParam(':monday_end', $sunday);

        // Execute the query
        $stmt->execute();

        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize an array for sales (Mon-Sun)
        $salesData = [0, 0, 0, 0, 0, 0, 0];

        // Map days of the week to their respective indices
        $dayMap = [
            'Monday' => 0,
            'Tuesday' => 1,
            'Wednesday' => 2,
            'Thursday' => 3,
            'Friday' => 4,
            'Saturday' => 5,
            'Sunday' => 6,
        ];

        // Fill the sales data for each day
        foreach ($results as $row) {
            $index = $dayMap[$row['day']];
            $salesData[$index] = (int)$row['total_sales'];
        }

        // Return the results as a JSON response
        echo json_encode([
            'success' => true,
            'sales' => $salesData
        ]);
    } catch (PDOException $e) {
        // Handle any exceptions and return an error message
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
