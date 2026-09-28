<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

apply_security_headers();


/*
|--------------------------------------------------------------------------
| MESSAGE COUNTS
|--------------------------------------------------------------------------
*/

$total_messages = 0;
$new_messages = 0;
$read_messages = 0;


$result = $conn->query(
    "SELECT COUNT(*) AS total FROM contact_messages"
);

if ($result) {

    $row = $result->fetch_assoc();

    $total_messages =
        (int)($row["total"] ?? 0);
}


$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM contact_messages
     WHERE status = 'new'"
);

if ($result) {

    $row = $result->fetch_assoc();

    $new_messages =
        (int)($row["total"] ?? 0);
}


$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM contact_messages
     WHERE status = 'read'"
);

if ($result) {

    $row = $result->fetch_assoc();

    $read_messages =
        (int)($row["total"] ?? 0);
}


/*
|--------------------------------------------------------------------------
| GET MESSAGES
|--------------------------------------------------------------------------
*/

$messages = [];

$result = $conn->query(
    "SELECT
        id,
        name,
        email,
        subject,
        message,
        status,
        created_at
     FROM contact_messages
     ORDER BY
        CASE
            WHEN status = 'new' THEN 0
            ELSE 1
        END,
        created_at DESC"
);

if ($result) {

    while ($row = $result->fetch_assoc()) {

        $messages[] = $row;
    }
}


/*
|--------------------------------------------------------------------------
| ADMIN NAME
|--------------------------------------------------------------------------
*/

$admin_name =
    $_SESSION["user_name"]
    ?? "Administrator";

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
        Messages | Fast Foto Colour Lab
    </title>

    <link
        rel="stylesheet"
        href="css/admin.css"
    >

    <style>

        .messages-page {
            padding: 30px;
        }

        .messages-header {
            margin-bottom: 25px;
        }

        .messages-header h1 {
            margin: 0;
        }

        .messages-header p {
            color: #94a3b8;
            margin-top: 8px;
        }

        .message-stats {
            display: grid;
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .message-stat {
            padding: 24px;
            border-radius: 20px;
            background:
                rgba(255,255,255,0.06);
            border:
                1px solid
                rgba(255,255,255,0.10);
            backdrop-filter: blur(18px);
            box-shadow:
                0 15px 40px
                rgba(0,0,0,0.20);
        }

        .message-stat span {
            display: block;
            color: #94a3b8;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .message-stat strong {
            font-size: 30px;
        }

        .messages-card {
            overflow: hidden;
            border-radius: 24px;
            background:
                rgba(255,255,255,0.06);
            border:
                1px solid
                rgba(255,255,255,0.10);
            backdrop-filter: blur(18px);
            box-shadow:
                0 25px 70px
                rgba(0,0,0,0.25);
        }

        .messages-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .messages-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        .messages-table th {
            padding: 18px;
            text-align: left;
            color: #94a3b8;
            font-size: 13px;
            border-bottom:
                1px solid
                rgba(255,255,255,0.08);
        }

        .messages-table td {
            padding: 18px;
            border-bottom:
                1px solid
                rgba(255,255,255,0.06);
            vertical-align: middle;
        }

        .messages-table tr:hover {
            background:
                rgba(255,255,255,0.035);
        }

        .sender-name {
            font-weight: 700;
            color: #ffffff;
        }

        .sender-email {
            margin-top: 4px;
            color: #94a3b8;
            font-size: 12px;
        }

        .message-subject {
            font-weight: 600;
            color: #e2e8f0;
        }

        .message-preview {
            max-width: 300px;
            margin-top: 5px;
            color: #94a3b8;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .message-status {
            display: inline-flex;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .message-status.new {
            color: #fef3c7;
            background:
                rgba(245,158,11,0.16);
            border:
                1px solid
                rgba(245,158,11,0.25);
        }

        .message-status.read {
            color: #bbf7d0;
            background:
                rgba(34,197,94,0.13);
            border:
                1px solid
                rgba(34,197,94,0.20);
        }

        .view-message {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 14px;
            border-radius: 12px;
            color: #ffffff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            background:
                linear-gradient(
                    135deg,
                    #6366f1,
                    #8b5cf6
                );
            box-shadow:
                0 5px 0 #312e81;
            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }

        .view-message:hover {
            transform: translateY(-2px);
            box-shadow:
                0 7px 0 #312e81;
        }

        .empty-messages {
            padding: 70px 30px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        @media (max-width: 800px) {

            .messages-page {
                padding: 20px;
            }

            .message-stats {
                grid-template-columns: 1fr;
            }
        }

    </style>

</head>

<body>


<!--
|--------------------------------------------------------------------------
| ADMIN NAVIGATION
|--------------------------------------------------------------------------
-->

<header class="admin-header">

    <div class="admin-brand">

        <a href="dashboard.php">

            <strong>
                Fast Foto
            </strong>

            <span>
                Colour Lab
            </span>

        </a>

    </div>


    <nav class="admin-nav">

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

        <a
            href="messages.php"
            class="active"
        >
            Messages
        </a>

        <a href="../logout.php">
            Logout
        </a>

    </nav>

</header>


<main class="messages-page">


    <div class="messages-header">

        <h1>
            Customer Messages
        </h1>

        <p>
            View and manage enquiries sent through the Fast Foto
            Colour Lab contact form.
        </p>

    </div>


    <!-- MESSAGE STATISTICS -->

    <div class="message-stats">


        <div class="message-stat">

            <span>
                Total Messages
            </span>

            <strong>
                <?php echo $total_messages; ?>
            </strong>

        </div>


        <div class="message-stat">

            <span>
                New Messages
            </span>

            <strong>
                <?php echo $new_messages; ?>
            </strong>

        </div>


        <div class="message-stat">

            <span>
                Read Messages
            </span>

            <strong>
                <?php echo $read_messages; ?>
            </strong>

        </div>

    </div>


    <!-- MESSAGES -->

    <div class="messages-card">


        <?php if (empty($messages)): ?>

            <div class="empty-messages">

                <div class="empty-icon">
                    💬
                </div>

                <h2>
                    No Messages Yet
                </h2>

                <p>
                    Customer enquiries submitted through the
                    Contact page will appear here.
                </p>

            </div>

        <?php else: ?>


            <div class="messages-table-wrapper">

                <table class="messages-table">

                    <thead>

                        <tr>

                            <th>
                                Customer
                            </th>

                            <th>
                                Subject
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($messages as $item): ?>

                            <tr>


                                <td>

                                    <div class="sender-name">

                                        <?php
                                        echo e($item["name"]);
                                        ?>

                                    </div>

                                    <div class="sender-email">

                                        <?php
                                        echo e($item["email"]);
                                        ?>

                                    </div>

                                </td>


                                <td>

                                    <div class="message-subject">

                                        <?php
                                        echo e($item["subject"]);
                                        ?>

                                    </div>

                                    <div class="message-preview">

                                        <?php
                                        echo e($item["message"]);
                                        ?>

                                    </div>

                                </td>


                                <td>

                                    <span
                                        class="message-status <?php echo e($item["status"]); ?>"
                                    >

                                        <?php
                                        echo ucfirst(
                                            $item["status"]
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <?php
                                    echo date(
                                        "d M Y, h:i A",
                                        strtotime(
                                            $item["created_at"]
                                        )
                                    );
                                    ?>

                                </td>


                                <td>

                                    <a
                                        href="message_details.php?id=<?php echo (int)$item["id"]; ?>"
                                        class="view-message"
                                    >
                                        View →
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</main>

</body>

</html>