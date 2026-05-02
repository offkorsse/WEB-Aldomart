-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Bulan Mei 2026 pada 08.51
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aldomart_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `category`, `image`) VALUES
(1, 'Minute Maid Pulpy Minuman Jus Buah 350 ml', 6900, 63, 'Minuman Ringan', 'https://i.pinimg.com/736x/3a/43/04/3a4304bea31f34540cfe3e041df5df66.jpg'),
(2, 'Fruit Tea Minuman Teh Freeze 350 ml', 4500, 39, 'Minuman Teh', 'https://i.pinimg.com/webp85/736x/08/89/2f/08892f4d735b217c578964617b3240e0.webp'),
(3, 'Nutriboost Minuman Susu Rasa Stroberi 200 ml', 7700, 89, 'Minuman', 'https://i.pinimg.com/736x/eb/d1/6e/ebd16e12104c9f9f9eefd4eb6809124d.jpg'),
(4, 'mentos Permen Rainbow Roll 37 g', 4800, 44, 'Permen', 'https://i.pinimg.com/736x/70/62/ab/7062ab1a7721cb53f9f53612bf3708ff.jpg'),
(5, 'mentos Permen Mint Roll 37 g', 4800, 34, 'Permen', 'https://i.pinimg.com/736x/03/64/c2/0364c2e664021804b17523013bbb7993.jpg'),
(6, 'ABC Minuman Sari Kacang Hijau 250 ml', 5800, 28, 'Minuman Ringan', 'https://i.pinimg.com/736x/13/0e/2b/130e2bc49b5af8d423117e4cefa3998b.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
