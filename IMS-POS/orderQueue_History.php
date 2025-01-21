<?php include 'POS.css'; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <h4>Austin's Café & Gastro Pub IMS-POS</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Stocks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Inventory</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2 class="top-text">Orders</h2>
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
                        <table class="table table-striped order-table mt-3">
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
                                <tr>
                                    <td>1</td>
                                    <td>1001</td>
                                    <td>R12345</td>
                                    <td>10</td>
                                    <td>11:30 AM</td>
                                    <td>IN PROCESS</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Done</button>
                                        <button class="btn btn-danger btn-sm">Cancel</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="order-details">
                            <h5>Order Details</h5>
                            <p><strong>Dine In:</strong></p>
                            <ul>
                                <li>Sample</li>
                                <li>Sample</li>
                                <li>Sample</li>
                                <li>Sample</li>
                            </ul>
                            <p><strong>Notes/Remarks:</strong>Sample hehe</p>
                            <p>25 minutes</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
</php>
