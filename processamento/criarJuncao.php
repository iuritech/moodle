<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["array_turmas"]) && isset($_POST["nome_juncao"]) && isset($_POST["id_docente"])){
		
		$array_turmas = $_POST["array_turmas"];
		$nome_juncao = $_POST["nome_juncao"];
		$id_docente = $_POST["id_docente"];
	
		//Inserir na tabela junção
		$statement0 = mysqli_prepare($conn, "INSERT INTO juncao(nome_juncao) VALUES ('$nome_juncao');");
		$statement0->execute();
		
		$id_juncao = mysqli_insert_id($conn);
		
		$loop = 0;
		while($loop < sizeof($array_turmas)){
			
			$id_turma = $array_turmas[$loop];
			$id_componente = $array_turmas[$loop + 1];
			
			$statement1 = "INSERT INTO juncao_componente (id_juncao, id_componente) VALUES ($id_juncao, $id_componente)";
			mysqli_query($conn, $statement1);
			
			$statement2 = "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma;";
			mysqli_query($conn, $statement2);
			
			$loop = $loop + 2;
		}
		
		if($id_docente == 0){
			$statement3 = "UPDATE aula SET id_docente = NULL WHERE id_juncao = $id_juncao;";
			mysqli_query($conn, $statement3);
		}
		else{
			$statement3 = "UPDATE aula SET id_docente = $id_docente WHERE id_juncao = $id_juncao;";
			mysqli_query($conn, $statement3);
		}
	}
	
?>