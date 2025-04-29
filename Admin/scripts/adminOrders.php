<?php
include '../../Login/database.php'; // Update path as needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Get total expenses (sum of Record_ItemPrice) grouped by weekday
        $stmt = $conn->query("
            SELECT 
                DAYOFWEEK(Record_ItemPurchaseDate) AS weekday, 
                SUM(Record_ItemPrice) AS total_expense
            FROM tbl_record
            GROUP BY weekday
        ");

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize total expenses for each day (Sunday=1, Monday=2, ..., Saturday=7)
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
            $days[$weekday] = (float)$row['total_expense']; // Use float for money
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
