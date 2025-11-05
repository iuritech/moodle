<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if(isset($_POST['id_componente']) && (isset($_POST['id_docente'])) && (isset($_POST['id_turma'])) && (isset($_POST['id_juncao']))){	

		$id_componente = $_POST["id_componente"];
		$id_docente = $_POST["id_docente"];
		$id_turma = $_POST["id_turma"];
		$id_juncao = $_POST["id_juncao"];
		
		$array_dados = array();
		
		$statement = mysqli_prepare($conn, "SELECT c.nome, d.nome_uc, d.ano, co.id_tipocomponente FROM curso c INNER JOIN disciplina d ON c.id_curso = d.id_curso INNER JOIN componente co ON d.id_disciplina = co.id_disciplina WHERE co.id_componente = $id_componente;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$nome_curso = $linha["nome"];
			$nome_uc = $linha["nome_uc"];
			$ano_uc = $linha["ano"];
			$id_tipocomponente = $linha["id_tipocomponente"];

		$statement1 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_assoc($resultado1);
			$sigla_tipocomponente = $linha1["sigla_tipocomponente"];
			
		$statement2 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_assoc($resultado2);
			$nome_docente = $linha2["nome"];

		$statement3 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		$linha3 = mysqli_fetch_assoc($resultado3);
			$nome_docente = $linha3["nome"];
			
		$statement4 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
		$statement4->execute();
		$resultado4 = $statement4->get_result();
		$linha4 = mysqli_fetch_assoc($resultado4);
			$nome_turma = $linha4["nome"];
			
			array_push($array_dados,$nome_curso);
			array_push($array_dados,$nome_uc);
			array_push($array_dados,$sigla_tipocomponente);
			array_push($array_dados,$ano_uc);
			array_push($array_dados,$nome_docente);
			array_push($array_dados,$nome_turma);
			
		if($id_juncao == 0){
			array_push($array_dados,"");
			array_push($array_dados,"");
		}
		else{
			
			$tipo_juncao = 0;
			
			$statement5 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
			$statement5->execute();
			$resultado5 = $statement5->get_result();
			$linha5 = mysqli_fetch_assoc($resultado5);
				$num_componentes_diferentes = $linha5["COUNT(DISTINCT id_componente)"];
								
				if($num_componentes_diferentes > 1){
					$tipo_juncao = 2;
				}
				else{
					$tipo_juncao = 1;
				}				
				
			$turmas = "";
				
			$loop = 0;
				
			$statement6 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_juncao = $id_juncao AND id_turma != $id_turma;");
			$statement6->execute();
			$resultado6 = $statement6->get_result();
			while($linha6 = mysqli_fetch_assoc($resultado6)){
				$id_turma_temp = $linha6["id_turma"];
						
				$statement7 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma_temp;");
				$statement7->execute();
				$resultado7 = $statement7->get_result();
				$linha7 = mysqli_fetch_assoc($resultado7);
					$nome_turma_temp = $linha7["nome"];
					
					if($loop == 0){
						$turmas = $turmas . $nome_turma_temp;
					}
					else{
						$turmas = $turmas . "_" . $nome_turma_temp;	
					}
					$loop += 1;
			}				
				
			array_push($array_dados,$tipo_juncao);
			array_push($array_dados,$turmas);
		}

		$id_sala = 0;
		$nome_sala = "";
		$salas = "";

		$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_horario) FROM aula WHERE id_componente = $id_componente AND id_docente = $id_docente AND id_turma = $id_turma;");
		$statement8->execute();
		$resultado8 = $statement8->get_result();
		$linha8 = mysqli_fetch_assoc($resultado8);
			$tem_horario = $linha8["COUNT(DISTINCT id_horario)"];
			
			//Aula atribuída, mostrar a opção de mudar a sala
			if($tem_horario > 0){
				
				$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_horario FROM aula WHERE id_componente = $id_componente AND id_docente = $id_docente AND id_turma = $id_turma;");
				$statement9->execute();
				$resultado9 = $statement9->get_result();
				$linha9 = mysqli_fetch_assoc($resultado9);
					$id_horario = $linha9["id_horario"];
				
				$statement10 = mysqli_prepare($conn, "SELECT s.id_sala, s.nome_sala, h.semestre FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
				$statement10->execute();
				$resultado10 = $statement10->get_result();
				$linha10 = mysqli_fetch_assoc($resultado10);
					$id_sala = $linha10["id_sala"];
					$nome_sala = $linha10["nome_sala"];
					$semestre = $linha10["semestre"];
					
				$statement11 = mysqli_prepare($conn, "SELECT hora_inicio, hora_fim, dia_semana FROM horario WHERE id_horario = $id_horario;");
				$statement11->execute();
				$resultado11 = $statement11->get_result();
				$linha11 = mysqli_fetch_assoc($resultado11);
					$hora_inicio = $linha11["hora_inicio"];
					$hora_fim = $linha11["hora_fim"];
					$dia_semana = $linha11["dia_semana"];
					
				$statement12 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
				$statement12->execute();
				$resultado12 = $statement12->get_result();
				$linha12 = mysqli_fetch_assoc($resultado12);
					$numero_horas = $linha12["numero_horas"];
			
				$salas = $salas . "" . "-" . "" . "_";
			
				$statement10 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'A' ORDER BY nome_sala;");
				$statement10->execute();
				$resultado10 = $statement10->get_result();
				while($linha10 = mysqli_fetch_assoc($resultado10)){
					$id_sala_temp = $linha10["id_sala"];
					$nome_sala_temp = $linha10["nome_sala"];
					
					$statement12 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_horario) FROM horario WHERE id_sala = $id_sala_temp AND dia_semana = '$dia_semana' AND semestre = $semestre;");
					$statement12->execute();
					$resultado12 = $statement12->get_result();
					$linha12 = mysqli_fetch_assoc($resultado12);
						$tabela_destino_tem_horario = $linha12["COUNT(DISTINCT id_horario)"];
				
						if($tabela_destino_tem_horario > 0){
							
							$aula_sobreposta = 0;
							
							$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_horario, hora_inicio, hora_fim FROM horario WHERE id_sala = $id_sala_temp AND dia_semana = '$dia_semana' AND semestre = $semestre;");
							$statement12->execute();
							$resultado12 = $statement12->get_result();
							while($linha12 = mysqli_fetch_assoc($resultado12)){
								$id_horario_temp = $linha12["id_horario"];
								$hora_inicio_temp = $linha12["hora_inicio"];
								$hora_fim_temp = $linha12["hora_fim"];
								
								if((strcmp($hora_inicio,$hora_inicio_temp) == -1) && (strcmp($hora_fim,$hora_inicio_temp) == 1)){
									$aula_sobreposta = 1;
								}
								
								if((strcmp($hora_inicio,$hora_fim_temp) == -1) && (strcmp($hora_fim,$hora_fim_temp) == 1)){
									$aula_sobreposta = 1;
								}
								
								if((strcmp($hora_inicio,$hora_inicio_temp) == 0) && (strcmp($hora_fim,$hora_fim_temp) == 0)){
									$aula_sobreposta = 1;
								}
								
							}
							
							if($aula_sobreposta == 1){
								$salas = $salas . 0 . "-" . $nome_sala_temp . "_";
							}
							else{
								$salas = $salas . $id_sala_temp . "-" . $nome_sala_temp . "_";
							}
							
						}
						else{
							$salas = $salas . $id_sala_temp . "-" . $nome_sala_temp . "_";
					
						}
				}
				
				$salas = $salas . "" . "-" . "" . "_";
				
				$statement11 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'B' ORDER BY nome_sala;");
				$statement11->execute();
				$resultado11 = $statement11->get_result();
				while($linha11 = mysqli_fetch_assoc($resultado11)){
					$id_sala_temp = $linha11["id_sala"];
					$nome_sala_temp = $linha11["nome_sala"];
					
					$statement12 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_horario) FROM horario WHERE id_sala = $id_sala_temp AND dia_semana = '$dia_semana' AND semestre = $semestre;");
					$statement12->execute();
					$resultado12 = $statement12->get_result();
					$linha12 = mysqli_fetch_assoc($resultado12);
						$tabela_destino_tem_horario = $linha12["COUNT(DISTINCT id_horario)"];
				
						if($tabela_destino_tem_horario > 0){
							
							$aula_sobreposta = 0;
							
							$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_horario, hora_inicio, hora_fim FROM horario WHERE id_sala = $id_sala_temp AND dia_semana = '$dia_semana' AND semestre = $semestre;");
							$statement12->execute();
							$resultado12 = $statement12->get_result();
							while($linha12 = mysqli_fetch_assoc($resultado12)){
								$id_horario_temp = $linha12["id_horario"];
								$hora_inicio_temp = $linha12["hora_inicio"];
								$hora_fim_temp = $linha12["hora_fim"];
								
								if((strcmp($hora_inicio,$hora_inicio_temp) == -1) && (strcmp($hora_fim,$hora_inicio_temp) == 1)){
									$aula_sobreposta = 1;
								}
								
								if((strcmp($hora_inicio,$hora_fim_temp) == -1) && (strcmp($hora_fim,$hora_fim_temp) == 1)){
									$aula_sobreposta = 1;
								}
								
								if((strcmp($hora_inicio,$hora_inicio_temp) == 0) && (strcmp($hora_fim,$hora_fim_temp) == 0)){
									$aula_sobreposta = 1;
								}
								
							}
							
							if($aula_sobreposta == 1){
								$salas = $salas . 0 . "-" . $nome_sala_temp . "_";
							}
							else{
								$salas = $salas . $id_sala_temp . "-" . $nome_sala_temp . "_";
							}
							
						}
						else{
							$salas = $salas . $id_sala_temp . "-" . $nome_sala_temp . "_";
					
						}
				}
				
				$salas = $salas . "" . "-" . "" . "_";
				
				$statement12 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'C' ORDER BY nome_sala;");
				$statement12->execute();
				$resultado12 = $statement12->get_result();
				while($linha12 = mysqli_fetch_assoc($resultado12)){
					$id_sala_temp = $linha12["id_sala"];
					$nome_sala_temp = $linha12["nome_sala"];
					
					$statement12 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_horario) FROM horario WHERE id_sala = $id_sala_temp AND dia_semana = '$dia_semana' AND semestre = $semestre;");
					$statement12->execute();
					$resultado12 = $statement12->get_result();
					$linha12 = mysqli_fetch_assoc($resultado12);
						$tabela_destino_tem_horario = $linha12["COUNT(DISTINCT id_horario)"];
				
						if($tabela_destino_tem_horario > 0){
							
							$aula_sobreposta = 0;
							
							$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_horario, hora_inicio, hora_fim FROM horario WHERE id_sala = $id_sala_temp AND dia_semana = '$dia_semana' AND semestre = $semestre;");
							$statement12->execute();
							$resultado12 = $statement12->get_result();
							while($linha12 = mysqli_fetch_assoc($resultado12)){
								$id_horario_temp = $linha12["id_horario"];
								$hora_inicio_temp = $linha12["hora_inicio"];
								$hora_fim_temp = $linha12["hora_fim"];
								//echo $hora_inicio, " - ", $hora_fim_temp, " / ", $hora_fim, " - ", $hora_fim_temp, "<br>";
								if((strcmp($hora_inicio,$hora_inicio_temp) == -1) && (strcmp($hora_fim,$hora_inicio_temp) == 1)){
									$aula_sobreposta = 1;
								}
								
								if((strcmp($hora_inicio,$hora_fim_temp) == -1) && (strcmp($hora_fim,$hora_fim_temp) == 1)){
									$aula_sobreposta = 1;
								}
								
								if((strcmp($hora_inicio,$hora_inicio_temp) == 0) && (strcmp($hora_fim,$hora_fim_temp) == 0)){
									$aula_sobreposta = 1;
								}
							}
							
							if($aula_sobreposta == 1){
								$salas = $salas . 0 . "-" . $nome_sala_temp . "_";
							}
							else{
								$salas = $salas . $id_sala_temp . "-" . $nome_sala_temp . "_";
							}
							
						}
						else{
							$salas = $salas . $id_sala_temp . "-" . $nome_sala_temp . "_";
					
						}
				}
					
				$salas = $salas . "" . "-" . "" . "_";
					
				$statement13 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'D' ORDER BY nome_sala;");
				$statement13->execute();
				$resultado13 = $statement13->get_result();
				while($linha13 = mysqli_fetch_assoc($resultado13)){
					$id_sala_temp = $linha13["id_sala"];
					$nome_sala_temp = $linha13["nome_sala"];
					
					$statement12 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_horario) FROM horario WHERE id_sala = $id_sala_temp AND dia_semana = '$dia_semana' AND semestre = $semestre;");
					$statement12->execute();
					$resultado12 = $statement12->get_result();
					$linha12 = mysqli_fetch_assoc($resultado12);
						$tabela_destino_tem_horario = $linha12["COUNT(DISTINCT id_horario)"];
				
						if($tabela_destino_tem_horario > 0){
							
							$aula_sobreposta = 0;
							
							$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_horario, hora_inicio, hora_fim FROM horario WHERE id_sala = $id_sala_temp AND dia_semana = '$dia_semana' AND semestre = $semestre;");
							$statement12->execute();
							$resultado12 = $statement12->get_result();
							while($linha12 = mysqli_fetch_assoc($resultado12)){
								$id_horario_temp = $linha12["id_horario"];
								$hora_inicio_temp = $linha12["hora_inicio"];
								$hora_fim_temp = $linha12["hora_fim"];
								
								if((strcmp($hora_inicio,$hora_inicio_temp) == -1) && (strcmp($hora_fim,$hora_inicio_temp) == 1)){
									$aula_sobreposta = 1;
								}
								
								if((strcmp($hora_inicio,$hora_fim_temp) == -1) && (strcmp($hora_fim,$hora_fim_temp) == 1)){
									$aula_sobreposta = 1;
								}
								
								if((strcmp($hora_inicio,$hora_inicio_temp) == 0) && (strcmp($hora_fim,$hora_fim_temp) == 0)){
									$aula_sobreposta = 1;
								}
							}
							
							if($aula_sobreposta == 1){
								$salas = $salas . 0 . "-" . $nome_sala_temp . "_";
							}
							else{
								$salas = $salas . $id_sala_temp . "-" . $nome_sala_temp . "_";
							}
							
						}
						else{
							$salas = $salas . $id_sala_temp . "-" . $nome_sala_temp . "_";
					
						}
				}
					
				$salas = substr($salas, 0, strlen($salas) - 1);
						
			}
			
		array_push($array_dados,$id_sala);
		array_push($array_dados,$nome_sala);
		array_push($array_dados,$salas);

		echo json_encode($array_dados);
		
	}