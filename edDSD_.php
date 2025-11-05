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
	
	if($dsd_1_sem == 1 && $dsd_2_sem == 1){
		header("Location: visDSD.php");
	}

/*--------------------------------------------------------------------------------------------------*/

$statement0 = mysqli_prepare($conn, "SELECT sigla_utc, id_responsavel FROM utc WHERE id_utc = $idUtcDocente");
$statement0->execute();
$resultado0 = $statement0->get_result();
$linha0 = mysqli_fetch_assoc($resultado0);
$siglaUtcDocente = $linha0["sigla_utc"];
$idResponsavelUtc = $linha0["id_responsavel"];

/*--------------------------------------------------------------------------------------------------*/

if($idAreaUtilizadorAtual != $idAreaDocente AND $idUtilizadorAtual != $idResponsavelUtc){
	header("Location: visDSD_.php?id=$idDocente");
}
		
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
			<a href="http://localhost/apoio_utc/visDSD.php"><h6 style="margin-left:15px; margin-top:10px;">...</a> / <a href="visDSD_.php?id=<?php echo $idDocente ?>"> <?php echo $nomeDocente ?></a> / <a href="">Editar</a></h6>
			<br>
			<img src="<?php echo $imgDocente ?>" style="width:60px; heigh:60px; border-radius:50%; border:2px solid #212529;"><h3 style="position:absolute; left:85px; top:93px;"><b> <?php echo $nomeDocente ?> </b></h3>
			<span style="position:absolute; left:315px; top:55px;"><h6><?php while($linha2 = mysqli_fetch_assoc($resultado2)){
				$funcao = $linha2["nome"];
				echo $funcao, "<br>";
			}?></h6></span>
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
			
			<?php
			if($dsd_1_sem == 0 && $dsd_2_sem == 0){ ?>
				<a class="btn btn-primary" data-toggle='modal' data-target='#edDSD_adicionar_turma' title="Atribuir UC" onclick="adicionarUC(<?php echo $idDocente ?>)" style="width:165px; border-radius:25px; position:absolute; left:165px; top:18px;"><i class='material-icons' style='vertical-align: middle; margin-right:4px;'>add_circle</i><b>Atribuir UC(s)</b></a>
			<?php }
			else if($dsd_1_sem == 0 && $dsd_2_sem == 1){?>
				<a class="btn btn-semi" data-toggle='modal' data-target='#edDSD_adicionar_turma' title="Atribuir UC (A DSD da UTC do docente no 2º semestre está bloqueada)" onclick="adicionarUC(<?php echo $idDocente ?>)" style="width:165px; border-radius:25px; position:absolute; left:165px; top:18px;"><span class="material-icons" style="vertical-align:middle;">lock</span><b>Atribuir UC(s)</b></a>
			<?php }
			else if($dsd_1_sem == 1 && $dsd_2_sem == 0){?>
				<a class="btn btn-semi" data-toggle='modal' data-target='#edDSD_adicionar_turma' title="Atribuir UC (A DSD da UTC do docente no 1º semestre está bloqueada)" onclick="adicionarUC(<?php echo $idDocente ?>)" style="width:165px; border-radius:25px; position:absolute; left:165px; top:18px;"><span class="material-icons" style="vertical-align:middle;">lock</span><b>Atribuir UC(s)</b></a>
			<?php } ?>

			<?php if($dsd_1_sem == 0) { ?>
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
				
				/*------------------------------------------------DIV------------------------------------------------*/
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
								
								$statement2002 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL;");
								$statement2002->execute();
								$resultado2002 = $statement2002->get_result();
								$linha2002 = mysqli_fetch_assoc($resultado2002);
									$num_turmas_restantes = $linha2002["COUNT(DISTINCT id_turma)"];
						
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
								
								//Botão "Outras..."
								if($num_turmas_restantes > 0){
									$altura_comp += 50;
								}
										
								array_push($array_alturas,$altura_comp);
						}
						
					$statement207 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL;");
					$statement207->execute();
					$resultado207 = $statement207->get_result();
					$linha207 = mysqli_fetch_assoc($resultado207);
						$componentes_por_atribuir = $linha207["COUNT(id_componente)"];
						
						if($componentes_por_atribuir > 0){
							
							$altura_comp = 120;
						
							$statement208 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL;");
							$statement208->execute();
							$resultado208 = $statement208->get_result();
							$linha208 = mysqli_fetch_assoc($resultado208);
								$num_juncoes = $linha208["COUNT(id_juncao)"];
						
								if($num_juncoes == 0){
									//Todas separadas
									$statement208 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL;");
									$statement208->execute();
									$resultado208 = $statement208->get_result();
									$linha208 = mysqli_fetch_assoc($resultado208);
										$num_turmas_individuais = $linha208["COUNT(DISTINCT id_turma)"];
										
										$altura_comp += $num_turmas_individuais * 45;
										
										//Botão "Juntar"
										if($num_turmas_individuais > 1){
											$altura_comp += 50;
										}
								}
								else{
									$statement209 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL AND id_juncao IS NULL;");
									$statement209->execute();
									$resultado209 = $statement209->get_result();
									$linha209 = mysqli_fetch_assoc($resultado209);
										$num_turmas_isoladas = $linha209["COUNT(DISTINCT id_turma)"];
									
										$statement2009 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL AND id_juncao IS NOT NULL;");
										$statement2009->execute();
										$resultado2009 = $statement2009->get_result();
										$linha2009 = mysqli_fetch_assoc($resultado2009);
											$num_juncoes_total = $linha2009["COUNT(DISTINCT id_juncao)"];
									
										$counter_juncao = 0;
										$statement210 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL AND id_juncao IS NOT NULL;");
										$statement210->execute();
										$resultado210 = $statement210->get_result();
										while($linha210 = mysqli_fetch_assoc($resultado210)){
											$id_juncao = $linha210["id_juncao"];
										
											$statement211 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL AND id_juncao = $id_juncao;");
											$statement211->execute();
											$resultado211 = $statement211->get_result();
											$linha211 = mysqli_fetch_assoc($resultado211);
												$num_turmas_juncao = $linha211["COUNT(DISTINCT id_turma)"];
													
												$altura_comp += $num_turmas_juncao * 37;
												
												$counter_juncao += 1;
												if($counter_juncao != $num_juncoes_total){
													$altura_comp += 20;
												}
										}
										
										if($num_turmas_isoladas != 0){
											
											$altura_comp += $num_turmas_isoladas * 45;
											
											//Botão "Juntar"
											if($num_turmas_isoladas > 1){
												$altura_comp += 50;
											}
										}
								}
								
								array_push($array_alturas,$altura_comp);
							
						}
				}
				
				$altura_final = max($array_alturas);
				
				if($altura_final < 185){
					$altura_final = 185;
				}
				//echo $altura_final;
				echo "<div style='width:1250px; height:", $altura_final, "px;'>";
				
				$statement45 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $idCurso;");
				$statement45->execute();
				$resultado45 = $statement45->get_result();
				$linha45 = mysqli_fetch_assoc($resultado45);
					$siglaCurso = $linha45["sigla"];
				?>
				<div class="card_DSD" id="card_DSD">
					<div class="container_card_DSD">
						<div class="container_card_DSD_disciplina">
							<text class="nome_uc_edDSD" onclick="editarUC(<?php echo $idDisciplina ?>)"><b><?php echo $nomeDisciplina, " (", $siglaCurso, ")<br>(", $siglaUC, ") (", $codigoUC, ")"; ?></b></text>
						</div> 
						
					</div>
				</div>
				<?php
					$array_comp_1_sem = array();
				
					$statement47 = mysqli_prepare($conn, "SELECT DISTINCT a.id_componente FROM aula a 
														INNER JOIN componente c ON a.id_componente = c.id_componente 
														WHERE c.id_disciplina = $idDisciplina AND a.id_docente = $idDocente;");
					$statement47->execute();
					$resultado47 = $statement47->get_result();
					while($linha47 = mysqli_fetch_assoc($resultado47)){
						$id_componente = $linha47["id_componente"];
						array_push($array_comp_1_sem,$id_componente);
					}
					
					$array_comp_1_sem_final = implode(",",$array_comp_1_sem);
					
					$array_comp_1_sem_por_atribuir = array();
					
					$statement48 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM componente WHERE id_componente 
															NOT IN ($array_comp_1_sem_final) AND id_disciplina = $idDisciplina;");
					$statement48->execute();
					$resultado48 = $statement48->get_result();
					$linha48 = mysqli_fetch_assoc($resultado48);
						$num_componentes_outros = $linha48["COUNT(id_componente)"];
						
						if($num_componentes_outros > 0){
							
							//Ver se algum dos componentes que faltam tem turmas por atribuir
							$statement49 = mysqli_prepare($conn, "SELECT id_componente FROM componente WHERE id_componente 
															NOT IN ($array_comp_1_sem_final) AND id_disciplina = $idDisciplina;");
							$statement49->execute();
							$resultado49 = $statement49->get_result();
							while($linha49 = mysqli_fetch_assoc($resultado49)){
								$id_componente_outro = $linha49["id_componente"];
								
								$statement50 = mysqli_prepare($conn, "SELECT COUNT(id_turma) FROM aula WHERE id_componente = 
																	$id_componente_outro AND id_docente IS NULL;");
								$statement50->execute();
								$resultado50 = $statement50->get_result();
								$linha50 = mysqli_fetch_assoc($resultado50);
									$num_turmas_por_atribuir_comp = $linha50["COUNT(id_turma)"];
									
									if($num_turmas_por_atribuir_comp > 0){
										array_push($array_comp_1_sem_por_atribuir,$id_componente_outro);
									}
								
							}
						}
						
					//echo "Nº componentes por atribuir: ";
					//print_r($array_comp_1_sem_por_atribuir);
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
								
								
								/*-------------------------------------------------------------------------------*/
								
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
								
								
								$statement14 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente IS NULL ORDER BY id_turma;");
								$statement14->execute();
								$resultado14 = $statement14->get_result();
								$linha14 = mysqli_fetch_assoc($resultado14);
									$num_turmas_em_falta_componente = $linha14["COUNT(DISTINCT id_turma)"];
									
									if($num_turmas_em_falta_componente > 0){
										$altura_total += 35;
									}
									
								
								$altura_total += 28 + ($counter_turma * 20);
								if($altura_total < 150){
									$altura_total = 150;
								}
								
								/*-------------------------------------------------------------------------------*/
							?>
						<div class="DSD_componente" style="height:<?php echo $altura_total ?>px">

						<div class="DSD_componente_titulo">
							<a class='btn btn-secondary' href='#' data-toggle='modal' data-target='#edDSD_componente' onclick='editarComponente(<?php echo $id_componente ?>, <?php echo $idDocente ?>)' style='width:180px; height:30px; border-radius:25px; margin-top:5px; line-height:15px;'><b> <?php echo $nome_componente, " (", $numero_horas, "H)"; ?> </b></a>
						</div>
						<div class="DSD_componente_turmas" style="margin-top:10px;">
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
															echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
														else{
															echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
											}
											else{
												echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, 0)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text><br>";
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
																	echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma_temp, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																	"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																}
																else{
																	echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma_temp, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																	"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																}
													}
													else{
														echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma_temp, 0)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text><br>";
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
																		echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
																	else{
																		echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
														}
														else{
															echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, 0)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text><br>";
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
																		echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
																	else{
																		echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
														}
														else{
															echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, 0)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text><br>";
														}
													
													}
												if($counter_juncoes_2 != $num_juncoes){
													echo "-------------------------<br>";
												}
											}
											
										}
									
									
								}
								$statement14 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente IS NULL ORDER BY id_turma;");
								$statement14->execute();
								$resultado14 = $statement14->get_result();
								$linha14 = mysqli_fetch_assoc($resultado14);
									$num_turmas_em_falta_componente = $linha14["COUNT(DISTINCT id_turma)"];
									
									if($num_turmas_em_falta_componente > 0){
										echo "<a class='btn btn-primary' href='#' data-toggle='modal' data-target='#edDSD_ver_turmas_em_falta' onclick='mostrarTurmasEmFaltaComponente($id_componente)' style='width:90px; height:28px; border-radius:25px; margin-top:5px; line-height:15px;'><b>Outras...</b></a>";
									}
							?>
						</div>
					</div>
					<?php if(sizeof($array_comp_1_sem_por_atribuir) > 0){
						$k = 0;
						while($k < sizeof($array_comp_1_sem_por_atribuir)){
							
							$id_comp_por_atribuir = $array_comp_1_sem_por_atribuir[$k];
							
							$statement100 = mysqli_prepare($conn, "SELECT tc.nome_tipocomponente, c.numero_horas FROM tipo_componente tc
													INNER JOIN componente c ON tc.id_tipocomponente = c.id_tipocomponente
													WHERE c.id_componente = $id_comp_por_atribuir;");
							$statement100->execute();
							$resultado100 = $statement100->get_result();
							$linha100 = mysqli_fetch_assoc($resultado100);
								$nome_componente = $linha100["nome_tipocomponente"];
								$numero_horas = $linha100["numero_horas"];
							
							/*
							if($k == (sizeof($array_comp_1_sem_por_atribuir) - 1)){
								echo "<div class='DSD_componente_por_adicionar' style='margin-right:400px;'>";
							}
							else{
								echo "<div class='DSD_componente_por_adicionar'>";
							}
							*/
							
							/*
							echo "<div class='DSD_componente_por_adicionar' style='height: 150px;'>";
							
							echo "<div class='DSD_componente_titulo_por_adicionar'>";
								echo "<i>", $nome_componente, " (", $numero_horas, "H)</i>";
							echo "</div>";
							
							echo "<div class='DSD_componente_turmas_por_adicionar' style='margin-top:10px;'>";
							
							*/
							
							$num_turmas_por_atribuir_total = 0;
							$num_turmas_por_atribuir_com_juncao = 0;
							$num_turmas_por_atribuir_sem_juncao = 0;
							
							$statement101 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL ORDER BY id_turma;");
							$statement101->execute();
							$resultado101 = $statement101->get_result();
							while($linha101 = mysqli_fetch_assoc($resultado101)){ 
								$id_turma = $linha101["id_turma"];
								
								$num_turmas_por_atribuir_total += 1;
								
								//echo $id_turma, " - ", $id_comp_por_atribuir;
								
								$statement102 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_turma = $id_turma AND id_juncao IS NOT NULL;");
								$statement102->execute();
								$resultado102 = $statement102->get_result();
								$linha102 = mysqli_fetch_assoc($resultado102);
									$num_juncoes_turma = $linha102["COUNT(DISTINCT id_juncao)"];
											 
								$statement103 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
								$statement103->execute();
								$resultado103 = $statement103->get_result();
								$linha103 = mysqli_fetch_assoc($resultado103);
								
									if($num_juncoes_turma > 0){
										$num_turmas_por_atribuir_com_juncao += 1;
									} 
									else{
										$num_turmas_por_atribuir_sem_juncao += 1;
									}
							}
							
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL ORDER BY id_turma;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_juncoes = $linha8["COUNT(DISTINCT id_juncao)"];
								
								$counter_turma = 0;
								
								$altura_total = 0;
								
								//São todas aulas separadas
								if($num_juncoes == 0){
									$altura_total += 75 + ($num_turmas_por_atribuir_total * 40);
									if($num_turmas_por_atribuir_total > 1){
										$altura_total += 25;
									}
								}
								else{
									$altura_total += 75;
									if($num_turmas_por_atribuir_sem_juncao > 0){
										//Há pelo menos uma turma que não está numa junção
										$count_c = 0;
										while($count_c < $num_turmas_por_atribuir_sem_juncao){
											$altura_total += 40;
											$count_c += 1;
										}
										
										$altura_total += 40;
									}
									
									//São só junções
									$count_j = 0;
									$array_turmas_temp = array();
										
									$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL AND id_juncao IS NOT NULL;");
									$statement12->execute();
									$resultado12 = $statement12->get_result();
									while($linha12 = mysqli_fetch_assoc($resultado12)){
										$id_juncao = $linha12["id_juncao"];
												
										$statement13 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL AND id_juncao = $id_juncao ORDER BY id_turma;");
										$statement13->execute();
										$resultado13 = $statement13->get_result();
										while($linha13 = mysqli_fetch_assoc($resultado13)){
											$id_turma_temp = $linha13["id_turma"];
											if(!in_array($id_turma,$array_turmas_temp)){
												$altura_total += 22;
												array_push($array_turmas_temp,$id_turma_temp);
											}
										}
											
										$count_j += 1;
										if($count_j != $num_juncoes){
											$altura_total += 20;
										}
									}									
									
								}
									
								if($altura_total < 150){
									$altura_total = 150;
								}
							
							$nome_div = 'div_' . $id_comp_por_atribuir;
							
							echo "<div class='DSD_componente_por_adicionar' style='height: " . $altura_total . "px;'>";
							
							echo "<div class='DSD_componente_titulo_por_adicionar'>";
								echo "<i>", $nome_componente, " (", $numero_horas, "H)</i>";
							echo "</div>";
							
							echo "<div class='DSD_componente_turmas_por_adicionar' id='$nome_div' style='margin-top:10px;'>";
							
							$turmas_ja_tratadas = array();
							
							$counter_2 = 0;
							$statement101 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL ORDER BY id_turma;");
							$statement101->execute();
							$resultado101 = $statement101->get_result();
							while($linha101 = mysqli_fetch_assoc($resultado101)){ 
								$id_turma = $linha101["id_turma"];
								
								if(!in_array($id_turma,$turmas_ja_tratadas)){
								
								$statement102 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_turma = $id_turma AND id_juncao IS NOT NULL;");
								$statement102->execute();
								$resultado102 = $statement102->get_result();
								$linha102 = mysqli_fetch_assoc($resultado102);
									$num_juncoes_turma = $linha102["COUNT(DISTINCT id_juncao)"];
											 
								$statement103 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
								$statement103->execute();
								$resultado103 = $statement103->get_result();
								$linha103 = mysqli_fetch_assoc($resultado103);
									$nomeTurma = $linha103["nome"];
									$anoTurma = $linha103["ano"];
									$semTurma = $linha103["semestre"];
										
									if($num_juncoes_turma > 0){
										$statement104 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_turma = $id_turma;");
										$statement104->execute();
										$resultado104 = $statement104->get_result();
										$linha104 = mysqli_fetch_assoc($resultado104);
											$id_juncao = $linha104["id_juncao"];
												
											$statement105 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
											$statement105->execute();
											$resultado105 = $statement105->get_result();
											$linha105 = mysqli_fetch_assoc($resultado105);
												$num_componentes_diferentes_juncao = $linha105["COUNT(DISTINCT id_componente)"];
											
												$statement106 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_juncao = $id_juncao;");
												$statement106->execute();
												$resultado106 = $statement106->get_result();
												while($linha106 = mysqli_fetch_assoc($resultado106)){
													$id_turma_temp = $linha106["id_turma"];		
													
													$statement107 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma_temp");
													$statement107->execute();
													$resultado107 = $statement107->get_result();
													$linha107 = mysqli_fetch_assoc($resultado107);
														$nomeTurma_temp = $linha107["nome"];
														$anoTurma_temp = $linha107["ano"];
														$semTurma_temp = $linha107["semestre"];
															
														if($num_componentes_diferentes_juncao == 1){
															echo "<input type='checkbox' data_id-turma='$id_turma_temp' data_id-juncao='$id_juncao' onclick='bloquearOutrasTurmas($id_comp_por_atribuir,$id_turma_temp,$id_juncao)' style='margin-right:2px; margin-top:2px;'><i>", $nomeTurma_temp, " (", $anoTurma_temp, "ºA/", $semTurma_temp, "ºS)</i> ", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#edDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
														else{
															echo "<input type='checkbox' data_id-turma='$id_turma' data_id-juncao='$id_juncao' onclick='bloquearOutrasTurmas($id_comp_por_atribuir,$id_turma,$id_juncao)' style='margin-right:2px; margin-top:2px;'> <i>", $nomeTurma_temp, " (", $anoTurma_temp, "ºA/", $semTurma_temp, "ºS)</i> ", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#edDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														} 
														
													array_push($turmas_ja_tratadas,$id_turma_temp);	
													$counter_2 += 1;
												}
											if($counter_2 < $num_turmas_por_atribuir_total){
												echo "-------------------------<br>";
											}
									} 
									else{
										array_push($turmas_ja_tratadas,$id_turma);
										$counter_2 += 1;
										echo "<input type='checkbox' data_id-turma='$id_turma' style='margin-right:2px; margin-top:2px;'> <i>", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS)</i> <br>";
										if($counter_2 < $num_turmas_por_atribuir_total){
											echo "-------------------------<br>";
										}
									}
								}
							}
							
							if($num_turmas_por_atribuir_com_juncao > 0){
								if($num_turmas_por_atribuir_sem_juncao > 0){
									echo "<input type='checkbox' data_join='1' style='margin-top:15px; margin-right:5px; margin-left:45px;'><b style='margin-right:50px;'>Juntar</b>";
								}
							}
							else{
								if($num_turmas_por_atribuir_sem_juncao > 1){
									echo "<input type='checkbox' data_join='1' style='margin-top:15px; margin-right:5px; margin-left:45px;'><b style='margin-right:50px;'>Juntar</b>";
								}
							}
							echo "<button class='btn btn-primary' onclick='atribuirComponente($id_comp_por_atribuir)' style='width:90px; height:30px; border-radius:25px; margin-top:5px; line-height:15px;'><b>Atribuir</b></button>";
							echo "</div>";
							
							echo "</div>";
							
							$k = $k + 1;
						}
						
						
						
					}?>
			<?php }
			echo "</div>";
			}
			?>
			
			<?php }
			else { ?>
			
				<h6 title="A DSD do 1º semestre está bloqueada" style="margin-left:15px;">1º Sem <span class="material-icons" title="A DSD do 1º semestre está bloqueada" style="vertical-align:middle;">lock</span></h6>
			
			<?php } ?>
			<?php if($dsd_2_sem == 0) { ?>
			<div id="2_sem" style="display:inline-block; width:1250px;">
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
				
				/*------------------------------------------------DIV------------------------------------------------*/
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
								
								$statement2002 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL;");
								$statement2002->execute();
								$resultado2002 = $statement2002->get_result();
								$linha2002 = mysqli_fetch_assoc($resultado2002);
									$num_turmas_restantes = $linha2002["COUNT(DISTINCT id_turma)"];
						
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
								
								//Botão "Outras..."
								if($num_turmas_restantes > 0){
									$altura_comp += 50;
								}
									
								array_push($array_alturas,$altura_comp);
						}
						
					$statement207 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL;");
					$statement207->execute();
					$resultado207 = $statement207->get_result();
					$linha207 = mysqli_fetch_assoc($resultado207);
						$componentes_por_atribuir = $linha207["COUNT(id_componente)"];
						
						if($componentes_por_atribuir > 0){
							
							$altura_comp = 120;
						
							$statement208 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL;");
							$statement208->execute();
							$resultado208 = $statement208->get_result();
							$linha208 = mysqli_fetch_assoc($resultado208);
								$num_juncoes = $linha208["COUNT(id_juncao)"];
						
								if($num_juncoes == 0){
									//Todas separadas
									$statement208 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL;");
									$statement208->execute();
									$resultado208 = $statement208->get_result();
									$linha208 = mysqli_fetch_assoc($resultado208);
										$num_turmas_individuais = $linha208["COUNT(DISTINCT id_turma)"];
										
										$altura_comp += $num_turmas_individuais * 45;
										
										//Botão "Juntar"
										if($num_turmas_individuais > 1){
											$altura_comp += 50;
										}
								}
								else{
									$statement209 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL AND id_juncao IS NULL;");
									$statement209->execute();
									$resultado209 = $statement209->get_result();
									$linha209 = mysqli_fetch_assoc($resultado209);
										$num_turmas_isoladas = $linha209["COUNT(DISTINCT id_turma)"];
									
										$statement2009 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL AND id_juncao IS NOT NULL;");
										$statement2009->execute();
										$resultado2009 = $statement2009->get_result();
										$linha2009 = mysqli_fetch_assoc($resultado2009);
											$num_juncoes_total = $linha2009["COUNT(DISTINCT id_juncao)"];
									
										$counter_juncao = 0;
										$statement210 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL AND id_juncao IS NOT NULL;");
										$statement210->execute();
										$resultado210 = $statement210->get_result();
										while($linha210 = mysqli_fetch_assoc($resultado210)){
											$id_juncao = $linha210["id_juncao"];
										
											$statement211 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp_comp AND id_docente IS NULL AND id_juncao = $id_juncao;");
											$statement211->execute();
											$resultado211 = $statement211->get_result();
											$linha211 = mysqli_fetch_assoc($resultado211);
												$num_turmas_juncao = $linha211["COUNT(DISTINCT id_turma)"];
													
												$altura_comp += $num_turmas_juncao * 37;
												
												$counter_juncao += 1;
												if($counter_juncao != $num_juncoes_total){
													$altura_comp += 20;
												}
										}
										
										if($num_turmas_isoladas != 0){
											
											$altura_comp += $num_turmas_isoladas * 45;
											
											//Botão "Juntar"
											if($num_turmas_isoladas > 1){
												$altura_comp += 50;
											}
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
				<div class="card_DSD" id="card_DSD">
					<div class="container_card_DSD">
						<div class="container_card_DSD_disciplina">
							<text class="nome_uc_edDSD" onclick="editarUC(<?php echo $idDisciplina ?>)"><b><?php echo $nomeDisciplina, " (", $siglaCurso, ")<br>(", $siglaUC, ") (", $codigoUC, ")"; ?></b></text>
						</div> 
						
					</div>
				</div>
				<?php
					$array_comp_2_sem = array();
				
					$statement47 = mysqli_prepare($conn, "SELECT DISTINCT a.id_componente FROM aula a 
														INNER JOIN componente c ON a.id_componente = c.id_componente 
														WHERE c.id_disciplina = $idDisciplina AND a.id_docente = $idDocente;");
					$statement47->execute();
					$resultado47 = $statement47->get_result();
					while($linha47 = mysqli_fetch_assoc($resultado47)){
						$id_componente = $linha47["id_componente"];
						array_push($array_comp_2_sem,$id_componente);
					}
					
					$array_comp_2_sem_final = implode(",",$array_comp_2_sem);
					
					$array_comp_2_sem_por_atribuir = array();
					
					$statement48 = mysqli_prepare($conn, "SELECT COUNT(id_componente) FROM componente WHERE id_componente 
															NOT IN ($array_comp_2_sem_final) AND id_disciplina = $idDisciplina;");
					$statement48->execute();
					$resultado48 = $statement48->get_result();
					$linha48 = mysqli_fetch_assoc($resultado48);
						$num_componentes_outros = $linha48["COUNT(id_componente)"];
						
						if($num_componentes_outros > 0){
							
							//Ver se algum dos componentes que faltam tem turmas por atribuir
							$statement49 = mysqli_prepare($conn, "SELECT id_componente FROM componente WHERE id_componente 
															NOT IN ($array_comp_2_sem_final) AND id_disciplina = $idDisciplina;");
							$statement49->execute();
							$resultado49 = $statement49->get_result();
							while($linha49 = mysqli_fetch_assoc($resultado49)){
								$id_componente_outro = $linha49["id_componente"];
								
								$statement50 = mysqli_prepare($conn, "SELECT COUNT(id_turma) FROM aula WHERE id_componente = 
																	$id_componente_outro AND id_docente IS NULL;");
								$statement50->execute();
								$resultado50 = $statement50->get_result();
								$linha50 = mysqli_fetch_assoc($resultado50);
									$num_turmas_por_atribuir_comp = $linha50["COUNT(id_turma)"];
									
									if($num_turmas_por_atribuir_comp > 0){
										array_push($array_comp_2_sem_por_atribuir,$id_componente_outro);
									}
								
							}
						}
						
					//echo "Nº componentes por atribuir: ";
					//print_r($array_comp_1_sem_por_atribuir);
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
								
								$altura_total += 28 + ($counter_turma * 20);
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
															echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
														else{
															echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
											}
											else{
												echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, 0)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text><br>";
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
																	echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma_temp, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																	"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																}
																else{
																	echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma_temp, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																	"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																}
													}
													else{
														echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma_temp, 0)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text><br>";
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
																		echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
																	else{
																		echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
														}
														else{
															echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, 0)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text><br>";
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
																		echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
																	else{
																		echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, $id_juncao)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) </text>", 
																		"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#edDSD_editar_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='editarJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
																	}
														}
														else{
															echo "<text class='texto_edDSD' data-toggle='modal' data-target='#edDSD_manipular_turma' onclick='gerarFormManipularTurma($idDocente,$id_componente,$id_turma, 0)'>• ", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS) <br>";
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
					</div><?php if(sizeof($array_comp_2_sem_por_atribuir) > 0){
						$k = 0;
						while($k < sizeof($array_comp_2_sem_por_atribuir)){
							
							$id_comp_por_atribuir = $array_comp_2_sem_por_atribuir[$k];
							
							$statement100 = mysqli_prepare($conn, "SELECT tc.nome_tipocomponente, c.numero_horas FROM tipo_componente tc
													INNER JOIN componente c ON tc.id_tipocomponente = c.id_tipocomponente
													WHERE c.id_componente = $id_comp_por_atribuir;");
							$statement100->execute();
							$resultado100 = $statement100->get_result();
							$linha100 = mysqli_fetch_assoc($resultado100);
								$nome_componente = $linha100["nome_tipocomponente"];
								$numero_horas = $linha100["numero_horas"];
							
							$num_turmas_por_atribuir_total = 0;
							$num_turmas_por_atribuir_com_juncao = 0;
							$num_turmas_por_atribuir_sem_juncao = 0;
							
							$statement101 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL ORDER BY id_turma;");
							$statement101->execute();
							$resultado101 = $statement101->get_result();
							while($linha101 = mysqli_fetch_assoc($resultado101)){ 
								$id_turma = $linha101["id_turma"];
								
								$num_turmas_por_atribuir_total += 1;
								
								//echo $id_turma, " - ", $id_comp_por_atribuir;
								
								$statement102 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_turma = $id_turma AND id_juncao IS NOT NULL;");
								$statement102->execute();
								$resultado102 = $statement102->get_result();
								$linha102 = mysqli_fetch_assoc($resultado102);
									$num_juncoes_turma = $linha102["COUNT(DISTINCT id_juncao)"];
											 
								$statement103 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
								$statement103->execute();
								$resultado103 = $statement103->get_result();
								$linha103 = mysqli_fetch_assoc($resultado103);
								
									if($num_juncoes_turma > 0){
										$num_turmas_por_atribuir_com_juncao += 1;
									} 
									else{
										$num_turmas_por_atribuir_sem_juncao += 1;
									}
							}
							
							/*
							echo "<input type='checkbox' style='margin-top:15px; margin-right:5px; margin-left:45px;'><b style='margin-right:50px;'>Juntar</b>";
							echo "<a class='btn btn-primary' href='#' onclick='atribuirComponente()' style='width:90px; height:30px; border-radius:25px; margin-top:5px; line-height:15px;'><b>Atribuir</b></a>";
							
							echo "</div>"; */
							
							
						/*	echo $num_turmas_por_atribuir_total, "<br>";
							echo $num_turmas_por_atribuir_com_juncao, "<br>";
							echo $num_turmas_por_atribuir_sem_juncao, "<br>"; */
							
							
							$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL ORDER BY id_turma;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							$linha8 = mysqli_fetch_assoc($resultado8);
								$num_juncoes = $linha8["COUNT(DISTINCT id_juncao)"];
								
								$counter_turma = 0;
								
								$altura_total = 0;
								
								//São todas aulas separadas
								if($num_juncoes == 0){
									$altura_total += 75 + ($num_turmas_por_atribuir_total * 40);
									if($num_turmas_por_atribuir_total > 1){
										$altura_total += 25;
									}
								}
								else{
									$altura_total += 75;
									if($num_turmas_por_atribuir_sem_juncao > 0){
										//Há pelo menos uma turma que não está numa junção
										$count_c = 0;
										while($count_c < $num_turmas_por_atribuir_sem_juncao){
											$altura_total += 40;
											$count_c += 1;
										}
										
										$altura_total += 40;
									}
									
									//São só junções
									$count_j = 0;
									$array_turmas_temp = array();
										
									$statement12 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL AND id_juncao IS NOT NULL;");
									$statement12->execute();
									$resultado12 = $statement12->get_result();
									while($linha12 = mysqli_fetch_assoc($resultado12)){
										$id_juncao = $linha12["id_juncao"];
												
										$statement13 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL AND id_juncao = $id_juncao ORDER BY id_turma;");
										$statement13->execute();
										$resultado13 = $statement13->get_result();
										while($linha13 = mysqli_fetch_assoc($resultado13)){
											$id_turma_temp = $linha13["id_turma"];
											if(!in_array($id_turma,$array_turmas_temp)){
												$altura_total += 22;
												array_push($array_turmas_temp,$id_turma_temp);
											}
										}
											
										$count_j += 1;
										if($count_j != $num_juncoes){
											$altura_total += 20;
										}
									}									
									
								}
									
								if($altura_total < 150){
									$altura_total = 150;
								}
							
							
							$nome_div = 'div_' . $id_comp_por_atribuir;
							
							echo "<div class='DSD_componente_por_adicionar' style='height: " . $altura_total . "px;'>";
							
							echo "<div class='DSD_componente_titulo_por_adicionar'>";
								echo "<i>", $nome_componente, " (", $numero_horas, "H)</i>";
							echo "</div>";
							
							echo "<div class='DSD_componente_turmas_por_adicionar' id='$nome_div' style='margin-top:10px;'>";
							
							$turmas_ja_tratadas = array();
							
							$counter_2 = 0;
							$statement101 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_docente IS NULL ORDER BY id_turma;");
							$statement101->execute();
							$resultado101 = $statement101->get_result();
							while($linha101 = mysqli_fetch_assoc($resultado101)){ 
								$id_turma = $linha101["id_turma"];
								
								if(!in_array($id_turma,$turmas_ja_tratadas)){
								
								$statement102 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_turma = $id_turma AND id_juncao IS NOT NULL;");
								$statement102->execute();
								$resultado102 = $statement102->get_result();
								$linha102 = mysqli_fetch_assoc($resultado102);
									$num_juncoes_turma = $linha102["COUNT(DISTINCT id_juncao)"];
											 
								$statement103 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
								$statement103->execute();
								$resultado103 = $statement103->get_result();
								$linha103 = mysqli_fetch_assoc($resultado103);
									$nomeTurma = $linha103["nome"];
									$anoTurma = $linha103["ano"];
									$semTurma = $linha103["semestre"];
										
									if($num_juncoes_turma > 0){
										$statement104 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_turma = $id_turma;");
										$statement104->execute();
										$resultado104 = $statement104->get_result();
										$linha104 = mysqli_fetch_assoc($resultado104);
											$id_juncao = $linha104["id_juncao"];
												
											$statement105 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
											$statement105->execute();
											$resultado105 = $statement105->get_result();
											$linha105 = mysqli_fetch_assoc($resultado105);
												$num_componentes_diferentes_juncao = $linha105["COUNT(DISTINCT id_componente)"];
											
												$statement106 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp_por_atribuir AND id_juncao = $id_juncao;");
												$statement106->execute();
												$resultado106 = $statement106->get_result();
												while($linha106 = mysqli_fetch_assoc($resultado106)){
													$id_turma_temp = $linha106["id_turma"];		
													
													$statement107 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma_temp");
													$statement107->execute();
													$resultado107 = $statement107->get_result();
													$linha107 = mysqli_fetch_assoc($resultado107);
														$nomeTurma_temp = $linha107["nome"];
														$anoTurma_temp = $linha107["ano"];
														$semTurma_temp = $linha107["semestre"];
															
														if($num_componentes_diferentes_juncao == 1){
															echo "<input type='checkbox' data_id-turma='$id_turma_temp' data_id-juncao='$id_juncao' onclick='bloquearOutrasTurmas($id_comp_por_atribuir,$id_turma_temp,$id_juncao)' style='margin-right:2px; margin-top:2px;'><i>", $nomeTurma_temp, " (", $anoTurma_temp, "ºA/", $semTurma_temp, "ºS)</i> ", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#edDSD_ver_dados_juncao' title='Esta turma está numa junção' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														}
														else{
															echo "<input type='checkbox' data_id-turma='$id_turma' data_id-juncao='$id_juncao' onclick='bloquearOutrasTurmas($id_comp_por_atribuir,$id_turma,$id_juncao)' style='margin-right:2px; margin-top:2px;'> <i>", $nomeTurma_temp, " (", $anoTurma_temp, "ºA/", $semTurma_temt, "ºS)</i> ", 
															"<img class='juncao' src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#edDSD_ver_dados_juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' onclick='verDadosJuncao($id_juncao)' style='width:15px; height:15px; margin-left:3px;'> <br>";
														} 
														
													array_push($turmas_ja_tratadas,$id_turma_temp);	
													$counter_2 += 1;
												}
											if($counter_2 < $num_turmas_por_atribuir_total){
												echo "-------------------------<br>";
											}
									} 
									else{
										array_push($turmas_ja_tratadas,$id_turma);
										$counter_2 += 1;
										echo "<input type='checkbox' data_id-turma='$id_turma' style='margin-right:2px; margin-top:2px;'> <i>", $nomeTurma, " (", $anoTurma, "ºA/", $semTurma, "ºS)</i> <br>";
										if($counter_2 < $num_turmas_por_atribuir_total){
											echo "-------------------------<br>";
										}
									}
								}
							}
							
							if($num_turmas_por_atribuir_com_juncao > 0){
								if($num_turmas_por_atribuir_sem_juncao > 0){
									echo "<input type='checkbox' data_join='1' style='margin-top:15px; margin-right:5px; margin-left:45px;'><b style='margin-right:50px;'>Juntar</b>";
								}
							}
							else{
								if($num_turmas_por_atribuir_sem_juncao > 1){
									echo "<input type='checkbox' data_join='1' style='margin-top:15px; margin-right:5px; margin-left:45px;'><b style='margin-right:50px;'>Juntar</b>";
								}
							}
							echo "<button class='btn btn-primary' onclick='atribuirComponente($id_comp_por_atribuir)' style='width:90px; height:30px; border-radius:25px; margin-top:5px; line-height:15px;'><b>Atribuir</b></button>";
							echo "</div>";
							
							echo "</div>";
							
							$k = $k + 1;
						}
						
						
						
					}?>
			<?php }
			echo "</div>";
			} ?>
			</div>
			<?php }
			else { ?>
			
				<h6 title="A DSD do 2º semestre está bloqueada" style="margin-left:15px;">2º Sem <span class="material-icons" title="A DSD do 2º semestre está bloqueada" style="vertical-align:middle;">lock</span></h6>
			
			<?php } ?>
		</div>
	</div>
</div>
</main>

<!-- Modal -->
<div class="modal fade" id="edDSD_adicionar_turma" tabindex="-1" role="dialog" aria-labelledby="tituloEditarDSD_adicionar_uc_modal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 51%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEditarDSD_adicionar_uc_modal">Adicionar UC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            </script>
            <div id="modalBodyEditarDSD_adicionar_uc" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edDSD_componente" tabindex="-1" role="dialog" aria-labelledby="titulo_edDSD_componente_modal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 23%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_edDSD_componente_modal">Alterar Docente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_edDSD_componente" class="modal-body">
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="edDSD_manipular_turma" tabindex="-1" role="dialog" aria-labelledby="titulo_edDSD_manipular_turma" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 20%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEditarDSUCdocenteModal">Atribuir Docente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_edDSD_manipular_turma" class="modal-body">
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="edDSD_ver_dados_juncao" tabindex="-1" role="dialog" aria-labelledby="titulo_edDSD_ver_dados_juncao" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 25%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_edDSD_ver_dados_juncao">Dados Junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            </script>
            <div id="modalBody_edDSD_ver_dados_juncao" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edDSD_editar_juncao" tabindex="-1" role="dialog" aria-labelledby="titulo_edDSD_editar_juncao" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 50%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_edDSD_editar_juncao">Dados Junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            </script>
            <div id="modalBody_edDSD_editar_juncao" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edDSD_ver_turmas_em_falta" tabindex="-1" role="dialog" aria-labelledby="titulo_edDSD_ver_turmas_em_falta" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 20%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_edDSD_ver_turmas_em_falta">Outras turmas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            </script>
            <div id="modalBody_edDSD_ver_turmas_em_falta" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edDSD_manipular_turma_criar_juncao" tabindex="-1" role="dialog" aria-labelledby="titulo_edDSD_manipular_turma_criar_juncao" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 27%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_edDSD_manipular_turma_criar_juncao">Criar Junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <script>
            </script>
            <div id="modalBody_edDSD_manipular_turma_criar_juncao" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_DSD");
	li_DSD.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

/*------------------------------ADICIONAR UC------------------------------*/
function adicionarUC(id_docente){
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarDSD_adicionar_uc").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/DSD_adicionar_UC.php?id_docente=" + id_docente, true);
    xhttp.send();	
}

function mostrarCursosUTC_adicionar_uc(){
	
	limparDadosDireita_adicionar_uc();
	
	const dropdown_utc = document.getElementById("adicionar_uc_utc");
	var id_utc_escolhida = dropdown_utc.value;
	
	const dropdown_cursos = document.getElementById("adicionar_uc_curso");
	
	const array_turmas_esquerda = verTurmasEsquerda();
	
	//alert("TESTE: " + array_turmas_esquerda);
	
	$('#semestre_bloqueado').remove();
	
	if(id_utc_escolhida != 0){
		
		$.ajax ({
			type: "POST",
			url: "processamento/juncoes/verificarSemestresBloqueadosUTC.php", 
			data: {id_utc: id_utc_escolhida},
			success: function(result) {
				var result_final = result.split(",");
				
				if(result_final[0] == 1 || result_final[1] == 1){
					
					var semestre_bloqueado = 0;
					
					if(result_final[0] == 1){
						semestre_bloqueado = 1;
					}
					if(result_final[1] == 1){
						semestre_bloqueado = 2;
					}
					
					const div_uc = document.getElementById("criar_juncao_uc");
					div_uc.innerHTML += "<span class='material-icons' id='semestre_bloqueado' title='A DSD do " + semestre_bloqueado + "º semestre desta UTC está bloqueada' style='vertical-align:middle; cursor:default;'>lock</span>";
					
				}
					
				$.ajax ({
					type: "POST",
					url: "processamento/mostrarCursosUTC_por_atribuir.php", 
					data: {id_utc: id_utc_escolhida, array_turmas: array_turmas_esquerda},
					success: function(result) {
						var array = result.split(',');
						//alert("Cursos: " + array);
						
						var vazia = document.createElement('option');
						vazia.value = 0;
						vazia.text = "";
						dropdown_cursos.options.add(vazia);
						
						if(array.length > 1){
						
							for(i = 0; i < array.length; i = i + 2){
								var opt = document.createElement('option');
								opt.value = array[i];
								opt.text = array[i + 1];
								dropdown_cursos.options.add(opt);
							} 
					
						}
				
					}
				});
				
			}
		});
		
		
	}
	
}

function mostrarDisciplinasCurso_adicionar_uc(){

	limparDadosDireita_abaixo_curso_adicionar_uc();

	const dropdown_utc = document.getElementById("adicionar_uc_utc");
	const id_utc_escolhida = dropdown_utc.value;

	const dropdown_curso = document.getElementById("adicionar_uc_curso");
	var id_curso_escolhido = dropdown_curso.value;
	
	const dropdown_uc = document.getElementById("adicionar_uc_disciplina");
	
	const array_turmas_esquerda = verTurmasEsquerda();
	
	if(id_curso_escolhido != 0){
		
		$.ajax ({
			type: "POST",
			url: "processamento/juncoes/verificarSemestresBloqueadosUTC.php", 
			data: {id_utc: id_utc_escolhida},
			success: function(result) {
				var result_final = result.split(",");
				
				if(result_final[0] == 1 || result_final[1] == 1){
					
					var semestre_bloqueado = 0;
					
					if(result_final[0] == 1){
						semestre_bloqueado = 1;
					}
					if(result_final[1] == 1){
						semestre_bloqueado = 2;
					}
					
					$.ajax ({
						type: "POST",
						url: "processamento/juncoes/mostrarUCsCurso_por_atribuir_sem_bloqueado.php", 
						data: {id_curso: id_curso_escolhido, array_turmas: array_turmas_esquerda, sem_bloqueado: semestre_bloqueado},
						success: function(result) {
							var array = result.split(',');
							//alert("Cursos: " + array);
							
							var vazia0 = document.createElement('option');
							vazia0.value = 0;
							vazia0.text = "";
							dropdown_uc.options.add(vazia0);
							
							if(array.length > 1){
							
								for(i = 0; i < array.length; i = i + 2){
									var opt = document.createElement('option');
									opt.value = array[i];
									opt.text = array[i + 1];
									dropdown_uc.options.add(opt);
								} 
						
							}
					
						}
					});
					
				}
				else{
		
					$.ajax ({
						type: "POST",
						url: "processamento/mostrarUCsCurso_por_atribuir.php", 
						data: {id_curso: id_curso_escolhido, array_turmas: array_turmas_esquerda},
						success: function(result) {
							var array = result.split(',');
							//alert("Cursos: " + array);
							
							var vazia0 = document.createElement('option');
							vazia0.value = 0;
							vazia0.text = "";
							dropdown_uc.options.add(vazia0);
							
							if(array.length > 1){
							
								for(i = 0; i < array.length; i = i + 2){
									var opt = document.createElement('option');
									opt.value = array[i];
									opt.text = array[i + 1];
									dropdown_uc.options.add(opt);
								} 
						
							}
					
						}
					});
					
				}
		
			}
		});
		
	}
	
}

function mostrarComponentesUC_adicionar_uc(){
	
	limparDadosDireita_abaixo_uc_adicionar_uc();
	
	const select_ucs = document.getElementById("adicionar_uc_disciplina");
	var id_uc_escolhida = select_ucs.value;
	
	const select_componentes = document.getElementById("adicionar_uc_componente");
	
	const array_turmas_esquerda = verTurmasEsquerda();
	
	if(id_uc_escolhida != 0){
	
		$.ajax ({
		type: "POST",
		url: "processamento/mostrarComponentesUC_por_atribuir.php", 
		data: {id_uc: id_uc_escolhida, array_turmas: array_turmas_esquerda},
		success: function(result) {
			var array = result.split(',');
			//alert("Componentes: " + array);
				
			var vazia = document.createElement('option');
			vazia.value = 0;
			vazia.text = "";
			select_componentes.options.add(vazia);
				
			if(array.length > 1){	
				
				for(i = 0; i < array.length; i = i + 2){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i + 1];
					select_componentes.options.add(opt);
				} 
				
			}
			
		}
		});
	
	}
}

function mostrarTurmas_adicionar_uc(){
	
	limparDadosDireita_abaixo_comp_adicionar_uc();
	
	const select_componentes = document.getElementById("adicionar_uc_componente");
	var id_componente_escolhida = select_componentes.value;
	
	const div_turmas = document.getElementById("adicionar_uc_turmas");
	
	const array_turmas_esquerda = verTurmasEsquerda();
	
	if(id_componente_escolhida != 0){
		
		$.ajax ({
		type: "POST",
		url: "processamento/mostrarTurmasComponente_por_atribuir.php", 
		data: {id_componente: id_componente_escolhida, array_turmas: array_turmas_esquerda},
		success: function(result) {
			var array = result.split(',');
			//alert("Turmas: " + array);
			
			if(array.length > 1){
				
				for(i = 0; i < array.length; i = i + 3){
				
					var select_turma = document.createElement("input");
					select_turma.type = "checkbox";
					select_turma.class = "checkbox";
					select_turma.id = 'turma_' + array[i];
					select_turma.name = 'turma_' + array[i];
					select_turma.value = 'turma_' + array[i];
					select_turma.css = 'margin-left:5px;';
					select_turma.css = 'margin-right:10px;';
					select_turma.setAttribute("data_id-turma",array[i]);
					select_turma.setAttribute("data_nome-turma",array[i + 1]);
					
					var label = document.createElement('label');
					label.htmlFor = 'turma_' + array[i];
					label.style.fontWeight = 'bold';
					label.style.marginLeft = '5px';
					label.appendChild(document.createTextNode(' ' + array[i + 1]));
					
					var paragrafo = document.createElement("br");
					
					if(array[i + 2] > 0){
						select_turma.setAttribute('onclick', 'bloquearOutrasTurmas_adicionar_uc(' + array[i] + ')');
						if(array[i + 2] == 1){
							select_turma.setAttribute("data_id-juncao",1);
						}
						else{
							select_turma.setAttribute("data_id-juncao",2);
						}
					}
					
					div_turmas.appendChild(select_turma);
					div_turmas.appendChild(label);
					if(array[i + 2] > 0){
						var join_imagem = document.createElement("img");
						join_imagem.class = "juncao";
						if(array[i + 2] == 1){
							join_imagem.setAttribute("src","http://localhost/apoio_utc/images/join.png");
							join_imagem.title = "Esta turma está numa junção";
						}
						else{
							join_imagem.setAttribute("src","http://localhost/apoio_utc/images/join_laranja.png");
							join_imagem.title = "Esta turma está numa junção com turmas de diferentes componentes/UC's";
						}
						join_imagem.style.height = '15px';
						join_imagem.style.width = '15px';
						join_imagem.style.marginLeft = '5px';
						div_turmas.appendChild(join_imagem);
					}
					div_turmas.appendChild(paragrafo);
				}
				
				var num_turmas_sem_juncoes = 0;
				for(i = 0; i < array.length; i = i + 3){
					if(array[i + 2] == 0){
						num_turmas_sem_juncoes = num_turmas_sem_juncoes + 1;
					}
				}
				
				if((array.length > 3) && (num_turmas_sem_juncoes > 0)){
					
					var select_juntar = document.createElement("input");
					select_juntar.type = "checkbox";
					select_juntar.class = "checkbox";
					select_juntar.id = 'select_juntar';
					select_juntar.name = 'select_juntar';
					select_juntar.value = 'select_juntar';
					select_juntar.style.marginLeft = '80px';
					select_juntar.css = 'margin-right:10px;';
		
					var label_juntar = document.createElement('label');
					label_juntar.htmlFor = 'select_juntar';
					label_juntar.style.marginLeft = '5px';
					label_juntar.appendChild(document.createTextNode(' (Juntar Turmas)'));
					
					div_turmas.appendChild(select_juntar);
					div_turmas.appendChild(label_juntar);
				}
				
			}
		}
		});
		
	}
	
}

function bloquearOutrasTurmas_adicionar_uc(id_turma){
	
	const div_turmas = document.getElementById("adicionar_uc_turmas");
	
	$('#adicionar_uc_turmas').find('input').each(function () {
		if(this.getAttribute("data_id-turma") == id_turma && this.getAttribute("data_id-juncao") != null){
			if(Boolean(this.checked)){
				
				$('#adicionar_uc_turmas').find('input').each(function () {
					if(this.getAttribute("data_id-juncao") != null && this.getAttribute("data_id-turma") != id_turma){
						//alert("ID: " + this.getAttribute("data_id-turma"));
						this.style.visibility = "hidden";
					}
				});
				
			}
			else{
				
				$('#adicionar_uc_turmas').find('input').each(function () {
					if(this.getAttribute("data_id-juncao") != null && this.getAttribute("data_id-turma") != id_turma){
						//alert("ID: " + this.getAttribute("data_id-turma"));
						this.style.visibility = "visible";
					}
				});
				
			}
		}
	});
}

function verificarErro1_adicionar_uc(){
	//alert("Verificar se o utilizador selecionou pelo menos 1 turma!");
	
	const select_componente = document.getElementById("adicionar_uc_componente");
	var id_componente = select_componente.value;
	
	const atribuir_uc_turma_ja_juncao = document.getElementById("atribuir_uc_turma_ja_juncao");
	
	var juntar = false;
	
	const select_juntar = document.getElementById("select_juntar");
	if(select_juntar != null){
		if(select_juntar.checked){
			juntar = true;
		}
	}
	
	const array_inputs = [];
	
	var ja_existe_juncao_normal = false;
	var ja_existe_juncao_composta = false;

	$('#adicionar_uc_turmas').find('input').each(function () {
		if(this.getAttribute("data_id-turma") != null){
			if(Boolean(this.checked)){
				array_inputs.push(this.getAttribute("data_id-turma"));
				array_inputs.push(this.getAttribute("data_nome-turma"));
				if(this.getAttribute("data_id-juncao") != null){
					if(this.getAttribute("data_id-juncao") == 1){
						ja_existe_juncao_normal = true;
					}
					if(this.getAttribute("data_id-juncao") == 2){
						ja_existe_juncao_composta = true;
					}
				}
			}	
		}
	});
	
	//alert("Array: " + array_inputs.length);
	if(array_inputs.length == 0){
		alert("Selecione pelo menos uma turma!");
	}
	else{
		//alert("Adicionar turma(s)!");
		
		if(juntar == true && !Boolean(ja_existe_juncao_normal) && !Boolean(ja_existe_juncao_composta)){
			var nome_juncao = prompt("Introduza um nome para a junção: ");
			if(nome_juncao.length < 10){
				while(nome_juncao.length < 10){
					alert("Introduza um nome válido! (10 caracteres)");
					nome_juncao = prompt("Introduza um nome para a junção: ");
				}
			}
		}
		
		array_ids = [];
		
		for(i = 0; i < array_inputs.length; i = i + 2){
			var id_turma = array_inputs[i];
			array_ids.push(id_turma);
		}
		
		array_nomes = [];
		
		for(i = 0; i < array_inputs.length; i = i + 2){
			var nome_turma = array_inputs[i + 1];
			array_nomes.push(nome_turma);
		}
		
		const div_esquerda = document.getElementById("adicionar_uc_turmas_temp");
		
		const uc_dropdown = document.getElementById("adicionar_uc_disciplina");
		var id_uc_escolhida = uc_dropdown.value;
		
		const componente_dropdown = document.getElementById("adicionar_uc_componente");
		var id_comp_escolhida = componente_dropdown.value;
		
		var texto = document.createElement('label');
		
		$.ajax ({
			type: "POST",
			url: "processamento/verNomeUC.php", 
			data: {id_uc: id_uc_escolhida},
			success: function(result) {
				var nome_uc = result;
				
				$.ajax ({
				type: "POST",
				url: "processamento/verNomeComponente.php", 
				data: {id_componente: id_comp_escolhida},
				success: function(result) {
					var nome_componente = result;
					
					if(juntar == true){
						texto.innerHTML = "<b>" + nome_uc + " - " + nome_componente + ": </b>" 
										+ "<img src='http://localhost/apoio_utc/images/join.png' title='Estas turmas vão estar numa junção: " + nome_juncao + "'  style='width:15px; height:15px; margin-left:2px;'>"
										+ "<br>"
										+ "<i class='material-icons' style='vertical-align:middle;'>people</i>"
										+ "<text data_array-turmas='" + array_ids + "' data_id-componente='" + id_comp_escolhida + "' style='margin-left:5px;'>" + array_nomes + "</text> "
										+ "<br>";
					}
					else if(ja_existe_juncao_normal == true){
						texto.innerHTML = "<b>" + nome_uc + " - " + nome_componente + ": </b>" 
										+ "<br>"
										+ "<i class='material-icons' style='vertical-align:middle;'>people</i>"
										+ "<img src='http://localhost/apoio_utc/images/join.png' title='Há uma junção existente'  style='width:15px; height:15px; margin-left:5px;'>"
										+ "<text data_array-turmas='" + array_ids + "' data_id-componente='" + id_comp_escolhida + "' style='margin-left:5px;'>" + array_nomes + "</text> "
										+ "<br>";
						atribuir_uc_turma_ja_juncao.style.visibility = "visible";
					}
					else if(ja_existe_juncao_composta == true){
						texto.innerHTML = "<b>" + nome_uc + " - " + nome_componente + ": </b>" 
										+ "<br>"
										+ "<i class='material-icons' style='vertical-align:middle;'>people</i>"
										+ "<img src='http://localhost/apoio_utc/images/join_laranja.png' title='Há uma junção existente'  style='width:15px; height:15px; margin-left:5px;'>"
										+ "<text data_array-turmas='" + array_ids + "' data_id-componente='" + id_comp_escolhida + "' style='margin-left:5px;'>" + array_nomes + "</text> "
										+ "<br>";
						atribuir_uc_turma_ja_juncao.style.visibility = "visible";
					}
					else{
						texto.innerHTML = "<b>" + nome_uc + " - " + nome_componente + ": </b><br>"
										+ "<i class='material-icons' style='vertical-align:middle;'>people</i>"
										+ "<text data_array-turmas='" + array_ids + "' data_id-componente='" + id_comp_escolhida + "' style='margin-left:5px;'>" + array_nomes + "</text><br>";
					}
					texto.style.marginLeft = '5px';
					texto.style.marginRight = '100px';
					texto.style.marginBottom = '15px';
					
					texto.setAttribute("data-id_uc",id_uc_escolhida);
					texto.setAttribute("data-id_componente",id_comp_escolhida);
					texto.setAttribute("data-ids_turmas",array_ids);
					texto.setAttribute("data-juntar",juntar);
					if(juntar == true && !Boolean(ja_existe_juncao_normal) && !Boolean(ja_existe_juncao_composta)){
						texto.setAttribute("data-juntar",nome_juncao);
					}
							
					div_esquerda.appendChild(texto);
					
					//Limpar todas opções da direita
					limparDadosDireita_adicionar_uc();
					
					const dropdown_utc = document.getElementById("adicionar_uc_utc");
					dropdown_utc.selectedIndex=0;
					}
				});
				
			}
		});
		
		
		
	}
	//alert("Nº turmas selecionadas: " + inputs.length);
	
}

function atribuirUCs_adicionar_uc(id_utilizador){
	
	var num_ucs = 0;
	
	$('#adicionar_uc_turmas_temp').find('label').each(function () {
		num_ucs = num_ucs + 1;
	});
	
	array_labels = [];
	
	var counter = 0;
	$('#adicionar_uc_turmas_temp').find('label').each(function () {
		array_labels.push(this.getAttribute("data-id_uc"));
		array_labels.push(this.getAttribute("data-id_componente"));
		array_labels.push(this.getAttribute("data-ids_turmas"));
		array_labels.push(this.getAttribute("data-juntar"));
		
		counter = counter + 1;
		if(counter != num_ucs){
			array_labels.push("_");
		}
		
	});
	
	$.ajax ({
		type: "POST",
		url: "processamento/edDSD_atribuir_ucs.php", 
		data: {dados: array_labels, id_docente: id_utilizador},
		success: function(result) {
			//alert("Result: " + result);
			location.reload();
		}
	});				
					
	
	//alert("Dados: " + array_labels);
	
}

function verTurmasEsquerda(){
	
	const div_turmas_temporarias = document.getElementById("adicionar_uc_turmas_temp");
	
	const array_turmas_temporarias = [];
	
	$('#adicionar_uc_turmas_temp').find('text').each(function () {
		if(this.getAttribute("data_array-turmas") != null){
			var id_componente = this.getAttribute("data_id-componente");
			var array = this.getAttribute("data_array-turmas");
			var array_temp = array.split(",");
			
			for(i = 0; i < array_temp.length; i++){
				var id_turma = array_temp[i];
		
				array_turmas_temporarias.push(id_componente);
				array_turmas_temporarias.push(id_turma);
			}
			
		}
	});
	
	return array_turmas_temporarias;
	
}

function limparDadosDireita_adicionar_uc(){
	
	const dropdown_curso = document.getElementById("adicionar_uc_curso");
	const dropdown_uc = document.getElementById("adicionar_uc_disciplina");
	const dropdown_comp = document.getElementById("adicionar_uc_componente");
	
	removeOptions(dropdown_curso);
	removeOptions(dropdown_uc);
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("adicionar_uc_turmas");
	
	$('#adicionar_uc_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('img').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('br').each(function () {
		this.remove();
	});
	
}

