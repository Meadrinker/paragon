-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 25 Mar 2020, 17:07
-- Wersja serwera: 5.7.26-0ubuntu0.18.04.1
-- Wersja PHP: 7.2.17-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `paragon`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `date` varchar(30) CHARACTER SET utf8 NOT NULL,
  `shop` varchar(30) CHARACTER SET utf8 NOT NULL,
  `image` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `bills`
--

INSERT INTO `bills` (`id`, `user`, `name`, `date`, `shop`, `image`) VALUES
(1, 1, 'zakupy auchan', '14-09-2018 15:36:12', 'auchan', 'paragonik.jpg'),
(2, 1, 'tv biedra', '27-04-2016 21:37:01', 'biedronka', '263c5dbe4cf97cbcbf7f9b98c5b26c94.jpeg'),
(3, 1, 'zakupy lidl', '27-04-2016 21:37:01', 'lidl', 'paraugon.jpeg'),
(4, 2, 'tesco', '27-03-2017 21:36:02', 'tesco', 'obrazek.png'),
(15, 1, 'tv biedra', '27-04-2016 21:37:01', 'biedronka', 'd315fb0977ccfd7f7673841a637386d9.jpeg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `bill` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `type` varchar(64) CHARACTER SET utf8 NOT NULL,
  `guarantee` text CHARACTER SET utf8 NOT NULL,
  `price` float(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`id`, `bill`, `name`, `type`, `guarantee`, `price`) VALUES
(7, 2, 'telewizor', 'rtv', '3', 799.00),
(8, 2, 'telewizor', 'rtv', '3', 799.00),
(9, 2, 'telewizor', 'rtv', '3', 799.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`) VALUES
(1, 7, '819581b32bd5237b6c58307c0ad388f8.jpeg'),
(2, 7, '8e28639b64a97886016300ca7d83d778.jpeg'),
(3, 8, 'a38daddbf8a537633a49e03a6de189b1.jpeg'),
(4, 8, 'd951243ce61e04feb841fd9c0c819818.jpeg'),
(5, 9, '62b0c5b8bf98f8c70bfe81f772fe3410.jpeg'),
(6, 9, '6cc12a1270f93dd6ad97e4f04e521cf8.jpeg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'user', 'zwykły użytkownik'),
(2, 'admin', 'administrator');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `email` varchar(64) CHARACTER SET utf8 NOT NULL,
  `password` varchar(20) CHARACTER SET utf8 NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin1', 2),
(2, 'dawid', 'dawid@gmail.com', 'krecik', 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- Indeksy dla tabeli `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill` (`bill`);

--
-- Indeksy dla tabeli `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeksy dla tabeli `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT dla tabeli `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT dla tabeli `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Ograniczenia dla tabeli `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`bill`) REFERENCES `bills` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
