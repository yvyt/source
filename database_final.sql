-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2022 at 12:25 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `modify` varchar(255) DEFAULT NULL,
  `deleted` int(11) NOT NULL,
  `open_time` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `share` int(11) NOT NULL,
  `folder` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id`, `username`, `file_name`, `type`, `size`, `modify`, `deleted`, `open_time`, `image`, `priority`, `share`, `folder`) VALUES
(5, 'huynhnguyentuongvy293@gmail.com', 'hinhanh.jpg', 'jpg', 158586, '22-12-18 04:34:05', 0, '22-12-18 04:09:06', 'files/huynhnguyentuongvy293@gmail.com/hinhanh.jpg', 0, 1, NULL),
(6, 'huynhnguyentuongvy293@gmail.com', 'cvci.net_.jpg', 'jpg', 251774, '22-12-18 04:09:15', 0, '22-12-18 04:09:15', 'files/huynhnguyentuongvy293@gmail.com/cvci.net_.jpg', 0, 0, NULL),
(7, 'huynhnguyentuongvy293@gmail.com', 'khoa cntt.docx', 'docx', 15041, '22-12-18 04:09:22', 0, '22-12-18 04:09:22', 'CSS/images/doc.png', 0, 1, NULL),
(8, 'huynhnguyentuongvy293@gmail.com', 'Career-Day-Banner-Banner.png', 'png', 621819, '22-12-18 04:11:49', 0, '22-12-18 04:11:25', 'files/huynhnguyentuongvy293@gmail.com/demo1/Career-Day-Banner-Banner.png', 1, 0, 'demo1'),
(10, 'vyy2903@gmail.com', 'NỘP ĐỒ ÁN CUỐI KỲ.docx', 'docx', 13285, '22-12-18 05:25:43', 0, '22-12-18 05:24:57', 'CSS/images/doc.png', 1, 1, NULL),
(11, 'vyy2903@gmail.com', 'Bac-Ton-1.jpg', 'jpg', 239756, '22-12-18 05:25:06', 0, '22-12-18 05:25:06', 'files/vyy2903@gmail.com/Bac-Ton-1.jpg', 0, 0, NULL),
(12, 'vyy2903@gmail.com', 'De_cuoi_ky.pdf', 'pdf', 561831, '22-12-18 05:25:19', 0, '22-12-18 05:25:19', 'CSS/images/pdf.png', 0, 0, 'test demo'),
(13, 'vyy2903@gmail.com', 'Group_Assigment_Semester_1_2022-2023_v2.pdf', 'pdf', 561831, '22-12-18 05:25:28', 0, '22-12-18 05:25:28', 'CSS/images/pdf.png', 0, 0, 'test demo'),
(14, 'vytuong2903@gmail.com', '1.jpg', 'jpg', 194883, '22-12-18 05:26:45', 0, '22-12-18 05:26:45', 'files/vytuong2903@gmail.com/1.jpg', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `folder`
--

CREATE TABLE `folder` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `parent` varchar(50) DEFAULT NULL,
  `date_create` date NOT NULL,
  `modify` date NOT NULL,
  `deleted` int(11) NOT NULL,
  `share` int(11) NOT NULL,
  `priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `folder`
--

INSERT INTO `folder` (`id`, `username`, `name`, `parent`, `date_create`, `modify`, `deleted`, `share`, `priority`) VALUES
(6, 'huynhnguyentuongvy293@gmail.com', 'demo1', NULL, '2022-12-18', '2022-12-18', 0, 0, 0),
(8, 'huynhnguyentuongvy293@gmail.com', 'demo3', 'demo1', '2022-12-18', '2022-12-18', 0, 0, 0),
(9, 'vyy2903@gmail.com', 'test demo', NULL, '2022-12-18', '2022-12-18', 0, 0, 0),
(10, 'vyy2903@gmail.com', 'check', 'test demo', '2022-12-18', '2022-12-18', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `forgot_password`
--

CREATE TABLE `forgot_password` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `ma_bc` varchar(255) NOT NULL,
  `id_file` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `own` varchar(255) NOT NULL,
  `who_report` varchar(255) NOT NULL,
  `xuly` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `ma_bc`, `id_file`, `type`, `own`, `who_report`, `xuly`) VALUES
(2, 'BC001', 5, 'Vi phạm bản quyền', 'huynhnguyentuongvy293@gmail.com', 'vyy2903@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `share`
--

CREATE TABLE `share` (
  `id` int(11) NOT NULL,
  `id_file` int(11) NOT NULL,
  `users` varchar(255) NOT NULL,
  `keyShare` varchar(255) NOT NULL,
  `isAll` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `share`
--

INSERT INTO `share` (`id`, `id_file`, `users`, `keyShare`, `isAll`) VALUES
(1, 2, '[]', '7dd856f10399cab25e9b0cd0c1f3ce4fc26ada4c643fef6c6686640853be2a9f', 1),
(2, 3, '[\"vyy2903@gmail.com\"]', 'e832bd3a69d88960181e2ece6ea9a1c9bb7ddb2c2e222e2ae743a69d69f66984', 0),
(3, 5, '[]', 'e17bfff2552be3c693921e0257d134110f5caf6173584884a634bfb3971986e2', 1),
(4, 7, '[\"vyy2903@gmail.com\",\"vytuong2903@gmail.com\"]', 'd6256ecdfdf87544ccd7691b29bee7c81695bae8f8d28707abd4661fac002537', 0),
(5, 10, '[]', 'e140b1efad59f1f337f4a7ab31e0e547067db9180fc50c4ddaf615937ec36648', 1);

-- --------------------------------------------------------

--
-- Table structure for table `share_with_me`
--

CREATE TABLE `share_with_me` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `id_file` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `share_with_me`
--

INSERT INTO `share_with_me` (`id`, `username`, `id_file`) VALUES
(3, 'vyy2903@gmail.com', 7),
(5, 'vyy2903@gmail.com', 5),
(6, 'vytuong2903@gmail.com', 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` int(11) NOT NULL,
  `size_page` int(11) DEFAULT NULL,
  `use_size` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` int(11) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `size_page`, `use_size`, `name`, `gender`, `phone`, `token`) VALUES
(1, 'vytuong2903@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 104857600, 194883, 'Vy Vy', 0, NULL, '2af875e92671b9c6da516454e0e329a9'),
(2, 'phanthidiemthuy16062002@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 0, 104857600, 0, 'Phan Thị Diễm Thúy ', 0, NULL, 'a45798ada284d0a18e27b402562171cd'),
(3, 'quang9angoquyen@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 0, 104857600, 1, 'Trần Đăng Quang', 0, NULL, 'dc23f674bc7f19a62f124332ec5d24ae'),
(4, 'vyy2903@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 104857600, 1376703, 'Tường Vy', 0, NULL, '5618ea02d1d81ba66862d726b4f3f77d'),
(7, 'huynhnguyentuongvy293@gmail.com', '5a44030af59b98301ab128f4fdc9cfb2', 1, 209715200, 1962520, 'Huỳnh Nguyễn Tường Vy', 0, '0384708803', '4781d6595dde0655756b45bfc8b69d2e'),
(8, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 0, NULL, 1, 'Admin', 1, '0353741989', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `folder`
--
ALTER TABLE `folder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `share`
--
ALTER TABLE `share`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `share_with_me`
--
ALTER TABLE `share_with_me`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `folder`
--
ALTER TABLE `folder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `forgot_password`
--
ALTER TABLE `forgot_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `share`
--
ALTER TABLE `share`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `share_with_me`
--
ALTER TABLE `share_with_me`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
