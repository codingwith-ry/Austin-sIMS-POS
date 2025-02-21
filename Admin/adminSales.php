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
            border-radius: 12px; /* Adjust as needed */
            overflow: hidden; /* Ensures corners are applied */
        }
        
        
    </style>
</head>
<body>
    <header>
        <?php include 'adminNavBar.php'; ?>
    </header>

    <main id="adminContent">
        <div class="row mb-2">
            <div id="accountDropdown" class="d-flex justify-content-end">
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
        <div class="row align-items-center mb-3">
            <div class="col-md-6 d-flex align-items-center">
                <h1 class="mb-0">Sales Data</h1>
            </div>
            <div class="col-md-6">
                <div id="searchButtonContainer" class="d-flex justify-content-end">
                    <form role="search">
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">
                                <span class="material-symbols-outlined">search</span>
                            </span>
                            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <hr>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div><strong>Total Sales</strong><h1>₱10,418.00</h1></div>
                        <div><span style="opacity: 0.5;">+4.56%</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div><strong>Total Revenue</strong><h1>₱7,059.00</h1></div>
                        <div><span style="opacity: 0.5;">-2.6%</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div><strong>Total Orders</strong><h1>9,102</h1></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div><strong>Total Products Sold</strong><h1>9,576</h1></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Sales Analytics</h5>
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daily Sales</h5>
                        <h2 class="fw-bold">2,584</h2>
                        <div class="chart-container">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
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
                            <tr>
                                <td><img src="resources/chickenalfredo.jpg" alt="Chicken Alfredo Pasta" width="50">Chicken Alfredo Pasta</td>
                                <td>₱150.00</td>
                                <td>124</td>
                                <td>₱18,600.00</td>
                                <td>₱18,600.00</td>
                            </tr>
                            <tr>
                                <td><img src="images/iced_biscoff_latte.jpg" alt="Iced Biscoff Latte" width="50">Iced Biscoff Latte</td>
                                <td>₱115.00</td>
                                <td>76</td>
                                <td>₱8,740.00</td>
                                <td>₱8,740.00</td>
                            </tr>
                            <tr>
                                <td><img src="images/matcha_latte.jpg" alt="Matcha Latte" width="50">Matcha Latte</td>
                                <td>₱110.00</td>
                                <td>54</td>
                                <td>₱5,940.00</td>
                                <td>₱5,940.00</td>
                            </tr>
                            <tr>
                                <td><img src="images/four_cheese_pizza.jpg" alt="Four Cheese Pizza" width="50">Four Cheese Pizza</td>
                                <td>₱220.00</td>
                                <td>39</td>
                                <td>₱8,580.00</td>
                                <td>₱8,580.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script>

        var ctx1 = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                datasets: [
                    {
                        label: 'Income',
                        data: [80, 90, 65, 95, 55, 40, 85, 70],
                        backgroundColor: 'rgba(200, 200, 200, 0.5)', 
                        borderWidth: 0
                    },
                    {
                        label: 'Profit',
                        data: [60, 60, 50, 80, 50, 30, 70, 55],
                        backgroundColor: 'black',
                        borderWidth: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        var ctx2 = document.getElementById('dailySalesChart').getContext('2d');
        var dailySalesChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Sales',
                        data: [20, 35, 25, 40, 50, 45, 55],
                        borderColor: 'black',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'Revenue',
                        data: [15, 30, 20, 35, 45, 40, 50],
                        borderColor: 'blue',
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>
