<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fast Foto Colour Lab | Online Photo Printing</title>

    <meta
        name="description"
        content="Fast Foto Colour Lab - Online Photo Studio Printing Management System. Upload, customize and order your photo prints online."
    >

    <link rel="stylesheet" href="css/home.css">
</head>

<body>

<!-- =========================
     PAGE LOADER
========================= -->

<div class="page-loader" id="pageLoader">
    <div class="loader-content">
        <div class="loader-ring"></div>
        <span>FAST FOTO</span>
    </div>
</div>


<!-- =========================
     BACKGROUND
========================= -->

<div class="background-effects">

    <div class="gradient-orb orb-one"></div>
    <div class="gradient-orb orb-two"></div>
    <div class="gradient-orb orb-three"></div>

    <div class="grid-background"></div>

</div>


<!-- =========================
     NAVBAR
========================= -->

<header class="navbar" id="navbar">

    <div class="nav-container">

        <a href="index.php" class="brand">

            <div class="brand-logo">
                <span>FF</span>
            </div>

            <div class="brand-text">
                <strong>Fast Foto</strong>
                <small>Colour Lab</small>
            </div>

        </a>


        <nav class="nav-menu" id="navMenu">

            <a href="index.php" class="nav-link active">
                Home
            </a>

            <a href="about.php" class="nav-link">
                About
            </a>

            <a href="services.php" class="nav-link">
                Services
            </a>

            <a href="contact.php" class="nav-link">
                Contact
            </a>

        </nav>


        <div class="nav-actions">

            <?php if ($is_logged_in): ?>

                <a href="dashboard.php" class="login-btn">
                    Dashboard
                </a>

                <a href="upload.php" class="nav-cta">
                    Start Printing
                    <span>→</span>
                </a>

            <?php else: ?>

                <a href="login.php" class="login-btn">
                    Login
                </a>

                <a href="register.php" class="nav-cta">
                    Get Started
                    <span>→</span>
                </a>

            <?php endif; ?>

        </div>


        <button
            class="mobile-menu-btn"
            id="mobileMenuBtn"
            aria-label="Open navigation menu"
            type="button"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>

</header>


<!-- =========================
     HERO SECTION
========================= -->

<main>

