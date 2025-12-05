<?php
session_start();
require_once __DIR__ . "/config/db.php";
include __DIR__ . "/includes/header.php";

$featured = [];

$stmt = $conn->prepare(
    "SELECT id, name, price, category, image_url FROM products ORDER BY id DESC LIMIT 8"
);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $featured[] = $row;
}
?>

<style>
    html,
    body {
        overflow-x: hidden;
        width: 100%;
    }

    .carousel-container {
        max-width: 1500px;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .carousel-track {
        display: flex;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .carousel-slide {
        min-width: 100%;
        height: 550px;
    }

    .carousel-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        border: none;
        color: var(--text-primary);
        padding: 12px;
        cursor: pointer;
        border-radius: 50%;
        z-index: 10;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .carousel-btn:hover {
        background: white;
        transform: translateY(-50%) scale(1.1);
    }

    .prev-btn {
        left: 20px;
    }

    .next-btn {
        right: 20px;
    }

    .carousel-dots {
        text-align: center;
        position: absolute;
        width: 100%;
        bottom: 20px;
        z-index: 10;
    }

    .dot {
        width: 12px;
        height: 12px;
        background: rgba(255, 255, 255, 0.5);
        display: inline-block;
        border-radius: 50%;
        margin: 0 6px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .dot.active {
        background: white;
        width: 32px;
        border-radius: 6px;
    }

    @media (max-width: 1200px) {
        .carousel-slide {
            height: 420px;
        }

        .carousel-btn {
            padding: 10px;
        }
    }

    @media (max-width: 768px) {
        .carousel-slide {
            height: 300px;
        }

        .prev-btn {
            left: 12px;
        }

        .next-btn {
            right: 12px;
        }

        .carousel-btn {
            padding: 8px;
        }
    }

    @media (max-width: 480px) {

        main.container {
            padding-left: 0 !important;
            padding-right: 0 !important;
            max-width: 100% !important;
            margin: 0 !important;
        }

        .carousel-container {
            width: 100% !important;
            max-width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            border-radius: 0 !important;
        }

        .carousel-slide {
            height: 200px;
        }

        .carousel-btn {
            padding: 6px;
        }

        .prev-btn {
            left: 6px;
        }

        .next-btn {
            right: 6px;
        }

        .dot {
            width: 10px;
            height: 10px;
            margin: 0 4px;
        }

        .dot.active {
            width: 24px;
        }
    }

    .brands-section {
        max-width: 1500px;
        margin: 5rem auto 4rem;
        padding: 0 1.25rem;
    }

    .brands-title {
        font-family: var(--font-instrument);
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        color: var(--text-primary);
    }

    .brands-marquee {
        position: relative;
        overflow: hidden;
        white-space: nowrap;
        padding: 2rem 0;
        mask-image: linear-gradient(to right,
                transparent 0%,
                black 10%,
                black 90%,
                transparent 100%);
        -webkit-mask-image: linear-gradient(to right,
                transparent 0%,
                black 10%,
                black 90%,
                transparent 100%);
    }

    .marquee-track {
        display: inline-flex;
        align-items: center;
        gap: 80px;
        animation: marquee-scroll 30s linear infinite;
    }

    .marquee-track img {
        height: 80px;
        width: 80px;
        object-fit: contain;
        filter: grayscale(100%);
        opacity: 0.6;
        transition: all 0.3s ease;
    }

    .brands-marquee,
    .marquee-track {
        overflow-x: hidden !important;
    }

    @media (max-width: 480px) {
        main.container {
            padding-left: 0 !important;
            padding-right: 0 !important;
            overflow-x: hidden !important;
            max-width: 100% !important;
        }

        .carousel-container,
        .carousel-track,
        .carousel-slide {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
    }

    @keyframes marquee-scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    @media (max-width: 1024px) {
        .brands-title {
            font-size: 2rem;
        }

        .marquee-track img {
            height: 60px;
        }

        .marquee-track {
            gap: 60px;
        }
    }

    @media (max-width: 768px) {
        .brands-title {
            font-size: 1.7rem;
            margin-bottom: 2rem;
        }

        .marquee-track img {
            height: 50px;
        }

        .marquee-track {
            gap: 40px;
        }
    }

    @media (max-width: 480px) {
        .brands-section {
            margin: 3rem 0 2rem;
            padding: 0 0.7rem;
        }

        .brands-title {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
        }

        .marquee-track img {
            height: 38px;
            width: 40px;
            opacity: 0.7;
        }

        .marquee-track {
            gap: 28px;
            animation-duration: 22s;
        }

        .brands-marquee {
            padding: 1.2rem 0;
        }
    }

    .featured-section {
        max-width: 1500px;
        margin: 4rem auto;
        padding: 0 1.25rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-family: var(--font-instrument);
        font-size: 2.5rem;
        color: var(--text-primary);
    }

    .btn-viewall {
        background: var(--button-color);
        padding: 0.75rem 1.5rem;
        color: white;
        border-radius: var(--radius);
        text-decoration: none;
        font-family: var(--font-inter);
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-viewall:hover {
        background: #3381ffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 86, 179, 0.3);
    }

    .products-slider-wrapper {
        position: relative;
    }

    .products-slider {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding-bottom: 1rem;
        scroll-snap-type: x mandatory;
        scrollbar-width: thin;
    }

    .products-slider::-webkit-scrollbar {
        height: 8px;
    }

    .products-slider::-webkit-scrollbar-track {
        background: var(--bg-secondary);
        border-radius: 10px;
    }

    .products-slider::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: white;
        border: 1px solid var(--border-color);
        padding: 12px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 10;
        box-shadow: var(--shadow);
        transition: 0.3s;
    }

    .slider-btn:hover {
        background: var(--bg-secondary);
        transform: translateY(-50%) scale(1.1);
    }

    .slider-btn svg {
        width: 20px;
        height: 20px;
    }

    .slider-prev {
        left: -20px;
    }

    .slider-next {
        right: -20px;
    }

    .product-card {
        min-width: 260px;
        max-width: 260px;
        flex-shrink: 0;
        scroll-snap-align: start;
        background: var(--bg-secondary);
        padding: 1.25rem;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        font-family: var(--font-inter);
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
    }

    .product-thumb {
        width: 100%;
        height: 190px;
        object-fit: contain;
        background: white;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .product-card h3 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }


    .product-price {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .btn-view {
        display: block;
        width: 100%;
        background: var(--button-color);
        color: white;
        padding: 0.7rem;
        border-radius: var(--radius);
        text-align: center;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-view:hover {
        background: #004494;
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 2rem;
        }

        .product-card {
            min-width: 220px;
            max-width: 220px;
        }

        .product-thumb {
            height: 160px;
        }

        .slider-prev,
        .slider-next {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .featured-section {
            margin: 3rem 0;
            padding: 0 0.7rem;
        }

        .section-header {
            flex-direction: column;
            gap: 1rem;
        }

        .section-title {
            font-size: 1.6rem;
        }

        .product-card {
            min-width: 190px;
            max-width: 190px;
        }

        .product-thumb {
            height: 140px;
        }

        .products-slider {
            gap: 1rem;
        }

        .slider-prev,
        .slider-next {
            display: none;
        }
    }

    .benefits-section {
        max-width: 1500px;
        margin: 5rem auto;
        padding: 0 1.25rem;
    }

    .benefits-title {
        font-family: var(--font-instrument);
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        color: var(--text-primary);
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 2rem;
    }

    .benefit-card {
        background: var(--bg-secondary);
        border-radius: var(--radius);
        padding: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        transition: 0.3s;
    }

    .benefit-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-hover);
    }

    .benefit-icon {
        width: 56px;
        height: 56px;
        background: #eef3ff;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }

    .benefit-icon svg {
        width: 28px;
        height: 28px;
        color: var(--button-color);
    }

    .benefit-title {
        font-family: var(--font-instrument);
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--text-primary);
    }

    .benefit-text {
        font-family: var(--font-inter);
        font-size: 1em;
        line-height: 1.6;
        color: var(--text-secondary);
    }

    .highlight-card {
        grid-column: span 2;
    }

    @media (max-width: 1366px) and (min-width: 1024px) {

        .benefits-title {
            font-size: 2.2rem;
            margin-bottom: 2.5rem;
        }

        .benefits-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.8rem;
        }

        .highlight-card {
            grid-column: span 2;
        }

        .benefit-card {
            padding: 1.8rem;
        }

        .benefit-title {
            font-size: 1.25rem;
        }

        .benefit-text {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 768px) {
        .benefits-title {
            font-size: 2rem;
            margin-bottom: 2.2rem;
        }

        .benefits-grid {
            gap: 1.5rem;
        }

        .benefit-card {
            padding: 1.6rem;
        }

        .benefit-title {
            font-size: 1.2rem;
        }

        .benefit-text {
            font-size: 0.9rem;
        }

        .highlight-card {
            grid-column: span 1;
        }
    }

    @media (max-width: 480px) {
        .benefits-section {
            margin: 3.5rem 0;
            padding: 0 0.7rem;
        }

        .benefits-title {
            font-size: 1.6rem;
            margin-bottom: 1.7rem;
        }

        .benefits-grid {
            grid-template-columns: 1fr;
            gap: 1.2rem;
        }

        .benefit-card {
            padding: 1.4rem;
        }

        .benefit-icon {
            width: 48px;
            height: 48px;
        }

        .benefit-icon svg {
            width: 24px;
            height: 24px;
        }

        .benefit-title {
            font-size: 1.15rem;
        }

        .benefit-text {
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .highlight-card {
            grid-column: span 1;
        }
    }

    .faq-section {
        max-width: 1500px;
        margin: 5rem auto;
        padding: 0 1.25rem;
    }

    .faq-title {
        font-family: var(--font-instrument);
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        color: var(--text-primary);
    }

    .faq-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .faq-item {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        margin-bottom: 1rem;
        overflow: hidden;
        transition: 0.3s ease;
    }

    .faq-item:hover {
        box-shadow: var(--shadow);
    }

    .faq-question {
        padding: 1.5rem;
        font-family: var(--font-inter);
        font-size: 1.15rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--text-primary);
        user-select: none;
    }

    .faq-arrow {
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }

    .faq-arrow svg {
        width: 22px;
        height: 22px;
        color: var(--text-secondary);
    }

    .faq-item.active .faq-arrow {
        transform: rotate(180deg);
    }

    .faq-answer {
        padding: 0 1.5rem 1.5rem;
        font-family: var(--font-inter);
        font-size: 1rem;
        line-height: 1.7;
        color: var(--text-secondary);
        display: none;
    }

    .faq-item.active .faq-answer {
        display: block;
        animation: fadeInFaq 0.3s ease;
    }

    @keyframes fadeInFaq {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {

        .faq-title {
            font-size: 2rem;
            margin-bottom: 2.2rem;
        }

        .faq-question {
            font-size: 1.05rem;
            padding: 1.25rem;
        }

        .faq-arrow svg {
            width: 20px;
            height: 20px;
        }

        .faq-answer {
            font-size: 0.95rem;
            padding: 0 1.25rem 1.25rem;
        }
    }

    @media (max-width: 480px) {

        .faq-section {
            margin: 3.5rem 0;
            padding: 0 0.7rem;
        }

        .faq-title {
            font-size: 1.6rem;
            margin-bottom: 1.7rem;
        }

        .faq-container {
            width: 100%;
        }

        .faq-question {
            font-size: 1rem;
            padding: 1rem 1.2rem;
        }

        .faq-arrow svg {
            width: 18px;
            height: 18px;
        }

        .faq-answer {
            font-size: 0.9rem;
            padding: 0 1.2rem 1.2rem;
            line-height: 1.6;
        }

        .faq-item {
            border-radius: 10px;
        }
    }
</style>
<main class="container">
    <div class="carousel-container">
        <button class="carousel-btn prev-btn">
            <i data-lucide="chevron-left"></i>
        </button>
        <button class="carousel-btn next-btn">
            <i data-lucide="chevron-right"></i>
        </button>

        <div class="carousel-track" id="carouselTrack">
            <div class="carousel-slide"><img src="/thakran-electronics/assets/images/banners/banner1.webp" alt="Banner 1"></div>
            <div class="carousel-slide"><img src="/thakran-electronics/assets/images/banners/banner2.webp" alt="Banner 2"></div>
            <div class="carousel-slide"><img src="/thakran-electronics/assets/images/banners/banner3.webp" alt="Banner 3"></div>
            <div class="carousel-slide"><img src="/thakran-electronics/assets/images/banners/banner4.webp" alt="Banner 4"></div>
            <div class="carousel-slide"><img src="/thakran-electronics/assets/images/banners/banner5.webp" alt="Banner 5"></div>
        </div>

        <div class="carousel-dots" id="carouselDots"></div>
    </div>

    <div class="brands-section">
        <h2 class="brands-title">Explore Accessories From Top Brands</h2>

        <div class="brands-marquee">
            <div class="marquee-track">
                <?php
                $brands = [
                    "logitech",
                    "apple",
                    "nvidia",
                    "hp",
                    "dell",
                    "amd",
                    "intel",
                    "lenovo",
                    "msi",
                    "asus",
                    "samsung",
                ];

                for ($i = 0; $i < 2; $i++):
                    foreach ($brands as $b):
                        echo "<img src='/thakran-electronics/assets/images/brands/{$b}.svg' alt='{$b} logo'>";
                    endforeach;
                endfor;
                ?>
            </div>
        </div>
    </div>

    <div class="featured-section">
        <div class="section-header">
            <h2 class="section-title">Featured Products</h2>
            <a class="btn-viewall" href="/thakran-electronics/products.php">
                View Products
            </a>
        </div>

        <div class="products-slider-wrapper">
            <button class="slider-btn slider-prev">
                <i data-lucide="chevron-left"></i>
            </button>
            <button class="slider-btn slider-next">
                <i data-lucide="chevron-right"></i>
            </button>

            <div class="products-slider" id="productsSlider">
                <?php if ($featured): ?>
                    <?php foreach ($featured as $p): ?>
                        <div class="product-card">
                            <img class="product-thumb"
                                src="/thakran-electronics/assets/images/products/<?= htmlspecialchars(
                                    $p["image_url"]
                                ) ?>"
                                alt="<?= htmlspecialchars($p["name"]) ?>">

                            <h3><?= htmlspecialchars($p["name"]) ?></h3>
                            <div class="product-price">$<?= number_format(
                                $p["price"],
                                2
                            ) ?></div>

                            <a class="btn-view" href="/thakran-electronics/product.php?id=<?= $p[
                                "id"
                            ] ?>">
                                View Details
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="padding: 2rem; text-align: center; width: 100%;">No products available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="benefits-section">
        <h2 class="benefits-title">Why Shop With Thakran Electronics?</h2>

        <div class="benefits-grid">
            <div class="benefit-card highlight-card">
                <div class="benefit-icon">
                    <i data-lucide="shield-check"></i>
                </div>
                <div class="benefit-title">Secure & Trusted Shopping</div>
                <div class="benefit-text">
                    Your privacy and security come first. We use encrypted payments,
                    fraud protection, and safe-checkout technology trusted by thousands
                    of customers across India.
                </div>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i data-lucide="truck"></i>
                </div>
                <div class="benefit-title">Lightning-Fast Delivery</div>
                <div class="benefit-text">
                    Enjoy superfast delivery with real-time tracking and priority
                    handling for electronic items—a smooth doorstep experience.
                </div>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i data-lucide="tag"></i>
                </div>
                <div class="benefit-title">Unbeatable Prices</div>
                <div class="benefit-text">
                    Get top-quality gadgets at the best market prices, exclusive deals,
                    and seasonal discounts. Premium tech without the premium price tag!
                </div>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i data-lucide="headphones"></i>
                </div>
                <div class="benefit-title">24/7 Customer Support</div>
                <div class="benefit-text">
                    Our support team is available round-the-clock to help with
                    orders, returns, warranty queries, and more. Real people, real help.
                </div>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i data-lucide="award"></i>
                </div>
                <div class="benefit-title">Warranty & Replacement</div>
                <div class="benefit-text">
                    Shop confidently with manufacturer warranty and easy replacement
                    on damaged or defective items.
                </div>
            </div>
        </div>
    </div>

    <div class="faq-section">
        <h2 class="faq-title">Frequently Asked Questions</h2>

        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <span>How long does delivery take?</span>
                    <div class="faq-arrow"><i data-lucide="chevron-down"></i></div>
                </div>
                <div class="faq-answer">
                    Delivery usually takes <strong>5 business days</strong> depending on your location and the courier selected. Metro cities typically receive packages faster. For large or out-of-stock items, you may see a longer estimated delivery time during checkout — we always provide tracking and timely updates so you know exactly when to expect your order.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Is my payment information secure?</span>
                    <div class="faq-arrow"><i data-lucide="chevron-down"></i></div>
                </div>
                <div class="faq-answer">
                    Absolutely. We use <strong>HTTPS/SSL encryption</strong> site-wide and partner with trusted payment gateways for card and UPI transactions. Sensitive payment details are processed by the gateway and are never stored in plain text on our servers. Additionally, we monitor for fraud and suspicious activity to keep accounts safe.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Do you offer warranty on products?</span>
                    <div class="faq-arrow"><i data-lucide="chevron-down"></i></div>
                </div>
                <div class="faq-answer">
                    All electronics include the <strong>manufacturer's official warranty</strong>. Warranty periods vary by brand and product (commonly 1 or 2 years). For warranty claims, we coordinate with the manufacturer and help you through the repair/replacement process to minimize downtime.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Can I return or replace a product?</span>
                    <div class="faq-arrow"><i data-lucide="chevron-down"></i></div>
                </div>
                <div class="faq-answer">
                    Yes. If an item arrives damaged, defective, or not as described, you may request a return or replacement within the stated return window <strong>typically 7 days from delivery</strong>. Please keep the original packaging and get in touch with support — we'll guide you through the steps and provide a prepaid return label when applicable.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I track my order?</span>
                    <div class="faq-arrow"><i data-lucide="chevron-down"></i></div>
                </div>
                <div class="faq-answer">
                    After dispatch, the courier tracking number appears on the <strong>My Orders</strong> page. Click the order to view detailed tracking history. We also send email/SMS updates at key milestones (dispatched, out for delivery, delivered).
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>What payment methods do you support?</span>
                    <div class="faq-arrow"><i data-lucide="chevron-down"></i></div>
                </div>
                <div class="faq-answer">
                    We support <strong>Debit/Credit Cards (Visa / MasterCard / Rupay), Net Banking, and popular wallets</strong>. If you face any issue while paying, please contact our support team for assistance.
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    lucide.createIcons();

    const track = document.getElementById("carouselTrack");
    const slides = document.querySelectorAll(".carousel-slide");
    const dotsContainer = document.getElementById("carouselDots");

    let currentIndex = 0;

    slides.forEach((_, i) => {
        const dot = document.createElement("span");
        dot.classList.add("dot");
        if (i === 0) dot.classList.add("active");
        dot.addEventListener("click", () => goToSlide(i));
        dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll(".dot");

    function updateCarousel() {
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        dots.forEach(d => d.classList.remove("active"));
        dots[currentIndex].classList.add("active");
    }

    function goToSlide(index) {
        currentIndex = index;
        updateCarousel();
    }

    document.querySelector(".carousel-container .next-btn").addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateCarousel();
    });

    document.querySelector(".carousel-container .prev-btn").addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateCarousel();
    });

    setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateCarousel();
    }, 3000);

    const productsSlider = document.getElementById("productsSlider");
    const sliderPrev = document.querySelector(".slider-prev");
    const sliderNext = document.querySelector(".slider-next");

    if (sliderPrev && sliderNext) {
        sliderPrev.addEventListener("click", () => {
            productsSlider.scrollBy({
                left: -300,
                behavior: "smooth"
            });
        });

        sliderNext.addEventListener("click", () => {
            productsSlider.scrollBy({
                left: 300,
                behavior: "smooth"
            });
        });
    }

    document.querySelectorAll(".faq-item").forEach(item => {
        const question = item.querySelector(".faq-question");
        question.addEventListener("click", () => {
            const isActive = item.classList.contains("active");

            document.querySelectorAll(".faq-item").forEach(i => i.classList.remove("active"));

            if (!isActive) {
                item.classList.add("active");
            }
        });
    });
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
