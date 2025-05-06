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
    r.Record_ItemExpirationDate,
    i.Item_Lowstock, -- Include the Item_Lowstock field
    IFNULL(SUM(ic.Change_Quantity), 0) AS Total_Change,
    IFNULL(SUM(CASE WHEN ic.Change_Type = 'decrease' THEN ic.Change_Quantity ELSE 0 END), 0) AS Total_Decrease,
    IFNULL(SUM(CASE WHEN ic.Change_Type = 'increase' THEN ic.Change_Quantity ELSE 0 END), 0) AS Total_Increase
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
    r.Record_ItemExpirationDate,
    i.Item_Lowstock -- Group by Item_Lowstock as well
ORDER BY i.Item_Name ASC;
";

$itemData = $pdo->query($fetchItemDataQuery)->fetchAll(PDO::FETCH_ASSOC);

// Arrays to hold out-of-stock, low stock, and expired items
$outOfStockItems = [];
$lowStockItems = [];
$expiredItems = [];

// Process each item
foreach ($itemData as $item) {
    // Calculate the current stock
    $currentStock = $item['Record_ItemQuantity'] + $item['Total_Increase'] - $item['Total_Decrease'];
    $item['Total_Quantity'] = $currentStock;

    // Check if the item is expired
    if (!empty($item['Record_ItemExpirationDate']) && strtotime($item['Record_ItemExpirationDate']) < time()) {
        $expiredItems[] = $item;
    }
    // Check if the item is out of stock
    elseif ($currentStock <= 0) {
        $outOfStockItems[] = $item;
    }
    // Check if the item is low in stock based on Item_Lowstock
    elseif ($currentStock > 0 && $currentStock <= $item['Item_Lowstock']) {
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
        <?php if (!empty($outOfStockItems) || !empty($lowStockItems) || !empty($expiredItems)): ?>
            <div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: rgb(50, 50, 50); color: white;">
                            <h5 class="modal-title" id="lowStockModalLabel">Stock Alerts</h5>
                        </div>
                        <div class="modal-body">
                            <?php if (!empty($expiredItems)): ?>
                                <h5 class="text-danger">Expired Items</h5>
                                <ul class="list-group mb-3" id="expiredItemsList">
                                    <?php foreach ($expiredItems as $item): ?>
                                        <li class="list-group-item d-flex align-items-center">
                                            <img src="<?php echo htmlspecialchars($item['Item_Image']); ?>" alt="Item Image" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                            <div class="flex-grow-1">
                                                <strong><?php echo htmlspecialchars($item['Item_Name']); ?></strong><br>
                                                <span class="text-danger">Expired on <?php echo date('F d, Y', strtotime($item['Record_ItemExpirationDate'])); ?></span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (!empty($outOfStockItems)): ?>
                                <h5 class="text-danger">Out of Stock</h5>
                                <ul class="list-group mb-3" id="outOfStockItemsList">
                                    <?php foreach ($outOfStockItems as $item): ?>
                                        <li class="list-group-item d-flex align-items-center">
                                            <img src="<?php echo htmlspecialchars($item['Item_Image']); ?>" alt="Item Image" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                            <div class="flex-grow-1">
                                                <strong><?php echo htmlspecialchars($item['Item_Name']); ?></strong><br>
                                                <span>
                                                    <?php echo htmlspecialchars($item['Total_Quantity']); ?> pcs
                                                    (<?php echo htmlspecialchars($item['Record_ItemVolume']) . ' ' . htmlspecialchars($item['Unit_Acronym']); ?>)
                                                </span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (!empty($lowStockItems)): ?>
                                <h5 class="text-warning">Low Stock</h5>
                                <ul class="list-group" id="lowStockItemsList">
                                    <?php foreach ($lowStockItems as $item): ?>
                                        <li class="list-group-item d-flex align-items-center">
                                            <img src="<?php echo htmlspecialchars($item['Item_Image']); ?>" alt="Item Image" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                            <div class="flex-grow-1">
                                                <strong><?php echo htmlspecialchars($item['Item_Name']); ?></strong><br>
                                                <span>
                                                    <?php echo htmlspecialchars($item['Total_Quantity']); ?> pcs (Low Stock Threshold: <?php echo htmlspecialchars($item['Item_Lowstock']); ?> pcs)
                                                </span>
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
                                                <i class="bi-search"></i>
                                            </span>
                                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name or category">
                                        </div>

                                    </div>
                                    <div class="col-auto" style="display: flex; gap: 10px">
                                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                            <li class="nav-item ms-auto" role="presentation">
                                                <!-- Add Item Button -->
                                                <button class="btn btn-success h-100 pt-2" id="partytrayMenu" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                                    Add Item
                                                    <i class="fi fi-rr-add" style="vertical-align: middle; font-size: 18px"></i>
                                                </button>

                                                <!-- Edit Item Button (Styled to match Add Item) -->
                                                <button class="btn btn-primary h-100 pt-2" data-bs-toggle="modal" data-bs-target="#editItemModal" id="editItem">
                                                    Edit Item
                                                    <i class="fi fi-rr-edit" style="vertical-align: middle; font-size: 18px"></i>
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

                                                <!-- Item Low Stock -->
                                                <div class="mb-3">
                                                    <label for="itemLowStock" class="form-label fw-bold" style="font-size: 18px;">Low Stock Threshold</label>
                                                    <input type="number" class="form-control" id="itemLowStock" placeholder="Enter low stock threshold" name="item_lowstock" required>
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


                            <!-- Edit Item Modal -->
                            <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editItemForm">
                                                <!-- Dropdown to select an item -->
                                                <div class="mb-3">
                                                    <label for="editItemDropdown" class="form-label">Select Item</label>
                                                    <select class="form-select" id="editItemDropdown" name="item_id" required>
                                                        <option value="" disabled selected>Select an item</option>
                                                        <?php
                                                        // Fetch items from tbl_item
                                                        $itemsQuery = "SELECT Item_ID, Item_Name FROM tbl_item";
                                                        $items = $pdo->query($itemsQuery)->fetchAll(PDO::FETCH_ASSOC);

                                                        foreach ($items as $item) {
                                                            echo '<option value="' . htmlspecialchars($item['Item_ID']) . '">' . htmlspecialchars($item['Item_Name']) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Fields to edit item details -->
                                                <div class="mb-3">
                                                    <label for="editItemName" class="form-label">Item Name</label>
                                                    <input type="text" class="form-control" id="editItemName" name="item_name" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editItemCategory" class="form-label">Category</label>
                                                    <select class="form-select" id="editItemCategory" name="item_category" required>
                                                        <option value="" disabled selected>Select a category</option>
                                                        <?php
                                                        // Fetch categories from tbl_itemcategories
                                                        $categoriesQuery = "SELECT Category_ID, Category_Name FROM tbl_itemcategories";
                                                        $categories = $pdo->query($categoriesQuery)->fetchAll(PDO::FETCH_ASSOC);

                                                        foreach ($categories as $category) {
                                                            echo '<option value="' . htmlspecialchars($category['Category_ID']) . '">' . htmlspecialchars($category['Category_Name']) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editItemUnit" class="form-label">Unit of Measurement</label>
                                                    <select class="form-select" id="editItemUnit" name="unit_id" required>
                                                        <option value="" disabled selected>Select a unit</option>
                                                        <?php
                                                        // Fetch units from tbl_unitofmeasurments
                                                        $unitsQuery = "SELECT Unit_ID, Unit_Name, Unit_Acronym FROM tbl_unitofmeasurments";
                                                        $units = $pdo->query($unitsQuery)->fetchAll(PDO::FETCH_ASSOC);

                                                        foreach ($units as $unit) {
                                                            echo '<option value="' . htmlspecialchars($unit['Unit_ID']) . '">' . htmlspecialchars($unit['Unit_Name']) . ' (' . htmlspecialchars($unit['Unit_Acronym']) . ')</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- Item Low Stock -->
                                                <div class="mb-3">
                                                    <label for="editItemLowStock" class="form-label">Low Stock Threshold</label>
                                                    <input type="number" class="form-control" id="editItemLowStock" name="item_lowstock" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editItemImage" class="form-label">Item Image</label>
                                                    <input type="file" class="form-control" id="editItemImage" name="item_image">
                                                    <img id="editItemImagePreview" src="" alt="Item Image" class="mt-2" width="100">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary" id="saveEditItem">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Item Details Modal -->
                            <div class="modal fade" id="editItemDetailsModal" tabindex="-1" aria-labelledby="editItemDetailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editItemDetailsModalLabel">Edit Item Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="update_item.php" enctype="multipart/form-data">
                                                <!-- Item Name -->
                                                <div class="mb-3">
                                                    <label for="itemName" class="form-label">Item Name</label>
                                                    <input type="text" class="form-control" id="itemName" name="itemName" required>
                                                </div>

                                                <!-- Item Category -->
                                                <div class="mb-3">
                                                    <label for="itemCategory" class="form-label">Item Category</label>
                                                    <input type="text" class="form-control" id="itemCategory" name="itemCategory" readonly required>
                                                </div>

                                                <!-- Unit of Measurement -->
                                                <div class="mb-3">
                                                    <label for="unitOfMeasurement" class="form-label">Unit of Measurement</label>
                                                    <input type="text" class="form-control" id="unitOfMeasurement" name="unitOfMeasurement" readonly required>
                                                </div>

                                                                                                <!-- Item Low Stock -->
                                                                                                <div class="mb-3">
                                                    <label for="editItemLowStock" class="form-label">Low Stock Threshold</label>
                                                    <input type="number" class="form-control" id="editItemLowStock" name="item_lowstock" required>
                                                </div>

                                                <!-- Item Image -->
                                                <div class="mb-3">
                                                    <label for="itemImage" class="form-label">Item Image</label>
                                                    <input type="file" class="form-control" id="itemImage" name="itemImage" onchange="previewImage(event)">
                                                    <img id="imagePreview" src="" alt="Item Image" class="mt-2" width="100">
                                                </div>



                                                <!-- Submit Button -->
                                                <button type="submit" class="btn btn-primary">Update Item</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                // Event listener for when an item is clicked in the Edit Item Modal
                                document.querySelectorAll('.item-link').forEach(item => {
                                    item.addEventListener('click', function() {
                                        // Get item details from data attributes
                                        const itemId = this.getAttribute('data-item-id');
                                        const itemName = this.getAttribute('data-item-name');
                                        const itemImage = this.getAttribute('data-item-image');
                                        const itemCategory = this.getAttribute('data-item-category');
                                        const unitOfMeasurement = this.getAttribute('data-item-unit');
                                        const itemImagePreview = this.getAttribute('data-item-image-preview');

                                        // Update the Edit Item Details Modal fields with the item details
                                        document.getElementById('itemName').value = itemName;
                                        document.getElementById('itemCategory').value = itemCategory;
                                        document.getElementById('unitOfMeasurement').value = unitOfMeasurement;
                                        document.getElementById('imagePreview').src = itemImagePreview;
                                    });
                                });
                            </script>



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
                                        // Loop through and display each item
                                        foreach ($groupedItems as $row) {
                                            $itemID = htmlspecialchars($row['Item_ID']);
                                            $itemName = htmlspecialchars($row['Item_Name']);
                                            $itemQty = htmlspecialchars($row['Record_ItemQuantity']);
                                            $volume = htmlspecialchars($row['Record_ItemVolume']);
                                            $lowStockThreshold = htmlspecialchars($row['Item_Lowstock']); 
                                            $uniqueKey = $itemID . '_' . $volume;
                                            $modalID = "decreaseModal_" . $uniqueKey;

                                            // Check for expired items
                                            $currentDate = date('Y-m-d');
                                            $expirationDate = $row['Record_ItemExpirationDate'];
                                            $isExpired = ($expirationDate && $expirationDate <= $currentDate);

                                            // Define border class and image style based on stock and expiration
                                            // Define styles
                                            $cardBorderClass = '';
                                            $imageStyle = 'object-fit: contain;';
                                            $outOfStockOverlay = '';
                                            $expiredOverlay = '';
                                            $tooltipAttr = '';

                                            // Priority: out-of-stock > expired > low stock
                                            if ($itemQty == 0) {
                                                // Out of stock
                                                $cardBorderClass = 'border border-danger';
                                                $imageStyle = 'filter: grayscale(100%) brightness(60%);';
                                                $outOfStockOverlay = '
                                                    <div class="position-absolute top-50 start-50 translate-middle bg-danger text-white px-2 py-1 rounded shadow" style="z-index: 10; font-size: 14px;">
                                                        Out of Stock
                                                    </div>';
                                            } elseif ($isExpired) {
                                                // Expired
                                                $cardBorderClass = 'border border-secondary';
                                                $expiredOverlay = '
                                                    <div class="position-absolute top-50 start-50 translate-middle bg-secondary text-white px-2 py-1 rounded shadow" style="z-index: 10; font-size: 14px;">
                                                        Expired
                                                    </div>';
                                                $imageStyle = 'filter: grayscale(100%) brightness(50%);';
                                            } elseif ($itemQty <= $row['Item_Lowstock']) {
                                                // Low stock
                                                $cardBorderClass = 'border border-warning';
                                                $tooltipAttr = 'data-bs-toggle="tooltip" data-bs-title="Quantity of this item is below the low stock threshold"';
                                            }

                                            echo '
                                                <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0 product-item" 
                                                    data-name="' . htmlspecialchars($row['Item_Name']) . '" 
                                                    data-category="' . htmlspecialchars($row['Category_Name']) . '" 
                                                    data-category-id="' . htmlspecialchars($row['Item_Category']) . '">
                                                    <div class="card flex-shrink-0 ' . $cardBorderClass . '" style="height: 430px; padding: 15px; position: relative;" ' . $tooltipAttr . '>
                                                        <a href="IMS_DisplayItemsTable.php?item_id=' . $itemID . '" class="text-decoration-none text-dark" style="display: block; position: relative;">
                                                            ' . $outOfStockOverlay . '
                                                            ' . $expiredOverlay . '
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
                                                                     <p>Low Stock Threshold: <strong>' . $lowStockThreshold . ' pcs</strong></p> <!-- Display Low Stock Threshold -->
                                                                    <div class="mb-3">
                                                                        <label for="slider_' . $itemID . '" class="form-label">Select amount to decrease:</label>
                                                                        <input type="range" class="form-range" min="0" max="' . $itemQty . '" value="1" id="slider_' . $uniqueKey . '" name="decrease_amount" oninput="updatePreview_' . $uniqueKey . '()">
                                                                        <div>Decreasing by: <strong id="decreasePreview_' . $uniqueKey . '">1</strong> pcs</div>
                                                                        <div>Quantity after decrease: <strong id="afterQty_' . $uniqueKey . '">' . ($itemQty - 1) . '</strong> pcs</div>
                                                                    </div>
                                                                    <input type="hidden" name="item_id" value="' . $itemID . '">
                                                                    <input type="hidden" name="volume" value="' . $volume . '">
                                                                    <input type="hidden" name="item_name" value="' . $itemName . '">
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
                            <div class="col-sm-8" style="display: flex; align-items: center;">
                                <input class="form-control" id="itemVolume" type="text" placeholder="0.00" name="item_volume" style="flex: 1;">
                                <span id="unitAcronym" style="margin-left: 10px; font-weight: bold;"></span>
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
                                <input class="form-control" id="purchaseDate" type="date" placeholder="Select Date" name="purchase_date" min="">
                            </div>
                        </div>
                        <div class="form-group" style="display:flex">
                            <span class="col-sm-4 control-label">Expiration Date</span>
                            <div class="flatpickr col-sm-8">
                                <input class="form-control" id="expirationDate" type="date" placeholder="Select Date" name="expiration_date" min="">
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
                    console.log("Raw Response:", response)
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('editItemDropdown').addEventListener('change', function () {
        const itemId = this.value;

        // Fetch item details via AJAX
        fetch('../IMS-POS/scripts/fetchItemDetails.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `item_id=${itemId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate the modal fields with item details
                document.getElementById('editItemName').value = data.item.Item_Name;
                document.getElementById('editItemCategory').value = data.item.Item_Category;
                document.getElementById('editItemUnit').value = data.item.Unit_ID;
                document.getElementById('editItemLowStock').value = data.item.Item_Lowstock; // Populate Low Stock
                document.getElementById('editItemImagePreview').src = data.item.Item_Image;
            } else {
                alert('Failed to fetch item details.');
            }
        })
        .catch(error => {
            console.error('Error fetching item details:', error);
            alert('An error occurred while fetching item details.');
        });
    });

            // Handle Save Changes button click
            document.getElementById('saveEditItem').addEventListener('click', function() {
                const formData = new FormData(document.getElementById('editItemForm'));

                // Submit the form via AJAX
                fetch('../IMS-POS/scripts/updateItemDetails.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Item updated successfully!');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Failed to update item: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating item:', error);
                        alert('An error occurred while updating the item.');
                    });
            });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemDropdown = document.getElementById('itemDropdown');
    const unitAcronymSpan = document.getElementById('unitAcronym');

    itemDropdown.addEventListener('change', function () {
        const itemName = this.value; // Use Item_Name instead of Item_ID

        // Fetch UOM details via AJAX
        fetch('../IMS-POS/scripts/fetchUnitOfMeasurement.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `item_name=${encodeURIComponent(itemName)}` // Pass Item_Name
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display the Unit_Acronym next to the Item Volume field
                unitAcronymSpan.textContent = data.unit.Unit_Acronym;
            } else {
                unitAcronymSpan.textContent = ''; // Clear the Unit_Acronym if not found
                alert(data.message || 'Failed to fetch Unit of Measurement.');
            }
        })
        .catch(error => {
            console.error('Error fetching Unit of Measurement:', error);
            unitAcronymSpan.textContent = ''; // Clear the Unit_Acronym on error
            alert('An error occurred while fetching Unit of Measurement.');
        });
    });
});
    </script>
</body>
<?php include 'footer.php' ?>

</html>