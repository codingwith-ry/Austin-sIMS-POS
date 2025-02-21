<?php include 'verticalNav.php'?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="mainContent">
        <div class="bg-white p-4 rounded shadow-sm" style="width: 100%;">
            <div class="mb-1">
                <h4 class="fw-bold">Order Summary</h4>
            </div>
            <hr />
            <div class="d-flex justify-content-between align-items-start mb-1">
                <div>
                    <p class="mb-1">Order No.:</p>
                    <h2 class="fw-bold">004827</h2>
                </div>
                <div class="text-end">
                    <p class="mb-1">Sun, Nov 10, 2024</p>
                    <p>09:16 AM</p>
                </div>
            </div>

            <div class="row">
                <div class="col-6 p-3">
                    <h6 class="fw-bold mb-3">Order Details</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <p>2 × Iced Matcha Latte</p>
                        <p>₱220.00</p>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <p>1 × Cheesy Lasagna</p>
                        <p>₱150.00</p>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <p>1 × Clubhouse Sandwich</p>
                        <p>₱90.00</p>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <p>1 × Overload Fries</p>
                        <p>₱110.00</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p>Items (5)</p>
                        <p>₱570.00</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Tax (12%)</p>
                        <p>₱61.07</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Discount 20% off</p>
                        <p>-₱126.21</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <p>Total Amount</p>
                        <p>₱504.86</p>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="col-6 p-3">
                    <h6 class="fw-bold mb-3">Order Status</h6>
                    <p class="text-warning">Pending</p>
                    <hr>
                    <h6 class="fw-bold mb-3">Additional Notes</h6>
                    <p>No onions in the sandwich.</p>
                    <hr>
                    <h6 class="fw-bold mb-3">Select a payment method</h6>
                    <select class="form-select mb-4">
                        <option selected>Cash</option>
                        <option>GCash</option>
                        <option>PayMaya</option>
                    </select>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-success flex-grow-1 me-2" id="payNowBtn">Pay Now</button>
                        <button class="btn btn-danger flex-grow-1 ms-2" id="cancelOrderBtn">Cancel Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('cancelOrderBtn').addEventListener('click', function() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Cancelled!',
                'Your order has been cancelled.',
                'success'
            )
        }
    })
});

    document.getElementById('payNowBtn').addEventListener('click', function() {
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
            Swal.fire(
                'Payment Successful!',
                `<br>Queue No.: ${queueNumber}`,
                `Payment has been processed successfully. Your queue number is ${queueNumber}.`,
                'success'
            )
        }
    })
});
    </script>

</body>
</html>
