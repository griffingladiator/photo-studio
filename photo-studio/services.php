<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Services | Fast Foto Colour Lab</title>

    <meta
        name="description"
        content="Explore photo printing, passport photos, enlargements, custom prints, bulk printing and digital photo services at Fast Foto Colour Lab."
    >

    <link rel="stylesheet" href="css/services.css">
</head>

<body>

<!-- =========================================================
     BACKGROUND
========================================================= -->
<div class="background-grid"></div>
<div class="ambient ambient-one"></div>
<div class="ambient ambient-two"></div>
<div class="ambient ambient-three"></div>


<!-- =========================================================
     NAVBAR
========================================================= -->
<header class="site-header" id="siteHeader">

    <nav class="navbar">

        <a href="index.php" class="brand">

            <span class="brand-icon">
                <span class="brand-lens"></span>
            </span>

            <span class="brand-text">
                <strong>Fast Foto</strong>
                <small>Colour Lab</small>
            </span>

        </a>


        <div class="nav-links" id="navLinks">

            <a href="index.php">Home</a>

            <a href="about.php">About</a>

            <a href="services.php" class="active">Services</a>

            <a href="contact.php">Contact</a>

            <?php if ($is_logged_in): ?>

                <a href="dashboard.php">Dashboard</a>

                <a href="upload.php" class="nav-button">
                    Start Printing
                </a>

            <?php else: ?>

                <a href="login.php">Login</a>

                <a href="register.php" class="nav-button">
                    Get Started
                </a>

            <?php endif; ?>

        </div>


        <button
            class="menu-toggle"
            id="menuToggle"
            type="button"
            aria-label="Open navigation menu"
            aria-expanded="false"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

    </nav>

</header>


<!-- =========================================================
     MAIN
