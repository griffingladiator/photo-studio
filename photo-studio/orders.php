<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT id, total_amount, payment_status,
            order_status, delivery_address, created_at
     FROM orders
     WHERE user_id = ?
     ORDER BY created_at DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

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
        My Orders - Fast Foto Colour Lab
    </title>

    <link
        rel="stylesheet"
        href="css/orders.css"
    >

</head>

<body>


<header class="orders-header">

    <div class="brand">

        <div class="brand-icon">
            📸
        </div>

        <div>

            <h1>
                Fast Foto Colour Lab
            </h1>

            <span>
                Photo Printing Studio
            </span>

        </div>

    </div>


    <nav>

        <a href="dashboard.php" class="active">
                🏠 Dashboard
            </a>

        <a href="upload.php">
                📸 Print Photos
            </a>

        <a href="logout.php" class="logout-link">
                🚪 Logout
            </a>

    </nav>

</header>


<main class="orders-container">


    <div class="page-title">

        <p>MY ACCOUNT</p>

        <h2>
            My Orders
        </h2>

        <span>
            View and track all your photo printing orders
        </span>

    </div>


<?php if ($result->num_rows === 0): ?>


    <div class="empty-orders">

        <div class="empty-icon">
            📦
        </div>

        <h2>
            No Orders Yet
        </h2>

        <p>
            You haven't placed any photo printing orders yet.
        </p>

        <a href="upload.php">
            Start Printing
        </a>

    </div>


<?php else: ?>


    <div class="orders-list">


    <?php while ($order = $result->fetch_assoc()): ?>


        <div class="order-card">


            <div class="order-top">

                <div>

                    <span class="order-label">
                        ORDER
                    </span>

                    <h3>
                        #<?php
                            echo $order["id"];
                        ?>
                    </h3>

                </div>


                <div class="order-date">

                    <?php
                        echo date(
                            "d M Y, h:i A",
                            strtotime(
                                $order["created_at"]
                            )
                        );
                    ?>

                </div>

            </div>


            <div class="order-info">


                <div>

                    <span>
                        Total Amount
                    </span>

                    <strong>
                        ₹<?php
                            echo number_format(
                                $order["total_amount"],
                                2
                            );
                        ?>
                    </strong>

                </div>


                <div>

                    <span>
                        Payment
                    </span>

                    <strong class="status-payment">

                        <?php
                            echo ucfirst(
                                $order["payment_status"]
                            );
                        ?>

                    </strong>

                </div>


                <div>

                    <span>
                        Order Status
                    </span>

                    <strong>

                        <?php
                            echo ucfirst(
                                $order["order_status"]
                            );
                        ?>

                    </strong>

                </div>


            </div>


            <div class="status-section">

                <div class="status-line">

                    <?php

                    $statuses = [
                        "pending",
                        "confirmed",
                        "printing",
                        "ready",
                        "delivered"
                    ];

                    $current =
                        $order["order_status"];

                    $current_index =
                        array_search(
                            $current,
                            $statuses
                        );

                    if ($current_index === false) {
                        $current_index = 0;
                    }

                    ?>


                    <?php foreach (
                        $statuses
                        as $index => $status
                    ): ?>


                        <div
                            class="status-step
                            <?php
                            if (
                                $index
                                <= $current_index
                            ) {
                                echo "active";
                            }
                            ?>"
                        >

                            <div class="status-circle">

                                <?php
                                echo $index
                                    <= $current_index
                                    ? "✓"
                                    : ($index + 1);
                                ?>

                            </div>


                            <span>

                                <?php
                                    echo ucfirst(
                                        $status
                                    );
                                ?>

                            </span>

                        </div>


                        <?php if (
                            $index < count($statuses) - 1
                        ): ?>

                            <div
                                class="status-connector
                                <?php
                                if (
                                    $index
                                    < $current_index
                                ) {
                                    echo "active";
                                }
                                ?>"
                            ></div>

                        <?php endif; ?>


                    <?php endforeach; ?>

                </div>

            </div>


            <div class="order-bottom">

                <span>

                    📍
                    <?php
                        echo htmlspecialchars(
                            $order["delivery_address"]
                        );
                    ?>

                </span>


                <a
                    href="order_details.php?id=<?php
                        echo $order["id"];
                    ?>"
                >
                    View Details →
                </a>

            </div>


        </div>


    <?php endwhile; ?>


    </div>


<?php endif; ?>


</main>

</body>

</html>