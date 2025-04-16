<?php
include '../Login/database.php';

try {
    // If user provided a date, use it; otherwise, get today's date
    $startDate = isset($_GET['startDate']) && !empty($_GET['startDate'])
        ? $_GET['startDate']
        : date('Y-m-d');

    // Get the start and end of the week from the provided date
    $startDateObj = new DateTime($startDate);
    $startDateObj->setISODate($startDateObj->format('Y'), $startDateObj->format('W'));
    $startOfWeek = $startDateObj->format('Y-m-d');
    $endOfWeek = (clone $startDateObj)->modify('+6 days')->format('Y-m-d');

    // Fetch expenses grouped by purchase date within the week range
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

    // Initialize expenses for the week (Sunday to Saturday)
    $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $expenses = array_fill(0, 7, 0);

    // Fill in the expenses based on what day they occurred
    foreach ($results as $row) {
        $dayIndex = date('w', strtotime($row['purchase_date'])); // Sunday = 0
        $expenses[$dayIndex] = (float) $row['total_expenses'];
    }

    // Return as JSON
    echo json_encode([
        'success' => true,
        'labels' => $daysOfWeek,
        'expenses' => $expenses,
        'totalExpenses' => array_sum($expenses)
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
