<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us | Fast Foto Colour Lab</title>

    <meta
        name="description"
        content="Learn about Fast Foto Colour Lab and our Online Photo Studio Printing Management System."
    >

    <link rel="stylesheet" href="css/about.css">
</head>

<body>

<!-- =========================
     BACKGROUND
========================= -->

<div class="background-effects">
    <div class="gradient-orb orb-one"></div>
    <div class="gradient-orb orb-two"></div>
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

            <a href="index.php" class="nav-link">
                Home
            </a>

            <a href="about.php" class="nav-link active">
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
            type="button"
            aria-label="Open navigation menu"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>

</header>


<!-- =========================
     HERO
========================= -->

<main>

<section class="about-hero">

    <div class="hero-container">

        <div class="hero-content">

            <span class="section-label">
                ABOUT FAST FOTO
            </span>

            <h1>
                Your Memories.
                <span>Our Craft.</span>
            </h1>

            <p>
                Fast Foto Colour Lab is designed to bring the
                traditional photo printing experience into a
                convenient online environment.
            </p>

            <p>
                Our Online Photo Studio Printing Management System
                allows customers to upload photographs, manage
                printing requirements and place orders through
                a simple digital workflow.
            </p>

            <div class="hero-actions">

                <?php if ($is_logged_in): ?>

                    <a href="upload.php" class="primary-btn">
                        Start Printing
                        <span>→</span>
                    </a>

                <?php else: ?>

                    <a href="register.php" class="primary-btn">
                        Get Started
                        <span>→</span>
                    </a>

                <?php endif; ?>

                <a href="services.php" class="secondary-btn">
                    View Services
                    <span>↗</span>
                </a>

            </div>

        </div>


        <!-- 3D CAMERA / PHOTO -->
        <div class="about-visual">

            <div class="visual-glow"></div>

            <div class="camera-platform">

                <div class="camera-body">

                    <div class="camera-top"></div>

                    <div class="camera-lens">

                        <div class="lens-ring">
                            <div class="lens-inner"></div>
                        </div>

                    </div>

                    <div class="camera-flash"></div>

                    <div class="camera-display">
                        FF
                    </div>

                </div>


                <div class="camera-bottom"></div>

            </div>


            <div class="floating-photo photo-one">

                <div class="photo-art art-one">
                    <span>MEMORY</span>
                </div>

            </div>


            <div class="floating-photo photo-two">

                <div class="photo-art art-two">
                    <span>PRINT</span>
                </div>

            </div>


            <div class="floating-label">

                <div class="label-icon">
                    ✦
                </div>

                <div>
                    <strong>FAST FOTO</strong>
                    <small>Memories in print</small>
                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     OUR STORY
========================= -->

<section class="story-section">

    <div class="section-container">

        <div class="story-grid">

            <div class="story-visual">

                <div class="story-card">

                    <div class="story-card-top">

                        <span>
                            FAST FOTO
                        </span>

                        <span>
                            01
                        </span>

                    </div>


                    <div class="story-image">

                        <div class="story-sun"></div>

                        <div class="story-mountain mountain-a"></div>
                        <div class="story-mountain mountain-b"></div>

                        <div class="story-ground"></div>

                    </div>


                    <div class="story-card-bottom">

                        <span>
                            YOUR MOMENTS
                        </span>

                        <span>
                            PRESERVED
                        </span>

                    </div>

                </div>

            </div>


            <div class="story-content">

                <span class="section-label">
                    OUR STORY
                </span>

                <h2>
                    From Digital
                    <span>To Physical.</span>
                </h2>

                <p>
                    Photographs have always been one of the simplest
                    ways to preserve important moments. However,
                    many photographs today remain only on phones,
                    computers and social media.
                </p>

                <p>
                    Fast Foto Colour Lab is built around the idea of
                    making the printing process easier through
                    technology.
                </p>

                <p>
                    Instead of visiting a studio for every printing
                    requirement, customers can use the online system
                    to upload their photographs and manage their
                    printing orders.
                </p>


                <div class="story-points">

                    <div class="story-point">

                        <div class="point-number">
                            01
                        </div>

                        <div>
                            <strong>Upload</strong>
                            <span>
                                Select your favourite photographs.
                            </span>
                        </div>

                    </div>


                    <div class="story-point">

                        <div class="point-number">
                            02
                        </div>

                        <div>
                            <strong>Customize</strong>
                            <span>
                                Choose your printing requirements.
                            </span>
                        </div>

                    </div>


                    <div class="story-point">

                        <div class="point-number">
                            03
                        </div>

                        <div>
                            <strong>Order</strong>
                            <span>
                                Submit your printing order online.
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     WHY THE SYSTEM
========================= -->

