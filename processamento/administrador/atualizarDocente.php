<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_docente']) && isset($_POST['login']) && isset($_POST['password']) && isset($_POST['nome']) && isset($_POST['id_utc']) && isset($_POST['id_area']) && isset($_POST['id_funcao']) && isset($_POST['is_admin']) && isset($_POST['perm_horarios'])){

		$id_docente = $_POST['id_docente'];
		$login = $_POST['login'];
		$password = $_POST['password'];
		$nome = $_POST['nome'];
		$id_utc = $_POST['id_utc'];
		$id_area = $_POST['id_area'];
		$id_funcao = $_POST['id_funcao'];
		$is_admin = $_POST['is_admin'];
		$perm_horarios = $_POST['perm_horarios'];
		
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
		
		$statement6 = mysqli_prepare($conn, "UPDATE utilizador SET is_admin = $is_admin WHERE id_utilizador = $id_docente;");
		$statement6->execute();
		
		$statement6 = mysqli_prepare($conn, "UPDATE utilizador SET perm_horarios = $perm_horarios WHERE id_utilizador = $id_docente;");
		$statement6->execute();
	}
?>