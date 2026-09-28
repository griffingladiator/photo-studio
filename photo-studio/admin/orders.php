<?php

require_once "auth.php";
require_once "../config/database.php";

$admin_name = $_SESSION["user_name"] ?? "Administrator";


// --------------------------------------------------
// Filter
// --------------------------------------------------

$status_filter = $_GET["status"] ?? "all";

$allowed_statuses = [
    "pending",
    "confirmed",
    "printing",
    "ready",
    "delivered",
    "cancelled"
];


// --------------------------------------------------
// Get Orders
// --------------------------------------------------

$orders = [];

if ($status_filter !== "all" && in_array($status_filter, $allowed_statuses)) {

    $stmt = $conn->prepare("
        SELECT
            o.id,
            o.total_amount,
            o.payment_status,
            o.order_status,
            o.delivery_address,
            o.created_at,
            u.name AS customer_name,
            u.email AS customer_email,
            u.phone AS customer_phone
        FROM orders o
        INNER JOIN users u
            ON o.user_id = u.id
        WHERE o.order_status = ?
        ORDER BY o.created_at DESC
    ");

    $stmt->bind_param("s", $status_filter);

} else {

    $stmt = $conn->prepare("
        SELECT
            o.id,
            o.total_amount,
            o.payment_status,
            o.order_status,
            o.delivery_address,
            o.created_at,
            u.name AS customer_name,
            u.email AS customer_email,
            u.phone AS customer_phone
        FROM orders o
        INNER JOIN users u
            ON o.user_id = u.id
        ORDER BY o.created_at DESC
    ");
}

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

$stmt->close();


// --------------------------------------------------
// Count orders for filters
// --------------------------------------------------

$status_counts = [];

$result = $conn->query("
    SELECT order_status, COUNT(*) AS total
    FROM orders
    GROUP BY order_status
");

while ($row = $result->fetch_assoc()) {
    $status_counts[$row["order_status"]] = $row["total"];
}


function statusClass($status)
{
    return htmlspecialchars($status);
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
        Manage Orders | Fast Foto Colour Lab
    </title>

    <link
        rel="stylesheet"
        href="css/admin.css"
    >

    <style>

        .orders-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .orders-page-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .orders-page-header p {
            color: #858599;
            font-size: 12px;
        }

        .back-dashboard {
            padding: 10px 14px;
            border-radius: 9px;
            background: rgba(124, 58, 237, 0.12);
            color: #c4b5fd;
            font-size: 11px;
            font-weight: 700;
        }

        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .filter-btn {
            padding: 8px 12px;
            border-radius: 8px;
            background: #11111c;
            border: 1px solid rgba(255,255,255,0.06);
            color: #858599;
            font-size: 10px;
            font-weight: 700;
        }

        .filter-btn:hover {
            color: white;
            border-color: rgba(124,58,237,0.3);
        }

        .filter-btn.active {
            color: #c4b5fd;
            background: rgba(124,58,237,0.14);
            border-color: rgba(124,58,237,0.25);
        }

        .filter-count {
            margin-left: 4px;
            color: #555568;
        }

        .orders-count {
            margin-bottom: 12px;
            color: #66667a;
            font-size: 10px;
        }

        .customer-order-info {
            display: flex;
            flex-direction: column;
        }

        .customer-order-info strong {
            color: #dedee5;
            font-size: 11px;
        }

        .customer-order-info small {
            color: #5f5f72;
            font-size: 8px;
        }

        .manage-btn {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 7px;
            background: rgba(6,182,212,0.09);
            color: #67e8f9;
            font-size: 9px;
            font-weight: 800;
        }

        .manage-btn:hover {
            background: rgba(6,182,212,0.17);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            border-radius: 15px;
            background: #101019;
            border: 1px dashed rgba(255,255,255,0.08);
        }

        .empty-state-icon {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .empty-state h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .empty-state p {
            color: #68687b;
            font-size: 11px;
        }

        @media (max-width: 650px) {

            .orders-page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .back-dashboard {
                width: 100%;
                text-align: center;
            }

        }

    </style>

</head>


<body>


<!-- NAVBAR -->

<header class="admin-navbar">

    <div class="admin-nav-container">

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


        <nav class="admin-nav-links">

            <a href="dashboard.php">
                📊 Dashboard
            </a>

            <a
                href="orders.php"
                class="active"
            >
                📦 Orders
            </a>

            <a href="customers.php">
                👥 Customers
            </a>

            <a href="products.php">
                🖨️ Products
            </a>

        </nav>


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
            >
                🚪
                <strong>Logout</strong>
            </a>

        </div>

    </div>

</header>


<!-- MAIN -->

<main class="admin-container">


    <div class="orders-page-header">

        <div>

            <p class="admin-label">
                ORDER MANAGEMENT
            </p>

            <h1>
                Customer Orders
            </h1>

            <p>
                View, manage and update photo printing orders.
            </p>

        </div>

        <a
            href="dashboard.php"
            class="back-dashboard"
        >
            ← Dashboard
        </a>

    </div>


    <!-- FILTERS -->

    <div class="filter-bar">

        <a
            href="orders.php"
            class="filter-btn <?php echo $status_filter === "all" ? "active" : ""; ?>"
        >
            All
            <span class="filter-count">
                <?php echo $total_orders = array_sum($status_counts); ?>
            </span>
        </a>


        <?php foreach ($allowed_statuses as $status): ?>

            <a
                href="orders.php?status=<?php echo $status; ?>"
                class="filter-btn
                <?php echo $status_filter === $status ? "active" : ""; ?>"
            >

                <?php echo ucfirst($status); ?>

                <span class="filter-count">
                    <?php echo $status_counts[$status] ?? 0; ?>
                </span>

            </a>

        <?php endforeach; ?>

    </div>


    <div class="orders-count">

        Showing
        <strong>
            <?php echo count($orders); ?>
        </strong>
        order(s)

    </div>


    <?php if (count($orders) > 0): ?>


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

                <?php foreach ($orders as $order): ?>

                    <tr>


                        <td>

                            <strong class="order-id">
                                #<?php
                                echo htmlspecialchars(
                                    $order["id"]
                                );
                                ?>
                            </strong>

                        </td>


                        <td>

                            <div class="customer-order-info">

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

                        </td>


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


                        <td>

                            <span
                                class="status-badge
                                <?php
                                echo statusClass(
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


                        <td>

                            <a
                                href="order_details.php?id=<?php
                                echo $order["id"];
                                ?>"
                                class="manage-btn"
                            >
                                Manage
                            </a>

                        </td>


                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>


    <?php else: ?>


        <div class="empty-state">

            <div class="empty-state-icon">
                📦
            </div>

            <h3>
                No Orders Found
            </h3>

            <p>
                There are no orders matching this filter.
            </p>

        </div>


    <?php endif; ?>


</main>


<!-- FOOTER -->

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