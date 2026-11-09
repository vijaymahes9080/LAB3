# Practical Laboratory Examination Manual
## Advanced Web Technology & Full-Stack Development
**Curated University Examination Suite (Synthesized from Lab Programs 34 to 85)**

---

## 🏛️ Examination Structure & Evaluation Rubric

In university practical examinations, students are evaluated across two standard tiers:
- **Part A: Simple & Fundamental Examination Questions**: Direct, concept-specific questions evaluating core language mechanisms, directives, filters, and standard modules.
- **Part B: Comprehensive & Integrated Projects**: Real-world application systems synthesizing multiple syllabus topics into end-to-end architectures.

### University Evaluation Mark Breakdown (Total: 100 Marks)
| Assessment Component | Description | Max Marks |
| :--- | :--- | :--- |
| **Part A: Simple Question Execution** | Direct implementation, syntax correctness, and concept demonstration | 30 Marks |
| **Part B: Advanced System Implementation** | Architectural design, directives, filters, services, and dynamic views | 40 Marks |
| **Execution & Output Verification** | Error-free execution, edge-case validation, dynamic UI rendering | 15 Marks |
| **Viva-Voce** | Conceptual mastery of AngularJS, Scopes, Node.js, and REST APIs | 15 Marks |
| **Total** | | **100 Marks** |

---

## 🗺️ Complete Examination Curriculum Matrix

### Part A: Simple Fundamental Questions (S1 — S8)
| Simple Question | Title | Combined Programs | Executable File | Key Concepts |
| :--- | :--- | :--- | :--- | :--- |
| **S1** | **Expressions & Data Binding** | 34 & 36 | `simple_q1_expressions_binding.html` | Numeric, String, Boolean, Object, Array expressions; One-Way (`{{ }}`) vs Two-Way (`ng-model`). |
| **S2** | **ng-model Directive Classes** | 35 | `simple_q2_ng_model_classes.html` | Form validation states (`$valid`, `$invalid`, `$dirty`, `$pristine`, `$touched`, `$untouched`) with dynamic CSS indicators. |
| **S3** | **Built-in & Custom Filters** | 38 | `simple_q3_filters.html` | Ten built-in filters (uppercase, lowercase, currency, number, limitTo, orderBy, filter, json, date) + Custom reverse filter. |
| **S4** | **Directives & Conditional Views** | 401, 41, 44 | `simple_q4_directives_table.html` | `ng-repeat` with `$index`, `$even`, `$odd`; conditional rendering with `ng-if`, `ng-show`, `ng-hide`, and `ng-switch`. |
| **S5** | **$http Asynchronous Requests** | 39, 402 | `simple_q5_http_service.html` | Asynchronous AJAX calls using `$http.get()`, `$http.post()`, `$http.put()`, `$http.delete()` and JSON rendering. |
| **S6** | **Node.js Core Modules & CLI** | 59, 60, 61, 62 | `simple_q6_nodejs_basics.js` | Command line arguments (`process.argv`), custom math module import/export, and `os`, `path`, `util` inspection. |
| **S7** | **Node.js Server & File CRUD** | 63, 64, 65, 66 | `simple_q7_nodejs_fs_server.js` | HTTP web server routing (`/`, `/about`, `/contact`, `404`) and file operations (write, append, read, rename, unlink). |
| **S8** | **Node.js URL Parsing & Events** | 67, 68, 70, 73 | `simple_q8_nodejs_events_url.js` | URL parsing via `url.parse(address, true)` and event handling using the `EventEmitter` class (`emit`, `on`). |

### Part B: Comprehensive Integrated Projects (B1 — B6)
| Project | Title | Combined Programs | Executable File |
| :--- | :--- | :--- | :--- |
| **B1** | **Integrated Student Academic & Performance Analytics Portal** | 34, 35, 36, 42, 44, 47, 49, 52, 57 | `exam1_academic_analytics.html` |
| **B2** | **Smart E-Commerce Catalog, POS Billing & Order Dispatch ERP** | 38, 41, 45, 48, 54 | `exam2_ecommerce_billing.html` |
| **B3** | **Institutional Library Multi-Controller & Cascading Catalog ERP** | 37, 43, 46, 50, 53 | `exam3_library_circulation.html` |
| **B4** | **Enterprise Operations Dashboard & Secure Employee Portal** | 51, 55, 56, 58 | `exam4_enterprise_operations.html` |
| **B5** | **Modular Enterprise Node.js Server & Asynchronous Audit Engine** | 59–70, 73 | `exam5_nodejs_server.js` |
| **B6** | **Full-Stack Integrated REST API Client & Server Management Suite** | Exps 1–10 (76–85), 39, 402 | `exam6_fullstack_crud.html` & `fullstack_server.js` |

