<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_utc"])){
		
		$id_utc = $_POST["id_utc"];

		$cursos = array();
		
		if(!isset($_POST["array_turmas_temp"])){
			
			$statement = mysqli_prepare($conn, "SELECT id_curso, nome FROM curso c WHERE id_utc = $id_utc ORDER BY nome;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_array($resultado)){
				$id_curso = $linha['id_curso'];
				$nome_curso = $linha['nome'];
				
				$statement1 = mysqli_prepare($conn, "SELECT id_disciplina, ano, semestre FROM disciplina WHERE id_curso = $id_curso ORDER BY id_curso, id_disciplina;");
				$statement1->execute();
				$resultado1 = $statement1->get_result();
				while($linha1 = mysqli_fetch_assoc($resultado1)){
					$id_disciplina = $linha1["id_disciplina"];
					$ano = $linha1["ano"];
					$semestre = $linha1["semestre"];
								
						$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						while($linha3 = mysqli_fetch_assoc($resultado3)){
							$id_turma = $linha3["id_turma"];
					
							$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_componente, numero_horas FROM componente WHERE id_disciplina = $id_disciplina;");
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
										if(!in_array($id_curso,$cursos)){
											array_push($cursos, $id_curso);
											array_push($cursos, $nome_curso);
										}
									}
							}
						}
				}
				
			}	
			
			$List = implode(",", $cursos);
			print_r($List);
				
		}
		
		else{
			$array_turmas_temp = $_POST["array_turmas_temp"];
			
			$id_comp_esquerda = $array_turmas_temp[1];
			
			
			$statement0 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp_esquerda;");
			$statement0->execute();
			$resultado0 = $statement0->get_result();
			$linha0 = mysqli_fetch_array($resultado0);
				$numero_horas_original = $linha0['numero_horas'];
				
			$statement01 = mysqli_prepare($conn, "SELECT d.semestre FROM disciplina d INNER JOIN componente c ON 
											d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_comp_esquerda;");
			$statement01->execute();
			$resultado01 = $statement01->get_result();
			$linha01 = mysqli_fetch_array($resultado01);
				$semestre_original = $linha01['semestre'];
					
			$statement = mysqli_prepare($conn, "SELECT id_curso, nome FROM curso c WHERE id_utc = $id_utc ORDER BY nome;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_array($resultado)){
				$id_curso = $linha['id_curso'];
				$nome_curso = $linha['nome'];
				
				$statement1 = mysqli_prepare($conn, "SELECT id_disciplina, ano, semestre FROM disciplina WHERE id_curso = $id_curso ORDER BY id_curso, id_disciplina;");
				$statement1->execute();
				$resultado1 = $statement1->get_result();
				while($linha1 = mysqli_fetch_assoc($resultado1)){
					$id_disciplina = $linha1["id_disciplina"];
					$ano = $linha1["ano"];
					$semestre = $linha1["semestre"];
								
						$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso;");
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

									if($id_turma_temp == $id_turma/* && $id_componente_temp == $id_componente */){
										$turma_ja_esta = 1;
									}
											
									$loop = $loop + 2;
								}
						
								$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
								$statement5->execute();
								$resultado5 = $statement5->get_result();
								$linha5 = mysqli_fetch_assoc($resultado5);
									$count_juncao = $linha5["COUNT(id_juncao)"];
									
									if($count_juncao == 0 && $numero_horas == $numero_horas_original && $semestre == $semestre_original && $turma_ja_esta == 0){
										if(!in_array($id_curso,$cursos)){
											array_push($cursos, $id_curso);
											array_push($cursos, $nome_curso);
										}
									}
							}
						}
				}
				
			}
			
			$List = implode(",", $cursos);
			print_r($List);
			
		}
		
	}
	
?>