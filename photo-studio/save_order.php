<?php

session_start();

require_once "config/database.php";

/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["user_id"])) {

    http_response_code(401);

    header("Content-Type: application/json");

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit;
}

$user_id = (int)$_SESSION["user_id"];


/*
|--------------------------------------------------------------------------
| JSON Response
|--------------------------------------------------------------------------
*/

header("Content-Type: application/json");


/*
|--------------------------------------------------------------------------
| Allow POST Only
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Read JSON Request
|--------------------------------------------------------------------------
*/

$json_data = file_get_contents("php://input");

$input = json_decode($json_data, true);


if (!is_array($input)) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid order data."
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Get Cart and Address
|--------------------------------------------------------------------------
*/

$cart = $input["cart"] ?? [];

$delivery_address = trim(
    $input["delivery_address"] ?? ""
);


/*
|--------------------------------------------------------------------------
| Validate Cart
|--------------------------------------------------------------------------
*/

if (!is_array($cart) || count($cart) === 0) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Your cart is empty."
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Validate Delivery Address
|--------------------------------------------------------------------------
*/

if ($delivery_address === "") {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Delivery address is required."
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Start Database Transaction
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();


try {

    $total_amount = 0;

    $validated_items = [];


    /*
    |--------------------------------------------------------------------------
    | Prepare Product Query
    |--------------------------------------------------------------------------
    |
    | We get the price directly from the database.
    | We NEVER trust the price sent by JavaScript.
    |
    */

    $product_stmt = $conn->prepare("
        SELECT
            id,
            price,
            status
        FROM print_products
        WHERE id = ?
        LIMIT 1
    ");

    if (!$product_stmt) {
        throw new Exception("Unable to prepare product query.");
    }


    /*
    |--------------------------------------------------------------------------
    | Prepare Photo Ownership Query
    |--------------------------------------------------------------------------
    |
    | This makes sure the photo belongs to the logged-in customer.
    |
    */

    $photo_stmt = $conn->prepare("
        SELECT
            id
        FROM photos
        WHERE id = ?
        AND user_id = ?
        LIMIT 1
    ");

    if (!$photo_stmt) {
        throw new Exception("Unable to prepare photo query.");
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Every Cart Item
    |--------------------------------------------------------------------------
    */

    foreach ($cart as $item) {

        /*
        |--------------------------------------------------------------------------
        | Get IDs and Quantity
        |--------------------------------------------------------------------------
        */

        $photo_id = filter_var(
            $item["photo_id"] ?? null,
            FILTER_VALIDATE_INT
        );

        $product_id = filter_var(
            $item["product_id"] ?? null,
            FILTER_VALIDATE_INT
        );

        $quantity = filter_var(
            $item["quantity"] ?? null,
            FILTER_VALIDATE_INT
        );


        /*
        |--------------------------------------------------------------------------
        | Validate IDs and Quantity
        |--------------------------------------------------------------------------
        */

        if (
            $photo_id === false ||
            $photo_id <= 0 ||
            $product_id === false ||
            $product_id <= 0 ||
            $quantity === false ||
            $quantity < 1 ||
            $quantity > 100
        ) {

            throw new Exception(
                "Invalid cart item."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Photo Ownership
        |--------------------------------------------------------------------------
        */

        $photo_stmt->bind_param(
            "ii",
            $photo_id,
            $user_id
        );

        $photo_stmt->execute();

        $photo_result = $photo_stmt->get_result();


        if ($photo_result->num_rows === 0) {

            throw new Exception(
                "You can only order your own uploaded photos."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Product From Database
        |--------------------------------------------------------------------------
        */

        $product_stmt->bind_param(
            "i",
            $product_id
        );

        $product_stmt->execute();

        $product_result = $product_stmt->get_result();


        if ($product_result->num_rows === 0) {

            throw new Exception(
                "Print product not found."
            );
        }


        $product = $product_result->fetch_assoc();


        /*
        |--------------------------------------------------------------------------
        | Check Product Status
        |--------------------------------------------------------------------------
        */

        if ($product["status"] !== "active") {

            throw new Exception(
                "One of the selected print products is no longer available."
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Price From Database
        |--------------------------------------------------------------------------
        |
        | The customer/browser cannot change the actual price.
        |
        */

        $price = (float)$product["price"];


        /*
        |--------------------------------------------------------------------------
        | Calculate Item Total
        |--------------------------------------------------------------------------
        */

        $item_total = $price * $quantity;


        /*
        |--------------------------------------------------------------------------
        | Add To Grand Total
        |--------------------------------------------------------------------------
        */

        $total_amount += $item_total;


        /*
        |--------------------------------------------------------------------------
        | Store Validated Item
        |--------------------------------------------------------------------------
        */

        $validated_items[] = [
            "photo_id" => $photo_id,
            "product_id" => $product_id,
            "quantity" => $quantity,
            "price" => $price
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Make Sure At Least One Item Was Validated
    |--------------------------------------------------------------------------
    */

    if (count($validated_items) === 0) {

        throw new Exception(
            "No valid items found in your cart."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Order
    |--------------------------------------------------------------------------
    */

    $order_stmt = $conn->prepare("
        INSERT INTO orders
        (
            user_id,
            total_amount,
            payment_status,
            order_status,
            delivery_address
        )
        VALUES
        (
            ?,
            ?,
            'pending',
            'pending',
            ?
        )
    ");

    if (!$order_stmt) {
        throw new Exception("Unable to prepare order query.");
    }


    $order_stmt->bind_param(
        "ids",
        $user_id,
        $total_amount,
        $delivery_address
    );


    if (!$order_stmt->execute()) {

        throw new Exception(
            "Unable to create order."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get New Order ID
    |--------------------------------------------------------------------------
    */

    $order_id = $conn->insert_id;


    /*
    |--------------------------------------------------------------------------
    | Prepare Order Items Query
    |--------------------------------------------------------------------------
    */

    $item_stmt = $conn->prepare("
        INSERT INTO order_items
        (
            order_id,
            photo_id,
            product_id,
            quantity,
            price
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?
        )
    ");

    if (!$item_stmt) {
        throw new Exception(
            "Unable to prepare order item query."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Insert Every Order Item
    |--------------------------------------------------------------------------
    */

    foreach ($validated_items as $item) {

        $item_stmt->bind_param(
            "iiiid",
            $order_id,
            $item["photo_id"],
            $item["product_id"],
            $item["quantity"],
            $item["price"]
        );


        if (!$item_stmt->execute()) {

            throw new Exception(
                "Unable to save order items."
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Commit Transaction
    |--------------------------------------------------------------------------
    */

    $conn->commit();


    /*
    |--------------------------------------------------------------------------
    | Success Response
    |--------------------------------------------------------------------------
    */

    echo json_encode([
        "success" => true,
        "message" => "Order placed successfully.",
        "order_id" => $order_id
    ]);

    exit;


} catch (Exception $e) {


    /*
    |--------------------------------------------------------------------------
    | Rollback Transaction
    |--------------------------------------------------------------------------
    */

    $conn->rollback();


    /*
    |--------------------------------------------------------------------------
    | Error Response
    |--------------------------------------------------------------------------
    */

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

    exit;
}