<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_uc"]) && isset($_POST["id_turma"]) && isset($_POST["id_componente"])){
		
		$id_uc = $_POST["id_uc"];
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
			$id_turma_temp = $linha6["id_turma"];
			
			$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_componente, id_tipocomponente, numero_horas FROM componente WHERE id_disciplina = $id_uc ORDER BY id_componente;");
			$statement7->execute();
			$resultado7 = $statement7->get_result();
			while($linha7 = mysqli_fetch_array($resultado7)){
				$id_componente_temp = $linha7['id_componente'];
				$id_tipocomponente = $linha7['id_tipocomponente'];
				$numero_horas = $linha7['numero_horas'];
				
				$statement8 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
				$statement8->execute();
				$resultado8 = $statement8->get_result();
				$linha8 = mysqli_fetch_array($resultado8);
					$sigla_tipocomponente = $linha8["sigla_tipocomponente"];

				$statement9 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente_temp AND id_turma = $id_turma_temp;");
				$statement9->execute();
				$resultado9 = $statement9->get_result();
				$linha9 = mysqli_fetch_assoc($resultado9);
					$count_juncao = $linha9["COUNT(id_juncao)"];

					if(($numero_horas == $numero_horas_original) && ($count_juncao == 0) && ($id_turma_temp != $id_turma)){
						if(!in_array($id_componente_temp,$componentes)){
							//echo "TURMA: ", $id_turma_temp, " COMP: ", $id_componente_temp, " COUNT_JUNCAO: ", $count_juncao, "  /  ";
							array_push($componentes, $id_componente_temp);
							array_push($componentes, $sigla_tipocomponente);
						}
					} 
			}
		}
		
		$List = implode(",", $componentes);
		print_r($List);
		
	}
	
?>