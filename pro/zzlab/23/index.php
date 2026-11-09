<?php
$xmlData = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<user>
  <name>Devil Kumar</name>
  <email>devil.kumar@example.com</email>
  <city>Madurai</city>
</user>
XML;

$xml = simplexml_load_string($xmlData);
echo "Name: " . $xml->name . "<br>";
echo "Email: " . $xml->email . "<br>";
echo "City: " . $xml->city . "<br>";
?>
