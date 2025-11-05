<?php
// Página de visualização do horário de uma sala

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}

if(!isset($_GET["id_docente"])){
	header("Location: visHorariosDocente.php");
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$id_utilizador_atual = $_SESSION['id'];

$statement = mysqli_prepare($conn, "SELECT id_utc, is_admin FROM utilizador WHERE id_utilizador = $id_utilizador_atual;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc_utilizador_atual = $linha["id_utc"];
	$is_admin = $linha["is_admin"];


$id_docente = $_GET["id_docente"];
$semestre = $_GET["sem"];

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_docente;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_docente = $linha["nome"];
	$id_utc_docente = $linha["id_utc"];
	
if(($id_utc_utilizador_atual != $id_utc_docente) && ($is_admin == 0)){
	header("Location: visHorariosDocente.php?sem=$semestre");
}	
	
$altura_final = 540;
	
$offset_seg = 0;
$offset_ter = 0;
$offset_qua = 0;
$offset_qui = 0;
$offset_sex = 0;

$offset_vertical_seg = 0;
$offset_vertical_ter = 0;
$offset_vertical_qua = 0;
$offset_vertical_qui = 0;
$offset_vertical_sex = 0;

$dias_semana = array('SEG','TER','QUA','QUI','SEX');
$offsets_semana = array($offset_seg,$offset_ter,$offset_qua,$offset_qui,$offset_sex);
$offsets_verticais = array($offset_vertical_seg,$offset_vertical_ter,$offset_vertical_qua,$offset_vertical_qui,$offset_vertical_sex);

$max_turmas_08_30 = 1;
$max_turmas_09_00 = 1;
$max_turmas_09_30 = 1;
$max_turmas_10_00 = 1;
$max_turmas_10_30 = 1;
$max_turmas_11_00 = 1;
$max_turmas_11_30 = 1;
$max_turmas_12_00 = 1;
$max_turmas_12_30 = 1;
$max_turmas_13_00 = 1;
$max_turmas_13_30 = 1;
$max_turmas_14_00 = 1;
$max_turmas_14_30 = 1;
$max_turmas_15_00 = 1;
$max_turmas_15_30 = 1;
$max_turmas_16_00 = 1;
$max_turmas_16_30 = 1;
$max_turmas_17_00 = 1;
$max_turmas_17_30 = 1;
$max_turmas_18_00 = 1;
$max_turmas_18_30 = 1;

$altura_08_30 = 45;
$altura_09_30 = 45;
$altura_10_30 = 45;
$altura_11_30 = 45;
$altura_12_30 = 45;
$altura_13_30 = 45;
$altura_14_30 = 45;
$altura_15_30 = 45;
$altura_16_30 = 45;
$altura_17_30 = 45;
$altura_18_30 = 45;

$horas_max_13_00 = 0;

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '08:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_08_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_08_30 > 0){
		
		$horarios_08_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '08:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_08_30 = $linha2["id_horario"];
			
			array_push($horarios_08_30,$id_horario_08_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_08_30)){
			
			$id_horario_temp = $horarios_08_30[$loop_max];
			
			$altura_08_30_max_temp = 45;
			$altura_09_30_max_temp = 45;
			$altura_10_30_max_temp = 45;
			$altura_11_30_max_temp = 45;
			$altura_12_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_08_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_08_30_max_temp += 30;
						$altura_09_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 95;
						$altura_09_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 162;
						$altura_09_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 228;
						$altura_09_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 295;
						$altura_09_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 53;
						$altura_09_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 95;
						$altura_09_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 135;
						$altura_09_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 180;
						$altura_09_30_max_temp += 180;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 35;
						$altura_09_30_max_temp += 35;
						$altura_10_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 72;
						$altura_09_30_max_temp += 72;
						$altura_10_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 108;
						$altura_09_30_max_temp += 108;
						$altura_10_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 145;
						$altura_09_30_max_temp += 145;
						$altura_10_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 22;
						$altura_09_30_max_temp += 22;
						$altura_10_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 47;
						$altura_09_30_max_temp += 47;
						$altura_10_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 75;
						$altura_09_30_max_temp += 75;
						$altura_10_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 104;
						$altura_09_30_max_temp += 104;
						$altura_10_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 10;
						$altura_09_30_max_temp += 10;
						$altura_10_30_max_temp += 10;
						$altura_11_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 35;
						$altura_09_30_max_temp += 35;
						$altura_10_30_max_temp += 35;
						$altura_11_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 61;
						$altura_09_30_max_temp += 61;
						$altura_10_30_max_temp += 61;
						$altura_11_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 86;
						$altura_09_30_max_temp += 86;
						$altura_10_30_max_temp += 86;
						$altura_11_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 23;
						$altura_09_30_max_temp += 23;
						$altura_10_30_max_temp += 23;
						$altura_11_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 45;
						$altura_09_30_max_temp += 45;
						$altura_10_30_max_temp += 45;
						$altura_11_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 67;
						$altura_09_30_max_temp += 67;
						$altura_10_30_max_temp += 67;
						$altura_11_30_max_temp += 67;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 17;
						$altura_09_30_max_temp += 17;
						$altura_10_30_max_temp += 17;
						$altura_11_30_max_temp += 17;
						$altura_12_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 37;
						$altura_09_30_max_temp += 37;
						$altura_10_30_max_temp += 37;
						$altura_11_30_max_temp += 37;
						$altura_12_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 56;
						$altura_09_30_max_temp += 56;
						$altura_10_30_max_temp += 56;
						$altura_11_30_max_temp += 56;
						$altura_12_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 10;
						$altura_09_30_max_temp += 10;
						$altura_10_30_max_temp += 10;
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 27;
						$altura_09_30_max_temp += 27;
						$altura_10_30_max_temp += 27;
						$altura_11_30_max_temp += 27;
						$altura_12_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 44;
						$altura_09_30_max_temp += 44;
						$altura_10_30_max_temp += 44;
						$altura_11_30_max_temp += 44;
						$altura_12_30_max_temp += 44;
					}
				}
				
				if($altura_08_30_max_temp > $altura_08_30){
					$offset_08_30 = $altura_08_30_max_temp - $altura_08_30;
					$altura_08_30 = $altura_08_30_max_temp;
					$altura_final += $offset_08_30;
				}
				
				if($altura_09_30_max_temp > $altura_09_30){
					$offset_09_30 = $altura_09_30_max_temp - $altura_09_30;
					$altura_09_30 = $altura_09_30_max_temp;
					$altura_final += $offset_09_30;
				}
				
				if($altura_10_30_max_temp > $altura_10_30){
					$offset_10_30 = $altura_10_30_max_temp - $altura_10_30;
					$altura_10_30 = $altura_10_30_max_temp;
					$altura_final += $offset_10_30;
				}				

				if($altura_11_30_max_temp > $altura_11_30){
					$offset_11_30 = $altura_11_30_max_temp - $altura_11_30;
					$altura_11_30 = $altura_11_30_max_temp;
					$altura_final += $offset_11_30;
				}	

				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

			$loop_max += 1;
		}
		
	}
	
$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '09:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_09_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_09_00 > 0){
		
		$horarios_09_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '09:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_09_00 = $linha2["id_horario"];
			
			array_push($horarios_09_00,$id_horario_09_00);
		}
		
		$altura_08_30_max = 45;
		$altura_09_30_max = 45;
		$altura_10_30_max = 45;
		$altura_11_30_max = 45;
		$altura_12_30_max = 45;
		$altura_13_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_09_00)){
			
			$id_horario_temp = $horarios_09_00[$loop_max];
			
			$altura_08_30_max_temp = 45;
			$altura_09_30_max_temp = 45;
			$altura_10_30_max_temp = 45;
			$altura_11_30_max_temp = 45;
			$altura_12_30_max_temp = 45;
			$altura_13_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_08_30_max_temp += 62;
						$altura_09_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 147;
						$altura_09_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 229;
						$altura_09_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 315;
						$altura_09_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 398;
						$altura_09_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_08_30_max_temp += 26;
						$altura_09_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 57;
						$altura_09_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 95;
						$altura_09_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 128;
						$altura_09_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 157;
						$altura_09_30_max_temp += 295;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 45;
						$altura_09_30_max_temp += 60;
						$altura_10_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 78;
						$altura_09_30_max_temp += 108;
						$altura_10_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 112;
						$altura_09_30_max_temp += 157;
						$altura_10_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 130;
						$altura_09_30_max_temp += 220;
						$altura_10_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 24;
						$altura_09_30_max_temp += 35;
						$altura_10_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 43;
						$altura_09_30_max_temp += 71;
						$altura_10_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 65;
						$altura_09_30_max_temp += 107;
						$altura_10_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 83;
						$altura_09_30_max_temp += 144;
						$altura_10_30_max_temp += 144;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 16;
						$altura_09_30_max_temp += 21;
						$altura_10_30_max_temp += 21;
						$altura_11_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 36;
						$altura_09_30_max_temp += 53;
						$altura_10_30_max_temp += 53;
						$altura_11_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 58;
						$altura_09_30_max_temp += 86;
						$altura_10_30_max_temp += 86;
						$altura_11_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 75;
						$altura_09_30_max_temp += 115;
						$altura_10_30_max_temp += 115;
						$altura_11_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_08_30_max_temp += 6;
						$altura_09_30_max_temp += 9;
						$altura_10_30_max_temp += 9;
						$altura_11_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 22;
						$altura_09_30_max_temp += 36;
						$altura_10_30_max_temp += 36;
						$altura_11_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 36;
						$altura_09_30_max_temp += 61;
						$altura_10_30_max_temp += 61;
						$altura_11_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 50;
						$altura_09_30_max_temp += 87;
						$altura_10_30_max_temp += 87;
						$altura_11_30_max_temp += 87;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 17;
						$altura_09_30_max_temp += 26;
						$altura_10_30_max_temp += 26;
						$altura_11_30_max_temp += 26;
						$altura_12_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 32;
						$altura_09_30_max_temp += 50;
						$altura_10_30_max_temp += 50;
						$altura_11_30_max_temp += 50;
						$altura_12_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 46;
						$altura_09_30_max_temp += 73;
						$altura_10_30_max_temp += 73;
						$altura_11_30_max_temp += 73;
						$altura_12_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 10;
						$altura_09_30_max_temp += 16;
						$altura_10_30_max_temp += 16;
						$altura_11_30_max_temp += 16;
						$altura_12_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 22;
						$altura_09_30_max_temp += 37;
						$altura_10_30_max_temp += 37;
						$altura_11_30_max_temp += 37;
						$altura_12_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 31;
						$altura_09_30_max_temp += 55;
						$altura_10_30_max_temp += 55;
						$altura_11_30_max_temp += 55;
						$altura_12_30_max_temp += 55;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_08_30_max_temp += 6;
						$altura_09_30_max_temp += 10;
						$altura_10_30_max_temp += 10;
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_08_30_max_temp += 18;
						$altura_09_30_max_temp += 30;
						$altura_10_30_max_temp += 30;
						$altura_11_30_max_temp += 30;
						$altura_12_30_max_temp += 30;
						$altura_13_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_08_30_max_temp += 29;
						$altura_09_30_max_temp += 48;
						$altura_10_30_max_temp += 48;
						$altura_11_30_max_temp += 48;
						$altura_12_30_max_temp += 48;
						$altura_13_30_max_temp += 29;
					}
				}	
				
				if($altura_08_30_max_temp > $altura_08_30){
					$offset_08_30 = $altura_08_30_max_temp - $altura_08_30;
					$altura_08_30 = $altura_08_30_max_temp;
					$altura_final += $offset_08_30;
				}	
				
				if($altura_09_30_max_temp > $altura_09_30){
					$offset_09_30 = $altura_09_30_max_temp - $altura_09_30;
					$altura_09_30 = $altura_09_30_max_temp;
					$altura_final += $offset_09_30;
				}	
				
				if($altura_10_30_max_temp > $altura_10_30){
					$offset_10_30 = $altura_10_30_max_temp - $altura_10_30;
					$altura_10_30 = $altura_10_30_max_temp;
					$altura_final += $offset_10_30;
				}	
				
				if($altura_11_30_max_temp > $altura_11_30){
					$offset_11_30 = $altura_11_30_max_temp - $altura_11_30;
					$altura_11_30 = $altura_11_30_max_temp;
					$altura_final += $offset_11_30;
				}	
				
				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
			$loop_max += 1;
		}
		
	}
	
$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '09:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_09_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_09_30 > 0){
		
		$horarios_09_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '09:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_09_30 = $linha2["id_horario"];
			
			array_push($horarios_09_30,$id_horario_09_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_09_30)){
			
			$id_horario_temp = $horarios_09_30[$loop_max];
			
			$altura_09_30_max_temp = 45;
			$altura_10_30_max_temp = 45;
			$altura_11_30_max_temp = 45;
			$altura_12_30_max_temp = 45;
			$altura_13_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_09_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_09_30_max_temp += 30;
						$altura_10_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 95;
						$altura_10_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 162;
						$altura_10_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 228;
						$altura_10_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 295;
						$altura_10_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 53;
						$altura_10_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 95;
						$altura_10_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 135;
						$altura_10_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 180;
						$altura_10_30_max_temp += 180;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 35;
						$altura_10_30_max_temp += 35;
						$altura_11_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 72;
						$altura_10_30_max_temp += 72;
						$altura_11_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 108;
						$altura_10_30_max_temp += 108;
						$altura_11_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 145;
						$altura_10_30_max_temp += 145;
						$altura_11_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 22;
						$altura_10_30_max_temp += 22;
						$altura_11_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 47;
						$altura_10_30_max_temp += 47;
						$altura_11_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 75;
						$altura_10_30_max_temp += 75;
						$altura_11_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 104;
						$altura_10_30_max_temp += 104;
						$altura_11_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 10;
						$altura_10_30_max_temp += 10;
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 35;
						$altura_10_30_max_temp += 35;
						$altura_11_30_max_temp += 35;
						$altura_12_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 61;
						$altura_10_30_max_temp += 61;
						$altura_11_30_max_temp += 61;
						$altura_12_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 86;
						$altura_10_30_max_temp += 86;
						$altura_11_30_max_temp += 86;
						$altura_12_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 23;
						$altura_10_30_max_temp += 23;
						$altura_11_30_max_temp += 23;
						$altura_12_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 45;
						$altura_10_30_max_temp += 45;
						$altura_11_30_max_temp += 45;
						$altura_12_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 67;
						$altura_10_30_max_temp += 67;
						$altura_11_30_max_temp += 67;
						$altura_12_30_max_temp += 67;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 17;
						$altura_10_30_max_temp += 17;
						$altura_11_30_max_temp += 17;
						$altura_12_30_max_temp += 17;
						$altura_13_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 37;
						$altura_10_30_max_temp += 37;
						$altura_11_30_max_temp += 37;
						$altura_12_30_max_temp += 37;
						$altura_13_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 56;
						$altura_10_30_max_temp += 56;
						$altura_11_30_max_temp += 56;
						$altura_12_30_max_temp += 56;
						$altura_13_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 10;
						$altura_10_30_max_temp += 10;
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 27;
						$altura_10_30_max_temp += 27;
						$altura_11_30_max_temp += 27;
						$altura_12_30_max_temp += 27;
						$altura_13_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 44;
						$altura_10_30_max_temp += 44;
						$altura_11_30_max_temp += 44;
						$altura_12_30_max_temp += 44;
						$altura_13_30_max_temp += 44;
					}
				}
				
				if($altura_09_30_max_temp > $altura_09_30){
					$offset_09_30 = $altura_09_30_max_temp - $altura_09_30;
					$altura_09_30 = $altura_09_30_max_temp;
					$altura_final += $offset_09_30;
				}
				
				if($altura_10_30_max_temp > $altura_10_30){
					$offset_10_30 = $altura_10_30_max_temp - $altura_10_30;
					$altura_10_30 = $altura_10_30_max_temp;
					$altura_final += $offset_10_30;
				}				

				if($altura_11_30_max_temp > $altura_11_30){
					$offset_11_30 = $altura_11_30_max_temp - $altura_11_30;
					$altura_11_30 = $altura_11_30_max_temp;
					$altura_final += $offset_11_30;
				}	

				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	

			$loop_max += 1;
		}
		
	}
	
