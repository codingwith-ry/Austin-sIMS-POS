<?php
require_once('../../Login/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salesOrderNumber = $_POST['salesOrderNum'];
    $status = $_POST['status'];

    try {
        $stmt = $conn->prepare("UPDATE tbl_orders SET orderStatus = :status WHERE salesOrderNumber = :salesOrderNumber");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':salesOrderNumber', $salesOrderNumber);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Order status updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update order status.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>