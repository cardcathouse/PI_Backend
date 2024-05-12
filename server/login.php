<?php
session_start();

$servername = "localhost";
$username = "";
$password = "";
$dbname = "image_gallery_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION["user_id"] = $row["user_id"];
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;

        $row = $result->fetch_assoc();
        if ($row["isAdmin"]) {
            header("Location: ../client/adminPanel.html");
            exit();
        } else {
            header("Location: ../client/gallery.html");
            exit();
        }
    } else {
        echo "Inicio de sesión fallido. Usuario o contraseña inválidos.";
    }
}

$conn->close();
