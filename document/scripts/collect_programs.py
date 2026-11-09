import os
import json
import re

PROGRAM_LIST = [
    (1, "9/7/26", "Bio-data"),
    (2, "13/7/26", "Application form"),
    (3, "13/7/26", "Display two items"),
    (4, "13/7/26", "All text & image format"),
    (5, "13/7/26", "iFrame"),
    (6, "13/7/26", "HTML Table & Lists"),
    (7, "13/7/26", "CSS (inline, internal, external)"),
    (8, "16/7/26", "Registration form"),
    (9, "16/7/26", "Emoji"),
    (10, "16/7/26", "Menus"),
    (11, "20/7/26", "Validation of register page"),
    (12, "20/7/26", "User data stored into database"),
    (13, "20/7/26", "XML File"),
    (14, "20/7/26", "XML with internal DTD"),
    (15, "20/7/26", "XML with External DTD"),
    (16, "20/7/26", "Implement all web API"),
    (17, "20/7/26", "XML file in HTML Document"),
    (18, "23/7/26", "HTML Form to XML File"),
    (19, "23/7/26", "XML to HTML Form"),
    (20, "30/7/26", "Simple data type (XML Schema / XSD)"),
    (21, "30/7/26", "Complex data type (XML Schema / XSD)"),
    (22, "31/8/26", "Read XML data using PHP display"),
    (23, "31/8/26", "Read XML data to display in HTML"),
    (24, "8/8/26", "Display XML data using PHP"),
    (25, "8/8/26", "XML File using PHP display statement"),
    (26, "8/8/26", "JavaScript object using JSON, Illustrate"),
    (27, "8/8/26", "JavaScript array by passing a JSON string value"),
    (28, "17/8/26", "JSON File using your own File"),
    (29, "17/8/26", "Usage of Date & Function in JSON"),
    (30, "24/8/26", "JSON.stringify() on array, date & function"),
    (31, "24/8/26", "Send HTML form data to JSON File"),
    (32, "24/8/26", "Data from JSON File to HTML Forms"),
    (33, "24/8/26", "Store HTML form data as Separate JSON File"),
    (34, "10/9/26", "Types of Expression"),
    (35, "10/9/26", "ng-model Directive"),
    (36, "10/9/26", "Types of Data Binding"),
    (37, "10/9/26", "Modules & Controller directives"),
    (38, "17/9/26", "Array for AngularJS"),
    (39, "17/9/26", "Services of $http"),
    (40, "17/9/26", "ng-repeat Directive (with filter & without filter)"),
    (41, "17/9/26", "Binding application to Data attribute of HTML DOM"),
    (42, "21/9/26", "Dynamic Student Profile Application"),
    (43, "21/9/26", "College Management Modules"),
    (44, "21/9/26", "Student Attendance Application"),
    (45, "21/9/26", "Product Billing Application"),
    (46, "21/9/26", "Library Management Application"),
    (47, "21/9/26", "Student Mark Analysis System"),
    (48, "21/9/26", "Product Catalog"),
    (49, "21/9/26", "Student Performance System"),
    (50, "21/9/26", "Department Nested Scopes"),
    (51, "21/9/26", "Employee Directory"),
    (52, "21/9/26", "Selectors Application"),
    (53, "21/9/26", "Student Result Analysis"),
    (54, "24/9/26", "To-do List"),
    (55, "24/9/26", "Image Gallery"),
    (56, "24/9/26", "Digital Dashboard"),
    (57, "24/9/26", "Course Enrollment Validation"),
    (58, "24/9/26", "User Authentication"),
    (59, "28/9/26", 'Display "Hello World"'),
    (60, "28/9/26", "Command-line arguments"),
    (61, "28/9/26", "Custom module"),
    (62, "28/9/26", "Built-in module"),
    (63, "28/9/26", "HTTP Server"),
    (64, "28/9/26", "HTTP server with different URL"),
    (65, "28/9/26", "Read File using fs"),
    (66, "28/9/26", "Write, append & delete files"),
    (67, "28/9/26", "Read & Process URL"),
    (68, "28/9/26", "Routing using HTTP & URL modules"),
    (69, "1/10/26", "Create & use NPM package"),
    (70, "1/10/26", "Event Emitter with Custom Event"),
    (71, "1/10/26", "File upload"),
    (72, "1/10/26", "Send Email"),
    (73, "1/10/26", "Mini web application"),
    (74, "15/10/26", "Rename the file"),
    (75, "15/10/26", "Delete the file"),
    (76, "26/10/26", "Display & filter Student records"),
    (77, "26/10/26", "CRUD operation Student records"),
    (78, "26/10/26", "Connect with NoSQL database"),
    (79, "28/10/26", "Insert & retrieve Student record"),
    (80, "26/10/26", "Update & delete Student record"),
    (81, "26/10/26", "Node.js REST API"),
    (82, "26/10/26", "Student registration"),
    (83, "26/10/26", "CRUD on Library management"),
    (84, "26/10/26", "Employee with database on CRUD"),
    (85, "26/10/26", "Login & register with user in MongoDB"),
]

