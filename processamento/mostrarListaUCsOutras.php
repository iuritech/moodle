<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_utc"])){
		
		$id_utc = $_POST["id_utc"];
		
		$ucs = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT * FROM curso c INNER JOIN disciplina d ON c.id_curso = d.id_curso WHERE c.id_utc != $id_utc ORDER BY nome_uc;");
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