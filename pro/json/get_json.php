<?php

// =============================================================
// PHP Backend: Serve JSON Data or list separate JSON records
// =============================================================

header("Content-Type: application/json; charset=UTF-8");

$master_file = __DIR__ . "/students.json";
$records_dir = __DIR__ . "/records/";

$action = $_GET["action"] ?? "all";

if ($action === "single" && !empty($_GET["file"])) {
    $filename = basename($_GET["file"]);
    $path = $records_dir . $filename;
    if (file_exists($path)) {
        echo file_get_contents($path);
    } else {
        echo json_encode(["status" => "error", "message" => "File not found"]);
    }
    exit();
}

if ($action === "list_files") {
    $files = [];
    if (is_dir($records_dir)) {
        foreach (glob($records_dir . "*.json") as $file) {
            $files[] = [
                "filename" => basename($file),
                "path"     => "records/" . basename($file),
                "size"     => filesize($file),
                "modified" => date("Y-m-d H:i:s", filemtime($file))
            ];
        }
    }
    echo json_encode(["status" => "success", "files" => $files], JSON_PRETTY_PRINT);
    exit();
}

// Default: return master students.json
if (file_exists($master_file)) {
    echo file_get_contents($master_file);
} else {
    echo json_encode([]);
}
exit();
?>
