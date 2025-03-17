<?php include '../Login/database.php' ?>
<?php

try {
    $pdo = new PDO($attrs, $db_user, $db_pass,$opts);
    echo 'database connected';    
} catch (Exception $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

/* Fetching Category List from the database */
$fetchCategoryQuery = "SELECT * FROM tbl_itemcategories";
$item_categories = $pdo->query($fetchCategoryQuery)->fetchAll(PDO::FETCH_ASSOC);

/* Fetching Employee List from the database */
$fetchEmployeeQuery = "SELECT * FROM employees";
$employee = $pdo -> query($fetchEmployeeQuery) -> fetchAll(PDO::FETCH_ASSOC);


/* PUSHING ITEM DATA TO THE DATABASE */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["item_name"]) && isset($_POST["item_category"]) ) {

        // Assign POST data to variables
        $item_Name = $_POST["item_name"];
        $item_Category = $_POST["item_category"];

        // File upload handling
        $file_name = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $folder = 'itemImages/' . $file_name;

        // Move uploaded file
        if (move_uploaded_file($tempname, $folder)) {
            try {
                // Insert query without prepared statement
                $sql = "INSERT INTO tbl_item (Item_Name, Item_Category, Item_Image) 
                        VALUES ('$item_Name', '$item_Category', '$folder')";

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

?>
