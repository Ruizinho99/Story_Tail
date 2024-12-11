-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 03:54 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lp`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_most_popular` ()   BEGIN
    SELECT b.* 
    FROM books b
    JOIN ratings r ON b.id = r.book_id
    GROUP BY b.id
    ORDER BY AVG(r.rating) DESC
    LIMIT 10;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_new_books` ()   BEGIN
    SELECT * FROM books WHERE access_level = 'public' ORDER BY added_at DESC LIMIT 10;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_our_picks` ()   BEGIN
    SELECT * FROM books WHERE access_level = 'public' ORDER BY RAND() LIMIT 10;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarAtividadesLivro` (IN `p_book_id` INT)   BEGIN
    SELECT 
        a.id AS activity_id,
        a.title AS activity_title,
        a.description AS activity_description,
        abu.user_id,
        abu.progress AS user_progress
    FROM activities a
    INNER JOIN activity_book ab ON a.id = ab.activity_id
    LEFT JOIN activity_book_user abu ON ab.id = abu.activity_book_id
    WHERE ab.book_id = p_book_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarLivros` (IN `p_title` VARCHAR(255), IN `p_age_group` VARCHAR(50), IN `p_is_active` BOOLEAN, IN `p_access_level` VARCHAR(50), IN `p_added_at_start` DATE, IN `p_added_at_end` DATE)   BEGIN
    SELECT id, title, description, cover_url, age_group, is_active, access_level, added_at, updated_at
    FROM books
    WHERE (title LIKE CONCAT('%', p_title, '%') OR p_title IS NULL)
      AND (age_group = p_age_group OR p_age_group IS NULL)
      AND (is_active = p_is_active)
      AND (access_level = p_access_level OR p_access_level IS NULL)
      AND (added_at BETWEEN p_added_at_start AND p_added_at_end);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarLivrosFavoritos` (IN `p_user_id` INT)   BEGIN
    SELECT 
        b.id AS book_id,
        b.title AS book_title,
        b.description AS book_description,
        b.cover_url AS book_cover,
        b.publication_year AS book_year,
        b.age_group AS book_age_group,
        b.is_active AS book_status,
        IFNULL(r.rating, 'Não Avaliado') AS book_rating,
        f.user_id
    FROM books b
    INNER JOIN favorites f ON b.id = f.book_id
    LEFT JOIN ratings r ON b.id = r.book_id AND r.user_id = f.user_id
    WHERE f.user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarLivrosLidos` (IN `user_id` INT)   BEGIN
    SELECT 
        b.id AS book_id,
        b.title AS book_title,
        b.description AS book_description,
        b.cover_url AS book_cover,
        b.publication_year AS book_year,
        b.age_group AS book_age_group,
        b.is_active AS book_status,
        rp.current_page AS user_progress,  -- Página em que o usuário parou de ler
        r.rating AS user_rating,
        r.rating_date AS user_read_date
    FROM reading_progress rp
    JOIN books b ON rp.book_id = b.id
    LEFT JOIN ratings r ON rp.book_id = r.book_id AND rp.user_id = r.user_id
    WHERE rp.user_id = user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SugerirLivros` (IN `p_user_id` INT)   BEGIN
    SELECT 
        b.id AS book_id,
        b.title AS book_title,
        b.description AS book_description,
        b.cover_url AS book_cover,
        COUNT(f.user_id) AS favorite_count,  -- Contagem de favoritos para cada livro
        c.name AS category_name  -- Nome da categoria
    FROM books b
    LEFT JOIN favorites f ON b.id = f.book_id  -- Tabela de favoritos
    LEFT JOIN book_category bc ON b.id = bc.book_id
    LEFT JOIN categories c ON bc.category_id = c.id
    WHERE b.is_active = TRUE  -- Sugere apenas livros ativos
      AND b.access_level = 'public'  -- Exemplo: apenas livros de acesso público
    GROUP BY b.id, b.title, b.description, b.cover_url, c.name
    ORDER BY favorite_count DESC  -- Ordena pelo número de favoritos em ordem decrescente
    LIMIT 5;  -- Limita o número de sugestões
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `activity_book`
--

CREATE TABLE `activity_book` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `activity_book_user`
--

CREATE TABLE `activity_book_user` (
  `id` int(11) NOT NULL,
  `activity_book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `progress` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `activity_images`
--

CREATE TABLE `activity_images` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `author_photo_url` varchar(255) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `first_name`, `last_name`, `description`, `author_photo_url`, `nationality`, `added_at`) VALUES
(1, 'Ana', 'Lima', 'Autora de livros infantis.', 'http://example.com/ana.jpg', 'Brasileira', '2024-11-13 22:27:06'),
(2, 'Pedro', 'Alves', 'Escritor de ficção científica.', 'http://example.com/pedro.jpg', 'Português', '2024-11-13 22:27:06'),
(3, 'Lucia', 'Fernandes', 'Escritora de Romance.', 'http://example.com/lucia.jpg', 'Espanhola', '2024-11-13 22:27:06');

