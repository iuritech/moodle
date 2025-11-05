<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['array_opcoes'])){

		$array_opcoes = $_POST['array_opcoes'];
		
//		print_r($array_opcoes);
		
		$copiar_dados = $array_opcoes[0];
		
		$copiar_utcs = $array_opcoes[1]; 
		$copiar_docentes = $array_opcoes[2]; 
		$copiar_cursos = $array_opcoes[3]; 
		$copiar_areas = $array_opcoes[4]; 
		$copiar_ucs = $array_opcoes[5]; 
		$copiar_turmas = $array_opcoes[6]; 
		$copiar_dsd = $array_opcoes[7]; 
		$copiar_horarios = $array_opcoes[8]; 
		
		$maior_ano_letivo = "";
		
		$loop_1 = 0;	
		
		$result = mysqli_query($conn,"SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA 
		WHERE SCHEMA_NAME LIKE 'apoio_utc%' ORDER BY SCHEMA_NAME DESC;"); 
		while ($row = mysqli_fetch_array($result)) { 
			$nome_bd = $row[0];
			
			$tmp = explode("_",$nome_bd);
			
			$nome_ano = $tmp[2] . "/" . $tmp[3];
			
			if($loop_1 == 0){
				$maior_ano_letivo = $nome_ano;
			}
			
			$loop_1 += 1;
		}

		$tmp1 = explode("/",$maior_ano_letivo);
		$tmp2 = $tmp1[0] + 1;
		$tmp3 = $tmp1[1] + 1;

		$maior_ano_letivo = $tmp2 . "/" . $tmp3;
		
		$nome_final_bd = "apoio_utc_" . $tmp2 . "_" . $tmp3;
		
		echo $copiar_dados, " - ", $copiar_utcs, " - ", $copiar_docentes, " - ", $copiar_cursos, " - ", $copiar_areas, " - ", $copiar_ucs, " - ", $copiar_turmas, " - ", $copiar_dsd, " - ", $copiar_horarios;
		
		if($copiar_dados == 'true'){
			echo "Copiar dados do ano passado! ", $nome_final_bd;
			
			$statement0 = mysqli_prepare($conn, "CREATE DATABASE IF NOT EXISTS `$nome_final_bd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
			$statement0->execute();
			
			mysqli_select_db($conn,"$nome_final_bd");
			
			$statement1 = mysqli_prepare($conn, "CREATE TABLE `area` (
												  `id_area` int(11) NOT NULL,
												  `nome` varchar(50) NOT NULL,
												  `id_utc` int(11) DEFAULT NULL,
												  `nome_completo` varchar(255) DEFAULT NULL,
												  `imagem` varchar(255) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement1->execute();
			
			$statement2 = mysqli_prepare($conn, "CREATE TABLE `aula` (
												  `id_componente` int(11) NOT NULL,
												  `id_horario` int(11) DEFAULT NULL,
												  `id_turma` int(11) NOT NULL,
												  `id_docente` int(11) DEFAULT NULL,
												  `id_juncao` int(11) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement2->execute();
			
			$statement3 = mysqli_prepare($conn, "CREATE TABLE `componente` (
												  `id_componente` int(11) NOT NULL,
												  `id_disciplina` int(11) DEFAULT NULL,
												  `id_tipocomponente` int(11) DEFAULT NULL,
												  `numero_horas` double DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement3->execute();
			
			$statement4 = mysqli_prepare($conn, "CREATE TABLE `curso` (
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
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement4->execute();
			
			$statement5 = mysqli_prepare($conn, "CREATE TABLE `curso_tipo` (
												  `id_tipo_curso` int(11) NOT NULL,
												  `nome` varchar(255) NOT NULL,
												  `sigla` varchar(50) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement5->execute();
			
			$statement6 = mysqli_prepare($conn, "CREATE TABLE `curso_utc` (
												  `id_curso` int(11) NOT NULL,
												  `id_utc` int(11) NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement6->execute();
			
			$statement7 = mysqli_prepare($conn, "CREATE TABLE `disciplina` (
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
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement7->execute();
			
			$statement8 = mysqli_prepare($conn, "CREATE TABLE `funcao` (
												  `id_funcao` int(11) NOT NULL,
												  `nome` varchar(45) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement8->execute();
			
			$statement10 = mysqli_prepare($conn, "CREATE TABLE `horario` (
												  `id_horario` int(11) NOT NULL,
												  `dia_semana` varchar(50) DEFAULT NULL,
												  `hora_inicio` time DEFAULT NULL,
												  `hora_fim` time DEFAULT NULL,
												  `id_sala` int(11) DEFAULT NULL,
												  `semestre` int(1) NOT NULL DEFAULT 2
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement10->execute();
			
			$statement11 = mysqli_prepare($conn, "CREATE TABLE `juncao` (
												  `id_juncao` int(11) NOT NULL,
												  `nome_juncao` varchar(255) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement11->execute();
			
			$statement12 = mysqli_prepare($conn, "CREATE TABLE `juncao_componente` (
												  `id_juncao` int(11) NOT NULL,
												  `id_componente` int(11) NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement12->execute();
			
			$statement13 = mysqli_prepare($conn, "CREATE TABLE `sala` (
												  `id_sala` int(11) NOT NULL,
												  `nome_sala` varchar(50) DEFAULT NULL,
												  `bloco_sala` varchar(1) NOT NULL DEFAULT 'A'
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement13->execute();
			
			$statement15 = mysqli_prepare($conn, "CREATE TABLE `tipo_componente` (
												  `id_tipocomponente` int(11) NOT NULL,
												  `nome_tipocomponente` varchar(45) DEFAULT NULL,
												  `sigla_tipocomponente` varchar(45) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement15->execute();
			
			$statement16 = mysqli_prepare($conn, "CREATE TABLE `turma` (
												  `id_turma` int(11) NOT NULL,
												  `nome` varchar(50) DEFAULT NULL,
												  `ano` int(11) DEFAULT NULL,
												  `semestre` int(11) DEFAULT NULL,
												  `id_curso` int(11) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement16->execute();
			
			$statement17 = mysqli_prepare($conn, "CREATE TABLE `utc` (
												  `id_utc` int(11) NOT NULL,
												  `nome_utc` varchar(50) NOT NULL,
												  `id_responsavel` int(11) DEFAULT NULL,
												  `sigla_utc` varchar(50) DEFAULT NULL,
												  `dsd_1_sem` int(1) NOT NULL DEFAULT 0,
												  `dsd_2_sem` int(1) NOT NULL DEFAULT 0
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement17->execute();
			
			$statement18 = mysqli_prepare($conn, "CREATE TABLE `utilizador` (
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
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement18->execute();
			
			$statement19 = mysqli_prepare($conn, "ALTER TABLE `area`
												  ADD PRIMARY KEY (`id_area`),
												  ADD KEY `area-id_utc_idx` (`id_utc`);");
			$statement19->execute();
			
			$statement20 = mysqli_prepare($conn, "ALTER TABLE `aula`
												  ADD PRIMARY KEY (`id_componente`,`id_turma`),
												  ADD KEY `aula-id_componente_idx` (`id_componente`),
												  ADD KEY `aula-id_turma_idx` (`id_turma`),
												  ADD KEY `aula-id_docente_idx` (`id_docente`),
												  ADD KEY `aula-id_horario_idx` (`id_horario`),
												  ADD KEY `aula-id_juncao_idx` (`id_juncao`);");
			$statement20->execute();
			
			$statement21 = mysqli_prepare($conn, "ALTER TABLE `componente`
												  ADD PRIMARY KEY (`id_componente`),
												  ADD KEY `componente-id_tipocomponente_idx` (`id_tipocomponente`),
												  ADD KEY `componente-id_disciplina_idx` (`id_disciplina`);");
			$statement21->execute();
			
			$statement22 = mysqli_prepare($conn, "ALTER TABLE `curso`
												  ADD PRIMARY KEY (`id_curso`),
												  ADD KEY `curso-id_utc_idx` (`id_utc`),
												  ADD KEY `curso-id_tipo_curso_idx` (`id_tipo_curso`),
												  ADD KEY `curso-id_coordenador_idx` (`id_coordenador`);");
			$statement22->execute();
			
			$statement23 = mysqli_prepare($conn, "ALTER TABLE `curso_tipo`
												  ADD PRIMARY KEY (`id_tipo_curso`);");
			$statement23->execute();
			
			$statement24 = mysqli_prepare($conn, "ALTER TABLE `curso_utc`
												  ADD PRIMARY KEY (`id_curso`,`id_utc`),
												  ADD KEY `curso_utc-id_utc_idx` (`id_utc`);");
			$statement24->execute();
			
			$statement25 = mysqli_prepare($conn, "ALTER TABLE `disciplina`
												  ADD PRIMARY KEY (`id_disciplina`),
												  ADD KEY `disciplina-id_area_idx` (`id_area`),
												  ADD KEY `disciplina-id_responsavel_idx` (`id_responsavel`),
												  ADD KEY `disciplina-id_curso_idx` (`id_curso`);");
			$statement25->execute();
			
			$statement26 = mysqli_prepare($conn, "ALTER TABLE `funcao`
												  ADD PRIMARY KEY (`id_funcao`);");
			$statement26->execute();
			
			$statement27 = mysqli_prepare($conn, "ALTER TABLE `horario`
												  ADD PRIMARY KEY (`id_horario`),
												  ADD KEY `horario-id_sala_idx` (`id_sala`);");
			$statement27->execute();
			
			$statement28 = mysqli_prepare($conn, "ALTER TABLE `juncao`
												  ADD PRIMARY KEY (`id_juncao`);");
			$statement28->execute();
			
			$statement29 = mysqli_prepare($conn, "ALTER TABLE `juncao_componente`
												  ADD PRIMARY KEY (`id_juncao`,`id_componente`),
												  ADD KEY `area-id_componente_idx` (`id_componente`);");
			$statement29->execute();
			
			$statement30 = mysqli_prepare($conn, "ALTER TABLE `sala`
												  ADD PRIMARY KEY (`id_sala`);");
			$statement30->execute();
			
			$statement31 = mysqli_prepare($conn, "ALTER TABLE `tipo_componente`
												  ADD PRIMARY KEY (`id_tipocomponente`);");
			$statement31->execute();
			
			$statement32 = mysqli_prepare($conn, "ALTER TABLE `turma`
												  ADD PRIMARY KEY (`id_turma`),
												  ADD KEY `turma-id_curso_idx` (`id_curso`);");
			$statement32->execute();
			
			$statement33 = mysqli_prepare($conn, "ALTER TABLE `utc`
												  ADD PRIMARY KEY (`id_utc`),
												  ADD KEY `utc-id_responsavel_idx` (`id_responsavel`);");
			$statement33->execute();
			
			$statement34 = mysqli_prepare($conn, "ALTER TABLE `utilizador`
												  ADD PRIMARY KEY (`id_utilizador`),
												  ADD KEY `utilizador-id_utc_idx` (`id_utc`),
												  ADD KEY `utilizador-id_area_idx` (`id_area`),
												  ADD KEY `utilizador-id_funcao_idx` (`id_funcao`);");
			$statement34->execute();
			
			$statement64 = mysqli_prepare($conn, "INSERT INTO `area` (`id_area`, `nome`, `id_utc`, `nome_completo`, `imagem`) VALUES
			(11, 'TESTE', 3, 'UTC TESTE', 'https://i.ibb.co/68Qw3Gm/default.png');");
			$statement64->execute();
			
			$statement64 = mysqli_prepare($conn, "INSERT INTO `curso_tipo` (`id_tipo_curso`, `nome`, `sigla`) VALUES
												(1, 'Licenciatura', 'LIC.'),
												(2, 'Mestrado', 'MES.'),
												(3, 'Curso Superior Técnico', 'CTeSP.');");
			$statement64->execute();
			
			$statement9 = mysqli_prepare($conn, "INSERT INTO `funcao` (`id_funcao`, `nome`) VALUES
												(1, 'Administrador'),
												(2, 'Gestor UTC'),
												(3, 'Gestor Área'),
												(4, 'Prof. Adjunto'),
												(5, 'Prof. Coord.'),
												(6, 'Assit. Conv.');");
			$statement9->execute();
			
			$statement14 = mysqli_prepare($conn, "INSERT INTO `sala` (`id_sala`, `nome_sala`, `bloco_sala`) VALUES
												(1, 'ANF B', 'B'),
												(2, 'A.7', 'A'),
												(3, 'D.5', 'D'),
												(4, 'AUD', 'A'),
												(5, 'A.1', 'A'),
												(6, 'A.2', 'A'),
												(7, 'A.5', 'A'),
												(8, 'C.10', 'C'),
												(9, 'A.4', 'A'),
												(10, 'A.3', 'A'),
												(11, 'A.9', 'A'),
												(12, 'D.6', 'D');");
			$statement14->execute();
		
			$statement64 = mysqli_prepare($conn, "INSERT INTO `utc` (`id_utc`, `nome_utc`, `id_responsavel`, `sigla_utc`, `dsd_1_sem`, `dsd_2_sem`) VALUES
												(3, 'UTC Teste', 1, 'UT', 0, 0);");
			$statement64->execute();

			$statement64 = mysqli_prepare($conn, "INSERT INTO `utilizador` (`id_utilizador`, `nome`, `login`, `password`, `imagem_perfil`, `id_utc`, `id_area`, `id_funcao`, `is_admin`, `ano_letivo`, `perm_horarios`) VALUES
(1, 'Administrador', 'admin', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 3, 11, 4, 1, '2021_2022', 0);");
			$statement64->execute();
			
			
			if($copiar_utcs == 'true'){
				$statement64 = mysqli_prepare($conn, "INSERT INTO `utc` (`id_utc`, `nome_utc`, `id_responsavel`, `sigla_utc`, `dsd_1_sem`, `dsd_2_sem`) VALUES
													(1, 'Informática', 18, 'INF', 0, 0),
													(2, 'Civil', 10, 'CIV', 0, 0),
													(10, 'UTCTeste123', 32, 'UTCT', 0, 0);");
				$statement64->execute();
			}
			
			if($copiar_areas == 'true'){
				$statement64 = mysqli_prepare($conn, "INSERT INTO `area` (`id_area`, `nome`, `id_utc`, `nome_completo`, `imagem`) VALUES
													(1, 'PADS', 1, 'Programação, Algoritmos e Desenvolvimento de Software', 'https://i.ibb.co/YTm5cXy/pads.jpg'),
													(2, 'SI', 1, 'Sistemas de Informação', 'https://i.ibb.co/m0J6QCD/si.jpg'),
													(3, 'ACSOR', 1, 'Arquitetura de Computadores, Sistemas Operativos e Redes de Computadores', 'https://i.ibb.co/gVtZM3y/acsor.jpg'),
													(4, 'MTC', 1, 'Multimédia e Tecnologias Criativas', 'https://i.ibb.co/68Qw3Gm/default.png'),
													(5, 'EMT', 2, NULL, 'https://i.ibb.co/68Qw3Gm/default.png'),
													(6, 'BAB', 2, NULL, 'https://i.ibb.co/68Qw3Gm/default.png'),
													(7, 'SCRI', 1, NULL, 'https://i.ibb.co/68Qw3Gm/default.png'),
													(8, 'DAAAA', 1, 'Isto é um teste para o nome da área', 'http://localhost/apoio_utc/images/area/default.png');");	
				$statement64->execute();
			}
			
			if($copiar_cursos == 'true'){
				$statement64 = mysqli_prepare($conn, "INSERT INTO `curso` (`id_curso`, `nome`, `nome_completo`, `sigla`, `sigla_completa`, `semestres`, `imagem_curso`, `id_utc`, `id_tipo_curso`, `id_coordenador`) VALUES
													(1, 'Engenharia Informática', 'CURSO LICENCIATURA EM ENGENHARIA INFORMÁTICA', 'INF', 'LIC.INF', 6, 'https://i.ibb.co/W680YzW/eng-inf.jpg', 1, 1, 4),
													(2, 'Tecnologias de Informação e Multimédia', 'CURSO LICENCIATURA EM TECNOLOGIAS DE INFORMAÇÃO E MULTIMÉDIA', 'TIM', 'LIC.TIM', 6, 'https://i.ibb.co/c3hWCBt/TIM.png', 1, 1, 4),
													(3, 'Desenvolvimento de Produtos Multimédia', 'CURSO SUPERIOR TÉCNICO EM DESENVOLVIMENTO DE PRODUTOS MULTIMÉDIA', 'DPM', 'CTeSP.DPM', 4, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 1, 3, 4),
													(4, 'Tecnologias e Programação de Sistemas de Informação', 'CURSO SUPERIOR TÉCNICO EM TECNOLOGIAS E PROGRAMAÇÃO DE SISTEMAS DE INFORMAÇÃO', 'TPSI', 'CTesP.TPSI', 4, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 1, 3, 4),
													(5, 'Data Center e Computação Cloud', 'CURSO SUPERIOR TÉCNICO EM DATA CENTER E COMPUTAÇÃO CLOUD', 'DCCC', 'CTesP.DCCC', 4, 'https://i.ibb.co/d5bdKNd/660x371-abc1c69847550ddefd872148986931ab.jpg', 1, 3, 4),
													(6, 'Desenvolvimento de Software e Sistemas Interativos', 'CURSO MESTRADO EM DESENVOLVIMENTO DE SOFTWARE E SISTEMAS INTERATIVOS', 'DCCC', 'MES.DSSI', 4, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 1, 2, 4),
													(7, 'Engenharia Civil', 'LICENCIATURA EM ENGENHARIA CIVIL', 'CIV', 'LIC.EC', 6, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 2, 1, 10),
													(8, 'Curso Teste', 'LICENCIATURA EM CURSO TESTE', 'TEST', 'LIC.TESTE', 6, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 3, 1, 11),
													(12, 'CURSOTESTE123', 'CURSO LICENCIATURA EM CURSO TESTE', 'CT', 'LIC.CT', 6, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 1, 1, 4),
													(14, 'Curso Teste', 'LICENCIATURA EM CURSO TESTE', 'TEST', 'LIC.TESTE', 6, 'https://i.ibb.co/4Kfz5SP/service-6.jpg', 10, 1, 32);");					
				$statement64->execute();
			}
			
			if($copiar_docentes == 'true'){
				$statement64 = mysqli_prepare($conn, "INSERT INTO `utilizador` (`id_utilizador`, `nome`, `login`, `password`, `imagem_perfil`, `id_utc`, `id_area`, `id_funcao`, `is_admin`, `ano_letivo`) VALUES
													(4, 'Sérgio', 'sergio', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 1, 4, 0, '2020_2021'),
													(5, 'Arlindo Silva', 'arlindo', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 1, 4, 0, '2021_2022'),
													(6, 'Tiago', 'tiago', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 4, 6, 0, '2021_2022'),
													(7, 'Ana Silva', 'ana', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 3, 4, 0, '2021_2022'),
													(8, 'Teresa', 'teresa', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 3, 4, 0, '2021_2022'),
													(9, 'Luis Barata', 'luis', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 5, 6, 0, '2021_2022'),
													(10, 'Rosa', 'rosa', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 0, '2021_2022'),
													(11, 'Utilizador Teste', 'teste', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 3, 1, 4, 0, '2021_2022'),
													(12, 'Utilizador Teste GestorArea', 'testearea', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 3, 1, 4, 0, '2021_2022'),
													(13, 'Utilizador Teste GestorUTC', 'testeutc', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 3, 1, 4, 0, '2021_2022'),
													(16, 'Eurico Lopes', 'eurico', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 2, 5, 0, '2021_2022'),
													(17, 'Paulo Serra', 'paulo', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 2, 6, 0, '2021_2022'),
													(18, 'Ângela Oliveira', 'angela', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 4, 4, 0, '2021_2022'),
													(19, 'João Caldeira', 'joao_caldeira', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 2, 4, 0, '2021_2022'),
													(20, 'Pedro Passão', 'pedro_passao', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 1, 6, 0, '2021_2022'),
													(21, 'Alexandre Fonte', 'alexandre_fonte', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 3, 4, 0, '2021_2022'),
													(22, 'Vasco Soares', 'vasco_soares', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 3, 4, 0, '2021_2022'),
													(23, 'Maria Constança Rigueiro', 'constanca_rigueiro', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 0, '2021_2022'),
													(24, 'Arlindo Cabrito', 'arlindo_cabrito', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 0, '2021_2022'),
													(25, 'Carlos Alves', 'carlos_alves', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 2, 4, 0, '2021_2022'),
													(31, 'Utilizador', 'Utilizador123', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 0, '2021_2022'),
													(32, 'Utilizador Teste', 'utilizador_teste', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 10, 2, 4, 1, '2021_2022'),
													(33, 'TesteTeste', 'teste123', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 2, 6, 4, 1, '2021_2022'),
													(35, 'Admin Teste Teste', 'admin_teste', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 1, 3, 5, 1, '2021_2022');");					
				$statement64->execute();
			}
			
			if($copiar_ucs == 'true'){
				$statement64 = mysqli_prepare($conn, "INSERT INTO `disciplina` (`id_disciplina`, `nome_uc`, `codigo_uc`, `abreviacao_uc`, `ano`, `semestre`, `id_responsavel`, `id_area`, `id_curso`, `imagem`) VALUES
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
													(111, 'UC Teste', 1234, 'UCT', 3, 1, 32, 11, 14, '');");					
				$statement64->execute();
				
				$statement64 = mysqli_prepare($conn, "INSERT INTO `tipo_componente` (`id_tipocomponente`, `nome_tipocomponente`, `sigla_tipocomponente`) VALUES
													(1, 'Teórica', 'T'),
													(2, 'Teórico-Prática', 'TP'),
													(3, 'Prática', 'P'),
													(4, 'Prático-Laboratoriais', 'PL'),
													(5, 'Trabalho de Campo', 'TC'),
													(6, 'Orientação Tutorial', 'OT'),
													(7, 'Seminário', 'S');");					
				$statement64->execute();
				
				$statement64 = mysqli_prepare($conn, "INSERT INTO `componente` (`id_componente`, `id_disciplina`, `id_tipocomponente`, `numero_horas`) VALUES
													(1, 1, 2, 3.5),
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
													(34, 29, 6, 1),
													(35, 8, 2, 2),
													(36, 8, 3, 2),
													(37, 9, 2, 2),
													(38, 9, 3, 1),
													(39, 10, 2, 4),
													(40, 10, 3, 3.5),
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
													(116, 111, 3, 3);");					
				$statement64->execute();
			}
			
			if($copiar_turmas == 'true'){
				$statement64 = mysqli_prepare($conn, "INSERT INTO `turma` (`id_turma`, `nome`, `ano`, `semestre`, `id_curso`) VALUES
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
													(102, 'INFT', 1, 2, 1),
													(110, 'TEST1', 3, 1, 12),
													(111, 'TEST2', 3, 1, 12),
													(114, 'TURM1', 3, 1, 14),
													(115, 'TURM2', 3, 1, 14);");					
				$statement64->execute();
			}
			
			//Tabela aula
			if($copiar_ucs == 'true' && $copiar_turmas == 'true' && $copiar_dsd == 'false'){
				$statement64 = mysqli_prepare($conn, "INSERT INTO `aula` (`id_componente`, `id_horario`, `id_turma`, `id_docente`, `id_juncao`) VALUES
													(1, NULL, 1, NULL, NULL),
													(1, NULL, 2, NULL, NULL),
													(1, NULL, 3, NULL, NULL),
													(1, NULL, 4, NULL, NULL),
													(1, NULL, 17, NULL, NULL),
													(2, NULL, 1, NULL, NULL),
													(2, NULL, 2, NULL, NULL),
													(2, NULL, 3, NULL, NULL),
													(2, NULL, 4, NULL, NULL),
													(2, NULL, 17, NULL, NULL),
													(3, NULL, 1, NULL, NULL),
													(3, NULL, 2, NULL, NULL),
													(3, NULL, 3, NULL, NULL),
													(3, NULL, 4, NULL, NULL),
													(3, NULL, 17, NULL, NULL),
													(4, NULL, 1, NULL, NULL),
													(4, NULL, 2, NULL, NULL),
													(4, NULL, 3, NULL, NULL),
													(4, NULL, 4, NULL, NULL),
													(4, NULL, 17, NULL, NULL),
													(5, NULL, 1, NULL, NULL),
													(5, NULL, 2, NULL, NULL),
													(5, NULL, 3, NULL, NULL),
													(5, NULL, 4, NULL, NULL),
													(5, NULL, 17, NULL, NULL),
													(6, NULL, 1, NULL, NULL),
													(6, NULL, 2, NULL, NULL),
													(6, NULL, 3, NULL, NULL),
													(6, NULL, 4, NULL, NULL),
													(6, NULL, 17, NULL, NULL),
													(7, NULL, 5, NULL, NULL),
													(8, NULL, 5, NULL, NULL),
													(9, NULL, 5, NULL, NULL),
													(10, NULL, 5, NULL, NULL),
													(29, NULL, 12, NULL, NULL),
													(29, NULL, 13, NULL, NULL),
													(29, NULL, 102, NULL, NULL),
													(30, NULL, 12, NULL, NULL),
													(30, NULL, 13, NULL, NULL),
													(30, NULL, 102, NULL, NULL),
													(34, NULL, 5, NULL, NULL),
													(34, NULL, 28, NULL, NULL),
													(35, NULL, 21, NULL, NULL),
													(35, NULL, 22, NULL, NULL),
													(35, NULL, 23, NULL, NULL),
													(36, NULL, 21, NULL, NULL),
													(36, NULL, 22, NULL, NULL),
													(36, NULL, 23, NULL, NULL),
													(37, NULL, 24, NULL, NULL),
													(37, NULL, 25, NULL, NULL),
													(37, NULL, 26, NULL, NULL),
													(38, NULL, 24, NULL, NULL),
													(38, NULL, 25, NULL, NULL),
													(38, NULL, 26, NULL, NULL),
													(39, NULL, 27, NULL, NULL),
													(40, NULL, 27, NULL, NULL),
													(41, NULL, 24, NULL, NULL),
													(41, NULL, 25, NULL, NULL),
													(41, NULL, 26, NULL, NULL),
													(42, NULL, 28, NULL, NULL),
													(43, NULL, 28, NULL, NULL),
													(44, NULL, 31, NULL, NULL),
													(44, NULL, 32, NULL, NULL),
													(44, NULL, 33, NULL, NULL),
													(45, NULL, 31, NULL, NULL),
													(45, NULL, 32, NULL, NULL),
													(45, NULL, 33, NULL, NULL),
													(46, NULL, 29, NULL, NULL),
													(46, NULL, 30, NULL, NULL),
													(47, NULL, 29, NULL, NULL),
													(47, NULL, 30, NULL, NULL),
													(48, NULL, 34, NULL, NULL),
													(49, NULL, 34, NULL, NULL),
													(111, NULL, 110, NULL, NULL),
													(111, NULL, 111, NULL, NULL),
													(115, NULL, 114, NULL, NULL),
													(115, NULL, 115, NULL, NULL),
													(116, NULL, 114, NULL, NULL),
													(116, NULL, 115, NULL, NULL);");					
				$statement64->execute();
			}
			
			//Atualizar tabela aula com os docentes e junções
			if($copiar_dsd == 'true' && $copiar_horarios == 'false'){
				
				$statement64 = mysqli_prepare($conn, "INSERT INTO `juncao` (`id_juncao`, `nome_juncao`) VALUES
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
													(283, 'JUNCAO_TESTE12');");	
				$statement64->execute();
				
				$statement64 = mysqli_prepare($conn, "INSERT INTO `juncao_componente` (`id_juncao`, `id_componente`) VALUES
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
													(283, 116);");						
				$statement64->execute();
				
				$statement64 = mysqli_prepare($conn, "INSERT INTO `aula` (`id_componente`, `id_horario`, `id_turma`, `id_docente`, `id_juncao`) VALUES
													(1, NULL, 1, 5, 1),
													(1, NULL, 2, 5, 1),
													(1, NULL, 3, 5, 1),
													(1, NULL, 4, 5, 1),
													(1, NULL, 17, 5, 1),
													(2, NULL, 1, 5, NULL),
													(2, NULL, 2, 5, 129),
													(2, NULL, 3, 5, 130),
													(2, NULL, 4, 5, 129),
													(2, NULL, 17, 5, 130),
													(3, NULL, 1, 5, NULL),
													(3, NULL, 2, 20, 131),
													(3, NULL, 3, 4, 132),
													(3, NULL, 4, 20, 131),
													(3, NULL, 17, 4, 132),
													(4, NULL, 1, 7, 169),
													(4, NULL, 2, 7, 169),
													(4, NULL, 3, 7, 169),
													(4, NULL, 4, 7, 169),
													(4, NULL, 17, 7, 169),
													(5, NULL, 1, 32, 282),
													(5, NULL, 2, NULL, 273),
													(5, NULL, 3, NULL, 273),
													(5, NULL, 4, NULL, 245),
													(5, NULL, 17, NULL, 245),
													(6, NULL, 1, 6, 233),
													(6, NULL, 2, 6, 233),
													(6, NULL, 3, 6, 233),
													(6, NULL, 4, 6, 233),
													(6, NULL, 17, 6, 233),
													(7, NULL, 5, 5, NULL),
													(8, NULL, 5, 5, 129),
													(9, NULL, 5, 20, 131),
													(10, NULL, 5, 18, NULL),
													(29, NULL, 12, 4, 195),
													(29, NULL, 13, 4, 195),
													(29, NULL, 102, NULL, NULL),
													(30, NULL, 12, 4, 247),
													(30, NULL, 13, 4, 247),
													(30, NULL, 102, NULL, NULL),
													(34, NULL, 5, NULL, NULL),
													(34, NULL, 28, 16, NULL),
													(35, NULL, 21, 16, 120),
													(35, NULL, 22, 16, 120),
													(35, NULL, 23, 32, 282),
													(36, NULL, 21, 16, NULL),
													(36, NULL, 22, 17, NULL),
													(36, NULL, 23, NULL, NULL),
													(37, NULL, 24, 4, 141),
													(37, NULL, 25, 4, 141),
													(37, NULL, 26, 4, 141),
													(38, NULL, 24, 4, NULL),
													(38, NULL, 25, NULL, NULL),
													(38, NULL, 26, 4, NULL),
													(39, NULL, 27, 4, 141),
													(40, NULL, 27, 4, NULL),
													(41, NULL, 24, 19, NULL),
													(41, NULL, 25, 19, 142),
													(41, NULL, 26, 19, 142),
													(42, NULL, 28, 21, 278),
													(43, NULL, 28, 22, NULL),
													(44, NULL, 31, 25, NULL),
													(44, NULL, 32, 25, NULL),
													(44, NULL, 33, 25, NULL),
													(45, NULL, 31, 16, 204),
													(45, NULL, 32, 16, 204),
													(45, NULL, 33, NULL, NULL),
													(46, NULL, 29, 23, 158),
													(46, NULL, 30, 23, 158),
													(47, NULL, 29, 24, NULL),
													(47, NULL, 30, 24, NULL),
													(48, NULL, 34, 4, NULL),
													(49, NULL, 34, 4, 247),
													(111, NULL, 110, 21, 278),
													(111, NULL, 111, 21, 278),
													(115, NULL, 114, 32, 282),
													(115, NULL, 115, 32, 282),
													(116, NULL, 114, 32, 283),
													(116, NULL, 115, 32, 283);");					
				$statement64->execute();
			}
			
			//Inserir na tabela horário e atualizar tabela aula com os horários respetivos
			if($copiar_horarios == 'true'){
				$statement64 = mysqli_prepare($conn, "INSERT INTO `juncao` (`id_juncao`, `nome_juncao`) VALUES
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
													(283, 'JUNCAO_TESTE12');");	
				$statement64->execute();
				
				$statement64 = mysqli_prepare($conn, "INSERT INTO `juncao_componente` (`id_juncao`, `id_componente`) VALUES
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
													(283, 116);");	
				$statement64->execute();
				
				$statement64 = mysqli_prepare($conn, "INSERT INTO `horario` (`id_horario`, `dia_semana`, `hora_inicio`, `hora_fim`, `id_sala`, `semestre`) VALUES
													(1, 'QUA', '08:30:00', '10:30:00', 1, 2),
													(2, 'QUA', '10:30:00', '12:30:00', 3, 2),
													(3, 'QUI', '10:30:00', '12:30:00', 2, 2),
													(4, 'TER', '11:30:00', '13:30:00', 4, 2),
													(5, 'QUA', '08:30:00', '11:30:00', 5, 2),
													(6, 'QUI', '08:30:00', '10:30:00', 5, 1),
													(7, 'SEX', '08:30:00', '10:30:00', 6, 2),
													(8, 'SEX', '10:30:00', '12:30:00', 6, 2),
													(9, 'QUI', '11:30:00', '13:30:00', 7, 2),
													(10, 'TER', '09:00:00', '11:00:00', 8, 2),
													(11, 'SEX', '08:30:00', '10:30:00', 4, 2),
													(12, 'SEX', '13:30:00', '13:30:00', 5, 2),
													(13, 'QUI', '10:30:00', '13:30:00', 5, 1),
													(14, 'SEG', '14:30:00', '17:30:00', 6, 2),
													(15, 'SEX', '15:30:00', '12:30:00', 5, 2),
													(16, 'SEG', '15:30:00', '18:30:00', 9, 2),
													(17, 'TER', '09:30:00', '12:30:00', 9, 2),
													(18, 'QUI', '10:30:00', '12:30:00', 11, 2),
													(19, 'SEX', '08:30:00', '11:30:00', 10, 2),
													(20, 'SEG', '08:30:00', '10:30:00', 6, 2),
													(21, 'QUA', '10:30:00', '13:30:00', 5, 2),
													(22, 'QUI', '09:30:00', '10:30:00', 3, 2),
													(23, 'QUI', '10:30:00', '13:30:00', 12, 2),
													(24, 'TER', '10:30:00', '13:30:00', 3, 2),
													(25, 'SEX', '08:30:00', '10:30:00', 5, 2);");	
				$statement64->execute();
				
				$statement64 = mysqli_prepare($conn, "INSERT INTO `aula` (`id_componente`, `id_horario`, `id_turma`, `id_docente`, `id_juncao`) VALUES
													(1, 4, 1, 5, 1),
													(1, 4, 2, 5, 1),
													(1, 4, 3, 5, 1),
													(1, 4, 4, 5, 1),
													(1, 4, 17, 5, 1),
													(2, 10, 1, 5, NULL),
													(2, 5, 2, 5, 129),
													(2, 7, 3, 5, 130),
													(2, 5, 4, 5, 129),
													(2, 7, 17, 5, 130),
													(3, 9, 1, 5, NULL),
													(3, 6, 2, 20, 131),
													(3, 8, 3, 4, 132),
													(3, 6, 4, 20, 131),
													(3, 8, 17, 4, 132),
													(4, NULL, 1, 7, 169),
													(4, NULL, 2, 7, 169),
													(4, NULL, 3, 7, 169),
													(4, NULL, 4, 7, 169),
													(4, NULL, 17, 7, 169),
													(5, NULL, 1, 32, 282),
													(5, NULL, 2, NULL, 273),
													(5, NULL, 3, NULL, 273),
													(5, NULL, 4, NULL, 245),
													(5, NULL, 17, NULL, 245),
													(6, NULL, 1, 6, 233),
													(6, NULL, 2, 6, 233),
													(6, NULL, 3, 6, 233),
													(6, NULL, 4, 6, 233),
													(6, NULL, 17, 6, 233),
													(7, NULL, 5, 5, NULL),
													(8, 20, 5, 5, 129),
													(9, 21, 5, 20, 131),
													(10, 5, 5, 18, NULL),
													(29, 5, 12, 4, 195),
													(29, 5, 13, 4, 195),
													(29, NULL, 102, NULL, NULL),
													(30, NULL, 12, 4, 247),
													(30, NULL, 13, 4, 247),
													(30, NULL, 102, NULL, NULL),
													(34, NULL, 5, NULL, NULL),
													(34, 1, 28, 16, NULL),
													(35, 1, 21, 16, 120),
													(35, 1, 22, 16, 120),
													(35, 2, 23, 32, 282),
													(36, 3, 21, 16, NULL),
													(36, 2, 22, 17, NULL),
													(36, 2, 23, NULL, NULL),
													(37, 11, 24, 4, 141),
													(37, 11, 25, 4, 141),
													(37, 11, 26, 4, 141),
													(38, 12, 24, 4, NULL),
													(38, 13, 25, NULL, NULL),
													(38, 14, 26, 4, NULL),
													(39, 11, 27, 4, 141),
													(40, 15, 27, 4, NULL),
													(41, 16, 24, 19, NULL),
													(41, 17, 25, 19, 142),
													(41, 17, 26, 19, 142),
													(42, 18, 28, 21, 278),
													(43, 19, 28, 22, NULL),
													(44, 25, 31, 25, NULL),
													(44, NULL, 32, 25, NULL),
													(44, NULL, 33, 25, NULL),
													(45, NULL, 31, 16, 204),
													(45, NULL, 32, 16, 204),
													(45, NULL, 33, NULL, NULL),
													(46, 22, 29, 23, 158),
													(46, 22, 30, 23, 158),
													(47, 23, 29, 24, NULL),
													(47, 24, 30, 24, NULL),
													(48, NULL, 34, 4, NULL),
													(49, NULL, 34, 4, 247),
													(111, NULL, 110, 21, 278),
													(111, NULL, 111, 21, 278),
													(115, 12, 114, 32, 282),
													(115, 12, 115, 32, 282),
													(116, 15, 114, 32, 283),
													(116, 15, 115, 32, 283);");					
				$statement64->execute();
			}
			
			$statement35 = mysqli_prepare($conn, "ALTER TABLE `area`
												  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT;");
			$statement35->execute();
			
			$statement36 = mysqli_prepare($conn, "ALTER TABLE `componente`
												  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT;");
			$statement36->execute();
			
			$statement37 = mysqli_prepare($conn, "ALTER TABLE `curso`
												  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;");
			$statement37->execute();
			
			$statement38 = mysqli_prepare($conn, "ALTER TABLE `curso_tipo`
												  MODIFY `id_tipo_curso` int(11) NOT NULL AUTO_INCREMENT;");
			$statement38->execute();
			
			$statement39 = mysqli_prepare($conn, "ALTER TABLE `disciplina`
												  MODIFY `id_disciplina` int(11) NOT NULL AUTO_INCREMENT;");
			$statement39->execute();
			
			$statement40 = mysqli_prepare($conn, "ALTER TABLE `funcao`
												  MODIFY `id_funcao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;");
			$statement40->execute();
			
			$statement41 = mysqli_prepare($conn, "ALTER TABLE `horario`
												  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;");
			$statement41->execute();
			
			$statement42 = mysqli_prepare($conn, "ALTER TABLE `juncao`
												  MODIFY `id_juncao` int(11) NOT NULL AUTO_INCREMENT;");
			$statement42->execute();
			
			$statement43 = mysqli_prepare($conn, "ALTER TABLE `juncao_componente`
												  MODIFY `id_juncao` int(11) NOT NULL AUTO_INCREMENT;");
			$statement43->execute();
			
			$statement44 = mysqli_prepare($conn, "ALTER TABLE `sala`
												  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;");
			$statement44->execute();
			
			$statement45 = mysqli_prepare($conn, "ALTER TABLE `tipo_componente`
												  MODIFY `id_tipocomponente` int(11) NOT NULL AUTO_INCREMENT;");
			$statement45->execute();
			
			$statement46 = mysqli_prepare($conn, "ALTER TABLE `turma`
												  MODIFY `id_turma` int(11) NOT NULL AUTO_INCREMENT;");
			$statement46->execute();
			
			$statement47 = mysqli_prepare($conn, "ALTER TABLE `utc`
											      MODIFY `id_utc` int(11) NOT NULL AUTO_INCREMENT;");
			$statement47->execute();
			
			$statement48 = mysqli_prepare($conn, "ALTER TABLE `utilizador`
												  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;");
			$statement48->execute();
			
			$statement49 = mysqli_prepare($conn, "ALTER TABLE `area`
												  ADD CONSTRAINT `area-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);");
			$statement49->execute();
			
			$statement53 = mysqli_prepare($conn, "ALTER TABLE `aula`
												  ADD CONSTRAINT `aula-id_componente` FOREIGN KEY (`id_componente`) REFERENCES `componente` (`id_componente`),
												  ADD CONSTRAINT `aula-id_docente` FOREIGN KEY (`id_docente`) REFERENCES `utilizador` (`id_utilizador`),
												  ADD CONSTRAINT `aula-id_horario` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`),
												  ADD CONSTRAINT `aula-id_juncao` FOREIGN KEY (`id_juncao`) REFERENCES `juncao` (`id_juncao`),
												  ADD CONSTRAINT `aula-id_turma` FOREIGN KEY (`id_turma`) REFERENCES `turma` (`id_turma`);");
			$statement53->execute();
			
			$statement54 = mysqli_prepare($conn, "ALTER TABLE `componente`
												  ADD CONSTRAINT `componente-id_disciplina` FOREIGN KEY (`id_disciplina`) REFERENCES `disciplina` (`id_disciplina`),
												  ADD CONSTRAINT `componente-id_tipocomponente` FOREIGN KEY (`id_tipocomponente`) REFERENCES `tipo_componente` (`id_tipocomponente`);");
			$statement54->execute();
			
			$statement55 = mysqli_prepare($conn, "ALTER TABLE `curso`
												  ADD CONSTRAINT `curso-id_coordenador` FOREIGN KEY (`id_coordenador`) REFERENCES `utilizador` (`id_utilizador`),
												  ADD CONSTRAINT `curso-id_tipo_curso` FOREIGN KEY (`id_tipo_curso`) REFERENCES `curso_tipo` (`id_tipo_curso`),
												  ADD CONSTRAINT `curso-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);");
			$statement55->execute();
			
			$statement56 = mysqli_prepare($conn, "ALTER TABLE `curso_utc`
												  ADD CONSTRAINT `curso_utc-id_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
												  ADD CONSTRAINT `curso_utc-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);");
			$statement56->execute();
			
			$statement57 = mysqli_prepare($conn, "ALTER TABLE `disciplina`
												  ADD CONSTRAINT `disciplina-id_area` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`),
												  ADD CONSTRAINT `disciplina-id_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
												  ADD CONSTRAINT `disciplina-id_responsavel` FOREIGN KEY (`id_responsavel`) REFERENCES `utilizador` (`id_utilizador`);");
			$statement57->execute();

			$statement59 = mysqli_prepare($conn, "ALTER TABLE `horario`
												  ADD CONSTRAINT `horario-id_sala` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`);");
			$statement59->execute();
			
			$statement60 = mysqli_prepare($conn, "ALTER TABLE `juncao_componente`
												  ADD CONSTRAINT `area-id_componente` FOREIGN KEY (`id_componente`) REFERENCES `componente` (`id_componente`);");
			$statement60->execute();
			
			$statement61 = mysqli_prepare($conn, "ALTER TABLE `turma`
												  ADD CONSTRAINT `turma-id_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`);");
			$statement61->execute();
			
			$statement62 = mysqli_prepare($conn, "ALTER TABLE `utc`
												  ADD CONSTRAINT `utc-id_responsavel` FOREIGN KEY (`id_responsavel`) REFERENCES `utilizador` (`id_utilizador`);");
			$statement62->execute();
			
			$statement63 = mysqli_prepare($conn, "ALTER TABLE `utilizador`
												  ADD CONSTRAINT `utilizador-id_area` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`),
												  ADD CONSTRAINT `utilizador-id_funcao` FOREIGN KEY (`id_funcao`) REFERENCES `funcao` (`id_funcao`),
												  ADD CONSTRAINT `utilizador-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);");
			$statement63->execute();
			
			
		}
		else{
			echo "Criar ano letivo vazio! ", $nome_final_bd;
			
			$statement0 = mysqli_prepare($conn, "CREATE DATABASE IF NOT EXISTS `$nome_final_bd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
			$statement0->execute();
			
			mysqli_select_db($conn,"$nome_final_bd");
			
			$statement1 = mysqli_prepare($conn, "CREATE TABLE `area` (
												  `id_area` int(11) NOT NULL,
												  `nome` varchar(50) NOT NULL,
												  `id_utc` int(11) DEFAULT NULL,
												  `nome_completo` varchar(255) DEFAULT NULL,
												  `imagem` varchar(255) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement1->execute();
			
			$statement2 = mysqli_prepare($conn, "CREATE TABLE `aula` (
												  `id_componente` int(11) NOT NULL,
												  `id_horario` int(11) DEFAULT NULL,
												  `id_turma` int(11) NOT NULL,
												  `id_docente` int(11) DEFAULT NULL,
												  `id_juncao` int(11) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement2->execute();
			
			$statement3 = mysqli_prepare($conn, "CREATE TABLE `componente` (
												  `id_componente` int(11) NOT NULL,
												  `id_disciplina` int(11) DEFAULT NULL,
												  `id_tipocomponente` int(11) DEFAULT NULL,
												  `numero_horas` double DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement3->execute();
			
			$statement4 = mysqli_prepare($conn, "CREATE TABLE `curso` (
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
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement4->execute();
			
			$statement5 = mysqli_prepare($conn, "CREATE TABLE `curso_tipo` (
												  `id_tipo_curso` int(11) NOT NULL,
												  `nome` varchar(255) NOT NULL,
												  `sigla` varchar(50) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement5->execute();
			
			$statement6 = mysqli_prepare($conn, "CREATE TABLE `curso_utc` (
												  `id_curso` int(11) NOT NULL,
												  `id_utc` int(11) NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement6->execute();
			
			$statement7 = mysqli_prepare($conn, "CREATE TABLE `disciplina` (
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
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement7->execute();
			
			$statement8 = mysqli_prepare($conn, "CREATE TABLE `funcao` (
												  `id_funcao` int(11) NOT NULL,
												  `nome` varchar(45) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement8->execute();
			
			$statement10 = mysqli_prepare($conn, "CREATE TABLE `horario` (
												  `id_horario` int(11) NOT NULL,
												  `dia_semana` varchar(50) DEFAULT NULL,
												  `hora_inicio` time DEFAULT NULL,
												  `hora_fim` time DEFAULT NULL,
												  `id_sala` int(11) DEFAULT NULL,
												  `semestre` int(1) NOT NULL DEFAULT 2
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement10->execute();
			
			$statement11 = mysqli_prepare($conn, "CREATE TABLE `juncao` (
												  `id_juncao` int(11) NOT NULL,
												  `nome_juncao` varchar(255) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement11->execute();
			
			$statement12 = mysqli_prepare($conn, "CREATE TABLE `juncao_componente` (
												  `id_juncao` int(11) NOT NULL,
												  `id_componente` int(11) NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement12->execute();
			
			$statement13 = mysqli_prepare($conn, "CREATE TABLE `sala` (
												  `id_sala` int(11) NOT NULL,
												  `nome_sala` varchar(50) DEFAULT NULL,
												  `bloco_sala` varchar(1) NOT NULL DEFAULT 'A'
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement13->execute();
			
			$statement15 = mysqli_prepare($conn, "CREATE TABLE `tipo_componente` (
												  `id_tipocomponente` int(11) NOT NULL,
												  `nome_tipocomponente` varchar(45) DEFAULT NULL,
												  `sigla_tipocomponente` varchar(45) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement15->execute();
			
			$statement16 = mysqli_prepare($conn, "CREATE TABLE `turma` (
												  `id_turma` int(11) NOT NULL,
												  `nome` varchar(50) DEFAULT NULL,
												  `ano` int(11) DEFAULT NULL,
												  `semestre` int(11) DEFAULT NULL,
												  `id_curso` int(11) DEFAULT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement16->execute();
			
			$statement17 = mysqli_prepare($conn, "CREATE TABLE `utc` (
												  `id_utc` int(11) NOT NULL,
												  `nome_utc` varchar(50) NOT NULL,
												  `id_responsavel` int(11) DEFAULT NULL,
												  `sigla_utc` varchar(50) DEFAULT NULL,
												  `dsd_1_sem` int(1) NOT NULL DEFAULT 0,
												  `dsd_2_sem` int(1) NOT NULL DEFAULT 0
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement17->execute();
			
			$statement18 = mysqli_prepare($conn, "CREATE TABLE `utilizador` (
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
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
			$statement18->execute();
			
			$statement19 = mysqli_prepare($conn, "ALTER TABLE `area`
												  ADD PRIMARY KEY (`id_area`),
												  ADD KEY `area-id_utc_idx` (`id_utc`);");
			$statement19->execute();
			
			$statement20 = mysqli_prepare($conn, "ALTER TABLE `aula`
												  ADD PRIMARY KEY (`id_componente`,`id_turma`),
												  ADD KEY `aula-id_componente_idx` (`id_componente`),
												  ADD KEY `aula-id_turma_idx` (`id_turma`),
												  ADD KEY `aula-id_docente_idx` (`id_docente`),
												  ADD KEY `aula-id_horario_idx` (`id_horario`),
												  ADD KEY `aula-id_juncao_idx` (`id_juncao`);");
			$statement20->execute();
			
			$statement21 = mysqli_prepare($conn, "ALTER TABLE `componente`
												  ADD PRIMARY KEY (`id_componente`),
												  ADD KEY `componente-id_tipocomponente_idx` (`id_tipocomponente`),
												  ADD KEY `componente-id_disciplina_idx` (`id_disciplina`);");
			$statement21->execute();
			
			$statement22 = mysqli_prepare($conn, "ALTER TABLE `curso`
												  ADD PRIMARY KEY (`id_curso`),
												  ADD KEY `curso-id_utc_idx` (`id_utc`),
												  ADD KEY `curso-id_tipo_curso_idx` (`id_tipo_curso`),
												  ADD KEY `curso-id_coordenador_idx` (`id_coordenador`);");
			$statement22->execute();
			
			$statement23 = mysqli_prepare($conn, "ALTER TABLE `curso_tipo`
												  ADD PRIMARY KEY (`id_tipo_curso`);");
			$statement23->execute();
			
			$statement24 = mysqli_prepare($conn, "ALTER TABLE `curso_utc`
												  ADD PRIMARY KEY (`id_curso`,`id_utc`),
												  ADD KEY `curso_utc-id_utc_idx` (`id_utc`);");
			$statement24->execute();
			
			$statement25 = mysqli_prepare($conn, "ALTER TABLE `disciplina`
												  ADD PRIMARY KEY (`id_disciplina`),
												  ADD KEY `disciplina-id_area_idx` (`id_area`),
												  ADD KEY `disciplina-id_responsavel_idx` (`id_responsavel`),
												  ADD KEY `disciplina-id_curso_idx` (`id_curso`);");
			$statement25->execute();
			
			$statement26 = mysqli_prepare($conn, "ALTER TABLE `funcao`
												  ADD PRIMARY KEY (`id_funcao`);");
			$statement26->execute();
			
			$statement27 = mysqli_prepare($conn, "ALTER TABLE `horario`
												  ADD PRIMARY KEY (`id_horario`),
												  ADD KEY `horario-id_sala_idx` (`id_sala`);");
			$statement27->execute();
			
			$statement28 = mysqli_prepare($conn, "ALTER TABLE `juncao`
												  ADD PRIMARY KEY (`id_juncao`);");
			$statement28->execute();
			
			$statement29 = mysqli_prepare($conn, "ALTER TABLE `juncao_componente`
												  ADD PRIMARY KEY (`id_juncao`,`id_componente`),
												  ADD KEY `area-id_componente_idx` (`id_componente`);");
			$statement29->execute();
			
			$statement30 = mysqli_prepare($conn, "ALTER TABLE `sala`
												  ADD PRIMARY KEY (`id_sala`);");
			$statement30->execute();
			
			$statement31 = mysqli_prepare($conn, "ALTER TABLE `tipo_componente`
												  ADD PRIMARY KEY (`id_tipocomponente`);");
			$statement31->execute();
			
			$statement32 = mysqli_prepare($conn, "ALTER TABLE `turma`
												  ADD PRIMARY KEY (`id_turma`),
												  ADD KEY `turma-id_curso_idx` (`id_curso`);");
			$statement32->execute();
			
			$statement33 = mysqli_prepare($conn, "ALTER TABLE `utc`
												  ADD PRIMARY KEY (`id_utc`),
												  ADD KEY `utc-id_responsavel_idx` (`id_responsavel`);");
			$statement33->execute();
			
			$statement34 = mysqli_prepare($conn, "ALTER TABLE `utilizador`
												  ADD PRIMARY KEY (`id_utilizador`),
												  ADD KEY `utilizador-id_utc_idx` (`id_utc`),
												  ADD KEY `utilizador-id_area_idx` (`id_area`),
												  ADD KEY `utilizador-id_funcao_idx` (`id_funcao`);");
			$statement34->execute();
			
			$statement64 = mysqli_prepare($conn, "INSERT INTO `area` (`id_area`, `nome`, `id_utc`, `nome_completo`, `imagem`) VALUES
			(11, 'TESTE', 3, 'UTC TESTE', 'https://i.ibb.co/68Qw3Gm/default.png');");
			$statement64->execute();
			
			$statement64 = mysqli_prepare($conn, "INSERT INTO `curso_tipo` (`id_tipo_curso`, `nome`, `sigla`) VALUES
												(1, 'Licenciatura', 'LIC.'),
												(2, 'Mestrado', 'MES.'),
												(3, 'Curso Superior Técnico', 'CTeSP.');");
			$statement64->execute();
			
			$statement9 = mysqli_prepare($conn, "INSERT INTO `funcao` (`id_funcao`, `nome`) VALUES
												(1, 'Administrador'),
												(2, 'Gestor UTC'),
												(3, 'Gestor Área'),
												(4, 'Prof. Adjunto'),
												(5, 'Prof. Coord.'),
												(6, 'Assit. Conv.');");
			$statement9->execute();
			
			$statement14 = mysqli_prepare($conn, "INSERT INTO `sala` (`id_sala`, `nome_sala`, `bloco_sala`) VALUES
												(1, 'ANF B', 'B'),
												(2, 'A.7', 'A'),
												(3, 'D.5', 'D'),
												(4, 'AUD', 'A'),
												(5, 'A.1', 'A'),
												(6, 'A.2', 'A'),
												(7, 'A.5', 'A'),
												(8, 'C.10', 'C'),
												(9, 'A.4', 'A'),
												(10, 'A.3', 'A'),
												(11, 'A.9', 'A'),
												(12, 'D.6', 'D');");
			$statement14->execute();
		
			$statement64 = mysqli_prepare($conn, "INSERT INTO `utc` (`id_utc`, `nome_utc`, `id_responsavel`, `sigla_utc`, `dsd_1_sem`, `dsd_2_sem`) VALUES
												(3, 'UTC Teste', 1, 'UT', 0, 0);");
			$statement64->execute();

			$statement64 = mysqli_prepare($conn, "INSERT INTO `utilizador` (`id_utilizador`, `nome`, `login`, `password`, `imagem_perfil`, `id_utc`, `id_area`, `id_funcao`, `is_admin`, `ano_letivo`, `perm_horarios`) VALUES
(1, 'Administrador', 'admin', 'password', 'https://i.ibb.co/n7bp9g2/perfil-default.jpg', 3, 11, 4, 1, '2021_2022', 0);");
			$statement64->execute();
			
			$statement35 = mysqli_prepare($conn, "ALTER TABLE `area`
												  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT;");
			$statement35->execute();
			
			$statement36 = mysqli_prepare($conn, "ALTER TABLE `componente`
												  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT;");
			$statement36->execute();
			
			$statement37 = mysqli_prepare($conn, "ALTER TABLE `curso`
												  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;");
			$statement37->execute();
			
			$statement38 = mysqli_prepare($conn, "ALTER TABLE `curso_tipo`
												  MODIFY `id_tipo_curso` int(11) NOT NULL AUTO_INCREMENT;");
			$statement38->execute();
			
			$statement39 = mysqli_prepare($conn, "ALTER TABLE `disciplina`
												  MODIFY `id_disciplina` int(11) NOT NULL AUTO_INCREMENT;");
			$statement39->execute();
			
			$statement40 = mysqli_prepare($conn, "ALTER TABLE `funcao`
												  MODIFY `id_funcao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;");
			$statement40->execute();
			
			$statement41 = mysqli_prepare($conn, "ALTER TABLE `horario`
												  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;");
			$statement41->execute();
			
			$statement42 = mysqli_prepare($conn, "ALTER TABLE `juncao`
												  MODIFY `id_juncao` int(11) NOT NULL AUTO_INCREMENT;");
			$statement42->execute();
			
			$statement43 = mysqli_prepare($conn, "ALTER TABLE `juncao_componente`
												  MODIFY `id_juncao` int(11) NOT NULL AUTO_INCREMENT;");
			$statement43->execute();
			
			$statement44 = mysqli_prepare($conn, "ALTER TABLE `sala`
												  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;");
			$statement44->execute();
			
			$statement45 = mysqli_prepare($conn, "ALTER TABLE `tipo_componente`
												  MODIFY `id_tipocomponente` int(11) NOT NULL AUTO_INCREMENT;");
			$statement45->execute();
			
			$statement46 = mysqli_prepare($conn, "ALTER TABLE `turma`
												  MODIFY `id_turma` int(11) NOT NULL AUTO_INCREMENT;");
			$statement46->execute();
			
			$statement47 = mysqli_prepare($conn, "ALTER TABLE `utc`
											      MODIFY `id_utc` int(11) NOT NULL AUTO_INCREMENT;");
			$statement47->execute();
			
			$statement48 = mysqli_prepare($conn, "ALTER TABLE `utilizador`
												  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;");
			$statement48->execute();
			
			$statement49 = mysqli_prepare($conn, "ALTER TABLE `area`
												  ADD CONSTRAINT `area-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);");
			$statement49->execute();
			
			$statement53 = mysqli_prepare($conn, "ALTER TABLE `aula`
												  ADD CONSTRAINT `aula-id_componente` FOREIGN KEY (`id_componente`) REFERENCES `componente` (`id_componente`),
												  ADD CONSTRAINT `aula-id_docente` FOREIGN KEY (`id_docente`) REFERENCES `utilizador` (`id_utilizador`),
												  ADD CONSTRAINT `aula-id_horario` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`),
												  ADD CONSTRAINT `aula-id_juncao` FOREIGN KEY (`id_juncao`) REFERENCES `juncao` (`id_juncao`),
												  ADD CONSTRAINT `aula-id_turma` FOREIGN KEY (`id_turma`) REFERENCES `turma` (`id_turma`);");
			$statement53->execute();
			
			$statement54 = mysqli_prepare($conn, "ALTER TABLE `componente`
												  ADD CONSTRAINT `componente-id_disciplina` FOREIGN KEY (`id_disciplina`) REFERENCES `disciplina` (`id_disciplina`),
												  ADD CONSTRAINT `componente-id_tipocomponente` FOREIGN KEY (`id_tipocomponente`) REFERENCES `tipo_componente` (`id_tipocomponente`);");
			$statement54->execute();
			
			$statement55 = mysqli_prepare($conn, "ALTER TABLE `curso`
												  ADD CONSTRAINT `curso-id_coordenador` FOREIGN KEY (`id_coordenador`) REFERENCES `utilizador` (`id_utilizador`),
												  ADD CONSTRAINT `curso-id_tipo_curso` FOREIGN KEY (`id_tipo_curso`) REFERENCES `curso_tipo` (`id_tipo_curso`),
												  ADD CONSTRAINT `curso-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);");
			$statement55->execute();
			
			$statement56 = mysqli_prepare($conn, "ALTER TABLE `curso_utc`
												  ADD CONSTRAINT `curso_utc-id_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
												  ADD CONSTRAINT `curso_utc-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);");
			$statement56->execute();
			
			$statement57 = mysqli_prepare($conn, "ALTER TABLE `disciplina`
												  ADD CONSTRAINT `disciplina-id_area` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`),
												  ADD CONSTRAINT `disciplina-id_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
												  ADD CONSTRAINT `disciplina-id_responsavel` FOREIGN KEY (`id_responsavel`) REFERENCES `utilizador` (`id_utilizador`);");
			$statement57->execute();

			$statement59 = mysqli_prepare($conn, "ALTER TABLE `horario`
												  ADD CONSTRAINT `horario-id_sala` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`);");
			$statement59->execute();
			
			$statement60 = mysqli_prepare($conn, "ALTER TABLE `juncao_componente`
												  ADD CONSTRAINT `area-id_componente` FOREIGN KEY (`id_componente`) REFERENCES `componente` (`id_componente`);");
			$statement60->execute();
			
			$statement61 = mysqli_prepare($conn, "ALTER TABLE `turma`
												  ADD CONSTRAINT `turma-id_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`);");
			$statement61->execute();
			
			$statement62 = mysqli_prepare($conn, "ALTER TABLE `utc`
												  ADD CONSTRAINT `utc-id_responsavel` FOREIGN KEY (`id_responsavel`) REFERENCES `utilizador` (`id_utilizador`);");
			$statement62->execute();
			
			$statement63 = mysqli_prepare($conn, "ALTER TABLE `utilizador`
												  ADD CONSTRAINT `utilizador-id_area` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`),
												  ADD CONSTRAINT `utilizador-id_funcao` FOREIGN KEY (`id_funcao`) REFERENCES `funcao` (`id_funcao`),
												  ADD CONSTRAINT `utilizador-id_utc` FOREIGN KEY (`id_utc`) REFERENCES `utc` (`id_utc`);");
			$statement63->execute();
			
			
			/*
			$statement64 = mysqli_prepare($conn, "");
			$statement64->execute(); */
			
		}
		
		/*
		$statement = mysqli_prepare($conn, "INSERT INTO utilizador(id_utilizador,nome,login,password,imagem_perfil,id_utc,id_area,id_funcao,is_admin) 
											VALUES (NULL,'$nome','$login','$password','$imagem',$id_utc,$id_area,$id_funcao,$is_admin);");
		$statement->execute(); */
	}
?>