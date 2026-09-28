<?php

require_once "auth.php";
require_once "../config/database.php";

$error_message = "";

$size = "";
$paper_type = "";
$price = "";
$description = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $size = trim($_POST["size"] ?? "");
    $paper_type = trim($_POST["paper_type"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $description = trim($_POST["description"] ?? "");


    /* Validation */

    if ($size === "" || $paper_type === "" || $price === "") {

        $error_message = "Please fill in all required fields.";

    } elseif (!is_numeric($price) || (float)$price <= 0) {

        $error_message = "Please enter a valid price.";

    } else {

        $price_value = (float)$price;


        /* Insert product */

        $stmt = $conn->prepare("
            INSERT INTO print_products
            (size, paper_type, price, description, status)
            VALUES (?, ?, ?, ?, 'active')
        ");

        $stmt->bind_param(
            "ssds",
            $size,
            $paper_type,
            $price_value,
            $description
        );


        if ($stmt->execute()) {

            header("Location: products.php?success=added");
            exit;

        } else {

            $error_message = "Unable to add product.";

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product - Fast Foto Colour Lab</title>

    <link rel="stylesheet" href="css/admin.css">

    <style>

        .form-card {
            max-width: 700px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .save-btn {
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            background: #111827;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .cancel-btn {
            padding: 12px 20px;
            border-radius: 8px;
            background: #e5e7eb;
            color: #111827;
            text-decoration: none;
            font-weight: 600;
        }

        .error-message {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
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

        <a href="dashboard.php">📊 Dashboard</a>

        <a href="orders.php">📦 Manage Orders</a>

        <a href="customers.php">👥 Customers</a>

        <a href="products.php" class="active">
            🖨️ Print Products
        </a>

        <a href="../logout.php">🚪 Logout</a>

    </aside>


    <main class="admin-container">

        <a href="products.php"
           style="display:inline-block;margin-bottom:20px;text-decoration:none;color:#374151;font-weight:600;">
            ← Back to Products
        </a>


        <h1>Add Print Product</h1>

        <p style="margin-bottom:25px;">
            Add a new photo printing option.
        </p>


        <?php if ($error_message): ?>

            <div class="error-message">
                ✕ <?php echo htmlspecialchars($error_message); ?>
            </div>

        <?php endif; ?>


        <div class="form-card">

            <form method="POST">


                <div class="form-group">

                    <label for="size">
                        Photo Size *
                    </label>

                    <input
                        type="text"
                        id="size"
                        name="size"
                        placeholder="Example: 4 × 6 inch"
                        value="<?php echo htmlspecialchars($size); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="paper_type">
                        Paper Type *
                    </label>

                    <select
                        id="paper_type"
                        name="paper_type"
                        required
                    >

                        <option value="">
                            Select paper type
                        </option>

                        <option value="Glossy"
                            <?php echo $paper_type === "Glossy" ? "selected" : ""; ?>>
                            Glossy
                        </option>

                        <option value="Matte"
                            <?php echo $paper_type === "Matte" ? "selected" : ""; ?>>
                            Matte
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="price">
                        Price (₹) *
                    </label>

                    <input
                        type="number"
                        id="price"
                        name="price"
                        min="0.01"
                        step="0.01"
                        placeholder="Example: 10"
                        value="<?php echo htmlspecialchars($price); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        placeholder="Enter product description..."
                    ><?php echo htmlspecialchars($description); ?></textarea>

                </div>


                <div class="form-actions">

                    <button type="submit" class="save-btn">
                        ✓ Add Product
                    </button>

                    <a href="products.php" class="cancel-btn">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </main>

</div>

</body>

</html>