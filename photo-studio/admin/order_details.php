<?php

require_once "auth.php";
require_once "../config/database.php";

apply_security_headers();

$order_id = isset($_GET["id"])
    ? (int)$_GET["id"]
    : 0;

if ($order_id <= 0) {
    header("Location: orders.php");
    exit;
}

$success_message = "";
$error_message = "";

/*
|--------------------------------------------------------------------------
| Messages
|--------------------------------------------------------------------------
*/

if (
    isset($_GET["success"]) &&
    $_GET["success"] === "updated"
) {
    $success_message =
        "Order and payment status updated successfully!";
}

if (
    isset($_GET["error"]) &&
    $_GET["error"] === "invalid_status"
) {
    $error_message =
        "Invalid order or payment status.";
}

if (
    isset($_GET["error"]) &&
    $_GET["error"] === "update_failed"
) {
    $error_message =
        "Unable to update the order.";
}

/*
|--------------------------------------------------------------------------
| Get Order
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        o.id,
        o.user_id,
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

    WHERE o.id = ?

    LIMIT 1
");

if (!$stmt) {
    die("Unable to prepare order query.");
}

$stmt->bind_param(
    "i",
    $order_id
);

$stmt->execute();

$result = $stmt->get_result();

$order = $result->fetch_assoc();

$stmt->close();

if (!$order) {
    header("Location: orders.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Order Items
|--------------------------------------------------------------------------
*/

