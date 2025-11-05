<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
		$nomes = array();
		
		$statement = mysqli_prepare($conn, "SELECT * FROM utilizador ORDER BY nome;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$nome = $linha['nome'];
			array_push($nomes, $nome);
		}
		
		$List = implode(",", $nomes);
		print_r($List);
		
	
	
?>