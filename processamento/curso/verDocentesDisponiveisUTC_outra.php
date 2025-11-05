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
		
		$statement = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc != $id_utc_curso ORDER BY nome;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_docente = $linha["id_utilizador"];
			$nome_docente = $linha["nome"];
			
			array_push($docentes,$id_docente);
			array_push($docentes,$nome_docente);
		}
		
		$docentes_final = implode(",",$docentes);
		print_r($docentes_final);
	
	}
	
?>