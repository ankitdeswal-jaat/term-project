<?php
session_start();

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        !isset($_POST["csrf_token"]) ||
        $_POST["csrf_token"] !== $_SESSION["csrf_token"]
    ) {
        die("Invalid form submission.");
    }

    $name = trim($_POST["name"]);
    $desc = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $stock = intval($_POST["stock"]);
    $category = trim($_POST["category"]);

    $imageFileName = null;

    if (!empty($_FILES["image"]["name"])) {
        $uploadDir = __DIR__ . "/../assets/images/products/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedExt = ["jpg", "jpeg", "png", "webp"];
        $extension = strtolower(
            pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
        );

        if (!in_array($extension, $allowedExt)) {
            $message =
                "<div class='error'>Invalid image format. Only JPG, PNG, WEBP allowed.</div>";
        } else {
            $fileName = time() . "_" . uniqid() . "." . $extension;

            if (
                move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    $uploadDir . $fileName
                )
            ) {
                $imageFileName = $fileName;
            } else {
                $message = "<div class='error'>Failed to upload image.</div>";
            }
        }
    }

    if (empty($message)) {
        $stmt = $conn->prepare("
            INSERT INTO products (name, description, price, category, stock, image_url, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            "ssdsis",
            $name,
            $desc,
            $price,
            $category,
            $stock,
            $imageFileName
        );

        if ($stmt->execute()) {
            unset($_SESSION["csrf_token"]);
            header("Location: products.php?success=added");
            exit();
        } else {
            $message = "<div class='error'>Failed to add product.</div>";
        }
    }
}

$page_title = "Add New Product - Thakran Electronics";
include __DIR__ . "/../includes/header.php";
?>
<style>
    .admin-container {
        max-width: 1500px;
        margin: 3rem auto;
        padding: 0 1.25rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: var(--font-instrument);
        font-size: 2rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .error {
        background: #f8d7da;
        border: 1px solid #f5c2c7;
        color: #842029;
        padding: 0.75rem 1rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
    }

    .add-card {
        background: var(--bg-secondary);
        padding: 2rem;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        margin-top: 1rem;
    }

    .form-container {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 2.5rem;
    }

    @media (max-width: 900px) {
        .form-container {
            grid-template-columns: 1fr;
        }
    }

    .form-label {
        display: block;
        font-family: var(--font-inter);
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .input,
    textarea,
    select {
        font-size: var(--font-inter);
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        margin-bottom: 1.2rem;
    }

    textarea {
        resize: vertical;
        min-height: 120px;
    }

    .right-column {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .current-image-wrapper {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 1rem;
    }

    .current-image {
        width: 100%;
        height: 320px;
        object-fit: contain;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: var(--radius);
    }

    .upload-wrapper {
        margin-top: 0;
    }

    .upload-box {
        font-family: var(--font-inter);
        border: 2px dashed #ccc;
        background: #fafafa;
        padding: 1rem;
        text-align: center;
        border-radius: var(--radius);
        cursor: pointer;
        transition: 0.25s;
        display: block;
    }

    .upload-box:hover {
        border-color: var(--button-color);
        background: #f0f0f0;
    }

    .upload-box p {
        margin: 0 0 0.25rem 0;
        font-weight: 600;
        color: var(--text-primary);
    }

    .upload-box span {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .file-input {
        display: none;
    }

    .btn {
        padding: 0.85rem 1.5rem;
        border-radius: var(--radius);
        font-weight: 600;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.25s;
    }

    .btn-save {
        background: var(--button-color);
        color: white;
        width: 100%;
        margin-top: auto;
    }

    .btn-save:hover {
        opacity: 0.9;
    }
</style>

<script src="https://unpkg.com/lucide@latest"></script>

<section>
    <div class="admin-container">

        <div class="page-header">
            <h1 class="page-title">Add New Product</h1>
        </div>

        <?= $message ?>

        <div class="add-card">

            <form method="POST" enctype="multipart/form-data" class="form-container">

                <input type="hidden" name="csrf_token" value="<?= $_SESSION[
                    "csrf_token"
                ] ?>">

                <div>
                    <div class="form-group">
                        <label class="form-label required">Product Name</label>
                        <input class="input" type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Category</label>
                        <select class="input" name="category" required>
                            <option value="">Select a category</option>
                            <option value="Monitor">Monitor</option>
                            <option value="Mouse">Mouse</option>
                            <option value="Keyboard">Keyboard</option>
                            <option value="Graphics Card">Graphics Card</option>
                            <option value="Laptop">Laptop</option>
                            <option value="Memory">Memory</option>
                            <option value="Storage">Storage</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="input" name="description"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Price ($)</label>
                        <input class="input" type="number" step="0.01" name="price" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Stock Quantity</label>
                        <input class="input" type="number" name="stock" value="0" required>
                    </div>
                </div>

                <div class="right-column">

                    <div class="current-image-wrapper">
                        <label class="form-label">Current Image</label>
                        <img src="/thakran-electronics/assets/images/icons/no-image.png" id="previewImg" class="current-image">
                    </div>

                    <div class="upload-wrapper">
                        <label class="form-label">Upload Image</label>

                        <label class="upload-box" id="uploadBox">
                            <p>Click to choose image</p>
                            <span>No file selected</span>
                            <input type="file" name="image" accept="image/*" class="file-input" id="imgInput">
                        </label>
                    </div>

                    <button class="btn btn-save" type="submit">Save Product</button>

                </div>

            </form>
        </div>
    </div>
</section>

<script>
    lucide.createIcons();

    const imgInput = document.getElementById("imgInput");
    const previewImg = document.getElementById("previewImg");
    const uploadBox = document.getElementById("uploadBox");

    imgInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
            };
            reader.readAsDataURL(file);

            uploadBox.querySelector("p").textContent = file.name;
            uploadBox.querySelector("span").textContent = "Click to change image";
        }
    });
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
