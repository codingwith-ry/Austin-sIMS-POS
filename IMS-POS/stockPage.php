<?php
session_start();
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'inventory staff management') {
    header("Location: /Austin-sIMS-POS/index.php");
    exit();
}

$active = "stockPage";

include '../Login/database.php';
include 'IMS_process.php';


// Query to count distinct items based on Item_ID and Record_ItemVolume
$query = "
    SELECT COUNT(*) AS total_items
    FROM (
        SELECT DISTINCT Item_ID, Record_ItemVolume
        FROM tbl_record
    ) AS distinct_items
";
$stmt = $conn->prepare($query);
$stmt->execute();
$totalItems = $stmt->fetch(PDO::FETCH_ASSOC)['total_items'];

// Query to fetch Total_Expenses from tbl_stocks for Stock_ID = 1
$expenseQuery = "SELECT Total_Expenses FROM tbl_stocks WHERE Stock_ID = 1";
$expenseStmt = $conn->prepare($expenseQuery);
$expenseStmt->execute();
$totalExpenses = $expenseStmt->fetch(PDO::FETCH_ASSOC)['Total_Expenses'] ?? 0;

// Query to fetch Total_Stock_Budget for Stock_ID = 1
$budgetQuery = "SELECT Total_Stock_Budget FROM tbl_stocks WHERE Stock_ID = 1";
$budgetStmt = $conn->prepare($budgetQuery);
$budgetStmt->execute();
$budgetResult = $budgetStmt->fetch(PDO::FETCH_ASSOC);
$totalStockBudget = $budgetResult ? $budgetResult['Total_Stock_Budget'] : 0;

// Calculate the adjusted stock budget
$adjustedStockBudget = $totalStockBudget - $totalExpenses;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS-POS | Stocks</title>
    <?php require_once('links.php'); ?>
    <link href="styles/header-alignment.css" rel="stylesheet" />
</head>
<style>
    .child-row {
        display: flex;
        justify-content: space-between;
        padding: 10px;
    }

    .child-row .image {
        flex: 1;
    }

    .child-row .employee {
        flex: 2;
    }

    .child-row img {
        max-width: 100%;
        height: auto;
    }
</style>

