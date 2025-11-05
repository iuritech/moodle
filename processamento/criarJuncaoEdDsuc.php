<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["array_turmas"]) && isset($_POST["id_turma"]) && isset($_POST["id_componente"]) && isset($_POST["nome_juncao"])){
		
		$array_turmas = $_POST["array_turmas"];
		$id_turma = $_POST["id_turma"];
		$id_componente = $_POST["id_componente"];
		$nome_juncao = $_POST["nome_juncao"];
	
		//Inserir na tabela junção
		$statement1 = mysqli_prepare($conn, "INSERT INTO juncao(nome_juncao) VALUES ('$nome_juncao');");
		$statement1->execute();
		
		$id_juncao = mysqli_insert_id($conn);
		
		$sql00 = "INSERT INTO juncao_componente (id_juncao, id_componente) VALUES ($id_juncao, $id_componente)";
		mysqli_query($conn, $sql00);
		
		$sql01 = "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma";
		mysqli_query($conn, $sql01);
		
		$statement0 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_assoc($resultado0);
			$num_docente = $linha0["COUNT(id_docente)"];
			
		if($num_docente > 0){
			
			$statement = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$id_docente = $linha["id_docente"];
				
				$sql02 = "UPDATE aula SET id_docente  = $id_docente WHERE id_componente = $id_componente AND id_turma = $id_turma;";
				mysqli_query($conn, $sql02);
			
			$s = 0;
			while($s < sizeof($array_turmas)){
				$id_turma = $array_turmas[$s];
				$id_componente = $array_turmas[$s + 1];
				
				$sql0 = "INSERT INTO juncao_componente (id_juncao, id_componente) VALUES ($id_juncao, $id_componente);";
				mysqli_query($conn, $sql0);
				
				$sql = "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma;";
				mysqli_query($conn, $sql);
				
				$sql2 = "UPDATE aula SET id_docente  = $id_docente WHERE id_componente = $id_componente AND id_turma = $id_turma;";
				if (mysqli_query($conn, $sql2)) {
				  echo "Sucesso";
				} else {
				  echo "Erro: " . mysqli_error($conn);
				}
				
				$s = $s + 2;
			}
			
		}
		
		else{
			
			$s = 0;
			while($s < sizeof($array_turmas)){
				$id_turma = $array_turmas[$s];
				$id_componente = $array_turmas[$s + 1];
				
				$sql0 = "INSERT INTO juncao_componente (id_juncao, id_componente) VALUES ($id_juncao, $id_componente);";
				mysqli_query($conn, $sql0);
				
				$sql = "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma;";
				mysqli_query($conn, $sql);
				
				$sql2 = "UPDATE aula SET id_docente  = NULL WHERE id_componente = $id_componente AND id_turma = $id_turma;";
				if (mysqli_query($conn, $sql2)) {
				  echo "Sucesso";
				} else {
				  echo "Erro: " . mysqli_error($conn);
				}
				
				$s = $s + 2;
			}
			
		}
	}
	
?>