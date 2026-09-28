<?php

require_once "auth.php";
require_once "../config/database.php";

if (!isset($_GET["id"]) || !filter_var($_GET["id"], FILTER_VALIDATE_INT)) {
    header("Location: customers.php");
    exit;
}

$customer_id = (int)$_GET["id"];


/* Get customer */

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        email,
        phone,
        address,
        created_at
    FROM users
    WHERE id = ?
    AND role = 'customer'
    LIMIT 1
");

$stmt->bind_param("i", $customer_id);
$stmt->execute();

$customer_result = $stmt->get_result();

if ($customer_result->num_rows === 0) {
    header("Location: customers.php");
    exit;
}

$customer = $customer_result->fetch_assoc();


/* Get customer orders */

$order_stmt = $conn->prepare("
    SELECT
        id,
        total_amount,
        payment_status,
        order_status,
        created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$order_stmt->bind_param("i", $customer_id);
$order_stmt->execute();

$orders = $order_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Customer Details - Fast Foto Colour Lab
    </title>

    <link rel="stylesheet" href="css/admin.css">

    <style>

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #374151;
            font-weight: 600;
        }

        .customer-profile {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-card {
            background: black;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .large-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }

        .profile-name {
            font-size: 22px;
            font-weight: 700;
        }

        .profile-email {
            color: #6b7280;
            margin-top: 3px;
        }

        .info-row {
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .info-value {
            font-weight: 600;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-printing {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-ready {
            background: #dcfce7;
            color: #166534;
        }

        .status-delivered {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 768px) {

            .customer-profile {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<header class="admin-header">

    <div class="admin-logo">
        📸 Fast Foto Colour Lab
    </div>

    <div class="admin-user">
        Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
    </div>

</header>


<div class="admin-layout">

    <aside class="admin-sidebar">

        <a href="dashboard.php">
            📊 Dashboard
        </a>

        <a href="orders.php">
            📦 Manage Orders
        </a>

        <a href="customers.php" class="active">
            👥 Customers
        </a>

        <a href="products.php">
            🖨️ Print Products
        </a>

        <a href="../logout.php">
            🚪 Logout
        </a>

    </aside>


    <main class="admin-container">

        <a href="customers.php" class="back-link">
            ← Back to Customers
        </a>


        <div class="customer-profile">

            <!-- Customer Information -->

            <div class="info-card">

                <div class="profile-header">

                    <div class="large-avatar">

                        <?php
                        echo htmlspecialchars(
                            strtoupper(substr($customer["name"], 0, 1))
                        );
                        ?>

                    </div>

                    <div>

                        <div class="profile-name">
                            <?php
                            echo htmlspecialchars($customer["name"]);
                            ?>
                        </div>

                        <div class="profile-email">
                            <?php
                            echo htmlspecialchars($customer["email"]);
                            ?>
                        </div>

                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Phone
                    </div>

                    <div class="info-value">

                        <?php
                        echo $customer["phone"]
                            ? htmlspecialchars($customer["phone"])
                            : "Not provided";
                        ?>

                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Address
                    </div>

                    <div class="info-value">

                        <?php
                        echo $customer["address"]
                            ? nl2br(htmlspecialchars($customer["address"]))
                            : "Not provided";
                        ?>

                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Registered On
                    </div>

                    <div class="info-value">

                        <?php
                        echo date(
                            "d M Y, h:i A",
                            strtotime($customer["created_at"])
                        );
                        ?>

                    </div>

                </div>

            </div>


            <!-- Order Summary -->

            <div class="info-card">

                <h2>📦 Order History</h2>

                <br>

                <?php

                $total_orders = $orders->num_rows;
                $total_value = 0;

                ?>

                <div class="info-row">

                    <div class="info-label">
                        Total Orders
                    </div>

                    <div class="info-value">
                        <?php echo $total_orders; ?>
                    </div>

                </div>


                <?php

                while ($order = $orders->fetch_assoc()) {
                    $total_value += (float)$order["total_amount"];
                }

                ?>

                <div class="info-row">

                    <div class="info-label">
                        Total Order Value
                    </div>

                    <div class="info-value">

                        ₹<?php
                        echo number_format($total_value, 2);
                        ?>

                    </div>

                </div>

            </div>

        </div>


        <!-- Orders -->

        <div class="admin-card">

            <h2 style="margin-bottom:20px;">
                Customer Orders
            </h2>


            <?php

            $order_stmt->execute();
            $orders = $order_stmt->get_result();

            ?>


            <div class="table-responsive">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if ($orders->num_rows > 0): ?>

                        <?php while ($order = $orders->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    #<?php echo (int)$order["id"]; ?>
                                </td>

                                <td>
                                    ₹<?php
                                    echo number_format(
                                        (float)$order["total_amount"],
                                        2
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($order["payment_status"]); ?>
                                </td>

                                <td>

                                    <span class="status status-<?php echo htmlspecialchars($order["order_status"]); ?>">

                                        <?php
                                        echo ucfirst(
                                            htmlspecialchars(
                                                $order["order_status"]
                                            )
                                        );
                                        ?>

                                    </span>

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

                                    <a
                                        href="order_details.php?id=<?php echo (int)$order["id"]; ?>"
                                        class="view-btn"
                                    >
                                        View Order
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="6">

                                <div class="empty-message">
                                    📦 This customer has no orders yet.
                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

</body>

</html>