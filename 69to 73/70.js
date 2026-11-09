const EventEmitter = require("events");
const e = new EventEmitter();

e.on("welcome", name => console.log("Welcome " + name));

e.emit("welcome", "Vijay");