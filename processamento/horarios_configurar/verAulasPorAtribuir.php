<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if((isset($_POST['id_utc'])) && (isset($_POST['semestre_atual']))){	

		$id_utc = $_POST["id_utc"];
		$semestre_atual = $_POST["semestre_atual"];
		
		$array_dados = array();
		
		$id_juncao = 0;
		$tipo_juncao = 1;
		
		$statement = mysqli_prepare($conn, "SELECT DISTINCT a.*, t.semestre, c.id_curso FROM aula a INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE c.id_utc = $id_utc AND t.semestre = $semestre_atual AND a.id_docente IS NOT NULL AND a.id_horario IS NULL;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_componente = $linha["id_componente"];
			$id_turma = $linha["id_turma"];
			$id_docente = $linha["id_docente"];
			$id_juncao = $linha["id_juncao"];
			$id_curso = $linha["id_curso"];
					
			$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$id_disciplina = $linha3["id_disciplina"];
				$numero_horas = $linha3["numero_horas"];
				$id_tipocomponente = $linha3["id_tipocomponente"];
						
			$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
						
			$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$abreviacao_disciplina = $linha4["abreviacao_uc"];
					
			$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
			$statement5->execute();
			$resultado5 = $statement5->get_result();
			$linha5 = mysqli_fetch_assoc($resultado5);
				$sigla_curso = $linha5["sigla"];
					
			$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
			$statement6->execute();
			$resultado6 = $statement6->get_result();
			$linha6 = mysqli_fetch_assoc($resultado6);
				$nome_turma = $linha6["nome"];
						
			$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
			$statement7->execute();
			$resultado7 = $statement7->get_result();
			$linha7 = mysqli_fetch_assoc($resultado7);
				$nome_docente = $linha7["nome"];
						
				$nome_docente_temp = explode(" ",$nome_docente);
				if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){							
					$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
					$nome_docente = $nome_temp;
				}
				
			if($id_juncao != null){
				$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
				$statement8->execute();
				$resultado8 = $statement8->get_result();
				$linha8 = mysqli_fetch_assoc($resultado8);
					$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
					
					if($num_componentes_diferentes > 1){
						$tipo_juncao = 2;
					}
					else{
						$tipo_juncao = 1;
					}	
				
			}
			
			/*
			$matriz_horarios_temp = array();
			
			//Docente
			
			$array_dias_semana = array("SEG","TER","QUA","QUI","SEX");
			$loop_dias_semana = 0;
			
			while($loop_dias_semana < sizeof($array_dias_semana)){
				$dia_semana = $array_dias_semana[$loop_dias_semana];
			
				$statement9 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario, h.hora_inicio FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = 'SEG' AND h.semestre = $semestre_atual ORDER BY h.hora_inicio;");
				$statement9->execute();
				$resultado9 = $statement9->get_result();
				while($linha9 = mysqli_fetch_assoc($resultado9)){
					$id_horario_docente = $linha9["id_horario"];
					$hora_inicio_horario_docente = $linha9["hora_inicio"];
					
					$statement10 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_docente;");
					$statement10->execute();
					$resultado10 = $statement10->get_result();
					$linha10 = mysqli_fetch_assoc($resultado10);
						$numero_horas_horario_docente = $linha10["numero_horas"];
						
						echo $id_horario_docente, " - ", $hora_inicio_horario_docente, "<br>";
				
				$loop_dias_semana += 1;
			}
			*/
			$matriz_horarios = 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0 . "_" . 0;
			
			array_push($array_dados,$numero_horas);
			array_push($array_dados,$sigla_curso);
			array_push($array_dados,$abreviacao_disciplina);
			array_push($array_dados,$sigla_tipocomponente);
			array_push($array_dados,$nome_docente);
			array_push($array_dados,$nome_turma);
			array_push($array_dados,$id_juncao);
			array_push($array_dados,$tipo_juncao);
			array_push($array_dados,$matriz_horarios);
			array_push($array_dados,$id_docente);
			array_push($array_dados,$id_turma);
			array_push($array_dados,$id_componente);
			
		}

		echo json_encode($array_dados);
		
	}