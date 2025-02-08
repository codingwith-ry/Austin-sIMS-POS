<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include 'adminCDN.php'; ?>
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
        <div class="row">
        <div id="searchButtonContainer" class="d-flex justify-content-end">
            <form role="search">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <span class="material-symbols-outlined">
                            search
                        </span>
                    </span>
                    <input type="text" class="form-control" placeholder="Search" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </form>
        </div>
        </div>
        <div class="row">
            <div class="d-flex justify-content-between">
                <div style="padding-left: 10px;">
                    <h1>Dashboard</h1>
                </div>
                <div class="ml-auto">
                    <div class="date-selector mb-3" style="width: 100%;">
                        <ul class="nav nav-underline" style="width: 100%;">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page">Weekly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link">Monthly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link">Yearly</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-stretch">
            <div id="dataChartsContainer" class="col col-7">
            <div class="card h-100" style="width: 100%;">
                <div class="card-body d-flex flex-column">
                <div class="card-title d-flex align-items-center justify-content-between">
                    <div>
                        <strong style="font-size: 20px;">Daily Sales</strong>
                    </div>
                    <div>
                        <button class="btn btn-secondary">View Report</button>
                    </div>
                </div>
                   <div id="sales-line-container" class="flex-grow-1 d-flex align-items-center justify-content-center">
                        <canvas id="salesDataChart" style="width: 100%; height: 90%"></canvas>
                   </div>
                </div>
            </div>
            </div>

            <div id="dataChartsContainer" class="col-3">
            <div class="card h-100" style="width: 100%;">
                <div class="card-body">
                <div class="card-title">
                    <div>
                        <strong style="font-size: 20px;">Total Orders</strong>
                    </div>
                </div>   
                <div id="totalOrdersContainer" class="flex-grow-1 d-flex align-items-center justify-content-center">
                    <canvas id="totalOrderChart"></canvas>
                </div>
                </div>
            </div>
            </div>

            <div id="dataChartsContainer" class="col-2 d-flex flex-column h-150 gap-3">
            <div class="row flex-grow-1">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div style="font-size: 20px;">    
                                        <strong>Total Products<br />Sold</strong>
                                    </div>
                                <div>
                                    <span style="opacity: 0.5;">-2.3%</span>
                                </div>
                                </div>
                                <div style="font-size: 30px;">
                                    <span class="mdi mdi-invoice-list-outline"></span>
                                </div>
                            </div>
                        </div>   
                        <div style="font-size: 30px;">
                            <strong>1,274</strong>
                        </div>
                        <div class="progress" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 2px">
                            <div class="progress-bar" style="width: 25%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row flex-grow-1">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div style="font-size: 20px;">    
                                        <strong>Total Products<br />Sold</strong>
                                    </div>
                                <div>
                                    <span style="opacity: 0.5;">-2.3%</span>
                                </div>
                                </div>
                                <div style="font-size: 30px;">
                                    <span class="mdi mdi-invoice-list-outline"></span>
                                </div>
                            </div>
                        </div>   
                        <div style="font-size: 30px;">
                            <strong>1,274</strong>
                        </div>
                        <div class="progress" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 2px">
                            <div class="progress-bar" style="width: 25%"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="row gx-0 align-items-stretch">
            <div id="dataChartsContainer" class="col-7">
                <div class="card h-100">
                <div class="card-body">
                    <div class="card-title">
                        <div>
                            <strong style="font-size: 20px;">Statistics</strong>
                        </div>
                        <div>
                            <span>Total Sales and purchases</span>
                        </div>
                    </div>   
                        <div id="statisticsChartContainer">
                            <canvas id="statisticsBarChart" style="width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div id="topSellingProductsContainer" class="col-5">
            <div class="card h-100">
                    <div class="card-body overflow-y-auto" style="max-height: 500px;">
                        <div class="card-title">
                            <div>
                                <div>
                                    <div style="font-size: 20px;">    
                                        <strong>Total Selling Products</strong>
                                    </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span style="opacity: 0.5; flex-grow:1;">Dishes</span>
                                    </div>
                                    <div>
                                        <span style="opacity: 0.5;">Orders</span>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <ul class="list-group list-group-item-flush">
                            <li class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-pizza-slice" style="font-size: 30px;"></i>
                                <div class="ms-2 me-auto">
                                    <div><span class="badge text-bg-primary rounded-pill">Food</span></div>
                                    <span style="font-size: 20px; font-weight:bold">Four Cheese Pizza</span>
                                </div>
                                </div>
                                <span style="font-size: 30px; font-weight:bold">524</span>
                            </li>
                            <hr />
                            <li class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-whiskey-glass" style="font-size: 30px;"></i>
                                <div class="ms-2 me-auto">
                                    <div><span class="badge text-bg-warning rounded-pill">Drink</span></div>
                                    <span style="font-size: 20px; font-weight:bold">Iced Biscoff Latte</span>
                                </div>
                                </div>
                                <span style="font-size: 30px; font-weight:bold">720</span>
                            </li>
                            <hr />
                            <li class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-bread-slice" style="font-size: 30px;"></i>
                                <div class="ms-2 me-auto">
                                    <div><span class="badge text-bg-primary rounded-pill">Food</span></div>
                                    <span style="font-size: 20px; font-weight:bold">Cheese Bread</span>
                                </div>
                                </div>
                                <span style="font-size: 30px; font-weight:bold">120</span>
                            </li>
                            <hr />
                            <li class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-whiskey-glass" style="font-size: 30px;"></i>
                                <div class="ms-2 me-auto">
                                    <div><span class="badge text-bg-warning rounded-pill">Drink</span></div>
                                    <span style="font-size: 20px; font-weight:bold">Matcha Latte</span>
                                </div>
                                </div>
                                <span style="font-size: 30px; font-weight:bold">720</span>
                            </li>
                            <hr />
                            <li class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-ice-cream" style="font-size: 30px;"></i>
                                <div class="ms-2 me-auto">
                                    <div><span class="badge text-bg-primary rounded-pill">Food</span></div>
                                    <span style="font-size: 20px; font-weight:bold">Chocolate Ice Cream</span>
                                </div>
                                </div>
                                <span style="font-size: 30px; font-weight:bold">100</span>
                            </li>
                            <hr />
                            <li class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-whiskey-glass" style="font-size: 30px;"></i>
                                <div class="ms-2 me-auto">
                                    <div><span class="badge text-bg-warning rounded-pill">Drink</span></div>
                                    <span style="font-size: 20px; font-weight:bold">Iced Biscoff Latte</span>
                                </div>
                                </div>
                                <span style="font-size: 30px; font-weight:bold">720</span>
                            </li>
                            <hr />
                            <li class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-whiskey-glass" style="font-size: 30px;"></i>
                                <div class="ms-2 me-auto">
                                    <div><span class="badge text-bg-warning rounded-pill">Drink</span></div>
                                    <span style="font-size: 20px; font-weight:bold">Iced Biscoff Latte</span>
                                </div>
                                </div>
                                <span style="font-size: 30px; font-weight:bold">720</span>
                            </li>
                        </ul>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>


<?php include "cdnScripts.php" ?>