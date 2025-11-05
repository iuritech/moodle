<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

if(isset($_POST['id_componente']) && isset($_POST['id_turma']) && isset($_POST['id_docente']) && isset($_POST['id_juncao'])){

	$id_componente = $_POST["id_componente"];
	$id_turma = $_POST["id_turma"];
	$id_docente = $_POST["id_docente"];
	$id_juncao = $_POST["id_juncao"];
	
	//Ver o docente que já está na junção
	$statement0 = mysqli_prepare($conn, "SELECT DISTINCT id_docente FROM aula WHERE id_juncao = $id_juncao;");
	$statement0->execute();
	$resultado0 = $statement0->get_result();
	$linha0 = mysqli_fetch_array($resultado0);
	$id_docente_juncao = $linha0["id_docente"];
		
	//Juntar a turma escolhida à junção
	$statement = mysqli_prepare($conn, "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma;");
	$statement->execute();
	
	if($id_docente != 0){
		//Atribuir o novo docente escolhido a todas as turmas da junção
		$statement2 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_juncao = $id_juncao;");
		$statement2->execute();
	}
	else{
		//Atribuir o docente que estava na junção a esta turma
		$statement = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma;");
		$statement->execute();
	}
	
}