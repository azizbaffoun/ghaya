-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2025 at 03:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ghaya1`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Size', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 'Color', '2025-10-10 05:30:48', '2025-10-10 05:30:48');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

CREATE TABLE `attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `attribute_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'Small', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 1, 'Medium', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(3, 1, 'Large', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(4, 2, 'Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(5, 2, 'Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(6, 2, 'Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_value_translations`
--

CREATE TABLE `attribute_value_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attribute_value_id` bigint(20) UNSIGNED NOT NULL,
  `language_code` varchar(5) NOT NULL,
  `translated_value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_value_translations`
--

INSERT INTO `attribute_value_translations` (`id`, `attribute_value_id`, `language_code`, `translated_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Small', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 1, 'de', 'Klein', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(3, 1, 'es', 'Pequeño', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(4, 1, 'fr', 'Small', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(5, 2, 'en', 'Medium', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(6, 2, 'de', 'Mittel', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(7, 2, 'es', 'Mediano', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(8, 2, 'fr', 'Medium', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(9, 3, 'en', 'Large', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(10, 3, 'de', 'Groß', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(11, 3, 'es', 'Grande', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(12, 3, 'fr', 'Large', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(13, 4, 'en', 'Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(14, 4, 'de', 'Rot', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(15, 4, 'es', 'Rojo', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(16, 4, 'fr', 'Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(17, 5, 'en', 'Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(18, 5, 'de', 'Blau', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(19, 5, 'es', 'Azul', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(20, 5, 'fr', 'Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(21, 6, 'en', 'Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(22, 6, 'de', 'Schwarz', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(23, 6, 'es', 'Negro', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(24, 6, 'fr', 'Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `type` enum('promotion','sale','seasonal','featured','announcement') NOT NULL DEFAULT 'promotion',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `status`, `type`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'promotion', '2025-10-10 05:30:51', '2025-10-10 05:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `banner_translations`
--

CREATE TABLE `banner_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `banner_id` bigint(20) UNSIGNED NOT NULL,
  `language_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banner_translations`
--

INSERT INTO `banner_translations` (`id`, `banner_id`, `language_code`, `title`, `description`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Ready to Shop', 'Your one-stop shop for everything you need.', 'banners/shoes-ready.png', '2025-10-10 05:30:52', '2025-10-10 05:30:52'),
(2, 1, 'de', 'Bereit zum Einkaufen', 'Ihr One-Stop-Shop für alles, was Sie brauchen.', 'banners/shoes-ready.png', '2025-10-10 05:30:52', '2025-10-10 05:30:52'),
(3, 1, 'es', 'Listo para comprar', 'Tu tienda única para todo lo que necesitas.', 'banners/shoes-ready.png', '2025-10-10 05:30:53', '2025-10-10 05:30:53'),
(4, 1, 'fr', 'Ready to Shop', 'Your one-stop shop for everything you need.', 'banners/shoes-ready.png', '2025-10-10 05:30:53', '2025-10-10 05:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','discontinued') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `slug`, `logo_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'awesome-brand', 'brands/logo-ready.png', 'active', '2025-10-10 05:30:47', '2025-10-10 05:30:47');

-- --------------------------------------------------------

--
-- Table structure for table `brand_translations`
--

CREATE TABLE `brand_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brand_translations`
--

INSERT INTO `brand_translations` (`id`, `brand_id`, `locale`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Awesome Brand', 'A high-quality brand known for its awesome products.', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(2, 1, 'de', 'Großartige Marke', 'Eine hochwertige Marke, bekannt für ihre großartigen Produkte.', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(3, 1, 'es', 'Marca Asombrosa', 'Una marca de alta calidad conocida por sus productos asombrosos.', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(4, 1, 'fr', 'Awesome Brand', 'A high-quality brand known for its awesome products.', '2025-10-10 05:30:47', '2025-10-10 05:30:47');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `slug`, `parent_category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'electronics', NULL, 1, '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(2, 'fashion', NULL, 1, '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(3, 'smartphones', 1, 1, '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(4, 't-shirts', 2, 1, '2025-10-10 05:30:47', '2025-10-10 05:30:47');

-- --------------------------------------------------------

--
-- Table structure for table `category_translations`
--

CREATE TABLE `category_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `language_code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_translations`
--

INSERT INTO `category_translations` (`id`, `category_id`, `language_code`, `name`, `description`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Electronics', 'Electronic devices', 'categories/07-300x300-1-1-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(2, 1, 'de', 'Electronics', 'Electronic devices', 'categories/07-300x300-1-1-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(3, 1, 'es', 'Electrónica', 'Dispositivos electrónicos', 'categories/07-300x300-1-1-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(4, 1, 'fr', 'Électronique', 'Appareils électroniques', 'categories/07-300x300-1-1-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(5, 2, 'en', 'Fashion', 'Clothing and accessories', 'categories/cat7-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(6, 2, 'de', 'Fashion', 'Clothing and accessories', 'categories/cat7-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(7, 2, 'es', 'Moda', 'Ropa y accesorios', 'categories/cat7-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(8, 2, 'fr', 'Mode', 'Vêtements et accessoires', 'categories/cat7-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(9, 3, 'en', 'Smartphones', 'Latest mobile phones', 'categories/cat1-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(10, 3, 'de', 'Smartphones', 'Latest mobile phones', 'categories/cat1-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(11, 3, 'es', 'Smartphones', 'Últimos teléfonos móviles', 'categories/cat1-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(12, 3, 'fr', 'Smartphones', 'Derniers téléphones mobiles', 'categories/cat1-removebg-preview.png', '2025-10-10 05:30:47', '2025-10-10 05:30:47'),
(13, 4, 'en', 'T-Shirts', 'Casual wear t-shirts', 'categories/cat2-removebg-preview.png', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(14, 4, 'de', 'T-Shirts', 'Casual wear t-shirts', 'categories/cat2-removebg-preview.png', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(15, 4, 'es', 'Camisetas', 'Camisetas informales', 'categories/cat2-removebg-preview.png', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(16, 4, 'fr', 'T-shirts', 'T-shirts décontractés', 'categories/cat2-removebg-preview.png', '2025-10-10 05:30:48', '2025-10-10 05:30:48');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `exchange_rate` decimal(10,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `exchange_rate`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', 'USD', '$', 1.0000, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(2, 'Euro', 'EUR', '€', 0.9200, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(3, 'British Pound', 'GBP', '£', 0.7900, '2025-10-10 05:30:46', '2025-10-10 05:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `translated_text` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `translated_text`, `active`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English', NULL, 1, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(2, 'ar', 'Arabic', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(3, 'de', 'German', NULL, 1, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(4, 'es', 'Spanish', NULL, 1, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(5, 'fa', 'Persian', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(6, 'fr', 'French', NULL, 1, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(7, 'hi', 'Hindi', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(8, 'id', 'Indonesian', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(9, 'it', 'Italian', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(10, 'ja', 'Japanese', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(11, 'ko', 'Korean', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(12, 'nl', 'Dutch', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(13, 'pl', 'Polish', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(14, 'pt', 'Portuguese', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(15, 'ru', 'Russian', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(16, 'th', 'Thai', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(17, 'tr', 'Turkish', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(18, 'vi', 'Vietnamese', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(19, 'zh', 'Chinese', NULL, 0, '2025-10-10 05:30:46', '2025-10-10 05:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `title`, `status`, `date`, `created_at`, `updated_at`) VALUES
(1, 'Main Menu', 1, '2025-10-10 05:30:46', '2025-10-10 05:30:46', '2025-10-10 05:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `order_number` int(11) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `slug`, `order_number`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'home', 1, NULL, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(2, 1, 'about', 2, NULL, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(3, 1, 'services', 3, NULL, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(4, 1, 'blog', 4, NULL, '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(5, 1, 'contact', 5, NULL, '2025-10-10 05:30:46', '2025-10-10 05:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item_translations`
--

CREATE TABLE `menu_item_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `language_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_item_translations`
--

INSERT INTO `menu_item_translations` (`id`, `menu_item_id`, `language_code`, `title`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Home', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(2, 1, 'fr', 'Accueil', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(3, 1, 'es', 'Inicio', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(4, 2, 'en', 'About Us', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(5, 2, 'fr', 'À propos', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(6, 2, 'es', 'Sobre nosotros', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(7, 3, 'en', 'Our Services', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(8, 3, 'fr', 'Nos services', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(9, 3, 'es', 'Nuestros servicios', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(10, 4, 'en', 'Blog', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(11, 4, 'fr', 'Blog', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(12, 4, 'es', 'Blog', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(13, 5, 'en', 'Contact Us', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(14, 5, 'fr', 'Contact', '2025-10-10 05:30:46', '2025-10-10 05:30:46'),
(15, 5, 'es', 'Contacto', '2025-10-10 05:30:46', '2025-10-10 05:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2025_01_18_165323_create_languages_table', 1),
(7, '2025_01_24_064245_create_site_settings_table', 1),
(8, '2025_01_26_105930_create_categories_table', 1),
(9, '2025_01_26_110154_create_brands_table', 1),
(10, '2025_01_26_110546_create_category_translations_table', 1),
(11, '2025_01_26_110739_create_brand_translations_table', 1),
(12, '2025_02_06_123525_create_banners_table', 1),
(13, '2025_02_06_123926_create_banner_translations_table', 1),
(14, '2025_02_12_123941_create_social_media_links_table', 1),
(15, '2025_02_12_124118_create_social_media_link_translations_table', 1),
(16, '2025_02_16_065528_create_menus_table', 1),
(17, '2025_02_16_065802_create_menu_items_table', 1),
(18, '2025_02_16_065921_create_menu_item_translations_table', 1),
(19, '2025_03_16_084022_create_vendors_table', 1),
(20, '2025_03_16_084048_create_shops_table', 1),
(21, '2025_03_16_084108_create_customers_table', 1),
(22, '2025_03_16_084130_create_currencies_table', 1),
(23, '2025_03_16_084156_create_coupons_table', 1),
(24, '2025_03_16_084254_create_products_table', 1),
(25, '2025_03_16_084326_create_wishlists_table', 1),
(26, '2025_03_16_084341_create_orders_table', 1),
(27, '2025_03_16_084359_create_order_details_table', 1),
(28, '2025_03_16_084502_create_shipping_addresses_table', 1),
(29, '2025_03_16_085620_create_product_translations_table', 1),
(30, '2025_03_16_102116_create_product_variants_table', 1),
(31, '2025_03_16_102341_create_product_variant_translations_table', 1),
(32, '2025_03_16_103136_create_product_reviews_table', 1),
(33, '2025_03_18_191106_create_store_settings_table', 1),
(34, '2025_03_29_115548_create_attributes_table', 1),
(35, '2025_03_29_115612_create_attribute_values_table', 1),
(36, '2025_03_29_115648_create_attribute_value_translations_table', 1),
(37, '2025_03_29_115733_create_product_attribute_values_table', 1),
(38, '2025_03_31_170450_create_product_images_table', 1),
(39, '2025_04_05_071654_create_product_variant_attribute_values_table', 1),
(40, '2025_05_29_084501_create_pages_table', 1),
(41, '2025_05_29_084747_create_page_translations_table', 1),
(42, '2025_09_01_041746_create_payment_gateways_table', 1),
(43, '2025_09_01_041758_create_payment_gateway_configs_table', 1),
(44, '2025_09_01_043045_create_payment_methods_table', 1),
(45, '2025_09_01_043405_create_payments_table', 1),
(46, '2025_09_01_043632_create_refunds_table', 1),
(47, '2025_09_24_000001_add_avatar_to_vendors_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','canceled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_translations`
--

CREATE TABLE `page_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_id` bigint(20) UNSIGNED NOT NULL,
  `language_code` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `gateway_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `status` enum('pending','processing','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response`)),
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway_configs`
--

CREATE TABLE `payment_gateway_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gateway_id` bigint(20) UNSIGNED NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `key_value` text NOT NULL,
  `is_encrypted` tinyint(1) NOT NULL DEFAULT 0,
  `environment` varchar(255) NOT NULL DEFAULT 'sandbox',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `gateway_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('card','bank','wallet','upi','paypal','crypto','cod','bnpl','other') NOT NULL DEFAULT 'card',
  `token` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_type` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `shop_id`, `vendor_id`, `slug`, `category_id`, `brand_id`, `product_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'cool-tshirt', 1, 1, 'variable', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 1, 1, 'sport-shoes', 1, 1, 'variable', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(3, 1, 1, 'wireless-headphones', 1, 1, 'variable', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(4, 1, 1, 'travel-backpack', 1, 1, 'variable', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_value_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`id`, `product_id`, `attribute_value_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 1, 4, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(3, 1, 5, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(4, 1, 6, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(5, 1, 2, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(6, 1, 3, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(7, 2, 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(8, 2, 4, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(9, 2, 5, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(10, 2, 6, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(11, 2, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(12, 2, 3, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(13, 3, 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(14, 3, 4, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(15, 3, 5, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(16, 3, 6, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(17, 3, 2, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(18, 3, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(19, 4, 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(20, 4, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(21, 4, 5, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(22, 4, 6, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(23, 4, 2, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(24, 4, 3, '2025-10-10 05:30:51', '2025-10-10 05:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `type` enum('thumb','slide') NOT NULL DEFAULT 'thumb',
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `name`, `image_url`, `type`, `product_id`, `variant_id`, `created_at`, `updated_at`) VALUES
(1, 'T-Shirt-removebg-preview.png', 'products/T-Shirt-removebg-preview.png', 'thumb', 1, NULL, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 'images-removebg-preview.png', 'products/images-removebg-preview.png', 'thumb', 2, NULL, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(3, 'images-1-removebg-preview-2.png', 'products/images-1-removebg-preview-2.png', 'thumb', 3, NULL, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(4, 'images-2-removebg-preview-1.png', 'products/images-2-removebg-preview-1.png', 'thumb', 4, NULL, '2025-10-10 05:30:51', '2025-10-10 05:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `review` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_translations`
--

CREATE TABLE `product_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `language_code` varchar(5) NOT NULL DEFAULT 'en',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_translations`
--

INSERT INTO `product_translations` (`id`, `product_id`, `language_code`, `name`, `description`, `short_description`, `tags`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Cool T-Shirt', 'Trendy T-Shirt available in multiple sizes and colors.', NULL, NULL, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 1, 'de', 'Cooles T-Shirt', 'Trendiges T-Shirt in verschiedenen Größen und Farben erhältlich.', NULL, NULL, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(3, 1, 'es', 'Camiseta genial', 'Camiseta moderna disponible en varios tamaños y colores.', NULL, NULL, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(4, 1, 'fr', 'Cool T-Shirt', 'Trendy T-Shirt available in multiple sizes and colors.', NULL, NULL, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(5, 2, 'en', 'Sport Shoes', 'Comfortable sport shoes for daily use.', NULL, NULL, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(6, 2, 'de', 'Sportschuhe', 'Bequeme Sportschuhe für den täglichen Gebrauch.', NULL, NULL, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(7, 2, 'es', 'Zapatillas deportivas', 'Zapatillas deportivas cómodas para uso diario.', NULL, NULL, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(8, 2, 'fr', 'Sport Shoes', 'Comfortable sport shoes for daily use.', NULL, NULL, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(9, 3, 'en', 'Wireless Headphones', 'Noise-cancelling wireless headphones with long battery life.', NULL, NULL, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(10, 3, 'de', 'Kabellose Kopfhörer', 'Kabellose Kopfhörer mit Geräuschunterdrückung und langer Akkulaufzeit.', NULL, NULL, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(11, 3, 'es', 'Auriculares inalámbricos', 'Auriculares inalámbricos con cancelación de ruido y batería de larga duración.', NULL, NULL, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(12, 3, 'fr', 'Wireless Headphones', 'Noise-cancelling wireless headphones with long battery life.', NULL, NULL, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(13, 4, 'en', 'Travel Backpack', 'Durable backpack for travel and outdoor activities.', NULL, NULL, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(14, 4, 'de', 'Reiserucksack', 'Robuster Rucksack für Reisen und Outdoor-Aktivitäten.', NULL, NULL, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(15, 4, 'es', 'Mochila de viaje', 'Mochila duradera para viajes y actividades al aire libre.', NULL, NULL, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(16, 4, 'fr', 'Travel Backpack', 'Durable backpack for travel and outdoor activities.', NULL, NULL, '2025-10-10 05:30:50', '2025-10-10 05:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_slug` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `SKU` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `variant_slug`, `price`, `discount_price`, `stock`, `SKU`, `barcode`, `weight`, `dimensions`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 1, 'cool-t-shirt-small-red-68e8a8186d2d7', 51.00, 26.00, 64, 'SRe679', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 1, 'cool-t-shirt-small-blue-68e8a818726d1', 47.00, 33.00, 86, 'SBl585', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(3, 1, 'cool-t-shirt-small-black-68e8a8187476a', 36.00, 32.00, 64, 'SBl390', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(4, 1, 'cool-t-shirt-medium-red-68e8a8187561b', 33.00, 30.00, 180, 'MRe240', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(5, 1, 'cool-t-shirt-medium-blue-68e8a8187674d', 43.00, 27.00, 123, 'MBl141', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(6, 1, 'cool-t-shirt-medium-black-68e8a818777ad', 39.00, 15.00, 196, 'MBl855', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(7, 1, 'cool-t-shirt-large-red-68e8a818786f0', 39.00, 35.00, 81, 'LRe875', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(8, 1, 'cool-t-shirt-large-blue-68e8a818795a2', 44.00, 28.00, 60, 'LBl102', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(9, 1, 'cool-t-shirt-large-black-68e8a8187a36a', 53.00, 26.00, 160, 'LBl142', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(10, 2, 'sport-shoes-small-red-68e8a819da646', 29.00, 26.00, 143, 'SRe559', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(11, 2, 'sport-shoes-small-blue-68e8a819dbc5d', 24.00, 12.00, 64, 'SBl881', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(12, 2, 'sport-shoes-small-black-68e8a819dde2a', 53.00, 11.00, 151, 'SBl743', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(13, 2, 'sport-shoes-medium-red-68e8a819deecd', 38.00, 23.00, 161, 'MRe279', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(14, 2, 'sport-shoes-medium-blue-68e8a819e11eb', 25.00, 17.00, 73, 'MBl644', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(15, 2, 'sport-shoes-medium-black-68e8a819e243f', 26.00, 17.00, 179, 'MBl721', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(16, 2, 'sport-shoes-large-red-68e8a819e32ad', 48.00, 20.00, 72, 'LRe842', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(17, 2, 'sport-shoes-large-blue-68e8a819e4f77', 52.00, 47.00, 154, 'LBl827', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(18, 2, 'sport-shoes-large-black-68e8a819e6054', 40.00, 16.00, 97, 'LBl950', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(19, 3, 'wireless-headphones-small-red-68e8a81a8dd59', 36.00, 19.00, 58, 'SRe948', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(20, 3, 'wireless-headphones-small-blue-68e8a81a8f2aa', 42.00, 20.00, 110, 'SBl843', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(21, 3, 'wireless-headphones-small-black-68e8a81a9059a', 44.00, 42.00, 166, 'SBl364', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(22, 3, 'wireless-headphones-medium-red-68e8a81a91734', 32.00, 26.00, 123, 'MRe954', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(23, 3, 'wireless-headphones-medium-blue-68e8a81a938fa', 56.00, 19.00, 90, 'MBl288', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(24, 3, 'wireless-headphones-medium-black-68e8a81a94ae5', 39.00, 25.00, 173, 'MBl792', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(25, 3, 'wireless-headphones-large-red-68e8a81a95b42', 52.00, 17.00, 115, 'LRe951', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(26, 3, 'wireless-headphones-large-blue-68e8a81a96c57', 21.00, 21.00, 79, 'LBl150', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(27, 3, 'wireless-headphones-large-black-68e8a81a97b7d', 48.00, 28.00, 164, 'LBl852', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(28, 4, 'travel-backpack-small-red-68e8a81bc56d7', 31.00, 11.00, 185, 'SRe954', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(29, 4, 'travel-backpack-small-blue-68e8a81bc6e3f', 41.00, 40.00, 80, 'SBl308', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(30, 4, 'travel-backpack-small-black-68e8a81bc7e8e', 39.00, 12.00, 81, 'SBl489', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(31, 4, 'travel-backpack-medium-red-68e8a81bc90bf', 28.00, 22.00, 88, 'MRe827', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(32, 4, 'travel-backpack-medium-blue-68e8a81bca16b', 52.00, 30.00, 169, 'MBl106', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(33, 4, 'travel-backpack-medium-black-68e8a81bcaf8c', 23.00, 18.00, 142, 'MBl255', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(34, 4, 'travel-backpack-large-red-68e8a81bcbdb8', 40.00, 34.00, 135, 'LRe226', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(35, 4, 'travel-backpack-large-blue-68e8a81bccfe4', 55.00, 15.00, 68, 'LBl490', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(36, 4, 'travel-backpack-large-black-68e8a81bce106', 44.00, 16.00, 128, 'LBl295', NULL, 0.50, '10x10x2 cm', 1, '2025-10-10 05:30:51', '2025-10-10 05:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_attribute_values`
--

CREATE TABLE `product_variant_attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_value_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variant_attribute_values`
--

INSERT INTO `product_variant_attribute_values` (`id`, `product_variant_id`, `attribute_value_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 1, 4, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(3, 2, 1, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(4, 2, 5, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(5, 3, 1, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(6, 3, 6, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(7, 4, 2, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(8, 4, 4, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(9, 5, 2, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(10, 5, 5, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(11, 6, 2, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(12, 6, 6, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(13, 7, 3, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(14, 7, 4, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(15, 8, 3, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(16, 8, 5, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(17, 9, 3, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(18, 9, 6, 1, '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(19, 10, 1, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(20, 10, 4, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(21, 11, 1, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(22, 11, 5, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(23, 12, 1, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(24, 12, 6, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(25, 13, 2, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(26, 13, 4, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(27, 14, 2, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(28, 14, 5, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(29, 15, 2, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(30, 15, 6, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(31, 16, 3, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(32, 16, 4, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(33, 17, 3, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(34, 17, 5, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(35, 18, 3, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(36, 18, 6, 2, '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(37, 19, 1, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(38, 19, 4, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(39, 20, 1, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(40, 20, 5, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(41, 21, 1, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(42, 21, 6, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(43, 22, 2, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(44, 22, 4, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(45, 23, 2, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(46, 23, 5, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(47, 24, 2, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(48, 24, 6, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(49, 25, 3, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(50, 25, 4, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(51, 26, 3, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(52, 26, 5, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(53, 27, 3, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(54, 27, 6, 3, '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(55, 28, 1, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(56, 28, 4, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(57, 29, 1, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(58, 29, 5, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(59, 30, 1, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(60, 30, 6, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(61, 31, 2, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(62, 31, 4, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(63, 32, 2, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(64, 32, 5, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(65, 33, 2, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(66, 33, 6, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(67, 34, 3, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(68, 34, 4, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(69, 35, 3, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(70, 35, 5, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(71, 36, 3, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(72, 36, 6, 4, '2025-10-10 05:30:51', '2025-10-10 05:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_translations`
--

CREATE TABLE `product_variant_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED NOT NULL,
  `language_code` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variant_translations`
--

INSERT INTO `product_variant_translations` (`id`, `product_variant_id`, `language_code`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'Small - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(2, 1, 'de', 'Small - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(3, 1, 'es', 'Small - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(4, 1, 'fr', 'Small - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(5, 2, 'en', 'Small - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(6, 2, 'de', 'Small - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(7, 2, 'es', 'Small - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(8, 2, 'fr', 'Small - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(9, 3, 'en', 'Small - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(10, 3, 'de', 'Small - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(11, 3, 'es', 'Small - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(12, 3, 'fr', 'Small - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(13, 4, 'en', 'Medium - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(14, 4, 'de', 'Medium - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(15, 4, 'es', 'Medium - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(16, 4, 'fr', 'Medium - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(17, 5, 'en', 'Medium - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(18, 5, 'de', 'Medium - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(19, 5, 'es', 'Medium - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(20, 5, 'fr', 'Medium - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(21, 6, 'en', 'Medium - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(22, 6, 'de', 'Medium - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(23, 6, 'es', 'Medium - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(24, 6, 'fr', 'Medium - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(25, 7, 'en', 'Large - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(26, 7, 'de', 'Large - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(27, 7, 'es', 'Large - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(28, 7, 'fr', 'Large - Red', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(29, 8, 'en', 'Large - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(30, 8, 'de', 'Large - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(31, 8, 'es', 'Large - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(32, 8, 'fr', 'Large - Blue', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(33, 9, 'en', 'Large - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(34, 9, 'de', 'Large - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(35, 9, 'es', 'Large - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(36, 9, 'fr', 'Large - Black', '2025-10-10 05:30:48', '2025-10-10 05:30:48'),
(37, 10, 'en', 'Small - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(38, 10, 'de', 'Small - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(39, 10, 'es', 'Small - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(40, 10, 'fr', 'Small - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(41, 11, 'en', 'Small - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(42, 11, 'de', 'Small - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(43, 11, 'es', 'Small - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(44, 11, 'fr', 'Small - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(45, 12, 'en', 'Small - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(46, 12, 'de', 'Small - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(47, 12, 'es', 'Small - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(48, 12, 'fr', 'Small - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(49, 13, 'en', 'Medium - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(50, 13, 'de', 'Medium - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(51, 13, 'es', 'Medium - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(52, 13, 'fr', 'Medium - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(53, 14, 'en', 'Medium - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(54, 14, 'de', 'Medium - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(55, 14, 'es', 'Medium - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(56, 14, 'fr', 'Medium - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(57, 15, 'en', 'Medium - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(58, 15, 'de', 'Medium - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(59, 15, 'es', 'Medium - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(60, 15, 'fr', 'Medium - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(61, 16, 'en', 'Large - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(62, 16, 'de', 'Large - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(63, 16, 'es', 'Large - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(64, 16, 'fr', 'Large - Red', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(65, 17, 'en', 'Large - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(66, 17, 'de', 'Large - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(67, 17, 'es', 'Large - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(68, 17, 'fr', 'Large - Blue', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(69, 18, 'en', 'Large - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(70, 18, 'de', 'Large - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(71, 18, 'es', 'Large - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(72, 18, 'fr', 'Large - Black', '2025-10-10 05:30:49', '2025-10-10 05:30:49'),
(73, 19, 'en', 'Small - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(74, 19, 'de', 'Small - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(75, 19, 'es', 'Small - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(76, 19, 'fr', 'Small - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(77, 20, 'en', 'Small - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(78, 20, 'de', 'Small - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(79, 20, 'es', 'Small - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(80, 20, 'fr', 'Small - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(81, 21, 'en', 'Small - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(82, 21, 'de', 'Small - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(83, 21, 'es', 'Small - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(84, 21, 'fr', 'Small - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(85, 22, 'en', 'Medium - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(86, 22, 'de', 'Medium - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(87, 22, 'es', 'Medium - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(88, 22, 'fr', 'Medium - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(89, 23, 'en', 'Medium - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(90, 23, 'de', 'Medium - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(91, 23, 'es', 'Medium - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(92, 23, 'fr', 'Medium - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(93, 24, 'en', 'Medium - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(94, 24, 'de', 'Medium - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(95, 24, 'es', 'Medium - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(96, 24, 'fr', 'Medium - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(97, 25, 'en', 'Large - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(98, 25, 'de', 'Large - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(99, 25, 'es', 'Large - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(100, 25, 'fr', 'Large - Red', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(101, 26, 'en', 'Large - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(102, 26, 'de', 'Large - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(103, 26, 'es', 'Large - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(104, 26, 'fr', 'Large - Blue', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(105, 27, 'en', 'Large - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(106, 27, 'de', 'Large - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(107, 27, 'es', 'Large - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(108, 27, 'fr', 'Large - Black', '2025-10-10 05:30:50', '2025-10-10 05:30:50'),
(109, 28, 'en', 'Small - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(110, 28, 'de', 'Small - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(111, 28, 'es', 'Small - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(112, 28, 'fr', 'Small - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(113, 29, 'en', 'Small - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(114, 29, 'de', 'Small - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(115, 29, 'es', 'Small - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(116, 29, 'fr', 'Small - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(117, 30, 'en', 'Small - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(118, 30, 'de', 'Small - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(119, 30, 'es', 'Small - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(120, 30, 'fr', 'Small - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(121, 31, 'en', 'Medium - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(122, 31, 'de', 'Medium - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(123, 31, 'es', 'Medium - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(124, 31, 'fr', 'Medium - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(125, 32, 'en', 'Medium - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(126, 32, 'de', 'Medium - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(127, 32, 'es', 'Medium - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(128, 32, 'fr', 'Medium - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(129, 33, 'en', 'Medium - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(130, 33, 'de', 'Medium - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(131, 33, 'es', 'Medium - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(132, 33, 'fr', 'Medium - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(133, 34, 'en', 'Large - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(134, 34, 'de', 'Large - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(135, 34, 'es', 'Large - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(136, 34, 'fr', 'Large - Red', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(137, 35, 'en', 'Large - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(138, 35, 'de', 'Large - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(139, 35, 'es', 'Large - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(140, 35, 'fr', 'Large - Blue', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(141, 36, 'en', 'Large - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(142, 36, 'de', 'Large - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(143, 36, 'es', 'Large - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51'),
(144, 36, 'fr', 'Large - Black', '2025-10-10 05:30:51', '2025-10-10 05:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `status` enum('requested','approved','rejected','completed','failed') NOT NULL DEFAULT 'requested',
  `refund_id` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

CREATE TABLE `shipping_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `vendor_id`, `name`, `slug`, `logo`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Soft Shoes', 'soft-shoes', 'N/A', 'Luxurious comfort in every step. Crafted with premium materials for a soft, stylish, and effortless walking experience. ', 'active', '2025-10-10 05:30:46', '2025-10-10 05:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `tagline` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_media_links`
--

CREATE TABLE `social_media_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('facebook','instagram','tiktok','youtube','x') NOT NULL,
  `platform` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_media_link_translations`
--

CREATE TABLE `social_media_link_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `social_media_link_id` bigint(20) UNSIGNED NOT NULL,
  `language_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE `store_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'default_currency', 'USD', NULL, NULL),
(2, 'meta_title', 'Welcome to Velstore - Your Laravel eCommerce Journey Begins!', NULL, NULL),
(3, 'meta_description', 'Welcome to Velstore! You have successfully installed the ultimate Laravel eCommerce boilerplate. Set up your store, configure settings, and start selling with a powerful multi-vendor, multilingual platform.', NULL, NULL),
(4, 'phone_number', '+1 234 567 890', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@example.com', NULL, '$2y$12$9wb0sbuybreTzHzwTJbyD.R/x.1v/UR4uMIisgYu0mzXTs6acdXWe', NULL, '2025-10-10 05:30:46', '2025-10-10 05:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `email`, `password`, `phone`, `status`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'Seller', 'seller@example.com', '$2y$12$LoeotEMClPIuwoYvn14jC.bBGbZot9G.7B.oda6MT9Tc3I.WT4Oca', '+923001234567', 'active', NULL, '2025-10-10 05:30:46', '2025-10-10 05:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_values_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `attribute_value_translations`
--
ALTER TABLE `attribute_value_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `av_trans_lang_unique` (`attribute_value_id`,`language_code`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_translations`
--
ALTER TABLE `banner_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `banner_translations_banner_id_language_code_unique` (`banner_id`,`language_code`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Indexes for table `brand_translations`
--
ALTER TABLE `brand_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brand_translations_brand_id_locale_unique` (`brand_id`,`locale`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_category_id_foreign` (`parent_category_id`);

--
-- Indexes for table `category_translations`
--
ALTER TABLE `category_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_translations_category_id_language_code_unique` (`category_id`,`language_code`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_code_unique` (`code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_code_unique` (`code`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_items_slug_unique` (`slug`),
  ADD KEY `menu_items_menu_id_foreign` (`menu_id`),
  ADD KEY `menu_items_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `menu_item_translations`
--
ALTER TABLE `menu_item_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_item_translations_menu_item_id_foreign` (`menu_item_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `page_translations`
--
ALTER TABLE `page_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_translations_page_id_language_code_unique` (`page_id`,`language_code`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_gateway_id_foreign` (`gateway_id`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_gateways_code_unique` (`code`);

--
-- Indexes for table `payment_gateway_configs`
--
ALTER TABLE `payment_gateway_configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_gateway_configs_gateway_id_foreign` (`gateway_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_methods_user_id_foreign` (`user_id`),
  ADD KEY `payment_methods_gateway_id_foreign` (`gateway_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_shop_id_foreign` (`shop_id`),
  ADD KEY `products_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attribute_values_product_id_foreign` (`product_id`),
  ADD KEY `product_attribute_values_attribute_value_id_foreign` (`attribute_value_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_index` (`product_id`),
  ADD KEY `product_images_variant_id_index` (`variant_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_reviews_customer_id_foreign` (`customer_id`),
  ADD KEY `product_reviews_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_translations`
--
ALTER TABLE `product_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_translations_product_id_language_code_unique` (`product_id`,`language_code`),
  ADD KEY `product_translations_product_id_index` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_variant_slug_unique` (`variant_slug`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`SKU`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variant_attribute_values_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `product_variant_attribute_values_attribute_value_id_foreign` (`attribute_value_id`),
  ADD KEY `product_variant_attribute_values_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_variant_translations`
--
ALTER TABLE `product_variant_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pv_translations_lang_unique` (`product_variant_id`,`language_code`),
  ADD KEY `product_variant_translations_language_code_index` (`language_code`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refunds_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_addresses_order_id_foreign` (`order_id`),
  ADD KEY `shipping_addresses_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shops_slug_unique` (`slug`),
  ADD KEY `shops_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media_links`
--
ALTER TABLE `social_media_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media_link_translations`
--
ALTER TABLE `social_media_link_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_media_link_translations_unique` (`social_media_link_id`,`language_code`);

--
-- Indexes for table `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `store_settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_email_unique` (`email`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_customer_id_foreign` (`customer_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attribute_values`
--
ALTER TABLE `attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `attribute_value_translations`
--
ALTER TABLE `attribute_value_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banner_translations`
--
ALTER TABLE `banner_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brand_translations`
--
ALTER TABLE `brand_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category_translations`
--
ALTER TABLE `category_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu_item_translations`
--
ALTER TABLE `menu_item_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page_translations`
--
ALTER TABLE `page_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateway_configs`
--
ALTER TABLE `payment_gateway_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_translations`
--
ALTER TABLE `product_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `product_variant_translations`
--
ALTER TABLE `product_variant_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media_links`
--
ALTER TABLE `social_media_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media_link_translations`
--
ALTER TABLE `social_media_link_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store_settings`
--
ALTER TABLE `store_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attribute_value_translations`
--
ALTER TABLE `attribute_value_translations`
  ADD CONSTRAINT `av_trans_value_fk` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `banner_translations`
--
ALTER TABLE `banner_translations`
  ADD CONSTRAINT `banner_translations_banner_id_foreign` FOREIGN KEY (`banner_id`) REFERENCES `banners` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `brand_translations`
--
ALTER TABLE `brand_translations`
  ADD CONSTRAINT `brand_translations_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_category_id_foreign` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `category_translations`
--
ALTER TABLE `category_translations`
  ADD CONSTRAINT `category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_item_translations`
--
ALTER TABLE `menu_item_translations`
  ADD CONSTRAINT `menu_item_translations_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `page_translations`
--
ALTER TABLE `page_translations`
  ADD CONSTRAINT `page_translations_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_gateway_id_foreign` FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_gateway_configs`
--
ALTER TABLE `payment_gateway_configs`
  ADD CONSTRAINT `payment_gateway_configs_gateway_id_foreign` FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_gateway_id_foreign` FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_methods_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_images_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_translations`
--
ALTER TABLE `product_translations`
  ADD CONSTRAINT `product_translations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  ADD CONSTRAINT `product_variant_attribute_values_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_attribute_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_attribute_values_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variant_translations`
--
ALTER TABLE `product_variant_translations`
  ADD CONSTRAINT `product_variant_translations_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `shipping_addresses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shops`
--
ALTER TABLE `shops`
  ADD CONSTRAINT `shops_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `social_media_link_translations`
--
ALTER TABLE `social_media_link_translations`
  ADD CONSTRAINT `social_media_link_translations_social_media_link_id_foreign` FOREIGN KEY (`social_media_link_id`) REFERENCES `social_media_links` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