<section class="hero-section">

    <div class="hero-container">


        <!-- LEFT CONTENT -->

        <div class="hero-content">

            <div class="hero-badge">
                <span class="badge-dot"></span>
                Online Photo Printing
            </div>


            <h1>
                Turn Your
                <span class="gradient-text">Memories</span>
                Into Prints.
            </h1>


            <p class="hero-description">
                Upload your favourite photos, customize your prints,
                and place your order online with
                <strong>Fast Foto Colour Lab.</strong>
            </p>


            <div class="hero-buttons">

                <?php if ($is_logged_in): ?>

                    <a href="upload.php" class="primary-btn">

                        <span class="btn-icon">✦</span>

                        Start Printing

                        <span class="btn-arrow">→</span>

                    </a>

                <?php else: ?>

                    <a href="register.php" class="primary-btn">

                        <span class="btn-icon">✦</span>

                        Start Printing

                        <span class="btn-arrow">→</span>

                    </a>

                <?php endif; ?>


                <a href="services.php" class="secondary-btn">

                    Explore Services

                    <span>↗</span>

                </a>

            </div>


            <!-- HERO FEATURES -->

            <div class="hero-features">

                <div class="hero-feature">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>
                        <strong>Easy Upload</strong>
                        <span>Upload photos online</span>
                    </div>

                </div>


                <div class="hero-feature">

                    <div class="feature-icon">
                        ◆
                    </div>

                    <div>
                        <strong>Quality Prints</strong>
                        <span>Beautiful photo output</span>
                    </div>

                </div>


                <div class="hero-feature">

                    <div class="feature-icon">
                        ⚡
                    </div>

                    <div>
                        <strong>Simple Ordering</strong>
                        <span>Order in a few steps</span>
                    </div>

                </div>

            </div>

        </div>


        <!-- RIGHT 3D PHOTO DISPLAY -->

        <div class="hero-visual" id="heroVisual">


            <!-- Glow behind cards -->

            <div class="visual-glow"></div>


            <!-- Floating decorative circles -->

            <div class="floating-circle circle-one"></div>
            <div class="floating-circle circle-two"></div>


            <!-- Main photo stack -->

            <div class="photo-stage">


                <!-- Back card -->

                <div class="photo-card photo-back">

                    <div class="fake-photo photo-bg-three">

                        <div class="photo-shape shape-a"></div>
                        <div class="photo-shape shape-b"></div>
                        <div class="photo-shape shape-c"></div>

                    </div>

                </div>


                <!-- Middle card -->

                <div class="photo-card photo-middle">

                    <div class="fake-photo photo-bg-two">

                        <div class="mountain mountain-one"></div>
                        <div class="mountain mountain-two"></div>

                        <div class="sun"></div>

                    </div>

                </div>


                <!-- Main card -->

                <div class="photo-card photo-main">

                    <div class="photo-image photo-bg-one">

                        <div class="photo-sky"></div>

                        <div class="sun-large"></div>

                        <div class="hill hill-one"></div>
                        <div class="hill hill-two"></div>

                        <div class="photo-ground"></div>

                    </div>


                    <div class="photo-caption">

                        <span class="caption-dot"></span>

                        YOUR MEMORIES

                    </div>

                </div>


                <!-- Small floating photo -->

                <div class="mini-photo">

                    <div class="mini-photo-inner">

                        <span>✦</span>

                    </div>

                </div>


                <!-- Floating print label -->

                <div class="print-label">

                    <span class="print-icon">▣</span>

                    <div>
                        <strong>PRINT READY</strong>
                        <small>Your photo is ready</small>
                    </div>

                </div>


                <!-- Floating quality badge -->

                <div class="quality-badge">

                    <div class="quality-check">
                        ✓
                    </div>

                    <div>
                        <strong>HIGH QUALITY</strong>
                        <span>Photo printing</span>
                    </div>

                </div>


            </div>

        </div>

    </div>


    <!-- SCROLL INDICATOR -->

    <div class="scroll-indicator">

        <span class="scroll-line"></span>

        <span>SCROLL TO EXPLORE</span>

    </div>

</section>


<!-- =========================
     QUICK SERVICES
========================= -->

<section class="quick-services">

    <div class="section-container">


        <div class="section-heading">

            <span class="section-label">
                WHAT WE DO
            </span>

            <h2>
                Everything You Need
                <span>For Your Photos.</span>
            </h2>

            <p>
                From digital uploads to beautiful physical prints,
                manage your photo orders easily through one platform.
            </p>

        </div>


        <div class="service-grid">


            <article class="service-card">

                <div class="service-number">
                    01
                </div>

                <div class="service-icon">
                    ▣
                </div>

                <h3>
                    Photo Printing
                </h3>

                <p>
                    Upload your digital memories and order
                    high-quality photo prints.
                </p>

                <a href="services.php">
                    Explore
                    <span>→</span>
                </a>

            </article>


            <article class="service-card featured-service">

                <div class="service-number">
                    02
                </div>

                <div class="service-icon">
                    ◈
                </div>

                <h3>
                    Custom Sizes
                </h3>

                <p>
                    Choose suitable print sizes and customize
                    your photo order according to your needs.
                </p>

                <a href="services.php">
                    Explore
                    <span>→</span>
                </a>

            </article>


            <article class="service-card">

                <div class="service-number">
                    03
                </div>

                <div class="service-icon">
                    ◎
                </div>

                <h3>
                    Online Ordering
                </h3>

                <p>
                    Place and track your photo printing orders
                    conveniently from anywhere.
                </p>

                <a href="services.php">
                    Explore
                    <span>→</span>
                </a>

            </article>


            <article class="service-card">

                <div class="service-number">
                    04
                </div>

                <div class="service-icon">
                    ✦
                </div>

                <h3>
                    Photo Management
                </h3>

                <p>
                    Keep your uploaded photos and orders
                    organized through your account.
                </p>

                <a href="services.php">
                    Explore
                    <span>→</span>
                </a>

            </article>


        </div>

    </div>

