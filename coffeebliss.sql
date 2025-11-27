-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 06:33 PM
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
-- Database: `coffeebliss`
--

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `category` varchar(20) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `total_sales` decimal(10,2) GENERATED ALWAYS AS (`price` * `quantity_sold`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `sale_date`, `product_id`, `product_name`, `category`, `price`, `quantity_sold`) VALUES
(1, '2025-10-01', 'P001', 'Americano', 'Brewed Coffee', 3.50, 45),
(2, '2025-10-01', 'P002', 'Cappuccino', 'Espresso', 4.50, 68),
(3, '2025-10-01', 'P003', 'Latte', 'Espresso', 4.80, 82),
(4, '2025-10-01', 'P004', 'Croissant', 'Pastry', 3.20, 38),
(5, '2025-10-01', 'P005', 'Pumpkin Spice Latte', 'Seasonal', 5.50, 95),
(6, '2025-10-02', 'P001', 'Americano', 'Brewed Coffee', 3.50, 42),
(7, '2025-10-02', 'P002', 'Cappuccino', 'Espresso', 4.50, 71),
(8, '2025-10-02', 'P003', 'Latte', 'Espresso', 4.80, 88),
(9, '2025-10-02', 'P004', 'Croissant', 'Pastry', 3.20, 35),
(10, '2025-10-02', 'P005', 'Pumpkin Spice Latte', 'Seasonal', 5.50, 102),
(11, '2025-10-03', 'P001', 'Americano', 'Brewed Coffee', 3.50, 58),
(12, '2025-10-03', 'P002', 'Cappuccino', 'Espresso', 4.50, 65),
(13, '2025-10-03', 'P003', 'Latte', 'Espresso', 4.80, 91),
(14, '2025-10-03', 'P004', 'Croissant', 'Pastry', 3.20, 41),
(15, '2025-10-03', 'P005', 'Pumpkin Spice Latte', 'Seasonal', 5.50, 118),
(16, '2025-10-04', 'P001', 'Americano', 'Brewed Coffee', 3.50, 72),
(17, '2025-10-04', 'P002', 'Cappuccino', 'Espresso', 4.50, 79),
(18, '2025-10-04', 'P003', 'Latte', 'Espresso', 4.80, 105),
(19, '2025-10-04', 'P004', 'Croissant', 'Pastry', 3.20, 44),
(20, '2025-10-04', 'P005', 'Pumpkin Spice Latte', 'Seasonal', 5.50, 135);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
