<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

if(isset($_POST['id_docente']) && isset($_POST['id_componente']) && isset($_POST['id_turma'])){

	$idDocente = $_POST["id_docente"];
	$idComponente = $_POST["id_componente"];
	$idTurma = $_POST["id_turma"];

	//Ver se a turma está numa junção
	$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_turma = $idTurma and id_componente = $idComponente;");
	$statement->execute();
	$resultado = $statement->get_result();
	$linha = mysqli_fetch_array($resultado);
		$numero_juncoes = $linha["COUNT(DISTINCT id_juncao)"];
		
	if($numero_juncoes > 0){
		$statement2 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_turma = $idTurma and id_componente = $idComponente;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_array($resultado2);
			$id_juncao = $linha2["id_juncao"];
			
			if($idDocente != 0){
				$statement = mysqli_prepare($conn, "UPDATE aula SET id_docente = $idDocente WHERE id_juncao = $id_juncao;");
				$statement->execute();
			}
			else{
				$statement = mysqli_prepare($conn, "UPDATE aula SET id_docente = NULL WHERE id_juncao = $id_juncao;");
				$statement->execute();
			}
	}
	else{
		if($idDocente != 0){
			$statement = mysqli_prepare($conn, "UPDATE aula SET id_docente = $idDocente WHERE id_componente = $idComponente AND id_turma = $idTurma;");
			$statement->execute();
		}
		else{
			$statement = mysqli_prepare($conn, "UPDATE aula SET id_docente = NULL WHERE id_componente = $idComponente AND id_turma = $idTurma;");
			$statement->execute();
		}
	}
}