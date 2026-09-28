<?php

session_start();

require_once "config/database.php";


if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


$user_id = $_SESSION["user_id"];


$name =
    trim($_POST["name"] ?? "");

$email =
    trim($_POST["email"] ?? "");

$phone =
    trim($_POST["phone"] ?? "");

$address =
    trim($_POST["address"] ?? "");


if (
    empty($name) ||
    empty($email) ||
    empty($phone) ||
    empty($address)
) {

    die("Please fill all required fields.");

}


/*
    Cart comes from browser localStorage.
    The browser sends the cart to PHP
    using a temporary JavaScript request.
*/

?>

<!DOCTYPE html>

<html>

<head>

    <title>
        Processing Order
    </title>

</head>

<body>

<script>

const cart =
    JSON.parse(
        localStorage.getItem("photoCart")
    ) || [];


if (cart.length === 0) {

    alert("Your cart is empty.");

    window.location.href =
        "cart.php";

} else {

    fetch("save_order.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify({

            name:
                <?php echo json_encode($name); ?>,

            email:
                <?php echo json_encode($email); ?>,

            phone:
                <?php echo json_encode($phone); ?>,

            address:
                <?php echo json_encode($address); ?>,

            cart: cart

        })

    })

    .then(response =>
        response.json()
    )

    .then(data => {

        if (data.success) {

            localStorage.removeItem(
                "photoCart"
            );

            window.location.href =
                "order_success.php?order_id="
                + data.order_id;

        } else {

            alert(
                data.message ||
                "Unable to place order."
            );

            window.location.href =
                "cart.php";

        }

    })

    .catch(error => {

        console.error(error);

        alert(
            "Something went wrong."
        );

        window.location.href =
            "cart.php";

    });

}

</script>

</body>

</html>