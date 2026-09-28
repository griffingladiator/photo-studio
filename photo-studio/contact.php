<?php

/*
|--------------------------------------------------------------------------
| Fast Foto Colour Lab
| Contact Page
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/config/security.php";
require_once __DIR__ . "/config/database.php";

apply_security_headers();

$is_logged_in = isset($_SESSION["user_id"]);

$message_sent = false;
$error_message = "";


/*
|--------------------------------------------------------------------------
| CONTACT FORM SUBMISSION
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
    |--------------------------------------------------------------------------
    | CSRF CHECK
    |--------------------------------------------------------------------------
    */

    if (
        !verify_csrf_token(
            $_POST["csrf_token"] ?? ""
        )
    ) {

        $error_message =
            "Your session has expired. Please refresh the page and try again.";

    } else {

        $name = trim(
            $_POST["name"] ?? ""
        );

        $email = trim(
            $_POST["email"] ?? ""
        );

        $subject = trim(
            $_POST["subject"] ?? ""
        );

        $message = trim(
            $_POST["message"] ?? ""
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            $name === "" ||
            $email === "" ||
            $subject === "" ||
            $message === ""
        ) {

            $error_message =
                "Please fill in all the required fields.";

        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $error_message =
                "Please enter a valid email address.";

        } elseif (mb_strlen($message) > 1000) {

            $error_message =
                "Your message must not exceed 1000 characters.";

        } elseif (mb_strlen($name) > 100) {

            $error_message =
                "Name is too long.";

        } elseif (mb_strlen($subject) > 200) {

            $error_message =
                "Subject is too long.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | SAVE MESSAGE
            |--------------------------------------------------------------------------
            */

            $sql = "
                INSERT INTO contact_messages
                (
                    name,
                    email,
                    subject,
                    message,
                    status
                )
                VALUES
                (?, ?, ?, ?, 'new')
            ";

            $stmt = $conn->prepare($sql);


            if ($stmt) {

                $stmt->bind_param(
                    "ssss",
                    $name,
                    $email,
                    $subject,
                    $message
                );


                if ($stmt->execute()) {

                    $message_sent = true;

                    /*
                    |--------------------------------------------------------------------------
                    | Clear submitted values after successful submission
                    |--------------------------------------------------------------------------
                    */

                    $_POST = [];

                    /*
                    |--------------------------------------------------------------------------
                    | Generate a fresh CSRF token
                    |--------------------------------------------------------------------------
                    */

                    $_SESSION["csrf_token"] =
                        bin2hex(random_bytes(32));

                } else {

                    $error_message =
                        "Unable to send your message right now. Please try again.";
                }


                $stmt->close();

            } else {

                $error_message =
                    "Unable to connect to the message service.";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Contact | Fast Foto Colour Lab</title>

    <meta
        name="description"
        content="Contact Fast Foto Colour Lab for photo printing and online photo studio services."
    >

    <link
        rel="stylesheet"
        href="css/contact.css"
    >

</head>

<body>

    <!-- Background -->
    <div class="background-grid"></div>

    <div class="ambient ambient-one"></div>
    <div class="ambient ambient-two"></div>
    <div class="ambient ambient-three"></div>


    <!-- HEADER -->

    <header
        class="site-header"
        id="siteHeader"
    >

        <nav class="navbar">


            <!-- BRAND -->

            <a
                href="index.php"
                class="brand"
            >

                <span class="brand-icon">
                    <span class="brand-lens"></span>
                </span>

                <span class="brand-text">
                    <strong>Fast Foto</strong>
                    <small>Colour Lab</small>
                </span>

            </a>


            <!-- NAVIGATION -->

            <div
                class="nav-links"
                id="navLinks"
            >

                <a href="index.php">
                    Home
                </a>

                <a href="about.php">
                    About
                </a>

                <a href="services.php">
                    Services
                </a>

                <a
                    href="contact.php"
                    class="active"
                >
                    Contact
                </a>


                <?php if ($is_logged_in): ?>

                    <a href="dashboard.php">
                        Dashboard
                    </a>

                    <a
                        href="upload.php"
                        class="nav-button"
                    >
                        Start Printing
                    </a>

                <?php else: ?>

                    <a href="login.php">
                        Login
                    </a>

                    <a
                        href="register.php"
                        class="nav-button"
                    >
                        Get Started
                    </a>

                <?php endif; ?>

            </div>


            <!-- MOBILE MENU -->

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


    <!-- MAIN -->

    <main>


        <!-- HERO -->

        <section class="contact-hero">

            <div class="hero-content reveal">

                <div class="section-badge">

                    <span class="badge-dot"></span>

                    GET IN TOUCH

                </div>


                <h1>

                    Let's Talk About

                    <span>
                        Your Photos.
                    </span>

                </h1>


                <p>

                    Have a question about printing, uploading photographs,
                    orders or our services? Send us a message and we'll
                    help you get started.

                </p>

            </div>


            <!-- 3D CAMERA -->

            <div class="hero-visual reveal">

                <div class="camera-scene">

                    <div class="camera-glow"></div>

                    <div class="camera">

                        <div class="camera-top">

                            <div class="camera-viewfinder"></div>

                            <div class="camera-button"></div>

                        </div>


                        <div class="camera-body">

                            <div class="camera-brand">
                                FAST FOTO
                            </div>


                            <div class="camera-lens">

                                <div class="lens-ring ring-one"></div>

                                <div class="lens-ring ring-two"></div>

                                <div class="lens-glass">

                                    <div class="lens-reflection"></div>

                                </div>

                            </div>


                            <div class="camera-small-light"></div>

                        </div>

                    </div>


                    <!-- Floating photos -->

                    <div class="floating-photo photo-one">

                        <div class="photo-image landscape"></div>

                    </div>


                    <div class="floating-photo photo-two">

                        <div class="photo-image portrait"></div>

                    </div>


                    <div class="floating-photo photo-three">

                        <div class="photo-image sunset"></div>

                    </div>

                </div>

            </div>

        </section>



        <!-- CONTACT AREA -->

        <section class="contact-section">

            <div class="contact-grid">


                <!-- CONTACT INFORMATION -->

                <div class="contact-info reveal">

                    <div class="section-badge">

                        <span class="badge-dot"></span>

                        CONTACT DETAILS

                    </div>


                    <h2>

                        We're Here To

                        <span>
                            Help.
                        </span>

                    </h2>


                    <p class="intro-text">

                        Whether you need help with your photo upload,
                        printing options, order status or anything else,
                        you can reach Fast Foto Colour Lab using the
                        information below.

                    </p>


                    <div class="info-card">

                        <div class="info-icon">
                            <span>⌖</span>
                        </div>

                        <div class="info-content">

                            <h3>
                                Visit Us
                            </h3>

                            <p>
                                Fast Foto Colour Lab<br>
                                Irattai Pillaiyar Kovil Street<br>
                                Thiruvannamalai<br>
                                Tamil Nadu 606601<br>
                                India
                            </p>

                        </div>

                    </div>


                    <div class="info-card">

                        <div class="info-icon">
                            <span>✉</span>
                        </div>

                        <div class="info-content">

                            <h3>
                                Email Us
                            </h3>

                            <p>
                                ffcltvm@gmail.com
                            </p>

                        </div>

                    </div>


                    <div class="info-card">

                        <div class="info-icon">
                            <span>☎</span>
                        </div>

                        <div class="info-content">

                            <h3>
                                Call Us
                            </h3>

                            <p>
                                +91 79042 40639
                            </p>

                        </div>

                    </div>


                    <div class="info-card">

                        <div class="info-icon">
                            <span>◷</span>
                        </div>

                        <div class="info-content">

                            <h3>
                                Working Hours
                            </h3>

                            <p>
                                Monday – Saturday<br>
                                9:00 AM – 9:00 PM
                            </p>

                        </div>

                    </div>


                    <div class="contact-orbit">

                        <div class="orbit-ring orbit-ring-one"></div>

                        <div class="orbit-ring orbit-ring-two"></div>

                        <div class="orbit-dot"></div>

                    </div>

                </div>



                <!-- CONTACT FORM -->

                <div class="contact-form-wrapper reveal">

                    <div class="form-header">

                        <div class="section-badge">

                            <span class="badge-dot"></span>

                            SEND A MESSAGE

                        </div>


                        <h2>

                            Tell Us

                            <span>
                                What's On Your Mind.
                            </span>

                        </h2>


                        <p>
                            Fill out the form below and send us your enquiry.
                        </p>

                    </div>


                    <?php if ($message_sent): ?>

                        <div class="form-message success-message">

                            <div class="message-icon">
                                ✓
                            </div>

                            <div>

                                <strong>
                                    Message received!
                                </strong>

                                <p>
                                    Thank you for contacting Fast Foto
                                    Colour Lab. Your message has been
                                    submitted successfully. Our team will
                                    review your message shortly.
                                </p>

                            </div>

                        </div>

                    <?php endif; ?>


                    <?php if ($error_message !== ""): ?>

                        <div class="form-message error-message">

                            <div class="message-icon">
                                !
                            </div>

                            <div>

                                <strong>
                                    Please check the form
                                </strong>

                                <p>
                                    <?php
                                    echo e($error_message);
                                    ?>
                                </p>

                            </div>

                        </div>

                    <?php endif; ?>


                    <form
                        class="contact-form"
                        id="contactForm"
                        method="POST"
                        action="contact.php"
                        novalidate
                    >

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?php echo e(csrf_token()); ?>"
                        >


                        <div class="form-row">


                            <div class="form-group">

                                <label for="name">
                                    Your Name
                                </label>

                                <div class="input-wrapper">

                                    <span class="input-icon">
                                        ◉
                                    </span>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        placeholder="Enter your name"
                                        value="<?php echo e($_POST["name"] ?? ""); ?>"
                                        autocomplete="name"
                                        maxlength="100"
                                        required
                                    >

                                </div>

                            </div>


                            <div class="form-group">

                                <label for="email">
                                    Email Address
                                </label>

                                <div class="input-wrapper">

                                    <span class="input-icon">
                                        @
                                    </span>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        placeholder="you@example.com"
                                        value="<?php echo e($_POST["email"] ?? ""); ?>"
                                        autocomplete="email"
                                        maxlength="150"
                                        required
                                    >

                                </div>

                            </div>

                        </div>


                        <div class="form-group">

                            <label for="subject">
                                Subject
                            </label>

                            <div class="input-wrapper">

                                <span class="input-icon">
                                    #
                                </span>

                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    placeholder="What can we help you with?"
                                    value="<?php echo e($_POST["subject"] ?? ""); ?>"
                                    maxlength="200"
                                    required
                                >

                            </div>

                        </div>


                        <div class="form-group">

                            <label for="message">
                                Message
                            </label>

                            <div class="input-wrapper textarea-wrapper">

                                <span class="input-icon textarea-icon">
                                    ✎
                                </span>

                                <textarea
                                    id="message"
                                    name="message"
                                    rows="6"
                                    maxlength="1000"
                                    placeholder="Write your message here..."
                                    required
                                ><?php echo e($_POST["message"] ?? ""); ?></textarea>

                            </div>


                            <div class="character-count">

                                <span id="characterCount">
                                    0
                                </span>

                                / 1000

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="submit-button"
                        >

                            <span class="button-text">
                                Send Message
                            </span>

                            <span class="button-arrow">
                                →
                            </span>

                        </button>

                    </form>

                </div>

            </div>

        </section>



        <!-- FAQ -->

        <section class="faq-section">

            <div class="section-heading reveal">

                <div class="section-badge">

                    <span class="badge-dot"></span>

                    QUICK ANSWERS

                </div>


                <h2>

                    Frequently Asked

                    <span>
                        Questions
                    </span>

                </h2>


                <p>
                    A few common questions about the online photo
                    printing system.
                </p>

            </div>


            <div class="faq-grid">


                <div class="faq-item reveal">

                    <button
                        class="faq-question"
                        type="button"
                    >

                        <span>
                            How do I upload my photos?
                        </span>

                        <span class="faq-plus">
                            +
                        </span>

                    </button>


                    <div class="faq-answer">

                        <p>
                            Create an account or log in, open the
                            printing section and upload the photographs
                            you want to use for your order.
                        </p>

                    </div>

                </div>


                <div class="faq-item reveal">

                    <button
                        class="faq-question"
                        type="button"
                    >

                        <span>
                            Can I track my order?
                        </span>

                        <span class="faq-plus">
                            +
                        </span>

                    </button>


                    <div class="faq-answer">

                        <p>
                            Yes. After placing an order, you can use
                            your customer dashboard to view your order
                            information and status.
                        </p>

                    </div>

                </div>


                <div class="faq-item reveal">

                    <button
                        class="faq-question"
                        type="button"
                    >

                        <span>
                            What types of photos can I print?
                        </span>

                        <span class="faq-plus">
                            +
                        </span>

                    </button>


                    <div class="faq-answer">

                        <p>
                            The system can be used for standard photo
                            prints, passport photographs, enlargements,
                            custom prints and other supported printing
                            requirements.
                        </p>

                    </div>

                </div>


                <div class="faq-item reveal">

                    <button
                        class="faq-question"
                        type="button"
                    >

                        <span>
                            Do I need an account to place an order?
                        </span>

                        <span class="faq-plus">
                            +
                        </span>

                    </button>


                    <div class="faq-answer">

                        <p>
                            An account is required for the customer
                            workflow so that uploaded photos, orders
                            and account information can be associated
                            with the correct customer.
                        </p>

                    </div>

                </div>

            </div>

        </section>



        <!-- CTA -->

        <section class="contact-cta">

            <div class="cta-box reveal">

                <div class="cta-content">

                    <div class="section-badge">

                        <span class="badge-dot"></span>

                        READY TO PRINT?

                    </div>


                    <h2>

                        Have Photos

                        <span>
                            Waiting?
                        </span>

                    </h2>


                    <p>
                        Stop keeping your memories only on your phone.
                        Turn them into beautiful physical prints.
                    </p>


                    <a
                        href="upload.php"
                        class="primary-button"
                    >

                        <span>
                            Start Printing
                        </span>

                        <span class="button-arrow">
                            →
                        </span>

                    </a>

                </div>


                <div class="cta-visual">

                    <div class="cta-circle circle-one"></div>

                    <div class="cta-circle circle-two"></div>

                    <div class="cta-camera">

                        <div class="cta-lens"></div>

                    </div>

                </div>

            </div>

        </section>

    </main>



    <!-- FOOTER -->

    <footer class="site-footer">

        <div class="footer-inner">


            <div class="footer-brand">

                <a
                    href="index.php"
                    class="brand"
                >

                    <span class="brand-icon">

                        <span class="brand-lens"></span>

                    </span>

                    <span class="brand-text">

                        <strong>
                            Fast Foto
                        </strong>

                        <small>
                            Colour Lab
                        </small>

                    </span>

                </a>


                <p>
                    An online photo studio and printing management
                    system designed to make photo printing simple.
                </p>

            </div>


            <div class="footer-links">


                <div>

                    <h4>
                        Explore
                    </h4>

                    <a href="index.php">
                        Home
                    </a>

                    <a href="about.php">
                        About
                    </a>

                    <a href="services.php">
                        Services
                    </a>

                    <a href="contact.php">
                        Contact
                    </a>

                </div>


                <div>

                    <h4>
                        Account
                    </h4>


                    <?php if ($is_logged_in): ?>

                        <a href="dashboard.php">
                            Dashboard
                        </a>

                        <a href="upload.php">
                            Start Printing
                        </a>

                    <?php else: ?>

                        <a href="login.php">
                            Login
                        </a>

                        <a href="register.php">
                            Register
                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <div class="footer-bottom">

            <span>

                © <?php echo date("Y"); ?>
                Fast Foto Colour Lab

            </span>


            <span>

                Online Photo Studio Printing Management System

            </span>

        </div>

    </footer>


    <script src="js/contact.js"></script>

</body>

</html>