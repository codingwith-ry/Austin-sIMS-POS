<?php include '../Login/database.php' ?>
<?php

date_default_timezone_set('Asia/Manila');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = new PDO($attrs, $db_user, $db_pass, $opts);
    //echo 'database connected';    
} catch (Exception $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

/* Fetching Category List from the database */
$fetchCategoryQuery = "SELECT * FROM tbl_itemcategories";
$item_categories = $pdo->query($fetchCategoryQuery)->fetchAll(PDO::FETCH_ASSOC);

/* Fetching Item Name from the database */
$fetchItemQuery = "SELECT * FROM tbl_item";
$items = $pdo->query($fetchItemQuery)->fetchAll(PDO::FETCH_ASSOC);

/* Fetching Unit of Measurement from the database */

$fetchUnitQuery = "SELECT * FROM tbl_unitofmeasurments";
$unitOfMeasurementList = $pdo->query($fetchUnitQuery)->fetchAll(PDO::FETCH_ASSOC);

/* Fetching Employee List from the database */
$fetchEmployeeQuery = "SELECT * FROM employees";
$employee = $pdo->query($fetchEmployeeQuery)->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['add_record'])) {
    $itemName = filter_input(INPUT_POST, 'item_Name', FILTER_SANITIZE_STRING);
    $itemCategory = filter_input(INPUT_POST, 'item_category', FILTER_SANITIZE_NUMBER_INT);
    $itemPrice = number_format((float)$_POST['item_price'], 2, '.', ''); // Format as decimal(11,2)
    $itemVolume = number_format((float)$_POST['item_volume'], 2, '.', ''); // Format as decimal(11,2)
    $itemQuantity = filter_input(INPUT_POST, 'item_quantity', FILTER_SANITIZE_NUMBER_INT);
    $purchaseDate = filter_input(INPUT_POST, 'purchase_date', FILTER_SANITIZE_STRING);
    $expirationDate = filter_input(INPUT_POST, 'expiration_date', FILTER_SANITIZE_STRING);
    $itemSupplier = filter_input(INPUT_POST, 'item_supplier', FILTER_SANITIZE_STRING);
    $employeeAssigned = filter_input(INPUT_POST, 'employee_assigned', FILTER_SANITIZE_NUMBER_INT);

    try {
        // Fetch the Item_ID based on the item_Name
        $stmt = $conn->prepare("SELECT Item_ID FROM tbl_item WHERE Item_Name = :itemName");
        $stmt->bindParam(':itemName', $itemName);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            echo "<script>alert('Item not found in the database. Please add the item first.');</script>";
            exit();
        }

        $itemID = $item['Item_ID'];

        // Generate a unique Record_ID
        $recordID = mt_rand(100000, 999999);

        // Calculate the total price
        $totalPrice = number_format($itemQuantity * $itemPrice, 2, '.', ''); // Format as decimal(11,2)

        // Insert into tbl_record
        $stmt = $conn->prepare("INSERT INTO tbl_record (Record_ID, Item_ID, Record_ItemVolume, 
        Record_ItemQuantity, Record_ItemPrice, Record_ItemExpirationDate,
        Record_ItemPurchaseDate, Record_ItemSupplier, Record_EmployeeAssigned, Record_TotalPrice) 
        VALUES (:recordID, :itemID, :itemVolume, :itemQuantity, 
        :itemPrice, :expirationDate, :purchaseDate, :itemSupplier,
        :employeeAssigned, :totalPrice)");

        $stmt->bindParam(':recordID', $recordID);
        $stmt->bindParam(':itemID', $itemID);
        $stmt->bindParam(':itemVolume', $itemVolume);
        $stmt->bindParam(':itemQuantity', $itemQuantity);
        $stmt->bindParam(':itemPrice', $itemPrice);
        $stmt->bindParam(':expirationDate', $expirationDate);
        $stmt->bindParam(':purchaseDate', $purchaseDate);
        $stmt->bindParam(':itemSupplier', $itemSupplier);
        $stmt->bindParam(':employeeAssigned', $employeeAssigned);
        $stmt->bindParam(':totalPrice', $totalPrice, PDO::PARAM_STR);
        $stmt->execute();

        // Insert into tbl_record_duplicate
        $duplicateStmt = $conn->prepare("
            INSERT INTO tbl_record_duplicate (RecordDuplicate_ID, Item_ID, Record_ItemVolume, Record_ItemQuantity, Record_ItemPrice, Record_ItemExpirationDate, Record_ItemPurchaseDate, Record_ItemSupplier, Record_EmployeeAssigned, Record_TotalPrice) 
            VALUES (:recordDuplicateID, :itemID, :itemVolume, :itemQuantity, :itemPrice, :expirationDate, :purchaseDate, :itemSupplier, :employeeAssigned, :totalPrice)
        ");
        $duplicateStmt->bindParam(':recordDuplicateID', $recordID);
        $duplicateStmt->bindParam(':itemID', $itemID);
        $duplicateStmt->bindParam(':itemVolume', $itemVolume);
        $duplicateStmt->bindParam(':itemQuantity', $itemQuantity);
        $duplicateStmt->bindParam(':itemPrice', $itemPrice);
        $duplicateStmt->bindParam(':expirationDate', $expirationDate);
        $duplicateStmt->bindParam(':purchaseDate', $purchaseDate);
        $duplicateStmt->bindParam(':itemSupplier', $itemSupplier);
        $duplicateStmt->bindParam(':employeeAssigned', $employeeAssigned);
        $duplicateStmt->bindParam(':totalPrice', $totalPrice);
        $duplicateStmt->execute();

        // Fetch the current Total_Stock_Budget, Total_Expenses, and Total_Calculated_Budget from tbl_stocks
        $stockID = 1; // Assuming Stock_ID is 1 (adjust as needed)
        $stockQuery = $conn->prepare("SELECT Total_Stock_Budget, Total_Expenses, Total_Calculated_Budget FROM tbl_stocks WHERE Stock_ID = :stockID");
        $stockQuery->bindParam(':stockID', $stockID);
        $stockQuery->execute();
        $stockResult = $stockQuery->fetch(PDO::FETCH_ASSOC);

        $totalStockBudget = number_format($stockResult['Total_Stock_Budget'] ?? 0, 2, '.', '');
        $currentTotalExpenses = number_format($stockResult['Total_Expenses'] ?? 0, 2, '.', '');
        $previousCalculatedBudget = number_format($stockResult['Total_Calculated_Budget'] ?? 0, 2, '.', '');

        // Add the calculated $totalPrice to the current Total_Expenses
        $newTotalExpenses = number_format($currentTotalExpenses + $totalPrice, 2, '.', '');

        // Calculate the new Total_Calculated_Budget
        $newCalculatedBudget = number_format($totalStockBudget - $newTotalExpenses, 2, '.', '');

        // Update the Total_Expenses and Total_Calculated_Budget in tbl_stocks
        $updateStockStmt = $conn->prepare("
            UPDATE tbl_stocks 
            SET Total_Expenses = :newTotalExpenses, Total_Calculated_Budget = :newCalculatedBudget 
            WHERE Stock_ID = :stockID
        ");
        $updateStockStmt->bindParam(':newTotalExpenses', $newTotalExpenses);
        $updateStockStmt->bindParam(':newCalculatedBudget', $newCalculatedBudget);
        $updateStockStmt->bindParam(':stockID', $stockID);
        $updateStockStmt->execute();

        // Insert an entry into tbl_inventorylogs
        $amountAdded = -1 * $totalPrice;  // Positive because it's an addition
        $dateTime = date('Y-m-d H:i:s'); // Current date and time

        $inventoryLogStmt = $conn->prepare("
            INSERT INTO tbl_inventorylogs (Employee_ID, Amount_Added, Date_Time, Previous_Sum, Stock_ID, Updated_Sum)
            VALUES (:employeeID, :amountAdded, :dateTime, :previousSum, :stockID, :updatedSum)
        ");
        $inventoryLogStmt->bindParam(':employeeID', $employeeAssigned); // Assuming Employee_ID is the assigned employee
        $inventoryLogStmt->bindParam(':amountAdded', $amountAdded);
        $inventoryLogStmt->bindParam(':dateTime', $dateTime);
        $inventoryLogStmt->bindParam(':previousSum', $previousCalculatedBudget); // Previous Total_Calculated_Budget
        $inventoryLogStmt->bindParam(':stockID', $stockID);
        $inventoryLogStmt->bindParam(':updatedSum', $newCalculatedBudget); // New Total_Calculated_Budget
        $inventoryLogStmt->execute();

        // Insert a log entry into tbl_userlogs
        $logEmail = $_SESSION['email']; // Use the session variable for the email
        $logRole = $_SESSION['userRole']; // Use the session variable for the user's role
        $logContent = "Added a new record to inventory: Item Name - $itemName, Quantity - $itemQuantity.";
        $logDate = date('Y-m-d H:i:s');  // Current date

        $logStmt = $conn->prepare("
            INSERT INTO tbl_userlogs (logEmail, logRole, logContent, logDate) 
            VALUES (:logEmail, :logRole, :logContent, :logDate)
        ");
        $logStmt->bindParam(':logEmail', $logEmail);
        $logStmt->bindParam(':logRole', $logRole);
        $logStmt->bindParam(':logContent', $logContent);
        $logStmt->bindParam(':logDate', $logDate);
        $logStmt->execute();

        echo "<script>alert('Record added successfully!');</script>";
        header("Location: Inventory_Item-Records.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

/* PUSHING ITEM DATA TO THE DATABASE */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["item_name"]) && isset($_POST["item_category"]) && isset($_POST["item_unit"]) && isset($_POST["item_lowstock"])) {
        // Assign POST data to variables
        $item_Name = $_POST["item_name"];
        $item_Category = $_POST["item_category"];
        $item_Unit = $_POST["item_unit"];
        $item_Lowstock = $_POST["item_lowstock"];

        // File upload handling
        $file_name = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $folder = 'itemImages/' . $file_name;

        // Move uploaded file
        if (move_uploaded_file($tempname, $folder)) {
            try {
                // Insert query to add the new item, including Item_Lowstock
                $sql = "INSERT INTO tbl_item (Item_Name, Item_Category, Item_Image, Unit_ID, Item_Lowstock) 
                        VALUES (:item_name, :item_category, :item_image, :unit_id, :item_lowstock)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':item_name', $item_Name);
                $stmt->bindParam(':item_category', $item_Category);
                $stmt->bindParam(':item_image', $folder);
                $stmt->bindParam(':unit_id', $item_Unit);
                $stmt->bindParam(':item_lowstock', $item_Lowstock);

                if ($stmt->execute()) {
                    echo "<script>alert('Item added successfully!');</script>";
                    header("Location: Inventory_Item-Records.php");
                    exit();
                } else {
                    echo "<script>alert('Failed to add item.');</script>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "<script>alert('Failed to upload image.');</script>";
        }
    }
}

$fetchInventoryQuery = "
    SELECT r.Record_ID, r.Record_ItemPurchaseDate, r.Record_EmployeeAssigned, r.Record_ItemVolume, r.Record_TotalPrice,
           i.Item_Name, i.Item_Image, ic_cat.Category_Name, u.Unit_Name, 
           r.Record_ItemQuantity, r.Record_ItemExpirationDate, r.Record_ItemPrice,
           e.Employee_Name
    FROM tbl_record r
    JOIN tbl_item i ON r.Item_ID = i.Item_ID
    LEFT JOIN tbl_itemcategories ic_cat ON i.Item_Category = ic_cat.Category_ID
    LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
    LEFT JOIN employees e ON r.Record_EmployeeAssigned = e.Employee_ID
    ORDER BY r.Record_ItemPurchaseDate DESC
";

$inventoryRecords = $pdo->query($fetchInventoryQuery)->fetchAll(PDO::FETCH_ASSOC);


$fetchItemDataQuery = "
    SELECT 
        i.Item_ID,
        i.Item_Name, 
        i.Item_Image, 
        i.Item_Category, 
        ic_cat.Category_Name, 
        um.Unit_Acronym, 
        IFNULL(r.Record_ItemQuantity, 0) AS Record_ItemQuantity,
        IFNULL(r.Record_ItemVolume, 0) AS Record_ItemVolume,
        r.Record_ItemExpirationDate,
        i.Item_Lowstock,
        IFNULL(SUM(ic.Change_Quantity), 0) AS Total_Change, 
        IFNULL(SUM(CASE WHEN ic.Change_Type = 'decrease' THEN ic.Change_Quantity ELSE 0 END), 0) AS Total_Decrease 
    FROM tbl_item i
    JOIN tbl_itemcategories ic_cat ON i.Item_Category = ic_cat.Category_ID
    LEFT JOIN tbl_record r ON i.Item_ID = r.Item_ID
    LEFT JOIN tbl_unitofmeasurments um ON i.Unit_ID = um.Unit_ID
    LEFT JOIN tbl_inventory_changes ic ON r.Record_ID = ic.Record_ID
    GROUP BY i.Item_ID, r.Record_ItemVolume, ic_cat.Category_Name, um.Unit_Acronym, i.Item_Name, i.Item_Image, r.Record_ItemExpirationDate
    ORDER BY r.Record_ItemExpirationDate DESC
";


$itemData = $pdo->query($fetchItemDataQuery)->fetchAll(PDO::FETCH_ASSOC);


if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    // Fetch the records of the clicked item
    $query = "
        SELECT i.Item_Name, r.Record_ItemQuantity, r.Record_ItemPurchaseDate, 
           r.Record_ItemExpirationDate, e.Employee_Name, r.Record_ID
    FROM tbl_record r
    JOIN tbl_item i ON i.Item_ID = r.Item_ID
    LEFT JOIN employees e ON r.Record_EmployeeAssigned = e.Employee_ID
    WHERE i.Item_ID = :item_id
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['item_id' => $item_id]);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>