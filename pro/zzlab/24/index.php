<?php
$xmlString = <<<XML
<bookstore>
  <book category="fiction">
    <title lang="en">The Great Gatsby</title>
    <author>F. Scott Fitzgerald</author>
    <price>12.99</price>
  </book>
  <book category="programming">
    <title lang="en">Learning PHP</title>
    <author>Jane Doe</author>
    <price>39.50</price>
  </book>
</bookstore>
XML;

$xml = simplexml_load_string($xmlString);

if ($xml !== false) {
    echo "<h1>Self-Contained Book List</h1><pre>";
    foreach ($xml->book as $book) {
        echo "Title: " . (string)$book->title . " (Category: " . (string)$book['category'] . ")\n";
        echo "Author: " . (string)$book->author . "\n";
        echo "Price: $" . (string)$book->price . "\n\n";
    }
    echo "</pre>";
} else {
    echo "Failed to load XML from string.";
}
?>
