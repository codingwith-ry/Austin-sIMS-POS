<?php
include '../../Login/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT);

    if ($itemId) {
        $stmt = $conn->prepare("
            SELECT 
                i.Item_ID, i.Item_Name, i.Item_Image, 
                i.Item_Category, c.Category_Name, 
                i.Unit_ID, u.Unit_Name, u.Unit_Acronym,
                i.Item_Lowstock
            FROM tbl_item i
            JOIN tbl_itemcategories c ON i.Item_Category = c.Category_ID
            LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
            WHERE i.Item_ID = :item_id
        ");
        $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->execute();

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            echo json_encode(['success' => true, 'item' => $item]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid item ID.']);
    }
}
?>