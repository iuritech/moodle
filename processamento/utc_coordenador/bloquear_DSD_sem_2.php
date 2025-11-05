<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_utc'])){

		$id_utc = $_POST["id_utc"];
	
		$statement = mysqli_prepare($conn, "UPDATE utc SET dsd_2_sem = 1 WHERE id_utc = $id_utc;");
		$statement->execute();
		
	}
?>