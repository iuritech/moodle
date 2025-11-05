<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['nome_bd_selecionada'])){

		$nome_bd_selecionada = $_POST['nome_bd_selecionada'];
		
		$query = mysqli_select_db($conn,"apoioutc_ano_atual");

		$statement = mysqli_prepare($conn, "UPDATE ano_atual SET ano_atual = '$nome_bd_selecionada';");
		$statement->execute();
		
		$query = mysqli_select_db($conn,$_SESSION['bd']);

	}
?>