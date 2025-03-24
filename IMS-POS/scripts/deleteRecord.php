<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../Login/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordIds = $_POST['recordIds'];

    // Debugging: Log received recordIds
    error_log("Received recordIds: " . print_r($recordIds, true));

    if (!empty($recordIds) && is_array($recordIds)) {
        try {
            $placeholders = implode(',', array_fill(0, count($recordIds), '?'));
            $stmt = $conn->prepare("DELETE FROM tbl_record WHERE Record_ID IN ($placeholders)");
            $stmt->execute($recordIds);

            // Fetch updated data
            $fetchStmt = $conn->query("
                SELECT 
                    r.Record_ID,
                    r.Record_ItemPurchaseDate,
                    r.Record_EmployeeAssigned,
                    i.Item_Image,
                    i.Item_Name,
                    r.Record_ItemVolume,
                    u.Unit_Name,
                    c.Category_Name,
                    r.Record_ItemQuantity,
                    r.Record_ItemExpirationDate,
                    r.Record_ItemPrice
                FROM tbl_record r
                LEFT JOIN tbl_item i ON r.Item_ID = i.Item_ID
                LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
                LEFT JOIN tbl_itemcategories c ON i.Item_Category = c.Category_ID
            ");
            $updatedData = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'updatedData' => $updatedData]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid record IDs']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>