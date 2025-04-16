<?php
include '../../Login/database.php'; // Update path as needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Get count of 'DONE' orders grouped by weekday (1=Sunday, 2=Monday, ..., 7=Saturday in MySQL)
        $stmt = $conn->query("
            SELECT 
                DAYOFWEEK(orderDate) AS weekday, 
                COUNT(*) AS count 
            FROM tbl_orders 
            WHERE orderStatus = 'DONE'
            GROUP BY weekday
        ");

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize counts for each day (MySQL's DAYOFWEEK: Sunday=1 ... Saturday=7)
        $days = [
            1 => 0, // Sunday
            2 => 0, // Monday
            3 => 0, // Tuesday
            4 => 0, // Wednesday
            5 => 0, // Thursday
            6 => 0, // Friday
            7 => 0  // Saturday
        ];

        foreach ($result as $row) {
            $weekday = (int)$row['weekday'];
            $days[$weekday] = (int)$row['count'];
        }

        // Reorder to: Monday -> Sunday
        $orderedData = [
            $days[2], // Monday
            $days[3], // Tuesday
            $days[4], // Wednesday
            $days[5], // Thursday
            $days[6], // Friday
            $days[7], // Saturday
            $days[1]  // Sunday
        ];

        echo json_encode([
            'success' => true,
            'ordersPerDay' => $orderedData
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