$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '10:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_10_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_10_00 > 0){
		
		$horarios_10_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '10:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_10_00 = $linha2["id_horario"];
			
			array_push($horarios_10_00,$id_horario_10_00);
		}
		
		$altura_09_30_max = 45;
		$altura_10_30_max = 45;
		$altura_11_30_max = 45;
		$altura_12_30_max = 45;
		$altura_13_30_max = 45;
		$altura_14_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_10_00)){
			
			$id_horario_temp = $horarios_10_00[$loop_max];
			
			$altura_09_30_max_temp = 45;
			$altura_10_30_max_temp = 45;
			$altura_11_30_max_temp = 45;
			$altura_12_30_max_temp = 45;
			$altura_13_30_max_temp = 45;
			$altura_14_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_09_30_max_temp += 62;
						$altura_10_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 147;
						$altura_10_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 229;
						$altura_10_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 315;
						$altura_10_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 398;
						$altura_10_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_09_30_max_temp += 26;
						$altura_10_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 57;
						$altura_10_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 95;
						$altura_10_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 128;
						$altura_10_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 157;
						$altura_10_30_max_temp += 295;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 45;
						$altura_10_30_max_temp += 60;
						$altura_11_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 78;
						$altura_10_30_max_temp += 108;
						$altura_11_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 112;
						$altura_10_30_max_temp += 157;
						$altura_11_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 130;
						$altura_10_30_max_temp += 220;
						$altura_11_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 24;
						$altura_10_30_max_temp += 35;
						$altura_11_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 43;
						$altura_10_30_max_temp += 71;
						$altura_11_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 65;
						$altura_10_30_max_temp += 107;
						$altura_11_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 83;
						$altura_10_30_max_temp += 144;
						$altura_11_30_max_temp += 144;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 16;
						$altura_10_30_max_temp += 21;
						$altura_11_30_max_temp += 21;
						$altura_12_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 36;
						$altura_10_30_max_temp += 53;
						$altura_11_30_max_temp += 53;
						$altura_12_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 58;
						$altura_10_30_max_temp += 86;
						$altura_11_30_max_temp += 86;
						$altura_12_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 75;
						$altura_10_30_max_temp += 115;
						$altura_11_30_max_temp += 115;
						$altura_12_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_09_30_max_temp += 6;
						$altura_10_30_max_temp += 9;
						$altura_11_30_max_temp += 9;
						$altura_12_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 22;
						$altura_10_30_max_temp += 36;
						$altura_11_30_max_temp += 36;
						$altura_12_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 36;
						$altura_10_30_max_temp += 61;
						$altura_11_30_max_temp += 61;
						$altura_12_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 50;
						$altura_10_30_max_temp += 87;
						$altura_11_30_max_temp += 87;
						$altura_12_30_max_temp += 87;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 17;
						$altura_10_30_max_temp += 26;
						$altura_11_30_max_temp += 26;
						$altura_12_30_max_temp += 26;
						$altura_13_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 32;
						$altura_10_30_max_temp += 50;
						$altura_11_30_max_temp += 50;
						$altura_12_30_max_temp += 50;
						$altura_13_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 46;
						$altura_10_30_max_temp += 73;
						$altura_11_30_max_temp += 73;
						$altura_12_30_max_temp += 73;
						$altura_13_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 10;
						$altura_10_30_max_temp += 16;
						$altura_11_30_max_temp += 16;
						$altura_12_30_max_temp += 16;
						$altura_13_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 22;
						$altura_10_30_max_temp += 37;
						$altura_11_30_max_temp += 37;
						$altura_12_30_max_temp += 37;
						$altura_13_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 31;
						$altura_10_30_max_temp += 55;
						$altura_11_30_max_temp += 55;
						$altura_12_30_max_temp += 55;
						$altura_13_30_max_temp += 55;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_09_30_max_temp += 6;
						$altura_10_30_max_temp += 10;
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_09_30_max_temp += 18;
						$altura_10_30_max_temp += 30;
						$altura_11_30_max_temp += 30;
						$altura_12_30_max_temp += 30;
						$altura_13_30_max_temp += 30;
						$altura_14_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_09_30_max_temp += 29;
						$altura_10_30_max_temp += 48;
						$altura_11_30_max_temp += 48;
						$altura_12_30_max_temp += 48;
						$altura_13_30_max_temp += 48;
						$altura_14_30_max_temp += 29;
					}
				}	
				
				if($altura_09_30_max_temp > $altura_09_30){
					$offset_09_30 = $altura_09_30_max_temp - $altura_09_30;
					$altura_09_30 = $altura_09_30_max_temp;
					$altura_final += $offset_09_30;
				}	
				
				if($altura_10_30_max_temp > $altura_10_30){
					$offset_10_30 = $altura_10_30_max_temp - $altura_10_30;
					$altura_10_30 = $altura_10_30_max_temp;
					$altura_final += $offset_10_30;
				}	
				
				if($altura_11_30_max_temp > $altura_11_30){
					$offset_11_30 = $altura_11_30_max_temp - $altura_11_30;
					$altura_11_30 = $altura_11_30_max_temp;
					$altura_final += $offset_11_30;
				}	
				
				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	
				
			$loop_max += 1;
		}
		
	}
	
$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '10:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_10_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_10_30 > 0){
		
		$horarios_10_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '10:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_10_30 = $linha2["id_horario"];
			
			array_push($horarios_10_30,$id_horario_10_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_10_30)){
			
			$id_horario_temp = $horarios_10_30[$loop_max];
			
			$altura_10_30_max_temp = 45;
			$altura_11_30_max_temp = 45;
			$altura_12_30_max_temp = 45;
			$altura_13_30_max_temp = 45;
			$altura_14_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_10_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_10_30_max_temp += 30;
						$altura_11_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 95;
						$altura_11_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 162;
						$altura_11_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 228;
						$altura_11_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 295;
						$altura_11_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 53;
						$altura_11_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 95;
						$altura_11_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 135;
						$altura_11_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 180;
						$altura_11_30_max_temp += 180;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 35;
						$altura_11_30_max_temp += 35;
						$altura_12_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 72;
						$altura_11_30_max_temp += 72;
						$altura_12_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 108;
						$altura_11_30_max_temp += 108;
						$altura_12_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 145;
						$altura_11_30_max_temp += 145;
						$altura_12_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 22;
						$altura_11_30_max_temp += 22;
						$altura_12_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 47;
						$altura_11_30_max_temp += 47;
						$altura_12_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 75;
						$altura_11_30_max_temp += 75;
						$altura_12_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 104;
						$altura_11_30_max_temp += 104;
						$altura_12_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 10;
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 35;
						$altura_11_30_max_temp += 35;
						$altura_12_30_max_temp += 35;
						$altura_13_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 61;
						$altura_11_30_max_temp += 61;
						$altura_12_30_max_temp += 61;
						$altura_13_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 86;
						$altura_11_30_max_temp += 86;
						$altura_12_30_max_temp += 86;
						$altura_13_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 23;
						$altura_11_30_max_temp += 23;
						$altura_12_30_max_temp += 23;
						$altura_13_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 45;
						$altura_11_30_max_temp += 45;
						$altura_12_30_max_temp += 45;
						$altura_13_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 67;
						$altura_11_30_max_temp += 67;
						$altura_12_30_max_temp += 67;
						$altura_13_30_max_temp += 67;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 17;
						$altura_11_30_max_temp += 17;
						$altura_12_30_max_temp += 17;
						$altura_13_30_max_temp += 17;
						$altura_14_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 37;
						$altura_11_30_max_temp += 37;
						$altura_12_30_max_temp += 37;
						$altura_13_30_max_temp += 37;
						$altura_14_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 56;
						$altura_11_30_max_temp += 56;
						$altura_12_30_max_temp += 56;
						$altura_13_30_max_temp += 56;
						$altura_14_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 10;
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 27;
						$altura_11_30_max_temp += 27;
						$altura_12_30_max_temp += 27;
						$altura_13_30_max_temp += 27;
						$altura_14_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 44;
						$altura_11_30_max_temp += 44;
						$altura_12_30_max_temp += 44;
						$altura_13_30_max_temp += 44;
						$altura_14_30_max_temp += 44;
					}
				}
				
				if($altura_10_30_max_temp > $altura_10_30){
					$offset_10_30 = $altura_10_30_max_temp - $altura_10_30;
					$altura_10_30 = $altura_10_30_max_temp;
					$altura_final += $offset_10_30;
				}				

				if($altura_11_30_max_temp > $altura_11_30){
					$offset_11_30 = $altura_11_30_max_temp - $altura_11_30;
					$altura_11_30 = $altura_11_30_max_temp;
					$altura_final += $offset_11_30;
				}	

				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	

			$loop_max += 1;
		}
		
	}
	
