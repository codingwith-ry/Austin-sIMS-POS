<?php
require_once('../../Login/database.php');
$orderStatus = isset($_GET['orderStatus']) ? $_GET['orderStatus'] : '';

$orders = $conn->query("
    SELECT 
    tbl_orders.orderID,
    tbl_orders.orderNumber, 
    tbl_orders.salesOrderNumber, 
    tbl_orders.orderTime, 
    tbl_orders.orderClass, 
    tbl_orders.orderStatus,
    tbl_orders.additionalNotes, 
    COALESCE(SUM(tbl_orderItems.productQuantity), 0) AS productQuantity
    FROM tbl_orders
    LEFT JOIN tbl_orderItems ON tbl_orders.orderNumber = tbl_orderItems.orderNumber
    GROUP BY 
    tbl_orders.orderNumber, 
    tbl_orders.salesOrderNumber, 
    tbl_orders.orderTime, 
    tbl_orders.orderClass, 
    tbl_orders.orderStatus
    HAVING tbl_orders.orderStatus = '$orderStatus'
    ORDER BY tbl_orders.orderID ASC
");

$data = [];
while ($order = $orders->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $order;
}

echo json_encode($data);
?>