<?php
ob_start();
session_start();

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";

$page_title = "Edit Product - Thakran Electronics";
include __DIR__ . "/../includes/header.php";

$id = (int) ($_GET["id"] ?? 0);

$stmt = $conn->prepare("SELECT * FROM products WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $desc = trim($_POST["description"]);
    $price = (float) $_POST["price"];
    $category = trim($_POST["category"]);
    $stock = (int) $_POST["stock"];

    $imageName = $product["image_url"];

    if (!empty($_FILES["image"]["name"])) {
        $uploadDir = __DIR__ . "/../assets/images/products/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowed = ["jpg", "jpeg", "png", "webp"];
        $ext = strtolower(
            pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
        );

        if (!in_array($ext, $allowed)) {
            $message =
                "<div class='error'>Invalid file format. Only JPG, PNG, WEBP allowed.</div>";
        } else {
            $newImage = time() . "_" . uniqid() . "." . $ext;

            if (
                move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    $uploadDir . $newImage
                )
            ) {
                if (
                    !empty($product["image_url"]) &&
                    file_exists($uploadDir . $product["image_url"])
                ) {
                    @unlink($uploadDir . $product["image_url"]);
                }

                $imageName = $newImage;
            } else {
                $message = "<div class='error'>Failed to upload image.</div>";
            }
        }
    }

    if ($message === "") {
        $update = $conn->prepare("
            UPDATE products
            SET name=?, description=?, price=?, category=?, stock=?, image_url=?
            WHERE id=?
        ");

        $update->bind_param(
            "ssdsisi",
            $name,
            $desc,
            $price,
            $category,
            $stock,
            $imageName,
            $id
        );

        if ($update->execute()) {
            header("Location: products.php?success=updated");
            exit();
        } else {
            $message = "<div class='error'>Failed to update product.</div>";
        }
    }
}
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

    .edit-card {
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
        font-family: var(--font-inter);
        font-size: 0.95rem;
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        margin-bottom: 1.2rem;
        color: var(--text-primary);
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

    .btn-save {
        background: var(--button-color);
        color: white;
        padding: 0.85rem 1.5rem;
        border-radius: var(--radius);
        font-weight: 600;
        width: 100%;
        border: none;
        cursor: pointer;
        font-family: var(--font-inter);
        margin-top: auto;
        transition: 0.25s;
    }

    .btn-save:hover {
        opacity: 0.9;
    }
</style>

<script src="https://unpkg.com/lucide@latest"></script>

<section>
    <div class="admin-container">

        <div class="page-header">
            <h1 class="page-title">Edit Product</h1>
        </div>

        <?= $message ?>

        <div class="edit-card">

            <form method="POST" enctype="multipart/form-data" class="form-container">

                <div>
                    <label class="form-label">Product Name</label>
                    <input class="input" type="text" name="name" value="<?= htmlspecialchars(
                        $product["name"]
                    ) ?>" required>

                    <label class="form-label">Category</label>
                    <select class="input" name="category" required>
                        <?php
                        $categories = [
                            "Monitor",
                            "Mouse",
                            "Keyboard",
                            "Graphics Card",
                            "Laptop",
                            "Memory",
                            "Storage",
                        ];
                        foreach ($categories as $cat): ?>
                            <option value="<?= $cat ?>" <?= $product[
    "category"
] == $cat
    ? "selected"
    : "" ?>>
                                <?= $cat ?>
                            </option>
                        <?php endforeach;
                        ?>
                    </select>

                    <label class="form-label">Description</label>
                    <textarea class="input" name="description"><?= htmlspecialchars(
                        $product["description"]
                    ) ?></textarea>

                    <label class="form-label">Price ($)</label>
                    <input class="input" type="number" step="0.01" name="price" value="<?= $product[
                        "price"
                    ] ?>" required>

                    <label class="form-label">Stock</label>
                    <input class="input" type="number" name="stock" value="<?= $product[
                        "stock"
                    ] ?>" required>
                </div>

                <div class="right-column">

                    <div class="current-image-wrapper">
                        <label class="form-label">Current Image</label>
                        <img src="/thakran-electronics/assets/images/products/<?= htmlspecialchars(
                            $product["image_url"]
                        ) ?>"
                            id="previewImg" class="current-image">
                    </div>

                    <div class="upload-wrapper">
                        <label class="form-label">Upload New Image</label>

                        <label class="upload-box" id="uploadBox">
                            <p>Click to choose image</p>
                            <input type="file" name="image" accept="image/*" class="file-input" id="imgInput">
                        </label>
                    </div>

                    <button class="btn-save" type="submit">Save Changes</button>

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
            previewImg.src = URL.createObjectURL(file);
            uploadBox.querySelector("p").textContent = file.name;
            uploadBox.querySelector("span").textContent = "Click to change image";
        }
    });
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>

<?php ob_end_flush(); ?>
