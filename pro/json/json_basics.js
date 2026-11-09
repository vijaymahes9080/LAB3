/**
 * =======================================================================
 * JSON & JavaScript Core Operations Master Guide
 * Concepts Covered:
 * 1. JSON.parse()
 * 2. JSON.stringify()
 * 3. JSON Objects & Nested Objects
 * 4. Value Access using Statements (Dot, Bracket, Destructuring)
 * 5. Value Access using Looping (for...in, for...of, forEach, map, filter)
 * 6. Send HTML Form Data to JSON File
 * 7. Display / Populate JSON File Data to HTML Form Inputs
 * 8. Download / Store Form Data as Separate JSON File
 * =======================================================================
 */

// =======================================================================
// 1. JSON.parse() Demonstration
// =======================================================================
function demoJsonParse() {
    // A valid JSON formatted string
    const jsonString = `{
        "id": 101,
        "name": "Jane Doe",
        "course": "MCA",
        "isActive": true,
        "skills": ["JavaScript", "JSON", "React"]
    }`;

    // Convert JSON String -> JavaScript Object
    const jsObject = JSON.parse(jsonString);

    console.log("--- 1. JSON.parse() Output ---");
    console.log("Parsed Object:", jsObject);
    console.log("Student Name:", jsObject.name);
    console.log("First Skill:", jsObject.skills[0]);

    return {
        inputString: jsonString,
        parsedObject: jsObject,
        name: jsObject.name,
        type: typeof jsObject
    };
}

// =======================================================================
// 2. JSON.stringify() Demonstration
// =======================================================================
function demoJsonStringify() {
    // JavaScript Object
    const studentObject = {
        id: 202,
        name: "Michael Scott",
        age: 24,
        email: "michael@example.com",
        skills: ["HTML", "CSS", "PHP"],
        address: {
            city: "Scranton",
            state: "Pennsylvania"
        }
    };

    // Convert JS Object -> JSON String (with 2-space indentation formatting)
    const formattedJsonString = JSON.stringify(studentObject, null, 2);

    // Convert with a Replacer Filter (Only include name, email, skills)
    const filteredJsonString = JSON.stringify(studentObject, ["name", "email", "skills"], 2);

    console.log("--- 2. JSON.stringify() Output ---");
    console.log("Formatted JSON:\n", formattedJsonString);

    return {
        originalObject: studentObject,
        jsonString: formattedJsonString,
        filteredString: filteredJsonString
    };
}

// =======================================================================
// 3. Accessing JSON Values using STATEMENTS
// =======================================================================
function demoAccessStatements(student) {
    // Sample student data if not passed
    const data = student || {
        rollNo: "CSB-2026",
        name: "Alex Johnson",
        age: 21,
        email: "alex@example.com",
        course: "B.Sc Computer Science",
        skills: ["HTML", "CSS", "JavaScript"],
        address: {
            city: "Chennai",
            pincode: "600042"
        }
    };

    const results = [];

    // Statement 1: Dot Notation
    const dotName = data.name;
    results.push(`1. Dot Notation (data.name): "${dotName}"`);

    // Statement 2: Bracket Notation (Dynamic key access)
    const key = "email";
    const bracketEmail = data[key];
    results.push(`2. Bracket Notation (data["${key}"]): "${bracketEmail}"`);

    // Statement 3: Nested Property Access
    const nestedCity = data.address.city;
    results.push(`3. Nested Dot Notation (data.address.city): "${nestedCity}"`);

    // Statement 4: Array Index Access inside JSON
    const primarySkill = data.skills[0];
    results.push(`4. Array Index inside JSON (data.skills[0]): "${primarySkill}"`);

    // Statement 5: Object Destructuring Statement
    const { name, course, age } = data;
    results.push(`5. Destructuring Statement ({ name, course, age }): Name="${name}", Course="${course}", Age=${age}`);

    // Statement 6: Conditional / Ternary Statement with JSON
    const eligibility = data.age >= 18 ? "Eligible (Adult)" : "Underage";
    results.push(`6. Conditional Statement (age >= 18): "${eligibility}"`);

    return results;
}

// =======================================================================
// 4. Accessing JSON Values using LOOPING
// =======================================================================
function demoAccessLooping(studentsArray) {
    const list = studentsArray || [
        { id: 1, name: "Alex", course: "BSc CS", skills: ["HTML", "JS"] },
        { id: 2, name: "Sophia", course: "MCA", skills: ["Python", "PHP"] },
        { id: 3, name: "David", course: "BCA", skills: ["React", "Java"] }
    ];

    const logs = [];

    // --- Loop 1: for...in (Iterate over Object Keys & Values) ---
    logs.push("🔹 [1. for...in Loop] Iterating Object Keys for First Student:");
    const firstStudent = list[0];
    for (const propertyKey in firstStudent) {
        logs.push(`   - ${propertyKey} : ${JSON.stringify(firstStudent[propertyKey])}`);
    }

    // --- Loop 2: for...of (Iterate over Array of JSON Objects) ---
    logs.push("\n🔹 [2. for...of Loop] Iterating List of Students:");
    for (const student of list) {
        logs.push(`   - Student ID: ${student.id} | Name: ${student.name} | Course: ${student.course}`);
    }

    // --- Loop 3: Array.prototype.forEach() Method ---
    logs.push("\n🔹 [3. forEach() Loop] Iterating with Index:");
    list.forEach((student, index) => {
        logs.push(`   - [Index ${index}] ${student.name} enrolled in ${student.course}`);
    });

    // --- Loop 4: Array.prototype.map() Transformation ---
    logs.push("\n🔹 [4. map() Loop] Extracting All Student Names:");
    const allNames = list.map(student => student.name);
    logs.push(`   - Result: [ ${allNames.join(", ")} ]`);

    // --- Loop 5: Array.prototype.filter() Search ---
    logs.push("\n🔹 [5. filter() Loop] Filtering Students with 'MCA' Course:");
    const mcaStudents = list.filter(student => student.course === "MCA");
    logs.push(`   - Found: ${mcaStudents.map(s => s.name).join(", ")}`);

    return logs.join("\n");
}