$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '11:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_11_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_11_00 > 0){
		
		$horarios_11_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '11:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_11_00 = $linha2["id_horario"];
			
			array_push($horarios_11_00,$id_horario_11_00);
		}
		
		$altura_10_30_max = 45;
		$altura_11_30_max = 45;
		$altura_12_30_max = 45;
		$altura_13_30_max = 45;
		$altura_14_30_max = 45;
		$altura_15_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_11_00)){
			
			$id_horario_temp = $horarios_11_00[$loop_max];
			
			$altura_10_30_max_temp = 45;
			$altura_11_30_max_temp = 45;
			$altura_12_30_max_temp = 45;
			$altura_13_30_max_temp = 45;
			$altura_14_30_max_temp = 45;
			$altura_15_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_10_30_max_temp += 62;
						$altura_11_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 147;
						$altura_11_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 229;
						$altura_11_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 315;
						$altura_11_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 398;
						$altura_11_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_10_30_max_temp += 26;
						$altura_11_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 57;
						$altura_11_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 95;
						$altura_11_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 128;
						$altura_11_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 157;
						$altura_11_30_max_temp += 295;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 45;
						$altura_11_30_max_temp += 60;
						$altura_12_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 78;
						$altura_11_30_max_temp += 108;
						$altura_12_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 112;
						$altura_11_30_max_temp += 157;
						$altura_12_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 130;
						$altura_11_30_max_temp += 220;
						$altura_12_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 24;
						$altura_11_30_max_temp += 35;
						$altura_12_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 43;
						$altura_11_30_max_temp += 71;
						$altura_12_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 65;
						$altura_11_30_max_temp += 107;
						$altura_12_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 83;
						$altura_11_30_max_temp += 144;
						$altura_12_30_max_temp += 144;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 16;
						$altura_11_30_max_temp += 21;
						$altura_12_30_max_temp += 21;
						$altura_13_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 36;
						$altura_11_30_max_temp += 53;
						$altura_12_30_max_temp += 53;
						$altura_13_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 58;
						$altura_11_30_max_temp += 86;
						$altura_12_30_max_temp += 86;
						$altura_13_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 75;
						$altura_11_30_max_temp += 115;
						$altura_12_30_max_temp += 115;
						$altura_13_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_10_30_max_temp += 6;
						$altura_11_30_max_temp += 9;
						$altura_12_30_max_temp += 9;
						$altura_13_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 22;
						$altura_11_30_max_temp += 36;
						$altura_12_30_max_temp += 36;
						$altura_13_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 36;
						$altura_11_30_max_temp += 61;
						$altura_12_30_max_temp += 61;
						$altura_13_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 50;
						$altura_11_30_max_temp += 87;
						$altura_12_30_max_temp += 87;
						$altura_13_30_max_temp += 87;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 17;
						$altura_11_30_max_temp += 26;
						$altura_12_30_max_temp += 26;
						$altura_13_30_max_temp += 26;
						$altura_14_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 32;
						$altura_11_30_max_temp += 50;
						$altura_12_30_max_temp += 50;
						$altura_13_30_max_temp += 50;
						$altura_14_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 46;
						$altura_11_30_max_temp += 73;
						$altura_12_30_max_temp += 73;
						$altura_13_30_max_temp += 73;
						$altura_14_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 10;
						$altura_11_30_max_temp += 16;
						$altura_12_30_max_temp += 16;
						$altura_13_30_max_temp += 16;
						$altura_14_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 22;
						$altura_11_30_max_temp += 37;
						$altura_12_30_max_temp += 37;
						$altura_13_30_max_temp += 37;
						$altura_14_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 31;
						$altura_11_30_max_temp += 55;
						$altura_12_30_max_temp += 55;
						$altura_13_30_max_temp += 55;
						$altura_14_30_max_temp += 55;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_10_30_max_temp += 6;
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_10_30_max_temp += 18;
						$altura_11_30_max_temp += 30;
						$altura_12_30_max_temp += 30;
						$altura_13_30_max_temp += 30;
						$altura_14_30_max_temp += 30;
						$altura_15_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_10_30_max_temp += 29;
						$altura_11_30_max_temp += 48;
						$altura_12_30_max_temp += 48;
						$altura_13_30_max_temp += 48;
						$altura_14_30_max_temp += 48;
						$altura_15_30_max_temp += 29;
					}
				}	
				
				if($altura_10_30_max_temp > $altura_10_30){
					$offset_10_30 = $altura_10_30_max_temp - $altura_10_30;
					$altura_10_30 = $altura_10_30_max_temp;
					$altura_final += $offset_10_30;
				}	
				
				if($altura_11_30_max_temp > $altura_11_30){
					$offset_11_30 = $altura_11_30_max_temp - $altura_11_30;
					$altura_11_30 = $altura_11_30_max_temp;
					$altura_final += $offset_11_30;
				}	
				
				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	

				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '11:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_11_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_11_30 > 0){
		
		$horarios_11_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '11:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_11_30 = $linha2["id_horario"];
			
			array_push($horarios_11_30,$id_horario_11_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_11_30)){
			
			$id_horario_temp = $horarios_11_30[$loop_max];
			
			$altura_11_30_max_temp = 45;
			$altura_12_30_max_temp = 45;
			$altura_13_30_max_temp = 45;
			$altura_14_30_max_temp = 45;
			$altura_15_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_11_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_11_30_max_temp += 30;
						$altura_12_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 95;
						$altura_12_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 162;
						$altura_12_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 228;
						$altura_12_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 295;
						$altura_12_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 53;
						$altura_12_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 95;
						$altura_12_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 135;
						$altura_12_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 180;
						$altura_12_30_max_temp += 180;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 35;
						$altura_12_30_max_temp += 35;
						$altura_13_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 72;
						$altura_12_30_max_temp += 72;
						$altura_13_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 108;
						$altura_12_30_max_temp += 108;
						$altura_13_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 145;
						$altura_12_30_max_temp += 145;
						$altura_13_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 22;
						$altura_12_30_max_temp += 22;
						$altura_13_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 47;
						$altura_12_30_max_temp += 47;
						$altura_13_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 75;
						$altura_12_30_max_temp += 75;
						$altura_13_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 104;
						$altura_12_30_max_temp += 104;
						$altura_13_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 35;
						$altura_12_30_max_temp += 35;
						$altura_13_30_max_temp += 35;
						$altura_14_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 61;
						$altura_12_30_max_temp += 61;
						$altura_13_30_max_temp += 61;
						$altura_14_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 86;
						$altura_12_30_max_temp += 86;
						$altura_13_30_max_temp += 86;
						$altura_14_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 23;
						$altura_12_30_max_temp += 23;
						$altura_13_30_max_temp += 23;
						$altura_14_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 45;
						$altura_12_30_max_temp += 45;
						$altura_13_30_max_temp += 45;
						$altura_13_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 67;
						$altura_12_30_max_temp += 67;
						$altura_13_30_max_temp += 67;
						$altura_14_30_max_temp += 67;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 17;
						$altura_12_30_max_temp += 17;
						$altura_13_30_max_temp += 17;
						$altura_14_30_max_temp += 17;
						$altura_15_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 37;
						$altura_12_30_max_temp += 37;
						$altura_13_30_max_temp += 37;
						$altura_14_30_max_temp += 37;
						$altura_15_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 56;
						$altura_12_30_max_temp += 56;
						$altura_13_30_max_temp += 56;
						$altura_14_30_max_temp += 56;
						$altura_15_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 27;
						$altura_12_30_max_temp += 27;
						$altura_13_30_max_temp += 27;
						$altura_14_30_max_temp += 27;
						$altura_15_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 44;
						$altura_12_30_max_temp += 44;
						$altura_13_30_max_temp += 44;
						$altura_14_30_max_temp += 44;
						$altura_15_30_max_temp += 44;
					}
				}
			
				if($altura_11_30_max_temp > $altura_11_30){
					$offset_11_30 = $altura_11_30_max_temp - $altura_11_30;
					$altura_11_30 = $altura_11_30_max_temp;
					$altura_final += $offset_11_30;
				}	

				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	
				
				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '12:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_12_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_12_00 > 0){
		
		$horarios_12_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '12:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_12_00 = $linha2["id_horario"];
			
			array_push($horarios_12_00,$id_horario_12_00);
		}
		
		$altura_11_30_max = 45;
		$altura_12_30_max = 45;
		$altura_13_30_max = 45;
		$altura_14_30_max = 45;
		$altura_15_30_max = 45;
		$altura_16_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_12_00)){
			
			$id_horario_temp = $horarios_12_00[$loop_max];
			
			$altura_11_30_max_temp = 45;
			$altura_12_30_max_temp = 45;
			$altura_13_30_max_temp = 45;
			$altura_14_30_max_temp = 45;
			$altura_15_30_max_temp = 45;
			$altura_16_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_11_30_max_temp += 62;
						$altura_12_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 147;
						$altura_12_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 229;
						$altura_12_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 315;
						$altura_12_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 398;
						$altura_12_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_11_30_max_temp += 26;
						$altura_12_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 57;
						$altura_12_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 95;
						$altura_12_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 128;
						$altura_12_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 157;
						$altura_12_30_max_temp += 295;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 45;
						$altura_12_30_max_temp += 60;
						$altura_13_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 78;
						$altura_12_30_max_temp += 108;
						$altura_13_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 112;
						$altura_12_30_max_temp += 157;
						$altura_13_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 130;
						$altura_12_30_max_temp += 220;
						$altura_13_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 24;
						$altura_12_30_max_temp += 35;
						$altura_13_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 43;
						$altura_12_30_max_temp += 71;
						$altura_13_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 65;
						$altura_12_30_max_temp += 107;
						$altura_13_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 83;
						$altura_12_30_max_temp += 144;
						$altura_13_30_max_temp += 144;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 16;
						$altura_12_30_max_temp += 21;
						$altura_13_30_max_temp += 21;
						$altura_14_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 36;
						$altura_12_30_max_temp += 53;
						$altura_13_30_max_temp += 53;
						$altura_14_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 58;
						$altura_12_30_max_temp += 86;
						$altura_13_30_max_temp += 86;
						$altura_14_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 75;
						$altura_12_30_max_temp += 115;
						$altura_13_30_max_temp += 115;
						$altura_14_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_11_30_max_temp += 6;
						$altura_12_30_max_temp += 9;
						$altura_13_30_max_temp += 9;
						$altura_14_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 22;
						$altura_12_30_max_temp += 36;
						$altura_13_30_max_temp += 36;
						$altura_14_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 36;
						$altura_12_30_max_temp += 61;
						$altura_13_30_max_temp += 61;
						$altura_14_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 50;
						$altura_12_30_max_temp += 87;
						$altura_13_30_max_temp += 87;
						$altura_14_30_max_temp += 87;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 17;
						$altura_12_30_max_temp += 26;
						$altura_13_30_max_temp += 26;
						$altura_14_30_max_temp += 26;
						$altura_15_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 32;
						$altura_12_30_max_temp += 50;
						$altura_13_30_max_temp += 50;
						$altura_14_30_max_temp += 50;
						$altura_15_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 46;
						$altura_12_30_max_temp += 73;
						$altura_13_30_max_temp += 73;
						$altura_14_30_max_temp += 73;
						$altura_15_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 10;
						$altura_12_30_max_temp += 16;
						$altura_13_30_max_temp += 16;
						$altura_14_30_max_temp += 16;
						$altura_15_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 22;
						$altura_12_30_max_temp += 37;
						$altura_13_30_max_temp += 37;
						$altura_14_30_max_temp += 37;
						$altura_15_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 31;
						$altura_12_30_max_temp += 55;
						$altura_13_30_max_temp += 55;
						$altura_14_30_max_temp += 55;
						$altura_15_30_max_temp += 55;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_11_30_max_temp += 6;
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_11_30_max_temp += 18;
						$altura_12_30_max_temp += 30;
						$altura_13_30_max_temp += 30;
						$altura_14_30_max_temp += 30;
						$altura_15_30_max_temp += 30;
						$altura_16_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 29;
						$altura_12_30_max_temp += 48;
						$altura_13_30_max_temp += 48;
						$altura_14_30_max_temp += 48;
						$altura_15_30_max_temp += 48;
						$altura_16_30_max_temp += 29;
					}
				}	
				
				if($altura_11_30_max_temp > $altura_11_30){
					$offset_11_30 = $altura_11_30_max_temp - $altura_11_30;
					$altura_11_30 = $altura_11_30_max_temp;
					$altura_final += $offset_11_30;
				}	
				
				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	

				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}
				
			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '12:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_12_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_12_30 > 0){
		
		$horarios_12_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '12:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_12_30 = $linha2["id_horario"];
			
			array_push($horarios_12_30,$id_horario_12_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_12_30)){
			
			$id_horario_temp = $horarios_12_30[$loop_max];
			
			$altura_12_30_max_temp = 45;
			$altura_13_30_max_temp = 45;
			$altura_14_30_max_temp = 45;
			$altura_15_30_max_temp = 45;
			$altura_16_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_12_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_12_30_max_temp += 30;
						$altura_13_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 95;
						$altura_13_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 162;
						$altura_13_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 228;
						$altura_13_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_11_30_max_temp += 295;
						$altura_13_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 53;
						$altura_13_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 95;
						$altura_13_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 135;
						$altura_13_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 180;
						$altura_13_30_max_temp += 180;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 35;
						$altura_13_30_max_temp += 35;
						$altura_14_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 72;
						$altura_13_30_max_temp += 72;
						$altura_14_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 108;
						$altura_13_30_max_temp += 108;
						$altura_14_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 145;
						$altura_13_30_max_temp += 145;
						$altura_14_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 22;
						$altura_13_30_max_temp += 22;
						$altura_14_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 47;
						$altura_13_30_max_temp += 47;
						$altura_14_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 75;
						$altura_13_30_max_temp += 75;
						$altura_14_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 104;
						$altura_13_30_max_temp += 104;
						$altura_14_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 35;
						$altura_13_30_max_temp += 35;
						$altura_14_30_max_temp += 35;
						$altura_15_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 61;
						$altura_13_30_max_temp += 61;
						$altura_14_30_max_temp += 61;
						$altura_15_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 86;
						$altura_13_30_max_temp += 86;
						$altura_14_30_max_temp += 86;
						$altura_15_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 23;
						$altura_13_30_max_temp += 23;
						$altura_14_30_max_temp += 23;
						$altura_15_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 45;
						$altura_13_30_max_temp += 45;
						$altura_14_30_max_temp += 45;
						$altura_15_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 67;
						$altura_13_30_max_temp += 67;
						$altura_14_30_max_temp += 67;
						$altura_15_30_max_temp += 67;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 17;
						$altura_13_30_max_temp += 17;
						$altura_14_30_max_temp += 17;
						$altura_15_30_max_temp += 17;
						$altura_16_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 37;
						$altura_13_30_max_temp += 37;
						$altura_14_30_max_temp += 37;
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 56;
						$altura_13_30_max_temp += 56;
						$altura_14_30_max_temp += 56;
						$altura_15_30_max_temp += 56;
						$altura_16_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 27;
						$altura_13_30_max_temp += 27;
						$altura_14_30_max_temp += 27;
						$altura_15_30_max_temp += 27;
						$altura_16_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 44;
						$altura_13_30_max_temp += 44;
						$altura_14_30_max_temp += 44;
						$altura_15_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
					}
				}

				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	
				
				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}	

			$loop_max += 1;
		}
		
	}
	
$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '13:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_13_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_13_00 > 0){
		
		$horarios_13_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '13:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_13_00 = $linha2["id_horario"];
			
			array_push($horarios_13_00,$id_horario_13_00);
		}
		
		$altura_12_30_max = 45;
		$altura_13_30_max = 45;
		$altura_14_30_max = 45;
		$altura_15_30_max = 45;
		$altura_16_30_max = 45;
		$altura_17_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_13_00)){
			
			$id_horario_temp = $horarios_13_00[$loop_max];
			
			$altura_12_30_max_temp = 45;
			$altura_13_30_max_temp = 45;
			$altura_14_30_max_temp = 45;
			$altura_15_30_max_temp = 45;
			$altura_16_30_max_temp = 45;
			$altura_17_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_12_30_max_temp += 62;
						$altura_13_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 147;
						$altura_13_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 229;
						$altura_13_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 315;
						$altura_13_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 398;
						$altura_13_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_12_30_max_temp += 26;
						$altura_13_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 57;
						$altura_13_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 95;
						$altura_13_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 128;
						$altura_13_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 157;
						$altura_13_30_max_temp += 295;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 45;
						$altura_13_30_max_temp += 60;
						$altura_14_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 78;
						$altura_13_30_max_temp += 108;
						$altura_14_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 112;
						$altura_13_30_max_temp += 157;
						$altura_14_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 130;
						$altura_13_30_max_temp += 220;
						$altura_14_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 24;
						$altura_13_30_max_temp += 35;
						$altura_14_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 43;
						$altura_13_30_max_temp += 71;
						$altura_14_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 65;
						$altura_13_30_max_temp += 107;
						$altura_14_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 83;
						$altura_13_30_max_temp += 144;
						$altura_14_30_max_temp += 144;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 16;
						$altura_13_30_max_temp += 21;
						$altura_14_30_max_temp += 21;
						$altura_15_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 36;
						$altura_13_30_max_temp += 53;
						$altura_14_30_max_temp += 53;
						$altura_15_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 58;
						$altura_13_30_max_temp += 86;
						$altura_14_30_max_temp += 86;
						$altura_15_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 75;
						$altura_13_30_max_temp += 115;
						$altura_14_30_max_temp += 115;
						$altura_15_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_12_30_max_temp += 6;
						$altura_13_30_max_temp += 9;
						$altura_14_30_max_temp += 9;
						$altura_15_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 22;
						$altura_13_30_max_temp += 36;
						$altura_14_30_max_temp += 36;
						$altura_15_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 36;
						$altura_13_30_max_temp += 61;
						$altura_14_30_max_temp += 61;
						$altura_15_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 50;
						$altura_13_30_max_temp += 87;
						$altura_14_30_max_temp += 87;
						$altura_15_30_max_temp += 87;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 17;
						$altura_13_30_max_temp += 26;
						$altura_14_30_max_temp += 26;
						$altura_15_30_max_temp += 26;
						$altura_16_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 32;
						$altura_13_30_max_temp += 50;
						$altura_14_30_max_temp += 50;
						$altura_15_30_max_temp += 50;
						$altura_16_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 46;
						$altura_13_30_max_temp += 73;
						$altura_14_30_max_temp += 73;
						$altura_15_30_max_temp += 73;
						$altura_16_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 10;
						$altura_13_30_max_temp += 16;
						$altura_14_30_max_temp += 16;
						$altura_15_30_max_temp += 16;
						$altura_16_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 22;
						$altura_13_30_max_temp += 37;
						$altura_14_30_max_temp += 37;
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 31;
						$altura_13_30_max_temp += 55;
						$altura_14_30_max_temp += 55;
						$altura_15_30_max_temp += 55;
						$altura_16_30_max_temp += 55;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_12_30_max_temp += 6;
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_12_30_max_temp += 18;
						$altura_13_30_max_temp += 30;
						$altura_14_30_max_temp += 30;
						$altura_15_30_max_temp += 30;
						$altura_16_30_max_temp += 30;
						$altura_17_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_12_30_max_temp += 29;
						$altura_13_30_max_temp += 48;
						$altura_14_30_max_temp += 48;
						$altura_15_30_max_temp += 48;
						$altura_16_30_max_temp += 48;
						$altura_17_30_max_temp += 29;
					}
				}	

				if($altura_12_30_max_temp > $altura_12_30){
					$offset_12_30 = $altura_12_30_max_temp - $altura_12_30;
					$altura_12_30 = $altura_12_30_max_temp;
					$altura_final += $offset_12_30;
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	

				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}

				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '13:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_13_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_13_30 > 0){
		
		$horarios_13_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '13:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_13_30 = $linha2["id_horario"];
			
			array_push($horarios_13_30,$id_horario_13_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_13_30)){
			
			$id_horario_temp = $horarios_13_30[$loop_max];
			
			$altura_13_30_max_temp = 45;
			$altura_14_30_max_temp = 45;
			$altura_15_30_max_temp = 45;
			$altura_16_30_max_temp = 45;
			$altura_17_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_13_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_13_30_max_temp += 30;
						$altura_14_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 95;
						$altura_14_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 162;
						$altura_14_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 228;
						$altura_14_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 295;
						$altura_14_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 53;
						$altura_14_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 95;
						$altura_14_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 135;
						$altura_14_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 180;
						$altura_14_30_max_temp += 180;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 35;
						$altura_14_30_max_temp += 35;
						$altura_15_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 72;
						$altura_14_30_max_temp += 72;
						$altura_15_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 108;
						$altura_14_30_max_temp += 108;
						$altura_15_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 145;
						$altura_14_30_max_temp += 145;
						$altura_15_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 22;
						$altura_14_30_max_temp += 22;
						$altura_15_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 47;
						$altura_14_30_max_temp += 47;
						$altura_15_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 75;
						$altura_14_30_max_temp += 75;
						$altura_15_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 104;
						$altura_14_30_max_temp += 104;
						$altura_15_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 35;
						$altura_14_30_max_temp += 35;
						$altura_15_30_max_temp += 35;
						$altura_16_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 61;
						$altura_14_30_max_temp += 61;
						$altura_15_30_max_temp += 61;
						$altura_16_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 86;
						$altura_14_30_max_temp += 86;
						$altura_15_30_max_temp += 86;
						$altura_16_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 23;
						$altura_14_30_max_temp += 23;
						$altura_15_30_max_temp += 23;
						$altura_16_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 45;
						$altura_14_30_max_temp += 45;
						$altura_15_30_max_temp += 45;
						$altura_16_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 67;
						$altura_14_30_max_temp += 67;
						$altura_15_30_max_temp += 67;
						$altura_16_30_max_temp += 67;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 17;
						$altura_14_30_max_temp += 17;
						$altura_15_30_max_temp += 17;
						$altura_16_30_max_temp += 17;
						$altura_17_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 37;
						$altura_14_30_max_temp += 37;
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 56;
						$altura_14_30_max_temp += 56;
						$altura_15_30_max_temp += 56;
						$altura_16_30_max_temp += 56;
						$altura_17_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 27;
						$altura_14_30_max_temp += 27;
						$altura_15_30_max_temp += 27;
						$altura_16_30_max_temp += 27;
						$altura_17_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 44;
						$altura_14_30_max_temp += 44;
						$altura_15_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_17_30_max_temp += 44;
					}
				}
				
				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	
				
				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}	
				
				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	

			$loop_max += 1;
		}
		
	}
	
