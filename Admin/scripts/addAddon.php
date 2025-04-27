<?php
require_once('../../Login/database.php');

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = json_decode(file_get_contents('php://input'), true);


    if (isset($input['addonName'], $input['addonPrice'], $input['menuClassName'])) {
        $addonName = trim($input['addonName']);
        $addonPrice = floatval($input['addonPrice']);
        $menuClassName = $input['menuClassName'];

        // Check if required fields are not empty
        if (empty($addonName) || empty($menuClassName) || $addonPrice <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid input. Please provide valid data.']);
            exit;
        }

        try {
            // Fetch the menuID based on the menuClassName
            $menuQuery = "SELECT menuID FROM tbl_menuclass WHERE menuName = :menuClassName";
            $menuStmt = $conn->prepare($menuQuery);
            $menuStmt->bindParam(':menuClassName', $menuClassName, PDO::PARAM_STR);
            $menuStmt->execute();

            $menu = $menuStmt->fetch(PDO::FETCH_ASSOC);

            if (!$menu) {
                echo json_encode(['success' => false, 'message' => 'Menu class not found.']);
                exit;
            }

           

            $menuID = $menu['menuID'];

            $addonQuery = "SELECT menuID FROM tbl_addons WHERE addonName = :addonName AND menuID = :menuID";
            $addonStmt = $conn->prepare($addonQuery);
            $addonStmt->bindParam(':addonName', $addonName, PDO::PARAM_STR);
            $addonStmt->bindParam(':menuID', $menuID, PDO::PARAM_STR);
            $addonStmt->execute();
            $existingAddon = $addonStmt->fetch(PDO::FETCH_ASSOC);
            if ($existingAddon) {
                echo json_encode(['success' => false, 'message' => 'Add-on already exists for this menu.']);
                exit;
            }


            // Insert the new add-on into the database
            $query = "INSERT INTO tbl_addons (addonName, addonPrice, menuID) VALUES (:addonName, :addonPrice, :menuID)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':addonName', $addonName, PDO::PARAM_STR);
            $stmt->bindParam(':addonPrice', $addonPrice, PDO::PARAM_STR);
            $stmt->bindParam(':menuID', $menuID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Add-on added successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add the add-on.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