function limparDadosDireita_abaixo_curso_adicionar_uc(){
	
	const dropdown_uc = document.getElementById("adicionar_uc_disciplina");
	const dropdown_comp = document.getElementById("adicionar_uc_componente");
	
	removeOptions(dropdown_uc);
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("adicionar_uc_turmas");
	
	$('#adicionar_uc_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('img').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('br').each(function () {
		this.remove();
	});
}

function limparDadosDireita_abaixo_uc_adicionar_uc(){
	
	const dropdown_comp = document.getElementById("adicionar_uc_componente");
	
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("adicionar_uc_turmas");
	
	$('#adicionar_uc_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('img').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('br').each(function () {
		this.remove();
	});
}

function limparDadosDireita_abaixo_comp_adicionar_uc(){

	const div_turmas = document.getElementById("adicionar_uc_turmas");
	
	$('#adicionar_uc_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('img').each(function () {
		this.remove();
	});
	
	$('#adicionar_uc_turmas').find('br').each(function () {
		this.remove();
	});
}


/*------------------------------ALTERAR DOCENTE COMPONENTE------------------------------*/
function editarComponente(id_componente, id_docente){
	//alert("Editar componente: " + id_componente + " do docente: " + id_docente);
	
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBody_edDSD_componente").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/DSD_editar_componente.php?id_componente=" + id_componente + "&id_docente=" + id_docente);
    xhttp.send();	
	
}