$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '14:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_14_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_14_00 > 0){
		
		$horarios_14_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '14:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_14_00 = $linha2["id_horario"];
			
			array_push($horarios_14_00,$id_horario_14_00);
		}
		
		$altura_13_30_max = 45;
		$altura_14_30_max = 45;
		$altura_15_30_max = 45;
		$altura_16_30_max = 45;
		$altura_17_30_max = 45;
		$altura_18_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_14_00)){
			
			$id_horario_temp = $horarios_14_00[$loop_max];
			
			$altura_13_30_max_temp = 45;
			$altura_14_30_max_temp = 45;
			$altura_15_30_max_temp = 45;
			$altura_16_30_max_temp = 45;
			$altura_17_30_max_temp = 45;
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_13_30_max_temp += 62;
						$altura_14_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 147;
						$altura_14_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 229;
						$altura_14_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 315;
						$altura_14_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 398;
						$altura_14_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_13_30_max_temp += 26;
						$altura_14_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 57;
						$altura_14_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 95;
						$altura_14_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 128;
						$altura_14_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 157;
						$altura_14_30_max_temp += 295;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 45;
						$altura_14_30_max_temp += 60;
						$altura_15_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 78;
						$altura_14_30_max_temp += 108;
						$altura_15_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 112;
						$altura_14_30_max_temp += 157;
						$altura_15_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 130;
						$altura_14_30_max_temp += 220;
						$altura_15_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 24;
						$altura_14_30_max_temp += 35;
						$altura_15_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 43;
						$altura_14_30_max_temp += 71;
						$altura_15_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 65;
						$altura_14_30_max_temp += 107;
						$altura_15_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 83;
						$altura_14_30_max_temp += 144;
						$altura_15_30_max_temp += 144;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 16;
						$altura_14_30_max_temp += 21;
						$altura_15_30_max_temp += 21;
						$altura_16_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 36;
						$altura_14_30_max_temp += 53;
						$altura_15_30_max_temp += 53;
						$altura_16_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 58;
						$altura_14_30_max_temp += 86;
						$altura_15_30_max_temp += 86;
						$altura_16_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 75;
						$altura_14_30_max_temp += 115;
						$altura_15_30_max_temp += 115;
						$altura_16_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_13_30_max_temp += 6;
						$altura_14_30_max_temp += 9;
						$altura_15_30_max_temp += 9;
						$altura_16_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 22;
						$altura_14_30_max_temp += 36;
						$altura_15_30_max_temp += 36;
						$altura_16_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 36;
						$altura_14_30_max_temp += 61;
						$altura_15_30_max_temp += 61;
						$altura_16_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 50;
						$altura_14_30_max_temp += 87;
						$altura_15_30_max_temp += 87;
						$altura_16_30_max_temp += 87;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 17;
						$altura_14_30_max_temp += 26;
						$altura_15_30_max_temp += 26;
						$altura_16_30_max_temp += 26;
						$altura_17_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 32;
						$altura_14_30_max_temp += 50;
						$altura_15_30_max_temp += 50;
						$altura_16_30_max_temp += 50;
						$altura_17_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 46;
						$altura_14_30_max_temp += 73;
						$altura_15_30_max_temp += 73;
						$altura_16_30_max_temp += 73;
						$altura_17_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 10;
						$altura_14_30_max_temp += 16;
						$altura_15_30_max_temp += 16;
						$altura_16_30_max_temp += 16;
						$altura_17_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 22;
						$altura_14_30_max_temp += 37;
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 31;
						$altura_14_30_max_temp += 55;
						$altura_15_30_max_temp += 55;
						$altura_16_30_max_temp += 55;
						$altura_17_30_max_temp += 55;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 6;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 18;
						$altura_14_30_max_temp += 30;
						$altura_15_30_max_temp += 30;
						$altura_16_30_max_temp += 30;
						$altura_17_30_max_temp += 30;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 29;
						$altura_14_30_max_temp += 48;
						$altura_15_30_max_temp += 48;
						$altura_16_30_max_temp += 48;
						$altura_17_30_max_temp += 48;
						$altura_18_30_max_temp += 29;
					}
				}	

				if($altura_13_30_max_temp > $altura_13_30){
					$offset_13_30 = $altura_13_30_max_temp - $altura_13_30;
					$altura_13_30 = $altura_13_30_max_temp;
					$altura_final += $offset_13_30;
				}	
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	

				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}

				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	
				
				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}	

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '14:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_14_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_14_30 > 0){
		
		$horarios_14_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '14:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_14_30 = $linha2["id_horario"];
			
			array_push($horarios_14_30,$id_horario_14_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_14_30)){
			
			$id_horario_temp = $horarios_14_30[$loop_max];
			
			$altura_14_30_max_temp = 45;
			$altura_15_30_max_temp = 45;
			$altura_16_30_max_temp = 45;
			$altura_17_30_max_temp = 45;
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_14_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_14_30_max_temp += 30;
						$altura_15_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 95;
						$altura_15_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 162;
						$altura_15_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 228;
						$altura_15_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 295;
						$altura_15_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 53;
						$altura_15_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 95;
						$altura_15_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 135;
						$altura_15_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 180;
						$altura_15_30_max_temp += 180;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 35;
						$altura_15_30_max_temp += 35;
						$altura_16_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 72;
						$altura_15_30_max_temp += 72;
						$altura_16_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 108;
						$altura_15_30_max_temp += 108;
						$altura_16_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 145;
						$altura_15_30_max_temp += 145;
						$altura_16_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 22;
						$altura_15_30_max_temp += 22;
						$altura_16_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 47;
						$altura_15_30_max_temp += 47;
						$altura_16_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 75;
						$altura_15_30_max_temp += 75;
						$altura_16_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 104;
						$altura_15_30_max_temp += 104;
						$altura_16_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 35;
						$altura_15_30_max_temp += 35;
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 61;
						$altura_15_30_max_temp += 61;
						$altura_16_30_max_temp += 61;
						$altura_17_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 86;
						$altura_15_30_max_temp += 86;
						$altura_16_30_max_temp += 86;
						$altura_17_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 23;
						$altura_15_30_max_temp += 23;
						$altura_16_30_max_temp += 23;
						$altura_17_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 45;
						$altura_15_30_max_temp += 45;
						$altura_16_30_max_temp += 45;
						$altura_17_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 67;
						$altura_15_30_max_temp += 67;
						$altura_16_30_max_temp += 67;
						$altura_17_30_max_temp += 67;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 17;
						$altura_15_30_max_temp += 17;
						$altura_16_30_max_temp += 17;
						$altura_17_30_max_temp += 17;
						$altura_18_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 37;
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 56;
						$altura_15_30_max_temp += 56;
						$altura_16_30_max_temp += 56;
						$altura_17_30_max_temp += 56;
						$altura_18_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 27;
						$altura_15_30_max_temp += 27;
						$altura_16_30_max_temp += 27;
						$altura_17_30_max_temp += 27;
						$altura_18_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 44;
						$altura_15_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_18_30_max_temp += 44;
					}
				}
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	
				
				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}	
				
				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	

				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '15:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_15_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_15_00 > 0){
		
		$horarios_15_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '15:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_15_00 = $linha2["id_horario"];
			
			array_push($horarios_15_00,$id_horario_15_00);
		}
		
		$altura_14_30_max = 45;
		$altura_15_30_max = 45;
		$altura_16_30_max = 45;
		$altura_17_30_max = 45;
		$altura_18_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_15_00)){
			
			$id_horario_temp = $horarios_15_00[$loop_max];
			
			$altura_14_30_max_temp = 45;
			$altura_15_30_max_temp = 45;
			$altura_16_30_max_temp = 45;
			$altura_17_30_max_temp = 45;
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_14_30_max_temp += 62;
						$altura_15_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 147;
						$altura_15_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 229;
						$altura_15_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 315;
						$altura_15_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 398;
						$altura_15_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_14_30_max_temp += 26;
						$altura_15_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 57;
						$altura_15_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 95;
						$altura_15_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 128;
						$altura_15_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 157;
						$altura_15_30_max_temp += 295;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 45;
						$altura_15_30_max_temp += 60;
						$altura_16_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 78;
						$altura_15_30_max_temp += 108;
						$altura_16_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 112;
						$altura_15_30_max_temp += 157;
						$altura_16_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 130;
						$altura_15_30_max_temp += 220;
						$altura_16_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 24;
						$altura_15_30_max_temp += 35;
						$altura_16_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 43;
						$altura_15_30_max_temp += 71;
						$altura_16_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 65;
						$altura_15_30_max_temp += 107;
						$altura_16_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 83;
						$altura_15_30_max_temp += 144;
						$altura_16_30_max_temp += 144;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 16;
						$altura_15_30_max_temp += 21;
						$altura_16_30_max_temp += 21;
						$altura_17_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 36;
						$altura_15_30_max_temp += 53;
						$altura_16_30_max_temp += 53;
						$altura_17_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 58;
						$altura_15_30_max_temp += 86;
						$altura_16_30_max_temp += 86;
						$altura_17_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 75;
						$altura_15_30_max_temp += 115;
						$altura_16_30_max_temp += 115;
						$altura_17_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_14_30_max_temp += 6;
						$altura_15_30_max_temp += 9;
						$altura_16_30_max_temp += 9;
						$altura_17_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 22;
						$altura_15_30_max_temp += 36;
						$altura_16_30_max_temp += 36;
						$altura_17_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 36;
						$altura_15_30_max_temp += 61;
						$altura_16_30_max_temp += 61;
						$altura_17_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 50;
						$altura_15_30_max_temp += 87;
						$altura_16_30_max_temp += 87;
						$altura_17_30_max_temp += 87;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 17;
						$altura_15_30_max_temp += 26;
						$altura_16_30_max_temp += 26;
						$altura_17_30_max_temp += 26;
						$altura_18_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 32;
						$altura_15_30_max_temp += 50;
						$altura_16_30_max_temp += 50;
						$altura_17_30_max_temp += 50;
						$altura_18_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 46;
						$altura_15_30_max_temp += 73;
						$altura_16_30_max_temp += 73;
						$altura_17_30_max_temp += 73;
						$altura_18_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 16;
						$altura_16_30_max_temp += 16;
						$altura_17_30_max_temp += 16;
						$altura_18_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 22;
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
						$altura_18_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 31;
						$altura_15_30_max_temp += 55;
						$altura_16_30_max_temp += 55;
						$altura_17_30_max_temp += 55;
						$altura_18_30_max_temp += 55;
					}
				}
				/*
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 6;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 18;
						$altura_14_30_max_temp += 30;
						$altura_15_30_max_temp += 30;
						$altura_16_30_max_temp += 30;
						$altura_17_30_max_temp += 30;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 29;
						$altura_14_30_max_temp += 48;
						$altura_15_30_max_temp += 48;
						$altura_16_30_max_temp += 48;
						$altura_17_30_max_temp += 48;
						$altura_18_30_max_temp += 29;
					}
				}	
				*/
				
				if($altura_14_30_max_temp > $altura_14_30){
					$offset_14_30 = $altura_14_30_max_temp - $altura_14_30;
					$altura_14_30 = $altura_14_30_max_temp;
					$altura_final += $offset_14_30;
				}	

				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}

				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	
				
				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}	

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '15:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_15_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_15_30 > 0){
		
		$horarios_15_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '15:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_15_30 = $linha2["id_horario"];
			
			array_push($horarios_15_30,$id_horario_15_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_15_30)){
			
			$id_horario_temp = $horarios_15_30[$loop_max];
			
			$altura_15_30_max_temp = 45;
			$altura_16_30_max_temp = 45;
			$altura_17_30_max_temp = 45;
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_15_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_15_30_max_temp += 30;
						$altura_16_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 95;
						$altura_16_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 162;
						$altura_16_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 228;
						$altura_16_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 295;
						$altura_16_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 53;
						$altura_16_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 95;
						$altura_16_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 135;
						$altura_16_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 180;
						$altura_16_30_max_temp += 180;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 35;
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 72;
						$altura_16_30_max_temp += 72;
						$altura_17_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 108;
						$altura_16_30_max_temp += 108;
						$altura_17_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 145;
						$altura_16_30_max_temp += 145;
						$altura_17_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 22;
						$altura_16_30_max_temp += 22;
						$altura_17_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 47;
						$altura_16_30_max_temp += 47;
						$altura_17_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 75;
						$altura_16_30_max_temp += 75;
						$altura_17_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 104;
						$altura_16_30_max_temp += 104;
						$altura_17_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 35;
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 35;
						$altura_18_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 61;
						$altura_16_30_max_temp += 61;
						$altura_17_30_max_temp += 61;
						$altura_18_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 86;
						$altura_16_30_max_temp += 86;
						$altura_17_30_max_temp += 86;
						$altura_18_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 23;
						$altura_16_30_max_temp += 23;
						$altura_17_30_max_temp += 23;
						$altura_18_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 45;
						$altura_16_30_max_temp += 45;
						$altura_17_30_max_temp += 45;
						$altura_18_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 67;
						$altura_16_30_max_temp += 67;
						$altura_17_30_max_temp += 67;
						$altura_18_30_max_temp += 67;
					}
				}
				/*
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 17;
						$altura_16_30_max_temp += 17;
						$altura_17_30_max_temp += 17;
						$altura_18_30_max_temp += 17;
						$altura_18_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
						$altura_18_30_max_temp += 37;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 56;
						$altura_16_30_max_temp += 56;
						$altura_17_30_max_temp += 56;
						$altura_18_30_max_temp += 56;
						$altura_18_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 27;
						$altura_15_30_max_temp += 27;
						$altura_16_30_max_temp += 27;
						$altura_17_30_max_temp += 27;
						$altura_18_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 44;
						$altura_15_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_18_30_max_temp += 44;
					}
				}
				*/
				
				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}	
				
				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	

				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}

			$loop_max += 1;
		}
		
	}
	
