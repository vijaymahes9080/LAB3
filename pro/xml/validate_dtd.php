<?php

$xml_file = __DIR__ . "/students.xml";
$dtd_file = __DIR__ . "/students.dtd";

libxml_use_internal_errors(true);

$dom = new DOMDocument();
$file_exists = file_exists($xml_file);

$is_valid = false;
$errors = [];

if ($file_exists) {
    $dom->load($xml_file);
    $is_valid = @$dom->validate();
    $errors = libxml_get_errors();
    libxml_clear_errors();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DTD Validation - students.xml</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">🛡️ <strong>DTD Validator</strong> &bull; Document Type Definition</div>
        <div class="nav-links">
            <a href="index.html" class="nav-btn">➕ Student Form</a>
            <a href="view_xml.php" class="nav-btn">📄 View XML</a>
            <a href="validate_xsd.php" class="nav-btn">📐 Validate XSD</a>
            <a href="records.php" class="nav-btn">📋 DB Records</a>
        </div>
    </nav>

    <div class="container">

        <header class="form-header">
            <h1>Document Type Definition (DTD) Validation</h1>
            <p class="subtitle">Validating <code>students.xml</code> structure against <code>students.dtd</code></p>
        </header>

        <!-- Validation Result -->
        <div style="text-align: center; margin: 25px 0;">
            <?php if ($is_valid): ?>
                <div class="alert-box alert-success" style="display:inline-block; padding: 20px 40px; font-size: 18px;">
                    ✅ <strong>VALID:</strong> <code>students.xml</code> strictly conforms to <code>students.dtd</code>!
                </div>
            <?php else: ?>
                <div class="alert-box alert-error" style="display:inline-block; padding: 20px 40px; font-size: 18px;">
                    ❌ <strong>INVALID / ERRORS FOUND:</strong>
                </div>
                <?php if (!empty($errors)): ?>
                    <div style="text-align: left; margin-top: 15px;">
                        <h4>Validation Error Details:</h4>
                        <ul style="color: #dc2626;">
                            <?php foreach ($errors as $error): ?>
                                <li><strong>Line <?php echo $error->line; ?>:</strong> <?php echo htmlspecialchars($error->message); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- DTD Definition Box -->
        <h3>DTD Schema Content (<code>students.dtd</code>):</h3>
        <pre class="code-box"><code><?php echo htmlspecialchars(file_get_contents($dtd_file)); ?></code></pre>

        <div class="button-group">
            <a href="validate_xsd.php" class="btn btn-submit">📐 Test XSD Schema Validation</a>
            <a href="view_xml.php" class="btn btn-secondary">📄 View XML File</a>
            <a href="index.html" class="btn btn-reset" style="background:#2563eb;">➕ Register Student</a>
        </div>

    </div>

</body>
</html>
