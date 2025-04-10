<?php
include '../Login/database.php';

// Get the startDate from the AJAX request
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d'); // Default to today's date if not set

$query = "
SELECT 
    i.Item_ID,
    i.Item_Name, 
    i.Item_Image, 
    i.Item_Category, 
    ic.Category_Name, 
    COALESCE(um.Unit_Acronym, '') as Unit_Acronym, 
    r.Record_ItemQuantity, 
    r.Record_ItemVolume
FROM tbl_item i
JOIN tbl_itemcategories ic ON i.Item_Category = ic.Category_ID
JOIN tbl_record r ON i.Item_ID = r.Item_ID
LEFT JOIN tbl_unitofmeasurments um ON i.Unit_ID = um.Unit_ID
WHERE r.Record_ItemPurchaseDate = :startDate
GROUP BY i.Item_ID
ORDER BY i.Item_Name ASC
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
$stmt->execute();

$data = [];

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
}

echo json_encode([
    "data" => $data
]);
