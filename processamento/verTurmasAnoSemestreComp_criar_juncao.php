<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["ano"]) && isset($_POST["semestre"]) && isset($_POST["id_componente"]) && isset($_POST["id_curso"])){
		
		$ano = $_POST["ano"];
		$semestre = $_POST["semestre"];
		$id_curso = $_POST["id_curso"];
		$id_componente = $_POST["id_componente"];
		
		if(!isset($_POST["array_turmas_temp"])){
			
			$turmas = array();
					
			$statement = mysqli_prepare($conn, "SELECT id_turma, nome FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso ORDER BY nome;");
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
					$count_juncao = $linha1['COUNT(id_juncao)'];
					
				$statement2 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_array($resultado2);
					$sigla_curso = $linha2['sigla'];
					
				$statement3 = mysqli_prepare($conn, "SELECT d.abreviacao_uc FROM disciplina d INNER JOIN componente c ON 
													d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				$linha3 = mysqli_fetch_array($resultado3);
					$sigla_uc = $linha3['abreviacao_uc'];
					
				$statement4 = mysqli_prepare($conn, "SELECT tc.sigla_tipocomponente FROM tipo_componente tc INNER JOIN 
													componente c ON tc.id_tipocomponente = c.id_tipocomponente WHERE 
													c.id_componente = $id_componente;");
				$statement4->execute();
				$resultado4 = $statement4->get_result();
				$linha4 = mysqli_fetch_array($resultado4);
					$sigla_componente = $linha4['sigla_tipocomponente'];
				
					if($count_juncao == 0){
						array_push($turmas, $id_turma);
						array_push($turmas, $nome_turma);		
						array_push($turmas, $id_componente);
						array_push($turmas, $sigla_curso);
						array_push($turmas, $sigla_uc);
						array_push($turmas, $sigla_componente);
						array_push($turmas, $ano);		
						array_push($turmas, $semestre);
					}
			}

			$List = implode(",", $turmas);
			print_r($List);
			
		}
		
		else{
		//echo "ANO: ", $ano, " SEM: ", $semestre, " COMP: ", $id_componente, " CURSO: ", $id_curso, " JUNÇÃO: ", $id_juncao;
		
			$array_turmas_temp = $_POST["array_turmas_temp"];
		
			$turmas = array();
					
			$statement = mysqli_prepare($conn, "SELECT id_turma, nome FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso ORDER BY nome;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_array($resultado)){
				$id_turma = $linha['id_turma'];
				$nome_turma = $linha["nome"];
				
				$loop = 0;
				$turma_ja_esta = 0;
				while($loop < sizeof($array_turmas_temp)){
									
					$id_turma_temp = $array_turmas_temp[$loop];
					$id_componente_temp = $array_turmas_temp[$loop + 1];

					if($id_turma_temp == $id_turma/* && $id_componente_temp == $id_componente*/){
						$turma_ja_esta = 1;
					}
											
					$loop = $loop + 2;
				}
				
				$statement1 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_turma = $id_turma 
													AND id_componente = $id_componente;");
				$statement1->execute();
				$resultado1 = $statement1->get_result();
				$linha1 = mysqli_fetch_array($resultado1);
					$count_juncao = $linha1['COUNT(id_juncao)'];
				
				$statement2 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_array($resultado2);
					$sigla_curso = $linha2['sigla'];
					
				$statement3 = mysqli_prepare($conn, "SELECT d.abreviacao_uc FROM disciplina d INNER JOIN componente c ON 
													d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				$linha3 = mysqli_fetch_array($resultado3);
					$sigla_uc = $linha3['abreviacao_uc'];
					
				$statement4 = mysqli_prepare($conn, "SELECT tc.sigla_tipocomponente FROM tipo_componente tc INNER JOIN 
													componente c ON tc.id_tipocomponente = c.id_tipocomponente WHERE 
													c.id_componente = $id_componente;");
				$statement4->execute();
				$resultado4 = $statement4->get_result();
				$linha4 = mysqli_fetch_array($resultado4);
					$sigla_componente = $linha4['sigla_tipocomponente'];
				
					if($count_juncao == 0 && $turma_ja_esta == 0){
						array_push($turmas, $id_turma);
						array_push($turmas, $nome_turma);		
						array_push($turmas, $id_componente);
						array_push($turmas, $sigla_curso);
						array_push($turmas, $sigla_uc);
						array_push($turmas, $sigla_componente);
						array_push($turmas, $ano);		
						array_push($turmas, $semestre);
					}
			}

			$List = implode(",", $turmas);
			print_r($List);
		
		}
	}
	
?>