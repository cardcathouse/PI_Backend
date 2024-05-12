<?php
$servername = "localhost";
$username = "";
$password = "";
$dbname = "image_gallery_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $userId = rand(100, 999);

    $sql = "INSERT INTO users (user_id, name, email, password, isAdmin) VALUES ('$userId', '$name', '$email', '$password', false)";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../client/gallery.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
