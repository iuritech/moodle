<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_uc"]) && isset($_POST["id_juncao"])){
		
		$id_uc = $_POST["id_uc"];
		$id_juncao = $_POST["id_juncao"];
		
		$statement1 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_juncao = $id_juncao;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_array($resultado1);
			$id_componente_original = $linha1['id_componente'];
			
		$statement2 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_original;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_array($resultado2);
			$numero_horas_original = $linha2['numero_horas'];
		
		$statement3 = mysqli_prepare($conn, "SELECT d.semestre FROM disciplina d INNER JOIN componente c ON 
										d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente_original;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		$linha3 = mysqli_fetch_array($resultado3);
			$semestre_original = $linha3['semestre'];
		
		$array_turmas = array();
		
		$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_juncao = $id_juncao;");
		$statement4->execute();
		$resultado4 = $statement4->get_result();
		while($linha4 = mysqli_fetch_array($resultado4)){
			$id_turma = $linha4['id_turma'];
			array_push($array_turmas,$id_turma);
		}
		
		$statement5 = mysqli_prepare($conn, "SELECT ano, id_curso FROM disciplina WHERE id_disciplina = $id_uc;");
		$statement5->execute();
		$resultado5 = $statement5->get_result();
		$linha5 = mysqli_fetch_array($resultado5);
			$ano = $linha5["ano"];
			$id_curso = $linha5["id_curso"];
			
		$componentes = array();
							
		$statement6 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre_original AND id_curso = $id_curso;");
		$statement6->execute();
		$resultado6 = $statement6->get_result();
		while($linha6 = mysqli_fetch_assoc($resultado6)){
			$id_turma = $linha6["id_turma"];
			
			$statement7 = mysqli_prepare($conn, "SELECT id_componente, id_tipocomponente, numero_horas FROM componente WHERE id_disciplina = $id_uc;");
			$statement7->execute();
			$resultado7 = $statement7->get_result();
			while($linha7 = mysqli_fetch_array($resultado7)){
				$id_componente = $linha7['id_componente'];
				$id_tipocomponente = $linha7['id_tipocomponente'];
				$numero_horas = $linha7['numero_horas'];
				
				$statement8 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
				$statement8->execute();
				$resultado8 = $statement8->get_result();
				$linha8 = mysqli_fetch_array($resultado8);
					$sigla_tipocomponente = $linha8["sigla_tipocomponente"];

				$statement9 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
				$statement9->execute();
				$resultado9 = $statement9->get_result();
				$linha9 = mysqli_fetch_assoc($resultado9);
					$count_juncao = $linha9["COUNT(id_juncao)"];
					
					//echo "TURMA: ", $id_turma, " COMP: ", $id_componente, " COUNT_JUNCAO: ", $count_juncao, " TURMA_JA_ESTA: ", $turma_ja_esta, "  /  ";
					
					//echo "COMP: ", $id_componente, " Nº horas: ", $numero_horas, " Turma: ", $id_turma, " count_juncao: ", $count_juncao, " ja_esta: ", $turma_ja_esta, "  /  "; 
					//echo "Nº horas: ", $numero_horas, " Nº horas original: ", $numero_horas_original, " count_juncao: ", $count_juncao, " semestre_temp: ", $semestre_temp, " semestre: ", $semestre, " turma_ja_esta: ", $turma_ja_esta, "  /  "; 

					if(($numero_horas == $numero_horas_original) && ($count_juncao == 0) && (!in_array($id_turma,$array_turmas))){
						if(!in_array($id_componente,$componentes)){
							//echo "COMP: ", $id_componente; 
							//echo "Turma: ", $id_turma, " Comp:", $id_componente, " Count_juncao; ", $count_juncao, "  /  ";
							array_push($componentes, $id_componente);
							array_push($componentes, $sigla_tipocomponente);
						}
					} 
			}
		}
		
		$List = implode(",", $componentes);
		print_r($List);
		
	}
	
?>