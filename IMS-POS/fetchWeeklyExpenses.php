<?php
include '../Login/database.php';

try {
    // Use current date or user-provided date
    $endDate = isset($_GET['startDate']) && !empty($_GET['startDate'])
        ? $_GET['startDate']
        : date('Y-m-d');

    // Calculate 6 days before to cover last 7 days (including end date)
    $endDateObj = new DateTime($endDate);
    $startDateObj = (clone $endDateObj)->modify('-6 days');

    $startOfWeek = $startDateObj->format('Y-m-d');
    $endOfWeek = $endDateObj->format('Y-m-d');

    $query = "
        SELECT 
            DATE(Record_ItemPurchaseDate) AS purchase_date,
            SUM(Record_ItemPrice) AS total_expenses
        FROM tbl_record
        WHERE Record_ItemPurchaseDate BETWEEN :startOfWeek AND :endOfWeek
        GROUP BY purchase_date
        ORDER BY purchase_date
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':startOfWeek', $startOfWeek);
    $stmt->bindParam(':endOfWeek', $endOfWeek);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create date labels for last 7 days ending on $endDate
    $labels = [];
    $expenses = [];

    $period = new DatePeriod($startDateObj, new DateInterval('P1D'), (clone $endDateObj)->modify('+1 day'));
    foreach ($period as $date) {
        $formatted = $date->format('Y-m-d');
        $labels[] = $date->format('D'); // Mon, Tue, etc.
        $expenses[$formatted] = 0; // Default 0 for each date
    }

    foreach ($results as $row) {
        $dateKey = $row['purchase_date'];
        if (isset($expenses[$dateKey])) {
            $expenses[$dateKey] = (float) $row['total_expenses'];
        }
    }

    echo json_encode([
        'success' => true,
        'labels' => $labels,
        'expenses' => array_values($expenses),
        'totalExpenses' => array_sum($expenses),
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
