<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['idTurma'], $_POST['idComp'])){

		$id_turma = $_POST['idTurma'];
		$id_componente = $_POST['idComp'];

		//Curso que foi selecionado
		$statement0 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM componente_turma WHERE id_componente = $id_componente AND id_turma = $id_turma;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		while($linha0 = mysqli_fetch_array($resultado0)){
			$count = (int) $linha0['COUNT(id_juncao)'];
		}	
		if($count == 1){
			echo 1;
		}
		else{
			echo 0;
		}
		
	} 
	
?>