<?php
include '../../Login/database.php';

try {
    $stmt = $conn->prepare("
        SELECT 
            i.Item_Name,
            i.Item_Image,
            SUM(r.Record_ItemQuantity) AS totalQuantity,
            SUM(r.Record_TotalPrice) AS totalPrice
        FROM tbl_record r
        JOIN tbl_item i ON i.Item_ID = r.Item_ID
        GROUP BY r.Item_ID
        ORDER BY totalQuantity DESC
        LIMIT 10
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "records" => $results
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
