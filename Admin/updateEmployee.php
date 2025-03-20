<?php
include("../Login/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST, 'Employee_Name', FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, 'Employee_Role', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'Employee_Status', FILTER_SANITIZE_STRING);

    if ($id && $name && $role && $status) {
        try {
            $stmt = $conn->prepare("UPDATE employees SET Employee_Name = :name, Employee_Role = :role, Employee_Status = :status WHERE Employee_ID = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update employee']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>