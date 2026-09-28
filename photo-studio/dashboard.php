<?php

session_start();

require_once "config/database.php";

// Check whether the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"] ?? "Customer";
$user_email = $_SESSION["user_email"] ?? "";

// --------------------------------------------------
// Get dashboard statistics
// --------------------------------------------------

// Total uploaded photos
$photo_count = 0;

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total 
     FROM photos 
     WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$photo_count = $row["total"] ?? 0;

$stmt->close();


// Total orders
$order_count = 0;

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total 
     FROM orders 
     WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$order_count = $row["total"] ?? 0;

$stmt->close();


// Pending orders
$pending_orders = 0;

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total 
     FROM orders 
     WHERE user_id = ? 
     AND order_status = 'pending'"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$pending_orders = $row["total"] ?? 0;

$stmt->close();


// Completed orders
$completed_orders = 0;

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total 
     FROM orders 
     WHERE user_id = ? 
     AND order_status = 'delivered'"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$completed_orders = $row["total"] ?? 0;

$stmt->close();


// --------------------------------------------------
// Get recent orders
// --------------------------------------------------

$recent_orders = [];

$stmt = $conn->prepare(
    "SELECT id, total_amount, payment_status, order_status, created_at
     FROM orders
     WHERE user_id = ?
     ORDER BY created_at DESC
     LIMIT 5"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $recent_orders[] = $row;
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Fast Foto Colour Lab</title>

    <link rel="stylesheet" href="css/dashboard.css">

</head>

<body>

<!-- ==========================================
     NAVIGATION BAR
========================================== -->

<header class="navbar">

    <div class="nav-container">

        <a href="dashboard.php" class="brand">

            <div class="brand-icon">
                📸
            </div>

            <div class="brand-text">
                <span class="brand-name">Fast Foto</span>
                <span class="brand-subtitle">Colour Lab</span>
            </div>

        </a>


        <nav class="nav-links">

            <a href="dashboard.php" class="active">
                🏠 Dashboard
            </a>

            <a href="upload.php">
                📸 Print Photos
            </a>

            <a href="orders.php">
                📦 My Orders
            </a>

            <a href="logout.php" class="logout-link">
                🚪 Logout
            </a>

        </nav>

    </div>

</header>


<!-- ==========================================
     MAIN CONTENT
========================================== -->

<main class="dashboard-container">


    <!-- ======================================
         WELCOME SECTION
    ======================================= -->

    <section class="welcome-section">

        <div class="welcome-content">

            <p class="welcome-label">
                CUSTOMER DASHBOARD
            </p>

            <h1>
                Welcome back,
                <span><?php echo htmlspecialchars($user_name); ?></span> 👋
            </h1>

            <p class="welcome-description">
                Upload your photos, customize your prints,
                place an order and track it from one place.
            </p>

        </div>


        <div class="welcome-action">

            <a href="upload.php" class="primary-btn">

                <span class="btn-icon">📸</span>

                Upload & Print Photos

                <span class="arrow">→</span>

            </a>

        </div>

    </section>



    <!-- ======================================
         STATISTICS
    ======================================= -->

    <section class="stats-grid">


        <div class="stat-card">

            <div class="stat-icon purple">
                🖼️
            </div>

            <div class="stat-info">

                <span class="stat-label">
                    Uploaded Photos
                </span>

                <strong>
                    <?php echo $photo_count; ?>
                </strong>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon cyan">
                📦
            </div>

            <div class="stat-info">

                <span class="stat-label">
                    Total Orders
                </span>

                <strong>
                    <?php echo $order_count; ?>
                </strong>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon orange">
                ⏳
            </div>

            <div class="stat-info">

                <span class="stat-label">
                    Pending Orders
                </span>

                <strong>
                    <?php echo $pending_orders; ?>
                </strong>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon green">
                ✓
            </div>

            <div class="stat-info">

                <span class="stat-label">
                    Completed Orders
                </span>

                <strong>
                    <?php echo $completed_orders; ?>
                </strong>

            </div>

        </div>

    </section>



    <!-- ======================================
         QUICK ACTIONS
    ======================================= -->

    <section class="section">

        <div class="section-heading">

            <div>

                <p class="section-label">
                    GET STARTED
                </p>

                <h2>
                    Quick Actions
                </h2>

            </div>

        </div>


        <div class="quick-actions">


            <!-- Upload -->

            <a href="upload.php" class="action-card">

                <div class="action-icon purple-bg">
                    📤
                </div>

                <div class="action-content">

                    <h3>
                        Upload Photos
                    </h3>

                    <p>
                        Upload your favorite photos
                        and start creating prints.
                    </p>

                </div>

                <span class="action-arrow">
                    →
                </span>

            </a>


            <!-- Orders -->

            <a href="orders.php" class="action-card">

                <div class="action-icon cyan-bg">
                    📦
                </div>

                <div class="action-content">

                    <h3>
                        My Orders
                    </h3>

                    <p>
                        View your orders and track
                        the printing status.
                    </p>

                </div>

                <span class="action-arrow">
                    →
                </span>

            </a>


            <!-- Print -->

            <a href="upload.php" class="action-card">

                <div class="action-icon pink-bg">
                    🖨️
                </div>

                <div class="action-content">

                    <h3>
                        Start Printing
                    </h3>

                    <p>
                        Choose photo size, paper type
                        and quantity.
                    </p>

                </div>

                <span class="action-arrow">
                    →
                </span>

            </a>

        </div>

    </section>



    <!-- ======================================
         RECENT ORDERS
    ======================================= -->

    <section class="section recent-section">

        <div class="section-heading">

            <div>

                <p class="section-label">
                    ORDER ACTIVITY
                </p>

                <h2>
                    Recent Orders
                </h2>

            </div>


            <?php if ($order_count > 0): ?>

                <a href="orders.php" class="view-all">
                    View All →
                </a>

            <?php endif; ?>

        </div>


        <?php if (count($recent_orders) > 0): ?>

            <div class="orders-table-wrapper">

                <table class="orders-table">

                    <thead>

                        <tr>

                            <th>
                                Order
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($recent_orders as $order): ?>

                        <tr>

                            <td>

                                <strong class="order-number">
                                    #<?php echo htmlspecialchars($order["id"]); ?>
                                </strong>

                            </td>


                            <td>

                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime($order["created_at"])
                                );
                                ?>

                            </td>


                            <td>

                                <strong class="amount">
                                    ₹<?php
                                    echo number_format(
                                        $order["total_amount"],
                                        2
                                    );
                                    ?>
                                </strong>

                            </td>


                            <td>

                                <span class="payment-badge
                                    <?php echo htmlspecialchars($order["payment_status"]); ?>">

                                    <?php
                                    echo ucfirst(
                                        $order["payment_status"]
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="status-badge
                                    <?php echo htmlspecialchars($order["order_status"]); ?>">

                                    <?php
                                    echo ucfirst(
                                        $order["order_status"]
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>

                                <a
                                    href="order_details.php?id=<?php echo $order["id"]; ?>"
                                    class="details-btn"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>


            <!-- Empty Orders -->

            <div class="empty-orders">

                <div class="empty-icon">
                    📦
                </div>

                <h3>
                    No orders yet
                </h3>

                <p>
                    Your recent orders will appear here
                    once you place your first order.
                </p>

                <a href="upload.php" class="secondary-btn">
                    Start Printing →
                </a>

            </div>


        <?php endif; ?>

    </section>



    <!-- ======================================
         HOW IT WORKS
    ======================================= -->

    <section class="how-section">

        <div class="section-heading center-heading">

            <div>

                <p class="section-label">
                    SIMPLE & FAST
                </p>

                <h2>
                    How It Works
                </h2>

                <p class="heading-description">
                    Get your photos printed in just a few simple steps.
                </p>

            </div>

        </div>


        <div class="steps">


            <div class="step">

                <div class="step-number">
                    01
                </div>

                <div class="step-icon">
                    📤
                </div>

                <h3>
                    Upload
                </h3>

                <p>
                    Upload your photos directly
                    from your device.
                </p>

            </div>


            <div class="step-line"></div>


            <div class="step">

                <div class="step-number">
                    02
                </div>

                <div class="step-icon">
                    ✨
                </div>

                <h3>
                    Customize
                </h3>

                <p>
                    Adjust your photo and select
                    the print options.
                </p>

            </div>


            <div class="step-line"></div>


            <div class="step">

                <div class="step-number">
                    03
                </div>

                <div class="step-icon">
                    🛒
                </div>

                <h3>
                    Order
                </h3>

                <p>
                    Add your photos to cart
                    and place your order.
                </p>

            </div>


            <div class="step-line"></div>


            <div class="step">

                <div class="step-number">
                    04
                </div>

                <div class="step-icon">
                    🖨️
                </div>

                <h3>
                    Collect
                </h3>

                <p>
                    Get your professionally
                    printed photos.
                </p>

            </div>

        </div>

    </section>



    <!-- ======================================
         CTA
    ======================================= -->

    <section class="bottom-cta">

        <div>

            <span class="cta-small">
                READY TO PRINT?
            </span>

            <h2>
                Turn your memories into prints.
            </h2>

            <p>
                Upload your photos and create
                beautiful prints today.
            </p>

        </div>


        <a href="upload.php" class="cta-btn">

            📸 Start Printing

            <span>→</span>

        </a>

    </section>


</main>



<!-- ==========================================
     FOOTER
========================================== -->

<footer class="footer">

    <div class="footer-container">

        <div class="footer-brand">

            <span>
                📸 Fast Foto Colour Lab
            </span>

            <p>
                Quality photo printing made simple.
            </p>

        </div>


        <div class="footer-links">

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="upload.php">
                Print Photos
            </a>

            <a href="orders.php">
                My Orders
            </a>

            <a href="logout.php">
                Logout
            </a>

        </div>


        <p class="copyright">
            © <?php echo date("Y"); ?>
            Fast Foto Colour Lab. All rights reserved.
        </p>

    </div>

</footer>


</body>
</html>