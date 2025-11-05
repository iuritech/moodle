<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_uc"])){
		
		$id_uc = $_POST["id_uc"];
		
		$ano_sem = array();
				
		$statement = mysqli_prepare($conn, "SELECT ano, semestre FROM disciplina WHERE id_disciplina = $id_uc;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_array($resultado);
			$ano = $linha['ano'];
			$semestre = $linha['semestre'];
			
			array_push($ano_sem, $ano);
			array_push($ano_sem, $semestre);

		$List = implode(",", $ano_sem);
		print_r($List);
		
	}
	
?>