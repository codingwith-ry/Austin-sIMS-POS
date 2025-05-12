<?php
require_once '../../Login/database.php'; // Include your database connection file

try {
    // Query to fetch product sales data
    $query = "
        SELECT 
            p.productName AS product, 
            p.productPrice AS price, 
            SUM(oi.productQuantity) AS quantity, 
            SUM(oi.productTotal) AS total_sales 
        FROM tbl_orderitems oi
        JOIN tbl_menu p ON oi.productID = p.productID
        GROUP BY p.productName, p.productPrice
        ORDER BY total_sales DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch all rows as an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode(['data' => $data]);

} catch (PDOException $e) {
    // Handle any errors
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>