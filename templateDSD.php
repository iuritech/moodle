<?php

session_start();

include('bd.h');
include('bd_final.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

$id_utc_post = $_POST["id_utc"];

$ano_letivo_temp = explode("_",$_SESSION["bd"]);
$ano_letivo = $ano_letivo_temp[2] . "/" . $ano_letivo_temp[3];
$ano_letivo_underscore = $ano_letivo_temp[2] . "_" . $ano_letivo_temp[3];

$statement000 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_post;");
$statement000->execute();
$resultado000 = $statement000->get_result();
$linha000 = mysqli_fetch_assoc($resultado000);
	$nome_utc = $linha000["nome_utc"];
	$nome_utc_upper_case = mb_strtoupper($nome_utc);

//Ler o template
$reader = IOFactory::createReader('Xlsx');
$spreadsheet1 = $reader->load("template_DSD.xlsx");
$sheet1 = $spreadsheet1->getActiveSheet();

$cell_utc_template = $sheet1->getCell('C5');
$utc_template = $cell_utc_template->getValue();

$utc_template = $utc_template . $nome_utc_upper_case;

$sheet1->setCellValue('C5',$utc_template);

$sheet1
	->getStyle('B7:N9')
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);

/*
$sheet1->getColumnDimension('A')->setWidth('0');
$sheet1->removeColumnByIndex(20);

$sheet1->removeRow(1,1);
*/

$cello = $sheet1->getCell('C6');
$o = $cello->getValue();
$cell1 = $sheet1->getCell('D6');
$á = $cell1->getValue();

$sheet1->setCellValue('C4','ANO LECTIVO ' . $ano_letivo);
$sheet1->setCellValue('C6','');
$sheet1->setCellValue('D6','');

$b = 10;

$array_ids_juncoes = array();
$array_letras = array('a)','b)','c)','d)','e)','f)','g)','h)','i)','j)','k)','l)','m)','n)','o)','p)','q)','r)','s)','t)','u)','v)','w)','x)','y)','z)',);
$contador_letras = 0;

$counter_docente_cincento = 1;

