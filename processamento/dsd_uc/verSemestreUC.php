<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
		
	if(isset($_POST['id_uc'])){

		$id_uc = $_POST['id_uc'];
	
		$statement = mysqli_prepare($conn, "SELECT semestre FROM disciplina WHERE id_disciplina = $id_uc;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_array($resultado);
			$semestre = $linha['semestre'];
			
			echo $semestre;
	}
	
?>