<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["ano"]) && isset($_POST["semestre"]) && isset($_POST["id_componente"]) && isset($_POST["id_curso"]) && isset($_POST["id_turma"])){
		
		$ano = $_POST["ano"];
		$semestre = $_POST["semestre"];
		$id_componente = $_POST["id_componente"];
		$id_curso = $_POST["id_curso"];
		$id_turma = $_POST["id_turma"];
	
		$turmas = array();
				
		$statement = mysqli_prepare($conn, "SELECT id_turma, nome FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso AND id_turma != $id_turma ORDER BY nome;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_array($resultado)){
			$id_turma = $linha['id_turma'];
			$nome_turma = $linha["nome"];
			
			$statement1 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_turma = $id_turma 
												AND id_componente = $id_componente;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			$linha1 = mysqli_fetch_array($resultado1);
				$num_juncoes_turma_comp = $linha1['COUNT(id_juncao)'];
			
				if($num_juncoes_turma_comp == 0){
					array_push($turmas, $id_turma);
					array_push($turmas, $nome_turma);		
					array_push($turmas, $id_componente);
				}
		}

		$List = implode(",", $turmas);
		print_r($List);
		
	}
	
?>