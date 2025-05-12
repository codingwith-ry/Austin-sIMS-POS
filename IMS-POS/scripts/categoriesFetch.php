<?php
include '../../Login/database.php';
header('Content-Type: application/json');

$period = $_GET['period'] ?? 'weekly';
$startDate = $_GET['startDate'] ?? date('Y-m-d'); // Default to today if not set

try {
    $dateFilter = '';
    $start = new DateTime($startDate);
    $end = clone $start;

    switch ($period) {
        case 'monthly':
            $month = $start->format('m');
            $year = $start->format('Y');
            $dateCondition = "WHERE MONTH(r.Record_ItemPurchaseDate) = $month AND YEAR(r.Record_ItemPurchaseDate) = $year";
            break;
        case 'yearly':
            $year = $start->format('Y');
            $dateCondition = "WHERE YEAR(r.Record_ItemPurchaseDate) = $year";
            break;
        case 'weekly':
        default:
            // Calculate last 7 days from today
            $start->modify('-6 days'); // Start from 6 days before today
            $dateCondition = "WHERE r.Record_ItemPurchaseDate BETWEEN '" . $start->format('Y-m-d') . "' AND '" . $end->format('Y-m-d') . "'";
            break;
    }

    $startFormatted = $start->format('Y-m-d');
    $endFormatted = $end->format('Y-m-d');

    $sql = "
        SELECT 
            ic.Category_Name,
            r.Record_TotalPrice
        FROM 
            tbl_record r
        JOIN 
            tbl_item i ON r.Item_ID = i.Item_ID
        JOIN 
            tbl_itemcategories ic ON i.Item_Category = ic.Category_ID
        $dateCondition
        ORDER BY 
            ic.Category_Name;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $groupedData = [];
    foreach ($purchases as $purchase) {
        $category = $purchase['Category_Name'];
        if (!isset($groupedData[$category])) {
            $groupedData[$category] = 0;
        }
        $groupedData[$category] += $purchase['Record_TotalPrice'];
    }

    echo json_encode([
        'categories' => array_keys($groupedData),
        'totals' => array_values($groupedData)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
