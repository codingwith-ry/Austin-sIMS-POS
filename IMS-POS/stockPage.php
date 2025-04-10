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
                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="btnradio1">Weekly</label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">Monthly</label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
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
                                                        <span class="stockLabel" style="font-size: 15px; font-weight: bold">Total Items In Stock</span>
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


        $(document).ready(function() {
            const currentDate = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
            const startDateInput = document.getElementById('startDate');
            startDateInput.value = currentDate;

            let barChart, doughnutChart; // Store chart instances
            let chartPeriod = 'weekly'; // Default chart period (weekly)

            // Function to fetch data and update charts based on the selected period
            function fetchDataAndUpdateCharts() {
                const startDate = startDateInput.value;
                const period = chartPeriod; // Use the selected period (weekly/monthly/yearly)

                // Log the selected period and start date (optional)
                console.log("Selected Period:", period);
                console.log("Selected Date:", startDate);

                // Fetch bar chart data based on the selected period and date
                fetch(`fetchDailyExpense.php?startDate=${startDate}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let labels = [];
                            let expensesData = [];

                            // Process data based on the selected period
                            if (period === 'weekly') {
                                const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                expensesData = Array(7).fill(0);

                                data.data.forEach(record => {
                                    const purchaseDate = new Date(record.purchase_date);
                                    const dayIndex = purchaseDate.getDay();
                                    expensesData[dayIndex] += parseFloat(record.total_expenses);
                                });

                                labels = daysOfWeek;
                            }

                            if (period === 'monthly') {
                                const monthsOfYear = [
                                    'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
                                ];
                                expensesData = Array(12).fill(0);

                                data.data.forEach(record => {
                                    const purchaseDate = new Date(record.purchase_date);
                                    const monthIndex = purchaseDate.getMonth(); // Get the month index (0-11)
                                    expensesData[monthIndex] += parseFloat(record.total_expenses);
                                });

                                labels = monthsOfYear;
                            }

                            if (period === 'yearly') {
                                // Handle the 'yearly' case similarly, adjust if needed
                                // For simplicity, assuming you're aggregating yearly expenses
                                const years = []; // Store unique years
                                expensesData = [];

                                data.data.forEach(record => {
                                    const purchaseDate = new Date(record.purchase_date);
                                    const year = purchaseDate.getFullYear();
                                    if (!years.includes(year)) {
                                        years.push(year);
                                        expensesData.push(0); // Initialize for new year
                                    }
                                    const yearIndex = years.indexOf(year);
                                    expensesData[yearIndex] += parseFloat(record.total_expenses);
                                });

                                labels = years;
                            }

                            // Destroy the old bar chart before creating a new one
                            if (barChart) {
                                barChart.destroy();
                            }

                            const ctx = document.getElementById('stockAnalyticsChart').getContext('2d');
                            barChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Expenses',
                                        data: expensesData,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        } else {
                            console.error('Error fetching expenses:', data.message);
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));

                // Fetch doughnut chart data based on the selected date
                fetch(`stockBudgetGraph.php?startDate=${startDate}`)
                    .then(response => response.json())
                    .then(data => {
                        const doughnutChartData = {
                            labels: [
                                'Remaining Stock Budget',
                                'Total Expenses',
                                'Total Items in Stock'
                            ],
                            data: [
                                data.remaining_budget,
                                data.expenses,
                                data.items_in_stock
                            ],
                        };

                        // Destroy the old doughnut chart before creating a new one
                        if (doughnutChart) {
                            doughnutChart.destroy();
                        }

                        const ctx = document.getElementById('stockdoughnutChart').getContext('2d');
                        doughnutChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: doughnutChartData.labels,
                                datasets: [{
                                    data: doughnutChartData.data,
                                    backgroundColor: ['#4CAF50', '#FF5722', '#2196F3'],
                                    borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                                    borderWidth: 2
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

                        document.getElementById('remainingBudget').textContent = `${data.remaining_budget.toFixed(2)}%`;
                        document.getElementById('totalExpenses').textContent = `${data.expenses.toFixed(2)}%`;
                        document.getElementById('itemsInStock').textContent = `${data.items_in_stock.toFixed(2)}%`;
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Initial fetch on page load
            fetchDataAndUpdateCharts();

            // Listen for changes in the start date input field
            startDateInput.addEventListener('change', function() {
                // Re-fetch and update charts based on the new selected date
                fetchDataAndUpdateCharts();
            });

            // Listen for radio button changes and update chart period accordingly
            $('input[name="btnradio"]').on('change', function() {
                if ($('#btnradio1').is(':checked')) {
                    chartPeriod = 'weekly';
                } else if ($('#btnradio2').is(':checked')) {
                    chartPeriod = 'monthly';
                } else if ($('#btnradio3').is(':checked')) {
                    chartPeriod = 'yearly';
                }

                // Re-fetch and update charts based on the selected period
                fetchDataAndUpdateCharts();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const startDateInput = document.getElementById('startDate');
            const radioButtons = document.querySelectorAll('input[name="btnradio"]');
            let barChart, doughnutChart; // Store chart instances

            function fetchDataAndUpdateCharts(timeframe) {
                const startDate = startDateInput.value;

                // Log the selected timeframe (optional)
                console.log("Selected Timeframe:", timeframe);

                // Fetch bar chart data based on selected timeframe
                fetch(`fetchWeeklyExpenses.php?startDate=${startDate}&timeframe=${timeframe}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const labels = data.labels; // Labels for the chart (e.g., days, months, years)
                            const expenses = data.expenses; // Expenses data

                            // Destroy the old bar chart before creating a new one
                            if (barChart) {
                                barChart.destroy();
                            }

                            const ctx = document.getElementById('stockAnalyticsChart').getContext('2d');
                            barChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Expenses',
                                        data: expenses,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });

                            // Update total expenses
                            document.querySelector('#totalExpenses').textContent = `${data.totalExpenses.toFixed(2)}%`;
                        } else {
                            console.error('Error fetching expenses:', data.message);
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Initial fetch on page load (default to Weekly)
            fetchDataAndUpdateCharts('Weekly');

            // Add event listeners to radio buttons
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        fetchDataAndUpdateCharts(this.nextElementSibling.textContent.trim());
                    }
                });
            });
        });
    </script>
    <script>
        /*************Date Picker Set Up Start****************/
        $(document).ready(function() {
            const currentDate = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
            const startDateInput = document.getElementById('startDate');
            startDateInput.value = currentDate;

            let table; // Declare table variable

            function fetchDataAndUpdateTable() {
                const startDate = startDateInput.value;

                // Destroy the old table if it exists
                if (table) {
                    table.clear().destroy();
                }

                // Initialize a new DataTable with updated data based on the selected date
                table = new DataTable('#stockTable', {
                    ajax: {
                        url: 'fetchRecentStockPurchases.php',
                        data: {
                            startDate: startDate // Pass the selected date as a parameter
                        },
                        dataSrc: function(json) {
                            return json.data; // Assuming the response contains an object with a 'data' property
                        }
                    },
                    responsive: true,
                    columns: [{
                            data: null,
                            className: "details-control",
                            defaultContent: "Details",
                            orderable: false
                        },
                        {
                            data: "Item_Name"
                        },
                        {
                            data: "Category_Name"
                        },
                        {
                            data: "Record_ItemQuantity"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `${row.Record_ItemVolume} ${row.Unit_Acronym}`;
                            }
                        }
                    ]
                });

                // Toggle child rows
                $('#stockTable tbody').on('click', 'td.details-control', function() {
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
            }

            // Initial fetch on page load
            fetchDataAndUpdateTable();

            // Listen for changes in the start date input field
            startDateInput.addEventListener('change', function() {
                // Re-fetch and update the table based on the new selected date
                fetchDataAndUpdateTable();
            });
        });
        /*************Stock Table Set Up End****************/

        /*************Stock Table Format Function Start****************/
        function format(data) {
            return `
    <table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
        <tr>
            <td><strong>Description:</strong></td>
            <td>${data.description}</td>
        </tr>
        <tr>
            <td><strong>Quantity:</strong></td>
            <td>${data.quantity}</td>
        </tr>
        <tr>
            <td><strong>Volume:</strong></td>
            <td>${data.volume} ${data.unit}</td>
        </tr>
        <tr>
            <td><strong>Image:</strong></td>
            <td><img class="item-img" src="${data.image}" alt="Item Image" style="height: 150px; width: 100%; object-fit: contain;"></td>
        </tr>
    </table>
    `;
        }
        /*************Stock Table Format Function End****************/
    </script>


</body>

</html>