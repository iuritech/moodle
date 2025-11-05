<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_sala'])){

		$id_sala = $_POST['id_sala'];
		
		$statement = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$nome_sala = $linha["nome_sala"];
			
		echo $nome_sala;
	}
?>