function alterarDocenteComponente(id_componente,id_docente_original){
	
	const dropdown_docente = document.getElementById("alterar_docente_componente");
	var docente_escolhido = dropdown_docente.value;
	var docente_escolhido_text = dropdown_docente.text;
	
	if(docente_escolhido != "nada_selecionado"){
		if(window.confirm("Tem a certeza que pretende alterar o docente desta componente?")){
		$.ajax ({
			type: "POST",
			url: "processamento/mudarDocenteComponente.php", 
			data: {id_componente: id_componente, id_docente_original: id_docente_original, id_docente_novo: docente_escolhido},
			success: function(result) {
				if(result == "Sucesso"){
					location.reload();
				}
				else{
					alert("Erro ao alterar docente: " + result);
				}
			},
			error: function(result) {
				alert("Erro ao alterar docente: " + result);
			}
		});
		}
	}
	else if($("#alterar_docente_componente option:selected").text() != ""){
		$('#edDSD_componente').modal('hide');
	}
	else{
		if(window.confirm("Pretende remover o docente desta componente?")){
			$.ajax ({
				type: "POST",
				url: "processamento/mudarDocenteComponenteNull.php", 
				data: {id_componente: id_componente, id_docente_original: id_docente_original},
				success: function(result) {
					if(result == "Sucesso"){
						location.reload();
					}
					else{
						alert("Erro ao alterar docente: " + result);
					}
				},
				error: function(result) {
					alert("Erro ao alterar docente: " + result);
				}
			});
		}
	}
	
}


