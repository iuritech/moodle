<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id_utc'])){

		$id_utc = $_POST['id_utc'];

		$array_areas = array();

		//Ver as componentes da disciplina
		$statement1 = mysqli_prepare($conn, "SELECT * FROM area WHERE id_utc = $id_utc ORDER BY nome;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			$id_area = $linha1["id_area"];
			$nome_area = $linha1["nome"];
			array_push($array_areas,$id_area);
			array_push($array_areas,$nome_area);
		}
		
		$array_areas_final = implode(",",$array_areas);
		
		echo (json_encode($array_areas_final));
	}
?>