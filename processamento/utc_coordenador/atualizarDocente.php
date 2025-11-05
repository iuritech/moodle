<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_docente']) && isset($_POST['login']) && isset($_POST['password']) && isset($_POST['nome']) && isset($_POST['id_utc']) && isset($_POST['id_area']) && isset($_POST['id_funcao'])){

		$id_docente = $_POST['id_docente'];
		$login = $_POST['login'];
		$password = $_POST['password'];
		$nome = $_POST['nome'];
		$id_utc = $_POST['id_utc'];
		$id_area = $_POST['id_area'];
		$id_funcao = $_POST['id_funcao'];
		
		$statement = mysqli_prepare($conn, "UPDATE utilizador SET login = '$login' WHERE id_utilizador = $id_docente;");
		$statement->execute();
		
		$statement1 = mysqli_prepare($conn, "UPDATE utilizador SET password = '$password' WHERE id_utilizador = $id_docente;");
		$statement1->execute();
		
		$statement2 = mysqli_prepare($conn, "UPDATE utilizador SET nome = '$nome' WHERE id_utilizador = $id_docente;");
		$statement2->execute();
		
		$statement3 = mysqli_prepare($conn, "UPDATE utilizador SET id_utc = '$id_utc' WHERE id_utilizador = $id_docente;");
		$statement3->execute();
		
		$statement4 = mysqli_prepare($conn, "UPDATE utilizador SET id_area = '$id_area' WHERE id_utilizador = $id_docente;");
		$statement4->execute();
		
		$statement5 = mysqli_prepare($conn, "UPDATE utilizador SET id_funcao = '$id_funcao' WHERE id_utilizador = $id_docente;");
		$statement5->execute();
	}
?>