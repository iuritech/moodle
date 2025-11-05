<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST["id_utc_curso"])){
		
		$id_utc_curso = $_POST["id_utc_curso"];
		$docentes = array();
		
		$statement = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_curso;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$nome_utc_curso = $linha["nome_utc"];
			
			array_push($docentes,$nome_utc_curso);
			
			$statement1 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc = $id_utc_curso ORDER BY nome;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_assoc($resultado1)){
				$id_docente = $linha1["id_utilizador"];
				$nome_docente = $linha1["nome"];
				
				array_push($docentes,$id_docente);
				array_push($docentes,$nome_docente);
			}
			
			$docentes_final = implode(",",$docentes);
			print_r($docentes_final);
	
	}
	
?>