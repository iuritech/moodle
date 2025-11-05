<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_curso']) && isset($_POST['sigla_introduzida'])){

		$id_curso = $_POST['id_curso'];
		$sigla_introduzida = $_POST['sigla_introduzida'];
		$sigla_introduzida = strtoupper($sigla_introduzida);
	
		$statement = mysqli_prepare($conn, "UPDATE curso SET sigla = '$sigla_introduzida' WHERE id_curso = $id_curso;");
		$statement->execute();

		$statement1 = mysqli_prepare($conn, "SELECT sigla_completa FROM curso WHERE id_curso = $id_curso;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_assoc($resultado1);
			$sigla_completa_curso = $linha1["sigla_completa"];
			
			$sigla_completa_curso_temp = explode(".",$sigla_completa_curso);
			
			$sigla_tipo_curso = $sigla_completa_curso_temp[0];
			$sigla_atual = $sigla_completa_curso_temp[1];
			
			$sigla_completa_curso_final = $sigla_tipo_curso . "." . $sigla_introduzida;
			
			$statement2 = mysqli_prepare($conn, "UPDATE curso SET sigla_completa = '$sigla_completa_curso_final' WHERE id_curso = $id_curso;");
			$statement2->execute();

	}	
?>