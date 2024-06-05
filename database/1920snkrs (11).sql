-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2024 at 10:18 AM
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
-- Database: `1920snkrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isActive` tinyint(1) DEFAULT 1,
  `datecreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `email`, `password`, `isActive`, `datecreated`) VALUES
(8693, 'jame', 'jame@sco.com', '$2y$10$xM1OmghT7NC/0KTED9ZsjeZYNOdsPSMlhB5NWEWezoz2VC2w8xkfy', 1, '2024-04-29 07:26:02');

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_request`
--

CREATE TABLE `cancellation_request` (
  `cancel_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `txn` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancellation_request`
--

INSERT INTO `cancellation_request` (`cancel_id`, `order_id`, `txn`, `userid`, `reason`, `status`, `date`) VALUES
(3, 62, 'txn_665fe18822cd8', 60436, 'asf', 'Approved', '2024-06-05 04:00:24'),
(4, 63, 'txn_665fe2bed8953', 60436, 'Cancel this order please.', 'Approved', '2024-06-04 06:59:55'),
(5, 65, 'txn_66600d6e63e72', 60436, 'Cancel!', 'Rejected', '2024-06-05 07:02:21');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `prod_size` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`, `Description`, `CreatedAt`) VALUES
(1, 'Men', 'Men Originals', '2024-05-23 06:19:25'),
(2, 'Women', 'Women Originals', '2024-05-23 06:19:25'),
(3, 'Kids', 'Kiddo', '2024-05-23 06:20:49'),
(4, 'Sports', 'Sportswear', '2024-05-23 06:20:49'),
(5, 'Unisex', 'For men and women', '2024-05-23 06:21:03');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `transaction_number` varchar(255) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(20) DEFAULT 'Pending',
  `imgURL` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `transaction_number`, `userid`, `total_amount`, `note`, `created_at`, `order_status`, `imgURL`, `updated_at`) VALUES
(62, 'txn_665fe18822cd8', 60436, 4495.00, '', '2024-06-05 03:54:48', 'Cancelled', '../user/assets/proof-payment/665fe188244e1_proof_payment_1717559688115_ss1.png', '2024-06-05 06:56:28'),
(63, 'txn_665fe2bed8953', 60436, 20067.80, '', '2024-06-05 03:59:58', 'Cancelled', '../user/assets/proof-payment/665fe2beda7c9_proof_payment_1717559998866_ss4.png', '2024-06-05 07:00:53'),
(64, 'txn_665ff87021f89', 60436, 6539.00, '', '2024-06-05 05:32:32', 'Completed', '../user/assets/proof-payment/665ff870234b8_proof_payment_1717565552124_ss1.png', '2024-06-05 07:14:49'),
(65, 'txn_66600d6e63e72', 60436, 20067.80, '', '2024-06-05 07:02:06', 'Confirmed', '../user/assets/proof-payment/66600d6e65625_proof_payment_1717570926391_ss4.png', '2024-06-05 07:02:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `order_list_id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `transaction_number` varchar(255) DEFAULT NULL,
  `prod_size` varchar(10) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`order_list_id`, `userid`, `product_id`, `transaction_number`, `prod_size`, `quantity`, `total_price`, `created_at`, `updated_at`) VALUES
