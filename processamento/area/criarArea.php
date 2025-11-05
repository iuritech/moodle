<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['nome_introduzido']) && isset($_POST['nome_completo']) && isset($_POST['id_utc']) && isset($_POST['imagem'])){

		$nome = $_POST['nome_introduzido'];
		$nome_completo = $_POST['nome_completo'];
		$id_utc = $_POST['id_utc'];
		$imagem = $_POST['imagem'];
	
		$statement = mysqli_prepare($conn, "INSERT INTO area(id_area,nome,id_utc,nome_completo,imagem) 
											VALUES (NULL,'$nome',$id_utc,'$nome_completo','$imagem');");
		$statement->execute();
		
		$id_area = mysqli_insert_id($conn);
		
		echo $id_area;
	}
?>