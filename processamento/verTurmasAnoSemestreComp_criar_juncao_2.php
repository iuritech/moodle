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
				
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma, nome FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso ORDER BY nome;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_array($resultado2)){
			$id_turma_temp = $linha2['id_turma'];
			$nome_turma = $linha2["nome"];
			
			$statement3 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_turma = $id_turma_temp 
												AND id_componente = $id_componente;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_array($resultado3);
				$count_juncao = $linha3['COUNT(id_juncao)'];
			
				if($count_juncao == 0 && $id_turma_temp != $id_turma){
					array_push($turmas, $id_turma_temp);
					array_push($turmas, $nome_turma);		
					array_push($turmas, $id_componente);
				}
		}

		$List = implode(",", $turmas);
		print_r($List);
		
	}
	
?>