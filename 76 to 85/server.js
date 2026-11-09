require("dotenv").config();
const express = require("express");
const { MongoClient, ObjectId } = require("mongodb");
const crypto = require("crypto");
const path = require("path");

const app = express();
app.use(express.json({ limit: "100kb" }));
app.use(express.static(path.join(__dirname, "public")));

let db;

function asyncRoute(fn) {
  return (req, res, next) => Promise.resolve(fn(req, res, next)).catch(next);
}
function validId(id) {
  return ObjectId.isValid(id) &&
    new ObjectId(id).toHexString() === id.toLowerCase();
}
function collection(name) {
  return db.collection(name);
}
function escapeRegex(value) {
  return value.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
}

function makeCrud(route, name, fields, searchField) {
  const col = () => collection(name);

  app.get(route, asyncRoute(async (req, res) => {
    const filter = {};
    if (typeof req.query.q === "string" && req.query.q.trim()) {
      filter[searchField] = {
        $regex: escapeRegex(req.query.q.trim()),
        $options: "i"
      };
    }
    res.json(await col().find(filter).limit(200).toArray());
  }));

  app.post(route, asyncRoute(async (req, res) => {
    const data = {};
    for (const field of fields) {
      const value = req.body[field];
      if (value === undefined || String(value).trim() === "") {
        return res.status(400).json({ error: `${field} is required` });
      }
      data[field] = String(value).trim();
    }
    if (name === "students" &&
        await col().findOne({ rollNo: data.rollNo })) {
      return res.status(409).json({ error: "Roll number already exists" });
    }
    if (name === "employees" &&
        await col().findOne({ employeeId: data.employeeId })) {
      return res.status(409).json({ error: "Employee ID already exists" });
    }
    const result = await col().insertOne(data);
    res.status(201).json({ ...data, _id: result.insertedId });
  }));

  app.put(`${route}/:id`, asyncRoute(async (req, res) => {
    if (!validId(req.params.id)) {
      return res.status(400).json({ error: "Invalid record ID" });
    }
    const data = {};
    for (const field of fields) {
      const value = req.body[field];
      if (value === undefined || String(value).trim() === "") {
        return res.status(400).json({ error: `${field} is required` });
      }
      data[field] = String(value).trim();
    }
    const result = await col().updateOne(
      { _id: new ObjectId(req.params.id) },
      { $set: data }
    );
    if (!result.matchedCount) {
      return res.status(404).json({ error: "Record not found" });
    }
    res.json({ message: "Record updated successfully" });
  }));

  app.delete(`${route}/:id`, asyncRoute(async (req, res) => {
    if (!validId(req.params.id)) {
      return res.status(400).json({ error: "Invalid record ID" });
    }
    const result = await col().deleteOne({
      _id: new ObjectId(req.params.id)
    });
    if (!result.deletedCount) {
      return res.status(404).json({ error: "Record not found" });
    }
    res.json({ message: "Record deleted successfully" });
  }));
}

makeCrud("/api/students", "students",
  ["rollNo", "name", "department"], "name");
makeCrud("/api/books", "books",
  ["title", "author", "isbn"], "title");
makeCrud("/api/employees", "employees",
  ["employeeId", "name", "department"], "name");

app.post("/api/register", asyncRoute(async (req, res) => {
  const { username, password } = req.body;
  if (typeof username !== "string" || typeof password !== "string" ||
      username.trim().length < 3 || password.length < 8) {
    return res.status(400).json({
      error: "Username must be at least 3 characters and password at least 8 characters"
    });
  }
  const users = collection("users");
  const key = username.trim().toLowerCase();
  if (await users.findOne({ username: key })) {
    return res.status(409).json({ error: "Username already exists" });
  }
  const salt = crypto.randomBytes(16).toString("hex");
  const hash = await new Promise((resolve, reject) => {
    crypto.scrypt(password, salt, 64, (err, derived) => {
      if (err) reject(err);
      else resolve(derived.toString("hex"));
    });
  });
  await users.insertOne({ username: key, salt, hash });
  res.status(201).json({ message: "Registration successful" });
}));

app.post("/api/login", asyncRoute(async (req, res) => {
  const { username, password } = req.body;
  if (typeof username !== "string" || typeof password !== "string") {
    return res.status(400).json({ error: "Enter username and password" });
  }
  const user = await collection("users").findOne({
    username: username.trim().toLowerCase()
  });
  if (!user) {
    return res.status(401).json({ error: "Invalid credentials" });
  }
  const hash = await new Promise((resolve, reject) => {
    crypto.scrypt(password, user.salt, 64, (err, derived) => {
      if (err) reject(err);
      else resolve(derived.toString("hex"));
    });
  });
  const expected = Buffer.from(user.hash, "hex");
  const actual = Buffer.from(hash, "hex");
  if (expected.length !== actual.length ||
      !crypto.timingSafeEqual(actual, expected)) {
    return res.status(401).json({ error: "Invalid credentials" });
  }
  res.json({
    message: "Login successful",
    username: user.username
  });
}));

app.use((err, req, res, next) => {
  console.error(err);
  res.status(500).json({ error: "Internal server error" });
});

async function start() {
  if (!process.env.MONGODB_URI) {
    throw new Error("MONGODB_URI is missing. Copy .env.example to .env.");
  }
  const client = new MongoClient(process.env.MONGODB_URI);
  await client.connect();
  db = client.db("CollegeDB");
  const port = Number(process.env.PORT) || 3000;
  app.listen(port, () => console.log(`Server running at http://localhost:${port}`));
}
start().catch(error => {
  console.error("Could not start server:", error.message);
  process.exit(1);
});
