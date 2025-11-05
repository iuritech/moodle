<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['uc'], $_POST['curso'])){

		$curso = $_POST['curso'];
		$uc = $_POST['uc'];
		$disciplina = trim($uc);

		$turmas = array();
		$componentes_ids = array();
	
		//Curso que foi selecionado
		$statement0 = mysqli_prepare($conn, "SELECT id_curso FROM curso WHERE nome = '$curso';");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		while($linha0 = mysqli_fetch_array($resultado0)){
			$id_curso = (int) $linha0['id_curso'];
		}	
		
		//Disciplina que foi selecionada tendo em conta o curso selecionado
		$statement1 = mysqli_prepare($conn, "SELECT ano, semestre FROM disciplina WHERE nome_uc = '$disciplina' AND id_curso = $id_curso;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_array($resultado1);
		
		$ano = (int) $linha1['ano'];
		$semestre = (int) $linha1['semestre'];
		
		//Todas as turmas que pertencem a essas componentes a ao curso escolhido
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT nome from Turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso ORDER BY nome;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			array_push($turmas,$linha2['nome']);			
		}	
		
		$List = implode(', ', $turmas);
		print_r($List);
		
	} 
	
?>