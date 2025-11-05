<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_docente'])){

		$id_docente = $_POST['id_docente'];
	
		$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_docente = $id_docente;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$num_juncoes_docente = $linha["COUNT(DISTINCT id_juncao)"];
		
			if($num_juncoes_docente == 0){
				$statement1 = mysqli_prepare($conn, "UPDATE aula SET id_docente = NULL WHERE id_docente = $id_docente;");
				$statement1->execute();
				
				$statement2 = mysqli_prepare($conn, "DELETE FROM utilizador WHERE id_utilizador = $id_docente;");
				$statement2->execute();
			}
			else{
				$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_docente = $id_docente;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				while($linha3 = mysqli_fetch_assoc($resultado3)){
					$id_juncao = $linha3["id_juncao"];
					
					$statement4 = mysqli_prepare($conn, "UPDATE aula SET id_docente = NULL WHERE id_juncao = $id_juncao;");
					$statement4->execute();
				}
				
				$statement5 = mysqli_prepare($conn, "UPDATE aula SET id_docente = NULL WHERE id_docente = $id_docente;");
				$statement5->execute();
				
				$statement6 = mysqli_prepare($conn, "DELETE FROM utilizador WHERE id_utilizador = $id_docente;");
				$statement6->execute();
			}
	}
?>