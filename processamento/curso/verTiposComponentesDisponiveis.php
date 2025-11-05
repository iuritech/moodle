<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	$tipos_componentes = array();
		
	$statement = mysqli_prepare($conn, "SELECT * FROM tipo_componente ORDER BY id_tipocomponente;");
	$statement->execute();
	$resultado = $statement->get_result();
	while($linha = mysqli_fetch_assoc($resultado)){
		$id_tipocomponente = $linha["id_tipocomponente"];
		$nome_tipocomponente = $linha["nome_tipocomponente"];
		$sigla_tipocomponente = $linha["sigla_tipocomponente"];
		
		array_push($tipos_componentes,$id_tipocomponente);
		array_push($tipos_componentes,$nome_tipocomponente);
		array_push($tipos_componentes,$sigla_tipocomponente);
	}
	
	$tipos_componentes_final = implode(",",$tipos_componentes);
	print_r($tipos_componentes_final);
	
?>