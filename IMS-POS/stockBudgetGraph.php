<?php
include '../Login/database.php';

// Helper function to get the start date for the selected period
function getStartDate($period)
{
    $currentDate = date('Y-m-d');
    switch ($period) {
        case 'weekly':
            return date('Y-m-d', strtotime('monday this week'));
        case 'monthly':
            return date('Y-m-01');
        case 'yearly':
            return date('Y-01-01');
        default:
            return $currentDate;
    }
}

$period = isset($_GET['period']) ? $_GET['period'] : 'weekly';
$startDate = getStartDate($period);

// Get total items in stock from the selected period
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

// Get total expenses for the selected period
$expenseQuery = "
    SELECT SUM(Record_ItemPrice) AS total_expenses 
    FROM tbl_record 
    WHERE Record_ItemPurchaseDate >= :startDate
";
$expenseStmt = $conn->prepare($expenseQuery);
$expenseStmt->bindParam(':startDate', $startDate);
$expenseStmt->execute();
$totalExpenses = $expenseStmt->fetch(PDO::FETCH_ASSOC)['total_expenses'] ?? 0;

// Get total stock budget
$budgetQuery = "SELECT Total_Stock_Budget FROM tbl_stocks WHERE Stock_ID = 1";
$budgetStmt = $conn->prepare($budgetQuery);
$budgetStmt->execute();
$totalStockBudget = $budgetStmt->fetch(PDO::FETCH_ASSOC)['Total_Stock_Budget'] ?? 1; // Avoid divide by zero

// Calculate percentages
$expensesPercentage = ($totalExpenses / $totalStockBudget) * 100;
$remainingBudgetPercentage = 100 - $expensesPercentage;
$itemsInStockPercentage = min(($totalItems / 100) * 100, 100); // Cap at 100%

// Format values
$expensesPercentage = round($expensesPercentage, 2);
$remainingBudgetPercentage = round($remainingBudgetPercentage, 2);
$itemsInStockPercentage = round($itemsInStockPercentage, 2);

// Output as JSON
echo json_encode([
    'remaining_budget' => $remainingBudgetPercentage,
    'expenses' => $expensesPercentage,
    'items_in_stock' => $itemsInStockPercentage,
]);
