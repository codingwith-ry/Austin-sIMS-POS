<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include 'links.php'?>
</head>
<body>
    
<?php include 'verticalNav.php'?>

<div id="mainContent">
    <div class="bg-white p-4 rounded shadow-sm" style="width: 100%;">
        <div class="mb-1">
            <h4 class="fw-bold">Order Summary</h4>
        </div>
        <hr />
        <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
                <p class="mb-1">Order Type:</p>
                <h2 class="fw-bold" id="orderType"></h2>
            </div>
            <div class="text-end">
                <p class="mb-1" id="orderDate"></p>
                <p id="orderTime"></p>
            </div>
        </div>

        <div class="row">
            <div class="col-6 p-3">
                <h6 class="fw-bold mb-3">Order Details</h6>
                <div id="orderItemsContainer"></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold p-4 pt-0">
                    <span>Amount Paid</span>
                    <span id="amountPaid"></span>
                </div>
                <div class="d-flex justify-content-between fw-bold p-4 pt-0">
                    <span>Order Total</span>
                    <span id="totalAmount"></span>
                </div>
                <div class="d-flex justify-content-between fw-bold p-4 pt-0">
                    <span>Change</span>
                    <span id="changeAmount"></span>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="col-6 p-3">
                <div class="mb-3">
                    <label for="customerName" class="form-label fw-bold">Customer Name</label>
                    <input type="text" class="form-control" id="customerName" placeholder="Juan Dela Cruz">
                </div>

                <hr>
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" id="additionalNotes" style="height: 100px"></textarea>
                    <label for="additionalNotes">Additional Notes</label>
                </div>
                <hr>
                <h6 class="fw-bold mb-3">Select a payment method</h6>
                <select class="form-select mb-4" id="paymentMethod">
                    <option selected>Cash</option>
                    <option>GCash</option>
                    <option>PayMaya</option>
                </select>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-success flex-grow-1 me-2" id="payNowBtn">Pay Now</button>
                    <button class="btn btn-danger flex-grow-1 ms-2" id="cancelOrderBtn">Back to Menu</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load order items from localStorage
    const orderItems = JSON.parse(localStorage.getItem('orderItems')) || [];
    const orderType = localStorage.getItem('orderType') || 'Take Out';
    const orderItemsContainer = document.getElementById('orderItemsContainer');
    const totalAmountElement = document.getElementById('totalAmount');
    const amountPaidElement = document.getElementById('amountPaid');
    const changeAmountElement = document.getElementById('changeAmount');
    let totalAmount = 0;
    let amountPaid = 0;

    orderItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.classList.add('border', 'rounded', 'p-4', 'mb-2');
        itemElement.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-bold">${item.productQuantity} × ${item.productName}</span>
                <span>₱${(item.productPrice * item.productQuantity).toFixed(2)}</span>
            </div>
            <div id="addonsSection">
                ${item.productAddons.map(addon => `
                    <div id="addonSumRow" class="d-flex justify-content-between mb-2">
                        <span>+${addon.addonName}</span>
                        <span>₱${(addon.addonPrice * item.productQuantity).toFixed(2)}</span>
                    </div>
                `).join('')}
            </div>
            <hr />
            <div class="d-flex"><span class="fw-bold ms-auto">₱${item.productTotal.toFixed(2)}</span></div>
        `;
        orderItemsContainer.appendChild(itemElement);
        totalAmount += item.productTotal;
    });
    amountPaid = totalAmount;
    amountPaidElement.textContent = `₱${amountPaid.toFixed(2)}`;
    totalAmountElement.textContent = `₱${totalAmount.toFixed(2)}`;
    changeAmountElement.textContent = `₱${(amountPaid - totalAmount).toFixed(2)}`;
    
    document.getElementById('orderType').textContent = orderType;

    // Set current date and time
    const now = new Date();
    document.getElementById('orderDate').textContent = now.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
    document.getElementById('orderTime').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

    // Cancel order button
    document.getElementById('cancelOrderBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "Payment preferences will not be saved.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'Menu.php';
            }
        });
    });

    // Pay now button
    document.getElementById('payNowBtn').addEventListener('click', function() {
        const customerName = document.getElementById('customerName').value;
        const additionalNotes = document.getElementById('additionalNotes').value;

        Swal.fire({
            title: 'Confirm Payment',
            text: "Proceed with the payment?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Proceed'
        }).then((result) => {
            if (result.isConfirmed) {
                const queueNumber = String(Math.floor(Math.random() * 100) + 1).padStart(3, '0'); // Generate a random queue number and pad with zeros
                
                // Generate receipt details
                let receiptDetails = `
                    <h4>Order Summary</h4>
                    <p><strong>Customer Name:</strong> ${customerName}</p>
                    <p><strong>Order Type:</strong> ${orderType}</p>
                    <p><strong>Date:</strong> ${now.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' })}</p>
                    <p><strong>Time:</strong> ${now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</p>
                    <hr>
                    <h5>Order Details</h5>
                `;

                orderItems.forEach(item => {
                    receiptDetails += `
                        <p>${item.productQuantity} × ${item.productName} - ₱${(item.productPrice * item.productQuantity).toFixed(2)}</p>
                        ${item.productAddons.map(addon => `
                            <p>+${addon.addonName} - ₱${(addon.addonPrice * item.productQuantity).toFixed(2)}</p>
                        `).join('')}
                    `;
                });

                receiptDetails += `
                    <hr>
                    <p><strong>Order Total:</strong> ₱${totalAmount.toFixed(2)}</p>
                    <p><strong>Amount Paid:</strong> ₱${amountPaid.toFixed(2)}</p>
                    <p><strong>Change:</strong> ₱${(amountPaid - totalAmount).toFixed(2)}</p>
                    <hr>
                    <p><strong>Additional Notes:</strong> ${additionalNotes}</p>
                    <hr>
                    <p><strong>Queue Number:</strong> ${queueNumber}</p>
                `;

                Swal.fire({
                    title: 'Payment Successful!',
                    html: receiptDetails,
                    icon: 'success'
                }).then(() => {
                    window.location.href = 'Menu.php';
                    localStorage.removeItem('orderItems');
                });
            }
        });
    });
});
</script>

</body>
</html>