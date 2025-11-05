<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
	
	if(isset($_POST["nome_introduzido"]) && isset($_POST["sigla_introduzida"])){
		
		$nome_introduzido = $_POST["nome_introduzido"];
		$sigla_introduzida = $_POST["sigla_introduzida"];
		
		$array_result = array();
		
		$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_utc) FROM utc WHERE nome_utc = '$nome_introduzido';");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_array($resultado);
			$num_utcs_mesmo_nome = $linha['COUNT(DISTINCT id_utc)'];
			
			if($num_utcs_mesmo_nome == 0){
				array_push($array_result,0);
			}
			else{
				array_push($array_result,1);
			}
		
		$statement1 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_utc) FROM utc WHERE sigla_utc = '$sigla_introduzida';");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_array($resultado1);
			$num_utcs_mesma_sigla = $linha1['COUNT(DISTINCT id_utc)'];
			
			if($num_utcs_mesma_sigla == 0){
				array_push($array_result,0);
			}
			else{
				array_push($array_result,1);
			}
		
		$List = implode(",", $array_result);
		print_r($List);
		
	}
?>