-- --------------------------------------------------------

--
-- Table structure for table `author_book`
--

CREATE TABLE `author_book` (
  `author_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cover_url` varchar(255) DEFAULT NULL,
  `book_url` varchar(255) NOT NULL,
  `publication_year` year(4) DEFAULT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `access_level` varchar(50) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `description`, `cover_url`, `book_url`, `publication_year`, `age_group`, `is_active`, `access_level`, `added_at`, `updated_at`) VALUES
(4, 'Brown Bear, Brown Bear, What Do You See', 'Bill Martin', 'Charlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live\r\n\r\nCharlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live', 'images/Pictures_Eric.png', 'Eric_Carle_Brown_Bear_What_Do_You_See.pdf', 1967, '4-7', 1, '0', '2024-11-14 17:31:31', '2024-12-09 15:00:34'),
(5, 'Giraffes Can\'t Dance', 'Giles Andreae', 'Charlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live\r\n\r\nCharlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live', 'images/Girafasdance.png', 'Giraffes_Can_t_Dance.pdf', 1999, '4-7', 1, '0', '2024-11-14 17:31:31', '2024-12-09 15:00:48'),
(6, 'Monkey Puzzle', 'Julia Donaldson', 'Charlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live\r\n\r\nCharlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live', 'images/monkeypuzzle.png', 'Monkey Puzzle_Julia Donaldson.pdf', 2000, '4-7', 1, '0', '2024-11-14 17:31:31', '2024-12-09 15:01:02'),
(7, 'Pancakes Pancakes', 'Eric Carle', 'Charlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live\r\n\r\nCharlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live', 'images/pancakes.png', 'Pancakes pancakes_Eric Carle.pdf', 1970, '4-7', 1, '0', '2024-11-14 17:31:31', '2024-12-09 15:00:59'),
(8, 'The Koala Who Could', 'Rachel Bright', 'Charlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live\r\n\r\nCharlotte\'s Web is a children\'s novel by American author E. B. White and illustrated by Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel tells the story of a livestock pig named Wilbur and his friendship with a barn spider named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte writes messages praising Wilbur (such as \"Some Pig\") in her web in order to persuade the farmer to let him live', 'images/Koalla.png', 'The koala who could.pdf', 2016, '8-12', 1, '1', '2024-11-14 17:31:31', '2024-12-09 15:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `book_category`
--

CREATE TABLE `book_category` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Stand-in structure for view `horariosmaisfortes`
-- (See below for the actual view)
--
CREATE TABLE `horariosmaisfortes` (
`hora_do_dia` int(2)
,`total_leituras` bigint(21)
,`total_favoritos` bigint(21)
,`total_interacoes` bigint(22)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `livrosmaispopulares`
-- (See below for the actual view)
--
CREATE TABLE `livrosmaispopulares` (
`book_id` int(11)
,`book_title` varchar(255)
,`book_description` text
,`book_cover` varchar(255)
,`total_leituras` bigint(21)
,`total_favoritos` bigint(21)
,`total_popularidade` bigint(22)
);

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `access_level` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `rating_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reading_progress`
--

CREATE TABLE `reading_progress` (
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `current_page` int(11) DEFAULT NULL,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `user_photo_url` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type_id`, `first_name`, `last_name`, `user_name`, `email`, `senha`, `user_photo_url`, `birth_date`, `created_at`, `updated_at`) VALUES
(5, 2, 'Rui', 'Rui', 'Rui', 'Rui@rui.pt', '$2y$10$FtucxtAnnZEOnrKsC1v1xu.1s.MbStJIR67zwq3Ye.5WHTXRvb9bS', NULL, NULL, '2024-11-14 22:35:15', '2024-11-14 22:35:15'),
(7, 2, 'r', 'r', 'r', 'r@2.com', '$2y$10$n6TCwbCiLnY5q2LRG4vhzO93aOx.KCCA8KB/NbNIWYF3efO6JuuZC', NULL, NULL, '2024-11-14 23:33:36', '2024-11-14 23:33:36'),
(8, 2, 'Rui', 'Lopes', 'ruijorgelopes', 'ruijorgelopes9@gmail.com', '$2y$10$FYdYFHRcO6wMCPlUu57szeOd47LJSIoBJlwVhx4AW6msDzGu8YKa2', NULL, NULL, '2024-12-02 15:39:35', '2024-12-02 15:39:35'),
(9, 2, 'Rui', 'Lopes', 'pedro', 'pedro@gmail.com', '$2y$10$4UmBPODjWVq9o5vt9wtPremsU7NB4j0p.tc8trqHwLe2ev1mcngXO', '6758579e3cb5c.png', NULL, '2024-12-02 15:40:01', '2024-12-10 15:00:46'),
(10, 1, 'Rui', 'Lopes', 'Rui Lopes', 'admin@gmail.com', '$2y$10$szEG6mF6T.kAcZBb80WlCePoT42QtQHGhBsLp20/8OJAgW/Aw9s6G', 'http://localhost/uploads/67586dcf54554.png', NULL, '2024-12-03 15:03:50', '2024-12-10 16:35:27'),
(11, 2, 'rui', 'Lopes', 'ruizinho', 'ruijorge9@gmail.com', '$2y$10$DLZYfwtT85zWt1wo1JMwHeE.cKY6fVjSgBYGW3rlQ69UweLhRwaiu', NULL, NULL, '2024-12-03 23:51:54', '2024-12-03 23:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL,
  `user_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `user_type`) VALUES
(1, 'Admin'),
(2, 'Free'),
(3, 'Premium');

-- --------------------------------------------------------

--
-- Structure for view `horariosmaisfortes`
--
DROP TABLE IF EXISTS `horariosmaisfortes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `horariosmaisfortes`  AS SELECT hour(`rp`.`last_accessed`) AS `hora_do_dia`, count(`rp`.`book_id`) AS `total_leituras`, count(`f`.`book_id`) AS `total_favoritos`, count(`rp`.`book_id`) + count(`f`.`book_id`) AS `total_interacoes` FROM (`reading_progress` `rp` left join `favorites` `f` on(hour(`rp`.`last_accessed`) = hour(`f`.`added_at`))) WHERE `rp`.`last_accessed` >= curdate() - interval 3 month OR `f`.`added_at` >= curdate() - interval 3 month GROUP BY hour(`rp`.`last_accessed`) ORDER BY count(`rp`.`book_id`) + count(`f`.`book_id`) AS `DESCdesc` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `livrosmaispopulares`
--
DROP TABLE IF EXISTS `livrosmaispopulares`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `livrosmaispopulares`  AS SELECT `b`.`id` AS `book_id`, `b`.`title` AS `book_title`, `b`.`description` AS `book_description`, `b`.`cover_url` AS `book_cover`, coalesce(`leituras`.`total_leituras`,0) AS `total_leituras`, coalesce(`favoritos`.`total_favoritos`,0) AS `total_favoritos`, coalesce(`leituras`.`total_leituras`,0) + coalesce(`favoritos`.`total_favoritos`,0) AS `total_popularidade` FROM ((`books` `b` left join (select `rp`.`book_id` AS `book_id`,count(`rp`.`book_id`) AS `total_leituras` from `reading_progress` `rp` where `rp`.`last_accessed` >= curdate() - interval 3 month group by `rp`.`book_id`) `leituras` on(`b`.`id` = `leituras`.`book_id`)) left join (select `f`.`book_id` AS `book_id`,count(`f`.`book_id`) AS `total_favoritos` from `favorites` `f` where `f`.`added_at` >= curdate() - interval 3 month group by `f`.`book_id`) `favoritos` on(`b`.`id` = `favoritos`.`book_id`)) WHERE `b`.`is_active` = 1 ORDER BY coalesce(`leituras`.`total_leituras`,0) + coalesce(`favoritos`.`total_favoritos`,0) AS `DESCdesc` ASC  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_book`
--
ALTER TABLE `activity_book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `activity_book_user`
--
ALTER TABLE `activity_book_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_book_id` (`activity_book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `activity_images`
--
ALTER TABLE `activity_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `author_book`
--
ALTER TABLE `author_book`
  ADD PRIMARY KEY (`author_id`,`book_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_category`
--
ALTER TABLE `book_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`book_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `reading_progress`
--
ALTER TABLE `reading_progress`
  ADD PRIMARY KEY (`book_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_book`
--
ALTER TABLE `activity_book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_book_user`
--
ALTER TABLE `activity_book_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_images`
--
ALTER TABLE `activity_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `book_category`
--
ALTER TABLE `book_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_book`
--
ALTER TABLE `activity_book`
  ADD CONSTRAINT `activity_book_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_book_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_book_user`
--
ALTER TABLE `activity_book_user`
  ADD CONSTRAINT `activity_book_user_ibfk_1` FOREIGN KEY (`activity_book_id`) REFERENCES `activity_book` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_book_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_images`
--
ALTER TABLE `activity_images`
  ADD CONSTRAINT `activity_images_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `author_book`
--
ALTER TABLE `author_book`
  ADD CONSTRAINT `author_book_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `author_book_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `book_category`
--
ALTER TABLE `book_category`
  ADD CONSTRAINT `book_category_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reading_progress`
--
ALTER TABLE `reading_progress`
  ADD CONSTRAINT `reading_progress_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reading_progress_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
