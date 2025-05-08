<?php
include '../../Login/database.php';

try {
    // Check if a budget exists
    $checkQuery = "SELECT COUNT(*) AS count FROM tbl_stocks WHERE Stock_ID = 1";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->execute();
    $rowExists = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;

    if (!$rowExists) {
        // Insert a default budget of 0 if no row exists
        $insertQuery = "INSERT INTO tbl_stocks (Stock_ID, Total_Stock_Budget) VALUES (1, 0)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->execute();
    }

    // Fetch the total stock budget
    $stmtBudget = $conn->query("SELECT Total_Stock_Budget AS totalBudget FROM tbl_stocks WHERE Stock_ID = 1");
    $budgetResult = $stmtBudget->fetch(PDO::FETCH_ASSOC);
    $totalBudget = $budgetResult['totalBudget'] ?? 0;

    echo json_encode([
        'success' => true,
        'remainingBudget' => $totalBudget,
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>