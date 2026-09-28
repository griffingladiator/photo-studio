/* =========================================================
   FAST FOTO COLOUR LAB
   HOME PAGE JAVASCRIPT
========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    /* =====================================================
       PAGE LOADER
    ===================================================== */

    const pageLoader = document.getElementById("pageLoader");

    window.addEventListener("load", () => {

        setTimeout(() => {

            if (pageLoader) {
                pageLoader.classList.add("loaded");
            }

        }, 500);

    });


    /* =====================================================
       NAVBAR SCROLL EFFECT
    ===================================================== */

    const navbar = document.getElementById("navbar");

    const handleNavbarScroll = () => {

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
        handleNavbarScroll,
        { passive: true }
    );

    handleNavbarScroll();


    /* =====================================================
       MOBILE MENU
    ===================================================== */

    const mobileMenuBtn =
        document.getElementById("mobileMenuBtn");

    const navMenu =
        document.getElementById("navMenu");

    if (mobileMenuBtn && navMenu) {

        mobileMenuBtn.addEventListener("click", () => {

            navMenu.classList.toggle("mobile-open");

            const isOpen =
                navMenu.classList.contains("mobile-open");

            mobileMenuBtn.setAttribute(
                "aria-expanded",
                isOpen ? "true" : "false"
            );

        });


        /* Close menu after selecting a link */

        const navLinks =
            navMenu.querySelectorAll(".nav-link");

        navLinks.forEach((link) => {

            link.addEventListener("click", () => {

                navMenu.classList.remove("mobile-open");

                mobileMenuBtn.setAttribute(
                    "aria-expanded",
                    "false"
                );

            });

        });

    }


    /* =====================================================
       3D PHOTO MOUSE MOVEMENT
    ===================================================== */

    const heroVisual =
        document.getElementById("heroVisual");

    const photoStage =
        document.querySelector(".photo-stage");

    if (
        heroVisual &&
        photoStage &&
        window.matchMedia("(pointer: fine)").matches
    ) {

        heroVisual.addEventListener(
            "mousemove",
            (event) => {

                const rect =
                    heroVisual.getBoundingClientRect();

                const x =
                    event.clientX - rect.left;

                const y =
                    event.clientY - rect.top;

                const centerX =
                    rect.width / 2;

                const centerY =
                    rect.height / 2;

                const rotateY =
                    ((x - centerX) / centerX) * 7;

                const rotateX =
                    ((centerY - y) / centerY) * 6;

                photoStage.style.transform =
                    `rotateX(${rotateX}deg)
                     rotateY(${rotateY}deg)`;

            }
        );


        heroVisual.addEventListener(
            "mouseleave",
            () => {

                photoStage.style.transform =
                    "rotateX(0deg) rotateY(0deg)";

            }
        );

    }


    /* =====================================================
       SCROLL REVEAL
    ===================================================== */

    const revealElements =
        document.querySelectorAll(
            ".service-card, .process-step, .cta-card"
        );

    revealElements.forEach((element) => {
        element.classList.add("reveal");
    });


    if ("IntersectionObserver" in window) {

        const observer =
            new IntersectionObserver(
                (entries, obs) => {

                    entries.forEach((entry) => {

                        if (entry.isIntersecting) {

                            entry.target.classList.add(
                                "visible"
                            );

                            obs.unobserve(entry.target);

                        }

                    });

                },
                {
                    threshold: 0.15
                }
            );


        revealElements.forEach((element) => {

            observer.observe(element);

        });

    } else {

        revealElements.forEach((element) => {

            element.classList.add("visible");

        });

    }


    /* =====================================================
       SERVICE CARD TILT
    ===================================================== */

    const serviceCards =
        document.querySelectorAll(".service-card");

    if (
        serviceCards.length &&
        window.matchMedia("(pointer: fine)").matches
    ) {

        serviceCards.forEach((card) => {

            card.addEventListener(
                "mousemove",
                (event) => {

                    const rect =
                        card.getBoundingClientRect();

                    const x =
                        event.clientX - rect.left;

                    const y =
                        event.clientY - rect.top;

                    const centerX =
                        rect.width / 2;

                    const centerY =
                        rect.height / 2;

                    const rotateX =
                        ((y - centerY) / centerY) * -3;

                    const rotateY =
                        ((x - centerX) / centerX) * 3;

                    card.style.transform =
                        `perspective(700px)
                         rotateX(${rotateX}deg)
                         rotateY(${rotateY}deg)
                         translateY(-10px)`;

                }
            );


            card.addEventListener(
                "mouseleave",
                () => {

                    card.style.transform = "";

                }
            );

        });

    }


    /* =====================================================
       SMOOTH INTERNAL ANCHORS
    ===================================================== */

    document
        .querySelectorAll('a[href^="#"]')
        .forEach((link) => {

            link.addEventListener(
                "click",
                (event) => {

                    const targetId =
                        link.getAttribute("href");

                    if (
                        !targetId ||
                        targetId === "#"
                    ) {
                        return;
                    }

                    const target =
                        document.querySelector(targetId);

                    if (!target) {
                        return;
                    }

                    event.preventDefault();

                    target.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });

                }
            );

        });


    /* =====================================================
       REDUCE ANIMATIONS FOR ACCESSIBILITY
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

                element.style.animationDuration = "0.001ms";
                element.style.transitionDuration = "0.001ms";

            });

    }

});