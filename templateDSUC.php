<?php

session_start();

include('bd.h');
include('bd_final.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

$id_utc = $_POST["id_utc"];

$ano_letivo_temp = explode("_",$_SESSION["bd"]);
$ano_letivo = $ano_letivo_temp[2] . "/" . $ano_letivo_temp[3];
$ano_letivo_underscore = $ano_letivo_temp[2] . "_" . $ano_letivo_temp[3];

$statement000 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc;");
$statement000->execute();
$resultado000 = $statement000->get_result();
$linha000 = mysqli_fetch_assoc($resultado000);
	$nome_utc = $linha000["nome_utc"];

//Ler o template
$reader = IOFactory::createReader('Xlsx');
$spreadsheet1 = $reader->load("template_DSUC.xlsx");
$sheet1 = $spreadsheet1->getActiveSheet();

$cello = $sheet1->getCell('D4');
$o = $cello->getValue();
$o_final = substr($o,1,2);

$spreadsheet1
    ->getActiveSheet()
    ->getStyle('D2:D3')
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('dddddddd');
	
$spreadsheet1
    ->getActiveSheet()
    ->getStyle('D4')
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('00abafff');		

$spreadsheet1
    ->getActiveSheet()
    ->getStyle('D5')
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('dddddddd');	

$spreadsheet1
    ->getActiveSheet()
    ->getStyle('A7:J7')
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('00abafff');
	
$spreadsheet1
    ->getActiveSheet()
    ->getStyle('G9:Q9')
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('00abafff');

$spreadsheet1
    ->getActiveSheet()
    ->getStyle('J8')
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('00abafff');

$workSheetTemplate = clone $spreadsheet1->getActiveSheet();

$array_ids_juncoes = array();
$array_letras = array('a)','b)','c)','d)','e)','f)','g)','h)','i)','j)','k)','l)','m)','n)','o)','p)','q)','r)','s)','t)','u)','v)','w)','x)','y)','z)',);
$array_juncoes_1_sem = array();
$contador_letras_1_sem = 0;

$array_juncoes_2_sem = array();
$contador_letras_2_sem = array();
		
$counter_sheet = 0;

$statement00 = mysqli_prepare($conn, "SELECT DISTINCT id_curso FROM curso WHERE id_utc = $id_utc;");
$statement00->execute();
$resultado00 = $statement00->get_result();
while($linha00 = mysqli_fetch_assoc($resultado00)){
	$id_curso = $linha00["id_curso"];

	$statement0 = mysqli_prepare($conn, "SELECT semestres, nome_completo, sigla_completa FROM curso WHERE id_curso = $id_curso;");
	$statement0->execute();
	$resultado0 = $statement0->get_result();
	$linha0 = mysqli_fetch_assoc($resultado0);
		$numero_semestres = $linha0["semestres"];
		$nome_completo = $linha0["nome_completo"];
		$sigla_completa = $linha0["sigla_completa"];
		
		$sem_1 = clone $workSheetTemplate;
		$sem_1->setTitle($sigla_completa . ' (1' . $o_final . 'SEM)');
		$spreadsheet1->addSheet($sem_1, $counter_sheet);
		$sheet1 = $spreadsheet1->getSheet($counter_sheet);
		$spreadsheet1->setActiveSheetIndex($counter_sheet);
		
		$sheet1->setCellValue('D4','1' . $o_final . ' SEMESTRE DO ANO LECTIVO ' . $ano_letivo);
		$sheet1->setCellValue('D5',$nome_completo);
		
		$a = 10;
		$counter_format = 0;
		$contador_ano = 1;
		$contador_letras = 0;
		$array_juncoes_1_sem = array();
		$contador_letras_1_sem = 0;
		
		while($contador_ano <= ($numero_semestres / 2)){
			
			escreverDisciplinasAno($conn, $o_final, $spreadsheet1, $sheet1, $contador_ano, $id_curso, 1);
			
			if($contador_ano != ($numero_semestres / 2)){
				dividirAno($spreadsheet1,$a);
				$a = $a + 1;
			}
			
			$contador_ano = $contador_ano + 1;
		}
		
		escreverJuncoes($sheet1,$array_juncoes_1_sem,$a);
		
		//corrigirJuncoes($sheet1, $a);

		$counter_sheet = $counter_sheet + 1;

		// Fazer isto lá em cima e nao aqui
		$sem_2 = clone $workSheetTemplate;
		$sem_2->setTitle($sigla_completa . ' (2' . $o_final . 'SEM)');

		// Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
		$spreadsheet1->addSheet($sem_2, $counter_sheet);
		
		$sheet2 = $spreadsheet1->getSheet($counter_sheet);
		$spreadsheet1->setActiveSheetIndex($counter_sheet);
		
		$sheet2->setCellValue('D4','2' . $o_final . ' SEMESTRE DO ANO LECTIVO 2021/2022');
		$sheet2->setCellValue('D5',$nome_completo);
		
		$a = 10;
		$counter_format = 0;
		$contador_ano_ = 1;
		$contador_letras = 0;
		$array_juncoes_2_sem = array();
		$contador_letras_2_sem = 0;
		
		while($contador_ano_ <= ($numero_semestres / 2)){
			
			escreverDisciplinasAno($conn, $o_final, $spreadsheet1, $sheet2, $contador_ano_, $id_curso, 2);
			
			if($contador_ano_ != ($numero_semestres / 2)){
				dividirAno($spreadsheet1,$a);
				$a = $a + 1;
			}
			
			$contador_ano_ = $contador_ano_ + 1;
		}
		
		escreverJuncoes_2_sem($sheet2,$array_juncoes_2_sem,$a);
		//corrigirJuncoes($sheet2, $a);
		
		$counter_sheet = $counter_sheet + 1;
	
}
	
	$num_sheets = $spreadsheet1->getSheetCount();
	$spreadsheet1->removeSheetByIndex($num_sheets - 1);
	$spreadsheet1->setActiveSheetIndex(0);
	
	$writer = new Xlsx($spreadsheet1);
	$nome_ficheiro = 'DSD_UTC_' . $nome_utc . '_' . $ano_letivo_underscore . '.xlsx';
	$writer->save($nome_ficheiro);
	echo $nome_ficheiro;

function escreverDisciplinasAno($conn, $o_final, $spreadsheet1, $sheet1, $contador_ano, $id_curso, $sem){
	$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_curso = $id_curso AND ano = $contador_ano AND semestre = $sem ORDER BY ano, semestre;");
	$statement->execute();
	$resultado = $statement->get_result();
	while($linha = mysqli_fetch_assoc($resultado)){
		
		global $a;
		global $counter_format;
		global $array_letras;
		global $contador_letras;
		global $array_ids_juncoes;
		
		global $array_juncoes_1_sem;
		global $contador_letras_1_sem;
		
		global $array_juncoes_2_sem;
		global $contador_letras_2_sem;
		
		$id = $linha["id_disciplina"];
		$ano = $linha["ano"] . $o_final;
		$nome_uc = $linha["nome_uc"];
		$codigo_uc = $linha["codigo_uc"];
		$id_area = $linha["id_area"];
		$id_responsavel = $linha["id_responsavel"];
		$sheet1->setCellValue('A' . $a,$ano);
		$sheet1->setCellValue('B' . $a,$nome_uc);
		$sheet1->setCellValue('C' . $a,$codigo_uc);
		
		$statement2 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_assoc($resultado2);
			$nome_area = $linha2["nome"];
			
			$sheet1->setCellValue('D' . $a,$nome_area);
		
		$carga_t = 0;
		$carga_tp = 0;
		$carga_p = 0;
		$carga_pl = 0;
		$carga_ot = 0;
		$carga_s = 0;
			
		$statement21 = mysqli_prepare($conn, "SELECT DISTINCT id_componente, id_tipocomponente, numero_horas FROM componente WHERE id_disciplina = $id;");
		$statement21->execute();
		$resultado21 = $statement21->get_result();
		while($linha21 = mysqli_fetch_assoc($resultado21)){
			$idComponente = $linha21["id_componente"];
			$id_tipocomponente = $linha21["id_tipocomponente"];
			$numero_horas = $linha21["numero_horas"];
			
			if($id_tipocomponente == 1){
				$carga_t = $carga_t + ($numero_horas * 15);
			}
			else if($id_tipocomponente == 2){
				$carga_tp = $carga_tp + ($numero_horas * 15);
			}
			else if($id_tipocomponente == 3){
				$carga_p = $carga_p + ($numero_horas * 15);
			}
			else if($id_tipocomponente == 4){
				$carga_pl = $carga_pl + ($numero_horas * 15);
			}
			else if($id_tipocomponente == 6){
				$carga_ot = $carga_ot + ($numero_horas * 15);
			}
			else if($id_tipocomponente == 7){
				$carga_s = $carga_s + ($numero_horas * 15);
			}
		}
		
		if($carga_t == 0){
			if($carga_s == 0){
				$string_carga_horaria_semanal = "TP: " . $carga_tp . "\n" . "P: " . $carga_p
												. "\n" . "PL: " . $carga_pl . "\n" . "OT: " . $carga_ot;
			}
			else{
				$string_carga_horaria_semanal = "TP: " . $carga_tp . "\n" . "P: " . $carga_p
												. "\n" . "PL: " . $carga_pl . "\n" . "S: " . $carga_s;
			}
			
		}
		else{
			if($carga_s == 0){
			$string_carga_horaria_semanal = "T: " . $carga_t . "\n" . "P: " . $carga_p
											. "\n" . "PL: " . $carga_pl . "\n" . "OT: " . $carga_ot;
			}
			else{
				$string_carga_horaria_semanal = "T: " . $carga_t . "\n" . "P: " . $carga_p
											. "\n" . "PL: " . $carga_pl . "\n" . "S: " . $carga_s;
			}
		}
		
		$sheet1->setCellValue('E' . $a,$string_carga_horaria_semanal);
		
		$array_componentes = array();
		
			$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			while($linha3 = mysqli_fetch_assoc($resultado3)){
				$id_componente = $linha3["id_componente"];
				
				array_push($array_componentes,$id_componente);
			}
			
		$array_componentes_final = implode(",",$array_componentes);
		
		$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente IN ($array_componentes_final);");
		$statement4->execute();
		$resultado4 = $statement4->get_result();
		$linha4 = mysqli_fetch_assoc($resultado4);
			$num_turmas = $linha4["COUNT(DISTINCT id_turma)"];
			
			$sheet1->setCellValue('F' . $a,$num_turmas);
			
			
		/*-----------------------------------------------RESPONSÁVEL-----------------------------------------------*/
		
		$statement5 = mysqli_prepare($conn, "SELECT nome, id_funcao FROM utilizador WHERE id_utilizador = $id_responsavel;");
		$statement5->execute();
		$resultado5 = $statement5->get_result();
		$linha5 = mysqli_fetch_assoc($resultado5);
			$nome_responsavel = $linha5["nome"];
			$id_funcao_responsavel = $linha5["id_funcao"];
			
			$statement6 = mysqli_prepare($conn, "SELECT nome FROM funcao f WHERE id_funcao = $id_funcao_responsavel;");
			$statement6->execute();
			$resultado6 = $statement6->get_result();
			$linha6 = mysqli_fetch_assoc($resultado6);
				$categoria_responsavel = $linha6["nome"];
			
			$sheet1->setCellValue('H' . $a,$nome_responsavel);
			$sheet1->setCellValue('I' . $a,$categoria_responsavel);
			
		$statement7 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente IN ($array_componentes_final) AND id_docente = $id_responsavel;");
		$statement7->execute();
		$resultado7 = $statement7->get_result();
		$linha7 = mysqli_fetch_assoc($resultado7);
			$num_componentes_docente = $linha7["COUNT(id_componente)"];
			
			if($num_componentes_docente > 0){
				
				$carga_horaria_t = 0;
				$carga_horaria_tp = 0;
				$carga_horaria_p = 0;
				$carga_horaria_pl = 0;
				$carga_horaria_tc = 0;
				$carga_horaria_ot = 0;
				$carga_horaria_s = 0;
				
				$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_componente IN ($array_componentes_final) AND id_docente = $id_responsavel;");
				$statement8->execute();
				$resultado8 = $statement8->get_result();
				while($linha8 = mysqli_fetch_assoc($resultado8)){
					$id_comp = $linha8["id_componente"];
					
					$num_aulas_componente = 0;
					
					$statement85 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_responsavel;");
					$statement85->execute();
					$resultado85 = $statement85->get_result();
					$linha85 = mysqli_fetch_assoc($resultado85);
						$num_juncoes_componente_docente = $linha85["COUNT(DISTINCT id_juncao)"];
						
						if($num_juncoes_componente_docente == 0){
							$statement86 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_responsavel;");
							$statement86->execute();
							$resultado86 = $statement86->get_result();
							$linha86 = mysqli_fetch_assoc($resultado86);
								$num_aulas_componente = $linha86["COUNT(id_componente)"];
						}
						else{
							
							$statement88 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_responsavel AND id_juncao IS NULL;");
							$statement88->execute();
							$resultado88 = $statement88->get_result();
							$linha88 = mysqli_fetch_assoc($resultado88);
								$num_componentes_nao_juncao = $linha88["COUNT(id_componente)"];
								
								if($num_componentes_nao_juncao > 0){
									$num_aulas_componente = $num_juncoes_componente_docente + $num_componentes_nao_juncao;
								}
								else{
									$num_aulas_componente = $num_juncoes_componente_docente;
								}
							
						}
					
					$statement9 = mysqli_prepare($conn, "SELECT id_tipocomponente, numero_horas FROM componente WHERE id_componente = $id_comp;");
					$statement9->execute();
					$resultado9 = $statement9->get_result();
					$linha9 = mysqli_fetch_assoc($resultado9);
						$id_tipocomp = $linha9["id_tipocomponente"];
						$carga_horaria = $linha9["numero_horas"];
						
						if($id_tipocomp == 1){
							$carga_horaria_t = $carga_horaria_t + ($num_aulas_componente * $carga_horaria);
						}
						else if($id_tipocomp == 2){
							$carga_horaria_tp = $carga_horaria_tp + ($num_aulas_componente * $carga_horaria);
						}
						else if($id_tipocomp == 3){
							$carga_horaria_p = $carga_horaria_p + ($num_aulas_componente * $carga_horaria);
						}
						else if($id_tipocomp == 4){
							$carga_horaria_pl = $carga_horaria_pl + ($num_aulas_componente * $carga_horaria);
						}
						else if($id_tipocomp == 5){
							$carga_horaria_tc = $carga_horaria_tc + ($num_aulas_componente * $carga_horaria);
						}
						else if($id_tipocomp == 6){
							$carga_horaria_ot = $carga_horaria_ot + ($num_aulas_componente * $carga_horaria);
						}
						else if($id_tipocomp == 7){
							$carga_horaria_s = $carga_horaria_s + ($num_aulas_componente * $carga_horaria);
						}
				}
				
				if($carga_horaria_t != 0){
					$sheet1->setCellValue('J' . $a,$carga_horaria_t);
				}
				if($carga_horaria_tp != 0){
					$sheet1->setCellValue('K' . $a,$carga_horaria_tp);
				}
				if($carga_horaria_p != 0){
					$sheet1->setCellValue('L' . $a,$carga_horaria_p);
				}
				if($carga_horaria_pl != 0){
					$sheet1->setCellValue('M' . $a,$carga_horaria_pl);
				}
				if($carga_horaria_tc != 0){
					$sheet1->setCellValue('N' . $a,$carga_horaria_tc);
				}
				if($carga_horaria_ot != 0){
					$sheet1->setCellValue('O' . $a,$carga_horaria_ot);
				}
				if($carga_horaria_s != 0){
					$sheet1->setCellValue('P' . $a,$carga_horaria_s);
				}
			
				$num_horas_total = $carga_horaria_t + $carga_horaria_tp + $carga_horaria_p + $carga_horaria_pl + 
						$carga_horaria_tc + $carga_horaria_ot + $carga_horaria_s;
					
				if($num_horas_total > 0){
					$sheet1->setCellValue('Q' . $a,$num_horas_total);
				}
				
				$statement190 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente IN($array_componentes_final) AND id_docente = $id_responsavel AND id_juncao IS NOT NULL;");
				$statement190->execute();
				$resultado190 = $statement190->get_result();
				$linha190 = mysqli_fetch_assoc($resultado190);
					$num_juncoes_docente = $linha190["COUNT(DISTINCT id_juncao)"];
					
					if($num_juncoes_docente > 0){
						
						$string_juncoes = "";
						$string_juncoes_r = "";
						
						$statement191 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente IN($array_componentes_final) AND id_docente = $id_responsavel AND id_juncao IS NOT NULL;");
						$statement191->execute();
						$resultado191 = $statement191->get_result();
						while($linha191 = mysqli_fetch_assoc($resultado191)){
							$id_juncao_docente = $linha191["id_juncao"];
							
							$statement2020 = mysqli_prepare($conn, "SELECT nome_juncao FROM juncao WHERE id_juncao = $id_juncao_docente;");
							$statement2020->execute();
							$resultado2020 = $statement2020->get_result();
							$linha2020 = mysqli_fetch_assoc($resultado2020);
								$nome_juncao = $linha2020["nome_juncao"];
							
							$statement192 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT d.id_curso) FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina INNER JOIN juncao_componente jc ON c.id_componente = jc.id_componente WHERE jc.id_juncao = $id_juncao_docente;");
							$statement192->execute();
							$resultado192 = $statement192->get_result();
							$linha192 = mysqli_fetch_assoc($resultado192);
								$num_cursos_diferentes_juncao = $linha192["COUNT(DISTINCT d.id_curso)"];
								
								if($num_cursos_diferentes_juncao > 1){	
									
									if($sem == 1){
										if(!in_array($id_juncao_docente,$array_juncoes_1_sem)){
											
											if($string_juncoes != ""){
												$string_juncoes = $string_juncoes . " e " . $array_letras[$contador_letras_1_sem];
											}
											else{
												$string_juncoes = $array_letras[$contador_letras_1_sem];
											}
											
											array_push($array_juncoes_1_sem,$id_juncao_docente);
											array_push($array_juncoes_1_sem,$array_letras[$contador_letras_1_sem]);
											array_push($array_juncoes_1_sem,$nome_juncao);
											$contador_letras_1_sem += 1;
											
										}
										else{
											
											$posicao = array_search($id_juncao_docente,$array_juncoes_1_sem);
											
											if($string_juncoes != ""){
												$string_juncoes = $string_juncoes . " e " . $array_juncoes_1_sem[$posicao + 1];
											}
											else{
												$string_juncoes = $array_juncoes_1_sem[$posicao + 1];
											}
												
										}
										
										$sheet1->setCellValue('R' . $a,$string_juncoes);
									}
									else{
										
										if(!in_array($id_juncao_docente,$array_juncoes_2_sem)){
											
											if($string_juncoes != ""){
												$string_juncoes = $string_juncoes . " e " . $array_letras[$contador_letras_2_sem];
											}
											else{
												$string_juncoes = $array_letras[$contador_letras_2_sem];
											}
											
											array_push($array_juncoes_2_sem,$id_juncao_docente);
											array_push($array_juncoes_2_sem,$array_letras[$contador_letras_2_sem]);
											array_push($array_juncoes_2_sem,$nome_juncao);
											$contador_letras_2_sem += 1;
											
										}
										else{
											
											$posicao = array_search($id_juncao_docente,$array_juncoes_2_sem);
											
											if($string_juncoes != ""){
												$string_juncoes = $string_juncoes . " e " . $array_juncoes_2_sem[$posicao + 1];
											}
											else{
												$string_juncoes = $array_juncoes_2_sem[$posicao + 1];
											}
												
										}
										
										$sheet1->setCellValue('R' . $a,$string_juncoes);
											
									}
								}
								
						}
						
					}
			
			}
			
		/*-----------------------------------------------OUTROS DOCENTES-----------------------------------------------*/	
		
		$statement20 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_docente) FROM aula WHERE id_componente IN ($array_componentes_final) AND id_docente != $id_responsavel;");
		$statement20->execute();
		$resultado20 = $statement20->get_result();
		$linha20 = mysqli_fetch_assoc($resultado20);
			$num_docentes_outros = $linha20["COUNT(DISTINCT id_docente)"];
		
		if($num_docentes_outros > 0){
		
			$counter_docente = $a;
		
			$statement10 = mysqli_prepare($conn, "SELECT DISTINCT id_docente FROM aula WHERE id_componente IN ($array_componentes_final)
													AND id_docente != $id_responsavel;");
			$statement10->execute();
			$resultado10 = $statement10->get_result();
			while($linha10 = mysqli_fetch_assoc($resultado10)){
				$id_docente = $linha10["id_docente"];
				
				$statement11 = mysqli_prepare($conn, "SELECT nome,id_funcao FROM utilizador WHERE id_utilizador = $id_docente;");
				$statement11->execute();
				$resultado11 = $statement11->get_result();
				$linha11 = mysqli_fetch_assoc($resultado11);
					$nome_docente = $linha11["nome"];
					$id_funcao_responsavel = $linha11["id_funcao"];
			
					$statement12 = mysqli_prepare($conn, "SELECT nome FROM funcao f WHERE id_funcao = $id_funcao_responsavel;");
					$statement12->execute();
					$resultado12 = $statement12->get_result();
					$linha12 = mysqli_fetch_assoc($resultado12);
						$categoria_docente = $linha12["nome"];
					
					$sheet1->setCellValue('H' . $counter_docente + 1,$nome_docente);
					$sheet1->setCellValue('I' . $counter_docente + 1,$categoria_docente);
					
		
				//Carga horária
				$statement13 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente IN ($array_componentes_final) AND id_docente = $id_docente;");
				$statement13->execute();
				$resultado13 = $statement13->get_result();
				$linha13 = mysqli_fetch_assoc($resultado13);
				
				$num_componentes_docente = $linha13["COUNT(id_componente)"];
				
				if($num_componentes_docente > 0){
					
					$carga_horaria_t = 0;
					$carga_horaria_tp = 0;
					$carga_horaria_p = 0;
					$carga_horaria_pl = 0;
					$carga_horaria_tc = 0;
					$carga_horaria_ot = 0;
					$carga_horaria_s = 0;
					
					$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_componente IN ($array_componentes_final) AND id_docente = $id_docente;");
					$statement8->execute();
					$resultado8 = $statement8->get_result();
					while($linha8 = mysqli_fetch_assoc($resultado8)){
						$id_comp = $linha8["id_componente"];
						
						$num_aulas_componente = 0;
					
						$statement85 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_docente;");
						$statement85->execute();
						$resultado85 = $statement85->get_result();
						$linha85 = mysqli_fetch_assoc($resultado85);
							$num_juncoes_componente_docente = $linha85["COUNT(DISTINCT id_juncao)"];
						
						if($num_juncoes_componente_docente == 0){
							$statement86 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_docente;");
							$statement86->execute();
							$resultado86 = $statement86->get_result();
							$linha86 = mysqli_fetch_assoc($resultado86);
								$num_aulas_componente = $linha86["COUNT(id_componente)"];
						}
						else{
							
							$statement88 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp AND id_docente = $id_docente AND id_juncao IS NULL;");
							$statement88->execute();
							$resultado88 = $statement88->get_result();
							$linha88 = mysqli_fetch_assoc($resultado88);
								$num_componentes_nao_juncao = $linha88["COUNT(id_componente)"];
								
								if($num_componentes_nao_juncao > 0){
									$num_aulas_componente = $num_juncoes_componente_docente + $num_componentes_nao_juncao;
								}
								else{
									$num_aulas_componente = $num_juncoes_componente_docente;
								}
							
						}
						
						$statement9 = mysqli_prepare($conn, "SELECT id_tipocomponente, numero_horas FROM componente WHERE id_componente = $id_comp;");
						$statement9->execute();
						$resultado9 = $statement9->get_result();
						$linha9 = mysqli_fetch_assoc($resultado9);
							$id_tipocomp = $linha9["id_tipocomponente"];
							$carga_horaria = $linha9["numero_horas"];
							
							if($id_tipocomp == 1){
								$carga_horaria_t = $carga_horaria_t + ($num_aulas_componente * $carga_horaria);
							}
							else if($id_tipocomp == 2){
								$carga_horaria_tp = $carga_horaria_tp + ($num_aulas_componente * $carga_horaria);
							}
							else if($id_tipocomp == 3){
								$carga_horaria_p = $carga_horaria_p + ($num_aulas_componente * $carga_horaria);
							}
							else if($id_tipocomp == 4){
								$carga_horaria_pl = $carga_horaria_pl + ($num_aulas_componente * $carga_horaria);
							}
							else if($id_tipocomp == 5){
								$carga_horaria_tc = $carga_horaria_tc + ($num_aulas_componente * $carga_horaria);
							}
							else if($id_tipocomp == 6){
								$carga_horaria_ot = $carga_horaria_ot + ($num_aulas_componente * $carga_horaria);
							}
							else if($id_tipocomp == 7){
								$carga_horaria_s = $carga_horaria_s + ($num_aulas_componente * $carga_horaria);
							}
					}
					
					if($carga_horaria_t != 0){
						$sheet1->setCellValue('J' . $counter_docente + 1,$carga_horaria_t);
					}
					if($carga_horaria_tp != 0){
						$sheet1->setCellValue('K' . $counter_docente + 1,$carga_horaria_tp);
					}
					if($carga_horaria_p != 0){
						$sheet1->setCellValue('L' . $counter_docente + 1,$carga_horaria_p);
					}
					if($carga_horaria_pl != 0){
						$sheet1->setCellValue('M' . $counter_docente + 1,$carga_horaria_pl);
					}
					if($carga_horaria_tc != 0){
						$sheet1->setCellValue('N' . $counter_docente + 1,$carga_horaria_tc);
					}
					if($carga_horaria_ot != 0){
						$sheet1->setCellValue('O' . $counter_docente + 1,$carga_horaria_ot);
					}
					if($carga_horaria_s != 0){
						$sheet1->setCellValue('P' . $counter_docente + 1,$carga_horaria_s);
					}
					
					$num_horas_total = $carga_horaria_t + $carga_horaria_tp + $carga_horaria_p + $carga_horaria_pl + 
						$carga_horaria_tc + $carga_horaria_ot + $carga_horaria_s;
					
					if($num_horas_total > 0){
						$sheet1->setCellValue('Q' . $counter_docente + 1,$num_horas_total);
					}
					
					$statement195 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente IN($array_componentes_final) AND id_docente = $id_docente AND id_juncao IS NOT NULL;");
					$statement195->execute();
					$resultado195 = $statement195->get_result();
					$linha195 = mysqli_fetch_assoc($resultado195);
						$num_juncoes_docente = $linha195["COUNT(DISTINCT id_juncao)"];
						
						if($num_juncoes_docente > 0){
							
							$string_juncoes_2 = "";
							$string_juncoes_r_2 = "";
							
							$statement196 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente IN($array_componentes_final) AND id_docente = $id_docente AND id_juncao IS NOT NULL;");
							$statement196->execute();
							$resultado196 = $statement196->get_result();
							while($linha196 = mysqli_fetch_assoc($resultado196)){
								$id_juncao_docente_2 = $linha196["id_juncao"];
								
								$statement2021 = mysqli_prepare($conn, "SELECT nome_juncao FROM juncao WHERE id_juncao = $id_juncao_docente_2;");
								$statement2021->execute();
								$resultado2021 = $statement2021->get_result();
								$linha2021 = mysqli_fetch_assoc($resultado2021);
									$nome_juncao_2 = $linha2021["nome_juncao"];
								
								$statement197 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT d.id_curso) FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina INNER JOIN juncao_componente jc ON c.id_componente = jc.id_componente WHERE jc.id_juncao = $id_juncao_docente_2;");
								$statement197->execute();
								$resultado197 = $statement197->get_result();
								$linha197 = mysqli_fetch_assoc($resultado197);
									$num_cursos_diferentes_juncao_2 = $linha197["COUNT(DISTINCT d.id_curso)"];
									
									if($num_cursos_diferentes_juncao_2 > 1){
										
										if($sem == 1){
										
											if(!in_array($id_juncao_docente_2,$array_juncoes_1_sem)){

												if($string_juncoes_2 != ""){
													$string_juncoes_2 = $string_juncoes_2 . " e " . $array_letras[$contador_letras_1_sem];
												}
												else{
													$string_juncoes_2 = $array_letras[$contador_letras_1_sem];
												}
												
												array_push($array_juncoes_1_sem,$id_juncao_docente_2);
												array_push($array_juncoes_1_sem,$array_letras[$contador_letras_1_sem]);
												array_push($array_juncoes_1_sem,$nome_juncao_2);
												$contador_letras_1_sem += 1;

											}
											else{
												
												$posicao = array_search($id_juncao_docente_2,$array_juncoes_1_sem);
												
												if($string_juncoes_2 != ""){
													$string_juncoes_2 = $string_juncoes_2 . " e " . $array_juncoes_1_sem[$posicao + 1];
												}
												else{
													$string_juncoes_2 = $array_juncoes_1_sem[$posicao + 1];
												}	
												
											}
											
										}
										else{
											
											if(!in_array($id_juncao_docente_2,$array_juncoes_2_sem)){

												if($string_juncoes_2 != ""){
													$string_juncoes_2 = $string_juncoes_2 . " e " . $array_letras[$contador_letras_2_sem];
												}
												else{
													$string_juncoes_2 = $array_letras[$contador_letras_2_sem];
												}
												
												array_push($array_juncoes_2_sem,$id_juncao_docente_2);
												array_push($array_juncoes_2_sem,$array_letras[$contador_letras_2_sem]);
												array_push($array_juncoes_2_sem,$nome_juncao_2);
												$contador_letras_2_sem += 1;

											}
											else{
												
												$posicao = array_search($id_juncao_docente_2,$array_juncoes_2_sem);
												
												if($string_juncoes_2 != ""){
													$string_juncoes_2 = $string_juncoes_2 . " e " . $array_juncoes_2_sem[$posicao + 1];
												}
												else{
													$string_juncoes_2 = $array_juncoes_2_sem[$posicao + 1];
												}	
												
											}
											
										}
										
									}
							}
							
							$sheet1->setCellValue('R' . $counter_docente + 1,$string_juncoes_2);
							
						}
					
					
				}
				
				$counter_docente = $counter_docente + 1;
				
			}
		
		}
		
		$num_docentes_total = 0;
		
		$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_componente = $linha2["id_componente"];
			
			$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_docente) FROM aula WHERE id_componente = $id_componente;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$num_docentes = $linha3["COUNT(DISTINCT id_docente)"];
				$num_docentes_total = $num_docentes_total + $num_docentes;
		}
		
		$counter_format = $counter_format + 1;
		
		if($num_docentes_total > 4){
				$a = $a + $num_docentes_total;
				
				formatarSpreadsheet($spreadsheet1, $a, $num_docentes_total);
				
				}
		else{
			$a = $a + 4;
			
			formatarSpreadsheet($spreadsheet1, $a, 4);
		} 
		
	}

}

