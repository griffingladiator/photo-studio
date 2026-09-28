/* =========================================================
   FAST FOTO COLOUR LAB
   CUSTOMER DASHBOARD JAVASCRIPT
========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    const mobileMenuBtn =
        document.getElementById("mobileMenuBtn");

    const mobileMenu =
        document.getElementById("mobileMenu");


    /* =====================================================
       MOBILE MENU
    ====================================================== */

    if (mobileMenuBtn && mobileMenu) {

        mobileMenuBtn.addEventListener("click", () => {

            mobileMenu.classList.toggle("open");

        });


        document.addEventListener("click", (event) => {

            if (
                !mobileMenu.contains(event.target) &&
                !mobileMenuBtn.contains(event.target)
            ) {
                mobileMenu.classList.remove("open");
            }

        });

    }


    /* =====================================================
       3D QUICK CARD EFFECT
    ====================================================== */

    const cards =
        document.querySelectorAll(".quick-card");


    cards.forEach(card => {

        card.addEventListener("mousemove", event => {

            if (
                window.matchMedia(
                    "(prefers-reduced-motion: reduce)"
                ).matches
            ) {
                return;
            }

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
                `perspective(800px)
                 rotateX(${rotateX}deg)
                 rotateY(${rotateY}deg)
                 translateY(-8px)`;

        });


        card.addEventListener("mouseleave", () => {

            card.style.transform = "";

        });

    });


    /* =====================================================
       STAT NUMBER ANIMATION
    ====================================================== */

    const numbers =
        document.querySelectorAll(".stat-content strong");


    const animateNumber = element => {

        const target =
            parseInt(
                element.textContent,
                10
            );

        if (
            Number.isNaN(target) ||
            target <= 0
        ) {
            return;
        }

        let current = 0;

        const duration = 700;

        const start =
            performance.now();


        const update = now => {

            const progress =
                Math.min(
                    (now - start) / duration,
                    1
                );

            const eased =
                1 -
                Math.pow(
                    1 - progress,
                    3
                );

            current =
                Math.floor(
                    target * eased
                );

            element.textContent =
                current;


            if (progress < 1) {

                requestAnimationFrame(update);

            } else {

                element.textContent =
                    target;

            }

        };


        requestAnimationFrame(update);

    };


    const statsObserver =
        new IntersectionObserver(
            entries => {

                entries.forEach(entry => {

                    if (entry.isIntersecting) {

                        animateNumber(entry.target);

                        statsObserver.unobserve(
                            entry.target
                        );

                    }

                });

            },
            {
                threshold: 0.6
            }
        );


    numbers.forEach(number => {

        statsObserver.observe(number);

    });


    /* =====================================================
       SCROLL REVEAL
    ====================================================== */

    const revealItems =
        document.querySelectorAll(
            ".stat-card, .quick-card, .workflow-step, .account-card"
        );


    revealItems.forEach((item, index) => {

        item.style.opacity = "0";

        item.style.transform =
            "translateY(18px)";

        item.style.transition =
            `opacity .55s ease ${index * 0.04}s,
             transform .55s ease ${index * 0.04}s`;

    });


    const revealObserver =
        new IntersectionObserver(
            entries => {

                entries.forEach(entry => {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.style.opacity = "1";

                    entry.target.style.transform =
                        "translateY(0)";

                    revealObserver.unobserve(
                        entry.target
                    );

                });

            },
            {
                threshold: 0.12
            }
        );


    revealItems.forEach(item => {

        revealObserver.observe(item);

    });


    /* =====================================================
       NAVBAR SHADOW ON SCROLL
    ====================================================== */

    const header =
        document.querySelector(
            ".dashboard-header"
        );


    const handleScroll = () => {

        if (!header) {
            return;
        }

        if (window.scrollY > 20) {

            header.style.boxShadow =
                "0 12px 40px rgba(0,0,0,0.28)";

        } else {

            header.style.boxShadow = "none";

        }

    };


    window.addEventListener(
        "scroll",
        handleScroll,
        {
            passive: true
        }
    );


    handleScroll();

});