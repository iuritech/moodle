<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_componente"]) && isset($_POST["id_docente"]) && isset($_POST["array_turmas"])){
		
		$id_componente = $_POST["id_componente"];
		$id_docente = $_POST["id_docente"];
		$array_turmas = $_POST["array_turmas"];
		
		$counter = 0;
		while($counter < sizeof($array_turmas)){
			$id_turma = $array_turmas[$counter];
			
			//Ver se a turma está num junção
			$statement0 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND 
												id_turma = $id_turma AND id_juncao IS NOT NULL;");
			$statement0->execute();
			$resultado0 = $statement0->get_result();
			$linha0 = mysqli_fetch_array($resultado0);
				$num_juncoes_turma = $linha0["COUNT(id_juncao)"];
				
			if($num_juncoes_turma > 0){
				$statement1 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND 
												id_turma = $id_turma AND id_juncao IS NOT NULL;");
				$statement1->execute();
				$resultado1 = $statement1->get_result();
				$linha1 = mysqli_fetch_array($resultado1);
					$id_juncao = $linha1["id_juncao"];
					
					//Atualizar em todas as turmas da junção
					$statement2 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_juncao = $id_juncao;");
					$statement2->execute();
						
			}
			else{
				$statement1 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_componente = $id_componente
														AND id_turma = $id_turma;");
				$statement1->execute();
			}
			
			$counter = $counter + 1;
		} /*
		$statement0 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_uc;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_array($resultado0);
			$nome_disciplina = $linha0["abreviacao_uc"];
		
		echo $id_componente;
		print_r($array_turmas); */
	}
	
?>