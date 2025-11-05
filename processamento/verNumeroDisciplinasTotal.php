<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	$array_final = array();
		
	$counter_disciplinas = 0;
	
	$statement = mysqli_prepare($conn, "SELECT * FROM curso ORDER BY id_curso");
	$statement->execute();
	$resultado1 = $statement->get_result();
	while($linha1 = mysqli_fetch_assoc($resultado1)){
		$idCurso = $linha1["id_curso"];
				
		//Obter disciplinas do curso
		$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_curso = $idCurso ORDER BY id_disciplina");
		$statement->execute();
		$resultado2 = $statement->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$idDisciplina = $linha2["id_disciplina"];
			
			array_push($array_final, $idDisciplina);
			
			$counter_disciplinas = $counter_disciplinas + 1;
		}
	}
	
	echo json_encode($array_final);
	
?>