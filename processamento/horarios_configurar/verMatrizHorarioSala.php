<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if(isset($_POST['semestre_atual']) && isset($_POST['numero_horas']) && (isset($_POST['id_sala'])) && (isset($_POST['id_docente'])) && (isset($_POST['id_turma'])) && (isset($_POST['id_juncao']))){	

		$semestre_atual = $_POST["semestre_atual"];
		$numero_horas = $_POST["numero_horas"];
		$id_sala = $_POST["id_sala"];
		$id_docente = $_POST["id_docente"];
		$id_turma = $_POST["id_turma"];
		$id_juncao = $_POST["id_juncao"];
		
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
		
		echo json_encode($array_dados);
		
	}