/*------------------------------MANIPULAR TURMAS PRESENTES------------------------------*/
function gerarFormManipularTurma(id_docente, id_comp, id_turma, id_juncao){

	//alert("Docente: " + id_docente + " Comp: " + id_comp + " Turma: " + id_turma + " Junção: " + id_juncao);
	
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBody_edDSD_manipular_turma").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formAlterarDocente_edDSD.php?id_docente=" + id_docente + "&id_comp=" + id_comp + "&id_turma=" + id_turma + "&id_juncao=" + id_juncao);
    xhttp.send();
	
}

function bloquearOutrasJuncoes(id_juncao){
	
	$('#div_juncoes').find('input').each(function () {
		if(this.getAttribute("data_id-juncao") != null){
			if(this.checked){
				var id_juncao_selecionada = this.getAttribute("data_id-juncao");
				
				$('#div_juncoes').find('input').each(function () {
					if(this.getAttribute("data_id-juncao") != null && this.getAttribute("data_id-juncao") != id_juncao_selecionada){
						this.style.visibility = "hidden";
					}
				});
				
			}
			else{
				var id_juncao_selecionada = this.getAttribute("data_id-juncao");
				
				$('#div_juncoes').find('input').each(function () {
					if(this.getAttribute("data_id-juncao") != null && this.getAttribute("data_id-juncao") != id_juncao_selecionada){
						this.style.visibility = "visible";
					}
				});
			}
		}
	});
	
}

