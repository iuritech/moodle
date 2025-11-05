<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id_disciplina']) && isset($_POST['id_componente'])){

		$id_disciplina = $_POST['id_disciplina'];
		$id_componente = $_POST['id_componente'];

		$array_dados = array();

		$statement0 = mysqli_prepare($conn, "SELECT ano, semestre, id_curso FROM disciplina WHERE id_disciplina = $id_disciplina;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_assoc($resultado0);
			$ano_disciplina = $linha0["ano"];
			$semestre_disciplina = $linha0["semestre"];
			$id_curso = $linha0["id_curso"];

		$statement1 = mysqli_prepare($conn, "SELECT id_turma, nome FROM turma WHERE ano = $ano_disciplina AND semestre = $semestre_disciplina AND id_curso = $id_curso");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			$id_turma = $linha1["id_turma"];
			$nome_turma = $linha1["nome"];
			
			$statement2 = mysqli_prepare($conn, "SELECT count(DISTINCT id_docente) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			$linha2 = mysqli_fetch_assoc($resultado2);
				$num_docentes = $linha2["count(DISTINCT id_docente)"];
	
			array_push($array_dados,$id_turma);
			array_push($array_dados,$nome_turma);
			
			if($num_docentes > 0){
				
				$statement3 = mysqli_prepare($conn, "SELECT u.nome FROM utilizador u INNER JOIN aula a ON u.id_utilizador = a.id_docente WHERE a.id_componente = $id_componente AND a.id_turma = $id_turma;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				$linha3 = mysqli_fetch_assoc($resultado3);
					$nome_docente = $linha3["nome"];
						
					array_push($array_dados,$nome_docente);
				}
			else{
				array_push($array_dados,0);
			}
			//array_push($array_dados,$num_docentes);
		}
		
		echo json_encode($array_dados);

	}
?>