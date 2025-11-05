<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

if(isset($_POST['id_utc']) && isset($_POST['id_area'])){

	$id_utc = $_POST["id_utc"];
	$id_area = $_POST["id_area"];
	
	$array_dados = array();

	if($id_utc == 0 && $id_area == 0){
		$statement = mysqli_prepare($conn, "SELECT * FROM utilizador ORDER BY nome;");
	}
	else if($id_utc == 0 && $id_area != 0){
		$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_area = $id_area ORDER BY nome;");
	}
	else if($id_utc != 0 && $id_area == 0){
		$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utc = $id_utc ORDER BY nome;");
	}
	else if($id_utc != 0 && $id_area != 0){
		$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utc = $id_utc AND id_area = $id_area ORDER BY nome;");
	}
	
	$statement->execute();
	$resultado = $statement->get_result();
	while($linha = mysqli_fetch_assoc($resultado)){
		$idUtilizador = $linha["id_utilizador"];
		$nomeUtilizador = $linha["nome"];
		$imgUtilizador = $linha["imagem_perfil"];
		$idUtcUtilizador = $linha["id_utc"];
		$idAreaUtilizador = $linha["id_area"];
				
		if(strlen($nomeUtilizador) > 20){
			$nomeUtilizador = substr_replace($nomeUtilizador,"...",(15-strlen($nomeUtilizador)));
		}
				
		$statement0 = mysqli_prepare($conn, "SELECT sigla_utc, id_responsavel FROM utc WHERE id_utc = $idUtcUtilizador");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_assoc($resultado0);
		$siglaUtcUtilizador = $linha0["sigla_utc"];
		$idResponsavelUtc = $linha0["id_responsavel"];
				
		$statement1 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $idAreaUtilizador");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_assoc($resultado1);
		$nomeAreaUtilizador = $linha1["nome"];
				
		$statement2 = mysqli_prepare($conn, "SELECT f.nome, COUNT(f.nome) FROM 
											funcao f INNER JOIN utilizador u ON f.id_funcao = u.id_funcao
											WHERE u.id_utilizador = $idUtilizador");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_assoc($resultado2);
			$nome_funcao_utilizador = $linha2["nome"];
				
		$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT d.id_disciplina) FROM 
											disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina 
											INNER JOIN aula a ON c.id_componente = a.id_componente 
											WHERE a.id_componente IN
											(SELECT DISTINCT id_componente FROM aula WHERE id_docente = $idUtilizador);");
		$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
			$numeroDisciplinasUtilizador = $linha3["COUNT(DISTINCT d.id_disciplina)"];
				
				
		/*---------------------------------------------1º SEMESTRE-----------------------------------------------------*/

		$array_componentes_1_sem = array();
		$array_disciplinas_1_sem = array();

		$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $idUtilizador;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		while($linha3 = mysqli_fetch_assoc($resultado3)){
			$id_componente = $linha3["id_componente"];
					
			$statement4 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$id_disciplina = $linha4["id_disciplina"];
						
				$statement5 = mysqli_prepare($conn, "SELECT semestre FROM disciplina WHERE id_disciplina = $id_disciplina;");
				$statement5->execute();
				$resultado5 = $statement5->get_result();
				$linha5 = mysqli_fetch_assoc($resultado5);
					$sem_disciplina = $linha5["semestre"];
						
					if($sem_disciplina == 1){
						array_push($array_componentes_1_sem,$id_componente);
						array_push($array_disciplinas_1_sem,$id_disciplina);
					}
					
		}

		$array_componentes_1_sem_temp = array_unique($array_componentes_1_sem);
		$array_componentes_1_sem_final = implode("','",$array_componentes_1_sem_temp);

		$array_disciplinas_1_sem_temp = array_unique($array_disciplinas_1_sem);
		$array_disciplinas_1_sem_final = implode("','",$array_disciplinas_1_sem_temp);

		$statement6 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE 
											id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idUtilizador;");
		$statement6->execute();
		$resultado6 = $statement6->get_result();
		$linha6 = mysqli_fetch_assoc($resultado6);
			$num_turmas_docente_1_sem = $linha6["COUNT(DISTINCT id_turma)"];

			$horas_1_sem = 0;

			$statement7 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE 
											id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idUtilizador;");
			$statement7->execute();
			$resultado7 = $statement7->get_result();
			$linha7 = mysqli_fetch_assoc($resultado7);
				$num_juncoes_1_sem = $linha7["COUNT(DISTINCT id_juncao)"];
					
				if($num_juncoes_1_sem == 0){
							
					$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
											id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idUtilizador;");
					$statement8->execute();
					$resultado8 = $statement8->get_result();
					while($linha8 = mysqli_fetch_assoc($resultado8)){
						$id_comp = $linha8["id_componente"];
								
						$statement9 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
						$statement9->execute();
						$resultado9 = $statement9->get_result();
						$linha9 = mysqli_fetch_assoc($resultado9);
							$numero_horas_comp = $linha9["numero_horas"];
							
							$statement009 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
							$statement009->execute();
							$resultado009 = $statement009->get_result();
							$linha009 = mysqli_fetch_assoc($resultado009);
								$numero_turmas = $linha009["COUNT(DISTINCT id_turma)"];
									
								$horas_1_sem = $horas_1_sem + ($numero_horas_comp * $numero_turmas);
					}
							
				}

						else{
							
					$juncoes_ja_contabilizadas = array();
							
					$statement10 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
											id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idUtilizador;");
					$statement10->execute();
					$resultado10 = $statement10->get_result();
					while($linha10 = mysqli_fetch_assoc($resultado10)){
						$id_comp = $linha10["id_componente"];
							
						$statement11 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
						$statement11->execute();
						$resultado11 = $statement11->get_result();
						$linha11 = mysqli_fetch_assoc($resultado11);
							$numero_horas = $linha11["numero_horas"];
							
						$statement12 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NULL;");
						$statement12->execute();
						$resultado12 = $statement12->get_result();
						$linha12 = mysqli_fetch_assoc($resultado12);
							$numero_turmas_sem_juncao = $linha12["COUNT(DISTINCT id_turma)"];
								
							if($numero_turmas_sem_juncao == 0){	
								$statement13 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
								$statement13->execute();
								$resultado13 = $statement13->get_result();
								$linha13 = mysqli_fetch_assoc($resultado13);
									$numero_juncoes_comp = $linha13["COUNT(DISTINCT id_juncao)"];
											
									$statement14 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
									$statement14->execute();
									$resultado14 = $statement14->get_result();
									while($linha14 = mysqli_fetch_assoc($resultado14)){
										$id_juncao = $linha14["id_juncao"];
										
										if(!in_array($id_juncao,$juncoes_ja_contabilizadas)){
											$horas_1_sem = $horas_1_sem + $numero_horas;
											array_push($juncoes_ja_contabilizadas,$id_juncao);
										}
									}
							}
							else{
								$statement14 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NOT NULL;");
								$statement14->execute();
								$resultado14 = $statement14->get_result();
								$linha14 = mysqli_fetch_assoc($resultado14);
									$numero_juncoes_comp = $linha14["COUNT(DISTINCT id_juncao)"];
											
									$statement15 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NOT NULL;");
									$statement15->execute();
									$resultado15 = $statement15->get_result();
									while($linha15 = mysqli_fetch_assoc($resultado15)){
										$id_juncao = $linha15["id_juncao"];
										
										if(!in_array($id_juncao,$juncoes_ja_contabilizadas)){
											$horas_1_sem = $horas_1_sem + $numero_horas;
											array_push($juncoes_ja_contabilizadas,$id_juncao);
										}
									}
											
									$horas_1_sem = $horas_1_sem + ($numero_turmas_sem_juncao * $numero_horas);
							}
					}
							
				}
						
		/*---------------------------------------------2º SEMESTRE-----------------------------------------------------*/
		$array_componentes_2_sem = array();
		$array_disciplinas_2_sem = array();

		$statement30 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $idUtilizador;");
		$statement30->execute();
		$resultado30 = $statement30->get_result();
		while($linha30 = mysqli_fetch_assoc($resultado30)){
			$id_componente = $linha30["id_componente"];
					
			$statement31 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
			$statement31->execute();
			$resultado31 = $statement31->get_result();
			while($linha31 = mysqli_fetch_assoc($resultado31)){
				$id_disciplina = $linha31["id_disciplina"];
						
				$statement32 = mysqli_prepare($conn, "SELECT semestre FROM disciplina WHERE id_disciplina = $id_disciplina;");
				$statement32->execute();
				$resultado32 = $statement32->get_result();
				$linha32 = mysqli_fetch_assoc($resultado32);
					$sem_disciplina = $linha32["semestre"];
						
					if($sem_disciplina == 2){
						array_push($array_componentes_2_sem,$id_componente);
						array_push($array_disciplinas_2_sem,$id_disciplina);
					}
			}
				
		}

		$array_componentes_2_sem_temp = array_unique($array_componentes_2_sem);
		$array_componentes_2_sem_final = implode("','",$array_componentes_2_sem_temp);

		$array_disciplinas_2_sem_temp = array_unique($array_disciplinas_2_sem);
		$array_disciplinas_2_sem_final = implode("','",$array_disciplinas_2_sem_temp);

		$statement33 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE 
											id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idUtilizador;");
		$statement33->execute();
		$resultado33 = $statement33->get_result();
		$linha33 = mysqli_fetch_assoc($resultado33);
			$num_turmas_docente_2_sem = $linha33["COUNT(DISTINCT id_turma)"];

			$horas_2_sem = 0;

			$statement34 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE 
											id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idUtilizador;");
			$statement34->execute();
			$resultado34 = $statement34->get_result();
			$linha34 = mysqli_fetch_assoc($resultado34);
				$num_juncoes_2_sem = $linha34["COUNT(DISTINCT id_juncao)"];
					
				if($num_juncoes_2_sem == 0){
							
					$statement35 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
											id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idUtilizador;");
					$statement35->execute();
					$resultado35 = $statement35->get_result();
					while($linha35 = mysqli_fetch_assoc($resultado35)){
						$id_comp = $linha35["id_componente"];
								
						$statement36 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
						$statement36->execute();
						$resultado36 = $statement36->get_result();
						$linha36 = mysqli_fetch_assoc($resultado36);
							$numero_horas_comp = $linha36["numero_horas"];
									
							$statement365 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
							$statement365->execute();
							$resultado365 = $statement365->get_result();
							$linha365 = mysqli_fetch_assoc($resultado365);
								$numero_turmas = $linha365["COUNT(DISTINCT id_turma)"];
										
								$horas_2_sem = $horas_2_sem + ($numero_horas_comp * $numero_turmas);
					}
					
				}

				else{
							
					$juncoes_ja_contabilizadas_2_sem = array();
							
					$statement37 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
											id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idUtilizador;");
					$statement37->execute();
					$resultado37 = $statement37->get_result();
					while($linha37 = mysqli_fetch_assoc($resultado37)){
						$id_comp = $linha37["id_componente"];
							
						$statement38 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
						$statement38->execute();
						$resultado38 = $statement38->get_result();
						$linha38 = mysqli_fetch_assoc($resultado38);
							$numero_horas = $linha38["numero_horas"];
							
						$statement39 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NULL;");
						$statement39->execute();
						$resultado39 = $statement39->get_result();
						$linha39 = mysqli_fetch_assoc($resultado39);
							$numero_turmas_sem_juncao = $linha39["COUNT(DISTINCT id_turma)"];
								
							if($numero_turmas_sem_juncao == 0){	
								$statement40 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
								$statement40->execute();
								$resultado40 = $statement40->get_result();
								$linha40 = mysqli_fetch_assoc($resultado40);
									$numero_juncoes_comp = $linha40["COUNT(DISTINCT id_juncao)"];
											
									$statement41 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
									$statement41->execute();
									$resultado41 = $statement41->get_result();
									while($linha41 = mysqli_fetch_assoc($resultado41)){
										$id_juncao = $linha41["id_juncao"];
												
										if(!in_array($id_juncao,$juncoes_ja_contabilizadas_2_sem)){
											$horas_2_sem = $horas_2_sem + $numero_horas;
											array_push($juncoes_ja_contabilizadas_2_sem,$id_juncao);
										}
									}
							}
							else{
								$statement42 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NOT NULL;");
								$statement42->execute();
								$resultado42 = $statement42->get_result();
								$linha42 = mysqli_fetch_assoc($resultado42);
									$numero_juncoes_comp = $linha42["COUNT(DISTINCT id_juncao)"];
											
									$statement43 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NOT NULL;");
									$statement43->execute();
									$resultado43 = $statement43->get_result();
									while($linha43 = mysqli_fetch_assoc($resultado43)){
										$id_juncao = $linha43["id_juncao"];
												
										if(!in_array($id_juncao,$juncoes_ja_contabilizadas_2_sem)){
											$horas_2_sem = $horas_2_sem + $numero_horas;
											array_push($juncoes_ja_contabilizadas_2_sem,$id_juncao);
										}
									}
											
									$horas_2_sem = $horas_2_sem + ($numero_turmas_sem_juncao * $numero_horas);
							}
					}
							
				}
				
			array_push($array_dados,$nomeUtilizador);
			array_push($array_dados,$imgUtilizador);
			array_push($array_dados,$nome_funcao_utilizador);
			array_push($array_dados,$siglaUtcUtilizador);
			array_push($array_dados,$nomeAreaUtilizador);
			array_push($array_dados,$idUtilizador);
			array_push($array_dados,sizeof($array_disciplinas_1_sem_temp));
			array_push($array_dados,$horas_1_sem);
			array_push($array_dados,sizeof($array_disciplinas_2_sem_temp));
			array_push($array_dados,$horas_2_sem);
			array_push($array_dados,$idAreaUtilizador);
			array_push($array_dados,$idResponsavelUtc);
			
	}	
	
		echo json_encode($array_dados);
		
}
?>
	
	