$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '16:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_16_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_16_00 > 0){
		
		$horarios_16_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '16:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_16_00 = $linha2["id_horario"];
			
			array_push($horarios_16_00,$id_horario_16_00);
		}
		
		$altura_15_30_max = 45;
		$altura_16_30_max = 45;
		$altura_17_30_max = 45;
		$altura_18_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_16_00)){
			
			$id_horario_temp = $horarios_16_00[$loop_max];
			
			$altura_15_30_max_temp = 45;
			$altura_16_30_max_temp = 45;
			$altura_17_30_max_temp = 45;
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_15_30_max_temp += 62;
						$altura_16_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 147;
						$altura_16_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 229;
						$altura_16_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 315;
						$altura_16_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 398;
						$altura_16_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_15_30_max_temp += 26;
						$altura_16_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 57;
						$altura_16_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 95;
						$altura_16_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 128;
						$altura_16_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 157;
						$altura_16_30_max_temp += 295;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 45;
						$altura_16_30_max_temp += 60;
						$altura_17_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 78;
						$altura_16_30_max_temp += 108;
						$altura_17_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 112;
						$altura_16_30_max_temp += 157;
						$altura_17_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 130;
						$altura_16_30_max_temp += 220;
						$altura_17_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 24;
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 43;
						$altura_16_30_max_temp += 71;
						$altura_17_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 65;
						$altura_16_30_max_temp += 107;
						$altura_17_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 83;
						$altura_16_30_max_temp += 144;
						$altura_17_30_max_temp += 144;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 16;
						$altura_16_30_max_temp += 21;
						$altura_17_30_max_temp += 21;
						$altura_18_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 36;
						$altura_16_30_max_temp += 53;
						$altura_17_30_max_temp += 53;
						$altura_18_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 58;
						$altura_16_30_max_temp += 86;
						$altura_17_30_max_temp += 86;
						$altura_18_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 75;
						$altura_16_30_max_temp += 115;
						$altura_17_30_max_temp += 115;
						$altura_18_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 6;
						$altura_16_30_max_temp += 9;
						$altura_17_30_max_temp += 9;
						$altura_18_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 22;
						$altura_16_30_max_temp += 36;
						$altura_17_30_max_temp += 36;
						$altura_18_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 36;
						$altura_16_30_max_temp += 61;
						$altura_17_30_max_temp += 61;
						$altura_18_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 50;
						$altura_16_30_max_temp += 87;
						$altura_17_30_max_temp += 87;
						$altura_18_30_max_temp += 87;
					}
				}
				/*
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 17;
						$altura_15_30_max_temp += 26;
						$altura_16_30_max_temp += 26;
						$altura_17_30_max_temp += 26;
						$altura_18_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 32;
						$altura_15_30_max_temp += 50;
						$altura_16_30_max_temp += 50;
						$altura_17_30_max_temp += 50;
						$altura_18_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 46;
						$altura_15_30_max_temp += 73;
						$altura_16_30_max_temp += 73;
						$altura_17_30_max_temp += 73;
						$altura_18_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 16;
						$altura_16_30_max_temp += 16;
						$altura_17_30_max_temp += 16;
						$altura_18_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 22;
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
						$altura_18_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 31;
						$altura_15_30_max_temp += 55;
						$altura_16_30_max_temp += 55;
						$altura_17_30_max_temp += 55;
						$altura_18_30_max_temp += 55;
					}
				}
				
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 6;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 18;
						$altura_14_30_max_temp += 30;
						$altura_15_30_max_temp += 30;
						$altura_16_30_max_temp += 30;
						$altura_17_30_max_temp += 30;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 29;
						$altura_14_30_max_temp += 48;
						$altura_15_30_max_temp += 48;
						$altura_16_30_max_temp += 48;
						$altura_17_30_max_temp += 48;
						$altura_18_30_max_temp += 29;
					}
				}	
				*/

				if($altura_15_30_max_temp > $altura_15_30){
					$offset_15_30 = $altura_15_30_max_temp - $altura_15_30;
					$altura_15_30 = $altura_15_30_max_temp;
					$altura_final += $offset_15_30;
				}	
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}

				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	
				
				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}	

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '16:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_16_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_16_30 > 0){
		
		$horarios_16_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '16:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_16_30 = $linha2["id_horario"];
			
			array_push($horarios_16_30,$id_horario_16_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_16_30)){
			
			$id_horario_temp = $horarios_16_30[$loop_max];
			
			$altura_16_30_max_temp = 45;
			$altura_17_30_max_temp = 45;
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_16_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_16_30_max_temp += 30;
						$altura_17_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 95;
						$altura_17_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 162;
						$altura_17_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 228;
						$altura_17_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 295;
						$altura_17_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 53;
						$altura_17_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 95;
						$altura_17_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 135;
						$altura_17_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 180;
						$altura_17_30_max_temp += 180;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 35;
						$altura_18_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 72;
						$altura_17_30_max_temp += 72;
						$altura_18_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 108;
						$altura_17_30_max_temp += 108;
						$altura_18_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 145;
						$altura_17_30_max_temp += 145;
						$altura_18_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 22;
						$altura_17_30_max_temp += 22;
						$altura_18_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 47;
						$altura_17_30_max_temp += 47;
						$altura_18_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 75;
						$altura_17_30_max_temp += 75;
						$altura_18_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 104;
						$altura_17_30_max_temp += 104;
						$altura_18_30_max_temp += 104;
					}
				}
				/*
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 35;
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 35;
						$altura_18_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 61;
						$altura_16_30_max_temp += 61;
						$altura_17_30_max_temp += 61;
						$altura_18_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 86;
						$altura_16_30_max_temp += 86;
						$altura_17_30_max_temp += 86;
						$altura_18_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 23;
						$altura_16_30_max_temp += 23;
						$altura_17_30_max_temp += 23;
						$altura_18_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 45;
						$altura_16_30_max_temp += 45;
						$altura_17_30_max_temp += 45;
						$altura_18_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 67;
						$altura_16_30_max_temp += 67;
						$altura_17_30_max_temp += 67;
						$altura_18_30_max_temp += 67;
					}
				}
				
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 17;
						$altura_16_30_max_temp += 17;
						$altura_17_30_max_temp += 17;
						$altura_18_30_max_temp += 17;
						$altura_18_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
						$altura_18_30_max_temp += 37;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 56;
						$altura_16_30_max_temp += 56;
						$altura_17_30_max_temp += 56;
						$altura_18_30_max_temp += 56;
						$altura_18_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 27;
						$altura_15_30_max_temp += 27;
						$altura_16_30_max_temp += 27;
						$altura_17_30_max_temp += 27;
						$altura_18_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 44;
						$altura_15_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_18_30_max_temp += 44;
					}
				}
				*/
				
				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}	
				
				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	

				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '17:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_17_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_17_00 > 0){
		
		$horarios_17_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '17:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_17_00 = $linha2["id_horario"];
			
			array_push($horarios_17_00,$id_horario_17_00);
		}
		
		$altura_16_30_max = 45;
		$altura_17_30_max = 45;
		$altura_18_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_17_00)){
			
			$id_horario_temp = $horarios_17_00[$loop_max];
			
			$altura_16_30_max_temp = 45;
			$altura_17_30_max_temp = 45;
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_16_30_max_temp += 62;
						$altura_17_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 147;
						$altura_17_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 229;
						$altura_17_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 315;
						$altura_17_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 398;
						$altura_17_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_16_30_max_temp += 26;
						$altura_17_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 57;
						$altura_17_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 95;
						$altura_17_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 128;
						$altura_17_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 157;
						$altura_17_30_max_temp += 295;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 45;
						$altura_17_30_max_temp += 60;
						$altura_18_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 78;
						$altura_17_30_max_temp += 108;
						$altura_18_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 112;
						$altura_17_30_max_temp += 157;
						$altura_18_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 130;
						$altura_17_30_max_temp += 220;
						$altura_18_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 24;
						$altura_17_30_max_temp += 35;
						$altura_18_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 43;
						$altura_17_30_max_temp += 71;
						$altura_18_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 65;
						$altura_17_30_max_temp += 107;
						$altura_18_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 83;
						$altura_17_30_max_temp += 144;
						$altura_18_30_max_temp += 144;
					}
				}
				/*
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 16;
						$altura_16_30_max_temp += 21;
						$altura_17_30_max_temp += 21;
						$altura_18_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 36;
						$altura_16_30_max_temp += 53;
						$altura_17_30_max_temp += 53;
						$altura_18_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 58;
						$altura_16_30_max_temp += 86;
						$altura_17_30_max_temp += 86;
						$altura_18_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 75;
						$altura_16_30_max_temp += 115;
						$altura_17_30_max_temp += 115;
						$altura_18_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 6;
						$altura_16_30_max_temp += 9;
						$altura_17_30_max_temp += 9;
						$altura_18_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 22;
						$altura_16_30_max_temp += 36;
						$altura_17_30_max_temp += 36;
						$altura_18_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 36;
						$altura_16_30_max_temp += 61;
						$altura_17_30_max_temp += 61;
						$altura_18_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 50;
						$altura_16_30_max_temp += 87;
						$altura_17_30_max_temp += 87;
						$altura_18_30_max_temp += 87;
					}
				}
				
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 17;
						$altura_15_30_max_temp += 26;
						$altura_16_30_max_temp += 26;
						$altura_17_30_max_temp += 26;
						$altura_18_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 32;
						$altura_15_30_max_temp += 50;
						$altura_16_30_max_temp += 50;
						$altura_17_30_max_temp += 50;
						$altura_18_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 46;
						$altura_15_30_max_temp += 73;
						$altura_16_30_max_temp += 73;
						$altura_17_30_max_temp += 73;
						$altura_18_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 16;
						$altura_16_30_max_temp += 16;
						$altura_17_30_max_temp += 16;
						$altura_18_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 22;
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
						$altura_18_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 31;
						$altura_15_30_max_temp += 55;
						$altura_16_30_max_temp += 55;
						$altura_17_30_max_temp += 55;
						$altura_18_30_max_temp += 55;
					}
				}
				
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 6;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 18;
						$altura_14_30_max_temp += 30;
						$altura_15_30_max_temp += 30;
						$altura_16_30_max_temp += 30;
						$altura_17_30_max_temp += 30;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 29;
						$altura_14_30_max_temp += 48;
						$altura_15_30_max_temp += 48;
						$altura_16_30_max_temp += 48;
						$altura_17_30_max_temp += 48;
						$altura_18_30_max_temp += 29;
					}
				}	
				*/

				if($altura_16_30_max_temp > $altura_16_30){
					$offset_16_30 = $altura_16_30_max_temp - $altura_16_30;
					$altura_16_30 = $altura_16_30_max_temp;
					$altura_final += $offset_16_30;
				}

				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	
				
				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}	

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '17:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_17_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_17_30 > 0){
		
		$horarios_17_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '17:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_17_30 = $linha2["id_horario"];
			
			array_push($horarios_17_30,$id_horario_17_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_17_30)){
			
			$id_horario_temp = $horarios_17_30[$loop_max];
			
			$altura_17_30_max_temp = 45;
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_17_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_17_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_17_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_17_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_17_30_max_temp += 405;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_17_30_max_temp += 30;
						$altura_18_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_17_30_max_temp += 95;
						$altura_18_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_17_30_max_temp += 162;
						$altura_18_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_17_30_max_temp += 228;
						$altura_18_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_17_30_max_temp += 295;
						$altura_18_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_17_30_max_temp += 53;
						$altura_18_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_17_30_max_temp += 95;
						$altura_18_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_17_30_max_temp += 135;
						$altura_18_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_17_30_max_temp += 180;
						$altura_18_30_max_temp += 180;
					}
				}
				/*
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 35;
						$altura_18_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 72;
						$altura_17_30_max_temp += 72;
						$altura_18_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 108;
						$altura_17_30_max_temp += 108;
						$altura_18_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 145;
						$altura_17_30_max_temp += 145;
						$altura_18_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 22;
						$altura_17_30_max_temp += 22;
						$altura_18_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 47;
						$altura_17_30_max_temp += 47;
						$altura_18_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 75;
						$altura_17_30_max_temp += 75;
						$altura_18_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 104;
						$altura_17_30_max_temp += 104;
						$altura_18_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 35;
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 35;
						$altura_18_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 61;
						$altura_16_30_max_temp += 61;
						$altura_17_30_max_temp += 61;
						$altura_18_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 86;
						$altura_16_30_max_temp += 86;
						$altura_17_30_max_temp += 86;
						$altura_18_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 23;
						$altura_16_30_max_temp += 23;
						$altura_17_30_max_temp += 23;
						$altura_18_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 45;
						$altura_16_30_max_temp += 45;
						$altura_17_30_max_temp += 45;
						$altura_18_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 67;
						$altura_16_30_max_temp += 67;
						$altura_17_30_max_temp += 67;
						$altura_18_30_max_temp += 67;
					}
				}
				
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 17;
						$altura_16_30_max_temp += 17;
						$altura_17_30_max_temp += 17;
						$altura_18_30_max_temp += 17;
						$altura_18_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
						$altura_18_30_max_temp += 37;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 56;
						$altura_16_30_max_temp += 56;
						$altura_17_30_max_temp += 56;
						$altura_18_30_max_temp += 56;
						$altura_18_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 27;
						$altura_15_30_max_temp += 27;
						$altura_16_30_max_temp += 27;
						$altura_17_30_max_temp += 27;
						$altura_18_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 44;
						$altura_15_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_18_30_max_temp += 44;
					}
				}
				*/	
				
				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	

				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '18:00:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_18_00 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_18_00 > 0){
		
		$horarios_18_00 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '18:00:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_18_00 = $linha2["id_horario"];
			
			array_push($horarios_18_00,$id_horario_18_00);
		}
		
		$altura_17_30_max = 45;
		$altura_18_30_max = 45;
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_18_00)){
			
			$id_horario_temp = $horarios_18_00[$loop_max];
			
			$altura_17_30_max_temp = 45;
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_17_30_max_temp += 62;
						$altura_18_30_max_temp += 62;
					}
					else if($max_turmas == 2){
						$altura_17_30_max_temp += 147;
						$altura_18_30_max_temp += 147;
					}
					else if($max_turmas == 3){
						$altura_17_30_max_temp += 229;
						$altura_18_30_max_temp += 229;
					}
					else if($max_turmas == 4){
						$altura_17_30_max_temp += 315;
						$altura_18_30_max_temp += 315;
					}
					else if($max_turmas == 5){
						$altura_17_30_max_temp += 398;
						$altura_18_30_max_temp += 398;
					}
				}
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_17_30_max_temp += 26;
						$altura_18_30_max_temp += 29;
					}
					else if($max_turmas == 2){
						$altura_17_30_max_temp += 57;
						$altura_18_30_max_temp += 95;
					}
					else if($max_turmas == 3){
						$altura_17_30_max_temp += 95;
						$altura_18_30_max_temp += 163;
					}
					else if($max_turmas == 4){
						$altura_17_30_max_temp += 128;
						$altura_18_30_max_temp += 225;
					}
					else if($max_turmas == 5){
						$altura_17_30_max_temp += 157;
						$altura_18_30_max_temp += 295;
					}
				}
				/*
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 45;
						$altura_17_30_max_temp += 60;
						$altura_18_30_max_temp += 45;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 78;
						$altura_17_30_max_temp += 108;
						$altura_18_30_max_temp += 78;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 112;
						$altura_17_30_max_temp += 157;
						$altura_18_30_max_temp += 112;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 130;
						$altura_17_30_max_temp += 220;
						$altura_18_30_max_temp += 130;
					}
				}
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 24;
						$altura_17_30_max_temp += 35;
						$altura_18_30_max_temp += 35;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 43;
						$altura_17_30_max_temp += 71;
						$altura_18_30_max_temp += 71;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 65;
						$altura_17_30_max_temp += 107;
						$altura_18_30_max_temp += 107;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 83;
						$altura_17_30_max_temp += 144;
						$altura_18_30_max_temp += 144;
					}
				}
				
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 16;
						$altura_16_30_max_temp += 21;
						$altura_17_30_max_temp += 21;
						$altura_18_30_max_temp += 16;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 36;
						$altura_16_30_max_temp += 53;
						$altura_17_30_max_temp += 53;
						$altura_18_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 58;
						$altura_16_30_max_temp += 86;
						$altura_17_30_max_temp += 86;
						$altura_18_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 75;
						$altura_16_30_max_temp += 115;
						$altura_17_30_max_temp += 115;
						$altura_18_30_max_temp += 75;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 6;
						$altura_16_30_max_temp += 9;
						$altura_17_30_max_temp += 9;
						$altura_18_30_max_temp += 9;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 22;
						$altura_16_30_max_temp += 36;
						$altura_17_30_max_temp += 36;
						$altura_18_30_max_temp += 36;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 36;
						$altura_16_30_max_temp += 61;
						$altura_17_30_max_temp += 61;
						$altura_18_30_max_temp += 61;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 50;
						$altura_16_30_max_temp += 87;
						$altura_17_30_max_temp += 87;
						$altura_18_30_max_temp += 87;
					}
				}
				
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 17;
						$altura_15_30_max_temp += 26;
						$altura_16_30_max_temp += 26;
						$altura_17_30_max_temp += 26;
						$altura_18_30_max_temp += 17;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 32;
						$altura_15_30_max_temp += 50;
						$altura_16_30_max_temp += 50;
						$altura_17_30_max_temp += 50;
						$altura_18_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 46;
						$altura_15_30_max_temp += 73;
						$altura_16_30_max_temp += 73;
						$altura_17_30_max_temp += 73;
						$altura_18_30_max_temp += 46;
					}
				}
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 16;
						$altura_16_30_max_temp += 16;
						$altura_17_30_max_temp += 16;
						$altura_18_30_max_temp += 16;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 22;
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
						$altura_18_30_max_temp += 37;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 31;
						$altura_15_30_max_temp += 55;
						$altura_16_30_max_temp += 55;
						$altura_17_30_max_temp += 55;
						$altura_18_30_max_temp += 55;
					}
				}
				
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_13_30_max_temp += 6;
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 6;
					}
					else if($max_turmas == 4){
						$altura_13_30_max_temp += 18;
						$altura_14_30_max_temp += 30;
						$altura_15_30_max_temp += 30;
						$altura_16_30_max_temp += 30;
						$altura_17_30_max_temp += 30;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_13_30_max_temp += 29;
						$altura_14_30_max_temp += 48;
						$altura_15_30_max_temp += 48;
						$altura_16_30_max_temp += 48;
						$altura_17_30_max_temp += 48;
						$altura_18_30_max_temp += 29;
					}
				}	
				*/

				if($altura_17_30_max_temp > $altura_17_30){
					$offset_17_30 = $altura_17_30_max_temp - $altura_17_30;
					$altura_17_30 = $altura_17_30_max_temp;
					$altura_final += $offset_17_30;
				}	
				
				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}	

			$loop_max += 1;
		}
		
	}

