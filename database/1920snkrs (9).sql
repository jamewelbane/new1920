-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2024 at 10:03 AM
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
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `userid`, `product_id`, `prod_size`, `quantity`, `updated_on`) VALUES
(113, 60436, 28, '37', 1, '2024-06-04 06:31:04');

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
(52, 'txn_665d5cade790e', 60435, 1599.20, 'Reduce stock', '2024-06-03 06:03:25', 'Pending', '../user/assets/proof-payment/665d5cade8ab8_proof_payment_1717394605917_ss1.png', '2024-06-03 06:03:25'),
(53, 'txn_665e566bcbeb5', 60436, 1599.20, '', '2024-06-03 23:48:59', 'Confirmed', '../user/assets/proof-payment/665e566bce85a_proof_payment_1717458539765_ss1.png', '2024-06-04 00:47:34'),
(54, 'txn_665e5bd07292c', 60436, 8279.08, 'Cool shoe', '2024-06-04 00:12:00', 'Confirmed', '../user/assets/proof-payment/665e5bd0751e6_proof_payment_1717459920426_ss2.png', '2024-06-04 01:04:44'),
(55, 'txn_665e6818e921b', 60436, 1599.20, '', '2024-06-04 01:04:24', 'Confirmed', '../user/assets/proof-payment/665e6818ea039_proof_payment_1717463064929_ss3.png', '2024-06-04 01:17:57'),
(56, 'txn_665e6a7b9b364', 60436, 8279.08, '', '2024-06-04 01:14:35', 'Confirmed', '../user/assets/proof-payment/665e6a7b9c7ad_proof_payment_1717463675612_ss4.png', '2024-06-04 01:14:54'),
(57, 'txn_665e6b162d518', 60436, 8279.08, '', '2024-06-04 01:17:10', 'Confirmed', '../user/assets/proof-payment/665e6b162e7eb_proof_payment_1717463830171_ss4.png', '2024-06-04 01:17:33'),
(58, 'txn_665e6ee08a461', 60436, 9878.28, '', '2024-06-04 01:33:20', 'Pending', '../user/assets/proof-payment/665e6ee08c625_proof_payment_1717464800531_ss3.png', '2024-06-04 01:33:20');

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
(74, 60435, 28, 'txn_665d5cade790e', '37', 1, 1599.20, '2024-06-03 06:03:25', '2024-06-03 06:03:25'),
(75, 60436, 28, 'txn_665e566bcbeb5', '37', 1, 1599.20, '2024-06-03 23:48:59', '2024-06-03 23:48:59'),
(76, 60436, 29, 'txn_665e5bd07292c', '36', 1, 8279.08, '2024-06-04 00:12:00', '2024-06-04 00:12:00'),
(77, 60436, 28, 'txn_665e6818e921b', '37', 1, 1599.20, '2024-06-04 01:04:24', '2024-06-04 01:04:24'),
(78, 60436, 29, 'txn_665e6a7b9b364', '36', 1, 8279.08, '2024-06-04 01:14:35', '2024-06-04 01:14:35'),
(79, 60436, 29, 'txn_665e6b162d518', '36', 1, 8279.08, '2024-06-04 01:17:10', '2024-06-04 01:17:10'),
(80, 60436, 28, 'txn_665e6ee08a461', '37', 1, 1599.20, '2024-06-04 01:33:20', '2024-06-04 01:33:20'),
(81, 60436, 29, 'txn_665e6ee08a461', '36', 1, 8279.08, '2024-06-04 01:33:20', '2024-06-04 01:33:20');

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
(29, 'NB 600', 'New balance, balance new', 8999.00, 'assets/product/demo3.jpg', '2024-06-01 18:06:00', '36,38');

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
(1260, 29, 1, 1, 8.00, NULL, 5);

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
(13, 28, '37', 4, NULL, NULL),
(14, 28, '38', 2, NULL, NULL),
(15, 28, '39', 3, NULL, NULL),
(16, 29, '36', 3, NULL, NULL),
(17, 29, '38', 2, NULL, NULL);

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
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `order_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `Product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `product_data`
--
ALTER TABLE `product_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1261;

--
-- AUTO_INCREMENT for table `product_inventory`
--
ALTER TABLE `product_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  ADD CONSTRAINT `product_inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`Product_id`);

--
-- Constraints for table `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `user_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
