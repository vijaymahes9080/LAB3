const http = require("http");
const url = require("url");

const server = http.createServer((req, res) => {

    const path = url.parse(req.url).pathname;

    let status = 200;
    let body = "";

    if (path === "/") {
        body = "<h1>Home Page</h1>";
    }
    else if (path === "/about") {
        body = "<h1>About Page</h1>";
    }
    else if (path === "/contact") {
        body = "<h1>Contact Page</h1>";
    }
    else if (path === "/student") {
        body = "<h1>Student Page</h1>";
    }
    else {
        status = 404;
        body = "<h1>404 - Page Not Found</h1>";
    }

    res.writeHead(status, {
        "Content-Type": "text/html"
    });

    res.end(body);
});

server.listen(3000, () => {
    console.log("Server running at http://localhost:3000");
});
