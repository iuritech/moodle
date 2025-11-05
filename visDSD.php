<?php
// Página de visualização de distribuição de serviço ordenada por docente (DSD)

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$ano_letivo_temp = explode("_",$_SESSION['bd']);
$ano_letivo = $ano_letivo_temp[2] . "_" . $ano_letivo_temp[3];

// ID do utilizador com sessão iniciada
$idUtilizador = (int) $_SESSION['id'];
$idUtilizadorAtual = (int) $_SESSION['id'];
$idAreaUtilizadorAtual = (int) $_SESSION['area_utilizador'];

$statement00 = mysqli_prepare($conn, "SELECT id_utc FROM utilizador WHERE id_utilizador = $idUtilizador;");
$statement00->execute();
$resultado00 = $statement00->get_result();
$linha00 = mysqli_fetch_assoc($resultado00);
    $id_utc_atual = $linha00["id_utc"];
	
	$statement001 = mysqli_prepare($conn, "SELECT dsd_1_sem, dsd_2_sem FROM utc WHERE id_utc = $id_utc_atual;");
	$statement001->execute();
	$resultado001 = $statement001->get_result();
	$linha001 = mysqli_fetch_assoc($resultado001);
		$dsd_1_sem = $linha001["dsd_1_sem"];
		$dsd_2_sem = $linha001["dsd_2_sem"];	
	
$permUTC = false;
$permArea = false;
$permAdmin = false;
$idUtcUtilizadorSessaoAtual = 0;
$idAreaUtilizadorSessaoAtual = 0;
if(isset($_SESSION['permUTC'])){
    $permUTC = true;
    
    $statement = mysqli_prepare($conn, "SELECT * FROM utc WHERE id_responsavel = ?");
    $statement->bind_param('i', $idUtilizador);
    $statement->execute();
    $resultado = $statement->get_result();
    if(mysqli_num_rows($resultado)!=0){
        $linha = mysqli_fetch_assoc($resultado);
        $idUtcUtilizadorSessaoAtual = (int) $linha["id_utc"];
    }
} else if(isset($_SESSION['permArea'])){
    $permArea = true;
    
    $statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
    $statement->bind_param('i', $idUtilizador);
    $statement->execute();
    $resultado = $statement->get_result();
    $linha = mysqli_fetch_assoc($resultado);
    $idAreaUtilizadorSessaoAtual = (int) $linha["id_area"];
}
if(isset($_SESSION['permAdmin'])){
    $permAdmin = true;
}

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
$statement->bind_param('i', $idUtilizador);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
    $idUTCUtilizador = $linha["id_utc"];

$statement00 = mysqli_prepare($conn, "SELECT id_responsavel, sigla_utc FROM utc WHERE id_utc = $idUTCUtilizador;");
	$statement00->execute();
	$resultado00 = $statement00->get_result();
	$linha00 = mysqli_fetch_assoc($resultado00);
		$id_responsavel_UTC_utilizador = $linha00["id_responsavel"];
		$sigla_UTC_utilizador = $linha00["sigla_utc"];
?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">

