-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Jun-2025 às 13:24
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `apoio_utc_2021_2022`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `area`
--

CREATE TABLE `area` (
  `id_area` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `id_utc` int(11) DEFAULT NULL,
  `nome_completo` varchar(255) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `area`
--

INSERT INTO `area` (`id_area`, `nome`, `id_utc`, `nome_completo`, `imagem`) VALUES
(1, 'PADS', 1, 'Programação, Algoritmos e Desenvolvimento de Software', 'https://i.ibb.co/YTm5cXy/pads.jpg'),
(2, 'SI', 1, 'Sistemas de Informação', 'https://i.ibb.co/m0J6QCD/si.jpg'),
(3, 'ACSOR', 1, 'Arquitetura de Computadores, Sistemas Operativos e Redes de Computadores', 'https://i.ibb.co/gVtZM3y/acsor.jpg'),
(4, 'MTC', 1, 'Multimédia e Tecnologias Criativas', 'https://i.ibb.co/68Qw3Gm/default.png'),
(5, 'EMT', 2, NULL, 'https://i.ibb.co/68Qw3Gm/default.png'),
(6, 'BAB', 2, NULL, 'https://i.ibb.co/68Qw3Gm/default.png'),
(7, 'SCRI', 1, NULL, 'https://i.ibb.co/68Qw3Gm/default.png'),
(8, 'DAAAA', 1, 'Isto é um teste para o nome da área', 'http://localhost/apoio_utc/images/area/default.png'),
(11, 'ART', 10, 'UTC ART', 'https://i.ibb.co/68Qw3Gm/default.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `aula`
--

CREATE TABLE `aula` (
  `id_componente` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `id_turma` int(11) NOT NULL,
  `id_docente` int(11) DEFAULT NULL,
  `id_juncao` int(11) DEFAULT NULL,
  `id_sala` int(11) DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `aula`
--

INSERT INTO `aula` (`id_componente`, `id_horario`, `id_turma`, `id_docente`, `id_juncao`) VALUES
(1, 1, 1, 5, NULL),
(1, 4, 1, 5, 1),
(1, 4, 2, 5, 1),
(1, 4, 3, 5, 1),
(1, 4, 4, 5, 1),
(1, 8, 4, 5, NULL),
(1, 4, 17, 5, 1),
(2, 10, 1, 5, NULL),
(2, 5, 2, 5, 129),
(2, 7, 3, 5, 130),
(2, 5, 4, 5, 129),
(2, 7, 17, 5, 130),
(3, 6, 2, 20, 131),
(3, 6, 4, 20, 131),
(5, 140, 1, 32, 282),
(5, 0, 2, NULL, 273),
(5, 0, 3, NULL, 273),
(5, 0, 4, NULL, 245),
(5, 0, 17, NULL, 245),
(6, 2, 1, 6, NULL),
(6, 141, 1, 6, 233),
(6, 11, 2, 6, NULL),
(6, 141, 2, 6, 233),
(6, 141, 3, 6, 233),
(6, 141, 4, 6, 233),
(6, 1, 17, 8, NULL),
(6, 141, 17, 6, 233),
(7, 0, 5, 5, NULL),
(8, 2, 3, 21, NULL),
(8, 17, 3, 21, NULL),
(8, 20, 5, 5, 129),
(9, 18, 5, 20, NULL),
(9, 21, 5, 20, 131),
(13, 142, 4, NULL, NULL),
(29, 0, 102, NULL, NULL),
(30, 19, 12, 4, NULL),
(30, 139, 12, 4, 247),
(30, 5, 13, 4, NULL),
(30, 139, 13, 4, 247),
(30, 0, 102, NULL, NULL),
(34, 0, 5, NULL, NULL),
(34, 0, 28, 16, NULL),
(34, 13, 28, 16, NULL),
(34, 20, 28, 16, NULL),
(34, 21, 28, 16, NULL),
(35, 1, 21, 16, 120),
(35, 10, 21, 16, NULL),
(35, 1, 22, 16, 120),
(35, 140, 23, 32, 282),
(36, 3, 21, 16, NULL),
(36, 1, 22, 17, NULL),
(36, 2, 22, 17, NULL),
(36, 5, 22, 17, NULL),
(36, 2, 23, NULL, NULL),
(38, 10, 25, 20, NULL),
(38, 4, 26, 4, NULL),
(38, 10, 26, 4, NULL),
(38, 14, 26, 4, NULL),
(40, 15, 27, 4, NULL),
(41, 17, 25, 19, 142),
(41, 17, 26, 19, 142),
(42, 18, 28, 21, 278),
(42, 20, 28, 21, NULL),
(44, 25, 31, 25, NULL),
(44, 0, 32, 25, NULL),
(44, 13, 32, 25, NULL),
(44, 20, 32, 25, NULL),
(44, 0, 33, 25, NULL),
(45, 138, 31, 16, 204),
(45, 138, 32, 16, 204),
(45, 0, 33, 1, NULL),
(45, 2, 33, 1, NULL),
(46, 22, 29, 23, 158),
(46, 22, 30, 23, 158),
(47, 20, 29, 24, NULL),
(47, 23, 29, 24, NULL),
(47, 1, 30, 24, NULL),
(47, 21, 30, 24, NULL),
(47, 24, 30, 24, NULL),
(48, 137, 34, 4, NULL),
(49, 139, 34, 4, 247),
(111, 0, 110, 21, 278),
(111, 0, 111, 21, 278),
(115, 140, 114, 32, 282),
(115, 140, 115, 32, 282),
(116, 15, 114, 32, 283),
(116, 15, 115, 32, 283),
(117, 0, 12, NULL, NULL),
(117, 0, 13, NULL, NULL),
(117, 0, 102, NULL, NULL),
(118, 0, 12, NULL, NULL),
(118, 0, 13, NULL, NULL),
(118, 0, 102, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `componente`
--

CREATE TABLE `componente` (
  `id_componente` int(11) NOT NULL,
  `id_disciplina` int(11) DEFAULT NULL,
  `id_tipocomponente` int(11) DEFAULT NULL,
  `numero_horas` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `componente`
--

INSERT INTO `componente` (`id_componente`, `id_disciplina`, `id_tipocomponente`, `numero_horas`) VALUES
(1, 1, 2, 3),
(2, 1, 3, 2),
(3, 1, 3, 2),
(4, 2, 2, 2),
(5, 2, 2, 2),
(6, 3, 2, 2),
(7, 4, 2, 2),
(8, 4, 3, 2),
(9, 4, 3, 2),
(10, 5, 2, 2),
(11, 6, 1, 2),
(12, 6, 2, 2),
(13, 7, 1, 2),
(14, 7, 2, 3),
(29, 26, 2, 2),
(30, 26, 3, 3),
(31, 27, 1, 2),
(33, 27, 2, 3),
(34, 29, 6, 2),
(35, 8, 2, 2),
(36, 8, 3, 2),
(37, 9, 2, 2),
(38, 9, 3, 2),
(39, 10, 2, 4),
(40, 10, 3, 3),
(41, 11, 2, 3),
(42, 12, 2, 2),
(43, 12, 3, 3),
(44, 50, 2, 2),
(45, 50, 3, 3),
(46, 13, 2, 2),
(47, 13, 3, 3),
(48, 14, 2, 2),
(49, 14, 3, 3),
(101, 101, 2, 2),
(102, 101, 3, 3),
(103, 102, 2, 2),
(104, 103, 2, 2.5),
(111, 108, 2, 2),
(115, 111, 2, 2),
(116, 111, 3, 3),
(117, 112, 2, 2),
(118, 112, 3, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `nome_completo` varchar(255) DEFAULT NULL,
  `sigla` varchar(50) DEFAULT NULL,
  `sigla_completa` varchar(50) DEFAULT NULL,
  `semestres` int(11) DEFAULT NULL,
  `imagem_curso` varchar(255) DEFAULT NULL,
  `id_utc` int(11) DEFAULT NULL,
  `id_tipo_curso` int(11) DEFAULT NULL,
  `id_coordenador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`id_curso`, `nome`, `nome_completo`, `sigla`, `sigla_completa`, `semestres`, `imagem_curso`, `id_utc`, `id_tipo_curso`, `id_coordenador`) VALUES
(1, 'Engenharia Informática', 'CURSO LICENCIATURA EM ENGENHARIA INFORMÁTICA', 'INF', 'LIC.INF', 6, 'https://i.ibb.co/W680YzW/eng-inf.jpg', 1, 1, 4),
(2, 'Tecnologias de Informação e Multimédia', 'CURSO LICENCIATURA EM TECNOLOGIAS DE INFORMAÇÃO E MULTIMÉDIA', 'TIM', 'LIC.TIM', 6, 'https://i.ibb.co/c3hWCBt/TIM.png', 1, 1, 4),
(3, 'Desenvolvimento de Produtos Multimédia', 'CURSO SUPERIOR TÉCNICO EM DESENVOLVIMENTO DE PRODUTOS MULTIMÉDIA', 'DPM', 'CTeSP.DPM', 4, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 1, 3, 4),
(4, 'Tecnologias e Programação de Sistemas de Informação', 'CURSO SUPERIOR TÉCNICO EM TECNOLOGIAS E PROGRAMAÇÃO DE SISTEMAS DE INFORMAÇÃO', 'TPSI', 'CTesP.TPSI', 4, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 1, 3, 4),
(5, 'Data Center e Computação Cloud', 'CURSO SUPERIOR TÉCNICO EM DATA CENTER E COMPUTAÇÃO CLOUD', 'DCCC', 'CTesP.DCCC', 4, 'https://i.ibb.co/d5bdKNd/660x371-abc1c69847550ddefd872148986931ab.jpg', 1, 3, 4),
(6, 'Desenvolvimento de Software e Sistemas Interativos', 'CURSO MESTRADO EM DESENVOLVIMENTO DE SOFTWARE E SISTEMAS INTERATIVOS', 'DCCC', 'MES.DSSI', 4, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 1, 2, 4),
(7, 'Engenharia Civil', 'LICENCIATURA EM ENGENHARIA CIVIL', 'CIV', 'LIC.EC', 6, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 2, 1, 10),
(8, 'Curso Teste', 'LICENCIATURA EM CURSO TESTE', 'TEST', 'LIC.TESTE', 6, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 3, 1, 11),
(12, 'CURSOTESTE123', 'CURSO LICENCIATURA EM CURSO TESTE', 'CT', 'LIC.CT', 6, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 1, 1, 4),
(14, 'Curso Teste', 'LICENCIATURA EM CURSO TESTE', 'TEST', 'LIC.TESTE', 6, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 10, 1, 32);

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso_tipo`
--

CREATE TABLE `curso_tipo` (
  `id_tipo_curso` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `sigla` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `curso_tipo`
--

INSERT INTO `curso_tipo` (`id_tipo_curso`, `nome`, `sigla`) VALUES
(1, 'Licenciatura', 'LIC.'),
(2, 'Mestrado', 'MES.'),
(3, 'Curso Superior Técnico', 'CTeSP.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso_utc`
--

CREATE TABLE `curso_utc` (
  `id_curso` int(11) NOT NULL,
  `id_utc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `curso_utc`
--

INSERT INTO `curso_utc` (`id_curso`, `id_utc`) VALUES
(1, 2),
(2, 2),
(3, 1),
(3, 3),
(4, 3),
(5, 2),
(5, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplina`
--

CREATE TABLE `disciplina` (
  `id_disciplina` int(11) NOT NULL,
  `nome_uc` varchar(50) DEFAULT NULL,
  `codigo_uc` int(11) DEFAULT NULL,
  `abreviacao_uc` varchar(50) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL,
  `id_responsavel` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_curso` int(11) NOT NULL,
  `imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `disciplina`
--

INSERT INTO `disciplina` (`id_disciplina`, `nome_uc`, `codigo_uc`, `abreviacao_uc`, `ano`, `semestre`, `id_responsavel`, `id_area`, `id_curso`, `imagem`) VALUES
(1, 'Programação I', 11111, 'P1', 1, 1, 5, 1, 1, ''),
(2, 'Matemática para a Informática I ', 22222, 'MPI1', 1, 1, 18, 3, 1, ''),
(3, 'Inglês I', 33333, 'ING1', 1, 1, 6, 4, 1, ''),
(4, 'Programação I', 5634, 'P1', 1, 1, 5, 1, 2, ''),
(5, 'Inglês I', 55555, 'ING1', 1, 1, 6, 4, 2, ''),
(6, 'Materiais', 66666, 'MAT', 1, 1, 11, 5, 7, ''),
(7, 'Betão Armado', 77777, 'BET', 1, 1, 9, 6, 7, ''),
(8, 'Estratégia Empresarial e Sistemas Informáticos', 6359, 'EESI', 3, 1, 18, 2, 1, ''),
(9, 'Programação III', 6131, 'P3', 2, 1, 4, 1, 1, ''),
(10, 'Programação III', 5684, 'P3', 2, 1, 4, 1, 2, ''),
(11, 'Arquitetura de Sistemas Computacionais', 5630, 'ASC', 2, 1, 19, 7, 1, ''),
(12, 'Arquitectura e Tecnologias de Internet', 6082, 'ATI', 3, 1, 21, 3, 2, ''),
(13, 'Empreendedorismo', 6613, 'EMP', 3, 2, 23, 2, 1, ''),
(14, 'Programação II', 6613, 'P2', 1, 2, 4, 1, 2, ''),
(26, 'Programação II', 88888, 'P2', 1, 2, 4, 1, 1, ''),
(27, 'Disciplina Teste', 123456789, 'DT', 3, 2, 11, 4, 8, ''),
(29, 'Projeto I', 1111, 'PRI', 3, 1, 4, 1, 2, ''),
(50, 'Linguagens de Programação para a Internet', 6500, 'LPI', 2, 2, 16, 2, 1, ''),
(101, 'Componente_teste23', 6653, 'CTT', 3, 1, 21, 5, 1, ''),
(102, 'DAAAAAAAAAAAA', 4113, 'DAA', 3, 1, 21, 4, 1, ''),
(103, 'Teste123Teste123', 123, 'Test', 3, 2, 19, 3, 1, ''),
(108, 'UCTESTE123123', 1224, 'UCT', 3, 1, 9, 4, 12, ''),
(111, 'UC Teste', 1234, 'UCT', 3, 1, 32, 11, 14, ''),
(112, 'Base de Dados', 70123, 'BD', 1, 2, 16, 2, 1, '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcao`
--

CREATE TABLE `funcao` (
  `id_funcao` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `funcao`
--

INSERT INTO `funcao` (`id_funcao`, `nome`) VALUES
(1, 'Administrador'),
(2, 'Gestor UTC'),
(3, 'Gestor Área'),
(4, 'Prof. Adjunto'),
(5, 'Prof. Coord.'),
(6, 'Assit. Conv.');

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
  `semestre` int(1) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `horario`
--

INSERT INTO `horario` (`id_horario`, `dia_semana`, `hora_inicio`, `hora_fim`, `id_sala`, `semestre`) VALUES
(1, 'QUA', '08:30:00', '10:30:00', 1, 1),
(2, 'QUA', '10:30:00', '12:30:00', 3, 1),
(3, 'QUI', '10:30:00', '12:30:00', 2, 1),
(4, 'TER', '11:30:00', '13:30:00', 4, 1),
(5, 'SEG', '08:30:00', '11:30:00', 5, 1),
(6, 'QUI', '08:30:00', '10:30:00', 5, 1),
(7, 'SEX', '08:30:00', '10:30:00', 6, 1),
(8, 'SEX', '10:30:00', '12:30:00', 6, 1),
(9, 'SEG', '14:30:00', '16:30:00', 6, 1),
(10, 'TER', '09:00:00', '11:30:00', 8, 1),
(11, 'SEX', '08:30:00', '10:30:00', 4, 1),
(12, 'SEX', '13:30:00', '15:30:00', 5, 1),
(13, 'QUI', '10:30:00', '13:30:00', 5, 1),
(14, 'QUI', '10:30:00', '12:30:00', 10, 1),
(15, 'TER', '15:30:00', '12:30:00', 5, 1),
(16, 'SEG', '15:30:00', '18:30:00', 9, 1),
(17, 'TER', '09:30:00', '12:30:00', 9, 1),
(18, 'QUI', '10:30:00', '12:30:00', 11, 1),
(19, 'SEX', '08:30:00', '11:30:00', 10, 1),
(20, 'SEG', '08:30:00', '10:30:00', 6, 1),
(21, 'SEG', '10:30:00', '13:30:00', 5, 1),
(22, 'SEG', '08:30:00', '10:30:00', 3, 2),
(23, 'QUI', '10:30:00', '13:30:00', 12, 2),
(24, 'TER', '08:30:00', '11:30:00', 3, 2),
(25, 'QUA', '12:00:00', '14:00:00', 5, 2),
(137, 'SEX', '15:30:00', '17:30:00', 5, 2),
(138, 'SEG', '10:00:00', '13:00:00', 15, 2),
(139, 'SEX', '08:30:00', '11:30:00', 5, 2),
(140, 'QUA', '12:30:00', '14:30:00', 5, 1),
(141, 'QUI', '15:30:00', '17:30:00', 7, 1),
(142, 'QUI', '09:30:00', '11:30:00', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `juncao`
--

CREATE TABLE `juncao` (
  `id_juncao` int(11) NOT NULL,
  `nome_juncao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `juncao`
--

INSERT INTO `juncao` (`id_juncao`, `nome_juncao`) VALUES
(1, 'P1 Teórica: INF + TIM'),
(3, 'Programação 1 - Pratica 2 - Tim - Inf'),
(4, 'Matemática para informática I - Teorica 1 - INF - Turma 3 + 4'),
(5, 'Matemática para informática I - Teorica 2 - INF - Turma 3 + 4'),
(6, 'Inglês - Teorica - INF - Turma 1 + 2'),
(7, 'Inglês - Teorica - INF - Turma 3 + 4 - Tim - Turma 1'),
(10, 'Teorica P2'),
(11, 'sa'),
(30, 'TESTETESTE'),
(120, 'EESI - Teórica: INF1 e INF2'),
(129, 'P1 Prática 1: INF2 + INF4'),
(130, 'P1 Prática 1: INF3 + INF5'),
(131, 'P1 Prática 2: INF2 + INF4'),
(132, 'P1 Prática 2: INF3 + INF5'),
(134, 'TESTE123'),
(141, 'P3 Teórica: INF e TIM juntas'),
(142, 'ASC: INF2 + INF3'),
(158, 'Empreendedorismo (Teórica): INF1 + INF2'),
(169, 'MPI1 - Junção Teste'),
(195, 'P2 Teórica: INF1 + INF2'),
(204, 'LPI - Práticas INF1 + INF2'),
(233, 'Inglês I : INF todas juntas'),
(245, 'EEEEEEEEEEEEEEEEEE'),
(247, 'P2 - Junção Teste'),
(273, 'GGGGGGGGGGGGG'),
(278, 'TEEEEST'),
(282, 'JUNCAO_TESTE1'),
(283, 'JUNCAO_TESTE12'),
(284, 'Juncaoooooo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `juncao_componente`
--

CREATE TABLE `juncao_componente` (
  `id_juncao` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `juncao_componente`
--

INSERT INTO `juncao_componente` (`id_juncao`, `id_componente`) VALUES
(1, 1),
(1, 7),
(120, 35),
(129, 2),
(129, 8),
(130, 2),
(131, 3),
(131, 9),
(132, 3),
(141, 37),
(141, 39),
(142, 41),
(158, 46),
(169, 4),
(195, 29),
(204, 45),
(233, 6),
(245, 5),
(247, 30),
(247, 49),
(273, 5),
(278, 42),
(278, 111),
(282, 5),
(282, 35),
(282, 115),
(283, 116),
(284, 41),
(284, 43);

-- --------------------------------------------------------

--
-- Estrutura da tabela `preferencias`
--

CREATE TABLE `preferencias` (
  `id_preferencias` int(11) NOT NULL,
  `preferencia` tinytext NOT NULL DEFAULT '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `preferencias`
--

INSERT INTO `preferencias` (`id_preferencias`, `preferencia`) VALUES
(2, '1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0'),
(3, '3,3,3,3,3,0,3,3,3,3,0,3,3,3,3,3,3,3,3,3,3,3,2,3,1,3,3,1,3,1,3,2,1,2,0,3,2,1,0,0,0,0,0,0,0,0,0,0,0,0'),
(4, '2,1,3,0,0,2,1,0,0,0,2,1,3,0,0,2,1,3,0,0,2,1,3,0,0,0,1,3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0'),
(5, '3,3,3,3,0,3,3,3,3,0,3,3,3,3,0,3,3,3,3,0,3,3,3,3,0,3,3,3,3,0,3,3,3,3,0,3,3,3,3,0,3,3,3,3,0,3,3,3,3,0'),
(6, '3,2,0,0,0,3,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,3,0,0,0,0,3,0,0,0,0,3,0,0,0,0,3,0,0,0,0,0,0,0,0,0,0,0,0'),
(7, '2,1,0,0,3,2,1,0,0,3,2,1,0,0,3,2,1,0,0,3,2,1,0,0,3,0,0,0,0,3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0'),
(8, '0,2,0,0,0,0,2,0,0,0,0,2,0,0,0,0,2,0,0,0,0,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0'),
(9, '3,1,1,1,2,3,1,1,1,2,3,1,1,1,2,3,1,1,1,0,3,3,3,2,0,3,3,3,2,0,1,3,3,2,0,1,3,3,2,0,0,0,0,0,0,0,0,0,0,0'),
(10, '3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,0,3,3,3,3,0,3,3,3,3,0,3,3,3,3,0'),
(11, '1,2,3,0,0,3,2,2,3,1,3,2,2,3,1,3,2,2,2,1,3,0,3,2,0,3,0,3,1,0,3,3,1,1,0,1,3,1,0,0,1,3,0,0,0,0,3,0,0,0'),
(12, '3,0,0,0,0,3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0'),
(13, '3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,1,2,1,1,1,1,2,1,1,1,1,2,1,1,1,1,2,0,0,0,1,2,0,0,0');

-- --------------------------------------------------------

--
-- Estrutura da tabela `preferencias_turma`
--

CREATE TABLE `preferencias_turma` (
  `id_turma` int(11) NOT NULL,
  `id_preferencias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `preferencias_turma`
--

INSERT INTO `preferencias_turma` (`id_turma`, `id_preferencias`) VALUES
(1, 6),
(14, 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `preferencia_sala`
--

CREATE TABLE `preferencia_sala` (
  `id_sala` int(11) NOT NULL,
  `id_preferencias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `preferencia_sala`
--

INSERT INTO `preferencia_sala` (`id_sala`, `id_preferencias`) VALUES
(1, 7),
(33, 8);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sala`
--

CREATE TABLE `sala` (
  `id_sala` int(11) NOT NULL,
  `nome_sala` varchar(50) DEFAULT NULL,
  `sigla_sala` varchar(6) NOT NULL,
  `bloco_sala` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sala`
--

INSERT INTO `sala` (`id_sala`, `nome_sala`, `sigla_sala`, `bloco_sala`) VALUES
(1, 'Anfiteatro B', 'ANF B', 'B'),
(2, 'Sala A7', 'A.7', 'A'),
(3, 'Sala D5', 'D.5', 'D'),
(4, 'Auditorio', 'AUD', 'A'),
(5, 'Sala A1', 'A.1', 'A'),
(6, 'Sala A2', 'A.2', 'A'),
(7, 'Sala A5', 'A.5', 'A'),
(8, 'Sala C10', 'C.10', 'C'),
(9, 'Sala A4', 'A.4', 'A'),
(10, 'Sala A3', 'A.3', 'A'),
(11, 'Sala A9', 'A.9', 'A'),
(12, 'Sala D6', 'D.6', 'D'),
(13, 'Sala A6', 'A.6', 'A'),
(14, 'Sala A8', 'A.8', 'A'),
(15, 'Sala A15', 'A.15', 'A'),
(16, 'Sala B4', 'B.4', 'B'),
(17, 'Sala B11', 'B.11', 'B'),
(18, 'Sala B18', 'B.18', 'B'),
(19, 'Sala C19', 'C.19', 'C'),
(20, 'Sala E2', 'E.2', 'E'),
(33, 'Anfiteatro C', 'ANF C', 'C'),
(34, 'Anfiteatro D', 'ANF D', 'D'),
(35, 'E 4', 'E.4', 'E');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sala_componente_atribuida`
--

CREATE TABLE `sala_componente_atribuida` (
  `id_sala` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sala_componente_atribuida`
--

INSERT INTO `sala_componente_atribuida` (`id_sala`, `id_componente`) VALUES
(1, 2),
(34, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sala_componente_disponivel`
--

CREATE TABLE `sala_componente_disponivel` (
  `idsala_componente_disponivel` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sala_componente_disponivel`
--

INSERT INTO `sala_componente_disponivel` (`idsala_componente_disponivel`, `id_componente`, `id_sala`) VALUES
(1, 1, 7),
(37, 12, 2),
(38, 12, 5),
(17, 13, 5),
(33, 13, 6),
(34, 13, 7),
(9, 29, 5),
(12, 111, 1),
(13, 111, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sala_disciplina`
--

CREATE TABLE `sala_disciplina` (
  `id_sala` int(11) NOT NULL,
  `id_disciplina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sala_disciplina`
--

INSERT INTO `sala_disciplina` (`id_sala`, `id_disciplina`) VALUES
(1, 2),
(1, 5),
(2, 3),
(2, 6),
(3, 4),
(3, 7),
(4, 5),
(4, 8),
(5, 6),
(5, 9),
(6, 7),
(6, 10),
(7, 8),
(7, 11),
(8, 9),
(8, 12),
(9, 10),
(9, 13),
(10, 11),
(10, 14),
(11, 2),
(11, 12),
(12, 3),
(12, 13),
(13, 4),
(13, 14),
(14, 2),
(14, 5),
(15, 6),
(15, 27),
(16, 7),
(16, 27),
(17, 8),
(17, 29),
(18, 2),
(18, 9),
(19, 3),
(19, 10),
(20, 4),
(20, 11);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sala_utc`
--

CREATE TABLE `sala_utc` (
  `id_sala` int(11) NOT NULL,
  `id_utc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `sala_utc`
--

INSERT INTO `sala_utc` (`id_sala`, `id_utc`) VALUES
(1, 1),
(6, 1),
(10, 1),
(11, 1),
(17, 1),
(33, 1),
(34, 1),
(2, 2),
(5, 2),
(7, 2),
(12, 2),
(20, 2),
(3, 3),
(8, 3),
(13, 3),
(4, 10),
(9, 10),
(14, 10),
(16, 11),
(35, 11);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_componente`
--

CREATE TABLE `tipo_componente` (
  `id_tipocomponente` int(11) NOT NULL,
  `nome_tipocomponente` varchar(45) DEFAULT NULL,
  `sigla_tipocomponente` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tipo_componente`
--

INSERT INTO `tipo_componente` (`id_tipocomponente`, `nome_tipocomponente`, `sigla_tipocomponente`) VALUES
(1, 'Teórica', 'T'),
(2, 'Teórico-Prática', 'TP'),
(3, 'Prática', 'P'),
(4, 'Prático-Laboratoriais', 'PL'),
(5, 'Trabalho de Campo', 'TC'),
(6, 'Orientação Tutorial', 'OT'),
(7, 'Seminário', 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma`
--

CREATE TABLE `turma` (
  `id_turma` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `turma`
--

INSERT INTO `turma` (`id_turma`, `nome`, `ano`, `semestre`, `id_curso`) VALUES
(1, 'INF1', 1, 1, 1),
(2, 'INF2', 1, 1, 1),
(3, 'INF3', 1, 1, 1),
(4, 'INF4', 1, 1, 1),
(5, 'TIM1', 1, 1, 2),
(6, 'CIV1', 1, 1, 7),
(12, 'INF1', 1, 2, 1),
(13, 'INF2', 1, 2, 1),
(14, 'CT1', 3, 2, 4),
(15, 'CT2', 3, 2, 4),
(16, 'TEST1', 3, 2, 8),
(17, 'INF5', 1, 1, 1),
(21, 'INF1', 3, 1, 1),
(22, 'INF2', 3, 1, 1),
(23, 'INF3', 3, 1, 1),
(24, 'INF1', 2, 1, 1),
(25, 'INF2', 2, 1, 1),
(26, 'INF3', 2, 1, 1),
(27, 'TIM1', 2, 1, 2),
(28, 'TIM1', 3, 1, 2),
(29, 'INF1', 3, 2, 1),
(30, 'INF2', 3, 2, 1),
(31, 'INF1', 2, 2, 1),
(32, 'INF2', 2, 2, 1),
(33, 'INF3', 2, 2, 1),
(34, 'TIM1', 1, 2, 2),
(102, 'INF3', 1, 2, 1),
(110, 'TEST1', 3, 1, 12),
(111, 'TEST2', 3, 1, 12),
(114, 'TURM1', 3, 1, 14),
(115, 'TURM2', 3, 1, 14);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utc`
--

CREATE TABLE `utc` (
  `id_utc` int(11) NOT NULL,
  `nome_utc` varchar(50) NOT NULL,
  `id_responsavel` int(11) DEFAULT NULL,
  `sigla_utc` varchar(50) DEFAULT NULL,
  `dsd_1_sem` int(1) NOT NULL DEFAULT 0,
  `dsd_2_sem` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utc`
--

INSERT INTO `utc` (`id_utc`, `nome_utc`, `id_responsavel`, `sigla_utc`, `dsd_1_sem`, `dsd_2_sem`) VALUES
(1, 'Informática', 18, 'INF', 0, 0),
(2, 'Civil', 10, 'CIV', 0, 0),
(3, 'UTC Teste', 11, 'UT', 0, 0),
(10, 'UTCTeste123', 32, 'UTCT', 0, 0),
(11, 'IPART', 5, 'IP', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

CREATE TABLE `utilizador` (
  `id_utilizador` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `imagem_perfil` varchar(255) DEFAULT NULL,
  `id_utc` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `id_funcao` int(11) DEFAULT NULL,
  `is_admin` int(11) DEFAULT NULL,
  `ano_letivo` varchar(50) NOT NULL DEFAULT '2021_2022',
  `perm_horarios` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizador`
--

INSERT INTO `utilizador` (`id_utilizador`, `nome`, `login`, `password`, `imagem_perfil`, `id_utc`, `id_area`, `id_funcao`, `is_admin`, `ano_letivo`, `perm_horarios`) VALUES
(1, 'Administrador', 'admin', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 3, 1, 4, 1, '2021_2022', 0),
(4, 'Sérgio', 'sergio', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 1, 4, 0, '2020_2021', 0),
(5, 'Arlindo Silva', 'arlindo', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 11, 1, 4, 0, '2021_2022', 0),
(6, 'Tiago', 'tiago', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 4, 6, 0, '2021_2022', 0),
(7, 'Ana Silva', 'ana', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 3, 4, 0, '2021_2022', 0),
(8, 'Teresa', 'teresa', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 3, 4, 0, '2021_2022', 0),
(9, 'Luis Barata', 'luis', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 5, 6, 0, '2021_2022', 0),
(10, 'Rosa', 'rosa', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 0, '2021_2022', 0),
(11, 'Utilizador Teste', 'teste', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 3, 1, 4, 0, '2021_2022', 0),
(12, 'Utilizador Teste GestorArea', 'testearea', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 3, 3, 4, 0, '2021_2022', 0),
(13, 'Utilizador Teste GestorUTC', 'testeutc', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 3, 1, 4, 0, '2021_2022', 0),
(16, 'Eurico Lopes', 'eurico', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 2, 5, 0, '2021_2022', 1),
(17, 'Paulo Serra', 'paulo', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 2, 6, 0, '2021_2022', 0),
(18, 'Ângela Oliveira', 'angela', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 4, 4, 0, '2021_2022', 0),
(19, 'João Caldeira', 'joao_caldeira', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 2, 4, 0, '2021_2022', 0),
(20, 'Pedro Passão', 'pedro_passao', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 1, 6, 0, '2021_2022', 0),
(21, 'Alexandre Fonte', 'alexandre_fonte', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 3, 4, 0, '2021_2022', 0),
(22, 'Vasco Soares', 'vasco_soares', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 3, 4, 0, '2021_2022', 0),
(23, 'Maria Constança Rigueiro', 'constanca_rigueiro', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 0, '2021_2022', 0),
(24, 'Arlindo Cabrito', 'arlindo_cabrito', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 0, '2021_2022', 0),
(25, 'Carlos Alves', 'carlos_alves', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 2, 4, 0, '2021_2022', 0),
(31, 'Utilizador', 'Utilizador123', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 0, '2021_2022', 0),
(32, 'Utilizador Teste', 'utilizador_teste', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 10, 2, 4, 1, '2021_2022', 0),
(33, 'TesteTeste', 'teste123', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 1, '2021_2022', 0),
(35, 'Admin Teste Teste', 'admin_teste', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 3, 5, 1, '2021_2022', 0),
(38, 'Filipe Fidalgo', 'filipe', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 2, 5, 0, '2021_2022', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador_preferencia`
--

CREATE TABLE `utilizador_preferencia` (
  `id_utilizador` int(100) NOT NULL,
  `id_preferencias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizador_preferencia`
--

INSERT INTO `utilizador_preferencia` (`id_utilizador`, `id_preferencias`) VALUES
(1, 2),
(4, 3),
(5, 4),
(6, 5),
(17, 10),
(21, 13),
(25, 11),
(32, 12);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador_utc`
--

CREATE TABLE `utilizador_utc` (
  `id_utilizador` int(11) NOT NULL,
  `id_disciplina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id_area`),
  ADD KEY `area-id_utc_idx` (`id_utc`);

--
-- Índices para tabela `aula`
--
ALTER TABLE `aula`
  ADD PRIMARY KEY (`id_componente`,`id_turma`,`id_horario`),
  ADD KEY `aula-id_componente_idx` (`id_componente`),
  ADD KEY `aula-id_turma_idx` (`id_turma`),
  ADD KEY `aula-id_docente_idx` (`id_docente`),
  ADD KEY `aula-id_horario_idx` (`id_horario`),
  ADD KEY `aula-id_juncao_idx` (`id_juncao`);

--
-- Índices para tabela `componente`
--
ALTER TABLE `componente`
  ADD PRIMARY KEY (`id_componente`),
  ADD KEY `componente-id_tipocomponente_idx` (`id_tipocomponente`),
  ADD KEY `componente-id_disciplina_idx` (`id_disciplina`);

--
-- Índices para tabela `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `curso-id_utc_idx` (`id_utc`),
  ADD KEY `curso-id_tipo_curso_idx` (`id_tipo_curso`),
  ADD KEY `curso-id_coordenador_idx` (`id_coordenador`);

--
-- Índices para tabela `curso_tipo`
--
ALTER TABLE `curso_tipo`
  ADD PRIMARY KEY (`id_tipo_curso`);

--
-- Índices para tabela `curso_utc`
--
ALTER TABLE `curso_utc`
  ADD PRIMARY KEY (`id_curso`,`id_utc`),
  ADD KEY `curso_utc-id_utc_idx` (`id_utc`);

--
-- Índices para tabela `disciplina`
--
ALTER TABLE `disciplina`
  ADD PRIMARY KEY (`id_disciplina`),
  ADD KEY `disciplina-id_area_idx` (`id_area`),
  ADD KEY `disciplina-id_responsavel_idx` (`id_responsavel`),
  ADD KEY `disciplina-id_curso_idx` (`id_curso`);

--
-- Índices para tabela `funcao`
--
ALTER TABLE `funcao`
  ADD PRIMARY KEY (`id_funcao`);

--
-- Índices para tabela `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `horario-id_sala_idx` (`id_sala`);

--
-- Índices para tabela `juncao`
--
ALTER TABLE `juncao`
  ADD PRIMARY KEY (`id_juncao`);

--
-- Índices para tabela `juncao_componente`
--
ALTER TABLE `juncao_componente`
  ADD PRIMARY KEY (`id_juncao`,`id_componente`),
  ADD KEY `area-id_componente_idx` (`id_componente`);

--
-- Índices para tabela `preferencias`
--
ALTER TABLE `preferencias`
  ADD PRIMARY KEY (`id_preferencias`);

--
-- Índices para tabela `preferencias_turma`
--
ALTER TABLE `preferencias_turma`
  ADD PRIMARY KEY (`id_turma`,`id_preferencias`),
  ADD KEY `id_preferencias` (`id_preferencias`);

--
-- Índices para tabela `preferencia_sala`
--
ALTER TABLE `preferencia_sala`
  ADD PRIMARY KEY (`id_sala`,`id_preferencias`),
  ADD KEY `id_preferencias` (`id_preferencias`);

--
-- Índices para tabela `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`id_sala`);

--
-- Índices para tabela `sala_componente_atribuida`
--
ALTER TABLE `sala_componente_atribuida`
  ADD PRIMARY KEY (`id_sala`,`id_componente`),
  ADD KEY `componentes` (`id_componente`);

--
-- Índices para tabela `sala_componente_disponivel`
--
ALTER TABLE `sala_componente_disponivel`
  ADD PRIMARY KEY (`idsala_componente_disponivel`),
  ADD UNIQUE KEY `id_componente` (`id_componente`,`id_sala`),
  ADD KEY `sala` (`id_sala`),
  ADD KEY `componente` (`id_componente`);

--
-- Índices para tabela `sala_disciplina`
--
ALTER TABLE `sala_disciplina`
  ADD PRIMARY KEY (`id_sala`,`id_disciplina`),
  ADD KEY `id_disciplina` (`id_disciplina`);

--
-- Índices para tabela `sala_utc`
--
ALTER TABLE `sala_utc`
  ADD PRIMARY KEY (`id_sala`),
  ADD KEY `id_utc` (`id_utc`);

--
-- Índices para tabela `tipo_componente`
--
ALTER TABLE `tipo_componente`
  ADD PRIMARY KEY (`id_tipocomponente`);

--
-- Índices para tabela `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`id_turma`),
  ADD KEY `turma-id_curso_idx` (`id_curso`);

--
-- Índices para tabela `utc`
--
ALTER TABLE `utc`
  ADD PRIMARY KEY (`id_utc`),
  ADD KEY `utc-id_responsavel_idx` (`id_responsavel`);

--
-- Índices para tabela `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`id_utilizador`),
  ADD KEY `utilizador-id_utc_idx` (`id_utc`),
  ADD KEY `utilizador-id_area_idx` (`id_area`),
  ADD KEY `utilizador-id_funcao_idx` (`id_funcao`);

--
-- Índices para tabela `utilizador_preferencia`
--
ALTER TABLE `utilizador_preferencia`
  ADD PRIMARY KEY (`id_utilizador`,`id_preferencias`),
  ADD KEY `pref` (`id_preferencias`);

--
-- Índices para tabela `utilizador_utc`
--
ALTER TABLE `utilizador_utc`
  ADD KEY `utilizador` (`id_utilizador`),
  ADD KEY `disciplina` (`id_disciplina`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `area`
--
ALTER TABLE `area`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `componente`
--
ALTER TABLE `componente`
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `curso_tipo`
--
ALTER TABLE `curso_tipo`
  MODIFY `id_tipo_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `disciplina`
--
ALTER TABLE `disciplina`
  MODIFY `id_disciplina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de tabela `funcao`
--
ALTER TABLE `funcao`
  MODIFY `id_funcao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `horario`
--
ALTER TABLE `horario`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT de tabela `juncao`
--
ALTER TABLE `juncao`
  MODIFY `id_juncao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

--
-- AUTO_INCREMENT de tabela `juncao_componente`
--
ALTER TABLE `juncao_componente`
  MODIFY `id_juncao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

--
-- AUTO_INCREMENT de tabela `preferencias`
--
ALTER TABLE `preferencias`
  MODIFY `id_preferencias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `sala`
--
ALTER TABLE `sala`
  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `sala_componente_disponivel`
--
ALTER TABLE `sala_componente_disponivel`
  MODIFY `idsala_componente_disponivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `tipo_componente`
--
ALTER TABLE `tipo_componente`
  MODIFY `id_tipocomponente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `turma`
--
ALTER TABLE `turma`
  MODIFY `id_turma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de tabela `utc`
--
ALTER TABLE `utc`
  MODIFY `id_utc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `area`
--
ALTER TABLE `area`
  ADD CONSTRAINT `area-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);

--
-- Limitadores para a tabela `aula`
--
ALTER TABLE `aula`
  ADD CONSTRAINT `aula-id_componente` FOREIGN KEY (`id_componente`) REFERENCES `componente` (`id_componente`),
  ADD CONSTRAINT `aula-id_docente` FOREIGN KEY (`id_docente`) REFERENCES `utilizador` (`id_utilizador`),
  ADD CONSTRAINT `aula-id_horario` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`),
  ADD CONSTRAINT `aula-id_juncao` FOREIGN KEY (`id_juncao`) REFERENCES `juncao` (`id_juncao`),
  ADD CONSTRAINT `aula-id_turma` FOREIGN KEY (`id_turma`) REFERENCES `turma` (`id_turma`);

--
-- Limitadores para a tabela `componente`
--
ALTER TABLE `componente`
  ADD CONSTRAINT `componente-id_disciplina` FOREIGN KEY (`id_disciplina`) REFERENCES `disciplina` (`id_disciplina`),
  ADD CONSTRAINT `componente-id_tipocomponente` FOREIGN KEY (`id_tipocomponente`) REFERENCES `tipo_componente` (`id_tipocomponente`);

--
-- Limitadores para a tabela `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso-id_coordenador` FOREIGN KEY (`id_coordenador`) REFERENCES `utilizador` (`id_utilizador`),
  ADD CONSTRAINT `curso-id_tipo_curso` FOREIGN KEY (`id_tipo_curso`) REFERENCES `curso_tipo` (`id_tipo_curso`),
  ADD CONSTRAINT `curso-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);

--
-- Limitadores para a tabela `curso_utc`
--
ALTER TABLE `curso_utc`
  ADD CONSTRAINT `curso_utc-id_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
  ADD CONSTRAINT `curso_utc-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);

--
-- Limitadores para a tabela `disciplina`
--
ALTER TABLE `disciplina`
  ADD CONSTRAINT `disciplina-id_area` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`),
  ADD CONSTRAINT `disciplina-id_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
  ADD CONSTRAINT `disciplina-id_responsavel` FOREIGN KEY (`id_responsavel`) REFERENCES `utilizador` (`id_utilizador`);

--
-- Limitadores para a tabela `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `horario-id_sala` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`);

--
-- Limitadores para a tabela `juncao_componente`
--
ALTER TABLE `juncao_componente`
  ADD CONSTRAINT `area-id_componente` FOREIGN KEY (`id_componente`) REFERENCES `componente` (`id_componente`);

--
-- Limitadores para a tabela `preferencias_turma`
--
ALTER TABLE `preferencias_turma`
  ADD CONSTRAINT `preferencias_turma_ibfk_1` FOREIGN KEY (`id_turma`) REFERENCES `turma` (`id_turma`),
  ADD CONSTRAINT `preferencias_turma_ibfk_2` FOREIGN KEY (`id_preferencias`) REFERENCES `preferencias` (`id_preferencias`);

--
-- Limitadores para a tabela `preferencia_sala`
--
ALTER TABLE `preferencia_sala`
  ADD CONSTRAINT `preferencia_sala_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`),
  ADD CONSTRAINT `preferencia_sala_ibfk_2` FOREIGN KEY (`id_preferencias`) REFERENCES `preferencias` (`id_preferencias`);

--
-- Limitadores para a tabela `sala_componente_atribuida`
--
ALTER TABLE `sala_componente_atribuida`
  ADD CONSTRAINT `componentes` FOREIGN KEY (`id_componente`) REFERENCES `componente` (`id_componente`),
  ADD CONSTRAINT `salas` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`);

--
-- Limitadores para a tabela `sala_componente_disponivel`
--
ALTER TABLE `sala_componente_disponivel`
  ADD CONSTRAINT `componente` FOREIGN KEY (`id_componente`) REFERENCES `componente` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sala` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `sala_disciplina`
--
ALTER TABLE `sala_disciplina`
  ADD CONSTRAINT `sala_disciplina_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sala_disciplina_ibfk_2` FOREIGN KEY (`id_disciplina`) REFERENCES `disciplina` (`id_disciplina`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `sala_utc`
--
ALTER TABLE `sala_utc`
  ADD CONSTRAINT `fk_sala` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`) ON DELETE CASCADE,
  ADD CONSTRAINT `id_sala` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`),
  ADD CONSTRAINT `id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);

--
-- Limitadores para a tabela `turma`
--
ALTER TABLE `turma`
  ADD CONSTRAINT `turma-id_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`);

--
-- Limitadores para a tabela `utc`
--
ALTER TABLE `utc`
  ADD CONSTRAINT `utc-id_responsavel` FOREIGN KEY (`id_responsavel`) REFERENCES `utilizador` (`id_utilizador`);

--
-- Limitadores para a tabela `utilizador`
--
ALTER TABLE `utilizador`
  ADD CONSTRAINT `utilizador-id_area` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`),
  ADD CONSTRAINT `utilizador-id_funcao` FOREIGN KEY (`id_funcao`) REFERENCES `funcao` (`id_funcao`),
  ADD CONSTRAINT `utilizador-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);

--
-- Limitadores para a tabela `utilizador_preferencia`
--
ALTER TABLE `utilizador_preferencia`
  ADD CONSTRAINT `pref` FOREIGN KEY (`id_preferencias`) REFERENCES `preferencias` (`id_preferencias`),
  ADD CONSTRAINT `user` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`);

--
-- Limitadores para a tabela `utilizador_utc`
--
ALTER TABLE `utilizador_utc`
  ADD CONSTRAINT `disciplina` FOREIGN KEY (`id_disciplina`) REFERENCES `disciplina` (`id_disciplina`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