function atribuirDocente(id_componente, id_turma, id_docente_original){	
	
	const dropdown_docente = document.getElementById("edDSD_alterar_docente");
	var id_docente_escolhido = dropdown_docente.value;
	/*
	const card_body = document.getElementsByClassName("card-body")[0];
	var cartoes = document.getElementsByClassName("card_UC");
	*/
	
	var num_juncoes_selecionadas = 0;
	var num_turmas_individuais_selecionadas = 0;
	
	$('#div_juncoes').find('input').each(function () {
		if(this.getAttribute("data_id-juncao") != null){
			if(this.checked){
				num_juncoes_selecionadas += 1;
			}
		}
	});	
	
	$('#div_juncoes').find('input').each(function () {
		if(this.getAttribute("data_id-turma") != null){
			if(this.checked){
				num_turmas_individuais_selecionadas += 1;
			}
		}
	});	
	
	if(num_juncoes_selecionadas == 0 && num_turmas_individuais_selecionadas == 0){
		if(id_docente_original == id_docente_escolhido){
				$('#edDSD_manipular_turma').modal('hide');
		}
		else{
			//Mudar apenas na turma/comp selecionada
			$.ajax ({
				type: "POST",
				url: "processamento/atribuirDocente.php", 
				data: {id_componente: id_componente, id_turma: id_turma, id_docente: id_docente_escolhido},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao atribuir docente: " + result);
				}
			});
		}
	}
	else{
		
		var id_juncao_a_adicionar = 0;
		const array_ids_turmas = [];
		
		$('#div_juncoes').find('input').each(function () {
			if(this.checked){
				if(this.getAttribute("data_id-juncao") != null){
					id_juncao_a_adicionar = this.getAttribute("data_id-juncao");
				}
				else{
					array_ids_turmas.push(this.getAttribute("data_id-turma"));
					array_ids_turmas.push(this.getAttribute("data_id-componente"));
				}
			}
		});
		
		if((id_juncao_a_adicionar != 0) && (array_ids_turmas.length == 0)){
			//Adicionar esta turma à junção
			$.ajax ({
				type: "POST",
				url: "processamento/juntar_a_juncao.php", 
				data: {id_componente: id_componente, id_turma: id_turma, id_docente: id_docente_escolhido, id_juncao: id_juncao_a_adicionar},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao juntar turmas: " + result);
				}
			});
		}
		else if((id_juncao_a_adicionar != 0) && (array_ids_turmas.length != 0)){
			//Adicionar esta turma a as outras selecionadas à junção
			
			array_ids_turmas.push(id_turma,id_componente);
			
			//alert("Juntar turmas: " + array_ids_turmas + " à junção: " + id_juncao_a_adicionar);
			$.ajax ({
				type: "POST",
				url: "processamento/juntar_turmas_a_juncao.php", 
				data: {id_componente: id_componente, array_turmas: array_ids_turmas, id_docente: id_docente_escolhido, id_juncao: id_juncao_a_adicionar},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao juntar turmas: " + result);
				}
			});
			
		}
		else if((id_juncao_a_adicionar == 0) && (array_ids_turmas.length != 0)){
			//Criar uma junção com esta turma a(s) outra(s) selecionada(s)
			//alert("Criar junção com a turma: " + id_turma + " e as turmas: " + array_ids_turmas);
			
			array_ids_turmas.push(id_turma,id_componente);
			
			let nome_juncao = prompt("Introduza um nome para a junção: ");
			if(nome_juncao.length < 10){
				while(nome_juncao.length < 10){
					nome_juncao = window.prompt("Introduza um nome válido! (10 caracteres)");
				}
			}
		
			$.ajax ({
				type: "POST",
				url: "processamento/criar_juncao_turmas.php", 
				data: {nome_juncao: nome_juncao, array_turmas: array_ids_turmas, id_docente: id_docente_escolhido},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao criar junção: " + result);
				}
			});
			
		}
			
	}
}

