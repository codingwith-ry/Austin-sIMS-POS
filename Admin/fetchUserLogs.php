<?php
include '../Login/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logsPerPage = 10;
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $offset = ($page - 1) * $logsPerPage;
    $logDate = isset($_POST['logDate']) ? $_POST['logDate'] : null;

    try {
        // Base query
        $query = "
            SELECT logID, logEmail, logRole, logContent, logDate
            FROM tbl_userlogs
        ";

        // Add date filter if logDate is provided
        if ($logDate) {
            $query .= " WHERE logDate = :logDate";
        }

        // Add ordering and pagination
        $query .= " ORDER BY logDate DESC, logID DESC LIMIT :offset, :logsPerPage";

        $stmt = $conn->prepare($query);

        // Bind parameters
        if ($logDate) {
            $stmt->bindValue(':logDate', $logDate, PDO::PARAM_STR);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':logsPerPage', $logsPerPage, PDO::PARAM_INT);
        $stmt->execute();

        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch total number of logs for pagination
        $countQuery = "SELECT COUNT(*) AS totalLogs FROM tbl_userlogs";
        if ($logDate) {
            $countQuery .= " WHERE logDate = :logDate";
        }
        $countStmt = $conn->prepare($countQuery);
        if ($logDate) {
            $countStmt->bindValue(':logDate', $logDate, PDO::PARAM_STR);
        }
        $countStmt->execute();
        $totalLogs = $countStmt->fetch(PDO::FETCH_ASSOC)['totalLogs'];

        echo json_encode(['success' => true, 'logs' => $logs, 'totalLogs' => $totalLogs]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>