<?php 
include '../Login/database.php';

$query = "
SELECT COUNT(*) AS total_items
FROM (
    SELECT DISTINCT Item_ID, Record_ItemVolume
    FROM tbl_record
) AS distinct_items
"; 
$stmt = $conn->prepare($query);
$stmt->execute();
$totalItems = $stmt->fetch(PDO::FETCH_ASSOC)['total_items'];

$expenseQuery = "SELECT SUM(Record_ItemPrice) AS total_expenses FROM tbl_record";
$expenseStmt = $conn->prepare($expenseQuery);
$expenseStmt->execute();
$totalExpenses = $expenseStmt->fetch(PDO::FETCH_ASSOC)['total_expenses'];

$budgetQuery = "SELECT Total_Stock_Budget FROM tbl_stocks WHERE Stock_ID = 1";
$budgetStmt = $conn->prepare($budgetQuery);
$budgetStmt->execute();
$totalStockBudget = $budgetStmt->fetch(PDO::FETCH_ASSOC)['Total_Stock_Budget'];

// Calculate the adjusted stock budget
$adjustedStockBudget = $totalStockBudget - $totalExpenses;

// Calculate percentages
$expensesPercentage = ($totalExpenses / $totalStockBudget) * 100;
$remainingBudgetPercentage = 100 - $expensesPercentage;
$itemsInStockPercentage = ($totalItems / 100) * 100;  // Example, adjust as needed for scale

// Prepare data for JavaScript
echo json_encode([
'remaining_budget' => $remainingBudgetPercentage,
'expenses' => $expensesPercentage,
'items_in_stock' => $itemsInStockPercentage
]);
?>