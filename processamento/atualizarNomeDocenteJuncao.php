<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id_juncao']) && isset($_POST['nome']) && isset($_POST['id_docente'])){

		$id_juncao = $_POST['id_juncao'];
		$novo_nome = $_POST['nome'];
		$novo_docente = $_POST['id_docente'];
	
		$statement = mysqli_prepare($conn, "UPDATE juncao SET nome_juncao = '$novo_nome' WHERE id_juncao = $id_juncao;");
		$statement->execute();
		
		if($novo_docente == 0){
			$statement2 = mysqli_prepare($conn, "UPDATE aula SET id_docente = NULL WHERE id_juncao = $id_juncao;");
			$statement2->execute();
		}
		else{
			$statement2 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $novo_docente WHERE id_juncao = $id_juncao;");
			$statement2->execute();
		}

	}
	
?>