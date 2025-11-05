<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['disciplina'])){

		$disciplina = trim($_POST['disciplina']);
		
		$statement = mysqli_prepare($conn, "SELECT ano, semestre FROM disciplina WHERE nome_uc = '$disciplina';");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$ano = (int) $linha['ano'];
			$sem = (int) $linha['semestre'];
		}
		
		//echo $ano, "ºA/" , $sem, "ºS";
		echo $ano, "º/", $sem, "º";
	}
	
?>