function gerarFormCriarJuncao(id_juncao, id_turma, id_comp){
	//alert("Adicionar outros docentes que não: " + id_juncao + " na COMP:" + id_comp + " na TURMA: " + id_turma);
	$('#edDSD_manipular_turma').modal('hide');
	$('#edDSD_manipular_turma_criar_juncao').modal('show');
	if(id_juncao == null){
		id_juncao = 0;
	}
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBody_edDSD_manipular_turma_criar_juncao").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formCriarJuncaoedDSUC.php?id_juncao=" + id_juncao + "&id_turma=" + id_turma + "&id_comp=" + id_comp);
    xhttp.send();
}

function mostrarCursosUTC2(id_turma,id_componente){
	
	limparDadosDireita_caso_2();
	
	const dropdown_utc = document.getElementById("utc_caso_2");
	var id_utc_escolhida = dropdown_utc.value;
	
	const dropdown_cursos = document.getElementById("curso_caso_2");
		
	if(id_utc_escolhida != 0){
		//Mostrar a lista de UC's
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarCursosUTC_criar_juncao_2.php", 
			data: {id_utc: id_utc_escolhida, id_turma: id_turma, id_componente: id_componente},
			success: function(result) {
				var array = result.split(',');
				//alert("Cursos: " + array);
				
				var vazia = document.createElement('option');
				vazia.value = "0";
				vazia.text = "";
				dropdown_cursos.options.add(vazia);
				
				if(array.length > 1){
					for(i = 0; i < array.length; i = i + 2){
						var opt = document.createElement('option');
						opt.value = array[i];
						opt.text = array[i + 1];
						dropdown_cursos.options.add(opt);
					} 
				}
		
			}
		});
		
	}
}

function mostrarDisciplinasCurso2(id_turma,id_componente){
	
	limparDadosDireita_abaixo_curso_caso_2();
	
	const div_curso = document.getElementById("curso_caso_2");
	var id_curso_escolhido = div_curso.value;
	
	const dropdown_disciplinas = document.getElementById("disciplina_caso_2");
	
	if(id_curso_escolhido != 0){
	
		$.ajax ({
		type: "POST",
		url: "processamento/mostrarUCsCurso_criar_juncao_2.php", 
		data: {id_curso: id_curso_escolhido, id_turma: id_turma, id_componente: id_componente},
		success: function(result) {
			var array = result.split(',');
			//alert("UCs: " + array);
				
			var vazia = document.createElement('option');
			vazia.value = "0";
			vazia.text = "";
			dropdown_disciplinas.options.add(vazia);
				
			if(array.length > 1){		
				for(i = 0; i < array.length; i = i + 2){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i + 1];
					dropdown_disciplinas.options.add(opt);
				} 
			}
			
		}
		});
	
	}
	
}

function mostrarComponentesUC2(id_turma,id_componente){
	
	limparDadosDireita_abaixo_uc_caso_2();
	
	const dropdown_uc = document.getElementById("disciplina_caso_2");
	var id_uc_escolhida = dropdown_uc.value;
	
	var dropdown_componentes = document.getElementById("componente_caso_2");
	
	if(id_uc_escolhida != 0){
		//alert("UC: " + id_uc_escolhida + " COMP: " + id_comp);
		$.ajax ({
		type: "POST",
		url: "processamento/mostrarComponentesUC_criar_juncao_2.php", 
		data: {id_uc: id_uc_escolhida, id_turma: id_turma, id_componente: id_componente},
		success: function(result) {
			var array = result.split(',');
			//alert("Componentes: " + array);
				
			var vazia = document.createElement('option');
			vazia.value = "0";
			vazia.text = "";
			dropdown_componentes.options.add(vazia);
				
			if(array.length > 1){		
				for(i = 0; i < array.length; i = i + 2){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i + 1];
					dropdown_componentes.options.add(opt);
				} 
			}
			
		}
		});
	
	}
	
}

function mostrarTurmas2(id_turma,id_componente){

	limparDadosDireita_abaixo_comp_caso_2();
	
	const dropdown_curso = document.getElementById("curso_caso_2");
	var id_curso_escolhido = dropdown_curso.value;

	const dropdown_uc = document.getElementById("disciplina_caso_2");
	var id_uc_escolhida = dropdown_uc.value;
	
	const dropdown_componente = document.getElementById("componente_caso_2");
	var id_componente_escolhida = dropdown_componente.value;
	
	const div_turmas = document.getElementById("turmas_caso_2");
	
	if(id_componente_escolhida != 0){
		
	$.ajax ({
	type: "POST",
	url: "processamento/verAnoSemestreUC.php", 
	data: {id_uc: id_uc_escolhida},
	success: function(result) {
		var array = result.split(',');
		
		const ano = array[0];
		const semestre = array[1];
		
		$.ajax ({
		type: "POST",
		url: "processamento/verTurmasAnoSemestreComp_criar_juncao_2.php", 
		data: {ano: ano, semestre: semestre, id_componente: id_componente_escolhida, id_curso: id_curso_escolhido, id_turma: id_turma},
		success: function(result) {
			var array = result.split(',');
			//alert("Turmas: " + array);
			
			if(array.length > 1){
					
				for(i = 0; i < array.length; i = i + 3){
					
					var select_turma = document.createElement("input");
					select_turma.type = "checkbox";
					select_turma.class = "checkbox";
					select_turma.id = 'turma_' + array[i];
					select_turma.name = 'turma_' + array[i];
					select_turma.value = 'turma_' + array[i] + '_comp_' + array[i + 2];
					select_turma.css = 'margin-left:5px;';
					select_turma.css = 'margin-right:10px;';
					select_turma.setAttribute("data_id-turma",array[i]);
					select_turma.setAttribute("data_nome-turma",array[i + 1]);
					select_turma.setAttribute("data_id_comp",array[i + 2]);
					
					var label = document.createElement('label');
					label.htmlFor = 'turma_' + array[i];
					label.style.fontWeight = 'bold';
					label.style.marginLeft = '5px';
					label.appendChild(document.createTextNode(' ' + array[i + 1]));
					
					var paragrafo = document.createElement("br");
					
					div_turmas.appendChild(paragrafo);
					div_turmas.appendChild(select_turma);
					div_turmas.appendChild(label);
				}
				//Colocar as turmas com checkbox
			} 
			
		/*	const ano = array[0];
			const semestre = array[1]; */
			}
		});
		
		
	}
	});
	
	}
}

function verificarErro1Novo(id_turma_original,id_componente){
	var div_turmas = document.getElementById("turmas_caso_2");
	var turmas_outras = div_turmas.getElementsByTagName("input");
	//alert("TESTE: " + turmas_outras.length);
	var num_turmas_selecionadas = 0;
	
	for(i = 0; i < turmas_outras.length; i++){
		if(turmas_outras[i].checked){
			var id_turma = turmas_outras[i].getAttribute("data_id-turma");
			var id_comp = turmas_outras[i].getAttribute("data_id_comp");
			//alert("Turma " + id_turma + " comp " + id_comp + " selecionadas!");
			num_turmas_selecionadas = num_turmas_selecionadas + 1;
		}
	}
	
	if(num_turmas_selecionadas == 0){
		alert("Selecione pelo menos uma turma!");
	} 
	else{
		const array_turmas = [];
		var string_turmas = "";
		
		for(i = 0; i < turmas_outras.length; i++){
			if(turmas_outras[i].checked){
				var id_turma = turmas_outras[i].getAttribute("data_id-turma");
				var id_comp = turmas_outras[i].getAttribute("data_id_comp");
				array_turmas.push(id_turma,id_comp);
				
				var nome_turma = turmas_outras[i].getAttribute("data_nome-turma");
				if(string_turmas != ""){
					string_turmas += ', ' + nome_turma;
				}
				else{
					string_turmas += nome_turma;
				}
			}
		}
		//alert("Turmas: " + string_turmas);
		
		let nome_juncao = prompt("Introduza um nome para a junção: ");
		if(nome_juncao.length < 10){
			alert("Introduza um nome válido! (10 caracteres)");
		}
		else {
			$.ajax ({
			type: "POST",
			url: "processamento/criarJuncaoEdDsuc.php", 
			data: {id_turma: id_turma_original, id_componente: id_componente, array_turmas: array_turmas, nome_juncao: nome_juncao},
			success: function(result) {
				alert("Junção criada com sucesso!");
				location.reload();
			}
			});
		
		}
		
	}
}




function limparDadosDireita_caso_2(){
	
	const dropdown_curso = document.getElementById("curso_caso_2");
	const dropdown_uc = document.getElementById("disciplina_caso_2");
	const dropdown_comp = document.getElementById("componente_caso_2");
	
	removeOptions(dropdown_curso);
	removeOptions(dropdown_uc);
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("turmas_caso_2");
	
	$('#turmas_caso_2').find('input').each(function () {
		this.remove();
	});
	
	$('#turmas_caso_2').find('label').each(function () {
		this.remove();
	});
	
	$('#turmas_caso_2').find('br').each(function () {
		this.remove();
	});
	
}

function limparDadosDireita_abaixo_curso_caso_2(){
	
	const dropdown_uc = document.getElementById("disciplina_caso_2");
	const dropdown_comp = document.getElementById("componente_caso_2");
	
	removeOptions(dropdown_uc);
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("turmas_caso_2");
	
	$('#turmas_caso_2').find('input').each(function () {
		this.remove();
	});
	
	$('#turmas_caso_2').find('label').each(function () {
		this.remove();
	});
	
	$('#turmas_caso_2').find('br').each(function () {
		this.remove();
	});
}

function limparDadosDireita_abaixo_uc_caso_2(){
	
	const dropdown_comp = document.getElementById("componente_caso_2");
	
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("turmas_caso_2");
	
	$('#turmas_caso_2').find('input').each(function () {
		this.remove();
	});
	
	$('#turmas_caso_2').find('label').each(function () {
		this.remove();
	});
	
	$('#turmas_caso_2').find('br').each(function () {
		this.remove();
	});
}

function limparDadosDireita_abaixo_comp_caso_2(){

	const div_turmas = document.getElementById("turmas_caso_2");
	
	$('#turmas_caso_2').find('input').each(function () {
		this.remove();
	});
	
	$('#turmas_caso_2').find('label').each(function () {
		this.remove();
	});
	
	$('#turmas_caso_2').find('br').each(function () {
		this.remove();
	});
}


/*------------------------------MANIPULAR JUNÇÕES------------------------------*/
function editarJuncao(id_juncao){
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBody_edDSD_editar_juncao").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/verTurmasJuncaoEditar.php?id=" + id_juncao, true);
    xhttp.send();
}

function removerTurmasJuncao(id_juncao){
	
	const div_turmas = document.getElementById("DSD_editar_juncao_remover_turmas");
	
	const array_turmas_total = [];
	const array_turmas_selecionadas = [];
	const array_nomes_turmas_selecionadas = [];
	
	$('#DSD_editar_juncao_remover_turmas').find('input').each(function () {
		var id_turma_total = this.getAttribute("data_id-turma");
		array_turmas_total.push(id_turma_total);
		if(this.getAttribute("data_id-turma") != null){
			if(Boolean(this.checked)){
				var id_turma = this.getAttribute("data_id-turma");
				array_turmas_selecionadas.push(id_turma);
				var nome_turma = this.getAttribute("data_nome-turma");
				array_nomes_turmas_selecionadas.push(nome_turma);
			}	
		}
	});
	
	//alert("Turmas: " + array_turmas_selecionadas);
	
	if(array_turmas_selecionadas.length == 0){
		alert("Selecione pelo menos uma turma!");
	}
	else{
		if((array_turmas_selecionadas.length == array_turmas_total.length) || (array_turmas_selecionadas.length == array_turmas_total.length - 1)){
			if(window.confirm("Pretende eliminar a junção?")){
				$.ajax ({
				type: "POST",
				url: "processamento/removerTurmasJuncaoFinal.php", 
				data: {array_turmas: array_turmas_selecionadas, id_juncao: id_juncao},
				success: function(result) {
					location.reload();
					}
				}); 
			}
		}
		else{
			if(window.confirm("Pretende remover a(s) turma(s) " + array_nomes_turmas_selecionadas + " da junção?")){
				$.ajax ({
				type: "POST",
				url: "processamento/removerTurmasJuncaoFinal.php", 
				data: {array_turmas: array_turmas_selecionadas, id_juncao: id_juncao},
				success: function(result) {
					location.reload();
					}
				}); 
			}
		}
	}
	
}

function eliminarJuncao(id_juncao){
	if(window.confirm("Pretende eliminar esta junção?")){
		$.ajax ({
		type: "POST",
		url: "processamento/eliminarJuncao.php", 
		data: {id_juncao: id_juncao},
		success: function(result) {
			location.reload();
			}
		});
	}
}

function mostrarCursosUTC(id_juncao){
	
	limparDadosDireita();
	
	const dropdown_utc = document.getElementById("edDSUC_outros_utc");
	var id_utc_escolhida = dropdown_utc.value;
	
	const dropdown_cursos = document.getElementById("edDSUC_outros_curso");

	if(id_utc_escolhida != 0){
		//Mostrar a lista de UC's
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarCursosUTC_editar_juncao.php", 
			data: {id_utc: id_utc_escolhida, id_juncao: id_juncao},
			success: function(result) {
				var array = result.split(',');
				alert("Cursos: " + array);
				
				var vazia = document.createElement('option');
				vazia.value = "0";
				vazia.text = "";
				dropdown_cursos.options.add(vazia);
				
				if(array.length > 1){
					for(i = 0; i < array.length; i = i + 2){
						var opt = document.createElement('option');
						opt.value = array[i];
						opt.text = array[i + 1];
						dropdown_cursos.options.add(opt);
					} 
				}
		
			}
		});
		
	}
}

