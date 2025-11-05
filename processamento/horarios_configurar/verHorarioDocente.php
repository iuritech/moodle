<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if(isset($_POST['id_utc']) && (isset($_POST['semestre_atual'])) && (isset($_POST['id_docente']))){	

		$id_utc = $_POST["id_utc"];
		$semestre_atual = $_POST["semestre_atual"];
		$id_docente = $_POST["id_docente"];
		
		$array_dados = array();
		
		$array_dias_semana = array("SEG","TER","QUA","QUI","SEX");
		$counter_dia_semana = 0;
		
		while($counter_dia_semana < sizeof($array_dias_semana)){
			
			$dia_semana = $array_dias_semana[$counter_dia_semana];
		
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
		
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
		
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
		
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
		
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND h.semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(DISTINCT a.id_horario)"];
				
				if($tem_aula == 0){
					array_push($array_dados,0);
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND h.semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
						$hora_inicio = $linha1["hora_inicio"];
						
					$id_juncao = 0;
					$tipo_juncao = 0;
					
					$statement2 = mysqli_prepare($conn, "SELECT a.*, h.id_sala, c.id_curso FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE a.id_docente IS NOT NULL AND a.id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
						$id_turma = $linha2["id_turma"];
						$id_sala = $linha2["id_sala"];
						$id_juncao = $linha2["id_juncao"];
						$id_curso = $linha2["id_curso"];
						
						if($id_juncao == null){
							$id_juncao = 0;
						}
							
						$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$id_disciplina = $linha3["id_disciplina"];
							$numero_horas = $linha3["numero_horas"];
							$id_tipocomponente = $linha3["id_tipocomponente"];
									
						$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
									
						$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$abreviacao_disciplina = $linha4["abreviacao_uc"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_curso = $linha5["sigla"];
								
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_turma = $linha6["nome"];
									
						$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
							$nome_docente = $linha7["nome"];
									
							$nome_docente_temp = explode(" ",$nome_docente);
							if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
								$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
								$nome_docente = $nome_temp;
							}
							
						if($id_juncao != null){
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
								
								if($num_componentes_diferentes > 1){
									$tipo_juncao = 2;
								}
								else{
									$tipo_juncao = 1;
								}	
							
						}
						
						$string_final = $id_horario . "_" . $numero_horas . "_" . $hora_inicio . "_" . $sigla_curso . "_" . $abreviacao_disciplina . "_" . $sigla_tipocomponente . "_" . $nome_docente . "_" . $nome_turma . "_" . $id_juncao . "_" . $tipo_juncao . "_" . $id_componente . "_" . $id_docente . "_" . $id_turma;
						
					array_push($array_dados,$string_final);
				}
			
			$counter_dia_semana += 1;
		}

		echo json_encode($array_dados);
		
	}