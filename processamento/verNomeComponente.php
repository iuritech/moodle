<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["id_componente"])){
		
		$id_componente = $_POST["id_componente"];
		
		$statement0 = mysqli_prepare($conn, "SELECT tc.sigla_tipocomponente FROM tipo_componente tc INNER JOIN 
											componente c ON tc.id_tipocomponente = c.id_tipocomponente WHERE c.id_componente 
											= $id_componente;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_array($resultado0);
			$nome_componente = $linha0["sigla_tipocomponente"];
			
			echo $nome_componente;
	}
	
?>