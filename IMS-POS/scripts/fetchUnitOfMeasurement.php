<?php
include '../../Login/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemID = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT);

    try {
        $stmt = $conn->prepare("
            SELECT 
                COALESCE(u.Unit_Name, 'No Unit Assigned') AS Unit_Name, 
                COALESCE(u.Unit_Acronym, '') AS Unit_Acronym
            FROM tbl_item i
            LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
            WHERE i.Item_ID = :item_id
        ");
        $stmt->bindParam(':item_id', $itemID, PDO::PARAM_INT);
        $stmt->execute();
        $unit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($unit) {
            echo json_encode(['success' => true, 'unit' => $unit]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unit of Measurement not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>