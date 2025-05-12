<?php
include '../../Login/database.php';

$query = "
    SELECT 
        il.Employee_ID,
        e.Employee_Name,
        il.Previous_Sum,
        il.Amount_Added,
        il.Updated_Sum AS Updated_Budget, -- Fetch Updated_Sum as Updated_Budget
        il.Date_Time
    FROM tbl_inventorylogs il
    LEFT JOIN employees e ON il.Employee_ID = e.Employee_ID
    ORDER BY il.Date_Time DESC -- Ensures most recent logs are fetched first
";

$stmt = $conn->prepare($query);
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['data' => $logs]);