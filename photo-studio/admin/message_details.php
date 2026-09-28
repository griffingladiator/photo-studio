<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

apply_security_headers();


/*
|--------------------------------------------------------------------------
| GET MESSAGE ID
|--------------------------------------------------------------------------
*/

$id = $_GET["id"] ?? 0;

if (!valid_id($id)) {

    header("Location: messages.php");
    exit;
}

$id = (int)$id;


/*
|--------------------------------------------------------------------------
| FETCH MESSAGE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT
        id,
        name,
        email,
        subject,
        message,
        status,
        created_at
     FROM contact_messages
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$result = $stmt->get_result();

$message = $result->fetch_assoc();

$stmt->close();


if (!$message) {

    header("Location: messages.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| MARK AS READ
|--------------------------------------------------------------------------
*/

if ($message["status"] === "new") {

    $update = $conn->prepare(
        "UPDATE contact_messages
         SET status = 'read'
         WHERE id = ?"
    );

    if ($update) {

        $update->bind_param(
            "i",
            $id
        );

        $update->execute();

        $update->close();

        $message["status"] = "read";
    }
}


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
        Message Details | Fast Foto Colour Lab
    </title>

    <link
        rel="stylesheet"
        href="css/admin.css"
    >

    <style>

        .message-details-page {
            padding: 30px;
            max-width: 1000px;
            margin: auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
            color: #a5b4fc;
            text-decoration: none;
            font-weight: 700;
        }

        .message-details-card {
            padding: 35px;
            border-radius: 25px;
            background:
                rgba(255,255,255,0.06);
            border:
                1px solid
                rgba(255,255,255,0.10);
            backdrop-filter: blur(20px);
            box-shadow:
                0 25px 70px
                rgba(0,0,0,0.25);
        }

        .message-title {
            margin: 0;
            font-size: 30px;
        }

        .message-meta {
            display: grid;
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
            gap: 15px;
            margin-top: 25px;
        }

        .meta-box {
            padding: 17px;
            border-radius: 15px;
            background:
                rgba(255,255,255,0.045);
            border:
                1px solid
                rgba(255,255,255,0.08);
        }

        .meta-label {
            display: block;
            margin-bottom: 6px;
            color: #94a3b8;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .meta-value {
            color: #ffffff;
            font-weight: 600;
            word-break: break-word;
        }

        .message-content {
            margin-top: 25px;
            padding: 25px;
            border-radius: 18px;
            background:
                rgba(0,0,0,0.15);
            border:
                1px solid
                rgba(255,255,255,0.08);
        }

        .message-content h3 {
            margin-top: 0;
        }

        .message-text {
            color: #cbd5e1;
            line-height: 1.8;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .message-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: 13px;
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            background:
                linear-gradient(
                    135deg,
                    #6366f1,
                    #8b5cf6
                );
            box-shadow:
                0 5px 0 #312e81;
        }

        .email-button {
            background:
                linear-gradient(
                    135deg,
                    #0891b2,
                    #06b6d4
                );
            box-shadow:
                0 5px 0 #155e75;
        }

        @media (max-width: 650px) {

            .message-details-page {
                padding: 20px;
            }

            .message-details-card {
                padding: 25px 20px;
            }

            .message-meta {
                grid-template-columns: 1fr;
            }
        }

    </style>

</head>

<body>


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


<main class="message-details-page">


    <a
        href="messages.php"
        class="back-link"
    >
        ← Back to Messages
    </a>


    <div class="message-details-card">


        <h1 class="message-title">

            <?php
            echo e($message["subject"]);
            ?>

        </h1>


        <div class="message-meta">


            <div class="meta-box">

                <span class="meta-label">
                    Customer
                </span>

                <div class="meta-value">

                    <?php
                    echo e($message["name"]);
                    ?>

                </div>

            </div>


            <div class="meta-box">

                <span class="meta-label">
                    Email
                </span>

                <div class="meta-value">

                    <?php
                    echo e($message["email"]);
                    ?>

                </div>

            </div>


            <div class="meta-box">

                <span class="meta-label">
                    Status
                </span>

                <div class="meta-value">

                    <?php
                    echo ucfirst(
                        e($message["status"])
                    );
                    ?>

                </div>

            </div>


            <div class="meta-box">

                <span class="meta-label">
                    Received
                </span>

                <div class="meta-value">

                    <?php
                    echo date(
                        "d M Y, h:i A",
                        strtotime(
                            $message["created_at"]
                        )
                    );
                    ?>

                </div>

            </div>

        </div>


        <div class="message-content">

            <h3>
                Customer Message
            </h3>

            <div class="message-text">

                <?php
                echo e($message["message"]);
                ?>

            </div>

        </div>


        <div class="message-actions">


            <a
                href="mailto:<?php echo e($message["email"]); ?>?subject=<?php echo rawurlencode("Re: " . $message["subject"]); ?>"
                class="action-button email-button"
            >
                ✉ Reply by Email
            </a>


            <a
                href="messages.php"
                class="action-button"
            >
                ← Message Inbox
            </a>

        </div>

    </div>

</main>

</body>

</html>