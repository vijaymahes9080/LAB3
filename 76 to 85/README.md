# Web Technology Lab — 10 Experiments

This project contains the 10 lab experiments from the supplied syllabus:
AngularJS, Node.js, MongoDB, REST APIs, and MEAN-stack CRUD applications.

## Requirements
- Node.js (LTS recommended)
- MongoDB Community Server running locally, or a MongoDB Atlas URI
- Internet access in the browser for AngularJS 1.8.3 CDN scripts

## Setup
1. Extract the ZIP.
2. Open a terminal in this folder.
3. Install dependencies:

   ```bash
   npm install
   ```

4. Copy `.env.example` to `.env` and set `MONGODB_URI`.
5. Ensure MongoDB is running.
6. Start the API:

   ```bash
   npm start
   ```

7. Open the experiment URLs listed below.

## Experiments
| No. | Title | Run |
|---:|---|---|
| 1 | Develop a web application using AngularJS to display and filter student records. | Open `experiment-01/index.html` in a browser |
| 2 | Develop an AngularJS application to perform CRUD operations on student details using data binding and directives. | Open `experiment-02/index.html` in a browser |
| 3 | Create a Node.js application to connect to a NoSQL database and display database records. | `npm run exp3` |
| 4 | Write a Node.js program to insert and retrieve student records from MongoDB. | `npm run exp4` |
| 5 | Write a Node.js program to update and delete student records in MongoDB. | `npm run exp5` |
| 6 | Develop an AngularJS application to retrieve and display student data from a Node.js REST API. | http://localhost:3000/exp6.html |
| 7 | Develop a student registration application using AngularJS, Node.js and MongoDB. | http://localhost:3000/exp7.html |
| 8 | Develop a library management application to add, search, update and delete book details using AngularJS, Node.js and MongoDB. | http://localhost:3000/exp8.html |
| 9 | Develop an employee management application with database connectivity and CRUD operations. | http://localhost:3000/exp9.html |
| 10 | Develop a simple login and registration application with user details stored in MongoDB. | http://localhost:3000/exp10.html |

## Notes
- Experiments 1–2 are frontend-only demonstrations. Their data is held in browser memory and resets on reload.
- Experiments 3–5 require a running MongoDB server and use the `CollegeDB` database.
- Experiments 6–10 share `server.js` and use the `CollegeDB` database.
- Do not commit `.env` or real credentials. `.env` is gitignored.
- AngularJS 1.x is a legacy framework, retained here to match the syllabus.
- The login experiment hashes passwords using Node.js `scrypt`, but is only a teaching demonstration: it does not establish a session or issue a token. Do not use as production authentication.
