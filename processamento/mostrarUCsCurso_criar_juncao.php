<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_curso"])){
		
		$id_curso = $_POST["id_curso"];
				
		if(!isset($_POST["array_turmas_temp"])){
			
			//Mostrar UC's que têm turmas/componentes sem junções
			$array_disciplinas_por_atribuir = array();
					
			$statement = mysqli_prepare($conn, "SELECT id_disciplina, nome_uc, abreviacao_uc, ano, semestre FROM disciplina WHERE id_curso = $id_curso ORDER BY nome_uc;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_assoc($resultado)){
				$id_disciplina = $linha["id_disciplina"];
				$nome_disciplina = $linha["nome_uc"];
				$sigla_disciplina = $linha["abreviacao_uc"];
				$ano = $linha["ano"];
				$semestre_temp = $linha["semestre"];
							
					$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre_temp AND id_curso = $id_curso;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					while($linha3 = mysqli_fetch_assoc($resultado3)){
						$id_turma = $linha3["id_turma"];
						
						$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						while($linha4 = mysqli_fetch_assoc($resultado4)){
							$id_componente = $linha4["id_componente"];
							
							$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
							$statement5->execute();
							$resultado5 = $statement5->get_result();
							$linha5 = mysqli_fetch_assoc($resultado5);
								$count_juncao = $linha5["COUNT(id_juncao)"];
									
							if($count_juncao == 0){
								if(!in_array($id_disciplina,$array_disciplinas_por_atribuir)){
									array_push($array_disciplinas_por_atribuir,$id_disciplina);
									array_push($array_disciplinas_por_atribuir,$nome_disciplina);
								}
							}
						}
					
					}
						
			}
			
			$List = implode(",", $array_disciplinas_por_atribuir);
			print_r($List);
			
		}
		
		else{
			
			$array_turmas_temp = $_POST["array_turmas_temp"];
			$id_componente_original = $array_turmas_temp[1];
			
			$statement0 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_original;");
			$statement0->execute();
			$resultado0 = $statement0->get_result();
			$linha0 = mysqli_fetch_array($resultado0);
				$numero_horas_original = $linha0['numero_horas'];
				
			$statement01 = mysqli_prepare($conn, "SELECT d.semestre FROM disciplina d INNER JOIN componente c ON 
											d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente_original;");
			$statement01->execute();
			$resultado01 = $statement01->get_result();
			$linha01 = mysqli_fetch_array($resultado01);
				$semestre_original = $linha01['semestre'];
			
			//Mostrar UC's que têm turmas/componentes sem junções
			$array_final = array();
			$array_disciplinas_por_atribuir = array();
		
			$statement2 = mysqli_prepare($conn, "SELECT sigla_completa FROM curso WHERE id_curso = $id_curso;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			$linha2 = mysqli_fetch_assoc($resultado2);
				$sigla_curso = $linha2["sigla_completa"];
					
			$statement = mysqli_prepare($conn, "SELECT id_disciplina, nome_uc, abreviacao_uc, ano, semestre FROM disciplina WHERE id_curso = $id_curso ORDER BY nome_uc;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_assoc($resultado)){
				$id_disciplina = $linha["id_disciplina"];
				$sigla_disciplina = $linha["abreviacao_uc"];
				$ano = $linha["ano"];
				$semestre_temp = $linha["semestre"];
							
					$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre_temp AND id_curso = $id_curso;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					while($linha3 = mysqli_fetch_assoc($resultado3)){
						$id_turma = $linha3["id_turma"];
						
						$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_componente, numero_horas FROM componente WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						while($linha4 = mysqli_fetch_assoc($resultado4)){
							$id_componente = $linha4["id_componente"];
							$numero_horas = $linha4["numero_horas"];
							
							$loop = 0;
							$turma_ja_esta = 0;
							while($loop < sizeof($array_turmas_temp)){
									
								$id_turma_temp = $array_turmas_temp[$loop];
								$id_componente_temp = $array_turmas_temp[$loop + 1];

								if($id_turma_temp == $id_turma/* && $id_componente_temp == $id_componente*/){
									$turma_ja_esta = 1;
								}
											
								$loop = $loop + 2;
							}
							
							$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
							$statement5->execute();
							$resultado5 = $statement5->get_result();
							$linha5 = mysqli_fetch_assoc($resultado5);
								$count_juncao = $linha5["COUNT(id_juncao)"];
									
							if($count_juncao == 0 && $numero_horas == $numero_horas_original && $semestre_temp == $semestre_original && $turma_ja_esta == 0){
								array_push($array_disciplinas_por_atribuir,$id_disciplina);
							}
						}
					
					}
						
			}
			
			$array_disciplinas_por_atribuir = array_values(array_unique($array_disciplinas_por_atribuir));
			
			$counter = 0;
			while($counter < sizeof($array_disciplinas_por_atribuir)){
				$id_disciplina = $array_disciplinas_por_atribuir[$counter];
				
				$statement6 = mysqli_prepare($conn, "SELECT nome_uc, codigo_uc, id_responsavel, id_area FROM disciplina WHERE id_disciplina = $id_disciplina;");
				$statement6->execute();
				$resultado6 = $statement6->get_result();
				$linha6 = mysqli_fetch_assoc($resultado6);
					$nome_disciplina = $linha6["nome_uc"];
					
					array_push($array_final, $id_disciplina);
					array_push($array_final, $nome_disciplina);
						
				$counter = $counter + 1;
			}
			
			$List = implode(",", $array_final);
			print_r($List);
		
		}
	}
	
?>