<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['turmas_selecionadas']) && !isset($_POST['ucs_selecionadas'])){

		$turmas_selecionadas = $_POST['turmas_selecionadas'];
		$counter_turma = 0;
		while($counter_turma < sizeof($turmas_selecionadas)){
				
			$id_turma = $turmas_selecionadas[$counter_turma];	
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_turma = $id_turma;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_assoc($resultado)){
				$numero_juncoes_turma = $linha["COUNT(DISTINCT id_juncao)"];
				
				if($numero_juncoes_turma == 0){
					$statement1 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_turma = $id_turma;");
					$statement1->execute();
					
					$statement2 = mysqli_prepare($conn, "DELETE FROM turma WHERE id_turma = $id_turma;");
					$statement2->execute();
				}
				else{
					
					$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_turma = $id_turma;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					while($linha3 = mysqli_fetch_assoc($resultado3)){
						$id_juncao_turma = $linha3["id_juncao"];
						//echo $id_juncao_turma, " : ";
						$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao_turma;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$num_turmas_juncao_turma = $linha4["COUNT(DISTINCT id_turma)"];
							
							if($num_turmas_juncao_turma < 3){
								//echo "CASE 1";
								$statement5 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_turma;");
								$statement5->execute();
								
								$statement6 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_turma;");
								$statement6->execute();
								
								$statement7 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao_turma;");
								$statement7->execute();
							}
							else{
								//echo "CASE 2";
								
								//Saber se apagar a componente da turma na tabela juncao_componente
								$statement8 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_turma = $id_turma AND id_juncao = $id_juncao_turma;");
								$statement8->execute();
								$resultado8 = $statement8->get_result();
								$linha8 = mysqli_fetch_assoc($resultado8);
									$id_componente = $linha8["id_componente"];
									
									$statement9 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_turma != $id_turma AND id_juncao = $id_juncao_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$num_outras_turmas_mesma_componente = $linha9["COUNT(DISTINCT id_turma)"];
										
										if($num_outras_turmas_mesma_componente == 0){
											$statement10 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_turma;");
											$statement10->execute();
											
											$statement11 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_turma;");
											$statement11->execute();
										}
								
							}
							
							$statement15 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_turma = $id_turma;");
							$statement15->execute();
									
							$statement16 = mysqli_prepare($conn, "DELETE FROM turma WHERE id_turma = $id_turma;");
							$statement16->execute();
					}
				}
				
			}
			
			$counter_turma += 1;
		}
	
	}
	
	else if(isset($_POST['ucs_selecionadas']) && !isset($_POST['turmas_selecionadas'])){
		
		$ucs_selecionadas = $_POST["ucs_selecionadas"];
		
		$counter_uc = 0;
		while($counter_uc < sizeof($ucs_selecionadas)){
			
			$id_uc = $ucs_selecionadas[$counter_uc];
			
			$statement14 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_uc;");
			$statement14->execute();
			$resultado14 = $statement14->get_result();
			while($linha14 = mysqli_fetch_assoc($resultado14)){
				$id_componente = $linha14["id_componente"];
			
				$statement15 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente;");
				$statement15->execute();
				$resultado15 = $statement15->get_result();
				while($linha15 = mysqli_fetch_assoc($resultado15)){
					$numero_juncoes_turma = $linha15["COUNT(DISTINCT id_juncao)"];
					
					if($numero_juncoes_turma == 0){
						$statement16 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_componente = $id_componente;");
						$statement16->execute();
						
						$statement17 = mysqli_prepare($conn, "DELETE FROM componente WHERE id_componente = $id_componente;");
						$statement17->execute();
					}
					else{
						$statement18 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente;");
						$statement18->execute();
						$resultado18 = $statement18->get_result();
						while($linha18 = mysqli_fetch_assoc($resultado18)){
							$id_juncao_componente = $linha18["id_juncao"];
								
							$statement19 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao_componente;");
							$statement19->execute();
							$resultado19 = $statement19->get_result();
							$linha19 = mysqli_fetch_assoc($resultado19);
								$num_turmas_juncao_componente = $linha19["COUNT(DISTINCT id_turma)"];
								
								if($num_turmas_juncao_componente < 3){
									//echo "CASE 1";
									$statement20 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_componente;");
									$statement20->execute();
									
									$statement21 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_componente;");
									$statement21->execute();
									
									$statement22 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao_componente;");
									$statement22->execute();
								}
								else{
									//echo "CASE 2";
								
									//Saber se apagar a componente da turma na tabela juncao_componente
									$statement23 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente != $id_componente AND id_juncao = $id_juncao_componente;");
									$statement23->execute();
									$resultado23 = $statement23->get_result();
									$linha23 = mysqli_fetch_assoc($resultado23);
										$num_turmas_diferentes_componente = $linha23["COUNT(DISTINCT id_turma)"];
										
										if($num_turmas_diferentes_componente == 1){
											$statement24 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_componente;");
											$statement24->execute();
											
											$statement25 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_componente;");
											$statement25->execute();
											
											$statement26 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao_componente;");
											$statement26->execute();
										}
										else{
											$statement27 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_componente AND id_componente = $id_componente;");
											$statement27->execute();
											
											$statement28 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_componente AND id_componente = $id_componente;");
											$statement28->execute();
										}
								}
						}	
							
						$statement29 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_componente = $id_componente;");
						$statement29->execute();
								
						$statement30 = mysqli_prepare($conn, "DELETE FROM componente WHERE id_componente = $id_componente;");
						$statement30->execute();
						
					}
					
				}
			}
			
			$statement31 = mysqli_prepare($conn, "DELETE FROM disciplina WHERE id_disciplina = $id_uc;");
			$statement31->execute();
				
			$counter_uc += 1;
		}
		
	}
	
	else if(isset($_POST['ucs_selecionadas']) && isset($_POST['turmas_selecionadas'])){
		
		$ucs_selecionadas = $_POST["ucs_selecionadas"];
		
		$counter_uc = 0;
		while($counter_uc < sizeof($ucs_selecionadas)){
			
			$id_uc = $ucs_selecionadas[$counter_uc];
			
			$statement14 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_uc;");
			$statement14->execute();
			$resultado14 = $statement14->get_result();
			while($linha14 = mysqli_fetch_assoc($resultado14)){
				$id_componente = $linha14["id_componente"];
			
				$statement15 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente;");
				$statement15->execute();
				$resultado15 = $statement15->get_result();
				while($linha15 = mysqli_fetch_assoc($resultado15)){
					$numero_juncoes_turma = $linha15["COUNT(DISTINCT id_juncao)"];
					
					if($numero_juncoes_turma == 0){
						$statement16 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_componente = $id_componente;");
						$statement16->execute();
						
						$statement17 = mysqli_prepare($conn, "DELETE FROM componente WHERE id_componente = $id_componente;");
						$statement17->execute();
					}
					else{
						$statement18 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente;");
						$statement18->execute();
						$resultado18 = $statement18->get_result();
						while($linha18 = mysqli_fetch_assoc($resultado18)){
							$id_juncao_componente = $linha18["id_juncao"];
								
							$statement19 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao_componente;");
							$statement19->execute();
							$resultado19 = $statement19->get_result();
							$linha19 = mysqli_fetch_assoc($resultado19);
								$num_turmas_juncao_componente = $linha19["COUNT(DISTINCT id_turma)"];
								
								if($num_turmas_juncao_componente < 3){
									//echo "CASE 1";
									$statement20 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_componente;");
									$statement20->execute();
									
									$statement21 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_componente;");
									$statement21->execute();
									
									$statement22 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao_componente;");
									$statement22->execute();
								}
								else{
									//echo "CASE 2";
								
									//Saber se apagar a componente da turma na tabela juncao_componente
									$statement23 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente != $id_componente AND id_juncao = $id_juncao_componente;");
									$statement23->execute();
									$resultado23 = $statement23->get_result();
									$linha23 = mysqli_fetch_assoc($resultado23);
										$num_turmas_diferentes_componente = $linha23["COUNT(DISTINCT id_turma)"];
										
										if($num_turmas_diferentes_componente == 1){
											$statement24 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_componente;");
											$statement24->execute();
											
											$statement25 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_componente;");
											$statement25->execute();
											
											$statement26 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao_componente;");
											$statement26->execute();
										}
										else{
											$statement27 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_componente AND id_componente = $id_componente;");
											$statement27->execute();
											
											$statement28 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_componente AND id_componente = $id_componente;");
											$statement28->execute();
										}
								}
						}	
							
						$statement29 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_componente = $id_componente;");
						$statement29->execute();
								
						$statement30 = mysqli_prepare($conn, "DELETE FROM componente WHERE id_componente = $id_componente;");
						$statement30->execute();
						
					}
					
				}
			}
			
			$statement31 = mysqli_prepare($conn, "DELETE FROM disciplina WHERE id_disciplina = $id_uc;");
			$statement31->execute();
				
			$counter_uc += 1;
		}
		
		$turmas_selecionadas = $_POST['turmas_selecionadas'];
		$counter_turma = 0;
		while($counter_turma < sizeof($turmas_selecionadas)){
				
			$id_turma = $turmas_selecionadas[$counter_turma];	
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_turma = $id_turma;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_assoc($resultado)){
				$numero_juncoes_turma = $linha["COUNT(DISTINCT id_juncao)"];
				
				if($numero_juncoes_turma == 0){
					$statement1 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_turma = $id_turma;");
					$statement1->execute();
					
					$statement2 = mysqli_prepare($conn, "DELETE FROM turma WHERE id_turma = $id_turma;");
					$statement2->execute();
				}
				else{
					
					$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_turma = $id_turma;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					while($linha3 = mysqli_fetch_assoc($resultado3)){
						$id_juncao_turma = $linha3["id_juncao"];
						//echo $id_juncao_turma, " : ";
						$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao_turma;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$num_turmas_juncao_turma = $linha4["COUNT(DISTINCT id_turma)"];
							
							if($num_turmas_juncao_turma < 3){
								//echo "CASE 1";
								$statement5 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_turma;");
								$statement5->execute();
								
								$statement6 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_turma;");
								$statement6->execute();
								
								$statement7 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao_turma;");
								$statement7->execute();
							}
							else{
								//echo "CASE 2";
								
								//Saber se apagar a componente da turma na tabela juncao_componente
								$statement8 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_turma = $id_turma AND id_juncao = $id_juncao_turma;");
								$statement8->execute();
								$resultado8 = $statement8->get_result();
								$linha8 = mysqli_fetch_assoc($resultado8);
									$id_componente = $linha8["id_componente"];
									
									$statement9 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente == $id_componente AND id_turma != $id_turma AND id_juncao = $id_juncao_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$num_outras_turmas_mesma_componente = $linha9["COUNT(DISTINCT id_turma)"];
										
										if($num_outras_turmas_mesma_componente == 0){
											$statement10 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_turma;");
											$statement10->execute();
											
											$statement11 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_turma;");
											$statement11->execute();
										}
								
							}
							
							$statement15 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_turma = $id_turma;");
							$statement15->execute();
									
							$statement16 = mysqli_prepare($conn, "DELETE FROM turma WHERE id_turma = $id_turma;");
							$statement16->execute();
					}
				}
				
			}
			
			$counter_turma += 1;
		}
		
	}
	
	
?>