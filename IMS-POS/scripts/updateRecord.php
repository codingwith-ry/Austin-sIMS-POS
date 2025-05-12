<?php
// filepath: c:\xampp\htdocs\Austin-sIMS-POS\IMS-POS\scripts\updateRecord.php
include '../../Login/database.php';

session_start();

date_default_timezone_set('Asia/Manila'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $recordId = $_POST['recordId'];
    $itemVolume = $_POST['itemVolume'];
    $itemQuantity = $_POST['itemQuantity'];
    $itemPrice = $_POST['itemPrice'];
    $itemExpirationDate = $_POST['itemExpirationDate']; // Fetch expiration date from input

    try {
        // Calculate the total price
        $newTotalPrice = $itemQuantity * $itemPrice;

           // Fetch the old total price for this record
        $fetchOldPriceStmt = $conn->prepare("SELECT Record_TotalPrice FROM tbl_record WHERE Record_ID = :recordId");
        $fetchOldPriceStmt->bindParam(':recordId', $recordId);
        $fetchOldPriceStmt->execute();
        $oldTotalPrice = $fetchOldPriceStmt->fetch(PDO::FETCH_ASSOC)['Record_TotalPrice'] ?? 0;

        // Update the record in tbl_record
        $updateStmt = $conn->prepare("
            UPDATE tbl_record
            SET 
                Record_ItemVolume = :itemVolume,
                Record_ItemQuantity = :itemQuantity,
                Record_ItemPrice = :itemPrice,
                Record_ItemExpirationDate = :itemExpirationDate,
                Record_TotalPrice = :totalPrice
            WHERE Record_ID = :recordId
        ");

        $updateStmt->bindParam(':recordId', $recordId);
        $updateStmt->bindParam(':itemVolume', $itemVolume);
        $updateStmt->bindParam(':itemQuantity', $itemQuantity);
        $updateStmt->bindParam(':itemPrice', $itemPrice);
        $updateStmt->bindParam(':itemExpirationDate', $itemExpirationDate);
        $updateStmt->bindParam(':totalPrice', $newTotalPrice);
        $updateStmt->execute();

        // Update the record in tbl_record_duplicate
        $updateDuplicateStmt = $conn->prepare("
            UPDATE tbl_record_duplicate
            SET 
                Record_ItemVolume = :itemVolume,
                Record_ItemQuantity = :itemQuantity,
                Record_ItemPrice = :itemPrice,
                Record_ItemExpirationDate = :itemExpirationDate,
                Record_TotalPrice = :totalPrice
            WHERE RecordDuplicate_ID = :recordId
        ");

        $updateDuplicateStmt->bindParam(':recordId', $recordId);
        $updateDuplicateStmt->bindParam(':itemVolume', $itemVolume);
        $updateDuplicateStmt->bindParam(':itemQuantity', $itemQuantity);
        $updateDuplicateStmt->bindParam(':itemPrice', $itemPrice);
        $updateDuplicateStmt->bindParam(':itemExpirationDate', $itemExpirationDate);
        $updateDuplicateStmt->bindParam(':totalPrice', $newTotalPrice);
        $updateDuplicateStmt->execute();

        

        // Fetch the updated record
        $fetchStmt = $conn->prepare("
            SELECT 
                r.Record_ID,
                r.Record_ItemVolume,
                r.Record_ItemQuantity,
                r.Record_ItemPrice,
                r.Record_ItemExpirationDate,
                r.Record_TotalPrice,
                u.Unit_Name
            FROM tbl_record r
            LEFT JOIN tbl_item i ON r.Item_ID = i.Item_ID
            LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
            WHERE r.Record_ID = :recordId
        ");

        $fetchStmt->bindParam(':recordId', $recordId);
        $fetchStmt->execute();
        $updatedRecord = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        // Fetch the current Total_Stock_Budget, Total_Expenses, and Total_Calculated_Budget from tbl_stocks
        $stockID = 1; // Assuming Stock_ID is 1 (adjust as needed)
        $stockQuery = $conn->prepare("SELECT Total_Stock_Budget, Total_Expenses, Total_Calculated_Budget FROM tbl_stocks WHERE Stock_ID = :stockID");
        $stockQuery->bindParam(':stockID', $stockID);
        $stockQuery->execute();
        $stockResult = $stockQuery->fetch(PDO::FETCH_ASSOC);

        $totalStockBudget = $stockResult['Total_Stock_Budget'] ?? 0;
        $currentTotalExpenses = $stockResult['Total_Expenses'] ?? 0;
        $previousCalculatedBudget = $stockResult['Total_Calculated_Budget'] ?? 0;

        // Calculate the new Total_Expenses and Total_Calculated_Budget
        $newTotalExpenses = $currentTotalExpenses - $oldTotalPrice + $newTotalPrice;
        $newCalculatedBudget = $totalStockBudget - $newTotalExpenses;

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
        $amountAdded = $newTotalPrice - $oldTotalPrice; // Difference between new and old total price
        $dateTime = date('Y-m-d H:i:s'); // Current date and time

        $inventoryLogStmt = $conn->prepare("
            INSERT INTO tbl_inventorylogs (Employee_ID, Amount_Added, Date_Time, Previous_Sum, Stock_ID, Updated_Sum)
            VALUES (:employeeID, :amountAdded, :dateTime, :previousSum, :stockID, :updatedSum)
        ");
        $inventoryLogStmt->bindParam(':employeeID', $_SESSION['employeeID']); // Assuming Employee_ID is stored in the session
        $inventoryLogStmt->bindParam(':amountAdded', $amountAdded);
        $inventoryLogStmt->bindParam(':dateTime', $dateTime);
        $inventoryLogStmt->bindParam(':previousSum', $previousCalculatedBudget); // Previous Total_Calculated_Budget
        $inventoryLogStmt->bindParam(':stockID', $stockID);
        $inventoryLogStmt->bindParam(':updatedSum', $newCalculatedBudget); // New Total_Calculated_Budget
        $inventoryLogStmt->execute();



        // Log the update in tbl_userlogs
        $logEmail = $_SESSION['email'] ?? 'unknown'; // Use the session variable for the email
        $logRole = $_SESSION['userRole'] ?? 'unknown'; // Use the session variable for the user's role
        $logContent = "Edited record with Record ID: $recordId. Updated fields: Volume - $itemVolume, Quantity - $itemQuantity, Price - $itemPrice, Expiration Date - $itemExpirationDate.";
        $logDate = date('Y-m-d H:i:s');

        $userLogStmt = $conn->prepare("
            INSERT INTO tbl_userlogs (logEmail, logRole, logContent, logDate)
            VALUES (:logEmail, :logRole, :logContent, :logDate)
        ");
        $userLogStmt->bindParam(':logEmail', $logEmail);
        $userLogStmt->bindParam(':logRole', $logRole);
        $userLogStmt->bindParam(':logContent', $logContent);
        $userLogStmt->bindParam(':logDate', $logDate);
        $userLogStmt->execute();

        echo json_encode(['success' => true, 'record' => $updatedRecord]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>