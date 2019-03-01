<?php
$server = "localhost";
$username = "root";
$pass = "";
$table = "authority_records";
$connection = new mysqli($server, $username, $pass, $table);


if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}