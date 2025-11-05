<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_componente"]) && isset($_POST["id_docente"]) && isset($_POST["array_turmas"]) && isset($_POST["id_juncao"])){
		
		$id_componente = $_POST["id_componente"];
		$id_docente = $_POST["id_docente"];
		$array_turmas = $_POST["array_turmas"];
		$id_juncao = $_POST["id_juncao"];
		
		//Pegar nas turmas e juntá-as à juncão
		
		$counter = 0;
		while($counter < sizeof($array_turmas)){
			$id_turma = $array_turmas[$counter];
	
			$statement0 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_componente = $id_componente
														AND id_turma = $id_turma;");
			$statement0->execute();
			$statement1 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente
														AND id_turma = $id_turma;");
			$statement1->execute();
			
			$counter = $counter + 1;
		}
	
		//Se houver alguma turma não selecionada mas que também esteja na junção, atualizar o docente dessa turma
		$statement2 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_juncao = $id_juncao;");
		$statement2->execute();
		
		/*
		$statement0 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_uc;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_array($resultado0);
			$nome_disciplina = $linha0["abreviacao_uc"];
		
		echo $id_componente;
		print_r($array_turmas); */
	}
	
?>