---

# 📘 PART A: SIMPLE & FUNDAMENTAL EXAMINATION QUESTIONS

---

### SIMPLE QUESTION S1: Expressions & Types of Data Binding
- **Question:** Write an AngularJS program to demonstrate all types of expressions (Numeric, String, Boolean, Object, Array indexing) and compare One-Way (`{{ }}`) vs Two-Way (`ng-model`) data binding.
- **Aim:** To implement AngularJS interpolation expressions and compare unidirectional vs bidirectional data binding.
- **Algorithm:**
  1. Define AngularJS module `simpleQ1App` and controller `ExprCtrl`.
  2. In controller `$scope`, define properties: `student = { name: 'Vijay', dept: 'MCA' }`, `marks = [85, 90, 95]`, `greeting = 'Welcome'`, and `liveText = 'Two-Way Binding'`.
  3. In HTML view, bind expressions inside `{{ }}`: `15 * 4 + 10` (numeric), `'Hello ' + 'AngularJS'` (string), `25 > 10` (boolean), `student.name` (object), and `marks[2]` (array).
  4. Illustrate one-way data binding by rendering `{{ greeting }}`.
  5. Illustrate two-way data binding by binding `<input ng-model="liveText">` to `{{ liveText }}` with real-time text synchronization.
- **Program File:** `creative/simple_q1_expressions_binding.html`
- **Output:** All expressions evaluate to their computed types; typing into the input box updates the bound text instantly.

---

### SIMPLE QUESTION S2: ng-model Directive Classes & Validation States
- **Question:** Write an AngularJS code to implement and observe all CSS classes and validation states that the `ng-model` directive adds to an input element (`$valid`, `$invalid`, `$dirty`, `$pristine`, `$touched`, `$untouched`).
- **Aim:** To observe dynamic CSS classes applied by AngularJS forms and verify input validation states.
- **Algorithm:**
  1. Create form `userForm` with input field `name="userEmail"` having `ng-model="email"`, `type="email"`, and `required`.
  2. Define CSS classes: `.ng-valid` (green border), `.ng-invalid.ng-touched` (red border), `.ng-dirty` (background tint), and `.ng-pristine`.
  3. Create live monitor elements binding `userForm.userEmail.$valid`, `$invalid`, `$pristine`, `$dirty`, `$touched`, and `$untouched`.
  4. Observe changes as user focuses, types, blurs, and supplies valid vs invalid email strings.
- **Program File:** `creative/simple_q2_ng_model_classes.html`
- **Output:** Color-coded status badges toggle dynamically based on user interaction and input validity.

---

### SIMPLE QUESTION S3: Built-in & Custom Filters in AngularJS
- **Question:** Write an AngularJS program to implement any ten built-in filters (uppercase, lowercase, currency, number, limitTo, orderBy, filter, json, date) and demonstrate the creation of a custom filter.
- **Aim:** To format data using built-in AngularJS filters and construct a custom filter using pipe syntax.
- **Algorithm:**
  1. Define module `simpleQ3App`. Register custom filter `reverse`:
     ```javascript
     app.filter('reverse', function() {
       return function(input) { return input ? input.split('').reverse().join('') : ''; };
     });
     ```
  2. In controller `$scope`, assign sample string, amount, date, array, and object data.
  3. Apply filters in view: `name | uppercase`, `framework | lowercase`, `amount | currency:"₹ "`, `pi | number:2`, `today | date:'medium'`, `students | limitTo:3`, `scores | orderBy:'+'`, `techs | filter:'A'`, `statusObj | json`, and `sampleText | reverse`.
- **Program File:** `creative/simple_q3_filters.html`
- **Output:** Table displaying raw input, filter syntax, and formatted output for all 10 filters.

---

