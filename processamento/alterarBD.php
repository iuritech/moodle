<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_GET['nome_bd'])){

		$nome_bd = $_GET['nome_bd'];
		
		$_SESSION["bd"] = $nome_bd;
		
		header("Location: http://localhost/apoio_utc/home.php");
	}
?>