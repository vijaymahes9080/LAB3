<?php
$con = mysqli_connect("localhost", "root", "", "awpdb");
$name = $_POST["name"];
$email = $_POST["email"];
$sql = "INSERT INTO users(name, email) VALUES('$name', '$email')";
mysqli_query($con, $sql);
echo "<h2>User Saved Successfully!</h2>";
echo "<a href='index.html'>Go Back</a>";
?>
