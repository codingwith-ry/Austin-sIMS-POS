<?php 
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
<body>
<?php include 'verticalNav.php' ?>
<main id="mainContent" style="padding-left: 12px; padding-right: 12px ;">
        <div class="row">
            <div class="col-md-10"><h1>Stock List Overview</h1></div>
            <div class="col-md-2">
                <div class="flatpickr">
                    <div class="flatpickr">
                        <input type="text" placeholder="Date" data-input class="dateInputField" style="font-weight: 600; font-size: 15px"> 
                    </div>
                </div>
            </div>
        </div>

    <div class="date-selector" style="margin-bottom: 20px;">
        <ul class="nav nav-underline">
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
                                        <div style="display: flex; flex-direction:column;">
                                            <span class="stockLabel" style="font-size: 15px; font-weight:bold">Remaining Stock Budget</span>
                                            <span style="font-size: 40px; font-weight:bold">20%</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div style="display: flex; flex-direction:column">
                                            <span class="stockLabel" style="font-size: 15px; font-weight:bold">Total Expenses</span>
                                            <span style="font-size: 40px; font-weight:bold">70%</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div style="display: flex; flex-direction:column">
                                            <span class="stockLabel" style="font-size: 15px; font-weight:bold">Total Items In Stock</span>
                                            <span style="font-size: 40px; font-weight:bold">100%</span>
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


</main>

<?php include 'footer.php' ?>



<script>
    /*************Date Picker Set Up Start****************/
    document.addEventListener("DOMContentLoaded", function () {
        flatpickr(".flatpickr input", {
            enableTime: false,
            dateFormat: "Y-M-D",
        });

        // Add Budget Button Functionality
        const addBudgetButton = document.getElementById("addBudgetButton");
        addBudgetButton.addEventListener("click", function () {
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
    /*************Date Picker Set Up End****************/

    const stockChart = document.getElementById('stockAnalyticsChart')
    const stockDoughnutChart = document.getElementById('stockdoughnutChart')

    /*************Bar Chart Set Up Start****************/
    new Chart(stockChart, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Daily Expenses',
                data: [1000, 3000, 5000, 7000, 10000, 500, 600],
                borderWidth: 1
            }]
        }
    });
    /*************Bar Chart Set Up End****************/

    /*************Doughnut Chart Set Up Start****************/
    const doughnutChartData = {
        labels: [
            'Remaining Stock Budget',
            'Total Expenses',
            'Total Items in Stock'
        ],
        data: [20, 70, 100],
    };

    new Chart(stockDoughnutChart, {
        type: 'doughnut',
        data: {
            labels: doughnutChartData.labels,
            datasets: [{
                data: doughnutChartData.data
            }]
        },
        options: {
            borderRadius: 2,
            hoverBorderWidth: 0,
            plugins: {
                legend: {
                    display: false
                }
            },
            spacing: 5,
            weight: 1,
            cutout: '80%'
        }
    });
    /*************Doughnut Chart Set Up End****************/

    /*************Stock Table Set Up Start****************/
    function format(data) {
        return `
            <table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
                <tr><td><strong>Description:</strong></td><td>${data.description}</td></tr>
                <tr><td><strong>Quantity:</strong></td><td>${data.quantity}</td></tr>
                <tr><td><strong>Volume:</strong></td><td>${data.volume} ${data.unit}</td></tr>
                <tr><td><strong>Image:</strong></td><td><img class="item-img" src="${data.image}" alt="Item Image" style="height: 150px; width: 100%; object-fit: contain;"></td></tr>
            </table>
        `;
    }

    $(document).ready(function () {
        let table = new DataTable('#stockTable', {
            ajax: 'fetchRecentStockPurchases.php',   // Fetch data from PHP backend
            responsive: true,
            columns: [
                { 
                    data: null,
                    className: "details-control",
                    defaultContent: "Details",
                    orderable: false
                },
                { data: "Item_Name" },
                { data: "Category_Name" },
                { data: "Record_ItemQuantity" },
                { 
                    data: null,
                    render: function(data, type, row) {
                        return `${row.Record_ItemVolume} ${row.Unit_Acronym}`;
                    }
                }
            ]
        });

        // Toggle child rows
        $('#stockTable tbody').on('click', 'td.details-control', function () {
            let tr = $(this).closest('tr');
            let row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Use full row data for dynamic child generation
                let data = row.data();
                let childData = {
                    description: "Category: " + data.Category_Name,
                    image: data.Item_Image,
                    unit: data.Unit_Acronym,
                    volume: data.Record_ItemVolume,
                    quantity: data.Record_ItemQuantity
                };
                row.child(format(childData)).show();
                tr.addClass('shown');
            }
        });
    });
    /*************Stock Table Set Up End****************/
</script>

</body>
</html>