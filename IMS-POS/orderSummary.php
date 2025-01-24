<?php include 'verticalNav.php'?>

<div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="bg-white p-4 rounded shadow-sm" style="width: 800px;">
            <div class="mb-3">
                <h4 class="fw-bold">Order Summary</h4>
            </div>

            <div class="d-flex justify-content-between align-items-start mb-4">
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
                <div class="col-6">
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
                <div class="col-6">
                    <h6 class="fw-bold mb-3">Select a payment method</h6>
                    <select class="form-select mb-4">
                        <option selected>Cash</option>
                        <option>GCash</option>
                        <option>PayMaya</option>
                    </select>
                    <div class="text-center mb-4">
                        <h1 class="fw-bold">₱504.86</h1>
                    </div>
                    <div class="d-grid text-center">
                        <!-- Number Pad -->
                        <div class="row g-2">
                            <div class="col-4"><button class="btn btn-light border">1</button></div>
                            <div class="col-4"><button class="btn btn-light border">2</button></div>
                            <div class="col-4"><button class="btn btn-light border">3</button></div>
                            <div class="col-4"><button class="btn btn-light border">4</button></div>
                            <div class="col-4"><button class="btn btn-light border">5</button></div>
                            <div class="col-4"><button class="btn btn-light border">6</button></div>
                            <div class="col-4"><button class="btn btn-light border">7</button></div>
                            <div class="col-4"><button class="btn btn-light border">8</button></div>
                            <div class="col-4"><button class="btn btn-light border">9</button></div>
                            <div class="col-4"><button class="btn btn-light border">.</button></div>
                            <div class="col-4"><button class="btn btn-light border">0</button></div>
                            <div class="col-4"><button class="btn btn-danger border">&larr;</button></div>
                        </div>
                    </div>
                    <button class="btn btn-secondary mt-4 w-100">Pay Now</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
