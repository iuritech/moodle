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
	
		//Curso que foi selecionado
		$statement0 = mysqli_prepare($conn, "SELECT id_curso FROM curso WHERE nome = '$curso';");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		while($linha0 = mysqli_fetch_array($resultado0)){
			$id_curso = (int) $linha0['id_curso'];
		}	
		
		//Disciplina que foi selecionada tendo em conta o curso selecionado
		$statement1 = mysqli_prepare($conn, "SELECT id_disciplina FROM disciplina WHERE nome_uc = '$disciplina' AND id_curso = $id_curso;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_array($resultado1)){
			$id_disciplina = (int) $linha1['id_disciplina'];
		}
		
		//echo $id_disciplina;
		
		//Todos os componentes da disciplina selecionada
		
		$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM componente WHERE id_disciplina = $id_disciplina;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_array($resultado2)){
			$id_componente = (int) $linha2['id_componente'];	
			//$componentes = implode(', ', $id_componente);

		
		
		
		//Todas as turmas que pertencem a esses blocos a ao curso escolhido
		$statement4 = mysqli_prepare($conn, "SELECT nome from Turma t LEFT JOIN componente_turma ct ON t.id_turma = ct.id_turma WHERE ct.id_componente = $id_componente && t.id_curso = $id_curso;");
		$statement4->execute();
		$resultado4 = $statement4->get_result();
		while($linha4 = mysqli_fetch_assoc($resultado4)){
			array_push($turmas,$linha4['nome']);			
		}	
		}
		$List = implode(', ', $turmas);
		print_r($List);
		
		

	} 
	
?>