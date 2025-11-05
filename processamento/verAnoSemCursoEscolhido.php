<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id_curso'])){

		$id = $_POST['id_curso'];
	
		$turmas = array();
	
		$statement = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_curso = $id AND id_turma ORDER BY ano, semestre, nome;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$nome = $linha['nome'];
			$ano = (int) $linha['ano'];
			$sem = (int) $linha['semestre'];
			
			$final = "(" . $ano . "º/" . $sem . "º) - " . $nome;
			array_push($turmas, $final);
		}
		
		$List = implode(",", $turmas);
		print_r($List);
		
	}
	
?>