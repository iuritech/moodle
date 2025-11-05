<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id_juncao'])){

		$id_juncao = $_POST['id_juncao'];
		
		mysqli_query($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao;");
		
		$statement = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao;");
		$statement->execute();
		
		$statement2 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao;");
		$statement2->execute();

	}
	
?>