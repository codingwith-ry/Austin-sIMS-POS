<?php
// filepath: d:\XAMPP3\htdocs\Austin-sIMS-POS\IMS-POS\updateBudget.php
include '../Login/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['budget'])) {
    $budgetToAdd = (float) $_POST['budget']; // Allow decimals by casting to float

    if ($budgetToAdd > 0) {
        try {
            // Update the Total_Stock_Budget for Stock_ID = 1
            $updateQuery = "UPDATE tbl_stocks SET Total_Stock_Budget = Total_Stock_Budget + :budget WHERE Stock_ID = 1";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':budget', $budgetToAdd);

            if ($stmt->execute()) {
                // Fetch the updated Total_Stock_Budget for Stock_ID = 1
                $fetchBudgetQuery = "SELECT Total_Stock_Budget FROM tbl_stocks WHERE Stock_ID = 1";
                $fetchBudgetStmt = $conn->prepare($fetchBudgetQuery);
                $fetchBudgetStmt->execute();
                $totalStockBudget = $fetchBudgetStmt->fetch(PDO::FETCH_ASSOC)['Total_Stock_Budget'];

                // Fetch the Total Expenses
                $fetchExpensesQuery = "SELECT SUM(Record_ItemPrice) AS total_expenses FROM tbl_record";
                $fetchExpensesStmt = $conn->prepare($fetchExpensesQuery);
                $fetchExpensesStmt->execute();
                $totalExpenses = $fetchExpensesStmt->fetch(PDO::FETCH_ASSOC)['total_expenses'];

                // Calculate the adjusted stock budget
                $adjustedStockBudget = $totalStockBudget - $totalExpenses;

                // Format the adjusted budget with two decimal places
                $formattedBudget = number_format((float) $adjustedStockBudget, 2);

                echo json_encode(['success' => true, 'new_budget' => $formattedBudget]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update the budget.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid budget amount.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>