<?php
include '../../Login/database.php';

session_start();

date_default_timezone_set('Asia/Manila'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $recordId = $_POST['recordId'];
    $itemVolume = $_POST['itemVolume'];
    $itemQuantity = $_POST['itemQuantity'];
    $itemPrice = $_POST['itemPrice'];
    $itemExpirationDate = $_POST['itemExpirationDate'];

    try {
        // Calculate the total cost of the new record
        $totalCost = $itemQuantity * $itemPrice;

        // Fetch the current Total_Stock_Budget
        $fetchBudgetStmt = $conn->prepare("
            SELECT Total_Stock_Budget
            FROM tbl_stocks
            WHERE Stock_ID = 1
        ");
        $fetchBudgetStmt->execute();
        $currentBudget = $fetchBudgetStmt->fetch(PDO::FETCH_ASSOC)['Total_Stock_Budget'];

        // Calculate the new budget after deduction
        $newBudget = $currentBudget - $totalCost;

        // Debugging: Log the budget changes
        error_log("Current Budget: " . $currentBudget);
        error_log("Total Cost: " . $totalCost);
        error_log("New Budget: " . $newBudget);

        // Update the Total_Stock_Budget in tbl_stocks
        $updateBudgetStmt = $conn->prepare("
            UPDATE tbl_stocks
            SET Total_Stock_Budget = :newBudget
            WHERE Stock_ID = 1
        ");
        $updateBudgetStmt->bindParam(':newBudget', $newBudget, PDO::PARAM_INT);
        $updateBudgetStmt->execute();

        // Update tbl_record
        $stmt = $conn->prepare("
            UPDATE tbl_record
            SET Record_ItemVolume = :itemVolume,
                Record_ItemQuantity = :itemQuantity,
                Record_ItemPrice = :itemPrice,
                Record_ItemExpirationDate = :itemExpirationDate
            WHERE Record_ID = :recordId
        ");
        $stmt->bindParam(':recordId', $recordId);
        $stmt->bindParam(':itemVolume', $itemVolume);
        $stmt->bindParam(':itemQuantity', $itemQuantity);
        $stmt->bindParam(':itemPrice', $itemPrice);
        $stmt->bindParam(':itemExpirationDate', $itemExpirationDate);
        $stmt->execute();

        // Fetch the updated record
        $fetchStmt = $conn->prepare("
            SELECT 
                r.Record_ID,
                r.Record_ItemVolume,
                r.Record_ItemQuantity,
                r.Record_ItemPrice,
                r.Record_ItemExpirationDate,
                u.Unit_Name
            FROM tbl_record r
            LEFT JOIN tbl_item i ON r.Item_ID = i.Item_ID
            LEFT JOIN tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
            WHERE r.Record_ID = :recordId
        ");
        $fetchStmt->bindParam(':recordId', $recordId);
        $fetchStmt->execute();
        $updatedRecord = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'updatedData' => [$updatedRecord]]);
        
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>