SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint(11) NOT NULL,
  `created_at` bigint(11) DEFAULT NULL,
  `released_at` bigint(20) DEFAULT NULL,
  `updated_at` bigint(11) DEFAULT NULL,
  `user` text,
  `parent` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `title` longtext,
  `content` longtext,
  `url` longtext,
  `category` longtext,
  `status` varchar(100) DEFAULT NULL,
  `locked` varchar(100) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `views` bigint(20) DEFAULT NULL,
  `keywords` longtext,
  `description` longtext,
  `visitor_ip` longtext,
  `image` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
