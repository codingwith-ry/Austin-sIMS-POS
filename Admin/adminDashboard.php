<?php
session_start();
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'administrator') {
    header("Location: /Austin-sIMS-POS/Login/index.php");
    exit();
}

include '../Login/database.php';

$fetchItemDataQuery = "
    SELECT 
        i.Item_ID,
        i.Item_Name, 
        i.Item_Image, 
        i.Item_Category, 
        ic_cat.Category_Name, 
        um.Unit_Acronym, 
        r.Record_ItemQuantity, 
        r.Record_ItemVolume,
        r.Record_ItemExpirationDate,
        IFNULL(SUM(ic.Change_Quantity), 0) AS Total_Change,
        IFNULL(SUM(CASE WHEN ic.Change_Type = 'decrease' THEN ic.Change_Quantity ELSE 0 END), 0) AS Total_Decrease
    FROM tbl_item i
    JOIN tbl_itemcategories ic_cat ON i.Item_Category = ic_cat.Category_ID
    JOIN tbl_record r ON i.Item_ID = r.Item_ID
    LEFT JOIN tbl_unitofmeasurments um ON i.Unit_ID = um.Unit_ID
    LEFT JOIN tbl_inventory_changes ic ON r.Record_ID = ic.Record_ID
    GROUP BY 
        i.Item_ID,
        i.Item_Name,
        i.Item_Image,
        i.Item_Category,
        ic_cat.Category_Name,
        um.Unit_Acronym,
        r.Record_ItemQuantity,
        r.Record_ItemVolume,
        r.Record_ItemExpirationDate
    ORDER BY i.Item_Name ASC;
";

$itemData = $conn->query($fetchItemDataQuery)->fetchAll(PDO::FETCH_ASSOC);

$outOfStockItems = [];
$lowStockItems = [];
$expiredItems = [];

foreach ($itemData as $item) {
    $currentStock = $item['Record_ItemQuantity'] - $item['Total_Decrease'];
    $item['Total_Quantity'] = $currentStock;

    // Check if the item is expired
    if (!empty($item['Record_ItemExpirationDate']) && strtotime($item['Record_ItemExpirationDate']) < time()) {
        $expiredItems[] = $item;
    } elseif ($currentStock <= 0) {
        $outOfStockItems[] = $item;
    } elseif ($currentStock > 0 && $currentStock < 4) {
        $lowStockItems[] = $item;
    }
}

// Calculate the counts
$expiredCount = count($expiredItems);
$outOfStockCount = count($outOfStockItems);
$lowStockCount = count($lowStockItems);

// Get the current date
$currentDate = date('Y-m-d'); // Get current date in 'YYYY-MM-DD' format

// Fetch order details for today's orders
$orderQuery = "
    SELECT 
        o.orderID,
        o.orderNumber,
        o.orderDate,
        o.orderTime,
        o.orderClass,
        o.orderStatus,
        o.customerName,
        o.totalAmount,
        o.amountPaid,
        o.paymentMode,
        o.additionalNotes
    FROM tbl_orders o
    WHERE o.orderDate = :currentDate
";

