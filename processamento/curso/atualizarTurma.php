<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_turma']) && isset($_POST['nome_introduzido'])){

		$id_turma = $_POST['id_turma'];
		$nome_introduzido = $_POST['nome_introduzido'];
		
		$statement = mysqli_prepare($conn, "UPDATE turma SET nome = '$nome_introduzido' WHERE id_turma = $id_turma;");
		$statement->execute();		
	
	}
?>