function formatarSpreadsheet($spreadsheet_param, $a, $offset) {

	global $counter_format;

	$spreadsheet_param
	->getActiveSheet()
	->mergeCells('A' . ($a - $offset) . ':A' . ($a - 1));

	$spreadsheet_param
	->getActiveSheet()
	->mergeCells('B' . ($a - $offset) . ':B' . ($a - 1));

	$spreadsheet_param
	->getActiveSheet()
	->mergeCells('C' . ($a - $offset) . ':C' . ($a - 1));

	$spreadsheet_param
	->getActiveSheet()
	->mergeCells('D' . ($a - $offset) . ':D' . ($a - 1));

	$spreadsheet_param
	->getActiveSheet()
	->mergeCells('E' . ($a - $offset) . ':E' . ($a - 1));

	$spreadsheet_param
	->getActiveSheet()
	->mergeCells('F' . ($a - $offset) . ':F' . ($a - 1));

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('A' . ($a - $offset) . ':C' . ($a - 1))
	->getFont()
	->setSize(13);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('C' . ($a - $offset))
	->getFont()
	->setBold(true);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('D' . ($a - $offset) . ':E' . ($a - $offset))
	->getFont()
	->setSize(12);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('D' . ($a - $offset) . ':E' . ($a - $offset))
	->getFont()
	->setName('Calibri');

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('F' . ($a - $offset))
	->getFont()
	->setSize(13);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('A' . ($a - $offset) . ':F' . ($a - $offset))
	->getAlignment()
	->setWrapText(true);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('A' . ($a - $offset) . ':F' . ($a - $offset))
	->getAlignment()
	->setHorizontal('center');
	
	if(($counter_format % 2) == 0){
		$spreadsheet_param
		->getActiveSheet()
		->getStyle('A' . ($a - $offset) . ':F' . ($a - $offset))
		->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()
		->setARGB('dddddddd');
		
		$spreadsheet_param
		->getActiveSheet()
		->getStyle('G' . ($a - $offset) . ':Q' . ($a - $offset))
		->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()
		->setARGB('dddddddd');
		
		$spreadsheet_param
		->getActiveSheet()
		->getStyle('G' . ($a - $offset + 1))
		->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()
		->setARGB('dddddddd');
		
		$r = 1;
		while($r < $offset){
			
			$spreadsheet_param
			->getActiveSheet()
			->getStyle('H' . ($a - $offset + $r) . ':Q' . ($a - $offset + $r))
			->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('dddddddd');
			
			$r = $r + 1;
		}
	}

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('G' . ($a - $offset))
	->getFont()
	->setSize(13);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('G' . ($a - $offset))
	->getFont()
	->setBold(true);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('G' . ($a - $offset))
	->getAlignment()
	->setWrapText(true);

	$spreadsheet_param
	->getActiveSheet()
	->setCellValue('G' . ($a - $offset),'Resp.');

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('G' . ($a - $offset))
	->getAlignment()
	->setHorizontal('center');

	$spreadsheet_param
	->getActiveSheet()
	->mergeCells('G' . ($a - $offset + 1) . ':G' . ($a - 1));

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('G' . ($a - $offset + 1))
	->getFont()
	->setSize(13);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('G' . ($a - $offset + 1))
	->getAlignment()
	->setWrapText(true);

	$spreadsheet_param
	->getActiveSheet()
	->setCellValue('G' . ($a - $offset + 1),'Corpo Docente');

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('G' . ($a - $offset + 1))
	->getAlignment()
	->setHorizontal('center');

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('H' . ($a - $offset) . ':Q' . ($a - $offset))
	->getFont()
	->setSize(13);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('H' . ($a - $offset) . ':Q' . ($a - $offset))
	->getFont()
	->setBold(true);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('H' . ($a - $offset) . ':Q' . ($a - $offset))
	->getAlignment()
	->setWrapText(true);
		
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('J' . ($a - $offset) . ':Q' . ($a - $offset))
	->getAlignment()
	->setHorizontal('center');

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('J' . ($a - $offset) . ':Q' . ($a - $offset))
	->getFont()
	->setBold(true);

	$counter = 1;
	while($counter < $offset){
		
		$spreadsheet_param
		->getActiveSheet()
		->getStyle('H' . ($a - $offset + $counter) . ':Q' . ($a - $offset + $counter))
		->getFont()
		->setSize(13);

		$spreadsheet_param
		->getActiveSheet()
		->getStyle('H' . ($a - $offset + $counter) . ':Q' . ($a - $offset + $counter))
		->getAlignment()
		->setWrapText(true);
			
		$spreadsheet_param
		->getActiveSheet()
		->getStyle('J' . ($a - $offset + $counter) . ':Q' . ($a - $offset + $counter))
		->getAlignment()
		->setHorizontal('center');
			
		$spreadsheet_param
		->getActiveSheet()
		->getStyle('J' . ($a - $offset + $counter) . ':Q' . ($a - $offset + $counter))
		->getFont()
		->setBold(true);
		
		$counter = $counter + 1;
		
	}	
		
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('A' . ($a - $offset) . ':A' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('B' . ($a - $offset) . ':B' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('C' . ($a - $offset) . ':C' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('D' . ($a - $offset) . ':E' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('D' . ($a - $offset) . ':D' . ($a - 1))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_THIN);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('F' . ($a - $offset) . ':F' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('G' . ($a - $offset) . ':G' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('G' . ($a - $offset))
	->getBorders()
	->getBottom()
	->setBorderStyle(Border::BORDER_THIN);
	
	$g = 0;
	
	while($g < $offset){
		
		$spreadsheet_param
		->getActiveSheet()
		->getStyle('H' . ($a - $offset + $g) . ':Q' . ($a - $offset + $g))
		->getBorders()
		->getBottom()
		->setBorderStyle(Border::BORDER_THIN);
		
		$g = $g + 1;
	}
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('H' . ($a - $offset) . ':H' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('I' . ($a - $offset) . ':I' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('J' . ($a - $offset) . ':J' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('K' . ($a - $offset) . ':K' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('L' . ($a - $offset) . ':L' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('M' . ($a - $offset) . ':M' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('N' . ($a - $offset) . ':N' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('O' . ($a - $offset) . ':O' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('P' . ($a - $offset) . ':P' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);

	$spreadsheet_param
	->getActiveSheet()
	->getStyle('Q' . ($a - $offset) . ':Q' . ($a - 1))
	->getBorders()
	->getOutline()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
}

function dividirAno($spreadsheet_param,$a_param){
	
	$spreadsheet_param
    ->getActiveSheet()
    ->getStyle('A' . ($a_param) . ':Q' . ($a_param))
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('00abafff');
	
	$spreadsheet_param
	->getActiveSheet()
	->getRowDimension($a_param)
	->setRowHeight(10); 
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('A' . ($a_param) . ':Q' . ($a_param))
	->getBorders()
	->getLeft()
	->setBorderStyle(Border::BORDER_MEDIUM);
	
	$spreadsheet_param
	->getActiveSheet()
	->getStyle('A' . ($a_param) . ':Q' . ($a_param))
	->getBorders()
	->getRight()
	->setBorderStyle(Border::BORDER_MEDIUM);
}

function escreverJuncoes($sheet,$array_juncoes_1_sem,$a){
	
	$loop = 0;
	$counter_bottom = $a + 2;
	/*
	echo "CURSO: ";
	print_r($array_juncoes_1_sem);
	echo "\n <br>";
	*/
	while($loop < sizeof($array_juncoes_1_sem)){
		
		$letra_juncao = $array_juncoes_1_sem[$loop + 1];
		$nome_juncao = $array_juncoes_1_sem[$loop + 2];
		
		$sheet->setCellValue('B' . $counter_bottom,$letra_juncao . " " . $nome_juncao);
		
		$counter_bottom = $counter_bottom + 1;
		$loop += 3;
	}
	
}

function escreverJuncoes_2_sem($sheet,$array_juncoes_2_sem,$a){
	
	$loop = 0;
	$counter_bottom = $a + 2;
	/*
	echo "CURSO: ", " (2o SEM) ";
	print_r($array_juncoes_2_sem);
	echo "\n <br>";
	*/
	while($loop < sizeof($array_juncoes_2_sem)){
		
		$letra_juncao = $array_juncoes_2_sem[$loop + 1];
		$nome_juncao = $array_juncoes_2_sem[$loop + 2];
		
		$sheet->setCellValue('B' . $counter_bottom,$letra_juncao . " " . $nome_juncao);
		
		$counter_bottom = $counter_bottom + 1;
		$loop += 3;
	}
	
}
/*
function corrigirJuncoes($sheet, $a){

	$s = 10;
	$counter_bottom = $a + 2;
	
	while($s <= $a){
		$cell = $sheet->getCell('S' . $s);
		$cell_valor = $cell->getValue();
		if($cell_valor != ""){
			$num_linhas_extra = substr_count($cell_valor, "\n");
			if($num_linhas_extra > 0){
				$tmp = explode("\n",$cell_valor);
				$z = 0;
				while($z < count($tmp)){
				$sheet->setCellValue('B' . $counter_bottom + $z,$tmp[$z]);
				$z = $z + 1;
				}
				$sheet->setCellValue('S' . $s,"");
			}
			else{
				$sheet->setCellValue('B' . $counter_bottom,$cell_valor);
				$sheet->setCellValue('S' . $s,"");
			}
			
			$counter_bottom = $counter_bottom + 1 + $num_linhas_extra;
		}
		$s = $s + 1;
	}
	
}
*/
/*
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('H10', 'TESTE123');
*/
/*
$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet1, 'My Data');

// Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
$spreadsheet1->addSheet($myWorkSheet, 12);
*/