$stmt = $conn->prepare($orderQuery);
$stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR); // Ensure correct binding of the parameter
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
            <div class="col-md-6 d-flex align-items-center">
                <h1 class="mb-3">Dashboard</h1> <!-- Added margin below the title -->
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-3">
                <!-- Notification Bell with Dot Badge -->
                <div class="dropdown">
                    <button class="btn position-relative p-0 border-0 bg-transparent" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fas fa-bell fa-lg text-white"></i>
                        </div>
                        <span id="notificationDot" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-white rounded-circle" style="width: 12px; height: 12px;"></span>
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

                    <!-- New Order Modal -->
                    <div class="modal fade" id="newOrderModal" tabindex="-1" aria-labelledby="newOrderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="newOrderModalLabel">New Orders Received</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php if (!empty($orders)): ?>
                                        <div class="accordion" id="orderAccordion">
                                            <?php foreach ($orders as $index => $order): ?>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="true" aria-controls="collapse<?php echo $index; ?>">
                                                            Order #<?php echo htmlspecialchars($order['orderNumber']); ?>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#orderAccordion">
                                                        <div class="accordion-body">
                                                            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['orderDate']); ?></p>
                                                            <p><strong>Order Time:</strong> <?php echo htmlspecialchars($order['orderTime']); ?></p>
                                                            <p><strong>Order Class:</strong> <?php echo htmlspecialchars($order['orderClass']); ?></p>
                                                            <p><strong>Order Status:</strong> <?php echo htmlspecialchars($order['orderStatus']); ?></p>
                                                            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customerName']); ?></p>
                                                            <p><strong>Total Amount:</strong> <?php echo htmlspecialchars($order['totalAmount']); ?></p>
                                                            <p><strong>Amount Paid:</strong> <?php echo htmlspecialchars($order['amountPaid']); ?></p>
                                                            <p><strong>Payment Mode:</strong> <?php echo htmlspecialchars($order['paymentMode']); ?></p>
                                                            <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($order['additionalNotes']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p>No new orders found.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Inventory Stock Modal -->
                    <div class="modal fade" id="inventoryStockModal" tabindex="-1" aria-labelledby="inventoryStockModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: rgb(50, 50, 50); color: white;">
                                    <h5 class="modal-title" id="inventoryStockModalLabel">Inventory Stock</h5>
                                </div>
                                <div class="modal-body">
                                    <!-- Display expired items -->
                                    <?php if (!empty($expiredItems)): ?>
                                        <h5 class="text-danger">Expired Items</h5>
                                        <ul class="list-group mb-3">
                                            <?php foreach ($expiredItems as $item): ?>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <img src="/Austin-sIMS-POS/IMS-POS/<?php echo htmlspecialchars($item['Item_Image']); ?>" alt="Item Image" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                                    <div class="flex-grow-1">
                                                        <strong><?php echo htmlspecialchars($item['Item_Name']); ?></strong><br>
                                                        <span class="text-danger">Expired on <?php echo date('F d, Y', strtotime($item['Record_ItemExpirationDate'])); ?></span>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <!-- Display out of stock items -->
                                    <?php if (!empty($outOfStockItems)): ?>
                                        <h5 class="text-danger">Out of Stock</h5>
                                        <ul class="list-group mb-3">
                                            <?php foreach ($outOfStockItems as $item): ?>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <img src="/Austin-sIMS-POS/IMS-POS/<?php echo htmlspecialchars($item['Item_Image']); ?>" alt="Item Image" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                                    <div class="flex-grow-1">
                                                        <strong><?php echo htmlspecialchars($item['Item_Name']); ?></strong><br>
                                                        <span><?php echo htmlspecialchars($item['Total_Quantity']); ?> pcs</span>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <!-- Display low stock items -->
                                    <?php if (!empty($lowStockItems)): ?>
                                        <h5 class="text-warning">Low Stock</h5>
                                        <ul class="list-group">
                                            <?php foreach ($lowStockItems as $item): ?>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <img src="/Austin-sIMS-POS/IMS-POS/<?php echo htmlspecialchars($item['Item_Image']); ?>" alt="Item Image" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                                    <div class="flex-grow-1">
                                                        <strong><?php echo htmlspecialchars($item['Item_Name']); ?></strong><br>
                                                        <span><?php echo htmlspecialchars($item['Total_Quantity']); ?> pcs left</span><br>
                                                        <span>Volume: <?php echo htmlspecialchars($item['Record_ItemVolume']) . ' ' . htmlspecialchars($item['Unit_Acronym']); ?></span>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" style="background-color: rgb(50, 50, 50); color: white;" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- New Employee Modal -->
                    <div class="modal fade" id="newEmployeeModal" tabindex="-1" aria-labelledby="newEmployeeModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="newEmployeeModalLabel">New Employee Registered</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Details of the new employee will go here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <hr>

        <div class="row align-items-center mb-3">
            <!-- Search Input -->
            <div class="col-md-6">
                <form role="search">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">
                            <span class="material-symbols-outlined">search</span>
                        </span>
                        <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                </form>
            </div>

            <!-- Date Selector Tabs -->
            <div class="col-md-6 d-flex justify-content-end">
                <div class="date-selector">
                    <ul class="nav nav-underline">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Weekly</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Monthly</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Yearly</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row px-3">
    <!-- Employee Role Cards -->
    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-stretch mb-4">
        <div class="card h-100 shadow-sm bg-light w-100" style="border-left: 5px solid #17a2b8;">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <strong>Regular<br />Employees</strong>
                    <h1 class="text-info" id="regularEmployeesCount">0</h1>
                </div>
                <div class="chart-container mt-auto text-center">
                    <canvas id="Regular_Chart" class="employee-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-stretch mb-4">
        <div class="card h-100 shadow-sm bg-light w-100" style="border-left: 5px solid #28a745;">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <strong>POS<br />Employees</strong>
                    <h1 class="text-success" id="posEmployeesCount">0</h1>
                </div>
                <div class="chart-container mt-auto text-center">
                    <canvas id="No_POS_Employees_Chart" class="employee-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-stretch mb-4">
        <div class="card h-100 shadow-sm bg-light w-100" style="border-left: 5px solid #ffc107;">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <strong>Inventory<br />Employees</strong>
                    <h1 class="text-warning" id="imsEmployeesCount">0</h1>
                </div>
                <div class="chart-container mt-auto text-center">
                    <canvas id="No_IMS_Employees_Chart" class="employee-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-stretch mb-4">
        <div class="card h-100 shadow-sm bg-light w-100" style="border-left: 5px solid #dc3545;">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <strong>Administrator</strong>
                    <h1 class="text-danger" id="adminEmployeesCount">0</h1>
                </div>
                <div class="chart-container mt-auto text-center">
                    <canvas id="Admin_Chart" class="employee-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>



        <div class="row gx-3 align-items-stretch">
    <!-- Daily Sales Chart -->
    <div class="col-lg-7 col-md-12 mb-3">
    <div class="card h-100">
        <div class="card-body d-flex flex-column">
            <div class="card-title d-flex align-items-center justify-content-between mb-3">
                <strong style="font-size: 20px;">Daily Sales</strong>
                <button class="btn btn-secondary btn-sm">View Report</button>
            </div>

            <!-- Responsive chart container with height -->
            <div id="sales-line-container" class="flex-grow-1">
                <canvas id="salesDataChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

    <!-- Top Selling Products -->
    <div id="topSellingProductsContainer" class="col-lg-5 col-md-12 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <div class="card-title">
                    <div style="font-size: 20px;">
                        <strong>Total Selling Products</strong>
                    </div>
                    <div class="d-flex justify-content-between mt-2" style="font-size: 14px; opacity: 0.6;">
                        <span>Dishes</span>
                        <span>Orders</span>
                    </div>
                </div>

                <!-- Scrollable list with flexible height -->
                <ul id="topSellingList" class="list-group list-group-flush flex-grow-1 overflow-auto mt-2">
                    <!-- Dynamically populated list items -->
                </ul>
            </div>
        </div>
    </div>
</div>


            <div class="row gx-3 align-items-stretch">
    <!-- Total Inventory Expenses -->
    <div class="col-lg-4 col-md-12 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="card-title">
                    <strong style="font-size: 20px;">Total Inventory Expenses</strong>
                </div>
                <div id="statisticsChartContainer" class="mt-4" style="height: 300px;">
                    <canvas id="inventoryExpensesGraph"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card h-100 w-100">
            <div class="card-body">
                <div class="card-title">
                    <strong style="font-size: 20px;">Total Orders</strong>
                </div>
                <div id="totalOrdersContainer" class="d-flex align-items-center justify-content-center" style="height: 250px;">
                <canvas id="totalOrderChart" style="max-width: 100%; max-height: 100%;"></canvas>
            </div>
                <div id="totalSalesLabel" class="text-center mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Budget & Expenses Summary -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex flex-column gap-3">
        <!-- Remaining Inventory Budget -->
        <div class="card flex-fill">
            <div class="card-body">
                <div class="card-title d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-size: 18px;"><strong>Remaining Inventory Budget</strong></div>
                        <span id="budget-percentage" style="opacity: 0.5;">+12%</span>
                    </div>
                    <span class="mdi mdi-invoice-list-outline" style="font-size: 28px;"></span>
                </div>
                <div style="font-size: 28px;">
                    <strong id="budget-amount">₱8,420</strong>
                </div>
                <div class="progress mt-2" style="height: 2px;">
                    <div id="budget-progress" class="progress-bar bg-success" style="width: 40%;"></div>
                </div>
            </div>
        </div>

        <!-- Total Inventory Expenses Summary -->
        <div class="card flex-fill">
            <div class="card-body">
                <div class="card-title d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-size: 18px;"><strong>Total Inventory Expenses</strong></div>
                        <span id="expenses-percentage" style="opacity: 0.5;">-2.3%</span>
                    </div>
                    <span class="mdi mdi-clipboard-check-outline" style="font-size: 28px;"></span>
                </div>
                <div style="font-size: 28px;">
                    <strong id="expenses-amount">₱1,274</strong>
                </div>
                <div class="progress mt-2" style="height: 2px;">
                    <div id="expenses-progress" class="progress-bar bg-warning" style="width: 25%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>


                </div>

            </div>
        </div>
    </main>
</body>

</html>


<?php
include "cdnScripts.php";
?>