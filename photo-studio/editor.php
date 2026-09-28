<?php
session_start();

require_once "config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$photo_id = isset($_GET["photo_id"]) ? intval($_GET["photo_id"]) : 0;

if ($photo_id <= 0) {
    die("Invalid photo.");
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT * FROM photos WHERE id = ? AND user_id = ?"
);

$stmt->bind_param("ii", $photo_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$photo = $result->fetch_assoc();

if (!$photo) {
    die("Photo not found.");
}

$product_result = $conn->query(
    "SELECT * FROM print_products
     WHERE status = 'active'
     ORDER BY size, paper_type"
);

$products = [];

while ($row = $product_result->fetch_assoc()) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customize Photo - Fast Foto Colour Lab</title>

    <link rel="stylesheet" href="css/editor.css">
</head>

<body>

<header class="editor-header">

    <div class="brand">
        <div class="brand-icon">📸</div>

        <div>
            <h1>Fast Foto Colour Lab</h1>
            <span>Photo Printing Studio</span>
        </div>
    </div>

    <a href="upload.php" class="back-btn">
        ← Back
    </a>

</header>


<main class="editor-container">

    <div class="page-title">

        <p>STEP 02</p>

        <h2>Customize Your Photo</h2>

        <span>
            Edit your photo and select printing options
        </span>

    </div>


    <section class="editor-layout">


        <!-- LEFT SIDE -->

        <div class="editing-panel">

            <h3>✏️ Edit Photo</h3>


            <div class="control-group">

                <label>Rotation</label>

                <div class="button-row">

                    <button type="button" onclick="rotatePhoto(-90)">
                        ↶ Rotate Left
                    </button>

                    <button type="button" onclick="rotatePhoto(90)">
                        ↷ Rotate Right
                    </button>

                </div>

            </div>


            <div class="control-group">

                <label>
                    Brightness
                    <span id="brightnessValue">100%</span>
                </label>

                <input
                    type="range"
                    id="brightness"
                    min="50"
                    max="150"
                    value="100"
                    oninput="updateImage()"
                >

            </div>


            <div class="control-group">

                <label>
                    Contrast
                    <span id="contrastValue">100%</span>
                </label>

                <input
                    type="range"
                    id="contrast"
                    min="50"
                    max="150"
                    value="100"
                    oninput="updateImage()"
                >

            </div>


            <button
                type="button"
                class="reset-btn"
                onclick="resetEditor()"
            >
                Reset Changes
            </button>

        </div>



        <!-- CENTER PREVIEW -->

        <div class="preview-panel">

            <h3>🖼️ Photo Preview</h3>

            <div class="photo-preview">

                <img
                    id="photoPreview"
                    src="<?php echo htmlspecialchars($photo['filepath']); ?>"
                    alt="Uploaded Photo"
                >

            </div>

            <p class="photo-name">
                <?php echo htmlspecialchars($photo['original_name']); ?>
            </p>

        </div>



        <!-- RIGHT SIDE -->

        <div class="print-panel">

            <h3>🖨️ Printing Options</h3>


            <div class="form-group">

                <label for="product">
                    Print Size & Paper
                </label>

                <select id="product" onchange="updatePrice()">

                    <option value="">
                        Select printing option
                    </option>

                    <?php foreach ($products as $product): ?>

                        <option
                            value="<?php echo $product['id']; ?>"
                            data-price="<?php echo $product['price']; ?>"
                        >

                            <?php echo htmlspecialchars($product['size']); ?>
                            -
                            <?php echo htmlspecialchars($product['paper_type']); ?>

                            (₹<?php echo number_format($product['price'], 2); ?>)

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="form-group">

                <label>Quantity</label>

                <div class="quantity-control">

                    <button
                        type="button"
                        onclick="changeQuantity(-1)"
                    >
                        −
                    </button>

                    <input
                        type="number"
                        id="quantity"
                        value="1"
                        min="1"
                        max="100"
                        onchange="updatePrice()"
                    >

                    <button
                        type="button"
                        onclick="changeQuantity(1)"
                    >
                        +
                    </button>

                </div>

            </div>


            <div class="price-box">

                <span>Total Price</span>

                <strong id="totalPrice">
                    ₹0.00
                </strong>

            </div>


            <button
                type="button"
                class="cart-btn"
                onclick="addToCart()"
            >
                🛒 Add to Cart
            </button>


            <p id="message"></p>

        </div>

    </section>

</main>


<script>

const products = <?php echo json_encode($products); ?>;

const photoId = <?php echo $photo_id; ?>;

let rotation = 0;


function rotatePhoto(degrees) {

    rotation += degrees;

    document.getElementById("photoPreview").style.transform =
        `rotate(${rotation}deg)`;

}


function updateImage() {

    const brightness =
        document.getElementById("brightness").value;

    const contrast =
        document.getElementById("contrast").value;

    document.getElementById("brightnessValue").textContent =
        brightness + "%";

    document.getElementById("contrastValue").textContent =
        contrast + "%";

    document.getElementById("photoPreview").style.filter =
        `brightness(${brightness}%)
         contrast(${contrast}%)`;

}


function resetEditor() {

    rotation = 0;

    document.getElementById("brightness").value = 100;

    document.getElementById("contrast").value = 100;

    document.getElementById("photoPreview").style.transform =
        "rotate(0deg)";

    updateImage();

}


function changeQuantity(amount) {

    const quantityInput =
        document.getElementById("quantity");

    let quantity =
        parseInt(quantityInput.value) || 1;

    quantity += amount;

    if (quantity < 1) {
        quantity = 1;
    }

    if (quantity > 100) {
        quantity = 100;
    }

    quantityInput.value = quantity;

    updatePrice();

}


function updatePrice() {

    const select =
        document.getElementById("product");

    const selectedOption =
        select.options[select.selectedIndex];

    const price =
        parseFloat(selectedOption.dataset.price) || 0;

    const quantity =
        parseInt(document.getElementById("quantity").value) || 1;

    const total =
        price * quantity;

    document.getElementById("totalPrice").textContent =
        "₹" + total.toFixed(2);

}


function addToCart() {

    const product =
        document.getElementById("product").value;

    const quantity =
        document.getElementById("quantity").value;

    const message =
        document.getElementById("message");


    if (!product) {

        message.textContent =
            "Please select a printing option.";

        message.className = "error";

        return;

    }


    const selectedProduct =
        products.find(
            p => p.id == product
        );


    const cartItem = {

        photo_id: photoId,

        product_id: parseInt(product),

        product_name:
            selectedProduct.size +
            " - " +
            selectedProduct.paper_type,

        price:
            parseFloat(selectedProduct.price),

        quantity:
            parseInt(quantity),

        rotation:
            rotation,

        brightness:
            document.getElementById("brightness").value,

        contrast:
            document.getElementById("contrast").value

    };


    let cart =
        JSON.parse(
            localStorage.getItem("photoCart")
        ) || [];


    cart.push(cartItem);


    localStorage.setItem(
        "photoCart",
        JSON.stringify(cart)
    );


    message.innerHTML =
    '✓ Photo added to cart successfully! ' +
    '<a href="cart.php" style="color:#67e8f9;">View Cart →</a>';

message.className = "success";

}

</script>

</body>
</html>