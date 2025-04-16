<?php
include '../Login/database.php';

// Get the startDate and period from the AJAX request
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d'); // Default to today's date if not set
$period = isset($_GET['period']) ? $_GET['period'] : 'weekly'; // Default to 'weekly' if not set

// Calculate the date range based on the selected period
switch ($period) {
    case 'weekly':
        // Calculate the start of the week (last Monday)
        $startDate = date('Y-m-d', strtotime('last Monday', strtotime($startDate)));
        $endDate = date('Y-m-d', strtotime('next Sunday', strtotime($startDate)));
        break;
    case 'monthly':
        // Set the date range to the start and end of the month
        $startDate = date('Y-m-01', strtotime($startDate));
        $endDate = date('Y-m-t', strtotime($startDate));
        break;
    case 'yearly':
        // Set the date range to the start and end of the year
        $startDate = date('Y-01-01', strtotime($startDate));
        $endDate = date('Y-12-31', strtotime($startDate));
        break;
    default:
        // Default to weekly if the period is invalid
        $startDate = date('Y-m-d', strtotime('last Monday', strtotime($startDate)));
        $endDate = date('Y-m-d', strtotime('next Sunday', strtotime($startDate)));
        break;
}

// SQL query to fetch data within the calculated date range
$query = "
SELECT 
    i.Item_ID,
    i.Item_Name, 
    i.Item_Image, 
    i.Item_Category, 
    ic.Category_Name, 
    COALESCE(um.Unit_Acronym, '') as Unit_Acronym, 
    r.Record_ItemQuantity, 
    r.Record_ItemVolume,
    e.Employee_Name AS Record_EmployeeAssigned
FROM tbl_item i
JOIN tbl_itemcategories ic ON i.Item_Category = ic.Category_ID
JOIN tbl_record r ON i.Item_ID = r.Item_ID
LEFT JOIN tbl_unitofmeasurments um ON i.Unit_ID = um.Unit_ID
LEFT JOIN employees e ON r.Record_EmployeeAssigned = e.Employee_ID
WHERE r.Record_ItemPurchaseDate BETWEEN :startDate AND :endDate
GROUP BY i.Item_ID
ORDER BY i.Item_Name ASC
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
$stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
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
