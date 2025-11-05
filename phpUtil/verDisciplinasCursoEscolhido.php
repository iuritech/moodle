<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['curso'])){

		$var = $_POST['curso'];

		$statement = mysqli_prepare($conn, "SELECT id_curso FROM curso WHERE nome = '$var';");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id_curso = $linha["id_curso"];

			$statement2 = mysqli_prepare($conn, "SELECT d.nome_uc FROM disciplina d INNER JOIN curso c ON d.id_curso = c.id_curso WHERE c.id_curso = $id_curso;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$nome_uc[] = $linha2["nome_uc"];
			}
		}
		
		$List = implode(', ', $nome_uc);
		print_r($List);

	}
	
?>