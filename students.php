<?php
header('Content-Type: application/json');
// Example data array
$students = [
["name" => "Sam", "age" => 20, "course" => "CSE"],
["name" => "Asha", "age" => 21, "course" => "ECE"], ["name" => "Ravi", "age" => 22, "course" => "ME"], ["name" => "Kiran", "age" => 19, "course" => "IT"]
];
// Output JSON (pretty print optional)
echo json_encode($students, JSON_PRETTY_PRINT);