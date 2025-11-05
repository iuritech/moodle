<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_area']) && isset($_POST['docente_selecionado'])){

		$id_area = $_POST["id_area"];
		$docente_selecionado = $_POST['docente_selecionado'];
	
		$statement = mysqli_prepare($conn, "UPDATE utilizador SET id_area = $id_area WHERE id_utilizador = $docente_selecionado;");
		$statement->execute();
		
	}
?>