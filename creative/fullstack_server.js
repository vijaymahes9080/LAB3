/**
 * ==============================================================================
 * LAB EXAM LEVEL 6: FULL-STACK REST API BACKEND SERVICE
 * ==============================================================================
 * Implements Complete RESTful CRUD API corresponding to:
 * - Lab Record Experiments 1 to 10 (Experiments 76-85)
 * - Program 39 ($http Services)
 * Features:
 * - Endpoints: GET /api/students, POST /api/students, PUT /api/students/:id, DELETE /api/students/:id
 * - In-memory and file-based JSON database persistence (resilient zero-dependency execution)
 * - Static file serving for the AngularJS client interface
 * ==============================================================================
 */

const http = require('http');
const url = require('url');
const fs = require('fs');
const path = require('path');

const PORT = 3001;
const DB_FILE = path.join(__dirname, 'students_db.json');

// Default Seed Data
const defaultStudents = [
  { id: '101', name: 'Vijay Kumar', email: 'vijay@univ.edu', department: 'Computer Applications', gpa: 9.2, status: 'Active' },
  { id: '102', name: 'Asha Sundaram', email: 'asha@univ.edu', department: 'Computer Science', gpa: 8.8, status: 'Active' },
  { id: '103', name: 'Ravi Teja', email: 'ravi@univ.edu', department: 'Information Technology', gpa: 7.5, status: 'On Probation' },
  { id: '104', name: 'Priya Raman', email: 'priya@univ.edu', department: 'Data Science', gpa: 9.6, status: 'Active' }
];

// Load or Initialize Persistence
function loadData() {
  if (fs.existsSync(DB_FILE)) {
    try {
      return JSON.parse(fs.readFileSync(DB_FILE, 'utf8'));
    } catch (e) {
      console.warn('Error reading DB file, reinitializing with seed data.');
    }
  }
  fs.writeFileSync(DB_FILE, JSON.stringify(defaultStudents, null, 2));
  return defaultStudents;
}

function saveData(data) {
  fs.writeFileSync(DB_FILE, JSON.stringify(data, null, 2));
}

let students = loadData();

// Helper to parse JSON body
function getJsonBody(req) {
  return new Promise((resolve, reject) => {
    let body = '';
    req.on('data', chunk => { body += chunk.toString(); });
    req.on('end', () => {
      try {
        resolve(body ? JSON.parse(body) : {});
      } catch (err) {
        reject(err);
      }
    });
  });
}

const server = http.createServer(async (req, res) => {
  const parsedUrl = url.parse(req.url, true);
  const pathname = parsedUrl.pathname;
  const method = req.method;

  // CORS Headers
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (method === 'OPTIONS') {
    res.writeHead(204);
    res.end();
    return;
  }

  // API ROUTING
  if (pathname.startsWith('/api/students')) {
    res.setHeader('Content-Type', 'application/json');
    const idMatch = pathname.match(/^\/api\/students\/([^\/]+)$/);
    const targetId = idMatch ? idMatch[1] : null;

    // 1. GET /api/students -> Fetch all records or filtered by ?search=
    if (method === 'GET' && !targetId) {
      const q = (parsedUrl.query.search || '').toLowerCase();
      let results = students;
      if (q) {
        results = students.filter(s => s.name.toLowerCase().includes(q) || s.department.toLowerCase().includes(q) || s.id.includes(q));
      }
      res.writeHead(200);
      res.end(JSON.stringify(results));
      return;
    }

    // 2. GET /api/students/:id -> Fetch single student
    if (method === 'GET' && targetId) {
      const student = students.find(s => s.id === targetId);
      if (student) {
        res.writeHead(200);
        res.end(JSON.stringify(student));
      } else {
        res.writeHead(404);
        res.end(JSON.stringify({ error: 'Student not found' }));
      }
      return;
    }

    // 3. POST /api/students -> Create new record
    if (method === 'POST') {
      try {
        const payload = await getJsonBody(req);
        if (!payload.name || !payload.id) {
          res.writeHead(400);
          res.end(JSON.stringify({ error: 'ID and Name are mandatory' }));
          return;
        }
        const exists = students.some(s => s.id === payload.id);
        if (exists) {
          res.writeHead(409);
          res.end(JSON.stringify({ error: 'Student with this ID already exists' }));
          return;
        }
        const newRecord = {
          id: String(payload.id),
          name: payload.name,
          email: payload.email || `${payload.id}@univ.edu`,
          department: payload.department || 'General',
          gpa: parseFloat(payload.gpa) || 7.0,
          status: payload.status || 'Active'
        };
        students.push(newRecord);
        saveData(students);
        res.writeHead(201);
        res.end(JSON.stringify(newRecord));
      } catch (err) {
        res.writeHead(400);
        res.end(JSON.stringify({ error: 'Invalid JSON payload' }));
      }
      return;
    }

    // 4. PUT /api/students/:id -> Update record
    if (method === 'PUT' && targetId) {
      try {
        const payload = await getJsonBody(req);
        const index = students.findIndex(s => s.id === targetId);
        if (index === -1) {
          res.writeHead(404);
          res.end(JSON.stringify({ error: 'Student not found' }));
          return;
        }
        students[index] = { ...students[index], ...payload, id: targetId };
        saveData(students);
        res.writeHead(200);
        res.end(JSON.stringify(students[index]));
      } catch (err) {
        res.writeHead(400);
        res.end(JSON.stringify({ error: 'Invalid JSON payload' }));
      }
      return;
    }

    // 5. DELETE /api/students/:id -> Remove record
    if (method === 'DELETE' && targetId) {
      const index = students.findIndex(s => s.id === targetId);
      if (index === -1) {
        res.writeHead(404);
        res.end(JSON.stringify({ error: 'Student not found' }));
        return;
      }
      const removed = students.splice(index, 1);
      saveData(students);
      res.writeHead(200);
      res.end(JSON.stringify({ message: 'Deleted successfully', student: removed[0] }));
      return;
    }
  }

  // Serve the static frontend HTML if requested
  if (pathname === '/' || pathname === '/exam6_fullstack_crud.html') {
    const filePath = path.join(__dirname, 'exam6_fullstack_crud.html');
    if (fs.existsSync(filePath)) {
      res.writeHead(200, { 'Content-Type': 'text/html' });
      res.end(fs.readFileSync(filePath));
      return;
    }
  }

  res.writeHead(404, { 'Content-Type': 'application/json' });
  res.end(JSON.stringify({ error: 'Route not found' }));
});

server.listen(PORT, () => {
  console.log(`========================================================`);
  console.log(`🌐 Full-Stack REST API Server active at http://localhost:${PORT}`);
  console.log(`Endpoints:`);
  console.log(`  GET    http://localhost:${PORT}/api/students`);
  console.log(`  POST   http://localhost:${PORT}/api/students`);
  console.log(`  PUT    http://localhost:${PORT}/api/students/:id`);
  console.log(`  DELETE http://localhost:${PORT}/api/students/:id`);
  console.log(`========================================================`);
});

