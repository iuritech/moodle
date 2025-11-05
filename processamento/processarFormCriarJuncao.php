<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

if(isset($_POST['nome_juncao']) && isset($_POST['componentes']) && isset($_POST['turmas'])){

	// Nome da junção
	$nomeJuncao = $_POST['nome_juncao'];

	$statement = mysqli_prepare($conn, "INSERT INTO juncao(nome_juncao,id_bloco) VALUES ('$nomeJuncao',NULL);");
	$statement->execute();
	
	$id_juncao = mysqli_insert_id($conn);

	//Tabela juncao_componente
	$componentes = json_decode($_POST['componentes']);
	$componentes_string = implode($componentes);
	$i = 0;
	while($i < (sizeof($componentes))){
		$statement1 = mysqli_prepare($conn, "INSERT INTO juncao_componente(id_juncao,id_componente) VALUES ('$id_juncao','$componentes[$i]');");
		$statement1->execute(); 
		$i = $i + 1;
	} 
	
	echo $id_juncao;

}