<?php include '../Login/database.php' ?>
<?php

try {
    $pdo = new PDO($attrs, $db_user, $db_pass,$opts);
    echo 'database connected';    
} catch (Exception $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

/* Fetching Category List from the database */
$fetchCategoryQuery = "SELECT * FROM tbl_categories";
$categories = $pdo->query($fetchCategoryQuery)->fetchAll(PDO::FETCH_ASSOC);

/* Fetching Employee List from the database */
$fetchEmployeeQuery = "SELECT * FROM employees";
$employee = $pdo -> query($fetchEmployeeQuery) -> fetchAll(PDO::FETCH_ASSOC);

/* Pushing inventory data to the database */
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["item_name"]) && isset($_POST["item_category"]) && isset($_POST["item_price"]) 
    && isset($_POST["purchase_date"]) && isset($_POST["expiration_date"]) && isset($_POST["employee_assigned"])){
        $item_Name = $_POST["item_name"];
        $item_Category = $_POST["item_category"];
        $item_Price = $_POST["item_price"];
        $purchase_date = $_POST["purchase_date"];
        $expiration_date = $_POST["expiration_date"];
        $employee_assigned = $_POST["employee_assigned"];
        header("Location: IMS.php");

    }
}
?>
