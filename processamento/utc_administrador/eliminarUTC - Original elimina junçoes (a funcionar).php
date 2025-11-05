<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_GET['id_utc'])){

		$id_utc = $_GET['id_utc'];
		
		$docentes_utc = array();
		$turmas_utc = array();
		$cursos_utc = array();
		$disciplinas_utc = array();
		$componentes_utc = array();
		$turmas_utc = array();
		$juncoes_utc = array();
		$areas_utc = array();
		
		$statement = mysqli_prepare($conn, "SELECT DISTINCT id_utilizador FROM utilizador WHERE id_utc = $id_utc;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_utilizador = $linha["id_utilizador"];
			
			array_push($docentes_utc,$id_utilizador);
		}
		
		$docentes_utc_final = implode(",",$docentes_utc);
		
		$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_curso FROM curso WHERE id_utc = $id_utc;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			$id_curso = $linha1["id_curso"];
			
			array_push($cursos_utc,$id_curso);
		}
		
		$cursos_utc_final = implode(",",$cursos_utc);
		
		if(sizeof($cursos_utc) > 0){
				
			$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE id_curso IN ($cursos_utc_final);");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$id_turma = $linha2["id_turma"];
				
				array_push($turmas_utc,$id_turma);
			}
			
			$turmas_utc_final = implode(",",$turmas_utc);
			
			$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_disciplina FROM disciplina WHERE id_curso IN ($cursos_utc_final);");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			while($linha3 = mysqli_fetch_assoc($resultado3)){
				$id_disciplina = $linha3["id_disciplina"];
				
				array_push($disciplinas_utc,$id_disciplina);
			}
			
			$disciplinas_utc_final = implode(",",$disciplinas_utc);
			
			$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina IN ($disciplinas_utc_final);");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			while($linha4 = mysqli_fetch_assoc($resultado4)){
				$id_componente = $linha4["id_componente"];
				
				array_push($componentes_utc,$id_componente);
			}
			
			$componentes_utc_final = implode(",",$componentes_utc);
			
			$statement5 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE id_curso IN ($cursos_utc_final);");
			$statement5->execute();
			$resultado5 = $statement5->get_result();
			while($linha5 = mysqli_fetch_assoc($resultado5)){
				$id_turma = $linha5["id_turma"];
				
				array_push($turmas_utc,$id_turma);
			}
			
			$turmas_utc_final = implode(",",$turmas_utc);
			
			$statement6 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM juncao_componente WHERE id_componente IN ($componentes_utc_final);");
			$statement6->execute();
			$resultado6 = $statement6->get_result();
			while($linha6 = mysqli_fetch_assoc($resultado6)){
				$id_juncao = $linha6["id_juncao"];
				
				array_push($juncoes_utc,$id_juncao);
			}
			
			$juncoes_utc_final = implode(",",$juncoes_utc);
			
		}
		
		$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_area FROM area WHERE id_utc = $id_utc;");
		$statement7->execute();
		$resultado7 = $statement7->get_result();
		while($linha7 = mysqli_fetch_assoc($resultado7)){
			$id_area = $linha7["id_area"];
			
			array_push($areas_utc,$id_area);
		}
		
		$areas_utc_final = implode(",",$areas_utc);
		
		if(sizeof($cursos_utc) > 0){
				
			if(sizeof($componentes_utc) && sizeof($turmas_utc)){
			
				$statement8 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_componente IN ($componentes_utc_final);");
				$statement8->execute();
				
				$statement9 = mysqli_prepare($conn, "DELETE FROM aula WHERE id_turma IN ($turmas_utc_final);");
				$statement9->execute();
			
			}
			
			if(sizeof($juncoes_utc) > 0){
				
				$statement10 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao IN ($juncoes_utc_final);");
				$statement10->execute();
				
				$statement11 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao IN ($juncoes_utc_final);");
				$statement11->execute();
				
				$statement12 = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao IN ($juncoes_utc_final);");
				$statement12->execute();
			
			}
			
			if(sizeof($turmas_utc) > 0){
				
				$statement13 = mysqli_prepare($conn, "DELETE FROM turma WHERE id_turma IN ($turmas_utc_final);");
				$statement13->execute();
			
			}
			
			if(sizeof($disciplinas_utc) > 0){
				
				$statement14 = mysqli_prepare($conn, "DELETE FROM componente WHERE id_disciplina IN ($disciplinas_utc_final);");
				$statement14->execute();
				
				$statement15 = mysqli_prepare($conn, "DELETE FROM disciplina WHERE id_curso IN ($cursos_utc_final);");
				$statement15->execute();
			
			}
			
		}
		
		if(sizeof($docentes_utc) > 0){
				
			$statement16 = mysqli_prepare($conn, "UPDATE utc SET id_responsavel = 1 WHERE id_responsavel IN ($docentes_utc_final);");
			$statement16->execute();
			
			$statement17 = mysqli_prepare($conn, "UPDATE curso SET id_coordenador = 1 WHERE id_coordenador IN ($docentes_utc_final);");
			$statement17->execute();
		
		}
		
		if(sizeof($cursos_utc) > 0){
		
			$statement18 = mysqli_prepare($conn, "DELETE FROM curso WHERE id_curso IN ($cursos_utc_final);");
			$statement18->execute();
		
		}
		
		if(sizeof($areas_utc) > 0){
			
			$statement19 = mysqli_prepare($conn, "DELETE FROM area WHERE id_area IN ($areas_utc_final);");
			$statement19->execute();
		
		}
		
		if(sizeof($docentes_utc) > 0){
			
			$statement20 = mysqli_prepare($conn, "UPDATE disciplina SET id_responsavel = 1 WHERE id_responsavel IN ($docentes_utc_final);");
			$statement20->execute();
			
			$statement21 = mysqli_prepare($conn, "UPDATE curso SET id_coordenador = 1 WHERE id_coordenador IN ($docentes_utc_final);");
			$statement21->execute();
			
			$statement22 = mysqli_prepare($conn, "DELETE FROM utilizador WHERE id_utilizador IN ($docentes_utc_final);");
			$statement22->execute();
			
		}
		
		$statement23 = mysqli_prepare($conn, "UPDATE utc SET id_responsavel = 1 WHERE id_utc = $id_utc;");
		$statement23->execute();
		
		$statement23 = mysqli_prepare($conn, "DELETE FROM utc WHERE id_utc = $id_utc;");
		$statement23->execute();
	}
?>