### SIMPLE QUESTION S4: Directives, ng-repeat & Conditional Views
- **Question:** Write an AngularJS program to display records in a table using `ng-repeat` (with `$index`, `$even`, `$odd`) and illustrate conditional display using `ng-if`, `ng-show`, `ng-hide`, and `ng-switch` directives.
- **Aim:** To iterate over collections in tables and compare DOM manipulation (`ng-if`) versus CSS visibility toggling (`ng-show`/`ng-hide`).
- **Algorithm:**
  1. In controller `$scope`, seed array `students` with student records having status `'Active'`, `'Probation'`, or `'Alumni'`.
  2. Use `<tr ng-repeat="s in students track by $index">` with `$even` and `$odd` to stripe table rows.
  3. Implement `<td ng-switch="s.status">` with `<span ng-switch-when="Active">`, etc.
  4. Compare `ng-if` (which completely adds/removes elements from DOM) and `ng-show` (which toggles `display: none`).
- **Program File:** `creative/simple_q4_directives_table.html`
- **Output:** Alternating row styled table with dynamic badges and live toggleable display sections.

---

### SIMPLE QUESTION S5: $http Services & Asynchronous Requests
- **Question:** Write an AngularJS program to illustrate the usage of the `$http` service methods (`$http.get`, `$http.post`, `$http.put`, `$http.delete`) and render the returned server response dynamically.
- **Aim:** To perform AJAX HTTP operations in AngularJS and process asynchronous promise resolutions.
- **Algorithm:**
  1. Inject `$http` service into controller `HttpSimpleCtrl`.
  2. Define methods:
     - `makeGet()`: Dispatches `$http.get(url)`.
     - `makePost()`: Dispatches `$http.post(url, payload)`.
     - `makePut()`: Dispatches `$http.put(url, payload)`.
     - `makeDelete()`: Dispatches `$http.delete(url)`.
  3. Bind response data to `$scope.responseData` and display in formatted `<pre>` tags using `{{ responseData | json }}`.
- **Program File:** `creative/simple_q5_http_service.html`
- **Output:** Interactive buttons dispatching requests with live JSON response viewer and HTTP status codes.

---

### SIMPLE QUESTION S6: Node.js Command Line Arguments, Local Modules & Core Modules
- **Question:** Write a Node.js program to display command line arguments using `process.argv`, import and execute a custom local math module, and inspect host system telemetry using `os`, `path`, and `util` core modules.
- **Aim:** To execute a modular Node.js program consuming local exports and host telemetry.
- **Algorithm:**
  1. Inspect `process.argv` array using `process.argv.forEach()`.
  2. Create local module `utils_math.js` exporting `add`, `multiply`, and `calculateGrade`.
  3. Require `utils_math.js` and execute arithmetic calculations.
  4. Import `os` to print `os.platform()`, `os.arch()`, `os.hostname()`, and `os.homedir()`.
  5. Import `path` and `util` to normalize file paths with `path.join()` and format strings with `util.format()`.
- **Program File:** `creative/simple_q6_nodejs_basics.js`
- **How to Run:** `node simple_q6_nodejs_basics.js arg1 arg2 --mode=test` (or run `run_simple_q6.bat`)
- **Output:** Console outputs CLI parameters, local math calculations, and system architecture details.

---

### SIMPLE QUESTION S7: Node.js HTTP Server Routing & File System (fs) CRUD
- **Question:** Write a Node.js program to create an HTTP web server with URL routing and demonstrate asynchronous and synchronous file operations using the `fs` module (write, read, append, rename, delete).
- **Aim:** To construct a Node.js web server and execute file manipulation operations using the `fs` core module.
- **Algorithm:**
  1. Demonstrate synchronous and asynchronous `fs` methods:
     - Write: `fs.writeFileSync(file, content)`
     - Append: `fs.appendFileSync(file, content)`
     - Read: `fs.readFile(file, 'utf8', callback)`
     - Rename: `fs.renameSync(oldPath, newPath)`
     - Unlink: `fs.unlinkSync(newPath)`
  2. Create HTTP server with `http.createServer((req, res) => { ... })`.
  3. Check `req.url`: serve home on `'/'`, about on `'/about'`, contact on `'/contact'`, and 404 for undefined paths.
- **Program File:** `creative/simple_q7_nodejs_fs_server.js`
- **How to Run:** `node simple_q7_nodejs_fs_server.js` (or run `run_simple_q7.bat`)
- **Output:** File lifecycle executes in console; HTTP server listens on port 3002.

