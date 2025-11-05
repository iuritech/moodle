<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
	
	if(isset($_POST["id_utc"])){
		
		$id_utc = $_POST["id_utc"];
		$areas = array();
		
		$statement = mysqli_prepare($conn, "SELECT id_area, nome FROM area WHERE id_utc = $id_utc ORDER BY nome;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id_area = $linha['id_area'];
			$nome = $linha["nome"];
			
			array_push($areas,$id_area);
			array_push($areas,$nome);
		}
		
		$List = implode(",", $areas);
		print_r($List);
		
	}
?>