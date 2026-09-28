<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errors = [];
$success = "";

$name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    /* -----------------------------
       VALIDATION
    ----------------------------- */

    if ($name === "") {
        $errors[] = "Please enter your full name.";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must contain at least 2 characters.";
    }

    if ($email === "") {
        $errors[] = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($password === "") {
        $errors[] = "Please create a password.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must contain at least 6 characters.";
    }

    if ($confirm_password === "") {
        $errors[] = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    /* -----------------------------
       DATABASE CONNECTION
    ----------------------------- */

    if (empty($errors)) {

        $connection_file = null;

        $possible_connections = [
            __DIR__ . "/config/database.php",
            __DIR__ . "/config/db.php",
            __DIR__ . "/database.php",
            __DIR__ . "/db.php"
        ];

        foreach ($possible_connections as $file) {
            if (file_exists($file)) {
                $connection_file = $file;
                break;
            }
        }

        if ($connection_file === null) {
            $errors[] = "Database connection file was not found.";
        } else {

            require_once $connection_file;

            /*
             * Supports common mysqli connection variables:
             * $conn
             * $connection
             * $mysqli
             */

            $db = null;

            if (isset($conn) && $conn instanceof mysqli) {
                $db = $conn;
            } elseif (isset($connection) && $connection instanceof mysqli) {
                $db = $connection;
            } elseif (isset($mysqli) && $mysqli instanceof mysqli) {
                $db = $mysqli;
            }

            /* -----------------------------
               MYSQLI
            ----------------------------- */

            if ($db instanceof mysqli) {

                $check = $db->prepare(
                    "SELECT id FROM users WHERE email = ? LIMIT 1"
                );

                if (!$check) {
                    $errors[] = "Database error: " . $db->error;
                } else {

                    $check->bind_param("s", $email);
                    $check->execute();

                    $result = $check->get_result();

                    if ($result && $result->num_rows > 0) {

                        $errors[] = "An account with this email already exists.";

                    } else {

                        $password_hash = password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        );

                        $insert = $db->prepare(
                            "INSERT INTO users (name, email, password)
                             VALUES (?, ?, ?)"
                        );

                        if (!$insert) {

                            $errors[] = "Unable to create account: " . $db->error;

                        } else {

                            $insert->bind_param(
                                "sss",
                                $name,
                                $email,
                                $password_hash
                            );

                            if ($insert->execute()) {

                                $success =
                                    "Account created successfully! Redirecting to login...";

                                $name = "";
                                $email = "";

                            } else {

                                $errors[] =
                                    "Unable to create your account. Please try again.";
                            }

                            $insert->close();
                        }
                    }

                    $check->close();
                }

            /* -----------------------------
               PDO
            ----------------------------- */

            } elseif (isset($pdo) && $pdo instanceof PDO) {

                try {

                    $check = $pdo->prepare(
                        "SELECT id FROM users WHERE email = ? LIMIT 1"
                    );

                    $check->execute([$email]);

                    if ($check->fetch()) {

                        $errors[] =
                            "An account with this email already exists.";

                    } else {

                        $password_hash = password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        );

                        $insert = $pdo->prepare(
                            "INSERT INTO users (name, email, password)
                             VALUES (?, ?, ?)"
                        );

                        $insert->execute([
                            $name,
                            $email,
                            $password_hash
                        ]);

                        $success =
                            "Account created successfully! Redirecting to login...";

                        $name = "";
                        $email = "";
                    }

                } catch (PDOException $e) {

                    $errors[] =
                        "Database error. Please try again.";
                }

            } else {

                $errors[] =
                    "Database connection is not available.";
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

    <title>Create Account | Fast Foto Colour Lab</title>

    <meta
        name="description"
        content="Create your Fast Foto Colour Lab account and start ordering professional photo printing services online."
    >

    <link
        rel="stylesheet"
        href="css/register.css"
    >

</head>

<body>

<div class="page-background">

    <div class="grid-background"></div>

    <div class="glow glow-one"></div>
    <div class="glow glow-two"></div>
    <div class="glow glow-three"></div>

</div>


<!-- NAVBAR -->

<header class="navbar">

    <a href="index.php" class="brand">

        <div class="brand-icon">
            FF
        </div>

        <div class="brand-text">
            <strong>Fast Foto</strong>
            <span>Colour Lab</span>
        </div>

    </a>

    <nav class="nav-links">

        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="services.php">Services</a>
        <a href="contact.php">Contact</a>

        <a href="login.php" class="nav-login">
            Login
        </a>

    </nav>

    <button
        type="button"
        class="menu-toggle"
        id="menuToggle"
        aria-label="Open menu"
    >
        <span></span>
        <span></span>
        <span></span>
    </button>

</header>


<!-- MAIN -->

<main class="register-page">

    <!-- LEFT SIDE -->

    <section class="register-showcase">

        <div class="showcase-content">

            <div class="mini-label">
                <span class="pulse-dot"></span>
                FAST • SIMPLE • PROFESSIONAL
            </div>

            <h1>
                Turn your
                <span>memories</span>
                into prints.
            </h1>

            <p>
                Create your Fast Foto Colour Lab account and
                manage your photo printing orders from one
                convenient place.
            </p>

            <div class="benefits">

                <div class="benefit-card">

                    <div class="benefit-icon">
                        📸
                    </div>

                    <div>
                        <strong>Upload Photos</strong>
                        <span>
                            Upload your favourite photos
                            securely.
                        </span>
                    </div>

                </div>


                <div class="benefit-card">

                    <div class="benefit-icon">
                        🖨️
                    </div>

                    <div>
                        <strong>Choose Printing</strong>
                        <span>
                            Select print size and quantity
                            easily.
                        </span>
                    </div>

                </div>


                <div class="benefit-card">

                    <div class="benefit-icon">
                        📦
                    </div>

                    <div>
                        <strong>Track Orders</strong>
                        <span>
                            Keep track of your printing
                            orders.
                        </span>
                    </div>

                </div>

            </div>

        </div>


        <!-- 3D CAMERA -->

        <div class="camera-scene">

            <div class="camera-shadow"></div>

            <div class="camera">

                <div class="camera-top">
                    FAST FOTO
                </div>

                <div class="camera-body">

                    <div class="camera-lens">

                        <div class="lens-ring">

                            <div class="lens-inner">
                                <div class="lens-reflection"></div>
                            </div>

                        </div>

                    </div>

                    <div class="camera-flash"></div>

                    <div class="camera-button"></div>

                </div>

            </div>


            <div class="floating-photo photo-one">

                <div class="photo-image">
                    🌄
                </div>

                <span>MEMORIES</span>

            </div>


            <div class="floating-photo photo-two">

                <div class="photo-image">
                    📷
                </div>

                <span>CAPTURE</span>

            </div>


            <div class="floating-photo photo-three">

                <div class="photo-image">
                    🏞️
                </div>

                <span>PRINT</span>

            </div>

        </div>

    </section>


    <!-- REGISTER CARD -->

    <section class="register-section">

        <div class="register-card">

            <div class="card-glow"></div>

            <div class="register-header">

                <div class="register-icon">
                    ✦
                </div>

                <div>

                    <p class="eyebrow">
                        CREATE ACCOUNT
                    </p>

                    <h2>
                        Join Fast Foto
                    </h2>

                </div>

            </div>

            <p class="register-intro">
                Create your account to start ordering
                professional photo prints online.
            </p>


            <?php if (!empty($errors)): ?>

                <div class="message error-message">

                    <strong>
                        Please check the following:
                    </strong>

                    <ul>

                        <?php foreach ($errors as $error): ?>

                            <li>
                                <?= htmlspecialchars($error) ?>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            <?php endif; ?>


            <?php if ($success !== ""): ?>

                <div class="message success-message">

                    <span class="success-check">✓</span>

                    <?= htmlspecialchars($success) ?>

                </div>

                <script>
                    setTimeout(function () {
                        window.location.href = "login.php";
                    }, 1800);
                </script>

            <?php endif; ?>


            <form
                method="POST"
                action=""
                id="registerForm"
                novalidate
            >

                <!-- NAME -->

                <div class="form-group">

                    <label for="name">
                        Full Name
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            👤
                        </span>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Enter your full name"
                            value="<?= htmlspecialchars($name) ?>"
                            autocomplete="name"
                            maxlength="100"
                            required
                        >

                    </div>

                    <small
                        class="field-error"
                        id="nameError"
                    ></small>

                </div>


                <!-- EMAIL -->

                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            ✉
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="you@example.com"
                            value="<?= htmlspecialchars($email) ?>"
                            autocomplete="email"
                            maxlength="150"
                            required
                        >

                    </div>

                    <small
                        class="field-error"
                        id="emailError"
                    ></small>

                </div>


                <!-- PASSWORD -->

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            🔒
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Create a password"
                            autocomplete="new-password"
                            minlength="6"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-target="password"
                            aria-label="Show password"
                        >
                            👁
                        </button>

                    </div>

                    <div class="password-strength">

                        <div class="strength-bars">

                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>

                        </div>

                        <span id="strengthText">
                            Use 6 or more characters
                        </span>

                    </div>

                    <small
                        class="field-error"
                        id="passwordError"
                    ></small>

                </div>


                <!-- CONFIRM PASSWORD -->

                <div class="form-group">

                    <label for="confirm_password">
                        Confirm Password
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            🔐
                        </span>

                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="Repeat your password"
                            autocomplete="new-password"
                            minlength="6"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-target="confirm_password"
                            aria-label="Show password"
                        >
                            👁
                        </button>

                    </div>

                    <small
                        class="field-error"
                        id="confirmError"
                    ></small>

                </div>


                <!-- TERMS -->

                <label class="terms">

                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        required
                    >

                    <span class="custom-checkbox"></span>

                    <span>
                        I agree to the
                        <a href="#" id="termsLink">
                            terms and conditions
                        </a>.
                    </span>

                </label>


                <!-- SUBMIT -->

                <button
                    type="submit"
                    class="register-button"
                    id="registerButton"
                >

                    <span class="button-text">
                        Create My Account
                    </span>

                    <span class="button-arrow">
                        →
                    </span>

                    <span class="button-loader"></span>

                </button>

            </form>


            <div class="login-link">

                Already have an account?

                <a href="login.php">
                    Sign in
                </a>

            </div>

        </div>

    </section>

</main>


<!-- FOOTER -->

<footer class="footer">

    <div class="footer-inner">

        <span>
            © <?= date("Y") ?> Fast Foto Colour Lab
        </span>

        <span class="footer-dot">•</span>

        <span>
            Online Photo Studio Printing Management System
        </span>

    </div>

</footer>


<script src="js/register.js"></script>

</body>
</html>