---

### SIMPLE QUESTION S8: Node.js URL Parsing & Event Handling (EventEmitter)
- **Question:** Write a Node.js program to illustrate URL parsing to extract query string parameters and implement custom event handling using the `EventEmitter` class.
- **Aim:** To parse URL parameters and implement event-driven architecture using `EventEmitter`.
- **Algorithm:**
  1. Import `url` and parse address: `parsed = url.parse(address, true)`. Access query parameters via `parsed.query.id`, `parsed.query.name`.
  2. Import `events` and create `customEmitter = new EventEmitter()`.
  3. Register listeners: `customEmitter.on('welcome', callback)` and `customEmitter.on('page_visit', callback)`.
  4. Trigger events via `customEmitter.emit('welcome', 'Vijay Kumar')`.
  5. Connect HTTP server on port 3003 where every client page hit emits `'page_visit'` and increments hit counter.
- **Program File:** `creative/simple_q8_nodejs_events_url.js`
- **How to Run:** `node simple_q8_nodejs_events_url.js` (or run `run_simple_q8.bat`)
- **Output:** URL queries parsed in terminal; browser visits emit real-time event logs to stdout.

---

# 🚀 PART B: COMPREHENSIVE INTEGRATED PROJECTS (B1 — B6)

---

### PROJECT B1: Integrated Student Academic Performance & Analytics Portal
- **Problem Statement:** Develop an interactive single-page AngularJS Web Application for university student academic evaluation combining form validation with `ng-model` state classes, two-way data binding for calculating GPA and aggregate marks, a `$scope.$watch` deep listener for an immutable live audit trail of mark modifications, attendance categorization using `ng-switch`, and multi-criteria sorting and filtering.
- **Programs Combined:** 34, 35, 36, 42, 44, 47, 49, 52, 57
- **Program File:** `creative/exam1_academic_analytics.html`

---

### PROJECT B2: Smart E-Commerce Catalog, POS Billing & Order Dispatch ERP
- **Problem Statement:** Design and implement an enterprise E-Commerce and Point of Sale (POS) application using AngularJS featuring reusable custom directives (`<product-stock-tag>` and `<star-rating>`), currency and date filters, two-way bound line-item billing with automated discount and GST tax slab calculations, a printable tax invoice, and an interactive order fulfillment table supporting inline double-click editing and keyboard shortcut listeners.
- **Programs Combined:** 38, 41, 45, 48, 54
- **Program File:** `creative/exam2_ecommerce_billing.html`

---

### PROJECT B3: Institutional Library Multi-Controller & Cascading Catalog System
- **Problem Statement:** Construct a modular institutional Library ERP in AngularJS demonstrating hierarchical scope inheritance (`$parent` access from branch to dean scope), a 4-tier dependent cascading dropdown (Level ➔ Department ➔ Semester ➔ Course Book) using `ng-options` and `ng-change`, and a multi-controller domain (`BookInventoryCtrl`, `CirculationCtrl`, `LibraryStatsCtrl`) synchronized via a shared singleton `LibrarySharedService` factory.
- **Programs Combined:** 37, 43, 46, 50, 53
- **Program File:** `creative/exam3_library_circulation.html`

---

### PROJECT B4: Enterprise Operations Dashboard & Secure Employee Portal
- **Problem Statement:** Develop a secure Enterprise Operations and HR Management Portal using AngularJS with client-side authentication, real-time password entropy scoring, real-time background telemetry updates using `$interval`, auto-dismissing toast notifications using `$timeout`, an employee compensation register with custom `experienceLevel` filter, and a keyboard-accessible modal lightbox dialog for staff credentials.
- **Programs Combined:** 51, 55, 56, 58
- **Program File:** `creative/exam4_enterprise_operations.html`

---

### PROJECT B5: Modular Enterprise Node.js Server & Asynchronous Audit Engine
- **Problem Statement:** Build a production-grade, modular Node.js Server application combining core modules (`http`, `url`, `fs`, `path`, `os`, `util`, `events`), command-line arguments processing (`process.argv`), local math module import, HTTP routing (`/status`, `/math`, `/student`, `/logs`), sync/async file CRUD operations, and an `EventEmitter` recording asynchronous audit log streams to disk.
- **Programs Combined:** 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 73
- **Program File:** `creative/exam5_nodejs_server.js`

