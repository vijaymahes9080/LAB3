<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "student_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$id = intval($_GET["id"] ?? 0);

if ($id > 0) {
    // Optionally delete uploaded photo
    $query = $conn->prepare("SELECT photo FROM students WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $res = $query->get_result();
    if ($row = $res->fetch_assoc()) {
        if (!empty($row["photo"]) && file_exists(__DIR__ . "/uploads/" . $row["photo"])) {
            unlink(__DIR__ . "/uploads/" . $row["photo"]);
        }
    }
    $query->close();

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: records.php?deleted=1");
exit();
?>
