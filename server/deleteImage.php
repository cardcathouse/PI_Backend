<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "El método solicitado es incorrecto."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));
if (!$data || !isset($data->id)) {
    http_response_code(400);
    echo json_encode(["error" => "Los datos JSON no son validos."]);
    exit();
}

$servername = "localhost";

# Llenar con tu propia información
$username = "";
$password = "";

$dbname = "image_gallery_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Falló la conección a la base de datos."]);
    exit();
}

$uploadId = $conn->real_escape_string($data->id);
$sql = "DELETE FROM uploads WHERE upload_id='$uploadId'";
if ($conn->query($sql) === TRUE) {
    $imagePath = "../images/" . $uploadId;
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Falló la subida."]);
}

$conn->close();
