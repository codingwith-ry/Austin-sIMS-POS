<?php
include '../Login/database.php';

try {
    // Fetch all years where data exists with valid price and date
    $query = "
        SELECT 
            YEAR(Record_ItemPurchaseDate) AS year,
            SUM(Record_ItemPrice) AS total_expenses
        FROM tbl_record
        WHERE 
            Record_ItemPurchaseDate IS NOT NULL
            AND Record_ItemPrice IS NOT NULL
            AND Record_ItemPrice >= 0
        GROUP BY year
        ORDER BY year
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Structure the response data
    $labels = [];
    $expenses = [];

    foreach ($results as $row) {
        $labels[] = $row['year'];
        $expenses[] = round((float)$row['total_expenses'], 2);
    }

    echo json_encode([
        'success' => true,
        'labels' => $labels,
        'expenses' => $expenses,
        'totalExpenses' => array_sum($expenses)
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
