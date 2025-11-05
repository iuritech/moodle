<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_utc'])){
		
		$id_utc = $_POST["id_utc"];
		
		$docentes_nao_abertos = array();
		
		array_push($docentes_nao_abertos,"");
		array_push($docentes_nao_abertos,"");
		
		$statement = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc = $id_utc ORDER BY nome;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_utilizador = $linha["id_utilizador"];
			$nome_utilizador = $linha["nome"];
			
			array_push($docentes_nao_abertos,$id_utilizador);
			array_push($docentes_nao_abertos,$nome_utilizador);
		}
		
		$List = implode(",", $docentes_nao_abertos);
		print_r($List);
	}	
		
?>