<?php
require_once('../Login/database.php'); 

$orderDetail = null;
if (isset($_GET['orderID'])) {
    $orderID = (int) $_GET['orderID'];
    $stmt = $conn->prepare("SELECT orderNumber, orderDate FROM tbl_orders WHERE orderID = ?");
    $stmt->execute([$orderID]);
    $orderDetail = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<div class="right-sidebar bg-dark-gray p-4 rounded-lg" style="border: 1px solid #000;">
    <div class="text-white mb-4">
        <p>Order No.:</p>
        <p class="h4 font-weight-bold">
            <?php echo $orderDetail ? htmlspecialchars($orderDetail['orderNumber']) : 'N/A'; ?>
        </p>
    </div>
    <div class="d-flex align-items-center text-white mb-2">
        <i class="fas fa-calendar-alt mr-2"></i>
        <p class="mb-0">
            <?php echo $orderDetail ? htmlspecialchars($orderDetail['orderDate']) : 'N/A'; ?>
        </p>
    </div>
    <!-- Dito yung content -->
    <button class="bg-medium-gray text-white w-100 py-2 rounded-lg">Print Invoice</button>
</div>