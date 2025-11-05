<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id_juncao'])){

		$id_juncao = $_POST['id_juncao'];
	
		$statement1 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao;");
		$statement1->execute();
		
		$statement2 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao;");
		$statement2->execute();
			
		$statement3 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao;");
		$statement3->execute();
			
	}
	
?>