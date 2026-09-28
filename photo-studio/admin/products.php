<?php

require_once "auth.php";
require_once "../config/database.php";

$success_message = "";
$error_message = "";

if (isset($_GET["success"])) {

    if ($_GET["success"] === "added") {
        $success_message = "Print product added successfully!";
    }

    if ($_GET["success"] === "updated") {
        $success_message = "Print product updated successfully!";
    }

    if ($_GET["success"] === "toggled") {
        $success_message = "Product status updated successfully!";
    }
}

if (isset($_GET["error"])) {

    if ($_GET["error"] === "delete_failed") {
        $error_message = "Unable to update product status.";
    }
}


/* Fetch products */

$stmt = $conn->prepare("
    SELECT
        id,
        size,
        paper_type,
        price,
        description,
        status,
        created_at
    FROM print_products
    ORDER BY created_at DESC
");

$stmt->execute();

$products = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Print Products - Fast Foto Colour Lab</title>

    <link rel="stylesheet" href="css/admin.css">

    <style>

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .add-btn {
            display: inline-block;
            padding: 12px 18px;
            background: #111827;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }

        .add-btn:hover {
            opacity: 0.9;
        }

        .product-size {
            font-weight: 700;
        }

        .paper-badge {
            display: inline-block;
            padding: 5px 10px;
            background: #f3f4f6;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .price {
            font-weight: 700;
        }

        .description {
            max-width: 250px;
            color: #6b7280;
            font-size: 13px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .edit-btn,
        .toggle-btn {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .edit-btn {
            background: #e0e7ff;
            color: #3730a3;
        }

        .toggle-btn {
            background: #f3f4f6;
            color: #374151;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        @media (max-width: 768px) {

            .page-header {
                align-items: stretch;
            }

            .add-btn {
                text-align: center;
            }

        }

    </style>

</head>

<body>

<header class="admin-header">

    <div class="admin-logo">
        📸 Fast Foto Colour Lab
    </div>

    <div class="admin-user">
        Welcome,
        <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
    </div>

</header>


<div class="admin-layout">

    <aside class="admin-sidebar">

        <a href="dashboard.php">
            📊 Dashboard
        </a>

        <a href="orders.php">
            📦 Manage Orders
        </a>

        <a href="customers.php">
            👥 Customers
        </a>

        <a href="products.php" class="active">
            🖨️ Print Products
        </a>

        <a href="../logout.php">
            🚪 Logout
        </a>

    </aside>


    <main class="admin-container">

        <?php if ($success_message): ?>

            <div class="alert success">
                ✓ <?php echo htmlspecialchars($success_message); ?>
            </div>

        <?php endif; ?>


        <?php if ($error_message): ?>

            <div class="alert error">
                ✕ <?php echo htmlspecialchars($error_message); ?>
            </div>

        <?php endif; ?>


        <div class="page-header">

            <div>

                <h1>Print Products</h1>

                <p>
                    Manage photo sizes, paper types and prices.
                </p>

            </div>

            <a href="add_product.php" class="add-btn">
                + Add Product
            </a>

        </div>


        <div class="admin-card">

            <div class="table-responsive">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>Size</th>
                            <th>Paper Type</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if ($products->num_rows > 0): ?>

                        <?php while ($product = $products->fetch_assoc()): ?>

                            <tr>

                                <td>

                                    <div class="product-size">
                                        <?php
                                        echo htmlspecialchars(
                                            $product["size"]
                                        );
                                        ?>
                                    </div>

                                </td>


                                <td>

                                    <span class="paper-badge">

                                        <?php
                                        echo htmlspecialchars(
                                            $product["paper_type"]
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <span class="price">

                                        ₹<?php
                                        echo number_format(
                                            (float)$product["price"],
                                            2
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="description">

                                        <?php
                                        echo $product["description"]
                                            ? htmlspecialchars(
                                                $product["description"]
                                            )
                                            : "—";
                                        ?>

                                    </div>

                                </td>


                                <td>

                                    <span class="status-badge status-<?php echo htmlspecialchars($product["status"]); ?>">

                                        <?php
                                        echo ucfirst(
                                            htmlspecialchars(
                                                $product["status"]
                                            )
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="edit_product.php?id=<?php echo (int)$product["id"]; ?>"
                                            class="edit-btn"
                                        >
                                            ✏️ Edit
                                        </a>


                                        <?php if ($product["status"] === "active"): ?>

                                            <a
                                                href="toggle_product.php?id=<?php echo (int)$product["id"]; ?>"
                                                class="toggle-btn"
                                                onclick="return confirm('Deactivate this product?');"
                                            >
                                                Disable
                                            </a>

                                        <?php else: ?>

                                            <a
                                                href="toggle_product.php?id=<?php echo (int)$product["id"]; ?>"
                                                class="toggle-btn"
                                            >
                                                Enable
                                            </a>

                                        <?php endif; ?>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="6">

                                <div style="text-align:center;padding:40px;color:#6b7280;">
                                    🖨️ No print products found.
                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

</body>

</html>