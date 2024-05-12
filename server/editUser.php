<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit();
}

$servername = "localhost";
$username = "";
$password = "";
$dbname = "image_gallery_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error al conectar con la base de datos"]);
    exit();
}

$user_id = $_POST["user_id"];
$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$is_admin = isset($_POST["is_admin"]) ? 1 : 0;

$sql = "UPDATE users SET name='$name', email='$email', password='$password', isAdmin=$is_admin WHERE user_id='$user_id'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al actualizar el usuario"]);
}

$conn->close();
