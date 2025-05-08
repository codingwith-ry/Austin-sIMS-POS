<?php
include '../Login/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['budget'])) {
    $budgetToAdd = (float) $_POST['budget']; // Allow decimals by casting to float

    if ($budgetToAdd > 0) {
        try {
            // Check if Stock_ID = 1 exists
            $checkQuery = "SELECT COUNT(*) AS count FROM tbl_stocks WHERE Stock_ID = 1";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->execute();
            $rowExists = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;

            if ($rowExists) {
                // Update the existing row
                $updateQuery = "UPDATE tbl_stocks SET Total_Stock_Budget = Total_Stock_Budget + :budget WHERE Stock_ID = 1";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bindParam(':budget', $budgetToAdd);
                $stmt->execute();
            } else {
                // Insert a new row with Stock_ID = 1 and the budget
                $insertQuery = "INSERT INTO tbl_stocks (Stock_ID, Total_Stock_Budget) VALUES (1, :budget)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bindParam(':budget', $budgetToAdd);
                $stmt->execute();
            }

            // Fetch the updated Total_Stock_Budget for Stock_ID = 1
            $fetchBudgetQuery = "SELECT Total_Stock_Budget FROM tbl_stocks WHERE Stock_ID = 1";
            $fetchBudgetStmt = $conn->prepare($fetchBudgetQuery);
            $fetchBudgetStmt->execute();
            $totalStockBudget = $fetchBudgetStmt->fetch(PDO::FETCH_ASSOC)['Total_Stock_Budget'];

            echo json_encode(['success' => true, 'new_budget' => $totalStockBudget]);
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