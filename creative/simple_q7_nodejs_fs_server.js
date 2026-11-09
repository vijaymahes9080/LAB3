/**
 * ==============================================================================
 * SIMPLE LAB EXAM Q7: NODE.JS HTTP SERVER & FILE SYSTEM (fs) OPERATIONS
 * ==============================================================================
 * Covers Syllabus Programs: 63, 64, 65 & 66
 * Question:
 * "Write a Node.js program to create an HTTP web server with URL routing
 *  and demonstrate asynchronous and synchronous file operations using
 *  the fs module (create, read, append, rename, and delete a file)."
 * ==============================================================================
 */

const http = require('http');
const fs = require('fs');
const path = require('path');

console.log('================================================================');
console.log('SIMPLE EXAM Q7: NODE.JS HTTP ROUTING & FILE SYSTEM OPERATIONS');
console.log('================================================================\n');

// 1. File System Operations Lifecycle (Programs 65 & 66)
const sampleFile = path.join(__dirname, 'simple_demo.txt');
const renamedFile = path.join(__dirname, 'simple_demo_renamed.txt');

console.log('--- 1. Demonstrating File System (fs) Operations ---');

// Write File
fs.writeFileSync(sampleFile, 'Initial line created via fs.writeFileSync()');
console.log('✔ File created: simple_demo.txt');

// Append File
fs.appendFileSync(sampleFile, '\nAppended line created via fs.appendFileSync()');
console.log('✔ Appended content to simple_demo.txt');

// Read File Asynchronously
fs.readFile(sampleFile, 'utf8', (err, data) => {
  if (err) return console.error('Error reading:', err);
  console.log('✔ Asynchronous read output:\n' + data);

  // Rename File
  fs.renameSync(sampleFile, renamedFile);
  console.log('✔ Renamed simple_demo.txt ➔ simple_demo_renamed.txt');

  // Delete File
  fs.unlinkSync(renamedFile);
  console.log('✔ Cleaned up / deleted renamed file.\n');
});

// 2. HTTP Server with Clean Routing (Programs 63 & 64)
const PORT = 3002;
const server = http.createServer((req, res) => {
  let body = '';
  let status = 200;

  if (req.url === '/') {
    body = '<h1>Simple Q7: Home Page</h1><p>Server running with Node.js http module.</p>';
  } else if (req.url === '/about') {
    body = '<h1>About Page</h1><p>Demonstrating Node.js basic route dispatcher.</p>';
  } else if (req.url === '/contact') {
    body = '<h1>Contact Page</h1><p>Department of Computer Science.</p>';
  } else {
    status = 404;
    body = '<h1>404 - Not Found</h1>';
  }

  res.writeHead(status, { 'Content-Type': 'text/html; charset=utf-8' });
  res.end(body);
});

server.listen(PORT, () => {
  console.log(`--- 2. HTTP Server Active ---`);
  console.log(`Server running at http://localhost:${PORT}`);
  console.log(`Try visiting: http://localhost:${PORT}/ or /about or /contact`);
  console.log(`(Press Ctrl+C to stop)`);
});

