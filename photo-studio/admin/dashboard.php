<?php

require_once "auth.php";
require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
| Fast Foto Colour Lab
|--------------------------------------------------------------------------
*/


// --------------------------------------------------
// Admin name
// --------------------------------------------------

$admin_name = $_SESSION["user_name"] ?? "Administrator";


// --------------------------------------------------
// Dashboard statistics
// --------------------------------------------------


// Total Orders
$total_orders = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM orders"
);

if ($result) {
    $row = $result->fetch_assoc();
    $total_orders = $row["total"] ?? 0;
}


// Pending Orders
$pending_orders = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE order_status = 'pending'"
);

if ($result) {
    $row = $result->fetch_assoc();
    $pending_orders = $row["total"] ?? 0;
}


// Printing Orders
$printing_orders = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE order_status = 'printing'"
);

if ($result) {
    $row = $result->fetch_assoc();
    $printing_orders = $row["total"] ?? 0;
}


// Completed Orders
$completed_orders = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE order_status = 'delivered'"
);

if ($result) {
    $row = $result->fetch_assoc();
    $completed_orders = $row["total"] ?? 0;
}


// Total Customers
$total_customers = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'customer'"
);

if ($result) {
    $row = $result->fetch_assoc();
    $total_customers = $row["total"] ?? 0;
}


// Total Products
$total_products = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM print_products"
);

if ($result) {
    $row = $result->fetch_assoc();
    $total_products = $row["total"] ?? 0;
}


// Active Products
$active_products = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM print_products
     WHERE status = 'active'"
);

if ($result) {
    $row = $result->fetch_assoc();
    $active_products = $row["total"] ?? 0;
}


// --------------------------------------------------
// Total Revenue
// --------------------------------------------------

$total_revenue = 0;

$result = $conn->query(
    "SELECT COALESCE(SUM(total_amount), 0) AS revenue
     FROM orders
     WHERE order_status != 'cancelled'"
);

if ($result) {
    $row = $result->fetch_assoc();
    $total_revenue = $row["revenue"] ?? 0;
}


// --------------------------------------------------
// Recent Orders
// --------------------------------------------------

$recent_orders = [];

$sql = "
    SELECT
        o.id,
        o.total_amount,
        o.payment_status,
        o.order_status,
        o.created_at,
        u.name AS customer_name,
        u.email AS customer_email
    FROM orders o
    INNER JOIN users u
        ON o.user_id = u.id
    ORDER BY o.created_at DESC
    LIMIT 8
";

$result = $conn->query($sql);

if ($result) {

    while ($row = $result->fetch_assoc()) {

        $recent_orders[] = $row;

    }

}


// --------------------------------------------------
// Helper function for status class
// --------------------------------------------------

