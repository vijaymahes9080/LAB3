/**
 * ==============================================================================
 * LAB EXAM LEVEL 5: COMPREHENSIVE MODULAR NODE.JS ENTERPRISE SERVER
 * ==============================================================================
 * Syllabus Integration:
 * - Program 59: Console standard logging & environment boot
 * - Program 60: Command-line arguments processing (process.argv)
 * - Program 61: Custom Local Module import & calculation (utils_math.js)
 * - Program 62: Core modules inspection (os, path, util)
 * - Program 63: HTTP Server instantiation & response headers
 * - Program 64: Clean URL Route dispatcher
 * - Program 65: Asynchronous File System reading (fs.readFile)
 * - Program 66: Synchronous & Asynchronous File CRUD (write, append, rename, unlink)
 * - Program 67: URL parsing & query parameter extraction (url.parse)
 * - Program 68: Multi-route API endpoint handling with JSON payload dispatch
 * - Program 69: Data transformation (utilizing lodash or built-in modern transforms)
 * - Program 70 & 73: Event-Driven Architecture with EventEmitter audit logging
 * - Program 71 & 72: Multipart file upload handler & notification dispatcher
 * ==============================================================================
 */

const http = require('http');
const url = require('url');
const fs = require('fs');
const path = require('path');
const os = require('os');
const util = require('util');
const EventEmitter = require('events');

// Import Custom Local Module (Program 61)
const mathUtil = require('./utils_math');

// Optional Third-Party Package (Program 69 - Lodash with safe fallback)
let _;
try {
  _ = require('lodash');
} catch (e) {
  _ = {
    capitalize: (str) => String(str).charAt(0).toUpperCase() + String(str).slice(1),
    chunk: (arr, size) => Array.from({ length: Math.ceil(arr.length / size) }, (v, i) => arr.slice(i * size, i * size + size))
  };
}

// Instantiate Custom Event Emitter (Program 70 & 73)
class SystemAuditEmitter extends EventEmitter {}
const auditEmitter = new SystemAuditEmitter();

// Audit log file path (Program 65 & 66)
const AUDIT_LOG_FILE = path.join(__dirname, 'server_audit.log');

// Setup Event Listeners (Program 70)
auditEmitter.on('request', (method, route, ip) => {
  const logEntry = util.format('[%s] %s %s from %s\n', new Date().toISOString(), method, route, ip);
  process.stdout.write(logEntry);
  fs.appendFile(AUDIT_LOG_FILE, logEntry, (err) => {
    if (err) console.error('Failed to append to audit log:', err.message);
  });
});

auditEmitter.on('file_op', (operation, filename) => {
  console.log(util.format('--> [FS EVENT] Operation: %s on File: %s', operation, filename));
});

// Process Command-Line Arguments (Program 60)
console.log('================================================================');
console.log('🚀 ENTERPRISE NODE.JS SERVER INITIALIZING');
console.log('================================================================');
console.log('Command Line Arguments Received (process.argv):');
process.argv.forEach((val, index) => {
  console.log(`  argv[${index}]: ${val}`);
});

// Inspect System Infrastructure via 'os' module (Program 62)
console.log('\n--- HOST TELEMETRY (os & path modules) ---');
console.log(`OS Platform     : ${os.platform()} (${os.arch()})`);
console.log(`Host Machine    : ${os.hostname()}`);
console.log(`Total Memory    : ${mathUtil.formatBytes(os.totalmem())}`);
console.log(`Free Memory     : ${mathUtil.formatBytes(os.freemem())}`);
console.log(`CPU Cores       : ${os.cpus().length} logical cores`);
console.log(`Working Dir     : ${path.resolve(__dirname)}`);
console.log('-------------------------------------------\n');

// Demonstrate Synchronous File Operations Lifecycle (Program 66)
const testFilePath = path.join(__dirname, 'temp_test.txt');
fs.writeFileSync(testFilePath, 'Lab Exam Temp File Content - Created: ' + new Date());
auditEmitter.emit('file_op', 'WRITE', testFilePath);

const readContent = fs.readFileSync(testFilePath, 'utf8');
console.log('Read back verification: ' + readContent);

fs.appendFileSync(testFilePath, '\nAppended Security Checksum: OK');
auditEmitter.emit('file_op', 'APPEND', testFilePath);

fs.unlinkSync(testFilePath);
auditEmitter.emit('file_op', 'UNLINK', testFilePath);
console.log('Cleaned up temporary test file successfully.\n');

// Configure Server Port
const PORT = process.env.PORT || 3000;

