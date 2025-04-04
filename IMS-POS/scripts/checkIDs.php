<?php
    require_once('../../Login/database.php');
    
    $currentDate = date('Y-m-d');
    $checkID = $conn->prepare("SELECT orderNumber,salesOrderNumber,orderDate FROM tbl_orders ORDER BY orderDate DESC LIMIT 1;");
    $checkID->execute();
    $row = $checkID->fetch(PDO::FETCH_ASSOC);


    if (isset($row['orderNumber'])) {
        if ($row['orderDate'] == $currentDate) {
            $orderNumber = $row['orderNumber'] + 1;
            $salesOrderNumber = $row['salesOrderNumber'] + 1;
        } else {
            $orderNumber = 1001;
            $salesOrderNumber = $row['salesOrderNumber'] + 1;
        }
    } else {
        $orderNumber = 1001;
        $salesOrderNumber = 10001;
    }
    
    echo json_encode(['orderNumber' => $orderNumber, 'salesOrderNumber' => $salesOrderNumber]);
?>