function getStatusClass($status)
{
    switch ($status) {

        case "pending":
            return "pending";

        case "confirmed":
            return "confirmed";

        case "printing":
            return "printing";

        case "ready":
            return "ready";

        case "delivered":
            return "delivered";

        case "cancelled":
            return "cancelled";

        default:
            return "pending";
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

    <title>
        Admin Dashboard | Fast Foto Colour Lab
    </title>

    <link
        rel="stylesheet"
        href="css/admin.css"
    >

</head>


<body>


<!-- ==================================================
     ADMIN NAVIGATION
================================================== -->

<header class="admin-navbar">

    <div class="admin-nav-container">


        <!-- BRAND -->

        <a
            href="dashboard.php"
            class="admin-brand"
        >

            <div class="admin-brand-icon">
                📸
            </div>

            <div class="admin-brand-text">

                <span class="admin-brand-name">
                    Fast Foto
                </span>

                <span class="admin-brand-subtitle">
                    Colour Lab
                </span>

            </div>

        </a>


        <!-- NAVIGATION -->

        <nav class="admin-nav-links">

            <a
                href="dashboard.php"
                class="active"
            >
                📊 Dashboard
            </a>

            <a href="orders.php">
                📦 Orders
            </a>

            <a href="customers.php">
                👥 Customers
            </a>

            <a href="products.php">
                🖨️ Products
            </a>
            <a href="messages.php" class="quick-action">
    <span class="quick-action-icon">💬</span>

    <span>
        <strong>Customer Messages</strong>
        <small>View customer enquiries</small>
    </span>
</a>

        </nav>


        <!-- ADMIN PROFILE -->

        <div class="admin-profile">

            <div class="admin-avatar">
                <?php
                echo strtoupper(
                    substr($admin_name, 0, 1)
                );
                ?>
            </div>

            <div class="admin-profile-info">

                <strong>
                    <?php
                    echo htmlspecialchars($admin_name);
                    ?>
                </strong>

                <span>
                    Administrator
                </span>

            </div>

            <a
                href="../logout.php"
                class="admin-logout"
                title="Logout"
            >
            
                🚪
                <strong>Logout</strong> 
            </a>

        </div>

    </div>

</header>



<!-- ==================================================
     MAIN CONTENT
================================================== -->

<main class="admin-container">


    <!-- ==================================================
         PAGE HEADER
    ================================================== -->

    <section class="admin-welcome">

        <div>

            <p class="admin-label">
                ADMINISTRATION
            </p>

            <h1>
                Welcome back,
                <span>
                    <?php
                    echo htmlspecialchars($admin_name);
                    ?>
                </span>
                👋
            </h1>

            <p class="admin-description">
                Manage photo printing orders, customers,
                and print products from one place.
            </p>

        </div>


        <div class="admin-date">

            <span>
                TODAY
            </span>

            <strong>
                <?php
                echo date("d M Y");
                ?>
            </strong>

        </div>

    </section>



    <!-- ==================================================
         STATISTICS
    ================================================== -->

    <section class="stats-grid">


        <!-- Total Orders -->

        <div class="stat-card">

            <div class="stat-icon purple">
                📦
            </div>

            <div class="stat-content">

                <span>
                    Total Orders
                </span>

                <strong>
                    <?php
                    echo number_format($total_orders);
                    ?>
                </strong>

                <small>
                    All orders
                </small>

            </div>

        </div>


        <!-- Pending -->

        <div class="stat-card">

            <div class="stat-icon orange">
                ⏳
            </div>

            <div class="stat-content">

                <span>
                    Pending Orders
                </span>

                <strong>
                    <?php
                    echo number_format($pending_orders);
                    ?>
                </strong>

                <small>
                    Need attention
                </small>

            </div>

        </div>


        <!-- Printing -->

        <div class="stat-card">

            <div class="stat-icon blue">
                🖨️
            </div>

            <div class="stat-content">

                <span>
                    Printing
                </span>

                <strong>
                    <?php
                    echo number_format($printing_orders);
                    ?>
                </strong>

                <small>
                    Currently printing
                </small>

            </div>

        </div>


        <!-- Completed -->

        <div class="stat-card">

            <div class="stat-icon green">
                ✓
            </div>

            <div class="stat-content">

                <span>
                    Completed
                </span>

                <strong>
                    <?php
                    echo number_format($completed_orders);
                    ?>
                </strong>

                <small>
                    Delivered orders
                </small>

            </div>

        </div>


    </section>



    <!-- ==================================================
         SECONDARY STATISTICS
    ================================================== -->

    <section class="secondary-stats">


        <div class="mini-stat">

            <div class="mini-icon">
                👥
            </div>

            <div>

                <span>
                    Customers
                </span>

                <strong>
                    <?php
                    echo number_format($total_customers);
                    ?>
                </strong>

            </div>

            <a href="customers.php">
                →
            </a>

        </div>


        <div class="mini-stat">

            <div class="mini-icon">
                🖨️
            </div>

            <div>

                <span>
                    Print Products
                </span>

                <strong>
                    <?php
                    echo number_format($total_products);
                    ?>
                </strong>

            </div>

            <a href="products.php">
                →
            </a>

        </div>


        <div class="mini-stat">

            <div class="mini-icon">
                ✓
            </div>

            <div>

                <span>
                    Active Products
                </span>

                <strong>
                    <?php
                    echo number_format($active_products);
                    ?>
                </strong>

            </div>

            <a href="products.php">
                →
            </a>

        </div>


        <div class="mini-stat revenue">

            <div class="mini-icon">
                ₹
            </div>

            <div>

                <span>
                    Order Value
                </span>

                <strong>
                    ₹<?php
                    echo number_format(
                        $total_revenue,
                        2
                    );
                    ?>
                </strong>

            </div>

        </div>


    </section>



    <!-- ==================================================
         QUICK ACTIONS
    ================================================== -->

    <section class="quick-section">

        <div class="section-heading">

            <div>

                <p class="section-label">
                    MANAGEMENT
                </p>

                <h2>
                    Quick Actions
                </h2>

            </div>

        </div>


        <div class="quick-grid">


            <a
                href="orders.php"
                class="quick-card"
            >

                <div class="quick-card-icon purple-bg">
                    📦
                </div>

                <div>

                    <h3>
                        Manage Orders
                    </h3>

                    <p>
                        View and update customer
                        printing orders.
                    </p>

                </div>

                <span>
                    →
                </span>

            </a>


            <a
                href="customers.php"
                class="quick-card"
            >

                <div class="quick-card-icon cyan-bg">
                    👥
                </div>

                <div>

                    <h3>
                        Customers
                    </h3>

                    <p>
                        View registered customer
                        information.
                    </p>

                </div>

                <span>
                    →
                </span>

            </a>


            <a
                href="products.php"
                class="quick-card"
            >

                <div class="quick-card-icon green-bg">
                    🖨️
                </div>

                <div>

                    <h3>
                        Print Products
                    </h3>

                    <p>
                        Manage sizes, paper types
                        and prices.
                    </p>

                </div>

                <span>
                    →
                </span>

            </a>


        </div>

    </section>



    <!-- ==================================================
         RECENT ORDERS
    ================================================== -->

    <section class="orders-section">


        <div class="section-heading">

            <div>

                <p class="section-label">
                    ORDER ACTIVITY
                </p>

                <h2>
                    Recent Orders
                </h2>

            </div>


            <a
                href="orders.php"
                class="view-all"
            >
                View All Orders →
            </a>

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
                                Customer
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


                            <!-- ORDER -->

                            <td>

                                <strong class="order-id">
                                    #<?php
                                    echo htmlspecialchars(
                                        $order["id"]
                                    );
                                    ?>
                                </strong>

                            </td>


                            <!-- CUSTOMER -->

                            <td>

                                <div class="customer-cell">

                                    <div class="customer-avatar">

                                        <?php
                                        echo strtoupper(
                                            substr(
                                                $order["customer_name"],
                                                0,
                                                1
                                            )
                                        );
                                        ?>

                                    </div>

                                    <div>

                                        <strong>
                                            <?php
                                            echo htmlspecialchars(
                                                $order["customer_name"]
                                            );
                                            ?>
                                        </strong>

                                        <small>
                                            <?php
                                            echo htmlspecialchars(
                                                $order["customer_email"]
                                            );
                                            ?>
                                        </small>

                                    </div>

                                </div>

                            </td>


                            <!-- DATE -->

                            <td>

                                <span class="date">

                                    <?php
                                    echo date(
                                        "d M Y",
                                        strtotime(
                                            $order["created_at"]
                                        )
                                    );
                                    ?>

                                </span>

                                <small class="time">

                                    <?php
                                    echo date(
                                        "h:i A",
                                        strtotime(
                                            $order["created_at"]
                                        )
                                    );
                                    ?>

                                </small>

                            </td>


                            <!-- AMOUNT -->

                            <td>

                                <strong class="order-amount">

                                    ₹<?php
                                    echo number_format(
                                        $order["total_amount"],
                                        2
                                    );
                                    ?>

                                </strong>

                            </td>


                            <!-- PAYMENT -->

                            <td>

                                <span
                                    class="payment-badge
                                    <?php
                                    echo htmlspecialchars(
                                        $order["payment_status"]
                                    );
                                    ?>"
                                >

                                    <?php
                                    echo ucfirst(
                                        $order["payment_status"]
                                    );
                                    ?>

                                </span>

                            </td>


                            <!-- STATUS -->

                            <td>

                                <span
                                    class="status-badge
                                    <?php
                                    echo getStatusClass(
                                        $order["order_status"]
                                    );
                                    ?>"
                                >

                                    <span class="status-dot"></span>

                                    <?php
                                    echo ucfirst(
                                        $order["order_status"]
                                    );
                                    ?>

                                </span>

                            </td>


                            <!-- ACTION -->

                            <td>

                                <a
                                    href="order_details.php?id=<?php
                                    echo $order["id"];
                                    ?>"
                                    class="view-btn"
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


            <!-- NO ORDERS -->

            <div class="empty-orders">

                <div class="empty-orders-icon">
                    📦
                </div>

                <h3>
                    No orders yet
                </h3>

                <p>
                    Customer orders will appear here
                    once they place their first order.
                </p>

            </div>


        <?php endif; ?>


    </section>



    <!-- ==================================================
         ADMIN INFO
    ================================================== -->

    <section class="admin-info-card">

        <div class="info-icon">
            💡
        </div>

        <div>

            <h3>
                Admin Panel
            </h3>

            <p>
                Use the Orders section to confirm orders,
                start printing, mark photos as ready,
                and complete deliveries.
            </p>

        </div>

        <a
            href="orders.php"
            class="info-button"
        >
            Manage Orders →
        </a>

    </section>


</main>



<!-- ==================================================
     FOOTER
================================================== -->

<footer class="admin-footer">

    <div class="footer-container">

        <div>

            <strong>
                📸 Fast Foto Colour Lab
            </strong>

            <p>
                Administration Panel
            </p>

        </div>


        <div class="footer-links">

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

            <a href="../logout.php">
                Logout
            </a>

        </div>


        <p class="copyright">

            © <?php echo date("Y"); ?>
            Fast Foto Colour Lab

        </p>

    </div>

</footer>


</body>

</html>