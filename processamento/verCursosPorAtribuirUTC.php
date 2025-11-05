<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_utc"])){
		
		$id_utc = $_POST["id_utc"];
		
		$array_final = array();
		
		$statement01 = mysqli_prepare($conn, "SELECT id_curso FROM curso WHERE id_utc = $id_utc ORDER BY id_curso;");
		$statement01->execute();
		$resultado01 = $statement01->get_result();
		while($linha01 = mysqli_fetch_assoc($resultado01)){			
			$id_curso = $linha01["id_curso"];
			
			$statement2 = mysqli_prepare($conn, "SELECT sigla_completa, nome FROM curso WHERE id_curso = $id_curso;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			$linha2 = mysqli_fetch_assoc($resultado2);
				$sigla_curso = $linha2["sigla_completa"];
				$nome_curso = $linha2["nome"];
				
			$array_disciplinas_total = array();
			$array_disciplinas_por_atribuir = array();
				
			$statement = mysqli_prepare($conn, "SELECT id_disciplina, abreviacao_uc, ano, semestre FROM disciplina WHERE id_curso = $id_curso ORDER BY id_curso, id_disciplina;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_assoc($resultado)){
				$id_disciplina = $linha["id_disciplina"];
				array_push($array_disciplinas_total,$id_disciplina);
				$sigla_disciplina = $linha["abreviacao_uc"];
				$ano = $linha["ano"];
				$semestre = $linha["semestre"];
						
					$array_turmas = array();
						
					$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					while($linha3 = mysqli_fetch_assoc($resultado3)){
						$id_turma = $linha3["id_turma"];
						array_push($array_turmas,$id_turma);
					}
					
					$array_turmas_final = implode(",",$array_turmas);
						
					$array_componentes = array();
							
					$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_disciplina;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					while($linha4 = mysqli_fetch_assoc($resultado4)){
						$id_componente = $linha4["id_componente"];
						array_push($array_componentes,$id_componente);
					}
							
					$array_componentes_final = implode(",",$array_componentes);
							
					//echo $sigla_disciplina, ": ", $array_componentes_final, "<br>", $array_turmas_final, "<br><br>";		
					
					$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_componente IN ($array_componentes_final) AND id_turma IN ($array_turmas_final);");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					$linha5 = mysqli_fetch_assoc($resultado5);
						$count_docente = $linha5["COUNT(id_docente)"];
						
						if($count_docente < (sizeof($array_componentes) * sizeof($array_turmas))){
							//echo $sigla_disciplina, ": ", $count_docente, " ", sizeof($array_componentes), " ", sizeof($array_turmas), "<br>";
							array_push($array_disciplinas_por_atribuir,$id_disciplina);
							//echo $sigla_disciplina, " (", $sigla_curso, ") tem docentes por atribuir!", "<br><br>";
						}
			}
			
			array_push($array_final,$id_curso);
			array_push($array_final,$sigla_curso);
			array_push($array_final,(sizeof($array_disciplinas_total) - sizeof($array_disciplinas_por_atribuir)));
			array_push($array_final,sizeof($array_disciplinas_total));	
			array_push($array_final,$nome_curso);	
		}
		
		$List = json_encode($array_final);
		print_r($List);
		
	}
	
?>