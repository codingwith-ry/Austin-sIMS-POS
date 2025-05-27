<?php
require_once '../../Login/database.php'; // Include your database connection file

try {
    $orderDate = isset($_GET['date']) ? $_GET['date'] : null;
    // Query to fetch product sales data
    $query = "
        SELECT 
            p.productName AS product,
            cat.categoryName as categoryName, 
            menu.menuName as menuName,
            ord.salesOrderNumber AS  salesOrderNumber,
            p.productPrice AS price, 
            SUM(oi.productQuantity) AS quantity, 
            SUM(oi.productTotal) AS total_sales,
				ord.orderDate AS  orderDate
        FROM tbl_orderitems oi
        JOIN tbl_menu p ON oi.productID = p.productID
        JOIN tbl_categories cat ON p.categoryID = cat.categoryID
        JOIN tbl_menuclass menu ON p.menuID = menu.menuID
        JOIN tbl_orders ord ON oi.salesOrderNumber = ord.salesOrderNumber
        WHERE ord.orderDate = $orderDate
        GROUP BY p.productName, p.productPrice, cat.categoryName, menu.menuName
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