
<?php
include '../Login/database.php';

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
WHERE r.Record_ItemPurchaseDate = CURDATE()
GROUP BY i.Item_ID
ORDER BY i.Item_Name ASC
";

$result = $conn->query($query);
$data = [];

if ($result->rowCount() > 0) {
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $row;
}
}

echo json_encode([
"data" => $data
]);

?>