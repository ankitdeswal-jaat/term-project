<?php
session_start();
require_once __DIR__ . "/config/db.php";
$message = "";
$page_title = "Register - Thakran Electronics";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirm_password = trim($_POST["confirm_password"] ?? "");

    if (
        $name === "" ||
        $email === "" ||
        $password === "" ||
        $confirm_password === ""
    ) {
        $message = '<div class="error">All fields are required!</div>';
    } elseif ($password !== $confirm_password) {
        $message = '<div class="error">Passwords do not match!</div>';
    } else {
        $stmt = $conn->prepare(
            "SELECT user_id FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = '<div class="error">Email already exists!</div>';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare(
                "INSERT INTO users (name, email, password, is_admin, created_at) VALUES (?, ?, ?, 0, NOW())"
            );
            $insert->bind_param("sss", $name, $email, $hash);

            if ($insert->execute()) {
                $message =
                    '<div class="success">Registration successful! <a href="login.php">Login</a></div>';
            } else {
                $message = '<div class="error">Something went wrong!</div>';
            }
            $insert->close();
        }
        $stmt->close();
    }
}

include __DIR__ . "/includes/header.php";
?>

<script src="https://unpkg.com/lucide@latest"></script>

<style>
    .container {
        max-width: 1500px !important;
        margin: 0 auto;
    }

    .register-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .register-wrap {
        font-family: "Inter", sans-serif;
        width: 390px;
        margin: 5rem auto;
        background: #f5f5f5;
        padding: 2.5rem;
        border-radius: 14px;
        border: 1px solid #ccc;
        text-align: center;
    }

    .register-wrap h2 {
        font-family: "Instrument Serif", serif;
        margin-bottom: 2rem;
        font-size: 1.8rem;
    }

    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
        text-align: left;
    }

    .input-group input {
        font-family: var(--font-inter);
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 0.9rem;
        background: #fff;
    }

    .input-group svg {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        stroke: #666;
    }

    .register-wrap button {
        width: 100%;
        padding: 14px;
        font-size: 1.05rem;
        background: #0056b3;
        color: #fff;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: .5rem;
    }

    .register-wrap button:hover {
        background: #004a99;
    }

    .register-wrap button svg {
        width: 18px;
        height: 18px;
    }

    .error {
        background: #ffd6d6;
        border: 1px solid #ff9393;
        color: #8b0000;
        padding: 14px;
        border-radius: 10px;
        margin-bottom: 1.2rem;
        font-size: .9rem;
        text-align: center;
    }

    .success {
        background: #d6ffd6;
        border: 1px solid #93ff93;
        color: #006b00;
        padding: 14px;
        border-radius: 10px;
        margin-bottom: 1.2rem;
        font-size: .9rem;
        text-align: center;
    }

    .register-wrap p {
        margin-top: 2rem;
        font-size: 1rem;
    }

    .register-wrap a {
        color: #0056b3;
        font-weight: 600;
        text-decoration: none;
    }

    .register-wrap a:hover {
        text-decoration: underline;
    }
</style>

<main class="container">
    <section class="register-container">

        <div class="register-wrap">
            <h2>Register Account</h2>

            <?= $message ?>

            <form method="POST">

                <div class="input-group">
                    <svg data-lucide="user"></svg>
                    <input type="text" name="name" placeholder="Full Name" required>
                </div>

                <div class="input-group">
                    <svg data-lucide="mail"></svg>
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>

                <div class="input-group">
                    <svg data-lucide="lock"></svg>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
                <div class="input-group">
                    <svg data-lucide="lock"></svg>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>

                <button type="submit"><i data-lucide="user-plus"></i> Register</button>
            </form>

            <p>Already have an account?<a href="login.php"> Login </a></p>
        </div>
    </section>
</main>

<script>
    lucide.createIcons();
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
