<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

if(isset($_POST['nome_juncao']) && isset($_POST['array_turmas']) && isset($_POST['id_docente'])){

	$nome_juncao = $_POST["nome_juncao"];
	$array_turmas = $_POST["array_turmas"];
	$id_docente = $_POST["id_docente"];
	
	//Inserir na tabela junção
	$statement = mysqli_prepare($conn, "INSERT INTO juncao(nome_juncao) VALUES ('$nome_juncao');");
	$statement->execute();
	
	$id_juncao = mysqli_insert_id($conn);
	
	//Inserir na tabela junção_componente
	$i = 0;
	while($i < sizeof($array_turmas)){
		$id_componente = $array_turmas[$i + 1];
		$statement2 = mysqli_prepare($conn, "INSERT INTO juncao_componente(id_juncao,id_componente) VALUES ($id_juncao,$id_componente);");
		$statement2->execute();
		$i = $i + 2;
	}
	
	//Atualizar a tabela aula
	if($id_docente != 0){
		//Colocar o docente escolhido para todas as turma da nova junção
		$j = 0;
		while($j < sizeof($array_turmas)){
			$id_turma = $array_turmas[$j];
			$id_componente = $array_turmas[$j + 1];
			$statement3 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = $id_juncao WHERE id_turma = $id_turma AND id_componente = $id_componente;");
			$statement3->execute();
			$statement4 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_juncao = $id_juncao;");
			$statement4->execute();
			$j = $j + 2;
		}
	}
	else{
		//Docente null
		$j = 0;
		while($j < sizeof($array_turmas)){
			$id_turma = $array_turmas[$j];
			$id_componente = $array_turmas[$j + 1];
			$statement3 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = $id_juncao WHERE id_turma = $id_turma AND id_componente = $id_componente;");
			$statement3->execute();
			$statement4 = mysqli_prepare($conn, "UPDATE aula SET id_docente = NULL WHERE id_juncao = $id_juncao;");
			$statement4->execute();
			$j = $j + 2;
		}
	}
	
}