<section class="values-section">

    <div class="section-container">

        <div class="section-heading">

            <span class="section-label">
                WHY THIS SYSTEM
            </span>

            <h2>
                Designed Around
                <span>The Customer.</span>
            </h2>

            <p>
                The system combines online convenience with
                the familiar concept of professional photo printing.
            </p>

        </div>


        <div class="values-grid">

            <article class="value-card">

                <div class="value-number">
                    01
                </div>

                <div class="value-icon">
                    ◇
                </div>

                <h3>
                    Convenience
                </h3>

                <p>
                    Customers can begin their photo printing
                    process online instead of handling every
                    step manually at the studio.
                </p>

            </article>


            <article class="value-card">

                <div class="value-number">
                    02
                </div>

                <div class="value-icon">
                    ◎
                </div>

                <h3>
                    Organization
                </h3>

                <p>
                    Uploaded photographs and customer orders
                    can be managed through the system.
                </p>

            </article>


            <article class="value-card">

                <div class="value-number">
                    03
                </div>

                <div class="value-icon">
                    ✦
                </div>

                <h3>
                    Simplicity
                </h3>

                <p>
                    The interface is designed to keep the
                    photo ordering process straightforward.
                </p>

            </article>


            <article class="value-card">

                <div class="value-number">
                    04
                </div>

                <div class="value-icon">
                    ⟡
                </div>

                <h3>
                    Management
                </h3>

                <p>
                    The administrative side provides a structured
                    way to manage customer printing orders.
                </p>

            </article>

        </div>

    </div>

</section>


<!-- =========================
     PROJECT FEATURES
========================= -->

<section class="features-section">

    <div class="section-container">

        <div class="features-panel">

            <div class="features-content">

                <span class="section-label">
                    THE PLATFORM
                </span>

                <h2>
                    One Digital Space
                    <span>For Photo Printing.</span>
                </h2>

                <p>
                    The platform connects the customer-facing
                    photo ordering experience with the management
                    side of the photo studio.
                </p>


                <div class="feature-list">

                    <div class="feature-item">

                        <div class="check">
                            ✓
                        </div>

                        <div>
                            <strong>
                                Customer Accounts
                            </strong>

                            <span>
                                Customers can register and access
                                their personal account.
                            </span>
                        </div>

                    </div>


                    <div class="feature-item">

                        <div class="check">
                            ✓
                        </div>

                        <div>
                            <strong>
                                Photo Upload
                            </strong>

                            <span>
                                Digital photographs can be uploaded
                                through the online system.
                            </span>
                        </div>

                    </div>


                    <div class="feature-item">

                        <div class="check">
                            ✓
                        </div>

                        <div>
                            <strong>
                                Order Management
                            </strong>

                            <span>
                                Printing orders can be created and
                                managed through the platform.
                            </span>
                        </div>

                    </div>


                    <div class="feature-item">

                        <div class="check">
                            ✓
                        </div>

                        <div>
                            <strong>
                                Administrative Control
                            </strong>

                            <span>
                                Administrators can manage the
                                operational side of the system.
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            <div class="features-visual">

                <div class="orbit orbit-one"></div>
                <div class="orbit orbit-two"></div>
                <div class="orbit orbit-three"></div>

                <div class="core">

                    <div class="core-inner">
                        FF
                    </div>

                </div>


                <div class="orbit-dot dot-one"></div>
                <div class="orbit-dot dot-two"></div>
                <div class="orbit-dot dot-three"></div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     CTA
========================= -->

<section class="cta-section">

    <div class="cta-card">

        <div class="cta-content">

            <span class="section-label">
                FAST FOTO COLOUR LAB
            </span>

            <h2>
                Ready To Turn Your
                <span>Photos Into Prints?</span>
            </h2>

            <p>
                Start your online photo printing journey today.
            </p>


            <?php if ($is_logged_in): ?>

                <a href="upload.php" class="primary-btn">
                    Upload Your Photos
                    <span>→</span>
                </a>

            <?php else: ?>

                <a href="register.php" class="primary-btn">
                    Create Your Account
                    <span>→</span>
                </a>

            <?php endif; ?>

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
                Bringing digital memories closer to
                the real world through online photo printing.
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

            <h4>System</h4>

            <span>Online Printing</span>
            <span>Photo Upload</span>
            <span>Order Management</span>

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


<script src="js/about.js"></script>

</body>
</html>