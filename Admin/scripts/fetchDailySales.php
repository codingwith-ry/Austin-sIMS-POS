<?php
require_once '../../Login/database.php'; // Include your database connection file

// Get the date from the request (default to today's date if not provided)
$date = "2025-05-03";

// Initialize an array to store the results
$response = [
    'totalSales' => 0,
    'discounts' => 0,
    'netSales' => 0,
    'refunds' => 0,
    'totalTransactions' => 0,
    'averageTransactionValue' => 0,
    'totalProductsSold' => 0,
    'actualCashAmount' => 0, // Add actualCashAmount to the response
    'cashlast7days' => [],
    'paymentBreakdown' => [
        'Cash' => 0,
        'GCash' => 0,
        'PayMaya' => 0
    ]
];

try {
    // Total Sales (Gross Revenue)
    $query = "SELECT SUM(totalAmount) AS totalSales FROM tbl_orders WHERE DATE(orderDate) = :date AND orderStatus != 'CANCELLED'";
    $stmt = $conn->prepare($query);
    $stmt->execute(['date' => $date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['totalSales'] = $row['totalSales'] ?? 0;

    // Discounts
    $query = "SELECT SUM(subTotal - totalAmount) AS discounts FROM tbl_orders WHERE DATE(orderDate) = :date AND orderStatus != 'CANCELLED'";
    $stmt = $conn->prepare($query);
    $stmt->execute(['date' => $date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['discounts'] = $row['discounts'] ?? 0;

    // Net Sales
    $response['netSales'] = number_format(($response['totalSales'] - $response['discounts']), 2);

    // Refunds/Cancellations
    $query = "SELECT SUM(totalAmount) AS refunds FROM tbl_orders WHERE DATE(orderDate) = :date AND orderStatus = 'CANCELLED'";
    $stmt = $conn->prepare($query);
    $stmt->execute(['date' => $date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['refunds'] = $row['refunds'] ?? 0;

    // Total Transactions (Orders)
    $query = "SELECT COUNT(orderID) AS totalTransactions FROM tbl_orders WHERE DATE(orderDate) = :date";
    $stmt = $conn->prepare($query);
    $stmt->execute(['date' => $date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['totalTransactions'] = $row['totalTransactions'] ?? 0;

    // Average Transaction Value
    if ($response['totalTransactions'] > 0) {
        $average = $response['totalSales'] / $response['totalTransactions'];
        $formattedAverage = number_format($average, 2);
        $response['averageTransactionValue'] = $formattedAverage;
    }

    // Total Products Sold
    $query = "
        SELECT SUM(productQuantity) AS totalProductsSold 
        FROM tbl_orderitems 
        WHERE salesOrderNumber IN (
            SELECT salesOrderNumber 
            FROM tbl_orders 
            WHERE DATE(orderDate) = :date
        )
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute(['date' => $date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['totalProductsSold'] = $row['totalProductsSold'] ?? 0;

    // Actual Cash Amount
    $query = "SELECT SUM(cashAmountValue) AS actualCashAmount FROM tbl_actualCashAmount WHERE DATE(cashAmountDate) = :date";
    $stmt = $conn->prepare($query);
    $stmt->execute(['date' => $date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['actualCashAmount'] = $row['actualCashAmount'] ?? 0;

    // Fetch data for the last 7 days
    $query = "
        SELECT 
            DATE(orderDate) AS date,
            SUM(totalAmount) AS expectedCashAmount,
            (
                SELECT SUM(cashAmountValue) 
                FROM tbl_actualCashAmount 
                WHERE DATE(cashAmountDate) = DATE(orderDate)
            ) AS actualCashAmount
        FROM tbl_orders
        WHERE DATE(orderDate) BETWEEN DATE_SUB(:lastDate, INTERVAL 6 DAY) AND :currentDate
        GROUP BY DATE(orderDate)
        ORDER BY DATE(orderDate) ASC
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute(['lastDate' => $date,'currentDate' => $date]); // Corrected parameter name

    // Fetch all rows
    $response["cashlast7days"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Payment Breakdown
    $paymentMethods = ['Cash', 'GCash', 'PayMaya'];
    foreach ($paymentMethods as $method) {
        $query = "SELECT SUM(totalAmount) AS total FROM tbl_orders WHERE DATE(orderDate) = :date AND paymentMode = :paymentMode AND orderStatus != 'CANCELLED'";
        $stmt = $conn->prepare($query);
        $stmt->execute(['date' => $date, 'paymentMode' => $method]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $response['paymentBreakdown'][$method] = $row['total'] ?? 0;
    }

    // Return the response as JSON
    echo json_encode($response);

} catch (PDOException $e) {
    // Handle any errors
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>