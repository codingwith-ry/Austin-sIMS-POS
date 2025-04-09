<?php
require_once('../../Login/database.php');
$orderItem = isset($_GET['orderItemID']) ? $_GET['orderItemID'] : '';

$orders = $conn->query("
    SELECT
    tbl_addons.addonName
    FROM tbl_orderaddons
    LEFT JOIN tbl_addons ON tbl_orderaddons.addonID = tbl_addons.addonID
    WHERE tbl_orderaddons.orderItemID = $orderItem;
");

$dataAddons = [];
while ($order = $orders->fetch(PDO::FETCH_ASSOC)) {
    $dataAddons[] = $order;
}

echo json_encode($dataAddons);
?>