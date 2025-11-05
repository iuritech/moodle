<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
		if(isset($_POST['sigla'])){
		
			$sigla = $_POST['sigla'];
			
			$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE sigla = '$sigla';");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_array($resultado)){
				$nome = $linha['nome'];
			}
			
		echo $nome;
		
		}
?>