<?php
include '../../Login/database.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Fetch the total number of employees
        $stmt = $conn->query("SELECT COUNT(*) AS total FROM employees");
        $totalEmployees = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch the number of POS employees
        $stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM employees WHERE Employee_Role = :role");
        $stmt2->execute(['role' => 'POS Staff Management']);
        $posEmployees = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch the number of IMS employees
        $stmt3 = $conn->prepare("SELECT COUNT(*) AS total FROM employees WHERE Employee_Role = :role");
        $stmt3->execute(['role' => 'Inventory Staff Management']);
        $imsEmployees = $stmt3->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch the number of Admin employees
        $stmt4 = $conn->prepare("SELECT COUNT(*) AS total FROM employees WHERE Employee_Role = :role");
        $stmt4->execute(['role' => 'Administrator']);
        $adminEmployees = $stmt4->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch the number of Regular Employees (new role)
        $stmt5 = $conn->prepare("SELECT COUNT(*) AS total FROM employees WHERE Employee_Role = :role");
        $stmt5->execute(['role' => 'Employee']);
        $regularEmployees = $stmt5->fetch(PDO::FETCH_ASSOC)['total'];

        // Return the data in JSON format
        echo json_encode([
            'success' => true,
            'totalEmployees' => (int)$totalEmployees,
            'posEmployees' => (int)$posEmployees,
            'imsEmployees' => (int)$imsEmployees,
            'adminEmployees' => (int)$adminEmployees,
            'regularEmployees' => (int)$regularEmployees // added here
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