<body>
    <?php include 'verticalNav.php' ?>
    <div class="mainContent" id="mainContent">
        <main role="main" class="content">
            <div class="row">
                <div class="col-12">
                    <h2>Stock List Overview</h2>
                    <hr>
                </div>
            </div>

            <div class="date-selector">
                <!-- Hidden radio buttons (used by backend and JavaScript) -->
                <div class="d-none">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" value="weekly" autocomplete="off" checked>
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" value="monthly" autocomplete="off">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" value="yearly" autocomplete="off">
                </div>

                <!-- Bootstrap Tab-like UI -->
                <ul class="nav nav-underline" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" type="button" role="tab" onclick="
                document.getElementById('btnradio1').checked = true;
                document.getElementById('btnradio1').dispatchEvent(new Event('change'));
            ">Weekly</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" type="button" role="tab" onclick="
                document.getElementById('btnradio2').checked = true;
                document.getElementById('btnradio2').dispatchEvent(new Event('change'));
            ">Monthly</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" type="button" role="tab" onclick="
                document.getElementById('btnradio3').checked = true;
                document.getElementById('btnradio3').dispatchEvent(new Event('change'));
            ">Yearly</button>
                    </li>
                    <li class="nav-item ms-auto" role="presentation">
                        <input type="date" class="form-control" id="startDate" name="startDate">
                    </li>
                </ul>
            </div>
            <br />

            <div class="data-summary" style="margin-bottom: 20px;">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div style="display: flex; gap:10px">
                                    <div style="margin-top: 10px;">
                                        <i class="fa-solid fa-chart-line fa-3x"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-title" style="font-size: 20px;">Remaining Stock Budget</h6>
                                        <h5 class="card-subtitle" style="font-size: x-large; font-weight:bold">
                                            ₱<?= htmlspecialchars(number_format($adjustedStockBudget, 2)) ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div style="display: flex; gap:20px">
                                    <div style="margin-top: 10px;">
                                        <i class="fa-solid fa-peso-sign fa-3x"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-title" style="font-size: 20px;">Total Expenses</h6>
                                        <h5 class="card-subtitle" style="font-size: x-large; font-weight:bold">
                                            ₱<?= htmlspecialchars(number_format($totalExpenses, 2)) ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col" style="margin-bottom: 20px;">
                        <div class="card">
                            <div class="card-body">
                                <div style="display: flex; gap:20px">
                                    <div style="margin-top: 10px;">
                                        <i class="fa-solid fa-box fa-3x"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-title" style="font-size: 20px;">Total Items In Stock</h6>
                                        <h6 class="card-subtitle" style="font-size: x-large; font-weight:bold">
                                            <?= htmlspecialchars($totalItems) ?>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="charts" style="margin-bottom: 20px;">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Stock Analytics</h5>

                                        <div id="stock-bar-Container">
                                            <canvas id="stockAnalyticsChart"></canvas>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="doughnutContainer">
                                            <div class="chartDetails">
                                                <ul>
                                                    <li>
                                                        <div style="display: flex; flex-direction: column;">
                                                            <span class="stockLabel" style="font-size: 15px; font-weight:bold">Remaining Stock Budget</span>
                                                            <span id="remainingBudget" style="font-size: 40px; font-weight: bold">0%</span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div style="display: flex; flex-direction: column;">
                                                            <span class="stockLabel" style="font-size: 15px; font-weight: bold">Total Expenses</span>
                                                            <span id="totalExpenses" style="font-size: 40px; font-weight: bold">0%</span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div style="display: flex; flex-direction: column;">
                                                            <span class="stockLabel" style="font-size: 15px; font-weight: bold">Total Items Added to Stock</span>
                                                            <span id="itemsInStock" style="font-size: 40px; font-weight: bold">0%</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div id="doughnut-chart-Container">
                                                <canvas id="stockdoughnutChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3" style="margin-bottom: 20px;">
                        <!-- Stock Trend Chart -->
                        <div class="flex-grow-1" style="min-width: 300px;">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">📈 Stock Trend Over Time</h5>
                                    <canvas id="stockTrendChart" height="500"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <div class="flex-grow-1" style="margin-bottom: 20px; display: flex; align-items: stretch; gap:10px;">
                            <!-- Category Chart -->
                            <!-- Card with Polar Area Chart -->
                            <div class="card" style="flex: 1; min-width: 250px; max-height: 500px;">
                                <div class="card-body">
                                    <h5 class="card-title">Category Breakdown</h5>
                                </div>
                                <div class="card-body" style="position: relative; width: 100%;">
                                    <!-- Chart container with fixed height for responsiveness -->

                                    <canvas id="categoryChart"></canvas>
                                </div>
                            </div>



                            <!-- Top 5 Expensive Items -->
                            <div id="topSellingProductsContainer" style="width: 400px; min-width: 280px; display: flex; flex-direction: column; justify-content: stretch;">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <div class="card-title">
                                            <div style="font-size: 20px;">
                                                <strong>Top 5 Most Expensive Items</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2" style="font-size: 14px; opacity: 0.6;">
                                                <span>Item Bought</span>
                                                <span>Total Price (₱)</span>
                                            </div>
                                        </div>

                                        <!-- Scrollable list -->
                                        <ul id="topSellingList" class="list-group list-group-flush flex-grow-1 overflow-auto mt-2">
                                            <!-- List items will be populated dynamically -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="stockTable">
                        <div class="card" style="padding: 5px;">
                            <h5 id="stockTableLabel" class="mb-2 text-primary">Loading data...</h5>
                            <table id="stockTable" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Volume</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </main>

    </div>




    <?php include 'footer.php' ?>







</body>

</html>