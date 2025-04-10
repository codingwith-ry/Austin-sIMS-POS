<?php
include '../Login/database.php';

try {
    // Get the start date from the request (default to today's date if not provided)
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');

    // Parse the start date to get the start of the week (Sunday) and the end of the week (Saturday)
    $startDateObj = new DateTime($startDate);
    $startDateObj->setISODate($startDateObj->format('Y'), $startDateObj->format('W'));
    $startOfWeek = $startDateObj->format('Y-m-d');
    $endOfWeek = (clone $startDateObj)->modify('+6 days')->format('Y-m-d');

    // Query to fetch and group data by day of the week (from Sunday to Saturday)
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

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode(['success' => true, 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