(85, 60436, 34, 'txn_665fe18822cd8', '37', 1, 4495.00, '2024-06-05 03:54:48', '2024-06-05 03:54:48'),
(86, 60436, 41, 'txn_665fe2bed8953', '40', 1, 20067.80, '2024-06-05 03:59:58', '2024-06-05 03:59:58'),
(87, 60436, 40, 'txn_665ff87021f89', '41', 1, 6539.00, '2024-06-05 05:32:32', '2024-06-05 05:32:32'),
(88, 60436, 41, 'txn_66600d6e63e72', '42', 1, 20067.80, '2024-06-05 07:02:06', '2024-06-05 07:02:06');

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `pay_id` int(11) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `account_holder` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `isActive` tinyint(1) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`pay_id`, `account_number`, `account_holder`, `bank_name`, `isActive`, `createdAt`, `updatedAt`) VALUES
(1, '09102579678', 'jame', 'gcash', 1, '2024-06-03 01:31:46', '2024-06-03 01:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `Product_id` int(11) NOT NULL,
  `prod_name` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `sizes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`Product_id`, `prod_name`, `Description`, `Price`, `ImageURL`, `CreatedAt`, `sizes`) VALUES
(28, 'NB300', 'New balance', 1999.00, 'assets/product/demo4.jpg', '2024-06-01 18:04:34', '37,38,39'),
(29, 'NB 600', 'New balance, balance new', 8999.00, 'assets/product/demo3.jpg', '2024-06-01 18:06:00', '36,38'),
(30, 'Nike Sunray Protect 2', 'The Nike Sunray Protect 2 sandals are perfect for active kids. Designed for both land and water play, they feature a lightweight, quick-drying upper with easy hook-and-loop closures. The toe cap provides added protection, and the non-marking rubber outsole ensures great traction. Comfortable and durable, these sandals are ideal for summer adventures.', 1595.00, 'assets/product/Nike Sunray Protect 2.png', '2024-06-04 08:34:20', '36,37,36,37'),
(31, 'Nike Dunk Low', 'The Nike Dunk Low sneakers are a timeless classic. Featuring a low-top design with premium materials, they offer both style and comfort. The durable construction and versatile look make them perfect for everyday wear. Available in a variety of colorways, the Nike Dunk Low seamlessly blends retro vibes with modern appeal, making them a must-have for any sneaker collection.', 3095.00, 'assets/product/Nike Dunk Low.png', '2024-06-04 08:35:42', '36,37,36'),
(32, 'Nike Air Max SC', 'The Nike Air Max SC sneakers offer a blend of classic style and modern comfort. Featuring a lightweight mesh and leather upper, they ensure breathability and durability. The iconic Air Max cushioning provides exceptional all-day comfort, while the sleek design makes them perfect for any casual outing. Versatile and stylish, the Air Max SC is ideal for everyday wear.', 2795.00, 'assets/product/Nike Air Max SC.png', '2024-06-04 08:36:41', '36,37'),
(33, 'Nike Cortez Basic SL', 'The Nike Cortez Basic SL sneakers are a timeless classic with a clean and iconic design. Featuring a synthetic leather upper, they offer durability and a premium look. The lightweight EVA midsole provides comfortable cushioning, while the rubber outsole with a herringbone pattern ensures excellent traction. Perfect for everyday wear, these sneakers combine retro style with modern comfort.', 2495.00, 'assets/product/Nike Cortez Basic SL.png', '2024-06-04 08:38:14', '36,37,36'),
(34, 'Nike Air Max Excee', 'The Nike Air Max Excee is a high-performance running shoe that combines innovative technology with sleek design. It features a full-length Air Max unit for maximum cushioning and impact protection, along with a responsive Flyknit upper that provides a snug and comfortable fit. The shoe\'s midsole is designed for explosive takeoff and smooth landing, making it ideal for runners who need to generate power and speed. With its bold colors and eye-catching design, the Nike Air Max Excee is sure to make a statement on the streets or on the track.', 4495.00, 'assets/product/Nike Air Max Excee.png', '2024-06-04 09:07:38', '36,37,36'),
(35, 'Nike Dunk Low', 'The Nike Dunk Low is a classic, versatile low-top basketball shoe that combines style, comfort, and functionality. Its iconic design features a low-cut profile, a rubber outsole, and a removable insole, making it perfect for casual wear, skateboarding, and basketball.', 6895.00, 'assets/product/Nike Dunk Low (1).png', '2024-06-04 09:09:35', '36,37,36,37'),
(36, 'Nike Killshot 2', 'The Nike Killshot 2: A sleek and high-performance basketball shoe with a low-profile design, Zoom Air cushioning, and a durable rubber outsole for traction and control.', 6195.00, 'assets/product/Nike Killshot 2.png', '2024-06-04 09:11:29', '36,37,36,37,38'),
(37, 'Nike V2K Run Premium', 'The Nike V2K Run Premium: A premium running shoe designed for serious runners, boasting a sleek and lightweight mesh upper with a breathable tongue and heel counter for added comfort. The Zoom Air cushioning provides a soft and responsive landing, while the Phylon midsole delivers a smooth and stable ride. The durable rubber outsole features a unique tread pattern for excellent traction and durability, making it perfect for long runs or high-intensity training.', 7395.00, 'assets/product/Nike V2K Run Premium.png', '2024-06-04 09:13:17', '36,37,36,37,38'),
(38, 'Jordan Stadium 90', 'The Jordan Stadium 90: A classic basketball shoe reimagined for modern performance. The lightweight mesh upper provides breathability and comfort, while the midsole cushioning system offers a smooth ride. The durable outsole features a unique tread pattern for traction on both hardwood and outdoor surfaces.', 7895.00, 'assets/product/Jordan Stadium 90.png', '2024-06-04 09:14:28', '36,37,36,37,38'),
(39, 'Air Jordan 1 Mid', 'The Air Jordan 1 Mid is a low-top version of the iconic Air Jordan 1, featuring a mix of premium materials, including leather, suede, and nylon mesh. The shoe is available in a range of colors, including classic and bold options, and features a durable rubber outsole and the iconic \"Jumpman\" logo.', 7095.00, 'assets/product/Air Jordan 1 Mid.png', '2024-06-04 09:17:46', '36,37,36,37,38,39'),
(40, 'Air Jordan XXXVIII Lunar New Year PF', 'Here\'s a short description:\r\n\r\nAir Jordan XXXVIII Lunar New Year PF - A limited-edition sneaker celebrating the Lunar New Year, featuring a metallic silver and red color scheme, premium flyknit upper, and unique design elements inspired by Asian culture.', 6539.00, 'assets/product/Air Jordan XXXVIII Lunar New Year PF.png', '2024-06-04 09:21:20', '40,41,42,43,44,45'),
(41, 'Anta Kai \"Artist on Court\"', 'Here\'s a short description:\r\n\r\nAnta Kai \"Artist on Court\" - A high-performance basketball shoe featuring a sleek and stylish design, with a unique color-blocking pattern, premium materials, and innovative technology for enhanced court performance.', 21124.00, 'assets/product/Anta Kai 1 _Artist on Court_.png', '2024-06-04 09:22:39', '40,41,42,43,44,45'),
(42, 'Nike P-6000 Premium', 'Here\'s a short description:\r\n\r\nNike P-6000 Premium - A high-end, premium basketball shoe featuring a sleek and modern design, with a luxurious upper made from premium materials, such as suede and leather, and a comfortable and responsive midsole for top-notch performance.', 6895.00, 'assets/product/Nike P-6000 Premium.png', '2024-06-04 09:23:55', '41,43,45'),
(43, 'Nike Zoom Vomero 5 SE', 'Here\'s a short description:\r\n\r\nNike Zoom Vomero 5 SE - A lightweight and responsive running shoe featuring a Zoom Air unit for added cushioning, a sleek and modern design, and a breathable mesh upper for a comfortable and supportive ride.', 9395.00, 'assets/product/Nike Zoom Vomero 5 SE.png', '2024-06-04 09:25:00', '42,44'),
(44, 'Nike Metcon 9 By You', 'Here\'s a short description:\r\n\r\nNike Metcon 9 By You - A customizable, high-performance CrossFit training shoe that allows you to personalize your colorway and design. It features a durable and grippy outsole, a breathable mesh upper, and a responsive midsole for intense workouts.', 9895.00, 'assets/product/Nike Metcon 9 By You.png', '2024-06-04 09:26:42', '36,37,43,44'),
(45, 'Nike V2K Run', 'Nike V2K Run - A versatile and comfortable running shoe designed for everyday training and casual wear, featuring a lightweight and breathable mesh upper, a cushioned midsole, and a durable outsole for traction on various surfaces.', 6895.00, 'assets/product/Nike V2K Run.png', '2024-06-04 09:31:02', '36,37'),
(46, 'Jordan 3 x J Balvin Rio', 'Now with 4 highly coveted sneakers in the Jordan x J Balvin line-up, the Colombian-born \"Prince of Reggaeton\" puts his signature spin on the AJ3. This striking edition channels Balvin\'s contagious energy while maintaining classic Jordan style. The colour palette is inspired by the tranquility of a sunset viewed from his Medellín sanctuary, balancing a black leather upper with vibrant bursts of Solar Flare and Total Crimson. Equipped with visible Air cushioning and iconic elephant print overlays, to say the AJ3 is simply legendary would be an understatement. Over the decades, it\'s transcended the hoops game and become a cultural icon. To celebrate the partnership, this nightfall-inspired design comes with a key ring and special packaging that reads: \"A sunset always reminds me, a new day full of opportunity is coming\". Wise words. So, don\'t let the opportunity pass you by. \r\n', 11895.00, 'assets/product/Jordan 3 x J Balvin Rio.png', '2024-06-04 09:53:16', '42,43,44'),
(47, 'Nike Air Terra Humara “Alchemy Pink”', 'In \'97, the Air Terra Humara was born for bold, brave wanderers. The trailblazers charging through the woods with destinations unknown. Serving the pioneers grinding to carve a new path for themselves in running and life, the rugged trail-running classic was the answer. Back for a victory lap on the streets, this lively edition of the Air Terra Humara brings a layered look thanks to Alchemy Pink suede, Diffused Blue woven textiles and University Gold accents. A lugged outsole and plush foam midsole come equipped with a Max Air unit in the heel, keeping you grounded in cushioned comfort.', 7895.00, 'assets/product/Nike Air Terra Humara “Alchemy Pink”.png', '2024-06-04 09:55:44', '37,42'),
(48, 'Nike Air Jordan 3 “Wings”', 'The Air Jordan 3 Retro Wings come in a white, fire red, off-white, vintage green, and rose colorway. Off-white elephant print in traditional places highlights the white-based leather upper. A minor hit of fire red can be seen via the Jumpman branding on the tongue and the top and bottom eyelets, while vintage green lands on the middle TPU eyelets and sockliner. Contrasting the smooth white leather around the upper is canvas in an off-white hue that features rose-printed graphics. This rose motif continues onto the heel with a semi-translucent mismatched Jumpman and Nike Air-branded tab revealing a pink rose photo below and the quote split between the inside tongue tag, \"Up, rose I too, have thorns.\" Rounding out the look is the white rubber midsole with a pink Air unit in the heel paired with a pink and red rubber outsole.\r\n', 12337.00, 'assets/product/Nike Air Jordan 3 “Wings”.png', '2024-06-04 09:57:01', '37,38,40,41'),
(49, 'Nike P-6000', 'A mash-up of past Pegasus sneakers, the P-6000 takes early-2000s running to modern heights. Featuring airy mesh and sporty lines, it\'s the perfect mix of head-turning looks and breathable comfort. Plus, its foam cushioning adds a lifted, athletics-inspired stance and unbelievable cushioning.\r\n', 6195.00, 'assets/product/Nike P-6000 (1).png', '2024-06-04 11:19:13', '36,37,37,39,43,45'),
(50, 'Nike SB Zoom Nyjah 3', 'Light. Effortless. On point. The Nyjah 3 delivers the next iteration of the skate shoe that\'s as extraordinary as Nyjah. Zoom Air cushioning is paired with a honeycomb outsole that\'s grippy yet featherlight.', 5495.00, 'assets/product/Nike SB Zoom Nyjah 3.png', '2024-06-04 11:20:54', '36,37,37,42,44'),
(51, 'Nike SB Dunk Low Pro', 'An \'80s b-ball icon returns with classic details and throwback hoops flair. Channelling vintage style back onto the streets, its padded low-cut collar lets you comfortably take your game anywhere.\r\n', 6195.00, 'assets/product/Nike SB Dunk Low Pro.png', '2024-06-04 11:22:04', '37,38,41,44'),
(52, 'NIKE AIRMAX 1', 'Meet the leader of the pack. Walking on clouds above the noise, the Air Max 1 blends timeless design with cushioned comfort. Sporting a fast-paced look, wavy mudguard and Nike Air, this classic icon hit the scene in \'87 and continues to be the soul of the franchise today.\r\n', 4995.00, 'assets/product/airmax 1.png', '2024-06-04 11:24:16', '37,37'),
(53, 'Nike SB Zoom Janoski OG+', 'Coming in hot after a year of R&R (research and redesign), the Janoski continues the journey of Stefan\'s signature skateboarding line. The skate-specific tread helps deliver great boardfeel and flick. A re-engineered upper helps give you better fit and comfort, opening the door to next-level performance.\r\n', 5195.00, 'assets/product/Nike SB Zoom Janoski OG+.png', '2024-06-04 11:27:04', '36,36,41,42,44'),
(54, 'Nike SB Force 58', 'The latest and greatest innovation to hit the streets, the Force 58 gives you the durability of a cupsole with the flexibility of vulcanised shoes. Made from canvas and suede and finished with perforations on the toe, the whole look is infused with heritage basketball DNA.', 4095.00, 'assets/product/Nike SB Force 58.png', '2024-06-04 11:28:14', '37,36,38,42,44,45'),
(55, 'AE 1 VELOCITY BLUE BASKETBALL SHOES', 'Lace up in the style of one of the game\'s emerging superstars. These signature sneakers from Adidas Basketball and Anthony Edwards are built for certified bucket getters. The combined BOOST and Lightstrike midsole is ultra-lightweight and adds outstanding energy return to your most explosive moves. A rubber outsole provides all the support you need to attack the hoop, while signature Anthony Edwards branding completes the look.', 7100.00, 'assets/product/AE 1 VELOCITY(Side).png', '2024-06-04 11:30:36', '37,36,37,39,42,43,44,45'),
(57, 'Air Jordan 6 “Reverse Oreo”', 'The Air Jordan 6 Retro \'Reverse Oreo\' rocks a white leather upper with perforations throughout the midfoot and collar areas. A black two-hole tongue and a matching, speckled heel tab provide bold contrast. The Jumpman logo adorns the neoprene lace cover and back heel. White speckles land once again on the black midsole, equipped with an Air-sole heel unit and anchored by a see-through rubber outsole.\r\n', 11749.00, 'assets/product/Air Jordan 6 “Reverse Oreo”(Side).png', '2024-06-04 11:32:45', '40,41,42,43,44,45');

-- --------------------------------------------------------

--
-- Table structure for table `product_data`
--

CREATE TABLE `product_data` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `isNew` tinyint(1) DEFAULT 1,
  `onDiscount` tinyint(1) DEFAULT 0,
  `Discount` decimal(5,2) DEFAULT 0.00,
  `DiscountExpirationDate` date DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_data`
--

INSERT INTO `product_data` (`id`, `product_id`, `isNew`, `onDiscount`, `Discount`, `DiscountExpirationDate`, `CategoryID`) VALUES
(1259, 28, 1, 1, 20.00, NULL, 5),
(1260, 29, 1, 1, 8.00, NULL, 5),
(1261, 30, 1, 1, 19.00, NULL, 3),
(1262, 31, 1, 0, 0.00, NULL, 3),
(1263, 32, 1, 0, 0.00, NULL, 3),
(1264, 33, 1, 0, 0.00, NULL, 3),
(1265, 34, 1, 0, 0.00, NULL, 1),
(1266, 35, 1, 0, 0.00, NULL, 2),
(1267, 36, 1, 0, 0.00, NULL, 2),
(1268, 37, 1, 0, 0.00, NULL, 2),
(1269, 38, 1, 0, 0.00, NULL, 2),
(1270, 39, 1, 1, 15.00, NULL, 2),
(1271, 40, 1, 0, 0.00, NULL, 1),
(1272, 41, 1, 1, 5.00, NULL, 1),
(1273, 42, 1, 0, 0.00, NULL, 1),
(1274, 43, 1, 0, 0.00, NULL, 1),
(1275, 44, 1, 1, 13.00, NULL, 1),
(1276, 45, 1, 0, 0.00, NULL, 3),
(1277, 46, 1, 0, 0.00, NULL, 4),
(1278, 47, 1, 0, 0.00, NULL, 4),
(1279, 48, 1, 0, 0.00, NULL, 4),
(1280, 49, 1, 1, 10.00, NULL, 5),
(1281, 50, 1, 1, 5.00, NULL, 5),
(1282, 51, 1, 0, 0.00, NULL, 5),
(1283, 52, 1, 1, 20.00, NULL, 2),
(1284, 53, 1, 0, 0.00, NULL, 5),
(1285, 54, 1, 0, 0.00, NULL, 5),
(1286, 55, 1, 0, 0.00, NULL, 4),
(1288, 57, 1, 0, 0.00, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `product_inventory`
--

CREATE TABLE `product_inventory` (
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `prod_size` varchar(10) NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `out_of_stock_datetime` datetime DEFAULT NULL,
  `replenished_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_inventory`
--

INSERT INTO `product_inventory` (`inventory_id`, `product_id`, `prod_size`, `stock`, `out_of_stock_datetime`, `replenished_datetime`) VALUES
(13, 28, '37', 3, NULL, NULL),
(14, 28, '38', 2, NULL, NULL),
(15, 28, '39', 3, NULL, NULL),
(16, 29, '36', 3, NULL, NULL),
(17, 29, '38', 2, NULL, NULL),
(18, 30, '36', 1, NULL, NULL),
(19, 30, '37', 1, NULL, NULL),
(20, 30, '36', 1, NULL, NULL),
(21, 30, '37', 1, NULL, NULL),
(22, 31, '36', 1, NULL, NULL),
(23, 31, '37', 1, NULL, NULL),
(24, 31, '36', 1, NULL, NULL),
(25, 32, '36', 1, NULL, NULL),
(26, 32, '37', 1, NULL, NULL),
(27, 33, '36', 2, NULL, NULL),
(28, 33, '37', 2, NULL, NULL),
(29, 33, '36', 2, NULL, NULL),
(30, 34, '36', 0, NULL, NULL),
(31, 34, '37', 0, NULL, NULL),
(32, 34, '36', 0, NULL, NULL),
(33, 35, '36', 2, NULL, NULL),
(34, 35, '37', 2, NULL, NULL),
(35, 35, '36', 2, NULL, NULL),
(36, 35, '37', 2, NULL, NULL),
(37, 36, '36', 1, NULL, NULL),
(38, 36, '37', 1, NULL, NULL),
(39, 36, '36', 1, NULL, NULL),
(40, 36, '37', 1, NULL, NULL),
(41, 36, '38', 1, NULL, NULL),
(42, 37, '36', 1, NULL, NULL),
(43, 37, '37', 1, NULL, NULL),
(44, 37, '36', 1, NULL, NULL),
(45, 37, '37', 1, NULL, NULL),
(46, 37, '38', 1, NULL, NULL),
(47, 38, '36', 1, NULL, NULL),
(48, 38, '37', 1, NULL, NULL),
(49, 38, '36', 1, NULL, NULL),
(50, 38, '37', 1, NULL, NULL),
(51, 38, '38', 1, NULL, NULL),
(52, 39, '36', 1, NULL, NULL),
(53, 39, '37', 1, NULL, NULL),
(54, 39, '36', 1, NULL, NULL),
(55, 39, '37', 1, NULL, NULL),
(56, 39, '38', 1, NULL, NULL),
(57, 39, '39', 1, NULL, NULL),
(58, 40, '40', 0, NULL, NULL),
(59, 40, '41', 0, NULL, NULL),
(60, 40, '42', 1, NULL, NULL),
(61, 40, '43', 1, NULL, NULL),
(62, 40, '44', 1, NULL, NULL),
(63, 40, '45', 1, NULL, NULL),
(64, 41, '40', 0, NULL, NULL),
(65, 41, '41', 1, NULL, NULL),
(66, 41, '42', 0, NULL, NULL),
(67, 41, '43', 1, NULL, NULL),
(68, 41, '44', 1, NULL, NULL),
(69, 41, '45', 1, NULL, NULL),
(70, 42, '41', 1, NULL, NULL),
(71, 42, '43', 1, NULL, NULL),
(72, 42, '45', 1, NULL, NULL),
(73, 43, '42', 2, NULL, NULL),
(74, 43, '44', 2, NULL, NULL),
(75, 44, '36', 1, NULL, NULL),
(76, 44, '37', 1, NULL, NULL),
(77, 44, '43', 1, NULL, NULL),
(78, 44, '44', 1, NULL, NULL),
(79, 45, '36', 1, NULL, NULL),
(80, 45, '37', 1, NULL, NULL),
(81, 46, '42', 4, NULL, NULL),
(82, 46, '43', 4, NULL, NULL),
(83, 46, '44', 4, NULL, NULL),
(84, 47, '37', 1, NULL, NULL),
(85, 47, '42', 1, NULL, NULL),
(86, 48, '37', 1, NULL, NULL),
(87, 48, '38', 1, NULL, NULL),
(88, 48, '40', 1, NULL, NULL),
(89, 48, '41', 1, NULL, NULL),
(90, 49, '36', 1, NULL, NULL),
(91, 49, '37', 1, NULL, NULL),
(92, 49, '37', 1, NULL, NULL),
(93, 49, '39', 1, NULL, NULL),
(94, 49, '43', 1, NULL, NULL),
(95, 49, '45', 1, NULL, NULL),
(96, 50, '36', 1, NULL, NULL),
(97, 50, '37', 1, NULL, NULL),
(98, 50, '37', 1, NULL, NULL),
(99, 50, '42', 1, NULL, NULL),
(100, 50, '44', 1, NULL, NULL),
(101, 51, '37', 1, NULL, NULL),
(102, 51, '38', 1, NULL, NULL),
(103, 51, '41', 1, NULL, NULL),
(104, 51, '44', 1, NULL, NULL),
(105, 52, '37', 1, NULL, NULL),
(106, 52, '37', 1, NULL, NULL),
(107, 53, '36', 1, NULL, NULL),
(108, 53, '36', 1, NULL, NULL),
(109, 53, '41', 1, NULL, NULL),
(110, 53, '42', 1, NULL, NULL),
(111, 53, '44', 1, NULL, NULL),
(112, 54, '37', 1, NULL, NULL),
(113, 54, '36', 1, NULL, NULL),
(114, 54, '38', 1, NULL, NULL),
(115, 54, '42', 1, NULL, NULL),
(116, 54, '44', 1, NULL, NULL),
(117, 54, '45', 1, NULL, NULL),
(118, 55, '37', 1, NULL, NULL),
(119, 55, '36', 1, NULL, NULL),
(120, 55, '37', 1, NULL, NULL),
(121, 55, '39', 1, NULL, NULL),
(122, 55, '42', 1, NULL, NULL),
(123, 55, '43', 1, NULL, NULL),
(124, 55, '44', 1, NULL, NULL),
(125, 55, '45', 1, NULL, NULL),
(132, 57, '40', 1, NULL, NULL),
(133, 57, '41', 1, NULL, NULL),
(134, 57, '42', 1, NULL, NULL),
(135, 57, '43', 1, NULL, NULL),
(136, 57, '44', 1, NULL, NULL),
(137, 57, '45', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `size_id` int(11) NOT NULL,
  `sizes_all` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`size_id`, `sizes_all`) VALUES
(1, '36'),
(2, '37'),
(3, '36'),
(4, '37'),
(5, '38'),
(6, '39'),
(7, '40'),
(8, '41'),
(9, '42'),
(10, '43'),
(11, '44'),
(12, '45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `datecreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `password`, `datecreated`) VALUES
(60435, 'marge', 'margelyn@gmail.com', '$2y$10$rcPgQrDXEdCzHntDL2RSp.BrH5iDSV0BU9BJmUfm3p8ITIGVHZw9S', '2024-06-02 14:41:50'),
(60436, 'jame', 'bjamewel29@gmail.com', '$2y$10$goam1FMN1SyNWSlqtXg.NOepmDObNTqIT0l.SbLy0mZOLos/HYnTu', '2024-06-02 23:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `user_info_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`user_info_id`, `user_id`, `name`, `address`, `email`, `phone_number`) VALUES
(2, 60435, 'Margelyn Librodo', 'Pasig', 'margelyn@gmail.com', '0912345678'),
(3, 60436, 'Jamewel Bane', 'B1 Lot1 Honey Dew St., Nagpayong, Pasig City', 'bjamewel29@gmail.com', '0910257967');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cancellation_request`
--
ALTER TABLE `cancellation_request`
  ADD PRIMARY KEY (`cancel_id`),
  ADD KEY `cancellation_request_ibfk_1` (`txn`),
  ADD KEY `cancellation_request_ibfk_2` (`userid`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `transaction_number` (`transaction_number`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`order_list_id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`Product_id`);

--
-- Indexes for table `product_data`
--
ALTER TABLE `product_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `FK_Category` (`CategoryID`);

--
-- Indexes for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`size_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`user_info_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8694;

--
-- AUTO_INCREMENT for table `cancellation_request`
--
ALTER TABLE `cancellation_request`
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `order_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `Product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `product_data`
--
ALTER TABLE `product_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1291;

--
-- AUTO_INCREMENT for table `product_inventory`
--
ALTER TABLE `product_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60437;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `user_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cancellation_request`
--
ALTER TABLE `cancellation_request`
  ADD CONSTRAINT `cancellation_request_ibfk_1` FOREIGN KEY (`txn`) REFERENCES `orders` (`transaction_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cancellation_request_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cancellation_request_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`Product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `order_list`
--
ALTER TABLE `order_list`
  ADD CONSTRAINT `order_list_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `product_data`
--
ALTER TABLE `product_data`
  ADD CONSTRAINT `FK_Category` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`),
  ADD CONSTRAINT `product_data_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`Product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD CONSTRAINT `product_inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`Product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `user_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
