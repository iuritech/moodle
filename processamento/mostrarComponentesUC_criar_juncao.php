<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_uc"])){
		
		$id_uc = $_POST["id_uc"];
		
		if(!isset($_POST["array_turmas_temp"])){
			
			$componentes = array();
			
			$statement1 = mysqli_prepare($conn, "SELECT ano, semestre, id_curso FROM disciplina WHERE id_disciplina = $id_uc;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			$linha1 = mysqli_fetch_array($resultado1);
				$ano = $linha1["ano"];
				$semestre = $linha1["semestre"];
				$id_curso = $linha1["id_curso"];
								
			$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$id_turma = $linha2["id_turma"];
				
				$statement3 = mysqli_prepare($conn, "SELECT id_componente, id_tipocomponente, numero_horas FROM componente WHERE id_disciplina = $id_uc;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				while($linha3 = mysqli_fetch_array($resultado3)){
					$id_componente = $linha3['id_componente'];
					$id_tipocomponente = $linha3['id_tipocomponente'];
					$numero_horas = $linha3['numero_horas'];
					
					$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					$linha4 = mysqli_fetch_array($resultado4);
						$sigla_tipocomponente = $linha4["sigla_tipocomponente"];

					$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					$linha5 = mysqli_fetch_assoc($resultado5);
						$count_juncao = $linha5["COUNT(id_juncao)"];

						if($count_juncao == 0){
							if(!in_array($id_componente,$componentes)){
								array_push($componentes, $id_componente);
								array_push($componentes, $sigla_tipocomponente);
							}
						} 
				}
			}
			
			
			$List = implode(",", $componentes);
			print_r($List);
			
		}
		
		else{
		
			$componentes = array();
			
			$array_turmas_temp = $_POST["array_turmas_temp"];
			$id_componente_original = $array_turmas_temp[1];
			
			$statement0 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_original;");
			$statement0->execute();
			$resultado0 = $statement0->get_result();
			$linha0 = mysqli_fetch_array($resultado0);
				$numero_horas_original = $linha0["numero_horas"];
			
			$statement01 = mysqli_prepare($conn, "SELECT d.semestre FROM disciplina d INNER JOIN componente c ON 
											d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente_original;");
			$statement01->execute();
			$resultado01 = $statement01->get_result();
			$linha01 = mysqli_fetch_array($resultado01);
				$semestre_original = $linha01['semestre'];
			
			$statement1 = mysqli_prepare($conn, "SELECT ano, semestre, id_curso FROM disciplina WHERE id_disciplina = $id_uc;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			$linha1 = mysqli_fetch_array($resultado1);
				$ano = $linha1["ano"];
				$semestre = $linha1["semestre"];
				$id_curso = $linha1["id_curso"];
								
			$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$id_turma = $linha2["id_turma"];
				
				$statement3 = mysqli_prepare($conn, "SELECT id_componente, id_tipocomponente, numero_horas FROM componente WHERE id_disciplina = $id_uc;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				while($linha3 = mysqli_fetch_array($resultado3)){
					$id_componente = $linha3['id_componente'];
					$id_tipocomponente = $linha3['id_tipocomponente'];
					$numero_horas = $linha3['numero_horas'];
					
					$loop = 0;
					$turma_ja_esta = 0;
					while($loop < sizeof($array_turmas_temp)){
									
						$id_turma_temp = $array_turmas_temp[$loop];
						$id_componente_temp = $array_turmas_temp[$loop + 1];

						if($id_turma_temp == $id_turma/* && $id_componente_temp == $id_componente*/){
							$turma_ja_esta = 1;
						}
											
						$loop = $loop + 2;
					}
					
					$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					$linha4 = mysqli_fetch_array($resultado4);
						$sigla_tipocomponente = $linha4["sigla_tipocomponente"];

					$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					$linha5 = mysqli_fetch_assoc($resultado5);
						$count_juncao = $linha5["COUNT(id_juncao)"];

						if(($numero_horas == $numero_horas_original) && $semestre == $semestre_original && ($count_juncao == 0) && $turma_ja_esta == 0){
							if(!in_array($id_componente,$componentes)){
								array_push($componentes, $id_componente);
								array_push($componentes, $sigla_tipocomponente);
							}
						} 
				}
			}
			
			$List = implode(",", $componentes);
			print_r($List);
		
		}
		
	}
	
?>