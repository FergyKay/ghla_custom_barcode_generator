<?php
include "dbcon.php";
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../");
}
if (isset($_GET['key'])) {
    $k = $_GET['key'];
    $s = "UPDATE record SET print_status = 'green' WHERE c_key = '$k'";
    $q = $connection->query($s);
    header("Location: index");
}