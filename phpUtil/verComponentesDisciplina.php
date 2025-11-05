<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['uc'])){

		$uc = $_POST['uc'];
		$curso = $_POST['curso'];
		$disciplina = trim($uc);
		
		$statement = mysqli_prepare($conn, "SELECT id_curso FROM curso WHERE nome = '$curso';");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id_curso = $linha['id_curso'];
		}

		$statement1 = mysqli_prepare($conn, "SELECT id_disciplina FROM disciplina WHERE nome_uc = '$disciplina' AND id_curso = $id_curso;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_array($resultado1)){
			$id_disciplina = $linha1['id_disciplina'];
		}
	
		//echo $id_disciplina;
	
		$statement2 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente tc INNER JOIN componente c ON tc.id_tipocomponente = c.id_tipocomponente WHERE c.id_disciplina = $id_disciplina;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_array($resultado2)){
			$tipocomponentes[] = $linha2["sigla_tipocomponente"];
		}
		
		$List = implode(', ', $tipocomponentes);
		print_r($List);

	}
	
?>