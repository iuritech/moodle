<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['turmas_selecionadas'])){

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
									
									$statement9 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_juncao = $id_juncao_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$num_turmas_mesma_componente = $linha9["COUNT(DISTINCT id_turma)"];
										
										if($num_turmas_mesma_componente == 1){
											$statement10 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao_turma;");
											$statement10->execute();
											
											$statement11 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao_turma AND id_componente = $id_componente;");
											$statement11->execute();
										}
								
							}
							
							$statement12 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_turma = $id_turma;");
							$statement12->execute();
									
							$statement13 = mysqli_prepare($conn, "DELETE FROM turma WHERE id_turma = $id_turma;");
							$statement13->execute();
					}
				}
				
			}
			
			$counter_turma += 1;
		}
	
	}
?>