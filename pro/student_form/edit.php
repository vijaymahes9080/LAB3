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

// Fetch existing record
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'><h2>Student Not Found</h2><a href='records.php'>Back to Records</a></div>");
}

$student = $result->fetch_assoc();
$skills_array = !empty($student["skills"]) ? array_map('trim', explode(",", $student["skills"])) : [];
$electives_array = !empty($student["electives"]) ? array_map('trim', explode(",", $student["electives"])) : [];

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name             = trim($_POST["name"] ?? "");
    $age              = !empty($_POST["age"]) ? intval($_POST["age"]) : null;
    $dob              = !empty($_POST["dob"]) ? $_POST["dob"] : null;
    $gender           = $_POST["gender"] ?? "";
    $email            = trim($_POST["email"] ?? "");
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

    $skills = isset($_POST["skills"]) && is_array($_POST["skills"]) ? implode(", ", $_POST["skills"]) : "";
    $electives = isset($_POST["electives"]) && is_array($_POST["electives"]) ? implode(", ", $_POST["electives"]) : "";

    // Photo upload check
    $photo_filename = $student["photo"];
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . "/uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $file_ext = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
        $photo_filename = "photo_" . time() . "_" . uniqid() . "." . $file_ext;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $upload_dir . $photo_filename);
    }

    $update_sql = "UPDATE students SET 
        name = ?, age = ?, dob = ?, gender = ?, photo = ?, email = ?, phone = ?, 
        portfolio = ?, course = ?, electives = ?, city = ?, batch_time = ?, interview_slot = ?, 
        grad_month = ?, orientation_week = ?, skills = ?, experience = ?, badge_color = ?, 
        interests = ?, address = ? 
        WHERE id = ?";

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param(
        "sissssssssssssssisssi",
        $name, $age, $dob, $gender, $photo_filename, $email, $phone,
        $portfolio, $course, $electives, $city, $batch_time, $interview_slot,
        $grad_month, $orientation_week, $skills, $experience, $badge_color,
        $interests, $address, $id
    );

    if ($update_stmt->execute()) {
        header("Location: records.php?updated=1");
        exit();
    } else {
        $error_msg = "Update Error: " . $update_stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - <?php echo htmlspecialchars($student['name']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">🎓 <strong>EduPortal</strong> Edit Student Profile</div>
        <div class="nav-links">
            <a href="records.php" class="nav-btn">📋 Back to Records</a>
            <a href="card.php?id=<?php echo $id; ?>" class="nav-btn">🪪 View ID Card</a>
            <button type="button" id="themeToggle" class="theme-toggle-btn">🌙</button>
        </div>
    </nav>

    <div class="container">
        <header class="form-header">
            <h1>✏️ Edit Student Record</h1>
            <p class="subtitle">Updating profile for Application ID: <strong><?php echo htmlspecialchars($student['app_id']); ?></strong></p>
        </header>

        <?php if (!empty($error_msg)): ?>
            <div style="color:red; margin-bottom:15px;"><?php echo htmlspecialchars($error_msg); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            
            <fieldset>
                <legend>👤 Personal Information</legend>

                <div class="form-row">
                    <div class="form-col">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                    </div>
                    <div class="form-col">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($student['age']); ?>" min="15" max="70" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($student['dob']); ?>" required>
                    </div>
                    <div class="form-col">
                        <label>Gender:</label>
                        <div class="options">
                            <label><input type="radio" name="gender" value="Male" <?php if ($student['gender'] === 'Male') echo 'checked'; ?>> Male</label>
                            <label><input type="radio" name="gender" value="Female" <?php if ($student['gender'] === 'Female') echo 'checked'; ?>> Female</label>
                            <label><input type="radio" name="gender" value="Other" <?php if ($student['gender'] === 'Other') echo 'checked'; ?>> Other</label>
                        </div>
                    </div>
                </div>

                <label for="photo">Update Profile Photo:</label>
                <div class="file-upload-box">
                    <div class="avatar-preview">
                        <?php if (!empty($student["photo"]) && file_exists(__DIR__ . "/uploads/" . $student["photo"])): ?>
                            <img id="imagePreview" src="uploads/<?php echo htmlspecialchars($student["photo"]); ?>" alt="Avatar">
                        <?php else: ?>
                            <img id="imagePreview" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' fill='%23ccc' viewBox='0 0 16 16'%3E%3Cpath d='M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z'/%3E%3Cpath fill-rule='evenodd' d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z'/%3E%3C/svg%3E" alt="Avatar">
                        <?php endif; ?>
                    </div>
                    <div class="file-input-wrapper">
                        <input type="file" id="photo" name="photo" accept="image/*">
                        <small class="hint">Upload a new photo to replace current photo.</small>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>🔐 Contact & Academic</legend>

                <div class="form-row">
                    <div class="form-col">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                    <div class="form-col">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label for="course">Degree / Course:</label>
                        <select id="course" name="course" required>
                            <option value="BSc CS" <?php if ($student['course'] === 'BSc CS') echo 'selected'; ?>>B.Sc Computer Science</option>
                            <option value="BCA" <?php if ($student['course'] === 'BCA') echo 'selected'; ?>>BCA</option>
                            <option value="BTech IT" <?php if ($student['course'] === 'BTech IT') echo 'selected'; ?>>B.Tech IT</option>
                            <option value="MCA" <?php if ($student['course'] === 'MCA') echo 'selected'; ?>>MCA</option>
                            <option value="MSc CS" <?php if ($student['course'] === 'MSc CS') echo 'selected'; ?>>M.Sc Computer Science</option>
                            <option value="MTech CSE" <?php if ($student['course'] === 'MTech CSE') echo 'selected'; ?>>M.Tech CSE</option>
                        </select>
                    </div>
                    <div class="form-col">
                        <label for="city">Campus / City:</label>
                        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($student['city']); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label for="batch_time">Batch Time:</label>
                        <input type="time" id="batch_time" name="batch_time" value="<?php echo htmlspecialchars($student['batch_time']); ?>" required>
                    </div>
                    <div class="form-col">
                        <label for="portfolio">Portfolio URL:</label>
                        <input type="url" id="portfolio" name="portfolio" value="<?php echo htmlspecialchars($student['portfolio']); ?>">
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>⚙️ Skills & Badge</legend>

                <label>Technical Skills:</label>
                <div class="options">
                    <?php
                    $all_skills = ["HTML5", "CSS3", "JavaScript", "PHP", "MySQL", "Python", "React"];
                    foreach ($all_skills as $s) {
                        $checked = in_array($s, $skills_array) ? 'checked' : '';
                        echo "<label><input type='checkbox' name='skills[]' value='$s' $checked> $s</label>";
                    }
                    ?>
                </div>

                <label for="experience">Skill Level (%):</label>
                <div class="range-container">
                    <input type="range" id="experience" name="experience" min="0" max="100" value="<?php echo htmlspecialchars($student['experience'] ?: 50); ?>" oninput="expOutput.value = this.value + '%'">
                    <output id="expOutput"><?php echo htmlspecialchars($student['experience'] ?: 50); ?>%</output>
                </div>

                <label for="badge_color">ID Card Badge Color:</label>
                <div class="color-picker-container">
                    <input type="color" id="badge_color" name="badge_color" value="<?php echo htmlspecialchars($student['badge_color'] ?: '#1a73e8'); ?>">
                </div>

                <label for="address">Postal Address:</label>
                <textarea id="address" name="address"><?php echo htmlspecialchars($student['address']); ?></textarea>
            </fieldset>

            <div class="button-group">
                <button type="submit" class="btn btn-submit">💾 Save Changes</button>
                <a href="records.php" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>

    <script src="validation.js"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
