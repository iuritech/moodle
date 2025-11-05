<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_utc'])){

		$id_utc = $_POST['id_utc'];
		
		$statement = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$nome_utc = $linha["nome_utc"];
			
			echo $nome_utc;
		
	}
?>