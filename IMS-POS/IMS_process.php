<?php include '../Login/database.php' ?>
<?php

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
    $itemPrice = filter_input(INPUT_POST, 'item_price', FILTER_SANITIZE_NUMBER_INT);
    $itemVolume = filter_input(INPUT_POST, 'item_volume', FILTER_SANITIZE_NUMBER_INT);
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

        // Insert into tbl_record
        $stmt = $conn->prepare("INSERT INTO tbl_record (Record_ID, Item_ID, Record_ItemVolume, 
        Record_ItemQuantity, Record_ItemPrice, Record_ItemExpirationDate,
        Record_ItemPurchaseDate, Record_ItemSupplier, Record_EmployeeAssigned) 
        VALUES (:recordID, :itemID, :itemVolume, :itemQuantity, 
        :itemPrice, :expirationDate, :purchaseDate, :itemSupplier,
        :employeeAssigned)");

        $stmt->bindParam(':recordID', $recordID);
        $stmt->bindParam(':itemID', $itemID);
        $stmt->bindParam(':itemVolume', $itemVolume);
        $stmt->bindParam(':itemQuantity', $itemQuantity);
        $stmt->bindParam(':itemPrice', $itemPrice);
        $stmt->bindParam(':expirationDate', $expirationDate);
        $stmt->bindParam(':purchaseDate', $purchaseDate);
        $stmt->bindParam(':itemSupplier', $itemSupplier);
        $stmt->bindParam(':employeeAssigned', $employeeAssigned);

        if ($stmt->execute()) {
            echo "<script>alert('Record added successfully!');</script>";
            header("Location: Inventory_Item-Records.php");
            exit();
        } else {
            echo "<script>alert('Failed to add record.');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

/* PUSHING ITEM DATA TO THE DATABASE */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["item_name"]) && isset($_POST["item_category"]) && isset($_POST["item_unit"])) {

        // Assign POST data to variables
        $item_Name = $_POST["item_name"];
        $item_Category = $_POST["item_category"];
        $item_Unit = $_POST["item_unit"];

        // File upload handling
        $file_name = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $folder = 'itemImages/' . $file_name;

        // Move uploaded file
        if (move_uploaded_file($tempname, $folder)) {
            try {
                // Insert query without prepared statement
                $sql = "INSERT INTO tbl_item (Item_Name, Item_Category, Item_Image, Unit_ID) 
                        VALUES ('$item_Name', '$item_Category', '$folder', '$item_Unit')";

                // Execute query
                $pdo->exec($sql);

                // Redirect after successful insert
                header("Location: Inventory_Item-Records.php");
                exit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Failed to upload image.";
        }
    }
}

$fetchInventoryQuery = "
    SELECT r.Record_ID, r.Record_ItemPurchaseDate, r.Record_EmployeeAssigned, r.Record_ItemVolume,
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
    r.Record_ItemQuantity, 
    r.Record_ItemVolume,
    r.Record_ItemExpirationDate,  -- Add this line to fetch the expiration date
    IFNULL(SUM(ic.Change_Quantity), 0) AS Total_Change, 
    IFNULL(SUM(CASE WHEN ic.Change_Type = 'decrease' THEN ic.Change_Quantity ELSE 0 END), 0) AS Total_Decrease 
FROM tbl_item i
JOIN tbl_itemcategories ic_cat ON i.Item_Category = ic_cat.Category_ID
JOIN tbl_record r ON i.Item_ID = r.Item_ID
LEFT JOIN tbl_unitofmeasurments um ON i.Unit_ID = um.Unit_ID
LEFT JOIN tbl_inventory_changes ic ON r.Record_ID = ic.Record_ID
GROUP BY i.Item_ID, r.Record_ItemVolume, ic_cat.Category_Name, um.Unit_Acronym, i.Item_Name, i.Item_Image, r.Record_ItemExpirationDate
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