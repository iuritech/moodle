<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
	
	if(isset($_POST['id_utc']) && isset($_POST['semestre_atual'])){	
	
		$id_utc = $_POST['id_utc'];
		$semestre_atual = $_POST['semestre_atual'];
		$turmas_nao_abertas = array();
		
		$loop_turmas = 0;
		
		$statement = mysqli_prepare($conn, "SELECT id_curso, sigla FROM curso WHERE id_utc = $id_utc;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_curso = $linha["id_curso"];
			$sigla_curso = $linha["sigla"] . "     ";
				
			$statement2 = mysqli_prepare($conn, "SELECT COUNT(id_turma) FROM turma WHERE id_curso = $id_curso AND semestre = $semestre_atual;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			$linha2 = mysqli_fetch_assoc($resultado2);
				$numero_turmas_curso = $linha2["COUNT(id_turma)"];
				
				if($numero_turmas_curso > 0){
					
					if($loop_turmas > 0){
						array_push($turmas_nao_abertas,"disabled");
						array_push($turmas_nao_abertas,"");
					}
					
					array_push($turmas_nao_abertas,"disabled");
					array_push($turmas_nao_abertas,$sigla_curso);
								
					$statement2 = mysqli_prepare($conn, "SELECT id_turma, nome, ano FROM turma WHERE id_curso = $id_curso AND semestre = $semestre_atual ORDER BY ano, nome;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					while($linha2 = mysqli_fetch_assoc($resultado2)){
						$id_turma = $linha2["id_turma"];
						$nome_turma = $linha2["nome"];
						$ano_turma = $linha2["ano"] . "ºA";
							
						$nome_final = $ano_turma . " - " . $nome_turma;
						
						array_push($turmas_nao_abertas,$id_turma);
						array_push($turmas_nao_abertas,$nome_final);
						
						$loop_turmas += 1;
					}
						
				}
		}
		
		$List = implode(",", $turmas_nao_abertas);
		print_r($List);
		
	}
?>