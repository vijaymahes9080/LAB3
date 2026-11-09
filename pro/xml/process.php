<?php

// =============================================================
// XML Processing & MySQL Database Storage
// =============================================================

$xml_file = __DIR__ . "/students.xml";
$dtd_file = __DIR__ . "/students.dtd";
$xsd_file = __DIR__ . "/students.xsd";

// 1. Check POST Request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit();
}

// 2. Retrieve and Sanitize Form Inputs
$rollNo     = trim($_POST["rollNo"] ?? "");
$name       = trim($_POST["name"] ?? "");
$age        = trim($_POST["age"] ?? "");
$dob        = trim($_POST["dob"] ?? "");
$gender     = trim($_POST["gender"] ?? "");
$email      = trim($_POST["email"] ?? "");
$phone      = trim($_POST["phone"] ?? "");
$course     = trim($_POST["course"] ?? "");
$city       = trim($_POST["city"] ?? "");
$batch_time = trim($_POST["batch_time"] ?? "");
$address    = trim($_POST["address"] ?? "");

// Skills checkboxes
$skills = isset($_POST["skills"]) && is_array($_POST["skills"]) ? implode(", ", $_POST["skills"]) : "";

// Basic validation
if (empty($rollNo) || empty($name) || empty($email) || empty($phone) || empty($course)) {
    die("<div style='color:red; font-family:sans-serif; padding:30px;'><strong>Error:</strong> All required fields must be provided. <a href='index.html'>Go Back</a></div>");
}

// =============================================================
// 3. Load or Initialize XML File with DOMDocument
// =============================================================
$dom = new DOMDocument("1.0", "UTF-8");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

if (file_exists($xml_file) && filesize($xml_file) > 0) {
    $dom->load($xml_file);
    $root = $dom->documentElement;
} else {
    // Create new document with DTD doctype
    $implementation = new DOMImplementation();
    $dtd = $implementation->createDocumentType("students", "", "students.dtd");
    $dom = $implementation->createDocument("", "", $dtd);
    $dom->encoding = "UTF-8";
    $dom->formatOutput = true;
    
    $root = $dom->createElement("students");
    $root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:noNamespaceSchemaLocation', 'students.xsd');
    $dom->appendChild($root);
}

// Determine Next Sequential ID
$studentsList = $dom->getElementsByTagName("student");
$nextId = $studentsList->length + 1;

// Create New <student> Node
$studentNode = $dom->createElement("student");
$studentNode->setAttribute("status", "enrolled");

$studentNode->appendChild($dom->createElement("id", htmlspecialchars($nextId)));
$studentNode->appendChild($dom->createElement("rollNo", htmlspecialchars($rollNo)));
$studentNode->appendChild($dom->createElement("name", htmlspecialchars($name)));
$studentNode->appendChild($dom->createElement("age", htmlspecialchars($age)));
$studentNode->appendChild($dom->createElement("dob", htmlspecialchars($dob)));
$studentNode->appendChild($dom->createElement("gender", htmlspecialchars($gender)));
$studentNode->appendChild($dom->createElement("email", htmlspecialchars($email)));
$studentNode->appendChild($dom->createElement("phone", htmlspecialchars($phone)));
$studentNode->appendChild($dom->createElement("course", htmlspecialchars($course)));
$studentNode->appendChild($dom->createElement("skills", htmlspecialchars($skills)));
$studentNode->appendChild($dom->createElement("city", htmlspecialchars($city)));
$studentNode->appendChild($dom->createElement("batch_time", htmlspecialchars($batch_time)));
$studentNode->appendChild($dom->createElement("address", htmlspecialchars($address)));

$root->appendChild($studentNode);

// Save updated XML to file
$dom->save($xml_file);

// =============================================================
// 4. Validate XML against DTD and XSD
// =============================================================
libxml_use_internal_errors(true);

// DTD Validation
$valDom = new DOMDocument();
$valDom->load($xml_file);
$dtd_valid = @$valDom->validate();
$dtd_errors = libxml_get_errors();
libxml_clear_errors();

// XSD Validation
$xsd_valid = false;
$xsd_errors = [];
if (file_exists($xsd_file)) {
    $xsd_valid = @$valDom->schemaValidate($xsd_file);
    $xsd_errors = libxml_get_errors();
    libxml_clear_errors();
}

// =============================================================
// 5. Connect to XAMPP MySQL and Insert XML Data
// =============================================================
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "student_db";

$db_success = false;
$db_error = "";
$db_inserted_id = null;

