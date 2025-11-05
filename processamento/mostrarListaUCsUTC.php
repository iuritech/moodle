<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_area"]) && isset($_POST["id_utc"])){
		
		$id_area = $_POST["id_area"];
		$id_utc = $_POST["id_utc"];
		
		$ucs_ja_no_dropdown = array();
		
		$statement = mysqli_prepare($conn, "SELECT * FROM curso c INNER JOIN disciplina d ON c.id_curso = d.id_curso WHERE d.id_area = $id_area ORDER BY nome_uc;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id_disciplina = $linha["id_disciplina"];
			array_push($ucs_ja_no_dropdown, $id_disciplina);
		}  
		
		$ucs_ja_no_dropdown_final = implode(",",$ucs_ja_no_dropdown);
	
		$ucs = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT * FROM curso c INNER JOIN disciplina d ON c.id_curso = d.id_curso WHERE c.id_utc = $id_utc AND d.id_disciplina NOT IN ($ucs_ja_no_dropdown_final) ORDER BY nome_uc;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_array($resultado2)){
			$nome = $linha2['nome_uc'];
			$sigla = $linha2['sigla'];
			$ano = $linha2['ano'];
			$sem = $linha2['semestre'];
			$final = $sigla . " (" . $ano . "ºA/" . $sem . "ºS) - " . $nome;

			array_push($ucs, $final);
			
		}
		
		sort($ucs);
		$List = implode(",", $ucs);
		print_r($List);
	
	}
	
?>