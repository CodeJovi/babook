-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 30. Sep 2024 um 20:06
-- Server-Version: 10.4.21-MariaDB
-- PHP-Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `booke`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `books`
--

CREATE TABLE `books` (
  `book_id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `book_name` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `genres` varchar(255) NOT NULL,
  `will_trade_genres` varchar(255) NOT NULL,
  `book_image` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `books`
--

INSERT INTO `books` (`book_id`, `user_id`, `book_name`, `author`, `genres`, `will_trade_genres`, `book_image`, `upload_date`) VALUES
(20, 10, 'Funny Story', 'Emily Henry', 'Romantik', 'Romantik, Thriller, Krimis', 'uploads/funnystory.jpg', '2024-09-26 21:27:27'),
(21, 10, 'The Secret History', 'Donna Tart', 'Roman', 'Romantik, Thriller, Krimis', 'uploads/secret history.jpg', '2024-09-26 21:28:53'),
(31, 9, 'Shadow and Bone', 'Leigh Bardugo', 'Fantasy', 'Fantasy', 'uploads/shadow and bone.jpg', '2024-09-27 21:21:30'),
(38, 9, 'Six Crimson Cranes', 'Elizabeth Lim', 'Fantasy', 'Fantasy or Sci-Fi', 'uploads/66f8777f4a410.jpg', '2024-09-28 21:39:11'),
(39, 9, 'In the life of puppets', 'TJ Klune', 'Fantasy', 'Fantasy', 'uploads/66f877e198427.jpg', '2024-09-28 21:40:49'),
(41, 10, 'Think and grow rich', 'Napoleon Hill', 'Sachbuch', 'Romance books', 'uploads/66f8788320b03.jpg', '2024-09-28 21:43:31'),
(45, 10, '<b style=\"color:orange;\">Title</b>', 'JK Rowling', 'Roman', 'Novels', 'uploads/66fa035795f44.jpg', '2024-09-30 01:48:07'),
(46, 10, 'Small things like these', 'Claire Keegan', 'Roman', 'Novels, Fantasy', 'uploads/66fa0497b67d4.jpg', '2024-09-30 01:53:27'),
(47, 11, 'Oliver Twist', 'Charles Dickens', 'Klassiker', 'other classics', 'uploads/66fa5459a8bb3.jpg', '2024-09-30 07:33:45'),
(48, 8, 'Scary Smart', 'Mo Gawdat', 'Sachbuch', 'IT books, books about AI', 'uploads/66fa6e72036ef.jpg', '2024-09-30 09:25:06');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) UNSIGNED NOT NULL,
  `sender_id` int(6) UNSIGNED NOT NULL,
  `receiver_id` int(6) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(7, 9, 9, 'hi\r\n', '2024-09-27 00:34:33'),
(8, 8, 9, 'hi, i am interested in six crimson cranes, would you like to swap for some of my books? Kind greetings, Mary', '2024-09-28 22:48:33'),
(9, 8, 10, 'hi, i am interested in all of your books', '2024-09-29 18:18:07'),
(10, 8, 10, 'i would offer some of my books..', '2024-09-30 11:15:32'),
(14, 15, 10, 'i am interested the claire Keegan books.. wanna swap for some of mine?\r\n', '2024-09-30 12:22:24');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_info`
--

CREATE TABLE `user_info` (
  `id` int(6) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user_info`
--

INSERT INTO `user_info` (`id`, `username`, `email`, `password`, `reg_date`) VALUES
(8, 'Mary', 'mary@gmail.com', '$2y$10$mjqFkWm8dnmPTpfh7EpYGuKTDb2fmWjSjynyNh3kmrWIqwu7MUafG', '2024-09-26 21:05:54'),
(9, 'Filipe', 'filipe@gmail.com', '$2y$10$g3rdP0rsHmWEzX0rJJP/3uzizWMe1Ich2N61Fae8GaRvPKfK2QJtG', '2024-09-26 21:10:47'),
(10, 'Mei', 'mei@gmail.com', '$2y$10$gO8MDZRcCm/ZWmsctd1lBeut0KD3EaQFHsT0xZb25wTjSlTOfuuRu', '2024-09-26 21:19:54'),
(11, 'Boki', 'boki@hotmail.com', '$2y$10$Qyeg8EFnAQMh3b1F/jJauOmfnoNakPPH0Le6Wm4zG59K4GEON4e9.', '2024-09-26 21:36:30'),
(13, 'ZAP', 'zaproxy@example.com0W45pz4p', '$2y$10$BB7wpdFaxqvTYB1GGmgeG.i8Un4sOzLGqVJ3I3kaKxSXU81597n1.', '2024-09-29 18:23:56'),
(14, 'color:orange;\">Romana</b>', 'romana@gmail.com', '$2y$10$RfCMQ6nWWQjrCmdYirEejuIIlVf2D8FL5JKCr0H453lFDh5roGL.K', '2024-09-29 19:46:30'),
(15, 'Itsme', 'lela@gmail.com', '$2y$10$zTCjYjmPBfHWWB9HRPJAX.xXyfk94EHJ.oeGWIrC2ZAbFEJZRX8xS', '2024-09-30 12:21:33');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indizes für die Tabelle `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_info` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `user_info` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `user_info` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
