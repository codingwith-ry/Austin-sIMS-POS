<?php include 'verticalNav.php'; ?>
<div class="main-content">
    <main role="main" class="content">
        <h1>Orders</h1>
        <ul class="nav nav-tabs" id="orderTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="queue-tab" data-toggle="tab" href="#queue" role="tab" aria-controls="queue" aria-selected="true">Queue</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
            </li>
        </ul>
        <div class="tab-content" id="orderTabsContent">
            <div class="tab-pane fade show active" id="queue" role="tabpanel" aria-labelledby="queue-tab">
                <div class="table-container mt-3">
                    <table class="table order-table">
                        <thead>
                            <tr>
                                <th>Queue</th>
                                <th>Order Number</th>
                                <th>Receipt Number</th>
                                <th>Product Quantity</th>
                                <th>Time</th>
                                <th>Order Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-toggle="collapse" data-target="#orderDetails1" class="accordion-toggle">
                                <td>1</td>
                                <td>003467</td>
                                <td>02022391929102</td>
                                <td>6</td>
                                <td>09:49:53 AM</td>
                                <td>IN PROCESS</td>
                                <td>
                                    <button class="btn btn-success btn-sm done"><i class="flaticon-check"></i> Done</button>
                                    <button class="btn btn-danger btn-sm cancel"><i class="flaticon-times"></i> Cancel</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="hiddenRow">
                                    <div class="collapse" id="orderDetails1">
                                        <div class="order-details">
                                            <div>
                                                <h5>Order Details</h5>
                                                <p><strong>Dine In:</strong></p>
                                                <ul>
                                                    <li><del>Four Cheese Pizza</del></li>
                                                    <li>Iced Spanish Latte</li>
                                                    <li>Overload Fries</li>
                                                    <li>Lasagna</li>
                                                </ul>
                                            </div>
                                            <div>
                                                <h5>Notes/Remarks:</h5>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                            </div>
                                            <div>
                                                <h5>&nbsp;</h5>
                                                <p>25 minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                <!-- To be continued pa -->
            </div>
        </div>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
