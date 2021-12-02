-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 21, 2021 at 05:11 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopifyapp_php`
--

-- --------------------------------------------------------

--
-- Table structure for table `shopify_billings`
--

CREATE TABLE `shopify_billings` (
  `id` int(10) UNSIGNED NOT NULL,
  `charge_id` varchar(255) NOT NULL,
  `shop_url` varchar(255) NOT NULL,
  `gid` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shopify_main`
--

CREATE TABLE `shopify_main` (
  `id` int(11) NOT NULL,
  `store_url` varchar(255) NOT NULL,
  `access_token` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shopify_billings`
--
ALTER TABLE `shopify_billings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shop_url` (`shop_url`);

--
-- Indexes for table `shopify_main`
--
ALTER TABLE `shopify_main`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `store_url` (`store_url`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shopify_billings`
--
ALTER TABLE `shopify_billings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shopify_main`
--
ALTER TABLE `shopify_main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
