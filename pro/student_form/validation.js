document.addEventListener("DOMContentLoaded", function() {

    // -------------------------------------------------------------
    // 1. Dark / Light Mode Toggle with LocalStorage
    // -------------------------------------------------------------
    const themeToggleBtn = document.getElementById("themeToggle");
    const currentTheme = localStorage.getItem("app_theme") || "light";

    if (currentTheme === "dark") {
        document.documentElement.setAttribute("data-theme", "dark");
        if (themeToggleBtn) themeToggleBtn.textContent = "☀️";
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", function() {
            let activeTheme = document.documentElement.getAttribute("data-theme");
            if (activeTheme === "dark") {
                document.documentElement.removeAttribute("data-theme");
                localStorage.setItem("app_theme", "light");
                themeToggleBtn.textContent = "🌙";
            } else {
                document.documentElement.setAttribute("data-theme", "dark");
                localStorage.setItem("app_theme", "dark");
                themeToggleBtn.textContent = "☀️";
            }
        });
    }

    // -------------------------------------------------------------
    // 2. Avatar / Photo Live Preview
    // -------------------------------------------------------------
    const photoInput = document.getElementById("photo");
    const imagePreview = document.getElementById("imagePreview");

    if (photoInput && imagePreview) {
        photoInput.addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) { // 2MB Limit
                    document.getElementById("photoError").textContent = "File size must be under 2MB";
                    photoInput.value = "";
                    return;
                }
                document.getElementById("photoError").textContent = "";
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // -------------------------------------------------------------
    // 3. Password Toggle & Real-time Strength Meter
    // -------------------------------------------------------------
    const passwordInput = document.getElementById("password");
    const togglePasswordBtn = document.getElementById("togglePasswordBtn");
    const passwordMeter = document.getElementById("passwordMeter");
    const passwordStrengthText = document.getElementById("passwordStrengthText");

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            togglePasswordBtn.textContent = type === "password" ? "👁️" : "🙈";
        });
    }

    if (passwordInput && passwordMeter) {
        passwordInput.addEventListener("input", function() {
            const val = passwordInput.value;
            let score = 0;

            if (val.length >= 6) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            passwordMeter.value = score;

            const strengthLabels = ["None", "Weak", "Fair", "Good", "Strong"];
            passwordStrengthText.textContent = "Strength: " + (val ? strengthLabels[score] : "None");
            
            if (score <= 1) passwordStrengthText.style.color = "#dc2626";
            else if (score <= 2) passwordStrengthText.style.color = "#ea580c";
            else if (score === 3) passwordStrengthText.style.color = "#ca8a04";
            else passwordStrengthText.style.color = "#16a34a";
        });
    }

    // -------------------------------------------------------------
    // 4. Textarea Live Character Counter
    // -------------------------------------------------------------
    const addressInput = document.getElementById("address");
    const charCounter = document.getElementById("charCounter");

    if (addressInput && charCounter) {
        addressInput.addEventListener("input", function() {
            charCounter.textContent = `${addressInput.value.length} / ${addressInput.maxLength || 300}`;
        });
    }

    // -------------------------------------------------------------
    // 5. Dynamic Form Completion Progress
    // -------------------------------------------------------------
    const form = document.getElementById("studentForm");
    const progressBar = document.getElementById("formProgress");
    const progressPercent = document.getElementById("progressPercent");

    function updateProgress() {
        if (!form || !progressBar) return;

        const trackedFields = [
            () => document.getElementById("name")?.value.trim().length > 0,
            () => document.getElementById("age")?.value.trim().length > 0,
            () => document.getElementById("dob")?.value.length > 0,
            () => !!document.querySelector('input[name="gender"]:checked'),
            () => document.getElementById("email")?.value.trim().length > 0,
            () => (document.getElementById("password")?.value.length || 0) >= 6,
            () => document.getElementById("phone")?.value.trim().length === 10,
            () => document.getElementById("course")?.value.length > 0,
            () => document.getElementById("batch_time")?.value.length > 0,
            () => document.querySelectorAll('input[name="skills[]"]:checked').length > 0,
            () => !!document.getElementById("terms")?.checked
        ];

        const filledCount = trackedFields.filter(check => check()).length;
        const total = trackedFields.length;
        const percent = Math.round((filledCount / total) * 100);

        progressBar.value = percent;
        if (progressPercent) progressPercent.textContent = `${percent}%`;
    }

    if (form) {
        form.addEventListener("input", updateProgress);
        form.addEventListener("change", updateProgress);
    }

    // -------------------------------------------------------------
    // 6. Fill Sample Data Helper
    // -------------------------------------------------------------
    const fillDemoBtn = document.getElementById("fillDemoBtn");
    if (fillDemoBtn) {
        fillDemoBtn.addEventListener("click", function() {
            document.getElementById("name").value = "Jane Doe";
            document.getElementById("age").value = "22";
            document.getElementById("dob").value = "2004-05-15";
            
            const femaleRadio = document.querySelector('input[name="gender"][value="Female"]');
            if (femaleRadio) femaleRadio.checked = true;

            document.getElementById("email").value = "jane.doe@example.com";
            document.getElementById("phone").value = "9876543210";
            document.getElementById("password").value = "SecurePass@2026";
            document.getElementById("portfolio").value = "https://janedoe.dev";
            document.getElementById("course").value = "MCA";
            document.getElementById("city").value = "Chennai Campus";
            document.getElementById("batch_time").value = "10:00";
            document.getElementById("interview_slot").value = "2026-09-15T11:00";
            document.getElementById("grad_month").value = "2028-05";
            document.getElementById("orientation_week").value = "2026-W38";
            
            // Skills
            const skillBoxes = document.querySelectorAll('input[name="skills[]"]');
            skillBoxes.forEach((box, i) => {
                box.checked = (i < 4);
            });

            document.getElementById("experience").value = 80;
            const expOutput = document.getElementById("expOutput");
            if (expOutput) expOutput.value = "80%";

            document.getElementById("badge_color").value = "#16a34a";
            document.getElementById("interests").value = "Machine Learning, Web Security";
            document.getElementById("address").value = "Suite 404, Tech Park Boulevard, Chennai, Tamil Nadu - 600001";
            document.getElementById("terms").checked = true;

            // Trigger events
            passwordInput.dispatchEvent(new Event("input"));
            addressInput.dispatchEvent(new Event("input"));
            updateProgress();
        });
    }

    // -------------------------------------------------------------
    // 7. Form Submit & Validation
    // -------------------------------------------------------------
    if (form) {
        form.addEventListener("submit", function(event) {
            let valid = true;

            // Clear errors
            document.querySelectorAll(".error").forEach(err => err.textContent = "");

            // 1. Name
            const name = document.getElementById("name").value.trim();
            if (name === "") {
                document.getElementById("nameError").textContent = "Full Name is required";
                valid = false;
            }

            // 2. Age
            const age = parseInt(document.getElementById("age").value);
            if (isNaN(age) || age < 15 || age > 70) {
                document.getElementById("ageError").textContent = "Age must be between 15 and 70";
                valid = false;
            }

            // 3. DOB
            const dob = document.getElementById("dob").value;
            if (!dob) {
                document.getElementById("dobError").textContent = "Date of Birth is required";
                valid = false;
            }

            // 4. Gender
            const gender = document.querySelector('input[name="gender"]:checked');
            if (!gender) {
                document.getElementById("genderError").textContent = "Please select gender";
                valid = false;
            }

            // 5. Email
            const email = document.getElementById("email").value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                document.getElementById("emailError").textContent = "Please enter a valid email address";
                valid = false;
            }

            // 6. Phone
            const phone = document.getElementById("phone").value.trim();
            const phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(phone)) {
                document.getElementById("phoneError").textContent = "Enter a valid 10-digit phone number";
                valid = false;
            }

            // 7. Password
            const password = document.getElementById("password").value;
            if (password.length < 6) {
                document.getElementById("passwordError").textContent = "Password must be at least 6 characters";
                valid = false;
            }

            // 8. Course
            const course = document.getElementById("course").value;
            if (!course) {
                document.getElementById("courseError").textContent = "Please select a degree course";
                valid = false;
            }

            // 9. Batch Time
            const batchTime = document.getElementById("batch_time").value;
            if (!batchTime) {
                document.getElementById("batchTimeError").textContent = "Please select daily batch time";
                valid = false;
            }

            // 10. Skills
            const skills = document.querySelectorAll('input[name="skills[]"]:checked');
            if (skills.length === 0) {
                document.getElementById("skillsError").textContent = "Please select at least one technical skill";
                valid = false;
            }

            // 11. Terms
            const terms = document.getElementById("terms");
            if (!terms.checked) {
                document.getElementById("termsError").textContent = "You must agree to the declaration";
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
                const firstError = document.querySelector(".error:not(:empty)");
                if (firstError) {
                    firstError.parentElement.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            }
        });
    }

});
