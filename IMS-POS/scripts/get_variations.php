<?php
// get_variations.php
require_once('../../Login/database.php');// Ensure this connects to your DB

// Establish database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    $request = json_decode($data, true);

    // Fetch variations from the database
    if(isset($request['productID'])) {
        $productID = $request['productID'];

        $query = "
        SELECT *
        FROM tbl_variations
        WHERE productID = ?
        ";

        $stmt = $conn->prepare($query);
        $stmt->execute([$productID]);

        $variations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($variations);
    } else {
        echo json_encode(["error" => "No productID provided"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}  
