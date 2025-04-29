<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sales</title>
    <?php include 'adminCDN.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            position: relative;
            height: 300px;
        }

        .chart-container-small {
            position: relative;
            height: 200px;
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <header>
        <?php include 'adminNavBar.php'; ?>
    </header>

    <main id="adminContent">
        <div class="row mb-2">
            <div class="col-md-6 d-flex align-items-center">
                <h1 class="mb-0">Sales Data</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-3">
                <!-- Notification Dropdown -->
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary position-relative dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 300px; overflow-y: auto;">
                        <li><strong class="dropdown-header">Notifications</strong></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">🛒 New order received</a></li>
                        <li><a class="dropdown-item" href="#">📦 Inventory stock low</a></li>
                        <li><a class="dropdown-item" href="#">👤 New employee registered</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center text-primary" href="#">View all</a></li>
                    </ul>
                </div>

                <!-- Administrator Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" id="accountDropdownBtn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administrator
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <hr>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div><strong>Total Sales</strong>
                            <h1>₱10,418.00</h1>
                        </div>
                        <div><span style="opacity: 0.5;">+4.56%</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div><strong>Total Revenue</strong>
                            <h1>₱7,059.00</h1>
                        </div>
                        <div><span style="opacity: 0.5;">-2.6%</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div><strong>Total Orders</strong>
                            <h1>9,102</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div><strong>Total Products Sold</strong>
                            <h1>9,576</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Sales Analytics</h5>
                        <h5 id="totalValues"></h5>
                        <div class="chart-container flex-grow-1">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100"> <!-- Added h-100 here -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Daily Sales</h5>
                        <h2 class="fw-bold">2,584</h2>
                        <div class="chart-container flex-grow-1">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="productSalesTable" class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Sales</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Leave tbody EMPTY. We'll fill it with JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
</body>
<?php
include "cdnScripts.php";
?>

</html>