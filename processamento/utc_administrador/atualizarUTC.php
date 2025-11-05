<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_utc']) && isset($_POST['nome_introduzido']) && isset($_POST['sigla_introduzida']) && isset($_POST['id_responsavel_selecionado'])){

		$id_utc = $_POST['id_utc'];
		$nome_introduzido = $_POST['nome_introduzido'];
		$sigla_introduzida = $_POST['sigla_introduzida'];
		$id_responsavel_selecionado = $_POST['id_responsavel_selecionado'];
		
		$statement = mysqli_prepare($conn, "UPDATE utc SET nome_utc = '$nome_introduzido' WHERE id_utc = $id_utc;");
		$statement->execute();
		
		$statement1 = mysqli_prepare($conn, "UPDATE utc SET sigla_utc = '$sigla_introduzida' WHERE id_utc = $id_utc;");
		$statement1->execute();
		
		$statement2 = mysqli_prepare($conn, "UPDATE utc SET id_responsavel = $id_responsavel_selecionado WHERE id_utc = $id_utc;");
		$statement2->execute();
	}
?>