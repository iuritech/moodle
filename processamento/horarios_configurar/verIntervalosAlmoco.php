<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');

	if(isset($_GET['id_utc']) && (isset($_GET['semestre_atual']))){	

		$id_utc = $_GET["id_utc"];
		$semestre_atual = $_GET["semestre_atual"];
		
		$array_dados = array();
		
		$array_dias_semana = array("SEG","TER","QUA","QUI","SEX");
	
		//DOCENTE
		
		$statement = mysqli_prepare($conn, "SELECT DISTINCT id_utilizador FROM utilizador WHERE id_utc = $id_utc;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_docente = $linha["id_utilizador"];
		
			$counter_dia_semana = 0;
			
			while($counter_dia_semana < sizeof($array_dias_semana)){
				
				$dia_semana = $array_dias_semana[$counter_dia_semana];
				
				$array_dia_semana = array();
		
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					
					}
					
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,5.0);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,5.0);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,5.0);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,5.0);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,5.0);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,5.0);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,5.0);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,5.0);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,5.0);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							
					
					}
		
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					
					}
					
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					
					}
		
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					
					}
		
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					
					}
				
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					
					}
				
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
					
					}
				
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					
					}
				
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					}
				
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					}
				
				$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$tem_aula = $linha["COUNT(h.id_horario)"];
		
					if($tem_aula == 0){
						array_push($array_dia_semana,0);
					}
					else{
						$statement = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$id_horario = $linha["h.id_horario"];
							
						$statement = mysqli_prepare($conn, "SELECT c.numero_horas FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numero_horas = $linha["c.numero_horas"];
					
							if($numero_horas == 1){
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 1.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 2){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 2.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 3){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 3.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 4){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							else if($numero_horas == 4.5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,0.5);
							}
							else if($numero_horas == 5){
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
								array_push($array_dia_semana,1);
							}
							
					
					}
				
					$counter_dia_semana += 1;
		
			}
		
		}
		
		
		$id_docente = 4;
		
		$array_dias_semana = array("SEG","TER","QUA","QUI","SEX");
		$offset_aula = 0;
		
		$counter_dia_semana = 0;
		$counter_array = 0;
		
		while($counter_dia_semana < sizeof($array_dias_semana)){
			
			$dia_semana = $array_dias_semana[$counter_dia_semana];
	
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
				$counter_array += 1;
				
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
				
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$tem_aula = $linha["COUNT(h.id_horario)"];
				
				if($tem_aula == 0){
					
					$statement = mysqli_prepare($conn, "SELECT COUNT(h.id_horario) FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$tem_aula_pontual = $linha["COUNT(h.id_horario)"];
					
						if($tem_aula_pontual == 0){
							if($offset_aula > 0.5){
								if($array_dados[$counter_array] != 1){
									$array_dados[$counter_array] = 1;
								}
								$offset_aula -= 1;
							}
							else if($offset_aula == 0.5){
								if($array_dados[$counter_array] == 5.0){
									$array_dados[$counter_array] = 1;
								}
								else if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 0.5;
								}
								$offset_aula -= 0.5;
							}
						}
						else{
							
							$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
							$statement1->execute();
							$resultado1 = $statement1->get_result();
							$linha1 = mysqli_fetch_assoc($resultado1);
								$id_horario_pontual = $linha1["id_horario"];
							
							$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario_pontual;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							$linha2 = mysqli_fetch_assoc($resultado2);
								$id_componente_pontual = $linha2["id_componente"];
						
							$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente_pontual;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							$linha3 = mysqli_fetch_assoc($resultado3);
								$numero_horas_pontual = $linha3["numero_horas"];
								
							$offset_aula = $numero_horas_pontual - 0.5;
						
							if($array_dados[$counter_array] == 0){
									$array_dados[$counter_array] = 5.0;
							}
							else if($array_dados[$counter_array] == 0.5){
									$array_dados[$counter_array] = 1;
							}
									
						}
					
				}
				else{
					
					$statement1 = mysqli_prepare($conn, "SELECT h.id_horario FROM horario h INNER JOIN aula a ON h.id_horario = a.id_horario WHERE h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00' AND a.id_docente = $id_docente AND semestre = $semestre_atual;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_horario = $linha1["id_horario"];
					
					$statement2 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_docente IS NOT NULL AND id_horario = $id_horario;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$id_componente = $linha2["id_componente"];
				
					$statement3 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$numero_horas = $linha3["numero_horas"];
					
						$offset_aula = $numero_horas - 1;
						
						if($array_dados[$counter_array] == 0 || $array_dados[$counter_array] == 0.5 || $array_dados[$counter_array] == 5.0){
							$array_dados[$counter_array] = 1;
						}
				}
			
			$counter_array += 1;
			
			$counter_dia_semana += 1;
		}
		
		echo json_encode($array_dados);
		
	}