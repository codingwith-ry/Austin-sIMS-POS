<?php
include '../Login/database.php';

// Helper function to get the start date for the selected period
function getStartDate($period)
{
    $currentDate = date('Y-m-d');

    switch ($period) {
        case 'weekly':
            return date('Y-m-d', strtotime('-1 week', strtotime($currentDate)));
        case 'monthly':
            return date('Y-m-d', strtotime('-1 month', strtotime($currentDate)));
        case 'yearly':
            return date('Y-m-d', strtotime('-1 year', strtotime($currentDate)));
        default:
            return $currentDate;
    }
}

// Get the period and start date from the request
$period = isset($_GET['period']) ? $_GET['period'] : 'weekly';
$startDate = getStartDate($period);

// Fetch the total number of items in stock for the selected date
$query = "
SELECT COUNT(*) AS total_items
FROM (
    SELECT DISTINCT Item_ID, Record_ItemVolume
    FROM tbl_record
    WHERE Record_ItemPurchaseDate >= :startDate
) AS distinct_items
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':startDate', $startDate);
$stmt->execute();
$totalItems = $stmt->fetch(PDO::FETCH_ASSOC)['total_items'];

$expenseQuery = "SELECT SUM(Record_ItemPrice) AS total_expenses FROM tbl_record WHERE Record_ItemPurchaseDate >= :startDate";
$expenseStmt = $conn->prepare($expenseQuery);
$expenseStmt->bindParam(':startDate', $startDate);
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

// Round to nearest whole number if an integer, or keep up to two decimal places
$expensesPercentage = (floor($expensesPercentage) == $expensesPercentage) ? round($expensesPercentage) : round($expensesPercentage, 2);
$remainingBudgetPercentage = (floor($remainingBudgetPercentage) == $remainingBudgetPercentage) ? round($remainingBudgetPercentage) : round($remainingBudgetPercentage, 2);
$itemsInStockPercentage = (floor($itemsInStockPercentage) == $itemsInStockPercentage) ? round($itemsInStockPercentage) : round($itemsInStockPercentage, 2);

// Prepare data for JavaScript
echo json_encode([
    'remaining_budget' => $remainingBudgetPercentage,
    'expenses' => $expensesPercentage,
    'items_in_stock' => $itemsInStockPercentage
]);
