<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');

$id_area_utilizador = (int) $_SESSION["area_utilizador"];
$id_utc_utilizador = (int) $_SESSION["utc_utilizador"];

$id_docente_atribuido = $_GET["id_docente"];
$id_componente = $_GET["id_comp"];
$id_turma = $_GET["id_turma"];
$id_juncao = $_GET["id_juncao"];

$statement1 = mysqli_prepare($conn, "SELECT nome, id_area, id_utc FROM utilizador WHERE id_utilizador = $id_docente_atribuido;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$nome_docente_atribuido = $linha1["nome"];
	$area_docente_atribuido = $linha1["id_area"];
	$utc_docente_atribuido = $linha1["id_utc"];

$statement2 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $area_docente_atribuido;");
$statement2->execute();
$resultado2 = $statement2->get_result();
$linha2 = mysqli_fetch_assoc($resultado2);
	$nome_area_docente_atribuido = $linha2["nome"];

$statement3 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $utc_docente_atribuido;");
$statement3->execute();
$resultado3 = $statement3->get_result();
$linha3 = mysqli_fetch_assoc($resultado3);
	$nome_utc_docente_atribuido = $linha3["nome_utc"];

?>
<div class="modal-body_1"> 
	<div class="dsd_alterar_docente_select">
	<h6>Docente</h6>
	<select id="edDSD_alterar_docente" style="width:200px;">
		<?php
			echo "<option value='$id_docente_atribuido'>$nome_docente_atribuido</option>";
			echo "<option value='0'></option>";
			echo "<option value='0'>-------->$nome_area_docente_atribuido</option>";
							
			$statement4 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $id_docente_atribuido AND id_area = $area_docente_atribuido AND id_utc = $utc_docente_atribuido ORDER BY nome;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			while($linha4 = mysqli_fetch_assoc($resultado4)){
				$id_utilizador = $linha4["id_utilizador"];
				$nome = $linha4["nome"];
				echo "<option value='$id_utilizador'>$nome</option>";	
			}
				
			echo "<option value='0'></option>";
			echo "<option value='0'>-------->$nome_utc_docente_atribuido</option>";
				
			$statement5 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $id_docente_atribuido AND id_area != $area_docente_atribuido AND id_utc = $utc_docente_atribuido ORDER BY nome;");
			$statement5->execute();
			$resultado5 = $statement5->get_result();
			while($linha5 = mysqli_fetch_assoc($resultado5)){
				$id_utilizador = $linha5["id_utilizador"];
				$nome = $linha5["nome"];
				echo "<option value='$id_utilizador'>$nome</option>";	
			}
				
			echo "<option value='0'></option>";
			echo "<option value='0'>-------->Restantes</option>";
				
			$statement6 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $id_docente_atribuido AND id_area != $area_docente_atribuido AND id_utc != $utc_docente_atribuido ORDER BY nome;");
			$statement6->execute();
			$resultado6 = $statement6->get_result();
			while($linha6 = mysqli_fetch_assoc($resultado6)){
				$id_utilizador = $linha6["id_utilizador"];
				$nome = $linha6["nome"];
				echo "<option value='$id_utilizador'>$nome</option>";	
			}
			?>
	</select>
	</div>
		<?php
			$statement7 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
			$statement7->execute();
			$resultado7 = $statement7->get_result();
			$linha7 = mysqli_fetch_assoc($resultado7);
				$numJuncoes = $linha7["COUNT(id_juncao)"];
				
				if($numJuncoes > 0){ ?>
				<div class="atribuir_docente_ja_juncao" id="atribuir_docente_ja_juncao" style="visibility:visible; margin-top:10px;">
					<img src="http://localhost/apoio_utc/images/warning.jpg" width="20" height="20">
					<text style="font-size:13px; font-family:comic_sans;"><b>Esta turma está junta com outras turmas. Ao alterar o docente nesta turma vai afetar as outras.</b></text>
				</div>
		<?php	}
				else{ ?>
				<div id='div_juncoes' style="width:100%; margin-top:15px;">
					<h6>Junções</h6>
					<?php
						$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_turma != $id_turma;");
						$statement8->execute();
						$resultado8 = $statement8->get_result();
						$linha8 = mysqli_fetch_assoc($resultado8);
							$num_outras_turmas = $linha8["COUNT(DISTINCT id_turma)"];
							
							if($num_outras_turmas > 0){
								
								$turmas_contabilizadas = array();
								
								$statement9 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_turma != $id_turma;");
								$statement9->execute();
								$resultado9 = $statement9->get_result();
								while($linha9 = mysqli_fetch_assoc($resultado9)){
									$id_turma_temp = $linha9["id_turma"];
								
									if(!in_array($id_turma_temp,$turmas_contabilizadas)){
										
										$statement10 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma_temp;");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										$linha10 = mysqli_fetch_assoc($resultado10);
											$nome_turma_temp = $linha10["nome"];
										
										$statement11 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
										$statement11->execute();
										$resultado11 = $statement11->get_result();
										$linha11 = mysqli_fetch_assoc($resultado11);
											$turma_outra_esta_juncao = $linha11["COUNT(id_juncao)"];
										
											if($turma_outra_esta_juncao == 0){
												echo "<input type='checkbox' data_id-turma='$id_turma_temp' data_id-componente='$id_componente' style='margin-right:5px;'><b>", $nome_turma_temp, "</b><br>";
												array_push($turmas_contabilizadas,$id_turma_temp);
											}
											else{
												$statement12 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma_temp AND id_juncao IS NOT NULL;");
												$statement12->execute();
												$resultado12 = $statement12->get_result();
												$linha12 = mysqli_fetch_assoc($resultado12);
													$id_juncao_temp = $linha12["id_juncao"];
													
													echo "<input type='checkbox' data_id-juncao='$id_juncao_temp' onclick='bloquearOutrasJuncoes($id_juncao_temp)' style='margin-right:5px;'>";
													
													$statement13 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao_temp AND id_componente != $id_componente;");
													$statement13->execute();
													$resultado13 = $statement13->get_result();
													$linha13 = mysqli_fetch_assoc($resultado13);
														$num_turmas_para_alem_componente = $linha13["COUNT(DISTINCT id_turma)"];
														
														if($num_turmas_para_alem_componente == 0){
															$statement14 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_juncao = $id_juncao_temp;");
															$statement14->execute();
															$resultado14 = $statement14->get_result();
															while($linha14 = mysqli_fetch_assoc($resultado14)){
																$id_turma_temp_temp = $linha14["id_turma"];
																
																$statement15 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma_temp_temp;");
																$statement15->execute();
																$resultado15 = $statement15->get_result();
																$linha15 = mysqli_fetch_assoc($resultado15);
																	$nome_turma_temp_temp = $linha15["nome"];
																	
																	if(!in_array($id_turma_temp_temp,$turmas_contabilizadas)){
																		echo "<b>",$nome_turma_temp_temp," ";
																		array_push($turmas_contabilizadas,$id_turma_temp_temp);
																	}
															}
														}
														else{
															$statement16 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_juncao = $id_juncao_temp AND id_componente = $id_componente;");
															$statement16->execute();
															$resultado16 = $statement16->get_result();
															while($linha16 = mysqli_fetch_assoc($resultado16)){
																$id_turma_temp_temp = $linha16["id_turma"];
																
																$statement17 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma_temp_temp;");
																$statement17->execute();
																$resultado17 = $statement17->get_result();
																$linha17 = mysqli_fetch_assoc($resultado17);
																	$nome_turma_temp_temp = $linha17["nome"];
																	
																	if(!in_array($id_turma_temp_temp,$turmas_contabilizadas)){
																		echo "<b>",$nome_turma_temp_temp," ";
																		array_push($turmas_contabilizadas,$id_turma_temp_temp);
																	}
															}
															echo " ...";
														}
												echo "<br>";
											}
									}
								
								}
								
							}
					?>
					<br>
					<h6 onclick="gerarFormCriarJuncao(0,<?php echo $id_turma ?>,<?php echo $id_componente ?>)" style="margin-left:100px; cursor:pointer;">Outras turmas...</h6>
		<?php   }	?>
		
</div>
<div class="modal-footer">
    <button type="button" onclick="atribuirDocente(<?php echo $id_componente ?>, <?php echo $id_turma ?>,<?php echo $id_docente_atribuido ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
		Atribuir
    </button>
</div>