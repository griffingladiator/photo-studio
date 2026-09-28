<?php

require_once "config/security.php";
require_once "config/database.php";

apply_security_headers();

require_login();

$user_id =
    (int)$_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT
        name,
        email,
        phone,
        address
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result =
    $stmt->get_result();

$user =
    $result->fetch_assoc();

$stmt->close();

if (!$user) {
    header("Location: logout.php");
    exit;
}

?>
<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];

/* Get current user information */
$stmt = $conn->prepare("
    SELECT name, email, phone, address
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

if (!$user) {
    die("User account not found.");
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

    <title>Checkout - Fast Foto Colour Lab</title>

    <link
        rel="stylesheet"
        href="css/checkout.css"
    >

</head>

<body>

<header>

    <div class="navbar">

        <div class="logo">
            📸 Fast Foto Colour Lab
        </div>

        <nav>

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="upload.php">
                Print Photos
            </a>

            <a href="cart.php">
                Cart
            </a>

            <a href="orders.php">
                My Orders
            </a>

            <a href="logout.php">
                Logout
            </a>

        </nav>

    </div>

</header>


<main class="checkout-container">

    <div class="checkout-header">

        <h1>
            Checkout
        </h1>

        <p>
            Complete your details and place your photo print order.
        </p>

    </div>


    <div class="checkout-grid">


        <!-- CUSTOMER DETAILS -->

        <section class="checkout-card">

            <h2>
                📍 Delivery Details
            </h2>

            <p class="card-description">
                Where should we deliver your printed photos?
            </p>


            <form id="checkoutForm">


                <div class="form-group">

                    <label for="customerName">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="customerName"
                        value="<?php echo htmlspecialchars($user["name"] ?? ""); ?>"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label for="customerEmail">
                        Email
                    </label>

                    <input
                        type="email"
                        id="customerEmail"
                        value="<?php echo htmlspecialchars($user["email"] ?? ""); ?>"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label for="phone">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        id="phone"
                        value="<?php echo htmlspecialchars($user["phone"] ?? ""); ?>"
                        placeholder="Enter phone number"
                    >

                </div>


                <div class="form-group">

                    <label for="delivery_address">
                        Delivery Address
                        <span class="required">*</span>
                    </label>

                    <textarea
                        id="delivery_address"
                        name="delivery_address"
                        rows="5"
                        placeholder="Enter your complete delivery address"
                        required
                    ><?php echo htmlspecialchars($user["address"] ?? ""); ?></textarea>

                    <small>
                        Please enter your complete address including
                        street, area, city and PIN code.
                    </small>

                </div>


                <!-- PAYMENT -->

                <div class="payment-section">

                    <h3>
                        💳 Payment Method
                    </h3>

                    <div class="payment-option">

                        <input
                            type="radio"
                            id="cod"
                            name="payment_method"
                            value="cod"
                            checked
                        >

                        <label for="cod">

                            <strong>
                                Cash on Delivery / Pay at Store
                            </strong>

                            <span>
                                Payment will be collected when
                                the order is delivered or picked up.
                            </span>

                        </label>

                    </div>

                </div>


                <div
                    id="checkoutMessage"
                    class="checkout-message"
                ></div>


                <button
                    type="submit"
                    id="placeOrderButton"
                    class="place-order-button"
                >

                    <span>
                        Place Order
                    </span>

                    <span>
                        →
                    </span>

                </button>


            </form>

        </section>


        <!-- ORDER SUMMARY -->

        <aside class="checkout-card summary-card">

            <h2>
                🛒 Order Summary
            </h2>

            <div id="cartItems">

                Loading cart...

            </div>


            <div class="summary-total">

                <span>
                    Total
                </span>

                <strong id="cartTotal">
                    ₹0.00
                </strong>

            </div>

        </aside>


    </div>

</main>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const checkoutForm =
        document.getElementById("checkoutForm");

    const addressInput =
        document.getElementById("delivery_address");

    const message =
        document.getElementById("checkoutMessage");

    const button =
        document.getElementById("placeOrderButton");

    const cartItems =
        document.getElementById("cartItems");

    const cartTotal =
        document.getElementById("cartTotal");


    /* =====================================
       LOAD CART
       ===================================== */

    let cart = [];

    try {

        cart =
            JSON.parse(
                localStorage.getItem("photoCart")
            ) || [];

    } catch (error) {

        cart = [];

    }


    if (!Array.isArray(cart) || cart.length === 0) {

        cartItems.innerHTML = `
            <div class="empty-cart">
                Your cart is empty.
            </div>
        `;

        button.disabled = true;

        button.style.opacity = "0.5";

        return;
    }


    /* =====================================
       DISPLAY CART
       ===================================== */

    let total = 0;

    let html = "";


    cart.forEach(function (item) {

        const price =
            Number(item.price) || 0;

        const quantity =
            Number(item.quantity) || 1;

        const itemTotal =
            price * quantity;

        total += itemTotal;


        html += `

            <div class="summary-item">

                <div class="item-info">

                    <strong>
                        ${escapeHtml(
                            item.product_name || "Photo Print"
                        )}
                    </strong>

                    <span>
                        Quantity: ${quantity}
                    </span>

                </div>

                <strong>
                    ₹${itemTotal.toFixed(2)}
                </strong>

            </div>

        `;

    });


    cartItems.innerHTML = html;

    cartTotal.textContent =
        "₹" + total.toFixed(2);


    /* =====================================
       PLACE ORDER
       ===================================== */

    checkoutForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            /*
             * IMPORTANT:
             * Read the textarea using its exact ID.
             */

            const deliveryAddress =
                addressInput.value.trim();


            /* Validate address */

            if (deliveryAddress.length === 0) {

                showMessage(
                    "Delivery address is required.",
                    "error"
                );

                addressInput.focus();

                return;
            }


            if (deliveryAddress.length < 5) {

                showMessage(
                    "Please enter a complete delivery address.",
                    "error"
                );

                addressInput.focus();

                return;
            }


            /* Disable button */

            button.disabled = true;

            button.style.opacity = "0.7";

            button.querySelector(
                "span:first-child"
            ).textContent =
                "Placing Order...";


            /*
             * Send the SAME field name expected
             * by save_order.php.
             */

            fetch("save_order.php", {

                method: "POST",

                headers: {
                    "Content-Type":
                        "application/json"
                },

                body: JSON.stringify({

                    cart: cart,

                    delivery_address:
                        deliveryAddress

                })

            })

            .then(function (response) {

                return response.json();

            })

            .then(function (data) {

                if (data.success) {

                    /*
                     * Clear cart only after
                     * successful order creation.
                     */

                    localStorage.removeItem(
                        "photoCart"
                    );


                    window.location.href =
                        "order_success.php?order_id=" +
                        encodeURIComponent(
                            data.order_id
                        );

                } else {

                    showMessage(
                        data.message ||
                        "Unable to place order.",
                        "error"
                    );

                    resetButton();
                }

            })

            .catch(function (error) {

                console.error(error);

                showMessage(
                    "Something went wrong while placing the order.",
                    "error"
                );

                resetButton();

            });

        }
    );


    /* =====================================
       MESSAGE
       ===================================== */

    function showMessage(
        text,
        type
    ) {

        message.textContent = text;

        message.className =
            "checkout-message " + type;

    }


    /* =====================================
       RESET BUTTON
       ===================================== */

    function resetButton() {

        button.disabled = false;

        button.style.opacity = "1";

        button.querySelector(
            "span:first-child"
        ).textContent =
            "Place Order";

    }


    /* =====================================
       HTML ESCAPE
       ===================================== */

    function escapeHtml(value) {

        return String(value)

            .replace(/&/g, "&amp;")

            .replace(/</g, "&lt;")

            .replace(/>/g, "&gt;")

            .replace(/"/g, "&quot;")

            .replace(/'/g, "&#039;");
    }

});

</script>

</body>
</html> 