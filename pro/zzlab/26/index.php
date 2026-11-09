<!DOCTYPE html>
<html>
<head>
<title>JavaScript Object Literal Demo</title>
<style>
body {
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
margin: 20px;
background-color: #f4f4f9;
}
.container {
max-width: 600px;
margin: 0 auto;
background-color: #fff;
padding: 20px;
border-radius: 8px;
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
h2 {
color: #333;
border-bottom: 2px solid #007bff;
padding-bottom: 10px;
}
.code-box {
background-color: #e9ecef;
padding: 15px;
border-radius: 4px;
margin-bottom: 20px;
overflow-x: auto;
}
.output-section p {
font-size: 1.1em;
margin: 10px 0;
}
.highlight {
font-weight: bold;
color: #28a745;
}
</style>
</head>
<body>
<div class="container">
<h2>JavaScript Object Access Demonstration</h2>
<p>The JavaScript Object (stored in the variable <code>book</code>):</p>
<div class="code-box">
<pre><code>
const book = {
title: "The Great Gatsby", author: "F. Scott Fitzgerald", year: 1925, "publisher-city": "New York", // Property with a hyphen
keywords: ["jazz", "wealth", "tragedy"]
};
</code></pre>
</div>
<hr>
<div class="output-section">
<h2>Accessing Object Values</h2>
<p>Title (Dot Notation): <span id="output-title" class="highlight"></span></p>
<p>Author (Dot Notation): <span id="output-author" class="highlight"></span></p><p>Publisher City (Bracket Notation - for hyphens): <span id="output-publisher"class="highlight"></span></p>
<p>First Keyword (Bracket Notation - accessing array): <span id="output-keyword"class="highlight"></span></p>
<p>Year (Dot Notation for operation): <span id="output-operation"class="highlight"></span></p>
</div>
</div>
<script>
const book = {
title: "The Great Gatsby", author: "F. Scott Fitzgerald", year: 1925, "publisher-city": "New York", keywords: ["jazz", "wealth", "tragedy"]
};
const bookTitle = book.title;
const bookAuthor = book.author
const publisherCity = book["publisher-city"];
const firstKeyword = book.keywords[0];
const age = 2024 - book.year;
document.getElementById('output-title').textContent = bookTitle + " (Code: book.title)";
document.getElementById('output-author').textContent = bookAuthor + " (Code: book.author)";
document.getElementById('output-publisher').textContent = publisherCity + " (Code:
book[\"publisher-city\"])";
document.getElementById('output-keyword').textContent = firstKeyword + " (Code:
book.keywords[0])";
document.getElementById('output-operation').textContent = age + " years old (2024- book.year)";
</script>
</body>
</html>