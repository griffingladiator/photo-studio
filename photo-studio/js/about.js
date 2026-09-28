/* =========================================================
   FAST FOTO COLOUR LAB
   ABOUT PAGE JAVASCRIPT
========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    /* =====================================================
       NAVBAR
    ===================================================== */

    const navbar = document.getElementById("navbar");

    const updateNavbar = () => {

        if (!navbar) {
            return;
        }

        if (window.scrollY > 30) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }

    };

    window.addEventListener(
        "scroll",
        updateNavbar,
        { passive: true }
    );

    updateNavbar();


    /* =====================================================
       MOBILE MENU
    ===================================================== */

    const menuButton =
        document.getElementById("mobileMenuBtn");

    const navMenu =
        document.getElementById("navMenu");

    if (menuButton && navMenu) {

        menuButton.addEventListener("click", () => {

            navMenu.classList.toggle("mobile-open");

            const opened =
                navMenu.classList.contains("mobile-open");

            menuButton.setAttribute(
                "aria-expanded",
                opened ? "true" : "false"
            );

        });


        navMenu
            .querySelectorAll(".nav-link")
            .forEach((link) => {

                link.addEventListener("click", () => {

                    navMenu.classList.remove(
                        "mobile-open"
                    );

                    menuButton.setAttribute(
                        "aria-expanded",
                        "false"
                    );

                });

            });

    }


    /* =====================================================
       CAMERA 3D MOVEMENT
    ===================================================== */

    const visual =
        document.querySelector(".about-visual");

    const camera =
        document.querySelector(".camera-platform");

    if (
        visual &&
        camera &&
        window.matchMedia("(pointer: fine)").matches
    ) {

        visual.addEventListener(
            "mousemove",
            (event) => {

                const rect =
                    visual.getBoundingClientRect();

                const x =
                    event.clientX - rect.left;

                const y =
                    event.clientY - rect.top;

                const centerX =
                    rect.width / 2;

                const centerY =
                    rect.height / 2;

                const rotateY =
                    ((x - centerX) / centerX) * 8;

                const rotateX =
                    ((centerY - y) / centerY) * 6;

                camera.style.transform =
                    `rotateX(${9 + rotateX}deg)
                     rotateY(${-12 + rotateY}deg)`;

            }
        );


        visual.addEventListener(
            "mouseleave",
            () => {

                camera.style.transform =
                    "rotateX(9deg) rotateY(-12deg)";

            }
        );

    }


    /* =====================================================
       CARD REVEAL
    ===================================================== */

    const revealElements =
        document.querySelectorAll(
            ".story-card, .value-card, .feature-item, .features-panel, .cta-card"
        );

    revealElements.forEach((element) => {

        element.style.opacity = "0";

        element.style.transform +=
            " translateY(25px)";

        element.style.transition =
            "opacity .7s ease, transform .7s ease";

    });


    if ("IntersectionObserver" in window) {

        const observer =
            new IntersectionObserver(
                (entries, obs) => {

                    entries.forEach((entry) => {

                        if (!entry.isIntersecting) {
                            return;
                        }

                        entry.target.style.opacity = "1";

                        entry.target.style.transform =
                            entry.target.style.transform
                                .replace(
                                    " translateY(25px)",
                                    ""
                                );

                        obs.unobserve(entry.target);

                    });

                },
                {
                    threshold: 0.12
                }
            );


        revealElements.forEach((element) => {

            observer.observe(element);

        });

    } else {

        revealElements.forEach((element) => {

            element.style.opacity = "1";

        });

    }


    /* =====================================================
       REDUCED MOTION
    ===================================================== */

    const reducedMotion =
        window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        );

    if (reducedMotion.matches) {

        document.documentElement.style.scrollBehavior =
            "auto";

        document
            .querySelectorAll("*")
            .forEach((element) => {

                element.style.animationDuration =
                    "0.001ms";

                element.style.transitionDuration =
                    "0.001ms";

            });

    }

});