$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '18:30:00' AND h.semestre = $semestre;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$num_aulas_18_30 = $linha1["COUNT(a.id_horario)"];

	if($num_aulas_18_30 > 0){
		
		$horarios_18_30 = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.hora_inicio = '18:30:00' AND h.semestre = $semestre;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_horario_18_30 = $linha2["id_horario"];
			
			array_push($horarios_18_30,$id_horario_18_30);
		}
		
		$id_horario_max = 0;
		
		$loop_max = 0;
		while($loop_max < sizeof($horarios_18_30)){
			
			$id_horario_temp = $horarios_18_30[$loop_max];
			
			$altura_18_30_max_temp = 45;
			
			$statement3 = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario_temp;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$numero_horas_componente = $linha3["numero_horas"];
				
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_turma) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE h.id_horario = $id_horario_temp;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$max_turmas = $linha4["COUNT(DISTINCT a.id_turma)"];
				
				if($numero_horas_componente == 1){
					if($max_turmas == 1){
						$altura_18_30_max_temp += 60;
					}
					else if($max_turmas == 2){
						$altura_18_30_max_temp += 150;
					}
					else if($max_turmas == 3){
						$altura_18_30_max_temp += 230;
					}
					else if($max_turmas == 4){
						$altura_18_30_max_temp += 320;
					}
					else if($max_turmas == 5){
						$altura_18_30_max_temp += 405;
					}
				}
				/*
				else if($numero_horas_componente == 1.5){
					if($max_turmas == 1){
						$altura_17_30_max_temp += 30;
						$altura_18_30_max_temp += 22;
					}
					else if($max_turmas == 2){
						$altura_17_30_max_temp += 95;
						$altura_18_30_max_temp += 57;
					}
					else if($max_turmas == 3){
						$altura_17_30_max_temp += 162;
						$altura_18_30_max_temp += 94;
					}
					else if($max_turmas == 4){
						$altura_17_30_max_temp += 228;
						$altura_18_30_max_temp += 130;
					}
					else if($max_turmas == 5){
						$altura_17_30_max_temp += 295;
						$altura_18_30_max_temp += 167;
					}
				}
				else if($numero_horas_componente == 2){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_17_30_max_temp += 53;
						$altura_18_30_max_temp += 53;
					}
					else if($max_turmas == 3){
						$altura_17_30_max_temp += 95;
						$altura_18_30_max_temp += 95;
					}
					else if($max_turmas == 4){
						$altura_17_30_max_temp += 135;
						$altura_18_30_max_temp += 135;
					}
					else if($max_turmas == 5){
						$altura_17_30_max_temp += 180;
						$altura_18_30_max_temp += 180;
					}
				}
				
				else if($numero_horas_componente == 2.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 35;
						$altura_18_30_max_temp += 20;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 72;
						$altura_17_30_max_temp += 72;
						$altura_18_30_max_temp += 40;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 108;
						$altura_17_30_max_temp += 108;
						$altura_18_30_max_temp += 58;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 145;
						$altura_17_30_max_temp += 145;
						$altura_18_30_max_temp += 77;
					}
				}
				else if($numero_horas_componente == 3){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_16_30_max_temp += 22;
						$altura_17_30_max_temp += 22;
						$altura_18_30_max_temp += 22;
					}
					else if($max_turmas == 3){
						$altura_16_30_max_temp += 47;
						$altura_17_30_max_temp += 47;
						$altura_18_30_max_temp += 47;
					}
					else if($max_turmas == 4){
						$altura_16_30_max_temp += 75;
						$altura_17_30_max_temp += 75;
						$altura_18_30_max_temp += 75;
					}
					else if($max_turmas == 5){
						$altura_16_30_max_temp += 104;
						$altura_17_30_max_temp += 104;
						$altura_18_30_max_temp += 104;
					}
				}
				else if($numero_horas_componente == 3.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 7;
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 35;
						$altura_16_30_max_temp += 35;
						$altura_17_30_max_temp += 35;
						$altura_18_30_max_temp += 19;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 61;
						$altura_16_30_max_temp += 61;
						$altura_17_30_max_temp += 61;
						$altura_18_30_max_temp += 32;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 86;
						$altura_16_30_max_temp += 86;
						$altura_17_30_max_temp += 86;
						$altura_18_30_max_temp += 45;
					}
				}
				else if($numero_horas_componente == 4){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 23;
						$altura_16_30_max_temp += 23;
						$altura_17_30_max_temp += 23;
						$altura_18_30_max_temp += 23;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 45;
						$altura_16_30_max_temp += 45;
						$altura_17_30_max_temp += 45;
						$altura_18_30_max_temp += 45;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 67;
						$altura_16_30_max_temp += 67;
						$altura_17_30_max_temp += 67;
						$altura_18_30_max_temp += 67;
					}
				}
				
				else if($numero_horas_componente == 4.5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_15_30_max_temp += 17;
						$altura_16_30_max_temp += 17;
						$altura_17_30_max_temp += 17;
						$altura_18_30_max_temp += 17;
						$altura_18_30_max_temp += 8;
					}
					else if($max_turmas == 4){
						$altura_15_30_max_temp += 37;
						$altura_16_30_max_temp += 37;
						$altura_17_30_max_temp += 37;
						$altura_18_30_max_temp += 37;
						$altura_18_30_max_temp += 18;
					}
					else if($max_turmas == 5){
						$altura_15_30_max_temp += 56;
						$altura_16_30_max_temp += 56;
						$altura_17_30_max_temp += 56;
						$altura_18_30_max_temp += 56;
						$altura_18_30_max_temp += 27;
					}
				}
				else if($numero_horas_componente == 5){
					if($max_turmas == 1){
					}
					else if($max_turmas == 2){
					}
					else if($max_turmas == 3){
						$altura_14_30_max_temp += 10;
						$altura_15_30_max_temp += 10;
						$altura_16_30_max_temp += 10;
						$altura_17_30_max_temp += 10;
						$altura_18_30_max_temp += 10;
					}
					else if($max_turmas == 4){
						$altura_14_30_max_temp += 27;
						$altura_15_30_max_temp += 27;
						$altura_16_30_max_temp += 27;
						$altura_17_30_max_temp += 27;
						$altura_18_30_max_temp += 27;
					}
					else if($max_turmas == 5){
						$altura_14_30_max_temp += 44;
						$altura_15_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_16_30_max_temp += 44;
						$altura_18_30_max_temp += 44;
					}
				}
				*/	

				if($altura_18_30_max_temp > $altura_18_30){
					$offset_18_30 = $altura_18_30_max_temp - $altura_18_30;
					$altura_18_30 = $altura_18_30_max_temp;
					$altura_final += $offset_18_30;
				}

			$loop_max += 1;
		}
		
	}

