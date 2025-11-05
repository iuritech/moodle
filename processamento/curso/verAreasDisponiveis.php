<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	$areas = array();
		
	$statement = mysqli_prepare($conn, "SELECT id_area, nome FROM area ORDER BY nome;");
	$statement->execute();
	$resultado = $statement->get_result();
	while($linha = mysqli_fetch_assoc($resultado)){
		$id_area = $linha["id_area"];
		$nome_area = $linha["nome"];
		
		array_push($areas,$id_area);
		array_push($areas,$nome_area);
	}
	
	$areas_final = implode(",",$areas);
	print_r($areas_final);
	
?>