function mostrarDisciplinasCurso(id_juncao){
	
	limparDadosDireita_abaixo_curso();
	
	const div_curso = document.getElementById("edDSUC_outros_curso");
	var id_curso_escolhido = div_curso.value;
	
	const dropdown_disciplinas = document.getElementById("edDSUC_outros_disciplina");
	
	if(id_curso_escolhido != 0){
	
		$.ajax ({
		type: "POST",
		url: "processamento/mostrarUCsCurso_editar_juncao.php", 
		data: {id_curso: id_curso_escolhido, id_juncao: id_juncao},
		success: function(result) {
			var array = result.split(',');
			//alert("UCs: " + array);
				
			var vazia = document.createElement('option');
			vazia.value = "0";
			vazia.text = "";
			dropdown_disciplinas.options.add(vazia);
				
			if(array.length > 1){		
				for(i = 0; i < array.length; i = i + 2){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i + 1];
					dropdown_disciplinas.options.add(opt);
				} 
			}
			
		}
		});
	
	}
	
}

function mostrarComponentesUC(id_juncao){
	
	limparDadosDireita_abaixo_uc();
	
	const dropdown_uc = document.getElementById("edDSUC_outros_disciplina");
	var id_uc_escolhida = dropdown_uc.value;
	
	var dropdown_componentes = document.getElementById("edDSUC_outros_componente");
	
	if(id_uc_escolhida != 0){
		//alert("UC: " + id_uc_escolhida + " COMP: " + id_comp);
		$.ajax ({
		type: "POST",
		url: "processamento/mostrarComponentesUC_editar_juncao.php", 
		data: {id_uc: id_uc_escolhida, id_juncao: id_juncao},
		success: function(result) {
			var array = result.split(',');
			//alert("Componentes: " + array);
				
			var vazia = document.createElement('option');
			vazia.value = "0";
			vazia.text = "";
			dropdown_componentes.options.add(vazia);
				
			if(array.length > 1){		
				for(i = 0; i < array.length; i = i + 2){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i + 1];
					dropdown_componentes.options.add(opt);
				} 
			}
			
		}
		});
	
	}
	
}

function mostrarTurmas(id_juncao){

	limparDadosDireita_abaixo_comp();
	
	const dropdown_curso = document.getElementById("edDSUC_outros_curso");
	var id_curso_escolhido = dropdown_curso.value;

	const dropdown_uc = document.getElementById("edDSUC_outros_disciplina");
	var id_uc_escolhida = dropdown_uc.value;
	
	const dropdown_componente = document.getElementById("edDSUC_outros_componente");
	var id_componente_escolhida = dropdown_componente.value;
	
	const div_turmas = document.getElementById("div_turmas_outros_turmas");
	
	if(id_componente_escolhida != 0){
		
	$.ajax ({
	type: "POST",
	url: "processamento/verAnoSemestreUC.php", 
	data: {id_uc: id_uc_escolhida},
	success: function(result) {
		var array = result.split(',');
		
		const ano = array[0];
		const semestre = array[1];
		
		$.ajax ({
		type: "POST",
		url: "processamento/verTurmasAnoSemestreComp_editar_juncao.php", 
		data: {ano: ano, semestre: semestre, id_componente: id_componente_escolhida, id_curso: id_curso_escolhido, id_juncao: id_juncao},
		success: function(result) {
			var array = result.split(',');
			//alert("Turmas: " + array);
			
			if(array.length > 1){
					
				for(i = 0; i < array.length; i = i + 3){
					
					var select_turma = document.createElement("input");
					select_turma.type = "checkbox";
					select_turma.class = "checkbox";
					select_turma.id = 'turma_' + array[i];
					select_turma.name = 'turma_' + array[i];
					select_turma.value = 'turma_' + array[i] + '_comp_' + array[i + 2];
					select_turma.css = 'margin-left:5px;';
					select_turma.css = 'margin-right:10px;';
					select_turma.setAttribute("data_id-turma",array[i]);
					select_turma.setAttribute("data_nome-turma",array[i + 1]);
					select_turma.setAttribute("data_id_comp",array[i + 2]);
					
					var label = document.createElement('label');
					label.htmlFor = 'turma_' + array[i];
					label.style.fontWeight = 'bold';
					label.style.marginLeft = '5px';
					label.appendChild(document.createTextNode(' ' + array[i + 1]));
					
					var paragrafo = document.createElement("br");
					
					div_turmas.appendChild(paragrafo);
					div_turmas.appendChild(select_turma);
					div_turmas.appendChild(label);
				}
				//Colocar as turmas com checkbox
			} 
			
		/*	const ano = array[0];
			const semestre = array[1]; */
			}
		});
		
		
	}
	});
	
	}
}

function verificarErro1(id_juncao){
	var div_turmas = document.getElementById("div_turmas_outros_turmas");
	var turmas_outras = div_turmas.getElementsByTagName("input");
	//alert("TESTE: " + turmas_outras.length);
	var num_turmas_selecionadas = 0;
	
	for(i = 0; i < turmas_outras.length; i++){
		if(turmas_outras[i].checked){
			var id_turma = turmas_outras[i].getAttribute("data_id-turma");
			var id_comp = turmas_outras[i].getAttribute("data_id_comp");
			//alert("Turma " + id_turma + " comp " + id_comp + " selecionadas!");
			num_turmas_selecionadas = num_turmas_selecionadas + 1;
		}
	}
	
	if(num_turmas_selecionadas == 0){
		alert("Selecione pelo menos uma turma!");
	} 
	else{
		const array_turmas = [];
		var string_turmas = "";
		
		for(i = 0; i < turmas_outras.length; i++){
			if(turmas_outras[i].checked){
				var id_turma = turmas_outras[i].getAttribute("data_id-turma");
				var id_comp = turmas_outras[i].getAttribute("data_id_comp");
				array_turmas.push(id_turma,id_comp);
				
				var nome_turma = turmas_outras[i].getAttribute("data_nome-turma");
				if(string_turmas != ""){
					string_turmas += ', ' + nome_turma;
				}
				else{
					string_turmas += nome_turma;
				}
			}
		}
		
		if(window.confirm("Pretende adicionar a(s) turma(s) " + string_turmas + " à junção?")){
		
			$.ajax ({
			type: "POST",
			url: "processamento/adicionarTurmasJuncao.php", 
			data: {id_juncao: id_juncao,array_turmas: array_turmas},
			success: function(result) {

				alert("Turma(s) adicionada(s) com sucesso!");
				location.reload();

			}
			});
		
		}
		
	}
}

function limparDadosDireita(){
	
	const dropdown_curso = document.getElementById("edDSUC_outros_curso");
	const dropdown_uc = document.getElementById("edDSUC_outros_disciplina");
	const dropdown_comp = document.getElementById("edDSUC_outros_componente");
	
	removeOptions(dropdown_curso);
	removeOptions(dropdown_uc);
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("div_turmas_outros_turmas");
	
	$('#div_turmas_outros_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#div_turmas_outros_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#div_turmas_outros_turmas').find('br').each(function () {
		this.remove();
	});
	
}

function limparDadosDireita_abaixo_curso(){
	
	const dropdown_uc = document.getElementById("edDSUC_outros_disciplina");
	const dropdown_comp = document.getElementById("edDSUC_outros_componente");
	
	removeOptions(dropdown_uc);
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("div_turmas_outros_turmas");
	
	$('#div_turmas_outros_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#div_turmas_outros_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#div_turmas_outros_turmas').find('br').each(function () {
		this.remove();
	});
}

function limparDadosDireita_abaixo_uc(){
	
	const dropdown_comp = document.getElementById("edDSUC_outros_componente");
	
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("div_turmas_outros_turmas");
	
	$('#div_turmas_outros_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#div_turmas_outros_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#div_turmas_outros_turmas').find('br').each(function () {
		this.remove();
	});
}

function limparDadosDireita_abaixo_comp(){

	const div_turmas = document.getElementById("div_turmas_outros_turmas");
	
	$('#div_turmas_outros_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#div_turmas_outros_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#div_turmas_outros_turmas').find('br').each(function () {
		this.remove();
	});
}

/*------------------------------MANIPULAR TURMAS POR ATRIBUIR------------------------------*/

function mostrarTurmasEmFaltaComponente(id_componente){
	//alert("Ver turmas que faltam: " + id_componente);
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBody_edDSD_ver_turmas_em_falta").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/DSD_ver_turmas_em_falta.php?id_componente=" + id_componente, true);
    xhttp.send();	
}

function esconderOutrasJuncoes_mais_turmas(id_juncao){
	
	$('#manipular_turmas_turmas').find('input').each(function () {
		if(this.getAttribute("data_id-juncao") == id_juncao){
			if(Boolean(this.checked)){
				
				$('#manipular_turmas_turmas').find('input').each(function () {
					if(this.getAttribute("data_id-juncao") != null && this.getAttribute("data_id-juncao") != id_juncao){
						this.style.visibility = "hidden";
					}
				});
				
				
			}
			
			else{
				$('#manipular_turmas_turmas').find('input').each(function () {
					if(this.getAttribute("data_id-juncao") != id_juncao && this.getAttribute("data_id-juncao") != null){
						this.style.visibility = "visible";
					}
				});
			}
			
		}
	});
	
}

function atribuirTurmas_em_falta(id_componente){
	
	const id_docente = <?php echo $idDocente ?>;
	var num_turmas_selecionadas = 0;
	var num_juncoes_selecionadas = 0;
	
	$('#manipular_turmas_turmas').find('input').each(function () {
		if(this.getAttribute("data_id-turma") != null){
			if(this.checked){
				num_turmas_selecionadas += 1;
			}
		}
	});
	
	$('#manipular_turmas_turmas').find('input').each(function () {
		if(this.getAttribute("data_id-juncao") != null){
			if(this.checked){
				num_juncoes_selecionadas += 1;
			}
		}
	});
	
	if(num_turmas_selecionadas == 0 && num_juncoes_selecionadas == 0){
		alert("Selecione pelo menos uma turma!");
	}
	else if(num_turmas_selecionadas == 0 && num_juncoes_selecionadas > 0){
		//Atribuir todas as turmas da junção selecionada
		
		var juntar_checkbox = document.getElementById("atribuirTurmas_em_falta_juntar");
		if(juntar_checkbox != null){
			var juntar = false;
			if(juntar_checkbox.checked){
				juntar = true;
			}
			
			if(Boolean(juntar) == true){
				alert("Selecione pelo menos outra turma!");
			}
			else{ 
				var id_juncao = 0;
				
				$('#manipular_turmas_turmas').find('input').each(function () {
					if(this.getAttribute("data_id-juncao") != null){
						if(this.checked){
							id_juncao = this.getAttribute("data_id-juncao");
						}
					}
				});
				
				const array_turmas_selecionadas = [];
				$('#div_' + id_juncao).find('text').each(function () {
					if(this.getAttribute("data_id-turma") != null){
						array_turmas_selecionadas.push(this.getAttribute("data_id-turma"));
					}
				});
				
				//alert("Turmas: " + array_turmas_selecionadas);
				
				$.ajax ({
					type: "POST",
					url: "processamento/edDSD_atribuir_turmas_normal.php", 
					data: {id_componente: id_componente, id_docente: id_docente,array_turmas: array_turmas_selecionadas},
					success: function(result) {
						//alert("Result: " + result);
						location.reload();
					}
				});	
			} 
		}
		else{
			var id_juncao = 0;
				
				$('#manipular_turmas_turmas').find('input').each(function () {
					if(this.getAttribute("data_id-juncao") != null){
						if(this.checked){
							id_juncao = this.getAttribute("data_id-juncao");
						}
					}
				});
				
				const array_turmas_selecionadas = [];
				$('#div_' + id_juncao).find('text').each(function () {
					if(this.getAttribute("data_id-turma") != null){
						array_turmas_selecionadas.push(this.getAttribute("data_id-turma"));
					}
				});
				
				//alert("Turmas: " + array_turmas_selecionadas);
				
				$.ajax ({
					type: "POST",
					url: "processamento/edDSD_atribuir_turmas_normal.php", 
					data: {id_componente: id_componente, id_docente: id_docente,array_turmas: array_turmas_selecionadas},
					success: function(result) {
						//alert("Result: " + result);
						location.reload();
					}
				});
		}
	}
	else if(num_turmas_selecionadas > 0 && num_juncoes_selecionadas == 0){
		
		const array_turmas_selecionadas = [];
		$('#manipular_turmas_turmas').find('input').each(function () {
			if(this.getAttribute("data_id-turma") != null){
				if(this.checked){
					array_turmas_selecionadas.push(this.getAttribute("data_id-turma"));
				}
			}
		});
		
		if(document.getElementById("atribuirTurmas_em_falta_juntar") != null){
			if(document.getElementById("atribuirTurmas_em_falta_juntar").checked){
				
				if(num_turmas_selecionadas < 2){
					alert("Selecione pelo menos duas turmas para juntar!");
				}
				else{

					let nome_juncao = window.prompt("Introduza um nome para a junção: ");
					if(nome_juncao.length < 10){
						while(nome_juncao.length < 10){
							nome_juncao = window.prompt("Introduza um nome válido! (10 caracteres)");
						}
					}
						
					$.ajax ({
						type: "POST",
						url: "processamento/edDSD_criar_juncao.php", 
						data: {id_componente: id_componente, id_docente: id_docente, array_turmas: array_turmas_selecionadas, nome_juncao: nome_juncao},
						success: function(result) {
							//alert("Result: " + result);
							location.reload();
						}
					});
				
				}

			}
			else{
				$.ajax ({
					type: "POST",
					url: "processamento/edDSD_atribuir_turmas_normal.php", 
					data: {id_componente: id_componente, id_docente: id_docente,array_turmas: array_turmas_selecionadas},
					success: function(result) {
						//alert("Result: " + result);
						location.reload();
					}
				});	
			}
			
		}
		else{
			//Turma individual
			//alert("TURMAS: " + array_turmas_selecionadas);
			//alert("Atribuir turmas normalmente individualmente!");
			
			$.ajax ({
				type: "POST",
				url: "processamento/edDSD_atribuir_turmas_normal.php", 
				data: {id_componente: id_componente, id_docente: id_docente,array_turmas: array_turmas_selecionadas},
				success: function(result) {
					//alert("Result: " + result);
					location.reload();
				}
			});	
			
		}

	}
	else{
		
		const array_turmas_selecionadas = [];
		$('#manipular_turmas_turmas').find('input').each(function () {
			if(this.getAttribute("data_id-turma") != null){
				if(this.checked){
					array_turmas_selecionadas.push(this.getAttribute("data_id-turma"));
				}
			}
		});
		
		var id_juncao_selecionada = 0;
						
		$('#manipular_turmas_turmas').find('input').each(function () {
			if(this.getAttribute("data_id-juncao") != null){
				if(this.checked){
					id_juncao_selecionada = this.getAttribute("data_id-juncao");
				}
			}
		});
		
		if(document.getElementById("atribuirTurmas_em_falta_juntar").checked){
			
			//Atribuir turmas selecionadas à junçao selecionada
			
			if(num_turmas_selecionadas < 1){
				alert("Selecione pelo menos uma turma para adicionar à junção!");
			}
			else{
				
				//alert("Adicionar turma(s) a junção existente: " + id_juncao_selecionada);
				
				$.ajax ({
					type: "POST",
					url: "processamento/edDSD_adicionar_turmas_a_juncao.php", 
					data: {id_componente: id_componente, id_docente: id_docente, array_turmas: array_turmas_selecionadas, id_juncao: id_juncao_selecionada},
					success: function(result) {
						//alert("Result: " + result);
						location.reload();
					}
				});
			}
		
		}
		else{
			//Atribuir as turmas individualmente
			
			//Ver primeiro as turmas da junção
			
			$.ajax ({
				type: "POST",
				url: "processamento/verTurmasJuncao.php", 
				data: {id_juncao: id_juncao_selecionada},
				success: function(result) {
					//alert("Turmas juncao: " + result);
					
					for(i = 0; i < result.length; i++){
						var id_turma_result = result[i];
						array_turmas_selecionadas.push(id_turma_result);
					}
					
					$.ajax ({
						type: "POST",
						url: "processamento/edDSD_atribuir_turmas_normal.php", 
						data: {id_componente: id_componente, id_docente: id_docente,array_turmas: array_turmas_selecionadas},
						success: function(result) {
							//alert("Result: " + result);
							location.reload();
						}
					});	
					
				}
			});	
			
		}
		
	}
	
}

