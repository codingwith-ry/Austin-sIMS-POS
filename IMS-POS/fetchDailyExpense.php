<?php
include '../Login/database.php';

try {
    // Get the start date from the request
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');

    // Query to fetch and group data by day of the week
    $query = "
        SELECT 
            DATE(Record_ItemPurchaseDate) AS purchase_date,
            SUM(Record_ItemPrice) AS total_expenses
        FROM tbl_record
        WHERE Record_ItemPurchaseDate = :startDate
        GROUP BY purchase_date
        ORDER BY purchase_date
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':startDate', $startDate);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode(['success' => true, 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
