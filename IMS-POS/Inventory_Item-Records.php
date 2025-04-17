<?php
session_start();
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'inventory staff management') {
    header("Location: /Austin-sIMS-POS/Login/index.php");
    exit();
}
$active = "Inventory_Item-Records";
include("../Login/database.php");
include("IMS_process.php");

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
        IFNULL(SUM(ic.Change_Quantity), 0) AS Total_Change,  -- Sum of all changes for the item
        IFNULL(SUM(CASE WHEN ic.Change_Type = 'decrease' THEN ic.Change_Quantity ELSE 0 END), 0) AS Total_Decrease  -- Sum only decreases
    FROM tbl_item i
    JOIN tbl_itemcategories ic_cat ON i.Item_Category = ic_cat.Category_ID
    JOIN tbl_record r ON i.Item_ID = r.Item_ID
    LEFT JOIN tbl_unitofmeasurments um ON i.Unit_ID = um.Unit_ID
    LEFT JOIN tbl_inventory_changes ic ON r.Record_ID = ic.Record_ID
    GROUP BY i.Item_ID, r.Record_ItemVolume
    ORDER BY i.Item_Name ASC
";

$itemData = $pdo->query($fetchItemDataQuery)->fetchAll(PDO::FETCH_ASSOC);

$outOfStockItems = [];
$lowStockItems = [];

