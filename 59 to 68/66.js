const fs = require("fs");

// Write file
fs.writeFileSync("test.txt", "Hello Node.js");

// Append file
fs.appendFileSync("test.txt", "\nWelcome to Node.js");

// Read file
console.log("File Content:");
console.log(fs.readFileSync("test.txt", "utf8"));

// Rename file
fs.renameSync("test.txt", "newtest.txt");

console.log("File renamed successfully");

// Delete renamed file
fs.unlinkSync("newtest.txt");

console.log("File deleted successfully");