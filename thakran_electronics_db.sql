-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 08:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thakran_electronics_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `category`, `stock`, `created_at`) VALUES
(37, 'Samsung 27 inch FHD Monitor', 'IPS PANEL: Experience stunning colors across the entire display with the IPS panel. Colors remain bright and clear across the screen, even when you change angles. Tones and shades are represented consistently and beautifully with less color washing\r\n\r\n100HZ REFRESH RATE: Stay in the action when playing games, watching videos, or working on creative projects. The 100Hz refresh rate reduces lag and motion blur so you don\'t miss a thing in fast-paced moments', 189.00, '1765001558_6933c956228f2.jpg', 'Monitor', 4, '2025-12-06 06:12:38'),
(39, 'LG Full HD Monitor (27U411A)', 'Vivid Color with Full HD Resolution - The LG 27\" IPS monitor with Full HD (1920 x 1080) resolution delivers sharp, vibrant visuals from virtually any angle. Whether you\'re tackling tasks, browsing the web, or managing your inbox, this monitor helps you stay clear, focused, and productive.\r\n\r\nVirtually Borderless. Visually Seamless. - The ultra-slim bezels and 3-side virtually borderless design creates a clean, modern look that keeps your focus where it belongs—on the screen. The sleek stand and base adds a floating feel, perfect for dual setups or a clutter-free workspace.', 169.00, '1765002029_6933cb2de5e03.jpg', 'Monitor', 8, '2025-12-06 06:20:29'),
(40, 'KOORUI 22 Inch Computer Monitor', 'IMPORTANT FEATURES: 22-inch monitor, resolution: 1920*1080P, 100HZ, color gamut 99% sRGB (NTSC 72%), response time 6ms, dynamic contrast ratio 20 million, horizontal and vertical viewing angle 178 degrees, with 1.5m HDMI cable\r\n\r\nUPGRADED EYE PROTECTION: The KOORUI desktop display greatly reduces screen flickering, keeps the screen clear and smooth, reduces the burden on the eyes, and relieves eye strain to the greatest extent\r\n\r\nEYE CARE: The blue light filter function is added to achieve high-energy Filtering of short-wave blue light. Even if you work and play for a long time, your eyes will not be tired, providing you with the most comfortable entertainment', 99.00, '1765002090_6933cb6a22d23.jpg', 'Monitor', 11, '2025-12-06 06:21:30'),
(41, 'Dell 24 Monitor - SE2425HM', 'An improved viewing experience: Experience a high 100Hz refresh rate across your work with a TÜV Rheinland 3-star* certified viewing experience coupled with ComfortView Plus.\r\n\r\nHigh refresh rate: 100Hz refresh rate delivers less flicker, more seamless scrolling and smoother motion.\r\n\r\nComfortView Plus: Minimizes harmful blue light exposure without sacrificing color accuracy with Dell’s always on, built-in ComfortView Plus.\r\n\r\nTidy and organized: Keep your space neat with this small footprint monitor featuring a built-in power supply unit and cable holder.', 98.00, '1765002143_6933cb9fa4e13.jpg', 'Monitor', 6, '2025-12-06 06:22:23'),
(42, 'Logitech M185 Wireless Mouse', 'Compact Mouse: With a comfortable and contoured shape, this Logitech ambidextrous wireless mouse feels great in either right or left hand and is far superior to a touchpad\r\n\r\nDurable and Reliable: This USB wireless mouse features a line-by-line scroll wheel, up to 1 year of battery life (2) thanks to a smart sleep mode function, and comes with the included AA battery\r\n\r\nUniversal Compatibility: Your Logitech mouse works with your Windows PC, Mac, or laptop, so no matter what type of computer you own today or buy tomorrow your mouse will be compatible\r\n\r\nPlug and Play Simplicity: Just plug in the tiny nano USB receiver and start working in seconds with a strong, reliable connection to your wireless computer mouse up to 33 feet / 10 m (5)', 15.00, '1765002213_6933cbe529957.jpg', 'Mouse', 8, '2025-12-06 06:23:33'),
(43, 'Logitech G502 HERO', 'High performance HERO 16K Sensor: Logitech\'s most accurate sensor yet with up to 16,000 DPI for the ultimate in gaming speed, accuracy and responsiveness across entire DPI range\r\n\r\n11 Customizable Buttons and Onboard Memory: Assign custom commands to the buttons and save up to five ready to play profiles directly to the mouse. Zero smoothing/acceleration/filtering\r\n\r\nAdjustable Weight System: Arrange up to five removable 3.6 grams weights inside the mouse for personalized weight and balance tuning\r\n\r\nProgrammable RGB Lighting and LIGHTSYNC Technology: Customize lighting from nearly 16.8 million colors to match your team\'s colors, sport your own or sync colors with other Logitech G gear', 49.00, '1765002265_6933cc19da67b.jpg', 'Mouse', 2, '2025-12-06 06:24:25'),
(44, 'Acer Wireless Mouse for Laptop', '【3 DPI & 6 Buttons】Acer mouse features 3 DPI settings (1600/1200/800) for design precision or speed. Easily switch for control. 6 buttons boost productivity, with quick-access back/forward buttons for seamless browsing. Moving quickly between documents or browsing your favorite websites is a breeze with the large, easy-to-reach back/forward buttons. 📌\"The indicator light blinks to show the current DPI level (1-3) when switching between modes.\r\n\r\n【2.4GHz Plug & Play】Enjoy a strong wireless connection with the compact USB receiver—no drivers needed. Simply plug in and go. The receiver stores neatly in the USB mouse\'s battery compartment.', 17.00, '1765002320_6933cc5053a38.jpg', 'Mouse', 12, '2025-12-06 06:25:20'),
(45, 'EPOMAKER TH108 PRO', 'PRO with Screen, Upgrade on Display: Featuring a sophisticated smart screen, the TH108 PRO gaming keyboard puts vital information and personalised flair right at your fingertips. With this powerful display, instantly verify the time, monitor keyboard power, or toggle RGB backlight with the volume knob doubles as screen control (FN+Knob to switch), without losing focus. Easily upload your own GIFs or pictures with the Chrome-based software on Mac or WIN computers; to flaunt the team you battle for or show off a perfectly timed meme that puts a smile on your face. With the custom screen, express your unique style that moves beyond static backlight.\r\n\r\n100% Sized, 100% Spectacular Performance: Level up your setup with the TH108 PRO full-sized gaming keyboard, engineered for intense gaming sessions and demanding office tasks. Built to last, this full-sized mechanical keyboard features 104 double-shot PBT keycaps, and a thick body in ABS plastic that weighs over 1.2kg for un-moving stable typing. The premium build quality goes beyond the exterior, with the massive 10,000mAh battery and the lab-tested gaming chips, the TH108 PRO gaming keyboard promises 2ms super low latency in cable mode and 1k polling in convenient 2.4Ghz wireless mode, for swift and stable execution of complex in-game maneuvers.', 148.00, '1765002402_6933cca25985d.jpg', 'Keyboard', 12, '2025-12-06 06:26:42'),
(46, 'Logitech MK270 Wireless Keyboard', 'Reliable Plug and Play: The USB receiver provides a reliable wireless connection up to 33 ft (1), so you can forget about drop-outs and delays and you can take it wherever you use your computer\r\n\r\nType in Comfort: The design of this keyboard creates a comfortable typing experience thanks to the low-profile, quiet keys and standard layout with full-size F-keys, number pad, and arrow keys\r\n\r\nDurable and Resilient: This full-size wireless keyboard features a spill-resistant design (2), durable keys and sturdy tilt legs with adjustable height', 39.00, '1765002460_6933ccdca81d2.jpg', 'Keyboard', 3, '2025-12-06 06:27:40'),
(47, 'ASUS Dual NVIDIA GeForce RTX 3050', 'NVIDIA Ampere Streaming Multiprocessors: The all-new Ampere SM brings 2X the FP32 throughput and improved power efficiency.\r\n\r\n2nd Generation RT Cores: Experience 2X the throughput of 1st gen RT Cores, plus concurrent RT and shading for a whole new level of ray-tracing performance.\r\n\r\n3rd Generation Tensor Cores: Get up to 2X the throughput with structural sparsity and advanced AI algorithms such as DLSS. These cores deliver a massive boost in game performance and all-new AI capabilities.\r\n\r\nAxial-tech fan design features a smaller fan hub that facilitates longer blades and a barrier ring that increases downward air pressure.\r\n\r\nA 2-slot Design maximizes compatibility and cooling efficiency for superior performance in small chassis.', 249.00, '1765002609_6933cd71c3f49.jpg', 'Graphics Card', 11, '2025-12-06 06:30:09'),
(48, 'ASUS Prime Radeon™ RX 9060 XT 16GB', 'Axial-tech fans now feature a smaller fan hub that facilitates longer blades and a barrier ring that increases downward air pressure\r\n\r\n2.5-slot design allows for greater build compatibility while maintaining cooling performance\r\n\r\nDual-ball fan bearings last up to twice as long as standard conventional sleeve bearings designs\r\n0dB technology lets you enjoy light gaming in relative silence\r\n\r\nDual BIOS switch lets you toggle between Quiet and Performance BIOS profiles', 549.00, '1765002665_6933cda91eb7e.jpg', 'Graphics Card', 3, '2025-12-06 06:31:05'),
(49, 'MSI Gaming GeForce RTX 3060', 'Chipset: NVIDIA GeForce RTX 3060\r\n\r\nVideo Memory: 12GB GDDR6\r\n\r\nMemory Interface: 192-bit\r\n\r\nOutput: DisplayPort x 3 (v1.4a) / HDMI 2.1 x 1\r\n\r\nDigital maximum resolution: 7680 x 43', 429.00, '1765002714_6933cddaa2790.jpg', 'Graphics Card', 10, '2025-12-06 06:31:54'),
(50, 'ASUS Prime GeForce RTX™ 5060', 'AI Performance: 630 AI TOPS\r\n\r\nOC Edition: 2595 MHz OC mode, 2565 MHz default mode\r\n\r\nPowered by the NVIDIA Blackwell architecture and DLSS 4\r\n\r\nSFF-Ready Enthusiast GeForce Card\r\n\r\nAxial-tech fans feature a smaller fan hub that facilitates longer blades and a barrier ring that increases downward air pressure', 439.00, '1765002775_6933ce1761282.jpg', 'Graphics Card', 4, '2025-12-06 06:32:55'),
(51, 'ASUS Dual NVIDIA GeForce RTX 3060', 'NVIDIA Ampere Streaming Multiprocessors: The building blocks for the world’s fastest, most efficient GPU, the all-new Ampere SM brings 2X the FP32 throughput and improved power efficiency.\r\n\r\n2nd Generation RT Cores: Experience 2X the throughput of 1st gen RT Cores, plus concurrent RT and shading for a whole new level of ray tracing performance.\r\n\r\n3rd Generation Tensor Cores: Get up to 2X the throughput with structural sparsity and advanced AI algorithms such as DLSS. Now with support for up to 8K resolution, these cores deliver a massive boost in game performance and all-new AI capabilities.\r\n\r\nOC mode: Boost clock 1867 MHz (OC mode)/ 1837 MHz (Gaming mode)\r\nAxial-Tech Fan Design features a smaller fan hub that facilitates longer blades and a barrier ring that increases downward air pressure.', 410.00, '1765002815_6933ce3fcd69c.jpg', 'Graphics Card', 13, '2025-12-06 06:33:35'),
(52, 'Apple 2025 MacBook Pro', 'SUPERCHARGED BY M5 — The 14-inch MacBook Pro with M5 brings next-generation speed and powerful on-device AI to personal, professional and creative tasks. Featuring all-day battery life and a breathtaking Liquid Retina XDR display with up to 1600 nits peak brightness, it’s pro in every way.*\r\n\r\nHAPPILY EVER FASTER—Along with its faster CPU and unified memory, M5 features a more powerful GPU with a Neural Accelerator built into each core, delivering faster AI performance. So you can blaze through demanding workloads at mind-bending speeds.\r\n\r\nBUILT FOR APPLE INTELLIGENCE— Apple Intelligence is the personal intelligence system that helps you write, express yourself and get things done effortlessly. With groundbreaking privacy protections, it gives you peace of mind that no one else can access your data — not even Apple.*\r\n\r\nALL-DAY BATTERY LIFE — MacBook Pro delivers the same exceptional performance whether it’s running on battery or plugged in.\r\n\r\nAPPS FLY WITH APPLE SILICON — All your favourites, including Microsoft 365 and Adobe Creative Cloud, run lightning fast in macOS.*', 2089.00, '1765002886_6933ce86eb441.jpg', 'Laptop', 21, '2025-12-06 06:34:46'),
(53, 'Lenovo ThinkPad E14 Gen 6', 'Powerful AMD Ryzen 7 7735U 8-Core (Base Clock 2.7GHz, Up to 4.75 GHz, 8 cores, 16 threads, 16MB L3 Cache)\r\n[[ Customization ]] Upgraded to 32GB DDR5 SDRAM 4800 MHz | 1TB NVMe M.2 Solid State Drive | Windows 11 Pro\r\n\r\nBrilliant 14\" WUXGA (1920 x 1200) IPS Touchscreen 300 nits Anti-glare, 45% NTSC, Thin Bezel LCD Display. Powered by AMD Radeon 680M Graphics, 1080p FHD Camera with Privacy Shutter and integrated digital microphone', 1637.00, '1765002967_6933ced714159.jpg', 'Laptop', 2, '2025-12-06 06:36:07'),
(54, 'ASUS Vivobook S 16 OLED Laptop', '(Memory Disk and System): 16GB 7467 MHz DDR5 SDRAM, 1TB PCI-E NVMe M.2 SSD for Storage, Pre-install Windows 11 Home\r\n\r\n(Processor): Intel Core Ultra 9 185H 16-Core Processor (Up to 5.1 GHz with Intel Turbo Boost Technology, 24 MB Intel Smart cache, 16 Cores: 6 Performance Cores + 8 Efficient Cores, 22 Threads)\r\n\r\n(Screen and Graphics): Brilliant 16\" 3200x2000 (3200 x 2000) 600 nits 16:10 aspect ratio, 100% DCI-P3 color gamut, 1,000,000:1, VESA CERTIFIED Display HDR True Black 600, 1.07 billion colors, Glossy display, 70% less harmful blue light, (Screen-to-body ratio)89% Display powered by Intel Arc Graphics\r\n\r\n(Connection and Others): Intel Wi-Fi 6E AX211 (2x2) and Bluetooth 5.3, Single-Zone RGB Backlit Keyboard with Numpad; 1080p FHD Camera; 75Whr 4-Cell Lithium-Ion Battery (up to 6 hours battery life); 13.92\" x 9.75\" x 0.63\" inches, 1.5 kg; Neutral Black; 90W AC Adapter; 32GB USB 3.0 Flash Drive for Free', 1655.00, '1765003037_6933cf1d5855a.jpg', 'Laptop', 12, '2025-12-06 06:37:17'),
(55, 'Seagate Portable 2TB', 'Easily store and access 2TB to content on the go with the Seagate Portable Drive, a USB external hard drive\r\n\r\nDesigned to work with Windows or Mac computers, this external hard drive makes backup a snap just drag and drop\r\n\r\nTo get set up, connect the portable hard drive to a computer for automatic recognition no software required\r\n\r\nThis USB drive provides plug and play simplicity with the included 18 inch USB 3.0 cable', 129.00, '1765003108_6933cf64409b0.jpg', 'Storage', 23, '2025-12-06 06:38:28'),
(56, 'UnionSine 500GB  Hard Drive', '【Upgraded version】 - The mirror logo strip is combined with the striped non-slip design. The rounded corners of the shell are more suitable for holding. The strips play a heat dissipation function to ensure a stable and fast transmission process.\r\n\r\n【Ultra-thin and quiet】 - The motherboard adopts JMicron 578 noise-free solution, giving you a quiet working environment. Lightweight and portable size designed to fit in your pocket for easy portability.', 39.00, '1765003197_6933cfbd3d975.jpg', 'Storage', 1, '2025-12-06 06:39:41'),
(57, 'Corsair Vengeance LPX 32GB', 'Designed for high-performance overclocking\r\n\r\nDesigned for great looks\r\n\r\nPerformance and compatibility\r\n\r\nSPD Speed is 2133MHz. Compatibility Intel 300 Series,Intel 400 Series,Intel 500 Series,Intel 600 Series,Intel 400 Series,Intel 500 Series,Intel 600 Series,Intel X299,AMD 300 Series,AMD 400 Series,AMD 500 Series,AMD X570', 231.00, '1765003288_6933d0183248a.jpg', 'Memory', 2, '2025-12-06 06:41:13'),
(58, 'CORSAIR Vengeance DDR5 RAM 32GB', 'Do it All, and Do it Faster: As modern CPUs feature more and more cores, the unprecedented speed of DDR5 ensures your high-end CPU gets data quickly, enabling faster processing, rendering, and buffering than ever before.\r\n\r\nOnboard Voltage Regulation: Makes for easier, more finely-tuned, and more stable overclocking through CORSAIR iCUE software than previous generation motherboard control.', 545.00, '1765003346_6933d052dcd85.jpg', 'Memory', 8, '2025-12-06 06:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `is_admin`, `created_at`) VALUES
(9, 'Lakshay Thakran', 'admin@admin.com', '$2y$10$UwiqB/bYoeN93Xp0aufCeefP5iZMDZPbOrFCVhW3m8duaLJjiqcQ.', 1, '2025-11-25 06:44:33'),
(10, 'Lucky Thakran', 'user@user.com', '$2y$10$T8CpwaqDS1HBIYAVwvDniOslGWVrWZnYBuaCSdiEj0aT0UtMl6FSy', 0, '2025-11-25 07:02:15');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `pincode` varchar(20) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT 'India',
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
