<?php

// =============================================================
// Export MySQL Database Records to XML Format
// =============================================================

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "student_db";

$conn = @new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("<div style='color:red; font-family:sans-serif; padding:30px;'>Database Connection Failed: " . htmlspecialchars($conn->connect_error) . "</div>");
}

$sql = "SELECT * FROM students ORDER BY id ASC";
$result = $conn->query($sql);

$dom = new DOMImplementation();
$dtd = $dom->createDocumentType("students", "", "students.dtd");
$xmlDoc = $dom->createDocument("", "", $dtd);
$xmlDoc->encoding = "UTF-8";
$xmlDoc->formatOutput = true;

$root = $xmlDoc->createElement("students");
$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:noNamespaceSchemaLocation', 'students.xsd');
$xmlDoc->appendChild($root);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $studentNode = $xmlDoc->createElement("student");
        $studentNode->setAttribute("status", "enrolled");

        $studentNode->appendChild($xmlDoc->createElement("id", htmlspecialchars($row["id"])));
        $studentNode->appendChild($xmlDoc->createElement("rollNo", htmlspecialchars($row["rollNo"] ?: ("STU-" . $row["id"]))));
        $studentNode->appendChild($xmlDoc->createElement("name", htmlspecialchars($row["name"])));
        $studentNode->appendChild($xmlDoc->createElement("age", htmlspecialchars($row["age"] ?: "20")));
        $studentNode->appendChild($xmlDoc->createElement("dob", htmlspecialchars($row["dob"] ?: "2005-01-01")));
        $studentNode->appendChild($xmlDoc->createElement("gender", htmlspecialchars($row["gender"] ?: "Male")));
        $studentNode->appendChild($xmlDoc->createElement("email", htmlspecialchars($row["email"])));
        $studentNode->appendChild($xmlDoc->createElement("phone", htmlspecialchars($row["phone"] ?: "9000000000")));
        $studentNode->appendChild($xmlDoc->createElement("course", htmlspecialchars($row["course"] ?: "BSc CS")));
        $studentNode->appendChild($xmlDoc->createElement("skills", htmlspecialchars($row["skills"] ?: "HTML, CSS")));
        $studentNode->appendChild($xmlDoc->createElement("city", htmlspecialchars($row["city"] ?: "Main Campus")));
        $studentNode->appendChild($xmlDoc->createElement("batch_time", htmlspecialchars($row["batch_time"] ?: "09:00")));
        $studentNode->appendChild($xmlDoc->createElement("address", htmlspecialchars($row["address"] ?: "Standard Campus Address")));

        $root->appendChild($studentNode);
    }
}

// Check download param
if (isset($_GET["download"]) && $_GET["download"] == "1") {
    header("Content-Type: application/xml; charset=utf-8");
    header("Content-Disposition: attachment; filename=exported_students.xml");
    echo $xmlDoc->saveXML();
    $conn->close();
    exit();
}

$xml_output = $xmlDoc->saveXML();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export MySQL Database to XML</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">📤 <strong>MySQL to XML Exporter</strong></div>
        <div class="nav-links">
            <a href="index.html" class="nav-btn">➕ Form</a>
            <a href="records.php" class="nav-btn">📋 DB Records</a>
            <a href="db_to_xml.php?download=1" class="nav-btn" style="background:#16a34a; color:#fff; border-color:#16a34a;">💾 Download .xml File</a>
        </div>
    </nav>

    <div class="container">

        <header class="form-header">
            <h1>MySQL Database &rarr; XML Exporter</h1>
            <p class="subtitle">Generated well-formed XML with DTD doctype and XSD schema references</p>
        </header>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h3>Live Generated XML from MySQL:</h3>
            <a href="db_to_xml.php?download=1" class="btn btn-submit" style="width:auto; padding:8px 18px; font-size:13px;">💾 Download exported_students.xml</a>
        </div>

        <pre class="code-box"><code><?php echo htmlspecialchars($xml_output); ?></code></pre>

        <div class="button-group">
            <a href="validate_dtd.php" class="btn btn-secondary">🛡️ Validate with DTD</a>
            <a href="validate_xsd.php" class="btn btn-secondary">📐 Validate with XSD</a>
            <a href="records.php" class="btn btn-submit">📋 View DB Records</a>
        </div>

    </div>

</body>
</html>
