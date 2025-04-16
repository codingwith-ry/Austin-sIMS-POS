<?php
require_once('../Login/database.php'); 

$orderDetail = null;
$orderItems = [];
if (isset($_GET['orderID'])) {
    $orderID = (int) $_GET['orderID'];

    // Fetch order details
    $stmt = $conn->prepare("SELECT * FROM tbl_orders WHERE orderID = ?");
    $stmt->execute([$orderID]);
    $orderDetail = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch order items
    $stmt = $conn->prepare("
        SELECT
        tbl_orderitems.orderItemID,
        tbl_orderitems.orderNumber,
        tbl_menuclass.menuName,
        tbl_categories.categoryName as productCategory,
        tbl_variations.variationName as productVariationName,
        tbl_menu.productName,
        tbl_orderitems.productQuantity,
        tbl_menu.productPrice,
        tbl_orderitems.productTotal,
         (
            SELECT GROUP_CONCAT(
                CONCAT(
                    '{\"addonName\":\"', a.addonName, '\",',
                    '\"addonPrice\":', a.addonPrice, '}'
                )
            )
            FROM tbl_orderaddons oa
            LEFT JOIN tbl_addons a ON oa.addonID = a.addonID
            WHERE oa.orderItemID = tbl_orderitems.orderItemID
        ) AS productAddons
        FROM tbl_orderitems
        LEFT JOIN tbl_variations ON tbl_orderitems.variationID = tbl_variations.variationID
        LEFT JOIN tbl_menu ON tbl_orderitems.productID = tbl_menu.productID
        LEFT JOIN tbl_menuClass ON tbl_menu.menuID = tbl_menuClass.menuID
        LEFT JOIN tbl_categories ON tbl_menu.categoryID = tbl_categories.categoryID
        WHERE tbl_orderitems.orderNumber = '{$orderDetail['orderNumber']}' AND tbl_orderitems.salesOrderNumber = {$orderDetail['salesOrderNumber']};
    ");

    $stmt->execute();
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="right-sidebar bg-dark-gray p-4 rounded-lg" style="border: 1px solid #000;">
    <div class="text-white mb-4">
        <p>Order No.:</p>
        <p class="h4 font-weight-bold">
            <?php echo $orderDetail ? htmlspecialchars($orderDetail['orderNumber']) : 'N/A'; ?>
        </p>
        
        <p>Customer Name: <?php echo $orderDetail['customerName']?></p>
        <p>Date: <?php echo $orderDetail['orderDate']?></p>
        <p>Time: <?php echo $orderDetail['orderTime']?></p>
        <p>Order Type: <?php echo $orderDetail['orderClass']?></p>
        <p>Payment Type: <?php echo $orderDetail['paymentMode']?></p>
    </div>
    
    <div class="text-white mb-4">
        <p class="h5 font-weight-bold">Order Items:</p>
        <ul class="list-unstyled">
            <?php if (!empty($orderItems)): ?>
                <?php foreach ($orderItems as $item): ?>
                    <li class="mb-2  border p-2 rounded-lg bg-light-gray">
                        <p class='fw-bold'><?php echo htmlspecialchars($item['menuName'] . ' - ' . $item['productCategory']); ?></p>
                        <div class="d-flex justify-content-between">
                            <span>
                                <?php echo htmlspecialchars($item['productQuantity'] . ' x ' . $item['productName']); ?>
                                
                                <?php echo $item['productVariationName'] ? '(' . htmlspecialchars($item['productVariationName']) . ')' : ''; ?>
                            </span>
                            <span>₱<?php echo number_format($item['productPrice'], 2); ?></span>
                        </div>
                        <!-- Add-ons placeholder -->
                        <ul class="ml-3">
                            <?php
                            // Fetch add-ons for the current item
                            $addonStmt = $conn->prepare("
                                SELECT a.addonName, a.addonPrice 
                                FROM tbl_orderaddons oa
                                LEFT JOIN tbl_addons a ON oa.addonID = a.addonID
                                WHERE oa.orderItemID = ?
                            ");
                            $addonStmt->execute([$item['orderItemID']]);
                            $addons = $addonStmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php if (!empty($addons)): ?>
                                <?php foreach ($addons as $addon): ?>
                                    <li>
                                        <div class="d-flex justify-content-between">
                                            <span>+ <?php echo htmlspecialchars($addon['addonName']); ?></span>
                                            <span>₱<?php echo number_format($addon['addonPrice'], 2); ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>No Add-Ons</li>
                            <?php endif; ?>
                        </ul>

                        <hr />
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Product Total:</span>
                            <span>₱<?php echo number_format($item['productTotal'], 2); ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No items found.</li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="text-white mb-4">
        <p>Total Amount:</p>
        <p class="h4 font-weight-bold">
            <?php echo $orderDetail ? '₱' . number_format($orderDetail['totalAmount'], 2) : 'N/A'; ?>
        </p>
        <p>Notes: <?php echo $orderDetail['additionalNotes']?></p>
    </div>
    <button id="printHistoryInvoice" class="btn btn-primary fw-bold w-100 py-2 rounded-lg" 
        data-order-number="<?php echo htmlspecialchars($orderDetail['orderNumber']); ?>"
        data-sales-order-number="<?php echo htmlspecialchars($orderDetail['salesOrderNumber']); ?>"
        data-employee-id="<?php echo htmlspecialchars($orderDetail['employeeID']); ?>"
        data-order-type="<?php echo htmlspecialchars($orderDetail['orderClass']); ?>"
        data-date-now="<?php echo htmlspecialchars($orderDetail['orderDate']); ?>"
        data-time-now="<?php echo htmlspecialchars($orderDetail['orderTime']); ?>"
        data-customer-name="<?php echo htmlspecialchars($orderDetail['customerName']); ?>"
        data-order-items="<?php echo htmlspecialchars(json_encode($orderItems)); ?>"
        data-total-amount="<?php echo htmlspecialchars($orderDetail['totalAmount']); ?>"
        data-amount-paid="<?php echo htmlspecialchars($orderDetail['amountPaid']); ?>"
        data-additional-notes="<?php echo htmlspecialchars($orderDetail['additionalNotes']); ?>"
        data-payment-method="<?php echo htmlspecialchars($orderDetail['paymentMode']); ?>"
    >Print Invoice</button>
</div>

<script>
    document.addEventListener('click', function (event) {
        if (event.target.matches('#printHistoryInvoice')) {
            let orderNumber = event.target.getAttribute('data-order-number');
            let salesOrderNumber = event.target.getAttribute('data-sales-order-number');
            let employeeID = event.target.getAttribute('data-employee-id');
            let orderType = event.target.getAttribute('data-order-type');
            let orderDate = event.target.getAttribute('data-date-now');
            let orderTime = event.target.getAttribute('data-time-now');
            let customerName = event.target.getAttribute('data-customer-name');
            let orderItems = event.target.getAttribute('data-order-items');
            let totalAmount = event.target.getAttribute('data-total-amount');
            let amountPaid = event.target.getAttribute('data-amount-paid');
            let additionalNotes = event.target.getAttribute('data-additional-notes');
            let paymentMethod = event.target.getAttribute('data-payment-method');

            orderItems = JSON.parse(orderItems);

            // Decode the productAddons for each item
            orderItems.forEach(item => {
                if (item.productAddons) {
                    item.productAddons = JSON.parse(`[${item.productAddons}]`);
                } else {
                    item.productAddons = [];
                }
            });

            const orderObj = {
                orderNumber: orderNumber,
                salesOrder: salesOrderNumber,
                employeeID: employeeID,
                orderType: orderType,
                orderDate: orderDate,
                orderTime: orderTime,
                customerName: customerName,
                orderItems: orderItems,
                totalAmount: totalAmount,
                amountPaid: amountPaid,
                changeAmount: amountPaid - totalAmount,
                additionalNotes: additionalNotes,
                paymentMode: paymentMethod
            };

            console.log(orderObj);

            fetch('generate_receipt.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderObj)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.blob(); // Get the response as a blob
            })
            .then(blob => {
                // Create a URL for the PDF blob
                const pdfUrl = URL.createObjectURL(blob);
                                            
                // Open the PDF in a new tab
                window.open(pdfUrl, '_blank');

                URL.revokeObjectURL(pdfUrl);
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to generate receipt', 'error');
            });
        }
    });
</script>