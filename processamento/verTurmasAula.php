<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

$id_area_utilizador = (int) $_SESSION['area_utilizador'];

$permAdmin = false;
//isset($_POST['id_area_utilizador']) && 
if(isset($_POST['id_componente']) && isset($_POST['id_docente'])){

	$idComponente = $_POST["id_componente"];
	$idDocente = $_POST["id_docente"];
	
	$arrayDados = array();
	
	$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $idComponente AND id_docente = $idDocente ORDER BY id_turma;");
	$statement8->execute();
	$resultado8 = $statement8->get_result();
	while($linha8 = mysqli_fetch_assoc($resultado8)){ 
		$id_turma = $linha8["id_turma"];
		
		$statement85 = mysqli_prepare($conn, "SELECT COUNT(id_juncao), id_juncao FROM aula WHERE id_turma = $id_turma AND id_componente = $idComponente AND id_docente = $idDocente");
		$statement85->execute();
		$resultado85 = $statement85->get_result();
		$linha85 = mysqli_fetch_assoc($resultado85);
			
		$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
		$statement9->execute();
		$resultado9 = $statement9->get_result();
		$linha9 = mysqli_fetch_assoc($resultado9);
			$nomeTurma = $linha9["nome"];
			$anoTurma = $linha9["ano"];
			$semTurma = $linha9["semestre"];
	
			if($linha85["COUNT(id_juncao)"] > 0){
				$id_juncao = $linha85["id_juncao"];
				array_push($arrayDados,$id_juncao,$nomeTurma,$anoTurma,$semTurma);
			}
			else{
				array_push($arrayDados,NULL,$nomeTurma,$anoTurma,$semTurma);
			}
	}
	
	echo json_encode($arrayDados);
	
}