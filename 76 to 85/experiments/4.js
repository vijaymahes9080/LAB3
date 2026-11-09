require("dotenv").config();
const { MongoClient } = require("mongodb");

async function main() {
  const client = new MongoClient(process.env.MONGODB_URI);
  try {
    await client.connect();
    const students = client.db("CollegeDB").collection("students");
    const student = {
      rollNo: `MCA-${Date.now()}`,
      name: "Arun",
      department: "MCA",
      marks: 85
    };
    await students.insertOne(student);
    console.log("Student inserted successfully");
    console.table(await students.find({}).toArray());
  } catch (error) {
    console.error("Error:", error.message);
    process.exitCode = 1;
  } finally {
    await client.close();
  }
}
main();
