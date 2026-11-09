/**
 * ==============================================================================
 * SIMPLE LAB EXAM Q6: NODE.JS CLI ARGS, LOCAL MODULES & CORE MODULES
 * ==============================================================================
 * Covers Syllabus Programs: 59, 60, 61 & 62
 * Question:
 * "Write a Node.js program to display command line arguments using process.argv,
 *  import and execute a custom local math module, and inspect host system
 *  telemetry using os, path, and util core modules."
 * ==============================================================================
 */

const os = require('os');
const path = require('path');
const util = require('util');
const math = require('./utils_math');

console.log('================================================================');
console.log('SIMPLE EXAM Q6: NODE.JS CORE MODULES & LOCAL MODULES');
console.log('================================================================\n');

// 1. Program 59 & 60: Hello World & Command Line Arguments
console.log('--- 1. Command Line Arguments (process.argv) ---');
console.log('Number of arguments supplied: ' + process.argv.length);
process.argv.forEach((arg, index) => {
  console.log(`  Argument [${index}]: ${arg}`);
});

// 2. Program 61: Local Math Module Execution
console.log('\n--- 2. Custom Local Module Functions (utils_math.js) ---');
const num1 = 20;
const num2 = 10;
console.log(`Addition (${num1} + ${num2})       : ${math.add(num1, num2)}`);
console.log(`Multiplication (${num1} * ${num2}) : ${math.multiply(num1, num2)}`);
console.log(`Academic Grade for 88 marks     : ${math.calculateGrade(88)}`);

// 3. Program 62: Core Modules (os, path, util)
console.log('\n--- 3. Core Modules (os, path, util) ---');
console.log('Operating System Platform       : ' + os.platform());
console.log('OS Architecture                 : ' + os.arch());
console.log('Host Computer Name              : ' + os.hostname());
console.log('User Home Directory             : ' + os.homedir());
console.log('Normalized Path.join Example    : ' + path.join('workspace', 'lab', 'program.js'));
console.log('Formatted Message via util      : ' + util.format('Hello %s! Score is %d%%', 'Vijay', 95));

console.log('\n================================================================');
console.log('Execution completed successfully.');
console.log('================================================================');

