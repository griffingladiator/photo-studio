/* =========================================================
   FAST FOTO COLOUR LAB
   CONTACT PAGE JAVASCRIPT
   ========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    const siteHeader = document.getElementById("siteHeader");
    const menuToggle = document.getElementById("menuToggle");
    const navLinks = document.getElementById("navLinks");

    const contactForm = document.getElementById("contactForm");
    const messageField = document.getElementById("message");
    const characterCount = document.getElementById("characterCount");



    /* =====================================================
       HEADER SCROLL EFFECT
       ===================================================== */

    function updateHeader() {

        if (!siteHeader) {
            return;
        }

        if (window.scrollY > 30) {

            siteHeader.classList.add("scrolled");

        } else {

            siteHeader.classList.remove("scrolled");

        }

    }

    window.addEventListener(
        "scroll",
        updateHeader,
        { passive: true }
    );

    updateHeader();



    /* =====================================================
       MOBILE NAVIGATION
       ===================================================== */

    if (menuToggle && navLinks) {

        menuToggle.addEventListener("click", () => {

            const isOpen =
                navLinks.classList.toggle("open");

            menuToggle.setAttribute(
                "aria-expanded",
                isOpen ? "true" : "false"
            );

        });


        navLinks
            .querySelectorAll("a")
            .forEach(link => {

                link.addEventListener("click", () => {

                    navLinks.classList.remove("open");

                    menuToggle.setAttribute(
                        "aria-expanded",
                        "false"
                    );

                });

            });

    }



    /* =====================================================
       REVEAL ANIMATION
       ===================================================== */

    const revealElements =
        document.querySelectorAll(".reveal");

    if ("IntersectionObserver" in window) {

        const revealObserver =
            new IntersectionObserver(
                entries => {

                    entries.forEach(entry => {

                        if (entry.isIntersecting) {

                            entry.target.classList.add(
                                "visible"
                            );

                            revealObserver.unobserve(
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

            revealObserver.observe(element);

        });

    } else {

        revealElements.forEach(element => {

            element.classList.add("visible");

        });

    }



    /* =====================================================
       TEXTAREA CHARACTER COUNTER
       ===================================================== */

    function updateCharacterCount() {

        if (!messageField || !characterCount) {
            return;
        }

        let value =
            messageField.value;

        if (value.length > 1000) {

            value =
                value.substring(0, 1000);

            messageField.value = value;

        }

        characterCount.textContent =
            value.length;

    }


    if (messageField) {

        messageField.setAttribute(
            "maxlength",
            "1000"
        );

        messageField.addEventListener(
            "input",
            updateCharacterCount
        );

        updateCharacterCount();

    }



    /* =====================================================
       CONTACT FORM VALIDATION
       ===================================================== */

    if (contactForm) {

        contactForm.addEventListener(
            "submit",
            event => {

                const name =
                    document.getElementById("name");

                const email =
                    document.getElementById("email");

                const subject =
                    document.getElementById("subject");

                const message =
                    document.getElementById("message");


                let valid = true;


                [
                    name,
                    email,
                    subject,
                    message
                ].forEach(field => {

                    if (!field) {
                        return;
                    }

                    field.classList.remove(
                        "invalid"
                    );

                });


                if (!name.value.trim()) {

                    name.classList.add(
                        "invalid"
                    );

                    valid = false;

                }


                if (!email.value.trim()) {

                    email.classList.add(
                        "invalid"
                    );

                    valid = false;

                } else {

                    const emailPattern =
                        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (
                        !emailPattern.test(
                            email.value.trim()
                        )
                    ) {

                        email.classList.add(
                            "invalid"
                        );

                        valid = false;

                    }

                }


                if (!subject.value.trim()) {

                    subject.classList.add(
                        "invalid"
                    );

                    valid = false;

                }


                if (!message.value.trim()) {

                    message.classList.add(
                        "invalid"
                    );

                    valid = false;

                }


                if (!valid) {

                    event.preventDefault();

                    const firstInvalid =
                        contactForm.querySelector(
                            ".invalid"
                        );

                    if (firstInvalid) {

                        firstInvalid.focus();

                    }

                }

            }
        );

    }



    /* =====================================================
       REMOVE INVALID STATE WHEN USER TYPES
       ===================================================== */

    document
        .querySelectorAll(
            ".contact-form input, .contact-form textarea"
        )
        .forEach(field => {

            field.addEventListener(
                "input",
                () => {

                    field.classList.remove(
                        "invalid"
                    );

                }
            );

        });



    /* =====================================================
       FAQ ACCORDION
       ===================================================== */

    const faqItems =
        document.querySelectorAll(".faq-item");

    faqItems.forEach(item => {

        const question =
            item.querySelector(
                ".faq-question"
            );

        if (!question) {
            return;
        }

        question.addEventListener(
            "click",
            () => {

                const wasOpen =
                    item.classList.contains(
                        "open"
                    );


                faqItems.forEach(otherItem => {

                    otherItem.classList.remove(
                        "open"
                    );

                });


                if (!wasOpen) {

                    item.classList.add(
                        "open"
                    );

                }

            }
        );

    });



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
                     rotateZ(-2deg)`;

            }
        );


        cameraScene.addEventListener(
            "mouseleave",
            () => {

                camera.style.transform =
                    "translate(-50%, -50%) rotateX(8deg) rotateY(-13deg) rotateZ(-2deg)";

            }
        );

    }



    /* =====================================================
       SMOOTH FAQ ACCESSIBILITY
       ===================================================== */

    document
        .querySelectorAll(".faq-question")
        .forEach(button => {

            button.setAttribute(
                "aria-expanded",
                "false"
            );

            button.addEventListener(
                "click",
                () => {

                    const item =
                        button.closest(
                            ".faq-item"
                        );

                    const isOpen =
                        item &&
                        item.classList.contains(
                            "open"
                        );

                    button.setAttribute(
                        "aria-expanded",
                        isOpen
                            ? "true"
                            : "false"
                    );

                }
            );

        });



    /* =====================================================
       PAGE READY
       ===================================================== */

    document.body.classList.add(
        "page-ready"
    );

});