// =======================================================================
// 5. Send HTML Form Data -> JSON File (via Fetch API / PHP)
// =======================================================================
async function sendFormDataToJson(formElement, saveAsSeparate = false) {
    const formData = new FormData(formElement);
    const formObject = {};

    // Collect Skills checkbox values as array
    const skills = [];
    formData.getAll("skills[]").forEach(s => skills.push(s));

    // Build Structured JSON Object
    formData.forEach((value, key) => {
        if (key !== "skills[]") {
            formObject[key] = value;
        }
    });
    formObject.skills = skills;
    formObject.saveAsSeparate = saveAsSeparate;
    formObject.timestamp = new Date().toISOString();

    // Convert JS Object to JSON String
    const jsonPayload = JSON.stringify(formObject, null, 2);

    try {
        const response = await fetch("save_json.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: jsonPayload
        });

        const result = await response.json();
        return { success: true, result, jsonPayload };
    } catch (error) {
        console.warn("Backend save_json.php not reachable or offline, returning payload locally:", error);
        return { success: true, isLocal: true, jsonPayload, result: { message: "Saved locally (Offline mode)", data: formObject } };
    }
}

// =======================================================================
// 6. Display Data from JSON File -> Populate HTML Form
// =======================================================================
function populateJsonToForm(jsonData, formElement) {
    if (!jsonData || !formElement) return false;

    // Direct Mapping for Form Elements
    if (formElement.elements["rollNo"]) formElement.elements["rollNo"].value = jsonData.rollNo || "";
    if (formElement.elements["name"]) formElement.elements["name"].value = jsonData.name || "";
    if (formElement.elements["age"]) formElement.elements["age"].value = jsonData.age || "";
    if (formElement.elements["dob"]) formElement.elements["dob"].value = jsonData.dob || "";
    if (formElement.elements["email"]) formElement.elements["email"].value = jsonData.email || "";
    if (formElement.elements["phone"]) formElement.elements["phone"].value = jsonData.phone || "";
    if (formElement.elements["course"]) formElement.elements["course"].value = jsonData.course || "";
    if (formElement.elements["city"]) formElement.elements["city"].value = jsonData.city || "";
    if (formElement.elements["batchTime"]) formElement.elements["batchTime"].value = jsonData.batchTime || "";
    
    // Address handling (if object or string)
    if (formElement.elements["address"]) {
        if (typeof jsonData.address === "object" && jsonData.address !== null) {
            formElement.elements["address"].value = `${jsonData.address.doorNo || ""}, ${jsonData.address.street || ""}, ${jsonData.address.city || ""}, ${jsonData.address.state || ""} - ${jsonData.address.pincode || ""}`.replace(/^, /, "");
        } else {
            formElement.elements["address"].value = jsonData.address || "";
        }
    }

    // Radio: Gender
    if (jsonData.gender) {
        const genderRadio = formElement.querySelector(`input[name="gender"][value="${jsonData.gender}"]`);
        if (genderRadio) genderRadio.checked = true;
    }

    // Checkbox: Skills
    const skillBoxes = formElement.querySelectorAll('input[name="skills[]"]');
    skillBoxes.forEach(box => {
        box.checked = Array.isArray(jsonData.skills) && jsonData.skills.includes(box.value);
    });

    return true;
}

// =======================================================================
// 7. Store / Download Form Data as a Separate JSON File
// =======================================================================
function downloadFormDataAsJsonFile(formElement) {
    const formData = new FormData(formElement);
    const formObject = {};

    const skills = [];
    formData.getAll("skills[]").forEach(s => skills.push(s));

    formData.forEach((value, key) => {
        if (key !== "skills[]") {
            formObject[key] = value;
        }
    });
    formObject.skills = skills;
    formObject.generatedAt = new Date().toLocaleString();

    // Convert to formatted JSON
    const jsonString = JSON.stringify(formObject, null, 2);

    // Create a Blob and trigger client-side download
    const blob = new Blob([jsonString], { type: "application/json" });
    const url = URL.createObjectURL(blob);

    const filename = `student_${(formObject.rollNo || "record").replace(/[^a-zA-Z0-9_-]/g, "_")}.json`;

    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);

    return { filename, jsonString };
}
