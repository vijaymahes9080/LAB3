const http = require("http");
const url = require("url");
const fs = require("fs");
const EventEmitter = require("events");

const e = new EventEmitter();

e.on("visit", () => console.log("Page visited"));

http.createServer((req, res) => {
  let path = url.parse(req.url).pathname;

  e.emit("visit");

  if (path == "/") {
    res.write("Home Page");
  } else if (path == "/about") {
    res.write("About Page");
  } else {
    res.writeHead(404);
    res.write("Page Not Found");
  }

  res.end();
}).listen(3000);

console.log("Server running at http://localhost:3000");