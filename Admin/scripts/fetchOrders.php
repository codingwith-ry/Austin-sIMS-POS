<?php
require_once '/xampp/htdocs/Austin-sIMS-POS/Login/database.php';

try {
    $query = "SELECT COUNT(*) AS totalOrders FROM tbl_orders";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo number_format($result['totalOrders'] ?? 0);
} catch (PDOException $e) {
    echo "0";
}
