<?php
include '../../Login/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT);
    $itemName = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING);
    $itemCategory = filter_input(INPUT_POST, 'item_category', FILTER_SANITIZE_NUMBER_INT);
    $unitId = filter_input(INPUT_POST, 'unit_id', FILTER_SANITIZE_NUMBER_INT);
    $itemLowstock = filter_input(INPUT_POST, 'item_lowstock', FILTER_SANITIZE_NUMBER_INT);

    // Handle file upload for item image
    $file_name = $_FILES["item_image"]["name"];
    $tempname = $_FILES["item_image"]["tmp_name"];
    $folder = '../../IMS-POS/itemImages/' . $file_name;

    if ($itemId && $itemName && $itemCategory && $unitId && $itemLowstock) {
        try {
            if (!empty($file_name)) {
                // Update with new image
                move_uploaded_file($tempname, $folder);
                $stmt = $conn->prepare("
                    UPDATE tbl_item
                    SET Item_Name = :item_name,
                        Item_Category = :item_category,
                        Unit_ID = :unit_id,
                        Item_Lowstock = :item_lowstock,
                        Item_Image = :item_image
                    WHERE Item_ID = :item_id
                ");
                $stmt->bindParam(':item_image', $folder);
            } else {
                // Update without changing the image
                $stmt = $conn->prepare("
                    UPDATE tbl_item
                    SET Item_Name = :item_name,
                        Item_Category = :item_category,
                        Unit_ID = :unit_id,
                        Item_Lowstock = :item_lowstock
                    WHERE Item_ID = :item_id
                ");
            }

            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->bindParam(':item_name', $itemName, PDO::PARAM_STR);
            $stmt->bindParam(':item_category', $itemCategory, PDO::PARAM_INT);
            $stmt->bindParam(':unit_id', $unitId, PDO::PARAM_INT);
            $stmt->bindParam(':item_lowstock', $itemLowstock, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update item.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    }
}
?>