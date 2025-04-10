<?php
include '../Login/database.php';

try {
    // Get the start date from the request (default to today's date if not provided)
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');

    // Parse the start date to get the first and last day of the month
    $startDateObj = new DateTime($startDate);
    $firstDayOfMonth = $startDateObj->modify('first day of this month')->format('Y-m-d');
    $lastDayOfMonth = $startDateObj->modify('last day of this month')->format('Y-m-d');

    // Query to fetch and group data by month
    $query = "
        SELECT 
            MONTH(Record_ItemPurchaseDate) AS month,
            SUM(Record_ItemPrice) AS total_expenses
        FROM tbl_record
        WHERE Record_ItemPurchaseDate BETWEEN :startDate AND :endDate
        GROUP BY month
        ORDER BY month
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':startDate', $firstDayOfMonth);
    $stmt->bindParam(':endDate', $lastDayOfMonth);
    $stmt->execute();

    // Fetch the results
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode(['success' => true, 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
