<?php
session_start();
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'inventory staff management') {
    header("Location: /Austin-sIMS-POS/Login/index.php");
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

// Query to calculate total expenses
$expenseQuery = "SELECT SUM(Record_ItemPrice) AS total_expenses FROM tbl_record";
$expenseStmt = $conn->prepare($expenseQuery);
$expenseStmt->execute();
$totalExpenses = $expenseStmt->fetch(PDO::FETCH_ASSOC)['total_expenses'];

// Query to fetch Total_Stock_Budget for Stock_ID = 1
$budgetQuery = "SELECT Total_Stock_Budget FROM tbl_stocks WHERE Stock_ID = 1";
$budgetStmt = $conn->prepare($budgetQuery);
$budgetStmt->execute();
$totalStockBudget = $budgetStmt->fetch(PDO::FETCH_ASSOC)['Total_Stock_Budget'];

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
    <main id="mainContent" style="padding-left: 12px; padding-right: 12px ;">
        <div class="row">
            <div class="col-md-10">
                <h1>Stock List Overview</h1>
            </div>
            <div class="col-md-2">
                <div>
                    <label for="startDate">Select Date</label>
                    <input type="date" id="startDate" name="startDate">
                </div>
            </div>
        </div>

        <div class="date-selector" style="margin-bottom: 20px;">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" value="weekly" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="btnradio1">Weekly</label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" value="monthly" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">Monthly</label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio3" value="yearly" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio3">Yearly</label>
            </div>


        </div>


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
                                <div style="display: flex; justify-content: flex-end; margin-bottom: 10px; margin-left: 50px;">
                                    <button class="btn btn-primary" id="addBudgetButton" style="font-size: 16px; font-weight: bold;">Add Budget</button>
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

                <div class="stockTable">
                    <div class="card" style="padding: 5px;">
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

    <?php include 'footer.php' ?>



    <script>
        /*************Date Picker Set Up Start****************/
        $(document).ready(function() {
            // Add Budget Button Functionality
            const addBudgetButton = document.getElementById("addBudgetButton");
            addBudgetButton.addEventListener("click", function() {
                // Prompt the user for a budget amount
                const budgetToAdd = prompt("Enter the amount to add to the Remaining Stock Budget:");

                // Validate the input
                if (budgetToAdd && !isNaN(budgetToAdd) && Number(budgetToAdd) > 0) {
                    // Send the budget amount to the server via AJAX
                    fetch("updateBudget.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: `budget=${encodeURIComponent(budgetToAdd)}`,
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                alert("Budget updated successfully!");
                                // Optionally, update the Remaining Stock Budget on the page
                                const remainingBudgetElement = document.querySelector(
                                    ".card-title + .card-subtitle"
                                );
                                remainingBudgetElement.textContent = `₱${data.new_budget.toLocaleString()}`;
                            } else {
                                alert("Failed to update budget: " + data.message);
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            alert("An error occurred while updating the budget.");
                        });
                } else {
                    alert("Please enter a valid positive number.");
                }
            });
        });
    </script>


</body>

</html>