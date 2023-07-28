-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2023 at 09:47 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hmp`
--

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(10) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `type` enum('benefit','cost') NOT NULL,
  `bobot` float NOT NULL,
  `ada_pilihan` tinyint(1) DEFAULT NULL,
  `urutan_order` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama`, `type`, `bobot`, `ada_pilihan`, `urutan_order`) VALUES
(11, 'Kedisiplinan', 'benefit', 5, 0, 1),
(12, 'Kepemimpinan', 'benefit', 5, 0, 2),
(13, 'Keaktifan', 'benefit', 5, 0, 3),
(14, 'Attitude', 'benefit', 5, 0, 4),
(15, 'IPK', 'benefit', 5, 0, 5);

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(10) NOT NULL,
  `nama_mahasiswa` varchar(255) NOT NULL,
  `angkatan` text NOT NULL,
  `tanggal_input` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `nama_mahasiswa`, `angkatan`, `tanggal_input`) VALUES
(6, 'Imam Muhyiddin', '1', '2023-07-03'),
(7, 'At Tafani Fillah', '1', '2023-07-03'),
(8, 'Firdaus Anesta Surya', '1', '2023-07-03'),
(9, 'Aditya Eka Pratama', '1', '2023-07-13'),
(10, 'Yudha Satria Abdi Susila', '1', '2023-07-13'),
(11, 'Muhamad Eko Alfianto', '1', '2023-07-13'),
(12, 'Clarissa Monique Maharani', '1', '2023-07-13'),
(13, 'Faiz Nesa Aulia Noor', '1', '2023-07-13'),
(14, 'Aji Nur Prasetyo', '1', '2023-07-13'),
(15, 'Nova Aditya', '1', '2023-07-13'),
(16, 'Alfian Yuda Syahputra', '2', '2023-07-13'),
(17, 'Asa Atina Zarra', '2', '2023-07-13'),
(18, 'Arsyad Abdulghani Asrori', '2', '2023-07-13'),
(20, 'Jidar Titahjaya', '2', '2023-07-13'),
(21, 'Kanca Dwi Sulistiyo', '2', '2023-07-13'),
(22, 'Muhamad Angga Ferdyan', '2', '2023-07-13'),
(23, 'Naufal Indra Permada', '2', '2023-07-13'),
(24, 'Sarah Gracia Kapite', '2', '2023-07-13'),
(25, 'Samuel Thomas Latekay', '2', '2023-07-13'),
(26, 'Uun Saifudin', '2', '2023-07-13'),
(28, 'Dionisius Lucky Noviantoro', '2', '2023-07-26');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_mahasiswa`
--

CREATE TABLE `nilai_mahasiswa` (
  `id_nilai_mahasiswa` int(11) NOT NULL,
  `id_mahasiswa` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nilai_mahasiswa`
--

