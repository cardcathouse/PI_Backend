<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(403);
    echo json_encode(["error" => "Usuario no ha iniciado sesión"]);
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

$userId = $_SESSION["user_id"];
$sqlUser = "SELECT name FROM users WHERE user_id='$userId'";
$resultUser = $conn->query($sqlUser);

if ($resultUser->num_rows == 1) {
    $rowUser = $resultUser->fetch_assoc();
    $userName = $rowUser["name"];
} else {
    $userName = "";
}

$userId = $_SESSION["user_id"];
$sql = "SELECT * FROM uploads WHERE user_id='$userId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $uploads = [];
    while ($row = $result->fetch_assoc()) {
        $uploads[] = [
            "id" => $row["upload_id"],
            "path" => "../" . $row["path"],
            "userName" => $userName
        ];
    }
    echo json_encode($uploads);
} else {
    http_response_code(404);
    echo json_encode(["error" => "No se encontraron subidas para el usuario conectado"]);
}

$conn->close();
