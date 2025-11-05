-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Jan-2022 às 21:01
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `apoio_utc`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `horario`
--

CREATE TABLE `horario` (
  `id_horario` int(11) NOT NULL,
  `dia_semana` varchar(50) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `id_sala` int(11) DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `horario-id_sala_idx` (`id_sala`),
  CONSTRAINT `horario-id_sala` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`));
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `horario`
--

INSERT INTO `horario` (`id_horario`, `dia_semana`, `hora_inicio`, `hora_fim`, `id_sala`) VALUES
(1, 'QUA', '08:30:00', '10:30:00', 1, 1),
(2, 'QUA', '10:30:00', '12:30:00', 3, 1),
(3, 'QUI', '10:30:00', '12:30:00', 2, 1),
(4, 'TER', '10:30:00', '12:30:00', 4, 1),
(5, 'QUA', '09:30:00', '11:30:00', 5, 1),
(6, 'QUI', '08:30:00', '10:30:00', 5, 1),
(7, 'SEX', '08:30:00', '10:30:00', 6, 1),
(8, 'SEX', '10:30:00', '12:30:00', 6, 1),
(9, 'QUI', '11:30:00', '13:30:00', 7, 1),
(10, 'TER', '08:30:00', '10:30:00', 8, 1),
(11, 'SEX', '08:30:00', '10:30:00', 4, 1),
(12, 'SEX', '10:30:00', '13:30:00', 5, 1),
(13, 'QUI', '10:30:00', '13:30:00', 5, 1),
(14, 'SEG', '14:30:00', '17:30:00', 6, 1),
(15, 'TER', '09:30:00', '12:30:00', 5, 1),
(16, 'SEG', '15:30:00', '18:30:00', 9, 1),
(17, 'TER', '09:30:00', '12:30:00', 9, 1),
(18, 'QUI', '10:30:00', '12:30:00', 11, 1),
(19, 'SEX', '08:30:00', '11:30:00', 10, 1),
(20, 'SEG', '08:30:00', '10:30:00', 6, 1),
(21, 'QUA', '11:30:00', '13:30:00', 5, 1),
(22, 'QUI', '08:30:00', '10:30:00', 12, 1),
(23, 'QUI', '10:30:00', '13:30:00', 12, 1),
(24, 'TER', '10:30:00', '13:30:00', 3, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `horario-id_sala_idx` (`id_sala`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `horario`
--
ALTER TABLE `horario`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `horario-id_sala` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
