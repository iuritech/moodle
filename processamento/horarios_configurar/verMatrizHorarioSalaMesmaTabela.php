<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if(isset($_POST['semestre_atual']) && isset($_POST['numero_horas']) && (isset($_POST['id_sala'])) && (isset($_POST['id_docente'])) && (isset($_POST['id_turma'])) && (isset($_POST['id_juncao'])) && (isset($_POST['id_componente']))){	

		$semestre_atual = $_POST["semestre_atual"];
		$numero_horas = $_POST["numero_horas"];
		$id_sala = $_POST["id_sala"];
		$id_docente = $_POST["id_docente"];
		$id_turma = $_POST["id_turma"];
		$id_juncao = $_POST["id_juncao"];
		$id_componente = $_POST["id_componente"];
		
		$statement = mysqli_prepare($conn, "SELECT id_horario FROM aula WHERE id_componente = $id_componente AND id_docente = $id_docente AND id_turma = $id_turma;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$id_horario_a_arrastar = $linha["id_horario"];
			
		$statement = mysqli_prepare($conn, "SELECT dia_semana, hora_inicio FROM horario WHERE id_horario = $id_horario_a_arrastar;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$dia_semana_a_arrastar = $linha["dia_semana"];
			$hora_inicio_a_arrastar = $linha["hora_inicio"];
		
		$array_dados = array();
		$offset_aula = 0;
		
		//SALA
		
		$array_dias_semana = array("SEG","TER","QUA","QUI","SEX");
		$counter_dia_semana = 0;
		
		while($counter_dia_semana < sizeof($array_dias_semana)){
			
			$dia_semana = $array_dias_semana[$counter_dia_semana];
	
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '08:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '09:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '09:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '08:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '09:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '10:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '10:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '09:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '10:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '11:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
						
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '11:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '10:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}	
			
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '11:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '12:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '12:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '11:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
			
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '12:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '13:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '13:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '12:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
			
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '13:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '14:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '14:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '13:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
			
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '14:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '15:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '15:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '14:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
			
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '15:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '16:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '16:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
							
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '15:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '16:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '17:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '17:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '16:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '17:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '18:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '18:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
					
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '17:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '18:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(id_horario) FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '19:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								array_push($array_dados,1);
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								array_push($array_dados,0.5);
								$offset_aula -= 0.5;
							}
							else{
								array_push($array_dados,0);
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '19:00:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							array_push($array_dados,5.0);
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT id_horario FROM horario WHERE dia_semana = '$dia_semana' AND hora_inicio = '18:30:00' AND id_sala = $id_sala AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						array_push($array_dados,1);
				}
			
			$counter_dia_semana += 1;
		}
		
		
		
		//DOCENTE
		
		$counter_dia_semana = 0;
		$counter_array = 0;
		
		while($counter_dia_semana < sizeof($array_dias_semana)){
			
			$dia_semana = $array_dias_semana[$counter_dia_semana];
	
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$counter_dia_semana += 1;
		}
			
		
		
		//TURMA
		
		$counter_dia_semana = 0;
		$counter_array = 0;
		
		while($counter_dia_semana < sizeof($array_dias_semana)){
			
			$dia_semana = $array_dias_semana[$counter_dia_semana];
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_turma = $id_turma AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
			
			$counter_dia_semana += 1;
		}
		
		
		//Junção
		
		if($id_juncao != 0){
		
			$turmas_juncao = array();
			
			$statement = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_juncao = $id_juncao;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_assoc($resultado)){
				$id_turma_juncao = $linha["id_turma"];
			
				array_push($turmas_juncao,$id_turma_juncao);
			}
			
			$counter_turmas_juncao = 0;
			
			while($counter_turmas_juncao < sizeof($turmas_juncao)){
			
				$id_turma_juncao = $turmas_juncao[$counter_turmas_juncao];
				
				$counter_dia_semana = 0;
				$counter_array = 0;
				
				while($counter_dia_semana < sizeof($array_dias_semana)){
					
					$dia_semana = $array_dias_semana[$counter_dia_semana];
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
						
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula = $linha["COUNT(h.id_horario)"];
						
						if($tem_aula == 0){
							
							$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement->execute();
							$resultado = $statement->get_result();
							$linha = mysqli_fetch_assoc($resultado);
								$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
							
								if($tem_aula_pontual == 0){
									if($offset_aula > 0.5){
										if($array_dados[$counter_array] != 1){
											$array_dados[$counter_array] = 1;
										}
										$offset_aula -= 1;
									}
									else if($offset_aula == 0.5){
										if($array_dados[$counter_array] == 5.0){
											$array_dados[$counter_array] = 1;
										}
										else if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 0.5;
										}
										$offset_aula -= 0.5;
									}
								}
								else{
									
									$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
									$statement1->execute();
									$resultado1 = $statement1->get_result();
									$linha1 = mysqli_fetch_assoc($resultado1);
										$id_horario_pontual = $linha1["id_horario"];
									
									$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									$linha2 = mysqli_fetch_assoc($resultado2);
										$id_componente_pontual = $linha2["id_componente"];
								
									$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$numero_horas_pontual = $linha3["numero_horas"];
										
									$offset_aula = $numero_horas_pontual - 0.5;
								
									if($array_dados[$counter_array] == 0){
											$array_dados[$counter_array] = 5.0;
									}
									else if($array_dados[$counter_array] == 0.5){
											$array_dados[$counter_array] = 1;
									}
											
								}
							
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_turma = $id_turma_juncao AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas = $linha3["numero_horas"];
							
								$offset_aula = $numero_horas - 1;
								
								if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
						}
						
						$counter_array += 1;
					
					$counter_dia_semana += 1;
				}
				
			
				$counter_turmas_juncao += 1;
				
			}
		
		}
		
		$statement = mysqli_prepare($conn, "SELECT dia_semana, hora_inicio FROM horario WHERE id_horario = $id_horario_a_arrastar;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$dia_semana_a_arrastar = $linha["dia_semana"];
			$hora_inicio_a_arrastar = $linha["hora_inicio"];
			
		if($numero_horas == 2){	
			
			if($dia_semana_a_arrastar == 'SEG'){
			
				if($hora_inicio_a_arrastar == '08:30:00'){
					$array_dados[0] = 3;
					$array_dados[1] = 3;
				}
				else if($hora_inicio_a_arrastar == '09:00:00'){
					$array_dados[0] = 3.3;
					$array_dados[1] = 3;
					$array_dados[2] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '09:30:00'){
					$array_dados[1] = 3;
					$array_dados[2] = 3;
				}
				else if($hora_inicio_a_arrastar == '10:00:00'){
					$array_dados[1] = 3.3;
					$array_dados[2] = 3;
					$array_dados[3] = 0.3;
				}
				if($hora_inicio_a_arrastar == '10:30:00'){
					$array_dados[2] = 3;
					$array_dados[3] = 3;
				}
				else if($hora_inicio_a_arrastar == '11:00:00'){
					$array_dados[2] = 3.3;
					$array_dados[3] = 3;
					$array_dados[4] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '11:30:00'){
					$array_dados[3] = 3;
					$array_dados[4] = 3;
				}
				else if($hora_inicio_a_arrastar == '12:00:00'){
					$array_dados[3] = 3.3;
					$array_dados[4] = 3;
					$array_dados[5] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '12:30:00'){
					$array_dados[4] = 3;
					$array_dados[5] = 3;
				}
				else if($hora_inicio_a_arrastar == '13:00:00'){
					$array_dados[4] = 3.3;
					$array_dados[5] = 3;
					$array_dados[6] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '13:30:00'){
					$array_dados[5] = 3;
					$array_dados[6] = 3;
				}
				else if($hora_inicio_a_arrastar == '14:00:00'){
					$array_dados[5] = 3.3;
					$array_dados[6] = 3;
					$array_dados[7] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '14:30:00'){
					$array_dados[6] = 3;
					$array_dados[7] = 3;
				}
				else if($hora_inicio_a_arrastar == '15:00:00'){
					$array_dados[6] = 3.3;
					$array_dados[7] = 3;
					$array_dados[8] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '15:30:00'){
					$array_dados[7] = 3;
					$array_dados[8] = 3;
				}
				else if($hora_inicio_a_arrastar == '16:00:00'){
					$array_dados[7] = 3.3;
					$array_dados[8] = 3;
					$array_dados[9] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '16:30:00'){
					$array_dados[8] = 3;
					$array_dados[9] = 3;
				}
				else if($hora_inicio_a_arrastar == '17:00:00'){
					$array_dados[8] = 3.3;
					$array_dados[9] = 3;
					$array_dados[10] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '17:30:00'){
					$array_dados[9] = 3;
					$array_dados[10] = 3;
				}
				else if($hora_inicio_a_arrastar == '18:00:00'){
					$array_dados[9] = 3.3;
					$array_dados[10] = 3;
					$array_dados[11] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '18:30:00'){
					$array_dados[10] = 3;
					$array_dados[11] = 3;
				}
			
			}	
			else if($dia_semana_a_arrastar == 'TER'){
				
				if($hora_inicio_a_arrastar == '08:30:00'){
					$array_dados[11] = 3;
					$array_dados[12] = 3;
				}
				else if($hora_inicio_a_arrastar == '09:00:00'){
					$array_dados[11] = 3.3;
					$array_dados[12] = 3;
					$array_dados[13] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '09:30:00'){
					$array_dados[12] = 3;
					$array_dados[13] = 3;
				}
				else if($hora_inicio_a_arrastar == '10:00:00'){
					$array_dados[12] = 3.3;
					$array_dados[13] = 3;
					$array_dados[14] = 0.3;
				}
				if($hora_inicio_a_arrastar == '10:30:00'){
					$array_dados[13] = 3;
					$array_dados[14] = 3;
				}
				else if($hora_inicio_a_arrastar == '11:00:00'){
					$array_dados[13] = 3.3;
					$array_dados[14] = 3;
					$array_dados[15] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '11:30:00'){
					$array_dados[14] = 3;
					$array_dados[15] = 3;
				}
				else if($hora_inicio_a_arrastar == '12:00:00'){
					$array_dados[14] = 3.3;
					$array_dados[15] = 3;
					$array_dados[16] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '12:30:00'){
					$array_dados[15] = 3;
					$array_dados[16] = 3;
				}
				else if($hora_inicio_a_arrastar == '13:00:00'){
					$array_dados[15] = 3.3;
					$array_dados[16] = 3;
					$array_dados[17] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '13:30:00'){
					$array_dados[16] = 3;
					$array_dados[17] = 3;
				}
				else if($hora_inicio_a_arrastar == '14:00:00'){
					$array_dados[16] = 3.3;
					$array_dados[17] = 3;
					$array_dados[18] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '14:30:00'){
					$array_dados[17] = 3;
					$array_dados[18] = 3;
				}
				else if($hora_inicio_a_arrastar == '15:00:00'){
					$array_dados[17] = 3.3;
					$array_dados[18] = 3;
					$array_dados[19] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '15:30:00'){
					$array_dados[18] = 3;
					$array_dados[19] = 3;
				}
				else if($hora_inicio_a_arrastar == '16:00:00'){
					$array_dados[18] = 3.3;
					$array_dados[19] = 3;
					$array_dados[20] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '16:30:00'){
					$array_dados[19] = 3;
					$array_dados[20] = 3;
				}
				else if($hora_inicio_a_arrastar == '17:00:00'){
					$array_dados[19] = 3.3;
					$array_dados[20] = 3;
					$array_dados[21] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '17:30:00'){
					$array_dados[20] = 3;
					$array_dados[21] = 3;
				}
				else if($hora_inicio_a_arrastar == '18:00:00'){
					$array_dados[20] = 3.3;
					$array_dados[21] = 3;
					$array_dados[22] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '18:30:00'){
					$array_dados[21] = 3;
					$array_dados[22] = 3;
				}
				
			}
			else if($dia_semana_a_arrastar == 'QUA'){
				
				if($hora_inicio_a_arrastar == '08:30:00'){
					$array_dados[22] = 3;
					$array_dados[23] = 3;
				}
				else if($hora_inicio_a_arrastar == '09:00:00'){
					$array_dados[22] = 3.3;
					$array_dados[23] = 3;
					$array_dados[24] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '09:30:00'){
					$array_dados[23] = 3;
					$array_dados[24] = 3;
				}
				else if($hora_inicio_a_arrastar == '10:00:00'){
					$array_dados[23] = 3.3;
					$array_dados[24] = 3;
					$array_dados[25] = 0.3;
				}
				if($hora_inicio_a_arrastar == '10:30:00'){
					$array_dados[24] = 3;
					$array_dados[25] = 3;
				}
				else if($hora_inicio_a_arrastar == '11:00:00'){
					$array_dados[24] = 3.3;
					$array_dados[25] = 3;
					$array_dados[26] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '11:30:00'){
					$array_dados[25] = 3;
					$array_dados[26] = 3;
				}
				else if($hora_inicio_a_arrastar == '12:00:00'){
					$array_dados[25] = 3.3;
					$array_dados[26] = 3;
					$array_dados[27] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '12:30:00'){
					$array_dados[26] = 3;
					$array_dados[27] = 3;
				}
				else if($hora_inicio_a_arrastar == '13:00:00'){
					$array_dados[26] = 3.3;
					$array_dados[27] = 3;
					$array_dados[28] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '13:30:00'){
					$array_dados[27] = 3;
					$array_dados[28] = 3;
				}
				else if($hora_inicio_a_arrastar == '14:00:00'){
					$array_dados[27] = 3.3;
					$array_dados[28] = 3;
					$array_dados[29] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '14:30:00'){
					$array_dados[28] = 3;
					$array_dados[29] = 3;
				}
				else if($hora_inicio_a_arrastar == '15:00:00'){
					$array_dados[28] = 3.3;
					$array_dados[29] = 3;
					$array_dados[30] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '15:30:00'){
					$array_dados[29] = 3;
					$array_dados[30] = 3;
				}
				else if($hora_inicio_a_arrastar == '16:00:00'){
					$array_dados[29] = 3.3;
					$array_dados[30] = 3;
					$array_dados[32] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '16:30:00'){
					$array_dados[30] = 3;
					$array_dados[31] = 3;
				}
				else if($hora_inicio_a_arrastar == '17:00:00'){
					$array_dados[30] = 3.3;
					$array_dados[31] = 3;
					$array_dados[32] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '17:30:00'){
					$array_dados[31] = 3;
					$array_dados[32] = 3;
				}
				else if($hora_inicio_a_arrastar == '18:00:00'){
					$array_dados[31] = 3.3;
					$array_dados[32] = 3;
					$array_dados[33] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '18:30:00'){
					$array_dados[32] = 3;
					$array_dados[33] = 3;
				}
				
			}
			else if($dia_semana_a_arrastar == 'QUI'){
				
				if($hora_inicio_a_arrastar == '08:30:00'){
					$array_dados[33] = 3;
					$array_dados[34] = 3;
				}
				else if($hora_inicio_a_arrastar == '09:00:00'){
					$array_dados[33] = 3.3;
					$array_dados[34] = 3;
					$array_dados[35] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '09:30:00'){
					$array_dados[34] = 3;
					$array_dados[35] = 3;
				}
				else if($hora_inicio_a_arrastar == '10:00:00'){
					$array_dados[34] = 3.3;
					$array_dados[35] = 3;
					$array_dados[36] = 0.3;
				}
				if($hora_inicio_a_arrastar == '10:30:00'){
					$array_dados[35] = 3;
					$array_dados[36] = 3;
				}
				else if($hora_inicio_a_arrastar == '11:00:00'){
					$array_dados[35] = 3.3;
					$array_dados[36] = 3;
					$array_dados[37] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '11:30:00'){
					$array_dados[36] = 3;
					$array_dados[37] = 3;
				}
				else if($hora_inicio_a_arrastar == '12:00:00'){
					$array_dados[36] = 3.3;
					$array_dados[37] = 3;
					$array_dados[38] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '12:30:00'){
					$array_dados[37] = 3;
					$array_dados[38] = 3;
				}
				else if($hora_inicio_a_arrastar == '13:00:00'){
					$array_dados[37] = 3.3;
					$array_dados[38] = 3;
					$array_dados[39] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '13:30:00'){
					$array_dados[38] = 3;
					$array_dados[39] = 3;
				}
				else if($hora_inicio_a_arrastar == '14:00:00'){
					$array_dados[38] = 3.3;
					$array_dados[39] = 3;
					$array_dados[40] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '14:30:00'){
					$array_dados[39] = 3;
					$array_dados[40] = 3;
				}
				else if($hora_inicio_a_arrastar == '15:00:00'){
					$array_dados[39] = 3.3;
					$array_dados[40] = 3;
					$array_dados[41] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '15:30:00'){
					$array_dados[40] = 3;
					$array_dados[41] = 3;
				}
				else if($hora_inicio_a_arrastar == '16:00:00'){
					$array_dados[40] = 3.3;
					$array_dados[41] = 3;
					$array_dados[42] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '16:30:00'){
					$array_dados[41] = 3;
					$array_dados[42] = 3;
				}
				else if($hora_inicio_a_arrastar == '17:00:00'){
					$array_dados[41] = 3.3;
					$array_dados[42] = 3;
					$array_dados[43] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '17:30:00'){
					$array_dados[42] = 3;
					$array_dados[43] = 3;
				}
				else if($hora_inicio_a_arrastar == '18:00:00'){
					$array_dados[42] = 3.3;
					$array_dados[43] = 3;
					$array_dados[44] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '18:30:00'){
					$array_dados[43] = 3;
					$array_dados[44] = 3;
				}
				
			}
			else if($dia_semana_a_arrastar == 'SEX'){
				
				if($hora_inicio_a_arrastar == '08:30:00'){
					$array_dados[44] = 3;
					$array_dados[45] = 3;
				}
				else if($hora_inicio_a_arrastar == '09:00:00'){
					$array_dados[44] = 3.3;
					$array_dados[45] = 3;
					$array_dados[46] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '09:30:00'){
					$array_dados[45] = 3;
					$array_dados[46] = 3;
				}
				else if($hora_inicio_a_arrastar == '10:00:00'){
					$array_dados[45] = 3.3;
					$array_dados[46] = 3;
					$array_dados[47] = 0.3;
				}
				if($hora_inicio_a_arrastar == '10:30:00'){
					$array_dados[46] = 3;
					$array_dados[47] = 3;
				}
				else if($hora_inicio_a_arrastar == '11:00:00'){
					$array_dados[46] = 3.3;
					$array_dados[47] = 3;
					$array_dados[48] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '11:30:00'){
					$array_dados[47] = 3;
					$array_dados[48] = 3;
				}
				else if($hora_inicio_a_arrastar == '12:00:00'){
					$array_dados[47] = 3.3;
					$array_dados[48] = 3;
					$array_dados[49] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '12:30:00'){
					$array_dados[48] = 3;
					$array_dados[49] = 3;
				}
				else if($hora_inicio_a_arrastar == '13:00:00'){
					$array_dados[48] = 3.3;
					$array_dados[49] = 3;
					$array_dados[50] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '13:30:00'){
					$array_dados[49] = 3;
					$array_dados[50] = 3;
				}
				else if($hora_inicio_a_arrastar == '14:00:00'){
					$array_dados[49] = 3.3;
					$array_dados[50] = 3;
					$array_dados[51] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '14:30:00'){
					$array_dados[50] = 3;
					$array_dados[51] = 3;
				}
				else if($hora_inicio_a_arrastar == '15:00:00'){
					$array_dados[50] = 3.3;
					$array_dados[51] = 3;
					$array_dados[52] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '15:30:00'){
					$array_dados[51] = 3;
					$array_dados[52] = 3;
				}
				else if($hora_inicio_a_arrastar == '16:00:00'){
					$array_dados[51] = 3.3;
					$array_dados[52] = 3;
					$array_dados[53] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '16:30:00'){
					$array_dados[52] = 3;
					$array_dados[53] = 3;
				}
				else if($hora_inicio_a_arrastar == '17:00:00'){
					$array_dados[52] = 3.3;
					$array_dados[53] = 3;
					$array_dados[54] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '17:30:00'){
					$array_dados[53] = 3;
					$array_dados[54] = 3;
				}
				else if($hora_inicio_a_arrastar == '18:00:00'){
					$array_dados[53] = 3.3;
					$array_dados[54] = 3;
					$array_dados[55] = 0.3;
				}
				else if($hora_inicio_a_arrastar == '18:30:00'){
					$array_dados[54] = 3;
					$array_dados[55] = 3;
				}
				
			}
			
		}
		
		echo json_encode($array_dados);
		
	}