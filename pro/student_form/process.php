<?php

// -------------------------------------------------------------
// 1. Database Configuration & Connection
// -------------------------------------------------------------
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "student_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("<div style='color:red; font-family:sans-serif; padding:20px;'><strong>Database Connection Failed:</strong> " . htmlspecialchars($conn->connect_error) . "</div>");
}

// -------------------------------------------------------------
// 2. Process Form Submission
// -------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve and sanitize all inputs
    $app_id           = trim($_POST["app_id"] ?? ("APP-" . date("Y") . "-" . rand(100, 999)));
    $name             = trim($_POST["name"] ?? "");
    $age              = !empty($_POST["age"]) ? intval($_POST["age"]) : null;
    $dob              = !empty($_POST["dob"]) ? $_POST["dob"] : null;
    $gender           = $_POST["gender"] ?? "";
    $email            = trim($_POST["email"] ?? "");
    $password         = $_POST["password"] ?? "";
    $phone            = trim($_POST["phone"] ?? "");
    $portfolio        = trim($_POST["portfolio"] ?? "");
    $course           = $_POST["course"] ?? "";
    $city             = trim($_POST["city"] ?? "");
    $batch_time       = !empty($_POST["batch_time"]) ? $_POST["batch_time"] : null;
    $interview_slot   = !empty($_POST["interview_slot"]) ? str_replace("T", " ", $_POST["interview_slot"]) : null;
    $grad_month       = trim($_POST["grad_month"] ?? "");
    $orientation_week = trim($_POST["orientation_week"] ?? "");
    $experience       = isset($_POST["experience"]) ? intval($_POST["experience"]) : 50;
    $badge_color      = $_POST["badge_color"] ?? "#1a73e8";
    $interests        = trim($_POST["interests"] ?? "");
    $address          = trim($_POST["address"] ?? "");

    // Process Skills Checkboxes
    if (isset($_POST["skills"]) && is_array($_POST["skills"])) {
        $skills = implode(", ", $_POST["skills"]);
    } else {
        $skills = "";
    }

    // Process Electives Multi-Select
    if (isset($_POST["electives"]) && is_array($_POST["electives"])) {
        $electives = implode(", ", $_POST["electives"]);
    } else {
        $electives = "";
    }

    // Handle Photo Upload
    $photo_filename = "";
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . "/uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_ext = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "webp"];
        if (in_array($file_ext, $allowed)) {
            $photo_filename = "photo_" . time() . "_" . uniqid() . "." . $file_ext;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $upload_dir . $photo_filename);
        }
    }

    // Required Field Validations
    if (empty($name) || empty($email) || empty($password) || empty($gender) || empty($course) || empty($phone) || empty($dob) || empty($batch_time)) {
        die("<div style='color:red; font-family:sans-serif; padding:20px;'><strong>Validation Error:</strong> All required fields marked with * must be filled. <br><br><a href='index.html'>Go Back</a></div>");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<div style='color:red; font-family:sans-serif; padding:20px;'><strong>Validation Error:</strong> Invalid Email Address. <br><br><a href='index.html'>Go Back</a></div>");
    }

    // Password Hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert Query
    $sql = "INSERT INTO students 
    (
        app_id, name, age, dob, gender, photo, email, password, phone, 
        portfolio, course, electives, city, batch_time, interview_slot, 
        grad_month, orientation_week, skills, experience, badge_color, interests, address
    ) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("<div style='color:red; font-family:sans-serif; padding:20px;'><strong>SQL Prepare Failed:</strong> " . htmlspecialchars($conn->error) . "</div>");
    }

    $stmt->bind_param(
        "ssisssssssssssssssisss",
        $app_id,
        $name,
        $age,
        $dob,
        $gender,
        $photo_filename,
        $email,
        $hashedPassword,
        $phone,
        $portfolio,
        $course,
        $electives,
        $city,
        $batch_time,
        $interview_slot,
        $grad_month,
        $orientation_week,
        $skills,
        $experience,
        $badge_color,
        $interests,
        $address
    );

    // Execute Statement
    if ($stmt->execute()) {
        $inserted_id = $stmt->insert_id;
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registration Confirmed - <?php echo htmlspecialchars($name); ?></title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <nav class="navbar">
                <div class="nav-brand">🎓 <strong>EduPortal</strong> Registration Confirmed</div>
                <div class="nav-links">
                    <a href="records.php" class="nav-btn">📋 View All Students</a>
                    <a href="index.html" class="nav-btn">➕ New Registration</a>
                </div>
            </nav>

            <div class="container" style="text-align: center;">
                <h1 style="color: #16a34a; margin-bottom: 5px;">🎉 Registration Successful!</h1>
                <p class="subtitle">Student admission profile has been registered in the database.</p>

                <div style="background: var(--input-bg); border: 1px solid var(--border-color); padding: 25px; border-radius: 10px; margin: 25px 0; text-align: left;">
                    
                    <div style="display: flex; align-items: center; gap: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px;">
                        <?php if (!empty($photo_filename) && file_exists(__DIR__ . "/uploads/" . $photo_filename)): ?>
                            <img src="uploads/<?php echo htmlspecialchars($photo_filename); ?>" alt="Photo" style="width: 75px; height: 75px; border-radius: 50%; object-fit: cover; border: 3px solid <?php echo htmlspecialchars($badge_color); ?>;">
                        <?php else: ?>
                            <div style="width: 75px; height: 75px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 30px;">🎓</div>
                        <?php endif; ?>
                        
                        <div>
                            <h2 style="margin: 0 0 5px 0; color: var(--text-main);"><?php echo htmlspecialchars($name); ?></h2>
                            <span class="badge-tag" style="background: <?php echo htmlspecialchars($badge_color); ?>; color: #fff; font-size: 12px;"><?php echo htmlspecialchars($app_id); ?></span>
                            <span class="badge-tag"><?php echo htmlspecialchars($course); ?></span>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; font-size: 13.5px;">
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
                        <p><strong>Gender / Age:</strong> <?php echo htmlspecialchars($gender); ?>, <?php echo htmlspecialchars($age); ?> yrs</p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($dob); ?></p>
                        <p><strong>Campus:</strong> <?php echo htmlspecialchars($city ?: 'Main Campus'); ?></p>
                        <p><strong>Batch Time:</strong> <?php echo htmlspecialchars($batch_time); ?></p>
                        <p><strong>Skills:</strong> <?php echo htmlspecialchars($skills ?: 'None'); ?></p>
                        <p><strong>Skill Level:</strong> <?php echo htmlspecialchars($experience); ?>%</p>
                        <?php if (!empty($electives)): ?>
                            <p style="grid-column: 1 / -1;"><strong>Electives:</strong> <?php echo htmlspecialchars($electives); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($address)): ?>
                            <p style="grid-column: 1 / -1;"><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($address)); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="button-group">
                    <a href="card.php?id=<?php echo $inserted_id; ?>" class="btn btn-submit">🪪 View & Print Student ID Card</a>
                    <a href="records.php" class="btn btn-secondary">📋 View All Student Records</a>
                    <a href="index.html" class="btn btn-reset" style="background: #2563eb;">➕ Register Another</a>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<div style='color:red; font-family:sans-serif; padding:20px;'><strong>Database Error:</strong> " . htmlspecialchars($stmt->error) . "<br><br><a href='index.html'>Go Back</a></div>";
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: index.html");
    exit();
}
?>
