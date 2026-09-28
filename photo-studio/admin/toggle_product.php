<?php

require_once "auth.php";
require_once "../config/database.php";

if (
    !isset($_GET["id"]) ||
    !filter_var($_GET["id"], FILTER_VALIDATE_INT)
) {
    header("Location: products.php");
    exit;
}

$product_id = (int)$_GET["id"];


/* Get current status */

$stmt = $conn->prepare("
    SELECT status
    FROM print_products
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $product_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: products.php");
    exit;
}

$product = $result->fetch_assoc();


/* Change status */

$new_status =
    $product["status"] === "active"
    ? "inactive"
    : "active";


$update = $conn->prepare("
    UPDATE print_products
    SET status = ?
    WHERE id = ?
");

$update->bind_param(
    "si",
    $new_status,
    $product_id
);


if ($update->execute()) {

    header("Location: products.php?success=toggled");
    exit;

}


header("Location: products.php?error=delete_failed");
exit;