/*
if($max_turmas_09_00 == 2){
	$altura_08_30 += 22;
	$altura_09_30 += 45;
	$altura_10_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_09_00 == 3){
	$altura_08_30 += 45;
	$altura_09_30 += 90;
	$altura_10_30 += 45;
	
	$altura_final += 180;
}

if($max_turmas_09_00 == 4){
	$altura_08_30 += 67;
	$altura_09_30 += 135;
	$altura_10_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_09_00 == 5){
	$altura_08_30 += 90;
	$altura_09_30 += 180;
	$altura_10_30 += 90;
	
	$altura_final += 360;
}*/
/*
if($max_turmas_09_30 == 2){
	$altura_09_30 += 45;
	$altura_10_30 += 45;
	$offset_09_30 = 90;
	$offset_10_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_09_30 == 3){
	if($horas_max_09_30 == 1){
		$altura_09_30 += 270;
		
		$altura_final += 270;
	}
	else if($horas_max_09_30 == 1.5){
		$altura_12_30 += 125;
		$altura_13_30 += 145;
		
		$altura_final += 270;
	}
	else if($horas_max_09_30 == 2){
		$altura_12_30 += 93;
		$altura_13_30 += 138;
		
		$altura_final += 231;
	}
	else if($horas_max_09_30 == 2.5){
		$altura_12_30 += 79;
		$altura_13_30 += 124;
		
		$altura_final += 203;
	}
	else if($horas_max_09_30 == 3){
		$altura_12_30 += 41;
		$altura_13_30 += 81;
		$altura_14_30 += 41;
		
		$altura_final += 163;
	}
	else if($horas_max_09_30 == 3.5){
		$altura_09_30 += 4;
		$altura_10_30 += 7;
		$altura_11_30 += 7;
		$altura_12_30 += 4;
		
		$altura_final += 22;
	}
	else if($horas_max_09_30 == 4){
		$altura_12_30 += 23;
		$altura_13_30 += 63;
		$altura_14_30 += 23;
		
		$altura_final += 109;
	}
	else if($horas_max_09_30 == 4.5){
		$altura_12_30 += 15;
		$altura_13_30 += 55;
		$altura_14_30 += 15;
		
		$altura_final += 85;
	}
	else if($horas_max_09_30 == 5){
		$altura_12_30 += 5;
		$altura_13_30 += 46;
		$altura_14_30 += 5;
		
		$altura_final += 56;
	}
}

if($max_turmas_09_30 == 4){
	$altura_09_30 += 135;
	$altura_10_30 += 135;	
	$offset_09_30 = 270;
	$offset_10_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_09_30 == 5){
	$altura_09_30 += 180;
	$altura_10_30 += 180;	
	$offset_09_30 = 360;
	$offset_10_30 = 180;
	
	$altura_final += 360;
}
*//*
if($max_turmas_10_00 == 2){
	$altura_09_30 += 22;
	$altura_10_30 += 45;
	$altura_11_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_10_00 == 3){
	$altura_09_30 += 45;
	$altura_10_30 += 90;
	$altura_11_30 += 45;
	
	$altura_final += 180;
}

if($max_turmas_10_00 == 4){
	$altura_09_30 += 67;
	$altura_10_30 += 135;
	$altura_11_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_10_00 == 5){
	$altura_09_30 += 90;
	$altura_10_30 += 180;
	$altura_11_30 += 90;
	
	$altura_final += 360;
}

if($max_turmas_10_30 == 2){
	$altura_10_30 += 45;
	$altura_11_30 += 45;
	$offset_10_30 = 90;
	$offset_11_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_10_30 == 3){
	$altura_10_30 += 90;
	$altura_11_30 += 90;	
	$offset_10_30 = 180;
	$offset_11_30 = 90;
	
	$altura_final += 180;
}

if($max_turmas_10_30 == 4){
	$altura_10_30 += 135;
	$altura_11_30 += 135;	
	$offset_10_30 = 270;
	$offset_11_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_10_30 == 5){
	$altura_10_30 += 180;
	$altura_11_30 += 180;	
	$offset_10_30 = 360;
	$offset_11_30 = 180;
	
	$altura_final += 360;
}

if($max_turmas_11_00 == 2){
	$altura_10_30 += 22;
	$altura_11_30 += 45;
	$altura_12_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_11_00 == 3){
	$altura_10_30 += 45;
	$altura_11_30 += 90;
	$altura_12_30 += 45;
	
	$altura_final += 180;
}

if($max_turmas_11_00 == 4){
	$altura_10_30 += 67;
	$altura_11_30 += 135;
	$altura_12_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_11_00 == 5){
	$altura_10_30 += 90;
	$altura_11_30 += 180;
	$altura_12_30 += 90;
	
	$altura_final += 360;
}*/
/*
if($max_turmas_11_30 == 2){
	$altura_11_30 += 45;
	$altura_12_30 += 45;
	$offset_11_30 = 90;
	$offset_12_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_11_30 == 3){
	$altura_11_30 += 90;
	$altura_12_30 += 90;	
	$offset_11_30 = 180;
	$offset_12_30 = 90;
	
	$altura_final += 180;
}

if($max_turmas_11_30 == 4){
	$altura_11_30 += 135;
	$altura_12_30 += 135;	
	$offset_11_30 = 270;
	$offset_12_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_11_30 == 5){
	$altura_11_30 += 180;
	$altura_12_30 += 180;	
	$offset_11_30 = 360;
	$offset_12_30 = 180;
	
	$altura_final += 360;
}
*/
/*
if($max_turmas_12_00 == 2){
	$altura_11_30 += 22;
	$altura_12_30 += 45;
	$altura_13_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_12_00 == 3){
	$altura_11_30 += 45;
	$altura_12_30 += 90;
	$altura_13_30 += 45;
	
	$altura_final += 180;
}

if($max_turmas_12_00 == 4){
	$altura_11_30 += 67;
	$altura_12_30 += 135;
	$altura_13_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_12_00 == 5){
	$altura_11_30 += 90;
	$altura_12_30 += 180;
	$altura_13_30 += 90;
	
	$altura_final += 360;
}

if($max_turmas_12_30 == 2){
	$altura_12_30 += 45;
	$altura_13_30 += 45;
	$offset_12_30 = 90;
	$offset_13_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_12_30 == 3){
	$altura_12_30 += 90;
	$altura_13_30 += 90;	
	$offset_12_30 = 180;
	$offset_13_30 = 90;
	
	$altura_final += 180;
}

if($max_turmas_12_30 == 4){
	$altura_12_30 += 135;
	$altura_13_30 += 135;	
	$offset_12_30 = 270;
	$offset_13_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_12_30 == 5){
	$altura_12_30 += 180;
	$altura_13_30 += 180;	
	$offset_12_30 = 360;
	$offset_13_30 = 180;
	
	$altura_final += 360;
}

if($max_turmas_13_00 == 2){
	$altura_12_30 += 22;
	$altura_13_30 += 45;
	$altura_14_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_13_00 == 3){
	if($horas_max_13_00 == 1){
		$altura_12_30 += 205;
		$altura_13_30 += 250;
		
		$altura_final += 455;
	}
	else if($horas_max_13_00 == 1.5){
		$altura_12_30 += 125;
		$altura_13_30 += 145;
		
		$altura_final += 270;
	}
	else if($horas_max_13_00 == 2){
		$altura_12_30 += 93;
		$altura_13_30 += 138;
		
		$altura_final += 231;
	}
	else if($horas_max_13_00 == 2.5){
		$altura_12_30 += 79;
		$altura_13_30 += 124;
		
		$altura_final += 203;
	}
	else if($horas_max_13_00 == 3){
		$altura_12_30 += 41;
		$altura_13_30 += 81;
		$altura_14_30 += 41;
		
		$altura_final += 163;
	}
	else if($horas_max_13_00 == 3.5){
		$altura_12_30 += 31;
		$altura_13_30 += 72;
		$altura_14_30 += 32;
		
		$altura_final += 135;
	}
	else if($horas_max_13_00 == 4){
		$altura_12_30 += 23;
		$altura_13_30 += 63;
		$altura_14_30 += 23;
		
		$altura_final += 109;
	}
	else if($horas_max_13_00 == 4.5){
		$altura_12_30 += 15;
		$altura_13_30 += 55;
		$altura_14_30 += 15;
		
		$altura_final += 85;
	}
	else if($horas_max_13_00 == 5){
		$altura_12_30 += 5;
		$altura_13_30 += 46;
		$altura_14_30 += 5;
		
		$altura_final += 56;
	}
}

if($max_turmas_13_00 == 4){
	$altura_12_30 += 67;
	$altura_13_30 += 135;
	$altura_14_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_13_00 == 5){
	$altura_12_30 += 90;
	$altura_13_30 += 180;
	$altura_14_30 += 90;
	
	$altura_final += 360;
}

if($max_turmas_13_30 == 2){
	$altura_13_30 += 45;
	$altura_14_30 += 45;
	$offset_13_30 = 90;
	$offset_14_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_13_30 == 3){
	$altura_13_30 += 90;
	$altura_14_30 += 90;	
	$offset_13_30 = 180;
	$offset_14_30 = 90;
	
	$altura_final += 180;
}

if($max_turmas_13_30 == 4){
	$altura_13_30 += 135;
	$altura_14_30 += 135;	
	$offset_13_30 = 270;
	$offset_14_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_13_30 == 5){
	$altura_13_30 += 180;
	$altura_14_30 += 180;	
	$offset_13_30 = 360;
	$offset_14_30 = 180;
	
	$altura_final += 360;
}

if($max_turmas_14_00 == 2){
	$altura_13_30 += 22;
	$altura_14_30 += 45;
	$altura_15_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_14_00 == 3){
	$altura_13_30 += 45;
	$altura_14_30 += 90;
	$altura_15_30 += 45;
	
	$altura_final += 180;
}

if($max_turmas_14_00 == 4){
	$altura_13_30 += 67;
	$altura_14_30 += 135;
	$altura_15_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_14_00 == 5){
	$altura_13_30 += 90;
	$altura_14_30 += 180;
	$altura_15_30 += 90;
	
	$altura_final += 360;
}

if($max_turmas_14_30 == 2){
	$altura_14_30 += 45;
	$altura_15_30 += 45;
	$offset_14_30 = 90;
	$offset_15_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_14_30 == 3){
	$altura_14_30 += 90;
	$altura_15_30 += 90;	
	$offset_14_30 = 180;
	$offset_15_30 = 90;
	
	$altura_final += 180;
}

if($max_turmas_14_30 == 4){
	$altura_14_30 += 135;
	$altura_15_30 += 135;	
	$offset_14_30 = 270;
	$offset_15_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_14_30 == 5){
	$altura_14_30 += 180;
	$altura_15_30 += 180;	
	$offset_14_30 = 360;
	$offset_15_30 = 180;
	
	$altura_final += 360;
}

if($max_turmas_15_00 == 2){
	$altura_14_30 += 22;
	$altura_15_30 += 45;
	$altura_16_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_15_00 == 3){
	$altura_14_30 += 45;
	$altura_15_30 += 90;
	$altura_16_30 += 45;
	
	$altura_final += 180;
}

if($max_turmas_15_00 == 4){
	$altura_14_30 += 67;
	$altura_15_30 += 135;
	$altura_16_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_15_00 == 5){
	$altura_14_30 += 90;
	$altura_15_30 += 180;
	$altura_16_30 += 90;
	
	$altura_final += 360;
}

if($max_turmas_15_30 == 2){
	$altura_15_30 += 45;
	$altura_16_30 += 45;
	$offset_15_30 = 90;
	$offset_16_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_15_30 == 3){
	$altura_15_30 += 90;
	$altura_16_30 += 90;	
	$offset_15_30 = 180;
	$offset_16_30 = 90;
	
	$altura_final += 180;
}

if($max_turmas_15_30 == 4){
	$altura_15_30 += 135;
	$altura_16_30 += 135;	
	$offset_15_30 = 270;
	$offset_16_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_15_30 == 5){
	$altura_15_30 += 180;
	$altura_16_30 += 180;	
	$offset_15_30 = 360;
	$offset_16_30 = 180;
	
	$altura_final += 360;
}

if($max_turmas_16_00 == 2){
	$altura_15_30 += 22;
	$altura_16_30 += 45;
	$altura_17_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_16_00 == 3){
	$altura_15_30 += 45;
	$altura_16_30 += 90;
	$altura_17_30 += 45;
	
	$altura_final += 180;
}

if($max_turmas_16_00 == 4){
	$altura_15_30 += 67;
	$altura_16_30 += 135;
	$altura_17_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_16_00 == 5){
	$altura_15_30 += 90;
	$altura_16_30 += 180;
	$altura_17_30 += 90;
	
	$altura_final += 360;
}

if($max_turmas_16_30 == 2){
	$altura_16_30 += 45;
	$altura_17_30 += 45;
	$offset_16_30 = 90;
	$offset_17_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_16_30 == 3){
	$altura_16_30 += 90;
	$altura_17_30 += 90;	
	$offset_16_30 = 180;
	$offset_17_30 = 90;
	
	$altura_final += 180;
}

if($max_turmas_16_30 == 4){
	$altura_16_30 += 135;
	$altura_17_30 += 135;	
	$offset_16_30 = 270;
	$offset_17_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_16_30 == 5){
	$altura_16_30 += 180;
	$altura_17_30 += 180;	
	$offset_16_30 = 360;
	$offset_17_30 = 180;
	
	$altura_final += 360;
}

if($max_turmas_17_00 == 2){
	$altura_16_30 += 22;
	$altura_17_30 += 45;
	$altura_18_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_17_00 == 3){
	$altura_16_30 += 45;
	$altura_17_30 += 90;
	$altura_18_30 += 45;
	
	$altura_final += 180;
}

if($max_turmas_17_00 == 4){
	$altura_16_30 += 67;
	$altura_17_30 += 135;
	$altura_18_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_17_00 == 5){
	$altura_16_30 += 90;
	$altura_17_30 += 180;
	$altura_18_30 += 90;
	
	$altura_final += 360;
}

if($max_turmas_17_30 == 2){
	$altura_17_30 += 45;
	$altura_18_30 += 45;
	$offset_17_30 = 90;
	$offset_18_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_17_30 == 3){
	$altura_17_30 += 90;
	$altura_18_30 += 90;	
	$offset_17_30 = 180;
	$offset_18_30 = 90;
	
	$altura_final += 180;
}

if($max_turmas_17_30 == 4){
	$altura_17_30 += 135;
	$altura_18_30 += 135;	
	$offset_17_30 = 270;
	$offset_18_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_17_30 == 5){
	$altura_17_30 += 180;
	$altura_18_30 += 180;	
	$offset_17_30 = 360;
	$offset_18_30 = 180;
	
	$altura_final += 360;
}

if($max_turmas_18_00 == 2){
	$altura_17_30 += 22;
	$altura_18_30 += 45;
	$altura_19_30 += 23;
	
	$altura_final += 90;
}

if($max_turmas_18_00 == 3){
	$altura_17_30 += 45;
	$altura_18_30 += 90;
	$altura_19_30 += 45;
	
	$altura_final += 180;
}

if($max_turmas_18_00 == 4){
	$altura_17_30 += 67;
	$altura_18_30 += 135;
	$altura_19_30 += 68;
	
	$altura_final += 270;
}

if($max_turmas_18_00 == 5){
	$altura_17_30 += 90;
	$altura_18_30 += 180;
	$altura_19_30 += 90;
	
	$altura_final += 360;
}

if($max_turmas_18_30 == 2){
	$altura_18_30 += 45;
	$altura_19_30 += 45;
	$offset_18_30 = 90;
	$offset_19_30 = 45;
	
	$altura_final += 90;
}

if($max_turmas_18_30 == 3){
	$altura_18_30 += 90;
	$altura_19_30 += 90;	
	$offset_18_30 = 180;
	$offset_19_30 = 90;
	
	$altura_final += 180;
}

if($max_turmas_18_30 == 4){
	$altura_18_30 += 135;
	$altura_19_30 += 135;	
	$offset_18_30 = 270;
	$offset_19_30 = 135;
	
	$altura_final += 270;
}

if($max_turmas_18_30 == 5){
	$altura_18_30 += 180;
	$altura_19_30 += 180;	
	$offset_18_30 = 360;
	$offset_19_30 = 180;
	
	$altura_final += 360;
}
*/
$altura_final_tabela = $altura_final . "px";
$altura_final_container_horas = ($altura_final - 46) . "px";

/*
else if($max_turmas_10_00 > 1){
	$altura_09_30 += $max_turmas_09_30 * 16;
}

if($max_turmas_11_30 > 1){
	$altura_11_30 += $max_turmas_11_30 * 45;
}
else if($max_turmas_12_00 > 1){
	$altura_11_30 += $max_turmas_11_30 * 22;
} */

