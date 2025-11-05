<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
		$ids = array();
		
		$statement = mysqli_prepare($conn, "SELECT * FROM utilizador ORDER BY nome;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id = $linha['id_utilizador'];
			array_push($ids, $id);
		}
		
		$List = implode(",", $ids);
		print_r($List);
		
?>