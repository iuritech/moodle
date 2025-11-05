<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_componente"]) && isset($_POST["id_docente_original"]) && isset($_POST["id_docente_novo"])){
		
		$id_componente = $_POST["id_componente"];
		$id_docente_original = $_POST["id_docente_original"];
		$id_docente_novo = $_POST["id_docente_novo"];

		$statement0 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND
											id_docente = $id_docente_original;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_array($resultado0);
			$numero_juncoes = $linha0["COUNT(DISTINCT id_juncao)"];
			
			if($numero_juncoes > 0){
			
				$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND
															id_docente = $id_docente_original;");
				$statement1->execute();
				$resultado1 = $statement1->get_result();
				while($linha1 = mysqli_fetch_array($resultado1)){
							
					$id_juncao = $linha1["id_juncao"];
				
					$sql = "UPDATE aula SET id_docente = $id_docente_novo WHERE id_juncao = $id_juncao;";
					
					mysqli_query($conn, $sql);
				}

				
				$statement2 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente
													AND id_docente = $id_docente_original AND id_juncao IS NULL;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_array($resultado2);
					$num_turmas_sem_juncao = $linha2["COUNT(DISTINCT id_turma)"];
				
					if($num_turmas_sem_juncao > 0){
						
						$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND
															id_docente = $id_docente_original AND id_juncao IS NULL;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						while($linha3 = mysqli_fetch_array($resultado3)){
							$id_turma = $linha3["id_turma"];
							
							$sql2 = "UPDATE aula SET id_docente = $id_docente_novo WHERE id_componente = $id_componente 
									AND = id_turma = $id_turma;";
							mysqli_query($conn, $sql2);
						}
						
					}
					
					echo "Sucesso";
			}
			else{

				$sql = "UPDATE aula SET id_docente = $id_docente_novo WHERE id_componente = $id_componente AND id_docente = $id_docente_original;";
				if (mysqli_query($conn, $sql)) {
				  echo "Sucesso";
				} else {			  
					echo "Erro: " . mysqli_error($conn);
				}
		
			}
	}
	
?>