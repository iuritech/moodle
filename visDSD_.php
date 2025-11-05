<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$idUtilizadorAtual = (int) $_SESSION["id"];
$idAreaUtilizadorAtual = (int) $_SESSION['area_utilizador'];

$permAdmin = false;
if(isset($_SESSION['permAdmin'])){
    $permAdmin = true;
}

/*--------------------------------------------------------------------------------------------------*/

$idDocente = (int) filter_input(INPUT_GET, 'id');

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $idDocente;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$nomeDocente = $linha["nome"];
$imgDocente = $linha["imagem_perfil"];
$idUtcDocente = $linha["id_utc"];
$idAreaDocente = $linha["id_area"];	

$statement001 = mysqli_prepare($conn, "SELECT dsd_1_sem, dsd_2_sem FROM utc WHERE id_utc = $idUtcDocente;");
$statement001->execute();
$resultado001 = $statement001->get_result();
$linha001 = mysqli_fetch_assoc($resultado001);
	$dsd_1_sem = $linha001["dsd_1_sem"];
	$dsd_2_sem = $linha001["dsd_2_sem"];

/*--------------------------------------------------------------------------------------------------*/

$statement0 = mysqli_prepare($conn, "SELECT sigla_utc, id_responsavel FROM utc WHERE id_utc = $idUtcDocente");
$statement0->execute();
$resultado0 = $statement0->get_result();
$linha0 = mysqli_fetch_assoc($resultado0);
$siglaUtcDocente = $linha0["sigla_utc"];
$idResponsavelUtc = $linha0["id_responsavel"];
		
/*--------------------------------------------------------------------------------------------------*/
		
$statement1 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $idAreaDocente");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
$nomeAreaDocente = $linha1["nome"];
				
