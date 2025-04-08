<?php
require_once('../../Login/database.php');
$orderNumber = isset($_GET['orderNumber']) ? $_GET['orderNumber'] : '';

$orders = $conn->query("
    SELECT
    tbl_orderitems.orderItemID,
    tbl_orderitems.orderNumber,
    tbl_menuclass.menuName,
    tbl_categories.categoryName,
    tbl_variations.variationName,
    tbl_menu.productName,
    tbl_orderitems.productQuantity
    FROM tbl_orderitems
    LEFT JOIN tbl_variations ON tbl_orderitems.variationID = tbl_variations.variationID
    LEFT JOIN tbl_menu ON tbl_orderitems.productID = tbl_menu.productID
    LEFT JOIN tbl_menuClass ON tbl_menu.menuID = tbl_menuClass.menuID
    LEFT JOIN tbl_categories ON tbl_menu.categoryID = tbl_categories.categoryID
    HAVING tbl_orderitems.orderNumber = '{$orderNumber}';
");

$data = [];
while ($order = $orders->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $order;
}

echo json_encode($data);
?>