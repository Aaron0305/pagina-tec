<?php
    $host = "localhost";
    $user = "root";
    $pswd = "";
    $base = "sipre";

    $mysqli = new mysqli($host, $user, $pswd, $base);
    mysqli_set_charset($mysqli, "utf8");
?>
