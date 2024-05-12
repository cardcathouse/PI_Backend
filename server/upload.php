<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(403);
    echo json_encode(["error" => "Usuario no ha iniciado sesión"]);
    exit();
}

if (!isset($_FILES["image"])) {
    http_response_code(400);
    echo json_encode(["error" => "No se ha subido ningún archivo"]);
    exit();
}

$allowedTypes = ["image/jpeg", "image/png", "image/gif"];
if (!in_array($_FILES["image"]["type"], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(["error" => "Solo se permiten archivos JPEG, PNG y GIF"]);
    exit();
}

$uploadDir = "../images/";
$uploadPath = $uploadDir . basename($_FILES["image"]["name"]);
if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath)) {
    $servername = "localhost";
    $username = "mario";
    $password = "mario123";
    $dbname = "image_gallery_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["error" => "Error al conectar con la base de datos"]);
        exit();
    }

    $uploadId = rand(100, 999);
    $userId = $_SESSION["user_id"];
    $imageName = $_FILES["image"]["name"];
    $imagePath = "images/" . $imageName;

    $sql = "INSERT INTO uploads (upload_id, user_id, path) VALUES ('$uploadId', '$userId', '$imagePath')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al insertar la subida en la base de datos"]);
    }

    $conn->close();
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al mover el archivo subido"]);
}
?>