$conn = @new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    $db_error = "MySQL Connection Failed: " . $conn->connect_error . ". Please make sure MySQL is running in XAMPP and database 'student_db' exists.";
} else {
    // Parse the newly added XML node directly from SimpleXML
    $xmlData = simplexml_load_file($xml_file);
    $lastStudent = $xmlData->student[$xmlData->count() - 1];

    $xml_rollNo     = (string)$lastStudent->rollNo;
    $xml_name       = (string)$lastStudent->name;
    $xml_age        = (int)$lastStudent->age;
    $xml_dob        = (string)$lastStudent->dob;
    $xml_gender     = (string)$lastStudent->gender;
    $xml_email      = (string)$lastStudent->email;
    $xml_phone      = (string)$lastStudent->phone;
    $xml_course     = (string)$lastStudent->course;
    $xml_skills     = (string)$lastStudent->skills;
    $xml_city       = (string)$lastStudent->city;
    $xml_batch_time = (string)$lastStudent->batch_time;
    $xml_address    = (string)$lastStudent->address;

    $sql = "INSERT INTO students (rollNo, name, age, dob, gender, email, phone, course, skills, city, batch_time, address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            name=VALUES(name), age=VALUES(age), dob=VALUES(dob), gender=VALUES(gender), 
            phone=VALUES(phone), course=VALUES(course), skills=VALUES(skills), city=VALUES(city), 
            batch_time=VALUES(batch_time), address=VALUES(address)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(
            "ssisssssssss",
            $xml_rollNo,
            $xml_name,
            $xml_age,
            $xml_dob,
            $xml_gender,
            $xml_email,
            $xml_phone,
            $xml_course,
            $xml_skills,
            $xml_city,
            $xml_batch_time,
            $xml_address
        );
        if ($stmt->execute()) {
            $db_success = true;
            $db_inserted_id = $stmt->insert_id ?: $nextId;
        } else {
            $db_error = "MySQL Insert Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $db_error = "MySQL Prepare Error: " . $conn->error;
    }
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XML Processed & MySQL Stored - <?php echo htmlspecialchars($name); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">📁 <strong>XML-MySQL Hub</strong> &bull; Process Complete</div>
        <div class="nav-links">
            <a href="index.html" class="nav-btn">➕ New Student</a>
            <a href="view_xml.php" class="nav-btn">📄 View XML File</a>
            <a href="records.php" class="nav-btn">📋 View DB Records</a>
        </div>
    </nav>

    <div class="container">

        <header class="form-header">
            <h1 style="color: #16a34a;">🎉 Processing Complete!</h1>
            <p class="subtitle">XML node generated, validated against DTD & XSD, and stored into MySQL database</p>
        </header>

        <!-- Status Badges Grid -->
        <div class="status-grid">
            <!-- XML Node Creation -->
            <div class="status-card success">
                <div class="status-icon">📄</div>
                <div class="status-info">
                    <h4>XML Document</h4>
                    <p>Appended & saved to <code>students.xml</code></p>
                    <span class="badge-success">CREATED & VALID</span>
                </div>
            </div>

            <!-- DTD Validation -->
            <div class="status-card <?php echo $dtd_valid ? 'success' : 'error-card'; ?>">
                <div class="status-icon">🛡️</div>
                <div class="status-info">
                    <h4>DTD Validation</h4>
                    <p>Validated against <code>students.dtd</code></p>
                    <?php if ($dtd_valid): ?>
                        <span class="badge-success">PASSED (Valid DTD)</span>
                    <?php else: ?>
                        <span class="badge-error">FAILED</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- XSD Validation -->
            <div class="status-card <?php echo $xsd_valid ? 'success' : 'error-card'; ?>">
                <div class="status-icon">📐</div>
                <div class="status-info">
                    <h4>XSD Validation</h4>
                    <p>Validated against <code>students.xsd</code></p>
                    <?php if ($xsd_valid): ?>
                        <span class="badge-success">PASSED (Valid XSD Schema)</span>
                    <?php else: ?>
                        <span class="badge-error">FAILED</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- MySQL Database -->
            <div class="status-card <?php echo $db_success ? 'success' : 'error-card'; ?>">
                <div class="status-icon">🗄️</div>
                <div class="status-info">
                    <h4>MySQL Database</h4>
                    <p>Inserted into <code>student_db.students</code></p>
                    <?php if ($db_success): ?>
                        <span class="badge-success">STORED (ID: <?php echo htmlspecialchars($db_inserted_id); ?>)</span>
                    <?php else: ?>
                        <span class="badge-error">DB FAILED</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!$db_success && !empty($db_error)): ?>
            <div class="alert-box alert-error">
                <strong>Database Notice:</strong> <?php echo htmlspecialchars($db_error); ?>
            </div>
        <?php endif; ?>

        <!-- XML Preview Snippet -->
        <div style="margin-top: 25px;">
            <h3>Generated Student XML Node:</h3>
            <pre class="code-box"><code>&lt;student status="enrolled"&gt;
    &lt;id&gt;<?php echo htmlspecialchars($nextId); ?>&lt;/id&gt;
    &lt;rollNo&gt;<?php echo htmlspecialchars($rollNo); ?>&lt;/rollNo&gt;
    &lt;name&gt;<?php echo htmlspecialchars($name); ?>&lt;/name&gt;
    &lt;age&gt;<?php echo htmlspecialchars($age); ?>&lt;/age&gt;
    &lt;dob&gt;<?php echo htmlspecialchars($dob); ?>&lt;/dob&gt;
    &lt;gender&gt;<?php echo htmlspecialchars($gender); ?>&lt;/gender&gt;
    &lt;email&gt;<?php echo htmlspecialchars($email); ?>&lt;/email&gt;
    &lt;phone&gt;<?php echo htmlspecialchars($phone); ?>&lt;/phone&gt;
    &lt;course&gt;<?php echo htmlspecialchars($course); ?>&lt;/course&gt;
    &lt;skills&gt;<?php echo htmlspecialchars($skills); ?>&lt;/skills&gt;
    &lt;city&gt;<?php echo htmlspecialchars($city); ?>&lt;/city&gt;
    &lt;batch_time&gt;<?php echo htmlspecialchars($batch_time); ?>&lt;/batch_time&gt;
    &lt;address&gt;<?php echo htmlspecialchars($address); ?>&lt;/address&gt;
&lt;/student&gt;</code></pre>
        </div>

        <!-- Action Buttons -->
        <div class="button-group" style="margin-top: 25px;">
            <a href="view_xml.php" class="btn btn-submit">📄 View Full XML File</a>
            <a href="records.php" class="btn btn-secondary" style="background:#2563eb;">📋 View Database Records</a>
            <a href="index.html" class="btn btn-reset" style="background:#475569;">➕ Add Another Student</a>
        </div>

    </div>

</body>
</html>
