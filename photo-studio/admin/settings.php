<?php

require_once "auth.php";
require_once "../config/database.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Settings - Fast Foto Colour Lab</title>

    <link rel="stylesheet"
          href="css/admin.css">

    <style>

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .settings-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .settings-card h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .settings-card p {
            color: #666;
            line-height: 1.6;
        }

        .setting-value {
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            font-weight: 600;
        }

        .status-active {
            color: #15803d;
        }

    </style>

</head>

<body>

<header class="admin-header">

    <div class="admin-logo">
        📸 Fast Foto Colour Lab
    </div>

    <nav>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="orders.php">
            Orders
        </a>

        <a href="customers.php">
            Customers
        </a>

        <a href="products.php">
            Products
        </a>

        <a href="settings.php">
            Settings
        </a>

        <a href="../logout.php">
            Logout
        </a>

    </nav>

</header>


<main class="admin-container">

    <h1>System Settings</h1>

    <p>
        Manage and view the basic configuration of
        Fast Foto Colour Lab.
    </p>


    <div class="settings-grid">


        <!-- Business Information -->

        <div class="settings-card">

            <h3>🏪 Business Information</h3>

            <p>
                Basic information about the photo printing
                business.
            </p>

            <div class="setting-value">

                Fast Foto Colour Lab

            </div>

            <div class="setting-value">

                Online Photo Printing & Management

            </div>

        </div>


        <!-- Database -->

        <div class="settings-card">

            <h3>🗄️ Database</h3>

            <p>
                Current application database information.
            </p>

            <div class="setting-value">

                Database:
                <strong>photo_studio</strong>

            </div>

            <div class="setting-value status-active">

                ● Database Connected

            </div>

        </div>


        <!-- Application -->

        <div class="settings-card">

            <h3>⚙️ Application</h3>

            <p>
                Current application configuration.
            </p>

            <div class="setting-value">

                Backend: PHP

            </div>

            <div class="setting-value">

                Database: MySQL

            </div>

            <div class="setting-value">

                Environment: XAMPP

            </div>

        </div>


        <!-- Order System -->

        <div class="settings-card">

            <h3>📦 Order System</h3>

            <p>
                Supported order statuses.
            </p>

            <div class="setting-value">

                Pending

            </div>

            <div class="setting-value">

                Confirmed

            </div>

            <div class="setting-value">

                Printing

            </div>

            <div class="setting-value">

                Ready

            </div>

            <div class="setting-value">

                Delivered

            </div>

            <div class="setting-value">

                Cancelled

            </div>

        </div>


        <!-- Payment -->

        <div class="settings-card">

            <h3>💳 Payment</h3>

            <p>
                Current payment configuration.
            </p>

            <div class="setting-value">

                Payment Method:
                Cash on Delivery / Pay at Store

            </div>

            <div class="setting-value">

                Online Payment:
                Not Enabled

            </div>

        </div>


        <!-- Security -->

        <div class="settings-card">

            <h3>🔐 Security</h3>

            <p>
                Current security features implemented
                in the system.
            </p>

            <div class="setting-value">

                ✓ Password Hashing

            </div>

            <div class="setting-value">

                ✓ Prepared SQL Statements

            </div>

            <div class="setting-value">

                ✓ Admin Authentication

            </div>

            <div class="setting-value">

                ✓ User Photo Ownership Validation

            </div>

        </div>


    </div>

</main>

</body>

</html>