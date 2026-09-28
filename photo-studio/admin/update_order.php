<?php

require_once "auth.php";
require_once "../config/database.php";

apply_security_headers();

/*
|--------------------------------------------------------------------------
| POST Only
|--------------------------------------------------------------------------
*/

require_post();

/*
|--------------------------------------------------------------------------
| CSRF Protection
|--------------------------------------------------------------------------
*/

require_csrf_token(
    $_POST["csrf_token"] ?? ""
);

/*
|--------------------------------------------------------------------------
| Get Values
|--------------------------------------------------------------------------
*/

$order_id = isset($_POST["order_id"])
    ? (int)$_POST["order_id"]
    : 0;

$order_status =
    trim(
        $_POST["order_status"] ?? ""
    );

$payment_status =
    trim(
        $_POST["payment_status"] ?? ""
    );

/*
|--------------------------------------------------------------------------
| Allowed Order Statuses
|--------------------------------------------------------------------------
*/

$allowed_order_statuses = [
    "pending",
    "confirmed",
    "printing",
    "ready",
    "delivered",
    "cancelled"
];

/*
|--------------------------------------------------------------------------
| Allowed Payment Statuses
|--------------------------------------------------------------------------
*/

$allowed_payment_statuses = [
    "pending",
    "paid",
    "failed"
];

/*
|--------------------------------------------------------------------------
| Validate Order ID
|--------------------------------------------------------------------------
*/

if ($order_id <= 0) {

    header(
        "Location: orders.php"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| Validate Order Status
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $order_status,
        $allowed_order_statuses,
        true
    )
) {

    header(
        "Location: order_details.php?id=" .
        $order_id .
        "&error=invalid_status"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| Validate Payment Status
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $payment_status,
        $allowed_payment_statuses,
        true
    )
) {

    header(
        "Location: order_details.php?id=" .
        $order_id .
        "&error=invalid_status"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| Make Sure Order Exists
|--------------------------------------------------------------------------
*/

$check_stmt = $conn->prepare("
    SELECT id
    FROM orders
    WHERE id = ?
    LIMIT 1
");

if (!$check_stmt) {

    header(
        "Location: order_details.php?id=" .
        $order_id .
        "&error=update_failed"
    );

    exit;
}

$check_stmt->bind_param(
    "i",
    $order_id
);

$check_stmt->execute();

$check_result =
    $check_stmt->get_result();

$order_exists =
    $check_result->num_rows > 0;

$check_stmt->close();

if (!$order_exists) {

    header(
        "Location: orders.php"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| Update Both Statuses
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    UPDATE orders
    SET
        order_status = ?,
        payment_status = ?
    WHERE id = ?
");

if (!$stmt) {

    header(
        "Location: order_details.php?id=" .
        $order_id .
        "&error=update_failed"
    );

    exit;
}

$stmt->bind_param(
    "ssi",
    $order_status,
    $payment_status,
    $order_id
);

/*
|--------------------------------------------------------------------------
| Execute
|--------------------------------------------------------------------------
*/

if ($stmt->execute()) {

    $stmt->close();

    header(
        "Location: order_details.php?id=" .
        $order_id .
        "&success=updated"
    );

    exit;
}

$stmt->close();

header(
    "Location: order_details.php?id=" .
    $order_id .
    "&error=update_failed"
);

exit;