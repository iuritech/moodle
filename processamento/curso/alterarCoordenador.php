<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_curso']) && isset($_POST['id_novo_coordenador'])){

		$id_curso = $_POST['id_curso'];
		$id_novo_coordenador = $_POST['id_novo_coordenador'];
	
		$statement = mysqli_prepare($conn, "UPDATE curso SET id_coordenador = $id_novo_coordenador WHERE id_curso = $id_curso;");
		$statement->execute();

	}	
?>