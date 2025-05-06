<?php
include '../../Login/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING);

    try {
        $stmt = $conn->prepare("
            SELECT 
                COALESCE(u.Unit_Name, 'No Unit Assigned') AS Unit_Name, 
                COALESCE(u.Unit_Acronym, '') AS Unit_Acronym
            FROM tbl_item i
            LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
            WHERE i.Item_Name = :item_name
        ");
        $stmt->bindParam(':item_name', $itemName, PDO::PARAM_STR);
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