$item_stmt = $conn->prepare("
    SELECT
        oi.id,
        oi.quantity,
        oi.price,

        pp.size,
        pp.paper_type,

        p.original_name,
        p.filepath

    FROM order_items oi

    INNER JOIN print_products pp
        ON oi.product_id = pp.id

    INNER JOIN photos p
        ON oi.photo_id = p.id

    WHERE oi.order_id = ?

    ORDER BY oi.id ASC
");

if (!$item_stmt) {
    die("Unable to prepare order item query.");
}

$item_stmt->bind_param(
    "i",
    $order_id
);

$item_stmt->execute();

$items_result =
    $item_stmt->get_result();

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
        Order #<?php echo $order_id; ?>
        - Fast Foto Colour Lab
    </title>

    <link
        rel="stylesheet"
        href="css/admin.css"
    >

    <style>

        /*
        |--------------------------------------------------------------------------
        | Page
        |--------------------------------------------------------------------------
        */

        .order-page {
            max-width: 1250px;
            margin: 0 auto;
            padding: 30px 20px 60px;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .page-heading h1 {
            margin: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Alerts
        |--------------------------------------------------------------------------
        */

        .alert {
            padding: 15px 20px;
            border-radius: 14px;
            margin-bottom: 22px;
            font-weight: 700;
        }

        .alert.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /*
        |--------------------------------------------------------------------------
        | Cards
        |--------------------------------------------------------------------------
        */

        .order-card {
            background:
                rgba(255, 255, 255, 0.95);

            border:
                1px solid
                rgba(148, 163, 184, 0.25);

            border-radius: 22px;

            padding: 25px;

            margin-bottom: 25px;

            box-shadow:
                0 15px 40px
                rgba(15, 23, 42, 0.10);

            overflow: hidden;
        }

        .order-card h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        /*
        |--------------------------------------------------------------------------
        | Information Grid
        |--------------------------------------------------------------------------
        */

        .info-grid {
            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(220px, 1fr)
                );

            gap: 18px;
        }

        .info-box {
            padding: 18px;

            border-radius: 15px;

            background:
                #f8fafc;

            border:
                1px solid
                #e2e8f0;
        }

        .info-label {
            display: block;

            font-size: 13px;

            color: #64748b;

            margin-bottom: 6px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .04em;
        }

        .info-value {
            color: #0f172a;

            font-size: 16px;

            font-weight: 700;

            word-break: break-word;
        }

        /*
        |--------------------------------------------------------------------------
        | Status Badges
        |--------------------------------------------------------------------------
        */

        .status-badge {
            display: inline-flex;

            align-items: center;

            padding: 7px 13px;

            border-radius: 999px;

            font-size: 13px;

            font-weight: 800;

            text-transform: capitalize;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-printing {
            background: #e0e7ff;
            color: #4338ca;
        }

        .status-ready {
            background: #cffafe;
            color: #155e75;
        }

        .status-delivered {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Section
        |--------------------------------------------------------------------------
        */

        .update-panel {
            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(250px, 1fr)
                );

            gap: 22px;

            align-items: end;
        }

        .field-group label {
            display: block;

            margin-bottom: 8px;

            color: #334155;

            font-size: 14px;

            font-weight: 800;
        }

        .field-group select {
            width: 100%;

            padding: 13px 15px;

            border:
                1px solid
                #cbd5e1;

            border-radius: 13px;

            background: white;

            color: #0f172a;

            font-size: 15px;

            font-weight: 700;

            outline: none;

            cursor: pointer;

            transition:
                border-color .2s ease,
                box-shadow .2s ease;
        }

        .field-group select:focus {
            border-color: #6366f1;

            box-shadow:
                0 0 0 4px
                rgba(99,102,241,.12);
        }

        /*
        |--------------------------------------------------------------------------
        | 3D Update Button
        |--------------------------------------------------------------------------
        */

        .update-button {
            width: 100%;

            min-height: 50px;

            border: 0;

            border-radius: 15px;

            padding: 13px 22px;

            color: white;

            font-size: 15px;

            font-weight: 900;

            cursor: pointer;

            background:
                linear-gradient(
                    135deg,
                    #6366f1,
                    #8b5cf6,
                    #06b6d4
                );

            box-shadow:
                0 7px 0 #312e81,
                0 17px 35px
                rgba(99,102,241,.35),

                inset 0 1px 0
                rgba(255,255,255,.40);

            transition:
                transform .2s ease,
                box-shadow .2s ease,
                filter .2s ease;
        }

        .update-button:hover {
            transform:
                translateY(-4px);

            filter:
                brightness(1.1);

            box-shadow:
                0 11px 0 #312e81,

                0 25px 45px
                rgba(99,102,241,.45),

                inset 0 1px 0
                rgba(255,255,255,.50);
        }

        .update-button:active {
            transform:
                translateY(3px);

            box-shadow:
                0 3px 0 #312e81,

                0 8px 18px
                rgba(99,102,241,.30);
        }

        /*
        |--------------------------------------------------------------------------
        | Order Items
        |--------------------------------------------------------------------------
        */

        .item-list {
            display: flex;

            flex-direction: column;

            gap: 15px;
        }

        .order-item {
            display: grid;

            grid-template-columns:
                90px
                1fr
                auto;

            align-items: center;

            gap: 18px;

            padding: 16px;

            border:
                1px solid
                #e2e8f0;

            border-radius: 16px;

            background:
                #f8fafc;
        }

        .order-photo {
            width: 90px;
            height: 90px;

            object-fit: cover;

            border-radius: 14px;

            display: block;

            box-shadow:
                0 8px 20px
                rgba(15,23,42,.15);
        }

        .item-info h3 {
            margin:
                0 0 7px;

            color: #0f172a;
        }

        .item-info p {
            margin:
                4px 0;

            color: #64748b;

            font-size: 14px;
        }

        .item-price {
            text-align: right;

            font-size: 17px;

            font-weight: 900;

            color: #0f172a;
        }

        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        .total-box {
            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 15px;

            margin-top: 20px;

            padding-top: 20px;

            border-top:
                2px solid
                #e2e8f0;
        }

        .total-label {
            font-size: 18px;

            font-weight: 800;
        }

        .total-value {
            font-size: 25px;

            font-weight: 900;

            color: #4f46e5;
        }

        /*
        |--------------------------------------------------------------------------
        | Back Button
        |--------------------------------------------------------------------------
        */

        .back-button {
            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 11px 18px;

            border-radius: 13px;

            color: #334155;

            text-decoration: none;

            font-weight: 800;

            background:
                #f1f5f9;

            border:
                1px solid
                #cbd5e1;

            transition:
                .2s ease;
        }

        .back-button:hover {
            transform:
                translateY(-2px);

            background:
                #e2e8f0;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        @media (max-width: 700px) {

            .order-page {
                padding:
                    20px 14px 40px;
            }

            .order-card {
                padding: 18px;
            }

            .order-item {
                grid-template-columns:
                    70px 1fr;

            }

            .order-photo {
                width: 70px;
                height: 70px;
            }

            .item-price {
                grid-column:
                    2;

                text-align: left;
            }

            .total-box {
                flex-direction:
                    column;

                align-items:
                    flex-start;
            }
        }

    </style>

</head>

<body>

<header class="admin-header">

    <div class="admin-navbar">

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

    </div>

</header>

<main class="order-page">

    <div class="page-heading">

        <div>

            <h1>
                📦 Order #<?php echo $order_id; ?>
            </h1>

            <p>
                Manage order and payment status
            </p>

        </div>

        <a
            href="orders.php"
            class="back-button"
        >
            ← Back to Orders
        </a>

    </div>

    <?php if ($success_message !== ""): ?>

        <div class="alert success">
            ✓
            <?php echo e($success_message); ?>
        </div>

    <?php endif; ?>

    <?php if ($error_message !== ""): ?>

        <div class="alert error">
            ✕
            <?php echo e($error_message); ?>
        </div>

    <?php endif; ?>

    <!--
    |--------------------------------------------------------------------------
    | Customer Information
    |--------------------------------------------------------------------------
    -->

    <section class="order-card">

        <h2>
            👤 Customer Information
        </h2>

        <div class="info-grid">

            <div class="info-box">

                <span class="info-label">
                    Name
                </span>

                <span class="info-value">
                    <?php
                    echo e(
                        $order["customer_name"]
                    );
                    ?>
                </span>

            </div>

            <div class="info-box">

                <span class="info-label">
                    Email
                </span>

                <span class="info-value">
                    <?php
                    echo e(
                        $order["customer_email"]
                    );
                    ?>
                </span>

            </div>

            <div class="info-box">

                <span class="info-label">
                    Phone
                </span>

                <span class="info-value">
                    <?php
                    echo e(
                        $order["customer_phone"]
                    );
                    ?>
                </span>

            </div>

            <div class="info-box">

                <span class="info-label">
                    Order Date
                </span>

                <span class="info-value">
                    <?php
                    echo e(
                        $order["created_at"]
                    );
                    ?>
                </span>

            </div>

        </div>

        <div
            class="info-box"
            style="margin-top:18px;"
        >

            <span class="info-label">
                Delivery Address
            </span>

            <span class="info-value">
                <?php
                echo nl2br(
                    e(
                        $order["delivery_address"]
                    )
                );
                ?>
            </span>

        </div>

    </section>

    <!--
    |--------------------------------------------------------------------------
    | Current Status
    |--------------------------------------------------------------------------
    -->

    <section class="order-card">

        <h2>
            📊 Current Status
        </h2>

        <div class="info-grid">

            <div class="info-box">

                <span class="info-label">
                    Order Status
                </span>

                <span
                    class="
                        status-badge
                        status-<?php
                        echo e(
                            $order["order_status"]
                        );
                        ?>
                    "
                >
                    <?php
                    echo e(
                        ucfirst(
                            $order["order_status"]
                        )
                    );
                    ?>
                </span>

            </div>

            <div class="info-box">

                <span class="info-label">
                    Payment Status
                </span>

                <span
                    class="
                        status-badge
                        status-<?php
                        echo e(
                            $order["payment_status"]
                        );
                        ?>
                    "
                >
                    <?php
                    echo e(
                        ucfirst(
                            $order["payment_status"]
                        )
                    );
                    ?>
                </span>

            </div>

            <div class="info-box">

                <span class="info-label">
                    Order Total
                </span>

                <span class="info-value">
                    ₹<?php
                    echo number_format(
                        (float)$order["total_amount"],
                        2
                    );
                    ?>
                </span>

            </div>

        </div>

    </section>

    <!--
    |--------------------------------------------------------------------------
    | Update Order
    |--------------------------------------------------------------------------
    -->

    <section class="order-card">

        <h2>
            ⚙️ Update Order
        </h2>

        <form
            method="POST"
            action="update_order.php"
        >

            <input
                type="hidden"
                name="csrf_token"
                value="<?php
                echo e(
                    csrf_token()
                );
                ?>"
            >

            <input
                type="hidden"
                name="order_id"
                value="<?php
                echo $order_id;
                ?>"
            >

            <div class="update-panel">

                <!-- Order Status -->

                <div class="field-group">

                    <label for="order_status">
                        📦 Order Status
                    </label>

                    <select
                        id="order_status"
                        name="order_status"
                        required
                    >

                        <?php

                        $order_statuses = [
                            "pending",
                            "confirmed",
                            "printing",
                            "ready",
                            "delivered",
                            "cancelled"
                        ];

                        foreach (
                            $order_statuses
                            as $status
                        ):

                        ?>

                            <option
                                value="<?php
                                echo e($status);
                                ?>"
                                <?php
                                echo (
                                    $order["order_status"]
                                    === $status
                                )
                                    ? "selected"
                                    : "";
                                ?>
                            >
                                <?php
                                echo e(
                                    ucfirst($status)
                                );
                                ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Payment Status -->

                <div class="field-group">

                    <label for="payment_status">
                        💳 Payment Status
                    </label>

                    <select
                        id="payment_status"
                        name="payment_status"
                        required
                    >

                        <?php

                        $payment_statuses = [
                            "pending",
                            "paid",
                            "failed"
                        ];

                        foreach (
                            $payment_statuses
                            as $status
                        ):

                        ?>

                            <option
                                value="<?php
                                echo e($status);
                                ?>"
                                <?php
                                echo (
                                    $order["payment_status"]
                                    === $status
                                )
                                    ? "selected"
                                    : "";
                                ?>
                            >
                                <?php
                                echo e(
                                    ucfirst($status)
                                );
                                ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Button -->

                <div>

                    <button
                        type="submit"
                        class="update-button"
                    >
                        💾 Update Status
                    </button>

                </div>

            </div>

        </form>

    </section>

    <!--
    |--------------------------------------------------------------------------
    | Order Items
    |--------------------------------------------------------------------------
    -->

    <section class="order-card">

        <h2>
            🖼️ Order Items
        </h2>

        <div class="item-list">

            <?php
            while (
                $item =
                $items_result->fetch_assoc()
            ):
            ?>

                <div class="order-item">

                    <img
                        src="../<?php
                        echo e(
                            $item["filepath"]
                        );
                        ?>"
                        alt="Order Photo"
                        class="order-photo"
                    >

                    <div class="item-info">

                        <h3>
                            <?php
                            echo e(
                                $item["size"]
                            );
                            ?>
                        </h3>

                        <p>
                            <strong>
                                Paper:
                            </strong>

                            <?php
                            echo e(
                                $item["paper_type"]
                            );
                            ?>
                        </p>

                        <p>
                            <strong>
                                Original File:
                            </strong>

                            <?php
                            echo e(
                                $item["original_name"]
                            );
                            ?>
                        </p>

                        <p>
                            <strong>
                                Quantity:
                            </strong>

                            <?php
                            echo (int)
                                $item["quantity"];
                            ?>
                        </p>

                    </div>

                    <div class="item-price">

                        ₹<?php
                        echo number_format(
                            (float)$item["price"] *
                            (int)$item["quantity"],
                            2
                        );
                        ?>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

        <div class="total-box">

            <div class="total-label">
                Total Order Amount
            </div>

            <div class="total-value">
                ₹<?php
                echo number_format(
                    (float)$order["total_amount"],
                    2
                );
                ?>
            </div>

        </div>

    </section>

</main>

</body>

</html>

<?php

$item_stmt->close();

?>