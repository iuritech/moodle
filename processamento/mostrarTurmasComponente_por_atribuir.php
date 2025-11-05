<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_componente"])){
		
		$id_componente = $_POST["id_componente"];
		
		$statement0 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_array($resultado0);
			$id_disciplina = $linha0["id_disciplina"];
			
		$statement1 = mysqli_prepare($conn, "SELECT ano, semestre, id_curso FROM disciplina WHERE id_disciplina = $id_disciplina;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_array($resultado1);
			$ano = $linha1["ano"];
			$semestre = $linha1["semestre"];
			$id_curso = $linha1["id_curso"];
				
		$turmas = array();
		
		if(!isset($_POST["array_turmas"])){
		
			$statement2 = mysqli_prepare($conn, "SELECT id_turma, nome FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso ORDER BY nome;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_array($resultado2)){
				$id_turma = $linha2['id_turma'];
				$nome_turma = $linha2["nome"];

				$statement3 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				$linha3 = mysqli_fetch_array($resultado3);
					$num_docentes = $linha3['COUNT(id_docente)'];
			
					if($num_docentes == 0){
					
						$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_array($resultado6);
							$num_juncoes = $linha6['COUNT(id_juncao)'];
							
							if($num_juncoes > 0){
								$statement7 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								$linha7 = mysqli_fetch_array($resultado7);
									$id_juncao = $linha7['id_juncao'];
									
								$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM juncao_componente WHERE id_juncao = $id_juncao;");
								$statement8->execute();
								$resultado8 = $statement8->get_result();
								$linha8 = mysqli_fetch_array($resultado8);
									$num_componentes_diferentes = $linha8['COUNT(DISTINCT id_componente)'];
									
									$num_juncoes = $num_componentes_diferentes;
							}
							
							if(!in_array($id_turma,$turmas)){
								array_push($turmas,$id_turma);
								array_push($turmas,$nome_turma);
								array_push($turmas,$num_juncoes);
							}
					}
			}
			
			$List = implode(",", $turmas);
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
			
			$statement2 = mysqli_prepare($conn, "SELECT id_turma, nome FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso ORDER BY nome;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_array($resultado2)){
				$id_turma = $linha2['id_turma'];
				$nome_turma = $linha2["nome"];

				$statement3 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				$linha3 = mysqli_fetch_array($resultado3);
					$num_docentes = $linha3['COUNT(id_docente)'];
			
					if($num_docentes == 0){
						if(($id_componente == $id_componente_original && (!in_array($id_turma,$array_ids_turmas))) || ($id_componente != $id_componente_original)){
							$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
							$statement6->execute();
							$resultado6 = $statement6->get_result();
							$linha6 = mysqli_fetch_array($resultado6);
								$num_juncoes = $linha6['COUNT(id_juncao)'];
								
								if($num_juncoes > 0 && $id_componente != $id_componente_original && (!in_array($id_turma,$array_ids_turmas))){
									$statement7 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									$linha7 = mysqli_fetch_array($resultado7);
										$id_juncao = $linha7['id_juncao'];
										
									$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM juncao_componente WHERE id_juncao = $id_juncao;");
									$statement8->execute();
									$resultado8 = $statement8->get_result();
									$linha8 = mysqli_fetch_array($resultado8);
										$num_componentes_diferentes = $linha8['COUNT(DISTINCT id_componente)'];
										
										$num_juncoes = $num_componentes_diferentes;
								}
								
								if(!in_array($id_turma,$turmas)){
									array_push($turmas,$id_turma);
									array_push($turmas,$nome_turma);
									array_push($turmas,$num_juncoes);
								}
						}
					}
			}
			
			$List = implode(",", $turmas);
			print_r($List);
	
		}
		
	}
	
?>