<?php 
$name = $_POST['name']; 
$age = $_POST['age']; 
$data = array("name"=>$name, "age"=>$age); 
$fileName = "record_" . time() . ".json"; 
file_put_contents($fileName, json_encode($data)); 
echo "Record saved as $fileName"; 
?>