<?php
header('Content-type: text/html; charset=utf-8');
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
$servername = "localhost";
$username = "root";
$password = "";
$database = "welovetest";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Csatlakozási hibaüzenet: " . $conn->connect_error);
}
