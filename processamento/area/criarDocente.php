<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['nome']) && isset($_POST['id_utc']) && isset($_POST['id_area']) && isset($_POST['id_funcao'])){

		$login = $_POST['login'];
		$password = $_POST['password'];
		$nome = $_POST['nome'];
		$id_utc = $_POST['id_utc'];
		$id_area = $_POST['id_area'];
		$id_funcao = $_POST['id_funcao'];
		$imagem = "https://i.ibb.co/n7bp9g2/perfil-default.jpg";
		
		$statement = mysqli_prepare($conn, "INSERT INTO utilizador(id_utilizador,nome,login,password,imagem_perfil,id_utc,id_area,id_funcao,is_admin) 
											VALUES (NULL,'$nome','$login','$password','$imagem',$id_utc,$id_area,$id_funcao,0);");
		$statement->execute();
	}
?>