const express = require("express");
const multer = require("multer");

const app = express();
const upload = multer({ dest: "uploads/" });

app.get("/", (req, res) => {
  res.send(`<form method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <button>Upload</button>
  </form>`);
});

app.post("/upload", upload.single("file"), (req, res) => {
  res.send("File uploaded!");
});

app.listen(3000);