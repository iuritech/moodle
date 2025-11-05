<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['array_turmas']) && isset($_POST['id_juncao'])){

		$array_turmas = $_POST['array_turmas'];
		$id_juncao = $_POST['id_juncao'];
	
		$statement0 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_array($resultado0);
			$num_turmas_juncao = $linha0["COUNT(DISTINCT id_turma)"];
		
		if(($num_turmas_juncao < 3) || (($num_turmas_juncao - sizeof($array_turmas)) < 2)){
			//Junção tinha apenas duas turmas // ia passar a ter apenas uma turma, eliminar a junção
			
			$statement1 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao;");
			$statement1->execute();
			
			$statement2 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao;");
			$statement2->execute();
			
			$statement3 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao;");
			$statement3->execute();
		}
		else {
			$counter = 0;
			while($counter < sizeof($array_turmas)){
				
				$id_turma = $array_turmas[$counter];
				
				$statement4 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_turma = $id_turma AND id_juncao = $id_juncao;");
				$statement4->execute();
				$resultado4 = $statement4->get_result();
				$linha4 = mysqli_fetch_array($resultado4);
					$id_componente = $linha4["id_componente"];

				$statement5 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_juncao = $id_juncao;");
				$statement5->execute();
				$resultado5 = $statement5->get_result();
				$linha5 = mysqli_fetch_array($resultado5);
					$num_turmas_comp_juncao = $linha5["COUNT(DISTINCT id_turma)"];
		
				if($num_turmas_comp_juncao == 1){
					//Apenas esta turma nesta componente estava na junção, logo apagar componente na tabela juncao_componente
					$statement6 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao AND id_componente = $id_componente;");
					$statement6->execute();
				}
				
				$statement7 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_turma = $id_turma AND id_componente = $id_componente;");
				$statement7->execute();
				
				$counter = $counter + 1;
			}
			
		}

	}
	
?>