//echo $max_turmas_08_30, "<br>", $max_turmas_09_00, "<br>", $max_turmas_09_30, "<br>", $max_turmas_10_00, "<br>", $max_turmas_10_30, "<br>", $max_turmas_11_00, "<br>", $max_turmas_11_30, "<br>", $max_turmas_12_00, "<br>", $max_turmas_12_30, "<br>", $max_turmas_13_00;
//echo "<br>",$altura_12_30;
?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-left:15px; margin-top:10px;">...</a> / <a href="visHorariosDocente.php?sem=<?php echo $semestre; ?>"> Horários - Docentes</a> / <a href="">Horários - <b><?php echo $nome_docente ?></b></a></h6>
			<h3 align="center" style="margin-left:15px; margin-top:20px; margin-bottom:30px;"> <b><?php echo $nome_docente, " (",$semestre, "ºS)" ?></b><!--<text style="margin-left:15px; font-size:17px; margin-bottom:35px; font-weight:550;"><i>(<?php echo $semestre; ?>º Semestre)</i></text> --></h3>
			
			<?php /*
				$statement2 = mysqli_prepare($conn, "SELECT * FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = 'SEG' AND semestre = $semestre ORDER BY hora_inicio;");
$statement2->execute();
$resultado2 = $statement2->get_result();
while($linha2 = mysqli_fetch_assoc($resultado2)){
	$id_horario = $linha2["id_horario"];
	$dia_semana = $linha2["dia_semana"];
	$hora_inicio = $linha2["hora_inicio"];
	$hora_fim = $linha2["hora_fim"];
			
	echo "DIA - ", $dia_semana, " / HORA_INICIO - ", $hora_inicio, " / HORA_FIM - ", $hora_fim, "<br>";
}

$statement2 = mysqli_prepare($conn, "SELECT * FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = 'TER' AND semestre = $semestre ORDER BY hora_inicio;");
$statement2->execute();
$resultado2 = $statement2->get_result();
while($linha2 = mysqli_fetch_assoc($resultado2)){
	$id_horario = $linha2["id_horario"];
	$dia_semana = $linha2["dia_semana"];
	$hora_inicio = $linha2["hora_inicio"];
	$hora_fim = $linha2["hora_fim"];
			
	echo "DIA - ", $dia_semana, " / HORA_INICIO - ", $hora_inicio, " / HORA_FIM - ", $hora_fim, "<br>";
}

$statement2 = mysqli_prepare($conn, "SELECT * FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = 'QUA' AND semestre = $semestre ORDER BY hora_inicio;");
$statement2->execute();
$resultado2 = $statement2->get_result();
while($linha2 = mysqli_fetch_assoc($resultado2)){
	$id_horario = $linha2["id_horario"];
	$dia_semana = $linha2["dia_semana"];
	$hora_inicio = $linha2["hora_inicio"];
	$hora_fim = $linha2["hora_fim"];
			
	echo "DIA - ", $dia_semana, " / HORA_INICIO - ", $hora_inicio, " / HORA_FIM - ", $hora_fim, "<br>";
}

$statement2 = mysqli_prepare($conn, "SELECT * FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = 'QUI' AND semestre = $semestre ORDER BY hora_inicio;");
$statement2->execute();
$resultado2 = $statement2->get_result();
while($linha2 = mysqli_fetch_assoc($resultado2)){
	$id_horario = $linha2["id_horario"];
	$dia_semana = $linha2["dia_semana"];
	$hora_inicio = $linha2["hora_inicio"];
	$hora_fim = $linha2["hora_fim"];
			
	echo "DIA - ", $dia_semana, " / HORA_INICIO - ", $hora_inicio, " / HORA_FIM - ", $hora_fim, "<br>";
}

$statement2 = mysqli_prepare($conn, "SELECT * FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = 'SEX' AND semestre = $semestre ORDER BY hora_inicio;");
$statement2->execute();
$resultado2 = $statement2->get_result();
while($linha2 = mysqli_fetch_assoc($resultado2)){
	$id_horario = $linha2["id_horario"];
	$dia_semana = $linha2["dia_semana"];
	$hora_inicio = $linha2["hora_inicio"];
	$hora_fim = $linha2["hora_fim"];
			
	echo "DIA - ", $dia_semana, " / HORA_INICIO - ", $hora_inicio, " / HORA_FIM - ", $hora_fim, "<br>";
}
echo $altura_final_tabela;
echo " - ", $altura_final_container_horas;  */
			?>
			
				<div class="tabela_horario_container" align="center" style="height:<?php echo $altura_final_tabela; ?>">
				<div class="tabela_horario_container_dias_semana">
					<div class="tabela_horario_dia_semana">
					</div>
					<div class="tabela_horario_dia_semana">
					<b>SEG</b>
					</div>
					<div class="tabela_horario_dia_semana">
					<b>TER</b>
					</div>
					<div class="tabela_horario_dia_semana">
					<b>QUA</b>
					</div>
					<div class="tabela_horario_dia_semana">
					<b>QUI</b>
					</div>
					<div class="tabela_horario_dia_semana" style="width:165px;">
					<b>SEX</b>
					</div>
				</div>
				<div class="tabela_horario_container_horas" style="height:<?php echo $altura_final_container_horas; ?>">
					<div class="tabela_horario_hora" style="height:<?php echo $altura_08_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo' >
							<b>08:30 - 09:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="height:<?php echo $altura_09_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>09:30 - 10:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="height:<?php echo $altura_10_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>10:30 - 11:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="height: <?php echo $altura_11_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>11:30 - 12:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="height: <?php echo $altura_12_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>12:30 - 13:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="height: <?php echo $altura_13_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>13:30 - 14:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="height: <?php echo $altura_14_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>14:30 - 15:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="height: <?php echo $altura_15_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>15:30 - 16:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="height: <?php echo $altura_16_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>16:30 - 17:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="height: <?php echo $altura_17_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>17:30 - 18:30</b>
						</div>
					</div>
					<div class="tabela_horario_hora" style="border-bottom:0px; height: <?php echo $altura_18_30; ?>px;">
						<div class='tabela_horario_divisao_conteudo'>
							<b>18:30 - 19:30</b>
						</div>
					</div>
				</div>
				<?php
					$loop_dias_semana = 0;
					while($loop_dias_semana < sizeof($dias_semana)){
						$dia_semana = $dias_semana[$loop_dias_semana];
						$offset_semana = $offsets_semana[$loop_dias_semana];
						$offset_vertical = $offsets_verticais[$loop_dias_semana];
						
						?>
						<?php
						if($loop_dias_semana == 4){
							echo "<div class='tabela_horario_container_dia_semana' style='width:164px; border-right:0px; height:$altura_final_container_horas;'>";
						}
						else{
							echo "<div class='tabela_horario_container_dia_semana' style='height:$altura_final_container_horas;'>";
						}
						//echo "CONTAINER_", $dia_semana;
						
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_08_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];
									
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_08_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_08_30 + ceil($altura_09_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_08_30 + $altura_09_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_08_30 + $altura_09_30 + ceil($altura_10_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30 + $altura_11_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_08_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_08_30 + ceil($altura_09_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_08_30 + $altura_09_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_08_30 + $altura_09_30 + ceil($altura_10_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30 + $altura_11_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_08_30 + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_08_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_08_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
												
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + ceil($altura_09_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + ceil($altura_10_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_08_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + ceil($altura_09_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + ceil($altura_10_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + ceil($altura_09_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + ceil($altura_10_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_08_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + ceil($altura_09_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + ceil($altura_10_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_08_30 / 2) + $altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_09_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];
										
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_09_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_09_30 + ceil($altura_10_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_09_30 + $altura_10_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_09_30 + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_09_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_09_30 + ceil($altura_10_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_09_30 + $altura_10_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_09_30 + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_09_30 + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_09_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_09_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
												
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + ceil($altura_10_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_09_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + ceil($altura_10_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + ceil($altura_10_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_09_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + ceil($altura_10_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_09_30 / 2) + $altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_10_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
									
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];									
									
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_10_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_10_30 + ceil($altura_11_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_10_30 + $altura_11_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_10_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_10_30 + ceil($altura_11_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_10_30 + $altura_11_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_10_30 + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_10_30 + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_10_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_10_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement6->execute();
												$resultado6 = $statement6->get_result();
												$linha6 = mysqli_fetch_assoc($resultado6);
													$nome_sala = $linha6["nome_sala"];
													
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_10_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_10_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + ceil($altura_11_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_10_30 / 2) + $altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_11_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];
									
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_11_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_11_30 + ceil($altura_12_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_11_30 + $altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_11_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_11_30 + ceil($altura_12_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_11_30 + $altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_11_30 + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_11_30 + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_11_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_11_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
												
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_11_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_11_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + ceil($altura_12_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_11_30 / 2) + $altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_12_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];
									
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_12_30 + ceil($altura_13_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_12_30 + $altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_12_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_12_30 + ceil($altura_13_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_12_30 + $altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_12_30 + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_12_30 + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
								
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_12_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_12_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
												
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_12_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_12_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + ceil($altura_13_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_12_30 / 2) + $altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_13_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];
									
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_13_30 + ceil($altura_14_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_13_30 + $altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_13_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_13_30 + ceil($altura_14_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_13_30 + $altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_13_30 + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_13_30 + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
								
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_13_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_13_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
												
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_13_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													$margem_top = ceil($altura_13_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + ceil($altura_14_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_13_30 / 2) + $altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_14_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];
									
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + ceil($altura_15_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_14_30 + $altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_14_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + ceil($altura_15_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_14_30 + $altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
								
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_14_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_14_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
												
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}/*
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_14_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}/*
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_14_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + ceil($altura_15_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_15_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];
									
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + ceil($altura_16_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_15_30 + $altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										/*
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										*/
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_15_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + ceil($altura_16_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_15_30 + $altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										/*
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										*/
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
								
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_15_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_15_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
																										
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
												
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_15_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_15_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + ceil($altura_16_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
				?>
				<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_16_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_16_30 + ceil($altura_17_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_16_30 + $altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										/*
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										*/
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_16_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_16_30 + ceil($altura_17_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_16_30 + $altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										/*
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										*/
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
								
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_16_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_16_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
												
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_16_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_16_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + ceil($altura_17_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_17_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];
									
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										/*
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										*/
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_17_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										/*
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										*/
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
								
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_17_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_17_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
												
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_17_30 / 2) + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_17_30 / 2) + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_17_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_17_30 / 2) + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_17_30 / 2) + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_17_30 / 2) + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_17_30 / 2) + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_17_30 / 2) . "px";
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_17_30 / 2) + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_17_30 / 2) + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													/*
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
					?>
					<?php
					
							$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND h.semestre = $semestre;");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							$altura_div = ($altura_18_30) . "px";
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND h.semestre = $semestre;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
									$statement6 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$nome_sala = $linha6["nome_sala"];
									
									$turmas = array();
									
									$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									while($linha9 = mysqli_fetch_assoc($resultado9)){
										$id_turma = $linha9["id_turma"];
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma  = $linha10["nome"];

										array_push($turmas,$id_turma);
									}	
									
									$num_turmas = sizeof($turmas);
									
									$statement11 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
										$sigla_completa_curso = $linha11["sigla_completa"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										/*
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_18_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_18_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										*/
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											$altura = ($altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										/*
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											$altura = ($altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											$altura = ($altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											$altura = ($altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											$altura = ($altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											$altura = ($altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
											echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											$altura = ($altura_14_30 + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
											echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										*/
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura_div;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
								
									$loop_turmas = 0;
									while($loop_turmas < sizeof($turmas)){
										$numero_turma = substr($turmas[$loop_turmas],strlen($turmas[$loop_turmas]) - 1,1);
										
										echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
									
									$loop_turmas += 1;
									}
									echo "</div></div>";
								
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND h.semestre = $semestre;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_div; border-bottom:0px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_div; border-bottom:0px;'></div>";
												}
											}
											else{
												$altura_metade = ($altura_18_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade; border-bottom:0px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade; border-bottom:0px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
											if($offset_vertical > 0 && $offset_semana == 0.5){
												$altura_metade = ($altura_18_30 / 2) . "px";
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:$altura_metade; border-bottom:0px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:$altura_metade; border-bottom:0px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_docente = $id_docente AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND h.semestre = $semestre;");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
												$statement16 = mysqli_prepare($conn, "SELECT s.nome_sala FROM sala s INNER JOIN horario h ON s.id_sala = h.id_sala WHERE h.id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$nome_sala = $linha16["nome_sala"];
															
												$turmas = array();
												
												$statement19 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_horario = $id_horario ORDER BY id_turma;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												while($linha19 = mysqli_fetch_assoc($resultado19)){
													$id_turma = $linha19["id_turma"];
													
													$statement20 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
													$statement20->execute();
													$resultado20 = $statement20->get_result();
													$linha20 = mysqli_fetch_assoc($resultado20);
														$nome_turma  = $linha20["nome"];

													array_push($turmas,$id_turma);
												}	
												
												$num_turmas = sizeof($turmas);
												
												$statement21 = mysqli_prepare($conn, "SELECT sigla, sigla_completa FROM curso WHERE id_curso = $id_curso;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$sigla_curso = $linha21["sigla"];
													$sigla_completa_curso = $linha21["sigla_completa"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													/*
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_18_30 / 2) + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_17_30 / 2) + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_18_30 / 2) . "px";
													/*
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_17_30 / 2) + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_17_30 / 2) + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; width:164px; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top; width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
											else{
												if($offset_vertical > 0){
													/*
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_17_30 / 2) + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_17_30 / 2) + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
												else{
													$margem_top = ceil($altura_18_30 / 2) . "px";
													/*
													if($numero_horas_componente == 1){
														$offset_vertical = 1;
														$altura = (floor($altura_17_30 / 2) + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														$altura = (floor($altura_17_30 / 2) + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:$margem_top; border-top:1px solid #000000; height:$altura;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														$offset_vertical = 1;
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														$altura = (floor($altura_16_30 / 2) + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														$offset_vertical = 1;
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														$altura = (floor($altura_15_30 / 2) + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30) . "px";
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														$offset_vertical = 1;
														$altura = (floor($altura_14_30 / 2) + $altura_15_30 + $altura_16_30 + $altura_17_30 + $altura_18_30 + ceil($altura_18_30 / 2)) . "px";
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; height:$altura; margin-top:$margem_top;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}*/
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
										
												$loop_turmas = 0;
												while($loop_turmas < sizeof($turmas)){
													$nome_turmas = $turmas[$loop_turmas];
													$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
													
													echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $sigla_completa_curso, ".", $ano, ".", $numero_turma, "<br>", $nome_sala, "</text><br>";
												
												$loop_turmas += 1;
												}
												echo "</div></div>";
											
										}
							}
							echo "</div>";
						$loop_dias_semana += 1;
					}
					?>
			</div>
			
		</div>    
	</div>
</div>

</main>
<script language="javascript">
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_HORARIOS");
	var li_DSD_especifico = document.getElementById("li_HORARIOS_DOCENTES");
	
	li_DSD.style.background = "#4a6f96";
	li_DSD_especifico.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function teste(){
	alert("TESTE");
}

</script>
<?php gerarHome2() ?>
