<?php
require_once('../../Login/database.php');// Ensure this connects to your DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    $request = json_decode($data, true);

    if (isset($request['productID'])) {
        $productID = $request['productID'];

        // Query to get addons for the selected product
        $query = "
        SELECT a.addonID, a.addonName, a.addonPrice 
        FROM tbl_menutoaddons ma
        INNER JOIN tbl_addons a ON ma.addonID = a.addonID
        WHERE ma.productID = ?
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$productID]);

        $addons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($addons);
    } else {
        echo json_encode(["error" => "No productID provided"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
