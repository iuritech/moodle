<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if(isset($_POST['id_docente'])){

		$id_docente = $_POST['id_docente'];
		$array_final = array();
	
		$statement = mysqli_prepare($conn, "SELECT COUNT(id_disciplina) FROM disciplina WHERE id_responsavel = $id_docente;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$num_disciplinas_responsavel = $linha["COUNT(id_disciplina)"];
			
			if($num_disciplinas_responsavel == 0){
				array_push($array_final,0);
			}
			else{
				array_push($array_final,1);
			}
			
		$statement1 = mysqli_prepare($conn, "SELECT COUNT(id_curso) FROM curso WHERE id_coordenador = $id_docente;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_assoc($resultado1);
			$num_cursos_responsavel = $linha1["COUNT(id_curso)"];	
			
			if($num_cursos_responsavel == 0){
				array_push($array_final,0);
			}
			else{
				array_push($array_final,1);
			}
		
			$array_final_final = implode(",",$array_final);
			print_r($array_final_final);
		
	}
?>