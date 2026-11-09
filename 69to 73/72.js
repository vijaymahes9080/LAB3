const nodemailer = require("nodemailer");

let mail = nodemailer.createTransport({
  service: "gmail",
  auth: {
    user: "your@gmail.com",
    pass: "your_app_password"
  }
});

mail.sendMail({
  from: "your@gmail.com",
  to: "receiver@gmail.com",
  subject: "Test Mail",
  text: "Hello from Node.js"
}, (err, info) => {
  if (err) console.log(err);
  else console.log("Email sent");
});