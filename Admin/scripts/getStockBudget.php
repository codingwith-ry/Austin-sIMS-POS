<?php
include '../../Login/database.php'; // adjust path

try {
    // Get the total stock budget
    $stmtBudget = $conn->query("SELECT SUM(Total_Stock_Budget) AS totalBudget FROM tbl_stocks");
    $budgetResult = $stmtBudget->fetch(PDO::FETCH_ASSOC);
    $totalBudget = $budgetResult['totalBudget'] ?? 0;

    // Get the total expenses
    $stmtExpenses = $conn->query("SELECT SUM(Record_ItemPrice) AS totalExpenses FROM tbl_record");
    $expensesResult = $stmtExpenses->fetch(PDO::FETCH_ASSOC);
    $totalExpenses = $expensesResult['totalExpenses'] ?? 0;

    // Calculate remaining budget
    $remainingBudget = $totalBudget - $totalExpenses;
    if ($remainingBudget < 0) {
        $remainingBudget = 0; // prevent negative remaining budget
    }

    echo json_encode([
        'success' => true,
        'remainingBudget' => $remainingBudget,
        'totalBudget' => $totalBudget,
        'totalExpenses' => $totalExpenses
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
