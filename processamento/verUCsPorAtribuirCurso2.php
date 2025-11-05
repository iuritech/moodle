<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_curso"])){
		
		$id_curso = $_POST["id_curso"];
		
		$array_final = array();
	
		$statement2 = mysqli_prepare($conn, "SELECT sigla_completa FROM curso WHERE id_curso = $id_curso;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_assoc($resultado2);
			$sigla_curso = $linha2["sigla_completa"];
				
		$array_disciplinas_por_atribuir = array();
				
		$statement = mysqli_prepare($conn, "SELECT id_disciplina, abreviacao_uc, ano, semestre FROM disciplina WHERE id_curso = $id_curso AND semestre = 2 ORDER BY id_curso, id_disciplina;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_disciplina = $linha["id_disciplina"];
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
						array_push($array_disciplinas_por_atribuir,$id_disciplina);
					}
		}
		
		$counter = 0;
		while($counter < sizeof($array_disciplinas_por_atribuir)){
			$id_disciplina = $array_disciplinas_por_atribuir[$counter];
			
			$statement6 = mysqli_prepare($conn, "SELECT nome_uc, codigo_uc, id_responsavel, id_area FROM disciplina WHERE id_disciplina = $id_disciplina;");
			$statement6->execute();
			$resultado6 = $statement6->get_result();
			$linha6 = mysqli_fetch_assoc($resultado6);
				$nome_disciplina = $linha6["nome_uc"];
				$codigo_disciplina = $linha6["codigo_uc"];
				$id_responsavel = $linha6["id_responsavel"];
				$id_area = $linha6["id_area"];
				
				$statement7 = mysqli_prepare($conn, "SELECT imagem_perfil, nome FROM utilizador WHERE id_utilizador = $id_responsavel;");
				$statement7->execute();
				$resultado7 = $statement7->get_result();
				$linha7 = mysqli_fetch_assoc($resultado7);
					$imagem_perfil_responsavel = $linha7["imagem_perfil"];
					$nome_responsavel = $linha7["nome"];
					
					array_push($array_final, $id_disciplina);
					array_push($array_final, $nome_disciplina);
					array_push($array_final, $codigo_disciplina);
					array_push($array_final, $imagem_perfil_responsavel);
					array_push($array_final, $nome_responsavel);
					array_push($array_final, $id_area);
					
					$statement8 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
					$statement8->execute();
					$resultado8 = $statement8->get_result();
					$linha8 = mysqli_fetch_assoc($resultado8);
						$id_utc = $linha8["id_utc"];
					
					$statement9 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc;");
					$statement9->execute();
					$resultado9 = $statement9->get_result();
					$linha9 = mysqli_fetch_assoc($resultado9);
						$id_responsavel_utc_disciplina = $linha9["id_responsavel"];
					
					array_push($array_final, $id_responsavel_utc_disciplina);
					array_push($array_final, $semestre);
					
			$counter = $counter + 1;
		}

		
		$List = json_encode($array_final);
		print_r($List);
		
	}
	
?>