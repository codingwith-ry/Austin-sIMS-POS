<?php
include '../../Login/database.php'; // Update path as needed

try {
    $stmt = $conn->prepare("
        SELECT 
            c.categoryName,
            SUM(oi.productQuantity) AS totalOrders,
            SUM(oi.productTotal) AS totalSales
        FROM tbl_orderitems oi
        JOIN tbl_menu m ON m.productID = oi.productID
        JOIN tbl_orders o ON o.orderID = oi.orderID
        JOIN tbl_categories c ON c.categoryID = m.categoryID
        WHERE o.orderStatus = 'DONE'
        GROUP BY c.categoryName
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize categories
    $categoryData = [
        'Food' => 0,
        'Drinks' => 0,
        'Others' => 0
    ];

    $totalSales = 0; // To store the sum of all productTotal values

    foreach ($results as $row) {
        $categoryName = $row['categoryName'];
        $totalOrders = $row['totalOrders'];
        $categorySales = $row['totalSales'];

        if (in_array($categoryName, ['Beef', 'Chicken', 'Pork', 'Seafoods', 'Rice and Noodles', 'Vegetables', 'Dessert'])) {
            $categoryData['Food'] += $totalOrders;
        } elseif (in_array($categoryName, ['Hot', 'Iced', 'Tea and Refresher', 'Slushies', 'Signature Drinks', 'Non-Coffee', 'Iced Blended Coffee', 'Signature Cocktails', 'Classic Cocktails', 'Shooters', 'Beers', 'Premium Bottles', 'Drinks'])) {
            $categoryData['Drinks'] += $totalOrders;
        } else {
            $categoryData['Others'] += $totalOrders;
        }

        $totalSales += $categorySales;
    }

    echo json_encode([
        "success" => true,
        "categoryData" => $categoryData,
        "totalSales" => $totalSales
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
