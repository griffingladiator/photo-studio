<?php
session_start();

require_once __DIR__ . '/config/database.php';

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password, phone, address, role
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {

                $user = $result->fetch_assoc();

                /*
                 * Supports passwords created with password_hash().
                 */
                if (password_verify($password, $user['password'])) {

                    session_regenerate_id(true);

                    $_SESSION['user_id'] = (int)$user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_phone'] = $user['phone'] ?? '';
                    $_SESSION['user_address'] = $user['address'] ?? '';
                    $_SESSION['role'] = strtolower(trim($user['role']));

                    /*
                     * Redirect based on account role.
                     */
                    if ($_SESSION['role'] === 'admin') {
                        header('Location: admin/dashboard.php');
                        exit;
                    }

                    header('Location: dashboard.php');
                    exit;

                } else {
                    $error = 'Incorrect email or password.';
                }

            } else {
                $error = 'Incorrect email or password.';
            }

            $stmt->close();

        } else {
            $error = 'Unable to process login. Please try again.';
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

    <title>Login | Fast Foto Colour Lab</title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #7c3aed;
            --secondary: #06b6d4;
            --pink: #ec4899;
            --dark: #070716;
            --dark2: #101026;
            --white: #ffffff;
            --muted: #a7a7c7;
            --glass: rgba(255, 255, 255, 0.08);
            --border: rgba(255, 255, 255, 0.14);
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            font-family:
                "Segoe UI",
                Tahoma,
                Geneva,
                Verdana,
                sans-serif;

            background:
                radial-gradient(
                    circle at 15% 20%,
                    rgba(124, 58, 237, 0.30),
                    transparent 32%
                ),
                radial-gradient(
                    circle at 85% 75%,
                    rgba(6, 182, 212, 0.24),
                    transparent 30%
                ),
                linear-gradient(
                    135deg,
                    #050510 0%,
                    #0a0a1c 45%,
                    #080817 100%
                );

            color: var(--white);
            overflow-x: hidden;
        }

        /* =========================
           BACKGROUND 3D EFFECT
        ========================= */

        .background {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(2px);
            opacity: 0.55;
            animation: floatOrb 9s ease-in-out infinite;
        }

        .orb.one {
            width: 260px;
            height: 260px;
            top: -90px;
            left: -80px;
            background:
                radial-gradient(
                    circle at 30% 30%,
                    rgba(236, 72, 153, 0.75),
                    rgba(124, 58, 237, 0.08) 70%
                );
        }

        .orb.two {
            width: 320px;
            height: 320px;
            right: -120px;
            top: 20%;
            background:
                radial-gradient(
                    circle at 40% 40%,
                    rgba(6, 182, 212, 0.70),
                    rgba(6, 182, 212, 0.05) 70%
                );
            animation-delay: -3s;
        }

        .orb.three {
            width: 210px;
            height: 210px;
            left: 25%;
            bottom: -100px;
            background:
                radial-gradient(
                    circle at 40% 40%,
                    rgba(124, 58, 237, 0.60),
                    transparent 70%
                );
            animation-delay: -6s;
        }

        @keyframes floatOrb {

            0%,
            100% {
                transform: translate3d(0, 0, 0) scale(1);
            }

            50% {
                transform: translate3d(25px, -25px, 0) scale(1.08);
            }
        }

        /* =========================
           NAVIGATION
        ========================= */

        .topbar {
            position: relative;
            z-index: 10;

            width: 100%;
            padding: 22px 6%;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;

            color: white;
            text-decoration: none;
        }

        .brand-icon {
            width: 46px;
            height: 46px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;

            background:
                linear-gradient(
                    135deg,
                    rgba(124, 58, 237, 0.95),
                    rgba(6, 182, 212, 0.85)
                );

            box-shadow:
                0 12px 35px rgba(124, 58, 237, 0.35),
                inset 0 1px 1px rgba(255, 255, 255, 0.35);

            transform: rotate(-5deg);
        }

        .brand-icon i {
            font-size: 20px;
            transform: rotate(5deg);
        }

        .brand-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 9px;

            padding: 11px 18px;

            color: #ffffff;
            text-decoration: none;

            border: 1px solid var(--border);
            border-radius: 14px;

            background: rgba(255, 255, 255, 0.055);

            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);

            transition: 0.3s ease;
        }

        .back-home:hover {
            transform: translateY(-3px);
            border-color: rgba(6, 182, 212, 0.55);

            background: rgba(255, 255, 255, 0.10);

            box-shadow:
                0 12px 30px rgba(6, 182, 212, 0.15);
        }

        /* =========================
           MAIN
        ========================= */

        .page {
            position: relative;
            z-index: 2;

            min-height: calc(100vh - 90px);

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 30px 20px 70px;
        }

        .login-wrapper {
            width: min(100%, 1040px);

            display: grid;
            grid-template-columns: 1fr 1fr;

            gap: 0;

            perspective: 1200px;
        }

        /* =========================
           LEFT PANEL
        ========================= */

        .intro-panel {
            position: relative;

            min-height: 560px;

            padding: 55px 48px;

            display: flex;
            flex-direction: column;
            justify-content: center;

            border: 1px solid var(--border);
            border-right: none;

            border-radius: 30px 0 0 30px;

            background:
                linear-gradient(
                    145deg,
                    rgba(124, 58, 237, 0.18),
                    rgba(6, 182, 212, 0.06)
                );

            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);

            box-shadow:
                inset 0 1px 1px rgba(255, 255, 255, 0.10),
                -20px 30px 80px rgba(0, 0, 0, 0.25);

            overflow: hidden;
        }

        .intro-panel::before {
            content: "";
            position: absolute;

            width: 250px;
            height: 250px;

            top: -130px;
            right: -100px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(236, 72, 153, 0.22),
                    transparent 68%
                );
        }

        .intro-panel::after {
            content: "";
            position: absolute;

            width: 200px;
            height: 200px;

            bottom: -100px;
            left: -80px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(6, 182, 212, 0.18),
                    transparent 68%
                );
        }

        .camera-3d {
            width: 90px;
            height: 90px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 28px;

            border-radius: 26px;

            background:
                linear-gradient(
                    145deg,
                    rgba(124, 58, 237, 0.85),
                    rgba(236, 72, 153, 0.65)
                );

            box-shadow:
                0 25px 50px rgba(124, 58, 237, 0.32),
                inset 0 2px 2px rgba(255, 255, 255, 0.28),
                inset 0 -8px 15px rgba(0, 0, 0, 0.15);

            transform:
                perspective(500px)
                rotateX(8deg)
                rotateY(-10deg);

            animation: cameraFloat 4s ease-in-out infinite;
        }

        .camera-3d i {
            font-size: 39px;
        }

        @keyframes cameraFloat {

            0%,
            100% {
                transform:
                    perspective(500px)
                    rotateX(8deg)
                    rotateY(-10deg)
                    translateY(0);
            }

            50% {
                transform:
                    perspective(500px)
                    rotateX(4deg)
                    rotateY(-5deg)
                    translateY(-9px);
            }
        }

        .intro-panel h1 {
            font-size: clamp(34px, 4vw, 53px);
            line-height: 1.05;
            margin-bottom: 20px;

            letter-spacing: -1.5px;
        }

        .gradient-text {
            background:
                linear-gradient(
                    90deg,
                    #ffffff,
                    #c4b5fd,
                    #67e8f9
                );

            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .intro-panel p {
            color: var(--muted);
            line-height: 1.75;
            font-size: 15px;

            max-width: 430px;
        }

        .feature-list {
            list-style: none;

            margin-top: 32px;

            display: grid;
            gap: 14px;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 12px;

            color: #ddddef;
            font-size: 14px;
        }

        .feature-list i {
            width: 29px;
            height: 29px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background:
                rgba(124, 58, 237, 0.16);

            color: #c4b5fd;

            border: 1px solid rgba(124, 58, 237, 0.22);
        }

        /* =========================
           LOGIN CARD
        ========================= */

        .login-card {
            position: relative;

            min-height: 560px;

            padding: 48px 44px;

            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 0 30px 30px 0;

            background:
                linear-gradient(
                    145deg,
                    rgba(255, 255, 255, 0.105),
                    rgba(255, 255, 255, 0.045)
                );

            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);

            box-shadow:
                20px 30px 80px rgba(0, 0, 0, 0.32),
                inset 0 1px 1px rgba(255, 255, 255, 0.14);

            transform-style: preserve-3d;
        }

        .login-card::before {
            content: "";

            position: absolute;
            inset: 1px;

            border-radius: 0 29px 29px 0;

            background:
                linear-gradient(
                    120deg,
                    rgba(255, 255, 255, 0.06),
                    transparent 35%
                );

            pointer-events: none;
        }

        .login-content {
            position: relative;
            z-index: 2;
        }

        .login-header {
            margin-bottom: 32px;
        }

        .login-header .small-title {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            margin-bottom: 12px;

            color: #a5f3fc;
            font-size: 12px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .small-title span {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: #22d3ee;

            box-shadow:
                0 0 12px rgba(34, 211, 238, 0.8);
        }

        .login-header h2 {
            font-size: 31px;
            margin-bottom: 9px;
        }

        .login-header p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        /* =========================
           ERROR
        ========================= */

        .error-box {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 13px 15px;
            margin-bottom: 22px;

            border-radius: 13px;

            color: #fecaca;

            background:
                rgba(239, 68, 68, 0.10);

            border:
                1px solid rgba(239, 68, 68, 0.24);

            font-size: 13px;
        }

        /* =========================
           FORM
        ========================= */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            margin-bottom: 9px;

            color: #e9e9f8;

            font-size: 13px;
            font-weight: 600;
        }

        .input-box {
            position: relative;
        }

        .input-box > i {
            position: absolute;

            left: 16px;
            top: 50%;

            transform: translateY(-50%);

            color: #8585a8;

            font-size: 15px;

            pointer-events: none;
        }

        .input-box input {
            width: 100%;

            height: 52px;

            padding:
                0 48px
                0 46px;

            border-radius: 15px;

            border: 1px solid rgba(255, 255, 255, 0.12);

            outline: none;

            background:
                rgba(4, 4, 18, 0.46);

            color: #ffffff;

            font-size: 14px;

            transition:
                border-color 0.3s ease,
                box-shadow 0.3s ease,
                background 0.3s ease;
        }

        .input-box input::placeholder {
            color: #666681;
        }

        .input-box input:focus {
            border-color:
                rgba(124, 58, 237, 0.75);

            background:
                rgba(4, 4, 18, 0.62);

            box-shadow:
                0 0 0 4px rgba(124, 58, 237, 0.10),
                0 12px 30px rgba(124, 58, 237, 0.08);
        }

        .password-toggle {
            position: absolute;

            right: 15px;
            top: 50%;

            transform: translateY(-50%);

            border: none;
            background: transparent;

            color: #777795;

            cursor: pointer;

            font-size: 15px;

            transition: 0.25s ease;
        }

        .password-toggle:hover {
            color: #ffffff;
        }

        /* =========================
           LOGIN BUTTON
        ========================= */

        .login-button {
            position: relative;

            width: 100%;
            height: 55px;

            margin-top: 7px;

            border: none;
            border-radius: 16px;

            cursor: pointer;

            color: white;

            font-size: 15px;
            font-weight: 800;

            letter-spacing: 0.2px;

            background:
                linear-gradient(
                    135deg,
                    #7c3aed,
                    #5b21b6 48%,
                    #0891b2
                );

            box-shadow:
                0 15px 35px rgba(124, 58, 237, 0.28),
                inset 0 1px 1px rgba(255, 255, 255, 0.30),
                inset 0 -5px 10px rgba(0, 0, 0, 0.15);

            overflow: hidden;

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }

        .login-button::before {
            content: "";

            position: absolute;

            top: 0;
            left: -100%;

            width: 70%;
            height: 100%;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    rgba(255, 255, 255, 0.22),
                    transparent
                );

            transform: skewX(-20deg);

            transition: left 0.65s ease;
        }

        .login-button:hover {
            transform:
                translateY(-3px)
                perspective(500px)
                rotateX(2deg);

            box-shadow:
                0 22px 45px rgba(124, 58, 237, 0.38),
                inset 0 1px 1px rgba(255, 255, 255, 0.35);
        }

        .login-button:hover::before {
            left: 130%;
        }

        .login-button:active {
            transform: translateY(1px);
        }

        .login-button-content {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* =========================
           REGISTER
        ========================= */

        .register-text {
            margin-top: 25px;

            text-align: center;

            color: #8585a8;

            font-size: 13px;
        }

        .register-text a {
            color: #c4b5fd;

            text-decoration: none;

            font-weight: 700;

            transition: 0.25s ease;
        }

        .register-text a:hover {
            color: #67e8f9;
        }

        /* =========================
           SECURITY NOTE
        ========================= */

        .security-note {
            margin-top: 26px;

            padding-top: 20px;

            border-top:
                1px solid rgba(255, 255, 255, 0.08);

            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;

            color: #666681;

            font-size: 11px;
        }

        .security-note i {
            color: #22c55e;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 850px) {

            .login-wrapper {
                grid-template-columns: 1fr;
                max-width: 550px;
            }

            .intro-panel {
                min-height: auto;

                padding: 40px;

                border-right: 1px solid var(--border);
                border-bottom: none;

                border-radius: 30px 30px 0 0;
            }

            .login-card {
                min-height: auto;

                border-radius: 0 0 30px 30px;
            }

            .login-card::before {
                border-radius: 0 0 29px 29px;
            }

            .feature-list {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 600px) {

            .topbar {
                padding: 18px 20px;
            }

            .brand-text {
                font-size: 16px;
            }

            .brand-icon {
                width: 40px;
                height: 40px;
                border-radius: 12px;
            }

            .back-home {
                padding: 9px 12px;
                font-size: 12px;
            }

            .page {
                padding:
                    20px 14px 50px;
            }

            .intro-panel {
                padding: 34px 28px;
            }

            .login-card {
                padding: 34px 26px;
            }

            .intro-panel h1 {
                font-size: 36px;
            }

            .feature-list {
                grid-template-columns: 1fr;
            }

            .camera-3d {
                width: 74px;
                height: 74px;
                border-radius: 21px;
            }

            .camera-3d i {
                font-size: 30px;
            }
        }

        /* =========================
           LOADING
        ========================= */

        .loading .button-text {
            opacity: 0;
        }

        .loading .button-loader {
            display: inline-block;
        }

        .button-loader {
            display: none;

            position: absolute;

            width: 20px;
            height: 20px;

            top: 50%;
            left: 50%;

            margin:
                -10px
                0
                0
                -10px;

            border:
                2px solid rgba(255, 255, 255, 0.35);

            border-top-color: #ffffff;

            border-radius: 50%;

            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>

<div class="background">
    <div class="orb one"></div>
    <div class="orb two"></div>
    <div class="orb three"></div>
</div>

<!-- =========================
     TOP NAVIGATION
========================= -->

<header class="topbar">

    <a href="index.php" class="brand">

        <div class="brand-icon">
            <i class="fa-solid fa-camera-retro"></i>
        </div>

        <span class="brand-text">
            Fast Foto Colour Lab
        </span>

    </a>

    <a href="index.php" class="back-home">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Back to Home</span>
    </a>

</header>


<!-- =========================
     LOGIN AREA
========================= -->

<main class="page">

    <div class="login-wrapper">

        <!-- LEFT SIDE -->

        <section class="intro-panel">

            <div class="camera-3d">
                <i class="fa-solid fa-camera"></i>
            </div>

            <h1>
                <span class="gradient-text">
                    Welcome Back
                </span>
            </h1>

            <p>
                Sign in to your Fast Foto Colour Lab account
                and manage your photo printing orders,
                uploaded photos and delivery details.
            </p>

            <ul class="feature-list">

                <li>
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Upload and manage your photos</span>
                </li>

                <li>
                    <i class="fa-solid fa-print"></i>
                    <span>Order professional photo prints</span>
                </li>

                <li>
                    <i class="fa-solid fa-box"></i>
                    <span>Track your printing orders</span>
                </li>

                <li>
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Secure account access</span>
                </li>

            </ul>

        </section>


        <!-- LOGIN FORM -->

        <section class="login-card">

            <div class="login-content">

                <div class="login-header">

                    <div class="small-title">
                        <span></span>
                        Account Login
                    </div>

                    <h2>
                        Sign in
                    </h2>

                    <p>
                        Enter your account details to continue.
                    </p>

                </div>


                <?php if ($error !== ''): ?>

                    <div class="error-box">
                        <i class="fa-solid fa-circle-exclamation"></i>

                        <span>
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>

                <?php endif; ?>


                <form
                    method="POST"
                    action=""
                    id="loginForm"
                    autocomplete="on"
                >

                    <!-- EMAIL -->

                    <div class="form-group">

                        <label for="email">
                            Email Address
                        </label>

                        <div class="input-box">

                            <i class="fa-solid fa-envelope"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Enter your email address"
                                value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                                autocomplete="email"
                                required
                            >

                        </div>

                    </div>


                    <!-- PASSWORD -->

                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <div class="input-box">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                id="passwordToggle"
                                aria-label="Show password"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>

                        </div>

                    </div>


                    <!-- LOGIN BUTTON -->

                    <button
                        type="submit"
                        class="login-button"
                        id="loginButton"
                    >

                        <span class="login-button-content">

                            <span class="button-text">
                                Sign In
                                <i class="fa-solid fa-arrow-right"></i>
                            </span>

                            <span class="button-loader"></span>

                        </span>

                    </button>

                </form>


                <!-- REGISTER -->

                <div class="register-text">

                    Don't have an account?

                    <a href="register.php">
                        Create Account
                    </a>

                </div>


                <!-- SECURITY -->

                <div class="security-note">

                    <i class="fa-solid fa-shield-check"></i>

                    <span>
                        Your login information is securely processed.
                    </span>

                </div>

            </div>

        </section>

    </div>

</main>


<script>

    /* =========================
       PASSWORD SHOW / HIDE
    ========================= */

    const passwordInput =
        document.getElementById('password');

    const passwordToggle =
        document.getElementById('passwordToggle');

    passwordToggle.addEventListener('click', function () {

        const icon =
            this.querySelector('i');

        if (passwordInput.type === 'password') {

            passwordInput.type = 'text';

            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');

            this.setAttribute(
                'aria-label',
                'Hide password'
            );

        } else {

            passwordInput.type = 'password';

            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');

            this.setAttribute(
                'aria-label',
                'Show password'
            );
        }
    });


    /* =========================
       FORM LOADING EFFECT
    ========================= */

    const loginForm =
        document.getElementById('loginForm');

    const loginButton =
        document.getElementById('loginButton');

    loginForm.addEventListener('submit', function () {

        if (!loginForm.checkValidity()) {
            return;
        }

        loginButton.classList.add('loading');

        loginButton.disabled = true;
    });


    /* =========================
       3D CARD MOUSE EFFECT
    ========================= */

    const card =
        document.querySelector('.login-card');

    if (window.innerWidth > 850) {

        card.addEventListener('mousemove', function (event) {

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

            const rotateY =
                ((x - centerX) / centerX) * 2.2;

            const rotateX =
                ((centerY - y) / centerY) * 2.2;

            card.style.transform =
                `perspective(1200px)
                 rotateX(${rotateX}deg)
                 rotateY(${rotateY}deg)`;
        });


        card.addEventListener('mouseleave', function () {

            card.style.transform =
                'perspective(1200px) rotateX(0deg) rotateY(0deg)';
        });
    }

</script>

</body>
</html>