<?php
require_once('../Login/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderID = $_POST['orderID'];
    $orderStatus = $_POST['orderStatus'];

    $query = "UPDATE tbl_orders SET orderStatus = :orderStatus WHERE orderID = :orderID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
    $stmt->bindParam(':orderStatus', $orderStatus, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Fetch updated order details
        $result = $conn->prepare("SELECT * FROM tbl_orders WHERE orderID = :orderID");
        $result->bindParam(':orderID', $orderID, PDO::PARAM_INT);
        $result->execute();
        $order = $result->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'orderDetails' => $order]);
    } else {
        error_log("Update failed: " . print_r($stmt->errorInfo(), true));
        echo json_encode(['success' => false, 'error' => $stmt->errorInfo()]);
    }
}
?>
