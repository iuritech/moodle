<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
		
	if(isset($_POST['id_curso'])){

		$id_curso = $_POST['id_curso'];
		
		$ids_novos = array();
		
		$statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_curso = $id_curso ORDER BY id_turma;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id = (int) $linha['id_turma'];
			array_push($ids_novos, $id);
		}
		
		$List = implode(",", $ids_novos);
		print_r($List);
		
	}
	
?>