<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
		$salas_nao_abertas = array();
		
		array_push($salas_nao_abertas,"");
		array_push($salas_nao_abertas,"");
		
		$statement = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'A' ORDER BY nome_sala;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_sala = $linha["id_sala"];
			$nome_sala = $linha["nome_sala"];
			
			array_push($salas_nao_abertas,$id_sala);
			array_push($salas_nao_abertas,$nome_sala);
		}
		
		array_push($salas_nao_abertas,"");
		array_push($salas_nao_abertas,"");
		
		$statement1 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'B' ORDER BY nome_sala;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			$id_sala = $linha1["id_sala"];
			$nome_sala = $linha1["nome_sala"];
			
			array_push($salas_nao_abertas,$id_sala);
			array_push($salas_nao_abertas,$nome_sala);
		}
		
		array_push($salas_nao_abertas,"");
		array_push($salas_nao_abertas,"");
		
		$statement2 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'C' ORDER BY nome_sala;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_sala = $linha2["id_sala"];
			$nome_sala = $linha2["nome_sala"];
			
			array_push($salas_nao_abertas,$id_sala);
			array_push($salas_nao_abertas,$nome_sala);
		}
		
		array_push($salas_nao_abertas,"");
		array_push($salas_nao_abertas,"");
		
		$statement3 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'D' ORDER BY nome_sala;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		while($linha3 = mysqli_fetch_assoc($resultado3)){
			$id_sala = $linha3["id_sala"];
			$nome_sala = $linha3["nome_sala"];
			
			array_push($salas_nao_abertas,$id_sala);
			array_push($salas_nao_abertas,$nome_sala);
		}
		
		$List = implode(",", $salas_nao_abertas);
		print_r($List);
		
?>