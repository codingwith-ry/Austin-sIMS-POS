<?php
    require_once('../../Login/database.php');
    
    $currentDate = date('Y-m-d');
    $checkID = $conn->prepare("SELECT MAX(orderNumber) AS maxOrderNumber, MAX(salesOrderNumber) AS maxSalesOrderNumber, orderDate FROM tbl_orders");
    $checkID->execute();
    $row = $checkID->fetch(PDO::FETCH_ASSOC);


    if ($row['maxOrderNumber']) {
        if ($row['orderDate'] == $currentDate) {
            $orderNumber = $row['maxOrderNumber'] + 1;
            $salesOrderNumber = $row['maxSalesOrderNumber'] + 1;
        } else {
            $orderNumber = 1001;
            $salesOrderNumber = $row['maxSalesOrderNumber'] + 1;
        }
    } else {
        $orderNumber = 1001;
        $salesOrderNumber = 10001;
    }
    
    echo json_encode(['orderNumber' => $orderNumber, 'salesOrderNumber' => $salesOrderNumber]);
?>