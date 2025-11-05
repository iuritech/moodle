<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id'])){

		$id = $_POST['id'];
		
		$nome_img = array();
		
		$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$nome = $linha['nome'];
			$img_perfil = $linha['imagem_perfil'];
			array_push($nome_img, $nome);
			array_push($nome_img, $img_perfil);
		}
		
		$List = implode(",", $nome_img);
		print_r($List);
		
	}
	
?>