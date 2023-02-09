-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2023 at 01:29 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alumniportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `extension_name` varchar(10) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` mediumtext NOT NULL,
  `picture` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, `username`, `password`, `picture`) VALUES
(1, 'Nestor', NULL, 'Lucino', NULL, 'nestor@gmail.com', 'admin', '202cb962ac59075b964b07152d234b70', '');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `date_commented` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `description`, `date_commented`) VALUES
(3, 1, 1, 'etrtyuiytr', '2023-01-03'),
(4, 1, 1, 'efg', '2023-01-03'),
(5, 1, 2, 'ewrettyuiolp[', '2023-01-03'),
(6, 1, 2, 'Comment\r\n', '2023-01-24'),
(7, 1, 2, 'Great', '2023-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `description`, `department_id`) VALUES
(1, 'BS IT', 1),
(2, 'BS MATH', 1);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `description`) VALUES
(1, 'College of Computing'),
(4, 'College of Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `picture` mediumtext NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `date_posted` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `picture`, `title`, `description`, `date_posted`) VALUES
(1, '63cf7bb80c135326305403_628617239031927_4583516561748484291_n.jpg', '𝐈𝐍 𝐓𝐇𝐄 𝐍𝐄𝐖𝐒 | 𝐏𝐒𝐔 𝐨𝐟𝐟𝐢𝐜𝐢𝐚𝐥𝐬 𝐚𝐭𝐭𝐞𝐧𝐝 𝐒𝐞𝐧𝐚𝐭𝐞 𝐩𝐮𝐛𝐥𝐢𝐜 𝐡𝐞𝐚𝐫𝐢𝐧𝐠 𝐨𝐧 𝐬𝐚𝐥𝐭 𝐢𝐧𝐝𝐮𝐬𝐭𝐫𝐲 𝐝𝐞𝐯’𝐭', '\r\n              <div paragraph_id=\"\" class=\"\"><span style=\"background-color: var(--bs-modal-bg); color: var(--bs-modal-color); font-size: var(--bs-body-font-size); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">Bent on igniting the wheel of innovation that will revive the salt industry in the Philippines, the Pangasinan State University (PSU) was invited for a  public hearing  at the Philippine Senate to give support to  the passage of two important laws. </span>\r\n</div><div paragraph_id=\"\"><div dir=\"auto\">The academic institution, represented by University President Dr. Elbert M. Galas, <span><a tabindex=\"-1\"></a></span>attended the joint public hearing of the Committee on Agriculture, Food and Agrarian Reform and the Committee on Trade, Commerce and Entrepreneurship; Finance; and Ways and Means on January 18, 2023 at the Senate of the Philippines. </div></div><div paragraph_id=\"\"><div dir=\"auto\">The said Senate hearing was presided over by Senator Cynthia A. Villar and attended by her colleagues in the Chamber namely Sen. Nancy Binay, Sen. Joel Villanueva and Sen. Robin Padilla. </div></div><div paragraph_id=\"\"><div dir=\"auto\">The agenda involved the passing of Senate Bill  No. 1334  otherwise known as the Philippine Salt Industry Development and Revitalization Act introduced by Sen. Villar and Senate Resolution No. 211 or the Salt Supply and Importation Resolution introduced by Sen. Binay. </div></div><div paragraph_id=\"\"><div dir=\"auto\">Dr. Galas said PSU is already researching heavily on the salt industry that has languished despite having one of the world’s longest shorelines. The country imports around 93 percent of its salt requirement despite having the largest salt beds, he added.</div></div>', '2023-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `title` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `company` varchar(100) NOT NULL,
  `date_posted` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `status`, `type`, `title`, `description`, `company`, `date_posted`) VALUES
(1, 1, 1, 'Junior Web Dev', '<span style=\"font-weight: bold;\">Lorem, ipsum dolor sit amet consecteturÂ </span><div><span style=\"font-weight: bold;\"><br></span></div><div><span style=\"font-style: italic;\">adipisicing elit. Libero accusamus blanditiis perferendis voluptate adipisci quisquam quo at impedit, iusto deleniti facere quae? Doloremque et consectetur quas id, voluptatem deleniti non.</span></div>', 'Sitel', '2022-11-06'),
(2, 2, 1, 'Senior Web Dev', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Libero accusamus blanditiis perferendis voluptate adipisci quisquam quo at impedit, iusto deleniti facere quae? Doloremque et consectetur quas id, voluptatem deleniti non.', 'Teleperformance', '2022-11-06'),
(3, 2, 1, 'Middle Web Dev', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Libero accusamus blanditiis perferendis voluptate adipisci quisquam quo at impedit, iusto deleniti facere quae? Doloremque et consectetur quas id, voluptatem deleniti non.', 'Accenture', '2022-11-06'),
(7, 1, 1, 'Customer Service Representative', '<div>fiehfefoehfuehfehfewfuehfeufe eifeifhhewuifeiufhewufheiu fewufheiu feuifheufh eufhewufheifue fuheifueh fuefiuef iuhuf pief</div>', 'Sutherlands', '2022-12-08');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `picture` mediumtext NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `date_posted` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `picture`, `title`, `author`, `description`, `date_posted`) VALUES
(1, '63cf7b65c77b8324307139_1424928378041096_7333504175819277888_n.jpg', 'Behind the scene photos of 4th year BSIT students during their Final Capstone Defense', 'PSU URD', '<div><span style=\"white-space: normal; background-color: var(--bs-modal-bg); color: var(--bs-modal-color); font-size: var(--bs-body-font-size); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">Today marked the end of the long-term planning and capstone preparation. Our seniors deserve our congratulations for completing this task, which tested them to the utmost.</span><br></div><div><span style=\"white-space: normal;\">We are proud of you all for working so hard to finish this defense. We are aware of how challenging it was, but it was also tremendously gratifying.</span></div><div><span style=\"white-space: normal;\">We hope that everyone had a fantastic time and that you will always remember this thesis and your time at Pangasinan State University - Urdaneta City Campus.</span></div><div><span style=\"white-space: normal;\">Whatever the result is, learn to appreciate your efforts. Take note that the important thing is to take the outcome positively and step forward on the path.</span></div>', '2023-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `picture` mediumtext DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `date_posted` date NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `picture`, `title`, `description`, `date_posted`, `status`) VALUES
(1, 1, '63b3c2bf5eb85profile.jpg', 'Refresh Knowldege', 'Amazon Philippines', '2023-01-03', 2),
(2, 1, '63b3c2bf5eb85profile.jpg', 'Refresh Knowldege', 'Amazon Philippines', '2023-01-03', 1),
(3, 1, '', 'wqertyt', 'reewrgtr', '2023-01-03', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `extension_name` varchar(10) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `civil_status` int(11) NOT NULL,
  `gender` int(11) NOT NULL,
  `address_line` varchar(100) NOT NULL,
  `muncity` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `zip_code` int(11) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course` int(11) NOT NULL,
  `batch` int(11) NOT NULL,
  `student_id` varchar(15) NOT NULL,
  `graduation_date` date NOT NULL,
  `employment_status` int(11) NOT NULL,
  `employment_date_first` date DEFAULT NULL,
  `employment_date_current` date DEFAULT NULL,
  `current_position` varchar(50) DEFAULT NULL,
  `password` mediumtext NOT NULL,
  `picture` mediumtext NOT NULL,
  `is_verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `birth_date`, `civil_status`, `gender`, `address_line`, `muncity`, `province`, `zip_code`, `contact`, `email`, `course`, `batch`, `student_id`, `graduation_date`, `employment_status`, `employment_date_first`, `employment_date_current`, `current_position`, `password`, `picture`, `is_verified`) VALUES
(1, 'Eulo', '', 'Sabado', '', '2000-04-27', 2, 1, '#53 Zone 1 Calepaan', 'Asingan', 'Pangasinan', 2439, '09565891878', 'michaelsabado.ms04@gmail.com', 1, 2022, '18-UR-0698', '2022-08-31', 1, '2022-10-26', '2022-10-27', 'Software Dev', '202cb962ac59075b964b07152d234b70', '63b3c1d3f0fcbwork-profile.jpg', 1),
(2, 'Michael', '', 'Sabado', '', '2000-04-27', 2, 1, '#53 Zone 1 Calepaan', 'Asingan', 'Pangasinan', 2439, '09565891878', 'michaelsabado.yns@gmail.com', 1, 2022, '18-UR-0720', '2022-08-31', 1, '2022-10-26', '2022-10-27', 'Software Dev', '202cb962ac59075b964b07152d234b70', '63b3c1d3f0fcbwork-profile.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course` (`course`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`course`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
