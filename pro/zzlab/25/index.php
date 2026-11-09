<?php
$xmlString = <<<XML
<bookstore>
  <book category="fiction">
    <title lang="en">The Great Gatsby</title>
    <author>F. Scott Fitzgerald</author>
    <year>1925</year>
    <pages>180</pages>
    <price>12.99</price>
  </book>
  <book category="programming">
    <title lang="en">Learning PHP</title>
    <author>Jane Doe</author>
    <year>2023</year>
    <pages>550</pages>
    <price>39.50</price>
  </book>
</bookstore>
XML;

$xml = simplexml_load_string($xmlString);

if ($xml !== false) {
    echo "<h1>Detailed Book List (Concise Loop)</h1><pre>";
    foreach ($xml->book as $book) {
        echo "--- " . (string)$book->title . " (Category: " . (string)$book['category'] . ") ---\n";
        foreach ($book as $key => $value) {
            echo ucfirst($key) . ": " . (string)$value . "\n";
        }
        echo "\n";
    }
    echo "</pre>";
} else {
    echo "Failed to load XML from string.";
}
?>
