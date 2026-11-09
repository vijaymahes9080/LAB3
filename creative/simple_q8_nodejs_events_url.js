/**
 * ==============================================================================
 * SIMPLE LAB EXAM Q8: NODE.JS URL MODULE & EVENT HANDLING (EventEmitter)
 * ==============================================================================
 * Covers Syllabus Programs: 67, 68, 70 & 73
 * Question:
 * "Write a Node.js program to illustrate URL parsing to extract query string
 *  parameters and implement custom event handling using the EventEmitter class."
 * ==============================================================================
 */

const http = require('http');
const url = require('url');
const EventEmitter = require('events');

console.log('================================================================');
console.log('SIMPLE EXAM Q8: NODE.JS URL PARSING & EVENT HANDLING');
console.log('================================================================\n');

// 1. URL Parsing Demonstration (Program 67)
console.log('--- 1. URL Module Parsing ---');
const sampleUrl = 'http://localhost:3000/student?id=101&name=Vijay&course=MCA';
const parsed = url.parse(sampleUrl, true);

console.log('Original Address :', sampleUrl);
console.log('Protocol         :', parsed.protocol);
console.log('Host             :', parsed.host);
console.log('Pathname         :', parsed.pathname);
console.log('Extracted Query  :', parsed.query);
console.log('Student ID       :', parsed.query.id);
console.log('Student Name     :', parsed.query.name);
console.log('Student Course   :', parsed.query.course);

// 2. Custom Event Emitter (Program 70 & 73)
console.log('\n--- 2. Custom EventEmitter Demonstration ---');
const customEmitter = new EventEmitter();

let visitCounter = 0;

// Register event listeners
customEmitter.on('welcome', (user) => {
  console.log(`[EVENT: welcome] Welcome to Practical Exam, ${user}!`);
});

customEmitter.on('page_visit', (route) => {
  visitCounter++;
  console.log(`[EVENT: page_visit] Path: ${route} | Total Page Hits: ${visitCounter}`);
});

// Trigger events manually
customEmitter.emit('welcome', 'Vijay Kumar');

// 3. Combined HTTP Server with Event Triggering (Program 73)
const PORT = 3003;
const server = http.createServer((req, res) => {
  const reqPath = url.parse(req.url, true).pathname;
  customEmitter.emit('page_visit', reqPath);

  res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
  res.end(`
    <h2>Simple Q8: Event & URL Server</h2>
    <p>Requested Path: <strong>${reqPath}</strong></p>
    <p>Current Page Hit Count: <strong>${visitCounter}</strong></p>
    <p>Check the console terminal to observe live EventEmitter logs!</p>
  `);
});

server.listen(PORT, () => {
  console.log(`\n--- 3. Event-Driven Server Active ---`);
  console.log(`Server running at http://localhost:${PORT}`);
  console.log(`Open http://localhost:${PORT}/ or /test in your browser to fire events.`);
  console.log(`(Press Ctrl+C to stop)`);
});