$statement2 = mysqli_prepare($conn, "SELECT f.nome, COUNT(f.nome) FROM 
									funcao f INNER JOIN utilizador u ON f.id_funcao = u.id_funcao
									WHERE u.id_utilizador = $idDocente");
$statement2->execute();
$resultado2 = $statement2->get_result();
$linha2 = mysqli_fetch_assoc($resultado2);
$funcao = $linha2["nome"];
				
/*---------------------------------------------1º SEMESTRE-----------------------------------------------------*/

$array_componentes_1_sem = array();
$array_disciplinas_1_sem = array();

$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $idDocente;");
$statement3->execute();
$resultado3 = $statement3->get_result();
while($linha3 = mysqli_fetch_assoc($resultado3)){
	$id_componente = $linha3["id_componente"];
	
	$statement4 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
	$statement4->execute();
	$resultado4 = $statement4->get_result();
	$linha4 = mysqli_fetch_assoc($resultado4);
		$id_disciplina = $linha4["id_disciplina"];
		
		$statement5 = mysqli_prepare($conn, "SELECT semestre FROM disciplina WHERE id_disciplina = $id_disciplina;");
		$statement5->execute();
		$resultado5 = $statement5->get_result();
		$linha5 = mysqli_fetch_assoc($resultado5);
			$sem_disciplina = $linha5["semestre"];
		
			if($sem_disciplina == 1){
				array_push($array_componentes_1_sem,$id_componente);
				array_push($array_disciplinas_1_sem,$id_disciplina);
			}
	
}

$array_componentes_1_sem_temp = array_unique($array_componentes_1_sem);
$array_componentes_1_sem_final = implode("','",$array_componentes_1_sem_temp);

$array_disciplinas_1_sem_temp = array_unique($array_disciplinas_1_sem);
$array_disciplinas_1_sem_final = implode("','",$array_disciplinas_1_sem_temp);

$statement6 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE 
									id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idDocente;");
$statement6->execute();
$resultado6 = $statement6->get_result();
$linha6 = mysqli_fetch_assoc($resultado6);
	$num_turmas_docente_1_sem = $linha6["COUNT(DISTINCT id_turma)"];

	$horas_1_sem = 0;

	$statement7 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE 
									id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idDocente;");
	$statement7->execute();
	$resultado7 = $statement7->get_result();
	$linha7 = mysqli_fetch_assoc($resultado7);
		$num_juncoes_1_sem = $linha7["COUNT(DISTINCT id_juncao)"];
	
		if($num_juncoes_1_sem == 0){
			
			$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
									id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idDocente;");
			$statement8->execute();
			$resultado8 = $statement8->get_result();
			while($linha8 = mysqli_fetch_assoc($resultado8)){
				$id_comp = $linha8["id_componente"];
				
				$statement9 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
				$statement9->execute();
				$resultado9 = $statement9->get_result();
				$linha9 = mysqli_fetch_assoc($resultado9);
					$numero_horas_comp = $linha9["numero_horas"];
					
					$statement009 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente;");
					$statement009->execute();
					$resultado009 = $statement009->get_result();
					$linha009 = mysqli_fetch_assoc($resultado009);
						$numero_turmas = $linha009["COUNT(DISTINCT id_turma)"];
									
						$horas_1_sem = $horas_1_sem + ($numero_horas_comp * $numero_turmas);
					
			}
			
		}

		else{
			
			$juncoes_ja_contabilizadas = array();
			
			$statement10 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
									id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idDocente;");
			$statement10->execute();
			$resultado10 = $statement10->get_result();
			while($linha10 = mysqli_fetch_assoc($resultado10)){
				$id_comp = $linha10["id_componente"];
			
				$statement11 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
				$statement11->execute();
				$resultado11 = $statement11->get_result();
				$linha11 = mysqli_fetch_assoc($resultado11);
					$numero_horas = $linha11["numero_horas"];
			
				$statement12 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente AND id_juncao IS NULL;");
				$statement12->execute();
				$resultado12 = $statement12->get_result();
				$linha12 = mysqli_fetch_assoc($resultado12);
					$numero_turmas_sem_juncao = $linha12["COUNT(DISTINCT id_turma)"];
				
					if($numero_turmas_sem_juncao == 0){	
						$statement13 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente;");
						$statement13->execute();
						$resultado13 = $statement13->get_result();
						$linha13 = mysqli_fetch_assoc($resultado13);
							$numero_juncoes_comp = $linha13["COUNT(DISTINCT id_juncao)"];
							
							$statement14 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente;");
							$statement14->execute();
							$resultado14 = $statement14->get_result();
							while($linha14 = mysqli_fetch_assoc($resultado14)){
								$id_juncao = $linha14["id_juncao"];
								
								if(!in_array($id_juncao,$juncoes_ja_contabilizadas)){
									$horas_1_sem = $horas_1_sem + $numero_horas;
									array_push($juncoes_ja_contabilizadas,$id_juncao);
								}
							}
					}
					else{
						$statement14 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
						$statement14->execute();
						$resultado14 = $statement14->get_result();
						$linha14 = mysqli_fetch_assoc($resultado14);
							$numero_juncoes_comp = $linha14["COUNT(DISTINCT id_juncao)"];
							
							$statement15 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
							$statement15->execute();
							$resultado15 = $statement15->get_result();
							while($linha15 = mysqli_fetch_assoc($resultado15)){
								$id_juncao = $linha15["id_juncao"];
								
								if(!in_array($id_juncao,$juncoes_ja_contabilizadas)){
									$horas_1_sem = $horas_1_sem + $numero_horas;
									array_push($juncoes_ja_contabilizadas,$id_juncao);
								}
							}
							
							$horas_1_sem = $horas_1_sem + ($numero_turmas_sem_juncao * $numero_horas);
					}
			}
			
		}
		
/*---------------------------------------------2º SEMESTRE-----------------------------------------------------*/
$array_componentes_2_sem = array();
$array_disciplinas_2_sem = array();

$statement30 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $idDocente;");
$statement30->execute();
$resultado30 = $statement30->get_result();
while($linha30 = mysqli_fetch_assoc($resultado30)){
	$id_componente = $linha30["id_componente"];
	
	$statement31 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_componente;");
	$statement31->execute();
	$resultado31 = $statement31->get_result();
	while($linha31 = mysqli_fetch_assoc($resultado31)){
		$id_disciplina = $linha31["id_disciplina"];
		
		$statement32 = mysqli_prepare($conn, "SELECT semestre FROM disciplina WHERE id_disciplina = $id_disciplina;");
		$statement32->execute();
		$resultado32 = $statement32->get_result();
		$linha32 = mysqli_fetch_assoc($resultado32);
			$sem_disciplina = $linha32["semestre"];
		
			if($sem_disciplina == 2){
				array_push($array_componentes_2_sem,$id_componente);
				array_push($array_disciplinas_2_sem,$id_disciplina);
			}
	}
	
}

$array_componentes_2_sem_temp = array_unique($array_componentes_2_sem);
$array_componentes_2_sem_final = implode("','",$array_componentes_2_sem_temp);

$array_disciplinas_2_sem_temp = array_unique($array_disciplinas_2_sem);
$array_disciplinas_2_sem_final = implode("','",$array_disciplinas_2_sem_temp);

$statement33 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE 
									id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idDocente;");
$statement33->execute();
$resultado33 = $statement33->get_result();
$linha33 = mysqli_fetch_assoc($resultado33);
	$num_turmas_docente_2_sem = $linha33["COUNT(DISTINCT id_turma)"];

	$horas_2_sem = 0;

	$statement34 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE 
									id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idDocente;");
	$statement34->execute();
	$resultado34 = $statement34->get_result();
	$linha34 = mysqli_fetch_assoc($resultado34);
		$num_juncoes_2_sem = $linha34["COUNT(DISTINCT id_juncao)"];
	
		if($num_juncoes_2_sem == 0){
			
			$statement35 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
									id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idDocente;");
			$statement35->execute();
			$resultado35 = $statement35->get_result();
			while($linha35 = mysqli_fetch_assoc($resultado35)){
				$id_comp = $linha35["id_componente"];
				
				$statement36 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
				$statement36->execute();
				$resultado36 = $statement36->get_result();
				$linha36 = mysqli_fetch_assoc($resultado36);
					$numero_horas_comp = $linha36["numero_horas"];
					
					$statement365 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente;");
					$statement365->execute();
					$resultado365 = $statement365->get_result();
					$linha365 = mysqli_fetch_assoc($resultado365);
						$numero_turmas = $linha365["COUNT(DISTINCT id_turma)"];
									
						$horas_2_sem = $horas_2_sem + ($numero_horas_comp * $numero_turmas);
					
			}
			
		}

		else{
			
			$juncoes_ja_contabilizadas_2_sem = array();
			
			$statement37 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
									id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idDocente;");
			$statement37->execute();
			$resultado37 = $statement37->get_result();
			while($linha37 = mysqli_fetch_assoc($resultado37)){
				$id_comp = $linha37["id_componente"];
			
				$statement38 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
				$statement38->execute();
				$resultado38 = $statement38->get_result();
				$linha38 = mysqli_fetch_assoc($resultado38);
					$numero_horas = $linha38["numero_horas"];
			
				$statement39 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente AND id_juncao IS NULL;");
				$statement39->execute();
				$resultado39 = $statement39->get_result();
				$linha39 = mysqli_fetch_assoc($resultado39);
					$numero_turmas_sem_juncao = $linha39["COUNT(DISTINCT id_turma)"];
				
					if($numero_turmas_sem_juncao == 0){	
						$statement40 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente;");
						$statement40->execute();
						$resultado40 = $statement40->get_result();
						$linha40 = mysqli_fetch_assoc($resultado40);
							$numero_juncoes_comp = $linha40["COUNT(DISTINCT id_juncao)"];
							
							$statement41 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente;");
							$statement41->execute();
							$resultado41 = $statement41->get_result();
							while($linha41 = mysqli_fetch_assoc($resultado41)){
								$id_juncao = $linha41["id_juncao"];
								
								if(!in_array($id_juncao,$juncoes_ja_contabilizadas_2_sem)){
									$horas_2_sem = $horas_2_sem + $numero_horas;
									array_push($juncoes_ja_contabilizadas_2_sem,$id_juncao);
								}
							}
					}
					else{
						$statement42 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
						$statement42->execute();
						$resultado42 = $statement42->get_result();
						$linha42 = mysqli_fetch_assoc($resultado42);
							$numero_juncoes_comp = $linha42["COUNT(DISTINCT id_juncao)"];
							
							$statement43 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
							$statement43->execute();
							$resultado43 = $statement43->get_result();
							while($linha43 = mysqli_fetch_assoc($resultado43)){
								$id_juncao = $linha43["id_juncao"];
								
								if(!in_array($id_juncao,$juncoes_ja_contabilizadas_2_sem)){
									$horas_2_sem = $horas_2_sem + $numero_horas;
									array_push($juncoes_ja_contabilizadas_2_sem,$id_juncao);
								}
							}
							
							$horas_2_sem = $horas_2_sem + ($numero_turmas_sem_juncao * $numero_horas);
					}
			}
			
		}

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card mb-4">
		<div class="card-body" style="background: url(images/fundo_perfil.jpg);">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-left:15px; margin-top:10px;">...</a> / <a href="visDSD.php"> DSD (Docentes)</a> / <a href=""><?php echo $nomeDocente ?></a></h6>
			<br>
			<img src="<?php echo $imgDocente ?>" style="width:60px; heigh:60px; border-radius:50%; border:2px solid #212529;"><h3 style="position:absolute; left:85px; top:93px;"><b> <?php echo $nomeDocente ?> </b></h3>
			<span style="position:absolute; left:315px; top:55px;"><h6><?php
				echo $funcao, "<br>";
			?></h6></span>
			<span title='UTC' style="position:absolute; left:450px; top:55px;"><h6><i class="material-icons" style="vertical-align:middle; margin-right:5px;">menu_book</i><b>UTC:  </b><?php echo $siglaUtcDocente ?></h6></span>
			<span title='Área' style="position:absolute; left:450px; top:85px;"><h6><i class="material-icons" style="vertical-align:middle; margin-right:5px;">monitor</i><b>Área: </b><?php echo $nomeAreaDocente ?></h6></span>
			
			<span title='1º Semestre' style="position:absolute; left:680px; top:25px;"><h6><b>1º Semestre</b></h6></span>
			<span title='Nº Disciplinas' style="position:absolute; left:615px; top:55px;"><h6><i class="material-icons" style="vertical-align:middle; margin-right:3px;">class</i><b>UC's: </b><?php echo sizeof($array_disciplinas_1_sem_temp) ?></h6></span>
			<span title='Nº Turmas' style="position:absolute; left:615px; top:85px;"><h6><i class="material-icons" style="vertical-align:middle; margin-right:3px;">people</i><b>Turmas: </b><?php echo $num_turmas_docente_1_sem ?></h6></span>
			<span title='Carga Horária (semanal)' style="position:absolute; left:615px; top:115px;"><h6><i class="material-icons" style="vertical-align:middle; margin-right:3px;">schedule</i><b>Carga horária: </b><?php echo $horas_1_sem, "H" ?></h6></span>
			
			<span title='2º Semestre' style="position:absolute; left:950px; top:30px;"><h6><b>2º Semestre</b></h6></span>
			<span title='Nº Disciplinas' style="position:absolute; left:885px; top:55px;"><h6><i class="material-icons" style="vertical-align:middle; margin-right:3px;">class</i><b>UC's: </b><?php echo sizeof($array_disciplinas_2_sem_temp) ?></h6></span>
			<span title='Nº Turmas' style="position:absolute; left:885px; top:85px;"><h6><i class="material-icons" style="vertical-align:middle; margin-right:3px;">people</i><b>Turmas: </b><?php echo $num_turmas_docente_2_sem ?></h6></span>
			<span title='Carga Horária (semanal)' style="position:absolute; left:885px; top:115px;"><h6><i class="material-icons" style="vertical-align:middle; margin-right:3px;">schedule</i><b>Carga horária: </b><?php echo $horas_2_sem, "H" ?></h6></span>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="card mb-4">
		<div class="card-body">
			<h4 style="margin-left:5px; margin-bottom:15px;">Disciplinas</h4>
			<?php if(($idAreaUtilizadorAtual == $idAreaDocente) ||/* $permAdmin ||*/ ($idUtilizadorAtual == $idResponsavelUtc)) { 
					if($dsd_1_sem == 1 && $dsd_2_sem == 1){?>
						<a class="btn btn-danger" title="A DSD está bloqueada em ambos os semestres" onclick="semestresBloqueados()" href="javascript:void(0)" style='width:101px; border-radius:25px; position:absolute; right:25px; top:25px;'><span class="material-icons" style="vertical-align:middle;">lock</span>Editar</a>
			<?php 		}
					else{ ?>
						<a class="btn btn-primary" href="edDSD_.php?id=<?php echo $idDocente ?>" style='width:101px; border-radius:25px; position:absolute; right:25px; top:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>
	<?php			}	
				}?>
			<div id="1_sem" style="width:1250px; display:inline-block;">
			<h6 style="margin-left:15px;">1º Sem</h6>
			<?php $statement4 = mysqli_prepare($conn, "SELECT DISTINCT d.id_disciplina, d.nome_uc, d.abreviacao_uc, d.codigo_uc, d.id_curso FROM disciplina d 
												INNER JOIN componente c ON d.id_disciplina = c.id_disciplina 
												INNER JOIN aula a ON c.id_componente = a.id_componente 
												WHERE a.id_componente IN
												(SELECT DISTINCT id_componente FROM aula a WHERE id_docente = $idDocente) AND d.semestre = 1;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			while($linha4 = mysqli_fetch_assoc($resultado4)){
				$idDisciplina = $linha4["id_disciplina"];
				$nomeDisciplina = $linha4["nome_uc"];
				$siglaUC = $linha4["abreviacao_uc"];
				$codigoUC = $linha4["codigo_uc"]; 
				$idCurso = $linha4["id_curso"];
				
				$array_alturas = array();
				
				$statement200 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $idDisciplina;");
				$statement200->execute();
				$resultado200 = $statement200->get_result();
				while($linha200 = mysqli_fetch_assoc($resultado200)){
					$id_comp_comp = $linha200["id_componente"];
					
					$statement201 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente;");
					$statement201->execute();
					$resultado201 = $statement201->get_result();
					$linha201 = mysqli_fetch_assoc($resultado201);
						$comp_tem_este_docente= $linha201["COUNT(id_componente)"];
					
						if($comp_tem_este_docente > 0){
							
							$altura_comp = 40;
						
							$statement202 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente;");
							$statement202->execute();
							$resultado202 = $statement202->get_result();
							$linha202 = mysqli_fetch_assoc($resultado202);
								$num_juncoes = $linha202["COUNT(id_juncao)"];
						
								if($num_juncoes == 0){
									//Todas separadas
									$statement203 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente;");
									$statement203->execute();
									$resultado203 = $statement203->get_result();
									$linha203 = mysqli_fetch_assoc($resultado203);
										$num_turmas_individuais = $linha203["COUNT(DISTINCT id_turma)"];
										
										$altura_comp += $num_turmas_individuais * 45;
										//echo $altura_comp;
								}
								else{
									$statement204 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente AND id_juncao IS NULL;");
									$statement204->execute();
									$resultado204 = $statement204->get_result();
									$linha204 = mysqli_fetch_assoc($resultado204);
										$num_turmas_isoladas = $linha204["COUNT(DISTINCT id_turma)"];
									
										$statement2004 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
										$statement2004->execute();
										$resultado2004 = $statement2004->get_result();
										$linha2004 = mysqli_fetch_assoc($resultado2004);
											$num_juncoes_total = $linha2004["COUNT(DISTINCT id_juncao)"];
									
										$counter_1 = 0;
									
										$statement205 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
										$statement205->execute();
										$resultado205 = $statement205->get_result();
										while($linha205 = mysqli_fetch_assoc($resultado205)){
											$id_juncao = $linha205["id_juncao"];
											$statement206 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente AND id_juncao = $id_juncao;");
											$statement206->execute();
											$resultado206 = $statement206->get_result();
											$linha206 = mysqli_fetch_assoc($resultado206);
												$num_turmas_juncao = $linha206["COUNT(DISTINCT id_turma)"];
													
												$altura_comp += $num_turmas_juncao * 33;
												
												$counter_1 += 1;
												if($counter_1 != $num_juncoes_total){
													$altura_comp += 20;
												}
										}
										
										if($num_turmas_isoladas != 0){
											
											$altura_comp += $num_turmas_isoladas * 45;
											
										}
								}
								
								array_push($array_alturas,$altura_comp);
						}
				}
				
				$altura_final = max($array_alturas);
				
				if($altura_final < 185){
					$altura_final = 185;
				}
				echo "<div style='width:1250px; height:", $altura_final, "px;'>";
				
				$statement45 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $idCurso;");
				$statement45->execute();
				$resultado45 = $statement45->get_result();
				$linha45 = mysqli_fetch_assoc($resultado45);
					$siglaCurso = $linha45["sigla"];
				?>
				<div class="card_DSD" id="card_DSD" onclick="">
					<div class="container_card_DSD">
						<div class="container_card_DSD_disciplina">
							<b><?php echo $nomeDisciplina, " (", $siglaCurso, ")<br>(", $siglaUC, ") (", $codigoUC, ")"; ?></b>
						</div> 
						
					</div>
				</div>
				<?php
					$statement46 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_componente) FROM aula a 
														INNER JOIN componente c ON a.id_componente = c.id_componente 
														WHERE c.id_disciplina = $idDisciplina AND a.id_docente = $idDocente;");
					$statement46->execute();
					$resultado46 = $statement46->get_result();
					$linha46 = mysqli_fetch_assoc($resultado46);
						$num_componentes_docente = $linha46["COUNT(DISTINCT a.id_componente)"];
				
					$c = 0;
					$statement5 = mysqli_prepare($conn, "SELECT DISTINCT a.id_componente FROM aula a 
														INNER JOIN componente c ON a.id_componente = c.id_componente 
														WHERE c.id_disciplina = $idDisciplina AND a.id_docente = $idDocente;");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					while($linha5 = mysqli_fetch_assoc($resultado5)){
						$id_componente = $linha5["id_componente"];
						$c = $c + 1;
						
						$statement6 = mysqli_prepare($conn, "SELECT tc.nome_tipocomponente, c.numero_horas FROM tipo_componente tc
													INNER JOIN componente c ON tc.id_tipocomponente = c.id_tipocomponente
													WHERE c.id_componente = $id_componente;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_componente = $linha6["nome_tipocomponente"];
							$numero_horas = $linha6["numero_horas"];
							
							$statement7 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								$linha7 = mysqli_fetch_assoc($resultado7);
									$num_turmas_comp_docente = $linha7["COUNT(DISTINCT id_turma)"];
								
								$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement8->execute();
								$resultado8 = $statement8->get_result();
								$linha8 = mysqli_fetch_assoc($resultado8);
									$num_juncoes = $linha8["COUNT(DISTINCT id_juncao)"];
								
								$counter_turma = 0;
								
								$altura_total = 0;
								
								//São todas aulas separadas
								if($num_juncoes == 0){
									$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									while($linha7 = mysqli_fetch_assoc($resultado7)){ 
										$id_turma = $linha7["id_turma"];
										$counter_turma = $counter_turma + 1;
										
										$altura_total += 20;
									}
								}
								else{
									$statement25 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NULL;");
									$statement25->execute();
									$resultado25 = $statement25->get_result();
									$linha25 = mysqli_fetch_assoc($resultado25);
										$num_turmas_outras = $linha25["COUNT(DISTINCT id_turma)"];
										
										//Colocar primeiro as turmas isoladas
										if($num_turmas_outras > 0){
											
											$statement26 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NULL;");
											$statement26->execute();
											$resultado26 = $statement26->get_result();
											while($linha26 = mysqli_fetch_assoc($resultado26)){
												$id_turma_temp = $linha26["id_turma"];
												$counter_turma = $counter_turma + 1;
												$altura_total += 20;
											}
										}
										//Ver agora as turmas nas junções
											$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
											$statement12->execute();
											$resultado12 = $statement12->get_result();
											while($linha12 = mysqli_fetch_assoc($resultado12)){
												$id_juncao = (int) $linha12["id_juncao"];
												
												//echo "COMP: ", $id_componente, " DOCENTE: ", $idDocente;
												
												$statement13 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao = $id_juncao ORDER BY id_turma;");
												$statement13->execute();
												$resultado13 = $statement13->get_result();
												while($linha13 = mysqli_fetch_assoc($resultado13)){
													$id_turma = $linha13["id_turma"];
													$counter_turma = $counter_turma + 1;
												}
												$altura_total += 20;
											}
										
								}
								
								$altura_total += 25 + ($counter_turma * 20);
								if($altura_total < 150){
									$altura_total = 150;
								}
							?>
						<div class="DSD_componente" style="height:<?php echo $altura_total ?>px">

						<div class="DSD_componente_titulo">
							<b> <?php echo $nome_componente, " (", $numero_horas, "H)"; ?> </b>
						</div>
						<div class="DSD_componente_turmas">
							<?php
								$statement7 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								$linha7 = mysqli_fetch_assoc($resultado7);
									$num_turmas_comp_docente = $linha7["COUNT(DISTINCT id_turma)"];
								
								$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement8->execute();
								$resultado8 = $statement8->get_result();
								$linha8 = mysqli_fetch_assoc($resultado8);
									$num_juncoes = $linha8["COUNT(DISTINCT id_juncao)"];
								
								//São todas aulas separadas
								if($num_juncoes == 0){
									
									$counter_turma = 0;
									$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									while($linha7 = mysqli_fetch_assoc($resultado7)){ 
										$id_turma = $linha7["id_turma"];
										$counter_turma = $counter_turma + 1;
										
										$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
										$statement8->execute();
										$resultado8 = $statement8->get_result();
										$linha8 = mysqli_fetch_assoc($resultado8);
											$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
												 
										$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
										$statement9->execute();
										$resultado9 = $statement9->get_result();
										$linha9 = mysqli_fetch_assoc($resultado9);
											$nomeTurma = $linha9["nome"];
											$anoTurma = $linha9["ano"];
											$semTurma = $linha9["semestre"];
											
											if($num_juncoes_turma > 0){
												$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
												$statement10->execute();
												$resultado10 = $statement10->get_result();
												$linha10 = mysqli_fetch_assoc($resultado10);
													$id_juncao = $linha10["id_juncao"];
													
													$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
													$statement11->execute();
													$resultado11 = $statement11->get_result();
													$linha11 = mysqli_fetch_assoc($resultado11);
														$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
													
														if($num_componentes_diferentes_juncao == 1){
															echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
														else{
															echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
											}
											else{
												echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
											}
											
											if($counter_turma != $num_turmas_comp_docente){
												echo "-------------------------<br>";
											}	
									}
									
								}
								//Há pelo menos uma junção
								else{
									
									$statement25 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NULL;");
									$statement25->execute();
									$resultado25 = $statement25->get_result();
									$linha25 = mysqli_fetch_assoc($resultado25);
										$num_turmas_outras = $linha25["COUNT(DISTINCT id_turma)"];
										
										//Colocar primeiro as turmas isoladas
										if($num_turmas_outras > 0){
											
											$statement26 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NULL;");
											$statement26->execute();
											$resultado26 = $statement26->get_result();
											while($linha26 = mysqli_fetch_assoc($resultado26)){
												$id_turma_temp = $linha26["id_turma"];
															
												$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
												$statement8->execute();
												$resultado8 = $statement8->get_result();
												$linha8 = mysqli_fetch_assoc($resultado8);
													$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
														 
												$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma_temp");
												$statement9->execute();
												$resultado9 = $statement9->get_result();
												$linha9 = mysqli_fetch_assoc($resultado9);
													$nomeTurma = $linha9["nome"];
													$anoTurma = $linha9["ano"];
													$semTurma = $linha9["semestre"];
													
													if($num_juncoes_turma > 0){
														$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
														$statement10->execute();
														$resultado10 = $statement10->get_result();
														$linha10 = mysqli_fetch_assoc($resultado10);
															$id_juncao = $linha10["id_juncao"];
															
															$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
															$statement11->execute();
															$resultado11 = $statement11->get_result();
															$linha11 = mysqli_fetch_assoc($resultado11);
																$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
															
																if($num_componentes_diferentes_juncao == 1){
																	echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																	"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																}
																else{
																	echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																	"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																}
													}
													else{
														echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
													}
												
												echo "-------------------------<br>";
											}
												
											$counter_juncoes_1 = 0;
											
											//Ver agora as turmas nas junções
											$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
											$statement12->execute();
											$resultado12 = $statement12->get_result();
											while($linha12 = mysqli_fetch_assoc($resultado12)){
												$id_juncao = (int) $linha12["id_juncao"];
												$counter_juncoes_1 = $counter_juncoes_1 + 1;
												
												//echo "COMP: ", $id_componente, " DOCENTE: ", $idDocente;
												
												$statement13 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao = $id_juncao ORDER BY id_turma;");
												$statement13->execute();
												$resultado13 = $statement13->get_result();
												while($linha13 = mysqli_fetch_assoc($resultado13)){
													$id_turma = $linha13["id_turma"];
													
													$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
													$statement8->execute();
													$resultado8 = $statement8->get_result();
													$linha8 = mysqli_fetch_assoc($resultado8);
														$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
															 
													$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
													$statement9->execute();
													$resultado9 = $statement9->get_result();
													$linha9 = mysqli_fetch_assoc($resultado9);
														$nomeTurma = $linha9["nome"];
														$anoTurma = $linha9["ano"];
														$semTurma = $linha9["semestre"];
														
														if($num_juncoes_turma > 0){
															$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
															$statement10->execute();
															$resultado10 = $statement10->get_result();
															$linha10 = mysqli_fetch_assoc($resultado10);
																$id_juncao = $linha10["id_juncao"];
																
																$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
																$statement11->execute();
																$resultado11 = $statement11->get_result();
																$linha11 = mysqli_fetch_assoc($resultado11);
																	$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
																
																	if($num_componentes_diferentes_juncao == 1){
																		echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
																	else{
																		echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
														}
														else{
															echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
														}
													
													}
												if($counter_juncoes_1 != $num_juncoes){
													echo "-------------------------<br>";
												}	
											}
											
										}
									
										//Só há junções
										else{
										
											$counter_juncoes_2 = 0;
											$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
											$statement12->execute();
											$resultado12 = $statement12->get_result();
											while($linha12 = mysqli_fetch_assoc($resultado12)){
												$id_juncao = (int) $linha12["id_juncao"];
												$counter_juncoes_2 = $counter_juncoes_2 + 1;
												
												//echo "COMP: ", $id_componente, " DOCENTE: ", $idDocente;
												
												$statement13 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao = $id_juncao ORDER BY id_turma;");
												$statement13->execute();
												$resultado13 = $statement13->get_result();
												while($linha13 = mysqli_fetch_assoc($resultado13)){
													$id_turma = $linha13["id_turma"];
													
													$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
													$statement8->execute();
													$resultado8 = $statement8->get_result();
													$linha8 = mysqli_fetch_assoc($resultado8);
														$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
															 
													$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
													$statement9->execute();
													$resultado9 = $statement9->get_result();
													$linha9 = mysqli_fetch_assoc($resultado9);
														$nomeTurma = $linha9["nome"];
														$anoTurma = $linha9["ano"];
														$semTurma = $linha9["semestre"];
														
														if($num_juncoes_turma > 0){
															$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
															$statement10->execute();
															$resultado10 = $statement10->get_result();
															$linha10 = mysqli_fetch_assoc($resultado10);
																$id_juncao = $linha10["id_juncao"];
																
																$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
																$statement11->execute();
																$resultado11 = $statement11->get_result();
																$linha11 = mysqli_fetch_assoc($resultado11);
																	$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
																
																	if($num_componentes_diferentes_juncao == 1){
																		echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
																	else{
																		echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
														}
														else{
															echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
														}
													
													}
												if($counter_juncoes_2 != $num_juncoes){
													echo "-------------------------<br>";
												}
											}
											
										}
									
									
								}
								/*
								$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								while($linha7 = mysqli_fetch_assoc($resultado7)){ 
									$id_turma = $linha7["id_turma"];
									
									$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
									$statement8->execute();
									$resultado8 = $statement8->get_result();
									$linha8 = mysqli_fetch_assoc($resultado8);
										$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
											 
									$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$nomeTurma = $linha9["nome"];
										$anoTurma = $linha9["ano"];
										$semTurma = $linha9["semestre"];
										
										if($num_juncoes_turma > 0){
											$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
											$statement10->execute();
											$resultado10 = $statement10->get_result();
											$linha10 = mysqli_fetch_assoc($resultado10);
												$id_juncao = $linha10["id_juncao"];
												
												$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
												$statement11->execute();
												$resultado11 = $statement11->get_result();
												$linha11 = mysqli_fetch_assoc($resultado11);
													$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
												
													if($num_componentes_diferentes_juncao == 1){
														echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
														"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
													}
													else{
														echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
														"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
													}
										}
										else{
											echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
										}
									} */
							?>
						</div>
					</div>
			<?php }
			echo "</div>";
			}
			 ?>
			</div>
			<div id="2_sem" style="width:1250px; display:inline-block;">
			<h6 style="margin-left:15px; margin-top:20px;">2º Sem</h6>
			<?php $statement4 = mysqli_prepare($conn, "SELECT DISTINCT d.id_disciplina, d.nome_uc, d.abreviacao_uc, d.codigo_uc, d.id_curso FROM disciplina d 
												INNER JOIN componente c ON d.id_disciplina = c.id_disciplina 
												INNER JOIN aula a ON c.id_componente = a.id_componente 
												WHERE a.id_componente IN
												(SELECT DISTINCT id_componente FROM aula a WHERE id_docente = $idDocente) AND d.semestre = 2;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			while($linha4 = mysqli_fetch_assoc($resultado4)){
				$idDisciplina = $linha4["id_disciplina"];
				$nomeDisciplina = $linha4["nome_uc"];
				$siglaUC = $linha4["abreviacao_uc"];
				$codigoUC = $linha4["codigo_uc"];
				$idCurso = $linha4["id_curso"];
				
				$array_alturas = array();
				
				$statement200 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $idDisciplina;");
				$statement200->execute();
				$resultado200 = $statement200->get_result();
				while($linha200 = mysqli_fetch_assoc($resultado200)){
					$id_comp_comp = $linha200["id_componente"];
					
					$statement201 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente;");
					$statement201->execute();
					$resultado201 = $statement201->get_result();
					$linha201 = mysqli_fetch_assoc($resultado201);
						$comp_tem_este_docente= $linha201["COUNT(id_componente)"];
					
						if($comp_tem_este_docente > 0){
							
							$altura_comp = 40;
						
							$statement202 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente;");
							$statement202->execute();
							$resultado202 = $statement202->get_result();
							$linha202 = mysqli_fetch_assoc($resultado202);
								$num_juncoes = $linha202["COUNT(id_juncao)"];
						
								if($num_juncoes == 0){
									//Todas separadas
									$statement203 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente;");
									$statement203->execute();
									$resultado203 = $statement203->get_result();
									$linha203 = mysqli_fetch_assoc($resultado203);
										$num_turmas_individuais = $linha203["COUNT(DISTINCT id_turma)"];
										
										$altura_comp += $num_turmas_individuais * 45;
										//echo $altura_comp;
								}
								else{
									$statement204 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente AND id_juncao IS NULL;");
									$statement204->execute();
									$resultado204 = $statement204->get_result();
									$linha204 = mysqli_fetch_assoc($resultado204);
										$num_turmas_isoladas = $linha204["COUNT(DISTINCT id_turma)"];
									
										$statement2004 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
										$statement2004->execute();
										$resultado2004 = $statement2004->get_result();
										$linha2004 = mysqli_fetch_assoc($resultado2004);
											$num_juncoes_total = $linha2004["COUNT(DISTINCT id_juncao)"];
									
										$counter_1 = 0;
									
										$statement205 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
										$statement205->execute();
										$resultado205 = $statement205->get_result();
										while($linha205 = mysqli_fetch_assoc($resultado205)){
											$id_juncao = $linha205["id_juncao"];
											$statement206 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente = $idDocente AND id_juncao = $id_juncao;");
											$statement206->execute();
											$resultado206 = $statement206->get_result();
											$linha206 = mysqli_fetch_assoc($resultado206);
												$num_turmas_juncao = $linha206["COUNT(DISTINCT id_turma)"];
													
												$altura_comp += $num_turmas_juncao * 33;
												
												$counter_1 += 1;
												if($counter_1 != $num_juncoes_total){
													$altura_comp += 20;
												}
										}
										
										if($num_turmas_isoladas != 0){
											
											$altura_comp += $num_turmas_isoladas * 45;
											
										}
								}
								
								array_push($array_alturas,$altura_comp);
						}
				}
				
				$altura_final = max($array_alturas);
				
				if($altura_final < 185){
					$altura_final = 185;
				}
				
				echo "<div style='width:1250px; height:", $altura_final, "px;'>";
				
				$statement45 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $idCurso;");
				$statement45->execute();
				$resultado45 = $statement45->get_result();
				$linha45 = mysqli_fetch_assoc($resultado45);
					$siglaCurso = $linha45["sigla"];

				?>
				<div class="card_DSD" id="card_DSD" onclick="">
					<div class="container_card_DSD">
						<div class="container_card_DSD_disciplina">
							<b><?php echo $nomeDisciplina, " (", $siglaCurso, ")<br>(", $siglaUC, ") (", $codigoUC, ")"; ?></b>
						</div> 
						
					</div>
				</div>
				<?php
					$statement46 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT a.id_componente) FROM aula a 
														INNER JOIN componente c ON a.id_componente = c.id_componente 
														WHERE c.id_disciplina = $idDisciplina AND a.id_docente = $idDocente;");
					$statement46->execute();
					$resultado46 = $statement46->get_result();
					$linha46 = mysqli_fetch_assoc($resultado46);
						$num_componentes_docente = $linha46["COUNT(DISTINCT a.id_componente)"];
				
					$c = 0;
					$statement5 = mysqli_prepare($conn, "SELECT DISTINCT a.id_componente FROM aula a 
														INNER JOIN componente c ON a.id_componente = c.id_componente 
														WHERE c.id_disciplina = $idDisciplina AND a.id_docente = $idDocente;");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					while($linha5 = mysqli_fetch_assoc($resultado5)){
						$id_componente = $linha5["id_componente"];
						$c = $c + 1;
						
						$statement6 = mysqli_prepare($conn, "SELECT tc.nome_tipocomponente, c.numero_horas FROM tipo_componente tc
													INNER JOIN componente c ON tc.id_tipocomponente = c.id_tipocomponente
													WHERE c.id_componente = $id_componente;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$nome_componente = $linha6["nome_tipocomponente"];
							$numero_horas = $linha6["numero_horas"];
							
							$statement7 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								$linha7 = mysqli_fetch_assoc($resultado7);
									$num_turmas_comp_docente = $linha7["COUNT(DISTINCT id_turma)"];
								
								$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement8->execute();
								$resultado8 = $statement8->get_result();
								$linha8 = mysqli_fetch_assoc($resultado8);
									$num_juncoes = $linha8["COUNT(DISTINCT id_juncao)"];
								
								$counter_turma = 0;
								
								$altura_total = 0;
								
								//São todas aulas separadas
								if($num_juncoes == 0){
									$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									while($linha7 = mysqli_fetch_assoc($resultado7)){ 
										$id_turma = $linha7["id_turma"];
										$counter_turma = $counter_turma + 1;
										
										$altura_total += 20;
									}
								}
								else{
									$statement25 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NULL;");
									$statement25->execute();
									$resultado25 = $statement25->get_result();
									$linha25 = mysqli_fetch_assoc($resultado25);
										$num_turmas_outras = $linha25["COUNT(DISTINCT id_turma)"];
										
										//Colocar primeiro as turmas isoladas
										if($num_turmas_outras > 0){
											
											$statement26 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NULL;");
											$statement26->execute();
											$resultado26 = $statement26->get_result();
											while($linha26 = mysqli_fetch_assoc($resultado26)){
												$id_turma_temp = $linha26["id_turma"];
												$counter_turma = $counter_turma + 1;
												$altura_total += 20;
											}
										}
										//Ver agora as turmas nas junções
											$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
											$statement12->execute();
											$resultado12 = $statement12->get_result();
											while($linha12 = mysqli_fetch_assoc($resultado12)){
												$id_juncao = (int) $linha12["id_juncao"];
												
												//echo "COMP: ", $id_componente, " DOCENTE: ", $idDocente;
												
												$statement13 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao = $id_juncao ORDER BY id_turma;");
												$statement13->execute();
												$resultado13 = $statement13->get_result();
												while($linha13 = mysqli_fetch_assoc($resultado13)){
													$id_turma = $linha13["id_turma"];
													$counter_turma = $counter_turma + 1;
												}
												$altura_total += 20;
											}
										
								}
								
								$altura_total += 25 + ($counter_turma * 20);
								if($altura_total < 150){
									$altura_total = 150;
								}
							?>
						<div class="DSD_componente" style="height:<?php echo $altura_total ?>px">

						<div class="DSD_componente_titulo">
							<b> <?php echo $nome_componente, " (", $numero_horas, "H)"; ?> </b>
						</div>
						<div class="DSD_componente_turmas">
							<?php
								$statement7 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								$linha7 = mysqli_fetch_assoc($resultado7);
									$num_turmas_comp_docente = $linha7["COUNT(DISTINCT id_turma)"];
								
								$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement8->execute();
								$resultado8 = $statement8->get_result();
								$linha8 = mysqli_fetch_assoc($resultado8);
									$num_juncoes = $linha8["COUNT(DISTINCT id_juncao)"];
								
								//São todas aulas separadas
								if($num_juncoes == 0){
									
									$counter_turma = 0;
									$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									while($linha7 = mysqli_fetch_assoc($resultado7)){ 
										$id_turma = $linha7["id_turma"];
										$counter_turma = $counter_turma + 1;
										
										$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
										$statement8->execute();
										$resultado8 = $statement8->get_result();
										$linha8 = mysqli_fetch_assoc($resultado8);
											$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
												 
										$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
										$statement9->execute();
										$resultado9 = $statement9->get_result();
										$linha9 = mysqli_fetch_assoc($resultado9);
											$nomeTurma = $linha9["nome"];
											$anoTurma = $linha9["ano"];
											$semTurma = $linha9["semestre"];
											
											if($num_juncoes_turma > 0){
												$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
												$statement10->execute();
												$resultado10 = $statement10->get_result();
												$linha10 = mysqli_fetch_assoc($resultado10);
													$id_juncao = $linha10["id_juncao"];
													
													$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
													$statement11->execute();
													$resultado11 = $statement11->get_result();
													$linha11 = mysqli_fetch_assoc($resultado11);
														$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
													
														if($num_componentes_diferentes_juncao == 1){
															echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
														else{
															echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
											}
											else{
												echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
											}
											
											if($counter_turma != $num_turmas_comp_docente){
												echo "-------------------------<br>";
											}	
									}
									
								}
								//Há pelo menos uma junção
								else{
									
									$statement25 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NULL;");
									$statement25->execute();
									$resultado25 = $statement25->get_result();
									$linha25 = mysqli_fetch_assoc($resultado25);
										$num_turmas_outras = $linha25["COUNT(DISTINCT id_turma)"];
										
										//Colocar primeiro as turmas isoladas
										if($num_turmas_outras > 0){
											
											$statement26 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NULL;");
											$statement26->execute();
											$resultado26 = $statement26->get_result();
											while($linha26 = mysqli_fetch_assoc($resultado26)){
												$id_turma_temp = $linha26["id_turma"];
															
												$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
												$statement8->execute();
												$resultado8 = $statement8->get_result();
												$linha8 = mysqli_fetch_assoc($resultado8);
													$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
														 
												$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma_temp");
												$statement9->execute();
												$resultado9 = $statement9->get_result();
												$linha9 = mysqli_fetch_assoc($resultado9);
													$nomeTurma = $linha9["nome"];
													$anoTurma = $linha9["ano"];
													$semTurma = $linha9["semestre"];
													
													if($num_juncoes_turma > 0){
														$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma_temp;");
														$statement10->execute();
														$resultado10 = $statement10->get_result();
														$linha10 = mysqli_fetch_assoc($resultado10);
															$id_juncao = $linha10["id_juncao"];
															
															$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
															$statement11->execute();
															$resultado11 = $statement11->get_result();
															$linha11 = mysqli_fetch_assoc($resultado11);
																$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
															
																if($num_componentes_diferentes_juncao == 1){
																	echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																	"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																}
																else{
																	echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																	"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																}
													}
													else{
														echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
													}
												
												echo "-------------------------<br>";
											}
												
											$counter_juncoes_1 = 0;
											
											//Ver agora as turmas nas junções
											$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
											$statement12->execute();
											$resultado12 = $statement12->get_result();
											while($linha12 = mysqli_fetch_assoc($resultado12)){
												$id_juncao = (int) $linha12["id_juncao"];
												$counter_juncoes_1 = $counter_juncoes_1 + 1;
												
												//echo "COMP: ", $id_componente, " DOCENTE: ", $idDocente;
												
												$statement13 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao = $id_juncao ORDER BY id_turma;");
												$statement13->execute();
												$resultado13 = $statement13->get_result();
												while($linha13 = mysqli_fetch_assoc($resultado13)){
													$id_turma = $linha13["id_turma"];
													
													$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
													$statement8->execute();
													$resultado8 = $statement8->get_result();
													$linha8 = mysqli_fetch_assoc($resultado8);
														$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
															 
													$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
													$statement9->execute();
													$resultado9 = $statement9->get_result();
													$linha9 = mysqli_fetch_assoc($resultado9);
														$nomeTurma = $linha9["nome"];
														$anoTurma = $linha9["ano"];
														$semTurma = $linha9["semestre"];
														
														if($num_juncoes_turma > 0){
															$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
															$statement10->execute();
															$resultado10 = $statement10->get_result();
															$linha10 = mysqli_fetch_assoc($resultado10);
																$id_juncao = $linha10["id_juncao"];
																
																$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
																$statement11->execute();
																$resultado11 = $statement11->get_result();
																$linha11 = mysqli_fetch_assoc($resultado11);
																	$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
																
																	if($num_componentes_diferentes_juncao == 1){
																		echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
																	else{
																		echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
														}
														else{
															echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
														}
													
													}
												if($counter_juncoes_1 != $num_juncoes){
													echo "-------------------------<br>";
												}	
											}
											
										}
									
										//Só há junções
										else{
										
											$counter_juncoes_2 = 0;
											$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao IS NOT NULL;");
											$statement12->execute();
											$resultado12 = $statement12->get_result();
											while($linha12 = mysqli_fetch_assoc($resultado12)){
												$id_juncao = (int) $linha12["id_juncao"];
												$counter_juncoes_2 = $counter_juncoes_2 + 1;
												
												//echo "COMP: ", $id_componente, " DOCENTE: ", $idDocente;
												
												$statement13 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente AND id_juncao = $id_juncao ORDER BY id_turma;");
												$statement13->execute();
												$resultado13 = $statement13->get_result();
												while($linha13 = mysqli_fetch_assoc($resultado13)){
													$id_turma = $linha13["id_turma"];
													
													$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
													$statement8->execute();
													$resultado8 = $statement8->get_result();
													$linha8 = mysqli_fetch_assoc($resultado8);
														$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
															 
													$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
													$statement9->execute();
													$resultado9 = $statement9->get_result();
													$linha9 = mysqli_fetch_assoc($resultado9);
														$nomeTurma = $linha9["nome"];
														$anoTurma = $linha9["ano"];
														$semTurma = $linha9["semestre"];
														
														if($num_juncoes_turma > 0){
															$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
															$statement10->execute();
															$resultado10 = $statement10->get_result();
															$linha10 = mysqli_fetch_assoc($resultado10);
																$id_juncao = $linha10["id_juncao"];
																
																$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
																$statement11->execute();
																$resultado11 = $statement11->get_result();
																$linha11 = mysqli_fetch_assoc($resultado11);
																	$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
																
																	if($num_componentes_diferentes_juncao == 1){
																		echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
																	else{
																		echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
														}
														else{
															echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
														}
													
													}
												if($counter_juncoes_2 != $num_juncoes){
													echo "-------------------------<br>";
												}
											}
											
										}
									
									
								}
								/*
								$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente = $idDocente ORDER BY id_turma;");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								while($linha7 = mysqli_fetch_assoc($resultado7)){ 
									$id_turma = $linha7["id_turma"];
									
									$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
									$statement8->execute();
									$resultado8 = $statement8->get_result();
									$linha8 = mysqli_fetch_assoc($resultado8);
										$num_juncoes_turma = $linha8["COUNT(DISTINCT id_juncao)"];
											 
									$statement9 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
									$statement9->execute();
									$resultado9 = $statement9->get_result();
									$linha9 = mysqli_fetch_assoc($resultado9);
										$nomeTurma = $linha9["nome"];
										$anoTurma = $linha9["ano"];
										$semTurma = $linha9["semestre"];
										
										if($num_juncoes_turma > 0){
											$statement10 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
											$statement10->execute();
											$resultado10 = $statement10->get_result();
											$linha10 = mysqli_fetch_assoc($resultado10);
												$id_juncao = $linha10["id_juncao"];
												
												$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
												$statement11->execute();
												$resultado11 = $statement11->get_result();
												$linha11 = mysqli_fetch_assoc($resultado11);
													$num_componentes_diferentes_juncao = $linha11["COUNT(DISTINCT id_componente)"];
												
													if($num_componentes_diferentes_juncao == 1){
														echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
														"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
													}
													else{
														echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) ", 
														"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
													}
										}
										else{
											echo "• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
										}
									} */
							?>
						</div>
					</div>
			<?php }
			echo "</div>";
			} ?>
			</div>
			
		</div>
	</div>
</div>
</main>

<!-- Modal -->
<div class="modal fade" id="visDSD_ver_dados_juncao" tabindex="-1" role="dialog" aria-labelledby="titulo_visDSD_ver_dados_juncao" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 25%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_visDSD_ver_dados_juncao">Dados Junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            </script>
            <div id="modalBody_visDSD_ver_dados_juncao" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function filtrar1sem(){
	const checkbox_1_sem = document.getElementById("checkbox_1_sem");
	const checkbox_1_sem_letra = document.getElementById("checkbox_1_sem_letra");
	const checkbox_2_sem = document.getElementById("checkbox_2_sem");
	const checkbox_2_sem_letra = document.getElementById("checkbox_2_sem_letra");
	
	if(!checkbox_1_sem.checked){
		checkbox_1_sem.style.opacity = "0.5";
		checkbox_1_sem_letra.style.opacity = "0.5";
		if(!checkbox_2_sem.checked){
			//Mostrar todas as UC's
			alert("Mostrar todas");
		}
		else{
			//Mostrar apenas do 2º Semestre
			alert("Mostrar 2_SEM");
		}
	}
	else{
		checkbox_1_sem.style.opacity = "1";
		checkbox_1_sem_letra.style.opacity = "1";
		//Mostrar apenas do 1º Semestre
		alert("Mostrar 1_SEM");
	}
	
}

function filtrar2sem(){
	
}

function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_DSD");
	li_DSD.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function verTurmas(id_disciplina,id_componente,id_docente){
	
	var divTurmas = document.getElementById("turmas_disciplina_" + id_disciplina);
	var id_comp = divTurmas.getAttribute('data-id_componente');
	var divComponente = document.getElementById("componente_" + id_componente);
	var divComponenteAntiga = document.getElementById("componente_" + id_comp);
	
	//alert("Ver turmas: " + id_componente + " docente: " + id_docente + " ATRIBUTO: " + id_comp);
	/*
	$.ajax ({
		type: "POST",
		url: "processamento/verTurmasAula.php", 
		dataType: "json",
		data: {id_componente: id_componente, id_docente: id_docente},
		success: function(result) {
			alert("Resultado: " + result);
		}
	});
	*/
	
	
	if(id_componente != id_comp){
		divTurmas.style.visibility = "hidden";
		//divTurmas.innerHTML = "<text style='font-size:14px;'>COMP: " + id_componente + "</text> "; 
		$.ajax ({
			type: "POST",
			url: "processamento/verTurmasAula.php", 
			dataType: "json",
			data: {id_componente: id_componente, id_docente: id_docente},
			success: function(result) {
				var num_turmas = (result.length / 4);
				//alert("Nº turmas: " + (result.length / 4));
				
				var string_turmas =  "<text style='font-size:14px;'>";
				
				for(i = 0; i < result.length; i = i + 4){
					string_turmas += "• " + result[i + 1] + " (" + result[i + 2] + "ºA/" + result[i + 3] + "ºS)";
					if(result[i] != null){
						string_turmas += "<img src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' onclick='verDadosJuncao(" + result[i] + ")' class='juncao' title='Esta turma está numa junção' style='width:15px; height:15px; margin-left:5px;'><br>";
					}
					else{
						string_turmas += "<br>";
					}
				}
				
				string_turmas += "</text> "; 
				
				divTurmas.innerHTML = string_turmas;
				
				divComponente.style.background = "#fafafa";
			//	divComponenteAntiga.style.background = "#ebebeb";
				divTurmas.style.visibility = "visible";
				divTurmas.setAttribute("data-id_componente",id_componente);
				divComponenteAntiga.style.background = "#ebebeb";
			}
		});
	}
	else{
		if(divTurmas.style.visibility != "visible"){
			divTurmas.innerHTML = "<text style='font-size:14px;'>INF1 (3ºA/1ºS)</text> " + 
						  "<img src='http://localhost/apoio_utc/images/join.png' style='width:18px; height:18px; margin-left:4px;' title='Esta turma está numa junção!'>";
			divTurmas.style.visibility = "visible";
			divTurmas.setAttribute("data-id_componente",id_componente);
		}
		else{
			divTurmas.style.visibility = "hidden";
			divTurmas.setAttribute("data-id_componente","");
			divComponenteAntiga.style.background = "#ebebeb";
		}
	}
	
	
}

function verDadosJuncao(id_juncao){
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBody_visDSD_ver_dados_juncao").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/verTurmasJuncao.php?id=" + id_juncao, true);
    xhttp.send();
}

function teste123(){
	alert("Teste123");
}

function semestresBloqueados(){
	alert("A DSD está bloqueada em ambos os semestres. Por favor contacte o coordenador da UTC.");
}
</script>

<?php gerarHome2() ?>
