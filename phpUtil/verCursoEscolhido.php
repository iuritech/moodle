<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['curso'])){

		$curso = $_POST['curso'];
		
		$statement = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE nome = '$curso';");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$sigla_curso = $linha['sigla'];
		}
		
		echo $sigla_curso;
	}
	
?>