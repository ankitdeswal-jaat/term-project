<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "thakran_electronics_db";

$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);

if ($conn->connect_errno) {
    die("Could not connect to the database: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
