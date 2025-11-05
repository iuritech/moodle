<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_area'])){

		$id_area = $_POST['id_area'];
	
		$statement = mysqli_prepare($conn, "DELETE FROM area WHERE id_area = $id_area;");
		$statement->execute();
		
	}
?>