require("dotenv").config();
const { MongoClient } = require("mongodb");

async function main() {
  const client = new MongoClient(process.env.MONGODB_URI);
  try {
    await client.connect();
    const students = client.db("CollegeDB").collection("students");
    const rollNo = "LAB-EXP5-DEMO";

    await students.updateOne(
      { rollNo },
      { $set: { name: "Test Student", department: "MCA", marks: 70 } },
      { upsert: true }
    );
    const updated = await students.updateOne(
      { rollNo }, { $set: { marks: 95 } }
    );
    console.log("Updated records:", updated.modifiedCount);
    console.log("After update:");
    console.table(await students.find({ rollNo }).toArray());

    const deleted = await students.deleteOne({ rollNo });
    console.log("Deleted records:", deleted.deletedCount);
    console.log("After deletion:");
    console.table(await students.find({ rollNo }).toArray());
  } catch (error) {
    console.error("Error:", error.message);
    process.exitCode = 1;
  } finally {
    await client.close();
  }
}
main();
