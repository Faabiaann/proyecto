<?php
$host = 'ec2-54-234-95-127.compute-1.amazonaws.com';
$user = 'root';
$password = '123456';
$dbname = 'proyecto';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
