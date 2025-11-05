<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_curso'])){
		
		$id_curso = $_POST['id_curso'];
		
		//Unidades Curriculares
		$statement = mysqli_prepare($conn, "SELECT id_disciplina FROM disciplina WHERE id_curso = $id_curso;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_disciplina = $linha["id_disciplina"];
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_disciplina;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_componente = $linha1["id_componente"];
				
				//Junções
				$statement2 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_juncao IS NOT NULL;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$num_juncoes_componente = $linha2["COUNT(id_juncao)"];
					
					if($num_juncoes_componente > 0){
						
						$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND id_juncao IS NOT NULL;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						while($linha3 = mysqli_fetch_assoc($resultado3)){
							$juncao_da_componente = $linha3["id_juncao"];
							
							$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $juncao_da_componente;");
							$statement4->execute();
							$resultado4 = $statement4->get_result();
							$linha4 = mysqli_fetch_assoc($resultado4);
								$num_turmas_juncao = $linha4["COUNT(DISTINCT id_turma)"];
							
								if($num_turmas_juncao < 3){
									//echo "TESTE1";
									$statement5 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $juncao_da_componente;");
									$statement5->execute();
								}
								else{
									//echo "TESTE2!";
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_turma) FROM aula WHERE id_juncao = $juncao_da_componente AND id_componente != $id_componente;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$num_turmas_outra_componente_na_juncao = $linha6["COUNT(id_turma)"];
										
										if($num_turmas_outra_componente_na_juncao < 2){
											$statement7 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $juncao_da_componente;");
											$statement7->execute();
											
											$statement8 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $juncao_da_componente");
											$statement8->execute();
											
											$statement9 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $juncao_da_componente;");
											$statement9->execute();
										}
								}
								
								$statement10 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $juncao_da_componente;");
								$statement10->execute();
								
								if($num_turmas_juncao < 3){
									$statement101 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $juncao_da_componente;");
									$statement101->execute();
								}
										
								$statement11 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_componente = $id_componente;");
								$statement11->execute();
										
								$statement12 = mysqli_prepare($conn, "DELETE FROM componente WHERE id_componente = $id_componente;");
								$statement12->execute();
						}
						
					}
					else{
						$statement13 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_componente = $id_componente;");
						$statement13->execute();
										
						$statement14 = mysqli_prepare($conn, "DELETE FROM componente WHERE id_componente = $id_componente;");
						$statement14->execute();
					}
					
			}
			
			$statement15 = mysqli_prepare($conn, "DELETE FROM disciplina WHERE id_disciplina = $id_disciplina;");
			$statement15->execute();
			
		}

		//Turmas
		$statement16 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE id_curso = $id_curso;");
		$statement16->execute();
		$resultado16 = $statement16->get_result();
		while($linha16 = mysqli_fetch_assoc($resultado16)){
			$id_turma = $linha16["id_turma"];
			
			//Junções
			$statement17 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_turma = $id_turma AND id_juncao IS NOT NULL;");
			$statement17->execute();
			$resultado17 = $statement17->get_result();
			$linha17 = mysqli_fetch_assoc($resultado17);
				$num_juncoes_turma = $linha17["COUNT(id_juncao)"];
				
				if($num_juncoes_turma == 0){
					$statement18 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_turma = $id_turma;");
					$statement18->execute();
					
					$statement19 = mysqli_prepare($conn, "DELETE FROM turma WHERE id_turma = $id_turma;");
					$statement19->execute();
				}
				else{
					$statement20 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_turma = $id_turma AND id_juncao IS NOT NULL;");
					$statement20->execute();
					$resultado20 = $statement20->get_result();
					while($linha20 = mysqli_fetch_assoc($resultado20)){
						$id_juncao_turma = $linha20["id_juncao"];
						
						$statement21 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao_turma;");
						$statement21->execute();
						$resultado21 = $statement21->get_result();
						$linha21 = mysqli_fetch_assoc($resultado21);
							$num_turmas_juncao = $linha21["COUNT(DISTINCT id_turma)"];
							
							if($num_turmas_juncao < 3){
								$statement22 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_turma;");
								$statement22->execute();
								
								$statement23 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_turma;");
								$statement23->execute();
								
								$statement24 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao_turma;");
								$statement24->execute();
							}
							else{
								$statement25 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_turma = $id_turma AND id_juncao = $id_juncao_turma;");
								$statement25->execute();
								$resultado25 = $statement25->get_result();
								$linha25 = mysqli_fetch_assoc($resultado25);
									$id_componente_turma_juncao = $linha25["id_componente"];
									
									$statement26 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao_turma AND id_componente = $id_componente_turma_juncao;");
									$statement26->execute();
									$resultado26 = $statement26->get_result();
									$linha26 = mysqli_fetch_assoc($resultado26);
										$num_turmas_componente_na_juncao = $linha26["COUNT(DISTINCT id_turma)"];
										
									if($num_turmas_componente_na_juncao == 1){
										$statement27 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_turma;");
										$statement27->execute();
										
										$statement28 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_turma AND id_componente = $id_componente_turma_juncao;");
										$statement28->execute();
									}
								
							}
							$statement29 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_turma = $id_turma;");
							$statement29->execute();
									
							$statement30 = mysqli_prepare($conn, "DELETE FROM turma WHERE id_turma = $id_turma;");
							$statement30->execute();
					}
					
				}
		}
		
		$statement31 = mysqli_prepare($conn, "DELETE FROM curso WHERE id_curso = $id_curso;");
		$statement31->execute();
	
	}	
?>