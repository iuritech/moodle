<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_curso"])){
		
		$id_curso = $_POST["id_curso"];
		
		$ucs = array();
				
		$statement = mysqli_prepare($conn, "SELECT id_disciplina, nome_uc, ano, semestre FROM disciplina WHERE id_curso = $id_curso AND semestre = 2 ORDER BY nome_uc;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id_disciplina = $linha['id_disciplina'];
			$nome_disciplina = $linha['nome_uc'];
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
					if(!in_array($id_disciplina,$ucs)){
						array_push($ucs,$id_disciplina);
						array_push($ucs, $nome_disciplina);
					}
				}
			
		}
		
		$List = implode(",", $ucs);
		print_r($List);
		
	}
	
?>