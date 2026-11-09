<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "student_db";

$conn = @new mysqli($servername, $username, $password, $database);

$db_connected = false;
$result = null;
$error_msg = "";

if ($conn->connect_error) {
    $error_msg = "Database Connection Failed: " . $conn->connect_error . ". Please make sure MySQL is active in XAMPP.";
} else {
    $db_connected = true;

    // Handle delete
    if (isset($_GET["delete_id"])) {
        $del_id = intval($_GET["delete_id"]);
        $conn->query("DELETE FROM students WHERE id = $del_id");
        header("Location: records.php?deleted=1");
        exit();
    }

    $search = trim($_GET["search"] ?? "");
    $sql = "SELECT * FROM students WHERE 1=1";
    if (!empty($search)) {
        $searchEsc = $conn->real_escape_string($search);
        $sql .= " AND (name LIKE '%$searchEsc%' OR email LIKE '%$searchEsc%' OR rollNo LIKE '%$searchEsc%' OR course LIKE '%$searchEsc%')";
    }
    $sql .= " ORDER BY id DESC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Database Records - EduPortal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">🗄️ <strong>MySQL Database Records</strong> &bull; student_db</div>
        <div class="nav-links">
            <a href="index.html" class="nav-btn">➕ Student Form</a>
            <a href="view_xml.php" class="nav-btn">📄 View XML</a>
            <a href="db_to_xml.php" class="nav-btn" style="background:#2563eb; color:#fff; border-color:#2563eb;">📤 Export DB to XML</a>
        </div>
    </nav>

    <div class="container" style="width: 1050px; max-width: 95%;">

        <header class="form-header">
            <h1>MySQL Database Records (student_db)</h1>
            <p class="subtitle">Live database table populated from HTML XML Form Submissions & XML Sync</p>
        </header>

        <!-- Search Bar -->
        <form method="GET" action="records.php" style="margin-bottom: 20px;">
            <div class="form-row">
                <div class="form-col" style="flex:3;">
                    <input type="search" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>" placeholder="Search by name, roll no, course, email...">
                </div>
                <div>
                    <button type="submit" class="btn btn-submit" style="padding: 10px 18px;">🔍 Search</button>
                    <a href="records.php" class="btn btn-secondary" style="padding: 10px 18px;">Reset</a>
                </div>
            </div>
        </form>

        <?php if ($db_connected && $result): ?>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <span>Total Stored in MySQL: <strong><?php echo $result->num_rows; ?></strong> student(s)</span>
                <a href="xml_to_db.php" class="btn btn-secondary" style="width:auto; padding:6px 14px; font-size:12px;">🔄 Re-sync from XML</a>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Roll No</th>
                            <th>Full Name</th>
                            <th>Course</th>
                            <th>Email & Phone</th>
                            <th>Skills</th>
                            <th>City & Batch</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo $row["id"]; ?></strong></td>
                                    <td><span class="badge-tag"><?php echo htmlspecialchars($row["rollNo"] ?: ("STU-" . $row["id"])); ?></span></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row["name"]); ?></strong><br>
                                        <small style="color:var(--text-muted);"><?php echo htmlspecialchars($row["gender"] ?? ''); ?>, <?php echo htmlspecialchars($row["age"] ?? '-'); ?> yrs</small>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($row["course"]); ?></strong></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($row["email"]); ?>"><?php echo htmlspecialchars($row["email"]); ?></a><br>
                                        <small style="color:var(--text-muted);"><?php echo htmlspecialchars($row["phone"]); ?></small>
                                    </td>
                                    <td><small><?php echo htmlspecialchars($row["skills"] ?? 'None'); ?></small></td>
                                    <td>
                                        <?php echo htmlspecialchars($row["city"] ?? 'Main'); ?><br>
                                        <small style="color:var(--text-muted);"><?php echo htmlspecialchars($row["batch_time"] ?? ''); ?></small>
                                    </td>
                                    <td>
                                        <a href="records.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this student from MySQL?');" style="color:#dc2626; text-decoration:none; font-weight:bold; font-size:13px;">🗑️ Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align:center; padding:30px; color:var(--text-muted);">
                                    No database records found. <a href="index.html">Click here to register a student</a> or <a href="xml_to_db.php">Import from students.xml</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert-box alert-error">
                <h3>Database Offline or Not Configured</h3>
                <p><?php echo htmlspecialchars($error_msg); ?></p>
                <p>Ensure Apache & MySQL are running in XAMPP and run <a href="database.sql">database.sql</a> in phpMyAdmin.</p>
            </div>
        <?php endif; ?>

        <div class="button-group" style="margin-top: 25px;">
            <a href="index.html" class="btn btn-submit">➕ Submit New Student</a>
            <a href="view_xml.php" class="btn btn-secondary">📄 View XML Data</a>
            <a href="db_to_xml.php" class="btn btn-reset" style="background:#2563eb;">📤 Export Database to XML</a>
        </div>

    </div>

</body>
</html>
<?php
if ($db_connected) $conn->close();
?>
