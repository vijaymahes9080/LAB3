const os = require("os");
const path = require("path");
const util = require("util");

console.log("Operating System:", os.platform());
console.log("Home Directory:", os.homedir());

console.log("Path:", path.join("NodeJS", "program.js"));

console.log("Message:", util.format("Hello %s", "Node.js"));