INSERT INTO `nilai_mahasiswa` (`id_nilai_mahasiswa`, `id_mahasiswa`, `id_kriteria`, `nilai`) VALUES
(25, 8, 11, 5),
(26, 8, 12, 3),
(27, 8, 13, 5),
(28, 8, 14, 4),
(30, 6, 11, 5),
(31, 6, 12, 4),
(32, 6, 13, 5),
(33, 6, 14, 4),
(35, 7, 11, 4),
(36, 7, 12, 5),
(37, 7, 13, 5),
(38, 7, 14, 4),
(43, 6, 15, 4),
(48, 7, 15, 5),
(53, 8, 15, 5),
(114, 9, 11, 5),
(115, 9, 12, 4),
(116, 9, 13, 5),
(117, 9, 14, 4),
(118, 9, 15, 4),
(119, 10, 11, 3),
(120, 10, 12, 4),
(121, 10, 13, 2),
(122, 10, 14, 3),
(123, 10, 15, 3),
(124, 11, 11, 2),
(125, 11, 12, 3),
(126, 11, 13, 5),
(127, 11, 14, 4),
(128, 11, 15, 4),
(129, 12, 11, 3),
(130, 12, 12, 3),
(131, 12, 13, 3),
(132, 12, 14, 4),
(133, 12, 15, 4),
(134, 13, 11, 3),
(135, 13, 12, 3),
(136, 13, 13, 3),
(137, 13, 14, 3),
(138, 13, 15, 4),
(139, 14, 11, 5),
(140, 14, 12, 3),
(141, 14, 13, 5),
(142, 14, 14, 4),
(143, 14, 15, 4),
(144, 15, 11, 4),
(145, 15, 12, 4),
(146, 15, 13, 3),
(147, 15, 14, 4),
(148, 15, 15, 4),
(149, 16, 11, 4),
(150, 16, 12, 2),
(151, 16, 13, 2),
(152, 16, 14, 3),
(153, 16, 15, 5),
(154, 17, 11, 4),
(155, 17, 12, 2),
(156, 17, 13, 2),
(157, 17, 14, 3),
(158, 17, 15, 3),
(159, 18, 11, 4),
(160, 18, 12, 2),
(161, 18, 13, 3),
(162, 18, 14, 3),
(163, 18, 15, 4),
(169, 20, 11, 4),
(170, 20, 12, 3),
(171, 20, 13, 3),
(172, 20, 14, 3),
(173, 20, 15, 3),
(174, 21, 11, 1),
(175, 21, 12, 2),
(176, 21, 13, 2),
(177, 21, 14, 2),
(178, 21, 15, 2),
(179, 22, 11, 4),
(180, 22, 12, 2),
(181, 22, 13, 3),
(182, 22, 14, 3),
(183, 22, 15, 3),
(184, 23, 11, 4),
(185, 23, 12, 2),
(186, 23, 13, 3),
(187, 23, 14, 2),
(188, 23, 15, 3),
(189, 24, 11, 4),
(190, 24, 12, 2),
(191, 24, 13, 2),
(192, 24, 14, 3),
(193, 24, 15, 3),
(194, 25, 11, 4),
(195, 25, 12, 2),
(196, 25, 13, 2),
(197, 25, 14, 2),
(198, 25, 15, 3),
(199, 26, 11, 4),
(200, 26, 12, 2),
(201, 26, 13, 3),
(202, 26, 14, 2),
(203, 26, 15, 2),
(339, 28, 11, 2),
(340, 28, 12, 2),
(341, 28, 13, 2),
(342, 28, 14, 2),
(343, 28, 15, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pilihan_kriteria`
--

CREATE TABLE `pilihan_kriteria` (
  `id_pil_kriteria` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `nilai` float NOT NULL,
  `urutan_order` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pilihan_kriteria`
--

INSERT INTO `pilihan_kriteria` (`id_pil_kriteria`, `id_kriteria`, `nama`, `nilai`, `urutan_order`) VALUES
(1, 11, 'ekspo', 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(5) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(70) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `role` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `email`, `alamat`, `role`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Imam Muhyiddin', 'administrator@gmail.com', 'Salatiga', '1'),
(7, 'User', '12dea96fec20593566ab75692c9949596833adc9', 'mahasiswa', 'user@email.com', 'test', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`);

--
-- Indexes for table `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  ADD PRIMARY KEY (`id_nilai_mahasiswa`),
  ADD UNIQUE KEY `id_kambing_2` (`id_mahasiswa`,`id_kriteria`),
  ADD KEY `id_kambing` (`id_mahasiswa`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  ADD PRIMARY KEY (`id_pil_kriteria`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  MODIFY `id_nilai_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=349;

--
-- AUTO_INCREMENT for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  MODIFY `id_pil_kriteria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  ADD CONSTRAINT `nilai_mahasiswa_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`),
  ADD CONSTRAINT `nilai_mahasiswa_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);

--
-- Constraints for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  ADD CONSTRAINT `pilihan_kriteria_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
