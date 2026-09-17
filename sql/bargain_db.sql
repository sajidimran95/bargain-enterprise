-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250422.c097b1deca
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 17, 2026 at 02:33 PM
-- Server version: 8.4.3
-- PHP Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bargain_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtype` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `number`, `name`, `type`, `subtype`, `is_active`, `is_system`, `created_at`, `updated_at`) VALUES
(1, '1000', 'Cash', 'asset', 'cash', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, '1010', 'Operating Checking', 'asset', 'bank', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, '1020', 'Business Checking', 'asset', 'bank', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, '1050', 'Undeposited Funds', 'asset', 'other_current', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(5, '1200', 'Accounts Receivable', 'asset', 'ar', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(6, '1300', 'Inventory Asset', 'asset', 'inventory', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(7, '2000', 'Accounts Payable', 'liability', 'ap', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(8, '2200', 'Sales Tax Payable', 'liability', 'tax', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(9, '3000', 'Owner Equity', 'equity', 'equity', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(10, '4000', 'Sales Revenue', 'income', 'sales', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(11, '5000', 'Cost of Goods Sold', 'cogs', 'cogs', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(12, '6000', 'Operating Expenses', 'expense', 'expense', 1, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `created_at`, `updated_at`) VALUES
(1, 1, 'created', 'App\\Models\\CreditMemo', 9, NULL, '{\"total\": \"161.35\", \"status\": \"open\", \"customer_id\": 22, \"credit_number\": \"CM-00009\", \"remaining_credit\": \"161.35\"}', '2026-09-10 15:09:23', '2026-09-10 15:09:23'),
(2, 1, 'created', 'App\\Models\\CreditMemo', 10, NULL, '{\"total\": \"54.15\", \"status\": \"open\", \"customer_id\": 2, \"credit_number\": \"CM-00010\", \"remaining_credit\": \"54.15\"}', '2026-09-10 15:09:36', '2026-09-10 15:09:36'),
(3, 1, 'created', 'App\\Models\\Item', 123, NULL, '{\"sku\": \"435\", \"name\": \"435\", \"is_active\": true, \"sales_price\": \"0.00\"}', '2026-09-15 11:52:23', '2026-09-15 11:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number_mask` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `account_id`, `name`, `bank_name`, `account_number_mask`, `opening_balance`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 'Operating Account', 'Demo First Bank', '****4521', 25000.00, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 3, 'Business Checking', 'Demo First Bank', '****8890', 8500.00, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('bargain-enterprise-cache-setting.value.employees.pay_frequency', 's:8:\"biweekly\";', 2104864882),
('bargain-enterprise-cache-setting.value.employees.payroll_enabled', 'b:0;', 2104864882),
('bargain-enterprise-cache-setting.value.employees.time_entries.1', 'a:3:{i:0;a:4:{s:4:\"date\";s:10:\"2026-09-15\";s:4:\"name\";s:12:\"System Owner\";s:5:\"hours\";s:4:\"8.00\";s:4:\"memo\";s:0:\"\";}i:1;a:4:{s:4:\"date\";s:10:\"2026-09-15\";s:4:\"name\";s:12:\"System Owner\";s:5:\"hours\";s:4:\"8.00\";s:4:\"memo\";s:0:\"\";}i:2;a:4:{s:4:\"date\";s:10:\"2026-09-15\";s:4:\"name\";s:12:\"System Owner\";s:5:\"hours\";s:4:\"8.00\";s:4:\"memo\";s:0:\"\";}}', 2104864893),
('bargain-enterprise-cache-setting.value.inventory.allow_manager_override', 'b:1;', 2104428284),
('bargain-enterprise-cache-setting.value.inventory.negative_policy', 's:4:\"WARN\";', 2104419643),
('bargain-enterprise-cache-setting.value.report_center.favorites.1', 'a:0:{}', 2104863321),
('bargain-enterprise-cache-setting.value.report_center.memorized.1', 'a:0:{}', 2104863321),
('bargain-enterprise-cache-setting.value.report_center.recent.1', 'a:0:{}', 2104863321),
('bargain-enterprise-cache-setting.value.user.1.sidebar_shortcuts', 'N;', 2104865282),
('japspos-cache-setting.value.company.address1', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.company.address2', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.company.city', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.company.country', 's:3:\"USA\";', 2104941345),
('japspos-cache-setting.value.company.email', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.company.fax', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.company.federal_ein', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.company.legal_name', 's:23:\"Bargain Enterprise Inc.\";', 2104941345),
('japspos-cache-setting.value.company.name', 's:23:\"Bargain Enterprise Inc.\";', 2104865666),
('japspos-cache-setting.value.company.phone', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.company.state', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.company.website', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.company.zip', 's:0:\"\";', 2104941345),
('japspos-cache-setting.value.employees.time_entries.1', 'a:3:{i:0;a:4:{s:4:\"date\";s:10:\"2026-09-15\";s:4:\"name\";s:12:\"System Owner\";s:5:\"hours\";s:4:\"8.00\";s:4:\"memo\";s:0:\"\";}i:1;a:4:{s:4:\"date\";s:10:\"2026-09-15\";s:4:\"name\";s:12:\"System Owner\";s:5:\"hours\";s:4:\"8.00\";s:4:\"memo\";s:0:\"\";}i:2;a:4:{s:4:\"date\";s:10:\"2026-09-15\";s:4:\"name\";s:12:\"System Owner\";s:5:\"hours\";s:4:\"8.00\";s:4:\"memo\";s:0:\"\";}}', 2104941355),
('japspos-cache-setting.value.inventory.allow_manager_override', 'b:1;', 2104865568),
('japspos-cache-setting.value.inventory.negative_policy', 's:4:\"WARN\";', 2104865568),
('japspos-cache-setting.value.report.comment.1.customer-open-balance-report', 's:0:\"\";', 2104926073),
('japspos-cache-setting.value.report.comment.1.inventory-stock-report', 's:0:\"\";', 2104941357),
('japspos-cache-setting.value.report.comment.1.sales-by-item-report', 's:0:\"\";', 2105011660),
('japspos-cache-setting.value.report.memorized.1.customer-open-balance-report', 'a:7:{s:10:\"datePreset\";s:9:\"this_year\";s:4:\"from\";s:10:\"2026-01-01\";s:2:\"to\";s:10:\"2026-12-31\";s:5:\"basis\";s:7:\"accrual\";s:6:\"sortBy\";s:7:\"default\";s:10:\"hideHeader\";b:1;s:16:\"showExtraFilters\";b:1;}', 2104926993),
('japspos-cache-setting.value.report.memorized.1.inventory-stock-report', 'N;', 2105014228),
('japspos-cache-setting.value.report.memorized.1.sales-by-item-report', 'N;', 2105011660),
('japspos-cache-setting.value.user.1.sidebar_shortcuts', 'N;', 2105015098);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checks`
--

CREATE TABLE `checks` (
  `id` bigint UNSIGNED NOT NULL,
  `check_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `check_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci,
  `cleared_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checks`
--

INSERT INTO `checks` (`id`, `check_number`, `bank_account_id`, `vendor_id`, `check_date`, `amount`, `payee`, `memo`, `cleared_at`, `created_at`, `updated_at`) VALUES
(1, '1001', 1, 1, '2026-08-29', 250.00, 'Office Supplies Demo', 'Demo check', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `credit_memos`
--

CREATE TABLE `credit_memos` (
  `id` bigint UNSIGNED NOT NULL,
  `credit_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `credit_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remaining_credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `memo` text COLLATE utf8mb4_unicode_ci,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `po_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `print_later` tinyint(1) NOT NULL DEFAULT '0',
  `email_later` tinyint(1) NOT NULL DEFAULT '0',
  `is_pending` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `credit_memos`
--

INSERT INTO `credit_memos` (`id`, `credit_number`, `customer_id`, `credit_date`, `status`, `subtotal`, `tax_total`, `total`, `remaining_credit`, `memo`, `class`, `template`, `po_number`, `print_later`, `email_later`, `is_pending`, `created_at`, `updated_at`) VALUES
(1, 'CM-00001', 2, '2026-08-25', 'open', 53.05, 3.37, 56.42, 56.42, 'Demo credit memo', NULL, NULL, NULL, 0, 0, 0, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(2, 'CM-00002', 3, '2026-08-24', 'open', 53.60, 3.40, 57.00, 57.00, 'Demo credit memo', NULL, NULL, NULL, 0, 0, 0, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(3, 'CM-00003', 4, '2026-08-23', 'open', 162.45, 10.32, 172.77, 172.77, 'Demo credit memo', NULL, NULL, NULL, 0, 0, 0, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(4, 'CM-00004', 5, '2026-08-22', 'open', 109.40, 6.95, 116.35, 116.35, 'Demo credit memo', NULL, NULL, NULL, 0, 0, 0, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(5, 'CM-00005', 6, '2026-08-21', 'open', 110.50, 7.02, 117.52, 117.52, 'Demo credit memo', NULL, NULL, NULL, 0, 0, 0, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(6, 'CM-00006', 7, '2026-08-20', 'open', 111.60, 7.09, 118.69, 118.69, 'Demo credit memo', NULL, NULL, NULL, 0, 0, 0, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(7, 'CM-00007', 8, '2026-08-19', 'open', 169.05, 10.73, 179.78, 179.78, 'Demo credit memo', NULL, NULL, NULL, 0, 0, 0, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(8, 'CM-00008', 9, '2026-08-18', 'open', 56.90, 3.61, 60.51, 60.51, 'Demo credit memo', NULL, NULL, NULL, 0, 0, 0, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(9, 'CM-00009', 22, '2026-09-10', 'open', 161.35, 0.00, 161.35, 161.35, NULL, NULL, 'Custom Credit Memo', NULL, 0, 0, 0, '2026-09-10 15:09:23', '2026-09-10 15:09:23'),
(10, 'CM-00010', 2, '2026-09-10', 'open', 54.15, 0.00, 54.15, 54.15, NULL, NULL, 'Custom Credit Memo', NULL, 0, 0, 0, '2026-09-10 15:09:36', '2026-09-10 15:09:36');

-- --------------------------------------------------------

--
-- Table structure for table `credit_memo_allocations`
--

CREATE TABLE `credit_memo_allocations` (
  `id` bigint UNSIGNED NOT NULL,
  `credit_memo_id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_memo_lines`
--

CREATE TABLE `credit_memo_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `credit_memo_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `taxable` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `credit_memo_lines`
--

INSERT INTO `credit_memo_lines` (`id`, `credit_memo_id`, `item_id`, `description`, `quantity`, `rate`, `amount`, `taxable`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Dark Horse Vanilla 200ct', 1.0000, 53.05, 53.05, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(2, 2, 4, 'Dark Horse Cherry 200ct', 1.0000, 53.60, 53.60, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(3, 3, 5, 'Dark Horse Blueberry 200ct', 3.0000, 54.15, 162.45, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(4, 4, 6, 'Dark Horse Mint 200ct', 2.0000, 54.70, 109.40, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(5, 5, 7, 'Dark Horse Honey 200ct', 2.0000, 55.25, 110.50, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(6, 6, 8, 'Dark Horse Wintergreen 200ct', 2.0000, 55.80, 111.60, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(7, 7, 9, 'Dark Horse Peach Ice 200ct', 3.0000, 56.35, 169.05, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(8, 8, 10, 'Dark Horse Mango 200ct', 1.0000, 56.90, 56.90, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(9, 9, 2, 'Dark Horse Original 200ct', 2.0000, 52.50, 105.00, 1, '2026-09-10 15:09:23', '2026-09-10 15:09:23'),
(10, 9, 9, 'Dark Horse Peach Ice 200ct', 1.0000, 56.35, 56.35, 1, '2026-09-10 15:09:23', '2026-09-10 15:09:23'),
(11, 10, 5, 'Dark Horse Blueberry 200ct', 1.0000, 54.15, 54.15, 1, '2026-09-10 15:09:36', '2026-09-10 15:09:36');

-- --------------------------------------------------------

--
-- Table structure for table `credit_memo_refunds`
--

CREATE TABLE `credit_memo_refunds` (
  `id` bigint UNSIGNED NOT NULL,
  `credit_memo_id` bigint UNSIGNED NOT NULL,
  `refund_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'check',
  `account_id` bigint UNSIGNED DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_street1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_street2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_state` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_zip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_level_id` bigint UNSIGNED DEFAULT NULL,
  `tax_code_id` bigint UNSIGNED DEFAULT NULL,
  `terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_limit` decimal(15,2) DEFAULT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `online_payment_eligible` tinyint(1) NOT NULL DEFAULT '0',
  `pinned_note` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_number`, `company_name`, `display_name`, `first_name`, `last_name`, `email`, `phone`, `alt_phone`, `fax`, `bill_to_street1`, `bill_to_street2`, `bill_to_city`, `bill_to_state`, `bill_to_zip`, `bill_to_country`, `price_level_id`, `tax_code_id`, `terms`, `credit_limit`, `balance`, `online_payment_eligible`, `pinned_note`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2341000', 'Mobil', 'Mobil North Haven', 'Tina', 'Blick', 'mobilnorthhaven@demo.local', '203-555-1000', NULL, NULL, '306 Mabel Wells', NULL, 'North Haven', 'CT', '06473', 'USA', 3, 1, 'Net 15', 10000.00, 1110.35, 1, 'Prefers morning deliveries.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:47', NULL),
(2, '2341001', 'Corner', 'Corner Smoke Shop', 'Anabel', 'Mohr', 'cornersmokeshop@demo.local', '203-555-1001', NULL, NULL, '208 Streich Crest', NULL, 'Bridgeport', 'CT', '06604', 'USA', 1, 1, 'Net 30', 5000.00, 677.49, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 15:09:36', NULL),
(3, '2341002', 'Shoreline', 'Shoreline Convenience', 'Isaac', 'Champlin', 'shorelineconvenience@demo.local', '203-555-1002', NULL, NULL, '113 Jalon Light', NULL, 'New Haven', 'CT', '06511', 'USA', 1, 1, 'Net 15', 5000.00, 0.00, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(4, '2341003', 'Elm', 'Elm City Market', 'Willis', 'Johns', 'elmcitymarket@demo.local', '203-555-1003', NULL, NULL, '192 Verla Flat', NULL, 'New Haven', 'CT', '06510', 'USA', 2, 1, 'Net 30', 10000.00, 1006.28, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:47', NULL),
(5, '2341004', 'Quinnipiac', 'Quinnipiac Gas & Go', 'Florence', 'Heidenreich', 'quinnipiacgasgo@demo.local', '203-555-1004', NULL, NULL, '196 Morar Field', NULL, 'Hamden', 'CT', '06514', 'USA', 4, 1, 'Net 15', 5000.00, 0.00, 1, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(6, '2341005', 'Westport', 'Westport Tobacco Lounge', 'Augustus', 'Walker', 'westporttobaccolounge@demo.local', '203-555-1005', NULL, NULL, '374 Pierce Haven', NULL, 'Westport', 'CT', '06880', 'USA', 3, 1, 'Net 30', 5000.00, 595.83, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(7, '2341006', 'Stamford', 'Stamford Express Mart', 'Arely', 'Haag', 'stamfordexpressmart@demo.local', '203-555-1006', NULL, NULL, '147 Manley Courts', NULL, 'Stamford', 'CT', '06901', 'USA', 2, 1, 'Net 15', 10000.00, 628.74, 0, 'Prefers morning deliveries.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:46', NULL),
(8, '2341007', 'Norwalk', 'Norwalk Night Owl', 'Godfrey', 'Jacobson', 'norwalknightowl@demo.local', '203-555-1007', NULL, NULL, '748 Gottlieb Plains', NULL, 'Norwalk', 'CT', '06854', 'USA', 5, 1, 'Net 30', 5000.00, 591.70, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(9, '2341008', 'Danbury', 'Danbury Discount Tobacco', 'Kennedy', 'Ferry', 'danburydiscounttobacco@demo.local', '203-555-1008', NULL, NULL, '887 Kuhic Passage', NULL, 'Danbury', 'CT', '06810', 'USA', 4, 1, 'Net 15', 5000.00, 309.71, 1, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(10, '2341009', 'Waterbury', 'Waterbury Wholesale Foods', 'Brittany', 'Gutkowski', 'waterburywholesalefoods@demo.local', '203-555-1009', NULL, NULL, '198 Bednar Key', NULL, 'Waterbury', 'CT', '06702', 'USA', 2, 1, 'Net 30', 10000.00, 612.41, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(11, '2341010', 'Hartford', 'Hartford Hub Convenience', 'Dianna', 'Hodkiewicz', 'hartfordhubconvenience@demo.local', '203-555-1010', NULL, NULL, '157 Marks Walks', NULL, 'Hartford', 'CT', '06103', 'USA', 3, 1, 'Net 15', 5000.00, 897.21, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(12, '2341011', 'East', 'East Haven Quick Stop', 'Roman', 'Ondricka', 'easthavenquickstop@demo.local', '203-555-1011', NULL, NULL, '379 Romaguera Gardens', NULL, 'East Haven', 'CT', '06512', 'USA', 1, 1, 'Net 30', 5000.00, 595.13, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(13, '2341012', 'Milford', 'Milford Marina Mart', 'Burdette', 'Ruecker', 'milfordmarinamart@demo.local', '203-555-1012', NULL, NULL, '89 Reed Ways', NULL, 'Milford', 'CT', '06460', 'USA', 4, 1, 'Net 15', 10000.00, 626.73, 1, 'Prefers morning deliveries.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(14, '2341013', 'Branford', 'Branford Beach Store', 'Charlene', 'Bahringer', 'branfordbeachstore@demo.local', '203-555-1013', NULL, NULL, '395 Garrison Divide', NULL, 'Branford', 'CT', '06405', 'USA', 1, 1, 'Net 30', 5000.00, 805.68, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(15, '2341014', 'Guilford', 'Guilford Green Grocer', 'Halie', 'Hayes', 'guilfordgreengrocer@demo.local', '203-555-1014', NULL, NULL, '198 Kub Flats', NULL, 'Guilford', 'CT', '06437', 'USA', 5, 1, 'Net 15', 5000.00, 960.73, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(16, '2341015', 'Madison', 'Madison Country Market', 'Betty', 'Kreiger', 'madisoncountrymarket@demo.local', '203-555-1015', NULL, NULL, '436 Jerde Branch', NULL, 'Madison', 'CT', '06443', 'USA', 3, 1, 'Net 30', 10000.00, 715.47, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(17, '2341016', 'Clinton', 'Clinton Harbor Shop', 'Yadira', 'Kunze', 'clintonharborshop@demo.local', '203-555-1016', NULL, NULL, '675 McLaughlin Mission', NULL, 'Clinton', 'CT', '06413', 'USA', 4, 1, 'Net 15', 5000.00, 874.20, 1, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:47', NULL),
(18, '2341017', 'Old', 'Old Saybrook Spirits & Smoke', 'Alvis', 'Cole', 'oldsaybrookspiritssmoke@demo.local', '203-555-1017', NULL, NULL, '234 Beier Station', NULL, 'Old Saybrook', 'CT', '06475', 'USA', 1, 1, 'Net 30', 5000.00, 0.00, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(19, '2341018', 'Middletown', 'Middletown Metro Mart', 'Rhoda', 'Bahringer', 'middletownmetromart@demo.local', '203-555-1018', NULL, NULL, '598 Raynor Shore', NULL, 'Middletown', 'CT', '06457', 'USA', 2, 1, 'Net 15', 10000.00, 675.72, 0, 'Prefers morning deliveries.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(20, '2341019', 'Meriden', 'Meriden Main Street Market', 'Trace', 'Heathcote', 'meridenmainstreetmarket@demo.local', '203-555-1019', NULL, NULL, '666 Lockman Creek', NULL, 'Meriden', 'CT', '06450', 'USA', 1, 1, 'Net 30', 5000.00, 975.39, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:47', NULL),
(21, '2341020', 'Wallingford', 'Wallingford Warehouse Retail', 'Adella', 'Blanda', 'wallingfordwarehouseretail@demo.local', '203-555-1020', NULL, NULL, '527 Delfina Loop', NULL, 'Wallingford', 'CT', '06492', 'USA', 3, 1, 'Net 15', 5000.00, 1043.17, 1, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(22, '2341021', 'Cheshire', 'Cheshire Crossroads Store', 'Kasey', 'Emmerich', 'cheshirecrossroadsstore@demo.local', '203-555-1021', NULL, NULL, '855 Heller Key', NULL, 'Cheshire', 'CT', '06410', 'USA', 2, 1, 'Net 30', 10000.00, -161.35, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 15:09:23', NULL),
(23, '2341022', 'Ansonia', 'Ansonia Avenue Cigars', 'Donald', 'Adams', 'ansoniaavenuecigars@demo.local', '203-555-1022', NULL, NULL, '288 Bechtelar Squares', NULL, 'Ansonia', 'CT', '06401', 'USA', 1, 1, 'Net 15', 5000.00, 1151.40, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:47', NULL),
(24, '2341023', 'Derby', 'Derby Dockside Mart', 'Genesis', 'Wehner', 'derbydocksidemart@demo.local', '203-555-1023', NULL, NULL, '85 Pfeffer Circles', NULL, 'Derby', 'CT', '06418', 'USA', 1, 1, 'Net 30', 5000.00, 0.00, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(25, '2341024', 'Shelton', 'Shelton Smoke & Snack', 'Dawson', 'Macejkovic', 'sheltonsmokesnack@demo.local', '203-555-1024', NULL, NULL, '745 Sawayn Mall', NULL, 'Shelton', 'CT', '06484', 'USA', 4, 1, 'Net 15', 10000.00, 491.30, 1, 'Prefers morning deliveries.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(26, '2341025', 'Trumbull', 'Trumbull Turnpike Tobacco', 'Angelina', 'Romaguera', 'trumbullturnpiketobacco@demo.local', '203-555-1025', NULL, NULL, '570 Wayne Villages', NULL, 'Trumbull', 'CT', '06611', 'USA', 3, 1, 'Net 30', 5000.00, 1241.44, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(27, '2341026', 'Fairfield', 'Fairfield Family Foods', 'Mertie', 'Hammes', 'fairfieldfamilyfoods@demo.local', '203-555-1026', NULL, NULL, '315 Hayes Station', NULL, 'Fairfield', 'CT', '06824', 'USA', 1, 1, 'Net 15', 5000.00, 1113.05, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(28, '2341027', 'Stratford', 'Stratford Station Store', 'Jocelyn', 'Tillman', 'stratfordstationstore@demo.local', '203-555-1027', NULL, NULL, '195 Emard Pass', NULL, 'Stratford', 'CT', '06614', 'USA', 2, 1, 'Net 30', 10000.00, 422.17, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(29, '2341028', 'Naugatuck', 'Naugatuck Neighborhood Market', 'Kaylie', 'Heathcote', 'naugatuckneighborhoodmarket@demo.local', '203-555-1028', NULL, NULL, '83 Johnston Trafficway', NULL, 'Naugatuck', 'CT', '06770', 'USA', 4, 1, 'Net 15', 5000.00, 729.99, 1, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(30, '2341029', 'Torrington', 'Torrington Tobacco Depot', 'Willy', 'Cormier', 'torringtontobaccodepot@demo.local', '203-555-1029', NULL, NULL, '720 Boyer Park', NULL, 'Torrington', 'CT', '06790', 'USA', 1, 1, 'Net 30', 5000.00, 495.16, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(31, '2341030', 'New', 'New Britain Bodega Plus', 'Jovan', 'Dickinson', 'newbritainbodegaplus@demo.local', '203-555-1030', NULL, NULL, '603 Wunsch Inlet', NULL, 'New Britain', 'CT', '06051', 'USA', 3, 1, 'Net 15', 10000.00, 367.67, 0, 'Prefers morning deliveries.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(32, '2341031', 'Bristol', 'Bristol Boulevard Mart', 'Nico', 'Nader', 'bristolboulevardmart@demo.local', '203-555-1031', NULL, NULL, '771 Reilly Tunnel', NULL, 'Bristol', 'CT', '06010', 'USA', 1, 1, 'Net 30', 5000.00, 841.31, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(33, '2341032', 'Southington', 'Southington Summit Shop', 'Kira', 'Bashirian', 'southingtonsummitshop@demo.local', '203-555-1032', NULL, NULL, '603 Garrett Rapid', NULL, 'Southington', 'CT', '06489', 'USA', 4, 1, 'Net 15', 5000.00, 807.53, 1, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(34, '2341033', 'Plainville', 'Plainville Plaza Convenience', 'Isadore', 'O\'Kon', 'plainvilleplazaconvenience@demo.local', '203-555-1033', NULL, NULL, '551 Marge Haven', NULL, 'Plainville', 'CT', '06062', 'USA', 2, 1, 'Net 30', 10000.00, 574.58, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(35, '2341034', 'Farmington', 'Farmington Fuel & Food', 'Jammie', 'Lynch', 'farmingtonfuelfood@demo.local', '203-555-1034', NULL, NULL, '293 Hirthe Street', NULL, 'Farmington', 'CT', '06032', 'USA', 1, 1, 'Net 15', 5000.00, 0.00, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(36, '2341035', 'West', 'West Hartford Wine & Smoke', 'Milton', 'Hartmann', 'westhartfordwinesmoke@demo.local', '203-555-1035', NULL, NULL, '516 Bins Meadows', NULL, 'West Hartford', 'CT', '06107', 'USA', 3, 1, 'Net 30', 5000.00, 2134.82, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(37, '2341036', 'Enfield', 'Enfield Exit 47 Mart', 'Bertha', 'Murphy', 'enfieldexit47mart@demo.local', '203-555-1036', NULL, NULL, '169 Flatley Overpass', NULL, 'Enfield', 'CT', '06082', 'USA', 4, 1, 'Net 15', 10000.00, 0.00, 1, 'Prefers morning deliveries.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(38, '2341037', 'Manchester', 'Manchester Mall Kiosk Supply', 'Virgil', 'Wolf', 'manchestermallkiosksupply@demo.local', '203-555-1037', NULL, NULL, '453 Weimann Shore', NULL, 'Manchester', 'CT', '06040', 'USA', 1, 1, 'Net 30', 5000.00, 572.51, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(39, '2341038', 'Glastonbury', 'Glastonbury Green Market', 'Raquel', 'Gusikowski', 'glastonburygreenmarket@demo.local', '203-555-1038', NULL, NULL, '675 Zechariah Springs', NULL, 'Glastonbury', 'CT', '06033', 'USA', 1, 1, 'Net 15', 5000.00, 626.09, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:47', NULL),
(40, '2341039', 'Vernon', 'Vernon Valley Vape & More', 'Camylle', 'Ferry', 'vernonvalleyvapemore@demo.local', '203-555-1039', NULL, NULL, '456 Moen Village', NULL, 'Vernon', 'CT', '06066', 'USA', 2, 1, 'Net 30', 10000.00, 157.10, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(41, '2341040', 'Windham', 'Windham Wholesale Club', 'Ronaldo', 'Bednar', 'windhamwholesaleclub@demo.local', '203-555-1040', NULL, NULL, '97 Patsy Pine', NULL, 'Willimantic', 'CT', '06226', 'USA', 3, 1, 'Net 15', 5000.00, 0.00, 1, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(42, '2341041', 'Norwich', 'Norwich North End Market', 'Esteban', 'Price', 'norwichnorthendmarket@demo.local', '203-555-1041', NULL, NULL, '499 Reyna Stream', NULL, 'Norwich', 'CT', '06360', 'USA', 1, 1, 'Net 30', 5000.00, 249.07, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:47', NULL),
(43, '2341042', 'New', 'New London Harbor Shop', 'Vivienne', 'Strosin', 'newlondonharborshop@demo.local', '203-555-1042', NULL, NULL, '307 Conroy Squares', NULL, 'New London', 'CT', '06320', 'USA', 2, 1, 'Net 15', 10000.00, 0.00, 0, 'Prefers morning deliveries.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(44, '2341043', 'Groton', 'Groton Gateway Convenience', 'Muriel', 'Mante', 'grotongatewayconvenience@demo.local', '203-555-1043', NULL, NULL, '503 Gwen Canyon', NULL, 'Groton', 'CT', '06340', 'USA', 1, 1, 'Net 30', 5000.00, 164.98, 0, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:48', NULL),
(45, '2341044', 'Inactive', 'Inactive Legacy Account', 'Jocelyn', 'Wilderman', 'inactivelegacyaccount@demo.local', '203-555-1044', NULL, NULL, '407 Ryan Plaza', NULL, 'New Haven', 'CT', '06511', 'USA', 4, 1, 'Net 15', 5000.00, 0.00, 1, NULL, 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_contacts`
--

CREATE TABLE `customer_contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_contacts`
--

INSERT INTO `customer_contacts` (`id`, `customer_id`, `name`, `title`, `email`, `phone`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tina Blick', 'Primary Contact', 'mobilnorthhaven@demo.local', '203-555-1000', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 1, 'Billing Desk', 'Billing Contact', 'billing0@demo.local', '203-555-1000', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, 1, 'Purchasing Manager', 'Purchasing Contact', 'buy0@demo.local', '203-555-1000', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, 2, 'Anabel Mohr', 'Primary Contact', 'cornersmokeshop@demo.local', '203-555-1001', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(5, 3, 'Isaac Champlin', 'Primary Contact', 'shorelineconvenience@demo.local', '203-555-1002', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(6, 4, 'Willis Johns', 'Primary Contact', 'elmcitymarket@demo.local', '203-555-1003', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(7, 4, 'Billing Desk', 'Billing Contact', 'billing3@demo.local', '203-555-1003', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(8, 4, 'Purchasing Manager', 'Purchasing Contact', 'buy3@demo.local', '203-555-1003', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(9, 5, 'Florence Heidenreich', 'Primary Contact', 'quinnipiacgasgo@demo.local', '203-555-1004', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(10, 6, 'Augustus Walker', 'Primary Contact', 'westporttobaccolounge@demo.local', '203-555-1005', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(11, 7, 'Arely Haag', 'Primary Contact', 'stamfordexpressmart@demo.local', '203-555-1006', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(12, 7, 'Billing Desk', 'Billing Contact', 'billing6@demo.local', '203-555-1006', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(13, 7, 'Purchasing Manager', 'Purchasing Contact', 'buy6@demo.local', '203-555-1006', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(14, 8, 'Godfrey Jacobson', 'Primary Contact', 'norwalknightowl@demo.local', '203-555-1007', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(15, 9, 'Kennedy Ferry', 'Primary Contact', 'danburydiscounttobacco@demo.local', '203-555-1008', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(16, 10, 'Brittany Gutkowski', 'Primary Contact', 'waterburywholesalefoods@demo.local', '203-555-1009', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(17, 10, 'Billing Desk', 'Billing Contact', 'billing9@demo.local', '203-555-1009', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(18, 10, 'Purchasing Manager', 'Purchasing Contact', 'buy9@demo.local', '203-555-1009', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(19, 11, 'Dianna Hodkiewicz', 'Primary Contact', 'hartfordhubconvenience@demo.local', '203-555-1010', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(20, 12, 'Roman Ondricka', 'Primary Contact', 'easthavenquickstop@demo.local', '203-555-1011', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(21, 13, 'Burdette Ruecker', 'Primary Contact', 'milfordmarinamart@demo.local', '203-555-1012', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(22, 13, 'Billing Desk', 'Billing Contact', 'billing12@demo.local', '203-555-1012', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(23, 13, 'Purchasing Manager', 'Purchasing Contact', 'buy12@demo.local', '203-555-1012', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(24, 14, 'Charlene Bahringer', 'Primary Contact', 'branfordbeachstore@demo.local', '203-555-1013', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(25, 15, 'Halie Hayes', 'Primary Contact', 'guilfordgreengrocer@demo.local', '203-555-1014', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(26, 16, 'Betty Kreiger', 'Primary Contact', 'madisoncountrymarket@demo.local', '203-555-1015', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(27, 16, 'Billing Desk', 'Billing Contact', 'billing15@demo.local', '203-555-1015', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(28, 16, 'Purchasing Manager', 'Purchasing Contact', 'buy15@demo.local', '203-555-1015', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(29, 17, 'Yadira Kunze', 'Primary Contact', 'clintonharborshop@demo.local', '203-555-1016', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(30, 18, 'Alvis Cole', 'Primary Contact', 'oldsaybrookspiritssmoke@demo.local', '203-555-1017', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(31, 19, 'Rhoda Bahringer', 'Primary Contact', 'middletownmetromart@demo.local', '203-555-1018', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(32, 19, 'Billing Desk', 'Billing Contact', 'billing18@demo.local', '203-555-1018', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(33, 19, 'Purchasing Manager', 'Purchasing Contact', 'buy18@demo.local', '203-555-1018', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(34, 20, 'Trace Heathcote', 'Primary Contact', 'meridenmainstreetmarket@demo.local', '203-555-1019', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(35, 21, 'Adella Blanda', 'Primary Contact', 'wallingfordwarehouseretail@demo.local', '203-555-1020', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(36, 22, 'Kasey Emmerich', 'Primary Contact', 'cheshirecrossroadsstore@demo.local', '203-555-1021', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(37, 22, 'Billing Desk', 'Billing Contact', 'billing21@demo.local', '203-555-1021', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(38, 22, 'Purchasing Manager', 'Purchasing Contact', 'buy21@demo.local', '203-555-1021', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(39, 23, 'Donald Adams', 'Primary Contact', 'ansoniaavenuecigars@demo.local', '203-555-1022', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(40, 24, 'Genesis Wehner', 'Primary Contact', 'derbydocksidemart@demo.local', '203-555-1023', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(41, 25, 'Dawson Macejkovic', 'Primary Contact', 'sheltonsmokesnack@demo.local', '203-555-1024', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(42, 25, 'Billing Desk', 'Billing Contact', 'billing24@demo.local', '203-555-1024', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(43, 25, 'Purchasing Manager', 'Purchasing Contact', 'buy24@demo.local', '203-555-1024', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(44, 26, 'Angelina Romaguera', 'Primary Contact', 'trumbullturnpiketobacco@demo.local', '203-555-1025', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(45, 27, 'Mertie Hammes', 'Primary Contact', 'fairfieldfamilyfoods@demo.local', '203-555-1026', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(46, 28, 'Jocelyn Tillman', 'Primary Contact', 'stratfordstationstore@demo.local', '203-555-1027', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(47, 28, 'Billing Desk', 'Billing Contact', 'billing27@demo.local', '203-555-1027', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(48, 28, 'Purchasing Manager', 'Purchasing Contact', 'buy27@demo.local', '203-555-1027', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(49, 29, 'Kaylie Heathcote', 'Primary Contact', 'naugatuckneighborhoodmarket@demo.local', '203-555-1028', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(50, 30, 'Willy Cormier', 'Primary Contact', 'torringtontobaccodepot@demo.local', '203-555-1029', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(51, 31, 'Jovan Dickinson', 'Primary Contact', 'newbritainbodegaplus@demo.local', '203-555-1030', 0, '2026-09-10 11:00:40', '2026-09-10 14:17:22'),
(52, 31, 'Billing Desk', 'Billing Contact', 'billing30@demo.local', '203-555-1030', 0, '2026-09-10 11:00:40', '2026-09-10 14:17:22'),
(53, 31, 'Purchasing Manager', 'Purchasing Contact', 'buy30@demo.local', '203-555-1030', 0, '2026-09-10 11:00:40', '2026-09-10 14:17:22'),
(54, 32, 'Nico Nader', 'Primary Contact', 'bristolboulevardmart@demo.local', '203-555-1031', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(55, 33, 'Kira Bashirian', 'Primary Contact', 'southingtonsummitshop@demo.local', '203-555-1032', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(56, 34, 'Isadore O\'Kon', 'Primary Contact', 'plainvilleplazaconvenience@demo.local', '203-555-1033', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(57, 34, 'Billing Desk', 'Billing Contact', 'billing33@demo.local', '203-555-1033', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(58, 34, 'Purchasing Manager', 'Purchasing Contact', 'buy33@demo.local', '203-555-1033', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(59, 35, 'Jammie Lynch', 'Primary Contact', 'farmingtonfuelfood@demo.local', '203-555-1034', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(60, 36, 'Milton Hartmann', 'Primary Contact', 'westhartfordwinesmoke@demo.local', '203-555-1035', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(61, 37, 'Bertha Murphy', 'Primary Contact', 'enfieldexit47mart@demo.local', '203-555-1036', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(62, 37, 'Billing Desk', 'Billing Contact', 'billing36@demo.local', '203-555-1036', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(63, 37, 'Purchasing Manager', 'Purchasing Contact', 'buy36@demo.local', '203-555-1036', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(64, 38, 'Virgil Wolf', 'Primary Contact', 'manchestermallkiosksupply@demo.local', '203-555-1037', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(65, 39, 'Raquel Gusikowski', 'Primary Contact', 'glastonburygreenmarket@demo.local', '203-555-1038', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(66, 40, 'Camylle Ferry', 'Primary Contact', 'vernonvalleyvapemore@demo.local', '203-555-1039', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(67, 40, 'Billing Desk', 'Billing Contact', 'billing39@demo.local', '203-555-1039', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(68, 40, 'Purchasing Manager', 'Purchasing Contact', 'buy39@demo.local', '203-555-1039', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(69, 41, 'Ronaldo Bednar', 'Primary Contact', 'windhamwholesaleclub@demo.local', '203-555-1040', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(70, 42, 'Esteban Price', 'Primary Contact', 'norwichnorthendmarket@demo.local', '203-555-1041', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(71, 43, 'Vivienne Strosin', 'Primary Contact', 'newlondonharborshop@demo.local', '203-555-1042', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(72, 43, 'Billing Desk', 'Billing Contact', 'billing42@demo.local', '203-555-1042', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(73, 43, 'Purchasing Manager', 'Purchasing Contact', 'buy42@demo.local', '203-555-1042', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(74, 44, 'Muriel Mante', 'Primary Contact', 'grotongatewayconvenience@demo.local', '203-555-1043', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(75, 45, 'Jocelyn Wilderman', 'Primary Contact', 'inactivelegacyaccount@demo.local', '203-555-1044', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(76, 31, 'Emran Hossain', NULL, 'Sajidimran95@gmail.com', '01710796333', 1, '2026-09-10 14:17:03', '2026-09-10 14:17:22');

-- --------------------------------------------------------

--
-- Table structure for table `customer_notes`
--

CREATE TABLE `customer_notes` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_notes`
--

INSERT INTO `customer_notes` (`id`, `customer_id`, `user_id`, `body`, `is_pinned`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Established account — weekly tobacco order.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 6, NULL, 'Established account — weekly tobacco order.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, 11, NULL, 'Established account — weekly tobacco order.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, 16, NULL, 'Established account — weekly tobacco order.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(5, 21, NULL, 'Established account — weekly tobacco order.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(6, 26, NULL, 'Established account — weekly tobacco order.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(7, 31, NULL, 'Established account — weekly tobacco order.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(8, 36, NULL, 'Established account — weekly tobacco order.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(9, 41, NULL, 'Established account — weekly tobacco order.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `customer_todos`
--

CREATE TABLE `customer_todos` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `due_date` date DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `deposit_date` date NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci,
  `cleared_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`id`, `number`, `bank_account_id`, `deposit_date`, `total`, `memo`, `cleared_at`, `created_at`, `updated_at`) VALUES
(1, 'DEP-00001', 1, '2026-09-07', 2002.57, 'Batch deposit', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(2, 'DEP-00002', 1, '2026-09-04', 2743.17, 'Batch deposit', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(3, 'DEP-00003', 1, '2026-09-01', 2079.99, 'Batch deposit', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `deposit_items`
--

CREATE TABLE `deposit_items` (
  `id` bigint UNSIGNED NOT NULL,
  `deposit_id` bigint UNSIGNED NOT NULL,
  `payment_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposit_items`
--

INSERT INTO `deposit_items` (`id`, `deposit_id`, `payment_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 401.43, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(2, 1, 2, 508.35, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(3, 1, 3, 736.95, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(4, 1, 4, 207.08, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(5, 1, 5, 148.76, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(6, 2, 6, 260.88, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(7, 2, 7, 481.44, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(8, 2, 8, 396.75, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(9, 2, 9, 233.52, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(10, 2, 10, 1370.58, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(11, 3, 11, 425.19, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(12, 3, 12, 207.59, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(13, 3, 13, 437.84, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(14, 3, 14, 569.51, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(15, 3, 15, 439.86, '2026-09-10 11:00:48', '2026-09-10 11:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipts`
--

CREATE TABLE `goods_receipts` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED DEFAULT NULL,
  `receipt_date` date NOT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipts`
--

INSERT INTO `goods_receipts` (`id`, `number`, `vendor_id`, `purchase_order_id`, `receipt_date`, `memo`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'GR-00002', 2, 2, '2026-08-10', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 'GR-00003', 3, 3, '2026-08-03', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 'GR-00004', 4, 4, '2026-07-27', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 'GR-00007', 7, 7, '2026-07-06', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 'GR-00008', 8, 8, '2026-06-29', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 'GR-00009', 9, 9, '2026-06-22', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(7, 'GR-00012', 12, 12, '2026-06-01', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(8, 'GR-00013', 13, 13, '2026-05-25', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(9, 'GR-00014', 14, 14, '2026-05-18', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(10, 'GR-00017', 3, 17, '2026-04-27', NULL, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(11, 'GR-00018', 4, 18, '2026-04-20', NULL, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(12, 'GR-00019', 5, 19, '2026-04-13', NULL, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(13, 'GR-00022', 8, 22, '2026-03-23', NULL, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(14, 'GR-00023', 9, 23, '2026-03-16', NULL, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(15, 'GR-00024', 10, 24, '2026-03-09', NULL, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(16, 'GR-00025', 1, 38, '2026-09-17', NULL, 1, '2026-09-17 08:09:55', '2026-09-17 08:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipt_lines`
--

CREATE TABLE `goods_receipt_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `goods_receipt_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `purchase_order_line_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `unit_cost` decimal(15,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipt_lines`
--

INSERT INTO `goods_receipt_lines` (`id`, `goods_receipt_id`, `item_id`, `purchase_order_line_id`, `quantity`, `unit_cost`, `created_at`, `updated_at`) VALUES
(1, 1, 77, 5, 14.0000, 18.3500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 1, 78, 6, 25.0000, 18.7000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 1, 104, 7, 29.0000, 18.5500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 1, 107, 8, 29.0000, 6.3500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 2, 4, 9, 8.0000, 39.0500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 2, 72, 10, 13.0000, 25.8500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(7, 2, 79, 11, 8.5000, 19.0500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(8, 2, 91, 12, 9.0000, 14.0000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(9, 3, 25, 13, 34.0000, 21.1500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(10, 3, 50, 14, 20.0000, 29.4000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(11, 3, 87, 15, 24.0000, 21.8500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(12, 3, 97, 16, 40.0000, 16.1000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(13, 4, 24, 25, 24.0000, 20.8000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(14, 4, 46, 26, 15.0000, 28.0000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(15, 4, 61, 27, 18.0000, 22.0000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(16, 4, 78, 28, 23.0000, 18.7000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(17, 5, 17, 29, 22.0000, 18.3500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(18, 5, 23, 30, 37.0000, 20.4500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(19, 5, 75, 31, 29.0000, 26.9000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(20, 5, 100, 32, 39.0000, 17.1500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(21, 6, 34, 33, 15.0000, 13.0500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(22, 6, 42, 34, 17.5000, 15.8500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(23, 6, 80, 35, 14.0000, 19.4000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(24, 6, 82, 36, 14.5000, 20.1000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(25, 7, 10, 45, 7.5000, 41.1500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(26, 7, 33, 46, 16.0000, 12.7000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(27, 7, 36, 47, 20.0000, 13.7500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(28, 7, 49, 48, 6.5000, 29.0500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(29, 8, 17, 49, 17.0000, 18.3500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(30, 8, 19, 50, 16.0000, 19.0500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(31, 8, 62, 51, 21.0000, 22.3500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(32, 8, 111, 52, 21.0000, 7.7500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(33, 9, 42, 53, 13.0000, 15.8500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(34, 9, 60, 54, 18.0000, 32.9000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(35, 9, 62, 55, 38.0000, 22.3500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(36, 9, 79, 56, 16.0000, 19.0500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(37, 10, 39, 65, 30.0000, 14.8000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(38, 10, 65, 66, 37.0000, 23.4000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(39, 10, 112, 67, 34.0000, 8.1000, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(40, 10, 119, 68, 32.0000, 10.5500, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(41, 11, 16, 69, 7.0000, 18.0000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(42, 11, 34, 70, 19.5000, 13.0500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(43, 11, 62, 71, 11.0000, 22.3500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(44, 11, 115, 72, 7.5000, 9.1500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(45, 12, 18, 73, 16.0000, 18.7000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(46, 12, 25, 74, 37.0000, 21.1500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(47, 12, 59, 75, 31.0000, 32.5500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(48, 12, 97, 76, 26.0000, 16.1000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(49, 13, 8, 85, 38.0000, 40.4500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(50, 13, 20, 86, 16.0000, 19.4000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(51, 13, 23, 87, 21.0000, 20.4500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(52, 13, 88, 88, 24.0000, 22.2000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(53, 14, 25, 89, 34.0000, 21.1500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(54, 14, 48, 90, 32.0000, 28.7000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(55, 14, 85, 91, 20.0000, 21.1500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(56, 14, 97, 92, 35.0000, 16.1000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(57, 15, 5, 93, 20.0000, 39.4000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(58, 15, 13, 94, 5.5000, 42.2000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(59, 15, 40, 95, 7.5000, 15.1500, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(60, 15, 84, 96, 19.0000, 20.8000, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(61, 16, 2, 101, 1.0000, 38.3500, '2026-09-17 08:09:55', '2026-09-17 08:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `import_batches`
--

CREATE TABLE `import_batches` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'csv',
  `entity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'raw',
  `row_count` int UNSIGNED NOT NULL DEFAULT '0',
  `error_count` int UNSIGNED NOT NULL DEFAULT '0',
  `success_count` int UNSIGNED NOT NULL DEFAULT '0',
  `summary` json DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `normalized_at` timestamp NULL DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `transformed_at` timestamp NULL DEFAULT NULL,
  `produced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `import_rows`
--

CREATE TABLE `import_rows` (
  `id` bigint UNSIGNED NOT NULL,
  `import_batch_id` bigint UNSIGNED NOT NULL,
  `line_number` int UNSIGNED NOT NULL,
  `stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'raw',
  `raw_payload` json NOT NULL,
  `normalized_payload` json DEFAULT NULL,
  `validation_errors` json DEFAULT NULL,
  `transformed_payload` json DEFAULT NULL,
  `production_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `production_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `qty_in` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_out` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `balance_after` decimal(15,4) NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci,
  `occurred_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_transactions`
--

INSERT INTO `inventory_transactions` (`id`, `item_id`, `type`, `reference_type`, `reference_id`, `qty_in`, `qty_out`, `unit_cost`, `balance_after`, `created_by`, `memo`, `occurred_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'opening_stock', NULL, NULL, 298.0000, 0.0000, 38.3500, 298.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(2, 3, 'opening_stock', NULL, NULL, 336.0000, 0.0000, 38.7000, 336.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(3, 4, 'opening_stock', NULL, NULL, 143.0000, 0.0000, 39.0500, 143.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(4, 5, 'opening_stock', NULL, NULL, 84.0000, 0.0000, 39.4000, 84.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(5, 6, 'opening_stock', NULL, NULL, 361.0000, 0.0000, 39.7500, 361.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(6, 7, 'opening_stock', NULL, NULL, 218.0000, 0.0000, 40.1000, 218.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(7, 8, 'opening_stock', NULL, NULL, 379.0000, 0.0000, 40.4500, 379.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(8, 9, 'opening_stock', NULL, NULL, 195.0000, 0.0000, 40.8000, 195.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(9, 10, 'opening_stock', NULL, NULL, 260.0000, 0.0000, 41.1500, 260.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(10, 11, 'opening_stock', NULL, NULL, 247.0000, 0.0000, 41.5000, 247.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(11, 12, 'opening_stock', NULL, NULL, 243.0000, 0.0000, 41.8500, 243.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(12, 13, 'opening_stock', NULL, NULL, 410.0000, 0.0000, 42.2000, 410.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(13, 14, 'opening_stock', NULL, NULL, 193.0000, 0.0000, 42.5500, 193.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(14, 15, 'opening_stock', NULL, NULL, 203.0000, 0.0000, 42.9000, 203.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(15, 16, 'opening_stock', NULL, NULL, 292.0000, 0.0000, 18.0000, 292.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(16, 17, 'opening_stock', NULL, NULL, 154.0000, 0.0000, 18.3500, 154.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(17, 18, 'opening_stock', NULL, NULL, 136.0000, 0.0000, 18.7000, 136.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(18, 19, 'opening_stock', NULL, NULL, 160.0000, 0.0000, 19.0500, 160.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(19, 20, 'opening_stock', NULL, NULL, 190.0000, 0.0000, 19.4000, 190.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(20, 21, 'opening_stock', NULL, NULL, 314.0000, 0.0000, 19.7500, 314.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(21, 22, 'opening_stock', NULL, NULL, 100.0000, 0.0000, 20.1000, 100.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(22, 23, 'opening_stock', NULL, NULL, 264.0000, 0.0000, 20.4500, 264.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(23, 24, 'opening_stock', NULL, NULL, 383.0000, 0.0000, 20.8000, 383.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(24, 25, 'opening_stock', NULL, NULL, 29.0000, 0.0000, 21.1500, 29.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(25, 26, 'opening_stock', NULL, NULL, 117.0000, 0.0000, 21.5000, 117.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(26, 27, 'opening_stock', NULL, NULL, 346.0000, 0.0000, 21.8500, 346.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(27, 28, 'opening_stock', NULL, NULL, 216.0000, 0.0000, 22.2000, 216.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(28, 29, 'opening_stock', NULL, NULL, 303.0000, 0.0000, 22.5500, 303.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(29, 30, 'opening_stock', NULL, NULL, 140.0000, 0.0000, 22.9000, 140.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(30, 31, 'opening_stock', NULL, NULL, 117.0000, 0.0000, 12.0000, 117.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(31, 32, 'opening_stock', NULL, NULL, 263.0000, 0.0000, 12.3500, 263.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(32, 33, 'opening_stock', NULL, NULL, 414.0000, 0.0000, 12.7000, 414.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(33, 34, 'opening_stock', NULL, NULL, 302.0000, 0.0000, 13.0500, 302.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(34, 35, 'opening_stock', NULL, NULL, 405.0000, 0.0000, 13.4000, 405.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(35, 36, 'opening_stock', NULL, NULL, 4.0000, 0.0000, 13.7500, 4.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(36, 37, 'opening_stock', NULL, NULL, 346.0000, 0.0000, 14.1000, 346.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(37, 38, 'opening_stock', NULL, NULL, 47.0000, 0.0000, 14.4500, 47.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(38, 39, 'opening_stock', NULL, NULL, 125.0000, 0.0000, 14.8000, 125.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(39, 40, 'opening_stock', NULL, NULL, 393.0000, 0.0000, 15.1500, 393.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(40, 42, 'opening_stock', NULL, NULL, 310.0000, 0.0000, 15.8500, 310.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(41, 43, 'opening_stock', NULL, NULL, 334.0000, 0.0000, 16.2000, 334.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(42, 44, 'opening_stock', NULL, NULL, 62.0000, 0.0000, 16.5500, 62.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(43, 45, 'opening_stock', NULL, NULL, 219.0000, 0.0000, 16.9000, 219.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(44, 46, 'opening_stock', NULL, NULL, 263.0000, 0.0000, 28.0000, 263.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(45, 47, 'opening_stock', NULL, NULL, 340.0000, 0.0000, 28.3500, 340.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(46, 48, 'opening_stock', NULL, NULL, 283.0000, 0.0000, 28.7000, 283.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(47, 49, 'opening_stock', NULL, NULL, 47.0000, 0.0000, 29.0500, 47.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(48, 50, 'opening_stock', NULL, NULL, 123.0000, 0.0000, 29.4000, 123.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(49, 51, 'opening_stock', NULL, NULL, 20.0000, 0.0000, 29.7500, 20.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(50, 51, 'adjustment', NULL, NULL, 0.0000, 32.0000, 29.7500, -12.0000, 1, 'Controlled negative inventory demo', '2026-07-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(51, 52, 'opening_stock', NULL, NULL, 70.0000, 0.0000, 30.1000, 70.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(52, 53, 'opening_stock', NULL, NULL, 138.0000, 0.0000, 30.4500, 138.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(53, 54, 'opening_stock', NULL, NULL, 321.0000, 0.0000, 30.8000, 321.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(54, 55, 'opening_stock', NULL, NULL, 279.0000, 0.0000, 31.1500, 279.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(55, 56, 'opening_stock', NULL, NULL, 60.0000, 0.0000, 31.5000, 60.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(56, 57, 'opening_stock', NULL, NULL, 255.0000, 0.0000, 31.8500, 255.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(57, 58, 'opening_stock', NULL, NULL, 379.0000, 0.0000, 32.2000, 379.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(58, 59, 'opening_stock', NULL, NULL, 52.0000, 0.0000, 32.5500, 52.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(59, 60, 'opening_stock', NULL, NULL, 80.0000, 0.0000, 32.9000, 80.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(60, 61, 'opening_stock', NULL, NULL, 178.0000, 0.0000, 22.0000, 178.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(61, 62, 'opening_stock', NULL, NULL, 133.0000, 0.0000, 22.3500, 133.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(62, 63, 'opening_stock', NULL, NULL, 230.0000, 0.0000, 22.7000, 230.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(63, 64, 'opening_stock', NULL, NULL, 130.0000, 0.0000, 23.0500, 130.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(64, 65, 'opening_stock', NULL, NULL, 148.0000, 0.0000, 23.4000, 148.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(65, 66, 'opening_stock', NULL, NULL, 162.0000, 0.0000, 23.7500, 162.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(66, 67, 'opening_stock', NULL, NULL, 275.0000, 0.0000, 24.1000, 275.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(67, 68, 'opening_stock', NULL, NULL, 169.0000, 0.0000, 24.4500, 169.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(68, 69, 'opening_stock', NULL, NULL, 210.0000, 0.0000, 24.8000, 210.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(69, 70, 'opening_stock', NULL, NULL, 67.0000, 0.0000, 25.1500, 67.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(70, 71, 'opening_stock', NULL, NULL, 4.0000, 0.0000, 25.5000, 4.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(71, 72, 'opening_stock', NULL, NULL, 319.0000, 0.0000, 25.8500, 319.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(72, 73, 'opening_stock', NULL, NULL, 28.0000, 0.0000, 26.2000, 28.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(73, 74, 'opening_stock', NULL, NULL, 315.0000, 0.0000, 26.5500, 315.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(74, 75, 'opening_stock', NULL, NULL, 321.0000, 0.0000, 26.9000, 321.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(75, 76, 'opening_stock', NULL, NULL, 102.0000, 0.0000, 18.0000, 102.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(76, 77, 'opening_stock', NULL, NULL, 240.0000, 0.0000, 18.3500, 240.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(77, 78, 'opening_stock', NULL, NULL, 96.0000, 0.0000, 18.7000, 96.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(78, 79, 'opening_stock', NULL, NULL, 64.0000, 0.0000, 19.0500, 64.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(79, 80, 'opening_stock', NULL, NULL, 98.0000, 0.0000, 19.4000, 98.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(80, 82, 'opening_stock', NULL, NULL, 182.0000, 0.0000, 20.1000, 182.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(81, 83, 'opening_stock', NULL, NULL, 332.0000, 0.0000, 20.4500, 332.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(82, 84, 'opening_stock', NULL, NULL, 45.0000, 0.0000, 20.8000, 45.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(83, 85, 'opening_stock', NULL, NULL, 202.0000, 0.0000, 21.1500, 202.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(84, 86, 'opening_stock', NULL, NULL, 382.0000, 0.0000, 21.5000, 382.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(85, 87, 'opening_stock', NULL, NULL, 148.0000, 0.0000, 21.8500, 148.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(86, 88, 'opening_stock', NULL, NULL, 156.0000, 0.0000, 22.2000, 156.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(87, 89, 'opening_stock', NULL, NULL, 299.0000, 0.0000, 22.5500, 299.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(88, 90, 'opening_stock', NULL, NULL, 390.0000, 0.0000, 22.9000, 390.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(89, 91, 'opening_stock', NULL, NULL, 86.0000, 0.0000, 14.0000, 86.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(90, 92, 'opening_stock', NULL, NULL, 61.0000, 0.0000, 14.3500, 61.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(91, 93, 'opening_stock', NULL, NULL, 268.0000, 0.0000, 14.7000, 268.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(92, 94, 'opening_stock', NULL, NULL, 345.0000, 0.0000, 15.0500, 345.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(93, 95, 'opening_stock', NULL, NULL, 283.0000, 0.0000, 15.4000, 283.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(94, 96, 'opening_stock', NULL, NULL, 231.0000, 0.0000, 15.7500, 231.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(95, 97, 'opening_stock', NULL, NULL, 101.0000, 0.0000, 16.1000, 101.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(96, 98, 'opening_stock', NULL, NULL, 93.0000, 0.0000, 16.4500, 93.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(97, 99, 'opening_stock', NULL, NULL, 309.0000, 0.0000, 16.8000, 309.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(98, 100, 'opening_stock', NULL, NULL, 324.0000, 0.0000, 17.1500, 324.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(99, 101, 'opening_stock', NULL, NULL, 20.0000, 0.0000, 17.5000, 20.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(100, 101, 'adjustment', NULL, NULL, 0.0000, 32.0000, 17.5000, -12.0000, 1, 'Controlled negative inventory demo', '2026-07-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(101, 102, 'opening_stock', NULL, NULL, 37.0000, 0.0000, 17.8500, 37.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(102, 103, 'opening_stock', NULL, NULL, 285.0000, 0.0000, 18.2000, 285.0000, 1, 'Opening stock', '2026-01-10 11:00:43', '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(103, 104, 'opening_stock', NULL, NULL, 296.0000, 0.0000, 18.5500, 296.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(104, 105, 'opening_stock', NULL, NULL, 120.0000, 0.0000, 18.9000, 120.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(105, 106, 'opening_stock', NULL, NULL, 4.0000, 0.0000, 6.0000, 4.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(106, 107, 'opening_stock', NULL, NULL, 200.0000, 0.0000, 6.3500, 200.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(107, 108, 'opening_stock', NULL, NULL, 123.0000, 0.0000, 6.7000, 123.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(108, 109, 'opening_stock', NULL, NULL, 127.0000, 0.0000, 7.0500, 127.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(109, 110, 'opening_stock', NULL, NULL, 170.0000, 0.0000, 7.4000, 170.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(110, 111, 'opening_stock', NULL, NULL, 396.0000, 0.0000, 7.7500, 396.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(111, 112, 'opening_stock', NULL, NULL, 51.0000, 0.0000, 8.1000, 51.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(112, 113, 'opening_stock', NULL, NULL, 374.0000, 0.0000, 8.4500, 374.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(113, 114, 'opening_stock', NULL, NULL, 124.0000, 0.0000, 8.8000, 124.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(114, 115, 'opening_stock', NULL, NULL, 209.0000, 0.0000, 9.1500, 209.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(115, 116, 'opening_stock', NULL, NULL, 116.0000, 0.0000, 9.5000, 116.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(116, 117, 'opening_stock', NULL, NULL, 60.0000, 0.0000, 9.8500, 60.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(117, 118, 'opening_stock', NULL, NULL, 271.0000, 0.0000, 10.2000, 271.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(118, 119, 'opening_stock', NULL, NULL, 194.0000, 0.0000, 10.5500, 194.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(119, 120, 'opening_stock', NULL, NULL, 260.0000, 0.0000, 10.9000, 260.0000, 1, 'Opening stock', '2026-01-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(120, 77, 'purchase', 'App\\Models\\GoodsReceipt', 1, 14.0000, 0.0000, 18.3500, 254.0000, 1, 'Receipt GR-00002', '2026-08-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(121, 78, 'purchase', 'App\\Models\\GoodsReceipt', 1, 25.0000, 0.0000, 18.7000, 121.0000, 1, 'Receipt GR-00002', '2026-08-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(122, 104, 'purchase', 'App\\Models\\GoodsReceipt', 1, 29.0000, 0.0000, 18.5500, 325.0000, 1, 'Receipt GR-00002', '2026-08-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(123, 107, 'purchase', 'App\\Models\\GoodsReceipt', 1, 29.0000, 0.0000, 6.3500, 229.0000, 1, 'Receipt GR-00002', '2026-08-10 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(124, 4, 'purchase', 'App\\Models\\GoodsReceipt', 2, 8.0000, 0.0000, 39.0500, 151.0000, 1, 'Receipt GR-00003', '2026-08-03 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(125, 72, 'purchase', 'App\\Models\\GoodsReceipt', 2, 13.0000, 0.0000, 25.8500, 332.0000, 1, 'Receipt GR-00003', '2026-08-03 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(126, 79, 'purchase', 'App\\Models\\GoodsReceipt', 2, 8.5000, 0.0000, 19.0500, 72.5000, 1, 'Receipt GR-00003', '2026-08-03 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(127, 91, 'purchase', 'App\\Models\\GoodsReceipt', 2, 9.0000, 0.0000, 14.0000, 95.0000, 1, 'Receipt GR-00003', '2026-08-03 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(128, 25, 'purchase', 'App\\Models\\GoodsReceipt', 3, 34.0000, 0.0000, 21.1500, 63.0000, 1, 'Receipt GR-00004', '2026-07-27 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(129, 50, 'purchase', 'App\\Models\\GoodsReceipt', 3, 20.0000, 0.0000, 29.4000, 143.0000, 1, 'Receipt GR-00004', '2026-07-27 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(130, 87, 'purchase', 'App\\Models\\GoodsReceipt', 3, 24.0000, 0.0000, 21.8500, 172.0000, 1, 'Receipt GR-00004', '2026-07-27 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(131, 97, 'purchase', 'App\\Models\\GoodsReceipt', 3, 40.0000, 0.0000, 16.1000, 141.0000, 1, 'Receipt GR-00004', '2026-07-27 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(132, 24, 'purchase', 'App\\Models\\GoodsReceipt', 4, 24.0000, 0.0000, 20.8000, 407.0000, 1, 'Receipt GR-00007', '2026-07-06 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(133, 46, 'purchase', 'App\\Models\\GoodsReceipt', 4, 15.0000, 0.0000, 28.0000, 278.0000, 1, 'Receipt GR-00007', '2026-07-06 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(134, 61, 'purchase', 'App\\Models\\GoodsReceipt', 4, 18.0000, 0.0000, 22.0000, 196.0000, 1, 'Receipt GR-00007', '2026-07-06 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(135, 78, 'purchase', 'App\\Models\\GoodsReceipt', 4, 23.0000, 0.0000, 18.7000, 144.0000, 1, 'Receipt GR-00007', '2026-07-06 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(136, 17, 'purchase', 'App\\Models\\GoodsReceipt', 5, 22.0000, 0.0000, 18.3500, 176.0000, 1, 'Receipt GR-00008', '2026-06-29 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(137, 23, 'purchase', 'App\\Models\\GoodsReceipt', 5, 37.0000, 0.0000, 20.4500, 301.0000, 1, 'Receipt GR-00008', '2026-06-29 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(138, 75, 'purchase', 'App\\Models\\GoodsReceipt', 5, 29.0000, 0.0000, 26.9000, 350.0000, 1, 'Receipt GR-00008', '2026-06-29 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(139, 100, 'purchase', 'App\\Models\\GoodsReceipt', 5, 39.0000, 0.0000, 17.1500, 363.0000, 1, 'Receipt GR-00008', '2026-06-29 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(140, 34, 'purchase', 'App\\Models\\GoodsReceipt', 6, 15.0000, 0.0000, 13.0500, 317.0000, 1, 'Receipt GR-00009', '2026-06-22 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(141, 42, 'purchase', 'App\\Models\\GoodsReceipt', 6, 17.5000, 0.0000, 15.8500, 327.5000, 1, 'Receipt GR-00009', '2026-06-22 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(142, 80, 'purchase', 'App\\Models\\GoodsReceipt', 6, 14.0000, 0.0000, 19.4000, 112.0000, 1, 'Receipt GR-00009', '2026-06-22 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(143, 82, 'purchase', 'App\\Models\\GoodsReceipt', 6, 14.5000, 0.0000, 20.1000, 196.5000, 1, 'Receipt GR-00009', '2026-06-22 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(144, 10, 'purchase', 'App\\Models\\GoodsReceipt', 7, 7.5000, 0.0000, 41.1500, 267.5000, 1, 'Receipt GR-00012', '2026-06-01 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(145, 33, 'purchase', 'App\\Models\\GoodsReceipt', 7, 16.0000, 0.0000, 12.7000, 430.0000, 1, 'Receipt GR-00012', '2026-06-01 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(146, 36, 'purchase', 'App\\Models\\GoodsReceipt', 7, 20.0000, 0.0000, 13.7500, 24.0000, 1, 'Receipt GR-00012', '2026-06-01 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(147, 49, 'purchase', 'App\\Models\\GoodsReceipt', 7, 6.5000, 0.0000, 29.0500, 53.5000, 1, 'Receipt GR-00012', '2026-06-01 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(148, 17, 'purchase', 'App\\Models\\GoodsReceipt', 8, 17.0000, 0.0000, 18.3500, 193.0000, 1, 'Receipt GR-00013', '2026-05-25 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(149, 19, 'purchase', 'App\\Models\\GoodsReceipt', 8, 16.0000, 0.0000, 19.0500, 176.0000, 1, 'Receipt GR-00013', '2026-05-25 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(150, 62, 'purchase', 'App\\Models\\GoodsReceipt', 8, 21.0000, 0.0000, 22.3500, 154.0000, 1, 'Receipt GR-00013', '2026-05-25 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(151, 111, 'purchase', 'App\\Models\\GoodsReceipt', 8, 21.0000, 0.0000, 7.7500, 417.0000, 1, 'Receipt GR-00013', '2026-05-25 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(152, 42, 'purchase', 'App\\Models\\GoodsReceipt', 9, 13.0000, 0.0000, 15.8500, 340.5000, 1, 'Receipt GR-00014', '2026-05-18 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(153, 60, 'purchase', 'App\\Models\\GoodsReceipt', 9, 18.0000, 0.0000, 32.9000, 98.0000, 1, 'Receipt GR-00014', '2026-05-18 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(154, 62, 'purchase', 'App\\Models\\GoodsReceipt', 9, 38.0000, 0.0000, 22.3500, 192.0000, 1, 'Receipt GR-00014', '2026-05-18 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(155, 79, 'purchase', 'App\\Models\\GoodsReceipt', 9, 16.0000, 0.0000, 19.0500, 88.5000, 1, 'Receipt GR-00014', '2026-05-18 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(156, 39, 'purchase', 'App\\Models\\GoodsReceipt', 10, 30.0000, 0.0000, 14.8000, 155.0000, 1, 'Receipt GR-00017', '2026-04-27 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(157, 65, 'purchase', 'App\\Models\\GoodsReceipt', 10, 37.0000, 0.0000, 23.4000, 185.0000, 1, 'Receipt GR-00017', '2026-04-27 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(158, 112, 'purchase', 'App\\Models\\GoodsReceipt', 10, 34.0000, 0.0000, 8.1000, 85.0000, 1, 'Receipt GR-00017', '2026-04-27 11:00:44', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(159, 119, 'purchase', 'App\\Models\\GoodsReceipt', 10, 32.0000, 0.0000, 10.5500, 226.0000, 1, 'Receipt GR-00017', '2026-04-27 11:00:44', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(160, 16, 'purchase', 'App\\Models\\GoodsReceipt', 11, 7.0000, 0.0000, 18.0000, 299.0000, 1, 'Receipt GR-00018', '2026-04-20 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(161, 34, 'purchase', 'App\\Models\\GoodsReceipt', 11, 19.5000, 0.0000, 13.0500, 336.5000, 1, 'Receipt GR-00018', '2026-04-20 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(162, 62, 'purchase', 'App\\Models\\GoodsReceipt', 11, 11.0000, 0.0000, 22.3500, 203.0000, 1, 'Receipt GR-00018', '2026-04-20 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(163, 115, 'purchase', 'App\\Models\\GoodsReceipt', 11, 7.5000, 0.0000, 9.1500, 216.5000, 1, 'Receipt GR-00018', '2026-04-20 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(164, 18, 'purchase', 'App\\Models\\GoodsReceipt', 12, 16.0000, 0.0000, 18.7000, 152.0000, 1, 'Receipt GR-00019', '2026-04-13 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(165, 25, 'purchase', 'App\\Models\\GoodsReceipt', 12, 37.0000, 0.0000, 21.1500, 100.0000, 1, 'Receipt GR-00019', '2026-04-13 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(166, 59, 'purchase', 'App\\Models\\GoodsReceipt', 12, 31.0000, 0.0000, 32.5500, 83.0000, 1, 'Receipt GR-00019', '2026-04-13 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(167, 97, 'purchase', 'App\\Models\\GoodsReceipt', 12, 26.0000, 0.0000, 16.1000, 167.0000, 1, 'Receipt GR-00019', '2026-04-13 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(168, 8, 'purchase', 'App\\Models\\GoodsReceipt', 13, 38.0000, 0.0000, 40.4500, 417.0000, 1, 'Receipt GR-00022', '2026-03-23 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(169, 20, 'purchase', 'App\\Models\\GoodsReceipt', 13, 16.0000, 0.0000, 19.4000, 206.0000, 1, 'Receipt GR-00022', '2026-03-23 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(170, 23, 'purchase', 'App\\Models\\GoodsReceipt', 13, 21.0000, 0.0000, 20.4500, 322.0000, 1, 'Receipt GR-00022', '2026-03-23 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(171, 88, 'purchase', 'App\\Models\\GoodsReceipt', 13, 24.0000, 0.0000, 22.2000, 180.0000, 1, 'Receipt GR-00022', '2026-03-23 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(172, 25, 'purchase', 'App\\Models\\GoodsReceipt', 14, 34.0000, 0.0000, 21.1500, 134.0000, 1, 'Receipt GR-00023', '2026-03-16 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(173, 48, 'purchase', 'App\\Models\\GoodsReceipt', 14, 32.0000, 0.0000, 28.7000, 315.0000, 1, 'Receipt GR-00023', '2026-03-16 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(174, 85, 'purchase', 'App\\Models\\GoodsReceipt', 14, 20.0000, 0.0000, 21.1500, 222.0000, 1, 'Receipt GR-00023', '2026-03-16 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(175, 97, 'purchase', 'App\\Models\\GoodsReceipt', 14, 35.0000, 0.0000, 16.1000, 202.0000, 1, 'Receipt GR-00023', '2026-03-16 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(176, 5, 'purchase', 'App\\Models\\GoodsReceipt', 15, 20.0000, 0.0000, 39.4000, 104.0000, 1, 'Receipt GR-00024', '2026-03-09 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(177, 13, 'purchase', 'App\\Models\\GoodsReceipt', 15, 5.5000, 0.0000, 42.2000, 415.5000, 1, 'Receipt GR-00024', '2026-03-09 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(178, 40, 'purchase', 'App\\Models\\GoodsReceipt', 15, 7.5000, 0.0000, 15.1500, 400.5000, 1, 'Receipt GR-00024', '2026-03-09 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(179, 84, 'purchase', 'App\\Models\\GoodsReceipt', 15, 19.0000, 0.0000, 20.8000, 64.0000, 1, 'Receipt GR-00024', '2026-03-09 11:00:45', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(180, 56, 'sale', 'App\\Models\\Invoice', 1, 0.0000, 1.0000, 31.5000, 59.0000, 1, 'Invoice INV-00001', '2026-09-07 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(181, 58, 'sale', 'App\\Models\\Invoice', 1, 0.0000, 5.0000, 32.2000, 374.0000, 1, 'Invoice INV-00001', '2026-09-07 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(182, 5, 'sale', 'App\\Models\\Invoice', 2, 0.0000, 5.0000, 39.4000, 99.0000, 1, 'Invoice INV-00002', '2026-09-05 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(183, 8, 'sale', 'App\\Models\\Invoice', 2, 0.0000, 8.0000, 40.4500, 409.0000, 1, 'Invoice INV-00002', '2026-09-05 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(184, 14, 'sale', 'App\\Models\\Invoice', 2, 0.0000, 3.0000, 42.5500, 190.0000, 1, 'Invoice INV-00002', '2026-09-05 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(185, 114, 'sale', 'App\\Models\\Invoice', 2, 0.0000, 3.0000, 8.8000, 121.0000, 1, 'Invoice INV-00002', '2026-09-05 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(186, 28, 'sale', 'App\\Models\\Invoice', 3, 0.0000, 7.0000, 22.2000, 209.0000, 1, 'Invoice INV-00003', '2026-09-03 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(187, 73, 'sale', 'App\\Models\\Invoice', 3, 0.0000, 4.0000, 26.2000, 24.0000, 1, 'Invoice INV-00003', '2026-09-03 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(188, 120, 'sale', 'App\\Models\\Invoice', 3, 0.0000, 3.0000, 10.9000, 257.0000, 1, 'Invoice INV-00003', '2026-09-03 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(189, 44, 'sale', 'App\\Models\\Invoice', 4, 0.0000, 6.0000, 16.5500, 56.0000, 1, 'Invoice INV-00004', '2026-09-01 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(190, 87, 'sale', 'App\\Models\\Invoice', 4, 0.0000, 7.0000, 21.8500, 165.0000, 1, 'Invoice INV-00004', '2026-09-01 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(191, 104, 'sale', 'App\\Models\\Invoice', 4, 0.0000, 5.0000, 18.5500, 320.0000, 1, 'Invoice INV-00004', '2026-09-01 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(192, 17, 'sale', 'App\\Models\\Invoice', 5, 0.0000, 7.0000, 18.3500, 186.0000, 1, 'Invoice INV-00005', '2026-08-30 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(193, 38, 'sale', 'App\\Models\\Invoice', 5, 0.0000, 6.0000, 14.4500, 41.0000, 1, 'Invoice INV-00005', '2026-08-30 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(194, 44, 'sale', 'App\\Models\\Invoice', 5, 0.0000, 2.0000, 16.5500, 54.0000, 1, 'Invoice INV-00005', '2026-08-30 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(195, 52, 'sale', 'App\\Models\\Invoice', 5, 0.0000, 2.0000, 30.1000, 68.0000, 1, 'Invoice INV-00005', '2026-08-30 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(196, 97, 'sale', 'App\\Models\\Invoice', 5, 0.0000, 7.0000, 16.1000, 195.0000, 1, 'Invoice INV-00005', '2026-08-30 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(197, 65, 'sale', 'App\\Models\\Invoice', 6, 0.0000, 6.0000, 23.4000, 179.0000, 1, 'Invoice INV-00006', '2026-08-28 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(198, 66, 'sale', 'App\\Models\\Invoice', 6, 0.0000, 7.0000, 23.7500, 155.0000, 1, 'Invoice INV-00006', '2026-08-28 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(199, 35, 'sale', 'App\\Models\\Invoice', 7, 0.0000, 3.0000, 13.4000, 402.0000, 1, 'Invoice INV-00007', '2026-08-26 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(200, 52, 'sale', 'App\\Models\\Invoice', 7, 0.0000, 7.0000, 30.1000, 61.0000, 1, 'Invoice INV-00007', '2026-08-26 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(201, 65, 'sale', 'App\\Models\\Invoice', 7, 0.0000, 2.0000, 23.4000, 177.0000, 1, 'Invoice INV-00007', '2026-08-26 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(202, 75, 'sale', 'App\\Models\\Invoice', 7, 0.0000, 3.0000, 26.9000, 347.0000, 1, 'Invoice INV-00007', '2026-08-26 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(203, 17, 'sale', 'App\\Models\\Invoice', 8, 0.0000, 4.0000, 18.3500, 182.0000, 1, 'Invoice INV-00008', '2026-08-24 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(204, 18, 'sale', 'App\\Models\\Invoice', 8, 0.0000, 7.0000, 18.7000, 145.0000, 1, 'Invoice INV-00008', '2026-08-24 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(205, 112, 'sale', 'App\\Models\\Invoice', 8, 0.0000, 1.0000, 8.1000, 84.0000, 1, 'Invoice INV-00008', '2026-08-24 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(206, 39, 'sale', 'App\\Models\\Invoice', 9, 0.0000, 6.0000, 14.8000, 149.0000, 1, 'Invoice INV-00009', '2026-08-22 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(207, 46, 'sale', 'App\\Models\\Invoice', 9, 0.0000, 1.0000, 28.0000, 277.0000, 1, 'Invoice INV-00009', '2026-08-22 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(208, 54, 'sale', 'App\\Models\\Invoice', 9, 0.0000, 1.0000, 30.8000, 320.0000, 1, 'Invoice INV-00009', '2026-08-22 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(209, 20, 'sale', 'App\\Models\\Invoice', 10, 0.0000, 8.0000, 19.4000, 198.0000, 1, 'Invoice INV-00010', '2026-08-20 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(210, 27, 'sale', 'App\\Models\\Invoice', 10, 0.0000, 1.0000, 21.8500, 345.0000, 1, 'Invoice INV-00010', '2026-08-20 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(211, 40, 'sale', 'App\\Models\\Invoice', 10, 0.0000, 2.0000, 15.1500, 398.5000, 1, 'Invoice INV-00010', '2026-08-20 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(212, 53, 'sale', 'App\\Models\\Invoice', 10, 0.0000, 2.0000, 30.4500, 136.0000, 1, 'Invoice INV-00010', '2026-08-20 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(213, 88, 'sale', 'App\\Models\\Invoice', 10, 0.0000, 4.0000, 22.2000, 176.0000, 1, 'Invoice INV-00010', '2026-08-20 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(214, 28, 'sale', 'App\\Models\\Invoice', 11, 0.0000, 5.0000, 22.2000, 204.0000, 1, 'Invoice INV-00011', '2026-08-18 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(215, 44, 'sale', 'App\\Models\\Invoice', 11, 0.0000, 6.0000, 16.5500, 48.0000, 1, 'Invoice INV-00011', '2026-08-18 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(216, 95, 'sale', 'App\\Models\\Invoice', 11, 0.0000, 4.0000, 15.4000, 279.0000, 1, 'Invoice INV-00011', '2026-08-18 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(217, 3, 'sale', 'App\\Models\\Invoice', 12, 0.0000, 7.0000, 38.7000, 329.0000, 1, 'Invoice INV-00012', '2026-08-16 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(218, 8, 'sale', 'App\\Models\\Invoice', 12, 0.0000, 2.0000, 40.4500, 407.0000, 1, 'Invoice INV-00012', '2026-08-16 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(219, 49, 'sale', 'App\\Models\\Invoice', 12, 0.0000, 6.0000, 29.0500, 47.5000, 1, 'Invoice INV-00012', '2026-08-16 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(220, 55, 'sale', 'App\\Models\\Invoice', 12, 0.0000, 4.0000, 31.1500, 275.0000, 1, 'Invoice INV-00012', '2026-08-16 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(221, 47, 'sale', 'App\\Models\\Invoice', 13, 0.0000, 2.0000, 28.3500, 338.0000, 1, 'Invoice INV-00013', '2026-08-14 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(222, 77, 'sale', 'App\\Models\\Invoice', 13, 0.0000, 2.0000, 18.3500, 252.0000, 1, 'Invoice INV-00013', '2026-08-14 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(223, 107, 'sale', 'App\\Models\\Invoice', 13, 0.0000, 7.0000, 6.3500, 222.0000, 1, 'Invoice INV-00013', '2026-08-14 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(224, 68, 'sale', 'App\\Models\\Invoice', 14, 0.0000, 7.0000, 24.4500, 162.0000, 1, 'Invoice INV-00014', '2026-08-12 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(225, 82, 'sale', 'App\\Models\\Invoice', 14, 0.0000, 2.0000, 20.1000, 194.5000, 1, 'Invoice INV-00014', '2026-08-12 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(226, 92, 'sale', 'App\\Models\\Invoice', 14, 0.0000, 3.0000, 14.3500, 58.0000, 1, 'Invoice INV-00014', '2026-08-12 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(227, 96, 'sale', 'App\\Models\\Invoice', 14, 0.0000, 3.0000, 15.7500, 228.0000, 1, 'Invoice INV-00014', '2026-08-12 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(228, 109, 'sale', 'App\\Models\\Invoice', 14, 0.0000, 5.0000, 7.0500, 122.0000, 1, 'Invoice INV-00014', '2026-08-12 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(229, 9, 'sale', 'App\\Models\\Invoice', 15, 0.0000, 3.0000, 40.8000, 192.0000, 1, 'Invoice INV-00015', '2026-08-10 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(230, 58, 'sale', 'App\\Models\\Invoice', 15, 0.0000, 5.0000, 32.2000, 369.0000, 1, 'Invoice INV-00015', '2026-08-10 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(231, 69, 'sale', 'App\\Models\\Invoice', 15, 0.0000, 8.0000, 24.8000, 202.0000, 1, 'Invoice INV-00015', '2026-08-10 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(232, 70, 'sale', 'App\\Models\\Invoice', 15, 0.0000, 8.0000, 25.1500, 59.0000, 1, 'Invoice INV-00015', '2026-08-10 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(233, 71, 'sale', 'App\\Models\\Invoice', 15, 0.0000, 6.0000, 25.5000, -2.0000, 1, 'Invoice INV-00015', '2026-08-10 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(234, 3, 'sale', 'App\\Models\\Invoice', 16, 0.0000, 1.0000, 38.7000, 328.0000, 1, 'Invoice INV-00016', '2026-08-08 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(235, 54, 'sale', 'App\\Models\\Invoice', 16, 0.0000, 8.0000, 30.8000, 312.0000, 1, 'Invoice INV-00016', '2026-08-08 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(236, 60, 'sale', 'App\\Models\\Invoice', 16, 0.0000, 5.0000, 32.9000, 93.0000, 1, 'Invoice INV-00016', '2026-08-08 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(237, 27, 'sale', 'App\\Models\\Invoice', 18, 0.0000, 4.0000, 21.8500, 341.0000, 1, 'Invoice INV-00018', '2026-08-04 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(238, 36, 'sale', 'App\\Models\\Invoice', 18, 0.0000, 1.0000, 13.7500, 23.0000, 1, 'Invoice INV-00018', '2026-08-04 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(239, 37, 'sale', 'App\\Models\\Invoice', 18, 0.0000, 2.0000, 14.1000, 344.0000, 1, 'Invoice INV-00018', '2026-08-04 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(240, 64, 'sale', 'App\\Models\\Invoice', 18, 0.0000, 5.0000, 23.0500, 125.0000, 1, 'Invoice INV-00018', '2026-08-04 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(241, 29, 'sale', 'App\\Models\\Invoice', 19, 0.0000, 7.0000, 22.5500, 296.0000, 1, 'Invoice INV-00019', '2026-08-02 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(242, 53, 'sale', 'App\\Models\\Invoice', 19, 0.0000, 3.0000, 30.4500, 133.0000, 1, 'Invoice INV-00019', '2026-08-02 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(243, 55, 'sale', 'App\\Models\\Invoice', 19, 0.0000, 2.0000, 31.1500, 273.0000, 1, 'Invoice INV-00019', '2026-08-02 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(244, 23, 'sale', 'App\\Models\\Invoice', 20, 0.0000, 6.0000, 20.4500, 316.0000, 1, 'Invoice INV-00020', '2026-07-31 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(245, 37, 'sale', 'App\\Models\\Invoice', 20, 0.0000, 7.0000, 14.1000, 337.0000, 1, 'Invoice INV-00020', '2026-07-31 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(246, 67, 'sale', 'App\\Models\\Invoice', 20, 0.0000, 4.0000, 24.1000, 271.0000, 1, 'Invoice INV-00020', '2026-07-31 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(247, 85, 'sale', 'App\\Models\\Invoice', 20, 0.0000, 3.0000, 21.1500, 219.0000, 1, 'Invoice INV-00020', '2026-07-31 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(248, 21, 'sale', 'App\\Models\\Invoice', 21, 0.0000, 5.0000, 19.7500, 309.0000, 1, 'Invoice INV-00021', '2026-07-29 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(249, 22, 'sale', 'App\\Models\\Invoice', 21, 0.0000, 8.0000, 20.1000, 92.0000, 1, 'Invoice INV-00021', '2026-07-29 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(250, 46, 'sale', 'App\\Models\\Invoice', 21, 0.0000, 6.0000, 28.0000, 271.0000, 1, 'Invoice INV-00021', '2026-07-29 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(251, 50, 'sale', 'App\\Models\\Invoice', 21, 0.0000, 8.0000, 29.4000, 135.0000, 1, 'Invoice INV-00021', '2026-07-29 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(252, 15, 'sale', 'App\\Models\\Invoice', 22, 0.0000, 2.0000, 42.9000, 201.0000, 1, 'Invoice INV-00022', '2026-07-27 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(253, 52, 'sale', 'App\\Models\\Invoice', 22, 0.0000, 6.0000, 30.1000, 55.0000, 1, 'Invoice INV-00022', '2026-07-27 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(254, 117, 'sale', 'App\\Models\\Invoice', 22, 0.0000, 8.0000, 9.8500, 52.0000, 1, 'Invoice INV-00022', '2026-07-27 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(255, 59, 'sale', 'App\\Models\\Invoice', 23, 0.0000, 4.0000, 32.5500, 79.0000, 1, 'Invoice INV-00023', '2026-07-25 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(256, 100, 'sale', 'App\\Models\\Invoice', 23, 0.0000, 3.0000, 17.1500, 360.0000, 1, 'Invoice INV-00023', '2026-07-25 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(257, 11, 'sale', 'App\\Models\\Invoice', 24, 0.0000, 5.0000, 41.5000, 242.0000, 1, 'Invoice INV-00024', '2026-07-23 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(258, 35, 'sale', 'App\\Models\\Invoice', 24, 0.0000, 1.0000, 13.4000, 401.0000, 1, 'Invoice INV-00024', '2026-07-23 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(259, 38, 'sale', 'App\\Models\\Invoice', 24, 0.0000, 4.0000, 14.4500, 37.0000, 1, 'Invoice INV-00024', '2026-07-23 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(260, 7, 'sale', 'App\\Models\\Invoice', 25, 0.0000, 1.0000, 40.1000, 217.0000, 1, 'Invoice INV-00025', '2026-07-21 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(261, 10, 'sale', 'App\\Models\\Invoice', 25, 0.0000, 6.0000, 41.1500, 261.5000, 1, 'Invoice INV-00025', '2026-07-21 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(262, 72, 'sale', 'App\\Models\\Invoice', 25, 0.0000, 4.0000, 25.8500, 328.0000, 1, 'Invoice INV-00025', '2026-07-21 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(263, 102, 'sale', 'App\\Models\\Invoice', 25, 0.0000, 4.0000, 17.8500, 33.0000, 1, 'Invoice INV-00025', '2026-07-21 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(264, 113, 'sale', 'App\\Models\\Invoice', 25, 0.0000, 6.0000, 8.4500, 368.0000, 1, 'Invoice INV-00025', '2026-07-21 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(265, 9, 'sale', 'App\\Models\\Invoice', 26, 0.0000, 7.0000, 40.8000, 185.0000, 1, 'Invoice INV-00026', '2026-07-19 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(266, 50, 'sale', 'App\\Models\\Invoice', 26, 0.0000, 3.0000, 29.4000, 132.0000, 1, 'Invoice INV-00026', '2026-07-19 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(267, 115, 'sale', 'App\\Models\\Invoice', 26, 0.0000, 7.0000, 9.1500, 209.5000, 1, 'Invoice INV-00026', '2026-07-19 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(268, 118, 'sale', 'App\\Models\\Invoice', 26, 0.0000, 5.0000, 10.2000, 266.0000, 1, 'Invoice INV-00026', '2026-07-19 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(269, 72, 'sale', 'App\\Models\\Invoice', 27, 0.0000, 4.0000, 25.8500, 324.0000, 1, 'Invoice INV-00027', '2026-07-17 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(270, 93, 'sale', 'App\\Models\\Invoice', 27, 0.0000, 2.0000, 14.7000, 266.0000, 1, 'Invoice INV-00027', '2026-07-17 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(271, 10, 'sale', 'App\\Models\\Invoice', 28, 0.0000, 7.0000, 41.1500, 254.5000, 1, 'Invoice INV-00028', '2026-07-15 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(272, 44, 'sale', 'App\\Models\\Invoice', 28, 0.0000, 5.0000, 16.5500, 43.0000, 1, 'Invoice INV-00028', '2026-07-15 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(273, 48, 'sale', 'App\\Models\\Invoice', 28, 0.0000, 4.0000, 28.7000, 311.0000, 1, 'Invoice INV-00028', '2026-07-15 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(274, 52, 'sale', 'App\\Models\\Invoice', 28, 0.0000, 5.0000, 30.1000, 50.0000, 1, 'Invoice INV-00028', '2026-07-15 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(275, 77, 'sale', 'App\\Models\\Invoice', 28, 0.0000, 7.0000, 18.3500, 245.0000, 1, 'Invoice INV-00028', '2026-07-15 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(276, 15, 'sale', 'App\\Models\\Invoice', 29, 0.0000, 7.0000, 42.9000, 194.0000, 1, 'Invoice INV-00029', '2026-07-13 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(277, 23, 'sale', 'App\\Models\\Invoice', 29, 0.0000, 1.0000, 20.4500, 315.0000, 1, 'Invoice INV-00029', '2026-07-13 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(278, 30, 'sale', 'App\\Models\\Invoice', 29, 0.0000, 4.0000, 22.9000, 136.0000, 1, 'Invoice INV-00029', '2026-07-13 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(279, 44, 'sale', 'App\\Models\\Invoice', 29, 0.0000, 3.0000, 16.5500, 40.0000, 1, 'Invoice INV-00029', '2026-07-13 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(280, 46, 'sale', 'App\\Models\\Invoice', 30, 0.0000, 5.0000, 28.0000, 266.0000, 1, 'Invoice INV-00030', '2026-07-11 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(281, 110, 'sale', 'App\\Models\\Invoice', 30, 0.0000, 1.0000, 7.4000, 169.0000, 1, 'Invoice INV-00030', '2026-07-11 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(282, 71, 'sale', 'App\\Models\\Invoice', 31, 0.0000, 7.0000, 25.5000, -9.0000, 1, 'Invoice INV-00031', '2026-07-09 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(283, 73, 'sale', 'App\\Models\\Invoice', 31, 0.0000, 3.0000, 26.2000, 21.0000, 1, 'Invoice INV-00031', '2026-07-09 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(284, 74, 'sale', 'App\\Models\\Invoice', 31, 0.0000, 4.0000, 26.5500, 311.0000, 1, 'Invoice INV-00031', '2026-07-09 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(285, 60, 'sale', 'App\\Models\\Invoice', 32, 0.0000, 8.0000, 32.9000, 85.0000, 1, 'Invoice INV-00032', '2026-07-07 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(286, 77, 'sale', 'App\\Models\\Invoice', 32, 0.0000, 3.0000, 18.3500, 242.0000, 1, 'Invoice INV-00032', '2026-07-07 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(287, 106, 'sale', 'App\\Models\\Invoice', 32, 0.0000, 4.0000, 6.0000, 0.0000, 1, 'Invoice INV-00032', '2026-07-07 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(288, 57, 'sale', 'App\\Models\\Invoice', 33, 0.0000, 1.0000, 31.8500, 254.0000, 1, 'Invoice INV-00033', '2026-07-05 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(289, 74, 'sale', 'App\\Models\\Invoice', 33, 0.0000, 1.0000, 26.5500, 310.0000, 1, 'Invoice INV-00033', '2026-07-05 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(290, 88, 'sale', 'App\\Models\\Invoice', 33, 0.0000, 2.0000, 22.2000, 174.0000, 1, 'Invoice INV-00033', '2026-07-05 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(291, 105, 'sale', 'App\\Models\\Invoice', 33, 0.0000, 2.0000, 18.9000, 118.0000, 1, 'Invoice INV-00033', '2026-07-05 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(292, 6, 'sale', 'App\\Models\\Invoice', 35, 0.0000, 1.0000, 39.7500, 360.0000, 1, 'Invoice INV-00035', '2026-07-01 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46');
INSERT INTO `inventory_transactions` (`id`, `item_id`, `type`, `reference_type`, `reference_id`, `qty_in`, `qty_out`, `unit_cost`, `balance_after`, `created_by`, `memo`, `occurred_at`, `created_at`, `updated_at`) VALUES
(293, 61, 'sale', 'App\\Models\\Invoice', 35, 0.0000, 7.0000, 22.0000, 189.0000, 1, 'Invoice INV-00035', '2026-07-01 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(294, 95, 'sale', 'App\\Models\\Invoice', 35, 0.0000, 7.0000, 15.4000, 272.0000, 1, 'Invoice INV-00035', '2026-07-01 18:00:00', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(295, 108, 'sale', 'App\\Models\\Invoice', 35, 0.0000, 6.0000, 6.7000, 117.0000, 1, 'Invoice INV-00035', '2026-07-01 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(296, 65, 'sale', 'App\\Models\\Invoice', 36, 0.0000, 7.0000, 23.4000, 170.0000, 1, 'Invoice INV-00036', '2026-06-29 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(297, 70, 'sale', 'App\\Models\\Invoice', 36, 0.0000, 7.0000, 25.1500, 52.0000, 1, 'Invoice INV-00036', '2026-06-29 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(298, 72, 'sale', 'App\\Models\\Invoice', 36, 0.0000, 5.0000, 25.8500, 319.0000, 1, 'Invoice INV-00036', '2026-06-29 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(299, 90, 'sale', 'App\\Models\\Invoice', 36, 0.0000, 4.0000, 22.9000, 386.0000, 1, 'Invoice INV-00036', '2026-06-29 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(300, 96, 'sale', 'App\\Models\\Invoice', 36, 0.0000, 2.0000, 15.7500, 226.0000, 1, 'Invoice INV-00036', '2026-06-29 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(301, 33, 'sale', 'App\\Models\\Invoice', 37, 0.0000, 7.0000, 12.7000, 423.0000, 1, 'Invoice INV-00037', '2026-06-27 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(302, 99, 'sale', 'App\\Models\\Invoice', 37, 0.0000, 6.0000, 16.8000, 303.0000, 1, 'Invoice INV-00037', '2026-06-27 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(303, 105, 'sale', 'App\\Models\\Invoice', 37, 0.0000, 2.0000, 18.9000, 116.0000, 1, 'Invoice INV-00037', '2026-06-27 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(304, 10, 'sale', 'App\\Models\\Invoice', 38, 0.0000, 7.0000, 41.1500, 247.5000, 1, 'Invoice INV-00038', '2026-06-25 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(305, 20, 'sale', 'App\\Models\\Invoice', 38, 0.0000, 6.0000, 19.4000, 192.0000, 1, 'Invoice INV-00038', '2026-06-25 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(306, 72, 'sale', 'App\\Models\\Invoice', 38, 0.0000, 5.0000, 25.8500, 314.0000, 1, 'Invoice INV-00038', '2026-06-25 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(307, 104, 'sale', 'App\\Models\\Invoice', 38, 0.0000, 3.0000, 18.5500, 317.0000, 1, 'Invoice INV-00038', '2026-06-25 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(308, 111, 'sale', 'App\\Models\\Invoice', 38, 0.0000, 1.0000, 7.7500, 416.0000, 1, 'Invoice INV-00038', '2026-06-25 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(309, 60, 'sale', 'App\\Models\\Invoice', 39, 0.0000, 6.0000, 32.9000, 79.0000, 1, 'Invoice INV-00039', '2026-06-23 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(310, 63, 'sale', 'App\\Models\\Invoice', 39, 0.0000, 2.0000, 22.7000, 228.0000, 1, 'Invoice INV-00039', '2026-06-23 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(311, 80, 'sale', 'App\\Models\\Invoice', 39, 0.0000, 5.0000, 19.4000, 107.0000, 1, 'Invoice INV-00039', '2026-06-23 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(312, 113, 'sale', 'App\\Models\\Invoice', 39, 0.0000, 4.0000, 8.4500, 364.0000, 1, 'Invoice INV-00039', '2026-06-23 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(313, 24, 'sale', 'App\\Models\\Invoice', 40, 0.0000, 3.0000, 20.8000, 404.0000, 1, 'Invoice INV-00040', '2026-06-21 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(314, 37, 'sale', 'App\\Models\\Invoice', 40, 0.0000, 2.0000, 14.1000, 335.0000, 1, 'Invoice INV-00040', '2026-06-21 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(315, 84, 'sale', 'App\\Models\\Invoice', 40, 0.0000, 1.0000, 20.8000, 63.0000, 1, 'Invoice INV-00040', '2026-06-21 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(316, 112, 'sale', 'App\\Models\\Invoice', 40, 0.0000, 4.0000, 8.1000, 80.0000, 1, 'Invoice INV-00040', '2026-06-21 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(317, 2, 'sale', 'App\\Models\\Invoice', 41, 0.0000, 4.0000, 38.3500, 294.0000, 1, 'Invoice INV-00041', '2026-06-19 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(318, 86, 'sale', 'App\\Models\\Invoice', 41, 0.0000, 2.0000, 21.5000, 380.0000, 1, 'Invoice INV-00041', '2026-06-19 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(319, 19, 'sale', 'App\\Models\\Invoice', 42, 0.0000, 4.0000, 19.0500, 172.0000, 1, 'Invoice INV-00042', '2026-06-17 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(320, 36, 'sale', 'App\\Models\\Invoice', 42, 0.0000, 3.0000, 13.7500, 20.0000, 1, 'Invoice INV-00042', '2026-06-17 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(321, 117, 'sale', 'App\\Models\\Invoice', 42, 0.0000, 2.0000, 9.8500, 50.0000, 1, 'Invoice INV-00042', '2026-06-17 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(322, 23, 'sale', 'App\\Models\\Invoice', 43, 0.0000, 8.0000, 20.4500, 307.0000, 1, 'Invoice INV-00043', '2026-06-15 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(323, 45, 'sale', 'App\\Models\\Invoice', 43, 0.0000, 1.0000, 16.9000, 218.0000, 1, 'Invoice INV-00043', '2026-06-15 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(324, 108, 'sale', 'App\\Models\\Invoice', 43, 0.0000, 5.0000, 6.7000, 112.0000, 1, 'Invoice INV-00043', '2026-06-15 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(325, 45, 'sale', 'App\\Models\\Invoice', 44, 0.0000, 3.0000, 16.9000, 215.0000, 1, 'Invoice INV-00044', '2026-06-13 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(326, 47, 'sale', 'App\\Models\\Invoice', 44, 0.0000, 4.0000, 28.3500, 334.0000, 1, 'Invoice INV-00044', '2026-06-13 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(327, 17, 'sale', 'App\\Models\\Invoice', 45, 0.0000, 4.0000, 18.3500, 178.0000, 1, 'Invoice INV-00045', '2026-06-11 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(328, 41, 'sale', 'App\\Models\\Invoice', 45, 0.0000, 5.0000, 15.5000, -5.0000, 1, 'Invoice INV-00045', '2026-06-11 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(329, 49, 'sale', 'App\\Models\\Invoice', 45, 0.0000, 6.0000, 29.0500, 41.5000, 1, 'Invoice INV-00045', '2026-06-11 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(330, 78, 'sale', 'App\\Models\\Invoice', 45, 0.0000, 3.0000, 18.7000, 141.0000, 1, 'Invoice INV-00045', '2026-06-11 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(331, 103, 'sale', 'App\\Models\\Invoice', 45, 0.0000, 5.0000, 18.2000, 280.0000, 1, 'Invoice INV-00045', '2026-06-11 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(332, 5, 'sale', 'App\\Models\\Invoice', 46, 0.0000, 1.0000, 39.4000, 98.0000, 1, 'Invoice INV-00046', '2026-06-09 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(333, 90, 'sale', 'App\\Models\\Invoice', 46, 0.0000, 3.0000, 22.9000, 383.0000, 1, 'Invoice INV-00046', '2026-06-09 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(334, 108, 'sale', 'App\\Models\\Invoice', 46, 0.0000, 3.0000, 6.7000, 109.0000, 1, 'Invoice INV-00046', '2026-06-09 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(335, 70, 'sale', 'App\\Models\\Invoice', 47, 0.0000, 8.0000, 25.1500, 44.0000, 1, 'Invoice INV-00047', '2026-06-07 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(336, 80, 'sale', 'App\\Models\\Invoice', 47, 0.0000, 6.0000, 19.4000, 101.0000, 1, 'Invoice INV-00047', '2026-06-07 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(337, 94, 'sale', 'App\\Models\\Invoice', 47, 0.0000, 4.0000, 15.0500, 341.0000, 1, 'Invoice INV-00047', '2026-06-07 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(338, 99, 'sale', 'App\\Models\\Invoice', 47, 0.0000, 7.0000, 16.8000, 296.0000, 1, 'Invoice INV-00047', '2026-06-07 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(339, 118, 'sale', 'App\\Models\\Invoice', 47, 0.0000, 7.0000, 10.2000, 259.0000, 1, 'Invoice INV-00047', '2026-06-07 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(340, 13, 'sale', 'App\\Models\\Invoice', 48, 0.0000, 5.0000, 42.2000, 410.5000, 1, 'Invoice INV-00048', '2026-06-05 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(341, 107, 'sale', 'App\\Models\\Invoice', 48, 0.0000, 7.0000, 6.3500, 215.0000, 1, 'Invoice INV-00048', '2026-06-05 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(342, 23, 'sale', 'App\\Models\\Invoice', 49, 0.0000, 7.0000, 20.4500, 300.0000, 1, 'Invoice INV-00049', '2026-06-03 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(343, 25, 'sale', 'App\\Models\\Invoice', 49, 0.0000, 1.0000, 21.1500, 133.0000, 1, 'Invoice INV-00049', '2026-06-03 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(344, 45, 'sale', 'App\\Models\\Invoice', 49, 0.0000, 5.0000, 16.9000, 210.0000, 1, 'Invoice INV-00049', '2026-06-03 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(345, 50, 'sale', 'App\\Models\\Invoice', 49, 0.0000, 4.0000, 29.4000, 128.0000, 1, 'Invoice INV-00049', '2026-06-03 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(346, 87, 'sale', 'App\\Models\\Invoice', 49, 0.0000, 4.0000, 21.8500, 161.0000, 1, 'Invoice INV-00049', '2026-06-03 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(347, 11, 'sale', 'App\\Models\\Invoice', 50, 0.0000, 5.0000, 41.5000, 237.0000, 1, 'Invoice INV-00050', '2026-06-01 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(348, 34, 'sale', 'App\\Models\\Invoice', 50, 0.0000, 3.0000, 13.0500, 333.5000, 1, 'Invoice INV-00050', '2026-06-01 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(349, 106, 'sale', 'App\\Models\\Invoice', 50, 0.0000, 5.0000, 6.0000, -5.0000, 1, 'Invoice INV-00050', '2026-06-01 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(350, 111, 'sale', 'App\\Models\\Invoice', 50, 0.0000, 2.0000, 7.7500, 414.0000, 1, 'Invoice INV-00050', '2026-06-01 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(351, 11, 'sale', 'App\\Models\\Invoice', 52, 0.0000, 5.0000, 41.5000, 232.0000, 1, 'Invoice INV-00052', '2026-05-28 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(352, 104, 'sale', 'App\\Models\\Invoice', 52, 0.0000, 2.0000, 18.5500, 315.0000, 1, 'Invoice INV-00052', '2026-05-28 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(353, 21, 'sale', 'App\\Models\\Invoice', 53, 0.0000, 3.0000, 19.7500, 306.0000, 1, 'Invoice INV-00053', '2026-05-26 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(354, 24, 'sale', 'App\\Models\\Invoice', 53, 0.0000, 3.0000, 20.8000, 401.0000, 1, 'Invoice INV-00053', '2026-05-26 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(355, 44, 'sale', 'App\\Models\\Invoice', 53, 0.0000, 3.0000, 16.5500, 37.0000, 1, 'Invoice INV-00053', '2026-05-26 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(356, 68, 'sale', 'App\\Models\\Invoice', 53, 0.0000, 4.0000, 24.4500, 158.0000, 1, 'Invoice INV-00053', '2026-05-26 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(357, 91, 'sale', 'App\\Models\\Invoice', 53, 0.0000, 2.0000, 14.0000, 93.0000, 1, 'Invoice INV-00053', '2026-05-26 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(358, 43, 'sale', 'App\\Models\\Invoice', 54, 0.0000, 6.0000, 16.2000, 328.0000, 1, 'Invoice INV-00054', '2026-05-24 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(359, 65, 'sale', 'App\\Models\\Invoice', 54, 0.0000, 1.0000, 23.4000, 169.0000, 1, 'Invoice INV-00054', '2026-05-24 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(360, 68, 'sale', 'App\\Models\\Invoice', 54, 0.0000, 2.0000, 24.4500, 156.0000, 1, 'Invoice INV-00054', '2026-05-24 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(361, 92, 'sale', 'App\\Models\\Invoice', 54, 0.0000, 3.0000, 14.3500, 55.0000, 1, 'Invoice INV-00054', '2026-05-24 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(362, 47, 'sale', 'App\\Models\\Invoice', 55, 0.0000, 7.0000, 28.3500, 327.0000, 1, 'Invoice INV-00055', '2026-05-22 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(363, 66, 'sale', 'App\\Models\\Invoice', 55, 0.0000, 3.0000, 23.7500, 152.0000, 1, 'Invoice INV-00055', '2026-05-22 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(364, 87, 'sale', 'App\\Models\\Invoice', 55, 0.0000, 7.0000, 21.8500, 154.0000, 1, 'Invoice INV-00055', '2026-05-22 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(365, 96, 'sale', 'App\\Models\\Invoice', 55, 0.0000, 7.0000, 15.7500, 219.0000, 1, 'Invoice INV-00055', '2026-05-22 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(366, 106, 'sale', 'App\\Models\\Invoice', 55, 0.0000, 1.0000, 6.0000, -6.0000, 1, 'Invoice INV-00055', '2026-05-22 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(367, 35, 'sale', 'App\\Models\\Invoice', 56, 0.0000, 4.0000, 13.4000, 397.0000, 1, 'Invoice INV-00056', '2026-05-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(368, 58, 'sale', 'App\\Models\\Invoice', 56, 0.0000, 5.0000, 32.2000, 364.0000, 1, 'Invoice INV-00056', '2026-05-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(369, 63, 'sale', 'App\\Models\\Invoice', 56, 0.0000, 1.0000, 22.7000, 227.0000, 1, 'Invoice INV-00056', '2026-05-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(370, 86, 'sale', 'App\\Models\\Invoice', 56, 0.0000, 1.0000, 21.5000, 379.0000, 1, 'Invoice INV-00056', '2026-05-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(371, 95, 'sale', 'App\\Models\\Invoice', 56, 0.0000, 1.0000, 15.4000, 271.0000, 1, 'Invoice INV-00056', '2026-05-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(372, 23, 'sale', 'App\\Models\\Invoice', 57, 0.0000, 7.0000, 20.4500, 293.0000, 1, 'Invoice INV-00057', '2026-05-18 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(373, 60, 'sale', 'App\\Models\\Invoice', 57, 0.0000, 1.0000, 32.9000, 78.0000, 1, 'Invoice INV-00057', '2026-05-18 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(374, 96, 'sale', 'App\\Models\\Invoice', 57, 0.0000, 3.0000, 15.7500, 216.0000, 1, 'Invoice INV-00057', '2026-05-18 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(375, 104, 'sale', 'App\\Models\\Invoice', 57, 0.0000, 8.0000, 18.5500, 307.0000, 1, 'Invoice INV-00057', '2026-05-18 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(376, 22, 'sale', 'App\\Models\\Invoice', 58, 0.0000, 2.0000, 20.1000, 90.0000, 1, 'Invoice INV-00058', '2026-05-16 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(377, 23, 'sale', 'App\\Models\\Invoice', 58, 0.0000, 3.0000, 20.4500, 290.0000, 1, 'Invoice INV-00058', '2026-05-16 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(378, 73, 'sale', 'App\\Models\\Invoice', 58, 0.0000, 1.0000, 26.2000, 20.0000, 1, 'Invoice INV-00058', '2026-05-16 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(379, 80, 'sale', 'App\\Models\\Invoice', 58, 0.0000, 6.0000, 19.4000, 95.0000, 1, 'Invoice INV-00058', '2026-05-16 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(380, 116, 'sale', 'App\\Models\\Invoice', 58, 0.0000, 2.0000, 9.5000, 114.0000, 1, 'Invoice INV-00058', '2026-05-16 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(381, 3, 'sale', 'App\\Models\\Invoice', 59, 0.0000, 8.0000, 38.7000, 320.0000, 1, 'Invoice INV-00059', '2026-05-14 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(382, 54, 'sale', 'App\\Models\\Invoice', 59, 0.0000, 8.0000, 30.8000, 304.0000, 1, 'Invoice INV-00059', '2026-05-14 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(383, 59, 'sale', 'App\\Models\\Invoice', 59, 0.0000, 6.0000, 32.5500, 73.0000, 1, 'Invoice INV-00059', '2026-05-14 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(384, 60, 'sale', 'App\\Models\\Invoice', 59, 0.0000, 8.0000, 32.9000, 70.0000, 1, 'Invoice INV-00059', '2026-05-14 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(385, 116, 'sale', 'App\\Models\\Invoice', 59, 0.0000, 1.0000, 9.5000, 113.0000, 1, 'Invoice INV-00059', '2026-05-14 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(386, 57, 'sale', 'App\\Models\\Invoice', 60, 0.0000, 6.0000, 31.8500, 248.0000, 1, 'Invoice INV-00060', '2026-05-12 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(387, 96, 'sale', 'App\\Models\\Invoice', 60, 0.0000, 3.0000, 15.7500, 213.0000, 1, 'Invoice INV-00060', '2026-05-12 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(388, 10, 'sale', 'App\\Models\\Invoice', 61, 0.0000, 8.0000, 41.1500, 239.5000, 1, 'Invoice INV-00061', '2026-05-10 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(389, 53, 'sale', 'App\\Models\\Invoice', 61, 0.0000, 8.0000, 30.4500, 125.0000, 1, 'Invoice INV-00061', '2026-05-10 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(390, 2, 'sale', 'App\\Models\\Invoice', 62, 0.0000, 1.0000, 38.3500, 293.0000, 1, 'Invoice INV-00062', '2026-05-08 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(391, 28, 'sale', 'App\\Models\\Invoice', 62, 0.0000, 2.0000, 22.2000, 202.0000, 1, 'Invoice INV-00062', '2026-05-08 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(392, 70, 'sale', 'App\\Models\\Invoice', 62, 0.0000, 1.0000, 25.1500, 43.0000, 1, 'Invoice INV-00062', '2026-05-08 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(393, 86, 'sale', 'App\\Models\\Invoice', 62, 0.0000, 7.0000, 21.5000, 372.0000, 1, 'Invoice INV-00062', '2026-05-08 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(394, 87, 'sale', 'App\\Models\\Invoice', 62, 0.0000, 5.0000, 21.8500, 149.0000, 1, 'Invoice INV-00062', '2026-05-08 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(395, 5, 'sale', 'App\\Models\\Invoice', 63, 0.0000, 2.0000, 39.4000, 96.0000, 1, 'Invoice INV-00063', '2026-05-06 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(396, 19, 'sale', 'App\\Models\\Invoice', 63, 0.0000, 3.0000, 19.0500, 169.0000, 1, 'Invoice INV-00063', '2026-05-06 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(397, 42, 'sale', 'App\\Models\\Invoice', 63, 0.0000, 7.0000, 15.8500, 333.5000, 1, 'Invoice INV-00063', '2026-05-06 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(398, 103, 'sale', 'App\\Models\\Invoice', 63, 0.0000, 6.0000, 18.2000, 274.0000, 1, 'Invoice INV-00063', '2026-05-06 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(399, 45, 'sale', 'App\\Models\\Invoice', 64, 0.0000, 2.0000, 16.9000, 208.0000, 1, 'Invoice INV-00064', '2026-05-04 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(400, 83, 'sale', 'App\\Models\\Invoice', 64, 0.0000, 7.0000, 20.4500, 325.0000, 1, 'Invoice INV-00064', '2026-05-04 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(401, 16, 'sale', 'App\\Models\\Invoice', 65, 0.0000, 2.0000, 18.0000, 297.0000, 1, 'Invoice INV-00065', '2026-05-02 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(402, 24, 'sale', 'App\\Models\\Invoice', 65, 0.0000, 3.0000, 20.8000, 398.0000, 1, 'Invoice INV-00065', '2026-05-02 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(403, 31, 'sale', 'App\\Models\\Invoice', 65, 0.0000, 1.0000, 12.0000, 116.0000, 1, 'Invoice INV-00065', '2026-05-02 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(404, 77, 'sale', 'App\\Models\\Invoice', 65, 0.0000, 8.0000, 18.3500, 234.0000, 1, 'Invoice INV-00065', '2026-05-02 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(405, 99, 'sale', 'App\\Models\\Invoice', 65, 0.0000, 7.0000, 16.8000, 289.0000, 1, 'Invoice INV-00065', '2026-05-02 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(406, 18, 'sale', 'App\\Models\\Invoice', 66, 0.0000, 6.0000, 18.7000, 139.0000, 1, 'Invoice INV-00066', '2026-04-30 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(407, 40, 'sale', 'App\\Models\\Invoice', 66, 0.0000, 3.0000, 15.1500, 395.5000, 1, 'Invoice INV-00066', '2026-04-30 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(408, 49, 'sale', 'App\\Models\\Invoice', 66, 0.0000, 7.0000, 29.0500, 34.5000, 1, 'Invoice INV-00066', '2026-04-30 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(409, 58, 'sale', 'App\\Models\\Invoice', 66, 0.0000, 3.0000, 32.2000, 361.0000, 1, 'Invoice INV-00066', '2026-04-30 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(410, 90, 'sale', 'App\\Models\\Invoice', 66, 0.0000, 8.0000, 22.9000, 375.0000, 1, 'Invoice INV-00066', '2026-04-30 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(411, 3, 'sale', 'App\\Models\\Invoice', 67, 0.0000, 7.0000, 38.7000, 313.0000, 1, 'Invoice INV-00067', '2026-04-28 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(412, 31, 'sale', 'App\\Models\\Invoice', 67, 0.0000, 1.0000, 12.0000, 115.0000, 1, 'Invoice INV-00067', '2026-04-28 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(413, 62, 'sale', 'App\\Models\\Invoice', 67, 0.0000, 6.0000, 22.3500, 197.0000, 1, 'Invoice INV-00067', '2026-04-28 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(414, 95, 'sale', 'App\\Models\\Invoice', 67, 0.0000, 8.0000, 15.4000, 263.0000, 1, 'Invoice INV-00067', '2026-04-28 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(415, 2, 'sale', 'App\\Models\\Invoice', 69, 0.0000, 4.0000, 38.3500, 289.0000, 1, 'Invoice INV-00069', '2026-04-24 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(416, 81, 'sale', 'App\\Models\\Invoice', 69, 0.0000, 3.0000, 19.7500, -3.0000, 1, 'Invoice INV-00069', '2026-04-24 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(417, 104, 'sale', 'App\\Models\\Invoice', 69, 0.0000, 7.0000, 18.5500, 300.0000, 1, 'Invoice INV-00069', '2026-04-24 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(418, 110, 'sale', 'App\\Models\\Invoice', 69, 0.0000, 5.0000, 7.4000, 164.0000, 1, 'Invoice INV-00069', '2026-04-24 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(419, 3, 'sale', 'App\\Models\\Invoice', 70, 0.0000, 5.0000, 38.7000, 308.0000, 1, 'Invoice INV-00070', '2026-04-22 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(420, 33, 'sale', 'App\\Models\\Invoice', 70, 0.0000, 8.0000, 12.7000, 415.0000, 1, 'Invoice INV-00070', '2026-04-22 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(421, 66, 'sale', 'App\\Models\\Invoice', 70, 0.0000, 3.0000, 23.7500, 149.0000, 1, 'Invoice INV-00070', '2026-04-22 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(422, 119, 'sale', 'App\\Models\\Invoice', 70, 0.0000, 8.0000, 10.5500, 218.0000, 1, 'Invoice INV-00070', '2026-04-22 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(423, 44, 'sale', 'App\\Models\\Invoice', 71, 0.0000, 3.0000, 16.5500, 34.0000, 1, 'Invoice INV-00071', '2026-04-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(424, 46, 'sale', 'App\\Models\\Invoice', 71, 0.0000, 5.0000, 28.0000, 261.0000, 1, 'Invoice INV-00071', '2026-04-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(425, 79, 'sale', 'App\\Models\\Invoice', 71, 0.0000, 7.0000, 19.0500, 81.5000, 1, 'Invoice INV-00071', '2026-04-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(426, 84, 'sale', 'App\\Models\\Invoice', 71, 0.0000, 6.0000, 20.8000, 57.0000, 1, 'Invoice INV-00071', '2026-04-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(427, 98, 'sale', 'App\\Models\\Invoice', 71, 0.0000, 8.0000, 16.4500, 85.0000, 1, 'Invoice INV-00071', '2026-04-20 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(428, 22, 'sale', 'App\\Models\\Invoice', 72, 0.0000, 7.0000, 20.1000, 83.0000, 1, 'Invoice INV-00072', '2026-04-18 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(429, 25, 'sale', 'App\\Models\\Invoice', 72, 0.0000, 5.0000, 21.1500, 128.0000, 1, 'Invoice INV-00072', '2026-04-18 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(430, 27, 'sale', 'App\\Models\\Invoice', 72, 0.0000, 5.0000, 21.8500, 336.0000, 1, 'Invoice INV-00072', '2026-04-18 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(431, 72, 'sale', 'App\\Models\\Invoice', 72, 0.0000, 2.0000, 25.8500, 312.0000, 1, 'Invoice INV-00072', '2026-04-18 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(432, 2, 'sale', 'App\\Models\\Invoice', 73, 0.0000, 8.0000, 38.3500, 281.0000, 1, 'Invoice INV-00073', '2026-04-16 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(433, 117, 'sale', 'App\\Models\\Invoice', 73, 0.0000, 8.0000, 9.8500, 42.0000, 1, 'Invoice INV-00073', '2026-04-16 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(434, 48, 'sale', 'App\\Models\\Invoice', 74, 0.0000, 6.0000, 28.7000, 305.0000, 1, 'Invoice INV-00074', '2026-04-14 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(435, 60, 'sale', 'App\\Models\\Invoice', 74, 0.0000, 2.0000, 32.9000, 68.0000, 1, 'Invoice INV-00074', '2026-04-14 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(436, 99, 'sale', 'App\\Models\\Invoice', 74, 0.0000, 4.0000, 16.8000, 285.0000, 1, 'Invoice INV-00074', '2026-04-14 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(437, 5, 'sale', 'App\\Models\\Invoice', 75, 0.0000, 4.0000, 39.4000, 92.0000, 1, 'Invoice INV-00075', '2026-04-12 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(438, 31, 'sale', 'App\\Models\\Invoice', 75, 0.0000, 7.0000, 12.0000, 108.0000, 1, 'Invoice INV-00075', '2026-04-12 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(439, 60, 'sale', 'App\\Models\\Invoice', 75, 0.0000, 5.0000, 32.9000, 63.0000, 1, 'Invoice INV-00075', '2026-04-12 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(440, 77, 'sale', 'App\\Models\\Invoice', 75, 0.0000, 5.0000, 18.3500, 229.0000, 1, 'Invoice INV-00075', '2026-04-12 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(441, 118, 'sale', 'App\\Models\\Invoice', 75, 0.0000, 1.0000, 10.2000, 258.0000, 1, 'Invoice INV-00075', '2026-04-12 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(442, 25, 'sale', 'App\\Models\\Invoice', 76, 0.0000, 4.0000, 21.1500, 124.0000, 1, 'Invoice INV-00076', '2026-04-10 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(443, 29, 'sale', 'App\\Models\\Invoice', 76, 0.0000, 4.0000, 22.5500, 292.0000, 1, 'Invoice INV-00076', '2026-04-10 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(444, 57, 'sale', 'App\\Models\\Invoice', 76, 0.0000, 1.0000, 31.8500, 247.0000, 1, 'Invoice INV-00076', '2026-04-10 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(445, 94, 'sale', 'App\\Models\\Invoice', 76, 0.0000, 4.0000, 15.0500, 337.0000, 1, 'Invoice INV-00076', '2026-04-10 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(446, 53, 'sale', 'App\\Models\\Invoice', 77, 0.0000, 1.0000, 30.4500, 124.0000, 1, 'Invoice INV-00077', '2026-04-08 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(447, 63, 'sale', 'App\\Models\\Invoice', 77, 0.0000, 8.0000, 22.7000, 219.0000, 1, 'Invoice INV-00077', '2026-04-08 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(448, 77, 'sale', 'App\\Models\\Invoice', 77, 0.0000, 5.0000, 18.3500, 224.0000, 1, 'Invoice INV-00077', '2026-04-08 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(449, 103, 'sale', 'App\\Models\\Invoice', 77, 0.0000, 5.0000, 18.2000, 269.0000, 1, 'Invoice INV-00077', '2026-04-08 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(450, 6, 'sale', 'App\\Models\\Invoice', 78, 0.0000, 6.0000, 39.7500, 354.0000, 1, 'Invoice INV-00078', '2026-04-06 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(451, 45, 'sale', 'App\\Models\\Invoice', 78, 0.0000, 5.0000, 16.9000, 203.0000, 1, 'Invoice INV-00078', '2026-04-06 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(452, 53, 'sale', 'App\\Models\\Invoice', 78, 0.0000, 4.0000, 30.4500, 120.0000, 1, 'Invoice INV-00078', '2026-04-06 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(453, 105, 'sale', 'App\\Models\\Invoice', 78, 0.0000, 8.0000, 18.9000, 108.0000, 1, 'Invoice INV-00078', '2026-04-06 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(454, 5, 'sale', 'App\\Models\\Invoice', 79, 0.0000, 1.0000, 39.4000, 91.0000, 1, 'Invoice INV-00079', '2026-04-04 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(455, 96, 'sale', 'App\\Models\\Invoice', 79, 0.0000, 2.0000, 15.7500, 211.0000, 1, 'Invoice INV-00079', '2026-04-04 18:00:00', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(456, 107, 'sale', 'App\\Models\\Invoice', 79, 0.0000, 6.0000, 6.3500, 209.0000, 1, 'Invoice INV-00079', '2026-04-04 18:00:00', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(457, 109, 'sale', 'App\\Models\\Invoice', 79, 0.0000, 2.0000, 7.0500, 120.0000, 1, 'Invoice INV-00079', '2026-04-04 18:00:00', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(458, 14, 'sale', 'App\\Models\\Invoice', 80, 0.0000, 6.0000, 42.5500, 184.0000, 1, 'Invoice INV-00080', '2026-04-02 18:00:00', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(459, 67, 'sale', 'App\\Models\\Invoice', 80, 0.0000, 5.0000, 24.1000, 266.0000, 1, 'Invoice INV-00080', '2026-04-02 18:00:00', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(460, 81, 'sale', 'App\\Models\\Invoice', 80, 0.0000, 7.0000, 19.7500, -10.0000, 1, 'Invoice INV-00080', '2026-04-02 18:00:00', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(461, 103, 'sale', 'App\\Models\\Invoice', 80, 0.0000, 2.0000, 18.2000, 267.0000, 1, 'Invoice INV-00080', '2026-04-02 18:00:00', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(462, 105, 'sale', 'App\\Models\\Invoice', 80, 0.0000, 8.0000, 18.9000, 100.0000, 1, 'Invoice INV-00080', '2026-04-02 18:00:00', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(463, 2, 'credit_memo', 'App\\Models\\CreditMemo', 9, 2.0000, 0.0000, 38.3500, 283.0000, 1, 'Credit memo CM-00009', '2026-09-09 18:00:00', '2026-09-10 15:09:23', '2026-09-10 15:09:23'),
(464, 9, 'credit_memo', 'App\\Models\\CreditMemo', 9, 1.0000, 0.0000, 40.8000, 186.0000, 1, 'Credit memo CM-00009', '2026-09-09 18:00:00', '2026-09-10 15:09:23', '2026-09-10 15:09:23'),
(465, 5, 'credit_memo', 'App\\Models\\CreditMemo', 10, 1.0000, 0.0000, 39.4000, 92.0000, 1, 'Credit memo CM-00010', '2026-09-09 18:00:00', '2026-09-10 15:09:36', '2026-09-10 15:09:36'),
(466, 2, 'purchase', 'App\\Models\\GoodsReceipt', 16, 1.0000, 0.0000, 38.3500, 284.0000, 1, 'Receipt GR-00025', '2026-09-16 18:00:00', '2026-09-17 08:09:55', '2026-09-17 08:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `sales_order_id` bigint UNSIGNED DEFAULT NULL,
  `tax_code_id` bigint UNSIGNED DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_due` decimal(15,2) NOT NULL DEFAULT '0.00',
  `customer_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `print_later` tinyint(1) NOT NULL DEFAULT '0',
  `email_later` tinyint(1) NOT NULL DEFAULT '0',
  `is_pending` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `customer_id`, `sales_order_id`, `tax_code_id`, `invoice_date`, `due_date`, `status`, `subtotal`, `tax_total`, `total`, `amount_paid`, `balance_due`, `customer_message`, `memo`, `class`, `template`, `print_later`, `email_later`, `is_pending`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'INV-00001', 1, NULL, 1, '2026-09-08', '2026-09-23', 'open', 290.50, 18.45, 308.95, 0.00, 308.95, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(2, 'INV-00002', 2, NULL, 1, '2026-09-06', '2026-09-21', 'partial', 943.65, 59.92, 1003.57, 401.43, 602.14, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(3, 'INV-00003', 3, NULL, 1, '2026-09-04', '2026-09-19', 'paid', 478.00, 30.35, 508.35, 508.35, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(4, 'INV-00004', 4, NULL, 1, '2026-09-02', '2026-09-17', 'open', 565.60, 35.91, 601.51, 0.00, 601.51, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(5, 'INV-00005', 5, NULL, 1, '2026-08-31', '2026-09-15', 'paid', 692.95, 44.00, 736.95, 736.95, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(6, 'INV-00006', 6, NULL, 1, '2026-08-29', '2026-09-13', 'partial', 486.80, 30.91, 517.71, 207.08, 310.63, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(7, 'INV-00007', 7, NULL, 1, '2026-08-27', '2026-09-11', 'open', 591.20, 37.54, 628.74, 0.00, 628.74, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(8, 'INV-00008', 8, NULL, 1, '2026-08-25', '2026-09-09', 'partial', 349.70, 22.20, 371.90, 148.76, 223.14, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(9, 'INV-00009', 9, NULL, 1, '2026-08-23', '2026-09-07', 'paid', 245.30, 15.58, 260.88, 260.88, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(10, 'INV-00010', 10, NULL, 1, '2026-08-21', '2026-09-05', 'open', 575.85, 36.56, 612.41, 0.00, 612.41, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(11, 'INV-00011', 11, NULL, 1, '2026-08-19', '2026-09-03', 'paid', 452.70, 28.74, 481.44, 481.44, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(12, 'INV-00012', 12, NULL, 1, '2026-08-17', '2026-09-01', 'partial', 932.65, 59.23, 991.88, 396.75, 595.13, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(13, 'INV-00013', 13, NULL, 1, '2026-08-15', '2026-08-30', 'open', 231.65, 14.71, 246.36, 0.00, 246.36, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(14, 'INV-00014', 14, NULL, 1, '2026-08-13', '2026-08-28', 'partial', 548.95, 34.86, 583.81, 233.52, 350.29, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(15, 'INV-00015', 15, NULL, 1, '2026-08-11', '2026-08-26', 'paid', 1288.75, 81.83, 1370.58, 1370.58, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(16, 'INV-00016', 16, NULL, 1, '2026-08-09', '2026-08-24', 'open', 672.75, 42.72, 715.47, 0.00, 715.47, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(17, 'INV-00017', 17, NULL, 1, '2026-08-07', '2026-08-22', 'draft', 434.80, 27.61, 462.41, 0.00, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(18, 'INV-00018', 18, NULL, 1, '2026-08-05', '2026-08-20', 'paid', 399.80, 25.39, 425.19, 425.19, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(19, 'INV-00019', 19, NULL, 1, '2026-08-03', '2026-08-18', 'partial', 488.00, 30.98, 518.98, 207.59, 311.39, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(20, 'INV-00020', 20, NULL, 1, '2026-08-01', '2026-08-16', 'open', 629.70, 39.99, 669.69, 0.00, 669.69, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(21, 'INV-00021', 21, NULL, 1, '2026-07-30', '2026-08-14', 'partial', 1029.25, 65.35, 1094.60, 437.84, 656.76, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(22, 'INV-00022', 22, NULL, 1, '2026-07-28', '2026-08-12', 'paid', 535.50, 34.01, 569.51, 569.51, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(23, 'INV-00023', 23, NULL, 1, '2026-07-26', '2026-08-10', 'open', 278.95, 17.71, 296.66, 0.00, 296.66, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(24, 'INV-00024', 24, NULL, 1, '2026-07-24', '2026-08-08', 'paid', 413.60, 26.26, 439.86, 439.86, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(25, 'INV-00025', 25, NULL, 1, '2026-07-22', '2026-08-06', 'partial', 769.95, 48.89, 818.84, 327.54, 491.30, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(26, 'INV-00026', 26, NULL, 1, '2026-07-20', '2026-08-04', 'open', 738.70, 46.91, 785.61, 0.00, 785.61, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(27, 'INV-00027', 27, NULL, 1, '2026-07-18', '2026-08-02', 'partial', 211.20, 13.41, 224.61, 89.84, 134.77, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(28, 'INV-00028', 28, NULL, 1, '2026-07-16', '2026-07-31', 'paid', 1147.15, 72.85, 1220.00, 1220.00, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(29, 'INV-00029', 29, NULL, 1, '2026-07-14', '2026-07-29', 'open', 686.40, 43.59, 729.99, 0.00, 729.99, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(30, 'INV-00030', 30, NULL, 1, '2026-07-12', '2026-07-27', 'paid', 224.20, 14.24, 238.44, 238.44, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(31, 'INV-00031', 31, NULL, 1, '2026-07-10', '2026-07-25', 'partial', 576.20, 36.59, 612.79, 245.12, 367.67, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(32, 'INV-00032', 32, NULL, 1, '2026-07-08', '2026-07-23', 'open', 533.65, 33.89, 567.54, 0.00, 567.54, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(33, 'INV-00033', 33, NULL, 1, '2026-07-06', '2026-07-21', 'partial', 221.35, 14.06, 235.41, 94.16, 141.25, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(34, 'INV-00034', 34, NULL, 1, '2026-07-04', '2026-07-19', 'draft', 355.60, 22.57, 378.17, 0.00, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46', NULL),
(35, 'INV-00035', 35, NULL, 1, '2026-07-02', '2026-07-17', 'paid', 550.85, 34.98, 585.83, 585.83, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:48', NULL),
(36, 'INV-00036', 36, NULL, 1, '2026-06-30', '2026-07-15', 'open', 940.85, 59.75, 1000.60, 0.00, 1000.60, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(37, 'INV-00037', 37, NULL, 1, '2026-06-28', '2026-07-13', 'paid', 381.75, 24.25, 406.00, 406.00, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(38, 'INV-00038', 38, NULL, 1, '2026-06-26', '2026-07-11', 'partial', 897.20, 56.98, 954.18, 381.67, 572.51, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(39, 'INV-00039', 39, NULL, 1, '2026-06-24', '2026-07-09', 'open', 588.70, 37.39, 626.09, 0.00, 626.09, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(40, 'INV-00040', 40, NULL, 1, '2026-06-22', '2026-07-07', 'partial', 246.20, 15.64, 261.84, 104.74, 157.10, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(41, 'INV-00041', 41, NULL, 1, '2026-06-20', '2026-07-05', 'paid', 278.60, 17.70, 296.30, 296.30, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(42, 'INV-00042', 42, NULL, 1, '2026-06-18', '2026-07-03', 'open', 234.20, 14.87, 249.07, 0.00, 249.07, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(43, 'INV-00043', 43, NULL, 1, '2026-06-16', '2026-07-01', 'paid', 361.75, 22.97, 384.72, 384.72, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(44, 'INV-00044', 44, NULL, 1, '2026-06-14', '2026-06-29', 'partial', 258.55, 16.42, 274.97, 109.99, 164.98, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(45, 'INV-00045', 1, NULL, 1, '2026-06-12', '2026-06-27', 'open', 753.55, 47.85, 801.40, 0.00, 801.40, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(46, 'INV-00046', 2, NULL, 1, '2026-06-10', '2026-06-25', 'partial', 202.95, 12.89, 215.84, 86.34, 129.50, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(47, 'INV-00047', 3, NULL, 1, '2026-06-08', '2026-06-23', 'paid', 920.30, 58.44, 978.74, 978.74, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(48, 'INV-00048', 4, NULL, 1, '2026-06-06', '2026-06-21', 'open', 380.60, 24.17, 404.77, 0.00, 404.77, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(49, 'INV-00049', 5, NULL, 1, '2026-06-04', '2026-06-19', 'paid', 731.35, 46.44, 777.79, 777.79, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(50, 'INV-00050', 6, NULL, 1, '2026-06-02', '2026-06-17', 'partial', 446.95, 28.38, 475.33, 190.13, 285.20, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(51, 'INV-00051', 7, NULL, 1, '2026-05-31', '2026-06-15', 'draft', 982.70, 62.41, 1045.11, 0.00, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(52, 'INV-00052', 8, NULL, 1, '2026-05-29', '2026-06-13', 'open', 346.55, 22.01, 368.56, 0.00, 368.56, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(53, 'INV-00053', 9, NULL, 1, '2026-05-27', '2026-06-11', 'partial', 485.35, 30.83, 516.18, 206.47, 309.71, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(54, 'INV-00054', 10, NULL, 1, '2026-05-25', '2026-06-09', 'paid', 354.00, 22.48, 376.48, 376.48, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(55, 'INV-00055', 11, NULL, 1, '2026-05-23', '2026-06-07', 'open', 843.65, 53.56, 897.21, 0.00, 897.21, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(56, 'INV-00056', 12, NULL, 1, '2026-05-21', '2026-06-05', 'paid', 433.85, 27.55, 461.40, 461.40, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(57, 'INV-00057', 13, NULL, 1, '2026-05-19', '2026-06-03', 'partial', 596.10, 37.85, 633.95, 253.58, 380.37, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(58, 'INV-00058', 14, NULL, 1, '2026-05-17', '2026-06-01', 'open', 428.20, 27.19, 455.39, 0.00, 455.39, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(59, 'INV-00059', 15, NULL, 1, '2026-05-15', '2026-05-30', 'partial', 1505.60, 95.61, 1601.21, 640.48, 960.73, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(60, 'INV-00060', 16, NULL, 1, '2026-05-13', '2026-05-28', 'paid', 364.05, 23.12, 387.17, 387.17, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(61, 'INV-00061', 17, NULL, 1, '2026-05-11', '2026-05-26', 'open', 822.00, 52.20, 874.20, 0.00, 874.20, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(62, 'INV-00062', 18, NULL, 1, '2026-05-09', '2026-05-24', 'paid', 578.95, 36.75, 615.70, 615.70, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(63, 'INV-00063', 19, NULL, 1, '2026-05-07', '2026-05-22', 'partial', 570.95, 36.26, 607.21, 242.88, 364.33, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(64, 'INV-00064', 20, NULL, 1, '2026-05-05', '2026-05-20', 'open', 287.45, 18.25, 305.70, 0.00, 305.70, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(65, 'INV-00065', 21, NULL, 1, '2026-05-03', '2026-05-18', 'partial', 605.55, 38.46, 644.01, 257.60, 386.41, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(66, 'INV-00066', 22, NULL, 1, '2026-05-01', '2026-05-16', 'paid', 1007.05, 63.95, 1071.00, 1071.00, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(67, 'INV-00067', 23, NULL, 1, '2026-04-29', '2026-05-14', 'open', 803.70, 51.04, 854.74, 0.00, 854.74, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(68, 'INV-00068', 24, NULL, 1, '2026-04-27', '2026-05-12', 'draft', 244.00, 15.49, 259.49, 0.00, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(69, 'INV-00069', 25, NULL, 1, '2026-04-25', '2026-05-10', 'paid', 583.20, 37.04, 620.24, 620.24, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(70, 'INV-00070', 26, NULL, 1, '2026-04-23', '2026-05-08', 'partial', 714.35, 45.36, 759.71, 303.88, 455.83, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(71, 'INV-00071', 27, NULL, 1, '2026-04-21', '2026-05-06', 'open', 919.85, 58.43, 978.28, 0.00, 978.28, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(72, 'INV-00072', 28, NULL, 1, '2026-04-19', '2026-05-04', 'partial', 661.60, 42.02, 703.62, 281.45, 422.17, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(73, 'INV-00073', 29, NULL, 1, '2026-04-17', '2026-05-02', 'paid', 564.40, 35.84, 600.24, 600.24, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(74, 'INV-00074', 30, NULL, 1, '2026-04-15', '2026-04-30', 'open', 465.60, 29.56, 495.16, 0.00, 495.16, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(75, 'INV-00075', 31, NULL, 1, '2026-04-13', '2026-04-28', 'paid', 782.70, 49.70, 832.40, 832.40, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(76, 'INV-00076', 32, NULL, 1, '2026-04-11', '2026-04-26', 'partial', 429.05, 27.24, 456.29, 182.52, 273.77, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(77, 'INV-00077', 33, NULL, 1, '2026-04-09', '2026-04-24', 'open', 626.50, 39.78, 666.28, 0.00, 666.28, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47', NULL),
(78, 'INV-00078', 34, NULL, 1, '2026-04-07', '2026-04-22', 'partial', 900.45, 57.18, 957.63, 383.05, 574.58, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(79, 'INV-00079', 35, NULL, 1, '2026-04-05', '2026-04-20', 'paid', 207.25, 13.16, 220.41, 220.41, 0.00, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:48', NULL),
(80, 'INV-00080', 36, NULL, 1, '2026-04-03', '2026-04-18', 'open', 1066.50, 67.72, 1134.22, 0.00, 1134.22, NULL, 'Demo invoice', NULL, NULL, 0, 0, 0, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_lines`
--

CREATE TABLE `invoice_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `taxable` tinyint(1) NOT NULL DEFAULT '1',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `line_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_lines`
--

INSERT INTO `invoice_lines` (`id`, `invoice_id`, `item_id`, `description`, `quantity`, `rate`, `amount`, `taxable`, `tax_amount`, `line_order`, `created_at`, `updated_at`) VALUES
(1, 1, 56, 'ALP Drifters Grape 5ct', 1.0000, 47.50, 47.50, 1, 3.02, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(2, 1, 58, 'ALP Drifters Tobacco Classic 5ct', 5.0000, 48.60, 243.00, 1, 15.43, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(3, 2, 5, 'Dark Horse Blueberry 200ct', 5.0000, 54.15, 270.75, 1, 17.19, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(4, 2, 8, 'Dark Horse Wintergreen 200ct', 8.0000, 55.80, 446.40, 1, 28.35, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(5, 2, 14, 'Dark Horse Smooth 200ct', 3.0000, 59.10, 177.30, 1, 11.26, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(6, 2, 114, 'Clipper Acc Peach Ice', 3.0000, 16.40, 49.20, 1, 3.12, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(7, 3, 28, 'Bluntville Tobacco Classic 25ct', 7.0000, 36.10, 252.70, 1, 16.05, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(8, 3, 73, 'VaporX Tobacco Classic', 4.0000, 41.55, 166.20, 1, 10.55, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(9, 3, 120, 'Clipper Acc Bold', 3.0000, 19.70, 59.10, 1, 3.75, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(10, 4, 44, 'Rogue Smooth 5ct', 6.0000, 28.90, 173.40, 1, 11.01, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(11, 4, 87, 'Volt Energy Watermelon 24ct', 7.0000, 34.85, 243.95, 1, 15.49, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(12, 4, 104, 'Crunch Trail Smooth 12ct', 5.0000, 29.65, 148.25, 1, 9.41, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(13, 5, 17, 'Bluntville Original 25ct', 7.0000, 30.05, 210.35, 1, 13.36, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(14, 5, 38, 'Rogue Wintergreen 5ct', 6.0000, 25.60, 153.60, 1, 9.75, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(15, 5, 44, 'Rogue Smooth 5ct', 2.0000, 28.90, 57.80, 1, 3.67, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(16, 5, 52, 'ALP Drifters Honey 5ct', 2.0000, 45.30, 90.60, 1, 5.75, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(17, 5, 97, 'Crunch Trail Honey 12ct', 7.0000, 25.80, 180.60, 1, 11.47, 4, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(18, 6, 65, 'VaporX Blueberry', 6.0000, 37.15, 222.90, 1, 14.15, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(19, 6, 66, 'VaporX Mint', 7.0000, 37.70, 263.90, 1, 16.76, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(20, 7, 35, 'Rogue Blueberry 5ct', 3.0000, 23.95, 71.85, 1, 4.56, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(21, 7, 52, 'ALP Drifters Honey 5ct', 7.0000, 45.30, 317.10, 1, 20.14, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(22, 7, 65, 'VaporX Blueberry', 2.0000, 37.15, 74.30, 1, 4.72, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(23, 7, 75, 'VaporX Bold', 3.0000, 42.65, 127.95, 1, 8.12, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(24, 8, 17, 'Bluntville Original 25ct', 4.0000, 30.05, 120.20, 1, 7.63, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(25, 8, 18, 'Bluntville Vanilla 25ct', 7.0000, 30.60, 214.20, 1, 13.60, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(26, 8, 112, 'Clipper Acc Honey', 1.0000, 15.30, 15.30, 1, 0.97, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(27, 9, 39, 'Rogue Peach Ice 5ct', 6.0000, 26.15, 156.90, 1, 9.96, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(28, 9, 46, 'ALP Drifters Menthol 5ct', 1.0000, 42.00, 42.00, 1, 2.67, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(29, 9, 54, 'ALP Drifters Peach Ice 5ct', 1.0000, 46.40, 46.40, 1, 2.95, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(30, 10, 20, 'Bluntville Blueberry 25ct', 8.0000, 31.70, 253.60, 1, 16.10, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(31, 10, 27, 'Bluntville Watermelon 25ct', 1.0000, 35.55, 35.55, 1, 2.26, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(32, 10, 40, 'Rogue Mango 5ct', 2.0000, 26.70, 53.40, 1, 3.39, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(33, 10, 53, 'ALP Drifters Wintergreen 5ct', 2.0000, 45.85, 91.70, 1, 5.82, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(34, 10, 88, 'Volt Energy Tobacco Classic 24ct', 4.0000, 35.40, 141.60, 1, 8.99, 4, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(35, 11, 28, 'Bluntville Tobacco Classic 25ct', 5.0000, 36.10, 180.50, 1, 11.46, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(36, 11, 44, 'Rogue Smooth 5ct', 6.0000, 28.90, 173.40, 1, 11.01, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(37, 11, 95, 'Crunch Trail Blueberry 12ct', 4.0000, 24.70, 98.80, 1, 6.27, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(38, 12, 3, 'Dark Horse Vanilla 200ct', 7.0000, 53.05, 371.35, 1, 23.58, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(39, 12, 8, 'Dark Horse Wintergreen 200ct', 2.0000, 55.80, 111.60, 1, 7.09, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(40, 12, 49, 'ALP Drifters Cherry 5ct', 6.0000, 43.65, 261.90, 1, 16.63, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(41, 12, 55, 'ALP Drifters Mango 5ct', 4.0000, 46.95, 187.80, 1, 11.93, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(42, 13, 47, 'ALP Drifters Original 5ct', 2.0000, 42.55, 85.10, 1, 5.40, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(43, 13, 77, 'Volt Energy Original 24ct', 2.0000, 29.35, 58.70, 1, 3.73, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(44, 13, 107, 'Clipper Acc Original', 7.0000, 12.55, 87.85, 1, 5.58, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(45, 14, 68, 'VaporX Wintergreen', 7.0000, 38.80, 271.60, 1, 17.25, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(46, 14, 82, 'Volt Energy Honey 24ct', 2.0000, 32.10, 64.20, 1, 4.08, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(47, 14, 92, 'Crunch Trail Original 12ct', 3.0000, 23.05, 69.15, 1, 4.39, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(48, 14, 96, 'Crunch Trail Mint 12ct', 3.0000, 25.25, 75.75, 1, 4.81, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(49, 14, 109, 'Clipper Acc Cherry', 5.0000, 13.65, 68.25, 1, 4.33, 4, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(50, 15, 9, 'Dark Horse Peach Ice 200ct', 3.0000, 56.35, 169.05, 1, 10.73, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(51, 15, 58, 'ALP Drifters Tobacco Classic 5ct', 5.0000, 48.60, 243.00, 1, 15.43, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(52, 15, 69, 'VaporX Peach Ice', 8.0000, 39.35, 314.80, 1, 19.99, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(53, 15, 70, 'VaporX Mango', 8.0000, 39.90, 319.20, 1, 20.27, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(54, 15, 71, 'VaporX Grape', 6.0000, 40.45, 242.70, 1, 15.41, 4, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(55, 16, 3, 'Dark Horse Vanilla 200ct', 1.0000, 53.05, 53.05, 1, 3.37, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(56, 16, 54, 'ALP Drifters Peach Ice 5ct', 8.0000, 46.40, 371.20, 1, 23.57, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(57, 16, 60, 'ALP Drifters Bold 5ct', 5.0000, 49.70, 248.50, 1, 15.78, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(58, 17, 68, 'VaporX Wintergreen', 6.0000, 38.80, 232.80, 1, 14.78, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(59, 17, 93, 'Crunch Trail Vanilla 12ct', 2.0000, 23.60, 47.20, 1, 3.00, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(60, 17, 97, 'Crunch Trail Honey 12ct', 6.0000, 25.80, 154.80, 1, 9.83, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(61, 18, 27, 'Bluntville Watermelon 25ct', 4.0000, 35.55, 142.20, 1, 9.03, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(62, 18, 36, 'Rogue Mint 5ct', 1.0000, 24.50, 24.50, 1, 1.56, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(63, 18, 37, 'Rogue Honey 5ct', 2.0000, 25.05, 50.10, 1, 3.18, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(64, 18, 64, 'VaporX Cherry', 5.0000, 36.60, 183.00, 1, 11.62, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(65, 19, 29, 'Bluntville Smooth 25ct', 7.0000, 36.65, 256.55, 1, 16.29, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(66, 19, 53, 'ALP Drifters Wintergreen 5ct', 3.0000, 45.85, 137.55, 1, 8.73, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(67, 19, 55, 'ALP Drifters Mango 5ct', 2.0000, 46.95, 93.90, 1, 5.96, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(68, 20, 23, 'Bluntville Wintergreen 25ct', 6.0000, 33.35, 200.10, 1, 12.71, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(69, 20, 37, 'Rogue Honey 5ct', 7.0000, 25.05, 175.35, 1, 11.13, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(70, 20, 67, 'VaporX Honey', 4.0000, 38.25, 153.00, 1, 9.72, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(71, 20, 85, 'Volt Energy Mango 24ct', 3.0000, 33.75, 101.25, 1, 6.43, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(72, 21, 21, 'Bluntville Mint 25ct', 5.0000, 32.25, 161.25, 1, 10.24, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(73, 21, 22, 'Bluntville Honey 25ct', 8.0000, 32.80, 262.40, 1, 16.66, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(74, 21, 46, 'ALP Drifters Menthol 5ct', 6.0000, 42.00, 252.00, 1, 16.00, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(75, 21, 50, 'ALP Drifters Blueberry 5ct', 8.0000, 44.20, 353.60, 1, 22.45, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(76, 22, 15, 'Dark Horse Bold 200ct', 2.0000, 59.65, 119.30, 1, 7.58, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(77, 22, 52, 'ALP Drifters Honey 5ct', 6.0000, 45.30, 271.80, 1, 17.26, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(78, 22, 117, 'Clipper Acc Watermelon', 8.0000, 18.05, 144.40, 1, 9.17, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(79, 23, 59, 'ALP Drifters Smooth 5ct', 4.0000, 49.15, 196.60, 1, 12.48, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(80, 23, 100, 'Crunch Trail Mango 12ct', 3.0000, 27.45, 82.35, 1, 5.23, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(81, 24, 11, 'Dark Horse Grape 200ct', 5.0000, 57.45, 287.25, 1, 18.24, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(82, 24, 35, 'Rogue Blueberry 5ct', 1.0000, 23.95, 23.95, 1, 1.52, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(83, 24, 38, 'Rogue Wintergreen 5ct', 4.0000, 25.60, 102.40, 1, 6.50, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(84, 25, 7, 'Dark Horse Honey 200ct', 1.0000, 55.25, 55.25, 1, 3.51, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(85, 25, 10, 'Dark Horse Mango 200ct', 6.0000, 56.90, 341.40, 1, 21.68, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(86, 25, 72, 'VaporX Watermelon', 4.0000, 41.00, 164.00, 1, 10.41, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(87, 25, 102, 'Crunch Trail Watermelon 12ct', 4.0000, 28.55, 114.20, 1, 7.25, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(88, 25, 113, 'Clipper Acc Wintergreen', 6.0000, 15.85, 95.10, 1, 6.04, 4, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(89, 26, 9, 'Dark Horse Peach Ice 200ct', 7.0000, 56.35, 394.45, 1, 25.05, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(90, 26, 50, 'ALP Drifters Blueberry 5ct', 3.0000, 44.20, 132.60, 1, 8.42, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(91, 26, 115, 'Clipper Acc Mango', 7.0000, 16.95, 118.65, 1, 7.53, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(92, 26, 118, 'Clipper Acc Tobacco Classic', 5.0000, 18.60, 93.00, 1, 5.91, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(93, 27, 72, 'VaporX Watermelon', 4.0000, 41.00, 164.00, 1, 10.41, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(94, 27, 93, 'Crunch Trail Vanilla 12ct', 2.0000, 23.60, 47.20, 1, 3.00, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(95, 28, 10, 'Dark Horse Mango 200ct', 7.0000, 56.90, 398.30, 1, 25.29, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(96, 28, 44, 'Rogue Smooth 5ct', 5.0000, 28.90, 144.50, 1, 9.18, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(97, 28, 48, 'ALP Drifters Vanilla 5ct', 4.0000, 43.10, 172.40, 1, 10.95, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(98, 28, 52, 'ALP Drifters Honey 5ct', 5.0000, 45.30, 226.50, 1, 14.38, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(99, 28, 77, 'Volt Energy Original 24ct', 7.0000, 29.35, 205.45, 1, 13.05, 4, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(100, 29, 15, 'Dark Horse Bold 200ct', 7.0000, 59.65, 417.55, 1, 26.51, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(101, 29, 23, 'Bluntville Wintergreen 25ct', 1.0000, 33.35, 33.35, 1, 2.12, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(102, 29, 30, 'Bluntville Bold 25ct', 4.0000, 37.20, 148.80, 1, 9.45, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(103, 29, 44, 'Rogue Smooth 5ct', 3.0000, 28.90, 86.70, 1, 5.51, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(104, 30, 46, 'ALP Drifters Menthol 5ct', 5.0000, 42.00, 210.00, 1, 13.34, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(105, 30, 110, 'Clipper Acc Blueberry', 1.0000, 14.20, 14.20, 1, 0.90, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(106, 31, 71, 'VaporX Grape', 7.0000, 40.45, 283.15, 1, 17.98, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(107, 31, 73, 'VaporX Tobacco Classic', 3.0000, 41.55, 124.65, 1, 7.92, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(108, 31, 74, 'VaporX Smooth', 4.0000, 42.10, 168.40, 1, 10.69, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(109, 32, 60, 'ALP Drifters Bold 5ct', 8.0000, 49.70, 397.60, 1, 25.25, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(110, 32, 77, 'Volt Energy Original 24ct', 3.0000, 29.35, 88.05, 1, 5.59, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(111, 32, 106, 'Clipper Acc Menthol', 4.0000, 12.00, 48.00, 1, 3.05, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(112, 33, 57, 'ALP Drifters Watermelon 5ct', 1.0000, 48.05, 48.05, 1, 3.05, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(113, 33, 74, 'VaporX Smooth', 1.0000, 42.10, 42.10, 1, 2.67, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(114, 33, 88, 'Volt Energy Tobacco Classic 24ct', 2.0000, 35.40, 70.80, 1, 4.50, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(115, 33, 105, 'Crunch Trail Bold 12ct', 2.0000, 30.20, 60.40, 1, 3.84, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(116, 34, 16, 'Bluntville Menthol 25ct', 1.0000, 29.50, 29.50, 1, 1.87, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(117, 34, 103, 'Crunch Trail Tobacco Classic 12ct', 7.0000, 29.10, 203.70, 1, 12.93, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(118, 34, 112, 'Clipper Acc Honey', 8.0000, 15.30, 122.40, 1, 7.77, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(119, 35, 6, 'Dark Horse Mint 200ct', 1.0000, 54.70, 54.70, 1, 3.47, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(120, 35, 61, 'VaporX Menthol', 7.0000, 34.95, 244.65, 1, 15.54, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(121, 35, 95, 'Crunch Trail Blueberry 12ct', 7.0000, 24.70, 172.90, 1, 10.98, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(122, 35, 108, 'Clipper Acc Vanilla', 6.0000, 13.10, 78.60, 1, 4.99, 3, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(123, 36, 65, 'VaporX Blueberry', 7.0000, 37.15, 260.05, 1, 16.51, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(124, 36, 70, 'VaporX Mango', 7.0000, 39.90, 279.30, 1, 17.74, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(125, 36, 72, 'VaporX Watermelon', 5.0000, 41.00, 205.00, 1, 13.02, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(126, 36, 90, 'Volt Energy Bold 24ct', 4.0000, 36.50, 146.00, 1, 9.27, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(127, 36, 96, 'Crunch Trail Mint 12ct', 2.0000, 25.25, 50.50, 1, 3.21, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(128, 37, 33, 'Rogue Vanilla 5ct', 7.0000, 22.85, 159.95, 1, 10.16, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(129, 37, 99, 'Crunch Trail Peach Ice 12ct', 6.0000, 26.90, 161.40, 1, 10.25, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(130, 37, 105, 'Crunch Trail Bold 12ct', 2.0000, 30.20, 60.40, 1, 3.84, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(131, 38, 10, 'Dark Horse Mango 200ct', 7.0000, 56.90, 398.30, 1, 25.29, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(132, 38, 20, 'Bluntville Blueberry 25ct', 6.0000, 31.70, 190.20, 1, 12.08, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(133, 38, 72, 'VaporX Watermelon', 5.0000, 41.00, 205.00, 1, 13.02, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(134, 38, 104, 'Crunch Trail Smooth 12ct', 3.0000, 29.65, 88.95, 1, 5.65, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(135, 38, 111, 'Clipper Acc Mint', 1.0000, 14.75, 14.75, 1, 0.94, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(136, 39, 60, 'ALP Drifters Bold 5ct', 6.0000, 49.70, 298.20, 1, 18.94, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(137, 39, 63, 'VaporX Vanilla', 2.0000, 36.05, 72.10, 1, 4.58, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(138, 39, 80, 'Volt Energy Blueberry 24ct', 5.0000, 31.00, 155.00, 1, 9.84, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(139, 39, 113, 'Clipper Acc Wintergreen', 4.0000, 15.85, 63.40, 1, 4.03, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(140, 40, 24, 'Bluntville Peach Ice 25ct', 3.0000, 33.90, 101.70, 1, 6.46, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(141, 40, 37, 'Rogue Honey 5ct', 2.0000, 25.05, 50.10, 1, 3.18, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(142, 40, 84, 'Volt Energy Peach Ice 24ct', 1.0000, 33.20, 33.20, 1, 2.11, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(143, 40, 112, 'Clipper Acc Honey', 4.0000, 15.30, 61.20, 1, 3.89, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(144, 41, 2, 'Dark Horse Original 200ct', 4.0000, 52.50, 210.00, 1, 13.34, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(145, 41, 86, 'Volt Energy Grape 24ct', 2.0000, 34.30, 68.60, 1, 4.36, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(146, 42, 19, 'Bluntville Cherry 25ct', 4.0000, 31.15, 124.60, 1, 7.91, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(147, 42, 36, 'Rogue Mint 5ct', 3.0000, 24.50, 73.50, 1, 4.67, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(148, 42, 117, 'Clipper Acc Watermelon', 2.0000, 18.05, 36.10, 1, 2.29, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(149, 43, 23, 'Bluntville Wintergreen 25ct', 8.0000, 33.35, 266.80, 1, 16.94, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(150, 43, 45, 'Rogue Bold 5ct', 1.0000, 29.45, 29.45, 1, 1.87, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(151, 43, 108, 'Clipper Acc Vanilla', 5.0000, 13.10, 65.50, 1, 4.16, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(152, 44, 45, 'Rogue Bold 5ct', 3.0000, 29.45, 88.35, 1, 5.61, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(153, 44, 47, 'ALP Drifters Original 5ct', 4.0000, 42.55, 170.20, 1, 10.81, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(154, 45, 17, 'Bluntville Original 25ct', 4.0000, 30.05, 120.20, 1, 7.63, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(155, 45, 41, 'Rogue Grape 5ct', 5.0000, 27.25, 136.25, 1, 8.65, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(156, 45, 49, 'ALP Drifters Cherry 5ct', 6.0000, 43.65, 261.90, 1, 16.63, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(157, 45, 78, 'Volt Energy Vanilla 24ct', 3.0000, 29.90, 89.70, 1, 5.70, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(158, 45, 103, 'Crunch Trail Tobacco Classic 12ct', 5.0000, 29.10, 145.50, 1, 9.24, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(159, 46, 5, 'Dark Horse Blueberry 200ct', 1.0000, 54.15, 54.15, 1, 3.44, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(160, 46, 90, 'Volt Energy Bold 24ct', 3.0000, 36.50, 109.50, 1, 6.95, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(161, 46, 108, 'Clipper Acc Vanilla', 3.0000, 13.10, 39.30, 1, 2.50, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(162, 47, 70, 'VaporX Mango', 8.0000, 39.90, 319.20, 1, 20.27, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(163, 47, 80, 'Volt Energy Blueberry 24ct', 6.0000, 31.00, 186.00, 1, 11.81, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(164, 47, 94, 'Crunch Trail Cherry 12ct', 4.0000, 24.15, 96.60, 1, 6.13, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(165, 47, 99, 'Crunch Trail Peach Ice 12ct', 7.0000, 26.90, 188.30, 1, 11.96, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(166, 47, 118, 'Clipper Acc Tobacco Classic', 7.0000, 18.60, 130.20, 1, 8.27, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(167, 48, 13, 'Dark Horse Tobacco Classic 200ct', 5.0000, 58.55, 292.75, 1, 18.59, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(168, 48, 107, 'Clipper Acc Original', 7.0000, 12.55, 87.85, 1, 5.58, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(169, 49, 23, 'Bluntville Wintergreen 25ct', 7.0000, 33.35, 233.45, 1, 14.82, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(170, 49, 25, 'Bluntville Mango 25ct', 1.0000, 34.45, 34.45, 1, 2.19, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(171, 49, 45, 'Rogue Bold 5ct', 5.0000, 29.45, 147.25, 1, 9.35, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(172, 49, 50, 'ALP Drifters Blueberry 5ct', 4.0000, 44.20, 176.80, 1, 11.23, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(173, 49, 87, 'Volt Energy Watermelon 24ct', 4.0000, 34.85, 139.40, 1, 8.85, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(174, 50, 11, 'Dark Horse Grape 200ct', 5.0000, 57.45, 287.25, 1, 18.24, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(175, 50, 34, 'Rogue Cherry 5ct', 3.0000, 23.40, 70.20, 1, 4.46, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(176, 50, 106, 'Clipper Acc Menthol', 5.0000, 12.00, 60.00, 1, 3.81, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(177, 50, 111, 'Clipper Acc Mint', 2.0000, 14.75, 29.50, 1, 1.87, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(178, 51, 6, 'Dark Horse Mint 200ct', 8.0000, 54.70, 437.60, 1, 27.79, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(179, 51, 8, 'Dark Horse Wintergreen 200ct', 2.0000, 55.80, 111.60, 1, 7.09, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(180, 51, 33, 'Rogue Vanilla 5ct', 4.0000, 22.85, 91.40, 1, 5.80, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(181, 51, 57, 'ALP Drifters Watermelon 5ct', 6.0000, 48.05, 288.30, 1, 18.31, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(182, 51, 99, 'Crunch Trail Peach Ice 12ct', 2.0000, 26.90, 53.80, 1, 3.42, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(183, 52, 11, 'Dark Horse Grape 200ct', 5.0000, 57.45, 287.25, 1, 18.24, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(184, 52, 104, 'Crunch Trail Smooth 12ct', 2.0000, 29.65, 59.30, 1, 3.77, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(185, 53, 21, 'Bluntville Mint 25ct', 3.0000, 32.25, 96.75, 1, 6.14, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(186, 53, 24, 'Bluntville Peach Ice 25ct', 3.0000, 33.90, 101.70, 1, 6.46, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(187, 53, 44, 'Rogue Smooth 5ct', 3.0000, 28.90, 86.70, 1, 5.51, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(188, 53, 68, 'VaporX Wintergreen', 4.0000, 38.80, 155.20, 1, 9.86, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(189, 53, 91, 'Crunch Trail Menthol 12ct', 2.0000, 22.50, 45.00, 1, 2.86, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(190, 54, 43, 'Rogue Tobacco Classic 5ct', 6.0000, 28.35, 170.10, 1, 10.80, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(191, 54, 65, 'VaporX Blueberry', 1.0000, 37.15, 37.15, 1, 2.36, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(192, 54, 68, 'VaporX Wintergreen', 2.0000, 38.80, 77.60, 1, 4.93, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(193, 54, 92, 'Crunch Trail Original 12ct', 3.0000, 23.05, 69.15, 1, 4.39, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(194, 55, 47, 'ALP Drifters Original 5ct', 7.0000, 42.55, 297.85, 1, 18.91, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(195, 55, 66, 'VaporX Mint', 3.0000, 37.70, 113.10, 1, 7.18, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(196, 55, 87, 'Volt Energy Watermelon 24ct', 7.0000, 34.85, 243.95, 1, 15.49, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(197, 55, 96, 'Crunch Trail Mint 12ct', 7.0000, 25.25, 176.75, 1, 11.22, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(198, 55, 106, 'Clipper Acc Menthol', 1.0000, 12.00, 12.00, 1, 0.76, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(199, 56, 35, 'Rogue Blueberry 5ct', 4.0000, 23.95, 95.80, 1, 6.08, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(200, 56, 58, 'ALP Drifters Tobacco Classic 5ct', 5.0000, 48.60, 243.00, 1, 15.43, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(201, 56, 63, 'VaporX Vanilla', 1.0000, 36.05, 36.05, 1, 2.29, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(202, 56, 86, 'Volt Energy Grape 24ct', 1.0000, 34.30, 34.30, 1, 2.18, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(203, 56, 95, 'Crunch Trail Blueberry 12ct', 1.0000, 24.70, 24.70, 1, 1.57, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(204, 57, 23, 'Bluntville Wintergreen 25ct', 7.0000, 33.35, 233.45, 1, 14.82, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(205, 57, 60, 'ALP Drifters Bold 5ct', 1.0000, 49.70, 49.70, 1, 3.16, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(206, 57, 96, 'Crunch Trail Mint 12ct', 3.0000, 25.25, 75.75, 1, 4.81, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(207, 57, 104, 'Crunch Trail Smooth 12ct', 8.0000, 29.65, 237.20, 1, 15.06, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(208, 58, 22, 'Bluntville Honey 25ct', 2.0000, 32.80, 65.60, 1, 4.17, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(209, 58, 23, 'Bluntville Wintergreen 25ct', 3.0000, 33.35, 100.05, 1, 6.35, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(210, 58, 73, 'VaporX Tobacco Classic', 1.0000, 41.55, 41.55, 1, 2.64, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(211, 58, 80, 'Volt Energy Blueberry 24ct', 6.0000, 31.00, 186.00, 1, 11.81, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(212, 58, 116, 'Clipper Acc Grape', 2.0000, 17.50, 35.00, 1, 2.22, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(213, 59, 3, 'Dark Horse Vanilla 200ct', 8.0000, 53.05, 424.40, 1, 26.95, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(214, 59, 54, 'ALP Drifters Peach Ice 5ct', 8.0000, 46.40, 371.20, 1, 23.57, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(215, 59, 59, 'ALP Drifters Smooth 5ct', 6.0000, 49.15, 294.90, 1, 18.73, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(216, 59, 60, 'ALP Drifters Bold 5ct', 8.0000, 49.70, 397.60, 1, 25.25, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(217, 59, 116, 'Clipper Acc Grape', 1.0000, 17.50, 17.50, 1, 1.11, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(218, 60, 57, 'ALP Drifters Watermelon 5ct', 6.0000, 48.05, 288.30, 1, 18.31, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(219, 60, 96, 'Crunch Trail Mint 12ct', 3.0000, 25.25, 75.75, 1, 4.81, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(220, 61, 10, 'Dark Horse Mango 200ct', 8.0000, 56.90, 455.20, 1, 28.91, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(221, 61, 53, 'ALP Drifters Wintergreen 5ct', 8.0000, 45.85, 366.80, 1, 23.29, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(222, 62, 2, 'Dark Horse Original 200ct', 1.0000, 52.50, 52.50, 1, 3.33, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(223, 62, 28, 'Bluntville Tobacco Classic 25ct', 2.0000, 36.10, 72.20, 1, 4.58, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(224, 62, 70, 'VaporX Mango', 1.0000, 39.90, 39.90, 1, 2.53, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(225, 62, 86, 'Volt Energy Grape 24ct', 7.0000, 34.30, 240.10, 1, 15.25, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(226, 62, 87, 'Volt Energy Watermelon 24ct', 5.0000, 34.85, 174.25, 1, 11.06, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(227, 63, 5, 'Dark Horse Blueberry 200ct', 2.0000, 54.15, 108.30, 1, 6.88, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(228, 63, 19, 'Bluntville Cherry 25ct', 3.0000, 31.15, 93.45, 1, 5.93, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(229, 63, 42, 'Rogue Watermelon 5ct', 7.0000, 27.80, 194.60, 1, 12.36, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(230, 63, 103, 'Crunch Trail Tobacco Classic 12ct', 6.0000, 29.10, 174.60, 1, 11.09, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(231, 64, 45, 'Rogue Bold 5ct', 2.0000, 29.45, 58.90, 1, 3.74, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(232, 64, 83, 'Volt Energy Wintergreen 24ct', 7.0000, 32.65, 228.55, 1, 14.51, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(233, 65, 16, 'Bluntville Menthol 25ct', 2.0000, 29.50, 59.00, 1, 3.75, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(234, 65, 24, 'Bluntville Peach Ice 25ct', 3.0000, 33.90, 101.70, 1, 6.46, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(235, 65, 31, 'Rogue Menthol 5ct', 1.0000, 21.75, 21.75, 1, 1.38, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(236, 65, 77, 'Volt Energy Original 24ct', 8.0000, 29.35, 234.80, 1, 14.91, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(237, 65, 99, 'Crunch Trail Peach Ice 12ct', 7.0000, 26.90, 188.30, 1, 11.96, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(238, 66, 18, 'Bluntville Vanilla 25ct', 6.0000, 30.60, 183.60, 1, 11.66, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(239, 66, 40, 'Rogue Mango 5ct', 3.0000, 26.70, 80.10, 1, 5.09, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(240, 66, 49, 'ALP Drifters Cherry 5ct', 7.0000, 43.65, 305.55, 1, 19.40, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(241, 66, 58, 'ALP Drifters Tobacco Classic 5ct', 3.0000, 48.60, 145.80, 1, 9.26, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(242, 66, 90, 'Volt Energy Bold 24ct', 8.0000, 36.50, 292.00, 1, 18.54, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(243, 67, 3, 'Dark Horse Vanilla 200ct', 7.0000, 53.05, 371.35, 1, 23.58, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(244, 67, 31, 'Rogue Menthol 5ct', 1.0000, 21.75, 21.75, 1, 1.38, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(245, 67, 62, 'VaporX Original', 6.0000, 35.50, 213.00, 1, 13.53, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(246, 67, 95, 'Crunch Trail Blueberry 12ct', 8.0000, 24.70, 197.60, 1, 12.55, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(247, 68, 103, 'Crunch Trail Tobacco Classic 12ct', 5.0000, 29.10, 145.50, 1, 9.24, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(248, 68, 120, 'Clipper Acc Bold', 5.0000, 19.70, 98.50, 1, 6.25, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(249, 69, 2, 'Dark Horse Original 200ct', 4.0000, 52.50, 210.00, 1, 13.34, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(250, 69, 81, 'Volt Energy Mint 24ct', 3.0000, 31.55, 94.65, 1, 6.01, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(251, 69, 104, 'Crunch Trail Smooth 12ct', 7.0000, 29.65, 207.55, 1, 13.18, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(252, 69, 110, 'Clipper Acc Blueberry', 5.0000, 14.20, 71.00, 1, 4.51, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(253, 70, 3, 'Dark Horse Vanilla 200ct', 5.0000, 53.05, 265.25, 1, 16.84, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(254, 70, 33, 'Rogue Vanilla 5ct', 8.0000, 22.85, 182.80, 1, 11.61, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(255, 70, 66, 'VaporX Mint', 3.0000, 37.70, 113.10, 1, 7.18, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(256, 70, 119, 'Clipper Acc Smooth', 8.0000, 19.15, 153.20, 1, 9.73, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(257, 71, 44, 'Rogue Smooth 5ct', 3.0000, 28.90, 86.70, 1, 5.51, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(258, 71, 46, 'ALP Drifters Menthol 5ct', 5.0000, 42.00, 210.00, 1, 13.34, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(259, 71, 79, 'Volt Energy Cherry 24ct', 7.0000, 30.45, 213.15, 1, 13.54, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(260, 71, 84, 'Volt Energy Peach Ice 24ct', 6.0000, 33.20, 199.20, 1, 12.65, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(261, 71, 98, 'Crunch Trail Wintergreen 12ct', 8.0000, 26.35, 210.80, 1, 13.39, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(262, 72, 22, 'Bluntville Honey 25ct', 7.0000, 32.80, 229.60, 1, 14.58, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(263, 72, 25, 'Bluntville Mango 25ct', 5.0000, 34.45, 172.25, 1, 10.94, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(264, 72, 27, 'Bluntville Watermelon 25ct', 5.0000, 35.55, 177.75, 1, 11.29, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(265, 72, 72, 'VaporX Watermelon', 2.0000, 41.00, 82.00, 1, 5.21, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(266, 73, 2, 'Dark Horse Original 200ct', 8.0000, 52.50, 420.00, 1, 26.67, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(267, 73, 117, 'Clipper Acc Watermelon', 8.0000, 18.05, 144.40, 1, 9.17, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(268, 74, 48, 'ALP Drifters Vanilla 5ct', 6.0000, 43.10, 258.60, 1, 16.42, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(269, 74, 60, 'ALP Drifters Bold 5ct', 2.0000, 49.70, 99.40, 1, 6.31, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(270, 74, 99, 'Crunch Trail Peach Ice 12ct', 4.0000, 26.90, 107.60, 1, 6.83, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(271, 75, 5, 'Dark Horse Blueberry 200ct', 4.0000, 54.15, 216.60, 1, 13.75, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(272, 75, 31, 'Rogue Menthol 5ct', 7.0000, 21.75, 152.25, 1, 9.67, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(273, 75, 60, 'ALP Drifters Bold 5ct', 5.0000, 49.70, 248.50, 1, 15.78, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(274, 75, 77, 'Volt Energy Original 24ct', 5.0000, 29.35, 146.75, 1, 9.32, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(275, 75, 118, 'Clipper Acc Tobacco Classic', 1.0000, 18.60, 18.60, 1, 1.18, 4, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(276, 76, 25, 'Bluntville Mango 25ct', 4.0000, 34.45, 137.80, 1, 8.75, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(277, 76, 29, 'Bluntville Smooth 25ct', 4.0000, 36.65, 146.60, 1, 9.31, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(278, 76, 57, 'ALP Drifters Watermelon 5ct', 1.0000, 48.05, 48.05, 1, 3.05, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(279, 76, 94, 'Crunch Trail Cherry 12ct', 4.0000, 24.15, 96.60, 1, 6.13, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(280, 77, 53, 'ALP Drifters Wintergreen 5ct', 1.0000, 45.85, 45.85, 1, 2.91, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(281, 77, 63, 'VaporX Vanilla', 8.0000, 36.05, 288.40, 1, 18.31, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(282, 77, 77, 'Volt Energy Original 24ct', 5.0000, 29.35, 146.75, 1, 9.32, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(283, 77, 103, 'Crunch Trail Tobacco Classic 12ct', 5.0000, 29.10, 145.50, 1, 9.24, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(284, 78, 6, 'Dark Horse Mint 200ct', 6.0000, 54.70, 328.20, 1, 20.84, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(285, 78, 45, 'Rogue Bold 5ct', 5.0000, 29.45, 147.25, 1, 9.35, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(286, 78, 53, 'ALP Drifters Wintergreen 5ct', 4.0000, 45.85, 183.40, 1, 11.65, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(287, 78, 105, 'Crunch Trail Bold 12ct', 8.0000, 30.20, 241.60, 1, 15.34, 3, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(288, 79, 5, 'Dark Horse Blueberry 200ct', 1.0000, 54.15, 54.15, 1, 3.44, 0, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(289, 79, 96, 'Crunch Trail Mint 12ct', 2.0000, 25.25, 50.50, 1, 3.21, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(290, 79, 107, 'Clipper Acc Original', 6.0000, 12.55, 75.30, 1, 4.78, 2, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(291, 79, 109, 'Clipper Acc Cherry', 2.0000, 13.65, 27.30, 1, 1.73, 3, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(292, 80, 14, 'Dark Horse Smooth 200ct', 6.0000, 59.10, 354.60, 1, 22.52, 0, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(293, 80, 67, 'VaporX Honey', 5.0000, 38.25, 191.25, 1, 12.14, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(294, 80, 81, 'Volt Energy Mint 24ct', 7.0000, 31.55, 220.85, 1, 14.02, 2, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(295, 80, 103, 'Crunch Trail Tobacco Classic 12ct', 2.0000, 29.10, 58.20, 1, 3.70, 3, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(296, 80, 105, 'Crunch Trail Bold 12ct', 8.0000, 30.20, 241.60, 1, 15.34, 4, '2026-09-10 11:00:48', '2026-09-10 11:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inventory_part',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `manufacturer_part_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_of_measure_id` bigint UNSIGNED DEFAULT NULL,
  `purchase_description` text COLLATE utf8mb4_unicode_ci,
  `purchase_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cogs_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_vendor_id` bigint UNSIGNED DEFAULT NULL,
  `sales_description` text COLLATE utf8mb4_unicode_ci,
  `sales_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_code_id` bigint UNSIGNED DEFAULT NULL,
  `income_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reorder_min` decimal(15,4) DEFAULT NULL,
  `reorder_max` decimal(15,4) DEFAULT NULL,
  `on_hand` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `average_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `on_po_qty` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `on_so_qty` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `item_category_id` bigint UNSIGNED DEFAULT NULL,
  `item_type_id` bigint UNSIGNED DEFAULT NULL,
  `items_per_container` decimal(15,4) DEFAULT NULL,
  `promotion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `sku`, `barcode`, `name`, `type`, `parent_id`, `manufacturer_part_number`, `unit_of_measure_id`, `purchase_description`, `purchase_cost`, `cogs_account`, `preferred_vendor_id`, `sales_description`, `sales_price`, `tax_code_id`, `income_account`, `asset_account`, `reorder_min`, `reorder_max`, `on_hand`, `average_cost`, `on_po_qty`, `on_so_qty`, `item_category_id`, `item_type_id`, `items_per_container`, `promotion`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '8100000000001', '8100000000001', 'Dark Horse Menthol 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Menthol 200ct', 38.00, 'Cost of Goods Sold', 1, 'Dark Horse Menthol 200ct', 51.95, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 0.0000, 38.0000, 0.0000, 0.0000, 1, 1, 200.0000, '2 For $46.76', 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40', NULL),
(2, '8100000000002', '8100000000002', 'Dark Horse Original 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Original 200ct', 38.35, 'Cost of Goods Sold', 2, 'Dark Horse Original 200ct', 52.50, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 284.0000, 38.3500, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:40', '2026-09-17 08:09:55', NULL),
(3, '8100000000003', '8100000000003', 'Dark Horse Vanilla 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Vanilla 200ct', 38.70, 'Cost of Goods Sold', 3, 'Dark Horse Vanilla 200ct', 53.05, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 308.0000, 38.7000, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:47', NULL),
(4, '8100000000004', '8100000000004', 'Dark Horse Cherry 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Cherry 200ct', 39.05, 'Cost of Goods Sold', 4, 'Dark Horse Cherry 200ct', 53.60, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 151.0000, 39.0500, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:44', NULL),
(5, '8100000000005', '8100000000005', 'Dark Horse Blueberry 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Blueberry 200ct', 39.40, 'Cost of Goods Sold', 5, 'Dark Horse Blueberry 200ct', 54.15, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 92.0000, 39.4000, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 15:09:36', NULL),
(6, '8100000000006', '8100000000006', 'Dark Horse Mint 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Mint 200ct', 39.75, 'Cost of Goods Sold', 6, 'Dark Horse Mint 200ct', 54.70, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 354.0000, 39.7500, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(7, '8100000000007', '8100000000007', 'Dark Horse Honey 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Honey 200ct', 40.10, 'Cost of Goods Sold', 7, 'Dark Horse Honey 200ct', 55.25, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 217.0000, 40.1000, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:46', NULL),
(8, '8100000000008', '8100000000008', 'Dark Horse Wintergreen 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Wintergreen 200ct', 40.45, 'Cost of Goods Sold', 8, 'Dark Horse Wintergreen 200ct', 55.80, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 407.0000, 40.4500, 0.0000, 0.0000, 1, 1, 200.0000, '2 For $50.22', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:46', NULL),
(9, '8100000000009', '8100000000009', 'Dark Horse Peach Ice 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Peach Ice 200ct', 40.80, 'Cost of Goods Sold', 9, 'Dark Horse Peach Ice 200ct', 56.35, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 186.0000, 40.8000, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 15:09:23', NULL),
(10, '8100000000010', '8100000000010', 'Dark Horse Mango 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Mango 200ct', 41.15, 'Cost of Goods Sold', 10, 'Dark Horse Mango 200ct', 56.90, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 239.5000, 41.1500, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(11, '8100000000011', '8100000000011', 'Dark Horse Grape 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Grape 200ct', 41.50, 'Cost of Goods Sold', 11, 'Dark Horse Grape 200ct', 57.45, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 232.0000, 41.5000, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(12, '8100000000012', '8100000000012', 'Dark Horse Watermelon 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Watermelon 200ct', 41.85, 'Cost of Goods Sold', 12, 'Dark Horse Watermelon 200ct', 58.00, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 243.0000, 41.8500, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:43', NULL),
(13, '8100000000013', '8100000000013', 'Dark Horse Tobacco Classic 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Tobacco Classic 200ct', 42.20, 'Cost of Goods Sold', 13, 'Dark Horse Tobacco Classic 200ct', 58.55, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 410.5000, 42.2000, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(14, '8100000000014', '8100000000014', 'Dark Horse Smooth 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Smooth 200ct', 42.55, 'Cost of Goods Sold', 14, 'Dark Horse Smooth 200ct', 59.10, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 184.0000, 42.5500, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:48', NULL),
(15, '8100000000015', '8100000000015', 'Dark Horse Bold 200ct', 'inventory_part', NULL, NULL, 5, 'Dark Horse Bold 200ct', 42.90, 'Cost of Goods Sold', 1, 'Dark Horse Bold 200ct', 59.65, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 194.0000, 42.9000, 0.0000, 0.0000, 1, 1, 200.0000, '2 For $53.69', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:46', NULL),
(16, '8100000000016', '8100000000016', 'Bluntville Menthol 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Menthol 25ct', 18.00, 'Cost of Goods Sold', 2, 'Bluntville Menthol 25ct', 29.50, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 297.0000, 18.0000, 0.0000, 0.0000, 1, 1, 25.0000, '2 For $26.55', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(17, '8100000000017', '8100000000017', 'Bluntville Original 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Original 25ct', 18.35, 'Cost of Goods Sold', 3, 'Bluntville Original 25ct', 30.05, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 178.0000, 18.3500, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(18, '8100000000018', '8100000000018', 'Bluntville Vanilla 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Vanilla 25ct', 18.70, 'Cost of Goods Sold', 4, 'Bluntville Vanilla 25ct', 30.60, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 139.0000, 18.7000, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(19, '8100000000019', '8100000000019', 'Bluntville Cherry 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Cherry 25ct', 19.05, 'Cost of Goods Sold', 5, 'Bluntville Cherry 25ct', 31.15, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 169.0000, 19.0500, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(20, '8100000000020', '8100000000020', 'Bluntville Blueberry 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Blueberry 25ct', 19.40, 'Cost of Goods Sold', 6, 'Bluntville Blueberry 25ct', 31.70, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 192.0000, 19.4000, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(21, '8100000000021', '8100000000021', 'Bluntville Mint 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Mint 25ct', 19.75, 'Cost of Goods Sold', 7, 'Bluntville Mint 25ct', 32.25, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 306.0000, 19.7500, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(22, '8100000000022', '8100000000022', 'Bluntville Honey 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Honey 25ct', 20.10, 'Cost of Goods Sold', 8, 'Bluntville Honey 25ct', 32.80, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 83.0000, 20.1000, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(23, '8100000000023', '8100000000023', 'Bluntville Wintergreen 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Wintergreen 25ct', 20.45, 'Cost of Goods Sold', 9, 'Bluntville Wintergreen 25ct', 33.35, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 290.0000, 20.4500, 0.0000, 0.0000, 1, 1, 25.0000, '2 For $30.02', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(24, '8100000000024', '8100000000024', 'Bluntville Peach Ice 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Peach Ice 25ct', 20.80, 'Cost of Goods Sold', 10, 'Bluntville Peach Ice 25ct', 33.90, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 398.0000, 20.8000, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(25, '8100000000025', '8100000000025', 'Bluntville Mango 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Mango 25ct', 21.15, 'Cost of Goods Sold', 11, 'Bluntville Mango 25ct', 34.45, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 124.0000, 21.1500, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(26, '8100000000026', '8100000000026', 'Bluntville Grape 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Grape 25ct', 21.50, 'Cost of Goods Sold', 12, 'Bluntville Grape 25ct', 35.00, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 117.0000, 21.5000, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 0, '2026-09-10 11:00:41', '2026-09-10 11:00:43', NULL),
(27, '8100000000027', '8100000000027', 'Bluntville Watermelon 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Watermelon 25ct', 21.85, 'Cost of Goods Sold', 13, 'Bluntville Watermelon 25ct', 35.55, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 336.0000, 21.8500, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(28, '8100000000028', '8100000000028', 'Bluntville Tobacco Classic 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Tobacco Classic 25ct', 22.20, 'Cost of Goods Sold', 14, 'Bluntville Tobacco Classic 25ct', 36.10, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 202.0000, 22.2000, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(29, '8100000000029', '8100000000029', 'Bluntville Smooth 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Smooth 25ct', 22.55, 'Cost of Goods Sold', 1, 'Bluntville Smooth 25ct', 36.65, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 292.0000, 22.5500, 0.0000, 0.0000, 1, 1, 25.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(30, '8100000000030', '8100000000030', 'Bluntville Bold 25ct', 'inventory_part', NULL, NULL, 5, 'Bluntville Bold 25ct', 22.90, 'Cost of Goods Sold', 2, 'Bluntville Bold 25ct', 37.20, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 136.0000, 22.9000, 0.0000, 0.0000, 1, 1, 25.0000, '2 For $33.48', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:46', NULL),
(31, '8100000000031', '8100000000031', 'Rogue Menthol 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Menthol 5ct', 12.00, 'Cost of Goods Sold', 3, 'Rogue Menthol 5ct', 21.75, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 108.0000, 12.0000, 0.0000, 0.0000, 1, 1, 5.0000, '2 For $19.58', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(32, '8100000000032', '8100000000032', 'Rogue Original 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Original 5ct', 12.35, 'Cost of Goods Sold', 4, 'Rogue Original 5ct', 22.30, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 263.0000, 12.3500, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:43', NULL),
(33, '8100000000033', '8100000000033', 'Rogue Vanilla 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Vanilla 5ct', 12.70, 'Cost of Goods Sold', 5, 'Rogue Vanilla 5ct', 22.85, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 415.0000, 12.7000, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(34, '8100000000034', '8100000000034', 'Rogue Cherry 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Cherry 5ct', 13.05, 'Cost of Goods Sold', 6, 'Rogue Cherry 5ct', 23.40, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 333.5000, 13.0500, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(35, '8100000000035', '8100000000035', 'Rogue Blueberry 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Blueberry 5ct', 13.40, 'Cost of Goods Sold', 7, 'Rogue Blueberry 5ct', 23.95, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 397.0000, 13.4000, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(36, '8100000000036', '8100000000036', 'Rogue Mint 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Mint 5ct', 13.75, 'Cost of Goods Sold', 8, 'Rogue Mint 5ct', 24.50, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 20.0000, 13.7500, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(37, '8100000000037', '8100000000037', 'Rogue Honey 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Honey 5ct', 14.10, 'Cost of Goods Sold', 9, 'Rogue Honey 5ct', 25.05, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 335.0000, 14.1000, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(38, '8100000000038', '8100000000038', 'Rogue Wintergreen 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Wintergreen 5ct', 14.45, 'Cost of Goods Sold', 10, 'Rogue Wintergreen 5ct', 25.60, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 37.0000, 14.4500, 0.0000, 0.0000, 1, 1, 5.0000, '2 For $23.04', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:46', NULL),
(39, '8100000000039', '8100000000039', 'Rogue Peach Ice 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Peach Ice 5ct', 14.80, 'Cost of Goods Sold', 11, 'Rogue Peach Ice 5ct', 26.15, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 149.0000, 14.8000, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:46', NULL),
(40, '8100000000040', '8100000000040', 'Rogue Mango 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Mango 5ct', 15.15, 'Cost of Goods Sold', 12, 'Rogue Mango 5ct', 26.70, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 395.5000, 15.1500, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(41, '8100000000041', '8100000000041', 'Rogue Grape 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Grape 5ct', 15.50, 'Cost of Goods Sold', 13, 'Rogue Grape 5ct', 27.25, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, -5.0000, 15.5000, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(42, '8100000000042', '8100000000042', 'Rogue Watermelon 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Watermelon 5ct', 15.85, 'Cost of Goods Sold', 14, 'Rogue Watermelon 5ct', 27.80, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 333.5000, 15.8500, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(43, '8100000000043', '8100000000043', 'Rogue Tobacco Classic 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Tobacco Classic 5ct', 16.20, 'Cost of Goods Sold', 1, 'Rogue Tobacco Classic 5ct', 28.35, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 328.0000, 16.2000, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(44, '8100000000044', '8100000000044', 'Rogue Smooth 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Smooth 5ct', 16.55, 'Cost of Goods Sold', 2, 'Rogue Smooth 5ct', 28.90, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 34.0000, 16.5500, 0.0000, 0.0000, 1, 1, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(45, '8100000000045', '8100000000045', 'Rogue Bold 5ct', 'inventory_part', NULL, NULL, 1, 'Rogue Bold 5ct', 16.90, 'Cost of Goods Sold', 3, 'Rogue Bold 5ct', 29.45, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 203.0000, 16.9000, 0.0000, 0.0000, 1, 1, 5.0000, '2 For $26.51', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(46, '8100000000046', '8100000000046', 'ALP Drifters Menthol 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Menthol 5ct', 28.00, 'Cost of Goods Sold', 4, 'ALP Drifters Menthol 5ct', 42.00, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 261.0000, 28.0000, 0.0000, 0.0000, 2, 2, 5.0000, '2 For $37.80', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(47, '8100000000047', '8100000000047', 'ALP Drifters Original 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Original 5ct', 28.35, 'Cost of Goods Sold', 5, 'ALP Drifters Original 5ct', 42.55, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 327.0000, 28.3500, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(48, '8100000000048', '8100000000048', 'ALP Drifters Vanilla 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Vanilla 5ct', 28.70, 'Cost of Goods Sold', 6, 'ALP Drifters Vanilla 5ct', 43.10, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 305.0000, 28.7000, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(49, '8100000000049', '8100000000049', 'ALP Drifters Cherry 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Cherry 5ct', 29.05, 'Cost of Goods Sold', 7, 'ALP Drifters Cherry 5ct', 43.65, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 34.5000, 29.0500, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(50, '8100000000050', '8100000000050', 'ALP Drifters Blueberry 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Blueberry 5ct', 29.40, 'Cost of Goods Sold', 8, 'ALP Drifters Blueberry 5ct', 44.20, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 128.0000, 29.4000, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(51, '8100000000051', '8100000000051', 'ALP Drifters Mint 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Mint 5ct', 29.75, 'Cost of Goods Sold', 9, 'ALP Drifters Mint 5ct', 44.75, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, -12.0000, 29.7500, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 0, '2026-09-10 11:00:41', '2026-09-10 11:00:43', NULL),
(52, '8100000000052', '8100000000052', 'ALP Drifters Honey 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Honey 5ct', 30.10, 'Cost of Goods Sold', 10, 'ALP Drifters Honey 5ct', 45.30, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 50.0000, 30.1000, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:41', '2026-09-10 11:00:46', NULL),
(53, '8100000000053', '8100000000053', 'ALP Drifters Wintergreen 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Wintergreen 5ct', 30.45, 'Cost of Goods Sold', 11, 'ALP Drifters Wintergreen 5ct', 45.85, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 120.0000, 30.4500, 0.0000, 0.0000, 2, 2, 5.0000, '2 For $41.27', 1, '2026-09-10 11:00:41', '2026-09-10 11:00:47', NULL),
(54, '8100000000054', '8100000000054', 'ALP Drifters Peach Ice 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Peach Ice 5ct', 30.80, 'Cost of Goods Sold', 12, 'ALP Drifters Peach Ice 5ct', 46.40, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 304.0000, 30.8000, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(55, '8100000000055', '8100000000055', 'ALP Drifters Mango 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Mango 5ct', 31.15, 'Cost of Goods Sold', 13, 'ALP Drifters Mango 5ct', 46.95, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 273.0000, 31.1500, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(56, '8100000000056', '8100000000056', 'ALP Drifters Grape 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Grape 5ct', 31.50, 'Cost of Goods Sold', 14, 'ALP Drifters Grape 5ct', 47.50, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 59.0000, 31.5000, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(57, '8100000000057', '8100000000057', 'ALP Drifters Watermelon 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Watermelon 5ct', 31.85, 'Cost of Goods Sold', 1, 'ALP Drifters Watermelon 5ct', 48.05, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 247.0000, 31.8500, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(58, '8100000000058', '8100000000058', 'ALP Drifters Tobacco Classic 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Tobacco Classic 5ct', 32.20, 'Cost of Goods Sold', 2, 'ALP Drifters Tobacco Classic 5ct', 48.60, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 361.0000, 32.2000, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(59, '8100000000059', '8100000000059', 'ALP Drifters Smooth 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Smooth 5ct', 32.55, 'Cost of Goods Sold', 3, 'ALP Drifters Smooth 5ct', 49.15, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 73.0000, 32.5500, 0.0000, 0.0000, 2, 2, 5.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(60, '8100000000060', '8100000000060', 'ALP Drifters Bold 5ct', 'inventory_part', NULL, NULL, 1, 'ALP Drifters Bold 5ct', 32.90, 'Cost of Goods Sold', 4, 'ALP Drifters Bold 5ct', 49.70, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 63.0000, 32.9000, 0.0000, 0.0000, 2, 2, 5.0000, '2 For $44.73', 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(61, '8100000000061', '8100000000061', 'VaporX Menthol', 'inventory_part', NULL, NULL, 1, 'VaporX Menthol', 22.00, 'Cost of Goods Sold', 5, 'VaporX Menthol', 34.95, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 189.0000, 22.0000, 0.0000, 0.0000, 2, 2, 1.0000, '2 For $31.46', 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(62, '8100000000062', '8100000000062', 'VaporX Original', 'inventory_part', NULL, NULL, 1, 'VaporX Original', 22.35, 'Cost of Goods Sold', 6, 'VaporX Original', 35.50, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 197.0000, 22.3500, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(63, '8100000000063', '8100000000063', 'VaporX Vanilla', 'inventory_part', NULL, NULL, 1, 'VaporX Vanilla', 22.70, 'Cost of Goods Sold', 7, 'VaporX Vanilla', 36.05, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 219.0000, 22.7000, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(64, '8100000000064', '8100000000064', 'VaporX Cherry', 'inventory_part', NULL, NULL, 1, 'VaporX Cherry', 23.05, 'Cost of Goods Sold', 8, 'VaporX Cherry', 36.60, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 125.0000, 23.0500, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(65, '8100000000065', '8100000000065', 'VaporX Blueberry', 'inventory_part', NULL, NULL, 1, 'VaporX Blueberry', 23.40, 'Cost of Goods Sold', 9, 'VaporX Blueberry', 37.15, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 169.0000, 23.4000, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(66, '8100000000066', '8100000000066', 'VaporX Mint', 'inventory_part', NULL, NULL, 1, 'VaporX Mint', 23.75, 'Cost of Goods Sold', 10, 'VaporX Mint', 37.70, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 149.0000, 23.7500, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(67, '8100000000067', '8100000000067', 'VaporX Honey', 'inventory_part', NULL, NULL, 1, 'VaporX Honey', 24.10, 'Cost of Goods Sold', 11, 'VaporX Honey', 38.25, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 266.0000, 24.1000, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:48', NULL),
(68, '8100000000068', '8100000000068', 'VaporX Wintergreen', 'inventory_part', NULL, NULL, 1, 'VaporX Wintergreen', 24.45, 'Cost of Goods Sold', 12, 'VaporX Wintergreen', 38.80, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 156.0000, 24.4500, 0.0000, 0.0000, 2, 2, 1.0000, '2 For $34.92', 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(69, '8100000000069', '8100000000069', 'VaporX Peach Ice', 'inventory_part', NULL, NULL, 1, 'VaporX Peach Ice', 24.80, 'Cost of Goods Sold', 13, 'VaporX Peach Ice', 39.35, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 202.0000, 24.8000, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(70, '8100000000070', '8100000000070', 'VaporX Mango', 'inventory_part', NULL, NULL, 1, 'VaporX Mango', 25.15, 'Cost of Goods Sold', 14, 'VaporX Mango', 39.90, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 43.0000, 25.1500, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(71, '8100000000071', '8100000000071', 'VaporX Grape', 'inventory_part', NULL, NULL, 1, 'VaporX Grape', 25.50, 'Cost of Goods Sold', 1, 'VaporX Grape', 40.45, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, -9.0000, 25.5000, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(72, '8100000000072', '8100000000072', 'VaporX Watermelon', 'inventory_part', NULL, NULL, 1, 'VaporX Watermelon', 25.85, 'Cost of Goods Sold', 2, 'VaporX Watermelon', 41.00, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 312.0000, 25.8500, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(73, '8100000000073', '8100000000073', 'VaporX Tobacco Classic', 'inventory_part', NULL, NULL, 1, 'VaporX Tobacco Classic', 26.20, 'Cost of Goods Sold', 3, 'VaporX Tobacco Classic', 41.55, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 20.0000, 26.2000, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(74, '8100000000074', '8100000000074', 'VaporX Smooth', 'inventory_part', NULL, NULL, 1, 'VaporX Smooth', 26.55, 'Cost of Goods Sold', 4, 'VaporX Smooth', 42.10, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 310.0000, 26.5500, 0.0000, 0.0000, 2, 2, 1.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(75, '8100000000075', '8100000000075', 'VaporX Bold', 'inventory_part', NULL, NULL, 1, 'VaporX Bold', 26.90, 'Cost of Goods Sold', 5, 'VaporX Bold', 42.65, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 347.0000, 26.9000, 0.0000, 0.0000, 2, 2, 1.0000, '2 For $38.39', 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(76, '8100000000076', '8100000000076', 'Volt Energy Menthol 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Menthol 24ct', 18.00, 'Cost of Goods Sold', 6, 'Volt Energy Menthol 24ct', 28.80, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 102.0000, 18.0000, 0.0000, 0.0000, 3, 3, 24.0000, '2 For $25.92', 0, '2026-09-10 11:00:42', '2026-09-10 11:00:43', NULL),
(77, '8100000000077', '8100000000077', 'Volt Energy Original 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Original 24ct', 18.35, 'Cost of Goods Sold', 7, 'Volt Energy Original 24ct', 29.35, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 224.0000, 18.3500, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(78, '8100000000078', '8100000000078', 'Volt Energy Vanilla 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Vanilla 24ct', 18.70, 'Cost of Goods Sold', 8, 'Volt Energy Vanilla 24ct', 29.90, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 141.0000, 18.7000, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(79, '8100000000079', '8100000000079', 'Volt Energy Cherry 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Cherry 24ct', 19.05, 'Cost of Goods Sold', 9, 'Volt Energy Cherry 24ct', 30.45, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 81.5000, 19.0500, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(80, '8100000000080', '8100000000080', 'Volt Energy Blueberry 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Blueberry 24ct', 19.40, 'Cost of Goods Sold', 10, 'Volt Energy Blueberry 24ct', 31.00, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 95.0000, 19.4000, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(81, '8100000000081', '8100000000081', 'Volt Energy Mint 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Mint 24ct', 19.75, 'Cost of Goods Sold', 11, 'Volt Energy Mint 24ct', 31.55, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, -10.0000, 19.7500, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:48', NULL),
(82, '8100000000082', '8100000000082', 'Volt Energy Honey 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Honey 24ct', 20.10, 'Cost of Goods Sold', 12, 'Volt Energy Honey 24ct', 32.10, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 194.5000, 20.1000, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(83, '8100000000083', '8100000000083', 'Volt Energy Wintergreen 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Wintergreen 24ct', 20.45, 'Cost of Goods Sold', 13, 'Volt Energy Wintergreen 24ct', 32.65, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 325.0000, 20.4500, 0.0000, 0.0000, 3, 3, 24.0000, '2 For $29.38', 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(84, '8100000000084', '8100000000084', 'Volt Energy Peach Ice 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Peach Ice 24ct', 20.80, 'Cost of Goods Sold', 14, 'Volt Energy Peach Ice 24ct', 33.20, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 57.0000, 20.8000, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(85, '8100000000085', '8100000000085', 'Volt Energy Mango 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Mango 24ct', 21.15, 'Cost of Goods Sold', 1, 'Volt Energy Mango 24ct', 33.75, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 219.0000, 21.1500, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(86, '8100000000086', '8100000000086', 'Volt Energy Grape 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Grape 24ct', 21.50, 'Cost of Goods Sold', 2, 'Volt Energy Grape 24ct', 34.30, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 372.0000, 21.5000, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(87, '8100000000087', '8100000000087', 'Volt Energy Watermelon 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Watermelon 24ct', 21.85, 'Cost of Goods Sold', 3, 'Volt Energy Watermelon 24ct', 34.85, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 149.0000, 21.8500, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(88, '8100000000088', '8100000000088', 'Volt Energy Tobacco Classic 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Tobacco Classic 24ct', 22.20, 'Cost of Goods Sold', 4, 'Volt Energy Tobacco Classic 24ct', 35.40, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 174.0000, 22.2000, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(89, '8100000000089', '8100000000089', 'Volt Energy Smooth 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Smooth 24ct', 22.55, 'Cost of Goods Sold', 5, 'Volt Energy Smooth 24ct', 35.95, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 299.0000, 22.5500, 0.0000, 0.0000, 3, 3, 24.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:43', NULL),
(90, '8100000000090', '8100000000090', 'Volt Energy Bold 24ct', 'inventory_part', NULL, NULL, 5, 'Volt Energy Bold 24ct', 22.90, 'Cost of Goods Sold', 6, 'Volt Energy Bold 24ct', 36.50, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 375.0000, 22.9000, 0.0000, 0.0000, 3, 3, 24.0000, '2 For $32.85', 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(91, '8100000000091', '8100000000091', 'Crunch Trail Menthol 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Menthol 12ct', 14.00, 'Cost of Goods Sold', 7, 'Crunch Trail Menthol 12ct', 22.50, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 93.0000, 14.0000, 0.0000, 0.0000, 4, 4, 12.0000, '2 For $20.25', 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(92, '8100000000092', '8100000000092', 'Crunch Trail Original 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Original 12ct', 14.35, 'Cost of Goods Sold', 8, 'Crunch Trail Original 12ct', 23.05, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 55.0000, 14.3500, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(93, '8100000000093', '8100000000093', 'Crunch Trail Vanilla 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Vanilla 12ct', 14.70, 'Cost of Goods Sold', 9, 'Crunch Trail Vanilla 12ct', 23.60, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 266.0000, 14.7000, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(94, '8100000000094', '8100000000094', 'Crunch Trail Cherry 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Cherry 12ct', 15.05, 'Cost of Goods Sold', 10, 'Crunch Trail Cherry 12ct', 24.15, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 337.0000, 15.0500, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(95, '8100000000095', '8100000000095', 'Crunch Trail Blueberry 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Blueberry 12ct', 15.40, 'Cost of Goods Sold', 11, 'Crunch Trail Blueberry 12ct', 24.70, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 263.0000, 15.4000, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(96, '8100000000096', '8100000000096', 'Crunch Trail Mint 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Mint 12ct', 15.75, 'Cost of Goods Sold', 12, 'Crunch Trail Mint 12ct', 25.25, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 211.0000, 15.7500, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(97, '8100000000097', '8100000000097', 'Crunch Trail Honey 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Honey 12ct', 16.10, 'Cost of Goods Sold', 13, 'Crunch Trail Honey 12ct', 25.80, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 195.0000, 16.1000, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(98, '8100000000098', '8100000000098', 'Crunch Trail Wintergreen 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Wintergreen 12ct', 16.45, 'Cost of Goods Sold', 14, 'Crunch Trail Wintergreen 12ct', 26.35, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 85.0000, 16.4500, 0.0000, 0.0000, 4, 4, 12.0000, '2 For $23.72', 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(99, '8100000000099', '8100000000099', 'Crunch Trail Peach Ice 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Peach Ice 12ct', 16.80, 'Cost of Goods Sold', 1, 'Crunch Trail Peach Ice 12ct', 26.90, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 285.0000, 16.8000, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:47', NULL),
(100, '8100000000100', '8100000000100', 'Crunch Trail Mango 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Mango 12ct', 17.15, 'Cost of Goods Sold', 2, 'Crunch Trail Mango 12ct', 27.45, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 360.0000, 17.1500, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:42', '2026-09-10 11:00:46', NULL),
(101, '8100000000101', '8100000000101', 'Crunch Trail Grape 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Grape 12ct', 17.50, 'Cost of Goods Sold', 3, 'Crunch Trail Grape 12ct', 28.00, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, -12.0000, 17.5000, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 0, '2026-09-10 11:00:43', '2026-09-10 11:00:43', NULL),
(102, '8100000000102', '8100000000102', 'Crunch Trail Watermelon 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Watermelon 12ct', 17.85, 'Cost of Goods Sold', 4, 'Crunch Trail Watermelon 12ct', 28.55, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 33.0000, 17.8500, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:46', NULL),
(103, '8100000000103', '8100000000103', 'Crunch Trail Tobacco Classic 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Tobacco Classic 12ct', 18.20, 'Cost of Goods Sold', 5, 'Crunch Trail Tobacco Classic 12ct', 29.10, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 267.0000, 18.2000, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:48', NULL),
(104, '8100000000104', '8100000000104', 'Crunch Trail Smooth 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Smooth 12ct', 18.55, 'Cost of Goods Sold', 6, 'Crunch Trail Smooth 12ct', 29.65, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 300.0000, 18.5500, 0.0000, 0.0000, 4, 4, 12.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(105, '8100000000105', '8100000000105', 'Crunch Trail Bold 12ct', 'inventory_part', NULL, NULL, 5, 'Crunch Trail Bold 12ct', 18.90, 'Cost of Goods Sold', 7, 'Crunch Trail Bold 12ct', 30.20, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 100.0000, 18.9000, 0.0000, 0.0000, 4, 4, 12.0000, '2 For $27.18', 1, '2026-09-10 11:00:43', '2026-09-10 11:00:48', NULL),
(106, '8100000000106', '8100000000106', 'Clipper Acc Menthol', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Menthol', 6.00, 'Cost of Goods Sold', 8, 'Clipper Acc Menthol', 12.00, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, -6.0000, 6.0000, 0.0000, 0.0000, 5, 5, 1.0000, '2 For $10.80', 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(107, '8100000000107', '8100000000107', 'Clipper Acc Original', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Original', 6.35, 'Cost of Goods Sold', 9, 'Clipper Acc Original', 12.55, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 209.0000, 6.3500, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:48', NULL),
(108, '8100000000108', '8100000000108', 'Clipper Acc Vanilla', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Vanilla', 6.70, 'Cost of Goods Sold', 10, 'Clipper Acc Vanilla', 13.10, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 109.0000, 6.7000, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(109, '8100000000109', '8100000000109', 'Clipper Acc Cherry', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Cherry', 7.05, 'Cost of Goods Sold', 11, 'Clipper Acc Cherry', 13.65, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 120.0000, 7.0500, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:48', NULL),
(110, '8100000000110', '8100000000110', 'Clipper Acc Blueberry', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Blueberry', 7.40, 'Cost of Goods Sold', 12, 'Clipper Acc Blueberry', 14.20, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 164.0000, 7.4000, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(111, '8100000000111', '8100000000111', 'Clipper Acc Mint', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Mint', 7.75, 'Cost of Goods Sold', 13, 'Clipper Acc Mint', 14.75, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 414.0000, 7.7500, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(112, '8100000000112', '8100000000112', 'Clipper Acc Honey', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Honey', 8.10, 'Cost of Goods Sold', 14, 'Clipper Acc Honey', 15.30, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 80.0000, 8.1000, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(113, '8100000000113', '8100000000113', 'Clipper Acc Wintergreen', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Wintergreen', 8.45, 'Cost of Goods Sold', 1, 'Clipper Acc Wintergreen', 15.85, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 364.0000, 8.4500, 0.0000, 0.0000, 5, 5, 1.0000, '2 For $14.27', 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(114, '8100000000114', '8100000000114', 'Clipper Acc Peach Ice', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Peach Ice', 8.80, 'Cost of Goods Sold', 2, 'Clipper Acc Peach Ice', 16.40, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 121.0000, 8.8000, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:46', NULL),
(115, '8100000000115', '8100000000115', 'Clipper Acc Mango', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Mango', 9.15, 'Cost of Goods Sold', 3, 'Clipper Acc Mango', 16.95, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 209.5000, 9.1500, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:46', NULL),
(116, '8100000000116', '8100000000116', 'Clipper Acc Grape', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Grape', 9.50, 'Cost of Goods Sold', 4, 'Clipper Acc Grape', 17.50, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 113.0000, 9.5000, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(117, '8100000000117', '8100000000117', 'Clipper Acc Watermelon', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Watermelon', 9.85, 'Cost of Goods Sold', 5, 'Clipper Acc Watermelon', 18.05, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 42.0000, 9.8500, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(118, '8100000000118', '8100000000118', 'Clipper Acc Tobacco Classic', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Tobacco Classic', 10.20, 'Cost of Goods Sold', 6, 'Clipper Acc Tobacco Classic', 18.60, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 258.0000, 10.2000, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(119, '8100000000119', '8100000000119', 'Clipper Acc Smooth', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Smooth', 10.55, 'Cost of Goods Sold', 7, 'Clipper Acc Smooth', 19.15, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 218.0000, 10.5500, 0.0000, 0.0000, 5, 5, 1.0000, NULL, 1, '2026-09-10 11:00:43', '2026-09-10 11:00:47', NULL),
(120, '8100000000120', '8100000000120', 'Clipper Acc Bold', 'inventory_part', NULL, NULL, 1, 'Clipper Acc Bold', 10.90, 'Cost of Goods Sold', 8, 'Clipper Acc Bold', 19.70, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 257.0000, 10.9000, 0.0000, 0.0000, 5, 5, 1.0000, '2 For $17.73', 1, '2026-09-10 11:00:43', '2026-09-10 11:00:46', NULL),
(121, '8100000000005-COPY', '8100000000005-COPY', 'Dark Horse Blueberry 200ct (Copy)', 'inventory_part', NULL, NULL, 5, 'Dark Horse Blueberry 200ct', 39.40, 'Cost of Goods Sold', 5, 'Dark Horse Blueberry 200ct (Copy)', 54.15, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 0.0000, 0.0000, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 11:06:02', '2026-09-10 12:24:13', '2026-09-10 12:24:13'),
(122, '8100000000002-COPY', '8100000000002', 'Dark Horse Original 200ct (Copy)', 'inventory_part', NULL, NULL, 5, 'Dark Horse Original 200ct', 38.35, 'Cost of Goods Sold', 2, 'Dark Horse Original 200ct (Copy)', 52.50, 1, 'Sales', 'Inventory Asset', 10.0000, 200.0000, 0.0000, 0.0000, 0.0000, 0.0000, 1, 1, 200.0000, NULL, 1, '2026-09-10 12:13:16', '2026-09-10 12:24:08', '2026-09-10 12:24:08'),
(123, '435', '435', '435', 'inventory_part', NULL, NULL, NULL, NULL, 0.00, 'Cost of Goods Sold', NULL, NULL, 0.00, NULL, 'Sales', 'Inventory Asset', NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, 1, '2026-09-15 11:52:23', '2026-09-15 11:52:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`id`, `code`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '3251', 'Cigars / Tobacco', 'Demo category for development', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, '4101', 'Vape / OTP', 'Demo category for development', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, '5200', 'Beverages', 'Demo category for development', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, '5300', 'Snacks', 'Demo category for development', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(5, '5400', 'Accessories', 'Demo category for development', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(6, '5900', 'Other', 'Demo category for development', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `item_histories`
--

CREATE TABLE `item_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `event` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_in` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_out` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `balance_after` decimal(15,4) DEFAULT NULL,
  `old_value` decimal(15,4) DEFAULT NULL,
  `new_value` decimal(15,4) DEFAULT NULL,
  `unit_cost` decimal(15,4) DEFAULT NULL,
  `suggested_sales_price` decimal(15,2) DEFAULT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci,
  `occurred_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_notes`
--

CREATE TABLE `item_notes` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_prices`
--

CREATE TABLE `item_prices` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `price_level_id` bigint UNSIGNED NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_prices`
--

INSERT INTO `item_prices` (`id`, `item_id`, `price_level_id`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 45.72, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 1, 2, 44.16, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, 1, 3, 51.95, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, 1, 4, 42.60, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(5, 1, 5, 51.95, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(6, 2, 1, 46.20, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(7, 2, 2, 44.63, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(8, 2, 3, 52.50, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(9, 2, 4, 43.05, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(10, 2, 5, 52.50, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(11, 3, 1, 46.68, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(12, 3, 2, 45.09, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(13, 3, 3, 53.05, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(14, 3, 4, 43.50, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(15, 3, 5, 53.05, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(16, 4, 1, 47.17, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(17, 4, 2, 45.56, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(18, 4, 3, 53.60, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(19, 4, 4, 43.95, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(20, 4, 5, 53.60, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(21, 5, 1, 47.65, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(22, 5, 2, 46.03, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(23, 5, 3, 54.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(24, 5, 4, 44.40, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(25, 5, 5, 54.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(26, 6, 1, 48.14, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(27, 6, 2, 46.50, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(28, 6, 3, 54.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(29, 6, 4, 44.85, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(30, 6, 5, 54.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(31, 7, 1, 48.62, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(32, 7, 2, 46.96, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(33, 7, 3, 55.25, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(34, 7, 4, 45.31, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(35, 7, 5, 55.25, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(36, 8, 1, 49.10, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(37, 8, 2, 47.43, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(38, 8, 3, 55.80, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(39, 8, 4, 45.76, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(40, 8, 5, 55.80, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(41, 9, 1, 49.59, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(42, 9, 2, 47.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(43, 9, 3, 56.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(44, 9, 4, 46.21, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(45, 9, 5, 56.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(46, 10, 1, 50.07, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(47, 10, 2, 48.36, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(48, 10, 3, 56.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(49, 10, 4, 46.66, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(50, 10, 5, 56.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(51, 11, 1, 50.56, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(52, 11, 2, 48.83, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(53, 11, 3, 57.45, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(54, 11, 4, 47.11, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(55, 11, 5, 57.45, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(56, 12, 1, 51.04, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(57, 12, 2, 49.30, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(58, 12, 3, 58.00, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(59, 12, 4, 47.56, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(60, 12, 5, 58.00, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(61, 13, 1, 51.52, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(62, 13, 2, 49.77, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(63, 13, 3, 58.55, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(64, 13, 4, 48.01, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(65, 13, 5, 58.55, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(66, 14, 1, 52.01, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(67, 14, 2, 50.24, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(68, 14, 3, 59.10, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(69, 14, 4, 48.46, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(70, 14, 5, 59.10, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(71, 15, 1, 52.49, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(72, 15, 2, 50.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(73, 15, 3, 59.65, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(74, 15, 4, 48.91, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(75, 15, 5, 59.65, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(76, 16, 1, 25.96, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(77, 16, 2, 25.08, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(78, 16, 3, 29.50, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(79, 16, 4, 24.19, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(80, 16, 5, 29.50, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(81, 17, 1, 26.44, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(82, 17, 2, 25.54, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(83, 17, 3, 30.05, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(84, 17, 4, 24.64, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(85, 17, 5, 30.05, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(86, 18, 1, 26.93, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(87, 18, 2, 26.01, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(88, 18, 3, 30.60, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(89, 18, 4, 25.09, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(90, 18, 5, 30.60, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(91, 19, 1, 27.41, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(92, 19, 2, 26.48, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(93, 19, 3, 31.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(94, 19, 4, 25.54, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(95, 19, 5, 31.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(96, 20, 1, 27.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(97, 20, 2, 26.95, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(98, 20, 3, 31.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(99, 20, 4, 25.99, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(100, 20, 5, 31.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(101, 21, 1, 28.38, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(102, 21, 2, 27.41, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(103, 21, 3, 32.25, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(104, 21, 4, 26.44, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(105, 21, 5, 32.25, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(106, 22, 1, 28.86, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(107, 22, 2, 27.88, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(108, 22, 3, 32.80, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(109, 22, 4, 26.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(110, 22, 5, 32.80, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(111, 23, 1, 29.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(112, 23, 2, 28.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(113, 23, 3, 33.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(114, 23, 4, 27.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(115, 23, 5, 33.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(116, 24, 1, 29.83, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(117, 24, 2, 28.81, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(118, 24, 3, 33.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(119, 24, 4, 27.80, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(120, 24, 5, 33.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(121, 25, 1, 30.32, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(122, 25, 2, 29.28, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(123, 25, 3, 34.45, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(124, 25, 4, 28.25, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(125, 25, 5, 34.45, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(126, 26, 1, 30.80, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(127, 26, 2, 29.75, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(128, 26, 3, 35.00, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(129, 26, 4, 28.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(130, 26, 5, 35.00, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(131, 27, 1, 31.28, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(132, 27, 2, 30.22, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(133, 27, 3, 35.55, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(134, 27, 4, 29.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(135, 27, 5, 35.55, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(136, 28, 1, 31.77, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(137, 28, 2, 30.69, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(138, 28, 3, 36.10, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(139, 28, 4, 29.60, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(140, 28, 5, 36.10, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(141, 29, 1, 32.25, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(142, 29, 2, 31.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(143, 29, 3, 36.65, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(144, 29, 4, 30.05, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(145, 29, 5, 36.65, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(146, 30, 1, 32.74, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(147, 30, 2, 31.62, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(148, 30, 3, 37.20, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(149, 30, 4, 30.50, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(150, 30, 5, 37.20, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(151, 31, 1, 19.14, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(152, 31, 2, 18.49, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(153, 31, 3, 21.75, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(154, 31, 4, 17.83, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(155, 31, 5, 21.75, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(156, 32, 1, 19.62, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(157, 32, 2, 18.96, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(158, 32, 3, 22.30, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(159, 32, 4, 18.29, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(160, 32, 5, 22.30, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(161, 33, 1, 20.11, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(162, 33, 2, 19.42, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(163, 33, 3, 22.85, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(164, 33, 4, 18.74, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(165, 33, 5, 22.85, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(166, 34, 1, 20.59, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(167, 34, 2, 19.89, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(168, 34, 3, 23.40, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(169, 34, 4, 19.19, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(170, 34, 5, 23.40, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(171, 35, 1, 21.08, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(172, 35, 2, 20.36, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(173, 35, 3, 23.95, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(174, 35, 4, 19.64, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(175, 35, 5, 23.95, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(176, 36, 1, 21.56, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(177, 36, 2, 20.83, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(178, 36, 3, 24.50, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(179, 36, 4, 20.09, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(180, 36, 5, 24.50, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(181, 37, 1, 22.04, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(182, 37, 2, 21.29, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(183, 37, 3, 25.05, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(184, 37, 4, 20.54, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(185, 37, 5, 25.05, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(186, 38, 1, 22.53, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(187, 38, 2, 21.76, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(188, 38, 3, 25.60, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(189, 38, 4, 20.99, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(190, 38, 5, 25.60, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(191, 39, 1, 23.01, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(192, 39, 2, 22.23, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(193, 39, 3, 26.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(194, 39, 4, 21.44, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(195, 39, 5, 26.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(196, 40, 1, 23.50, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(197, 40, 2, 22.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(198, 40, 3, 26.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(199, 40, 4, 21.89, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(200, 40, 5, 26.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(201, 41, 1, 23.98, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(202, 41, 2, 23.16, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(203, 41, 3, 27.25, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(204, 41, 4, 22.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(205, 41, 5, 27.25, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(206, 42, 1, 24.46, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(207, 42, 2, 23.63, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(208, 42, 3, 27.80, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(209, 42, 4, 22.80, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(210, 42, 5, 27.80, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(211, 43, 1, 24.95, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(212, 43, 2, 24.10, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(213, 43, 3, 28.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(214, 43, 4, 23.25, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(215, 43, 5, 28.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(216, 44, 1, 25.43, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(217, 44, 2, 24.56, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(218, 44, 3, 28.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(219, 44, 4, 23.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(220, 44, 5, 28.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(221, 45, 1, 25.92, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(222, 45, 2, 25.03, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(223, 45, 3, 29.45, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(224, 45, 4, 24.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(225, 45, 5, 29.45, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(226, 46, 1, 36.96, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(227, 46, 2, 35.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(228, 46, 3, 42.00, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(229, 46, 4, 34.44, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(230, 46, 5, 42.00, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(231, 47, 1, 37.44, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(232, 47, 2, 36.17, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(233, 47, 3, 42.55, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(234, 47, 4, 34.89, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(235, 47, 5, 42.55, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(236, 48, 1, 37.93, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(237, 48, 2, 36.64, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(238, 48, 3, 43.10, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(239, 48, 4, 35.34, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(240, 48, 5, 43.10, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(241, 49, 1, 38.41, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(242, 49, 2, 37.10, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(243, 49, 3, 43.65, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(244, 49, 4, 35.79, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(245, 49, 5, 43.65, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(246, 50, 1, 38.90, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(247, 50, 2, 37.57, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(248, 50, 3, 44.20, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(249, 50, 4, 36.24, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(250, 50, 5, 44.20, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(251, 51, 1, 39.38, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(252, 51, 2, 38.04, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(253, 51, 3, 44.75, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(254, 51, 4, 36.70, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(255, 51, 5, 44.75, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(256, 52, 1, 39.86, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(257, 52, 2, 38.50, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(258, 52, 3, 45.30, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(259, 52, 4, 37.15, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(260, 52, 5, 45.30, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(261, 53, 1, 40.35, '2026-09-10 11:00:41', '2026-09-10 11:00:41'),
(262, 53, 2, 38.97, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(263, 53, 3, 45.85, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(264, 53, 4, 37.60, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(265, 53, 5, 45.85, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(266, 54, 1, 40.83, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(267, 54, 2, 39.44, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(268, 54, 3, 46.40, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(269, 54, 4, 38.05, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(270, 54, 5, 46.40, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(271, 55, 1, 41.32, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(272, 55, 2, 39.91, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(273, 55, 3, 46.95, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(274, 55, 4, 38.50, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(275, 55, 5, 46.95, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(276, 56, 1, 41.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(277, 56, 2, 40.38, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(278, 56, 3, 47.50, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(279, 56, 4, 38.95, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(280, 56, 5, 47.50, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(281, 57, 1, 42.28, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(282, 57, 2, 40.84, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(283, 57, 3, 48.05, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(284, 57, 4, 39.40, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(285, 57, 5, 48.05, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(286, 58, 1, 42.77, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(287, 58, 2, 41.31, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(288, 58, 3, 48.60, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(289, 58, 4, 39.85, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(290, 58, 5, 48.60, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(291, 59, 1, 43.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(292, 59, 2, 41.78, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(293, 59, 3, 49.15, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(294, 59, 4, 40.30, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(295, 59, 5, 49.15, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(296, 60, 1, 43.74, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(297, 60, 2, 42.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(298, 60, 3, 49.70, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(299, 60, 4, 40.75, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(300, 60, 5, 49.70, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(301, 61, 1, 30.76, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(302, 61, 2, 29.71, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(303, 61, 3, 34.95, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(304, 61, 4, 28.66, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(305, 61, 5, 34.95, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(306, 62, 1, 31.24, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(307, 62, 2, 30.18, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(308, 62, 3, 35.50, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(309, 62, 4, 29.11, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(310, 62, 5, 35.50, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(311, 63, 1, 31.72, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(312, 63, 2, 30.64, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(313, 63, 3, 36.05, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(314, 63, 4, 29.56, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(315, 63, 5, 36.05, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(316, 64, 1, 32.21, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(317, 64, 2, 31.11, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(318, 64, 3, 36.60, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(319, 64, 4, 30.01, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(320, 64, 5, 36.60, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(321, 65, 1, 32.69, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(322, 65, 2, 31.58, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(323, 65, 3, 37.15, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(324, 65, 4, 30.46, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(325, 65, 5, 37.15, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(326, 66, 1, 33.18, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(327, 66, 2, 32.05, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(328, 66, 3, 37.70, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(329, 66, 4, 30.91, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(330, 66, 5, 37.70, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(331, 67, 1, 33.66, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(332, 67, 2, 32.51, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(333, 67, 3, 38.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(334, 67, 4, 31.37, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(335, 67, 5, 38.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(336, 68, 1, 34.14, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(337, 68, 2, 32.98, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(338, 68, 3, 38.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(339, 68, 4, 31.82, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(340, 68, 5, 38.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(341, 69, 1, 34.63, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(342, 69, 2, 33.45, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(343, 69, 3, 39.35, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(344, 69, 4, 32.27, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(345, 69, 5, 39.35, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(346, 70, 1, 35.11, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(347, 70, 2, 33.92, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(348, 70, 3, 39.90, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(349, 70, 4, 32.72, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(350, 70, 5, 39.90, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(351, 71, 1, 35.60, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(352, 71, 2, 34.38, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(353, 71, 3, 40.45, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(354, 71, 4, 33.17, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(355, 71, 5, 40.45, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(356, 72, 1, 36.08, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(357, 72, 2, 34.85, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(358, 72, 3, 41.00, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(359, 72, 4, 33.62, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(360, 72, 5, 41.00, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(361, 73, 1, 36.56, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(362, 73, 2, 35.32, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(363, 73, 3, 41.55, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(364, 73, 4, 34.07, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(365, 73, 5, 41.55, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(366, 74, 1, 37.05, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(367, 74, 2, 35.79, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(368, 74, 3, 42.10, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(369, 74, 4, 34.52, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(370, 74, 5, 42.10, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(371, 75, 1, 37.53, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(372, 75, 2, 36.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(373, 75, 3, 42.65, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(374, 75, 4, 34.97, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(375, 75, 5, 42.65, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(376, 76, 1, 25.34, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(377, 76, 2, 24.48, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(378, 76, 3, 28.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(379, 76, 4, 23.62, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(380, 76, 5, 28.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(381, 77, 1, 25.83, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(382, 77, 2, 24.95, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(383, 77, 3, 29.35, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(384, 77, 4, 24.07, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(385, 77, 5, 29.35, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(386, 78, 1, 26.31, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(387, 78, 2, 25.42, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(388, 78, 3, 29.90, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(389, 78, 4, 24.52, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(390, 78, 5, 29.90, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(391, 79, 1, 26.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(392, 79, 2, 25.88, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(393, 79, 3, 30.45, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(394, 79, 4, 24.97, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(395, 79, 5, 30.45, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(396, 80, 1, 27.28, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(397, 80, 2, 26.35, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(398, 80, 3, 31.00, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(399, 80, 4, 25.42, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(400, 80, 5, 31.00, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(401, 81, 1, 27.76, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(402, 81, 2, 26.82, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(403, 81, 3, 31.55, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(404, 81, 4, 25.87, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(405, 81, 5, 31.55, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(406, 82, 1, 28.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(407, 82, 2, 27.29, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(408, 82, 3, 32.10, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(409, 82, 4, 26.32, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(410, 82, 5, 32.10, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(411, 83, 1, 28.73, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(412, 83, 2, 27.75, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(413, 83, 3, 32.65, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(414, 83, 4, 26.77, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(415, 83, 5, 32.65, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(416, 84, 1, 29.22, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(417, 84, 2, 28.22, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(418, 84, 3, 33.20, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(419, 84, 4, 27.22, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(420, 84, 5, 33.20, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(421, 85, 1, 29.70, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(422, 85, 2, 28.69, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(423, 85, 3, 33.75, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(424, 85, 4, 27.67, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(425, 85, 5, 33.75, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(426, 86, 1, 30.18, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(427, 86, 2, 29.15, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(428, 86, 3, 34.30, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(429, 86, 4, 28.13, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(430, 86, 5, 34.30, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(431, 87, 1, 30.67, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(432, 87, 2, 29.62, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(433, 87, 3, 34.85, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(434, 87, 4, 28.58, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(435, 87, 5, 34.85, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(436, 88, 1, 31.15, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(437, 88, 2, 30.09, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(438, 88, 3, 35.40, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(439, 88, 4, 29.03, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(440, 88, 5, 35.40, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(441, 89, 1, 31.64, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(442, 89, 2, 30.56, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(443, 89, 3, 35.95, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(444, 89, 4, 29.48, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(445, 89, 5, 35.95, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(446, 90, 1, 32.12, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(447, 90, 2, 31.03, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(448, 90, 3, 36.50, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(449, 90, 4, 29.93, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(450, 90, 5, 36.50, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(451, 91, 1, 19.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(452, 91, 2, 19.13, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(453, 91, 3, 22.50, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(454, 91, 4, 18.45, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(455, 91, 5, 22.50, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(456, 92, 1, 20.28, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(457, 92, 2, 19.59, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(458, 92, 3, 23.05, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(459, 92, 4, 18.90, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(460, 92, 5, 23.05, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(461, 93, 1, 20.77, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(462, 93, 2, 20.06, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(463, 93, 3, 23.60, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(464, 93, 4, 19.35, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(465, 93, 5, 23.60, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(466, 94, 1, 21.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(467, 94, 2, 20.53, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(468, 94, 3, 24.15, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(469, 94, 4, 19.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(470, 94, 5, 24.15, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(471, 95, 1, 21.74, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(472, 95, 2, 20.99, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(473, 95, 3, 24.70, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(474, 95, 4, 20.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(475, 95, 5, 24.70, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(476, 96, 1, 22.22, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(477, 96, 2, 21.46, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(478, 96, 3, 25.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(479, 96, 4, 20.71, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(480, 96, 5, 25.25, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(481, 97, 1, 22.70, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(482, 97, 2, 21.93, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(483, 97, 3, 25.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(484, 97, 4, 21.16, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(485, 97, 5, 25.80, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(486, 98, 1, 23.19, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(487, 98, 2, 22.40, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(488, 98, 3, 26.35, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(489, 98, 4, 21.61, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(490, 98, 5, 26.35, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(491, 99, 1, 23.67, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(492, 99, 2, 22.87, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(493, 99, 3, 26.90, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(494, 99, 4, 22.06, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(495, 99, 5, 26.90, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(496, 100, 1, 24.16, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(497, 100, 2, 23.33, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(498, 100, 3, 27.45, '2026-09-10 11:00:42', '2026-09-10 11:00:42'),
(499, 100, 4, 22.51, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(500, 100, 5, 27.45, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(501, 101, 1, 24.64, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(502, 101, 2, 23.80, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(503, 101, 3, 28.00, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(504, 101, 4, 22.96, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(505, 101, 5, 28.00, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(506, 102, 1, 25.12, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(507, 102, 2, 24.27, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(508, 102, 3, 28.55, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(509, 102, 4, 23.41, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(510, 102, 5, 28.55, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(511, 103, 1, 25.61, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(512, 103, 2, 24.74, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(513, 103, 3, 29.10, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(514, 103, 4, 23.86, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(515, 103, 5, 29.10, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(516, 104, 1, 26.09, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(517, 104, 2, 25.20, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(518, 104, 3, 29.65, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(519, 104, 4, 24.31, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(520, 104, 5, 29.65, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(521, 105, 1, 26.58, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(522, 105, 2, 25.67, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(523, 105, 3, 30.20, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(524, 105, 4, 24.76, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(525, 105, 5, 30.20, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(526, 106, 1, 10.56, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(527, 106, 2, 10.20, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(528, 106, 3, 12.00, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(529, 106, 4, 9.84, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(530, 106, 5, 12.00, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(531, 107, 1, 11.04, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(532, 107, 2, 10.67, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(533, 107, 3, 12.55, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(534, 107, 4, 10.29, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(535, 107, 5, 12.55, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(536, 108, 1, 11.53, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(537, 108, 2, 11.14, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(538, 108, 3, 13.10, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(539, 108, 4, 10.74, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(540, 108, 5, 13.10, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(541, 109, 1, 12.01, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(542, 109, 2, 11.60, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(543, 109, 3, 13.65, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(544, 109, 4, 11.19, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(545, 109, 5, 13.65, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(546, 110, 1, 12.50, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(547, 110, 2, 12.07, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(548, 110, 3, 14.20, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(549, 110, 4, 11.64, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(550, 110, 5, 14.20, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(551, 111, 1, 12.98, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(552, 111, 2, 12.54, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(553, 111, 3, 14.75, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(554, 111, 4, 12.09, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(555, 111, 5, 14.75, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(556, 112, 1, 13.46, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(557, 112, 2, 13.01, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(558, 112, 3, 15.30, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(559, 112, 4, 12.55, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(560, 112, 5, 15.30, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(561, 113, 1, 13.95, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(562, 113, 2, 13.47, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(563, 113, 3, 15.85, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(564, 113, 4, 13.00, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(565, 113, 5, 15.85, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(566, 114, 1, 14.43, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(567, 114, 2, 13.94, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(568, 114, 3, 16.40, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(569, 114, 4, 13.45, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(570, 114, 5, 16.40, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(571, 115, 1, 14.92, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(572, 115, 2, 14.41, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(573, 115, 3, 16.95, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(574, 115, 4, 13.90, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(575, 115, 5, 16.95, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(576, 116, 1, 15.40, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(577, 116, 2, 14.88, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(578, 116, 3, 17.50, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(579, 116, 4, 14.35, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(580, 116, 5, 17.50, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(581, 117, 1, 15.88, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(582, 117, 2, 15.34, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(583, 117, 3, 18.05, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(584, 117, 4, 14.80, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(585, 117, 5, 18.05, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(586, 118, 1, 16.37, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(587, 118, 2, 15.81, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(588, 118, 3, 18.60, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(589, 118, 4, 15.25, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(590, 118, 5, 18.60, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(591, 119, 1, 16.85, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(592, 119, 2, 16.28, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(593, 119, 3, 19.15, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(594, 119, 4, 15.70, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(595, 119, 5, 19.15, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(596, 120, 1, 17.34, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(597, 120, 2, 16.74, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(598, 120, 3, 19.70, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(599, 120, 4, 16.15, '2026-09-10 11:00:43', '2026-09-10 11:00:43'),
(600, 120, 5, 19.70, '2026-09-10 11:00:43', '2026-09-10 11:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `item_types`
--

CREATE TABLE `item_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_types`
--

INSERT INTO `item_types` (`id`, `name`, `label`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'tobacco', 'Tobacco', NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 'vape', 'Vape', NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, 'beverage', 'Beverage', NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, 'snack', 'Snack', NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(5, 'accessory', 'Accessory', NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(6, 'general', 'General', NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `entry_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_date` date NOT NULL,
  `memo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `entry_number`, `entry_date`, `memo`, `reference_type`, `reference_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'JE-BILL-BILL-00002', '2026-08-11', 'Vendor bill BILL-00002', 'App\\Models\\VendorBill', 1, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 'JE-VP-VP-00002', '2026-08-27', 'Vendor payment VP-00002', 'App\\Models\\VendorPayment', 1, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 'JE-BILL-BILL-00003', '2026-08-04', 'Vendor bill BILL-00003', 'App\\Models\\VendorBill', 2, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 'JE-BILL-BILL-00004', '2026-07-28', 'Vendor bill BILL-00004', 'App\\Models\\VendorBill', 3, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 'JE-VP-VP-00004', '2026-08-13', 'Vendor payment VP-00004', 'App\\Models\\VendorPayment', 2, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 'JE-BILL-BILL-00007', '2026-07-07', 'Vendor bill BILL-00007', 'App\\Models\\VendorBill', 4, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(7, 'JE-BILL-BILL-00008', '2026-06-30', 'Vendor bill BILL-00008', 'App\\Models\\VendorBill', 5, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(8, 'JE-VP-VP-00008', '2026-07-16', 'Vendor payment VP-00008', 'App\\Models\\VendorPayment', 3, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(9, 'JE-BILL-BILL-00009', '2026-06-23', 'Vendor bill BILL-00009', 'App\\Models\\VendorBill', 6, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(10, 'JE-BILL-BILL-00012', '2026-06-02', 'Vendor bill BILL-00012', 'App\\Models\\VendorBill', 7, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(11, 'JE-VP-VP-00012', '2026-06-18', 'Vendor payment VP-00012', 'App\\Models\\VendorPayment', 4, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(12, 'JE-BILL-BILL-00013', '2026-05-26', 'Vendor bill BILL-00013', 'App\\Models\\VendorBill', 8, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(13, 'JE-BILL-BILL-00014', '2026-05-19', 'Vendor bill BILL-00014', 'App\\Models\\VendorBill', 9, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(14, 'JE-VP-VP-00014', '2026-06-04', 'Vendor payment VP-00014', 'App\\Models\\VendorPayment', 5, 1, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(15, 'JE-BILL-BILL-00017', '2026-04-28', 'Vendor bill BILL-00017', 'App\\Models\\VendorBill', 10, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(16, 'JE-BILL-BILL-00018', '2026-04-21', 'Vendor bill BILL-00018', 'App\\Models\\VendorBill', 11, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(17, 'JE-VP-VP-00018', '2026-05-07', 'Vendor payment VP-00018', 'App\\Models\\VendorPayment', 6, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(18, 'JE-BILL-BILL-00019', '2026-04-14', 'Vendor bill BILL-00019', 'App\\Models\\VendorBill', 12, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(19, 'JE-BILL-BILL-00022', '2026-03-24', 'Vendor bill BILL-00022', 'App\\Models\\VendorBill', 13, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(20, 'JE-VP-VP-00022', '2026-04-09', 'Vendor payment VP-00022', 'App\\Models\\VendorPayment', 7, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(21, 'JE-BILL-BILL-00023', '2026-03-17', 'Vendor bill BILL-00023', 'App\\Models\\VendorBill', 14, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(22, 'JE-BILL-BILL-00024', '2026-03-10', 'Vendor bill BILL-00024', 'App\\Models\\VendorBill', 15, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(23, 'JE-VP-VP-00024', '2026-03-26', 'Vendor payment VP-00024', 'App\\Models\\VendorPayment', 8, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(24, 'JE-INV-INV-00001', '2026-09-08', 'Invoice INV-00001', 'App\\Models\\Invoice', 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(25, 'JE-INV-INV-00002', '2026-09-06', 'Invoice INV-00002', 'App\\Models\\Invoice', 2, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(26, 'JE-INV-INV-00003', '2026-09-04', 'Invoice INV-00003', 'App\\Models\\Invoice', 3, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(27, 'JE-INV-INV-00004', '2026-09-02', 'Invoice INV-00004', 'App\\Models\\Invoice', 4, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(28, 'JE-INV-INV-00005', '2026-08-31', 'Invoice INV-00005', 'App\\Models\\Invoice', 5, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(29, 'JE-INV-INV-00006', '2026-08-29', 'Invoice INV-00006', 'App\\Models\\Invoice', 6, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(30, 'JE-INV-INV-00007', '2026-08-27', 'Invoice INV-00007', 'App\\Models\\Invoice', 7, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(31, 'JE-INV-INV-00008', '2026-08-25', 'Invoice INV-00008', 'App\\Models\\Invoice', 8, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(32, 'JE-INV-INV-00009', '2026-08-23', 'Invoice INV-00009', 'App\\Models\\Invoice', 9, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(33, 'JE-INV-INV-00010', '2026-08-21', 'Invoice INV-00010', 'App\\Models\\Invoice', 10, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(34, 'JE-INV-INV-00011', '2026-08-19', 'Invoice INV-00011', 'App\\Models\\Invoice', 11, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(35, 'JE-INV-INV-00012', '2026-08-17', 'Invoice INV-00012', 'App\\Models\\Invoice', 12, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(36, 'JE-INV-INV-00013', '2026-08-15', 'Invoice INV-00013', 'App\\Models\\Invoice', 13, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(37, 'JE-INV-INV-00014', '2026-08-13', 'Invoice INV-00014', 'App\\Models\\Invoice', 14, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(38, 'JE-INV-INV-00015', '2026-08-11', 'Invoice INV-00015', 'App\\Models\\Invoice', 15, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(39, 'JE-INV-INV-00016', '2026-08-09', 'Invoice INV-00016', 'App\\Models\\Invoice', 16, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(40, 'JE-INV-INV-00018', '2026-08-05', 'Invoice INV-00018', 'App\\Models\\Invoice', 18, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(41, 'JE-INV-INV-00019', '2026-08-03', 'Invoice INV-00019', 'App\\Models\\Invoice', 19, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(42, 'JE-INV-INV-00020', '2026-08-01', 'Invoice INV-00020', 'App\\Models\\Invoice', 20, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(43, 'JE-INV-INV-00021', '2026-07-30', 'Invoice INV-00021', 'App\\Models\\Invoice', 21, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(44, 'JE-INV-INV-00022', '2026-07-28', 'Invoice INV-00022', 'App\\Models\\Invoice', 22, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(45, 'JE-INV-INV-00023', '2026-07-26', 'Invoice INV-00023', 'App\\Models\\Invoice', 23, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(46, 'JE-INV-INV-00024', '2026-07-24', 'Invoice INV-00024', 'App\\Models\\Invoice', 24, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(47, 'JE-INV-INV-00025', '2026-07-22', 'Invoice INV-00025', 'App\\Models\\Invoice', 25, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(48, 'JE-INV-INV-00026', '2026-07-20', 'Invoice INV-00026', 'App\\Models\\Invoice', 26, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(49, 'JE-INV-INV-00027', '2026-07-18', 'Invoice INV-00027', 'App\\Models\\Invoice', 27, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(50, 'JE-INV-INV-00028', '2026-07-16', 'Invoice INV-00028', 'App\\Models\\Invoice', 28, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(51, 'JE-INV-INV-00029', '2026-07-14', 'Invoice INV-00029', 'App\\Models\\Invoice', 29, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(52, 'JE-INV-INV-00030', '2026-07-12', 'Invoice INV-00030', 'App\\Models\\Invoice', 30, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(53, 'JE-INV-INV-00031', '2026-07-10', 'Invoice INV-00031', 'App\\Models\\Invoice', 31, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(54, 'JE-INV-INV-00032', '2026-07-08', 'Invoice INV-00032', 'App\\Models\\Invoice', 32, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(55, 'JE-INV-INV-00033', '2026-07-06', 'Invoice INV-00033', 'App\\Models\\Invoice', 33, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(56, 'JE-INV-INV-00035', '2026-07-02', 'Invoice INV-00035', 'App\\Models\\Invoice', 35, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(57, 'JE-INV-INV-00036', '2026-06-30', 'Invoice INV-00036', 'App\\Models\\Invoice', 36, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(58, 'JE-INV-INV-00037', '2026-06-28', 'Invoice INV-00037', 'App\\Models\\Invoice', 37, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(59, 'JE-INV-INV-00038', '2026-06-26', 'Invoice INV-00038', 'App\\Models\\Invoice', 38, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(60, 'JE-INV-INV-00039', '2026-06-24', 'Invoice INV-00039', 'App\\Models\\Invoice', 39, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(61, 'JE-INV-INV-00040', '2026-06-22', 'Invoice INV-00040', 'App\\Models\\Invoice', 40, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(62, 'JE-INV-INV-00041', '2026-06-20', 'Invoice INV-00041', 'App\\Models\\Invoice', 41, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(63, 'JE-INV-INV-00042', '2026-06-18', 'Invoice INV-00042', 'App\\Models\\Invoice', 42, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(64, 'JE-INV-INV-00043', '2026-06-16', 'Invoice INV-00043', 'App\\Models\\Invoice', 43, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(65, 'JE-INV-INV-00044', '2026-06-14', 'Invoice INV-00044', 'App\\Models\\Invoice', 44, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(66, 'JE-INV-INV-00045', '2026-06-12', 'Invoice INV-00045', 'App\\Models\\Invoice', 45, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(67, 'JE-INV-INV-00046', '2026-06-10', 'Invoice INV-00046', 'App\\Models\\Invoice', 46, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(68, 'JE-INV-INV-00047', '2026-06-08', 'Invoice INV-00047', 'App\\Models\\Invoice', 47, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(69, 'JE-INV-INV-00048', '2026-06-06', 'Invoice INV-00048', 'App\\Models\\Invoice', 48, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(70, 'JE-INV-INV-00049', '2026-06-04', 'Invoice INV-00049', 'App\\Models\\Invoice', 49, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(71, 'JE-INV-INV-00050', '2026-06-02', 'Invoice INV-00050', 'App\\Models\\Invoice', 50, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(72, 'JE-INV-INV-00052', '2026-05-29', 'Invoice INV-00052', 'App\\Models\\Invoice', 52, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(73, 'JE-INV-INV-00053', '2026-05-27', 'Invoice INV-00053', 'App\\Models\\Invoice', 53, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(74, 'JE-INV-INV-00054', '2026-05-25', 'Invoice INV-00054', 'App\\Models\\Invoice', 54, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(75, 'JE-INV-INV-00055', '2026-05-23', 'Invoice INV-00055', 'App\\Models\\Invoice', 55, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(76, 'JE-INV-INV-00056', '2026-05-21', 'Invoice INV-00056', 'App\\Models\\Invoice', 56, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(77, 'JE-INV-INV-00057', '2026-05-19', 'Invoice INV-00057', 'App\\Models\\Invoice', 57, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(78, 'JE-INV-INV-00058', '2026-05-17', 'Invoice INV-00058', 'App\\Models\\Invoice', 58, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(79, 'JE-INV-INV-00059', '2026-05-15', 'Invoice INV-00059', 'App\\Models\\Invoice', 59, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(80, 'JE-INV-INV-00060', '2026-05-13', 'Invoice INV-00060', 'App\\Models\\Invoice', 60, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(81, 'JE-INV-INV-00061', '2026-05-11', 'Invoice INV-00061', 'App\\Models\\Invoice', 61, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(82, 'JE-INV-INV-00062', '2026-05-09', 'Invoice INV-00062', 'App\\Models\\Invoice', 62, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(83, 'JE-INV-INV-00063', '2026-05-07', 'Invoice INV-00063', 'App\\Models\\Invoice', 63, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(84, 'JE-INV-INV-00064', '2026-05-05', 'Invoice INV-00064', 'App\\Models\\Invoice', 64, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(85, 'JE-INV-INV-00065', '2026-05-03', 'Invoice INV-00065', 'App\\Models\\Invoice', 65, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(86, 'JE-INV-INV-00066', '2026-05-01', 'Invoice INV-00066', 'App\\Models\\Invoice', 66, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(87, 'JE-INV-INV-00067', '2026-04-29', 'Invoice INV-00067', 'App\\Models\\Invoice', 67, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(88, 'JE-INV-INV-00069', '2026-04-25', 'Invoice INV-00069', 'App\\Models\\Invoice', 69, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(89, 'JE-INV-INV-00070', '2026-04-23', 'Invoice INV-00070', 'App\\Models\\Invoice', 70, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(90, 'JE-INV-INV-00071', '2026-04-21', 'Invoice INV-00071', 'App\\Models\\Invoice', 71, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(91, 'JE-INV-INV-00072', '2026-04-19', 'Invoice INV-00072', 'App\\Models\\Invoice', 72, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(92, 'JE-INV-INV-00073', '2026-04-17', 'Invoice INV-00073', 'App\\Models\\Invoice', 73, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(93, 'JE-INV-INV-00074', '2026-04-15', 'Invoice INV-00074', 'App\\Models\\Invoice', 74, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(94, 'JE-INV-INV-00075', '2026-04-13', 'Invoice INV-00075', 'App\\Models\\Invoice', 75, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(95, 'JE-INV-INV-00076', '2026-04-11', 'Invoice INV-00076', 'App\\Models\\Invoice', 76, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(96, 'JE-INV-INV-00077', '2026-04-09', 'Invoice INV-00077', 'App\\Models\\Invoice', 77, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(97, 'JE-INV-INV-00078', '2026-04-07', 'Invoice INV-00078', 'App\\Models\\Invoice', 78, 1, '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(98, 'JE-INV-INV-00079', '2026-04-05', 'Invoice INV-00079', 'App\\Models\\Invoice', 79, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(99, 'JE-INV-INV-00080', '2026-04-03', 'Invoice INV-00080', 'App\\Models\\Invoice', 80, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(100, 'JE-PMT-PMT-00002', '2026-09-11', 'Payment PMT-00002', 'App\\Models\\Payment', 1, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(101, 'JE-PMT-PMT-00003', '2026-09-09', 'Payment PMT-00003', 'App\\Models\\Payment', 2, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(102, 'JE-PMT-PMT-00005', '2026-09-05', 'Payment PMT-00005', 'App\\Models\\Payment', 3, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(103, 'JE-PMT-PMT-00006', '2026-09-03', 'Payment PMT-00006', 'App\\Models\\Payment', 4, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(104, 'JE-PMT-PMT-00008', '2026-08-30', 'Payment PMT-00008', 'App\\Models\\Payment', 5, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(105, 'JE-PMT-PMT-00009', '2026-08-28', 'Payment PMT-00009', 'App\\Models\\Payment', 6, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(106, 'JE-PMT-PMT-00011', '2026-08-24', 'Payment PMT-00011', 'App\\Models\\Payment', 7, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(107, 'JE-PMT-PMT-00012', '2026-08-22', 'Payment PMT-00012', 'App\\Models\\Payment', 8, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(108, 'JE-PMT-PMT-00014', '2026-08-18', 'Payment PMT-00014', 'App\\Models\\Payment', 9, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(109, 'JE-PMT-PMT-00015', '2026-08-16', 'Payment PMT-00015', 'App\\Models\\Payment', 10, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(110, 'JE-PMT-PMT-00017', '2026-08-10', 'Payment PMT-00017', 'App\\Models\\Payment', 11, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(111, 'JE-PMT-PMT-00018', '2026-08-08', 'Payment PMT-00018', 'App\\Models\\Payment', 12, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(112, 'JE-PMT-PMT-00020', '2026-08-04', 'Payment PMT-00020', 'App\\Models\\Payment', 13, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(113, 'JE-PMT-PMT-00021', '2026-08-02', 'Payment PMT-00021', 'App\\Models\\Payment', 14, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(114, 'JE-PMT-PMT-00023', '2026-07-29', 'Payment PMT-00023', 'App\\Models\\Payment', 15, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(115, 'JE-PMT-PMT-00024', '2026-07-27', 'Payment PMT-00024', 'App\\Models\\Payment', 16, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(116, 'JE-PMT-PMT-00026', '2026-07-23', 'Payment PMT-00026', 'App\\Models\\Payment', 17, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(117, 'JE-PMT-PMT-00027', '2026-07-21', 'Payment PMT-00027', 'App\\Models\\Payment', 18, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(118, 'JE-PMT-PMT-00029', '2026-07-17', 'Payment PMT-00029', 'App\\Models\\Payment', 19, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(119, 'JE-PMT-PMT-00030', '2026-07-15', 'Payment PMT-00030', 'App\\Models\\Payment', 20, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(120, 'JE-PMT-PMT-00032', '2026-07-11', 'Payment PMT-00032', 'App\\Models\\Payment', 21, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(121, 'JE-PMT-PMT-00033', '2026-07-07', 'Payment PMT-00033', 'App\\Models\\Payment', 22, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(122, 'JE-PMT-PMT-00035', '2026-07-03', 'Payment PMT-00035', 'App\\Models\\Payment', 23, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(123, 'JE-PMT-PMT-00036', '2026-07-01', 'Payment PMT-00036', 'App\\Models\\Payment', 24, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(124, 'JE-PMT-PMT-00038', '2026-06-27', 'Payment PMT-00038', 'App\\Models\\Payment', 25, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(125, 'JE-PMT-PMT-00039', '2026-06-25', 'Payment PMT-00039', 'App\\Models\\Payment', 26, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(126, 'JE-PMT-PMT-00041', '2026-06-21', 'Payment PMT-00041', 'App\\Models\\Payment', 27, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(127, 'JE-PMT-PMT-00042', '2026-06-19', 'Payment PMT-00042', 'App\\Models\\Payment', 28, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(128, 'JE-PMT-PMT-00044', '2026-06-15', 'Payment PMT-00044', 'App\\Models\\Payment', 29, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(129, 'JE-PMT-PMT-00045', '2026-06-13', 'Payment PMT-00045', 'App\\Models\\Payment', 30, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(130, 'JE-PMT-PMT-00047', '2026-06-09', 'Payment PMT-00047', 'App\\Models\\Payment', 31, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(131, 'JE-PMT-PMT-00048', '2026-06-07', 'Payment PMT-00048', 'App\\Models\\Payment', 32, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(132, 'JE-PMT-PMT-00050', '2026-06-01', 'Payment PMT-00050', 'App\\Models\\Payment', 33, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(133, 'JE-PMT-PMT-00051', '2026-05-30', 'Payment PMT-00051', 'App\\Models\\Payment', 34, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(134, 'JE-PMT-PMT-00053', '2026-05-26', 'Payment PMT-00053', 'App\\Models\\Payment', 35, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(135, 'JE-PMT-PMT-00054', '2026-05-24', 'Payment PMT-00054', 'App\\Models\\Payment', 36, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(136, 'JE-PMT-PMT-00056', '2026-05-20', 'Payment PMT-00056', 'App\\Models\\Payment', 37, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(137, 'JE-PMT-PMT-00057', '2026-05-18', 'Payment PMT-00057', 'App\\Models\\Payment', 38, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(138, 'JE-PMT-PMT-00059', '2026-05-14', 'Payment PMT-00059', 'App\\Models\\Payment', 39, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(139, 'JE-PMT-PMT-00060', '2026-05-12', 'Payment PMT-00060', 'App\\Models\\Payment', 40, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(140, 'JE-PMT-PMT-00062', '2026-05-08', 'Payment PMT-00062', 'App\\Models\\Payment', 41, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(141, 'JE-PMT-PMT-00063', '2026-05-06', 'Payment PMT-00063', 'App\\Models\\Payment', 42, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(142, 'JE-PMT-PMT-00065', '2026-04-30', 'Payment PMT-00065', 'App\\Models\\Payment', 43, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(143, 'JE-PMT-PMT-00066', '2026-04-28', 'Payment PMT-00066', 'App\\Models\\Payment', 44, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(144, 'JE-PMT-PMT-00068', '2026-04-24', 'Payment PMT-00068', 'App\\Models\\Payment', 45, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(145, 'JE-PMT-PMT-00069', '2026-04-22', 'Payment PMT-00069', 'App\\Models\\Payment', 46, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(146, 'JE-PMT-PMT-00071', '2026-04-18', 'Payment PMT-00071', 'App\\Models\\Payment', 47, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(147, 'JE-PMT-PMT-00072', '2026-04-16', 'Payment PMT-00072', 'App\\Models\\Payment', 48, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(148, 'JE-PMT-PMT-00074', '2026-04-12', 'Payment PMT-00074', 'App\\Models\\Payment', 49, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(149, 'JE-PMT-PMT-00075', '2026-04-10', 'Payment PMT-00075', 'App\\Models\\Payment', 50, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(150, 'JE-DEP-DEP-00001', '2026-09-07', 'Deposit DEP-00001', 'App\\Models\\Deposit', 1, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(151, 'JE-DEP-DEP-00002', '2026-09-04', 'Deposit DEP-00002', 'App\\Models\\Deposit', 2, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(152, 'JE-DEP-DEP-00003', '2026-09-01', 'Deposit DEP-00003', 'App\\Models\\Deposit', 3, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(153, 'JE-CM-CM-00009', '2026-09-10', 'Credit memo CM-00009', 'App\\Models\\CreditMemo', 9, 1, '2026-09-10 15:09:23', '2026-09-10 15:09:23'),
(154, 'JE-CM-CM-00010', '2026-09-10', 'Credit memo CM-00010', 'App\\Models\\CreditMemo', 10, 1, '2026-09-10 15:09:36', '2026-09-10 15:09:36');

-- --------------------------------------------------------

--
-- Table structure for table `journal_lines`
--

CREATE TABLE `journal_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `journal_entry_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `memo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_lines`
--

INSERT INTO `journal_lines` (`id`, `journal_entry_id`, `account_id`, `debit`, `credit`, `memo`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 1446.50, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 1, 7, 0.00, 1446.50, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 2, 7, 723.25, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 2, 2, 0.00, 723.25, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 3, 6, 936.38, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 3, 7, 0.00, 936.38, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(7, 4, 6, 2475.50, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(8, 4, 7, 0.00, 2475.50, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(9, 5, 7, 2475.50, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(10, 5, 2, 0.00, 2475.50, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(11, 6, 6, 1745.30, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(12, 6, 7, 0.00, 1745.30, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(13, 7, 6, 2609.30, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(14, 7, 7, 0.00, 2609.30, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(15, 8, 7, 2609.30, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(16, 8, 2, 0.00, 2609.30, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(17, 9, 6, 1036.18, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(18, 9, 7, 0.00, 1036.18, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(19, 10, 6, 975.66, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(20, 10, 7, 0.00, 975.66, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(21, 11, 7, 975.66, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(22, 11, 2, 0.00, 975.66, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(23, 12, 6, 1248.85, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(24, 12, 7, 0.00, 1248.85, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(25, 13, 6, 1952.35, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(26, 13, 7, 0.00, 1952.35, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(27, 14, 7, 976.18, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(28, 14, 2, 0.00, 976.18, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(29, 15, 6, 1922.80, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(30, 15, 7, 0.00, 1922.80, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(31, 16, 6, 694.96, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(32, 16, 7, 0.00, 694.96, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(33, 17, 7, 347.48, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(34, 17, 2, 0.00, 347.48, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(35, 18, 6, 2509.40, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(36, 18, 7, 0.00, 2509.40, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(37, 19, 6, 2809.75, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(38, 19, 7, 0.00, 2809.75, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(39, 20, 7, 1404.88, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(40, 20, 2, 0.00, 1404.88, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(41, 21, 6, 2624.00, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(42, 21, 7, 0.00, 2624.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(43, 22, 6, 1528.93, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(44, 22, 7, 0.00, 1528.93, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(45, 23, 7, 1528.93, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(46, 23, 2, 0.00, 1528.93, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(47, 24, 5, 308.95, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(48, 24, 10, 0.00, 290.50, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(49, 24, 8, 0.00, 18.45, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(50, 24, 11, 192.50, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(51, 24, 6, 0.00, 192.50, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(52, 25, 5, 1003.57, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(53, 25, 10, 0.00, 943.65, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(54, 25, 8, 0.00, 59.92, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(55, 25, 11, 674.65, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(56, 25, 6, 0.00, 674.65, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(57, 26, 5, 508.35, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(58, 26, 10, 0.00, 478.00, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(59, 26, 8, 0.00, 30.35, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(60, 26, 11, 292.90, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(61, 26, 6, 0.00, 292.90, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(62, 27, 5, 601.51, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(63, 27, 10, 0.00, 565.60, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(64, 27, 8, 0.00, 35.91, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(65, 27, 11, 345.00, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(66, 27, 6, 0.00, 345.00, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(67, 28, 5, 736.95, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(68, 28, 10, 0.00, 692.95, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(69, 28, 8, 0.00, 44.00, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(70, 28, 11, 421.15, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(71, 28, 6, 0.00, 421.15, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(72, 29, 5, 517.71, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(73, 29, 10, 0.00, 486.80, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(74, 29, 8, 0.00, 30.91, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(75, 29, 11, 306.65, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(76, 29, 6, 0.00, 306.65, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(77, 30, 5, 628.74, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(78, 30, 10, 0.00, 591.20, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(79, 30, 8, 0.00, 37.54, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(80, 30, 11, 378.40, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(81, 30, 6, 0.00, 378.40, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(82, 31, 5, 371.90, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(83, 31, 10, 0.00, 349.70, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(84, 31, 8, 0.00, 22.20, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(85, 31, 11, 212.40, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(86, 31, 6, 0.00, 212.40, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(87, 32, 5, 260.88, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(88, 32, 10, 0.00, 245.30, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(89, 32, 8, 0.00, 15.58, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(90, 32, 11, 147.60, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(91, 32, 6, 0.00, 147.60, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(92, 33, 5, 612.41, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(93, 33, 10, 0.00, 575.85, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(94, 33, 8, 0.00, 36.56, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(95, 33, 11, 357.05, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(96, 33, 6, 0.00, 357.05, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(97, 34, 5, 481.44, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(98, 34, 10, 0.00, 452.70, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(99, 34, 8, 0.00, 28.74, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(100, 34, 11, 271.90, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(101, 34, 6, 0.00, 271.90, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(102, 35, 5, 991.88, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(103, 35, 10, 0.00, 932.65, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(104, 35, 8, 0.00, 59.23, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(105, 35, 11, 650.70, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(106, 35, 6, 0.00, 650.70, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(107, 36, 5, 246.36, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(108, 36, 10, 0.00, 231.65, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(109, 36, 8, 0.00, 14.71, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(110, 36, 11, 137.85, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(111, 36, 6, 0.00, 137.85, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(112, 37, 5, 583.81, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(113, 37, 10, 0.00, 548.95, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(114, 37, 8, 0.00, 34.86, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(115, 37, 11, 336.90, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(116, 37, 6, 0.00, 336.90, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(117, 38, 5, 1370.58, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(118, 38, 10, 0.00, 1288.75, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(119, 38, 8, 0.00, 81.83, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(120, 38, 11, 836.00, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(121, 38, 6, 0.00, 836.00, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(122, 39, 5, 715.47, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(123, 39, 10, 0.00, 672.75, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(124, 39, 8, 0.00, 42.72, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(125, 39, 11, 449.60, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(126, 39, 6, 0.00, 449.60, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(127, 40, 5, 425.19, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(128, 40, 10, 0.00, 399.80, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(129, 40, 8, 0.00, 25.39, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(130, 40, 11, 244.60, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(131, 40, 6, 0.00, 244.60, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(132, 41, 5, 518.98, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(133, 41, 10, 0.00, 488.00, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(134, 41, 8, 0.00, 30.98, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(135, 41, 11, 311.50, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(136, 41, 6, 0.00, 311.50, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(137, 42, 5, 669.69, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(138, 42, 10, 0.00, 629.70, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(139, 42, 8, 0.00, 39.99, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(140, 42, 11, 381.25, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(141, 42, 6, 0.00, 381.25, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(142, 43, 5, 1094.60, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(143, 43, 10, 0.00, 1029.25, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(144, 43, 8, 0.00, 65.35, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(145, 43, 11, 662.75, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(146, 43, 6, 0.00, 662.75, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(147, 44, 5, 569.51, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(148, 44, 10, 0.00, 535.50, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(149, 44, 8, 0.00, 34.01, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(150, 44, 11, 345.20, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(151, 44, 6, 0.00, 345.20, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(152, 45, 5, 296.66, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(153, 45, 10, 0.00, 278.95, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(154, 45, 8, 0.00, 17.71, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(155, 45, 11, 181.65, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(156, 45, 6, 0.00, 181.65, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(157, 46, 5, 439.86, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(158, 46, 10, 0.00, 413.60, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(159, 46, 8, 0.00, 26.26, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(160, 46, 11, 278.70, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(161, 46, 6, 0.00, 278.70, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(162, 47, 5, 818.84, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(163, 47, 10, 0.00, 769.95, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(164, 47, 8, 0.00, 48.89, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(165, 47, 11, 512.50, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(166, 47, 6, 0.00, 512.50, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(167, 48, 5, 785.61, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(168, 48, 10, 0.00, 738.70, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(169, 48, 8, 0.00, 46.91, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(170, 48, 11, 488.85, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(171, 48, 6, 0.00, 488.85, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(172, 49, 5, 224.61, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(173, 49, 10, 0.00, 211.20, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(174, 49, 8, 0.00, 13.41, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(175, 49, 11, 132.80, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(176, 49, 6, 0.00, 132.80, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(177, 50, 5, 1220.00, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(178, 50, 10, 0.00, 1147.15, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(179, 50, 8, 0.00, 72.85, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(180, 50, 11, 764.55, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(181, 50, 6, 0.00, 764.55, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(182, 51, 5, 729.99, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(183, 51, 10, 0.00, 686.40, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(184, 51, 8, 0.00, 43.59, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(185, 51, 11, 462.00, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(186, 51, 6, 0.00, 462.00, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(187, 52, 5, 238.44, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(188, 52, 10, 0.00, 224.20, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(189, 52, 8, 0.00, 14.24, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(190, 52, 11, 147.40, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(191, 52, 6, 0.00, 147.40, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(192, 53, 5, 612.79, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(193, 53, 10, 0.00, 576.20, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(194, 53, 8, 0.00, 36.59, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(195, 53, 11, 363.30, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(196, 53, 6, 0.00, 363.30, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(197, 54, 5, 567.54, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(198, 54, 10, 0.00, 533.65, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(199, 54, 8, 0.00, 33.89, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(200, 54, 11, 342.25, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(201, 54, 6, 0.00, 342.25, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(202, 55, 5, 235.41, 0.00, 'AR', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(203, 55, 10, 0.00, 221.35, 'Sales', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(204, 55, 8, 0.00, 14.06, 'Sales Tax', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(205, 55, 11, 140.60, 0.00, 'COGS', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(206, 55, 6, 0.00, 140.60, 'Inventory', '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(207, 56, 5, 585.83, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(208, 56, 10, 0.00, 550.85, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(209, 56, 8, 0.00, 34.98, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(210, 56, 11, 341.75, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(211, 56, 6, 0.00, 341.75, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(212, 57, 5, 1000.60, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(213, 57, 10, 0.00, 940.85, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(214, 57, 8, 0.00, 59.75, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(215, 57, 11, 592.20, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(216, 57, 6, 0.00, 592.20, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(217, 58, 5, 406.00, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(218, 58, 10, 0.00, 381.75, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(219, 58, 8, 0.00, 24.25, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(220, 58, 11, 227.50, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(221, 58, 6, 0.00, 227.50, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(222, 59, 5, 954.18, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(223, 59, 10, 0.00, 897.20, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(224, 59, 8, 0.00, 56.98, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(225, 59, 11, 597.10, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(226, 59, 6, 0.00, 597.10, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(227, 60, 5, 626.09, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(228, 60, 10, 0.00, 588.70, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(229, 60, 8, 0.00, 37.39, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(230, 60, 11, 373.60, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(231, 60, 6, 0.00, 373.60, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(232, 61, 5, 261.84, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(233, 61, 10, 0.00, 246.20, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(234, 61, 8, 0.00, 15.64, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(235, 61, 11, 143.80, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(236, 61, 6, 0.00, 143.80, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(237, 62, 5, 296.30, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(238, 62, 10, 0.00, 278.60, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(239, 62, 8, 0.00, 17.70, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(240, 62, 11, 196.40, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(241, 62, 6, 0.00, 196.40, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(242, 63, 5, 249.07, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(243, 63, 10, 0.00, 234.20, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(244, 63, 8, 0.00, 14.87, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(245, 63, 11, 137.15, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(246, 63, 6, 0.00, 137.15, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(247, 64, 5, 384.72, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(248, 64, 10, 0.00, 361.75, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(249, 64, 8, 0.00, 22.97, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(250, 64, 11, 214.00, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(251, 64, 6, 0.00, 214.00, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(252, 65, 5, 274.97, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(253, 65, 10, 0.00, 258.55, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(254, 65, 8, 0.00, 16.42, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(255, 65, 11, 164.10, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(256, 65, 6, 0.00, 164.10, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(257, 66, 5, 801.40, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(258, 66, 10, 0.00, 753.55, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(259, 66, 8, 0.00, 47.85, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(260, 66, 11, 472.30, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(261, 66, 6, 0.00, 472.30, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(262, 67, 5, 215.84, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(263, 67, 10, 0.00, 202.95, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(264, 67, 8, 0.00, 12.89, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(265, 67, 11, 128.20, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(266, 67, 6, 0.00, 128.20, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(267, 68, 5, 978.74, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(268, 68, 10, 0.00, 920.30, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(269, 68, 8, 0.00, 58.44, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(270, 68, 11, 566.80, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(271, 68, 6, 0.00, 566.80, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(272, 69, 5, 404.77, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(273, 69, 10, 0.00, 380.60, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(274, 69, 8, 0.00, 24.17, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(275, 69, 11, 255.45, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(276, 69, 6, 0.00, 255.45, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(277, 70, 5, 777.79, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(278, 70, 10, 0.00, 731.35, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(279, 70, 8, 0.00, 46.44, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(280, 70, 11, 453.80, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(281, 70, 6, 0.00, 453.80, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(282, 71, 5, 475.33, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(283, 71, 10, 0.00, 446.95, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(284, 71, 8, 0.00, 28.38, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(285, 71, 11, 292.15, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(286, 71, 6, 0.00, 292.15, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(287, 72, 5, 368.56, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(288, 72, 10, 0.00, 346.55, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(289, 72, 8, 0.00, 22.01, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(290, 72, 11, 244.60, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(291, 72, 6, 0.00, 244.60, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(292, 73, 5, 516.18, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(293, 73, 10, 0.00, 485.35, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(294, 73, 8, 0.00, 30.83, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(295, 73, 11, 297.10, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(296, 73, 6, 0.00, 297.10, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(297, 74, 5, 376.48, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(298, 74, 10, 0.00, 354.00, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(299, 74, 8, 0.00, 22.48, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(300, 74, 11, 212.55, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(301, 74, 6, 0.00, 212.55, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(302, 75, 5, 897.21, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(303, 75, 10, 0.00, 843.65, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(304, 75, 8, 0.00, 53.56, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(305, 75, 11, 538.90, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(306, 75, 6, 0.00, 538.90, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(307, 76, 5, 461.40, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(308, 76, 10, 0.00, 433.85, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(309, 76, 8, 0.00, 27.55, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(310, 76, 11, 274.20, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(311, 76, 6, 0.00, 274.20, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(312, 77, 5, 633.95, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(313, 77, 10, 0.00, 596.10, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(314, 77, 8, 0.00, 37.85, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(315, 77, 11, 371.70, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(316, 77, 6, 0.00, 371.70, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(317, 78, 5, 455.39, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(318, 78, 10, 0.00, 428.20, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(319, 78, 8, 0.00, 27.19, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(320, 78, 11, 263.15, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(321, 78, 6, 0.00, 263.15, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(322, 79, 5, 1601.21, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(323, 79, 10, 0.00, 1505.60, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(324, 79, 8, 0.00, 95.61, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(325, 79, 11, 1024.00, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(326, 79, 6, 0.00, 1024.00, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(327, 80, 5, 387.17, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(328, 80, 10, 0.00, 364.05, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(329, 80, 8, 0.00, 23.12, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(330, 80, 11, 238.35, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(331, 80, 6, 0.00, 238.35, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(332, 81, 5, 874.20, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(333, 81, 10, 0.00, 822.00, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(334, 81, 8, 0.00, 52.20, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(335, 81, 11, 572.80, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(336, 81, 6, 0.00, 572.80, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(337, 82, 5, 615.70, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(338, 82, 10, 0.00, 578.95, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(339, 82, 8, 0.00, 36.75, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(340, 82, 11, 367.65, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(341, 82, 6, 0.00, 367.65, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(342, 83, 5, 607.21, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(343, 83, 10, 0.00, 570.95, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(344, 83, 8, 0.00, 36.26, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(345, 83, 11, 356.10, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(346, 83, 6, 0.00, 356.10, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(347, 84, 5, 305.70, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(348, 84, 10, 0.00, 287.45, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(349, 84, 8, 0.00, 18.25, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(350, 84, 11, 176.95, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(351, 84, 6, 0.00, 176.95, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(352, 85, 5, 644.01, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(353, 85, 10, 0.00, 605.55, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(354, 85, 8, 0.00, 38.46, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(355, 85, 11, 374.80, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(356, 85, 6, 0.00, 374.80, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(357, 86, 5, 1071.00, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(358, 86, 10, 0.00, 1007.05, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(359, 86, 8, 0.00, 63.95, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(360, 86, 11, 640.80, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(361, 86, 6, 0.00, 640.80, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(362, 87, 5, 854.74, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(363, 87, 10, 0.00, 803.70, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(364, 87, 8, 0.00, 51.04, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(365, 87, 11, 540.20, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(366, 87, 6, 0.00, 540.20, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(367, 88, 5, 620.24, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(368, 88, 10, 0.00, 583.20, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(369, 88, 8, 0.00, 37.04, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(370, 88, 11, 379.50, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(371, 88, 6, 0.00, 379.50, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(372, 89, 5, 759.71, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(373, 89, 10, 0.00, 714.35, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(374, 89, 8, 0.00, 45.36, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(375, 89, 11, 450.75, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(376, 89, 6, 0.00, 450.75, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(377, 90, 5, 978.28, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(378, 90, 10, 0.00, 919.85, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(379, 90, 8, 0.00, 58.43, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(380, 90, 11, 579.40, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(381, 90, 6, 0.00, 579.40, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(382, 91, 5, 703.62, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(383, 91, 10, 0.00, 661.60, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(384, 91, 8, 0.00, 42.02, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(385, 91, 11, 407.40, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(386, 91, 6, 0.00, 407.40, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(387, 92, 5, 600.24, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(388, 92, 10, 0.00, 564.40, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(389, 92, 8, 0.00, 35.84, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(390, 92, 11, 385.60, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(391, 92, 6, 0.00, 385.60, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(392, 93, 5, 495.16, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(393, 93, 10, 0.00, 465.60, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(394, 93, 8, 0.00, 29.56, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(395, 93, 11, 305.20, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(396, 93, 6, 0.00, 305.20, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(397, 94, 5, 832.40, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(398, 94, 10, 0.00, 782.70, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(399, 94, 8, 0.00, 49.70, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(400, 94, 11, 508.05, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(401, 94, 6, 0.00, 508.05, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(402, 95, 5, 456.29, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(403, 95, 10, 0.00, 429.05, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(404, 95, 8, 0.00, 27.24, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(405, 95, 11, 266.85, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(406, 95, 6, 0.00, 266.85, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(407, 96, 5, 666.28, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(408, 96, 10, 0.00, 626.50, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(409, 96, 8, 0.00, 39.78, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(410, 96, 11, 394.80, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(411, 96, 6, 0.00, 394.80, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(412, 97, 5, 957.63, 0.00, 'AR', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(413, 97, 10, 0.00, 900.45, 'Sales', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(414, 97, 8, 0.00, 57.18, 'Sales Tax', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(415, 97, 11, 596.00, 0.00, 'COGS', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(416, 97, 6, 0.00, 596.00, 'Inventory', '2026-09-10 11:00:47', '2026-09-10 11:00:47'),
(417, 98, 5, 220.41, 0.00, 'AR', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(418, 98, 10, 0.00, 207.25, 'Sales', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(419, 98, 8, 0.00, 13.16, 'Sales Tax', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(420, 98, 11, 123.10, 0.00, 'COGS', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(421, 98, 6, 0.00, 123.10, 'Inventory', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(422, 99, 5, 1134.22, 0.00, 'AR', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(423, 99, 10, 0.00, 1066.50, 'Sales', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(424, 99, 8, 0.00, 67.72, 'Sales Tax', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(425, 99, 11, 701.65, 0.00, 'COGS', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(426, 99, 6, 0.00, 701.65, 'Inventory', '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(427, 100, 4, 401.43, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(428, 100, 5, 0.00, 401.43, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(429, 101, 4, 508.35, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(430, 101, 5, 0.00, 508.35, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(431, 102, 4, 736.95, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(432, 102, 5, 0.00, 736.95, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(433, 103, 4, 207.08, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(434, 103, 5, 0.00, 207.08, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(435, 104, 4, 148.76, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(436, 104, 5, 0.00, 148.76, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(437, 105, 4, 260.88, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(438, 105, 5, 0.00, 260.88, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(439, 106, 4, 481.44, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(440, 106, 5, 0.00, 481.44, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(441, 107, 4, 396.75, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(442, 107, 5, 0.00, 396.75, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(443, 108, 4, 233.52, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(444, 108, 5, 0.00, 233.52, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(445, 109, 4, 1370.58, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(446, 109, 5, 0.00, 1370.58, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(447, 110, 4, 425.19, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(448, 110, 5, 0.00, 425.19, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(449, 111, 4, 207.59, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(450, 111, 5, 0.00, 207.59, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(451, 112, 4, 437.84, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(452, 112, 5, 0.00, 437.84, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(453, 113, 4, 569.51, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(454, 113, 5, 0.00, 569.51, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(455, 114, 4, 439.86, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(456, 114, 5, 0.00, 439.86, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(457, 115, 4, 327.54, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(458, 115, 5, 0.00, 327.54, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(459, 116, 4, 89.84, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(460, 116, 5, 0.00, 89.84, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(461, 117, 4, 1220.00, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(462, 117, 5, 0.00, 1220.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(463, 118, 4, 238.44, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(464, 118, 5, 0.00, 238.44, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(465, 119, 4, 245.12, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(466, 119, 5, 0.00, 245.12, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(467, 120, 4, 94.16, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(468, 120, 5, 0.00, 94.16, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(469, 121, 4, 585.83, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(470, 121, 5, 0.00, 585.83, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(471, 122, 4, 406.00, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(472, 122, 5, 0.00, 406.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(473, 123, 4, 381.67, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(474, 123, 5, 0.00, 381.67, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(475, 124, 4, 104.74, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(476, 124, 5, 0.00, 104.74, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(477, 125, 4, 296.30, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(478, 125, 5, 0.00, 296.30, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(479, 126, 4, 384.72, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(480, 126, 5, 0.00, 384.72, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(481, 127, 4, 109.99, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(482, 127, 5, 0.00, 109.99, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(483, 128, 4, 86.34, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(484, 128, 5, 0.00, 86.34, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(485, 129, 4, 978.74, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(486, 129, 5, 0.00, 978.74, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(487, 130, 4, 777.79, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(488, 130, 5, 0.00, 777.79, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(489, 131, 4, 190.13, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(490, 131, 5, 0.00, 190.13, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(491, 132, 4, 206.47, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(492, 132, 5, 0.00, 206.47, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(493, 133, 4, 376.48, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(494, 133, 5, 0.00, 376.48, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(495, 134, 4, 461.40, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(496, 134, 5, 0.00, 461.40, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(497, 135, 4, 253.58, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(498, 135, 5, 0.00, 253.58, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(499, 136, 4, 640.48, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(500, 136, 5, 0.00, 640.48, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(501, 137, 4, 387.17, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(502, 137, 5, 0.00, 387.17, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(503, 138, 4, 615.70, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(504, 138, 5, 0.00, 615.70, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(505, 139, 4, 242.88, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(506, 139, 5, 0.00, 242.88, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(507, 140, 4, 257.60, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(508, 140, 5, 0.00, 257.60, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(509, 141, 4, 1071.00, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(510, 141, 5, 0.00, 1071.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(511, 142, 4, 620.24, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(512, 142, 5, 0.00, 620.24, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(513, 143, 4, 303.88, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(514, 143, 5, 0.00, 303.88, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(515, 144, 4, 281.45, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(516, 144, 5, 0.00, 281.45, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(517, 145, 4, 600.24, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(518, 145, 5, 0.00, 600.24, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(519, 146, 4, 832.40, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(520, 146, 5, 0.00, 832.40, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(521, 147, 4, 182.52, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(522, 147, 5, 0.00, 182.52, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(523, 148, 4, 383.05, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(524, 148, 5, 0.00, 383.05, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(525, 149, 4, 220.41, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(526, 149, 5, 0.00, 220.41, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(527, 150, 2, 2002.57, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(528, 150, 4, 0.00, 2002.57, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(529, 151, 2, 2743.17, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(530, 151, 4, 0.00, 2743.17, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(531, 152, 2, 2079.99, 0.00, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(532, 152, 4, 0.00, 2079.99, NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(533, 153, 10, 161.35, 0.00, 'Sales returns', '2026-09-10 15:09:23', '2026-09-10 15:09:23'),
(534, 153, 5, 0.00, 161.35, 'AR credit', '2026-09-10 15:09:23', '2026-09-10 15:09:23'),
(535, 154, 10, 54.15, 0.00, 'Sales returns', '2026-09-10 15:09:36', '2026-09-10 15:09:36'),
(536, 154, 5, 0.00, 54.15, 'AR credit', '2026-09-10 15:09:36', '2026-09-10 15:09:36');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_10_100000_create_roles_and_permissions_tables', 1),
(5, '2026_09_10_100100_create_settings_table', 1),
(6, '2026_09_10_162635_create_master_data_tables', 1),
(7, '2026_09_10_220000_create_erp_transaction_tables', 1),
(8, '2026_09_10_173019_add_barcode_to_items_table', 2),
(9, '2026_09_11_120000_add_cleared_at_to_deposits_and_checks_tables', 3),
(10, '2026_09_11_130000_create_credit_memo_applications_tables', 4),
(11, '2026_09_11_140000_add_document_presentation_fields_to_sales_tables', 5),
(13, '2026_09_11_150000_create_qb_import_tables', 6),
(14, '2026_09_15_174419_create_item_notes_table', 7),
(15, '2026_09_17_141429_create_item_histories_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `unapplied_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'check',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_to_account_id` bigint UNSIGNED DEFAULT NULL,
  `deposited` tinyint(1) NOT NULL DEFAULT '0',
  `memo` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_number`, `customer_id`, `payment_date`, `amount`, `unapplied_amount`, `method`, `reference`, `deposit_to_account_id`, `deposited`, `memo`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PMT-00002', 2, '2026-09-11', 401.43, 0.00, 'cash', 'REF-7001', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(2, 'PMT-00003', 3, '2026-09-09', 508.35, 0.00, 'check', 'REF-7002', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(3, 'PMT-00005', 5, '2026-09-05', 736.95, 0.00, 'check', 'REF-7004', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(4, 'PMT-00006', 6, '2026-09-03', 207.08, 0.00, 'cash', 'REF-7005', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(5, 'PMT-00008', 8, '2026-08-30', 148.76, 0.00, 'cash', 'REF-7007', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(6, 'PMT-00009', 9, '2026-08-28', 260.88, 0.00, 'check', 'REF-7008', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(7, 'PMT-00011', 11, '2026-08-24', 481.44, 0.00, 'check', 'REF-7010', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(8, 'PMT-00012', 12, '2026-08-22', 396.75, 0.00, 'cash', 'REF-7011', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(9, 'PMT-00014', 14, '2026-08-18', 233.52, 0.00, 'cash', 'REF-7013', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(10, 'PMT-00015', 15, '2026-08-16', 1370.58, 0.00, 'check', 'REF-7014', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(11, 'PMT-00017', 18, '2026-08-10', 425.19, 0.00, 'check', 'REF-7016', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(12, 'PMT-00018', 19, '2026-08-08', 207.59, 0.00, 'cash', 'REF-7017', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(13, 'PMT-00020', 21, '2026-08-04', 437.84, 0.00, 'cash', 'REF-7019', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(14, 'PMT-00021', 22, '2026-08-02', 569.51, 0.00, 'check', 'REF-7020', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(15, 'PMT-00023', 24, '2026-07-29', 439.86, 0.00, 'check', 'REF-7022', 4, 1, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(16, 'PMT-00024', 25, '2026-07-27', 327.54, 0.00, 'cash', 'REF-7023', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(17, 'PMT-00026', 27, '2026-07-23', 89.84, 0.00, 'cash', 'REF-7025', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(18, 'PMT-00027', 28, '2026-07-21', 1220.00, 0.00, 'check', 'REF-7026', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(19, 'PMT-00029', 30, '2026-07-17', 238.44, 0.00, 'check', 'REF-7028', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(20, 'PMT-00030', 31, '2026-07-15', 245.12, 0.00, 'cash', 'REF-7029', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(21, 'PMT-00032', 33, '2026-07-11', 94.16, 0.00, 'cash', 'REF-7031', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(22, 'PMT-00033', 35, '2026-07-07', 585.83, 0.00, 'check', 'REF-7032', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(23, 'PMT-00035', 37, '2026-07-03', 406.00, 0.00, 'check', 'REF-7034', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(24, 'PMT-00036', 38, '2026-07-01', 381.67, 0.00, 'cash', 'REF-7035', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(25, 'PMT-00038', 40, '2026-06-27', 104.74, 0.00, 'cash', 'REF-7037', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(26, 'PMT-00039', 41, '2026-06-25', 296.30, 0.00, 'check', 'REF-7038', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(27, 'PMT-00041', 43, '2026-06-21', 384.72, 0.00, 'check', 'REF-7040', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(28, 'PMT-00042', 44, '2026-06-19', 109.99, 0.00, 'cash', 'REF-7041', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(29, 'PMT-00044', 2, '2026-06-15', 86.34, 0.00, 'cash', 'REF-7043', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(30, 'PMT-00045', 3, '2026-06-13', 978.74, 0.00, 'check', 'REF-7044', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(31, 'PMT-00047', 5, '2026-06-09', 777.79, 0.00, 'check', 'REF-7046', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(32, 'PMT-00048', 6, '2026-06-07', 190.13, 0.00, 'cash', 'REF-7047', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(33, 'PMT-00050', 9, '2026-06-01', 206.47, 0.00, 'cash', 'REF-7049', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(34, 'PMT-00051', 10, '2026-05-30', 376.48, 0.00, 'check', 'REF-7050', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(35, 'PMT-00053', 12, '2026-05-26', 461.40, 0.00, 'check', 'REF-7052', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(36, 'PMT-00054', 13, '2026-05-24', 253.58, 0.00, 'cash', 'REF-7053', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(37, 'PMT-00056', 15, '2026-05-20', 640.48, 0.00, 'cash', 'REF-7055', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(38, 'PMT-00057', 16, '2026-05-18', 387.17, 0.00, 'check', 'REF-7056', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(39, 'PMT-00059', 18, '2026-05-14', 615.70, 0.00, 'check', 'REF-7058', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(40, 'PMT-00060', 19, '2026-05-12', 242.88, 0.00, 'cash', 'REF-7059', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(41, 'PMT-00062', 21, '2026-05-08', 257.60, 0.00, 'cash', 'REF-7061', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(42, 'PMT-00063', 22, '2026-05-06', 1071.00, 0.00, 'check', 'REF-7062', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(43, 'PMT-00065', 25, '2026-04-30', 620.24, 0.00, 'check', 'REF-7064', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(44, 'PMT-00066', 26, '2026-04-28', 303.88, 0.00, 'cash', 'REF-7065', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(45, 'PMT-00068', 28, '2026-04-24', 281.45, 0.00, 'cash', 'REF-7067', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(46, 'PMT-00069', 29, '2026-04-22', 600.24, 0.00, 'check', 'REF-7068', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(47, 'PMT-00071', 31, '2026-04-18', 832.40, 0.00, 'check', 'REF-7070', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(48, 'PMT-00072', 32, '2026-04-16', 182.52, 0.00, 'cash', 'REF-7071', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(49, 'PMT-00074', 34, '2026-04-12', 383.05, 0.00, 'cash', 'REF-7073', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(50, 'PMT-00075', 35, '2026-04-10', 220.41, 0.00, 'check', 'REF-7074', 4, 0, NULL, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `payment_allocations`
--

CREATE TABLE `payment_allocations` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_allocations`
--

INSERT INTO `payment_allocations` (`id`, `payment_id`, `invoice_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 401.43, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(2, 2, 3, 508.35, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(3, 3, 5, 736.95, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(4, 4, 6, 207.08, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(5, 5, 8, 148.76, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(6, 6, 9, 260.88, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(7, 7, 11, 481.44, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(8, 8, 12, 396.75, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(9, 9, 14, 233.52, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(10, 10, 15, 1370.58, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(11, 11, 18, 425.19, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(12, 12, 19, 207.59, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(13, 13, 21, 437.84, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(14, 14, 22, 569.51, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(15, 15, 24, 439.86, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(16, 16, 25, 327.54, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(17, 17, 27, 89.84, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(18, 18, 28, 1220.00, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(19, 19, 30, 238.44, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(20, 20, 31, 245.12, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(21, 21, 33, 94.16, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(22, 22, 35, 585.83, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(23, 23, 37, 406.00, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(24, 24, 38, 381.67, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(25, 25, 40, 104.74, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(26, 26, 41, 296.30, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(27, 27, 43, 384.72, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(28, 28, 44, 109.99, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(29, 29, 46, 86.34, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(30, 30, 47, 978.74, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(31, 31, 49, 777.79, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(32, 32, 50, 190.13, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(33, 33, 53, 206.47, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(34, 34, 54, 376.48, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(35, 35, 56, 461.40, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(36, 36, 57, 253.58, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(37, 37, 59, 640.48, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(38, 38, 60, 387.17, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(39, 39, 62, 615.70, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(40, 40, 63, 242.88, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(41, 41, 65, 257.60, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(42, 42, 66, 1071.00, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(43, 43, 69, 620.24, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(44, 44, 70, 303.88, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(45, 45, 72, 281.45, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(46, 46, 73, 600.24, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(47, 47, 75, 832.40, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(48, 48, 76, 182.52, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(49, 49, 78, 383.05, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(50, 50, 79, 220.41, '2026-09-10 11:00:48', '2026-09-10 11:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `label`, `group`, `created_at`, `updated_at`) VALUES
(1, 'invoice.view', 'View Invoices', 'invoices', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(2, 'invoice.create', 'Create Invoices', 'invoices', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(3, 'invoice.edit', 'Edit Invoices', 'invoices', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(4, 'invoice.delete', 'Delete Invoices', 'invoices', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(5, 'invoice.void', 'Void Invoices', 'invoices', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(6, 'payment.view', 'View Payments', 'payments', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(7, 'payment.create', 'Create Payments', 'payments', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(8, 'customer.view', 'View Customers', 'customers', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(9, 'customer.manage', 'Manage Customers', 'customers', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(10, 'vendor.view', 'View Vendors', 'vendors', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(11, 'vendor.manage', 'Manage Vendors', 'vendors', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(12, 'item.view', 'View Items', 'items', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(13, 'item.manage', 'Manage Items', 'items', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(14, 'inventory.view', 'View Inventory', 'inventory', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(15, 'inventory.adjust', 'Adjust Inventory', 'inventory', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(16, 'inventory.override', 'Override Inventory Policy', 'inventory', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(17, 'purchase.view', 'View Purchasing', 'purchasing', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(18, 'purchase.create', 'Create Purchasing Docs', 'purchasing', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(19, 'accounting.view', 'View Accounting', 'accounting', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(20, 'accounting.manage', 'Manage Accounting', 'accounting', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(21, 'banking.view', 'View Banking', 'banking', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(22, 'banking.manage', 'Manage Banking', 'banking', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(23, 'report.view', 'View Reports', 'reports', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(24, 'report.export', 'Export Reports', 'reports', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(25, 'settings.manage', 'Manage Settings', 'settings', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(26, 'dashboard.view', 'View Dashboard', 'dashboard', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(27, 'audit.view', 'View Audit Log', 'audit', '2026-09-10 13:28:43', '2026-09-10 13:28:43'),
(28, 'import.manage', 'Manage QB Import', 'import', '2026-09-10 14:36:05', '2026-09-10 14:36:05');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 20, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(2, 19, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(3, 22, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(4, 21, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(5, 9, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(6, 8, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(7, 26, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(8, 15, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(9, 16, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(10, 14, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(11, 2, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(12, 4, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(13, 3, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(14, 1, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(15, 5, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(16, 13, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(17, 12, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(18, 7, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(19, 6, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(20, 18, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(21, 17, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(22, 24, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(23, 23, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(24, 25, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(25, 11, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(26, 10, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(27, 19, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(28, 22, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(29, 21, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(30, 9, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(31, 8, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(32, 26, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(33, 15, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(34, 16, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(35, 14, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(36, 2, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(37, 3, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(38, 1, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(39, 5, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(40, 13, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(41, 12, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(42, 7, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(43, 6, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(44, 18, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(45, 17, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(46, 24, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(47, 23, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(48, 11, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(49, 10, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(50, 9, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(51, 8, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(52, 26, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(53, 14, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(54, 2, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(55, 3, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(56, 1, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(57, 12, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(58, 7, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(59, 6, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(60, 23, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(61, 20, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(62, 19, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(63, 22, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(64, 21, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(65, 8, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(66, 26, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(67, 14, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(68, 1, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(69, 12, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(70, 7, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(71, 6, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(72, 17, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(73, 24, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(74, 23, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(75, 10, 4, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(76, 27, 1, '2026-09-10 13:28:43', '2026-09-10 13:28:43'),
(77, 27, 2, '2026-09-10 13:28:43', '2026-09-10 13:28:43'),
(78, 27, 4, '2026-09-10 13:28:43', '2026-09-10 13:28:43'),
(79, 28, 1, '2026-09-10 14:36:05', '2026-09-10 14:36:05');

-- --------------------------------------------------------

--
-- Table structure for table `price_levels`
--

CREATE TABLE `price_levels` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adjustment_percent` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_levels`
--

INSERT INTO `price_levels` (`id`, `name`, `description`, `adjustment_percent`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Wholesale A', 'Primary wholesale tier', 0.0000, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 'Wholesale B', 'Volume wholesale tier', -3.0000, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, 'Retail', 'Retail counter pricing', 12.0000, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, 'Special Customer', 'Negotiated special pricing', -5.0000, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(5, 'Zero', 'No adjustment', 0.0000, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `order_date` date NOT NULL,
  `expected_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `memo` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `number`, `vendor_id`, `order_date`, `expected_date`, `status`, `subtotal`, `total`, `memo`, `created_at`, `updated_at`) VALUES
(1, 'PO-00001', 1, '2026-08-14', '2026-08-21', 'draft', 4203.35, 4203.35, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 'PO-00002', 2, '2026-08-07', '2026-08-14', 'received', 1446.50, 1446.50, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 'PO-00003', 3, '2026-07-31', '2026-08-07', 'partial', 1872.75, 1872.75, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 'PO-00004', 4, '2026-07-24', '2026-07-31', 'received', 2475.50, 2475.50, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 'PO-00005', 5, '2026-07-17', '2026-07-24', 'cancelled', 2830.20, 2830.20, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 'PO-00006', 6, '2026-07-10', '2026-07-17', 'draft', 2011.20, 2011.20, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(7, 'PO-00007', 7, '2026-07-03', '2026-07-10', 'received', 1745.30, 1745.30, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(8, 'PO-00008', 8, '2026-06-26', '2026-07-03', 'received', 2609.30, 2609.30, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(9, 'PO-00009', 9, '2026-06-19', '2026-06-26', 'partial', 2072.35, 2072.35, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(10, 'PO-00010', 10, '2026-06-12', '2026-06-19', 'cancelled', 2972.20, 2972.20, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(11, 'PO-00011', 11, '2026-06-05', '2026-06-12', 'draft', 4009.25, 4009.25, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(12, 'PO-00012', 12, '2026-05-29', '2026-06-05', 'partial', 1951.30, 1951.30, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(13, 'PO-00013', 13, '2026-05-22', '2026-05-29', 'received', 1248.85, 1248.85, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(14, 'PO-00014', 14, '2026-05-15', '2026-05-22', 'received', 1952.35, 1952.35, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(15, 'PO-00015', 1, '2026-05-08', '2026-05-15', 'cancelled', 2932.70, 2932.70, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(16, 'PO-00016', 2, '2026-05-01', '2026-05-08', 'draft', 1787.20, 1787.20, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(17, 'PO-00017', 3, '2026-04-24', '2026-05-01', 'received', 1922.80, 1922.80, 'Demo purchase order', '2026-09-10 11:00:44', '2026-09-10 11:00:45'),
(18, 'PO-00018', 4, '2026-04-17', '2026-04-24', 'partial', 1389.90, 1389.90, 'Demo purchase order', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(19, 'PO-00019', 5, '2026-04-10', '2026-04-17', 'received', 2509.40, 2509.40, 'Demo purchase order', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(20, 'PO-00020', 6, '2026-04-03', '2026-04-10', 'cancelled', 2321.00, 2321.00, 'Demo purchase order', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(21, 'PO-00021', 7, '2026-03-27', '2026-04-03', 'draft', 3098.65, 3098.65, 'Demo purchase order', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(22, 'PO-00022', 8, '2026-03-20', '2026-03-27', 'received', 2809.75, 2809.75, 'Demo purchase order', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(23, 'PO-00023', 9, '2026-03-13', '2026-03-20', 'received', 2624.00, 2624.00, 'Demo purchase order', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(24, 'PO-00024', 10, '2026-03-06', '2026-03-13', 'partial', 3057.85, 3057.85, 'Demo purchase order', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(25, 'PO-00025', 11, '2026-02-27', '2026-03-06', 'cancelled', 3707.65, 3707.65, 'Demo purchase order', '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(26, 'PO-00026', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:18:47', '2026-09-10 11:18:47'),
(27, 'PO-00027', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:18:49', '2026-09-10 11:18:49'),
(28, 'PO-00028', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:18:51', '2026-09-10 11:18:51'),
(29, 'PO-00029', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:19:04', '2026-09-10 11:19:04'),
(30, 'PO-00030', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:19:06', '2026-09-10 11:19:06'),
(31, 'PO-00031', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:19:07', '2026-09-10 11:19:07'),
(32, 'PO-00032', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:19:08', '2026-09-10 11:19:08'),
(33, 'PO-00033', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:19:09', '2026-09-10 11:19:09'),
(34, 'PO-00034', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:19:09', '2026-09-10 11:19:09'),
(35, 'PO-00035', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:19:15', '2026-09-10 11:19:15'),
(36, 'PO-00036', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:19:35', '2026-09-10 11:19:35'),
(37, 'PO-00037', 1, '2026-09-10', NULL, 'draft', 0.00, 0.00, 'Draft purchase order', '2026-09-10 11:23:59', '2026-09-10 11:23:59'),
(38, 'PO-00038', 1, '2026-09-17', '2026-09-24', 'received', 38.35, 38.35, NULL, '2026-09-17 08:04:58', '2026-09-17 08:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_lines`
--

CREATE TABLE `purchase_order_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `qty_received` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `rate` decimal(15,2) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_lines`
--

INSERT INTO `purchase_order_lines` (`id`, `purchase_order_id`, `item_id`, `description`, `quantity`, `qty_received`, `rate`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Dark Horse Original 200ct', 37.0000, 0.0000, 38.35, 1418.95, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 1, 5, 'Dark Horse Blueberry 200ct', 25.0000, 0.0000, 39.40, 985.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 1, 13, 'Dark Horse Tobacco Classic 200ct', 27.0000, 0.0000, 42.20, 1139.40, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 1, 61, 'VaporX Menthol', 30.0000, 0.0000, 22.00, 660.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 2, 77, 'Volt Energy Original 24ct', 14.0000, 14.0000, 18.35, 256.90, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 2, 78, 'Volt Energy Vanilla 24ct', 25.0000, 25.0000, 18.70, 467.50, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(7, 2, 104, 'Crunch Trail Smooth 12ct', 29.0000, 29.0000, 18.55, 537.95, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(8, 2, 107, 'Clipper Acc Original', 29.0000, 29.0000, 6.35, 184.15, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(9, 3, 4, 'Dark Horse Cherry 200ct', 16.0000, 8.0000, 39.05, 624.80, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(10, 3, 72, 'VaporX Watermelon', 26.0000, 13.0000, 25.85, 672.10, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(11, 3, 79, 'Volt Energy Cherry 24ct', 17.0000, 8.5000, 19.05, 323.85, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(12, 3, 91, 'Crunch Trail Menthol 12ct', 18.0000, 9.0000, 14.00, 252.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(13, 4, 25, 'Bluntville Mango 25ct', 34.0000, 34.0000, 21.15, 719.10, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(14, 4, 50, 'ALP Drifters Blueberry 5ct', 20.0000, 20.0000, 29.40, 588.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(15, 4, 87, 'Volt Energy Watermelon 24ct', 24.0000, 24.0000, 21.85, 524.40, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(16, 4, 97, 'Crunch Trail Honey 12ct', 40.0000, 40.0000, 16.10, 644.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(17, 5, 41, 'Rogue Grape 5ct', 36.0000, 0.0000, 15.50, 558.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(18, 5, 47, 'ALP Drifters Original 5ct', 39.0000, 0.0000, 28.35, 1105.65, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(19, 5, 74, 'VaporX Smooth', 31.0000, 0.0000, 26.55, 823.05, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(20, 5, 90, 'Volt Energy Bold 24ct', 15.0000, 0.0000, 22.90, 343.50, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(21, 6, 33, 'Rogue Vanilla 5ct', 30.0000, 0.0000, 12.70, 381.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(22, 6, 34, 'Rogue Cherry 5ct', 26.0000, 0.0000, 13.05, 339.30, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(23, 6, 42, 'Rogue Watermelon 5ct', 29.0000, 0.0000, 15.85, 459.65, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(24, 6, 66, 'VaporX Mint', 35.0000, 0.0000, 23.75, 831.25, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(25, 7, 24, 'Bluntville Peach Ice 25ct', 24.0000, 24.0000, 20.80, 499.20, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(26, 7, 46, 'ALP Drifters Menthol 5ct', 15.0000, 15.0000, 28.00, 420.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(27, 7, 61, 'VaporX Menthol', 18.0000, 18.0000, 22.00, 396.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(28, 7, 78, 'Volt Energy Vanilla 24ct', 23.0000, 23.0000, 18.70, 430.10, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(29, 8, 17, 'Bluntville Original 25ct', 22.0000, 22.0000, 18.35, 403.70, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(30, 8, 23, 'Bluntville Wintergreen 25ct', 37.0000, 37.0000, 20.45, 756.65, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(31, 8, 75, 'VaporX Bold', 29.0000, 29.0000, 26.90, 780.10, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(32, 8, 100, 'Crunch Trail Mango 12ct', 39.0000, 39.0000, 17.15, 668.85, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(33, 9, 34, 'Rogue Cherry 5ct', 30.0000, 15.0000, 13.05, 391.50, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(34, 9, 42, 'Rogue Watermelon 5ct', 35.0000, 17.5000, 15.85, 554.75, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(35, 9, 80, 'Volt Energy Blueberry 24ct', 28.0000, 14.0000, 19.40, 543.20, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(36, 9, 82, 'Volt Energy Honey 24ct', 29.0000, 14.5000, 20.10, 582.90, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(37, 10, 59, 'ALP Drifters Smooth 5ct', 20.0000, 0.0000, 32.55, 651.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(38, 10, 80, 'Volt Energy Blueberry 24ct', 40.0000, 0.0000, 19.40, 776.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(39, 10, 82, 'Volt Energy Honey 24ct', 37.0000, 0.0000, 20.10, 743.70, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(40, 10, 90, 'Volt Energy Bold 24ct', 35.0000, 0.0000, 22.90, 801.50, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(41, 11, 3, 'Dark Horse Vanilla 200ct', 33.0000, 0.0000, 38.70, 1277.10, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(42, 11, 53, 'ALP Drifters Wintergreen 5ct', 40.0000, 0.0000, 30.45, 1218.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(43, 11, 54, 'ALP Drifters Peach Ice 5ct', 25.0000, 0.0000, 30.80, 770.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(44, 11, 89, 'Volt Energy Smooth 24ct', 33.0000, 0.0000, 22.55, 744.15, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(45, 12, 10, 'Dark Horse Mango 200ct', 15.0000, 7.5000, 41.15, 617.25, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(46, 12, 33, 'Rogue Vanilla 5ct', 32.0000, 16.0000, 12.70, 406.40, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(47, 12, 36, 'Rogue Mint 5ct', 40.0000, 20.0000, 13.75, 550.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(48, 12, 49, 'ALP Drifters Cherry 5ct', 13.0000, 6.5000, 29.05, 377.65, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(49, 13, 17, 'Bluntville Original 25ct', 17.0000, 17.0000, 18.35, 311.95, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(50, 13, 19, 'Bluntville Cherry 25ct', 16.0000, 16.0000, 19.05, 304.80, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(51, 13, 62, 'VaporX Original', 21.0000, 21.0000, 22.35, 469.35, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(52, 13, 111, 'Clipper Acc Mint', 21.0000, 21.0000, 7.75, 162.75, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(53, 14, 42, 'Rogue Watermelon 5ct', 13.0000, 13.0000, 15.85, 206.05, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(54, 14, 60, 'ALP Drifters Bold 5ct', 18.0000, 18.0000, 32.90, 592.20, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(55, 14, 62, 'VaporX Original', 38.0000, 38.0000, 22.35, 849.30, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(56, 14, 79, 'Volt Energy Cherry 24ct', 16.0000, 16.0000, 19.05, 304.80, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(57, 15, 7, 'Dark Horse Honey 200ct', 27.0000, 0.0000, 40.10, 1082.70, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(58, 15, 39, 'Rogue Peach Ice 5ct', 37.0000, 0.0000, 14.80, 547.60, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(59, 15, 84, 'Volt Energy Peach Ice 24ct', 33.0000, 0.0000, 20.80, 686.40, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(60, 15, 95, 'Crunch Trail Blueberry 12ct', 40.0000, 0.0000, 15.40, 616.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(61, 16, 50, 'ALP Drifters Blueberry 5ct', 31.0000, 0.0000, 29.40, 911.40, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(62, 16, 61, 'VaporX Menthol', 25.0000, 0.0000, 22.00, 550.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(63, 16, 80, 'Volt Energy Blueberry 24ct', 12.0000, 0.0000, 19.40, 232.80, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(64, 16, 111, 'Clipper Acc Mint', 12.0000, 0.0000, 7.75, 93.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(65, 17, 39, 'Rogue Peach Ice 5ct', 30.0000, 30.0000, 14.80, 444.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(66, 17, 65, 'VaporX Blueberry', 37.0000, 37.0000, 23.40, 865.80, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(67, 17, 112, 'Clipper Acc Honey', 34.0000, 34.0000, 8.10, 275.40, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(68, 17, 119, 'Clipper Acc Smooth', 32.0000, 32.0000, 10.55, 337.60, '2026-09-10 11:00:44', '2026-09-10 11:00:45'),
(69, 18, 16, 'Bluntville Menthol 25ct', 14.0000, 7.0000, 18.00, 252.00, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(70, 18, 34, 'Rogue Cherry 5ct', 39.0000, 19.5000, 13.05, 508.95, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(71, 18, 62, 'VaporX Original', 22.0000, 11.0000, 22.35, 491.70, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(72, 18, 115, 'Clipper Acc Mango', 15.0000, 7.5000, 9.15, 137.25, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(73, 19, 18, 'Bluntville Vanilla 25ct', 16.0000, 16.0000, 18.70, 299.20, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(74, 19, 25, 'Bluntville Mango 25ct', 37.0000, 37.0000, 21.15, 782.55, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(75, 19, 59, 'ALP Drifters Smooth 5ct', 31.0000, 31.0000, 32.55, 1009.05, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(76, 19, 97, 'Crunch Trail Honey 12ct', 26.0000, 26.0000, 16.10, 418.60, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(77, 20, 24, 'Bluntville Peach Ice 25ct', 36.0000, 0.0000, 20.80, 748.80, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(78, 20, 25, 'Bluntville Mango 25ct', 37.0000, 0.0000, 21.15, 782.55, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(79, 20, 38, 'Rogue Wintergreen 5ct', 21.0000, 0.0000, 14.45, 303.45, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(80, 20, 78, 'Volt Energy Vanilla 24ct', 26.0000, 0.0000, 18.70, 486.20, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(81, 21, 56, 'ALP Drifters Grape 5ct', 34.0000, 0.0000, 31.50, 1071.00, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(82, 21, 87, 'Volt Energy Watermelon 24ct', 27.0000, 0.0000, 21.85, 589.95, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(83, 21, 88, 'Volt Energy Tobacco Classic 24ct', 39.0000, 0.0000, 22.20, 865.80, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(84, 21, 94, 'Crunch Trail Cherry 12ct', 38.0000, 0.0000, 15.05, 571.90, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(85, 22, 8, 'Dark Horse Wintergreen 200ct', 38.0000, 38.0000, 40.45, 1537.10, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(86, 22, 20, 'Bluntville Blueberry 25ct', 16.0000, 16.0000, 19.40, 310.40, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(87, 22, 23, 'Bluntville Wintergreen 25ct', 21.0000, 21.0000, 20.45, 429.45, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(88, 22, 88, 'Volt Energy Tobacco Classic 24ct', 24.0000, 24.0000, 22.20, 532.80, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(89, 23, 25, 'Bluntville Mango 25ct', 34.0000, 34.0000, 21.15, 719.10, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(90, 23, 48, 'ALP Drifters Vanilla 5ct', 32.0000, 32.0000, 28.70, 918.40, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(91, 23, 85, 'Volt Energy Mango 24ct', 20.0000, 20.0000, 21.15, 423.00, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(92, 23, 97, 'Crunch Trail Honey 12ct', 35.0000, 35.0000, 16.10, 563.50, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(93, 24, 5, 'Dark Horse Blueberry 200ct', 40.0000, 20.0000, 39.40, 1576.00, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(94, 24, 13, 'Dark Horse Tobacco Classic 200ct', 11.0000, 5.5000, 42.20, 464.20, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(95, 24, 40, 'Rogue Mango 5ct', 15.0000, 7.5000, 15.15, 227.25, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(96, 24, 84, 'Volt Energy Peach Ice 24ct', 38.0000, 19.0000, 20.80, 790.40, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(97, 25, 13, 'Dark Horse Tobacco Classic 200ct', 39.0000, 0.0000, 42.20, 1645.80, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(98, 25, 14, 'Dark Horse Smooth 200ct', 22.0000, 0.0000, 42.55, 936.10, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(99, 25, 37, 'Rogue Honey 5ct', 29.0000, 0.0000, 14.10, 408.90, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(100, 25, 74, 'VaporX Smooth', 27.0000, 0.0000, 26.55, 716.85, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(101, 38, 2, 'Dark Horse Original 200ct', 1.0000, 1.0000, 38.35, 38.35, '2026-09-17 08:04:58', '2026-09-17 08:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `qb_import_tables`
--

CREATE TABLE `qb_import_tables` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `quote_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `memo` text COLLATE utf8mb4_unicode_ci,
  `converted_invoice_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `number`, `customer_id`, `quote_date`, `expiry_date`, `status`, `subtotal`, `tax_total`, `total`, `memo`, `converted_invoice_id`, `created_at`, `updated_at`) VALUES
(1, 'QT-00001', 1, '2026-08-28', '2026-09-27', 'sent', 682.80, 43.36, 726.16, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(2, 'QT-00002', 2, '2026-08-25', '2026-09-24', 'accepted', 593.80, 37.71, 631.51, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(3, 'QT-00003', 3, '2026-08-22', '2026-09-21', 'rejected', 604.00, 38.35, 642.35, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(4, 'QT-00004', 4, '2026-08-19', '2026-09-18', 'converted', 914.10, 58.05, 972.15, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(5, 'QT-00005', 5, '2026-08-16', '2026-09-15', 'draft', 750.55, 47.66, 798.21, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(6, 'QT-00006', 6, '2026-08-13', '2026-09-12', 'sent', 963.15, 61.16, 1024.31, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(7, 'QT-00007', 7, '2026-08-10', '2026-09-09', 'accepted', 454.15, 28.84, 482.99, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(8, 'QT-00008', 8, '2026-08-07', '2026-09-06', 'rejected', 859.30, 54.57, 913.87, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(9, 'QT-00009', 9, '2026-08-04', '2026-09-03', 'converted', 528.40, 33.55, 561.95, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(10, 'QT-00010', 10, '2026-08-01', '2026-08-31', 'draft', 390.25, 24.78, 415.03, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(11, 'QT-00011', 11, '2026-07-29', '2026-08-28', 'sent', 314.80, 19.99, 334.79, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(12, 'QT-00012', 12, '2026-07-26', '2026-08-25', 'accepted', 444.10, 28.20, 472.30, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(13, 'QT-00013', 13, '2026-07-23', '2026-08-22', 'rejected', 671.15, 42.62, 713.77, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(14, 'QT-00014', 14, '2026-07-20', '2026-08-19', 'converted', 1279.40, 81.24, 1360.64, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(15, 'QT-00015', 15, '2026-07-17', '2026-08-16', 'draft', 769.80, 48.88, 818.68, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(16, 'QT-00016', 16, '2026-07-14', '2026-08-13', 'sent', 515.80, 32.75, 548.55, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(17, 'QT-00017', 17, '2026-07-11', '2026-08-10', 'accepted', 282.40, 17.93, 300.33, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(18, 'QT-00018', 18, '2026-07-08', '2026-08-07', 'rejected', 257.90, 16.38, 274.28, NULL, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:46'),
(19, 'QT-00019', 19, '2026-07-05', '2026-08-04', 'converted', 774.80, 49.20, 824.00, NULL, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(20, 'QT-00020', 20, '2026-07-02', '2026-08-01', 'draft', 913.50, 58.01, 971.51, NULL, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(21, 'QT-00021', 21, '2026-06-29', '2026-07-29', 'sent', 1096.50, 69.63, 1166.13, NULL, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(22, 'QT-00022', 22, '2026-06-26', '2026-07-26', 'accepted', 606.40, 38.51, 644.91, NULL, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(23, 'QT-00023', 23, '2026-06-23', '2026-07-23', 'rejected', 979.85, 62.22, 1042.07, NULL, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(24, 'QT-00024', 24, '2026-06-20', '2026-07-20', 'converted', 825.15, 52.40, 877.55, NULL, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(25, 'QT-00025', 25, '2026-06-17', '2026-07-17', 'draft', 636.50, 40.42, 676.92, NULL, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46');

-- --------------------------------------------------------

--
-- Table structure for table `quote_lines`
--

CREATE TABLE `quote_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `quote_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `taxable` tinyint(1) NOT NULL DEFAULT '1',
  `line_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quote_lines`
--

INSERT INTO `quote_lines` (`id`, `quote_id`, `item_id`, `description`, `quantity`, `rate`, `amount`, `taxable`, `line_order`, `created_at`, `updated_at`) VALUES
(1, 1, 87, 'Volt Energy Watermelon 24ct', 6.0000, 34.85, 209.10, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(2, 1, 89, 'Volt Energy Smooth 24ct', 9.0000, 35.95, 323.55, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(3, 1, 109, 'Clipper Acc Cherry', 11.0000, 13.65, 150.15, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(4, 2, 43, 'Rogue Tobacco Classic 5ct', 12.0000, 28.35, 340.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(5, 2, 111, 'Clipper Acc Mint', 12.0000, 14.75, 177.00, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(6, 2, 119, 'Clipper Acc Smooth', 4.0000, 19.15, 76.60, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(7, 3, 45, 'Rogue Bold 5ct', 8.0000, 29.45, 235.60, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(8, 3, 48, 'ALP Drifters Vanilla 5ct', 6.0000, 43.10, 258.60, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(9, 3, 100, 'Crunch Trail Mango 12ct', 4.0000, 27.45, 109.80, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(10, 4, 16, 'Bluntville Menthol 25ct', 9.0000, 29.50, 265.50, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(11, 4, 55, 'ALP Drifters Mango 5ct', 12.0000, 46.95, 563.40, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(12, 4, 110, 'Clipper Acc Blueberry', 6.0000, 14.20, 85.20, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(13, 5, 11, 'Dark Horse Grape 200ct', 5.0000, 57.45, 287.25, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(14, 5, 96, 'Crunch Trail Mint 12ct', 10.0000, 25.25, 252.50, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(15, 5, 98, 'Crunch Trail Wintergreen 12ct', 8.0000, 26.35, 210.80, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(16, 6, 3, 'Dark Horse Vanilla 200ct', 5.0000, 53.05, 265.25, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(17, 6, 45, 'Rogue Bold 5ct', 9.0000, 29.45, 265.05, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(18, 6, 69, 'VaporX Peach Ice', 11.0000, 39.35, 432.85, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(19, 7, 53, 'ALP Drifters Wintergreen 5ct', 4.0000, 45.85, 183.40, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(20, 7, 115, 'Clipper Acc Mango', 5.0000, 16.95, 84.75, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(21, 7, 118, 'Clipper Acc Tobacco Classic', 10.0000, 18.60, 186.00, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(22, 8, 2, 'Dark Horse Original 200ct', 8.0000, 52.50, 420.00, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(23, 8, 84, 'Volt Energy Peach Ice 24ct', 6.0000, 33.20, 199.20, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(24, 8, 86, 'Volt Energy Grape 24ct', 7.0000, 34.30, 240.10, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(25, 9, 60, 'ALP Drifters Bold 5ct', 6.0000, 49.70, 298.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(26, 9, 83, 'Volt Energy Wintergreen 24ct', 4.0000, 32.65, 130.60, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(27, 9, 84, 'Volt Energy Peach Ice 24ct', 3.0000, 33.20, 99.60, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(28, 10, 4, 'Dark Horse Cherry 200ct', 2.0000, 53.60, 107.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(29, 10, 78, 'Volt Energy Vanilla 24ct', 7.0000, 29.90, 209.30, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(30, 10, 111, 'Clipper Acc Mint', 5.0000, 14.75, 73.75, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(31, 11, 58, 'ALP Drifters Tobacco Classic 5ct', 2.0000, 48.60, 97.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(32, 11, 110, 'Clipper Acc Blueberry', 7.0000, 14.20, 99.40, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(33, 11, 120, 'Clipper Acc Bold', 6.0000, 19.70, 118.20, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(34, 12, 18, 'Bluntville Vanilla 25ct', 4.0000, 30.60, 122.40, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(35, 12, 30, 'Bluntville Bold 25ct', 4.0000, 37.20, 148.80, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(36, 12, 95, 'Crunch Trail Blueberry 12ct', 7.0000, 24.70, 172.90, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(37, 13, 29, 'Bluntville Smooth 25ct', 10.0000, 36.65, 366.50, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(38, 13, 37, 'Rogue Honey 5ct', 5.0000, 25.05, 125.25, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(39, 13, 78, 'Volt Energy Vanilla 24ct', 6.0000, 29.90, 179.40, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(40, 14, 10, 'Dark Horse Mango 200ct', 11.0000, 56.90, 625.90, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(41, 14, 40, 'Rogue Mango 5ct', 4.0000, 26.70, 106.80, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(42, 14, 60, 'ALP Drifters Bold 5ct', 11.0000, 49.70, 546.70, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(43, 15, 14, 'Dark Horse Smooth 200ct', 8.0000, 59.10, 472.80, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(44, 15, 69, 'VaporX Peach Ice', 6.0000, 39.35, 236.10, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(45, 15, 79, 'Volt Energy Cherry 24ct', 2.0000, 30.45, 60.90, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(46, 16, 8, 'Dark Horse Wintergreen 200ct', 4.0000, 55.80, 223.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(47, 16, 78, 'Volt Energy Vanilla 24ct', 2.0000, 29.90, 59.80, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(48, 16, 103, 'Crunch Trail Tobacco Classic 12ct', 8.0000, 29.10, 232.80, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(49, 17, 88, 'Volt Energy Tobacco Classic 24ct', 2.0000, 35.40, 70.80, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(50, 17, 97, 'Crunch Trail Honey 12ct', 6.0000, 25.80, 154.80, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(51, 17, 110, 'Clipper Acc Blueberry', 4.0000, 14.20, 56.80, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(52, 18, 44, 'Rogue Smooth 5ct', 2.0000, 28.90, 57.80, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(53, 18, 73, 'VaporX Tobacco Classic', 4.0000, 41.55, 166.20, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(54, 18, 115, 'Clipper Acc Mango', 2.0000, 16.95, 33.90, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(55, 19, 23, 'Bluntville Wintergreen 25ct', 8.0000, 33.35, 266.80, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(56, 19, 42, 'Rogue Watermelon 5ct', 2.0000, 27.80, 55.60, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(57, 19, 66, 'VaporX Mint', 12.0000, 37.70, 452.40, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(58, 20, 44, 'Rogue Smooth 5ct', 12.0000, 28.90, 346.80, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(59, 20, 52, 'ALP Drifters Honey 5ct', 6.0000, 45.30, 271.80, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(60, 20, 59, 'ALP Drifters Smooth 5ct', 6.0000, 49.15, 294.90, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(61, 21, 11, 'Dark Horse Grape 200ct', 10.0000, 57.45, 574.50, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(62, 21, 54, 'ALP Drifters Peach Ice 5ct', 8.0000, 46.40, 371.20, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(63, 21, 66, 'VaporX Mint', 4.0000, 37.70, 150.80, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(64, 22, 18, 'Bluntville Vanilla 25ct', 2.0000, 30.60, 61.20, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(65, 22, 47, 'ALP Drifters Original 5ct', 9.0000, 42.55, 382.95, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(66, 22, 111, 'Clipper Acc Mint', 11.0000, 14.75, 162.25, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(67, 23, 15, 'Dark Horse Bold 200ct', 9.0000, 59.65, 536.85, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(68, 23, 27, 'Bluntville Watermelon 25ct', 3.0000, 35.55, 106.65, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(69, 23, 57, 'ALP Drifters Watermelon 5ct', 7.0000, 48.05, 336.35, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(70, 24, 3, 'Dark Horse Vanilla 200ct', 3.0000, 53.05, 159.15, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(71, 24, 4, 'Dark Horse Cherry 200ct', 8.0000, 53.60, 428.80, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(72, 24, 104, 'Crunch Trail Smooth 12ct', 8.0000, 29.65, 237.20, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(73, 25, 10, 'Dark Horse Mango 200ct', 4.0000, 56.90, 227.60, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(74, 25, 69, 'VaporX Peach Ice', 3.0000, 39.35, 118.05, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(75, 25, 73, 'VaporX Tobacco Classic', 7.0000, 41.55, 290.85, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'Owner', 'Full system access', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(2, 'manager', 'Manager', 'Operations management without destructive settings-only limits', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(3, 'sales', 'Sales / Counter Staff', 'Point-of-sale and customer transactions', '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(4, 'bookkeeper', 'Bookkeeper', 'Accounting, banking, and reporting', '2026-09-10 11:00:39', '2026-09-10 11:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(2, 2, 2, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(3, 3, 3, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(4, 4, 4, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `quote_id` bigint UNSIGNED DEFAULT NULL,
  `order_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `memo` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_orders`
--

INSERT INTO `sales_orders` (`id`, `number`, `customer_id`, `quote_id`, `order_date`, `status`, `subtotal`, `tax_total`, `total`, `memo`, `created_at`, `updated_at`) VALUES
(1, 'SO-00001', 1, NULL, '2026-08-29', 'open', 682.80, 43.36, 726.16, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(2, 'SO-00002', 2, NULL, '2026-08-26', 'open', 593.80, 37.71, 631.51, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(3, 'SO-00003', 3, NULL, '2026-08-23', 'invoiced', 604.00, 38.35, 642.35, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(4, 'SO-00004', 4, 4, '2026-08-20', 'cancelled', 914.10, 58.05, 972.15, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(5, 'SO-00005', 5, NULL, '2026-08-17', 'draft', 750.55, 47.66, 798.21, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(6, 'SO-00006', 6, NULL, '2026-08-14', 'open', 963.15, 61.16, 1024.31, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(7, 'SO-00007', 7, NULL, '2026-08-11', 'open', 454.15, 28.84, 482.99, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(8, 'SO-00008', 8, NULL, '2026-08-08', 'invoiced', 859.30, 54.57, 913.87, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(9, 'SO-00009', 9, 9, '2026-08-05', 'cancelled', 528.40, 33.55, 561.95, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(10, 'SO-00010', 10, NULL, '2026-08-02', 'draft', 390.25, 24.78, 415.03, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(11, 'SO-00011', 11, NULL, '2026-07-30', 'open', 314.80, 19.99, 334.79, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(12, 'SO-00012', 12, NULL, '2026-07-27', 'open', 444.10, 28.20, 472.30, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(13, 'SO-00013', 13, NULL, '2026-07-24', 'invoiced', 671.15, 42.62, 713.77, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(14, 'SO-00014', 14, 14, '2026-07-21', 'cancelled', 1279.40, 81.24, 1360.64, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(15, 'SO-00015', 15, NULL, '2026-07-18', 'draft', 769.80, 48.88, 818.68, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(16, 'SO-00016', 16, NULL, '2026-07-15', 'open', 515.80, 32.75, 548.55, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(17, 'SO-00017', 17, NULL, '2026-07-12', 'open', 282.40, 17.93, 300.33, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(18, 'SO-00018', 18, NULL, '2026-07-09', 'invoiced', 257.90, 16.38, 274.28, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(19, 'SO-00019', 19, 19, '2026-07-06', 'cancelled', 774.80, 49.20, 824.00, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(20, 'SO-00020', 20, NULL, '2026-07-03', 'draft', 913.50, 58.01, 971.51, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(21, 'SO-00021', 21, NULL, '2026-06-30', 'open', 1096.50, 69.63, 1166.13, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(22, 'SO-00022', 22, NULL, '2026-06-27', 'open', 606.40, 38.51, 644.91, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(23, 'SO-00023', 23, NULL, '2026-06-24', 'invoiced', 979.85, 62.22, 1042.07, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(24, 'SO-00024', 24, 24, '2026-06-21', 'cancelled', 825.15, 52.40, 877.55, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(25, 'SO-00025', 25, NULL, '2026-06-18', 'draft', 636.50, 40.42, 676.92, NULL, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(26, 'SO-00026', 1, NULL, '2026-09-10', 'draft', 0.00, 0.00, 0.00, 'Draft sales order', '2026-09-10 11:24:52', '2026-09-10 11:24:52');

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_lines`
--

CREATE TABLE `sales_order_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `sales_order_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `taxable` tinyint(1) NOT NULL DEFAULT '1',
  `line_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_order_lines`
--

INSERT INTO `sales_order_lines` (`id`, `sales_order_id`, `item_id`, `description`, `quantity`, `rate`, `amount`, `taxable`, `line_order`, `created_at`, `updated_at`) VALUES
(1, 1, 87, 'Volt Energy Watermelon 24ct', 6.0000, 34.85, 209.10, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(2, 1, 89, 'Volt Energy Smooth 24ct', 9.0000, 35.95, 323.55, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(3, 1, 109, 'Clipper Acc Cherry', 11.0000, 13.65, 150.15, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(4, 2, 43, 'Rogue Tobacco Classic 5ct', 12.0000, 28.35, 340.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(5, 2, 111, 'Clipper Acc Mint', 12.0000, 14.75, 177.00, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(6, 2, 119, 'Clipper Acc Smooth', 4.0000, 19.15, 76.60, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(7, 3, 45, 'Rogue Bold 5ct', 8.0000, 29.45, 235.60, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(8, 3, 48, 'ALP Drifters Vanilla 5ct', 6.0000, 43.10, 258.60, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(9, 3, 100, 'Crunch Trail Mango 12ct', 4.0000, 27.45, 109.80, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(10, 4, 16, 'Bluntville Menthol 25ct', 9.0000, 29.50, 265.50, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(11, 4, 55, 'ALP Drifters Mango 5ct', 12.0000, 46.95, 563.40, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(12, 4, 110, 'Clipper Acc Blueberry', 6.0000, 14.20, 85.20, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(13, 5, 11, 'Dark Horse Grape 200ct', 5.0000, 57.45, 287.25, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(14, 5, 96, 'Crunch Trail Mint 12ct', 10.0000, 25.25, 252.50, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(15, 5, 98, 'Crunch Trail Wintergreen 12ct', 8.0000, 26.35, 210.80, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(16, 6, 3, 'Dark Horse Vanilla 200ct', 5.0000, 53.05, 265.25, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(17, 6, 45, 'Rogue Bold 5ct', 9.0000, 29.45, 265.05, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(18, 6, 69, 'VaporX Peach Ice', 11.0000, 39.35, 432.85, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(19, 7, 53, 'ALP Drifters Wintergreen 5ct', 4.0000, 45.85, 183.40, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(20, 7, 115, 'Clipper Acc Mango', 5.0000, 16.95, 84.75, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(21, 7, 118, 'Clipper Acc Tobacco Classic', 10.0000, 18.60, 186.00, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(22, 8, 2, 'Dark Horse Original 200ct', 8.0000, 52.50, 420.00, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(23, 8, 84, 'Volt Energy Peach Ice 24ct', 6.0000, 33.20, 199.20, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(24, 8, 86, 'Volt Energy Grape 24ct', 7.0000, 34.30, 240.10, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(25, 9, 60, 'ALP Drifters Bold 5ct', 6.0000, 49.70, 298.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(26, 9, 83, 'Volt Energy Wintergreen 24ct', 4.0000, 32.65, 130.60, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(27, 9, 84, 'Volt Energy Peach Ice 24ct', 3.0000, 33.20, 99.60, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(28, 10, 4, 'Dark Horse Cherry 200ct', 2.0000, 53.60, 107.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(29, 10, 78, 'Volt Energy Vanilla 24ct', 7.0000, 29.90, 209.30, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(30, 10, 111, 'Clipper Acc Mint', 5.0000, 14.75, 73.75, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(31, 11, 58, 'ALP Drifters Tobacco Classic 5ct', 2.0000, 48.60, 97.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(32, 11, 110, 'Clipper Acc Blueberry', 7.0000, 14.20, 99.40, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(33, 11, 120, 'Clipper Acc Bold', 6.0000, 19.70, 118.20, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(34, 12, 18, 'Bluntville Vanilla 25ct', 4.0000, 30.60, 122.40, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(35, 12, 30, 'Bluntville Bold 25ct', 4.0000, 37.20, 148.80, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(36, 12, 95, 'Crunch Trail Blueberry 12ct', 7.0000, 24.70, 172.90, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(37, 13, 29, 'Bluntville Smooth 25ct', 10.0000, 36.65, 366.50, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(38, 13, 37, 'Rogue Honey 5ct', 5.0000, 25.05, 125.25, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(39, 13, 78, 'Volt Energy Vanilla 24ct', 6.0000, 29.90, 179.40, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(40, 14, 10, 'Dark Horse Mango 200ct', 11.0000, 56.90, 625.90, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(41, 14, 40, 'Rogue Mango 5ct', 4.0000, 26.70, 106.80, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(42, 14, 60, 'ALP Drifters Bold 5ct', 11.0000, 49.70, 546.70, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(43, 15, 14, 'Dark Horse Smooth 200ct', 8.0000, 59.10, 472.80, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(44, 15, 69, 'VaporX Peach Ice', 6.0000, 39.35, 236.10, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(45, 15, 79, 'Volt Energy Cherry 24ct', 2.0000, 30.45, 60.90, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(46, 16, 8, 'Dark Horse Wintergreen 200ct', 4.0000, 55.80, 223.20, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(47, 16, 78, 'Volt Energy Vanilla 24ct', 2.0000, 29.90, 59.80, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(48, 16, 103, 'Crunch Trail Tobacco Classic 12ct', 8.0000, 29.10, 232.80, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(49, 17, 88, 'Volt Energy Tobacco Classic 24ct', 2.0000, 35.40, 70.80, 1, 0, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(50, 17, 97, 'Crunch Trail Honey 12ct', 6.0000, 25.80, 154.80, 1, 1, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(51, 17, 110, 'Clipper Acc Blueberry', 4.0000, 14.20, 56.80, 1, 2, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(52, 18, 44, 'Rogue Smooth 5ct', 2.0000, 28.90, 57.80, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(53, 18, 73, 'VaporX Tobacco Classic', 4.0000, 41.55, 166.20, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(54, 18, 115, 'Clipper Acc Mango', 2.0000, 16.95, 33.90, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(55, 19, 23, 'Bluntville Wintergreen 25ct', 8.0000, 33.35, 266.80, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(56, 19, 42, 'Rogue Watermelon 5ct', 2.0000, 27.80, 55.60, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(57, 19, 66, 'VaporX Mint', 12.0000, 37.70, 452.40, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(58, 20, 44, 'Rogue Smooth 5ct', 12.0000, 28.90, 346.80, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(59, 20, 52, 'ALP Drifters Honey 5ct', 6.0000, 45.30, 271.80, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(60, 20, 59, 'ALP Drifters Smooth 5ct', 6.0000, 49.15, 294.90, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(61, 21, 11, 'Dark Horse Grape 200ct', 10.0000, 57.45, 574.50, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(62, 21, 54, 'ALP Drifters Peach Ice 5ct', 8.0000, 46.40, 371.20, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(63, 21, 66, 'VaporX Mint', 4.0000, 37.70, 150.80, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(64, 22, 18, 'Bluntville Vanilla 25ct', 2.0000, 30.60, 61.20, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(65, 22, 47, 'ALP Drifters Original 5ct', 9.0000, 42.55, 382.95, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(66, 22, 111, 'Clipper Acc Mint', 11.0000, 14.75, 162.25, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(67, 23, 15, 'Dark Horse Bold 200ct', 9.0000, 59.65, 536.85, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(68, 23, 27, 'Bluntville Watermelon 25ct', 3.0000, 35.55, 106.65, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(69, 23, 57, 'ALP Drifters Watermelon 5ct', 7.0000, 48.05, 336.35, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(70, 24, 3, 'Dark Horse Vanilla 200ct', 3.0000, 53.05, 159.15, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(71, 24, 4, 'Dark Horse Cherry 200ct', 8.0000, 53.60, 428.80, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(72, 24, 104, 'Crunch Trail Smooth 12ct', 8.0000, 29.65, 237.20, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(73, 25, 10, 'Dark Horse Mango 200ct', 4.0000, 56.90, 227.60, 1, 0, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(74, 25, 69, 'VaporX Peach Ice', 3.0000, 39.35, 118.05, 1, 1, '2026-09-10 11:00:46', '2026-09-10 11:00:46'),
(75, 25, 73, 'VaporX Tobacco Classic', 7.0000, 41.55, 290.85, 1, 2, '2026-09-10 11:00:46', '2026-09-10 11:00:46');

-- --------------------------------------------------------

--
-- Table structure for table `sales_receipts`
--

CREATE TABLE `sales_receipts` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `receipt_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_receipts`
--

INSERT INTO `sales_receipts` (`id`, `number`, `customer_id`, `receipt_date`, `subtotal`, `tax_total`, `total`, `payment_method`, `memo`, `created_at`, `updated_at`) VALUES
(1, 'SR-00001', 2, '2026-09-09', 124.60, 7.91, 132.51, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(2, 'SR-00002', 3, '2026-09-08', 103.20, 6.55, 109.75, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(3, 'SR-00003', 4, '2026-09-07', 84.00, 5.33, 89.33, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(4, 'SR-00004', 5, '2026-09-06', 161.80, 10.27, 172.07, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(5, 'SR-00005', 6, '2026-09-05', 38.80, 2.46, 41.26, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(6, 'SR-00006', 7, '2026-09-04', 29.45, 1.87, 31.32, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(7, 'SR-00007', 8, '2026-09-03', 102.40, 6.50, 108.90, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(8, 'SR-00008', 9, '2026-09-02', 36.05, 2.29, 38.34, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(9, 'SR-00009', 10, '2026-09-01', 139.40, 8.85, 148.25, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(10, 'SR-00010', 11, '2026-08-31', 106.50, 6.76, 113.26, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(11, 'SR-00011', 12, '2026-08-30', 21.75, 1.38, 23.13, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(12, 'SR-00012', 13, '2026-08-29', 170.70, 10.84, 181.54, 'cash', NULL, '2026-09-10 11:00:48', '2026-09-10 11:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `sales_receipt_lines`
--

CREATE TABLE `sales_receipt_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `sales_receipt_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `taxable` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_receipt_lines`
--

INSERT INTO `sales_receipt_lines` (`id`, `sales_receipt_id`, `item_id`, `description`, `quantity`, `rate`, `amount`, `taxable`, `created_at`, `updated_at`) VALUES
(1, 1, 19, 'Bluntville Cherry 25ct', 4.0000, 31.15, 124.60, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(2, 2, 97, 'Crunch Trail Honey 12ct', 4.0000, 25.80, 103.20, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(3, 3, 46, 'ALP Drifters Menthol 5ct', 2.0000, 42.00, 84.00, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(4, 4, 71, 'VaporX Grape', 4.0000, 40.45, 161.80, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(5, 5, 68, 'VaporX Wintergreen', 1.0000, 38.80, 38.80, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(6, 6, 45, 'Rogue Bold 5ct', 1.0000, 29.45, 29.45, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(7, 7, 38, 'Rogue Wintergreen 5ct', 4.0000, 25.60, 102.40, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(8, 8, 63, 'VaporX Vanilla', 1.0000, 36.05, 36.05, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(9, 9, 87, 'Volt Energy Watermelon 24ct', 4.0000, 34.85, 139.40, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(10, 10, 62, 'VaporX Original', 3.0000, 35.50, 106.50, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(11, 11, 31, 'Rogue Menthol 5ct', 1.0000, 21.75, 21.75, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48'),
(12, 12, 10, 'Dark Horse Mango 200ct', 3.0000, 56.90, 170.70, 1, '2026-09-10 11:00:48', '2026-09-10 11:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('v6coYmmrPqjLImLjNAo9pR5jyLAuH8XHGLr4wITW', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJOTHE0UE5vb252cEV2QnJJQmpKVE9TVFZORVZDRWlvMnkyd3pweUxhIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iYXJnYWluLWVudGVycHJpc2UudGVzdFwvZGFzaGJvYXJkXC9ob21lP2VtYmVkPTEmcnQ9MCZ0YWJfaWQ9ZGFzaGJvYXJkIiwicm91dGUiOiJkYXNoYm9hcmQuaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sInVybCI6W10sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJ3b3Jrc3BhY2UiOnsidGFicyI6W3siaWQiOiJkYXNoYm9hcmQiLCJ0eXBlIjoiZGFzaGJvYXJkIiwidGl0bGUiOiJIb21lIFBhZ2UiLCJyb3V0ZSI6ImRhc2hib2FyZC5ob21lIiwicGFyYW1zIjpbXSwidXJsIjoiXC9kYXNoYm9hcmRcL2hvbWU/ZW1iZWQ9MSZ0YWJfaWQ9ZGFzaGJvYXJkIiwiY2xvc2FibGUiOmZhbHNlLCJkaXJ0eSI6ZmFsc2UsInJlZnJlc2hfdG9rZW4iOjB9XSwiYWN0aXZlX2lkIjoiZGFzaGJvYXJkIn19', 1789655120),
('vXFxUkg2fQxwc7QqT31jlHG03Cpz2FbL1jADSXXP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/3.17.8 Chrome/144.0.7559.236 Electron/40.10.3 Safari/537.36', 'eyJfdG9rZW4iOiJoOFBWMktVcEJIcHpvbTBqNU9ZMEhUdlJhWmJxVmNXbTNpSTk0S3Y0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2JhcmdhaW4tZW50ZXJwcmlzZS50ZXN0XC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1789651106);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `created_at`, `updated_at`) VALUES
(1, 'company.name', 'Bargain Enterprise Inc.', 'string', 'company', '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 'inventory.negative_policy', 'WARN', 'string', 'inventory', '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, 'inventory.allow_manager_override', '1', 'boolean', 'inventory', '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, 'employees.time_entries.1', '[{\"date\":\"2026-09-15\",\"name\":\"System Owner\",\"hours\":\"8.00\",\"memo\":\"\"},{\"date\":\"2026-09-15\",\"name\":\"System Owner\",\"hours\":\"8.00\",\"memo\":\"\"},{\"date\":\"2026-09-15\",\"name\":\"System Owner\",\"hours\":\"8.00\",\"memo\":\"\"}]', 'json', 'employees', '2026-09-15 14:41:01', '2026-09-15 14:41:09'),
(5, 'company.legal_name', 'Bargain Enterprise Inc.', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(6, 'company.phone', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(7, 'company.fax', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(8, 'company.email', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(9, 'company.website', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(10, 'company.address1', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(11, 'company.address2', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(12, 'company.city', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(13, 'company.state', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(14, 'company.zip', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(15, 'company.country', 'USA', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(16, 'company.federal_ein', '', 'string', 'company', '2026-09-15 14:43:49', '2026-09-15 14:43:49'),
(17, 'report.memorized.1.customer-open-balance-report', '{\"datePreset\":\"this_year\",\"from\":\"2026-01-01\",\"to\":\"2026-12-31\",\"basis\":\"accrual\",\"sortBy\":\"default\",\"hideHeader\":true,\"showExtraFilters\":true}', 'json', 'reports', '2026-09-16 07:41:25', '2026-09-16 07:42:48');

-- --------------------------------------------------------

--
-- Table structure for table `tax_codes`
--

CREATE TABLE `tax_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_codes`
--

INSERT INTO `tax_codes` (`id`, `code`, `name`, `rate`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tax', 'Taxable (dev 6.35%)', 6.3500, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 'Non', 'Non-taxable', 0.0000, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `units_of_measure`
--

CREATE TABLE `units_of_measure` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units_of_measure`
--

INSERT INTO `units_of_measure` (`id`, `name`, `abbreviation`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Each', 'ea', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 'Pack', 'pk', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, 'Box', 'bx', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, 'Carton', 'ctn', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(5, 'Case', 'cs', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System Owner', 'owner@bargain.local', '2026-09-10 11:00:39', '$2y$12$GlXojNa2XeHz0AJ5qkRm..gR.WaD4RNBUwGK0h1W5S9ZnQlkCjrDS', NULL, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(2, 'Operations Manager', 'manager@bargain.local', '2026-09-10 11:00:39', '$2y$12$bA7K9sU9WjCJ7zZ0ezm6E.1JpAappLrJ.WeDbKeFIqckm7j6AeeHK', NULL, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(3, 'Counter Staff', 'sales@bargain.local', '2026-09-10 11:00:39', '$2y$12$78Q1OGTjl5kexLDVjreVheS4l8mKQusHA.kMvPRA55bzZal255tG2', NULL, '2026-09-10 11:00:39', '2026-09-10 11:00:39'),
(4, 'Bookkeeper', 'books@bargain.local', '2026-09-10 11:00:39', '$2y$12$B.f.3CkcmbTvbLMW3wXoseM8iU3hMe8KzJ85SJJuhrkOH/JWBEF3a', NULL, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_street1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_street2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_state` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_zip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `vendor_number`, `company_name`, `display_name`, `first_name`, `last_name`, `email`, `phone`, `fax`, `bill_from_street1`, `bill_from_street2`, `bill_from_city`, `bill_from_state`, `bill_from_zip`, `bill_from_country`, `terms`, `account_number`, `balance`, `notes`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'V0001', 'Atlantic Tobacco Distributors', 'Atlantic Tobacco Distributors', 'Jovanny', 'Kuhlman', 'orders1@vendor-demo.local', '860-555-2000', NULL, '236 Industrial Pkwy', NULL, 'Stamford', 'CT', '06194', 'USA', 'Net 30', 'A10000', 0.00, 'Preferred weekly delivery Tuesday.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40', NULL),
(2, 'V0002', 'New England Convenience Supply', 'New England Convenience Supply', 'Weldon', 'Larkin', 'orders2@vendor-demo.local', '860-555-2001', NULL, '551 Industrial Pkwy', NULL, 'Stamford', 'CT', '06851', 'USA', 'Net 30', 'A10001', 723.25, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:44', NULL),
(3, 'V0003', 'Harbor Leaf Wholesale', 'Harbor Leaf Wholesale', 'Jadon', 'Will', 'orders3@vendor-demo.local', '860-555-2002', NULL, '728 Industrial Pkwy', NULL, 'Stamford', 'CT', '06860', 'USA', 'Net 30', 'A10002', 2859.18, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:45', NULL),
(4, 'V0004', 'Quinnipiac Packaged Goods', 'Quinnipiac Packaged Goods', 'Macy', 'Ferry', 'orders4@vendor-demo.local', '860-555-2003', NULL, '496 Industrial Pkwy', NULL, 'Bridgeport', 'CT', '06738', 'USA', 'Net 30', 'A10003', 347.48, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:45', NULL),
(5, 'V0005', 'Shoreline Snack Importers', 'Shoreline Snack Importers', 'Jacky', 'Nader', 'orders5@vendor-demo.local', '860-555-2004', NULL, '274 Industrial Pkwy', NULL, 'Hartford', 'CT', '06545', 'USA', 'Net 30', 'A10004', 2509.40, 'Preferred weekly delivery Tuesday.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:45', NULL),
(6, 'V0006', 'Elm City Vapor Supply', 'Elm City Vapor Supply', 'Mona', 'Hickle', 'orders6@vendor-demo.local', '860-555-2005', NULL, '495 Industrial Pkwy', NULL, 'New Haven', 'CT', '06507', 'USA', 'Net 30', 'A10005', 0.00, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40', NULL),
(7, 'V0007', 'Yankee Carton Company', 'Yankee Carton Company', 'Aleen', 'Bergnaum', 'orders7@vendor-demo.local', '860-555-2006', NULL, '722 Industrial Pkwy', NULL, 'Hartford', 'CT', '06452', 'USA', 'Net 30', 'A10006', 1745.30, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:44', NULL),
(8, 'V0008', 'Nutmeg Novelty Trading', 'Nutmeg Novelty Trading', 'Myriam', 'Schaden', 'orders8@vendor-demo.local', '860-555-2007', NULL, '355 Industrial Pkwy', NULL, 'Hartford', 'CT', '06777', 'USA', 'Net 30', 'A10007', 1404.87, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:45', NULL),
(9, 'V0009', 'Constitution State Candy Co', 'Constitution State Candy Co', 'Rosario', 'Schmidt', 'orders9@vendor-demo.local', '860-555-2008', NULL, '505 Industrial Pkwy', NULL, 'New Haven', 'CT', '06273', 'USA', 'Net 30', 'A10008', 3660.18, 'Preferred weekly delivery Tuesday.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:45', NULL),
(10, 'V0010', 'Long Island Sound Imports', 'Long Island Sound Imports', 'Mohamed', 'Pfannerstill', 'orders10@vendor-demo.local', '860-555-2009', NULL, '334 Industrial Pkwy', NULL, 'Stamford', 'CT', '06542', 'USA', 'Net 30', 'A10009', 0.00, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:45', NULL),
(11, 'V0011', 'Capitol Region Wholesale', 'Capitol Region Wholesale', 'Carroll', 'Kunze', 'orders11@vendor-demo.local', '860-555-2010', NULL, '141 Industrial Pkwy', NULL, 'New Haven', 'CT', '06747', 'USA', 'Net 30', 'A10010', 0.00, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40', NULL),
(12, 'V0012', 'Pioneer Pack & Ship Supply', 'Pioneer Pack & Ship Supply', 'Mohamed', 'Boehm', 'orders12@vendor-demo.local', '860-555-2011', NULL, '499 Industrial Pkwy', NULL, 'Bridgeport', 'CT', '06275', 'USA', 'Net 30', 'A10011', 0.00, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:44', NULL),
(13, 'V0013', 'Green Mountain Beverage Co', 'Green Mountain Beverage Co', 'Kip', 'Kub', 'orders13@vendor-demo.local', '860-555-2012', NULL, '358 Industrial Pkwy', NULL, 'Hartford', 'CT', '06346', 'USA', 'Net 30', 'A10012', 1248.85, 'Preferred weekly delivery Tuesday.', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:44', NULL),
(14, 'V0014', 'Coastal Energy Drink Brokers', 'Coastal Energy Drink Brokers', 'Charles', 'Balistreri', 'orders14@vendor-demo.local', '860-555-2013', NULL, '394 Industrial Pkwy', NULL, 'Bridgeport', 'CT', '06388', 'USA', 'Net 30', 'A10013', 976.17, NULL, 1, '2026-09-10 11:00:40', '2026-09-10 11:00:44', NULL),
(15, 'V0015', 'Inactive Vendor Holdings', 'Inactive Vendor Holdings', 'Ricardo', 'Satterfield', 'orders15@vendor-demo.local', '860-555-2014', NULL, '329 Industrial Pkwy', NULL, 'New Haven', 'CT', '06179', 'USA', 'Net 30', 'A10014', 0.00, NULL, 0, '2026-09-10 11:00:40', '2026-09-10 11:00:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_bills`
--

CREATE TABLE `vendor_bills` (
  `id` bigint UNSIGNED NOT NULL,
  `bill_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `goods_receipt_id` bigint UNSIGNED DEFAULT NULL,
  `bill_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_due` decimal(15,2) NOT NULL DEFAULT '0.00',
  `memo` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_bills`
--

INSERT INTO `vendor_bills` (`id`, `bill_number`, `ref_no`, `vendor_id`, `goods_receipt_id`, `bill_date`, `due_date`, `status`, `subtotal`, `total`, `amount_paid`, `balance_due`, `memo`, `created_at`, `updated_at`) VALUES
(1, 'BILL-00002', 'VREF-2', 2, 1, '2026-08-11', '2026-09-10', 'partial', 1446.50, 1446.50, 723.25, 723.25, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 'BILL-00003', 'VREF-3', 3, 2, '2026-08-04', '2026-09-03', 'open', 936.38, 936.38, 0.00, 936.38, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 'BILL-00004', 'VREF-4', 4, 3, '2026-07-28', '2026-08-27', 'paid', 2475.50, 2475.50, 2475.50, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 'BILL-00007', 'VREF-7', 7, 4, '2026-07-07', '2026-08-06', 'open', 1745.30, 1745.30, 0.00, 1745.30, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 'BILL-00008', 'VREF-8', 8, 5, '2026-06-30', '2026-07-30', 'paid', 2609.30, 2609.30, 2609.30, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 'BILL-00009', 'VREF-9', 9, 6, '2026-06-23', '2026-07-23', 'open', 1036.18, 1036.18, 0.00, 1036.18, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(7, 'BILL-00012', 'VREF-12', 12, 7, '2026-06-02', '2026-07-02', 'paid', 975.66, 975.66, 975.66, 0.00, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(8, 'BILL-00013', 'VREF-13', 13, 8, '2026-05-26', '2026-06-25', 'open', 1248.85, 1248.85, 0.00, 1248.85, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(9, 'BILL-00014', 'VREF-14', 14, 9, '2026-05-19', '2026-06-18', 'partial', 1952.35, 1952.35, 976.18, 976.17, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(10, 'BILL-00017', 'VREF-17', 3, 10, '2026-04-28', '2026-05-28', 'open', 1922.80, 1922.80, 0.00, 1922.80, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(11, 'BILL-00018', 'VREF-18', 4, 11, '2026-04-21', '2026-05-21', 'partial', 694.96, 694.96, 347.48, 347.48, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(12, 'BILL-00019', 'VREF-19', 5, 12, '2026-04-14', '2026-05-14', 'open', 2509.40, 2509.40, 0.00, 2509.40, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(13, 'BILL-00022', 'VREF-22', 8, 13, '2026-03-24', '2026-04-23', 'partial', 2809.75, 2809.75, 1404.88, 1404.87, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(14, 'BILL-00023', 'VREF-23', 9, 14, '2026-03-17', '2026-04-16', 'open', 2624.00, 2624.00, 0.00, 2624.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(15, 'BILL-00024', 'VREF-24', 10, 15, '2026-03-10', '2026-04-09', 'paid', 1528.93, 1528.93, 1528.93, 0.00, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_bill_lines`
--

CREATE TABLE `vendor_bill_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_bill_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `rate` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_bill_lines`
--

INSERT INTO `vendor_bill_lines` (`id`, `vendor_bill_id`, `item_id`, `description`, `quantity`, `rate`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 77, 'Received stock', 14.0000, 18.35, 256.90, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 1, 78, 'Received stock', 25.0000, 18.70, 467.50, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 1, 104, 'Received stock', 29.0000, 18.55, 537.95, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 1, 107, 'Received stock', 29.0000, 6.35, 184.15, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 2, 4, 'Received stock', 8.0000, 39.05, 312.40, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 2, 72, 'Received stock', 13.0000, 25.85, 336.05, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(7, 2, 79, 'Received stock', 8.5000, 19.05, 161.93, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(8, 2, 91, 'Received stock', 9.0000, 14.00, 126.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(9, 3, 25, 'Received stock', 34.0000, 21.15, 719.10, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(10, 3, 50, 'Received stock', 20.0000, 29.40, 588.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(11, 3, 87, 'Received stock', 24.0000, 21.85, 524.40, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(12, 3, 97, 'Received stock', 40.0000, 16.10, 644.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(13, 4, 24, 'Received stock', 24.0000, 20.80, 499.20, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(14, 4, 46, 'Received stock', 15.0000, 28.00, 420.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(15, 4, 61, 'Received stock', 18.0000, 22.00, 396.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(16, 4, 78, 'Received stock', 23.0000, 18.70, 430.10, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(17, 5, 17, 'Received stock', 22.0000, 18.35, 403.70, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(18, 5, 23, 'Received stock', 37.0000, 20.45, 756.65, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(19, 5, 75, 'Received stock', 29.0000, 26.90, 780.10, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(20, 5, 100, 'Received stock', 39.0000, 17.15, 668.85, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(21, 6, 34, 'Received stock', 15.0000, 13.05, 195.75, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(22, 6, 42, 'Received stock', 17.5000, 15.85, 277.38, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(23, 6, 80, 'Received stock', 14.0000, 19.40, 271.60, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(24, 6, 82, 'Received stock', 14.5000, 20.10, 291.45, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(25, 7, 10, 'Received stock', 7.5000, 41.15, 308.63, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(26, 7, 33, 'Received stock', 16.0000, 12.70, 203.20, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(27, 7, 36, 'Received stock', 20.0000, 13.75, 275.00, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(28, 7, 49, 'Received stock', 6.5000, 29.05, 188.83, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(29, 8, 17, 'Received stock', 17.0000, 18.35, 311.95, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(30, 8, 19, 'Received stock', 16.0000, 19.05, 304.80, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(31, 8, 62, 'Received stock', 21.0000, 22.35, 469.35, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(32, 8, 111, 'Received stock', 21.0000, 7.75, 162.75, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(33, 9, 42, 'Received stock', 13.0000, 15.85, 206.05, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(34, 9, 60, 'Received stock', 18.0000, 32.90, 592.20, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(35, 9, 62, 'Received stock', 38.0000, 22.35, 849.30, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(36, 9, 79, 'Received stock', 16.0000, 19.05, 304.80, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(37, 10, 39, 'Received stock', 30.0000, 14.80, 444.00, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(38, 10, 65, 'Received stock', 37.0000, 23.40, 865.80, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(39, 10, 112, 'Received stock', 34.0000, 8.10, 275.40, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(40, 10, 119, 'Received stock', 32.0000, 10.55, 337.60, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(41, 11, 16, 'Received stock', 7.0000, 18.00, 126.00, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(42, 11, 34, 'Received stock', 19.5000, 13.05, 254.48, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(43, 11, 62, 'Received stock', 11.0000, 22.35, 245.85, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(44, 11, 115, 'Received stock', 7.5000, 9.15, 68.63, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(45, 12, 18, 'Received stock', 16.0000, 18.70, 299.20, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(46, 12, 25, 'Received stock', 37.0000, 21.15, 782.55, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(47, 12, 59, 'Received stock', 31.0000, 32.55, 1009.05, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(48, 12, 97, 'Received stock', 26.0000, 16.10, 418.60, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(49, 13, 8, 'Received stock', 38.0000, 40.45, 1537.10, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(50, 13, 20, 'Received stock', 16.0000, 19.40, 310.40, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(51, 13, 23, 'Received stock', 21.0000, 20.45, 429.45, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(52, 13, 88, 'Received stock', 24.0000, 22.20, 532.80, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(53, 14, 25, 'Received stock', 34.0000, 21.15, 719.10, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(54, 14, 48, 'Received stock', 32.0000, 28.70, 918.40, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(55, 14, 85, 'Received stock', 20.0000, 21.15, 423.00, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(56, 14, 97, 'Received stock', 35.0000, 16.10, 563.50, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(57, 15, 5, 'Received stock', 20.0000, 39.40, 788.00, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(58, 15, 13, 'Received stock', 5.5000, 42.20, 232.10, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(59, 15, 40, 'Received stock', 7.5000, 15.15, 113.63, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(60, 15, 84, 'Received stock', 19.0000, 20.80, 395.20, '2026-09-10 11:00:45', '2026-09-10 11:00:45');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_contacts`
--

CREATE TABLE `vendor_contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_contacts`
--

INSERT INTO `vendor_contacts` (`id`, `vendor_id`, `name`, `title`, `email`, `phone`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jovanny Kuhlman', 'Sales Rep', 'orders1@vendor-demo.local', '860-555-2000', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(2, 2, 'Weldon Larkin', 'Sales Rep', 'orders2@vendor-demo.local', '860-555-2001', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(3, 3, 'Jadon Will', 'Sales Rep', 'orders3@vendor-demo.local', '860-555-2002', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(4, 4, 'Macy Ferry', 'Sales Rep', 'orders4@vendor-demo.local', '860-555-2003', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(5, 5, 'Jacky Nader', 'Sales Rep', 'orders5@vendor-demo.local', '860-555-2004', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(6, 6, 'Mona Hickle', 'Sales Rep', 'orders6@vendor-demo.local', '860-555-2005', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(7, 7, 'Aleen Bergnaum', 'Sales Rep', 'orders7@vendor-demo.local', '860-555-2006', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(8, 8, 'Myriam Schaden', 'Sales Rep', 'orders8@vendor-demo.local', '860-555-2007', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(9, 9, 'Rosario Schmidt', 'Sales Rep', 'orders9@vendor-demo.local', '860-555-2008', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(10, 10, 'Mohamed Pfannerstill', 'Sales Rep', 'orders10@vendor-demo.local', '860-555-2009', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(11, 11, 'Carroll Kunze', 'Sales Rep', 'orders11@vendor-demo.local', '860-555-2010', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(12, 12, 'Mohamed Boehm', 'Sales Rep', 'orders12@vendor-demo.local', '860-555-2011', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(13, 13, 'Kip Kub', 'Sales Rep', 'orders13@vendor-demo.local', '860-555-2012', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(14, 14, 'Charles Balistreri', 'Sales Rep', 'orders14@vendor-demo.local', '860-555-2013', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40'),
(15, 15, 'Ricardo Satterfield', 'Sales Rep', 'orders15@vendor-demo.local', '860-555-2014', 1, '2026-09-10 11:00:40', '2026-09-10 11:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_notes`
--

CREATE TABLE `vendor_notes` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'check',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `memo` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_payments`
--

INSERT INTO `vendor_payments` (`id`, `payment_number`, `vendor_id`, `payment_date`, `amount`, `method`, `reference`, `bank_account_id`, `memo`, `created_at`, `updated_at`) VALUES
(1, 'VP-00002', 2, '2026-08-27', 723.25, 'check', 'CHK-4002', 2, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 'VP-00004', 4, '2026-08-13', 2475.50, 'check', 'CHK-4004', 2, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 'VP-00008', 8, '2026-07-16', 2609.30, 'check', 'CHK-4008', 2, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 'VP-00012', 12, '2026-06-18', 975.66, 'check', 'CHK-4012', 2, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 'VP-00014', 14, '2026-06-04', 976.18, 'check', 'CHK-4014', 2, NULL, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 'VP-00018', 4, '2026-05-07', 347.48, 'check', 'CHK-4018', 2, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(7, 'VP-00022', 8, '2026-04-09', 1404.88, 'check', 'CHK-4022', 2, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(8, 'VP-00024', 10, '2026-03-26', 1528.93, 'check', 'CHK-4024', 2, NULL, '2026-09-10 11:00:45', '2026-09-10 11:00:45');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payment_allocations`
--

CREATE TABLE `vendor_payment_allocations` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_payment_id` bigint UNSIGNED NOT NULL,
  `vendor_bill_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_payment_allocations`
--

INSERT INTO `vendor_payment_allocations` (`id`, `vendor_payment_id`, `vendor_bill_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 723.25, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(2, 2, 3, 2475.50, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(3, 3, 5, 2609.30, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(4, 4, 7, 975.66, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(5, 5, 9, 976.18, '2026-09-10 11:00:44', '2026-09-10 11:00:44'),
(6, 6, 11, 347.48, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(7, 7, 13, 1404.88, '2026-09-10 11:00:45', '2026-09-10 11:00:45'),
(8, 8, 15, 1528.93, '2026-09-10 11:00:45', '2026-09-10 11:00:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounts_number_unique` (`number`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`),
  ADD KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_accounts_account_id_foreign` (`account_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `checks`
--
ALTER TABLE `checks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `checks_check_number_unique` (`check_number`),
  ADD KEY `checks_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `checks_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `credit_memos`
--
ALTER TABLE `credit_memos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `credit_memos_credit_number_unique` (`credit_number`),
  ADD KEY `credit_memos_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `credit_memo_allocations`
--
ALTER TABLE `credit_memo_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_memo_allocations_invoice_id_foreign` (`invoice_id`),
  ADD KEY `credit_memo_allocations_credit_memo_id_invoice_id_index` (`credit_memo_id`,`invoice_id`);

--
-- Indexes for table `credit_memo_lines`
--
ALTER TABLE `credit_memo_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_memo_lines_credit_memo_id_foreign` (`credit_memo_id`),
  ADD KEY `credit_memo_lines_item_id_foreign` (`item_id`);

--
-- Indexes for table `credit_memo_refunds`
--
ALTER TABLE `credit_memo_refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_memo_refunds_credit_memo_id_foreign` (`credit_memo_id`),
  ADD KEY `credit_memo_refunds_account_id_foreign` (`account_id`),
  ADD KEY `credit_memo_refunds_created_by_foreign` (`created_by`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_customer_number_unique` (`customer_number`),
  ADD KEY `customers_price_level_id_foreign` (`price_level_id`),
  ADD KEY `customers_tax_code_id_foreign` (`tax_code_id`),
  ADD KEY `customers_company_name_index` (`company_name`),
  ADD KEY `customers_display_name_index` (`display_name`),
  ADD KEY `customers_email_index` (`email`),
  ADD KEY `customers_phone_index` (`phone`),
  ADD KEY `customers_is_active_index` (`is_active`);

--
-- Indexes for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_contacts_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `customer_notes`
--
ALTER TABLE `customer_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_notes_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_notes_user_id_foreign` (`user_id`);

--
-- Indexes for table `customer_todos`
--
ALTER TABLE `customer_todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_todos_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_todos_assigned_to_foreign` (`assigned_to`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deposits_number_unique` (`number`),
  ADD KEY `deposits_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `deposit_items`
--
ALTER TABLE `deposit_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deposit_items_deposit_id_foreign` (`deposit_id`),
  ADD KEY `deposit_items_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goods_receipts_number_unique` (`number`),
  ADD KEY `goods_receipts_vendor_id_foreign` (`vendor_id`),
  ADD KEY `goods_receipts_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `goods_receipts_created_by_foreign` (`created_by`);

--
-- Indexes for table `goods_receipt_lines`
--
ALTER TABLE `goods_receipt_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_lines_goods_receipt_id_foreign` (`goods_receipt_id`),
  ADD KEY `goods_receipt_lines_item_id_foreign` (`item_id`),
  ADD KEY `goods_receipt_lines_purchase_order_line_id_foreign` (`purchase_order_line_id`);

--
-- Indexes for table `import_batches`
--
ALTER TABLE `import_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `import_batches_uuid_unique` (`uuid`),
  ADD KEY `import_batches_created_by_foreign` (`created_by`),
  ADD KEY `import_batches_entity_status_index` (`entity`,`status`);

--
-- Indexes for table `import_rows`
--
ALTER TABLE `import_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `import_rows_import_batch_id_stage_index` (`import_batch_id`,`stage`),
  ADD KEY `import_rows_production_type_production_id_index` (`production_type`,`production_id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_transactions_created_by_foreign` (`created_by`),
  ADD KEY `inventory_transactions_item_id_created_at_index` (`item_id`,`created_at`),
  ADD KEY `inventory_transactions_reference_type_reference_id_index` (`reference_type`,`reference_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_sales_order_id_foreign` (`sales_order_id`),
  ADD KEY `invoices_tax_code_id_foreign` (`tax_code_id`),
  ADD KEY `invoices_created_by_foreign` (`created_by`),
  ADD KEY `invoices_customer_id_invoice_date_index` (`customer_id`,`invoice_date`),
  ADD KEY `invoices_invoice_date_index` (`invoice_date`);

--
-- Indexes for table `invoice_lines`
--
ALTER TABLE `invoice_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_lines_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_lines_item_id_foreign` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_sku_unique` (`sku`),
  ADD KEY `items_parent_id_foreign` (`parent_id`),
  ADD KEY `items_unit_of_measure_id_foreign` (`unit_of_measure_id`),
  ADD KEY `items_preferred_vendor_id_foreign` (`preferred_vendor_id`),
  ADD KEY `items_tax_code_id_foreign` (`tax_code_id`),
  ADD KEY `items_item_type_id_foreign` (`item_type_id`),
  ADD KEY `items_name_index` (`name`),
  ADD KEY `items_item_category_id_index` (`item_category_id`),
  ADD KEY `items_is_active_index` (`is_active`),
  ADD KEY `items_barcode_index` (`barcode`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_categories_code_unique` (`code`);

--
-- Indexes for table `item_histories`
--
ALTER TABLE `item_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_histories_created_by_foreign` (`created_by`),
  ADD KEY `item_histories_item_id_occurred_at_index` (`item_id`,`occurred_at`),
  ADD KEY `item_histories_item_id_event_index` (`item_id`,`event`),
  ADD KEY `item_histories_reference_type_reference_id_index` (`reference_type`,`reference_id`);

--
-- Indexes for table `item_notes`
--
ALTER TABLE `item_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_notes_item_id_foreign` (`item_id`),
  ADD KEY `item_notes_user_id_foreign` (`user_id`);

--
-- Indexes for table `item_prices`
--
ALTER TABLE `item_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_prices_item_id_price_level_id_unique` (`item_id`,`price_level_id`),
  ADD KEY `item_prices_price_level_id_foreign` (`price_level_id`);

--
-- Indexes for table `item_types`
--
ALTER TABLE `item_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_types_name_unique` (`name`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_entries_entry_number_unique` (`entry_number`),
  ADD KEY `journal_entries_created_by_foreign` (`created_by`),
  ADD KEY `journal_entries_reference_type_reference_id_index` (`reference_type`,`reference_id`);

--
-- Indexes for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_lines_journal_entry_id_foreign` (`journal_entry_id`),
  ADD KEY `journal_lines_account_id_foreign` (`account_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `payments_payment_number_unique` (`payment_number`),
  ADD KEY `payments_deposit_to_account_id_foreign` (`deposit_to_account_id`),
  ADD KEY `payments_created_by_foreign` (`created_by`),
  ADD KEY `payments_customer_id_payment_date_index` (`customer_id`,`payment_date`);

--
-- Indexes for table `payment_allocations`
--
ALTER TABLE `payment_allocations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_allocations_payment_id_invoice_id_unique` (`payment_id`,`invoice_id`),
  ADD KEY `payment_allocations_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`),
  ADD KEY `permissions_group_index` (`group`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_role_permission_id_role_id_unique` (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `price_levels`
--
ALTER TABLE `price_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `price_levels_name_unique` (`name`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_number_unique` (`number`),
  ADD KEY `purchase_orders_vendor_id_index` (`vendor_id`);

--
-- Indexes for table `purchase_order_lines`
--
ALTER TABLE `purchase_order_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_lines_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_lines_item_id_foreign` (`item_id`);

--
-- Indexes for table `qb_import_tables`
--
ALTER TABLE `qb_import_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotes_number_unique` (`number`),
  ADD KEY `quotes_customer_id_foreign` (`customer_id`),
  ADD KEY `quotes_converted_invoice_id_foreign` (`converted_invoice_id`);

--
-- Indexes for table `quote_lines`
--
ALTER TABLE `quote_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quote_lines_quote_id_foreign` (`quote_id`),
  ADD KEY `quote_lines_item_id_foreign` (`item_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_user_role_id_user_id_unique` (`role_id`,`user_id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_orders_number_unique` (`number`),
  ADD KEY `sales_orders_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_orders_quote_id_foreign` (`quote_id`);

--
-- Indexes for table `sales_order_lines`
--
ALTER TABLE `sales_order_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_lines_sales_order_id_foreign` (`sales_order_id`),
  ADD KEY `sales_order_lines_item_id_foreign` (`item_id`);

--
-- Indexes for table `sales_receipts`
--
ALTER TABLE `sales_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_receipts_number_unique` (`number`),
  ADD KEY `sales_receipts_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `sales_receipt_lines`
--
ALTER TABLE `sales_receipt_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_receipt_lines_sales_receipt_id_foreign` (`sales_receipt_id`),
  ADD KEY `sales_receipt_lines_item_id_foreign` (`item_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`),
  ADD KEY `settings_group_index` (`group`);

--
-- Indexes for table `tax_codes`
--
ALTER TABLE `tax_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tax_codes_code_unique` (`code`);

--
-- Indexes for table `units_of_measure`
--
ALTER TABLE `units_of_measure`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `units_of_measure_name_unique` (`name`);

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
  ADD UNIQUE KEY `vendors_vendor_number_unique` (`vendor_number`),
  ADD KEY `vendors_company_name_index` (`company_name`),
  ADD KEY `vendors_display_name_index` (`display_name`),
  ADD KEY `vendors_email_index` (`email`),
  ADD KEY `vendors_phone_index` (`phone`),
  ADD KEY `vendors_is_active_index` (`is_active`);

--
-- Indexes for table `vendor_bills`
--
ALTER TABLE `vendor_bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_bills_bill_number_unique` (`bill_number`),
  ADD KEY `vendor_bills_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_bills_goods_receipt_id_foreign` (`goods_receipt_id`);

--
-- Indexes for table `vendor_bill_lines`
--
ALTER TABLE `vendor_bill_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_bill_lines_vendor_bill_id_foreign` (`vendor_bill_id`),
  ADD KEY `vendor_bill_lines_item_id_foreign` (`item_id`);

--
-- Indexes for table `vendor_contacts`
--
ALTER TABLE `vendor_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_contacts_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendor_notes`
--
ALTER TABLE `vendor_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_notes_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_notes_user_id_foreign` (`user_id`);

--
-- Indexes for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_payments_payment_number_unique` (`payment_number`),
  ADD KEY `vendor_payments_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_payments_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `vendor_payment_allocations`
--
ALTER TABLE `vendor_payment_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_payment_allocations_vendor_payment_id_foreign` (`vendor_payment_id`),
  ADD KEY `vendor_payment_allocations_vendor_bill_id_foreign` (`vendor_bill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `checks`
--
ALTER TABLE `checks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `credit_memos`
--
ALTER TABLE `credit_memos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `credit_memo_allocations`
--
ALTER TABLE `credit_memo_allocations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_memo_lines`
--
ALTER TABLE `credit_memo_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `credit_memo_refunds`
--
ALTER TABLE `credit_memo_refunds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `customer_notes`
--
ALTER TABLE `customer_notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer_todos`
--
ALTER TABLE `customer_todos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deposit_items`
--
ALTER TABLE `deposit_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `goods_receipt_lines`
--
ALTER TABLE `goods_receipt_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `import_batches`
--
ALTER TABLE `import_batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `import_rows`
--
ALTER TABLE `import_rows`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=467;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `invoice_lines`
--
ALTER TABLE `invoice_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item_histories`
--
ALTER TABLE `item_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_notes`
--
ALTER TABLE `item_notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_prices`
--
ALTER TABLE `item_prices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=601;

--
-- AUTO_INCREMENT for table `item_types`
--
ALTER TABLE `item_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `journal_lines`
--
ALTER TABLE `journal_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=537;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `payment_allocations`
--
ALTER TABLE `payment_allocations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `price_levels`
--
ALTER TABLE `price_levels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `purchase_order_lines`
--
ALTER TABLE `purchase_order_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `qb_import_tables`
--
ALTER TABLE `qb_import_tables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `quote_lines`
--
ALTER TABLE `quote_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `sales_order_lines`
--
ALTER TABLE `sales_order_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `sales_receipts`
--
ALTER TABLE `sales_receipts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales_receipt_lines`
--
ALTER TABLE `sales_receipt_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tax_codes`
--
ALTER TABLE `tax_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `units_of_measure`
--
ALTER TABLE `units_of_measure`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vendor_bills`
--
ALTER TABLE `vendor_bills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vendor_bill_lines`
--
ALTER TABLE `vendor_bill_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `vendor_contacts`
--
ALTER TABLE `vendor_contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vendor_notes`
--
ALTER TABLE `vendor_notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vendor_payment_allocations`
--
ALTER TABLE `vendor_payment_allocations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD CONSTRAINT `bank_accounts_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `checks`
--
ALTER TABLE `checks`
  ADD CONSTRAINT `checks_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`),
  ADD CONSTRAINT `checks_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `credit_memos`
--
ALTER TABLE `credit_memos`
  ADD CONSTRAINT `credit_memos_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `credit_memo_allocations`
--
ALTER TABLE `credit_memo_allocations`
  ADD CONSTRAINT `credit_memo_allocations_credit_memo_id_foreign` FOREIGN KEY (`credit_memo_id`) REFERENCES `credit_memos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_memo_allocations_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `credit_memo_lines`
--
ALTER TABLE `credit_memo_lines`
  ADD CONSTRAINT `credit_memo_lines_credit_memo_id_foreign` FOREIGN KEY (`credit_memo_id`) REFERENCES `credit_memos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `credit_memo_lines_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `credit_memo_refunds`
--
ALTER TABLE `credit_memo_refunds`
  ADD CONSTRAINT `credit_memo_refunds_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `credit_memo_refunds_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `credit_memo_refunds_credit_memo_id_foreign` FOREIGN KEY (`credit_memo_id`) REFERENCES `credit_memos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_price_level_id_foreign` FOREIGN KEY (`price_level_id`) REFERENCES `price_levels` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customers_tax_code_id_foreign` FOREIGN KEY (`tax_code_id`) REFERENCES `tax_codes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD CONSTRAINT `customer_contacts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_notes`
--
ALTER TABLE `customer_notes`
  ADD CONSTRAINT `customer_notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_todos`
--
ALTER TABLE `customer_todos`
  ADD CONSTRAINT `customer_todos_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_todos_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deposits`
--
ALTER TABLE `deposits`
  ADD CONSTRAINT `deposits_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`);

--
-- Constraints for table `deposit_items`
--
ALTER TABLE `deposit_items`
  ADD CONSTRAINT `deposit_items_deposit_id_foreign` FOREIGN KEY (`deposit_id`) REFERENCES `deposits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deposit_items_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD CONSTRAINT `goods_receipts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `goods_receipts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `goods_receipt_lines`
--
ALTER TABLE `goods_receipt_lines`
  ADD CONSTRAINT `goods_receipt_lines_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipt_lines_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `goods_receipt_lines_purchase_order_line_id_foreign` FOREIGN KEY (`purchase_order_line_id`) REFERENCES `purchase_order_lines` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `import_batches`
--
ALTER TABLE `import_batches`
  ADD CONSTRAINT `import_batches_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `import_rows`
--
ALTER TABLE `import_rows`
  ADD CONSTRAINT `import_rows_import_batch_id_foreign` FOREIGN KEY (`import_batch_id`) REFERENCES `import_batches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_transactions_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `invoices_sales_order_id_foreign` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_tax_code_id_foreign` FOREIGN KEY (`tax_code_id`) REFERENCES `tax_codes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_lines`
--
ALTER TABLE `invoice_lines`
  ADD CONSTRAINT `invoice_lines_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_lines_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_item_category_id_foreign` FOREIGN KEY (`item_category_id`) REFERENCES `item_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_item_type_id_foreign` FOREIGN KEY (`item_type_id`) REFERENCES `item_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_preferred_vendor_id_foreign` FOREIGN KEY (`preferred_vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_tax_code_id_foreign` FOREIGN KEY (`tax_code_id`) REFERENCES `tax_codes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_unit_of_measure_id_foreign` FOREIGN KEY (`unit_of_measure_id`) REFERENCES `units_of_measure` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_histories`
--
ALTER TABLE `item_histories`
  ADD CONSTRAINT `item_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_histories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_notes`
--
ALTER TABLE `item_notes`
  ADD CONSTRAINT `item_notes_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_prices`
--
ALTER TABLE `item_prices`
  ADD CONSTRAINT `item_prices_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_prices_price_level_id_foreign` FOREIGN KEY (`price_level_id`) REFERENCES `price_levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD CONSTRAINT `journal_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `journal_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `payments_deposit_to_account_id_foreign` FOREIGN KEY (`deposit_to_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_allocations`
--
ALTER TABLE `payment_allocations`
  ADD CONSTRAINT `payment_allocations_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_allocations_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `purchase_order_lines`
--
ALTER TABLE `purchase_order_lines`
  ADD CONSTRAINT `purchase_order_lines_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `purchase_order_lines_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_converted_invoice_id_foreign` FOREIGN KEY (`converted_invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `quote_lines`
--
ALTER TABLE `quote_lines`
  ADD CONSTRAINT `quote_lines_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `quote_lines_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD CONSTRAINT `sales_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `sales_orders_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_order_lines`
--
ALTER TABLE `sales_order_lines`
  ADD CONSTRAINT `sales_order_lines_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `sales_order_lines_sales_order_id_foreign` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_receipts`
--
ALTER TABLE `sales_receipts`
  ADD CONSTRAINT `sales_receipts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_receipt_lines`
--
ALTER TABLE `sales_receipt_lines`
  ADD CONSTRAINT `sales_receipt_lines_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `sales_receipt_lines_sales_receipt_id_foreign` FOREIGN KEY (`sales_receipt_id`) REFERENCES `sales_receipts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_bills`
--
ALTER TABLE `vendor_bills`
  ADD CONSTRAINT `vendor_bills_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendor_bills_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `vendor_bill_lines`
--
ALTER TABLE `vendor_bill_lines`
  ADD CONSTRAINT `vendor_bill_lines_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendor_bill_lines_vendor_bill_id_foreign` FOREIGN KEY (`vendor_bill_id`) REFERENCES `vendor_bills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_contacts`
--
ALTER TABLE `vendor_contacts`
  ADD CONSTRAINT `vendor_contacts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_notes`
--
ALTER TABLE `vendor_notes`
  ADD CONSTRAINT `vendor_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendor_notes_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD CONSTRAINT `vendor_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendor_payments_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `vendor_payment_allocations`
--
ALTER TABLE `vendor_payment_allocations`
  ADD CONSTRAINT `vendor_payment_allocations_vendor_bill_id_foreign` FOREIGN KEY (`vendor_bill_id`) REFERENCES `vendor_bills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_payment_allocations_vendor_payment_id_foreign` FOREIGN KEY (`vendor_payment_id`) REFERENCES `vendor_payments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
