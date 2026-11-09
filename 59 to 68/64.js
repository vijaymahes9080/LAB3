const http = require("http");

const server = http.createServer((req, res) => {

    let status = 200;
    let body = "";

    if (req.url === "/") {
        body = "<h1>Home Page</h1>";
    }
    else if (req.url === "/about") {
        body = "<h1>About Page</h1>";
    }
    else if (req.url === "/contact") {
        body = "<h1>Contact Page</h1>";
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