---

### PROJECT B6: Full-Stack Integrated REST API Client & Server Management Suite
- **Problem Statement:** Design and implement an end-to-end Full-Stack Student Management System comprising a Node.js REST API backend and an AngularJS single-page client. The REST backend must implement endpoints for `GET /api/students`, `POST /api/students`, `PUT /api/students/:id`, and `DELETE /api/students/:id` with file database persistence. The AngularJS client must consume these endpoints using `$http` services, rendering a live searchable data table with immediate CRUD synchronization and a terminal network monitor.
- **Programs Combined:** Experiments 1–10 (Syllabus 76–85), 39, 402
- **Program Files:** `creative/exam6_fullstack_crud.html` & `creative/fullstack_server.js`

---

## 💡 Practical Examination Viva-Voce Question Bank (25 Core Q&A)

1. **What is the `ng-app` directive?** It bootstraps AngularJS and defines the root DOM element of the application.
2. **Differentiate One-Way vs Two-Way Data Binding:** One-way (`{{ }}`) renders model values into the view; two-way (`ng-model`) synchronizes mutations bidirectionally.
3. **What is the function of `$scope.$watch`?** It observes variable changes in the scope and executes a callback function whenever modified; passing `true` enables deep object comparison.
4. **Explain prototypal inheritance in nested controllers:** Child scopes inherit properties from ancestor scopes through the JavaScript prototype chain and can access parent properties via `$scope.$parent`.
5. **What are custom directive restriction flags?** `'E'` for Element name, `'A'` for Attribute, `'C'` for CSS Class, and `'M'` for Comment.
6. **Compare `ng-if` vs `ng-show`:** `ng-if` dynamically adds/removes elements from the DOM; `ng-show` leaves elements in the DOM and toggles CSS `display: none`.
7. **What is the difference between a Factory and a Service?** A Service is instantiated with `new` (constructor pattern); a Factory returns a plain object with exposed methods.
8. **Why use `$interval` over `setInterval`?** `$interval` triggers AngularJS digest cycles automatically so the view re-renders immediately.
9. **Explain form validation states on `ng-model`:** `$valid`, `$invalid`, `$pristine`, `$dirty`, `$touched`, and `$untouched`.
10. **What does `track by $index` do?** It binds DOM elements to array indices, preventing duplicate key errors and boosting iteration speed.
11. **What is the Node.js Event Loop?** The single-threaded non-blocking I/O loop offloading operations to the kernel.
12. **How to read CLI arguments in Node.js?** Through the `process.argv` array starting from index 2.
13. **Differentiate `fs.readFile` and `fs.readFileSync`:** `fs.readFile` is asynchronous and non-blocking; `fs.readFileSync` blocks execution until the read completes.
14. **What is the `EventEmitter` class?** A core Node.js class providing the pub/sub event mechanism (`.on()` to register, `.emit()` to trigger).
15. **How are CommonJS modules exported?** Using `module.exports = { ... }` and imported using `require('path')`.
16. **What does `url.parse(address, true)` do?** Parses URL string into protocol, host, pathname, and an object of query parameters.
17. **What is CORS?** Cross-Origin Resource Sharing headers allowing browser clients on different origins to access server APIs.
18. **Name the standard REST HTTP methods:** `GET` (Read), `POST` (Create), `PUT` (Update), `PATCH` (Partial Update), `DELETE` (Remove).
19. **What does `path.join()` do?** Concatenates path segments using the operating system's native separator.
20. **What is the MEAN stack?** MongoDB, Express.js, AngularJS, and Node.js.
21. **What is dependency injection in AngularJS?** A design pattern where dependencies (services, factories, `$scope`) are passed into controllers automatically by the injector.
22. **What does the currency filter do?** Formats numbers into currency strings with custom symbol and decimal precision (e.g., `amount | currency:"₹ "`).
23. **What is `$rootScope`?** The top-level root scope created on the `ng-app` element, accessible across all controllers in the application.
24. **How do you handle 404 in Node.js HTTP server?** By writing header `res.writeHead(404)` and serving a not found response in the route dispatcher's fallback `else` branch.
25. **What is JSON persistence?** Storing structured application state serialized as JSON strings in flat files on the server file system.
