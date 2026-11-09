<?php

// =============================================================
// Batch Import XML File data into XAMPP MySQL Database
// =============================================================

$xml_file = __DIR__ . "/students.xml";

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "student_db";

$imported_count = 0;
$updated_count = 0;
$error_msg = "";
$db_connected = false;

if (file_exists($xml_file)) {
    $xml = simplexml_load_file($xml_file);

    $conn = @new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        $error_msg = "Database Connection Failed: " . $conn->connect_error . ". Please make sure MySQL is active in XAMPP and 'student_db' database is created.";
    } else {
        $db_connected = true;

        $sql = "INSERT INTO students (rollNo, name, age, dob, gender, email, phone, course, skills, city, batch_time, address) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                name=VALUES(name), age=VALUES(age), dob=VALUES(dob), gender=VALUES(gender), 
                phone=VALUES(phone), course=VALUES(course), skills=VALUES(skills), city=VALUES(city), 
                batch_time=VALUES(batch_time), address=VALUES(address)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            foreach ($xml->student as $student) {
                $rollNo     = (string)$student->rollNo;
                $name       = (string)$student->name;
                $age        = (int)$student->age;
                $dob        = (string)$student->dob;
                $gender     = (string)$student->gender;
                $email      = (string)$student->email;
                $phone      = (string)$student->phone;
                $course     = (string)$student->course;
                $skills     = (string)$student->skills;
                $city       = (string)$student->city;
                $batch_time = (string)$student->batch_time;
                $address    = (string)$student->address;

                $stmt->bind_param(
                    "ssisssssssss",
                    $rollNo, $name, $age, $dob, $gender, $email, $phone,
                    $course, $skills, $city, $batch_time, $address
                );

                if ($stmt->execute()) {
                    $imported_count++;
                }
            }
            $stmt->close();
        } else {
            $error_msg = "Prepare Statement Failed: " . $conn->error;
        }

        $conn->close();
    }
} else {
    $error_msg = "File students.xml does not exist.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import XML to MySQL Database</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">📥 <strong>XML to MySQL Importer</strong></div>
        <div class="nav-links">
            <a href="index.html" class="nav-btn">➕ Form</a>
            <a href="view_xml.php" class="nav-btn">📄 View XML</a>
            <a href="records.php" class="nav-btn">📋 DB Records</a>
        </div>
    </nav>

    <div class="container" style="text-align: center;">

        <header class="form-header">
            <h1>XML &rarr; MySQL Database Sync</h1>
            <p class="subtitle">Bulk parsed <code>students.xml</code> and synchronized records into MySQL <code>student_db</code></p>
        </header>

        <?php if ($db_connected && empty($error_msg)): ?>
            <div class="alert-box alert-success" style="padding: 25px; margin: 25px 0;">
                <h2 style="margin: 0 0 10px 0;">🎉 Batch Import Successful!</h2>
                <p style="margin: 0; font-size: 16px;">
                    Successfully processed and synchronized <strong><?php echo $imported_count; ?></strong> student record(s) from <code>students.xml</code> into MySQL database.
                </p>
            </div>
        <?php else: ?>
            <div class="alert-box alert-error" style="padding: 25px; margin: 25px 0;">
                <h2 style="margin: 0 0 10px 0;">❌ Import Issue</h2>
                <p style="margin: 0;"><?php echo htmlspecialchars($error_msg); ?></p>
            </div>
        <?php endif; ?>

        <div class="button-group" style="justify-content: center;">
            <a href="records.php" class="btn btn-submit">📋 View All Database Records</a>
            <a href="view_xml.php" class="btn btn-secondary">📄 View XML Source</a>
            <a href="index.html" class="btn btn-reset" style="background:#2563eb;">➕ Add New Student</a>
        </div>

    </div>

</body>
</html>
