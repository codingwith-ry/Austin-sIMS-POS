<?php
include '../../Login/database.php';
header('Content-Type: application/json');

try {
    $range = $_GET['range'] ?? 'weekly';
    $startDate = new DateTime();
    $endDate = new DateTime(); // today

    switch ($range) {
        case 'weekly':
            $startDate->modify('-6 days'); // Last 7 days including today
            $groupFormat = '%Y-%m-%d';
            break;

        case 'monthly':
            $startDate = new DateTime(date('Y-01-01')); // Start from January of the current year
            $currentMonth = (int)date('m'); // Get current month (1-12)
            $groupFormat = '%Y-%m';
            break;

        case 'yearly':
            $query = "
                SELECT 
                    YEAR(r.Record_ItemPurchaseDate) AS date,
                    SUM(r.Record_ItemQuantity) AS totalQuantity,
                    SUM(r.Record_TotalPrice) AS totalPrice
                FROM tbl_record r
                GROUP BY YEAR(r.Record_ItemPurchaseDate)
                ORDER BY date ASC
            ";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'labels' => array_column($results, 'date'),
                'quantities' => array_column($results, 'totalQuantity'),
                'prices' => array_column($results, 'totalPrice')
            ]);
            exit;
    }

    // Format dates
    $startDateFormatted = $startDate->format('Y-m-d');
    $endDateFormatted = $endDate->format('Y-m-d');

    // Fetch actual data (total quantity and total price)
    $query = "
        SELECT 
            DATE_FORMAT(r.Record_ItemPurchaseDate, '$groupFormat') AS date,
            SUM(r.Record_ItemQuantity) AS totalQuantity,
            SUM(r.Record_TotalPrice) AS totalPrice
        FROM tbl_record r
        WHERE r.Record_ItemPurchaseDate BETWEEN :startDate AND :endDate
        GROUP BY DATE_FORMAT(r.Record_ItemPurchaseDate, '$groupFormat')
        ORDER BY date ASC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':startDate', $startDateFormatted);
    $stmt->bindParam(':endDate', $endDateFormatted);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch both quantities and prices

    if (!$data) {
        // If there's no data
        echo json_encode([
            'success' => false,
            'message' => 'No data found for the specified range.'
        ]);
        exit;
    }

    // Generate labels for months and populate quantities and prices
    $labels = [];
    $quantities = [];
    $prices = [];

    if ($range === 'monthly') {
        // Generate labels from January to current month
        for ($month = 1; $month <= $currentMonth; $month++) {
            $monthLabel = sprintf("%04d-%02d", date('Y'), $month); // e.g. 2025-01
            $labels[] = date('M', strtotime($monthLabel)); // e.g. Jan, Feb, etc.

            $record = array_filter($data, fn($d) => $d['date'] === $monthLabel);
            $record = reset($record);

            if ($record) {
                $quantities[] = (int)$record['totalQuantity'];
                $prices[] = (float)$record['totalPrice'];
            } else {
                $quantities[] = 0;
                $prices[] = 0.0;
            }
        }
    } else {
        // Process weekly or yearly data
        foreach ($data as $row) {
            $labels[] = $row['date'];
            $quantities[] = (int)$row['totalQuantity'];
            $prices[] = (float)$row['totalPrice'];
        }
    }

    echo json_encode([
        'success' => true,
        'labels' => $labels,
        'quantities' => $quantities,
        'prices' => $prices,
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
