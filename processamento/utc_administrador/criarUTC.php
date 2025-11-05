<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['nome']) && isset($_POST['sigla']) && isset($_POST['id_responsavel'])){

		$nome = $_POST['nome'];
		$sigla = $_POST['sigla'];
		$id_responsavel = $_POST['id_responsavel'];
		
		$statement = mysqli_prepare($conn, "INSERT INTO utc(id_utc, nome_utc, id_responsavel, sigla_utc, dsd_1_sem, dsd_2_sem) 
											VALUES (NULL, '$nome', $id_responsavel, '$sigla', 0, 0);");
		$statement->execute();
		
		$id_utc = mysqli_insert_id($conn);
		
		$statement1 = mysqli_prepare($conn, "UPDATE utilizador SET id_utc = $id_utc WHERE id_utilizador = $id_responsavel;");
		$statement1->execute();
	}
?>