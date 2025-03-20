<?php 
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = '';
    $db_name = "austingastropub";
    $db_port = 3306;
    $chrs = "utf8mb4";
    $attrs = "mysql:host=$db_server;dbname=$db_name;charset=$chrs;port=$db_port";
    $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
            ];

            try {
                $conn = new PDO($attrs, $db_user, $db_pass, $opts);
                // echo "DB Connected Successfully (PDO) <br>";
            } catch (PDOException $e) {
                echo "Could not connect: " . $e->getMessage() . "<br>";
            }
?>