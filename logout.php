<?php
    require("common.php");
    unset($_SESSION['user']);
    header("Location: index.php");
    die("Redirecting to: index.php");

?>
