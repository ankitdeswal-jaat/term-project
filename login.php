<?php
session_start();
require_once __DIR__ . "/config/db.php";
$message = "";
$page_title = "Login - Thakran Electronics";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email === "" || $password === "") {
        $message = '<div class="error">All fields are required!</div>';
    } else {
        $stmt = $conn->prepare(
            "SELECT user_id, name, password, is_admin FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["is_admin"] = (int) $user["is_admin"];

                setcookie("user_id", $user["user_id"], time() + 86400 * 7, "/");
                setcookie("user_name", $user["name"], time() + 86400 * 7, "/");

                header("Location: index.php");
                exit();
            } else {
                $message = '<div class="error">Incorrect password!</div>';
            }
        } else {
            $message = '<div class="error">Email not found!</div>';
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

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .login-wrap {
        width: 390px;
        margin: 5rem auto;
        background: #f5f5f5;
        padding: 2.5rem;
        border-radius: 14px;
        border: 1px solid #ccc;
        text-align: center;
    }

    .login-wrap h2 {
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

    .login-wrap button {
        width: 100%;
        padding: 14px;
        font-size: 1.05rem;
        background: #0056b3;
        color: #f5f5f5;
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

    .login-wrap button:hover {
        background: #004a99;
    }

    .login-wrap button svg {
        width: 18px;
        height: 18px;
    }

    .error {
        font-family: var(--font-inter);
        background: #ffd6d6;
        border: 1px solid #ff9393ff;
        color: #c20000;
        padding: 14px;
        border-radius: 10px;
        margin-bottom: 1.2rem;
        font-size: .9rem;
        text-align: center;
    }

    .login-wrap p {
        font-family: var(--font-inter);
        margin-top: 2rem;
        font-size: 1rem;
    }

    .login-wrap a {
        font-family: var(--font-inter);
        color: #0056b3;
        font-weight: 600;
        text-decoration: none;
    }

    .login-wrap a:hover {
        text-decoration: underline;
    }

    html,
    body {
        overflow-x: hidden;
    }

    main,
    .container,
    .login-container {
        max-width: 100%;
        overflow-x: hidden;
    }

    .login-wrap {
        max-width: 100%;
        box-sizing: border-box;
    }

    @media (max-width: 420px) {

        .login-wrap {
            width: 100%;
            margin: 2.5rem 1rem;
            padding: 2rem 1.3rem;
        }

        .login-wrap h2 {
            font-size: 1.6rem;
        }

        .input-group input {
            padding-left: 44px;
        }

        .input-group svg {
            left: 12px;
        }

        .login-container {
            padding: 0 1rem;
        }
    }

    @media (max-width: 340px) {
        .login-wrap {
            padding: 1.6rem 1rem;
        }

        .login-wrap h2 {
            font-size: 1.4rem;
        }
    }
</style>

<main class="container">
    <section class="login-container">
        <div class="login-wrap">
            <h2>Login</h2>

            <?= $message ?>

            <form method="POST">

                <div class="input-group">
                    <svg data-lucide="mail"></svg>
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>

                <div class="input-group">
                    <svg data-lucide="lock"></svg>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit"><i data-lucide="log-in"></i> Login</button>
            </form>

            <p>Don't have an account?<a href="register.php"> Register </a></p>
        </div>
    </section>
</main>

<script>
    lucide.createIcons();
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
