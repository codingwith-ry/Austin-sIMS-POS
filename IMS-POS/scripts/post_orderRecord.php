<?php
require_once('../../Login/database.php');

$orderData = json_decode(file_get_contents('php://input'), true);

$orderNumber = $orderData['orderNumber'];
$orderDate = $orderData['orderDate'];
$orderTime = $orderData['orderTime'];
$orderClass = $orderData['orderType'];
$salesOrderNumber = $orderData['salesOrder']; // Corrected key
$employeeID = $orderData['employeeID'];
$customerName = $orderData['customerName'];
$totalAmount = $orderData['totalAmount'];
$amountPaid = $orderData['amountPaid'];
$paymentMode = $orderData['paymentMode'];
$orderStatus = "IN PROCESS";
$orderItems = $orderData['orderItems'];

    // Prepare and bind
    try {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO tbl_orders (orderNumber, orderDate, orderTime, orderClass, orderStatus, salesOrderNumber, employeeID, customerName, totalAmount, amountPaid, paymentMode) VALUES (:orderNumber, :orderDate, :orderTime, :orderClass, :orderStatus, :salesOrderNumber, :employeeID, :customerName, :totalAmount, :amountPaid, :paymentMode)");
        $stmt->bindParam(':orderNumber', $orderNumber, PDO::PARAM_INT);
        $stmt->bindParam(':orderDate', $orderDate, PDO::PARAM_STR);
        $stmt->bindParam(':orderTime', $orderTime, PDO::PARAM_STR);
        $stmt->bindParam(':orderClass', $orderClass, PDO::PARAM_STR);
        $stmt->bindParam(':orderStatus', $orderStatus, PDO::PARAM_STR);
        $stmt->bindParam(':salesOrderNumber', $salesOrderNumber, PDO::PARAM_INT);
        $stmt->bindParam(':employeeID', $employeeID, PDO::PARAM_STR);
        $stmt->bindParam(':customerName', $customerName, PDO::PARAM_STR);
        $stmt->bindParam(':totalAmount', $totalAmount, PDO::PARAM_STR);
        $stmt->bindParam(':amountPaid', $amountPaid, PDO::PARAM_STR);
        $stmt->bindParam(':paymentMode', $paymentMode, PDO::PARAM_STR);

        $stmt->execute();



    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>