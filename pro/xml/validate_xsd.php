<?php

$xml_file = __DIR__ . "/students.xml";
$xsd_file = __DIR__ . "/students.xsd";

libxml_use_internal_errors(true);

$dom = new DOMDocument();
$file_exists = file_exists($xml_file) && file_exists($xsd_file);

$is_valid = false;
$errors = [];

if ($file_exists) {
    $dom->load($xml_file);
    $is_valid = @$dom->schemaValidate($xsd_file);
    $errors = libxml_get_errors();
    libxml_clear_errors();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSD Schema Validation - students.xml</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">📐 <strong>XSD Validator</strong> &bull; XML Schema Definition</div>
        <div class="nav-links">
            <a href="index.html" class="nav-btn">➕ Student Form</a>
            <a href="view_xml.php" class="nav-btn">📄 View XML</a>
            <a href="validate_dtd.php" class="nav-btn">🛡️ Validate DTD</a>
            <a href="records.php" class="nav-btn">📋 DB Records</a>
        </div>
    </nav>

    <div class="container">

        <header class="form-header">
            <h1>XML Schema Definition (XSD) Validation</h1>
            <p class="subtitle">Validating <code>students.xml</code> types, regex patterns & constraints against <code>students.xsd</code></p>
        </header>

        <!-- Validation Result -->
        <div style="text-align: center; margin: 25px 0;">
            <?php if ($is_valid): ?>
                <div class="alert-box alert-success" style="display:inline-block; padding: 20px 40px; font-size: 18px;">
                    ✅ <strong>VALID:</strong> <code>students.xml</code> strictly complies with all types and constraints in <code>students.xsd</code>!
                </div>
            <?php else: ?>
                <div class="alert-box alert-error" style="display:inline-block; padding: 20px 40px; font-size: 18px;">
                    ❌ <strong>XSD VALIDATION FAILED:</strong>
                </div>
                <?php if (!empty($errors)): ?>
                    <div style="text-align: left; margin-top: 15px;">
                        <h4>Schema Error Details:</h4>
                        <ul style="color: #dc2626;">
                            <?php foreach ($errors as $error): ?>
                                <li><strong>Line <?php echo $error->line; ?>:</strong> <?php echo htmlspecialchars($error->message); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- XSD Definition Box -->
        <h3>XSD Schema Content (<code>students.xsd</code>):</h3>
        <pre class="code-box"><code><?php echo htmlspecialchars(file_get_contents($xsd_file)); ?></code></pre>

        <div class="button-group">
            <a href="validate_dtd.php" class="btn btn-submit">🛡️ Test DTD Validation</a>
            <a href="view_xml.php" class="btn btn-secondary">📄 View XML File</a>
            <a href="xml_to_db.php" class="btn btn-reset" style="background:#16a34a;">📥 Import to MySQL</a>
        </div>

    </div>

</body>
</html>
