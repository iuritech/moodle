<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['ids_ja_na_tabela']) && isset($_POST['id_curso'])){

		$ids = $_POST['ids_ja_na_tabela'];
		$ids_final = implode(",",$ids);
		
		$id_curso = $_POST['id_curso'];
		
		$nomes_novos = array();
		
		$statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_turma NOT IN ($ids_final) AND id_curso = $id_curso ORDER BY id_turma;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$nome = $linha['nome'];
			$ano = $linha['ano'];
			$sem = $linha['semestre'];
			
			$final = $ano . "ºA/" . $sem . "ºS - " . $nome;
			
			array_push($nomes_novos, $final);
		}
		
		$List = implode(",", $nomes_novos);
		print_r($List);
		
	}
	
?>