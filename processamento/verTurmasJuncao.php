<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_juncao"])){
		
		$id_juncao = $_POST["id_juncao"];
		
		$turmas = array();
				
		$statement = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_juncao = $id_juncao;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id_turma = $linha['id_turma'];
			array_push($turmas,$id_turma);
		}

		$List = implode(",", $turmas);
		print_r($List);
		
	}
	
?>