/*------------------------------COMPONENTES EM FALTA------------------------------*/

function atribuirComponente(id_componente){
	
	const div_turmas = document.getElementById("div_" + id_componente);
	var inputs_div = div_turmas.getElementsByClassName("input");
	
	const id_docente = <?php echo $idDocente ?>;
	const array_turmas_a_atribuir = [];
	var juntar = 0;
	
	$('#div_' + id_componente).find('input').each(function () {
		if(this.getAttribute("data_id-turma") != null){
			if(Boolean(this.checked)){
				var id_turma = this.getAttribute("data_id-turma");
				array_turmas_a_atribuir.push(id_turma);
			}	
		}
	});
	
	//alert("Turmas a atribuir: " + array_turmas_a_atribuir);
	
	$('#div_' + id_componente).find('input').each(function () {
		if(this.getAttribute("data_join") != null){
			if(Boolean(this.checked)){
				//alert("Juntar");
				juntar = 1;
			}	
		}
	});
	
	if(array_turmas_a_atribuir.length == 0){
		alert("Selecione pelo menos uma turma!");
	}
	
	else{
	
		//1º CENÁRIO - Atribuir turma(s) sem criar junção
		if(juntar == 0){
			alert("Atribuir turmas normalmente!");
			$.ajax ({
				type: "POST",
				url: "processamento/edDSD_atribuir_turmas_normal.php", 
				data: {id_componente: id_componente, id_docente: id_docente,array_turmas: array_turmas_a_atribuir},
				success: function(result) {
					//alert("Result: " + result);
					location.reload();
				}
			});
		}
		
		//2º CENÁRIO - Criar junção com as turmas selecionadas
		else{
			
			alert("Juntar!");
			
			//Verificar se o utilizador selecionou pelo menos duas turmas
			if(array_turmas_a_atribuir.length < 2){
				alert("Selecione pelo menos duas turmas!");
			}
			else{
				var ja_tem_juncao = 0;
				//Se alguma das turmas selecionadas já estiver numa junção, simplesmente adicionar as outras a essa mesma junção
				$('#div_' + id_componente).find('input').each(function () {
					if(this.getAttribute("data_id-turma") != null && this.getAttribute("data_id-juncao") != null){
						if(Boolean(this.checked)){
							ja_tem_juncao = this.getAttribute("data_id-juncao");
						}	
					}
				});
				
				if(ja_tem_juncao != 0){
					//alert("Adicionar as outras turmas selecionadas à junção: " + ja_tem_juncao);
					$.ajax ({
						type: "POST",
						url: "processamento/edDSD_adicionar_turmas_a_juncao.php", 
						data: {id_componente: id_componente, id_docente: id_docente, array_turmas: array_turmas_a_atribuir, id_juncao: ja_tem_juncao},
						success: function(result) {
							//alert("Result: " + result);
							location.reload();
						}
					});
				}
				//Caso contrário, criar a junção
				else{
					var nome_juncao = prompt("Introduza um nome para a junção: ");
					if(nome_juncao.length < 10){
						while(nome_juncao.length < 10){
							alert("Introduza um nome válido! (10 caracteres)");
							nome_juncao = prompt("Introduza um nome para a junção: ");
						}
					}
							
					$.ajax ({
						type: "POST",
						url: "processamento/edDSD_criar_juncao.php", 
						data: {id_componente: id_componente, id_docente: id_docente, array_turmas: array_turmas_a_atribuir, nome_juncao: nome_juncao},
						success: function(result) {
							//alert("Result: " + result);
							location.reload();
						}
					});
					
				}
			}
		}
	}
	//alert("Atribuir componente: " + id_componente);
}

function bloquearOutrasTurmas(id_componente,id_turma,id_juncao){
	
	//alert("Junção: " + id_juncao + " selecionada, bloquear todas as outras turmas com junções!");
	
	$('#div_' + id_componente).find('input').each(function () {
		if(this.getAttribute("data_id-turma") == id_turma && this.getAttribute("data_id-juncao") != null){
			if(Boolean(this.checked)){
				
				$('#div_' + id_componente).find('input').each(function () {
					if(this.getAttribute("data_id-turma") != id_turma && this.getAttribute("data_id-juncao") == id_juncao){
						this.style.visibility = "hidden";
					}
				});
				
			}	
			else{
				
				$('#div_' + id_componente).find('input').each(function () {
					if(this.getAttribute("data_id-turma") != id_turma && this.getAttribute("data_id-juncao") == id_juncao){
						this.style.visibility = "visible";
					}
				});
				
			}
		}
	});
	
	
}

function verDadosJuncao(id_juncao){
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBody_edDSD_ver_dados_juncao").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/verTurmasJuncao.php?id=" + id_juncao, true);
    xhttp.send();
}

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
				
				var string_turmas =  "";
				
				for(i = 0; i < result.length; i = i + 4){
					string_turmas += "<img src='http://localhost/apoio_utc/images/apagar_icone.png' class='apagar_icone' onclick='apagarTurma()' style='width:15px; height:15px; margin-right:3px;'<br>";
					string_turmas += "<text style='font-size:14px;'>" + result[i + 1] + " (" + result[i + 2] + "ºA/" + result[i + 3] + "ºS)<br>";
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


function editarUC(id_disciplina){
	//alert("Editar UC: " + id_disciplina + " do docente: " + id_docente);
	
	window.location.href = "http://localhost/apoio_utc/edDSUC.php?i=" + id_disciplina;
	
}

/*
function mostrarDisciplinasCurso(){
	
	var dropdown_curso = document.getElementById("curso_dropdown");
	var dropdown_disciplinas = document.getElementById("disciplina_dropdown");
	var componentes_dropdown = document.getElementById("componentes_dropdown");
	
	var span_componentes = document.getElementById("componente_span");
	var span_turmas = document.getElementById("turma_span");
	
	//Limpar as opções anteriores
	removeOptions(dropdown_disciplinas);
	removeOptions(componentes_dropdown);
	
	
	//Ver as UC's já atribuídas ao docente
	var cartoes = document.getElementsByClassName("card_DSD");
	var array_ids_ucs = [];
	
	for(i = 0; i < cartoes.length; i++){
		var id_disciplina = cartoes[i].getAttribute("data-id_disciplina");
		array_ids_ucs.push(id_disciplina);
	}
	
	const id_curso_escolhido = dropdown_curso.value;
	
	//alert("Id curso escolhido: " + id_curso_escolhido);
	
	if(id_curso_escolhido != "nada_selecionado"){
		$.ajax ({
			type: "POST",
			url: "processamento/verDisciplinasCurso.php", 
			data: {id_curso: id_curso_escolhido, ids_ja: array_ids_ucs},
			dataType: "json",
			success: function(result) {
				//alert("Resultado: " + result);

				createOption(dropdown_disciplinas, "", "nada_selecionado");

				for(i = 0; i < result.length; i = i + 4){
					var texto = "(" + result[i + 2] + "ºA/" + result[i + 3] + "ºS) - " + result[i + 1];
					createOption(dropdown_disciplinas, texto, result[i]);
					//alert("ID: " + result[i] + " nome: " + result[1] + " ano: " + result[2] + " sem: " + result[3]);
				}
				
			}
		});
	}
	else{
		span_componentes.style.visibility = "hidden";
		span_turmas.style.visibility = "hidden";
	}
	
}

function mostrarComponentes(){
	
	var span_componentes = document.getElementById("componente_span");
	var span_turmas = document.getElementById("turma_span");
	
	var disciplina_dropdown = document.getElementById("disciplina_dropdown");
	const id_disciplina_escolhida = disciplina_dropdown.value;
	
	var componentes_dropdown = document.getElementById("componentes_dropdown");
	
	//Remover as opções anteriores
	removeOptions(componentes_dropdown);
	
	if(id_disciplina_escolhida != "nada_selecionado"){
		span_componentes.style.visibility = "visible";
		$.ajax ({
			type: "POST",
			url: "processamento/verComponentesDisciplina.php", 
			data: {id_disciplina: id_disciplina_escolhida},
			dataType: "json",
			success: function(result) {
				//alert("Resultado: " + result);

				createOption(componentes_dropdown, "", "nada_selecionado");

				for(i = 0; i < result.length; i = i + 3){
					var texto = result[i] + " - " + result[i + 1];
					createOption(componentes_dropdown, texto, result[i + 2]);
					//alert("ID: " + result[i] + " nome: " + result[1] + " ano: " + result[2] + " sem: " + result[3]);
				}
				
			}
		});
	}
	else{
		span_componentes.style.visibility = "hidden";
		span_turmas.style.visibility = "hidden";
	}
	
}

function mostrarTurmas(){
	
	var disciplina_dropdown = document.getElementById("disciplina_dropdown");
	const id_disciplina_escolhida = disciplina_dropdown.value;
	
	var componentes_dropdown = document.getElementById("componentes_dropdown");
	const id_componente_escolhido = componentes_dropdown.value;
	
	var span_turmas = document.getElementById("turma_span");
	span_turmas.innerHTML = "<h6>Turmas</h6>";
	
	if(id_componente_escolhido != "nada_selecionado"){
	span_turmas.style.visibility = "visible";
		$.ajax ({
			type: "POST",
			url: "processamento/verTurmasDisciplinaComponente.php", 
			data: {id_disciplina: id_disciplina_escolhida, id_componente: id_componente_escolhido},
			dataType: "json",
			success: function(result) {
				//alert("Resultado: " + result);

				//createOption(componentes_dropdown, "", "nada_selecionado");

				var texto = "";

				for(i = 0; i < result.length; i = i + 3){
					if(result[i + 2] == 0){
						texto += result[i + 1] + "<input type='checkbox' id='" + result[i] + "' style='width:15px; height:15px; margin-left:5px; margin-top:5px;'>";
					}
					else{
						texto += "<text style='opacity:50%;' title='" + result[i + 2] +"'>" + result[i + 1] + "</text><br>";
					}
				}
				
				var botao_adicionar = "<button type='button' style='border-radius:25px; margin-top:15px; margin-left:100px; margin-bottom:15px;' id='adicionarTurmaTemp' onclick='verificarErro1()' class='btn btn-primary'><b>Adicionar</b></button>";
				
				span_turmas.innerHTML += texto + botao_adicionar + "<br>";
				//createOption(componentes_dropdown, texto, result[i + 2]);
				//alert("ID: " + result[i] + " nome: " + result[1] + " ano: " + result[2] + " sem: " + result[3]);
			}
		});
	}
	else{
		span_turmas.style.visibility = "hidden";
	}
	
}
*/

function createOption(dropdown_disciplinas, text, value) {
	var opt = document.createElement('option');
    opt.value = value;
    opt.text = text;
    dropdown_disciplinas.options.add(opt);
}

function removeOptions(selectElement) {
	var i, L = selectElement.options.length - 1;
	for(i = L; i >= 0; i--) {
		selectElement.remove(i);
	}
}
</script>

<?php gerarHome2() ?>
