<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: /Austin-sIMS-POS/index.php");
?>