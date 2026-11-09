require("dotenv").config();
const { MongoClient } = require("mongodb");

async function main() {
  const client = new MongoClient(process.env.MONGODB_URI);
  try {
    await client.connect();
    console.log("MongoDB connected successfully");
    const db = client.db("CollegeDB");
    const records = await db.collection("students").find({}).toArray();
    console.log("Student Records:");
    console.table(records);
  } catch (error) {
    console.error("Error:", error.message);
    process.exitCode = 1;
  } finally {
    await client.close();
  }
}
main();
