/* =========================================================
   FAST FOTO COLOUR LAB
   LOGIN PAGE JAVASCRIPT
   ========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    const loginForm =
        document.getElementById("loginForm");

    const email =
        document.getElementById("email");

    const password =
        document.getElementById("password");

    const passwordToggle =
        document.getElementById("passwordToggle");

    const forgotPassword =
        document.getElementById("forgotPassword");

    const emailError =
        document.getElementById("emailError");

    const passwordError =
        document.getElementById("passwordError");



    /* =====================================================
       REVEAL ANIMATIONS
       ===================================================== */

    const revealElements =
        document.querySelectorAll(".reveal");


    if ("IntersectionObserver" in window) {

        const observer =
            new IntersectionObserver(
                entries => {

                    entries.forEach(entry => {

                        if (
                            entry.isIntersecting
                        ) {

                            entry.target.classList.add(
                                "visible"
                            );

                            observer.unobserve(
                                entry.target
                            );

                        }

                    });

                },
                {
                    threshold: 0.12
                }
            );


        revealElements.forEach(element => {

            observer.observe(element);

        });

    } else {

        revealElements.forEach(element => {

            element.classList.add(
                "visible"
            );

        });

    }



    /* =====================================================
       PASSWORD SHOW / HIDE
       ===================================================== */

    if (
        password &&
        passwordToggle
    ) {

        passwordToggle.addEventListener(
            "click",
            () => {

                const isPassword =
                    password.type === "password";


                password.type =
                    isPassword
                        ? "text"
                        : "password";


                passwordToggle.textContent =
                    isPassword
                        ? "◌"
                        : "◉";


                passwordToggle.setAttribute(
                    "aria-label",
                    isPassword
                        ? "Hide password"
                        : "Show password"
                );

            }
        );

    }



    /* =====================================================
       VALIDATION HELPERS
       ===================================================== */

    function showError(
        field,
        errorElement,
        message
    ) {

        if (field) {

            field.classList.add(
                "invalid"
            );

        }

        if (errorElement) {

            errorElement.textContent =
                message;

        }

    }


    function clearError(
        field,
        errorElement
    ) {

        if (field) {

            field.classList.remove(
                "invalid"
            );

        }

        if (errorElement) {

            errorElement.textContent =
                "";

        }

    }



    /* =====================================================
       EMAIL VALIDATION
       ===================================================== */

    function validateEmail() {

        if (!email) {
            return false;
        }

        const value =
            email.value.trim();


        if (!value) {

            showError(
                email,
                emailError,
                "Email address is required."
            );

            return false;
        }


        const pattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


        if (!pattern.test(value)) {

            showError(
                email,
                emailError,
                "Enter a valid email address."
            );

            return false;
        }


        clearError(
            email,
            emailError
        );

        return true;
    }



    /* =====================================================
       PASSWORD VALIDATION
       ===================================================== */

    function validatePassword() {

        if (!password) {
            return false;
        }

        const value =
            password.value;


        if (!value) {

            showError(
                password,
                passwordError,
                "Password is required."
            );

            return false;
        }


        clearError(
            password,
            passwordError
        );

        return true;
    }



    /* =====================================================
       FORM SUBMISSION
       ===================================================== */

    if (loginForm) {

        loginForm.addEventListener(
            "submit",
            event => {

                const emailValid =
                    validateEmail();

                const passwordValid =
                    validatePassword();


                if (
                    !emailValid ||
                    !passwordValid
                ) {

                    event.preventDefault();

                    if (!emailValid) {

                        email.focus();

                    } else if (!passwordValid) {

                        password.focus();

                    }

                    return;
                }


                /*
                 * Small loading state.
                 */

                const button =
                    loginForm.querySelector(
                        ".login-button"
                    );


                if (button) {

                    button.disabled = true;

                    button.innerHTML = `
                        <span>Signing In...</span>
                        <span class="button-arrow">↗</span>
                    `;

                }

            }
        );

    }



    /* =====================================================
       LIVE VALIDATION
       ===================================================== */

    if (email) {

        email.addEventListener(
            "input",
            () => {

                if (
                    email.classList.contains(
                        "invalid"
                    )
                ) {

                    validateEmail();

                }

            }
        );

        email.addEventListener(
            "blur",
            validateEmail
        );

    }


    if (password) {

        password.addEventListener(
            "input",
            () => {

                if (
                    password.classList.contains(
                        "invalid"
                    )
                ) {

                    validatePassword();

                }

            }
        );

    }



    /* =====================================================
       FORGOT PASSWORD
       ===================================================== */

    if (forgotPassword) {

        forgotPassword.addEventListener(
            "click",
            event => {

                event.preventDefault();

                alert(
                    "Password recovery can be connected to your email system in the next security step."
                );

            }
        );

    }



    /* =====================================================
       CAMERA PARALLAX
       ===================================================== */

    const cameraScene =
        document.querySelector(
            ".camera-scene"
        );

    const camera =
        document.querySelector(
            ".camera"
        );


    if (
        cameraScene &&
        camera &&
        window.matchMedia(
            "(prefers-reduced-motion: no-preference)"
        ).matches
    ) {

        cameraScene.addEventListener(
            "mousemove",
            event => {

                const rect =
                    cameraScene.getBoundingClientRect();


                const x =
                    (event.clientX - rect.left)
                    / rect.width;


                const y =
                    (event.clientY - rect.top)
                    / rect.height;


                const rotateY =
                    (x - 0.5) * 12;


                const rotateX =
                    (0.5 - y) * 10;


                camera.style.transform =
                    `translate(-50%, -50%)
                     rotateX(${8 + rotateX}deg)
                     rotateY(${-13 + rotateY}deg)
                     rotateZ(-3deg)`;

            }
        );


        cameraScene.addEventListener(
            "mouseleave",
            () => {

                camera.style.transform =
                    "translate(-50%, -50%) rotateX(8deg) rotateY(-13deg) rotateZ(-3deg)";

            }
        );

    }



    /* =====================================================
       PAGE READY
       ===================================================== */

    document.body.classList.add(
        "page-ready"
    );

});