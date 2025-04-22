<?php
include '../Login/database.php';

$itemID = $_GET['itemID']; // Assuming item ID is passed via GET or another method
$query = "
    SELECT 
        i.Item_ID, i.Item_Name, i.Item_Image, 
        c.Category_ID, c.Category_Name, c.Category_Icon, 
        u.Unit_ID, u.Unit_Name, u.Unit_Acronym
    FROM 
        tbl_item i
    JOIN 
        tbl_itemcategories c ON i.Item_Category = c.Category_ID
    LEFT JOIN 
        tbl_unitofmeasurments u ON i.Unit_ID = u.Unit_ID
    WHERE 
        i.Item_ID = :itemID
";
$stmt = $pdo->prepare($query);
$stmt->execute([':itemID' => $itemID]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
