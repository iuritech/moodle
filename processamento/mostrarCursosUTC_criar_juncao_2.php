<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_utc"]) && isset($_POST["id_turma"]) && isset($_POST["id_componente"])){
		
		$id_utc = $_POST["id_utc"];
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
		
		//print_r($array_turmas);
		
		$cursos = array();
		
		$statement5 = mysqli_prepare($conn, "SELECT id_curso, nome FROM curso WHERE id_utc = $id_utc ORDER BY nome;");
		$statement5->execute();
		$resultado5 = $statement5->get_result();
		while($linha5 = mysqli_fetch_array($resultado5)){
			$id_curso = $linha5['id_curso'];
			$nome_curso = $linha5['nome'];
			
			$statement6 = mysqli_prepare($conn, "SELECT id_disciplina, ano FROM disciplina WHERE id_curso = $id_curso AND semestre = $semestre_original ORDER BY nome_uc;");
			$statement6->execute();
			$resultado6 = $statement6->get_result();
			while($linha6 = mysqli_fetch_assoc($resultado6)){
				$id_disciplina = $linha6["id_disciplina"];
				$ano = $linha6["ano"];
							
					$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre_original AND id_curso = $id_curso;");
					$statement7->execute();
					$resultado7 = $statement7->get_result();
					while($linha7 = mysqli_fetch_assoc($resultado7)){
						$id_turma_temp = $linha7["id_turma"];
				
						$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_componente, numero_horas FROM componente WHERE id_disciplina = $id_disciplina;");
						$statement8->execute();
						$resultado8 = $statement8->get_result();
						while($linha8 = mysqli_fetch_assoc($resultado8)){
							$id_componente_temp = $linha8["id_componente"];			
							$numero_horas = $linha8["numero_horas"];
							
							$statement9 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente_temp AND id_turma = $id_turma_temp;");
							$statement9->execute();
							$resultado9 = $statement9->get_result();
							$linha9 = mysqli_fetch_assoc($resultado9);
								$count_juncao = $linha9["COUNT(id_juncao)"];
								
								if($count_juncao == 0 && $numero_horas == $numero_horas_original && $id_turma_temp != $id_turma){
									if(!in_array($id_curso,$cursos)){
										array_push($cursos, $id_curso);
										array_push($cursos, $nome_curso);
									}
								}
						}
					}
			}
			
		}
		
		$List = implode(",", $cursos);
		print_r($List);
		
	}
	
?>