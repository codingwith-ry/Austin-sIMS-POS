<?php
require_once('../../Login/database.php');

$query = "SELECT menuID, menuName FROM tbl_menuclass";
$result = $conn->prepare($query);
$result->execute();

$menuClasses = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $menuClasses[] = $row;
}

header('Content-Type: application/json');
echo json_encode($menuClasses);
?>