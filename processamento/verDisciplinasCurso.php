<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id_curso']) && isset($_POST['ids_ja'])){

		$id_curso = $_POST['id_curso'];
		$ids_ja = $_POST['ids_ja'];
		$ids_ja_final = implode(",",$ids_ja);

		$array_dados = array();

		$statement1 = mysqli_prepare($conn, "SELECT id_disciplina, nome_uc, ano, semestre FROM disciplina WHERE id_curso = $id_curso AND id_disciplina NOT IN ($ids_ja_final) ORDER BY ano, semestre;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			$id_disciplina = $linha1["id_disciplina"];
			$nome_uc = $linha1["nome_uc"];
			$ano = $linha1["ano"];
			$semestre = $linha1["semestre"];
	
			array_push($array_dados,$id_disciplina);
			array_push($array_dados,$nome_uc);
			array_push($array_dados,$ano);
			array_push($array_dados,$semestre);
		}
		
		echo json_encode($array_dados);

	}
?>