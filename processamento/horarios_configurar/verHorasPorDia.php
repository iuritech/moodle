<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if(isset($_POST['id_utc']) && (isset($_POST['semestre_atual']))){	

		$id_utc = $_POST["id_utc"];
		$semestre_atual = $_POST["semestre_atual"];
		
		$array_dados = array();
		
		$statement = mysqli_prepare($conn, "SELECT DISTINCT id_utilizador, nome FROM utilizador WHERE id_utc = $id_utc;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_utilizador = $linha["id_utilizador"];
			$nome_utilizador = $linha["nome"];
			
			$soma_seg_docente = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_utilizador AND h.dia_semana = 'SEG' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
				
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_seg_docente += $numero_horas;
					
					if($soma_seg_docente > 8){
						array_push($array_dados,$nome_utilizador);
						array_push($array_dados," tem mais do que 8 horas na Segunda!");
					}
			}
			
			$soma_ter_docente = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_utilizador AND h.dia_semana = 'TER' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
				
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_ter_docente += $numero_horas;
					
					if($soma_ter_docente > 8){
						array_push($array_dados,$nome_utilizador);
						array_push($array_dados," tem mais do que 8 horas na Terça!");
					}
			}
			
			$soma_qua_docente = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_utilizador AND h.dia_semana = 'QUA' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
				
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_qua_docente += $numero_horas;
					
					if($soma_qua_docente > 8){
						array_push($array_dados,$nome_utilizador);
						array_push($array_dados," tem mais do que 8 horas na Qua!");
					}
			}
			
			$soma_qui_docente = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_utilizador AND h.dia_semana = 'QUI' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
				
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_qui_docente += $numero_horas;
					
					if($soma_qui_docente > 8){
						array_push($array_dados,$nome_utilizador);
						array_push($array_dados," tem mais do que 8 horas na Quinta!");
					}
			}
			
			$soma_sex_docente = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_utilizador AND h.dia_semana = 'SEX' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
			
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_sex_docente += $numero_horas;
					
					if($soma_sex_docente > 8){
						array_push($array_dados,$nome_utilizador);
						array_push($array_dados," tem mais do que 8 horas na Sexta!");
					}
			}
			
		}

		$statement = mysqli_prepare($conn, "SELECT DISTINCT t.id_turma, t.nome, t.ano, c.sigla FROM turma t INNER JOIN curso c ON t.id_curso = c.id_curso WHERE c.id_utc = $id_utc AND t.semestre = $semestre_atual ORDER BY c.id_curso, t.ano, t.semestre, t.nome;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_turma = $linha["id_turma"];
			$nome_turma = $linha["nome"];
			$ano_turma = $linha["ano"];
			$sigla_curso = $linha["sigla"];
			
			$soma_seg_turma = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = 'SEG' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
				
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_seg_turma += $numero_horas;
					
					if($soma_seg_turma > 8){
						array_push($array_dados,$nome_turma . "(" . $ano_turma . "");
						array_push($array_dados," tem mais do que 8 horas na Segunda!");
					}
			}
			
			$soma_ter_turma = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = 'TER' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
				
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_ter_turma += $numero_horas;
					
					if($soma_ter_turma > 8){
						array_push($array_dados,$nome_turma . "(" . $ano_turma . "");
						array_push($array_dados," tem mais do que 8 horas na Terça!");
					}
			}
			
			$soma_qua_turma = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = 'QUA' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
				
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_qua_turma += $numero_horas;
					
					if($soma_qua_turma > 8){
						array_push($array_dados,$nome_turma . "(" . $ano_turma . "");
						array_push($array_dados," tem mais do que 8 horas na Quarta!");
					}
			}
			
			$soma_qui_turma = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = 'QUI' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
				
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_qui_turma += $numero_horas;
					
					if($soma_qui_turma > 8){
						array_push($array_dados,$nome_turma . "(" . $ano_turma . "");
						array_push($array_dados," tem mais do que 8 horas na Quinta!");
					}
			}
			
			$soma_sex_turma = 0;
			
			$statement1 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = 'SEX' AND h.semestre = $semestre_atual;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_horario = $linha1["id_horario"];
				
				$statement2 = mysqli_prepare($conn, "SELECT DISTINCT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_assoc($resultado2);
					$numero_horas = $linha2["numero_horas"];
			
					$soma_sex_turma += $numero_horas;
					
					if($soma_sex_turma > 8){
						array_push($array_dados,$nome_turma . "(" . $ano_turma . "");
						array_push($array_dados," tem mais do que 8 horas na Sexta!");
					}
			}
		}
		/*
		array_push($array_dados,"Sérgio");
		array_push($array_dados," tem mais do que 8 horas na Segunda!");
		
		array_push($array_dados,"Sérgio");
		array_push($array_dados," tem mais do que 8 horas na Segunda!");
		*/
		echo json_encode($array_dados);
		
	}