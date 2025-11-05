<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['nome']) && isset($_POST['ano']) && isset($_POST['semestre']) && isset($_POST['id_curso'])){

		$nome = $_POST['nome'];
		$ano = $_POST['ano'];
		$semestre = $_POST['semestre'];
		$id_curso = $_POST['id_curso'];
	
		$statement = mysqli_prepare($conn, "INSERT INTO turma(id_turma,nome,ano,semestre,id_curso) VALUES (NULL,'$nome',$ano,$semestre,$id_curso);");
		$statement->execute();
		
		$id_turma = mysqli_insert_id($conn);
		
		//Adicionar uma entrada na tabela "aula" para cada turma
		
		$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_disciplina FROM disciplina WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			$id_disciplina = $linha1["id_disciplina"];
			
			$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_disciplina;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$id_componente = $linha2["id_componente"];
				
				$statement3 = mysqli_prepare($conn, "INSERT INTO aula(id_componente,id_horario,id_turma,id_docente,id_juncao) VALUES ($id_componente,NULL,$id_turma,NULL,NULL);");
				$statement3->execute();
			}
			
		}
		
	}
?>