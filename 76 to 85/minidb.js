const fs = require("fs");
const path = require("path");
const crypto = require("crypto");

// 24-character hex ObjectId compatible with MongoDB
class ObjectId {
  constructor(id) {
    if (id instanceof ObjectId) return id;
    if (id) {
      this.id = String(id);
    } else {
      this.id = crypto.randomBytes(12).toString("hex");
    }
  }

  toString() {
    return this.id;
  }

  toHexString() {
    return this.id;
  }

  toJSON() {
    return this.id;
  }

  valueOf() {
    return this.id;
  }

  equals(other) {
    if (!other) return false;
    const str = other instanceof ObjectId ? other.id : String(other);
    return this.id === str;
  }

  static isValid(id) {
    if (!id) return false;
    if (id instanceof ObjectId) return true;
    if (typeof id !== "string") return false;
    return /^[0-9a-fA-F]{24}$/.test(id);
  }
}

class MiniCollection {
  constructor(dbDir, name) {
    this.name = name;
    this.filePath = path.join(dbDir, `${name}.json`);
    this.dbDir = dbDir;
    this._ensureFile();
  }

  _ensureFile() {
    if (!fs.existsSync(this.dbDir)) {
      fs.mkdirSync(this.dbDir, { recursive: true });
    }
    if (!fs.existsSync(this.filePath)) {
      // Default initial data for students so Exp 3 has data immediately
      let initialData = [];
      if (this.name === "students") {
        initialData = [
          { _id: new ObjectId().toString(), rollNo: "MCA101", name: "Arun", department: "MCA", marks: 85 },
          { _id: new ObjectId().toString(), rollNo: "BCA102", name: "Priya", department: "BCA", marks: 90 },
          { _id: new ObjectId().toString(), rollNo: "MCA103", name: "Kumar", department: "MCA", marks: 78 }
        ];
      } else if (this.name === "books") {
        initialData = [
          { _id: new ObjectId().toString(), title: "Modern Web Development", author: "John Smith", isbn: "978-0134685991" },
          { _id: new ObjectId().toString(), title: "Database Systems", author: "C. J. Date", isbn: "978-0321197849" }
        ];
      } else if (this.name === "employees") {
        initialData = [
          { _id: new ObjectId().toString(), employeeId: "EMP001", name: "Rajesh Kumar", department: "Engineering" },
          { _id: new ObjectId().toString(), employeeId: "EMP002", name: "Ananya Sharma", department: "Design" }
        ];
      }
      fs.writeFileSync(this.filePath, JSON.stringify(initialData, null, 2), "utf8");
    }
  }

  _read() {
    this._ensureFile();
    try {
      const data = fs.readFileSync(this.filePath, "utf8");
      return JSON.parse(data || "[]");
    } catch (err) {
      return [];
    }
  }

  _write(data) {
    this._ensureFile();
    const tempFile = this.filePath + ".tmp";
    fs.writeFileSync(tempFile, JSON.stringify(data, null, 2), "utf8");
    fs.renameSync(tempFile, this.filePath);
  }

  _match(doc, query) {
    if (!query || Object.keys(query).length === 0) return true;
    for (const key of Object.keys(query)) {
      const condition = query[key];
      const docVal = doc[key];

      // Match ObjectId
      if (key === "_id") {
        const queryId = condition instanceof ObjectId ? condition.toString() : String(condition);
        const docId = docVal instanceof ObjectId ? docVal.toString() : String(docVal);
        if (docId !== queryId) return false;
        continue;
      }

      // Regex condition
      if (condition && typeof condition === "object" && "$regex" in condition) {
        const pattern = new RegExp(condition.$regex, condition.$options || "");
        if (!pattern.test(String(docVal || ""))) return false;
        continue;
      }

      // Exact match
      if (docVal !== condition) {
        return false;
      }
    }
    return true;
  }

