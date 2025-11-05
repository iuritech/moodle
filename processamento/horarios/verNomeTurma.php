<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_turma'])){

		$id_turma = $_POST['id_turma'];
		
		$statement = mysqli_prepare($conn, "SELECT nome, ano FROM turma WHERE id_turma = $id_turma;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$nome_turma = $linha["nome"];
			$ano_turma = $linha["ano"];
			
			$nome_final = $ano_turma . "ºA - " . $nome_turma;
			
		echo $nome_final;
	}
?>