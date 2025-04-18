<?php
session_start();
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'administrator') {
    header("Location: /Austin-sIMS-POS/Login/index.php");
    exit();
}

include '/xampp/htdocs/Austin-sIMS-POS/Login/database.php';

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
                <h1 class="mb-0">Dashboard</h1>
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
                        <li>
                            <button class="dropdown-item d-flex justify-content-between align-items-center" data-bs-toggle="modal" data-bs-target="#newOrderModal">
                                🛒 New order received
                                <?php if (!empty($orders)): ?>
                                    <span class="badge bg-danger"><?php echo count($orders); ?></span>
                                <?php endif; ?>
                            </button>
                        </li>

                        <li>
                            <button class="dropdown-item d-flex justify-content-between align-items-center" data-bs-toggle="modal" data-bs-target="#inventoryStockModal"
                                data-bs-toggle="tooltip" title="Expired: <?php echo $expiredCount; ?>, Out of Stock: <?php echo $outOfStockCount; ?>, Low Stock: <?php echo $lowStockCount; ?>">
                                📦 Inventory stock
                                <span class="badge bg-danger">
                                    <?php echo ($expiredCount + $outOfStockCount + $lowStockCount); ?>
                                </span>
                            </button>
                        </li>

                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#newEmployeeModal">👤 New employee registered</button></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center text-primary" href="#">View all</a></li>
                    </ul>

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

        <div class="row" style="padding-left: 10px; padding-right: 10px;">
            <!-- Regular Employees Card -->
            <div class="col-12 col-md-3 d-flex align-items-stretch mb-4">
                <div class="card h-100 shadow-sm bg-light" style="border-left: 5px solid #17a2b8;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Regular<br />Employees</strong>
                            <h1 class="text-info" id="regularEmployeesCount">0</h1>
                        </div>
                            <div class="chart-container d-flex justify-content-center">
                                <canvas id="Regular_Chart"></canvas>
                            </div>
                    </div>
                </div>
            </div>


            <!-- POS Employees Card -->
            <div class="col-12 col-md-3 d-flex align-items-stretch mb-4">
                <div class="card h-100 shadow-sm bg-light" style="border-left: 5px solid #28a745;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>POS<br />Employees</strong>
                            <h1 class="text-success" id="posEmployeesCount">0</h1>
                        </div>
                            <div class="chart-container d-flex justify-content-center">
                                <canvas id="No_POS_Employees_Chart"></canvas>
                            </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Employees Card -->
            <div class="col-12 col-md-3 d-flex align-items-stretch mb-4">
                <div class="card h-100 shadow-sm bg-light" style="border-left: 5px solid #ffc107;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Inventory<br />Employees</strong>
                            <h1 class="text-warning" id="imsEmployeesCount">0</h1>
                        </div>
                            <div class="chart-container d-flex justify-content-center">
                                <canvas id="No_IMS_Employees_Chart"></canvas>
                            </div>
                    </div>
                </div>
            </div>

            <!-- Administrator Card -->
            <div class="col-12 col-md-3 d-flex align-items-stretch mb-4">
                <div class="card h-100 shadow-sm bg-light" style="border-left: 5px solid #dc3545;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Administrator</strong>
                            <h1 class="text-danger" id="adminEmployeesCount">0</h1>
                        </div>
                            <div class="chart-container d-flex justify-content-center">
                                <canvas id="Admin_Chart"></canvas>
                            </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row align-items-stretch">
            <div id="dataChartsContainer" class="col-7">
                <div class="card" style="max-height: 100%;">
                    <div class="card-body d-flex flex-column">
                        <div class="card-title d-flex align-items-center justify-content-between">
                            <div>
                                <strong style="font-size: 20px;">Daily Sales</strong>
                            </div>
                            <div>
                                <button class="btn btn-secondary">View Report</button>
                            </div>
                        </div>
                        <div id="sales-line-container">
                            <canvas id="salesDataChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div id="topSellingProductsContainer" class="col-5">
                <div class="card h-100">
                    <div class="card-body overflow-y-auto">
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

                        <ul id="topSellingList" class="list-group list-group-item-flush">
                            <!-- List items will be inserted here -->
                        </ul>

                    </div>
                </div>
            </div>

            <div class="row gx-0 justify-content-center align-item-center">
                <div></div>
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
                            <div id="statisticsChartContainer" style="margin-top: 5%;">
                                <canvas id="statisticsBarChart"></canvas>
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

            </div>
        </div>
    </main>
</body>

</html>


<?php
include "cdnScripts.php";
?>