========================================================= -->
<main>


    <!-- =====================================================
         HERO
    ====================================================== -->
    <section class="services-hero">

        <div class="hero-content reveal">

            <div class="section-badge">
                <span class="badge-dot"></span>
                OUR SERVICES
            </div>

            <h1>
                Turn Your
                <span>Memories</span>
                Into Prints.
            </h1>

            <p>
                Upload your photographs, choose the print options you need,
                and manage your photo printing order through one simple
                online platform.
            </p>

            <div class="hero-actions">

                <a href="upload.php" class="primary-button">
                    <span>Start Printing</span>
                    <span class="button-arrow">→</span>
                </a>

                <a href="#service-list" class="secondary-button">
                    Explore Services
                </a>

            </div>

        </div>


        <!-- 3D PRINTING MACHINE -->
        <div class="hero-visual reveal">

            <div class="printer-scene">

                <div class="printer-glow"></div>

                <div class="printer">

                    <div class="printer-top">

                        <div class="printer-display">
                            <span></span>
                            READY
                        </div>

                        <div class="printer-light"></div>

                    </div>


                    <div class="printer-body">

                        <div class="printer-slot">
                            <div class="paper-sheet"></div>
                        </div>

                        <div class="printer-controls">

                            <span></span>
                            <span></span>
                            <span></span>

                        </div>

                    </div>


                    <div class="printer-base">

                        <div class="paper-output">

                            <div class="photo-output">
                                <div class="mini-sun"></div>
                                <div class="mini-mountain"></div>
                            </div>

                        </div>

                    </div>

                </div>


                <div class="floating-print print-one">
                    <div class="print-image landscape"></div>
                </div>

                <div class="floating-print print-two">
                    <div class="print-image portrait"></div>
                </div>

                <div class="floating-print print-three">
                    <div class="print-image sunset"></div>
                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         SERVICE INTRO
    ====================================================== -->
    <section class="service-intro">

        <div class="section-heading reveal">

            <div class="section-badge">
                <span class="badge-dot"></span>
                WHAT WE OFFER
            </div>

            <h2>
                Everything You Need
                <span>For Your Photos</span>
            </h2>

            <p>
                The platform is designed to make common photo-printing
                requirements easier to manage from upload to order.
            </p>

        </div>

    </section>


    <!-- =====================================================
         SERVICES
    ====================================================== -->
    <section class="service-list" id="service-list">

        <div class="services-grid">


            <!-- CARD 1 -->
            <article class="service-card reveal">

                <div class="card-number">01</div>

                <div class="service-icon icon-print">

                    <div class="icon-paper"></div>

                </div>

                <div class="service-content">

                    <h3>Photo Prints</h3>

                    <p>
                        Upload your photographs and prepare them for
                        high-quality physical photo printing.
                    </p>

                    <ul>
                        <li>Multiple photo formats</li>
                        <li>Easy online upload</li>
                        <li>Print-ready workflow</li>
                    </ul>

                </div>

                <a href="upload.php" class="service-link">
                    Start Printing <span>→</span>
                </a>

            </article>


            <!-- CARD 2 -->
            <article class="service-card reveal">

                <div class="card-number">02</div>

                <div class="service-icon icon-passport">

                    <div class="person-head"></div>
                    <div class="person-body"></div>

                </div>

                <div class="service-content">

                    <h3>Passport & ID Photos</h3>

                    <p>
                        Prepare photographs suitable for passport,
                        identification and other document requirements.
                    </p>

                    <ul>
                        <li>Standard photo formats</li>
                        <li>Digital upload workflow</li>
                        <li>Easy order management</li>
                    </ul>

                </div>

                <a href="upload.php" class="service-link">
                    Upload Photo <span>→</span>
                </a>

            </article>


            <!-- CARD 3 -->
            <article class="service-card reveal">

                <div class="card-number">03</div>

                <div class="service-icon icon-expand">

                    <div class="expand-corners">
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                    </div>

                </div>

                <div class="service-content">

                    <h3>Photo Enlargements</h3>

                    <p>
                        Prepare larger versions of your favourite
                        photographs for display and special occasions.
                    </p>

                    <ul>
                        <li>Large-format options</li>
                        <li>Photo selection</li>
                        <li>Order tracking</li>
                    </ul>

                </div>

                <a href="upload.php" class="service-link">
                    Create Order <span>→</span>
                </a>

            </article>


            <!-- CARD 4 -->
            <article class="service-card reveal">

                <div class="card-number">04</div>

                <div class="service-icon icon-custom">

                    <div class="custom-frame">
                        <span></span>
                    </div>

                </div>

                <div class="service-content">

                    <h3>Custom Photo Prints</h3>

                    <p>
                        Select photographs and prepare customised
                        printing requirements through the online system.
                    </p>

                    <ul>
                        <li>Flexible print selection</li>
                        <li>Digital photo editing</li>
                        <li>Custom order details</li>
                    </ul>

                </div>

                <a href="upload.php" class="service-link">
                    Customize <span>→</span>
                </a>

            </article>


            <!-- CARD 5 -->
            <article class="service-card reveal">

                <div class="card-number">05</div>

                <div class="service-icon icon-bulk">

                    <div class="stack stack-one"></div>
                    <div class="stack stack-two"></div>
                    <div class="stack stack-three"></div>

                </div>

                <div class="service-content">

                    <h3>Event & Bulk Printing</h3>

                    <p>
                        Organise multiple photographs for events,
                        functions and larger printing requirements.
                    </p>

                    <ul>
                        <li>Multiple image uploads</li>
                        <li>Organised orders</li>
                        <li>Centralised management</li>
                    </ul>

                </div>

                <a href="upload.php" class="service-link">
                    Upload Photos <span>→</span>
                </a>

            </article>


            <!-- CARD 6 -->
            <article class="service-card reveal">

                <div class="card-number">06</div>

                <div class="service-icon icon-digital">

                    <div class="digital-screen">

                        <span class="screen-sun"></span>
                        <span class="screen-mountain"></span>

                    </div>

                </div>

                <div class="service-content">

                    <h3>Digital Photo Services</h3>

                    <p>
                        Manage digital photographs before sending them
                        through the printing and ordering workflow.
                    </p>

                    <ul>
                        <li>Digital image upload</li>
                        <li>Basic editing workflow</li>
                        <li>Order history</li>
                    </ul>

                </div>

                <a href="upload.php" class="service-link">
                    Get Started <span>→</span>
                </a>

            </article>

        </div>

    </section>


    <!-- =====================================================
         HOW IT WORKS
    ====================================================== -->
    <section class="workflow-section">

        <div class="section-heading reveal">

            <div class="section-badge">
                <span class="badge-dot"></span>
                SIMPLE WORKFLOW
            </div>

            <h2>
                From Photo
                <span>To Print</span>
            </h2>

            <p>
                A straightforward workflow helps you move from selecting
                your photos to managing your printing order.
            </p>

        </div>


        <div class="workflow">

            <div class="workflow-line"></div>


            <div class="workflow-step reveal">

                <div class="step-circle">
                    01
                </div>

                <h3>Upload</h3>

                <p>
                    Select and upload the photographs you want to print.
                </p>

            </div>


            <div class="workflow-step reveal">

                <div class="step-circle">
                    02
                </div>

                <h3>Edit</h3>

                <p>
                    Prepare your photograph and select the required
                    printing options.
                </p>

            </div>


            <div class="workflow-step reveal">

                <div class="step-circle">
                    03
                </div>

                <h3>Order</h3>

                <p>
                    Confirm your selected photos and provide your order
                    details.
                </p>

            </div>


            <div class="workflow-step reveal">

                <div class="step-circle">
                    04
                </div>

                <h3>Track</h3>

                <p>
                    View your order information and follow its status.
                </p>

            </div>

        </div>

    </section>


    <!-- =====================================================
         CTA
    ====================================================== -->
    <section class="service-cta">

        <div class="cta-box reveal">

            <div class="cta-content">

                <div class="section-badge">
                    <span class="badge-dot"></span>
                    READY TO PRINT?
                </div>

                <h2>
                    Your Photos.
                    <span>Your Memories.</span>
                </h2>

                <p>
                    Start your photo printing journey with
                    Fast Foto Colour Lab.
                </p>

                <a href="upload.php" class="primary-button">

                    <span>Upload Your Photos</span>

                    <span class="button-arrow">→</span>

                </a>

            </div>


            <div class="cta-orbit">

                <div class="orbit-ring ring-one"></div>
                <div class="orbit-ring ring-two"></div>

                <div class="orbit-photo">
                    <div class="orbit-photo-inner"></div>
                </div>

            </div>

        </div>

    </section>

</main>


<!-- =========================================================
     FOOTER
========================================================= -->
<footer class="site-footer">

    <div class="footer-inner">

        <div class="footer-brand">

            <a href="index.php" class="brand">

                <span class="brand-icon">
                    <span class="brand-lens"></span>
                </span>

                <span class="brand-text">
                    <strong>Fast Foto</strong>
                    <small>Colour Lab</small>
                </span>

            </a>

            <p>
                An online photo studio and printing management
                system designed to make photo printing simple.
            </p>

        </div>


        <div class="footer-links">

            <div>

                <h4>Explore</h4>

                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="services.php">Services</a>
                <a href="contact.php">Contact</a>

            </div>


            <div>

                <h4>Account</h4>

                <?php if ($is_logged_in): ?>

                    <a href="dashboard.php">Dashboard</a>
                    <a href="upload.php">Start Printing</a>

                <?php else: ?>

                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>

                <?php endif; ?>

            </div>

        </div>

    </div>


    <div class="footer-bottom">

        <span>
            © <?php echo date('Y'); ?> Fast Foto Colour Lab
        </span>

        <span>
            Online Photo Studio Printing Management System
        </span>

    </div>

</footer>


<script src="js/services.js"></script>

</body>
</html>