<div id="cover-spin"></div>
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">DSD (Docentes)</a></h6>
			<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 35px;"><b>Docentes</b></h3>
			<?php if($idUtilizadorAtual == $id_responsavel_UTC_utilizador){ ?>
			<img src="http://localhost/apoio_utc/images/excel_final.png" class="gerarExcel" onclick="gerarFicheiroExcelDSD(<?php echo $id_utc_atual ?>)" title="Gerar Excel: Docentes_<?php echo $sigla_UTC_utilizador ?>_<?php echo $ano_letivo; ?>" style="position:absolute; right:55px; top:45px; width:70px; height:70px; cursor:pointer;">
			<?php }?>
			
			<input type="checkbox" id="checkbox_utc" checked="true" onchange="filtrarCheckbox()" style="position:absolute; left: 70px; top: 125px; width: 17px; height: 17px;"><text style="position: absolute; left: 90px; top: 120px;"><b>Minha UTC</b></text>
			<input type="checkbox" id="checkbox_area" checked="true" onchange="filtrarCheckbox()" style="position:absolute; left: 190px; top: 125px; width: 17px; height: 17px;"><text style="position: absolute; left: 210px; top: 120px;"><b>Minha Área</b></text>
			
			<br><br>
			
			<?php 
			$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utc = $id_utc_atual AND id_area = $idAreaUtilizadorAtual ORDER BY nome;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_assoc($resultado)){
				$idUtilizador = $linha["id_utilizador"];
				$nomeUtilizador = $linha["nome"];
				$imgUtilizador = $linha["imagem_perfil"];
				$idUtcUtilizador = $linha["id_utc"];
				$idAreaUtilizador = $linha["id_area"];
				
				if(strlen($nomeUtilizador) > 20){
					$nomeUtilizador = substr_replace($nomeUtilizador,"...",(15-strlen($nomeUtilizador)));
				}
				
				$statement0 = mysqli_prepare($conn, "SELECT sigla_utc, id_responsavel FROM utc WHERE id_utc = $idUtcUtilizador");
				$statement0->execute();
				$resultado0 = $statement0->get_result();
				$linha0 = mysqli_fetch_assoc($resultado0);
				$siglaUtcUtilizador = $linha0["sigla_utc"];
				$idResponsavelUtc = $linha0["id_responsavel"];
				
				$statement1 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $idAreaUtilizador");
				$statement1->execute();
				$resultado1 = $statement1->get_result();
				$linha1 = mysqli_fetch_assoc($resultado1);
				$nomeAreaUtilizador = $linha1["nome"];
				
				$statement2 = mysqli_prepare($conn, "SELECT f.nome, COUNT(f.nome) FROM 
													funcao f INNER JOIN utilizador u ON f.id_funcao = u.id_funcao
													WHERE u.id_utilizador = $idUtilizador");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				
				$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT d.id_disciplina) FROM 
													disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina 
													INNER JOIN aula a ON c.id_componente = a.id_componente 
													WHERE a.id_componente IN
													(SELECT DISTINCT id_componente FROM aula WHERE id_docente = $idUtilizador);");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				$linha3 = mysqli_fetch_assoc($resultado3);
				$numeroDisciplinasUtilizador = $linha3["COUNT(DISTINCT d.id_disciplina)"];
				
				
				/*---------------------------------------------1º SEMESTRE-----------------------------------------------------*/

				$array_componentes_1_sem = array();
				$array_disciplinas_1_sem = array();

				$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $idUtilizador;");
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
													id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idUtilizador;");
				$statement6->execute();
				$resultado6 = $statement6->get_result();
				$linha6 = mysqli_fetch_assoc($resultado6);
					$num_turmas_docente_1_sem = $linha6["COUNT(DISTINCT id_turma)"];

					$horas_1_sem = 0;

					$statement7 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE 
													id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idUtilizador;");
					$statement7->execute();
					$resultado7 = $statement7->get_result();
					$linha7 = mysqli_fetch_assoc($resultado7);
						$num_juncoes_1_sem = $linha7["COUNT(DISTINCT id_juncao)"];
					
						if($num_juncoes_1_sem == 0){
							
							$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
													id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idUtilizador;");
							$statement8->execute();
							$resultado8 = $statement8->get_result();
							while($linha8 = mysqli_fetch_assoc($resultado8)){
								$id_comp = $linha8["id_componente"];
								
								$statement9 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
								$statement9->execute();
								$resultado9 = $statement9->get_result();
								$linha9 = mysqli_fetch_assoc($resultado9);
									$numero_horas_comp = $linha9["numero_horas"];
									
									$statement009 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
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
													id_componente IN ('$array_componentes_1_sem_final') AND id_docente = $idUtilizador;");
							$statement10->execute();
							$resultado10 = $statement10->get_result();
							while($linha10 = mysqli_fetch_assoc($resultado10)){
								$id_comp = $linha10["id_componente"];
							
								$statement11 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
								$statement11->execute();
								$resultado11 = $statement11->get_result();
								$linha11 = mysqli_fetch_assoc($resultado11);
									$numero_horas = $linha11["numero_horas"];
							
								$statement12 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NULL;");
								$statement12->execute();
								$resultado12 = $statement12->get_result();
								$linha12 = mysqli_fetch_assoc($resultado12);
									$numero_turmas_sem_juncao = $linha12["COUNT(DISTINCT id_turma)"];
								
									if($numero_turmas_sem_juncao == 0){	
										$statement13 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
										$statement13->execute();
										$resultado13 = $statement13->get_result();
										$linha13 = mysqli_fetch_assoc($resultado13);
											$numero_juncoes_comp = $linha13["COUNT(DISTINCT id_juncao)"];
											
											$statement14 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
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
										$statement14 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NOT NULL;");
										$statement14->execute();
										$resultado14 = $statement14->get_result();
										$linha14 = mysqli_fetch_assoc($resultado14);
											$numero_juncoes_comp = $linha14["COUNT(DISTINCT id_juncao)"];
											
											$statement15 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NOT NULL;");
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

				$statement30 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $idUtilizador;");
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
													id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idUtilizador;");
				$statement33->execute();
				$resultado33 = $statement33->get_result();
				$linha33 = mysqli_fetch_assoc($resultado33);
					$num_turmas_docente_2_sem = $linha33["COUNT(DISTINCT id_turma)"];

					$horas_2_sem = 0;

					$statement34 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE 
													id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idUtilizador;");
					$statement34->execute();
					$resultado34 = $statement34->get_result();
					$linha34 = mysqli_fetch_assoc($resultado34);
						$num_juncoes_2_sem = $linha34["COUNT(DISTINCT id_juncao)"];
					
						if($num_juncoes_2_sem == 0){
							
							$statement35 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE 
													id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idUtilizador;");
							$statement35->execute();
							$resultado35 = $statement35->get_result();
							while($linha35 = mysqli_fetch_assoc($resultado35)){
								$id_comp = $linha35["id_componente"];
								
								$statement36 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
								$statement36->execute();
								$resultado36 = $statement36->get_result();
								$linha36 = mysqli_fetch_assoc($resultado36);
									$numero_horas_comp = $linha36["numero_horas"];
									
									$statement365 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
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
													id_componente IN ('$array_componentes_2_sem_final') AND id_docente = $idUtilizador;");
							$statement37->execute();
							$resultado37 = $statement37->get_result();
							while($linha37 = mysqli_fetch_assoc($resultado37)){
								$id_comp = $linha37["id_componente"];
							
								$statement38 = mysqli_prepare($conn, "SELECT numero_horas FROM componente WHERE id_componente = $id_comp;");
								$statement38->execute();
								$resultado38 = $statement38->get_result();
								$linha38 = mysqli_fetch_assoc($resultado38);
									$numero_horas = $linha38["numero_horas"];
							
								$statement39 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NULL;");
								$statement39->execute();
								$resultado39 = $statement39->get_result();
								$linha39 = mysqli_fetch_assoc($resultado39);
									$numero_turmas_sem_juncao = $linha39["COUNT(DISTINCT id_turma)"];
								
									if($numero_turmas_sem_juncao == 0){	
										$statement40 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
										$statement40->execute();
										$resultado40 = $statement40->get_result();
										$linha40 = mysqli_fetch_assoc($resultado40);
											$numero_juncoes_comp = $linha40["COUNT(DISTINCT id_juncao)"];
											
											$statement41 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador;");
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
										$statement42 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NOT NULL;");
										$statement42->execute();
										$resultado42 = $statement42->get_result();
										$linha42 = mysqli_fetch_assoc($resultado42);
											$numero_juncoes_comp = $linha42["COUNT(DISTINCT id_juncao)"];
											
											$statement43 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_docente = $idUtilizador AND id_juncao IS NOT NULL;");
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
			<div class="card_docente" id="card_docente"><a href="visDSD_.php?id=<?php echo $idUtilizador; ?>" style="color: #000000;">
				<div class="container_card_docente">
					<div class="container_card_docente_imagem">
						<img src="<?php echo $imgUtilizador ?>" style="margin-top:20px; margin-bottom:20px; width:110px; heigh:110px; border-radius:50%; border:2px solid #212529;">
					</div>
					<div class="container_card_docente_detalhes">
						<h4 style="margin-top:15px;"><b><?php echo $nomeUtilizador ?></b> </h4>
						<text style="font-family:sans-serif;"><?php while($linha2 = mysqli_fetch_assoc($resultado2)){
							echo $linha2["nome"], "<br>"; 
						}?>
						</text>
						<i class="material-icons" title='UTC' style="vertical-align:middle;">menu_book</i> <text title='UTC' style="font-family:sans-serif;"><?php echo $siglaUtcUtilizador ?></text><br>
						<i class="material-icons" title='Área' style="vertical-align:middle;">monitor</i> <text title='Área' style="font-family:sans-serif;"><?php echo $nomeAreaUtilizador ?></text>
					</div>
					<div class="container_card_docente_editar">
						<?php if(($idAreaUtilizadorAtual == $idAreaUtilizador) || ($idUtilizadorAtual == $idResponsavelUtc)) { 
						if($dsd_1_sem == 1 && $dsd_2_sem == 1){ ?>
							<a class="btn btn-danger" title="A DSD está bloqueada em ambos os semestres" onclick="semestresBloqueados()" href="javascript:void(0)" style='width:101px; border-radius:25px;'><span class="material-icons" style="vertical-align:middle;">lock</span>Editar</a>
				<?php	}
						else{ ?>
							<a class="btn btn-primary" href="edDSD_.php?id=<?php echo $idUtilizador ?>" style='width:101px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>
					<?php } 
						}?>
					</div></p>
					<div class="container_card_docente_detalhes2">
						<h6 style="margin-left:15px;">1ºS</h6>
						<text title='Disciplinas' style="font-family:sans-serif;"><i class="material-icons" title='Disciplinas' style="vertical-align:middle;">class</i> <?php echo sizeof($array_disciplinas_1_sem_temp) ?></text><br>
						<text title='Carga horária semanal' style="font-family:sans-serif;"><i class="material-icons" title='Carga horária semanal' style="vertical-align:middle;">schedule</i> <?php echo $horas_1_sem, "H"?></text>
					</div>
					<div class="container_card_docente_detalhes3">
						<h6 style="margin-left:15px;">2ºS</h6>
						<text title='Disciplinas' style="font-family:sans-serif;"><i class="material-icons" title='Disciplinas' style="vertical-align:middle;">class</i> <?php echo sizeof($array_disciplinas_2_sem_temp) ?></text><br>
						<text title='Carga horária semanal' style="font-family:sans-serif;"><i class="material-icons" title='Carga horária semanal' style="vertical-align:middle;">schedule</i> <?php echo $horas_2_sem, "H"?></text>
					</div>
					<!--<a class='btn btn-primary' style='width:101px; border-radius:25px; float:right; margin-bottom:50px; margin-top:50px;' onclick=''><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a> -->
				</div>
				</a>
			</div>
			</a>
			<?php }; ?> <!--
			<div class="card_docente" id="card_docente">
				<div class="container_card_UC">
					<div class="container_card_UC_titulo">
						<h4><b>Paulo Serra</b></h4>
					</div>
					<div class="container_card_UC_detalhes">
					</div>
					<div class="container_card_UC_editar">
					</div></p>
					<!--<a class='btn btn-primary' style='width:101px; border-radius:25px; float:right; margin-bottom:50px; margin-top:50px;' onclick=''><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a> -->
				</div>
			</a>
			</div>
		</div>    
	</div>
</div>

</main>
<script language="javascript">
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_DSD");
	var li_DSD_especifico = document.getElementById("li_DSD_DSD");
	
	li_DSD.style.background = "#4a6f96";
	li_DSD_especifico.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function verDadosDocente(id_docente){
	window.location.href = 'visDSD_.php?id=' + id_docente;
}

function gerarFicheiroExcelDSD(id_utc){
	$('#cover-spin').show(0);
	
	//Gerar o ficheiro
	$.ajax ({
		type: "POST",
		url: "templateDSD.php",
		data: {id_utc: id_utc},
		success: function(result) {
			
			//Redirecionar para o download
			window.location.href = result;
			
			setTimeout(function(){
					$('#cover-spin').hide();
					}
					,350);
		}
	});
	
}

function filtrarCheckbox(){
	var area_div = document.getElementById("checkbox_area");
	var utc_div = document.getElementById("checkbox_utc");
	
	const id_utc_utilizador = <?php echo $id_utc_atual ?>;
	const id_area_utilizador = <?php echo $idAreaUtilizadorAtual ?>;
	
/*	if(!Boolean(<?php echo $permAdmin ?>)){ */
		
		//alert("Gerar ficheiro excel!");
		$('#cover-spin').show(0);
		
		apagarCartoes();
		
		if(utc_div.checked){
			if(area_div.checked){
				//Mostrar apenas docentes da minha área e UTC
				criarCartoes(id_utc_utilizador,id_area_utilizador);
				setTimeout(function(){
					$('#cover-spin').hide();
					}
					,2000);
			}
			else{
				//Mostrar todos os docentes da minha UTC
				criarCartoes(id_utc_utilizador,0);
				setTimeout(function(){
					$('#cover-spin').hide();
					}
					,2000);
			}
		}
		else{
			if(area_div.checked){
				//Mostrar apenas os docentes da minha área
				criarCartoes(0,id_area_utilizador);
				setTimeout(function(){
					$('#cover-spin').hide();
					}
					,2000);
			}
			else{
				//Mostrar todos os docentes
				criarCartoes(0,0);
				setTimeout(function(){
					$('#cover-spin').hide();
					}
					,2000);
			}
		}
/*	}  */
	
}

function preencherAreas(){
	var area_div = document.getElementById("area_dropdown");
	var utc_div = document.getElementById("utc_dropdown");
	var id_utc_escolhida = utc_div.value;
	
	$.ajax ({
		type: "POST",
		url: "processamento/verAreasUTC.php",
		dataType: "json",
		data: {id_utc: id_utc_escolhida},
		success: function(result) {
			var array = result.split(",");
			
			criarOpcao(area_div,"nada_selecionado","");
			
			for(i = 0; i < array.length; i = i + 2){
				criarOpcao(area_div,array[i],array[i + 1]);
			}
		}
	});
}

function filtrarArea(){
	var area_div = document.getElementById("area_dropdown");
	var utc_div = document.getElementById("utc_dropdown");
	var id_area_escolhida = area_div.value;
	var id_utc_escolhida = utc_div.value;
	
	//Mostrar todos os docentes da UTC
	if(id_area_escolhida == "nada_selecionado"){
		criarCartoes(id_utc_escolhida,0);
	}
	else{
		criarCartoes(id_utc_escolhida,id_area_escolhida);
	}
}

function criarCartoes(id_utc,id_area){
	
	const card_body = document.getElementsByClassName("card-body")[0];
	
	//alert("Criar cartões para os docentes da UTC " + id_utc + " e da área " + id_area);
	
	$.ajax ({
		type: "POST",
		url: "processamento/verDadosCartaoDocente.php", 
		dataType: "json",
		data: {id_utc: id_utc, id_area: id_area},
		success: function(result) {
			//alert("Resultado: " + result); 
					
			var array_final_json = JSON.stringify(result);
			var array_final = array_final_json.split(",");
						
			var array_final_primeiro = array_final[0].replace("[","");
			var array_final_ultimo = array_final[array_final.length - 1].replace("]","");
			array_final[0] = array_final_primeiro;
			array_final[array_final.length - 1] = array_final_ultimo;
					
			for(i = 0; i < result.length; i = i + 12){
				//alert("Docente: " + result[i] + " img: " + result[i + 1]);
				
				const div_card_docente = document.createElement("div");
				div_card_docente.className = "card_docente";
				div_card_docente.id = "card_docente";
				div_card_docente.setAttribute("onclick","verDadosDocente(" + result[i + 5] + ")");
				
				const container_card_docente = document.createElement("div");
				container_card_docente.className = "container_card_docente";
				
				const container_card_docente_imagem = document.createElement("div");
				container_card_docente_imagem.className = "container_card_docente_imagem";
				container_card_docente_imagem.innerHTML = "<img src='" + result[i + 1] + "' style='margin-top:20px; margin-bottom:20px; width:110px; heigh:110px; border-radius:50%; border:2px solid #212529;'>";
				
				const container_card_docente_detalhes = document.createElement("div");
				container_card_docente_detalhes.className = "container_card_docente_detalhes";
				
					const bold = document.createElement("strong");
					const nome_utilizador = document.createElement("h4");
					nome_utilizador.innerHTML = "<b>" + result[i] + "</b>";
					nome_utilizador.style.marginTop = "15px";
					bold.appendChild(nome_utilizador);
					
					const text_funcao = document.createElement("text");
					text_funcao.style.fontFamily = "sans-serif";
					text_funcao.innerHTML = result[i + 2];
					const paragrafo1 = document.createElement("br");
					
					const icone_utc = document.createElement("material-icons");
					icone_utc.title = "UTC";
					icone_utc.style.verticalAlign = "middle";
					icone_utc.innerHTML = "menu_book";
					
					const text_utc = document.createElement("text");
					text_utc.title = "UTC";
					text_utc.style.fontFamily = "sans-serif";
					text_utc.innerHTML = "<i class='material-icons' title='UTC' style='vertical-align:middle;'>menu_book</i> " + result[i + 3];					
					const paragrafo2 = document.createElement("br");
					
					const icone_area = document.createElement("material-icons");
					icone_area.title = "Área";
					icone_area.style.verticalAlign = "middle";
					icone_area.innerHTML = "monitor";
					
					const text_area = document.createElement("text");
					text_area.title = "Área";
					text_area.style.fontFamily = "sans-serif";
					text_area.innerHTML = "<i class='material-icons' title='Área' style='vertical-align:middle;'>monitor</i> " + result[i + 4];					
					const paragrafo3 = document.createElement("br");

						
					container_card_docente_detalhes.appendChild(bold);
					container_card_docente_detalhes.appendChild(text_funcao);
					container_card_docente_detalhes.appendChild(paragrafo1);
					container_card_docente_detalhes.appendChild(text_utc);
					container_card_docente_detalhes.appendChild(paragrafo2);
					container_card_docente_detalhes.appendChild(text_area);
					container_card_docente_detalhes.appendChild(paragrafo3);
				
				const container_card_editar = document.createElement("div");
				container_card_editar.className = "container_card_docente_editar";
				
				if((<?php echo $idAreaUtilizadorAtual ?> == result[i + 10]) /* || (Boolean(<?php echo $permAdmin ?>)) */ || (Boolean(<?php echo $idUtilizadorAtual ?> == result[i + 11]))){
				
					const botao_editar = document.createElement("a");
					botao_editar.className = "btn btn-primary";
					botao_editar.href = "edDSD_.php?id=" + result[i + 5];
					botao_editar.style.width = "101px";
					botao_editar.style.borderRadius = "25px";
					botao_editar.innerHTML = "<i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar";
					
					container_card_editar.appendChild(botao_editar);
				}
				
				const container_card_docente_detalhes2 = document.createElement("div");
				container_card_docente_detalhes2.className = "container_card_docente_detalhes2";
				
					const titulo_1s = document.createElement("h6");
					titulo_1s.style.marginLeft = "15px";
					titulo_1s.innerHTML = "1ºS";
					
					const text_sem1 = document.createElement("text");
					text_sem1.title = "Disciplinas";
					text_sem1.style.fontFamily = "sans-serif";
					text_sem1.innerHTML = "<i class='material-icons' title='Disciplinas' style='vertical-align:middle;'>class</i> " + result[i + 6];
					const paragrafo4 = document.createElement("br");
					
					const text_sem1_2 = document.createElement("text");
					text_sem1_2.title = "Carga horária semanal";
					text_sem1_2.style.fontFamily = "sans-serif";
					text_sem1_2.innerHTML = "<i class='material-icons' title='Carga horária semanal' style='vertical-align:middle;'>schedule</i> " + result[i + 7] + "H";
					const paragrafo5 = document.createElement("br");
			
					container_card_docente_detalhes2.appendChild(titulo_1s);
					container_card_docente_detalhes2.appendChild(text_sem1);
					container_card_docente_detalhes2.appendChild(paragrafo4);
					container_card_docente_detalhes2.appendChild(text_sem1_2);
					container_card_docente_detalhes2.appendChild(paragrafo5);
					
					
				const container_card_docente_detalhes3 = document.createElement("div");
				container_card_docente_detalhes3.className = "container_card_docente_detalhes3";
				
					const titulo_1s_3 = document.createElement("h6");
					titulo_1s_3.style.marginLeft = "15px";
					titulo_1s_3.innerHTML = "2ºS";
					
					const text_sem2 = document.createElement("text");
					text_sem2.title = "Disciplinas";
					text_sem2.style.fontFamily = "sans-serif";
					text_sem2.innerHTML = "<i class='material-icons' title='Disciplinas' style='vertical-align:middle;'>class</i> " + result[i + 8];
					const paragrafo6 = document.createElement("br");
					
					const text_sem2_2 = document.createElement("text");
					text_sem2_2.title = "Carga horária semanal";
					text_sem2_2.style.fontFamily = "sans-serif";
					text_sem2_2.innerHTML = "<i class='material-icons' title='Carga horária semanal' style='vertical-align:middle;'>schedule</i> " + result[i + 9] + "H";
					const paragrafo7 = document.createElement("br");
			
					container_card_docente_detalhes3.appendChild(titulo_1s_3);
					container_card_docente_detalhes3.appendChild(text_sem2);
					container_card_docente_detalhes3.appendChild(paragrafo6);
					container_card_docente_detalhes3.appendChild(text_sem2_2);
					container_card_docente_detalhes3.appendChild(paragrafo7);
					
					
					
					
					
				container_card_docente.appendChild(container_card_docente_imagem);
				container_card_docente.appendChild(container_card_docente_detalhes);
				container_card_docente.appendChild(container_card_editar);
				container_card_docente.appendChild(container_card_docente_detalhes2);
				container_card_docente.appendChild(container_card_docente_detalhes3);
				
				div_card_docente.appendChild(container_card_docente);
				
				card_body.appendChild(div_card_docente);
			}	
/*
			<div class="card_docente" id="card_docente" onclick="verDadosDocente(<?php echo $idUtilizador ?>)">
				<div class="container_card_docente">
					<div class="container_card_docente_imagem">
						<img src="<?php echo $imgUtilizador ?>" style="margin-top:20px; margin-bottom:20px; width:110px; heigh:110px; border-radius:50%; border:2px solid #212529;">
					</div>
					<div class="container_card_docente_detalhes">
						<h4 style="margin-top:15px;"><b><?php echo $nomeUtilizador ?></b> </h4>
						<text style="font-family:sans-serif;"><?php while($linha2 = mysqli_fetch_assoc($resultado2)){
							echo $linha2["nome"], "<br>"; 
						}?>
						</text>
						<i class="material-icons" title='UTC' style="vertical-align:middle;">menu_book</i> <text title='UTC' style="font-family:sans-serif;"><?php echo $siglaUtcUtilizador ?></text><br>
						<i class="material-icons" title='Área' style="vertical-align:middle;">monitor</i> <text title='Área' style="font-family:sans-serif;"><?php echo $nomeAreaUtilizador ?></text>
					</div>
					<div class="container_card_docente_editar">
						<?php if(($idAreaUtilizadorAtual == $idAreaUtilizador) || $permAdmin || ($idUtilizadorAtual == $idResponsavelUtc)) { ?>
						<a class="btn btn-primary" href="edDSD_.php?id=<?php echo $idUtilizador ?>" style='width:101px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>
						<?php } ?>
					</div></p>
					<div class="container_card_docente_detalhes2">
						<h6 style="margin-left:15px;">1ºS</h6>
						<text title='Disciplinas' style="font-family:sans-serif;"><i class="material-icons" title='Disciplinas' style="vertical-align:middle;">class</i> <?php echo sizeof($array_disciplinas_1_sem_temp) ?></text><br>
						<text title='Carga horária semanal' style="font-family:sans-serif;"><i class="material-icons" title='Carga horária semanal' style="vertical-align:middle;">schedule</i> <?php echo $horas_1_sem, "H"?></text>
					</div>
					<div class="container_card_docente_detalhes3">
						<h6 style="margin-left:15px;">2ºS</h6>
						<text title='Disciplinas' style="font-family:sans-serif;"><i class="material-icons" title='Disciplinas' style="vertical-align:middle;">class</i> <?php echo sizeof($array_disciplinas_2_sem_temp) ?></text><br>
						<text title='Carga horária semanal' style="font-family:sans-serif;"><i class="material-icons" title='Carga horária semanal' style="vertical-align:middle;">schedule</i> <?php echo $horas_2_sem, "H"?></text>
					</div>
					<!--<a class='btn btn-primary' style='width:101px; border-radius:25px; float:right; margin-bottom:50px; margin-top:50px;' onclick=''><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a> -->
				</div>
			</div>
*/
					/*
			var array_final_sigla_curso = array_final[3].replaceAll('"','');
			array_final[3] = array_final_sigla_curso;
						
			var array_final_nome_uc = array_final[4].replaceAll('"','');
			array_final[4] = array_final_nome_uc;
						
			var array_final_nome_disciplina = array_final[4].replaceAll('"','');
		
			const array_final_nome_responsavel = array_final[9].replaceAll('"','');
			const array_final_nome_responsavel_final = array_final_nome_responsavel.replace("[","");
			array_final[9] = array_final_nome_responsavel_final;
						
			const array_final_imagem_responsavel = array_final[10].replaceAll('"','');
			const array_final_imagem_responsavel_final = array_final_imagem_responsavel.replace("]","");
			array_final[10] = array_final_imagem_responsavel_final;
						
			const array_final_nome_docente = array_final[11].replaceAll('"','');
			array_final[11] = array_final_nome_docente;
					
			//alert("array_final: " + array_final);
					
			//Criar o cartão da disciplina escolhida
			card_body.appendChild(criarCartao(array_final[0],array_final[1],array_final[2],array_final[3],array_final[4],array_final[5],
									array_final[6],array_final[7],array_final[8], array_final[9], array_final[10], array_final[11], array_final[12]));
						*/
		}
	});
	
}

function apagarCartoes(){
	var cartoes = document.getElementsByClassName("card_docente");
	for(i = 0; i < cartoes.length; i++){
		cartoes[i].style.display = "none";
	} 
}

function criarOpcao(div,valor,texto){
	var opt = document.createElement('option');
	opt.value = valor;
	opt.text = texto;
	div.options.add(opt);
}

function removerOpcoes(elemento) {
	var i, L = elemento.options.length - 1;
	for(i = L; i >= 0; i--) {
		elemento.remove(i);
	}
}

function semestresBloqueados(){
	alert("A DSD está bloqueada em ambos os semestres. Por favor contacte o coordenador da UTC.");
}
</script>
<?php gerarHome2() ?>
