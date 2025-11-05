<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id_disciplina'])){

		$id_disciplina = $_POST['id_disciplina'];

		$array_dados = array();

		$statement1 = mysqli_prepare($conn, "SELECT id_componente, id_tipocomponente FROM componente WHERE id_disciplina = $id_disciplina;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			
			$id_componente = $linha1["id_componente"];
			$id_tipocomponente = $linha1["id_tipocomponente"];
			
			$statement2 = mysqli_prepare($conn, "SELECT nome_tipocomponente, sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			$linha2 = mysqli_fetch_assoc($resultado2);
			
				$nome_tipocomponente = $linha2["nome_tipocomponente"];
				$sigla_tipocomponente = $linha2["sigla_tipocomponente"];
	
				array_push($array_dados,$sigla_tipocomponente);
				array_push($array_dados,$nome_tipocomponente); 
				array_push($array_dados,$id_componente);
				
		}
		
		echo json_encode($array_dados);

	}
?>