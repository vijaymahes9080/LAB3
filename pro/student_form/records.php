<?php

// Database Connection
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "student_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("<div style='color:red; font-family:sans-serif; padding:20px;'><strong>Database Connection Failed:</strong> " . htmlspecialchars($conn->connect_error) . "</div>");
}

// Search & Filter
$search = trim($_GET["search"] ?? "");
$course_filter = trim($_GET["course_filter"] ?? "");

$sql = "SELECT * FROM students WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR app_id LIKE ? OR phone LIKE ?)";
    $searchParam = "%" . $search . "%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
    $types .= "ssss";
}

if (!empty($course_filter)) {
    $sql .= " AND course = ?";
    $params[] = $course_filter;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Count total
$total_students = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records Directory - EduPortal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar">
        <div class="nav-brand">🎓 <strong>EduPortal</strong> Student Directory</div>
        <div class="nav-links">
            <a href="index.html" class="nav-btn">➕ New Student Registration</a>
            <a href="export.php" class="nav-btn" style="background:#16a34a; color:#fff; border-color:#16a34a;">📥 Export to CSV</a>
            <button type="button" id="themeToggle" class="theme-toggle-btn">🌙</button>
        </div>
    </nav>

    <div class="container" style="width: 1100px; max-width: 95%;">

        <header class="form-header">
            <h1>📋 Registered Students Directory</h1>
            <p class="subtitle">Search, filter, manage student profiles, and generate printable ID cards</p>
        </header>

        <!-- Search & Filter Controls -->
        <form method="GET" action="records.php" style="margin-bottom: 20px;">
            <div class="form-row" style="align-items: flex-end;">
                <div class="form-col" style="flex: 2;">
                    <label for="search">Search by Name, Email, App ID, Phone:</label>
                    <input type="search" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Type keyword and press search...">
                </div>
                <div class="form-col">
                    <label for="course_filter">Filter by Course:</label>
                    <select id="course_filter" name="course_filter">
                        <option value="">-- All Courses --</option>
                        <option value="BSc CS" <?php if ($course_filter == 'BSc CS') echo 'selected'; ?>>B.Sc Computer Science</option>
                        <option value="BCA" <?php if ($course_filter == 'BCA') echo 'selected'; ?>>BCA</option>
                        <option value="BTech IT" <?php if ($course_filter == 'BTech IT') echo 'selected'; ?>>B.Tech IT</option>
                        <option value="MCA" <?php if ($course_filter == 'MCA') echo 'selected'; ?>>MCA</option>
                        <option value="MSc CS" <?php if ($course_filter == 'MSc CS') echo 'selected'; ?>>M.Sc Computer Science</option>
                        <option value="MTech CSE" <?php if ($course_filter == 'MTech CSE') echo 'selected'; ?>>M.Tech CSE</option>
                    </select>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-submit" style="padding: 10px 16px;">🔍 Filter</button>
                    <a href="records.php" class="btn btn-secondary" style="padding: 10px 16px;">Reset</a>
                </div>
            </div>
        </form>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <span style="font-size: 14px; color: var(--text-muted);">
                Showing <strong><?php echo $total_students; ?></strong> registered student(s)
            </span>
            <button onclick="window.print()" class="btn btn-secondary" style="width: auto; padding: 6px 14px; font-size: 13px;">🖨️ Print List</button>
        </div>

        <!-- Student Table -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>App ID</th>
                        <th>Full Name</th>
                        <th>Course</th>
                        <th>Email & Phone</th>
                        <th>Skills</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($row["photo"]) && file_exists(__DIR__ . "/uploads/" . $row["photo"])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($row["photo"]); ?>" alt="Photo" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid <?php echo htmlspecialchars($row['badge_color'] ?: '#2563eb'); ?>;">
                                    <?php else: ?>
                                        <div style="width: 42px; height: 42px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 18px;">🎓</div>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge-tag"><?php echo htmlspecialchars($row["app_id"] ?: ("APP-" . $row["id"])); ?></span></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row["name"]); ?></strong><br>
                                    <small style="color: var(--text-muted);"><?php echo htmlspecialchars($row["gender"]); ?>, <?php echo htmlspecialchars($row["age"] ?: '-'); ?> yrs</small>
                                </td>
                                <td><?php echo htmlspecialchars($row["course"]); ?></td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($row["email"]); ?>" style="color: var(--primary-color); text-decoration: none;"><?php echo htmlspecialchars($row["email"]); ?></a><br>
                                    <small style="color: var(--text-muted);"><?php echo htmlspecialchars($row["phone"]); ?></small>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($row["skills"] ?: 'None'); ?></small>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="card.php?id=<?php echo $row['id']; ?>" title="View ID Card" style="text-decoration: none; padding: 4px 8px; background: #2563eb; color: #fff; border-radius: 4px; font-size: 12px;">🪪 ID Card</a>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" title="Edit" style="text-decoration: none; padding: 4px 8px; background: #d97706; color: #fff; border-radius: 4px; font-size: 12px;">✏️ Edit</a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars(addslashes($row['name'])); ?>?');" title="Delete" style="text-decoration: none; padding: 4px 8px; background: #dc2626; color: #fff; border-radius: 4px; font-size: 12px;">🗑️</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                No student records found. <a href="index.html" style="color: var(--primary-color);">Click here to register a new student</a>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="validation.js"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
