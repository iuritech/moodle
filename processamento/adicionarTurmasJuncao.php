<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_juncao"]) && isset($_POST["array_turmas"])){
		
		$id_juncao = $_POST["id_juncao"];
		$array_turmas = $_POST["array_turmas"];
		
		$statement = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_juncao = $id_juncao;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_array($resultado);
			$id_docente = $linha["id_docente"];
		
		$s = 0;
		while($s < sizeof($array_turmas)){
			$id_turma = $array_turmas[$s];
			$id_componente = $array_turmas[$s + 1];
			
			$sql0 = "INSERT INTO juncao_componente (id_juncao, id_componente) VALUES ($id_juncao, $id_componente);";
			mysqli_query($conn, $sql0);
			
			$sql = "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma;";
			mysqli_query($conn, $sql);
			
			$sql2 = "UPDATE aula SET id_docente = $id_docente WHERE id_componente = $id_componente AND id_turma = $id_turma;";
			mysqli_query($conn, $sql2);

			$s = $s + 2;
		}
		
	}
	
?>