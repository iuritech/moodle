<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	$statement = mysqli_prepare($conn, "SELECT MAX(id_turma) FROM turma;");
	$statement->execute();
	$resultado = $statement->get_result();
	while($linha = mysqli_fetch_array($resultado)){
		$id_max = $linha['MAX(id_turma)'];	
	}
		
	echo $id_max;
?>