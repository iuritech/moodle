<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_area']) && isset($_POST['docentes_selecionados'])){

		$id_area = $_POST["id_area"];
		$docentes_selecionados = $_POST['docentes_selecionados'];
	
		//Docentes
		$counter_docentes = 0;
		while($counter_docentes < sizeof($docentes_selecionados)){
			
			$id_docente = $docentes_selecionados[$counter_docentes];
			
			$statement = mysqli_prepare($conn, "UPDATE utilizador SET id_area = $id_area WHERE id_utilizador = $id_docente;");
			$statement->execute();
			
			$counter_docentes += 1;
		}
		
	}
?>