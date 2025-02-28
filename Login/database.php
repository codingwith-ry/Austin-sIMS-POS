<?php 
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = '';
    $db_name = "austingastropub";
    $db_port = 3306;

    try {
        $dsn = "mysql:host=$db_server;dbname=$db_name;port=$db_port";
        $conn = new PDO($dsn, $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "DB Connected Successfully (PDO) <br>";
    } catch (PDOException $e) {
        echo "Could not connect: " . $e->getMessage() . "<br>";
    }
?>