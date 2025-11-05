<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_area']) && isset($_POST['ucs_selecionadas'])){

		$id_area = $_POST["id_area"];
		$ucs_selecionadas = $_POST['ucs_selecionadas'];
	
		//Disciplinas
		$counter_uc = 0;
		while($counter_uc < sizeof($ucs_selecionadas)){
			
			$id_uc = $ucs_selecionadas[$counter_uc];
			
			$statement = mysqli_prepare($conn, "UPDATE disciplina SET id_area = $id_area WHERE id_disciplina = $id_uc;");
			$statement->execute();
			
			$counter_uc += 1;
		}
		
	}
?>