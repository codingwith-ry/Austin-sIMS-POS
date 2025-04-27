<?php
require_once('../../Login/database.php');

$menuID = isset($_GET['menuID']) ? intval($_GET['menuID']) : 0;

$query = "SELECT addonID, addonName, addonPrice FROM tbl_addons WHERE menuID = :menuID";
$result = $conn->prepare($query);
$result->bindParam(':menuID', $menuID, PDO::PARAM_INT);
$result->execute();

$addons = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $addons[] = $row;
}

header('Content-Type: application/json');
echo json_encode($addons);
?>