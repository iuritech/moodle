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
				
		if(!isset($_POST["array_turmas"])){
			
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
							
						$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						while($linha4 = mysqli_fetch_assoc($resultado4)){
							$id_componente = $linha4["id_componente"];
							
							$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
							$statement5->execute();
							$resultado5 = $statement5->get_result();
							$linha5 = mysqli_fetch_assoc($resultado5);
								$count_docente = $linha5["COUNT(id_docente)"];
									
								if($count_docente == 0){
									if(!in_array($id_curso,$cursos)){
										array_push($cursos,$id_curso);
										array_push($cursos,$nome_curso);
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
			$array_turmas = $_POST["array_turmas"];
			
			$id_componente_original = $array_turmas[0];
			
			$array_ids_turmas = array();
			
			$loop = 0;
			while($loop < sizeof($array_turmas)){
				$id_turma = $array_turmas[$loop + 1];
				
				array_push($array_ids_turmas,$id_turma);
				
				$loop = $loop + 2;
			}
			
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
							
						$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_disciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						while($linha4 = mysqli_fetch_assoc($resultado4)){
							$id_componente = $linha4["id_componente"];
							
							$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
							$statement5->execute();
							$resultado5 = $statement5->get_result();
							$linha5 = mysqli_fetch_assoc($resultado5);
								$count_docente = $linha5["COUNT(id_docente)"];
									
								$componentes_turma = array();

								$counter = 0;
								while($counter < sizeof($array_turmas)){
									$id_turma_temp = $array_turmas[$counter + 1];
									
									if($id_turma_temp == $id_turma){
										array_push($componentes_turma,$array_turmas[$counter]);
									}
									
									$counter += 2;
								}
									
								if($count_docente == 0){
									if((!in_array($id_turma,$array_turmas)) || (in_array($id_turma,$array_turmas) && (!in_array($id_componente,$componentes_turma))) ){
										if(!in_array($id_curso,$cursos)){
											array_push($cursos,$id_curso);
											array_push($cursos,$nome_curso);
										}
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