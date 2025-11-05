<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['array_componentes']) && isset($_POST['array_turmas'])){

		$idsComponentes = $_POST['array_componentes'];
		$ids_componentes_final = implode(",",$idsComponentes);
		
		$idsTurmas = $_POST['array_turmas'];
		$ids_turmas_final = implode(",",$idsTurmas);
		
		$aulas = array();
		
		$statement = mysqli_prepare($conn, "SELECT h.dia_semana, h.hora_inicio, h.hora_fim, u.nome, h.id_horario FROM horario h INNER JOIN componente_horario ch ON h.id_horario = ch.id_horario INNER JOIN utilizador u ON ch.id_docente = u.id_utilizador WHERE ch.id_componente IN ($ids_componentes_final) AND ch.id_turma IN ($ids_turmas_final);");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$dia_semana = $linha["dia_semana"];
			$hora_inicio = $linha["hora_inicio"];
			$hora_fim = $linha["hora_fim"];
			$docente = $linha["nome"];
			$id = $linha["id_horario"];	

			$aula = $dia_semana . " : " . $hora_inicio . " - " . $hora_fim . " - " . $docente;

			array_push($aulas,$aula);
		
		}
		
		$List = implode(",", $aulas);
		print_r($List);
		
	}
	
?>