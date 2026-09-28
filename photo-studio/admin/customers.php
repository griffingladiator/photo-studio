<?php

require_once "auth.php";
require_once "../config/database.php";

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

/* Fetch customers */
if ($search !== "") {

    $searchTerm = "%" . $search . "%";

    $stmt = $conn->prepare("
        SELECT
            u.id,
            u.name,
            u.email,
            u.phone,
            u.address,
            u.created_at,
            COUNT(o.id) AS total_orders,
            COALESCE(SUM(
                CASE
                    WHEN o.payment_status = 'paid'
                    THEN o.total_amount
                    ELSE 0
                END
            ), 0) AS total_spent
        FROM users u
        LEFT JOIN orders o ON u.id = o.user_id
        WHERE u.role = 'customer'
        AND (
            u.name LIKE ?
            OR u.email LIKE ?
            OR u.phone LIKE ?
        )
        GROUP BY
            u.id,
            u.name,
            u.email,
            u.phone,
            u.address,
            u.created_at
        ORDER BY u.created_at DESC
    ");

    $stmt->bind_param(
        "sss",
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

} else {

    $stmt = $conn->prepare("
        SELECT
            u.id,
            u.name,
            u.email,
            u.phone,
            u.address,
            u.created_at,
            COUNT(o.id) AS total_orders,
            COALESCE(SUM(
                CASE
                    WHEN o.payment_status = 'paid'
                    THEN o.total_amount
                    ELSE 0
                END
            ), 0) AS total_spent
        FROM users u
        LEFT JOIN orders o ON u.id = o.user_id
        WHERE u.role = 'customer'
        GROUP BY
            u.id,
            u.name,
            u.email,
            u.phone,
            u.address,
            u.created_at
        ORDER BY u.created_at DESC
    ");
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customers - Fast Foto Colour Lab</title>

    <link rel="stylesheet" href="css/admin.css">

    <style>

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .search-box input {
            flex: 1;
            min-width: 250px;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
        }

        .search-box button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            background: #111827;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .search-box button:hover {
            opacity: 0.9;
        }

        .clear-btn {
            padding: 12px 20px;
            border-radius: 8px;
            background: #e5e7eb;
            color: #111827;
            text-decoration: none;
            font-weight: 600;
        }

        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #374151;
        }

        .customer-name {
            font-weight: 600;
        }

        .customer-email {
            font-size: 13px;
            color: #6b7280;
            margin-top: 3px;
        }

        .view-btn {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 7px;
            background: #111827;
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .view-btn:hover {
            opacity: 0.9;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }

        @media (max-width: 768px) {

            .search-box {
                flex-direction: column;
            }

            .search-box input {
                min-width: 100%;
            }

            .search-box button,
            .clear-btn {
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
            Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
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

            <a href="customers.php" class="active">
                👥 Customers
            </a>

            <a href="products.php">
                🖨️ Print Products
            </a>

            <a href="../logout.php">
                🚪 Logout
            </a>

        </aside>


        <main class="admin-container">

            <div class="page-header">

                <div>
                    <h1>Customers</h1>

                    <p>
                        View and manage registered customers.
                    </p>
                </div>

            </div>


            <!-- Search -->

            <form method="GET" class="search-box">

                <input
                    type="text"
                    name="search"
                    placeholder="Search by name, email or phone..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >

                <button type="submit">
                    🔍 Search
                </button>

                <?php if ($search !== ""): ?>

                    <a href="customers.php" class="clear-btn">
                        Clear
                    </a>

                <?php endif; ?>

            </form>


            <!-- Customer Table -->

            <div class="admin-card">

                <div class="table-responsive">

                    <table class="admin-table">

                        <thead>

                            <tr>

                                <th>Customer</th>

                                <th>Phone</th>

                                <th>Orders</th>

                                <th>Total Spent</th>

                                <th>Joined</th>

                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if ($result->num_rows > 0): ?>

                                <?php while ($customer = $result->fetch_assoc()): ?>

                                    <?php
                                    $initial = strtoupper(
                                        substr($customer["name"], 0, 1)
                                    );
                                    ?>

                                    <tr>

                                        <td>

                                            <div style="display:flex; align-items:center; gap:12px;">

                                                <div class="customer-avatar">
                                                    <?php echo htmlspecialchars($initial); ?>
                                                </div>

                                                <div>

                                                    <div class="customer-name">
                                                        <?php
                                                        echo htmlspecialchars(
                                                            $customer["name"]
                                                        );
                                                        ?>
                                                    </div>

                                                    <div class="customer-email">
                                                        <?php
                                                        echo htmlspecialchars(
                                                            $customer["email"]
                                                        );
                                                        ?>
                                                    </div>

                                                </div>

                                            </div>

                                        </td>


                                        <td>

                                            <?php
                                            echo $customer["phone"]
                                                ? htmlspecialchars($customer["phone"])
                                                : "—";
                                            ?>

                                        </td>


                                        <td>

                                            <strong>
                                                <?php
                                                echo (int)$customer["total_orders"];
                                                ?>
                                            </strong>

                                        </td>


                                        <td>

                                            ₹<?php
                                            echo number_format(
                                                (float)$customer["total_spent"],
                                                2
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <?php
                                            echo date(
                                                "d M Y",
                                                strtotime($customer["created_at"])
                                            );
                                            ?>

                                        </td>


                                        <td>

                                            <a
                                                href="customer_details.php?id=<?php echo (int)$customer["id"]; ?>"
                                                class="view-btn"
                                            >
                                                View
                                            </a>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="6">

                                        <div class="empty-message">

                                            👥 No customers found.

                                            <?php if ($search !== ""): ?>

                                                <br>
                                                Try a different search.

                                            <?php endif; ?>

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