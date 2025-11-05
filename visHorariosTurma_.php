<?php
// Página de visualização de distribuição de serviço ordenada por docente (DSD)

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}

if(!isset($_GET["id_turma"])){
	header("Location: visHorariosTurma.php");
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$id_utilizador_atual = $_SESSION['id'];

$statement = mysqli_prepare($conn, "SELECT id_utc, is_admin FROM utilizador WHERE id_utilizador = $id_utilizador_atual;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc_utilizador_atual = $linha["id_utc"];
	$is_admin = $linha["is_admin"];


$id_turma = $_GET["id_turma"];
$semestre = $_GET["sem"];

$statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_turma = $id_turma;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_turma = $linha["nome"];
	$ano_turma = $linha["ano"];
	$semestre_turma = $linha["semestre"];
	$id_curso_turma = $linha["id_curso"];
	
	$numero_turma = substr($nome_turma,strlen($nome_turma) - 1,1);
	
$statement = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso_turma;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc_turma = $linha["id_utc"];
	
if(($id_utc_utilizador_atual != $id_utc_turma) && ($is_admin == 0)){
	header("Location: visHorariosTurma.php?sem=$semestre");
}
	
$statement1 = mysqli_prepare($conn, "SELECT sigla_completa FROM curso WHERE id_curso = $id_curso_turma;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$sigla_curso = $linha1["sigla_completa"];	

$offset_seg = 0;
$offset_ter = 0;
$offset_qua = 0;
$offset_qui = 0;
$offset_sex = 0;

$offset_vertical_seg = 0;
$offset_vertical_ter = 0;
$offset_vertical_qua = 0;
$offset_vertical_qui = 0;
$offset_vertical_sex = 0;

$dias_semana = array('SEG','TER','QUA','QUI','SEX');
$offsets_semana = array($offset_seg,$offset_ter,$offset_qua,$offset_qui,$offset_sex);
$offsets_verticais = array($offset_vertical_seg,$offset_vertical_ter,$offset_vertical_qua,$offset_vertical_qui,$offset_vertical_sex);

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-left:15px; margin-top:10px;">...</a> / <a href="visHorariosTurma.php?sem=<?php echo $semestre; ?>"> Horários - Turmas</a> / <a href="">Horários - <b><?php echo $nome_turma ?></b></a></h6>
			<h3 align="center" style="margin-left:15px; margin-top:20px; margin-bottom: 30px;"><?php echo $sigla_curso; ?> - <?php echo " (", $ano_turma, "ºA/", $semestre_turma, "ºS)"; ?> : <b>Turma <?php echo $numero_turma; ?></b></h3>
			
			<div class="tabela_horario_container" align="center">
				<div class="tabela_horario_container_dias_semana">
					<div class="tabela_horario_dia_semana">
					</div>
					<div class="tabela_horario_dia_semana">
					<b>SEG</b>
					</div>
					<div class="tabela_horario_dia_semana">
					<b>TER</b>
					</div>
					<div class="tabela_horario_dia_semana">
					<b>QUA</b>
					</div>
					<div class="tabela_horario_dia_semana">
					<b>QUI</b>
					</div>
					<div class="tabela_horario_dia_semana" style="width:165px;">
					<b>SEX</b>
					</div>
				</div>
				<div class="tabela_horario_container_horas">
					<div class="tabela_horario_hora">
						<b>08:30 - 09:30</b>
					</div>
					<div class="tabela_horario_hora">
						<b>09:30 - 10:30</b>
					</div>
					<div class="tabela_horario_hora">
						<b>10:30 - 11:30</b>
					</div>
					<div class="tabela_horario_hora">
						<b>11:30 - 12:30</b>
					</div>
					<div class="tabela_horario_hora">
						<b>12:30 - 13:30</b>
					</div>
					<div class="tabela_horario_hora">
						<b>13:30 - 14:30</b>
					</div>
					<div class="tabela_horario_hora">
						<b>14:30 - 15:30</b>
					</div>
					<div class="tabela_horario_hora">
						<b>15:30 - 16:30</b>
					</div>
					<div class="tabela_horario_hora">
						<b>16:30 - 17:30</b>
					</div>
					<div class="tabela_horario_hora">
						<b>17:30 - 18:30</b>
					</div>
					<div class="tabela_horario_hora" style="border-bottom:0px;">
						<b>18:30 - 19:30</b>
					</div>
				</div>
				<?php
					$loop_dias_semana = 0;
					while($loop_dias_semana < sizeof($dias_semana)){
						$dia_semana = $dias_semana[$loop_dias_semana];
						$offset_semana = $offsets_semana[$loop_dias_semana];
						$offset_vertical = $offsets_verticais[$loop_dias_semana];
						
						?>
						<?php
						if($loop_dias_semana == 4){
							echo "<div class='tabela_horario_container_dia_semana' style='width:164px; border-right:0px;'>";
						}
						else{
							echo "<div class='tabela_horario_container_dia_semana'>";
						}
						//echo "CONTAINER_", $dia_semana;
						
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '08:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '09:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '10:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '11:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														
													}echo "<div class='tabela_horario_divisao_conteudo'>";
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '12:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '13:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '14:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";														
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '15:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
				<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '16:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '17:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
													
													$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
													$statement22->execute();
													$resultado22 = $statement22->get_result();
													$linha22 = mysqli_fetch_assoc($resultado22);
														$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
					?>
					<?php
					
						$statement1 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00';");
						$statement1->execute();
						$resultado1 = $statement1->get_result();
						$linha1 = mysqli_fetch_assoc($resultado1);
							$tem_aula = $linha1["COUNT(a.id_horario)"];
							
							if($tem_aula > 0){
								$statement2 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '18:30:00';");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								$linha2 = mysqli_fetch_assoc($resultado2);
									$id_horario = $linha2["id_horario"];
									
								$statement3 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$numero_horas_componente = $linha3["numero_horas"];
									$id_tipocomponente = $linha3["id_tipocomponente"];
									$id_disciplina = $linha3["id_disciplina"];
									
									if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
										$offset_semana = $numero_horas_componente - 1.5;
									}
									else{
										$offset_semana = $numero_horas_componente - 1;
									}	
									
									$statement4 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
									$statement4->execute();
									$resultado4 = $statement4->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
										$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										
									$statement5 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									$linha5 = mysqli_fetch_assoc($resultado5);
										$abreviacao_uc = $linha5["abreviacao_uc"];
										$id_curso = $linha5["id_curso"];
										$ano = $linha5["ano"];
										
										$nome_docente = "--------";
										
									$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$tem_docente = $linha6["COUNT(id_docente)"];
										
										if($tem_docente > 0){
											$statement7 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
											$statement7->execute();
											$resultado7 = $statement7->get_result();
											$linha7 = mysqli_fetch_assoc($resultado7);
												$id_docente = $linha7["id_docente"];
												
											$statement8 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											$linha8 = mysqli_fetch_assoc($resultado8);
												$nome_docente = $linha8["nome"];
												
												$nome_docente_temp = explode(" ",$nome_docente);
												if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
													
													$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
													$nome_docente = $nome_temp;
												}
										}
									
									$statement9 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$id_sala = $linha9["id_sala"];
										
									$statement10 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
									$statement10->execute();
									$resultado10 = $statement10->get_result();
									$linha10 = mysqli_fetch_assoc($resultado10);
										$nome_sala = $linha10["nome_sala"];
										
									$statement11 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
									$statement11->execute();
									$resultado11 = $statement11->get_result();
									$linha11 = mysqli_fetch_assoc($resultado11);
										$sigla_curso = $linha11["sigla"];
								
								if($loop_dias_semana == 4){
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; width:164px; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000; border-bottom:0px;''>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}	
								else{
									if($offset_vertical == 0){
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_1_5_horas' style='border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_2_5_horas' style='border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_3_5_horas' style='border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											$offset_vertical = 1;
											echo "<div class='tabela_horario_divisao_4_5_horas' style='border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
									else{
										if($numero_horas_componente == 1){
											echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 1.5){
											echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2){
											echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 2.5){
											echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3){
											echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 3.5){
											echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4){
											echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 4.5){
											echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
										else if($numero_horas_componente == 5){
											echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000; border-bottom:0px;'>";
											echo "<div class='tabela_horario_divisao_conteudo'>";
										}
									}
								}
									
								if($numero_horas_componente < 2){
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
									echo "</div></div>";
								}
								else{
									echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
									echo "</div></div>";
								}
							}
							else{
								$statement11 = mysqli_prepare($conn, "SELECT COUNT(a.id_horario) FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00';");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$tem_aula_pontual = $linha11["COUNT(a.id_horario)"];

									if($tem_aula_pontual == 0){
										if($offset_semana == 0){
											if($offset_vertical == 0){
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; border-bottom:0px;'></div>";
												}
												else{
													echo "<div class='tabela_horario_divisao' style='border-bottom:0px;'></div>";
												}
											}
											else{
												if($loop_dias_semana == 4){
													echo "<div class='tabela_horario_divisao' style='width:164px; height:23px; border-bottom:0px;'></div>";
													$offset_vertical = 0;
												}
												else{
													echo "<div class='tabela_horario_divisao' style='height:23px; border-bottom:0px;'></div>";
													$offset_vertical = 0;
												}
											}
										}
										else{
											$offset_semana -= 1;
										}
									}
									else{
										$statement12 = mysqli_prepare($conn, "SELECT a.id_horario FROM aula a INNER JOIN horario h ON a.id_horario = h.id_horario WHERE a.id_turma = $id_turma AND h.dia_semana = '$dia_semana' AND h.hora_inicio = '19:00:00';");
										$statement12->execute();
										$resultado12 = $statement12->get_result();
										$linha12 = mysqli_fetch_assoc($resultado12);
											$id_horario = $linha12["id_horario"];
											
											$statement13 = mysqli_prepare($conn, "SELECT * FROM componente c INNER JOIN aula a ON c.id_componente = a.id_componente WHERE a.id_horario = $id_horario;");
											$statement13->execute();
											$resultado13 = $statement13->get_result();
											$linha13 = mysqli_fetch_assoc($resultado13);
												$numero_horas_componente = $linha13["numero_horas"];
												$id_tipocomponente = $linha13["id_tipocomponente"];
												$id_disciplina = $linha13["id_disciplina"];
												
												if($offset_vertical > 0){
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 0.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												else{
													if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
														$offset_semana = $numero_horas_componente - 1.5;
													}
													else{
														$offset_semana = $numero_horas_componente - 1;
													}	
												}
												
												$statement14 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
												$statement14->execute();
												$resultado14 = $statement14->get_result();
												$linha14 = mysqli_fetch_assoc($resultado14);
													$sigla_tipocomponente = $linha14["sigla_tipocomponente"];
													
												$statement15 = mysqli_prepare($conn, "SELECT abreviacao_uc, id_curso, ano FROM disciplina WHERE id_disciplina = $id_disciplina;");
												$statement15->execute();
												$resultado15 = $statement15->get_result();
												$linha15 = mysqli_fetch_assoc($resultado15);
													$abreviacao_uc = $linha15["abreviacao_uc"];
													$id_curso = $linha15["id_curso"];
													$ano = $linha15["ano"];
													
													$nome_docente = "--------";
													
												$statement16 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_horario = $id_horario;");
												$statement16->execute();
												$resultado16 = $statement16->get_result();
												$linha16 = mysqli_fetch_assoc($resultado16);
													$tem_docente = $linha16["COUNT(id_docente)"];
													
													if($tem_docente > 0){
														$statement17 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_horario = $id_horario;");
														$statement17->execute();
														$resultado17 = $statement17->get_result();
														$linha17 = mysqli_fetch_assoc($resultado17);
															$id_docente = $linha17["id_docente"];
															
														$statement18 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
														$statement18->execute();
														$resultado18 = $statement18->get_result();
														$linha18 = mysqli_fetch_assoc($resultado18);
															$nome_docente = $linha18["nome"];
															
															$nome_docente_temp = explode(" ",$nome_docente);
															if((strlen($nome_docente) > 15) || (sizeof($nome_docente_temp) > 2)){
																
																$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
																$nome_docente = $nome_temp;
															}
													}
												
												$statement19 = mysqli_prepare($conn, "SELECT id_sala FROM horario WHERE id_horario = $id_horario;");
												$statement19->execute();
												$resultado19 = $statement19->get_result();
												$linha19 = mysqli_fetch_assoc($resultado19);
													$id_sala = $linha19["id_sala"];
													
												$statement21 = mysqli_prepare($conn, "SELECT nome_sala FROM sala WHERE id_sala = $id_sala;");
												$statement21->execute();
												$resultado21 = $statement21->get_result();
												$linha21 = mysqli_fetch_assoc($resultado21);
													$nome_sala = $linha21["nome_sala"];
												
												$statement22 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
												$statement22->execute();
												$resultado22 = $statement22->get_result();
												$linha22 = mysqli_fetch_assoc($resultado22);
													$sigla_curso = $linha22["sigla"];
												
											if($loop_dias_semana == 4){
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='width:164px; margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
											else{
												if($offset_vertical > 0){
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
												else{
													if($numero_horas_componente == 1){
														echo "<div class='tabela_horario_divisao_1_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 1.5){
														echo "<div class='tabela_horario_divisao_1_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2){
														echo "<div class='tabela_horario_divisao_2_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 2.5){
														echo "<div class='tabela_horario_divisao_2_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3){
														echo "<div class='tabela_horario_divisao_3_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 3.5){
														echo "<div class='tabela_horario_divisao_3_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4){
														echo "<div class='tabela_horario_divisao_4_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 4.5){
														echo "<div class='tabela_horario_divisao_4_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
													else if($numero_horas_componente == 5){
														echo "<div class='tabela_horario_divisao_5_horas' style='margin-top:23px; border-top:1px solid #000000;'>";
														echo "<div class='tabela_horario_divisao_conteudo'>";
													}
												}
											}
												
											if($numero_horas_componente == 1.5 || $numero_horas_componente == 2.5 || $numero_horas_componente == 3.5 || $numero_horas_componente == 4.5){
												$offset_vertical = 0;
											}
											else{
												$offset_vertical = 1;	
											}	
												
											if($numero_horas_componente < 2){
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, " / ", $sigla_tipocomponente, "<br>", $nome_docente, " / ", $nome_sala, "</text>";
												echo "</div></div>";
											}
											else{
												echo "<text style='font-weight:500;'>", $sigla_curso, $ano, "_", $abreviacao_uc, "<br>", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_sala, "</text>";
												echo "</div></div>";
											}
										}
							}
							echo "</div>";
						$loop_dias_semana += 1;
					}
					?>
			</div>
			
		</div>    
	</div>
</div>

</main>
<script language="javascript">
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_HORARIOS");
	var li_DSD_especifico = document.getElementById("li_HORARIOS_TURMAS");
	
	li_DSD.style.background = "#4a6f96";
	li_DSD_especifico.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function carregarHorarios(){
	
	const id_sala = <?php echo $id_sala; ?>
	
	$.ajax ({
		type: "POST",
		url: "processamento/horarios_sala/carregarHorarios.php", 
		data: {id_sala: id_sala},
		success: function(result) {
			alert
		}
	});
	
}

function teste(){
	alert("TESTE");
}

</script>
<?php gerarHome2() ?>
