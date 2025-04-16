<?php
include '../Login/database.php';

try {
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');

    $year = (new DateTime($startDate))->format('Y');
    $firstDayOfYear = "$year-01-01";
    $lastDayOfYear = "$year-12-31";

    // ✅ Use only Record_ItemPrice in the SUM
    $query = "
        SELECT 
            MONTH(Record_ItemPurchaseDate) AS month,
            SUM(Record_ItemPrice) AS total_expenses
        FROM tbl_record
        WHERE 
            Record_ItemPurchaseDate BETWEEN :startDate AND :endDate
            AND Record_ItemPrice IS NOT NULL
            AND Record_ItemPrice >= 0
        GROUP BY month
        ORDER BY month
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':startDate' => $firstDayOfYear,
        ':endDate' => $lastDayOfYear
    ]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $months = [];
    $expenses = array_fill(0, 12, 0);

    for ($i = 1; $i <= 12; $i++) {
        $months[] = DateTime::createFromFormat('!m', $i)->format('F');
    }

    foreach ($results as $row) {
        $monthIndex = (int)$row['month'] - 1;
        $expenses[$monthIndex] = round((float)$row['total_expenses'], 2);
    }

    echo json_encode([
        'success' => true,
        'labels' => $months,
        'expenses' => $expenses,
        'totalExpenses' => array_sum($expenses)
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
