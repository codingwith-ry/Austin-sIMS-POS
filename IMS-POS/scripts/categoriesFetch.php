<?php
include '../../Login/database.php';
header('Content-Type: application/json');

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
    ORDER BY 
        ic.Category_Name;
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by category
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
