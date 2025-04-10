<?php
include '../Login/database.php';

try {
    // Get the start date from the request (default to today's date if not provided)
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');

    // Parse the start date to get the first and last day of the year
    $startDateObj = new DateTime($startDate);
    $startYear = $startDateObj->format('Y');
    $firstDayOfYear = new DateTime("$startYear-01-01");
    $lastDayOfYear = new DateTime("$startYear-12-31");

    // Query to fetch and group data by year
    $query = "
        SELECT 
            YEAR(Record_ItemPurchaseDate) AS year,
            SUM(Record_ItemPrice) AS total_expenses
        FROM tbl_record
        WHERE Record_ItemPurchaseDate BETWEEN :startDate AND :endDate
        GROUP BY year
        ORDER BY year
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':startDate', $firstDayOfYear->format('Y-m-d'));
    $stmt->bindParam(':endDate', $lastDayOfYear->format('Y-m-d'));
    $stmt->execute();

    // Fetch the results
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode(['success' => true, 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