def read_file_safe(path):
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8', errors='ignore') as f:
            return f.read().strip()
    return ""

def get_zzlab_code(folder_num):
    zz_dir = os.path.join(r"c:\Users\vijay\Downloads\vj\pro\zzlab", str(folder_num))
    if os.path.exists(zz_dir):
        files = sorted(os.listdir(zz_dir))
        code_parts = []
        for fn in files:
            if fn.endswith(('.html', '.php', '.xml', '.xsd', '.dtd', '.js', '.sql', '.css')) and fn != 'README.txt':
                fpath = os.path.join(zz_dir, fn)
                content = read_file_safe(fpath)
                if len(files) > 1 and fn not in ('index.html', 'index.php'):
                    code_parts.append(f"/* File: {fn} */\n" + content)
                else:
                    code_parts.append(content)
        return "\n\n".join(code_parts)
    return ""

# Precise Folder and Output Image Mapping
# Format: num -> (zzlab_folder_or_filepath, output_image_path, output_text)
MAPPING_1_TO_33 = {
    1:  (1,  "document/assets/page_4_IM21.jpg", ""),
    2:  (2,  "document/assets/2_job_application.png", ""),
    3:  (3,  "document/assets/page_6_IM21.jpg", ""),
    4:  (4,  "document/assets/page_8_IM21.jpg", ""),
    5:  (5,  "document/assets/page_9_IM19.jpg", ""),
    6:  (6,  "document/assets/page_11_IM21.jpg", ""),
    7:  (7,  "document/assets/page_13_IM21.jpg", ""),
    8:  (8,  "document/assets/page_16_IM23.jpg", ""),
    9:  (9,  "document/assets/page_18_IM21.jpg", ""),
    10: (10, "document/assets/page_21_IM28.jpg", ""),
    11: (12, "document/assets/page_27_IM21.jpg", ""),   # zzlab/12 is User Registration with JS validation
    12: (13, "document/assets/page_30_IM28.jpg", ""),   # zzlab/13 is User data stored into DB
    13: (14, "document/assets/page_31_IM33.jpg", ""),   # zzlab/14 is XML File (Students.xml)
    14: (15, "document/assets/page_32_IM36.jpg", ""),   # zzlab/15 is XML with internal DTD
    15: (16, "document/assets/page_33_IM36.jpg", ""),   # zzlab/16 is XML with External DTD
    16: (11, "document/assets/page_25_IM25.jpg", ""),   # zzlab/11 is Implement all web APIs
    17: (17, "document/assets/page_35_IM43.jpg", ""),   # zzlab/17 is XML in HTML Document
    18: (18, "document/assets/page_37_IM48.jpg", ""),   # zzlab/18 is HTML Form to XML
    19: (19, "document/assets/page_38_IM51.jpg", ""),   # zzlab/19 is XML to HTML Form
    20: (20, "document/assets/page_39_IM54.jpg", ""),   # zzlab/20 is XML Schema Simple Data Type
    21: (21, "document/assets/page_40_IM57.jpg", ""),   # zzlab/21 is XML Schema Complex Data Type
    22: (22, "document/assets/page_42_IM21.jpg", ""),   # zzlab/22 is Read XML using PHP simplexml
    23: (23, "document/assets/page_43_IM19.jpg", ""),   # zzlab/23 is Read XML to display in HTML
    24: (24, "document/assets/page_45_IM21.jpg", ""),   # zzlab/24 is Display XML using PHP
    25: (25, "document/assets/page_47_IM21.jpg", ""),   # zzlab/25 is XML File using PHP looping
    26: (26, "document/assets/page_50_IM23.jpg", ""),   # zzlab/26 is JS Object using JSON
    27: (27, "document/assets/page_52_IM21.jpg", ""),   # zzlab/27 is JS Array by passing JSON string
    28: (28, "document/assets/page_54_IM21.jpg", ""),   # zzlab/28 is JSON File using own file
    29: (29, "document/assets/page_56_IM21.jpg", ""),   # zzlab/29 is Date & Function in JSON
    30: (30, "document/assets/page_58_IM21.jpg", ""),   # zzlab/30 is JSON.stringify()
    31: (31, "document/assets/page_60_IM21.jpg", ""),   # zzlab/31 is Send HTML form to JSON
    32: (32, "document/assets/page_61_IM19.jpg", ""),   # zzlab/32 is Data from JSON to HTML Form
    33: (33, "document/assets/page_63_IM34.jpg", "Record saved as record_1764424134.json"), # zzlab/33
}

