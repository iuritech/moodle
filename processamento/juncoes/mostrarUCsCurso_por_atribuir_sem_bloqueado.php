<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
	
	if(isset($_POST["id_curso"]) && isset($_POST["sem_bloqueado"])){
		
		$id_curso = $_POST["id_curso"];
		$sem_bloqueado = $_POST["sem_bloqueado"];
		
		$ucs = array();
				
		if(!isset($_POST["array_turmas"])){
					
			$statement = mysqli_prepare($conn, "SELECT id_disciplina, nome_uc, ano, semestre FROM disciplina WHERE id_curso = $id_curso AND semestre != $sem_bloqueado ORDER BY nome_uc;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_array($resultado)){
				$id_disciplina = $linha['id_disciplina'];
				$nome_disciplina = $linha['nome_uc'];
				$ano = $linha["ano"];
				$semestre = $linha["semestre"];
				
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
								if(!in_array($id_disciplina,$ucs)){
									array_push($ucs,$id_disciplina);
									array_push($ucs, $nome_disciplina);
								}
							}
					}
				}
			}
			
			$List = implode(",", $ucs);
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
			
			$statement = mysqli_prepare($conn, "SELECT id_disciplina, nome_uc, ano, semestre FROM disciplina WHERE id_curso = $id_curso AND semestre != $sem_bloqueado ORDER BY nome_uc;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_array($resultado)){
				$id_disciplina = $linha['id_disciplina'];
				$nome_disciplina = $linha['nome_uc'];
				$ano = $linha["ano"];
				$semestre = $linha["semestre"];
				
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
									if(!in_array($id_disciplina,$ucs)){
										array_push($ucs,$id_disciplina);
										array_push($ucs, $nome_disciplina);
									}
								}
							}
					}
				}
			}
			
			$List = implode(",", $ucs);
			print_r($List);
			
		}
		
	}
	
?>