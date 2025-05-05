<?php
require_once '/xampp/htdocs/Austin-sIMS-POS/Login/database.php';

try {
    $query = "SELECT SUM(amountPaid) AS totalSales FROM tbl_orders WHERE orderStatus = 'DONE'";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalSales = $result['totalSales'] ?? 0;
    echo number_format($totalSales, 2); // just output the value
} catch (PDOException $e) {
    echo "0.00"; // fallback
}
