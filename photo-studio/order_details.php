<?php

session_start();

require_once "config/database.php";


if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


$user_id = $_SESSION["user_id"];

$order_id =
    intval($_GET["id"] ?? 0);


if ($order_id <= 0) {
    die("Invalid order.");
}


/*
    Get order
*/

$stmt = $conn->prepare(
    "SELECT *
     FROM orders
     WHERE id = ?
     AND user_id = ?"
);

$stmt->bind_param(
    "ii",
    $order_id,
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$order = $result->fetch_assoc();


if (!$order) {
    die("Order not found.");
}


/*
    Get order items
*/

$stmt = $conn->prepare(
    "SELECT
        oi.quantity,
        oi.price,
        p.size,
        p.paper_type,
        ph.original_name
     FROM order_items oi

     JOIN print_products p
        ON oi.product_id = p.id

     JOIN photos ph
        ON oi.photo_id = ph.id

     WHERE oi.order_id = ?"
);

$stmt->bind_param(
    "i",
    $order_id
);

$stmt->execute();

$items =
    $stmt->get_result();

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

    <style>

        * {
            box-sizing: border-box;
        }

        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #0d0d16;

            color: white;

        }

        header {

            height: 75px;

            padding: 0 5%;

            display: flex;

            justify-content: space-between;

            align-items: center;

            background: #151522;

            border-bottom: 1px solid #29293b;

        }

        header h1 {
            font-size: 20px;
        }

        header a {

            color: #ccc;

            text-decoration: none;

        }

        main {

            width: 92%;

            max-width: 900px;

            margin: auto;

            padding: 45px 0;

        }

        .card {

            background: #151522;

            border: 1px solid #29293b;

            border-radius: 16px;

            padding: 25px;

            margin-bottom: 20px;

        }

        .order-heading {

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 20px;

        }

        .order-heading h2 {
            margin: 0;
        }

        .status {

            padding: 8px 14px;

            border-radius: 20px;

            background: #312e81;

            color: #c4b5fd;

            font-size: 13px;

        }

        .item {

            display: flex;

            justify-content: space-between;

            gap: 20px;

            padding: 18px 0;

            border-bottom: 1px solid #29293b;

        }

        .item:last-child {
            border-bottom: none;
        }

        .item span {
            color: #888;
        }

        .item strong {
            display: block;
            margin-bottom: 6px;
        }

        .item-price {
            color: #67e8f9;
            white-space: nowrap;
        }

        .info-row {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 20px;

        }

        .info-box {

            padding: 18px;

            background: #202033;

            border-radius: 10px;

        }

        .info-box span {

            display: block;

            color: #777;

            font-size: 12px;

            margin-bottom: 7px;

        }

        .info-box strong {
            font-size: 15px;
        }

        .total {

            display: flex;

            justify-content: space-between;

            align-items: center;

            padding-top: 20px;

            font-size: 18px;

        }

        .total strong {

            font-size: 25px;

            color: #67e8f9;

        }

        @media (max-width: 600px) {

            .info-row {
                grid-template-columns: 1fr;
            }

            .order-heading {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    </style>

</head>

<body>


<header>

    <h1>
        📸 Fast Foto Colour Lab
    </h1>

    <a href="orders.php">
        ← My Orders
    </a>

</header>


<main>


    <div class="card">

        <div class="order-heading">

            <div>

                <small>
                    ORDER
                </small>

                <h2>
                    #<?php echo $order_id; ?>
                </h2>

            </div>


            <div class="status">

                <?php
                    echo ucfirst(
                        $order["order_status"]
                    );
                ?>

            </div>

        </div>

    </div>


    <div class="card">

        <h3>
            Printed Photos
        </h3>


        <?php while ($item = $items->fetch_assoc()): ?>

            <div class="item">

                <div>

                    <strong>
                        <?php
                            echo htmlspecialchars(
                                $item["original_name"]
                            );
                        ?>
                    </strong>

                    <span>
                        <?php
                            echo htmlspecialchars(
                                $item["size"]
                            );
                        ?>

                        -
                        <?php
                            echo htmlspecialchars(
                                $item["paper_type"]
                            );
                        ?>

                        ×
                        <?php
                            echo $item["quantity"];
                        ?>
                    </span>

                </div>


                <div class="item-price">

                    ₹<?php
                        echo number_format(
                            $item["price"]
                            * $item["quantity"],
                            2
                        );
                    ?>

                </div>

            </div>

        <?php endwhile; ?>


        <div class="total">

            <span>
                Total
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

    </div>


    <div class="card">

        <h3>
            Order Information
        </h3>

        <br>


        <div class="info-row">

            <div class="info-box">

                <span>
                    Order Date
                </span>

                <strong>

                    <?php
                        echo date(
                            "d M Y, h:i A",
                            strtotime(
                                $order["created_at"]
                            )
                        );
                    ?>

                </strong>

            </div>


            <div class="info-box">

                <span>
                    Payment Status
                </span>

                <strong>

                    <?php
                        echo ucfirst(
                            $order["payment_status"]
                        );
                    ?>

                </strong>

            </div>


            <div class="info-box">

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


            <div class="info-box">

                <span>
                    Delivery Address
                </span>

                <strong>

                    <?php
                        echo nl2br(
                            htmlspecialchars(
                                $order["delivery_address"]
                            )
                        );
                    ?>

                </strong>

            </div>

        </div>

    </div>


</main>

</body>

</html>