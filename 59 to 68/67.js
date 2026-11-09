const url = require("url");

const address = "http://localhost:3000/student?id=101&name=Vijay";

const result = url.parse(address, true);

console.log("Protocol:", result.protocol);
console.log("Host:", result.host);
console.log("Path:", result.pathname);
console.log("Query:", result.query);
console.log("Student ID:", result.query.id);
console.log("Student Name:", result.query.name);
