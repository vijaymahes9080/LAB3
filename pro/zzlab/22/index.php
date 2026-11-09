<?php
$xmlString = <<<XML
<bookstore>
  <book category="fiction">
    <title lang="en">END</title>
    <author>vijay malik</author>
    <year>1925</year>
    <price>12.99</price>
  </book>
</bookstore>
XML;

$xml = simplexml_load_string($xmlString);

if ($xml !== false) {
    echo "<h1>Book List</h1><pre>";

    foreach ($xml->book as $book) {
        echo "Title: " . (string)$book->title . "\n";
        echo "Category: " . (string)$book['category'] . "\n";
        echo "Author: " . (string)$book->author . "\n";
        echo "Year: " . (string)$book->year . "\n";
        echo "Price: $" . (string)$book->price . "\n\n";
    }

    echo "</pre>";
} else {
    echo "Failed to load XML.";
}
?>