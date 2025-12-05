<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /x/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";
$page_title = "Profile - Thakran Electronics";

$user_id = (int) $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT user_id, name, email, created_at FROM users WHERE user_id = ? LIMIT 1"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

include __DIR__ . "/../includes/header.php";
?>

<style>
    #profile-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1.25rem;
        font-family: "Inter", sans-serif;
    }
    
    .profile-container {
        max-width: 650px;
        margin: 4rem auto;
    }

    .profile-title {
        font-family: "Instrument Serif", serif;
        font-size: 2.6rem;
        text-align: center;
        margin-bottom: 2rem;
        letter-spacing: -0.5px;
    }

    .profile-box {
        background: #ffffff;
        padding: 2.8rem 2.2rem;
        border-radius: 18px;
        border: 1px solid #e4e4e4;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.07);
        text-align: center;
    }

    .profile-avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef3ff, #dde8fa);
        border: 2px solid #cfd8e6;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 1.6rem;
    }

    .profile-avatar svg {
        width: 60px;
        height: 60px;
        stroke-width: 1.5;
    }

    .profile-item {
        font-size: 1.1rem;
        margin: 0.8rem 0;
        color: #444;
    }

    .profile-buttons {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .btn-prof {
        padding: 0.75rem 1.4rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: 0.25s ease;
    }

    .btn-prof svg {
        width: 20px;
        height: 20px;
    }

    .btn-orders {
        background: #0b63e6;
        color: #fff;
    }

    .btn-orders:hover {
        background: #084cb8;
    }

    .btn-logout {
        background: #ffe5e5;
        color: #c20000;
        border: 1px solid #ffb4b4;
    }

    .btn-logout:hover {
        background: #ffd1d1;
    }
</style>

<main id="profile-wrapper">
    <div class="profile-container">
        <h2 class="profile-title">My Profile</h2>
        <div class="profile-box">

            <div class="profile-avatar">
                <svg data-lucide="user-round"></svg>
            </div>

            <p class="profile-item"><strong>Name:</strong> <?= htmlspecialchars(
                $user["name"]
            ) ?></p>
            <p class="profile-item"><strong>Email:</strong> <?= htmlspecialchars(
                $user["email"]
            ) ?></p>
            <p class="profile-item"><strong>Joined:</strong> <?= date(
                "d M Y",
                strtotime($user["created_at"])
            ) ?></p>

            <div class="profile-buttons">
                <a class="btn-prof btn-orders" href="/thakran-electronics/user/order.php">
                    <svg data-lucide="receipt"></svg> My Orders
                </a>

                <a class="btn-prof btn-logout" href="/thakran-electronics/logout.php">
                    <svg data-lucide="log-out"></svg> Logout
                </a>
            </div>

        </div>

    </div>
</main>

<script>
    lucide.createIcons();
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
