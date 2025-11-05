<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if(isset($_POST['id_componente']) && (isset($_POST['id_docente'])) && (isset($_POST['id_turma'])) && (isset($_POST['id_juncao'])) && (isset($_POST['id_nova_sala']))){	

		$id_componente = $_POST["id_componente"];
		$id_docente = $_POST["id_docente"];
		$id_turma = $_POST["id_turma"];
		$id_juncao = $_POST["id_juncao"];
		$id_nova_sala = $_POST["id_nova_sala"];
		
		$statement = mysqli_prepare($conn, "SELECT id_horario FROM aula WHERE id_componente = $id_componente AND id_docente = $id_docente AND id_turma = $id_turma;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$id_horario = $linha["id_horario"];
		
		$statement1 = mysqli_prepare($conn, "UPDATE horario SET id_sala = $id_nova_sala WHERE id_horario = $id_horario;");
		$statement1->execute();
		
	}