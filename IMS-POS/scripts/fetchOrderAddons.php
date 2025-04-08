<?php
require_once('../../Login/database.php');
$orderItem = json_decode(file_get_contents('php://input'), true);

$orders = $conn->query("
    SELECT
    tbl_addons.addonName
    FROM tbl_orderaddons
    LEFT JOIN tbl_addons ON tbl_orderaddons.addonID = tbl_addons.addonID
    WHERE tbl_orderaddons.orderItemID = '{$orderItem['orderItemID']}';
");

$data = [];
while ($order = $orders->fetch(PDO::FETCH_ASSOC)) {
    $dataAddons[] = $order;
}

echo json_encode($data);
?>