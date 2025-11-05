<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_uc"])){
		
		$id_uc = $_POST["id_uc"];
		
		$componentes = array();
		
		if(!isset($_POST["array_turmas"])){
		
			$statement0 = mysqli_prepare($conn, "SELECT ano, semestre, id_curso FROM disciplina WHERE id_disciplina = $id_uc;");
			$statement0->execute();
			$resultado0 = $statement0->get_result();
			$linha0 = mysqli_fetch_array($resultado0);
				$ano = $linha0["ano"];
				$semestre = $linha0["semestre"];
				$id_curso = $linha0["id_curso"];
								
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_turma = $linha1["id_turma"];
				
				$statement2 = mysqli_prepare($conn, "SELECT id_componente, id_tipocomponente, numero_horas FROM componente WHERE id_disciplina = $id_uc;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				while($linha2 = mysqli_fetch_array($resultado2)){
					$id_componente = $linha2['id_componente'];
					$id_tipocomponente = $linha2['id_tipocomponente'];
					$numero_horas = $linha2['numero_horas'];
					
					$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_array($resultado3);
						$sigla_tipocomponente = $linha3["sigla_tipocomponente"];

					$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					$linha5 = mysqli_fetch_assoc($resultado5);
						$count_docente = $linha5["COUNT(id_docente)"];

						if($count_docente == 0){
							if(!in_array($id_componente,$componentes)){
								array_push($componentes,$id_componente);
								array_push($componentes, $sigla_tipocomponente);
							}
						}
				}
			}
			
			$List = implode(",", $componentes);
			print_r($List);
		
		}
		else{
			
			$array_turmas = $_POST["array_turmas"];
			
			$id_componente_original = $array_turmas[0];
			
			$array_ids_turmas = array();
			
			$loop = 0;
			while($loop < sizeof($array_turmas)){
				$id_turma = $array_turmas[$loop + 1];
				
				array_push($array_ids_turmas,$id_turma);
				
				$loop = $loop + 2;
			}
			
			$statement0 = mysqli_prepare($conn, "SELECT ano, semestre, id_curso FROM disciplina WHERE id_disciplina = $id_uc;");
			$statement0->execute();
			$resultado0 = $statement0->get_result();
			$linha0 = mysqli_fetch_array($resultado0);
				$ano = $linha0["ano"];
				$semestre = $linha0["semestre"];
				$id_curso = $linha0["id_curso"];
								
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_turma = $linha1["id_turma"];
				
				$statement2 = mysqli_prepare($conn, "SELECT id_componente, id_tipocomponente, numero_horas FROM componente WHERE id_disciplina = $id_uc;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				while($linha2 = mysqli_fetch_array($resultado2)){
					$id_componente = $linha2['id_componente'];
					$id_tipocomponente = $linha2['id_tipocomponente'];
					$numero_horas = $linha2['numero_horas'];
					
					$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_array($resultado3);
						$sigla_tipocomponente = $linha3["sigla_tipocomponente"];

					$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					$linha5 = mysqli_fetch_assoc($resultado5);
						$count_docente = $linha5["COUNT(id_docente)"];

						$componentes_turma = array();

						$counter = 0;
						while($counter < sizeof($array_turmas)){
							$id_turma_temp = $array_turmas[$counter + 1];
							
							if($id_turma_temp == $id_turma){
								array_push($componentes_turma,$array_turmas[$counter]);
							}
							
							$counter += 2;
						}

						if($count_docente == 0){
							if((!in_array($id_turma,$array_turmas)) || (in_array($id_turma,$array_turmas) && (!in_array($id_componente,$componentes_turma))) ){
								if(!in_array($id_componente,$componentes)){
									array_push($componentes,$id_componente);
									array_push($componentes, $sigla_tipocomponente);
								}
							}
						}
				}
			}
			
			$List = implode(",", $componentes);
			print_r($List);
			
		}
		
	}
	
?>