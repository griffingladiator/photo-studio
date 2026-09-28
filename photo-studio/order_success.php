<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


$order_id =
    intval($_GET["order_id"] ?? 0);

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
        Order Successful - Fast Foto Colour Lab
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

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

        }

        .success-box {

            width: 90%;

            max-width: 550px;

            text-align: center;

            background: #151522;

            border: 1px solid #29293b;

            border-radius: 20px;

            padding: 50px 30px;

        }

        .check {

            width: 80px;

            height: 80px;

            margin: auto;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #166534;

            font-size: 40px;

        }

        h1 {

            margin-top: 25px;

        }

        p {

            color: #aaa;

            line-height: 1.6;

        }

        .order-number {

            margin: 25px 0;

            padding: 18px;

            background: #202033;

            border-radius: 10px;

        }

        .order-number strong {

            display: block;

            color: #67e8f9;

            font-size: 25px;

            margin-top: 8px;

        }

        .buttons {

            display: flex;

            gap: 12px;

            justify-content: center;

            margin-top: 25px;

        }

        .buttons a {

            padding: 13px 20px;

            border-radius: 8px;

            text-decoration: none;

            color: white;

            background: #7c3aed;

        }

        .buttons a.secondary {

            background: #25253a;

        }

    </style>

</head>


<body>


<div class="success-box">

    <div class="check">
        ✓
    </div>


    <h1>
        Order Placed Successfully!
    </h1>


    <p>
        Thank you for choosing
        Fast Foto Colour Lab.
        Your photo printing order has been received.
    </p>


    <div class="order-number">

        Order Number

        <strong>
            #<?php echo $order_id; ?>
        </strong>

    </div>


    <p>
        Your order is currently
        <strong>Pending</strong>.
    </p>


    <div class="buttons">

        <a href="dashboard.php">
            Go to Dashboard
        </a>

        <a
            href="upload.php"
            class="secondary"
        >
            Print More Photos
        </a>

    </div>

</div>


</body>

</html>