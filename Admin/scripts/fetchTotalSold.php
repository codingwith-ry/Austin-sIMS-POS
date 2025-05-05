<?php

require_once '/xampp/htdocs/Austin-sIMS-POS/Login/database.php';

try {
    $query = "SELECT SUM(productQuantity) AS totalSold FROM tbl_orderitems";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo number_format($result['totalSold'] ?? 0);
} catch (PDOException $e) {
    echo "0";
}
