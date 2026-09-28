/* =========================================================
   FAST FOTO COLOUR LAB
   REGISTER PAGE JAVASCRIPT
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const menuToggle = document.getElementById("menuToggle");
    const navLinks = document.querySelector(".nav-links");

    const form = document.getElementById("registerForm");
    const registerButton = document.getElementById("registerButton");

    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const confirmInput = document.getElementById("confirm_password");
    const termsInput = document.getElementById("terms");

    const nameError = document.getElementById("nameError");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");
    const confirmError = document.getElementById("confirmError");

    const strengthText =
        document.getElementById("strengthText");

    const strengthBars =
        document.querySelectorAll(".strength-bars span");


    /* =====================================================
       MOBILE MENU
    ===================================================== */

    if (menuToggle && navLinks) {

        menuToggle.addEventListener("click", function () {

            navLinks.classList.toggle("active");

        });


        navLinks.querySelectorAll("a").forEach(function (link) {

            link.addEventListener("click", function () {

                navLinks.classList.remove("active");

            });

        });

    }


    /* =====================================================
       PASSWORD SHOW / HIDE
    ===================================================== */

    document.querySelectorAll(".password-toggle").forEach(function (button) {

        button.addEventListener("click", function () {

            const targetId =
                button.getAttribute("data-target");

            const input =
                document.getElementById(targetId);

            if (!input) {
                return;
            }

            if (input.type === "password") {

                input.type = "text";

                button.textContent = "🙈";

                button.setAttribute(
                    "aria-label",
                    "Hide password"
                );

            } else {

                input.type = "password";

                button.textContent = "👁";

                button.setAttribute(
                    "aria-label",
                    "Show password"
                );

            }

        });

    });


    /* =====================================================
       VALIDATION HELPERS
    ===================================================== */

    function showError(input, element, message) {

        input.closest(".input-wrapper")
            ?.classList.add("invalid");

        input.closest(".input-wrapper")
            ?.classList.remove("valid");

        element.textContent = message;

    }


    function showValid(input, element) {

        input.closest(".input-wrapper")
            ?.classList.remove("invalid");

        input.closest(".input-wrapper")
            ?.classList.add("valid");

        element.textContent = "";

    }


    function validateName() {

        const value = nameInput.value.trim();

        if (value === "") {

            showError(
                nameInput,
                nameError,
                "Please enter your name."
            );

            return false;
        }

        if (value.length < 2) {

            showError(
                nameInput,
                nameError,
                "Name must contain at least 2 characters."
            );

            return false;
        }

        showValid(nameInput, nameError);

        return true;
    }


    function validateEmail() {

        const value = emailInput.value.trim();

        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (value === "") {

            showError(
                emailInput,
                emailError,
                "Please enter your email."
            );

            return false;
        }

        if (!emailPattern.test(value)) {

            showError(
                emailInput,
                emailError,
                "Please enter a valid email address."
            );

            return false;
        }

        showValid(emailInput, emailError);

        return true;
    }


    function validatePassword() {

        const value = passwordInput.value;

        if (value === "") {

            showError(
                passwordInput,
                passwordError,
                "Please create a password."
            );

            return false;
        }

        if (value.length < 6) {

            showError(
                passwordInput,
                passwordError,
                "Password must contain at least 6 characters."
            );

            return false;
        }

        showValid(passwordInput, passwordError);

        return true;
    }


    function validateConfirmPassword() {

        const value = confirmInput.value;

        if (value === "") {

            showError(
                confirmInput,
                confirmError,
                "Please confirm your password."
            );

            return false;
        }

        if (value !== passwordInput.value) {

            showError(
                confirmInput,
                confirmError,
                "Passwords do not match."
            );

            return false;
        }

        showValid(confirmInput, confirmError);

        return true;
    }


    /* =====================================================
       PASSWORD STRENGTH
    ===================================================== */

    function updatePasswordStrength() {

        const password = passwordInput.value;

        let strength = 0;

        if (password.length >= 6) {
            strength++;
        }

        if (password.length >= 8) {
            strength++;
        }

        if (/[A-Z]/.test(password)) {
            strength++;
        }

        if (/[0-9!@#$%^&*]/.test(password)) {
            strength++;
        }


        strengthBars.forEach(function (bar, index) {

            bar.style.background = "";

            if (index < strength) {

                if (strength === 1) {
                    bar.style.background = "#ff657d";
                }

                else if (strength === 2) {
                    bar.style.background = "#ffb35c";
                }

                else if (strength === 3) {
                    bar.style.background = "#8bdc70";
                }

                else {
                    bar.style.background = "#43e6a4";
                }

            }

        });


        if (password.length === 0) {

            strengthText.textContent =
                "Use 6 or more characters";

        } else if (strength <= 1) {

            strengthText.textContent =
                "Weak password";

        } else if (strength === 2) {

            strengthText.textContent =
                "Fair password";

        } else if (strength === 3) {

            strengthText.textContent =
                "Good password";

        } else {

            strengthText.textContent =
                "Strong password";
        }

    }


    /* =====================================================
       LIVE VALIDATION
    ===================================================== */

    nameInput.addEventListener(
        "blur",
        validateName
    );

    emailInput.addEventListener(
        "blur",
        validateEmail
    );

    passwordInput.addEventListener(
        "input",
        function () {

            updatePasswordStrength();

            if (passwordInput.value !== "") {
                validatePassword();
            }

            if (confirmInput.value !== "") {
                validateConfirmPassword();
            }

        }
    );

    confirmInput.addEventListener(
        "input",
        validateConfirmPassword
    );


    /* =====================================================
       FORM SUBMIT
    ===================================================== */

    if (form) {

        form.addEventListener("submit", function (event) {

            const validName =
                validateName();

            const validEmail =
                validateEmail();

            const validPassword =
                validatePassword();

            const validConfirm =
                validateConfirmPassword();


            if (
                !validName ||
                !validEmail ||
                !validPassword ||
                !validConfirm
            ) {

                event.preventDefault();

                const firstInvalid =
                    form.querySelector(
                        ".input-wrapper.invalid input"
                    );

                if (firstInvalid) {
                    firstInvalid.focus();
                }

                return;
            }


            if (!termsInput.checked) {

                event.preventDefault();

                alert(
                    "Please agree to the terms and conditions."
                );

                termsInput.focus();

                return;
            }


            registerButton.classList.add("loading");

            registerButton.disabled = true;

        });

    }


    /* =====================================================
       TERMS LINK
    ===================================================== */

    const termsLink =
        document.getElementById("termsLink");

    if (termsLink) {

        termsLink.addEventListener("click", function (event) {

            event.preventDefault();

            alert(
                "Terms and conditions can be added here later."
            );

        });

    }


    /* =====================================================
       3D CAMERA PARALLAX
    ===================================================== */

    const cameraScene =
        document.querySelector(".camera-scene");

    const camera =
        document.querySelector(".camera");

    const photos =
        document.querySelectorAll(".floating-photo");


    if (
        cameraScene &&
        camera &&
        window.matchMedia(
            "(prefers-reduced-motion: no-preference)"
        ).matches
    ) {

        cameraScene.addEventListener(
            "mousemove",
            function (event) {

                const rect =
                    cameraScene.getBoundingClientRect();

                const x =
                    (event.clientX - rect.left)
                    / rect.width;

                const y =
                    (event.clientY - rect.top)
                    / rect.height;

                const rotateY =
                    -14 + ((x - 0.5) * 15);

                const rotateX =
                    8 - ((y - 0.5) * 12);

                camera.style.transform =
                    `rotateX(${rotateX}deg)
                     rotateY(${rotateY}deg)
                     rotateZ(-3deg)`;


                photos.forEach(function (photo, index) {

                    const amount =
                        (index + 1) * 5;

                    const moveX =
                        (x - 0.5) * amount;

                    const moveY =
                        (y - 0.5) * amount;

                    photo.style.marginLeft =
                        `${moveX}px`;

                    photo.style.marginTop =
                        `${moveY}px`;

                });

            }
        );


        cameraScene.addEventListener(
            "mouseleave",
            function () {

                camera.style.transform =
                    "rotateX(8deg) rotateY(-14deg) rotateZ(-3deg)";

                photos.forEach(function (photo) {

                    photo.style.marginLeft = "0";
                    photo.style.marginTop = "0";

                });

            }
        );

    }


    /* =====================================================
       INPUT FOCUS EFFECT
    ===================================================== */

    document.querySelectorAll(
        ".input-wrapper input"
    ).forEach(function (input) {

        input.addEventListener(
            "focus",
            function () {

                input.closest(".form-group")
                    ?.classList.add("focused");

            }
        );

        input.addEventListener(
            "blur",
            function () {

                input.closest(".form-group")
                    ?.classList.remove("focused");

            }
        );

    });

});