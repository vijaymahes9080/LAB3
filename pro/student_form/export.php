<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "student_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$filename = "students_export_" . date("Y-m-d_His") . ".csv";

header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");

$output = fopen("php://output", "w");

// CSV Header
fputcsv($output, [
    "ID", "Application ID", "Name", "Age", "DOB", "Gender", 
    "Email", "Phone", "Course", "Electives", "Campus", 
    "Batch Time", "Interview Slot", "Graduation Month", 
    "Skills", "Experience (%)", "Portfolio URL", "Interests", 
    "Address", "Registration Date"
]);

$query = "SELECT * FROM students ORDER BY id DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row["id"],
            $row["app_id"],
            $row["name"],
            $row["age"],
            $row["dob"],
            $row["gender"],
            $row["email"],
            $row["phone"],
            $row["course"],
            $row["electives"],
            $row["city"],
            $row["batch_time"],
            $row["interview_slot"],
            $row["grad_month"],
            $row["skills"],
            $row["experience"],
            $row["portfolio"],
            $row["interests"],
            $row["address"],
            $row["created_at"]
        ]);
    }
}

fclose($output);
$conn->close();
exit();
?>
