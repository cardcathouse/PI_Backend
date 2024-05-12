<?php

$servername = "localhost";

# Llenar con tu propia información
$username = "";
$password = "";

$dbname = "image_gallery_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$is_admin = isset($_POST["isAdmin"]) ? 1 : 0;

$sql = "INSERT INTO users (name, email, password, isAdmin) VALUES ('$name', '$email', '$password', $is_admin)";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "No se pudo añadir al usuario."]);
}

$conn->close();
