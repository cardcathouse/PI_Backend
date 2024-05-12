<?php
if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    http_response_code(405);
    echo json_encode(["error" => "El método solicitado es incorrecto."]);
    exit();
}

if (!isset($_GET["user_id"])) {
    http_response_code(400);
    echo json_encode(["error" => "No se dió el ID de usuario."]);
    exit();
}

$servername = "localhost";
$username = "";
$password = "";
$dbname = "image_gallery_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Falló conexión a BD"]);
    exit();
}

$user_id = $_GET["user_id"];
$sql = "DELETE FROM users WHERE user_id='$user_id'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "No se pudo eliminar usuario"]);
}

$conn->close();
?>
