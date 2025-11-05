<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_area"])){
		
	$ucs = array();
		
	$id_area = $_POST["id_area"];	
		
	$statement = mysqli_prepare($conn, "SELECT * FROM curso c INNER JOIN disciplina d ON c.id_curso = d.id_curso WHERE d.id_area = $id_area ORDER BY nome_uc;");
	$statement->execute();
	$resultado = $statement->get_result();
	while($linha = mysqli_fetch_array($resultado)){
		$nome = $linha['nome_uc'];
		$sigla = $linha['sigla'];
		$ano = $linha['ano'];
		$sem = $linha['semestre'];
		$final = $sigla . " (" . $ano . "ºA/" . $sem . "ºS) - " . $nome;
/*		$statement2 = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = $id_curso;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_array($resultado2)){
			$sigla_curso = $linha2['sigla'];
			
			$string_final = $sigla_curso . " - " . $nome;
			*/
		array_push($ucs, $final);
	/*	}  */
		
	}
	
		sort($ucs);
		$List = implode(",", $ucs);
		print_r($List);
		
	}
	
?>