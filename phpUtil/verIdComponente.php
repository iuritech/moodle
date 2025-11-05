<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['curso']) && isset($_POST['sigla_disciplina']) && isset($_POST['componente'])){

		$curso = trim($_POST['curso']);
		$sigla = trim($_POST['sigla_disciplina']);
		$componente = trim($_POST['componente']);
		
		$statement = mysqli_prepare($conn, "SELECT id_curso FROM curso WHERE sigla = '$curso';");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id_curso = $linha['id_curso'];
		
			$statement2 = mysqli_prepare($conn, "SELECT id_disciplina FROM disciplina WHERE abreviacao_uc = '$sigla' AND id_curso = $id_curso;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_array($resultado2)){
				$id_disciplina = $linha2['id_disciplina'];
				//echo $id_disciplina;
				
				$statement3 = mysqli_prepare($conn, "SELECT id_tipocomponente FROM tipo_componente WHERE sigla_tipocomponente = '$componente';");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				while($linha3 = mysqli_fetch_array($resultado3)){
					$id_tipocomponente = $linha3['id_tipocomponente'];
					
					$statement4 = mysqli_prepare($conn, "SELECT c.id_componente FROM componente c WHERE c.id_disciplina = $id_disciplina AND c.id_tipocomponente = $id_tipocomponente;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					while($linha4 = mysqli_fetch_array($resultado4)){
						$id_componente = $linha4['id_componente'];
						echo $id_componente;
					}
				}
			}
		
		}
	}
	
?>