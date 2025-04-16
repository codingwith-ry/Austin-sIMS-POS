<?php
include '../../Login/database.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Set the current date for the monthly calculations
        $startDate = date('Y-m-01'); // First day of the current month
        $endDate = date('Y-m-t');    // Last day of the current month

        // Sales (Income) from tbl_orderitems
        $stmt = $conn->prepare("
            SELECT 
                MONTH(o.orderDate) AS month,
                SUM(oi.productTotal) AS income
            FROM tbl_orders o
            JOIN tbl_orderitems oi ON o.orderNumber = oi.orderNumber
            WHERE o.orderDate BETWEEN :startDate AND :endDate
            AND o.orderStatus = 'DONE'
            GROUP BY MONTH(o.orderDate)
            ORDER BY MONTH(o.orderDate)
        ");
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();
        $salesResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Expenses from tbl_record (inventory purchases)
        $stmt2 = $conn->prepare("
            SELECT 
                MONTH(r.Record_ItemPurchaseDate) AS month,
                SUM(r.Record_ItemPrice) AS expenses  -- Using Record_ItemPrice only, no quantity involved
            FROM tbl_record r
            WHERE r.Record_ItemPurchaseDate BETWEEN :startDate AND :endDate
            GROUP BY MONTH(r.Record_ItemPurchaseDate)
            ORDER BY MONTH(r.Record_ItemPurchaseDate)
        ");
        $stmt2->bindParam(':startDate', $startDate);
        $stmt2->bindParam(':endDate', $endDate);
        $stmt2->execute();
        $expenseResults = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // Prepare arrays for the monthly data
        $incomeData = array_fill(0, 12, 0);  // Default to 0 for all months
        $profitData = array_fill(0, 12, 0);  // Default to 0 for all months
        $expenseData = array_fill(0, 12, 0); // Default to 0 for all months

        // Populate sales (income) data
        foreach ($salesResults as $row) {
            $incomeData[$row['month'] - 1] = (float)$row['income'];  // Store monthly income
        }

        // Populate expenses data
        foreach ($expenseResults as $row) {
            $expenseData[$row['month'] - 1] = (float)$row['expenses'];  // Store monthly expenses
        }

        // Calculate profit (income - expenses)
        for ($i = 0; $i < 12; $i++) {
            $profitData[$i] = $incomeData[$i] - $expenseData[$i];
        }

        // Return the data in JSON format
        echo json_encode([
            'success' => true,
            'income' => $incomeData,
            'profit' => $profitData,
            'expenses' => $expenseData
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
