<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['disciplina'])){

		$disciplina = trim($_POST['disciplina']);
		
		$statement = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE nome_uc = '$disciplina';");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$sigla = $linha['abreviacao_uc'];
		}
		
		echo $sigla;
	}
	
?>