</section>


<!-- =========================
     HOW IT WORKS
========================= -->

<section class="process-section">

    <div class="section-container">


        <div class="process-header">

            <div>

                <span class="section-label">
                    SIMPLE PROCESS
                </span>

                <h2>
                    From Photo
                    <span>To Print.</span>
                </h2>

            </div>

            <p>
                A simple online workflow designed to make
                photo printing easier.
            </p>

        </div>


        <div class="process-grid">


            <div class="process-step">

                <div class="process-icon">
                    <span>01</span>
                </div>

                <h3>
                    Create Account
                </h3>

                <p>
                    Register and create your personal account.
                </p>

            </div>


            <div class="process-line"></div>


            <div class="process-step">

                <div class="process-icon">
                    <span>02</span>
                </div>

                <h3>
                    Upload Photos
                </h3>

                <p>
                    Select and upload the photos you want to print.
                </p>

            </div>


            <div class="process-line"></div>


            <div class="process-step">

                <div class="process-icon">
                    <span>03</span>
                </div>

                <h3>
                    Customize
                </h3>

                <p>
                    Choose your printing options and quantities.
                </p>

            </div>


            <div class="process-line"></div>


            <div class="process-step">

                <div class="process-icon">
                    <span>04</span>
                </div>

                <h3>
                    Place Order
                </h3>

                <p>
                    Confirm your details and submit your order.
                </p>

            </div>


        </div>

    </div>

</section>


<!-- =========================
     CTA SECTION
========================= -->

<section class="cta-section">

    <div class="cta-card">

        <div class="cta-glow"></div>

        <div class="cta-content">

            <span class="section-label">
                READY TO PRINT?
            </span>

            <h2>
                Your Favourite Moments
                <span>Deserve To Be Printed.</span>
            </h2>

            <p>
                Bring your digital memories to life with
                Fast Foto Colour Lab.
            </p>


            <?php if ($is_logged_in): ?>

                <a href="upload.php" class="primary-btn cta-button">

                    Start Your Order

                    <span class="btn-arrow">→</span>

                </a>

            <?php else: ?>

                <a href="register.php" class="primary-btn cta-button">

                    Create Your Account

                    <span class="btn-arrow">→</span>

                </a>

            <?php endif; ?>

        </div>


        <div class="cta-decoration">

            <div class="cta-ring ring-one"></div>
            <div class="cta-ring ring-two"></div>
            <div class="cta-ring ring-three"></div>

            <div class="cta-camera">
                ✦
            </div>

        </div>

    </div>

</section>

</main>


<!-- =========================
     FOOTER
========================= -->

<footer class="footer">

    <div class="footer-container">


        <div class="footer-brand">

            <a href="index.php" class="brand">

                <div class="brand-logo">
                    <span>FF</span>
                </div>

                <div class="brand-text">
                    <strong>Fast Foto</strong>
                    <small>Colour Lab</small>
                </div>

            </a>

            <p>
                Making your digital memories
                ready for the real world.
            </p>

        </div>


        <div class="footer-column">

            <h4>Explore</h4>

            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="contact.php">Contact</a>

        </div>


        <div class="footer-column">

            <h4>Account</h4>

            <?php if ($is_logged_in): ?>

                <a href="dashboard.php">Dashboard</a>
                <a href="upload.php">Upload Photo</a>
                <a href="orders.php">My Orders</a>

            <?php else: ?>

                <a href="login.php">Login</a>
                <a href="register.php">Register</a>

            <?php endif; ?>

        </div>


        <div class="footer-column">

            <h4>Fast Foto</h4>

            <span>Photo Printing</span>
            <span>Online Ordering</span>
            <span>Photo Management</span>

        </div>


    </div>


    <div class="footer-bottom">

        <span>
            © <?php echo date("Y"); ?> Fast Foto Colour Lab.
            All rights reserved.
        </span>

        <span>
            Online Photo Studio Printing Management System
        </span>

    </div>

</footer>


<script src="js/home.js"></script>

</body>
</html>