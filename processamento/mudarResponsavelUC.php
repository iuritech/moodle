<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_uc"]) && isset($_POST["id_novo_docente"])){
		
		$id_uc = $_POST["id_uc"];
		$id_novo_docente = $_POST["id_novo_docente"];

		$sql = "UPDATE disciplina SET id_responsavel = $id_novo_docente WHERE id_disciplina = $id_uc";
		if (mysqli_query($conn, $sql)) {
		  echo "Sucesso";
		} else {			  
			echo "Erro: " . mysqli_error($conn);
		}
		
	}
	
?>