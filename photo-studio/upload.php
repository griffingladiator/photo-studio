<?php

session_start();

require_once "config/database.php";

// User must be logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";
$uploadedPhoto = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_FILES["photo"])) {

        $message = "Please select a photo.";

    } else {

        $file = $_FILES["photo"];

        // Check upload error
        if ($file["error"] !== UPLOAD_ERR_OK) {

            $message = "There was a problem uploading your photo.";

        } else {

            $allowedTypes = [
                "image/jpeg",
                "image/png",
                "image/webp"
            ];

            // Validate file type
            if (!in_array($file["type"], $allowedTypes)) {

                $message = "Only JPG, PNG and WEBP images are allowed.";

            // 10 MB limit
            } elseif ($file["size"] > 10 * 1024 * 1024) {

                $message = "Maximum file size is 10 MB.";

            } else {

                $extension = strtolower(
                    pathinfo(
                        $file["name"],
                        PATHINFO_EXTENSION
                    )
                );

                $newFilename =
                    uniqid("photo_", true)
                    . "."
                    . $extension;

                $uploadDirectory = "uploads/";

                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0777, true);
                }

                $filePath =
                    $uploadDirectory
                    . $newFilename;

                if (move_uploaded_file(
                    $file["tmp_name"],
                    $filePath
                )) {

                    // Save information in database
                    $stmt = $conn->prepare(
                        "INSERT INTO photos
                        (user_id, original_name, filename, filepath)
                        VALUES (?, ?, ?, ?)"
                    );

                    $stmt->bind_param(
                        "isss",
                        $_SESSION["user_id"],
                        $file["name"],
                        $newFilename,
                        $filePath
                    );

                    if ($stmt->execute()) {

                        $uploadedPhoto = [
                            "id" => $stmt->insert_id,
                            "name" => $file["name"],
                            "path" => $filePath
                        ];

                    } else {

                        $message = "Photo database entry failed.";
                    }

                    $stmt->close();

                } else {

                    $message = "Unable to save uploaded photo.";
                }
            }
        }
    }
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

    <title>Dashboard | Fast Foto Colour Lab</title>

    <link
        rel="stylesheet"
        href="css/upload.css"
    >

</head>

<body>

<header class="studio-header">

    <a href="dashboard.php" class="logo">
        <span>F</span>
        Fast Foto Colour Lab
    </a>

    <div class="user-info">

        <span>
            Hi,
            <?php
            echo htmlspecialchars(
                $_SESSION["user_name"]
            );
            ?>
        </span>

        <a href="logout.php">
            Logout
        </a>

    </div>

</header>


<main class="upload-page">

    <div class="page-heading">

        <div>
            <div class="eyebrow">
                STEP 01
            </div>

            <h1>
                Upload your
                <em>photo.</em>
            </h1>

            <p>
                Choose a high-quality image to begin your printing order.
            </p>
        </div>

    </div>


    <?php if (!empty($message)): ?>

        <div class="upload-message">
            <?php
            echo htmlspecialchars($message);
            ?>
        </div>

    <?php endif; ?>


    <?php if ($uploadedPhoto === null): ?>

        <form
            method="POST"
            enctype="multipart/form-data"
            class="upload-form"
        >

            <label
                for="photo"
                class="drop-zone"
                id="dropZone"
            >

                <div class="upload-icon">
                    ↑
                </div>

                <h2>
                    Drop your photo here
                </h2>

                <p>
                    or click to browse from your device
                </p>

                <span>
                    JPG · PNG · WEBP · Maximum 10 MB
                </span>

                <input
                    type="file"
                    id="photo"
                    name="photo"
                    accept="image/jpeg,image/png,image/webp"
                    hidden
                    required
                >

            </label>


            <div
                class="selected-file"
                id="selectedFile"
            ></div>


            <button
                type="submit"
                class="upload-button"
            >
                Continue with this photo →
            </button>

        </form>

    <?php else: ?>

        <section class="photo-preview-section">

            <div class="preview-card">

                <div class="preview-label">
                    PHOTO UPLOADED ✓
                </div>

                <div class="image-wrapper">

                    <img
                        src="<?php
                        echo htmlspecialchars(
                            $uploadedPhoto["path"]
                        );
                        ?>"
                        alt="Uploaded photo"
                    >

                </div>

                <div class="photo-name">

                    <?php
                    echo htmlspecialchars(
                        $uploadedPhoto["name"]
                    );
                    ?>

                </div>

            </div>


            <div class="continue-card">

                <div class="eyebrow">
                    READY TO CUSTOMIZE
                </div>

                <h2>
                    Great shot! 📸
                </h2>

                <p>
                    Your photo has been securely uploaded.
                    Now let's choose how you'd like it printed.
                </p>

                <a
                    href="editor.php?photo_id=<?php
                    echo $uploadedPhoto["id"];
                    ?>"
                    class="upload-button"
                >
                    Customize Print →
                </a>

                <a
                    href="upload.php"
                    class="back-link"
                >
                    Upload another photo
                </a>

            </div>

        </section>

    <?php endif; ?>

</main>


<script src="js/upload.js"></script>

</body>
</html>