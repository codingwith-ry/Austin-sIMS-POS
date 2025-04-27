<?php
require_once('../../Login/database.php');

$menuID = isset($_GET['menuID']) ? intval($_GET['menuID']) : 0;

$query = "SELECT categoryID, categoryName FROM tbl_categories WHERE menuID = :menuID";
$result = $conn->prepare($query);
$result->bindParam(':menuID', $menuID, PDO::PARAM_INT);
$result->execute();

$categories = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $categories[] = $row;
}

header('Content-Type: application/json');
echo json_encode($categories);
?>