foreach ($itemData as $item) {
    // Calculate the current quantity by subtracting Total_Decrease from Record_ItemQuantity
    $currentStock = $item['Record_ItemQuantity'] - $item['Total_Decrease'];

    // Add Total_Quantity to the item for easy access
    $item['Total_Quantity'] = $currentStock;

    // Classify the items based on the stock
    if ($currentStock <= 0) {
        $outOfStockItems[] = $item;
    } elseif ($currentStock > 0 && $currentStock < 4) {
        $lowStockItems[] = $item;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS-POS | Inventory</title>
    <?php require_once('links.php'); ?>
    <?php require('IMS_process.php') ?>
</head>

<body>
    <?php include 'verticalNav.php' ?>
    <main id="mainContent" style="padding-left: 12px; padding-right: 12px ;">
        <?php if (!empty($outOfStockItems) || !empty($lowStockItems)): ?>
            <div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: rgb(50, 50, 50); color: white;">
                            <h5 class="modal-title" id="lowStockModalLabel">Stock Alerts</h5>
                        </div>
                        <div class="modal-body">
                            <?php if (!empty($outOfStockItems)): ?>
                                <h5 class="text-danger">Out of Stock</h5>
                                <ul class="list-group mb-3">
                                    <?php foreach ($outOfStockItems as $item): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong><?php echo htmlspecialchars($item['Item_Name']); ?></strong>
                                            <span>
                                                <?php echo htmlspecialchars($item['Total_Quantity']); ?> pcs
                                                (<?php echo htmlspecialchars($item['Record_ItemVolume']) . ' ' . htmlspecialchars($item['Unit_Acronym']); ?>)
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (!empty($lowStockItems)): ?>
                                <h5 class="text-warning">Low Stock</h5>
                                <ul class="list-group">
                                    <?php foreach ($lowStockItems as $item): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong><?php echo htmlspecialchars($item['Item_Name']); ?></strong>
                                            <span>
                                                <?php echo htmlspecialchars($item['Total_Quantity']); ?> pcs
                                                (<?php echo htmlspecialchars($item['Record_ItemVolume']) . ' ' . htmlspecialchars($item['Unit_Acronym']); ?>)
                                            </span>
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
        <?php endif; ?>
        <div class="title">
            <div class="row">
                <div>
                    <h2>Inventory</h2>
                </div>
            </div>
            <div class="row" style="margin-bottom: 20px;">
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="padding-left: 12px;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#Records-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                            Records
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#Items-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">
                            Items
                        </button>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="Records-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <!-- Print Button - placed above the table for better visibility -->
                            <div class="button-container">
                                <!-- Print Document Button -->
                                <button id="customPrintBtn" class="btn btn-primary">
                                    <i class="bi bi-printer-fill me-2"></i> Print Document
                                </button>


                            </div>

                            <!-- Table -->
                            <table id="itemRecords" class="display nowrap table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <!-- Select-All Checkbox -->
                                            <div class="form-check">
                                                <input class="form-check-input" id="select-all" type="checkbox">
                                            </div>
                                        </th>
                                        <th>Record ID</th>
                                        <th>Purchase Date</th>
                                        <th>Employee Assigned</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!----Test niyo kung gagana yung pag export pati print ng data lagay kayo mga data !------>
                                </tbody>
                            </table>

                            <script>
                                window.inventoryData = <?php echo json_encode($inventoryRecords); ?>;
                            </script>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="Items-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <strong style="font-size: 25px">Categories</strong>
                    <div class="row">
                        <div class="col">
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping">
                                                <i class="bi-search"></i></span> <input type="text" class="form-control" placeholder="Search product here" aria-describedby="addon-wrapping">
                                        </div>
                                    </div>
                                    <div class="col-auto" style="display: flex; gap: 10px">
                                        <button class="btn btn-secondary" type="button">
                                            <i class="fi fi-rr-settings-sliders"></i>
                                        </button>
                                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                            <li class="nav-item ms-auto" role="presentation">
                                                <button class="btn btn-success h-100 pt-2" id="partytrayMenu" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Item
                                                    <i
                                                        class="fi fi-rr-add " style="vertical-align: middle; font-size: 18px"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <br />

                            <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5 fw-bold" id="addItemLabel">Add Item</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="IMS_process.php" enctype="multipart/form-data">
                                                <!-- Category -->
                                                <div class="mb-3">
                                                    <label for="itemCategory" class="form-label fw-bold" style="font-size: 18px;">Category</label>
                                                    <select class="form-select" id="itemCategory" name="item_category">
                                                        <option disabled selected>Select Category</option>
                                                        <?php
                                                        foreach ($item_categories as $category) {
                                                            echo '<option value="' . $category['Category_ID'] . '">' . $category['Category_Name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Item Unit of Measurement -->
                                                <div class="mb-3">
                                                    <label for="itemCategory" class="form-label fw-bold" style="font-size: 18px;">Unit Of Measurement</label>
                                                    <select class="form-select" id="itemCategory" name="item_unit">
                                                        <option disabled selected>Select Measurement</option>
                                                        <?php
                                                        foreach ($unitOfMeasurementList as $unitOfMeasurement) {
                                                            echo '<option value="' . $unitOfMeasurement['Unit_ID'] . '">' . $unitOfMeasurement['Unit_Name'] . " (" . $unitOfMeasurement['Unit_Acronym'] . ")" . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- Item Name -->
                                                <div class="mb-3">
                                                    <label for="itemNameInput" class="form-label fw-bold" style="font-size: 18px;">Item Name</label>
                                                    <input type="text" class="form-control" id="itemNameInput" placeholder="ex. Nachos" name="item_name">
                                                </div>

                                                <hr />
                                                <!-- Item Picture -->
                                                <div class="row">
                                                    <div id="imagePreview" style="width: 100px; height: 100px;">

                                                    </div>
                                                    <div class="col-12">
                                                        <input type="file" id="itemImageUpload" name="image" onchange="previewImage(event)">
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" name="addItem">Add Item</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <br />
                            <div class="d-flex flex-row flex-nowrap overflow-x-scroll custom-scrollbar">
                                <!-- Static 'All' Button -->
                                <button class="btn btn-primary flex-shrink-0 align-baseline me-3 category-btn"
                                    id="allCategory"
                                    data-category="all"
                                    style="width: 12rem; text-align: left; padding-left:15px;">
                                    <span><i class="fi fi-ss-apps" id="categoryIcon"></i></span><br><br>
                                    <p class="card-text fw-bold">All</p>
                                </button>

                                <?php
                                // Fetch categories from the database
                                $categoryQuery = "SELECT * FROM tbl_itemcategories";
                                foreach ($pdo->query($categoryQuery) as $category) {
                                    $categoryIcon = htmlspecialchars($category['Category_Icon']);
                                    $categoryName = htmlspecialchars($category['Category_Name']);
                                    $categoryID = htmlspecialchars($category['Category_ID']);

                                    // Removed logic for auto-assigning 'active' class
                                    echo '
                                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3 category-btn" 
                                                data-category="' . $categoryID . '" 
                                                style="width: 12rem; text-align: left; padding-left:15px;">
                                            <span><i class="' . $categoryIcon . '" id="categoryIcon"></i></span><br><br>
                                            <p class="card-text">' . $categoryName . '</p>
                                        </button>';
                                }
                                ?>
                            </div>

                            <br />
                            <div class="tab-content" id="pills-tabContent">
                                <div class="products-wrapper">
                                    <div class="row" id="product-list">
                                        <strong style="font-size: 25px">Inventory Items</strong>
                                        <!-- Loop through each item to display in the desired format -->
                                        <?php
                                        $groupedItems = [];

                                        foreach ($itemData as $row) {
                                            $itemKey = $row['Item_Name'] . '_' . $row['Record_ItemVolume'];

                                            if (!isset($groupedItems[$itemKey])) {
                                                // First time adding this item+volume combo
                                                $groupedItems[$itemKey] = $row;
                                            } else {
                                                // Sum the quantities
                                                $groupedItems[$itemKey]['Record_ItemQuantity'] += $row['Record_ItemQuantity'];
                                            }

                                            // Adjust quantity based on the total decrease
                                            $groupedItems[$itemKey]['Record_ItemQuantity'] -= $row['Total_Decrease'];
                                        }

                                        // Loop through and display each item
                                        foreach ($groupedItems as $row) {
                                            $itemID = htmlspecialchars($row['Item_ID']);
                                            $itemName = htmlspecialchars($row['Item_Name']);
                                            $itemQty = htmlspecialchars($row['Record_ItemQuantity']);
                                            $volume = htmlspecialchars($row['Record_ItemVolume']);
                                            $uniqueKey = $itemID . '_' . $volume;
                                            $modalID = "decreaseModal_" . $uniqueKey;

                                            $cardBorderClass = ($itemQty == 0) ? 'border border-danger' : (($itemQty <= 3) ? 'border border-warning' : '');
                                            $tooltipAttr = ($itemQty <= 3) ? 'data-bs-toggle="tooltip" data-bs-title="Quantity of this item is low"' : '';
                                            $imageStyle = ($itemQty == 0) ? 'filter: grayscale(100%) brightness(60%);' : 'object-fit: contain;';
                                            $outOfStockOverlay = ($itemQty == 0) ? '
                                                <div class="position-absolute top-50 start-50 translate-middle bg-danger text-white px-2 py-1 rounded shadow" style="z-index: 10; font-size: 14px;">
                                                    Out of Stock
                                                </div>' : '';

                                            echo '
                                                <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0 product-item" data-category="' . htmlspecialchars($row['Item_Category']) . '">
                                                    <div class="card flex-shrink-0 ' . $cardBorderClass . '" style="height: 430px; padding: 15px; position: relative;" ' . $tooltipAttr . '>
                                                        <a href="IMS_DisplayItemsTable.php?item_id=' . $itemID . '" class="text-decoration-none text-dark" style="display: block; position: relative;">
                                                            ' . $outOfStockOverlay . '
                                                            <img src="' . htmlspecialchars($row['Item_Image']) . '" class="card-img-top rounded mb-2" alt="' . $itemName . '" style="height: 150px; width: 100%; ' . $imageStyle . '">
                                                        </a>
                                                        <div class="card-body d-flex flex-column justify-content-between" style="height: calc(100% - 150px);">
                                                            <div>
                                                                <span style="font-weight: bold; opacity: 0.5; font-size:10px;">' . htmlspecialchars($row['Category_Name']) . '</span><br />
                                                                <span style="font-size: 20px; font-weight:bold;">' . $itemName . '</span><br />
                                                                <span style="font-weight: bold; font-size:15px;">' . $itemQty . ' pcs</span><br />
                                                                <span style="opacity: 0.5; font-size:15px;">' . $volume . ' (' . htmlspecialchars($row['Unit_Acronym']) . ')</span>
                                                            </div>
                                                            <button class="btn btn-warning mt-3 w-100" data-bs-toggle="modal" data-bs-target="#' . $modalID . '">
                                                                Decrease Quantity
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>';

                                            // Modal (keep using unique modal ID using volume)
                                            echo '
                                                <div class="modal fade" id="' . $modalID . '" tabindex="-1" aria-labelledby="' . $modalID . 'Label" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form method="post" action="decreaseItemQuantity.php">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-warning">
                                                                    <h5 class="modal-title" id="' . $modalID . 'Label">Decrease Quantity</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p><strong>' . $itemName . '</strong></p>
                                                                    <p>Available Quantity: <strong>' . $itemQty . ' pcs</strong></p>
                                                                    <div class="mb-3">
                                                                        <label for="slider_' . $itemID . '" class="form-label">Select amount to decrease:</label>
                                                                        <input type="range" class="form-range" min="1" max="' . $itemQty . '" value="1" id="slider_' . $uniqueKey . '" name="decrease_amount" oninput="updatePreview_' . $uniqueKey . '()">
                                                                        <div>Decreasing by: <strong id="decreasePreview_' . $uniqueKey . '">1</strong> pcs</div>
                                                                        <div>Quantity after decrease: <strong id="afterQty_' . $uniqueKey . '">' . ($itemQty - 1) . '</strong> pcs</div>
                                                                    </div>
                                                                    <input type="hidden" name="item_id" value="' . $itemID . '">
                                                                    <input type="hidden" name="volume" value="' . $volume . '">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" name="confirm_decrease" class="btn btn-danger">Confirm Decrease</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>';

                                            // Slider JS
                                            echo '
                                                <script>
                                                function updatePreview_' . $uniqueKey . '() {
                                                    const slider = document.getElementById("slider_' . $uniqueKey . '");
                                                    const preview = document.getElementById("decreasePreview_' . $uniqueKey . '");
                                                    const afterQty = document.getElementById("afterQty_' . $uniqueKey . '");
                                                    const decreaseVal = parseInt(slider.value);
                                                    preview.textContent = decreaseVal;
                                                    afterQty.textContent = ' . $itemQty . ' - decreaseVal;
                                                }
                                                </script>';
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </main>
    <!-- Modal HTML (Move this to your PHP document) -->
    <div class="modal" id="addRecordForm">
        <div class="modal-dialog modal-dialog-centered modal-l modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Record</h5>
                </div>
                <div class="modal-body">
                    <form id="myForm" action="IMS_process.php" method="post">

                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Category</span>
                            <div class="col-sm-8">
                                <select class="form-select" name="item_category" id="categoryDropdown">
                                    <option selected disabled>Select Category</option>
                                    <?php
                                    foreach ($item_categories as $category) {
                                        echo '<option value="' . htmlspecialchars($category['Category_ID']) . '">' . htmlspecialchars($category['Category_Name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Item Name</span>
                            <div class="col-sm-8">
                                <select class="form-select" name="item_Name" id="itemDropdown">
                                    <option selected disabled>Select Name</option>
                                    <?php
                                    foreach ($items as $item) {
                                        echo '<option value="' . htmlspecialchars($item['Item_Name']) . '">' . htmlspecialchars($item['Item_Name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Item Price</span>
                            <div class="col-sm-8">
                                <input class="form-control" id="focusedInput" type="text" placeholder="0.00" name="item_price">
                            </div>
                        </div>
                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Item Volume</span>
                            <div class="col-sm-8">
                                <input class="form-control" id="focusedInput" type="text" placeholder="0.00" name="item_volume">
                            </div>
                        </div>
                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Item Quantity</span>
                            <div class="col-sm-8">
                                <input class="form-control" id="focusedInput" type="number" placeholder="Number of Items" name="item_quantity">
                            </div>
                        </div>
                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Purchase Date</span>
                            <div class="flatpickr col-sm-8">
                                <input class="form-control" id="focusedInput" type="date" placeholder="Select Date" data-input class="dateInputField" name="purchase_date">
                            </div>
                        </div>
                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Expiration Date</span>
                            <div class="flatpickr col-sm-8">
                                <input class="form-control" id="focusedInput" type="date" placeholder="Select Date" data-input class="dateInputField" name="expiration_date">
                            </div>
                        </div>
                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Supplier</span>
                            <div class="col-sm-8">
                                <input class="form-control" id="focusedInput" type="text" placeholder="Supplier Name" name="item_supplier">
                            </div>
                        </div>
                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Employee Assigned</span>
                            <div class="col-sm-8">
                                <select class="form-select" name="employee_assigned">
                                    <option selected disabled>Select Employee</option>
                                    <?php
                                    foreach ($employee as $emp) {
                                        echo '<option value="' . htmlspecialchars($emp['Employee_ID']) . '">' . htmlspecialchars($emp['Employee_Name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" name="add_record">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRecordModalLabel">Edit Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRecordForm">
                        <input type="hidden" id="recordId" name="recordId">
                        <div class="mb-3">
                            <label for="itemName" class="form-label">Item Name</label>
                            <select class="form-select" id="itemName" name="itemName">
                                <option value="" disabled selected>Select Item</option>
                                <!-- Options will be dynamically populated -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemVolume" class="form-label">Item Volume</label>
                            <input type="text" class="form-control" id="itemVolume" name="itemVolume">
                        </div>
                        <div class="mb-3">
                            <label for="itemQuantity" class="form-label">Item Quantity</label>
                            <input type="number" class="form-control" id="itemQuantity" name="itemQuantity">
                        </div>
                        <div class="mb-3">
                            <label for="itemPrice" class="form-label">Item Price</label>
                            <input type="number" class="form-control" id="itemPrice" name="itemPrice">
                        </div>
                        <div class="mb-3">
                            <label for="itemExpirationDate" class="form-label">Expiration Date</label>
                            <input type="date" class="form-control" id="itemExpirationDate" name="itemExpirationDate">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEditRecord">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("saveEditRecord").addEventListener("click", function() {
            const formData = $("#editRecordForm").serialize();

            $.ajax({
                url: "../IMS-POS/scripts/updateRecord.php",
                type: "POST",
                data: formData,
                success: function(response) {
                    const res = JSON.parse(response);
                    if (res.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Updated!",
                            text: "The record has been updated successfully.",
                        }).then(() => {
                            // Refresh the page after the success message
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: res.message || "Failed to update the record.",
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Failed to update the record.",
                    });
                },
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
    <script>
        // Automatically show the modal if there are low-stock or out-of-stock items
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($outOfStockItems) || !empty($lowStockItems)): ?>
                var lowStockModal = new bootstrap.Modal(document.getElementById('lowStockModal'));
                lowStockModal.show();
            <?php endif; ?>
        });
    </script>
</body>
<?php include 'footer.php' ?>

</html>