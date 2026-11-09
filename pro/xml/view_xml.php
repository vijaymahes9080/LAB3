<?php

$xml_file = __DIR__ . "/students.xml";
$dtd_file = __DIR__ . "/students.dtd";
$xsd_file = __DIR__ . "/students.xsd";

$xml_content = file_exists($xml_file) ? file_get_contents($xml_file) : "File not found";
$dtd_content = file_exists($dtd_file) ? file_get_contents($dtd_file) : "File not found";
$xsd_content = file_exists($xsd_file) ? file_get_contents($xsd_file) : "File not found";

// Parse XML for table view
$xml_data = file_exists($xml_file) ? @simplexml_load_file($xml_file) : false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XML, DTD & XSD Explorer - EduPortal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 8px;
        }
        .tab-btn {
            background: none;
            border: none;
            padding: 10px 18px;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .tab-btn.active {
            background: var(--primary-color);
            color: #ffffff;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">📄 <strong>XML & Schemas Explorer</strong></div>
        <div class="nav-links">
            <a href="index.html" class="nav-btn">➕ Student Form</a>
            <a href="validate_dtd.php" class="nav-btn">🛡️ Validate DTD</a>
            <a href="validate_xsd.php" class="nav-btn">📐 Validate XSD</a>
            <a href="records.php" class="nav-btn">📋 DB Records</a>
        </div>
    </nav>

    <div class="container" style="width: 1000px; max-width: 95%;">

        <header class="form-header">
            <h1>XML Architecture Explorer</h1>
            <p class="subtitle">Inspect XML data file, Document Type Definition (DTD), and XML Schema Definition (XSD)</p>
        </header>

        <!-- Tabs Navigation -->
        <div class="tabs">
            <button class="tab-btn active" onclick="openTab('tab-xml-table', this)">📊 XML Parsed Table</button>
            <button class="tab-btn" onclick="openTab('tab-xml-raw', this)">📄 students.xml</button>
            <button class="tab-btn" onclick="openTab('tab-dtd', this)">🛡️ students.dtd</button>
            <button class="tab-btn" onclick="openTab('tab-xsd', this)">📐 students.xsd</button>
        </div>

        <!-- Tab 1: XML Table -->
        <div id="tab-xml-table" class="tab-content active">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h3>Parsed XML Records (SimpleXML Tree):</h3>
                <a href="xml_to_db.php" class="btn btn-submit" style="width:auto; padding:7px 16px; font-size:13px;">📥 Sync this XML to MySQL</a>
            </div>

            <?php if ($xml_data && count($xml_data->student) > 0): ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Roll No</th>
                                <th>Name</th>
                                <th>Age / Gender</th>
                                <th>Email & Phone</th>
                                <th>Course</th>
                                <th>Skills</th>
                                <th>City</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($xml_data->student as $s): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars((string)$s->id); ?></strong></td>
                                    <td><span class="badge-tag"><?php echo htmlspecialchars((string)$s->rollNo); ?></span></td>
                                    <td><strong><?php echo htmlspecialchars((string)$s->name); ?></strong></td>
                                    <td><?php echo htmlspecialchars((string)$s->age); ?> yrs &bull; <?php echo htmlspecialchars((string)$s->gender); ?></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars((string)$s->email); ?>"><?php echo htmlspecialchars((string)$s->email); ?></a><br>
                                        <small style="color:var(--text-muted);"><?php echo htmlspecialchars((string)$s->phone); ?></small>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars((string)$s->course); ?></strong></td>
                                    <td><small><?php echo htmlspecialchars((string)$s->skills); ?></small></td>
                                    <td><?php echo htmlspecialchars((string)$s->city); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No records found in <code>students.xml</code>.</p>
            <?php endif; ?>
        </div>

        <!-- Tab 2: Raw XML -->
        <div id="tab-xml-raw" class="tab-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h3>Source: <code>students.xml</code></h3>
                <span class="badge-tag">Well-Formed XML</span>
            </div>
            <pre class="code-box"><code><?php echo htmlspecialchars($xml_content); ?></code></pre>
        </div>

        <!-- Tab 3: DTD -->
        <div id="tab-dtd" class="tab-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h3>Source: <code>students.dtd</code></h3>
                <a href="validate_dtd.php" class="btn btn-submit" style="width:auto; padding:6px 14px; font-size:12px;">Run DTD Test</a>
            </div>
            <pre class="code-box"><code><?php echo htmlspecialchars($dtd_content); ?></code></pre>
        </div>

        <!-- Tab 4: XSD -->
        <div id="tab-xsd" class="tab-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h3>Source: <code>students.xsd</code></h3>
                <a href="validate_xsd.php" class="btn btn-submit" style="width:auto; padding:6px 14px; font-size:12px;">Run XSD Test</a>
            </div>
            <pre class="code-box"><code><?php echo htmlspecialchars($xsd_content); ?></code></pre>
        </div>

    </div>

    <script>
        function openTab(tabId, btn) {
            document.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));
            document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));

            document.getElementById(tabId).classList.add("active");
            btn.classList.add("active");
        }
    </script>

</body>
</html>
