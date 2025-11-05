<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
		
	if(isset($_POST['id_utc'])){

		$id_utc = $_POST["id_utc"];
		$result = array();
	
		$statement = mysqli_prepare($conn, "SELECT dsd_1_sem, dsd_2_sem FROM utc WHERE id_utc = $id_utc;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$dsd_1_sem = $linha["dsd_1_sem"];
			$dsd_2_sem = $linha["dsd_2_sem"];	
			
			array_push($result,$dsd_1_sem);
			array_push($result,$dsd_2_sem);
			
			
		$List = implode(",",$result);
		print_r($List);
		
	}
?>