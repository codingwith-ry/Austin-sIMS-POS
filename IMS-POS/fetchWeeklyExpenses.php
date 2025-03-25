<?php
include '../Login/database.php';

try {
    // Get the start and end dates for the current week
    $startOfWeek = date('Y-m-d', strtotime('sunday last week'));
    $endOfWeek = date('Y-m-d', strtotime('saturday this week'));

    // Query to fetch and group data by day of the week
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
?>