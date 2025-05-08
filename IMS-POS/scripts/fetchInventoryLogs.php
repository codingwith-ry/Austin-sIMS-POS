<?php
include '../../Login/database.php';

try {
    // Query to fetch inventory logs
    $query = "
        SELECT 
            il.inventoryLogs_ID,
            e.Employee_Name AS Employee_Name,
            il.Previous_Sum AS Previous_Sum,
            il.Amount_Added AS Amount_Added,
            (il.Previous_Sum + il.Amount_Added) AS Updated_Budget,
            il.Date_Time AS Date_Time
        FROM tbl_inventorylogs il
        LEFT JOIN employees e ON il.Employee_ID = e.Employee_ID
        ORDER BY il.Date_Time DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $logs]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>