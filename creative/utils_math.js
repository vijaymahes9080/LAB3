/**
 * Local Module: Custom Mathematical & Security Utility Library
 * Corresponding to Lab Program #61 & #62
 */

function add(a, b) {
  return Number(a) + Number(b);
}

function multiply(a, b) {
  return Number(a) * Number(b);
}

function calculateGrade(marks) {
  if (marks >= 85) return 'Distinction';
  if (marks >= 60) return 'First Class';
  if (marks >= 50) return 'Second Class';
  return 'Re-Appear';
}

function formatBytes(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

module.exports = {
  add,
  multiply,
  calculateGrade,
  formatBytes
};

