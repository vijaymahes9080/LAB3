<?php

// =============================================================
// PHP Backend: Store HTML Form Data to JSON File(s)
// =============================================================

header("Content-Type: application/json; charset=UTF-8");

$master_file = __DIR__ . "/students.json";
$records_dir = __DIR__ . "/records/";

if (!is_dir($records_dir)) {
    mkdir($records_dir, 0777, true);
}

// 1. Read JSON input payload from HTTP POST request
$raw_input = file_get_contents("php://input");
$input_data = json_decode($raw_input, true);

// Fallback to standard $_POST if not sent as JSON body
if (!$input_data && !empty($_POST)) {
    $input_data = $_POST;
    if (isset($_POST["skills"]) && is_array($_POST["skills"])) {
        $input_data["skills"] = $_POST["skills"];
    }
}

if (empty($input_data)) {
    echo json_encode([
        "status" => "error",
        "message" => "No form data received"
    ]);
    exit();
}

// 2. Load existing master students.json
$students_list = [];
if (file_exists($master_file)) {
    $file_content = file_get_contents($master_file);
    $students_list = json_decode($file_content, true) ?: [];
}

// 3. Assign New ID & Timestamps
$next_id = count($students_list) + 1;
$rollNo  = trim($input_data["rollNo"] ?? ("STU-" . date("Y") . "-" . rand(100, 999)));

$new_student = [
    "id"           => $next_id,
    "rollNo"       => $rollNo,
    "name"         => trim($input_data["name"] ?? "Anonymous"),
    "age"          => intval($input_data["age"] ?? 20),
    "dob"          => trim($input_data["dob"] ?? ""),
    "gender"       => trim($input_data["gender"] ?? "Other"),
    "email"        => trim($input_data["email"] ?? ""),
    "phone"        => trim($input_data["phone"] ?? ""),
    "course"       => trim($input_data["course"] ?? "N/A"),
    "skills"       => is_array($input_data["skills"] ?? null) ? $input_data["skills"] : [],
    "city"         => trim($input_data["city"] ?? ""),
    "batchTime"    => trim($input_data["batchTime"] ?? ""),
    "address"      => $input_data["address"] ?? "",
    "registeredAt" => date("Y-m-d H:i:s")
];

// 4. Append to Master students.json
$students_list[] = $new_student;
$master_saved = file_put_contents($master_file, json_encode($students_list, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// 5. Save as a Separate Individual JSON File
$safe_roll = preg_replace("/[^a-zA-Z0-9_-]/", "_", $rollNo);
$individual_filename = "student_" . $safe_roll . ".json";
$individual_filepath = $records_dir . $individual_filename;

$individual_record = array_merge($new_student, [
    "savedAsSeparateFile" => true,
    "individualFilePath"  => "records/" . $individual_filename
]);

$individual_saved = file_put_contents($individual_filepath, json_encode($individual_record, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// 6. Return JSON response
echo json_encode([
    "status"               => "success",
    "message"              => "Form data successfully saved into JSON file(s)!",
    "masterFileUpdated"    => (bool)$master_saved,
    "masterFilePath"       => "students.json",
    "totalStudentsInMaster"=> count($students_list),
    "separateFileCreated"  => (bool)$individual_saved,
    "separateFilePath"     => "records/" . $individual_filename,
    "studentData"          => $new_student
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

exit();
?>
