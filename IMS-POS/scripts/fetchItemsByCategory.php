<?php
include '../../Login/database.php';

header('Content-Type: application/json');

$categoryID = isset($_GET['categoryID']) ? intval($_GET['categoryID']) : 0;

if ($categoryID > 0) {
    try {
        $query = "SELECT Item_ID, Item_Name FROM tbl_item WHERE Item_Category = :categoryID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':categoryID', $categoryID, PDO::PARAM_INT);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($items);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid category ID.']);
}
?>