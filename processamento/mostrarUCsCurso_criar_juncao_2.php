<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_curso"]) && isset($_POST["id_turma"]) && isset($_POST["id_componente"])){
		
		$id_curso = $_POST["id_curso"];
		$id_turma = $_POST["id_turma"];
		$id_componente = $_POST["id_componente"];
		
		$statement2 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_array($resultado2);
			$numero_horas_original = $linha2['numero_horas'];
			
		$statement3 = mysqli_prepare($conn, "SELECT d.semestre FROM disciplina d INNER JOIN componente c ON 
										d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		$linha3 = mysqli_fetch_array($resultado3);
			$semestre_original = $linha3['semestre'];
		
		$disciplinas = array();
		
		$statement5 = mysqli_prepare($conn, "SELECT id_disciplina, nome_uc, abreviacao_uc, ano FROM disciplina WHERE semestre = $semestre_original AND id_curso = $id_curso ORDER BY nome_uc;");
		$statement5->execute();
		$resultado5 = $statement5->get_result();
		while($linha5 = mysqli_fetch_assoc($resultado5)){
			$id_disciplina = $linha5["id_disciplina"];
			$nome_disciplina = $linha5["nome_uc"];
			$ano = $linha5["ano"];
							
			$statement6 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre_original AND id_curso = $id_curso;");
			$statement6->execute();
			$resultado6 = $statement6->get_result();
			while($linha6 = mysqli_fetch_assoc($resultado6)){
				$id_turma_temp = $linha6["id_turma"];
						
				$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_componente, numero_horas FROM componente WHERE id_disciplina = $id_disciplina;");
				$statement7->execute();
				$resultado7 = $statement7->get_result();
				while($linha7 = mysqli_fetch_assoc($resultado7)){
					$id_componente_temp = $linha7["id_componente"];
					$numero_horas = $linha7["numero_horas"];
					
					$statement8 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente_temp AND id_turma = $id_turma_temp;");
					$statement8->execute();
					$resultado8 = $statement8->get_result();
					$linha8 = mysqli_fetch_assoc($resultado8);
					$count_juncao = $linha8["COUNT(id_juncao)"];
									
					if($count_juncao == 0 && $numero_horas == $numero_horas_original && $id_turma_temp != $id_turma){
						if(!in_array($id_disciplina,$disciplinas)){
							array_push($disciplinas,$id_disciplina);
							array_push($disciplinas,$nome_disciplina);
						}
					}
				}	
			}		
		}
		
		$List = implode(",", $disciplinas);
		print_r($List);
		
	}
	
?>