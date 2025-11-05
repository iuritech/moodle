<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id'])){

		$id_juncao = $_POST['id'];
		$sql = "UPDATE componente_turma SET id_juncao = NULL WHERE id_juncao = $id_juncao;";
		
		if (mysqli_query($conn, $sql)) {
			
			$sql2 = "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao;";
			if (mysqli_query($conn, $sql2)) {
			
				$sql3 = "DELETE FROM juncao WHERE id_juncao = $id_juncao;";
				if (mysqli_query($conn, $sql3)) {
					echo "Junção removida com sucesso!";
				}
				else {
					echo "Erro ao apagar junção: " . mysqli_error($conn);
				}
			
			}
			else {
				echo "Erro ao apagar junção: " . mysqli_error($conn);
			}
			
			
		} else {
			echo "Erro ao apagar junção: " . mysqli_error($conn);
		}
	}
	
?>