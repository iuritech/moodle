<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['codigo'])){

		$codigo = $_POST['codigo'];
		$codigo_ja_usado = 0;
		
		$statement = mysqli_prepare($conn, "SELECT DISTINCT codigo_uc FROM disciplina;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$codigo_temp = $linha["codigo_uc"];
			if($codigo_temp == $codigo){
				$codigo_ja_usado = 1;
			}
		}

		echo $codigo_ja_usado;
	}
?>