<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_area']) && isset($_POST['nome_introduzido'])){

		$id_area = $_POST["id_area"];
		$nome_introduzido = $_POST['nome_introduzido'];
	
		$statement = mysqli_prepare($conn, "UPDATE area SET nome_completos = '$nome_introduzido' WHERE id_area = $id_area;");
		$statement->execute();
		
	}
?>