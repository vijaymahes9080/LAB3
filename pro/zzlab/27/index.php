<!DOCTYPE html>
<html>
<head>
<title>JSON to Array Demo</title>
</head>
<body>
<h1>Parsing JSON String to JavaScript Array</h1>
<p>JSON String (input): <code id="json-string"></code></p>
<p>Parsed Array Length: <span id="array-length"></span></p>
<p>First Element (Index 0): <span id="first-element"></span></p>
<p>Last Element (Index 2): <span id="last-element"></span></p>
<script>
const jsonString = '[ "Apple", "Banana", "Cherry", "Date" ]';
document.getElementById('json-string').textContent = jsonString;
const fruitArray = JSON.parse(jsonString);
document.getElementById('array-length').textContent = fruitArray.length;
document.getElementById('first-element').textContent = fruitArray[0];
document.getElementById('last-element').textContent = fruitArray[fruitArray.length- 1];
console.log("Parsed Array:", fruitArray);
</script>
</body>
</html>