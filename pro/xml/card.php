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

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'><h2>Student Not Found</h2><a href='records.php'>Back to Records</a></div>");
}

$student = $result->fetch_assoc();
$badgeColor = $student["badge_color"] ?: "#2563eb";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID Card - <?php echo htmlspecialchars($student['name']); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .id-badge {
            background: <?php echo htmlspecialchars($badgeColor); ?>;
        }
        .qr-mock {
            width: 70px;
            height: 70px;
            margin: 15px auto 5px;
            background: repeating-linear-gradient(45deg, #111, #111 4px, #fff 4px, #fff 8px);
            border: 1px solid #000;
        }
    </style>
</head>
<body>

    <nav class="navbar no-print">
        <div class="nav-brand">🎓 <strong>EduPortal</strong> Student ID Card Generator</div>
        <div class="nav-links">
            <a href="records.php" class="nav-btn">📋 Back to Records</a>
            <a href="index.html" class="nav-btn">➕ New Student</a>
            <button onclick="window.print()" class="btn btn-submit" style="padding: 7px 16px;">🖨️ Print ID Card</button>
        </div>
    </nav>

    <div class="container" style="background: transparent; border: none; box-shadow: none;">
        
        <!-- ID Card Front -->
        <div class="id-card">
            <div class="id-header id-badge">
                <h3 style="margin:0; font-size: 18px; letter-spacing: 1px;">INSTITUTE OF TECHNOLOGY</h3>
                <small style="opacity: 0.9; text-transform: uppercase; font-size: 11px;">Student Identity Card (2026 - 2027)</small>
            </div>

            <div class="id-body">
                <?php if (!empty($student["photo"]) && file_exists(__DIR__ . "/uploads/" . $student["photo"])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($student["photo"]); ?>" alt="Photo" class="id-photo" style="border-color: <?php echo htmlspecialchars($badgeColor); ?>;">
                <?php else: ?>
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='90' height='90' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z'/%3E%3Cpath fill-rule='evenodd' d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z'/%3E%3C/svg%3E" alt="Avatar" class="id-photo">
                <?php endif; ?>

                <h2 style="margin: 8px 0 2px; font-size: 20px; color: #1e293b;"><?php echo htmlspecialchars($student["name"]); ?></h2>
                <span class="badge-tag id-badge" style="color: #fff; font-size: 12px;"><?php echo htmlspecialchars($student["course"]); ?></span>

                <div class="id-details">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding: 4px 0;">
                        <span style="color: #64748b;">Student ID:</span>
                        <strong><?php echo htmlspecialchars($student["app_id"] ?: ("APP-" . $student["id"])); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding: 4px 0;">
                        <span style="color: #64748b;">Email:</span>
                        <span><?php echo htmlspecialchars($student["email"]); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding: 4px 0;">
                        <span style="color: #64748b;">Phone:</span>
                        <span><?php echo htmlspecialchars($student["phone"]); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding: 4px 0;">
                        <span style="color: #64748b;">DOB:</span>
                        <span><?php echo htmlspecialchars($student["dob"]); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0;">
                        <span style="color: #64748b;">Campus:</span>
                        <span><?php echo htmlspecialchars($student["city"] ?: 'Main Campus'); ?></span>
                    </div>
                </div>

                <div class="qr-mock" title="Scannable Student Token"></div>
                <small style="font-size: 10px; color: #94a3b8;">Authorized Academic Signature</small>
            </div>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" class="btn btn-submit" style="width: auto; padding: 12px 30px;">🖨️ Print Student ID Card</button>
            <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-secondary" style="width: auto; padding: 12px 25px;">✏️ Edit Student Info</a>
        </div>

    </div>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
