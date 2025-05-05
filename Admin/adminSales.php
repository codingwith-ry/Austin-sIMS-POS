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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
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
<div class="dropdown">
  <button class="btn position-relative p-0 border-0 bg-transparent" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
      <i class="fas fa-bell fa-lg text-white"></i>
    </div>
    <span id="notificationDot"
      class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-white rounded-circle"
      style="width: 12px; height: 12px;">
    </span>
  </button>

  <ul class="dropdown-menu dropdown-menu-end shadow p-3" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 360px; overflow-y: auto;">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <strong>Notifications</strong>
    </div>
    <hr>

    <!-- Notification Items -->
    <li class="mb-2">
      <span class="d-flex">
        <span class="text-primary me-2">●</span>
        <span><strong>Low inventory alert</strong> – a total of 3 items are in low stock.</span>
      </span>
      <small class="text-muted ms-4">Now</small>
    </li>
    <li class="mb-2">
      <span class="d-flex">
        <span class="text-primary me-2">●</span>
        <span><strong>New employee</strong> has been successfully registered.</span>
      </span>
      <small class="text-muted ms-4">1h ago</small>
    </li>
    <li class="mb-2">
      <span class="d-flex">
        <span class="text-primary me-2">●</span>
        <span><strong>Negative inventory alert</strong> has been triggered.</span>
      </span>
      <small class="text-muted ms-4">4h ago</small>
    </li>

    <!-- Footer -->
    <li><hr></li>
    <li class="text-center">
      <a href="#" class="text-decoration-none text-primary" id="markAllAsRead">Mark all as read</a>
    </li>
  </ul>
</div>

                
            </div>
        </div>
        <hr>
        <div class="col-12 d-flex justify-content-between">
            <div>
                <ul class="nav nav-underline" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#dailyTab" type="button" role="tab">Daily</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#weeklyTab" type="button" role="tab">Weekly</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#monthlyTab" type="button" role="tab">Monthly</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#yearlyTab" type="button" role="tab">Yearly</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <input type="date" class="form-control" id="dateInput">
            </div>
        </div>
        <br />
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="dailyTab" role="tabpanel" tabindex="0">
                <div class="row g-3">
                    <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div><strong>Total Sales(Gross Revenue)</strong>
                                        <h1>₱10,418.00</h1>
                                    </div>
                                    <div><span style="opacity: 0.5;">+4.56%</span></div>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div><strong>Discounts</strong>
                                        <h1>₱7,059.00</h1>
                                    </div>
                                    <div><span style="opacity: 0.5;">-2.6%</span></div>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div><strong>Net Sales</strong>
                                        <h1>₱7,059.00</h1>
                                    </div>
                                    <div><span style="opacity: 0.5;">-2.6%</span></div>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div><strong>Refunds/Cancellations</strong>
                                        <h1>9,102</h1>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div><strong>Actual Cash Amount</strong>
                                        <h1>9,102</h1>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div><strong>Total Transactions(Orders)</strong>
                                        <h1>9,102</h1>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div><strong>Average Transaction Value</strong>
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
                        <div class="col-8 h-40">
                            <div class="card h-100"> <!-- Added h-100 here -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">Daily Sales</h5>
                                    <h2 class="fw-bold">2,584</h2>
                                    <div class="chart-container">
                                        <canvas id="cashLineChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 h-25 border rounded p-0 pb-2">
                            <div class="col-md-3 w-100">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div><strong>Total Payments</strong>
                                            <h1>9,102</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <canvas id="transactionTypes" class="p-4" width="400" height="300"></canvas>
                        </div>
                </div>

                <hr>

                
                <div class="row">
                    <div class="col-12 border rounded p-4">
                        <h3 class="fw-bold">Product Sales</h3>
                        <hr>
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
                        <hr />
                        <div class="row border rounded">
                            
                            <div class="col-8 border rounded p-4">
                                <h3 class="fw-bold">Menu Sales</h3>
                                <hr>
                                <br />
                                <canvas id="menuSalesChart" class="h-75"></canvas>
                            </div>
                            <div class="col-4 border rounded p-4">
                                <h3 class="fw-bold">Category Sales</h3>
                                <hr>
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Coffee Menu</button>
                                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Gastro Pub Menu</button>
                                    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Party Tray Menu</button>
                                </div>

                                <hr />
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                        <canvas id="coffeeMenuChart"></canvas>
                                    </div>
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                        <canvas id="gastroPubChart"></canvas>
                                    </div>
                                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                                        <canvas id="partyTrayChart" width="400" height="400"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                
            </div>
            <div class="tab-pane fade" id="weeklyTab" role="tabpanel" tabindex="0">
                <p>Weekly tab content goes here.</p>
            </div>
            <div class="tab-pane fade" id="monthlyTab" role="tabpanel" tabindex="0">
                <p>Monthly tab content goes here.</p>
            </div>
            <div class="tab-pane fade" id="yearlyTab" role="tabpanel" tabindex="0">
                <p>Yearly tab content goes here.</p>
            </div>
        </div>
        <br />
        

    </main>
</body>
<script src="scripts/adminSales.js"></script>
<?php
include "cdnScripts.php";
?>

</html>