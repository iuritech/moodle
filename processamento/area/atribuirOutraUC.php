<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_area']) && isset($_POST['uc_selecionada'])){

		$id_area = $_POST["id_area"];
		$uc_selecionada = $_POST['uc_selecionada'];
	
		$statement = mysqli_prepare($conn, "UPDATE disciplina SET id_area = $id_area WHERE id_disciplina = $uc_selecionada;");
		$statement->execute();
		
	}
?>