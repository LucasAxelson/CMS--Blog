-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 21, 2023 at 08:09 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

DROP TABLE IF EXISTS `access`;
CREATE TABLE IF NOT EXISTS `access` (
  `access_id` int NOT NULL AUTO_INCREMENT,
  `access_title` varchar(50) NOT NULL,
  PRIMARY KEY (`access_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`access_id`, `access_title`) VALUES
(1, 'Visitor'),
(2, 'Member'),
(3, 'Admin'),
(4, 'Senior Admin');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` int NOT NULL AUTO_INCREMENT,
  `cat_title` varchar(255) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_title`) VALUES
(1, 'JavaScript'),
(16, 'PHP'),
(21, 'Java'),
(22, 'C++'),
(23, 'C'),
(24, 'Node.js'),
(25, 'C#'),
(26, 'Laravel'),
(27, 'Symfony');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int NOT NULL AUTO_INCREMENT,
  `comment_post_id` int NOT NULL,
  `comment_reply_id` int DEFAULT NULL,
  `comment_author_id` int NOT NULL,
  `comment_email` varchar(100) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_author` varchar(100) NOT NULL,
  `comment_status_id` int NOT NULL,
  `comment_date` datetime NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_post_id` (`comment_post_id`),
  KEY `comment_status` (`comment_status_id`),
  KEY `comment_reply_id` (`comment_reply_id`),
  KEY `comment_author_id` (`comment_author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_post_id`, `comment_reply_id`, `comment_author_id`, `comment_email`, `comment_content`, `comment_author`, `comment_status_id`, `comment_date`) VALUES
(34, 41, NULL, 6, '', 'Add a photo.', '', 4, '2023-12-17 21:04:09'),
(35, 41, 34, 6, '', 'I did it!', '', 4, '2023-12-18 16:26:59'),
(36, 41, 34, 6, '', 'Test comment', '', 4, '2023-12-19 10:38:57'),
(38, 41, 34, 9, '', 'Stop spamming bro', '', 4, '2023-12-20 12:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int NOT NULL AUTO_INCREMENT,
  `post_category_id` int NOT NULL,
  `post_status_id` int NOT NULL,
  `post_author_id` int NOT NULL,
  `post_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `post_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `post_tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `post_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `post_comment_count` int NOT NULL,
  `post_modified` datetime DEFAULT NULL,
  `post_created` datetime NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `post_category_id` (`post_category_id`),
  KEY `post_status` (`post_status_id`),
  KEY `post_author_id` (`post_author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `post_category_id`, `post_status_id`, `post_author_id`, `post_title`, `post_content`, `post_tags`, `post_image`, `post_comment_count`, `post_modified`, `post_created`) VALUES
(41, 1, 4, 1, 'JS is alright for front end usage, I don&#039;t know about it', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.&lt;br&gt;', 'Javascript, JS, Node, Patriot', 'OIP.jfif', 4, '2023-12-20 18:43:08', '2023-12-19 18:43:47'),
(47, 16, 4, 39, 'Symfony is a great framework', '&lt;p&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; text-align: justify;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;', 'Framework, PHP, Symfony, Catwoman', 'wwleijeb.jfif', 0, NULL, '2023-12-21 20:08:35');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `status_name` varchar(50) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`) VALUES
(1, 'Draft'),
(2, 'Pending'),
(3, 'Rejected'),
(4, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_username` varchar(100) NOT NULL,
  `user_legal_name` varchar(150) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `user_about` text,
  `user_password` varchar(150) NOT NULL,
  `user_status_id` int NOT NULL,
  `user_access_id` int NOT NULL,
  `user_image` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'http://placehold.it/64x64',
  `user_modified` datetime DEFAULT NULL,
  `user_created` datetime NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_status_id` (`user_status_id`),
  KEY `user_access_id` (`user_access_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_username`, `user_legal_name`, `user_email`, `user_about`, `user_password`, `user_status_id`, `user_access_id`, `user_image`, `user_modified`, `user_created`) VALUES
(1, 'PHP Man', 'Peter Howard Patrick', 'peter@gmail.com', NULL, '$2y$10$UkZl.lGuNPyLyk8yWpyrbujmY63J.8yvH4nK7VHmmu4mUWg4ulPIm', 4, 2, 'OIP (2).jfif\r\n', '2023-12-19 20:29:04', '2023-12-11 12:47:55'),
(5, 'Adriatic C', 'Adrian Colera', 'adrian@gmail.com', NULL, '$2y$10$Zjw6.W1hhwfDj/4/U2X5jeMsBrnQQf.FT.RMLoijKPFOmlJ58wu26', 4, 3, 'download.jfif', '2023-12-14 17:32:50', '2023-12-11 14:29:18'),
(6, 'PatriotHP', 'Patrick H. Patterson', 'patrick@email.com', 'My name is PHP and I love PHP so much. It\'s the best thing ever. Praesent semper feugiat nibh sed. Dignissim convallis aenean et tortor at. Vitae aliquet nec ullamcorper sit. Faucibus vitae aliquet nec ullamcorper sit amet. Blandit libero volutpat sed cras. Ullamcorper eget nulla facilisi etiam dignissim diam quis enim. Amet nisl suscipit adipiscing bibendum est. Sed vulputate odio ut enim blandit. Aliquam eleifend mi in nulla posuere sollicitudin.', '$2y$10$VUWir.rW6K8.tDJQllkDEefrIUDlxG/4p1QuuNF53xizGJVztowX2', 4, 3, 'java.jfif', '2023-12-14 19:04:32', '2023-12-12 18:35:10'),
(8, 'Crisp31', 'John T Crisp', 'john.t@crisp.com', 'Lectus arcu bibendum at varius. Sed enim ut sem viverra aliquet. Nam at lectus urna duis convallis. Ornare quam viverra orci sagittis eu volutpat odio facilisis mauris. Non pulvinar neque laoreet suspendisse interdum. Id venenatis a condimentum vitae sapien pellentesque habitant morbi. Nullam ac tortor vitae purus faucibus. Elit eget gravida cum sociis natoque. Vulputate odio ut enim blandit volutpat.', '$2y$10$gVjNAAClWcykLfseKXPVQ.4thIsPb/kzy2uSjbEvYCAz8ArTq390S', 4, 1, 'default.png', NULL, '2023-12-12 20:04:00'),
(9, 'Lucas', 'Lucas Parrot', 'lucas@email.com', NULL, '$2y$10$L6cZLZu6Np/Gk1Nrn99scO7nucorVrgsMPns.T8wG/h6NfLE/5kF6', 4, 3, 'profile.jpeg', '2023-12-19 19:45:04', '2023-12-19 18:06:47'),
(17, 'catman', 'Cat Person', 'cat@email.com', NULL, '$2y$10$XbfwcYwyQi5xvpx7zIovE.psQZQKIcqlhgZxmieuANHHz6oVBVWC2', 4, 1, 'cat.jfif', NULL, '2023-12-19 19:09:45'),
(39, 'catwoman', 'Cat Woman Person', 'cat2@email.com', NULL, '$2y$10$OOaxa0EC/8WY4Xc4lpw9HOIbd1gmn.f/Ej0OXbO5vJ0NDDsLoGFiG', 4, 1, 'inllugfv.jfif', NULL, '2023-12-19 23:07:57'),
(52, 'Dog', 'Dog Person', 'dog@gmail.com', NULL, '', 4, 2, 'wwuxlcog.jfif', NULL, '2023-12-19 23:48:16'),
(53, 'New Dog Person', 'Newton Dog Person', 'newdog@gmail.com', NULL, '', 4, 2, 'dog.jfif', NULL, '2023-12-19 23:50:39'),
(54, 'Bobbie', 'Bob Bobbieson', 'bobbie@email.com', NULL, '$2y$10$Gila2ErUIZIY8oRVsWPDPen.3Y/wG4TJbDQ4NcegxRUx0MKHx5M5u', 4, 1, 'uwkcqqok.jfif', NULL, '2023-12-20 15:48:28');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_author_id_restraint` FOREIGN KEY (`comment_author_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_id_restraint` FOREIGN KEY (`comment_post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reply_restraint` FOREIGN KEY (`comment_reply_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `status_restraint` FOREIGN KEY (`comment_status_id`) REFERENCES `status` (`status_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `author_id_restraint` FOREIGN KEY (`post_author_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category_id_restraint` FOREIGN KEY (`post_category_id`) REFERENCES `categories` (`cat_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `status_id_restraint` FOREIGN KEY (`post_status_id`) REFERENCES `status` (`status_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_access_restraint` FOREIGN KEY (`user_access_id`) REFERENCES `access` (`access_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_status_restraint` FOREIGN KEY (`user_status_id`) REFERENCES `status` (`status_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
