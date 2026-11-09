console.log("Command Line Arguments:");

for (let i = 2; i < process.argv.length; i++) {
    console.log(process.argv[i]);
}