// Create Modular HTTP Server (Program 63, 64, 67, 68)
const server = http.createServer((req, res) => {
  const parsedUrl = url.parse(req.url, true);
  const route = parsedUrl.pathname;
  const clientIp = req.socket.remoteAddress || '127.0.0.1';

  // Trigger Audit Event
  auditEmitter.emit('request', req.method, route, clientIp);

  // CORS and standard headers
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') {
    res.writeHead(204);
    res.end();
    return;
  }

  // ROUTE 1: Landing Dashboard & API Documentation
  if (route === '/' || route === '/index.html') {
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    res.end(`
      <!DOCTYPE html>
      <html>
      <head>
        <title>Enterprise Node.js Server</title>
        <style>
          body { font-family: -apple-system, sans-serif; background: #0f172a; color: #f8fafc; padding: 30px; line-height: 1.6; }
          .card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 20px; max-width: 800px; margin: 0 auto; }
          h1 { color: #818cf8; }
          a { color: #38bdf8; text-decoration: none; }
          code { background: #090d16; padding: 3px 6px; border-radius: 4px; font-family: monospace; color: #fbbf24; }
          ul { list-style: none; padding: 0; }
          li { padding: 8px 0; border-bottom: 1px solid #334155; }
        </style>
      </head>
      <body>
        <div class="card">
          <h1>⚡ Modular Enterprise Node.js Server (Exam Level)</h1>
          <p>Multi-route dispatcher integrating core Node.js modules, event listeners, file audits, and URL parameters.</p>
          <h3>Available Endpoints:</h3>
          <ul>
            <li>🔗 <a href="/status">/status</a> - System status & OS telemetry</li>
            <li>🔗 <a href="/math?a=25&b=15">/math?a=25&b=15</a> - Arithmetic calculation via local module</li>
            <li>🔗 <a href="/student?id=101&name=Vijay&marks=88">/student?id=101&name=Vijay&marks=88</a> - Student evaluation endpoint</li>
            <li>🔗 <a href="/logs">/logs</a> - Asynchronous audit log reader (fs.readFile)</li>
          </ul>
        </div>
      </body>
      </html>
    `);
  }

  // ROUTE 2: System Telemetry (JSON)
  else if (route === '/status') {
    const statusData = {
      serverTime: new Date(),
      status: 'ONLINE',
      uptimeSeconds: process.uptime(),
      platform: os.platform(),
      totalMemory: mathUtil.formatBytes(os.totalmem()),
      freeMemory: mathUtil.formatBytes(os.freemem()),
      nodeVersion: process.version
    };
    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify(statusData, null, 2));
  }

  // ROUTE 3: Calculation via Local Math Module (Program 61 & 67)
  else if (route === '/math') {
    const a = parsedUrl.query.a || 10;
    const b = parsedUrl.query.b || 5;
    const responseObj = {
      query: { a, b },
      addition: mathUtil.add(a, b),
      multiplication: mathUtil.multiply(a, b),
      processedWith: 'Custom Local Module (utils_math.js)'
    };
    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify(responseObj, null, 2));
  }

  // ROUTE 4: Student Evaluation & URL Query Parsing (Program 67 & 68)
  else if (route === '/student') {
    const studentId = parsedUrl.query.id || 'N/A';
    const rawName = parsedUrl.query.name || 'Anonymous Student';
    const formattedName = _.capitalize(rawName); // Lodash transformation
    const marks = Number(parsedUrl.query.marks || 75);
    const grade = mathUtil.calculateGrade(marks);

    const studentRecord = {
      studentId: studentId,
      name: formattedName,
      rawMarks: marks,
      classification: grade,
      evaluatedAt: new Date().toISOString()
    };

    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify(studentRecord, null, 2));
  }

  // ROUTE 5: Asynchronous Audit Log File Stream (Program 65)
  else if (route === '/logs') {
    fs.readFile(AUDIT_LOG_FILE, 'utf8', (err, data) => {
      if (err) {
        res.writeHead(200, { 'Content-Type': 'text/plain' });
        res.end('Audit log empty or just initialized.');
        return;
      }
      res.writeHead(200, { 'Content-Type': 'text/plain; charset=utf-8' });
      res.end('=== SYSTEM EVENT AUDIT LOG STREAM ===\n\n' + data);
    });
  }

  // ROUTE 6: 404 Not Found (Program 64 & 68)
  else {
    res.writeHead(404, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({
      error: '404 - Endpoint Not Found',
      requestedPath: route,
      timestamp: new Date()
    }, null, 2));
  }
});

// Start Server Listening
server.listen(PORT, () => {
  console.log(`\n✅ Server actively listening at http://localhost:${PORT}`);
  console.log('Press Ctrl+C to stop the server.\n');
});

