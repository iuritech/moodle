<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if(isset($_POST['dia']) && (isset($_POST['hora'])) && (isset($_POST['numero_horas'])) && (isset($_POST['id_sala'])) && (isset($_POST['id_componente'])) && (isset($_POST['id_docente'])) && (isset($_POST['id_turma'])) && (isset($_POST['id_juncao'])) && (isset($_POST['semestre']))){	

		$dia = $_POST["dia"];
		$hora = $_POST["hora"];
		$numero_horas = $_POST["numero_horas"];
		$id_sala = $_POST["id_sala"];
		$id_componente = $_POST["id_componente"];
		$id_docente = $_POST["id_docente"];
		$id_turma = $_POST["id_turma"];
		$id_juncao = $_POST["id_juncao"];
		$semestre = $_POST["semestre"];

		$dia_semana = null;
		$hora_inicio = null;
		$hora_fim = null;
		
		if($dia == 0){
			$dia_semana = "SEG";
		}
		else if($dia == 1){
			$dia_semana = "TER";
		}
		else if($dia == 2){
			$dia_semana = "QUA";
		}
		else if($dia == 3){
			$dia_semana = "QUI";
		}
		else if($dia == 4){
			$dia_semana = "SEX";
		}
		
		if($hora == 8.5){
		
			$hora_inicio = "08:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "09:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "10:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "10:30:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "11:00:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "11:30:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "12:00:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "12:30:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "13:00:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "13:30:00";
			}
		}
		else if($hora == 9){
		
			$hora_inicio = "09:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "10:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "10:30:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "11:00:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "11:30:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "12:00:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "12:30:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "13:00:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "13:30:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "14:00:00";
			}
		}
		else if($hora == 9.5){
		
			$hora_inicio = "09:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "10:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "11:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "11:30:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "12:00:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "12:30:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "13:00:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "13:30:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "14:00:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "14:30:00";
			}
		}
		else if($hora == 10){
		
			$hora_inicio = "10:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "11:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "11:30:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "12:00:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "12:30:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "13:00:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "13:30:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "14:00:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "14:30:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "15:00:00";
			}
		}
		else if($hora == 10.5){
		
			$hora_inicio = "10:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "11:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "12:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "12:30:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "13:00:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "13:30:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "14:00:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "14:30:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "15:00:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "15:30:00";
			}
		}
		else if($hora == 11){
		
			$hora_inicio = "11:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "12:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "12:30:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "13:00:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "13:30:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "14:00:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "14:30:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "15:00:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "15:30:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "16:00:00";
			}
		}
		else if($hora == 11.5){
		
			$hora_inicio = "11:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "12:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "13:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "13:30:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "14:00:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "14:30:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "15:00:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "15:30:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "16:00:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "16:30:00";
			}
		}
		else if($hora == 12){
		
			$hora_inicio = "12:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "13:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "13:30:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "14:00:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "14:30:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "15:00:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "15:30:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "16:00:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "16:30:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "17:00:00";
			}
		}
		else if($hora == 12.5){
		
			$hora_inicio = "12:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "13:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "14:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "14:30:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "15:00:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "15:30:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "16:00:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "16:30:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "17:00:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "17:30:00";
			}
			
		}
		else if($hora == 13){
		
			$hora_inicio = "13:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "14:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "14:30:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "15:00:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "15:30:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "16:00:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "16:30:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "17:00:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "17:30:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "18:00:00";
			}
		}
		else if($hora == 13.5){
		
			$hora_inicio = "13:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "14:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "15:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "15:30:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "16:00:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "16:30:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "17:00:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "17:30:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "18:00:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "18:30:00";
			}
		}
		else if($hora == 14){
		
			$hora_inicio = "14:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "15:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "15:30:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "16:00:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "16:30:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "17:00:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "17:30:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "18:00:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "18:30:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "19:00:00";
			}
		}
		else if($hora == 14.5){
			$hora_inicio = "14:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "15:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "16:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "16:30:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "17:00:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "17:30:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "18:00:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "18:30:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "19:00:00";
			}
			else if($numero_horas == 5){
				$hora_fim = "19:30:00";
			}
		}
		else if($hora == 15){
		
			$hora_inicio = "15:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "16:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "16:30:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "17:00:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "17:30:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "18:00:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "18:30:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "19:00:00";
			}
			else if($numero_horas == 4.5){
				$hora_fim = "19:30:00";
			}
		}
		else if($hora == 15.5){
		
			$hora_inicio = "15:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "16:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "17:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "17:30:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "18:00:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "18:30:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "19:00:00";
			}
			else if($numero_horas == 4){
				$hora_fim = "19:30:00";
			}
		}
		else if($hora == 16){
		
			$hora_inicio = "16:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "17:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "17:30:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "18:00:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "18:30:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "19:00:00";
			}
			else if($numero_horas == 3.5){
				$hora_fim = "19:30:00";
			}
		}
		else if($hora == 16.5){
		
			$hora_inicio = "16:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "17:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "18:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "18:30:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "19:00:00";
			}
			else if($numero_horas == 3){
				$hora_fim = "19:30:00";
			}
		}
		else if($hora == 17){
		
			$hora_inicio = "17:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "18:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "18:30:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "19:00:00";
			}
			else if($numero_horas == 2.5){
				$hora_fim = "19:30:00";
			}
		}
		else if($hora == 17.5){
		
			$hora_inicio = "17:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "18:30:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "19:00:00";
			}
			else if($numero_horas == 2){
				$hora_fim = "19:30:00";
			}
		}
		else if($hora == 18){
		
			$hora_inicio = "18:00:00";
			
			if($numero_horas == 1){
				$hora_fim = "19:00:00";
			}
			else if($numero_horas == 1.5){
				$hora_fim = "19:30:00";
			}
		}
		else if($hora == 18.5){
		
			$hora_inicio = "18:30:00";
			
			if($numero_horas == 1){
				$hora_fim = "19:30:00";
			}
		}
		
		//Inserir na tabela junção
		$statement = mysqli_prepare($conn, "INSERT INTO horario(dia_semana,hora_inicio,hora_fim,id_sala,semestre) VALUES ('$dia_semana','$hora_inicio','$hora_fim',$id_sala,$semestre);");
		$statement->execute();
		
		$id_horario = mysqli_insert_id($conn);
		
		if($id_juncao == 0){
		
			$statement2 = mysqli_prepare($conn, "UPDATE aula SET id_horario = $id_horario WHERE id_componente = $id_componente AND id_turma = $id_turma AND id_docente = $id_docente;");
			$statement2->execute();
		
		}
		
		else{
		
			$statement2 = mysqli_prepare($conn, "UPDATE aula SET id_horario = $id_horario WHERE id_juncao = $id_juncao;");
			$statement2->execute();
		
		}
		
		//echo $dia, " - ", $hora, " - ", $numero_horas, " - ", $id_sala, " - ", $id_docente, " - ", $id_turma, " - ", $id_juncao, " - ", $semestre;
	}