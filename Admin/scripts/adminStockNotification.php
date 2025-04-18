<?php
include '/xampp/htdocs/Austin-sIMS-POS/Login/database.php';

// Get the current date
$currentDate = date('Y-m-d'); // Get current date in 'YYYY-MM-DD' format

// Fetch order details for today's orders
$orderQuery = "
    SELECT 
        o.orderID,
        o.orderNumber,
        o.orderDate,
        o.orderTime,
        o.orderClass,
        o.orderStatus,
        o.customerName,
        o.totalAmount,
        o.amountPaid,
        o.paymentMode,
        o.additionalNotes
    FROM tbl_orders o
    WHERE o.orderDate = :currentDate
";

$stmt = $conn->prepare($orderQuery);
$stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR); // Ensure correct binding of the parameter
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