  find(query = {}) {
    let limitCount = Infinity;
    const self = this;

    const cursor = {
      limit(n) {
        limitCount = n;
        return cursor;
      },
      async toArray() {
        const docs = self._read();
        const matches = docs.filter(doc => self._match(doc, query));
        return matches.slice(0, limitCount);
      }
    };
    return cursor;
  }

  async findOne(query = {}) {
    const docs = this._read();
    return docs.find(doc => this._match(doc, query)) || null;
  }

  async insertOne(doc) {
    const docs = this._read();
    const newDoc = { ...doc };
    if (!newDoc._id) {
      newDoc._id = new ObjectId().toString();
    } else if (newDoc._id instanceof ObjectId) {
      newDoc._id = newDoc._id.toString();
    }
    docs.push(newDoc);
    this._write(docs);
    return { acknowledged: true, insertedId: newDoc._id };
  }

  async updateOne(filter, update, options = {}) {
    const docs = this._read();
    const index = docs.findIndex(doc => this._match(doc, filter));

    if (index === -1) {
      if (options.upsert) {
        const newDoc = { ...filter };
        if (update.$set) {
          Object.assign(newDoc, update.$set);
        }
        if (!newDoc._id) {
          newDoc._id = new ObjectId().toString();
        }
        docs.push(newDoc);
        this._write(docs);
        return { matchedCount: 0, modifiedCount: 0, upsertedCount: 1, upsertedId: newDoc._id };
      }
      return { matchedCount: 0, modifiedCount: 0, upsertedCount: 0 };
    }

    if (update.$set) {
      Object.assign(docs[index], update.$set);
    }
    this._write(docs);
    return { matchedCount: 1, modifiedCount: 1, upsertedCount: 0 };
  }

  async deleteOne(filter) {
    const docs = this._read();
    const index = docs.findIndex(doc => this._match(doc, filter));
    if (index === -1) {
      return { acknowledged: true, deletedCount: 0 };
    }
    docs.splice(index, 1);
    this._write(docs);
    return { acknowledged: true, deletedCount: 1 };
  }

  async deleteMany(filter) {
    const docs = this._read();
    const remaining = docs.filter(doc => !this._match(doc, filter));
    const deletedCount = docs.length - remaining.length;
    this._write(remaining);
    return { acknowledged: true, deletedCount };
  }
}

class MiniDb {
  constructor(dbName, baseDir = path.join(process.cwd(), "data")) {
    this.dbName = dbName;
    this.dbDir = path.join(baseDir, dbName);
  }

  collection(name) {
    return new MiniCollection(this.dbDir, name);
  }
}

class MiniMongoClient {
  constructor(uri) {
    this.uri = uri || "mongodb://127.0.0.1:27017";
    this.connected = false;
    this.nativeClient = null;
    this.useNative = false;
  }

  async connect() {
    // Try connecting to real MongoDB if mongodb package is available and mongod is alive
    try {
      const nativeMongo = require("mongodb");
      if (nativeMongo && nativeMongo.MongoClient && !process.env.FORCE_MINI_DB) {
        const client = new nativeMongo.MongoClient(this.uri, {
          serverSelectionTimeoutMS: 800,
          connectTimeoutMS: 800
        });
        await client.connect();
        // Ping to ensure server is actually reachable
        await client.db("admin").command({ ping: 1 });
        this.nativeClient = client;
        this.useNative = true;
        this.connected = true;
        console.log("MongoDB connected successfully (Live MongoDB server)");
        return this;
      }
    } catch (e) {
      // Live MongoDB not available, smoothly fall back to MiniDB file storage
    }

    this.connected = true;
    console.log("MongoDB connected successfully [File-based MiniDB: ./data/CollegeDB/]");
    return this;
  }

  db(name = "CollegeDB") {
    if (this.useNative && this.nativeClient) {
      return this.nativeClient.db(name);
    }
    return new MiniDb(name);
  }

  async close() {
    if (this.useNative && this.nativeClient) {
      await this.nativeClient.close();
    }
    this.connected = false;
  }
}

module.exports = {
  MongoClient: MiniMongoClient,
  ObjectId
};
