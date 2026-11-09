<?php
$xml = $_POST["xmlData"];
// Save the XML string into a file
file_put_contents("user.xml", $xml);
echo "XML File Saved Successfully!";
?>