$statement0 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utc = $id_utc_post and id_funcao != 6 ORDER BY nome;");
$statement0->execute();
$resultado0 = $statement0->get_result();
while($linha0 = mysqli_fetch_assoc($resultado0)){
	$id_utilizador = $linha0["id_utilizador"];
	$nome_utilizador = $linha0["nome"];
	$id_area_utilizador = $linha0["id_area"];
	$id_funcao_utilizador = $linha0["id_funcao"];
	
	$juncoes_ja_contabilizadas_1_sem = array();
	$juncoes_ja_contabilizadas_2_sem = array();
	
	$statement1 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area_utilizador;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_area_utilizador = $linha1["nome"];
		
	$statement2 = mysqli_prepare($conn, "SELECT nome FROM funcao WHERE id_funcao = $id_funcao_utilizador;");
	$statement2->execute();
	$resultado2 = $statement2->get_result();
	$linha2 = mysqli_fetch_assoc($resultado2);
		$nome_funcao_utilizador = $linha2["nome"];
	
	$array_componentes = array();
	
	$array_disciplinas = array();
	
	$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $id_utilizador;");
	$statement3->execute();
	$resultado3 = $statement3->get_result();
	while($linha3 = mysqli_fetch_assoc($resultado3)){
		$id_componente = $linha3["id_componente"];
		
		$statement4 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
		$statement4 ->execute();
		$resultado4 = $statement4->get_result();
		$linha4 = mysqli_fetch_assoc($resultado4);
			$id_disciplina = $linha4["id_disciplina"];
			
			$statement42 = mysqli_prepare($conn, "SELECT c.id_utc FROM disciplina d INNER JOIN curso c ON d.id_curso = c.id_curso 
													WHERE d.id_disciplina = $id_disciplina;");
			$statement42 ->execute();
			$resultado42 = $statement42->get_result();
			$linha42 = mysqli_fetch_assoc($resultado42);
				$id_utc_disciplina = $linha42["id_utc"];
				
		/*		if($id_utc_disciplina == $id_utc_post){ */
					array_push($array_componentes,$id_componente);
					array_push($array_disciplinas,$id_disciplina);
		/*		} */
	}
	
	$array_componentes_final = implode("','",$array_componentes);
	$array_disciplinas_unique = array_unique($array_disciplinas);
	$array_disciplinas_final = implode("','",$array_disciplinas_unique);
	
	if(sizeof($array_disciplinas_unique) == 0){
		$offset_docente = sizeof($array_disciplinas_unique) + 3;
	}
	else{
	$offset_docente = sizeof($array_disciplinas_unique) + 2;
	}
	
	$sheet1->setCellValue('B' . $b, $nome_utilizador);
	$sheet1->setCellValue('C' . $b, $nome_funcao_utilizador);
	//$sheet1->setCellValue('N' . $b, 'Média');
	
	$counter_disciplina = $b;
	
	/*--------------------------1o SEMESTRE--------------------------*/
	
	$statement45 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina 
										WHERE id_disciplina IN ('$array_disciplinas_final') AND semestre = 1
										ORDER BY id_curso, ano, semestre, nome_uc;");
	$statement45->execute();
	$resultado45 = $statement45->get_result();
	$linha45 = mysqli_fetch_assoc($resultado45);
		$num_disciplinas_1_sem = $linha45["COUNT(DISTINCT id_disciplina)"];
	
	$statement800 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina 
										WHERE id_disciplina NOT IN ('$array_disciplinas_final') AND semestre = 1
										AND id_responsavel = $id_utilizador
										ORDER BY id_curso, ano, semestre, nome_uc;");
	$statement800->execute();
	$resultado800 = $statement800->get_result();
	$linha800 = mysqli_fetch_assoc($resultado800);
		$num_disciplinas_fora_responsavel = $linha800["COUNT(DISTINCT id_disciplina)"];
	
	if($num_disciplinas_1_sem == 0 && $num_disciplinas_fora_responsavel == 0){
		//echo $nome_utilizador, " NÃO LECIONA DISCIPLINAS NEM É RESPONSÁVEL", "\n <br>";
		$sheet1->setCellValue('D' . $counter_disciplina, "");
		$sheet1->setCellValue('E' . $counter_disciplina, "");
		$sheet1->setCellValue('F' . $counter_disciplina, "");
		$sheet1->setCellValue('G' . $counter_disciplina, "");
		$sheet1->setCellValue('H' . $counter_disciplina, "");
		$sheet1->setCellValue('I' . $counter_disciplina, "");
		
		formatarLinhaUC($sheet1, $counter_disciplina);
		
		$counter_disciplina = $counter_disciplina + 1;
	}
	else{
	
	$statement5 = mysqli_prepare($conn, "SELECT DISTINCT * FROM disciplina 
										WHERE id_disciplina IN ('$array_disciplinas_final') AND semestre = 1
										ORDER BY id_curso, ano, semestre, nome_uc;");
	$statement5->execute();
	$resultado5 = $statement5->get_result();
	while($linha5 = mysqli_fetch_assoc($resultado5)){
		$id_disciplina = $linha5["id_disciplina"];
		$nome_disciplina = $linha5["nome_uc"];
		$codigo_disciplina = $linha5["codigo_uc"];
		$id_curso = $linha5["id_curso"];
		$id_area = $linha5["id_area"];
		$ano = $linha5["ano"];
		$semestre = $linha5["semestre"];
		$id_responsavel = $linha5["id_responsavel"];
		
		$statement6 = mysqli_prepare($conn, "SELECT nome FROM curso WHERE id_curso = $id_curso");
		$statement6->execute();
		$resultado6 = $statement6->get_result();
		$linha6 = mysqli_fetch_assoc($resultado6);
			$nome_curso = $linha6["nome"];
			
		$statement7 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
		$statement7->execute();
		$resultado7 = $statement7->get_result();
		$linha7 = mysqli_fetch_assoc($resultado7);
			$nome_area_curso = $linha7["nome"];
		
		$sheet1->setCellValue('D' . $counter_disciplina, $codigo_disciplina);
		$sheet1->setCellValue('E' . $counter_disciplina, $nome_curso);
		$sheet1->setCellValue('F' . $counter_disciplina, $nome_disciplina);
		$sheet1->setCellValue('G' . $counter_disciplina, $nome_area_curso);
		$sheet1->setCellValue('H' . $counter_disciplina, $ano . $o);
		if($id_utilizador == $id_responsavel){
			$sheet1->setCellValue('I' . $counter_disciplina, 'Respons' . $á . 'vel');
		}
		else{
			$sheet1->setCellValue('I' . $counter_disciplina, 'Colaborador');
		}
		
		$array_componentes_disciplina = array();
		
		$statement70 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $id_utilizador;");
		$statement70->execute();
		$resultado70 = $statement70->get_result();
		while($linha70 = mysqli_fetch_assoc($resultado70)){
			$id_componente = $linha70["id_componente"];
			
			$statement701 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
			$statement701 ->execute();
			$resultado701 = $statement701->get_result();
			$linha701 = mysqli_fetch_assoc($resultado701);
				$id_disciplina_componente = $linha701["id_disciplina"];
				if($id_disciplina_componente == $id_disciplina){
					array_push($array_componentes_disciplina,$id_componente);	
				}
		}
		
		$array_componentes_disciplina_final = implode("','",$array_componentes_disciplina);
		
		$statement71 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula 
											WHERE id_componente IN ('$array_componentes_disciplina_final')
											AND id_docente = $id_utilizador;");
		$statement71->execute();
		$resultado71 = $statement71->get_result();
		$linha71 = mysqli_fetch_assoc($resultado71);
			$num_turmas_uc_docente = $linha71["COUNT(DISTINCT id_turma)"];
			
		$sheet1->setCellValue('J' . $counter_disciplina, $num_turmas_uc_docente);
		
		$array_tipo_componentes = array();
			
		$statement72 = mysqli_prepare($conn, "SELECT DISTINCT id_tipocomponente FROM componente
											WHERE id_componente IN ('$array_componentes_disciplina_final');");
		$statement72->execute();
		$resultado72 = $statement72->get_result();
		while($linha72 = mysqli_fetch_assoc($resultado72)){
			$id_tipocomponente = $linha72["id_tipocomponente"];
			
			$statement73 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
			$statement73->execute();
			$resultado73 = $statement73->get_result();
			$linha73 = mysqli_fetch_assoc($resultado73);
				$sigla_tipocomponente = $linha73["sigla_tipocomponente"];
				
			array_push($array_tipo_componentes,$sigla_tipocomponente);
		}
		
		$array_tipo_componentes_final = implode(" + ",$array_tipo_componentes); 
			
		$sheet1->setCellValue('K' . $counter_disciplina, $array_tipo_componentes_final);	
	
		$numHorasSemanal = 0;
		$numSegundosSemanal = 0;
	
		$componentes_ja_tratadas = array();
	
		$statement73 = mysqli_prepare($conn, "SELECT id_componente FROM aula 
										   	  WHERE id_componente IN ('$array_componentes_disciplina_final')
											  AND id_docente = $id_utilizador;");
		$statement73->execute();
		$resultado73 = $statement73->get_result();
		while($linha73 = mysqli_fetch_assoc($resultado73)){
			$id_comp = $linha73["id_componente"];
	
			if(!in_array($id_comp,$componentes_ja_tratadas)){	
	
				$statement37 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
				$statement37->execute();
				$resultado37 = $statement37->get_result();
				$linha37 = mysqli_fetch_assoc($resultado37);
					$numero_horas_comp = $linha37["numero_horas"];
						
					$statement38 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador;");
					$statement38->execute();
					$resultado38 = $statement38->get_result();
					$linha38 = mysqli_fetch_assoc($resultado38);
						$num_juncoes_comp_docente = $linha38["COUNT(DISTINCT id_juncao)"];
					
						if($num_juncoes_comp_docente > 0){
							
							$statement40 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NULL;");
							$statement40->execute();
							$resultado40 = $statement40->get_result();
							$linha40 = mysqli_fetch_assoc($resultado40);
								$num_componentes_nao_juncao = $linha40["COUNT(id_componente)"];
							
							if($num_componentes_nao_juncao == 0){
								
								$statement430 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
								$statement430->execute();
								$resultado430 = $statement430->get_result();
								while($linha430 = mysqli_fetch_assoc($resultado430)){
									$id_juncao = $linha430["id_juncao"];
									
									if(!in_array($id_juncao,$juncoes_ja_contabilizadas_1_sem)){
										$numHorasSemanal = $numHorasSemanal + $numero_horas_comp;
										array_push($juncoes_ja_contabilizadas_1_sem,$id_juncao);
									}
									
								}
							}
							else{	
							
								$statement430 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
								$statement430->execute();
								$resultado430 = $statement430->get_result();
								while($linha430 = mysqli_fetch_assoc($resultado430)){
									$id_juncao = $linha430["id_juncao"];
									
									if(!in_array($id_juncao,$juncoes_ja_contabilizadas_1_sem)){
										$numHorasSemanal = $numHorasSemanal + $numero_horas_comp;
										array_push($juncoes_ja_contabilizadas_1_sem,$id_juncao);
									}
									
								}
							
								$numHorasSemanal += $numero_horas_comp * $num_componentes_nao_juncao;	
							}
							
						}
						else{
							$statement39 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador;");
							$statement39->execute();
							$resultado39 = $statement39->get_result();
							$linha39 = mysqli_fetch_assoc($resultado39);
								$num_aulas_componente_docente = $linha39["COUNT(id_componente)"];
							
								$numHorasSemanal += $numero_horas_comp * $num_aulas_componente_docente;
						}
						
						array_push($componentes_ja_tratadas,$id_comp);
			}
		}
	
		if($numHorasSemanal > 0){
			$sheet1->setCellValue('L' . $counter_disciplina, $numHorasSemanal);
		}
		
		//JUNÇOES
		$statement38 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente IN ('$array_componentes_disciplina_final') AND id_docente = $id_utilizador;");
		$statement38->execute();
		$resultado38 = $statement38->get_result();
		$linha38 = mysqli_fetch_assoc($resultado38);
			$num_juncoes_uc_docente = $linha38["COUNT(DISTINCT id_juncao)"];
			
			if($num_juncoes_uc_docente > 0){
				
				$string_letras_juncao = "";
				$string_nomes_juncao = "";
				
				$statement39 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente IN ('$array_componentes_disciplina_final') AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
				$statement39->execute();
				$resultado39 = $statement39->get_result();
				while($linha39 = mysqli_fetch_assoc($resultado39)){
					$id_juncao = $linha39["id_juncao"];
					
					$statement41 = mysqli_prepare($conn, "SELECT nome_juncao FROM juncao WHERE id_juncao = '$id_juncao';");
					$statement41->execute();
					$resultado41 = $statement41->get_result();
					$linha41 = mysqli_fetch_assoc($resultado41);
						$nome_juncao = $linha41["nome_juncao"];
					
					$statement40 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT d.id_curso) FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina INNER JOIN juncao_componente jc ON c.id_componente = jc.id_componente WHERE jc.id_juncao = '$id_juncao';");
					$statement40->execute();
					$resultado40 = $statement40->get_result();
					$linha40 = mysqli_fetch_assoc($resultado40);
						$num_cursos_diferentes_juncao = $linha40["COUNT(DISTINCT d.id_curso)"];
					
						if($num_cursos_diferentes_juncao > 1){
							
							if(in_array($id_juncao,$array_ids_juncoes)){
								$key = array_search($id_juncao,$array_ids_juncoes);
								
								if($string_letras_juncao != ""){
									$string_letras_juncao = $string_letras_juncao . " e " . $array_ids_juncoes[$key + 1];
								}
								else{
									$string_letras_juncao = $string_letras_juncao . $array_ids_juncoes[$key + 1];
								}
								if($string_nomes_juncao != ""){
									$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
								}
								else{
									$string_nomes_juncao = $string_nomes_juncao . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
								}
							}				
							else{
							
								if($string_letras_juncao != ""){
									$string_letras_juncao = $string_letras_juncao . " e " . $array_letras[$contador_letras];
								}
								else{
									$string_letras_juncao = $string_letras_juncao . $array_letras[$contador_letras];
								}
									
								if($string_nomes_juncao != ""){
									$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_letras[$contador_letras] . " " . $nome_juncao;
								}
								else{
									$string_nomes_juncao = $string_nomes_juncao . $array_letras[$contador_letras] . " " . $nome_juncao;
								}
								
								array_push($array_ids_juncoes,$id_juncao);
								array_push($array_ids_juncoes,$array_letras[$contador_letras]);
								array_push($array_ids_juncoes,$nome_juncao);
									
								$contador_letras = $contador_letras + 1;
								
							}
						}
					
				}
				
				$sheet1->setCellValue('M' . $counter_disciplina, $string_letras_juncao);
				
				//$sheet1->setCellValue('P' . $counter_disciplina, $string_nomes_juncao);
				
			}
	
		formatarLinhaUC($sheet1, $counter_disciplina);
		
		$counter_disciplina = $counter_disciplina + 1;
	}

	}
	
		if($num_disciplinas_fora_responsavel > 0){
			
			
			$statement801 = mysqli_prepare($conn, "SELECT DISTINCT id_disciplina FROM disciplina 
										WHERE id_disciplina NOT IN ('$array_disciplinas_final') AND semestre = 1
										AND id_responsavel = $id_utilizador
										ORDER BY id_curso, ano, semestre, nome_uc;");
			$statement801->execute();
			$resultado801 = $statement801->get_result();
			while($linha801 = mysqli_fetch_assoc($resultado801)){
				$id_disciplina_fora = $linha801["id_disciplina"];
				
				$statement802 = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = $id_disciplina_fora;");
				$statement802->execute();
				$resultado802 = $statement802->get_result();
				$linha802 = mysqli_fetch_assoc($resultado802);
					$nome_disciplina = $linha802["nome_uc"];
					$codigo_disciplina = $linha802["codigo_uc"];
					$id_curso = $linha802["id_curso"];
					$id_area = $linha802["id_area"];
					$ano = $linha802["ano"];
					
					$statement803 = mysqli_prepare($conn, "SELECT nome FROM curso WHERE id_curso = $id_curso");
					$statement803->execute();
					$resultado803 = $statement803->get_result();
					$linha803 = mysqli_fetch_assoc($resultado803);
						$nome_curso = $linha803["nome"];
						
					$statement804 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
					$statement804->execute();
					$resultado804 = $statement804->get_result();
					$linha804 = mysqli_fetch_assoc($resultado804);
						$nome_area_disciplina = $linha804["nome"];
							
					$sheet1->setCellValue('D' . $counter_disciplina, $codigo_disciplina);
					$sheet1->setCellValue('E' . $counter_disciplina, $nome_curso);
					$sheet1->setCellValue('F' . $counter_disciplina, $nome_disciplina);
					$sheet1->setCellValue('G' . $counter_disciplina, $nome_area_disciplina);
					$sheet1->setCellValue('H' . $counter_disciplina, $ano);
					$sheet1->setCellValue('I' . $counter_disciplina, 'Respons' . $á . 'vel');
					
					formatarLinhaUC($sheet1, $counter_disciplina);
					
					$counter_disciplina = $counter_disciplina + 1;
				
			}
			
		}
	
	criarTotal1Sem($sheet1,$counter_disciplina,$o);
	$sheet1->setCellValue('D' . $counter_disciplina, 'Total 1' . $o . ' Semestre');
	
	if(($counter_disciplina - $b) > 1){
		$sum_range = "L" . $b . ":L" . ($counter_disciplina - 1);
		$sheet1->setCellValue('L' . $counter_disciplina, '=SUM(L' . $b . ':L' . ($counter_disciplina - 1) . ')');
	}
	else if(($counter_disciplina - $b) == 1){
		$cell_valor = $sheet1->getCell('L' . ($counter_disciplina - 1));
		$valor = $cell_valor->getValue();
		$sheet1->setCellValue('L' . $counter_disciplina, $valor);
	}
	
	$counter_disciplina = $counter_disciplina + 1;
	
	/*--------------------------2o SEMESTRE--------------------------*/
	
	$offset = $counter_disciplina;
	
	$statement78 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina 
										WHERE id_disciplina IN ('$array_disciplinas_final') AND semestre = 2
										ORDER BY id_curso, ano, semestre, nome_uc;");
	$statement78->execute();
	$resultado78 = $statement78->get_result();
	$linha78 = mysqli_fetch_assoc($resultado78);
		$num_disciplinas_2_sem = $linha78["COUNT(DISTINCT id_disciplina)"];
	
	$statement805 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina 
										WHERE id_disciplina NOT IN ('$array_disciplinas_final') AND semestre = 2
										AND id_responsavel = $id_utilizador;");
	$statement805->execute();
	$resultado805 = $statement805->get_result();
	$linha805 = mysqli_fetch_assoc($resultado805);
		$num_disciplinas_fora_responsavel_2sem = $linha805["COUNT(DISTINCT id_disciplina)"];
	
	if($num_disciplinas_2_sem == 0 && $num_disciplinas_fora_responsavel_2sem == 0){
		
		$sheet1->setCellValue('D' . $counter_disciplina, "");
		$sheet1->setCellValue('E' . $counter_disciplina, "");
		$sheet1->setCellValue('F' . $counter_disciplina, "");
		$sheet1->setCellValue('G' . $counter_disciplina, "");
		$sheet1->setCellValue('H' . $counter_disciplina, "");
		$sheet1->setCellValue('I' . $counter_disciplina, "");
		
		formatarLinhaUC($sheet1, $counter_disciplina);
		
		$counter_disciplina = $counter_disciplina + 1;
	}
	else{
	$statement8 = mysqli_prepare($conn, "SELECT DISTINCT * FROM disciplina 
										WHERE id_disciplina IN ('$array_disciplinas_final') AND semestre = 2
										ORDER BY id_curso, ano, semestre, nome_uc;");
	$statement8->execute();
	$resultado8 = $statement8->get_result();
	while($linha8 = mysqli_fetch_assoc($resultado8)){
		$id_disciplina = $linha8["id_disciplina"];
		$nome_disciplina = $linha8["nome_uc"];
		$codigo_disciplina = $linha8["codigo_uc"];
		$id_curso = $linha8["id_curso"];
		$id_area = $linha8["id_area"];
		$ano = $linha8["ano"];
		$semestre = $linha8["semestre"];
		$id_responsavel = $linha8["id_responsavel"];
		
		$statement9 = mysqli_prepare($conn, "SELECT nome FROM curso WHERE id_curso = $id_curso");
		$statement9->execute();
		$resultado9 = $statement9->get_result();
		$linha9 = mysqli_fetch_assoc($resultado9);
			$nome_curso = $linha9["nome"];
			
		$statement10 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
		$statement10->execute();
		$resultado10 = $statement10->get_result();
		$linha10 = mysqli_fetch_assoc($resultado10);
			$nome_area_curso = $linha10["nome"];
		
		$sheet1->setCellValue('D' . $counter_disciplina, $codigo_disciplina . "\n" . $counter_disciplina);
		$sheet1->setCellValue('E' . $counter_disciplina, $nome_curso);
		$sheet1->setCellValue('F' . $counter_disciplina, $nome_disciplina);
		$sheet1->setCellValue('G' . $counter_disciplina, $nome_area_curso);
		$sheet1->setCellValue('H' . $counter_disciplina, $ano . $o);
		if($id_utilizador == $id_responsavel){
			$sheet1->setCellValue('I' . $counter_disciplina, 'Respons' . $á . 'vel');
		}
		else{
			$sheet1->setCellValue('I' . $counter_disciplina, 'Colaborador');
		}
		
		$array_componentes_disciplina = array();
		
		$statement70 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $id_utilizador;");
		$statement70->execute();
		$resultado70 = $statement70->get_result();
		while($linha70 = mysqli_fetch_assoc($resultado70)){
			$id_componente = $linha70["id_componente"];
			
			$statement701 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
			$statement701 ->execute();
			$resultado701 = $statement701->get_result();
			$linha701 = mysqli_fetch_assoc($resultado701);
				$id_disciplina_componente = $linha701["id_disciplina"];
				if($id_disciplina_componente == $id_disciplina){
					array_push($array_componentes_disciplina,$id_componente);	
				}
		}
		
		$array_componentes_disciplina_final = implode("','",$array_componentes_disciplina);
		
		$statement71 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula 
											WHERE id_componente IN ('$array_componentes_disciplina_final')
											AND id_docente = $id_utilizador;");
		$statement71->execute();
		$resultado71 = $statement71->get_result();
		$linha71 = mysqli_fetch_assoc($resultado71);
			$num_turmas_uc_docente = $linha71["COUNT(DISTINCT id_turma)"];
			
		$sheet1->setCellValue('J' . $counter_disciplina, $num_turmas_uc_docente);
		
		$array_tipo_componentes = array();
			
		$statement72 = mysqli_prepare($conn, "SELECT DISTINCT id_tipocomponente FROM componente
											WHERE id_componente IN ('$array_componentes_disciplina_final');");
		$statement72->execute();
		$resultado72 = $statement72->get_result();
		while($linha72 = mysqli_fetch_assoc($resultado72)){
			$id_tipocomponente = $linha72["id_tipocomponente"];
			
			$statement73 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
			$statement73->execute();
			$resultado73 = $statement73->get_result();
			$linha73 = mysqli_fetch_assoc($resultado73);
				$sigla_tipocomponente = $linha73["sigla_tipocomponente"];
				
			array_push($array_tipo_componentes,$sigla_tipocomponente);
		}
		
		$array_tipo_componentes_final = implode(" + ",$array_tipo_componentes); 
			
		$sheet1->setCellValue('K' . $counter_disciplina, $array_tipo_componentes_final);	
	
		$numHorasSemanal = 0;
		$numSegundosSemanal = 0;
	
		$componentes_ja_tratadas = array();
	
		$statement73 = mysqli_prepare($conn, "SELECT id_componente FROM aula 
										   	  WHERE id_componente IN ('$array_componentes_disciplina_final')
											  AND id_docente = $id_utilizador;");
		$statement73->execute();
		$resultado73 = $statement73->get_result();
		while($linha73 = mysqli_fetch_assoc($resultado73)){
			$id_comp = $linha73["id_componente"];
	
			if(!in_array($id_comp,$componentes_ja_tratadas)){	
	
				$statement37 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
				$statement37->execute();
				$resultado37 = $statement37->get_result();
				$linha37 = mysqli_fetch_assoc($resultado37);
					$numero_horas_comp = $linha37["numero_horas"];
						
					$statement38 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador;");
					$statement38->execute();
					$resultado38 = $statement38->get_result();
					$linha38 = mysqli_fetch_assoc($resultado38);
						$num_juncoes_comp_docente = $linha38["COUNT(DISTINCT id_juncao)"];
					
						if($num_juncoes_comp_docente > 0){
							
							$statement40 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NULL;");
							$statement40->execute();
							$resultado40 = $statement40->get_result();
							$linha40 = mysqli_fetch_assoc($resultado40);
								$num_componentes_nao_juncao = $linha40["COUNT(id_componente)"];
							
							if($num_componentes_nao_juncao == 0){
								
								$statement430 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
								$statement430->execute();
								$resultado430 = $statement430->get_result();
								while($linha430 = mysqli_fetch_assoc($resultado430)){
									$id_juncao = $linha430["id_juncao"];
									
									if(!in_array($id_juncao,$juncoes_ja_contabilizadas_2_sem)){
										$numHorasSemanal = $numHorasSemanal + $numero_horas_comp;
										array_push($juncoes_ja_contabilizadas_2_sem,$id_juncao);
									}
									
								}
								
							}
							else{	
							
								$statement430 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
								$statement430->execute();
								$resultado430 = $statement430->get_result();
								while($linha430 = mysqli_fetch_assoc($resultado430)){
									$id_juncao = $linha430["id_juncao"];
									
									if(!in_array($id_juncao,$juncoes_ja_contabilizadas_1_sem)){
										$numHorasSemanal = $numHorasSemanal + $numero_horas_comp;
										array_push($juncoes_ja_contabilizadas_1_sem,$id_juncao);
									}
									
								}
							
								$numHorasSemanal += $numero_horas_comp * $num_componentes_nao_juncao;	
							}
							
						}
						else{
							$statement39 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador;");
							$statement39->execute();
							$resultado39 = $statement39->get_result();
							$linha39 = mysqli_fetch_assoc($resultado39);
								$num_aulas_componente_docente = $linha39["COUNT(id_componente)"];
							
								$numHorasSemanal += $numero_horas_comp * $num_aulas_componente_docente;
						}
						
						array_push($componentes_ja_tratadas,$id_comp);
			}
		}
	
		$sheet1->setCellValue('M' . $counter_disciplina, $numHorasSemanal);
	
		//JUNÇOES
		$statement38 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente IN ('$array_componentes_disciplina_final') AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
		$statement38->execute();
		$resultado38 = $statement38->get_result();
		$linha38 = mysqli_fetch_assoc($resultado38);
			$num_juncoes_uc_docente = $linha38["COUNT(DISTINCT id_juncao)"];
			
			if($num_juncoes_uc_docente > 0){
				
				$string_letras_juncao = "";
				$string_nomes_juncao = "";
				
				$statement39 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente IN ('$array_componentes_disciplina_final') AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
				$statement39->execute();
				$resultado39 = $statement39->get_result();
				while($linha39 = mysqli_fetch_assoc($resultado39)){
					$id_juncao = $linha39["id_juncao"];
					
					$statement41 = mysqli_prepare($conn, "SELECT nome_juncao FROM juncao WHERE id_juncao = '$id_juncao';");
					$statement41->execute();
					$resultado41 = $statement41->get_result();
					$linha41 = mysqli_fetch_assoc($resultado41);
						$nome_juncao = $linha41["nome_juncao"];
					
					$statement40 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT d.id_curso) FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina INNER JOIN juncao_componente jc ON c.id_componente = jc.id_componente WHERE jc.id_juncao = '$id_juncao';");
					$statement40->execute();
					$resultado40 = $statement40->get_result();
					$linha40 = mysqli_fetch_assoc($resultado40);
						$num_cursos_diferentes_juncao = $linha40["COUNT(DISTINCT d.id_curso)"];
					
						if($num_cursos_diferentes_juncao > 1){
							
							if(in_array($id_juncao,$array_ids_juncoes)){
								$key = array_search($id_juncao,$array_ids_juncoes);
								
								if($string_letras_juncao != ""){
									$string_letras_juncao = $string_letras_juncao . " e " . $array_ids_juncoes[$key + 1];
								}
								else{
									$string_letras_juncao = $string_letras_juncao . $array_ids_juncoes[$key + 1];
								}
								if($string_nomes_juncao != ""){
									$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
								}
								else{
									$string_nomes_juncao = $string_nomes_juncao . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
								}
							}				
							else{
							
								if($string_letras_juncao != ""){
									$string_letras_juncao = $string_letras_juncao . " e " . $array_letras[$contador_letras];
								}
								else{
									$string_letras_juncao = $string_letras_juncao . $array_letras[$contador_letras];
								}
									
								if($string_nomes_juncao != ""){
									$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_letras[$contador_letras] . " " . $nome_juncao;
								}
								else{
									$string_nomes_juncao = $string_nomes_juncao . $array_letras[$contador_letras] . " " . $nome_juncao;
								}
								
								array_push($array_ids_juncoes,$id_juncao);
								array_push($array_ids_juncoes,$array_letras[$contador_letras]);
								array_push($array_ids_juncoes,$nome_juncao);
								
								$contador_letras = $contador_letras + 1;
							}
						}
					
				}
				
				$sheet1->setCellValue('L' . $counter_disciplina, $string_letras_juncao);
				
				//$sheet1->setCellValue('P' . $counter_disciplina, $string_nomes_juncao);
				
			}
	
		formatarLinhaUC($sheet1, $counter_disciplina);
		
		$counter_disciplina = $counter_disciplina + 1;
	}
	
	}
		
		if($num_disciplinas_fora_responsavel_2sem > 0){
				
			$counter_disciplina = $counter_disciplina - 1;
				
			$statement806 = mysqli_prepare($conn, "SELECT DISTINCT id_disciplina FROM disciplina 
										WHERE id_disciplina NOT IN ('$array_disciplinas_final') AND semestre = 2
										AND id_responsavel = $id_utilizador
										ORDER BY id_curso, ano, semestre, nome_uc;");
			$statement806->execute();
			$resultado806 = $statement806->get_result();
			while($linha806 = mysqli_fetch_assoc($resultado806)){
				$id_disciplina_fora = $linha806["id_disciplina"];
				
				$statement807 = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = $id_disciplina_fora;");
				$statement807->execute();
				$resultado807 = $statement807->get_result();
				$linha807 = mysqli_fetch_assoc($resultado807);
					$nome_disciplina = $linha807["nome_uc"];
					$codigo_disciplina = $linha807["codigo_uc"];
					$id_curso = $linha807["id_curso"];
					$id_area = $linha807["id_area"];
					$ano = $linha807["ano"];
					
					$statement808 = mysqli_prepare($conn, "SELECT nome FROM curso WHERE id_curso = $id_curso");
					$statement808->execute();
					$resultado808 = $statement808->get_result();
					$linha808 = mysqli_fetch_assoc($resultado808);
						$nome_curso = $linha808["nome"];
						
					$statement809 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
					$statement809->execute();
					$resultado809 = $statement809->get_result();
					$linha809 = mysqli_fetch_assoc($resultado809);
						$nome_area_disciplina = $linha809["nome"];
							
					$sheet1->setCellValue('D' . $counter_disciplina, $codigo_disciplina);
					$sheet1->setCellValue('E' . $counter_disciplina, $nome_curso);
					$sheet1->setCellValue('F' . $counter_disciplina, $nome_disciplina);
					$sheet1->setCellValue('G' . $counter_disciplina, $nome_area_disciplina);
					$sheet1->setCellValue('H' . $counter_disciplina, $ano);
					$sheet1->setCellValue('I' . $counter_disciplina, 'Respons' . $á . 'vel');
					
					formatarLinhaUC($sheet1, $counter_disciplina);
					
					$counter_disciplina = $counter_disciplina + 1;
				
			}
					
		}
			
		//$sheet1->setCellValue('P' . $counter_disciplina, $id_utilizador . " - " . $array_disciplinas_final);
	
	criarTotal1Sem($sheet1,$counter_disciplina,$o);
	$sheet1->setCellValue('D' . $counter_disciplina, 'Total 2' . $o . ' Semestre');
	
	if(($counter_disciplina - $offset) > 1){
		$sum_range = "M" . $offset . ":M" . ($counter_disciplina - 1);
		$sheet1->setCellValue('M' . $counter_disciplina, '=SUM(M' . $offset . ':M' . ($counter_disciplina - 1) . ')');
	}
	else if(($counter_disciplina - $offset) == 1){
		$cell_valor = $sheet1->getCell('M' . ($counter_disciplina - 1));
		$valor = $cell_valor->getValue();
		$sheet1->setCellValue('M' . $counter_disciplina, $valor);
	}
	
	$cell_1 = $sheet1->getCell('L' . ($offset - 1));
	$cell_1_valor = $cell_1->getValue();
	$cell_2 = $sheet1->getCell('M' . ($counter_disciplina));
	$cell_2_valor = $cell_2->getValue();
	
	if(($cell_1_valor != "") &&($cell_2_valor != "")){
		$sheet1->setCellValue('N' . $b, '=(SUM(L' . ($offset - 1) . ',M' . ($counter_disciplina) . '))/2');
	}
	
	formatarDocente($b, ($counter_disciplina), $sheet1);
	
	$counter_disciplina = $counter_disciplina + 1;
	
	$b = $counter_disciplina;
	$counter_docente_cincento = $counter_docente_cincento + 1;
}

$array_componentes_docente_utc = array();

$statement100 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utc != $id_utc_post OR (id_utc = $id_utc_post AND id_funcao = 6) ORDER BY nome;");
$statement100->execute();
$resultado100 = $statement100->get_result();
while($linha100 = mysqli_fetch_assoc($resultado100)){
	$id_docente = $linha100["id_utilizador"];
	$nome_docente = $linha100["nome"];
	
	$statement101 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_docente = $id_docente;");
	$statement101->execute();
	$resultado101 = $statement101->get_result();
	$linha101 = mysqli_fetch_assoc($resultado101);
		$num_aulas_docente = $linha101["COUNT(id_docente)"];
		
		if($num_aulas_docente > 0){
			
			$statement102 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $id_docente;");
			$statement102->execute();
			$resultado102 = $statement102->get_result();
			while($linha102 = mysqli_fetch_assoc($resultado102)){
				$id_componente = $linha102["id_componente"];
			
				$statement103 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
				$statement103->execute();
				$resultado103 = $statement103->get_result();
				$linha103 = mysqli_fetch_assoc($resultado103);
					$id_disciplina = $linha103["id_disciplina"];
				
					$statement104 = mysqli_prepare($conn, "SELECT id_curso FROM disciplina WHERE id_disciplina = $id_disciplina;");
					$statement104->execute();
					$resultado104 = $statement104->get_result();
					$linha104 = mysqli_fetch_assoc($resultado104);
						$id_curso = $linha104["id_curso"];
				
						$statement105 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
						$statement105->execute();
						$resultado105 = $statement105->get_result();
						$linha105 = mysqli_fetch_assoc($resultado105);
							$id_utc_disciplina = $linha105["id_utc"];
					
							if($id_utc_disciplina == $id_utc_post){	
								array_push($array_componentes_docente_utc,$id_componente);
								array_push($array_componentes_docente_utc,$id_docente);
							}	
			}
		}
}

$array_docentes_temp = array();
$i = 0;
while($i < sizeof($array_componentes_docente_utc)){
	if(!in_array($array_componentes_docente_utc[$i + 1],$array_docentes_temp)){
		array_push($array_docentes_temp,$array_componentes_docente_utc[$i + 1]);
	}
	$i = $i + 2;
}
			
$array_docentes_assistentes_outra_utc_temp = array_unique($array_docentes_temp);
$array_docentes_assistentes_outra_utc_final = implode("','",$array_docentes_assistentes_outra_utc_temp);

if(sizeof($array_docentes_assistentes_outra_utc_temp) > 0){
dividirCategoria();
$b = $b + 1;
$counter_disciplina = $counter_disciplina + 1;


$c = 0;
while($c < sizeof($array_docentes_assistentes_outra_utc_temp)){
	
	$id_docente = $array_docentes_assistentes_outra_utc_temp[$c];
	
	$statement100 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_docente;");
	$statement100->execute();
	$resultado100 = $statement100->get_result();
	$linha100 = mysqli_fetch_assoc($resultado100);
		$id_utilizador = $linha100["id_utilizador"];
		$nome_utilizador = $linha100["nome"];
		$id_area_utilizador = $linha100["id_area"];
		$id_funcao_utilizador = $linha100["id_funcao"];
		
		$juncoes_ja_contabilizadas_1_sem = array();
		$juncoes_ja_contabilizadas_2_sem = array();
		
		$sheet1->setCellValue('B' . $b,$nome_utilizador);
		
		$statement101 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area_utilizador;");
		$statement101->execute();
		$resultado101 = $statement101->get_result();
		$linha101 = mysqli_fetch_assoc($resultado101);
			$nome_area_utilizador = $linha101["nome"];
			
		$statement102 = mysqli_prepare($conn, "SELECT nome FROM funcao WHERE id_funcao = $id_funcao_utilizador;");
		$statement102->execute();
		$resultado102 = $statement102->get_result();
		$linha102 = mysqli_fetch_assoc($resultado102);
			$nome_funcao_utilizador = $linha102["nome"];
	
			$sheet1->setCellValue('C' . $b,$nome_funcao_utilizador);
	
			$array_componentes = array();
			
			$array_disciplinas = array();
			
			$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $id_docente;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			while($linha3 = mysqli_fetch_assoc($resultado3)){
				$id_componente = $linha3["id_componente"];
				
				$statement4 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
				$statement4 ->execute();
				$resultado4 = $statement4->get_result();
				$linha4 = mysqli_fetch_assoc($resultado4);
					$id_disciplina = $linha4["id_disciplina"];
					
					$statement42 = mysqli_prepare($conn, "SELECT c.id_utc FROM disciplina d INNER JOIN curso c ON d.id_curso = c.id_curso 
													WHERE d.id_disciplina = $id_disciplina;");
					$statement42 ->execute();
					$resultado42 = $statement42->get_result();
					$linha42 = mysqli_fetch_assoc($resultado42);
						$id_utc_disciplina = $linha42["id_utc"];
						
					/*	if($id_utc_disciplina == $id_utc_post){	*/
							array_push($array_componentes,$id_componente);
							array_push($array_disciplinas,$id_disciplina);
			/*			} */
			}
			
			$array_componentes_final = implode("','",$array_componentes);
			$array_disciplinas_unique = array_unique($array_disciplinas);
			$array_disciplinas_final = implode("','",$array_disciplinas_unique);
			
			$counter_disciplina = $b;
	
			/*--------------------------1o SEMESTRE--------------------------*/
	
			$statement45 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina 
												WHERE id_disciplina IN ('$array_disciplinas_final') AND semestre = 1
												ORDER BY id_curso, ano, semestre, nome_uc;");
			$statement45->execute();
			$resultado45 = $statement45->get_result();
			$linha45 = mysqli_fetch_assoc($resultado45);
				$num_disciplinas_1_sem = $linha45["COUNT(DISTINCT id_disciplina)"];
			
			if($num_disciplinas_1_sem == 0){
				$sheet1->setCellValue('D' . $counter_disciplina, "");
				$sheet1->setCellValue('E' . $counter_disciplina, "");
				$sheet1->setCellValue('F' . $counter_disciplina, "");
				$sheet1->setCellValue('G' . $counter_disciplina, "");
				$sheet1->setCellValue('H' . $counter_disciplina, "");
				$sheet1->setCellValue('I' . $counter_disciplina, "");
				
				formatarLinhaUC($sheet1, $counter_disciplina);
				
				$counter_disciplina = $counter_disciplina + 1;
			}
			else{
			
			$statement5 = mysqli_prepare($conn, "SELECT DISTINCT * FROM disciplina 
												WHERE id_disciplina IN ('$array_disciplinas_final') AND semestre = 1
												ORDER BY id_curso, ano, semestre, nome_uc;");
			$statement5->execute();
			$resultado5 = $statement5->get_result();
			while($linha5 = mysqli_fetch_assoc($resultado5)){
				$id_disciplina = $linha5["id_disciplina"];
				$nome_disciplina = $linha5["nome_uc"];
				$codigo_disciplina = $linha5["codigo_uc"];
				$id_curso = $linha5["id_curso"];
				$id_area = $linha5["id_area"];
				$ano = $linha5["ano"];
				$semestre = $linha5["semestre"];
				$id_responsavel = $linha5["id_responsavel"];
				
				$statement6 = mysqli_prepare($conn, "SELECT nome FROM curso WHERE id_curso = $id_curso");
				$statement6->execute();
				$resultado6 = $statement6->get_result();
				$linha6 = mysqli_fetch_assoc($resultado6);
					$nome_curso = $linha6["nome"];
					
				$statement7 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
				$statement7->execute();
				$resultado7 = $statement7->get_result();
				$linha7 = mysqli_fetch_assoc($resultado7);
					$nome_area_curso = $linha7["nome"];
				
				$sheet1->setCellValue('D' . $counter_disciplina, $codigo_disciplina);
				$sheet1->setCellValue('E' . $counter_disciplina, $nome_curso);
				$sheet1->setCellValue('F' . $counter_disciplina, $nome_disciplina);
				$sheet1->setCellValue('G' . $counter_disciplina, $nome_area_curso);
				$sheet1->setCellValue('H' . $counter_disciplina, $ano . $o);
				if($id_utilizador == $id_responsavel){
					$sheet1->setCellValue('I' . $counter_disciplina, 'Respons' . $á . 'vel');
				}
				else{
					$sheet1->setCellValue('I' . $counter_disciplina, 'Colaborador');
				}
				
				$array_componentes_disciplina = array();
				
				$statement70 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $id_docente;");
				$statement70->execute();
				$resultado70 = $statement70->get_result();
				while($linha70 = mysqli_fetch_assoc($resultado70)){
					$id_componente = $linha70["id_componente"];
					
					$statement701 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
					$statement701 ->execute();
					$resultado701 = $statement701->get_result();
					$linha701 = mysqli_fetch_assoc($resultado701);
						$id_disciplina_componente = $linha701["id_disciplina"];
						if($id_disciplina_componente == $id_disciplina){
							array_push($array_componentes_disciplina,$id_componente);	
						}
				}
				
				$array_componentes_disciplina_final = implode("','",$array_componentes_disciplina);
				
				$statement71 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula 
													WHERE id_componente IN ('$array_componentes_disciplina_final')
													AND id_docente = $id_docente;");
				$statement71->execute();
				$resultado71 = $statement71->get_result();
				$linha71 = mysqli_fetch_assoc($resultado71);
					$num_turmas_uc_docente = $linha71["COUNT(DISTINCT id_turma)"];
					
				$sheet1->setCellValue('J' . $counter_disciplina, $num_turmas_uc_docente);
				
				$array_tipo_componentes = array();
					
				$statement72 = mysqli_prepare($conn, "SELECT DISTINCT id_tipocomponente FROM componente
													WHERE id_componente IN ('$array_componentes_disciplina_final');");
				$statement72->execute();
				$resultado72 = $statement72->get_result();
				while($linha72 = mysqli_fetch_assoc($resultado72)){
					$id_tipocomponente = $linha72["id_tipocomponente"];
					
					$statement73 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
					$statement73->execute();
					$resultado73 = $statement73->get_result();
					$linha73 = mysqli_fetch_assoc($resultado73);
						$sigla_tipocomponente = $linha73["sigla_tipocomponente"];
						
					array_push($array_tipo_componentes,$sigla_tipocomponente);
				}
				
				$array_tipo_componentes_final = implode(" + ",$array_tipo_componentes); 
					
				$sheet1->setCellValue('K' . $counter_disciplina, $array_tipo_componentes_final);	
			
				$numHorasSemanal = 0;
				$numSegundosSemanal = 0;
			
				$componentes_ja_tratadas = array();
			
				$statement73 = mysqli_prepare($conn, "SELECT id_componente FROM aula 
													  WHERE id_componente IN ('$array_componentes_disciplina_final')
													  AND id_docente = $id_docente;");
				$statement73->execute();
				$resultado73 = $statement73->get_result();
				while($linha73 = mysqli_fetch_assoc($resultado73)){
					$id_comp = $linha73["id_componente"];
			
					if(!in_array($id_comp,$componentes_ja_tratadas)){	
			
						$statement37 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
						$statement37->execute();
						$resultado37 = $statement37->get_result();
						$linha37 = mysqli_fetch_assoc($resultado37);
							$numero_horas_comp = $linha37["numero_horas"];
								
							$statement38 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_docente;");
							$statement38->execute();
							$resultado38 = $statement38->get_result();
							$linha38 = mysqli_fetch_assoc($resultado38);
								$num_juncoes_comp_docente = $linha38["COUNT(DISTINCT id_juncao)"];
							
								if($num_juncoes_comp_docente > 0){
									
									$statement40 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_docente AND id_juncao IS NULL;");
									$statement40->execute();
									$resultado40 = $statement40->get_result();
									$linha40 = mysqli_fetch_assoc($resultado40);
										$num_componentes_nao_juncao = $linha40["COUNT(id_componente)"];
									
									if($num_componentes_nao_juncao == 0){
										
										$statement430 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
										$statement430->execute();
										$resultado430 = $statement430->get_result();
										while($linha430 = mysqli_fetch_assoc($resultado430)){
											$id_juncao = $linha430["id_juncao"];
											
											if(!in_array($id_juncao,$juncoes_ja_contabilizadas_1_sem)){
												$numHorasSemanal = $numHorasSemanal + $numero_horas_comp;
												array_push($juncoes_ja_contabilizadas_1_sem,$id_juncao);
											}
											
										}
										
									}
									else{	
									
										$statement430 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
										$statement430->execute();
										$resultado430 = $statement430->get_result();
										while($linha430 = mysqli_fetch_assoc($resultado430)){
											$id_juncao = $linha430["id_juncao"];
											
											if(!in_array($id_juncao,$juncoes_ja_contabilizadas_1_sem)){
												$numHorasSemanal = $numHorasSemanal + $numero_horas_comp;
												array_push($juncoes_ja_contabilizadas_1_sem,$id_juncao);
											}
											
										}
									
										$numHorasSemanal += $numero_horas_comp * $num_componentes_nao_juncao;
										
									}
									
								}
								else{
									$statement39 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_docente;");
									$statement39->execute();
									$resultado39 = $statement39->get_result();
									$linha39 = mysqli_fetch_assoc($resultado39);
										$num_aulas_componente_docente = $linha39["COUNT(id_componente)"];
									
										$numHorasSemanal += $numero_horas_comp * $num_aulas_componente_docente;
								}
								
								array_push($componentes_ja_tratadas,$id_comp);
					}
				}
				
				if($numHorasSemanal > 0){
					$sheet1->setCellValue('L' . $counter_disciplina, $numHorasSemanal);	
				}
				
				//JUNÇOES
				$statement38 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente IN ('$array_componentes_disciplina_final') AND id_docente = $id_docente AND id_juncao IS NOT NULL;;");
				$statement38->execute();
				$resultado38 = $statement38->get_result();
				$linha38 = mysqli_fetch_assoc($resultado38);
					$num_juncoes_uc_docente = $linha38["COUNT(DISTINCT id_juncao)"];
					
					if($num_juncoes_uc_docente > 0){
						
						$string_letras_juncao = "";
						$string_nomes_juncao = "";
						
						$string_letras_juncao = "";
						$string_nomes_juncao = "";
						
						$statement39 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente IN ('$array_componentes_disciplina_final') AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
						$statement39->execute();
						$resultado39 = $statement39->get_result();
						while($linha39 = mysqli_fetch_assoc($resultado39)){
							$id_juncao = $linha39["id_juncao"];
							
							$statement41 = mysqli_prepare($conn, "SELECT nome_juncao FROM juncao WHERE id_juncao = '$id_juncao';");
							$statement41->execute();
							$resultado41 = $statement41->get_result();
							$linha41 = mysqli_fetch_assoc($resultado41);
								$nome_juncao = $linha41["nome_juncao"];
							
							$statement40 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT d.id_curso) FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina INNER JOIN juncao_componente jc ON c.id_componente = jc.id_componente WHERE jc.id_juncao = '$id_juncao';");
							$statement40->execute();
							$resultado40 = $statement40->get_result();
							$linha40 = mysqli_fetch_assoc($resultado40);
								$num_cursos_diferentes_juncao = $linha40["COUNT(DISTINCT d.id_curso)"];
					
								if($num_cursos_diferentes_juncao > 1){
									
									if(in_array($id_juncao,$array_ids_juncoes)){
										$key = array_search($id_juncao,$array_ids_juncoes);
										
										if($string_letras_juncao != ""){
											$string_letras_juncao = $string_letras_juncao . " e " . $array_ids_juncoes[$key + 1];
										}
										else{
											$string_letras_juncao = $string_letras_juncao . $array_ids_juncoes[$key + 1];
										}
										if($string_nomes_juncao != ""){
											$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
										}
										else{
											$string_nomes_juncao = $string_nomes_juncao . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
										}
									}				
									else{
									
										if($string_letras_juncao != ""){
											$string_letras_juncao = $string_letras_juncao . " e " . $array_letras[$contador_letras];
										}
										else{
											$string_letras_juncao = $string_letras_juncao . $array_letras[$contador_letras];
										}
											
										if($string_nomes_juncao != ""){
											$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_letras[$contador_letras] . " " . $nome_juncao;
										}
										else{
											$string_nomes_juncao = $string_nomes_juncao . $array_letras[$contador_letras] . " " . $nome_juncao;
										}
										
										array_push($array_ids_juncoes,$id_juncao);
										array_push($array_ids_juncoes,$array_letras[$contador_letras]);
										array_push($array_ids_juncoes,$nome_juncao);
											
										$contador_letras = $contador_letras + 1;
									
									}
								}
					
						}
						
						$sheet1->setCellValue('M' . $counter_disciplina, $string_letras_juncao);
						
						//$sheet1->setCellValue('P' . $counter_disciplina, $string_nomes_juncao);
						
					}
			
				formatarLinhaUC($sheet1, $counter_disciplina);
				
				$counter_disciplina = $counter_disciplina + 1;
			}
			
			}
			
			criarTotal1Sem($sheet1,$counter_disciplina,$o);
			$sheet1->setCellValue('D' . $counter_disciplina, 'Total 1' . $o . ' Semestre');
			
			if(($counter_disciplina - $b) > 1){
				$sum_range = "L" . $b . ":L" . ($counter_disciplina - 1);
				$sheet1->setCellValue('L' . $counter_disciplina, '=SUM(L' . $b . ':L' . ($counter_disciplina - 1) . ')');
			}
			else if(($counter_disciplina - $b) == 1){
				$cell_valor = $sheet1->getCell('L' . ($counter_disciplina - 1));
				$valor = $cell_valor->getValue();
				$sheet1->setCellValue('L' . $counter_disciplina, $valor);
			}
			
			$counter_disciplina = $counter_disciplina + 1;
	
	
	
	
	
			/*--------------------------2o SEMESTRE--------------------------*/
			
			$offset = $counter_disciplina;
			
			$statement78 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina 
												WHERE id_disciplina IN ('$array_disciplinas_final') AND semestre = 2
												ORDER BY id_curso, ano, semestre, nome_uc;");
			$statement78->execute();
			$resultado78 = $statement78->get_result();
			$linha78 = mysqli_fetch_assoc($resultado78);
				$num_disciplinas_2_sem = $linha78["COUNT(DISTINCT id_disciplina)"];
			
			if($num_disciplinas_2_sem == 0){
				
				$sheet1->setCellValue('D' . $counter_disciplina, "");
				$sheet1->setCellValue('E' . $counter_disciplina, "");
				$sheet1->setCellValue('F' . $counter_disciplina, "");
				$sheet1->setCellValue('G' . $counter_disciplina, "");
				$sheet1->setCellValue('H' . $counter_disciplina, "");
				$sheet1->setCellValue('I' . $counter_disciplina, "");
				
				formatarLinhaUC($sheet1, $counter_disciplina);
				
				$counter_disciplina = $counter_disciplina + 1;
			}
			else{
			$statement8 = mysqli_prepare($conn, "SELECT DISTINCT * FROM disciplina 
												WHERE id_disciplina IN ('$array_disciplinas_final') AND semestre = 2
												ORDER BY id_curso, ano, semestre, nome_uc;");
			$statement8->execute();
			$resultado8 = $statement8->get_result();
			while($linha8 = mysqli_fetch_assoc($resultado8)){
				$id_disciplina = $linha8["id_disciplina"];
				$nome_disciplina = $linha8["nome_uc"];
				$codigo_disciplina = $linha8["codigo_uc"];
				$id_curso = $linha8["id_curso"];
				$id_area = $linha8["id_area"];
				$ano = $linha8["ano"];
				$semestre = $linha8["semestre"];
				$id_responsavel = $linha8["id_responsavel"];
				
				$statement9 = mysqli_prepare($conn, "SELECT nome FROM curso WHERE id_curso = $id_curso");
				$statement9->execute();
				$resultado9 = $statement9->get_result();
				$linha9 = mysqli_fetch_assoc($resultado9);
					$nome_curso = $linha9["nome"];
					
				$statement10 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
				$statement10->execute();
				$resultado10 = $statement10->get_result();
				$linha10 = mysqli_fetch_assoc($resultado10);
					$nome_area_curso = $linha10["nome"];
				
				$sheet1->setCellValue('D' . $counter_disciplina, $codigo_disciplina . "\n" . $counter_disciplina);
				$sheet1->setCellValue('E' . $counter_disciplina, $nome_curso);
				$sheet1->setCellValue('F' . $counter_disciplina, $nome_disciplina);
				$sheet1->setCellValue('G' . $counter_disciplina, $nome_area_curso);
				$sheet1->setCellValue('H' . $counter_disciplina, $ano . $o);
				if($id_utilizador == $id_responsavel){
					$sheet1->setCellValue('I' . $counter_disciplina, 'Respons' . $á . 'vel');
				}
				else{
					$sheet1->setCellValue('I' . $counter_disciplina, 'Colaborador');
				}
				
				$array_componentes_disciplina = array();
				
				$statement70 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $id_utilizador;");
				$statement70->execute();
				$resultado70 = $statement70->get_result();
				while($linha70 = mysqli_fetch_assoc($resultado70)){
					$id_componente = $linha70["id_componente"];
					
					$statement701 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
					$statement701 ->execute();
					$resultado701 = $statement701->get_result();
					$linha701 = mysqli_fetch_assoc($resultado701);
						$id_disciplina_componente = $linha701["id_disciplina"];
						if($id_disciplina_componente == $id_disciplina){
							array_push($array_componentes_disciplina,$id_componente);	
						}
				}
				
				$array_componentes_disciplina_final = implode("','",$array_componentes_disciplina);
				
				$statement71 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula 
													WHERE id_componente IN ('$array_componentes_disciplina_final')
													AND id_docente = $id_utilizador;");
				$statement71->execute();
				$resultado71 = $statement71->get_result();
				$linha71 = mysqli_fetch_assoc($resultado71);
					$num_turmas_uc_docente = $linha71["COUNT(DISTINCT id_turma)"];
					
				$sheet1->setCellValue('J' . $counter_disciplina, $num_turmas_uc_docente);
				
				$array_tipo_componentes = array();
					
				$statement72 = mysqli_prepare($conn, "SELECT DISTINCT id_tipocomponente FROM componente
													WHERE id_componente IN ('$array_componentes_disciplina_final');");
				$statement72->execute();
				$resultado72 = $statement72->get_result();
				while($linha72 = mysqli_fetch_assoc($resultado72)){
					$id_tipocomponente = $linha72["id_tipocomponente"];
					
					$statement73 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
					$statement73->execute();
					$resultado73 = $statement73->get_result();
					$linha73 = mysqli_fetch_assoc($resultado73);
						$sigla_tipocomponente = $linha73["sigla_tipocomponente"];
						
					array_push($array_tipo_componentes,$sigla_tipocomponente);
				}
				
				$array_tipo_componentes_final = implode(" + ",$array_tipo_componentes); 
					
				$sheet1->setCellValue('K' . $counter_disciplina, $array_tipo_componentes_final);	
			
				$numHorasSemanal = 0;
				$numSegundosSemanal = 0;
			
				$componentes_ja_tratadas = array();
			
				$statement73 = mysqli_prepare($conn, "SELECT id_componente FROM aula 
													  WHERE id_componente IN ('$array_componentes_disciplina_final')
													  AND id_docente = $id_utilizador;");
				$statement73->execute();
				$resultado73 = $statement73->get_result();
				while($linha73 = mysqli_fetch_assoc($resultado73)){
					$id_comp = $linha73["id_componente"];
			
					if(!in_array($id_comp,$componentes_ja_tratadas)){	
			
						$statement37 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
						$statement37->execute();
						$resultado37 = $statement37->get_result();
						$linha37 = mysqli_fetch_assoc($resultado37);
							$numero_horas_comp = $linha37["numero_horas"];
								
							$statement38 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador;");
							$statement38->execute();
							$resultado38 = $statement38->get_result();
							$linha38 = mysqli_fetch_assoc($resultado38);
								$num_juncoes_comp_docente = $linha38["COUNT(DISTINCT id_juncao)"];
							
								if($num_juncoes_comp_docente > 0){
									
									$statement40 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NULL;");
									$statement40->execute();
									$resultado40 = $statement40->get_result();
									$linha40 = mysqli_fetch_assoc($resultado40);
										$num_componentes_nao_juncao = $linha40["COUNT(id_componente)"];
									
									if($num_componentes_nao_juncao == 0){
										
										$statement430 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
										$statement430->execute();
										$resultado430 = $statement430->get_result();
										while($linha430 = mysqli_fetch_assoc($resultado430)){
											$id_juncao = $linha430["id_juncao"];
											
											if(!in_array($id_juncao,$juncoes_ja_contabilizadas_2_sem)){
												$numHorasSemanal = $numHorasSemanal + $numero_horas_comp;
												array_push($juncoes_ja_contabilizadas_2_sem,$id_juncao);
											}
											
										}
										
									}
									else{	
									
										$statement430 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
										$statement430->execute();
										$resultado430 = $statement430->get_result();
										while($linha430 = mysqli_fetch_assoc($resultado430)){
											$id_juncao = $linha430["id_juncao"];
											
											if(!in_array($id_juncao,$juncoes_ja_contabilizadas_1_sem)){
												$numHorasSemanal = $numHorasSemanal + $numero_horas_comp;
												array_push($juncoes_ja_contabilizadas_1_sem,$id_juncao);
											}
											
										}
									
										$numHorasSemanal += $numero_horas_comp * $num_componentes_nao_juncao;	
									
									}
									
								}
								else{
									$statement39 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_utilizador;");
									$statement39->execute();
									$resultado39 = $statement39->get_result();
									$linha39 = mysqli_fetch_assoc($resultado39);
										$num_aulas_componente_docente = $linha39["COUNT(id_componente)"];
									
										$numHorasSemanal += $numero_horas_comp * $num_aulas_componente_docente;
								}
								
								array_push($componentes_ja_tratadas,$id_comp);
					}
				}
			
				$sheet1->setCellValue('M' . $counter_disciplina, $numHorasSemanal);
			
				//JUNÇOES
				$statement38 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente IN ('$array_componentes_disciplina_final') AND id_docente = $id_utilizador;");
				$statement38->execute();
				$resultado38 = $statement38->get_result();
				$linha38 = mysqli_fetch_assoc($resultado38);
					$num_juncoes_uc_docente = $linha38["COUNT(DISTINCT id_juncao)"];
					
					if($num_juncoes_uc_docente > 0){
						
						$string_letras_juncao = "";
						$string_nomes_juncao = "";
						
						$statement39 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente IN ('$array_componentes_disciplina_final') AND id_docente = $id_utilizador AND id_juncao IS NOT NULL;");
						$statement39->execute();
						$resultado39 = $statement39->get_result();
						while($linha39 = mysqli_fetch_assoc($resultado39)){
							$id_juncao = $linha39["id_juncao"];
							
							$statement41 = mysqli_prepare($conn, "SELECT nome_juncao FROM juncao WHERE id_juncao = '$id_juncao';");
							$statement41->execute();
							$resultado41 = $statement41->get_result();
							$linha41 = mysqli_fetch_assoc($resultado41);
								$nome_juncao = $linha41["nome_juncao"];
							
							$statement40 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT d.id_curso) FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina INNER JOIN juncao_componente jc ON c.id_componente = jc.id_componente WHERE jc.id_juncao = '$id_juncao';");
							$statement40->execute();
							$resultado40 = $statement40->get_result();
							$linha40 = mysqli_fetch_assoc($resultado40);
								$num_cursos_diferentes_juncao = $linha40["COUNT(DISTINCT d.id_curso)"];
							
								if($num_cursos_diferentes_juncao > 1){
									
									if(in_array($id_juncao,$array_ids_juncoes)){
										$key = array_search($id_juncao,$array_ids_juncoes);
										
										if($string_letras_juncao != ""){
											$string_letras_juncao = $string_letras_juncao . " e " . $array_ids_juncoes[$key + 1];
										}
										else{
											$string_letras_juncao = $string_letras_juncao . $array_ids_juncoes[$key + 1];
										}
										if($string_nomes_juncao != ""){
											$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
										}
										else{
											$string_nomes_juncao = $string_nomes_juncao . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
										}
									}				
									else{
									
										if($string_letras_juncao != ""){
											$string_letras_juncao = $string_letras_juncao . " e " . $array_letras[$contador_letras];
										}
										else{
											$string_letras_juncao = $string_letras_juncao . $array_letras[$contador_letras];
										}
											
										if($string_nomes_juncao != ""){
											$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_letras[$contador_letras] . " " . $nome_juncao;
										}
										else{
											$string_nomes_juncao = $string_nomes_juncao . $array_letras[$contador_letras] . " " . $nome_juncao;
										}
										
										array_push($array_ids_juncoes,$id_juncao);
										array_push($array_ids_juncoes,$array_letras[$contador_letras]);
										array_push($array_ids_juncoes,$nome_juncao);
										
										$contador_letras = $contador_letras + 1;
									}
								}
							
						}
						
						$sheet1->setCellValue('L' . $counter_disciplina, $string_letras_juncao);
						
						//$sheet1->setCellValue('P' . $counter_disciplina, $string_nomes_juncao);
						
					}
			
				formatarLinhaUC($sheet1, $counter_disciplina);
				
				$counter_disciplina = $counter_disciplina + 1;
			}
			
			}
			
			criarTotal1Sem($sheet1,$counter_disciplina,$o);
			$sheet1->setCellValue('D' . $counter_disciplina, 'Total 2' . $o . ' Semestre');
			
			if(($counter_disciplina - $offset) > 1){
				$sum_range = "M" . $offset . ":M" . ($counter_disciplina - 1);
				$sheet1->setCellValue('M' . $counter_disciplina, '=SUM(M' . $offset . ':M' . ($counter_disciplina - 1) . ')');
			}
			else if(($counter_disciplina - $offset) == 1){
				$cell_valor = $sheet1->getCell('M' . ($counter_disciplina - 1));
				$valor = $cell_valor->getValue();
				$sheet1->setCellValue('M' . $counter_disciplina, $valor);
			}
			
			$cell_1 = $sheet1->getCell('L' . ($offset - 1));
			$cell_1_valor = $cell_1->getValue();
			$cell_2 = $sheet1->getCell('M' . ($counter_disciplina));
			$cell_2_valor = $cell_2->getValue();
			
			if(($cell_1_valor != "") &&($cell_2_valor != "")){
				$sheet1->setCellValue('N' . $b, '=(SUM(L' . ($offset - 1) . ',M' . ($counter_disciplina) . '))/2');
			}
	
	
	
	
	
	
	
	
	formatarDocente($b, ($counter_disciplina), $sheet1);
		
	$counter_disciplina = $counter_disciplina + 1;
	$b = $counter_disciplina;
	$counter_docente_cincento = $counter_docente_cincento + 1;
		
	$c = $c + 1;
}
$b = $b + 1;
	$counter_disciplina = $counter_disciplina + 1;
}

$componentes_turmas_utc_sem_docente = array();

$statement200 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente IS NULL;");
$statement200->execute();
$resultado200 = $statement200->get_result();
while($linha200 = mysqli_fetch_assoc($resultado200)){
	$id_componente = $linha200["id_componente"];
			
	$statement201 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
	$statement201->execute();
	$resultado201 = $statement201->get_result();
	$linha201 = mysqli_fetch_assoc($resultado201);
		$id_disciplina = $linha201["id_disciplina"];
				
		$statement202 = mysqli_prepare($conn, "SELECT id_curso FROM disciplina WHERE id_disciplina = $id_disciplina;");
		$statement202->execute();
		$resultado202 = $statement202->get_result();
		$linha202 = mysqli_fetch_assoc($resultado202);
			$id_curso = $linha202["id_curso"];
				
			$statement203 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
			$statement203->execute();
			$resultado203 = $statement203->get_result();
			$linha203 = mysqli_fetch_assoc($resultado203);
				$id_utc_disciplina = $linha203["id_utc"];
					
				if($id_utc_disciplina == $id_utc_post){
					array_push($componentes_turmas_utc_sem_docente,$id_componente);
				}	
}

$componentes_turmas_utc_sem_docente_final = implode("','",$componentes_turmas_utc_sem_docente);
$disciplinas_turmas_utc_sem_docente = array();

$r = 0;
while($r < sizeof($componentes_turmas_utc_sem_docente)){
	
	$id_componente = $componentes_turmas_utc_sem_docente[$r];
	
	$sheet1->setCellValue('B' . $b, '');
	$sheet1->setCellValue('C' . $b, '');
	
	$statement204 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
	$statement204->execute();
	$resultado204 = $statement204->get_result();
	$linha204 = mysqli_fetch_assoc($resultado204);
		$id_disciplina = $linha204["id_disciplina"];
	
		if(!in_array($id_disciplina,$disciplinas_turmas_utc_sem_docente)){
		array_push($disciplinas_turmas_utc_sem_docente,$id_disciplina);
		}
		/*
		$statement205 = mysqli_prepare($conn, "SELECT nome_uc, codigo_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
		$statement205->execute();
		$resultado205 = $statement205->get_result();
		$linha205 = mysqli_fetch_assoc($resultado205);
			$nome_uc = $linha205["nome_uc"];
			$codigo_uc = $linha205["codigo_uc"];
			$id_curso = $linha205["id_curso"];
			$ano = $linha205["ano"];
			
				$statement206 = mysqli_prepare($conn, "SELECT nome FROM curso WHERE id_curso = $id_curso;");
				$statement206->execute();
				$resultado206 = $statement206->get_result();
				$linha206 = mysqli_fetch_assoc($resultado206);
					$nome_curso = $linha206["nome"];
			
			$sheet1->setCellValue('D' . $b, $codigo_uc);
			$sheet1->setCellValue('E' . $b, $nome_curso);
			$sheet1->setCellValue('F' . $b, $nome_uc);
			
			$sheet1->setCellValue('H' . $b, $ano);
			
			$statement207 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente IS NULL;");
			$statement207->execute();
			$resultado207 = $statement207->get_result();
			$linha207 = mysqli_fetch_assoc($resultado207);
				$num_turmas = $linha207["COUNT(DISTINCT id_turma)"];
	
				$sheet1->setCellValue('J' . $b, $num_turmas);
				
				$siglas_string = "";
				
	
	formatarPorPreencher($b, ($counter_disciplina), $sheet1);
	
	$counter_disciplina = $counter_disciplina + 1;
	$b = $counter_disciplina;
	*/
	$r = $r + 1;
}

$disciplinas_turmas_utc_sem_docente_final = array_unique($disciplinas_turmas_utc_sem_docente); 

//print_r($disciplinas_turmas_utc_sem_docente_final); echo "\n";

if(sizeof($disciplinas_turmas_utc_sem_docente_final) > 0){
	$b = $b - 1;
	$counter_disciplina = $counter_disciplina - 1;
	dividirCategoria();
	$b = $b + 1;
	$counter_disciplina = $counter_disciplina + 1;

$e = 0;
while($e < sizeof($disciplinas_turmas_utc_sem_docente_final)){
	
	$id_disciplina = $disciplinas_turmas_utc_sem_docente_final[$e];
	
	$statement205 = mysqli_prepare($conn, "SELECT nome_uc, codigo_uc, id_curso, ano, id_area, semestre FROM disciplina WHERE id_disciplina = $id_disciplina;");
	$statement205->execute();
	$resultado205 = $statement205->get_result();
	$linha205 = mysqli_fetch_assoc($resultado205);
		$nome_uc = $linha205["nome_uc"];
		$codigo_uc = $linha205["codigo_uc"];
		$id_curso = $linha205["id_curso"];
		$ano = $linha205["ano"];
		$id_area = $linha205["id_area"];
		$semestre = $linha205["semestre"];
			
			$statement206 = mysqli_prepare($conn, "SELECT nome FROM curso WHERE id_curso = $id_curso;");
			$statement206->execute();
			$resultado206 = $statement206->get_result();
			$linha206 = mysqli_fetch_assoc($resultado206);
				$nome_curso = $linha206["nome"];
				
			$statement207 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
			$statement207->execute();
			$resultado207 = $statement207->get_result();
			$linha207 = mysqli_fetch_assoc($resultado207);
				$nome_area = $linha207["nome"];
			
			$sheet1->setCellValue('D' . $b, $codigo_uc);
			$sheet1->setCellValue('E' . $b, $nome_curso);
			$sheet1->setCellValue('F' . $b, $nome_uc);
			$sheet1->setCellValue('G' . $b, $nome_area);
			$sheet1->setCellValue('H' . $b, $ano . $o);
			$sheet1->setCellValue('I' . $b, 'Colaborador');
			
			$array_componentes_disciplina_sem_docente = array();
			$siglas_string = "";
			
			$statement208 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula a WHERE id_docente IS NULL;");
			$statement208->execute();
			$resultado208 = $statement208->get_result();
			while($linha208 = mysqli_fetch_assoc($resultado208)){
				$id_componente = $linha208["id_componente"];
				
				$statement209 = mysqli_prepare($conn, "SELECT id_disciplina, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
				$statement209->execute();
				$resultado209 = $statement209->get_result();
				$linha209 = mysqli_fetch_assoc($resultado209);
					$id_disciplina_temp = $linha209["id_disciplina"];
					$id_tipocomponente = $linha209["id_tipocomponente"];
					
					if($id_disciplina_temp == $id_disciplina){
						array_push($array_componentes_disciplina_sem_docente,$id_componente);	
						
						$statement211 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
						$statement211->execute();
						$resultado211 = $statement211->get_result();
						$linha211 = mysqli_fetch_assoc($resultado211);
							$sigla_tipocomponente = $linha211["sigla_tipocomponente"];;
						
						if($siglas_string != ""){
							$siglas_string = $siglas_string . " + " . $sigla_tipocomponente;
						}
						else{
							$siglas_string = $siglas_string . $sigla_tipocomponente;
						}
					}
			}
			
			$array_componentes_disciplina_sem_docente_final = implode(",",$array_componentes_disciplina_sem_docente);
			
			$statement210 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente IN ($array_componentes_disciplina_sem_docente_final) AND id_docente IS NULL;");
			$statement210->execute();
			$resultado210 = $statement210->get_result();
			$linha210 = mysqli_fetch_assoc($resultado210);
				$num_turmas = $linha210["COUNT(DISTINCT id_turma)"];
	
				$sheet1->setCellValue('J' . $b, $num_turmas);
				$sheet1->setCellValue('K' . $b, $siglas_string);
				
				$total_horas_1_sem = 0;
				$total_horas_2_sem = 0;
				
				//print_r($array_componentes_disciplina_sem_docente); echo "\n";
				
				$j = 0;
				while($j < sizeof($array_componentes_disciplina_sem_docente)){
					$id_comp_temp = $array_componentes_disciplina_sem_docente[$j];
					
					$statement212 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp_temp;");
					$statement212->execute();
					$resultado212 = $statement212->get_result();
					$linha212 = mysqli_fetch_assoc($resultado212);
						$num_horas = $linha212["numero_horas"];
						
						$statement213 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_temp AND id_docente IS NULL;");
						$statement213->execute();
						$resultado213 = $statement213->get_result();
						$linha213 = mysqli_fetch_assoc($resultado213);
							$num_turmas_temp = $linha213["COUNT(DISTINCT id_turma)"];
						
							$statement214 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_temp AND id_docente IS NULL;");
							$statement214->execute();
							$resultado214 = $statement214->get_result();
							$linha214 = mysqli_fetch_assoc($resultado214);
								$num_juncoes = $linha214["COUNT(DISTINCT id_juncao)"];
						
								if($num_juncoes == 0){
									//Numero horas = numero turmas
									
									if($semestre == 1){
										$total_horas_1_sem = $total_horas_1_sem + ($num_turmas_temp * $num_horas);
									}
									else{
										$total_horas_2_sem = $total_horas_2_sem + ($num_turmas_temp * $num_horas);
									}
								}
								
								else{
									$statement215 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_temp AND id_docente IS NULL AND id_juncao IS NULL;");
									$statement215->execute();
									$resultado215 = $statement215->get_result();
									$linha215 = mysqli_fetch_assoc($resultado215);
										$num_turmas_alem_juncao = $linha215["COUNT(DISTINCT id_turma)"];
										if($num_turmas_alem_juncao > 0){
											if($semestre == 1){
												$total_horas_1_sem = $total_horas_1_sem + ($num_juncoes * $num_horas) + ($num_turmas_alem_juncao * $num_horas);
											}
											else{
												$total_horas_2_sem = $total_horas_2_sem + ($num_juncoes * $num_horas) + ($num_turmas_alem_juncao * $num_horas);
											}
										}
										else{
											if($semestre == 1){
												$total_horas_1_sem = $total_horas_1_sem + ($num_juncoes * $num_horas);
											}
											else{
												$total_horas_2_sem = $total_horas_2_sem + ($num_juncoes * $num_horas);
											}
										}
								}
						
						$j = $j + 1;
				}

				if($total_horas_1_sem != 0){
					$sheet1->setCellValue('L' . $b, $total_horas_1_sem);
				}
				if($total_horas_2_sem != 0){
					$sheet1->setCellValue('M' . $b, $total_horas_2_sem);
				}
				
				//print_r($array_componentes_disciplina_sem_docente); echo "\n";
				
				//JUNCOES
				$f = 0;
				while($f < sizeof($array_componentes_disciplina_sem_docente)){
					$id_comp_temp = $array_componentes_disciplina_sem_docente[$f];

					$statement216 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_temp AND id_docente IS NULL;");
					$statement216->execute();
					$resultado216 = $statement216->get_result();
					$linha216 = mysqli_fetch_assoc($resultado216);
						$num_juncoes = $linha216["COUNT(DISTINCT id_juncao)"];		
				
						if($num_juncoes > 0){
							
							$string_letras_juncao = "";
							$string_nomes_juncao = "";
							
							$statement217 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp_temp AND id_docente IS NULL AND id_juncao IS NOT NULL;");
							$statement217->execute();
							$resultado217 = $statement217->get_result();
							while($linha217 = mysqli_fetch_assoc($resultado217)){
								$id_juncao = $linha217["id_juncao"];		
							
								$statement218 = mysqli_prepare($conn, "SELECT nome_juncao FROM juncao WHERE id_juncao = $id_juncao;");
								$statement218->execute();
								$resultado218 = $statement218->get_result();
								$linha218 = mysqli_fetch_assoc($resultado218);
									$nome_juncao = $linha218["nome_juncao"];
								
								$statement219 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT d.id_curso) FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina INNER JOIN juncao_componente jc ON c.id_componente = jc.id_componente WHERE jc.id_juncao = '$id_juncao';");
								$statement219->execute();
								$resultado219 = $statement219->get_result();
								$linha219 = mysqli_fetch_assoc($resultado219);
									$num_cursos_diferentes_juncao = $linha219["COUNT(DISTINCT d.id_curso)"];
								
									if($num_cursos_diferentes_juncao > 1){
										
										if(in_array($id_juncao,$array_ids_juncoes)){
											$key = array_search($id_juncao,$array_ids_juncoes);
											
											if($string_letras_juncao != ""){
												$string_letras_juncao = $string_letras_juncao . " e " . $array_ids_juncoes[$key + 1];
											}
											else{
												$string_letras_juncao = $string_letras_juncao . $array_ids_juncoes[$key + 1];
											}
											if($string_nomes_juncao != ""){
												$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
											}
											else{
												$string_nomes_juncao = $string_nomes_juncao . $array_ids_juncoes[$key + 1] . " " . $array_ids_juncoes[$key + 2];
											}
										}				
										else{
										
											if($string_letras_juncao != ""){
												$string_letras_juncao = $string_letras_juncao . " e " . $array_letras[$contador_letras];
											}
											else{
												$string_letras_juncao = $string_letras_juncao . $array_letras[$contador_letras];
											}
												
											if($string_nomes_juncao != ""){
												$string_nomes_juncao = $string_nomes_juncao . "\n" . $array_letras[$contador_letras] . " " . $nome_juncao;
											}
											else{
												$string_nomes_juncao = $string_nomes_juncao . $array_letras[$contador_letras] . " " . $nome_juncao;
											}
											
											array_push($array_ids_juncoes,$id_juncao);
											array_push($array_ids_juncoes,$array_letras[$contador_letras]);
											array_push($array_ids_juncoes,$nome_juncao);
											
											$contador_letras = $contador_letras + 1;
										}
									}	
							}
							
							if($semestre == 1){
								$sheet1->setCellValue('M' . $b, $string_letras_juncao);
								//$sheet1->setCellValue('P' . $b, $string_nomes_juncao);
							}
							if($semestre == 2){
								$sheet1->setCellValue('L' . $b, $string_letras_juncao);
								//$sheet1->setCellValue('P' . $b, $string_nomes_juncao);
							}
							$string_letras_juncao = "";
							$string_nomes_juncao = "";
							
						}
				
					$f = $f + 1;
				}
						
	
	formatarPorPreencher($b, ($counter_disciplina), $sheet1);
	
	$counter_disciplina = $counter_disciplina + 1;
	$b = $counter_disciplina;

	$e = $e + 1;
}

$b = $b + 1;
}

if(sizeof($array_ids_juncoes) > 0){
$sheet1->setCellValue('D' . $b, 'Notas:');
}

$b = $b + 2;

$u = 0;
while($u < sizeof($array_ids_juncoes)){
$id_juncao = $array_ids_juncoes[$u];
$letra_juncao = $array_ids_juncoes[$u + 1];
$nome_juncao = $array_ids_juncoes[$u + 2];

$sheet1->setCellValue('D' . $b, $letra_juncao . " " . $nome_juncao);
	
$b = $b + 1;
$u = $u + 3;
}

function formatarDocente($b, $offset_docente, $sheet1){
	
	global $counter_docente_cincento;
	
	$sheet1->mergeCells('B' . $b . ':B' . $offset_docente);
	$sheet1->mergeCells('C' . $b . ':C' . $offset_docente);
	$sheet1->mergeCells('N' . $b . ':N' . $offset_docente);
	$sheet1->mergeCells('D' . $offset_docente . ':K' . $offset_docente);	
	
	$sheet1
	->getStyle('B' . ($b) . ':B' . ($offset_docente))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('B' . ($b) . ':B' . ($offset_docente))
	->getAlignment()
	->setHorizontal('center');
	
	$sheet1
	->getStyle('C' . ($b) . ':C' . ($offset_docente))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('C' . ($b) . ':C' . ($offset_docente))
	->getAlignment()
	->setHorizontal('center');
	
	$sheet1
	->getStyle('B' . ($b) . ':b' . ($offset_docente))
	->getBorders()
	->getTop()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$sheet1
	->getStyle('B' . ($b) . ':b' . ($offset_docente))
	->getBorders()
	->getLeft()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	
	$sheet1
	->getStyle('B' . ($b) . ':M' . ($offset_docente))
	->getBorders()
	->getBottom()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$sheet1
	->getStyle('C' . ($b) . ':C' . ($offset_docente))
	->getBorders()
	->getLeft()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('C' . ($b) . ':C' . ($offset_docente))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('N' . ($b) . ':N' . ($offset_docente))
	->getBorders()
	->getLeft()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('N' . ($b) . ':N' . ($offset_docente))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$sheet1
	->getStyle('N' . ($b) . ':N' . ($offset_docente))
	->getBorders()
	->getBottom()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
		
	if(($counter_docente_cincento % 2) == 0){
		$sheet1
		->getStyle('B' . $b . ':N' . $b)
		->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()
		->setARGB('dddddddd');
	}
	
}

function formatarLinhaUC($sheet1, $counter_uc){
	
	global $counter_docente_cincento;
	
	$sheet1
	->getStyle('D' . ($counter_uc))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('D' . ($counter_uc))
	->getAlignment()
	->setHorizontal('center');
	
	$sheet1
	->getStyle('E' . ($counter_uc))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('E' . ($counter_uc))
	->getAlignment()
	->setHorizontal('left');
	
	$sheet1
	->getStyle('F' . ($counter_uc))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('F' . ($counter_uc))
	->getAlignment()
	->setHorizontal('left');
	
	$sheet1
	->getStyle('G' . ($counter_uc))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('G' . ($counter_uc))
	->getAlignment()
	->setHorizontal('left');
	
	$sheet1
	->getStyle('H' . ($counter_uc) . ':N' . ($counter_uc))
	->getAlignment()
	->setHorizontal('center');
	
	
	
	$sheet1
	->getStyle('D' . ($counter_uc))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('E' . ($counter_uc))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('F' . ($counter_uc))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('G' . ($counter_uc))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('H' . ($counter_uc))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('I' . ($counter_uc))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('J' . ($counter_uc))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('K' . ($counter_uc))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('L' . ($counter_uc))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('D' . ($counter_uc) . ':M' . ($counter_uc))
	->getBorders()
	->getBottom()
	->setBorderStyle(Border::BORDER_THIN);
	
	
	if(($counter_docente_cincento % 2) == 0){
		$sheet1
		->getStyle('D' . $counter_uc . ':M' . $counter_uc)
		->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()
		->setARGB('dddddddd');
	}
	
}

function criarTotal1Sem($sheet1,$counter_disciplina,$o){

	global $counter_docente_cincento;

	$sheet1->mergeCells('D' . $counter_disciplina . ':K' . $counter_disciplina);
	
	$sheet1
	->getStyle('D' . $counter_disciplina . ':M' . $counter_disciplina)
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('D' . $counter_disciplina . ':K' . $counter_disciplina)
	->getAlignment()
	->setHorizontal('right');
	
	$sheet1
	->getStyle('D' . $counter_disciplina . ':M' . $counter_disciplina)
	->getFont()
	->setBold(true);
	
	$sheet1
	->getStyle('L' . $counter_disciplina . ':M' . $counter_disciplina)
	->getAlignment()
	->setHorizontal('center');
	
	$sheet1
	->getStyle('D' . $counter_disciplina . ':K' . $counter_disciplina)
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('D' . $counter_disciplina . ':M' . $counter_disciplina)
	->getBorders()
	->getBottom()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('l' . $counter_disciplina)
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	if(($counter_docente_cincento % 2) == 0){
		$sheet1
		->getStyle('D' . $counter_disciplina . ':M' . $counter_disciplina)
		->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()
		->setARGB('dddddddd');
	}
	
}

function formatarPorPreencher($b, $offset_docente, $sheet1){
	
	$sheet1
		->getStyle('B' . $offset_docente)
		->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()
		->setARGB('00f8fc03');
	
	$sheet1
	->getStyle('B' . ($b))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('B' . ($b))
	->getAlignment()
	->setHorizontal('center');
	
	$sheet1
	->getStyle('C' . ($b))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('C' . ($b))
	->getAlignment()
	->setHorizontal('center');
	
	$sheet1
	->getStyle('B' . ($b))
	->getBorders()
	->getTop()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$sheet1
	->getStyle('B' . ($b))
	->getBorders()
	->getLeft()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	
	$sheet1
	->getStyle('B' . ($b))
	->getBorders()
	->getBottom()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$sheet1
	->getStyle('C' . ($b))
	->getBorders()
	->getLeft()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('C' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('N' . ($b))
	->getBorders()
	->getLeft()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('N' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$sheet1
	->getStyle('N' . ($b))
	->getBorders()
	->getBottom()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	
	
	$sheet1
	->getStyle('D' . ($b))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('D' . ($b))
	->getAlignment()
	->setHorizontal('center');
	
	$sheet1
	->getStyle('E' . ($b))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('E' . ($b))
	->getAlignment()
	->setHorizontal('left');
	
	$sheet1
	->getStyle('F' . ($b))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('F' . ($b))
	->getAlignment()
	->setHorizontal('left');
	
	$sheet1
	->getStyle('G' . ($b))
	->getAlignment()
	->setWrapText(true);
	
	$sheet1
	->getStyle('G' . ($b))
	->getAlignment()
	->setHorizontal('left');
	
	$sheet1
	->getStyle('H' . ($b) . ':N' . ($b))
	->getAlignment()
	->setHorizontal('center');
	
	
	$sheet1
	->getStyle('C' . ($b))
	->getBorders()
	->getBottom()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$sheet1
	->getStyle('D' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('E' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('F' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('G' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('H' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('I' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('J' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('K' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('L' . ($b))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$sheet1
	->getStyle('D' . ($b) . ':M' . ($b))
	->getBorders()
	->getBottom()
	->setBorderStyle(Border::BORDER_MEDIUM); 
	
}

function dividirCategoria(){
	
	global $b;
	global $sheet1;
	
	$sheet1
    ->getStyle('B' . ($b) . ':N' . ($b))
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('00abafff');

	$sheet1
	->getRowDimension($b)
	->setRowHeight(10); 

	$sheet1
	->getStyle('B' . ($b) . ':N' . ($b))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
}

//$sheet1->freezePane('A1','A1');

$writer = new Xlsx($spreadsheet1);
$nome_ficheiro = 'Docentes_' . $nome_utc . '_' . $ano_letivo_underscore . '.xlsx';
$writer->save($nome_ficheiro);
echo $nome_ficheiro;
//$writer->save("Docentes_CTC_2021_2022.xlsx");