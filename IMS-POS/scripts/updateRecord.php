<?php
include '../../Login/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordId = $_POST['recordId'];
    $itemID = $_POST['itemID'];
    $itemVolume = $_POST['itemVolume'];
    $itemQuantity = $_POST['itemQuantity'];
    $itemPrice = $_POST['itemPrice'];
    $itemExpirationDate = $_POST['itemExpirationDate'];
    $itemPurchaseDate = $_POST['itemPurchaseDate'];
    $itemSupplier = $_POST['itemSupplier'];
    $employeeAssigned = $_POST['employeeAssigned'];
    $totalPrice = $itemQuantity * $itemPrice;

    try {
        // Calculate the total price


        // Insert into tbl_record
        $stmt = $conn->prepare("
            INSERT INTO tbl_record (
                Record_ID, 
                Item_ID, 
                Record_ItemVolume, 
                Record_ItemQuantity, 
                Record_ItemPrice, 
                Record_ItemExpirationDate, 
                Record_ItemPurchaseDate, 
                Record_ItemSupplier, 
                Record_EmployeeAssigned, 
                Record_TotalPrice
            ) VALUES (
                :recordId, 
                :itemID, 
                :itemVolume, 
                :itemQuantity, 
                :itemPrice, 
                :itemExpirationDate, 
                :itemPurchaseDate, 
                :itemSupplier, 
                :employeeAssigned, 
                :totalPrice
            )
        ");
        $stmt->bindParam(':recordId', $recordId);
        $stmt->bindParam(':itemID', $itemID);
        $stmt->bindParam(':itemVolume', $itemVolume);
        $stmt->bindParam(':itemQuantity', $itemQuantity);
        $stmt->bindParam(':itemPrice', $itemPrice);
        $stmt->bindParam(':itemExpirationDate', $itemExpirationDate);
        $stmt->bindParam(':itemPurchaseDate', $itemPurchaseDate);
        $stmt->bindParam(':itemSupplier', $itemSupplier);
        $stmt->bindParam(':employeeAssigned', $employeeAssigned);
        $stmt->bindParam(':totalPrice', $totalPrice);

        $stmt->execute();

        echo "Record inserted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>