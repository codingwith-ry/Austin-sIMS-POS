<?php
include '../../Login/database.php'; // adjust path

try {
    $stmt = $conn->query("SELECT SUM(Record_ItemPrice) AS totalExpenses FROM tbl_record");
    $expenses = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($expenses) {
        echo json_encode([
            'success' => true,
            'totalExpenses' => $expenses['totalExpenses'] ?? 0
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No expenses found.'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