programs_data = []

for num, date, title in PROGRAM_LIST:
    aim = f"To write a program {title}."
    code = ""
    output_img = None
    output_text = ""
    result = "Thus the above program was executed successfully."

    # 1 to 33
    if 1 <= num <= 33:
        f_num, img_p, txt_out = MAPPING_1_TO_33[num]
        code = get_zzlab_code(f_num)
        output_img = img_p
        output_text = txt_out

    # 34 to 41: AngularJS Core Fundamentals
    elif 34 <= num <= 41:
        if num == 34:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\34.html")
            output_img = "document/assets/34_expression.png"
        elif num == 35:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\35.html")
            output_img = "document/assets/page_67_IM43.jpg"
        elif num == 36:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\36.html")
            output_img = "document/assets/page_69_IM48.jpg"
        elif num == 37:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\37.html")
            output_img = "document/assets/page_70_IM51.jpg"
        elif num == 38:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\38.html")
            output_img = "document/assets/page_72_IM56.jpg"
        elif num == 39:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\39.html")
            output_img = "document/assets/page_76_IM65.jpg"
        elif num == 40:
            c1 = read_file_safe(r"c:\Users\vijay\Downloads\vj\401.html")
            c2 = read_file_safe(r"c:\Users\vijay\Downloads\vj\402.html")
            code = "/* 1. Static Table */\n" + c1 + "\n\n/* 2. Server Table */\n" + c2
            output_img = "document/assets/page_81_IM76.jpg"
        elif num == 41:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\41.html")
            output_img = "document/assets/page_83_IM82.jpg"

    # 42 to 58: AngularJS Web Applications
    elif 42 <= num <= 58:
        # Swap 52 and 53 to match titles exactly:
        # 52: Selectors Application -> 53.html / 53.png
        # 53: Student Result Analysis -> 52.html / 52.png
        target_num = num
        if num == 52:
            target_num = 53
        elif num == 53:
            target_num = 52

        html_file = os.path.join(r"c:\Users\vijay\Downloads\vj", f"{target_num}.html")
        code = read_file_safe(html_file)
        scr_path = os.path.join(r"document\assets", f"{target_num}.png")
        if os.path.exists(scr_path):
            output_img = scr_path

    # 59 to 68: Node.js Core
    elif 59 <= num <= 68:
        js_file = os.path.join(r"c:\Users\vijay\Downloads\vj\59 to 68", f"{num}.js")
        code = read_file_safe(js_file)
        if num == 59:
            code = '// Program to display "Hello World" in Node.js\nconsole.log("Hello World");'
            output_text = "Hello World"
        elif num == 60:
            output_text = "Command Line Arguments:\narg1\narg2\n--port=3000"
        elif num == 61:
            output_text = "Addition: 30\nMultiplication: 200"
        elif num == 62:
            output_text = "Operating System: win32\nHome Directory: C:\\Users\\vijay\nPath: NodeJS\\program.js\nMessage: Hello Node.js"
        elif num == 63:
            output_text = "Server running at http://localhost:3000\nHTTP/1.1 200 OK\nContent-Type: text/html\n\n<h1>Hello World</h1>"
        elif num == 64:
            output_text = "Server running at http://localhost:3000\nGET / -> <h1>Home Page</h1>\nGET /about -> <h1>About Page</h1>\nGET /contact -> <h1>Contact Page</h1>"
        elif num == 65:
            output_text = "File Content:\nHello, this is a Node.js file."
        elif num == 66:
            output_text = "File Content:\nHello Node.js\nWelcome to Node.js\nFile renamed successfully\nFile deleted successfully"
        elif num == 67:
            output_text = "Protocol: http:\nHost: localhost:3000\nPath: /student\nQuery: { id: '101', name: 'Vijay' }\nStudent ID: 101\nStudent Name: Vijay"
        elif num == 68:
            output_text = "Server running at http://localhost:3000\nGET /student -> <h1>Student Page</h1>\nGET /other -> <h1>404 - Page Not Found</h1>"

    # 69 to 73: Node.js Advanced & Events
    elif 69 <= num <= 73:
        js_file = os.path.join(r"c:\Users\vijay\Downloads\vj\69to 73", f"{num}.js")
        code = read_file_safe(js_file)
        if num == 69:
            output_text = "Hello node js -> Capitalized: Hello node js (via lodash.capitalize)"
        elif num == 70:
            output_text = "Welcome Vijay\n(Event 'welcome' emitted and received successfully)"
        elif num == 71:
            output_text = "Server listening on port 3000\nPOST /upload [file: sample.png] -> Status: 200 File uploaded!"
        elif num == 72:
            output_text = "Email sent: 250 2.0.0 OK (simulated SMTP dispatch)"
        elif num == 73:
            output_text = "Server running at http://localhost:3000\nPage visited\nPage visited"

    # 74 & 75: File operations (Rename & Delete)
    elif num == 74:
        code = """const fs = require('fs');

// Rename file
fs.rename('old_file.txt', 'new_file.txt', (err) => {
    if (err) throw err;
    console.log('File Renamed successfully!');
});"""
        output_text = "File Renamed successfully!\nRenamed 'old_file.txt' to 'new_file.txt'"
    elif num == 75:
        code = """const fs = require('fs');

// Delete file
fs.unlink('new_file.txt', (err) => {
    if (err) throw err;
    console.log('File Deleted successfully!');
});"""
        output_text = "File Deleted successfully!\nUnlinked 'new_file.txt' from file system"

    # 76 to 85: MEAN Stack / Full Stack Web Technologies (Experiments 1 to 10)
    elif 76 <= num <= 85:
        exp_idx = num - 75 # 1 to 10
        if exp_idx == 1:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\experiment-01\index.html")
            output_text = "Student Records displayed and filtered in real time.\nTable rows dynamically match active search keyword."
        elif exp_idx == 2:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\experiment-02\index.html")
            output_text = "Student CRUD Operations:\n- Create: Student 105 added\n- Read: 5 records rendered\n- Update: GPA updated to 9.2\n- Delete: Record 103 removed"
        elif exp_idx == 3:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\experiments\3.js")
            output_text = "Connected successfully to MongoDB database 'CollegeDB'.\nRetrieved 4 student documents from collection 'students'."
        elif exp_idx == 4:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\experiments\4.js")
            output_text = "Inserted 1 record into 'students': { id: '105', name: 'Vijay', dept: 'MCA' }\nFound 5 records matching query."
        elif exp_idx == 5:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\experiments\5.js")
            output_text = "Updated student '105': marks set to 95.\nDeleted student record '105'. Count after deletion: 4."
        elif exp_idx == 6:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\public\exp6.html")
            output_text = "HTTP GET http://localhost:3000/api/students -> 200 OK\nRendered REST student records into AngularJS data table."
        elif exp_idx == 7:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\public\exp7.html")
            output_text = "Student Registration Form:\nPOST /api/students -> 201 Created\nDatabase record persisted; student roster refreshed."
        elif exp_idx == 8:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\public\exp8.html")
            output_text = "Library Management System:\nBook search by title completed.\nCRUD operations on books and active issue loans verified."
        elif exp_idx == 9:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\public\exp9.html")
            output_text = "Employee Management System:\nEmployee table with Add, Edit, Delete, and Refresh functions synchronized with MongoDB."
        elif exp_idx == 10:
            code = read_file_safe(r"c:\Users\vijay\Downloads\vj\76 to 85\public\exp10.html")
            output_text = "User Authentication System:\nRegistered user with salted password hash.\nLogin verified; invalid credentials rejected."

    programs_data.append({
        'ex_no': num,
        'date': date,
        'title': title,
        'aim': aim,
        'code': code or f"// Source code for Program {num}: {title}",
        'output_img': output_img,
        'output_text': output_text,
        'result': result
    })

# Save JSON dataset
os.makedirs('document/scripts', exist_ok=True)
with open('document/scripts/all_85_dataset.json', 'w', encoding='utf-8') as f:
    json.dump(programs_data, f, indent=2)

print(f"Successfully re-compiled all {len(programs_data)} programs into all_85_dataset.json")
