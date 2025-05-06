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
    <div class="main-content" id="mainContent">
        <main role="main" class="content">
            <div class="row">
                <div class="col">
                    <h2>Stock List Overview</h2>
                    <hr>
                </div>
            </div>

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

                    <!-- Add Budget Modal -->
                    <div class="modal fade" id="addBudgetModal" tabindex="-1" aria-labelledby="addBudgetModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addBudgetModalLabel">Add Budget</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="budgetForm">
                                        <div class="mb-3">
                                            <label for="budgetAmount" class="form-label">Amount to Add</label>
                                            <input type="number" class="form-control" id="budgetAmount" required min="1" />
                                            <div class="invalid-feedback">Please enter a valid positive number.</div>
                                        </div>
                                        <div id="budgetSummary" style="display: none;">
                                            <p>Current Budget: <strong>₱<span id="currentBudget"></span></strong></p>
                                            <p>Amount to Add: <strong>₱<span id="amountToAdd"></span></strong></p>
                                            <p>New Total Budget: <strong>₱<span id="newBudget"></span></strong></p>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" form="budgetForm" class="btn btn-primary">Confirm</button>
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
    </div>

    <?php include 'footer.php' ?>


    <script>
        $(document).ready(function() {
            let currentBudget = parseFloat(document.querySelector(".card-title + .card-subtitle").textContent.replace(/[₱,]/g, ''));
            const budgetModal = new bootstrap.Modal(document.getElementById('addBudgetModal'));

            $("#addBudgetButton").on("click", function() {
                $("#budgetAmount").val('');
                $("#budgetAmount").removeClass("is-invalid");
                $("#budgetSummary").hide();
                budgetModal.show();
            });

            $("#budgetAmount").on("input", function() {
                const amount = parseFloat($(this).val());
                if (!isNaN(amount) && amount > 0) {
                    $("#budgetAmount").removeClass("is-invalid");
                    const newTotal = currentBudget + amount;
                    $("#currentBudget").text(currentBudget.toLocaleString());
                    $("#amountToAdd").text(amount.toLocaleString());
                    $("#newBudget").text(newTotal.toLocaleString());
                    $("#budgetSummary").show();
                } else {
                    $("#budgetSummary").hide();
                }
            });

            $("#budgetForm").on("submit", function(e) {
                e.preventDefault();
                const amountStr = $("#budgetAmount").val();
                const budgetToAdd = parseFloat(amountStr);

                if (!amountStr || isNaN(budgetToAdd) || budgetToAdd <= 0) {
                    $("#budgetAmount").addClass("is-invalid");
                    return;
                } else {
                    $("#budgetAmount").removeClass("is-invalid");
                }

                fetch("updateBudget.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: `budget=${encodeURIComponent(budgetToAdd)}`
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            const remainingBudgetElement = document.querySelector(".card-title + .card-subtitle");
                            remainingBudgetElement.textContent = `₱${data.new_budget.toLocaleString()}`;
                            currentBudget = data.new_budget;
                            budgetModal.hide();

                            Swal.fire({
                                icon: 'success',
                                title: 'Budget Updated!',
                                text: `The new total budget is ₱${data.new_budget.toLocaleString()}`,
                                confirmButtonColor: '#3085d6'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: data.message,
                            });
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while updating the budget.',
                        });
                    });
            });
        });
    </script>

</body>

</html>
