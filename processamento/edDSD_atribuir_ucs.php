<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
	
	if(isset($_POST["dados"]) && isset($_POST["id_docente"])){
		
		$id_docente = $_POST["id_docente"];
		
		$dados = $_POST["dados"];
		$dados_temp = implode(",",$dados);
		$dados_final = explode("_",$dados_temp);
		
		$counter = 0;
		while($counter < sizeof($dados_final)){
			
			$dados_uc = $dados_final[$counter];
			$array_dados_label = explode(",",$dados_uc);
			
			$array_dados_label_final = array_values(array_filter($array_dados_label));
			
			$id_uc = $array_dados_label_final[0];
			$id_componente = $array_dados_label_final[1];
			$juntar = $array_dados_label_final[sizeof($array_dados_label_final) - 1];
			$int_juntar = 0;
			if(!$juntar){
				$int_juntar = 1;
			}
			
			$array_turmas = array_slice($array_dados_label_final,2,-1);
			
			$array_turmas_final = implode(",",$array_turmas);
			
			$statement0 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma IN ($array_turmas_final);");
			$statement0->execute();
			$resultado0 = $statement0->get_result();
			$linha0 = mysqli_fetch_assoc($resultado0);
				$count_juncao = $linha0["COUNT(id_juncao)"];
			
				echo "COUNT_JUNCAO: ", $count_juncao, " JUNTAR: ", $juntar;
			
			/*--------1º CASO - Atribuir turmas normalmente--------*/
			if($count_juncao == 0 && strlen($juntar) < 10){
				//echo "CASO 1";
				
				$loop = 0;
				while($loop < sizeof($array_turmas)){
					$id_turma_temp = $array_turmas[$loop];
				
					$statement1 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
					$statement1->execute();
				
					$loop += 1;
				}
				
			}
			/*--------2º CASO - Atribuir turmas normalmente, mas uma delas já está numa junção--------*/
			else if($count_juncao != 0 && strlen($juntar) < 10){
				//echo "CASO 2", $juntar;
				if($juntar == "false"){
					//echo "TESTE1";
					//Atribuir individualmente
					
					$turmas_contabilizadas = array();
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma IN ($array_turmas_final) AND id_juncao IS NOT NULL;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					while($linha1 = mysqli_fetch_assoc($resultado1)){
						$id_juncao = $linha1["id_juncao"];
						
						$statement3 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_juncao = $id_juncao;;");
						$statement3->execute();	
					}
					
					//Restantes turmas
					$loop = 0;
					while($loop < sizeof($array_turmas)){
						$id_turma_temp = $array_turmas[$loop];
					
						if(!in_array($id_turma_temp,$turmas_contabilizadas)){
							$statement4 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
							$statement4->execute();
							array_push($turmas_contabilizadas,$id_turma_temp);
						}
						$loop += 1;
					}
						
				}
				else{
					//echo "TESTE2";
					//Juntar turmas selecionadas à junção selecionada
					
					$statement1 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma IN ($array_turmas_final) AND id_juncao IS NOT NULL;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$id_juncao = $linha1["id_juncao"];
						
						$loop = 0;
						while($loop < sizeof($array_turmas)){
							$id_turma_temp = $array_turmas[$loop];
						
							$statement2 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
							$statement2->execute();
								
							$loop += 1;
						}
						
						$statement3 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_juncao = $id_juncao;");
						$statement3->execute();	
					
				}
			}
			/*--------3º CASO - Criar uma junção do zero--------*/
			else if($count_juncao == 0 && strlen($juntar) >= 10){
				//echo "CASO 3";
				
				$statement1 = mysqli_prepare($conn, "INSERT INTO juncao(nome_juncao) VALUES ('$juntar');");
				$statement1->execute();
					
				$id_juncao = mysqli_insert_id($conn);
				
				$statement2 = mysqli_prepare($conn, "INSERT INTO juncao_componente VALUES ($id_juncao,$id_componente);");
				$statement2->execute();
				
				$loop = 0;
				while($loop < sizeof($array_turmas)){
					$id_turma_temp = $array_turmas[$loop];
				
					$statement3 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
					$statement3->execute();
					
					$statement4 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
					$statement4->execute();
				
					$loop += 1;
				}
			}
			/*--------4º CASO - Adicionar turmas a uma junção, sendo que uma das turmas selecionadas está numa junção--------*/
			else if($count_juncao != 0 && strlen($juntar) >= 10){
				//echo "CASO 4";
				
			}
			
			
			//print_r($array_turmas);
			/*
			if($juntar != "false"){
				//Ver se alguma das turmas selecionadas já esta numa junção
				
				//Criar a junção
				$nome_juncao = $juntar;
				
				$statement1 = mysqli_prepare($conn, "INSERT INTO juncao(nome_juncao) VALUES ('$juntar');");
				$statement1->execute();
					
				$id_juncao = mysqli_insert_id($conn);
				
				$statement2 = mysqli_prepare($conn, "INSERT INTO juncao_componente VALUES ($id_juncao,$id_componente);");
				$statement2->execute();
			}
			
			$num_turmas = (sizeof($array_dados_label_final) - 3);
			
			$array_turmas = array();
		
			$i = 0;
			while($i < $num_turmas){
				$id_turma = $array_dados_label_final[2 + $i];
				array_push($array_turmas,$id_turma);
				
				//echo "UC: ", $id_uc, " COMP: ", $id_componente, " TURMA: ", $id_turma, " DOCENTE: ", $id_docente;
				
				if($juntar == "false"){
					$statement = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_componente = $id_componente AND id_turma = $id_turma;");
					$statement->execute();
				}
				else{ 
					$statement3 = mysqli_prepare($conn, "UPDATE aula SET id_docente = $id_docente WHERE id_componente = $id_componente AND id_turma = $id_turma;");
					$statement3->execute();
					
					$statement4 = mysqli_prepare($conn, "UPDATE aula SET id_juncao = $id_juncao WHERE id_componente = $id_componente AND id_turma = $id_turma;");
					$statement4->execute(); 
				}
				
				$i = $i + 1;
			}
			
			//print_r($array_turmas);
			*/
			$counter = $counter + 1;
		}
		
	/*	print_r($dados);
		echo $dados_temp;
		print_r($dados_final); */
		/*
		$statement0 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_array($resultado0);
			$id_disciplina = $linha0["id_disciplina"];
			
		$statement1 = mysqli_prepare($conn, "SELECT ano, semestre, id_curso FROM disciplina WHERE id_disciplina = $id_disciplina;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_array($resultado1);
			$ano = $linha1["ano"];
			$semestre = $linha1["semestre"];
			$id_curso = $linha1["id_curso"];
				
		$turmas = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso ORDER BY nome;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_array($resultado2)){
			$id_turma = $linha2['id_turma'];
			array_push($turmas, $id_turma);
		}
		
		$turmas_final = implode(",",$turmas);
			
		$turmas_final_final = array();
			
		$statement3 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_componente = $id_componente;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		$linha3 = mysqli_fetch_array($resultado3);
			$num_docentes = $linha3['COUNT(id_docente)'];
		
			if($num_docentes < sizeof($turmas)){
				
				$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente IS NULL;");
				$statement4->execute();
				$resultado4 = $statement4->get_result();
				while($linha4 = mysqli_fetch_array($resultado4)){
					$id_turma = $linha4['id_turma'];
					
					$statement5 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					$linha5 = mysqli_fetch_array($resultado5);
						$nome_turma = $linha5['nome'];
						
					$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
					$statement6->execute();
					$resultado6 = $statement6->get_result();
					$linha6 = mysqli_fetch_array($resultado6);
						$num_juncoes = $linha6['COUNT(id_juncao)'];
						
						if($num_juncoes > 0){
							
							$statement7 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
							$statement7->execute();
							$resultado7 = $statement7->get_result();
							$linha7 = mysqli_fetch_array($resultado7);
								$id_juncao = $linha7['id_juncao'];
								
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM juncao_componente WHERE id_juncao = $id_juncao;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							while($linha8 = mysqli_fetch_array($resultado8)){
								$num_componentes_diferentes = $linha8['COUNT(DISTINCT id_componente)'];
								
								$num_juncoes = $num_componentes_diferentes;
							}
						}
									
					if(!in_array($id_turma,$turmas_final_final)){
						array_push($turmas_final_final,$id_turma);
						array_push($turmas_final_final,$nome_turma);
						array_push($turmas_final_final,$num_juncoes);
					}
				}
			}

		$List = implode(",", $turmas_final_final);
		print_r($List);
		*/
	}
	
?>