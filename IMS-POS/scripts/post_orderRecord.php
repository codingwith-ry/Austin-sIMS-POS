<?php
require_once('../../Login/database.php');

$orderData = json_decode(file_get_contents('php://input'), true);

$orderNumber = $orderData['orderNumber'];
$orderDate = $orderData['orderDate'];
$orderTime = $orderData['orderTime'];
$orderClass = $orderData['orderType'];
$salesOrderNumber = $orderData['salesOrder'];
$employeeID = $orderData['employeeID'];
$customerName = $orderData['customerName'];
$totalAmount = $orderData['totalAmount'];
$amountPaid = $orderData['amountPaid'];
$paymentMode = $orderData['paymentMode'];
$orderStatus = "IN PROCESS";
$additionalNotes = $orderData['additionalNotes'];
$orderItems = $orderData['orderItems'];


try {
    // Insert the main order into tbl_orders
    $stmt = $conn->prepare("INSERT INTO tbl_orders (orderNumber, orderDate, orderTime, orderClass, orderStatus, salesOrderNumber, employeeID, customerName, totalAmount, amountPaid, paymentMode, additionalNotes) VALUES (:orderNumber, :orderDate, :orderTime, :orderClass, :orderStatus, :salesOrderNumber, :employeeID, :customerName, :totalAmount, :amountPaid, :paymentMode, :additionalNotes)");
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
    $stmt->bindParam(':additionalNotes', $additionalNotes, PDO::PARAM_STR);

    $stmt->execute();

    $lastOrderID = $orderNumber;

    foreach ($orderItems as $item) {
        $productID = $item['productID'];
        $menuID = $item['menuID'];
        $menuName = $item['menuName'];
        $productVariation = $item['productVariation'];
        $productQuantity = $item['productQuantity'];
        $productTotal = $item['productTotal'];

        $stmt = $conn->prepare("INSERT INTO tbl_orderitems (orderNumber, salesOrderNumber, productID, variationID, productQuantity, productTotal) VALUES (:orderNumber, :salesOrderNumber, :productID, :productVariation, :productQuantity, :productTotal)");
        $stmt->bindParam(':orderNumber', $lastOrderID, PDO::PARAM_INT);
        $stmt->bindParam(':salesOrderNumber', $salesOrderNumber, PDO::PARAM_INT);
        $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
        $stmt->bindParam(':productVariation', $productVariation, PDO::PARAM_INT);
        $stmt->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
        $stmt->bindParam(':productTotal', $productTotal, PDO::PARAM_STR);

        $stmt->execute();

        // Get the last inserted order item ID
        $lastOrderItemID = $conn->lastInsertId();

        // Insert each addon for the current order item into the order_addons table
        if(isset($item['productAddons'])) {
            foreach ($item['productAddons'] as $addon) {
                $addonID = $addon['addonID'];

                $stmt = $conn->prepare("INSERT INTO tbl_orderaddons (orderItemID, addonID) VALUES (:orderItemID, :addonID)");
                $stmt->bindParam(':orderItemID', $lastOrderItemID, PDO::PARAM_INT);
                $stmt->bindParam(':addonID', $addonID, PDO::PARAM_INT);

                $stmt->execute();
            }
        }
    }

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn = null;
?>