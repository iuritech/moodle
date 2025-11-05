<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['semestre'])){

		$semestre = $_POST['semestre'];
		
		$_SESSION['semestre'] = $semestre;
	}
?>