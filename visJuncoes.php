<?php
// Página de visualização das junções das turmas (visJuncoes)

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$idUtilizador = (int) $_SESSION['id'];
$idUtilizadorAtual = $idUtilizador;
$permAdmin = false;
$permUTC = false;
$permArea = false;

if(isset($_SESSION['permAdmin'])){
    $permAdmin = true;
}
if(isset($_SESSION['permUTC'])){
    $permUTC = true;
}
if(isset($_SESSION['permArea'])){
    $permArea = true;
}

$idUTCUtilizador = 0;
$idAreaUtilizador = 0;

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
$statement->bind_param('i', $idUtilizador);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
    $idAreaUtilizador = (int) $linha["id_area"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = ?");
    $statement->bind_param('i', $idAreaUtilizador);
    $statement->execute();
    $resultado = $statement->get_result();
    $linha = mysqli_fetch_assoc($resultado);
    $idUTCUtilizador = (int) $linha["id_utc"];
	
	$statement101 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $idUTCUtilizador;");
	$statement101->execute();
	$resultado101 = $statement101->get_result();
	$linha101 = mysqli_fetch_assoc($resultado101);
		$id_gestor_UTC = $linha101["id_responsavel"];

	$statement001 = mysqli_prepare($conn, "SELECT dsd_1_sem, dsd_2_sem FROM utc WHERE id_utc = $idUTCUtilizador;");
	$statement001->execute();
	$resultado001 = $statement001->get_result();
	$linha001 = mysqli_fetch_assoc($resultado001);
		$dsd_1_sem = $linha001["dsd_1_sem"];
		$dsd_2_sem = $linha001["dsd_2_sem"];

?>
<?php gerarHome1() ?>
<script src="js/juncoes.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
		<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Junções</a></h6>
        <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 35px;"><b>Junções de turmas</b></h3>
		
		<?php
			if($dsd_1_sem == 0 && $dsd_2_sem == 0){ ?>
				<a class="btn btn-primary" title="Criar Junção" data-toggle="modal" data-target="#criarJuncaoModal" onclick="gerarFormCriarJuncao()" style="border-radius:25px;  margin-bottom:25px; position: absolute; top: 70px; left: 320px;"><i class="material-icons" style="line-height:50%; margin-right:5px; vertical-align: middle;">join_inner</i>Criar Junção</a>
		<?php }
			else if($dsd_1_sem == 0 && $dsd_2_sem == 1){?>
				<a class="btn btn-semi" title="Criar Junção (A DSD do 2º Semestre está bloqueada)" data-toggle="modal" data-target="#criarJuncaoModal" onclick="gerarFormCriarJuncao()" style="border-radius:25px;  margin-bottom:25px; position: absolute; top: 70px; left: 320px;"><span class="material-icons" style="vertical-align:middle;">lock</span>Criar Junção</a>
			<?php }
			else if($dsd_1_sem == 1 && $dsd_2_sem == 0){?>
				<a class="btn btn-semi" title="Criar Junção (A DSD do 1º Semestre está bloqueada)" data-toggle="modal" data-target="#criarJuncaoModal" onclick="gerarFormCriarJuncao()" style="border-radius:25px;  margin-bottom:25px; position: absolute; top: 70px; left: 320px;"><span class="material-icons" style="vertical-align:middle;">lock</span>Criar Junção</a>
			<?php }
			else { ?>
				<a class="btn btn-danger" title="A DSD está bloqueada em ambos os semestres" onclick="semestresBloqueados()" style="border-radius:25px;  margin-bottom:25px; position: absolute; top: 70px; left: 320px;"><span class="material-icons" style="vertical-align:middle;">lock</span>Criar Junção</a>
			<?php } ?>
			
		<div class="juncoes_1_sem">
			 <h6 align="center"><b>1º Semestre</b></h6>
				<?php
				$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_curso, nome FROM curso WHERE id_utc = $idUTCUtilizador;");
				$statement1->execute();
				$resultado1 = $statement1->get_result();
				while($linha1 = mysqli_fetch_assoc($resultado1)){
					$id_curso = $linha1["id_curso"];
					$nome_curso = $linha1["nome"];
					
					$id_content = $id_curso . "_" .  1;
					$id_content_icone = "icone_" . $id_content;
					
					$array_juncoes_curso = array();
					
					$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao, nome_juncao FROM juncao ORDER BY nome_juncao;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					while($linha2 = mysqli_fetch_assoc($resultado2)){
						$id_juncao = $linha2["id_juncao"];
						$nome_juncao = $linha2["nome_juncao"];
							
						$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$num_turmas_juncao = $linha3["COUNT(DISTINCT id_turma)"];	
							
						$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM juncao_componente WHERE id_juncao = $id_juncao");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$num_componentes_juncao = $linha4["COUNT(DISTINCT id_componente)"];
							
						$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_juncao = $id_juncao AND id_docente IS NOT NULL;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$num_docente_juncao = $linha5["COUNT(id_docente)"];
							
						if($num_docente_juncao > 0){
							$statement6 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_juncao = $id_juncao;");
							$statement6->execute();
							$resultado6 = $statement6->get_result();
							$linha6 = mysqli_fetch_assoc($resultado6);
								$id_docente_juncao = $linha6["id_docente"];
														
								$statement7 = mysqli_prepare($conn, "SELECT imagem_perfil, nome FROM utilizador WHERE id_utilizador = $id_docente_juncao;");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								$linha7 = mysqli_fetch_assoc($resultado7);
									$imagem_perfil = $linha7["imagem_perfil"];
									$nome_docente_juncao = $linha7["nome"];										
						}
							
							
						$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM juncao_componente WHERE id_juncao = $id_juncao");
						$statement8->execute();
						$resultado8 = $statement8->get_result();
						while($linha8 = mysqli_fetch_assoc($resultado8)){
							$id_componente = $linha8["id_componente"];
								
							$statement9 = mysqli_prepare($conn, "SELECT d.id_curso, d.semestre FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente");
							$statement9->execute();
							$resultado9 = $statement9->get_result();
							$linha9 = mysqli_fetch_assoc($resultado9);
								$id_curso_componente = $linha9["id_curso"];
								$semestre = $linha9["semestre"];
								
								if($id_curso_componente == $id_curso && $semestre == 1){
									if(!in_array($id_juncao,$array_juncoes_curso)){
										array_push($array_juncoes_curso,$id_juncao);
										array_push($array_juncoes_curso,$nome_juncao);
										array_push($array_juncoes_curso,$num_turmas_juncao);
										array_push($array_juncoes_curso,$num_componentes_juncao);
										if($num_docente_juncao > 0){
											array_push($array_juncoes_curso,$nome_docente_juncao);
											array_push($array_juncoes_curso,$imagem_perfil);
											array_push($array_juncoes_curso,$id_docente_juncao);
										}
										else{
											array_push($array_juncoes_curso,"");
											array_push($array_juncoes_curso,"");
											array_push($array_juncoes_curso,"");
										}
										
										$array_cursos_juncao = array();
										
										$statement10 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM juncao_componente WHERE id_juncao = $id_juncao");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										while($linha10 = mysqli_fetch_assoc($resultado10)){
											$id_comp_temp = $linha10["id_componente"];
													
											$statement11 = mysqli_prepare($conn, "SELECT d.id_curso, d.semestre FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_comp_temp");
											$statement11->execute();
											$resultado11 = $statement11->get_result();
											$linha11 = mysqli_fetch_assoc($resultado11);
												$id_curso_temp_2 = $linha11["id_curso"];
												$semestre_temp = $linha11["semestre"];
															
												$statement12 = mysqli_prepare($conn, "SELECT sigla_completa FROM curso WHERE id_curso = $id_curso_temp_2;");
												$statement12->execute();
												$resultado12 = $statement12->get_result();
												$linha12 = mysqli_fetch_assoc($resultado12);
													$sigla_curso = $linha12["sigla_completa"];
														
													if($semestre_temp == 1){
														if(!in_array($sigla_curso,$array_cursos_juncao)){
															array_push($array_cursos_juncao,$sigla_curso);
														}
													}
										}
										array_push($array_juncoes_curso,$array_cursos_juncao);
									}
								}
						}
					}
						
					//print_r($array_juncoes_curso);
					echo "<div class='header_utc' onclick='verJuncoesCurso($id_curso,1)' data-id_curso='$id_curso'>
							<i class='material-icons' id='$id_content_icone' style='float:right; margin-right:10px; margin-top:13px;'>add</i>
							<b>Curso: $nome_curso </b>
						</div>
						<div class='content' id='$id_content'>";
					
					$counter = 0;
					while($counter < sizeof($array_juncoes_curso)){
						$id_juncao = $array_juncoes_curso[$counter];
						$nome_juncao = $array_juncoes_curso[$counter + 1];
						$num_turmas = $array_juncoes_curso[$counter + 2];
						$num_componentes = $array_juncoes_curso[$counter + 3];
						$docente_juncao = $array_juncoes_curso[$counter + 4];
						$imagem_docente_juncao = $array_juncoes_curso[$counter + 5];
						$id_docente_juncao = $array_juncoes_curso[$counter + 6];
						$array_cursos = $array_juncoes_curso[$counter + 7];
						$array_cursos_final = implode(", ",$array_cursos);
							
						$array_areas_juncao = array();
							
						$statement101 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM juncao_componente WHERE id_juncao = $id_juncao");
						$statement101->execute();
						$resultado101 = $statement101->get_result();
						while($linha101 = mysqli_fetch_assoc($resultado101)){
							$id_comp_comp = $linha101["id_componente"];
							
							$statement102 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_comp_comp;");
							$statement102->execute();
							$resultado102 = $statement102->get_result();
							$linha102 = mysqli_fetch_assoc($resultado102);
								$id_disciplina_disciplina = $linha102["id_disciplina"];
								
								
							$statement103 = mysqli_prepare($conn, "SELECT id_area FROM disciplina WHERE id_disciplina = $id_disciplina_disciplina;");
							$statement103->execute();
							$resultado103 = $statement103->get_result();
							$linha103 = mysqli_fetch_assoc($resultado103);
								$id_area_temp = $linha103["id_area"];
								
								if(!in_array($id_area_temp,$array_areas_juncao)){
									array_push($array_areas_juncao,$id_area_temp);
								}
							
						}
							
						if(strlen($docente_juncao) > 17){
							$docente_juncao = substr_replace($docente_juncao, '...', 17, (strlen($docente_juncao) - 17));
						}
						
						echo "<div class='card_juncao'>";
							echo "<div class='card_juncao_imagem' data-toggle='modal' data-target='#verDadosJuncaoModal' onclick='verDetalhesJuncao($id_juncao)'>";
								if($num_componentes > 1){
									echo "<img src='http://localhost/apoio_utc/images/join_laranja.png' title='Esta junção tem turmas de diferentes componentes/UC's' style='width:50px; height:50px; margin-left:10px; margin-top:15px;'>";
								}
								else{
									echo "<img src='http://localhost/apoio_utc/images/join.png' title='Esta junção apenas tem turmas da mesma componente' style='width:50px; height:50px; margin-left:10px; margin-top:15px;'>";
								}
							echo "</div>";
							echo "<div class='card_juncao_titulo' data-toggle='modal' data-target='#verDadosJuncaoModal' onclick='verDetalhesJuncao($id_juncao)'>";
								echo "<b>", $nome_juncao, "</b><br>";
							echo "</div>";
							if(in_array($idAreaUtilizador,$array_areas_juncao) /*|| $permAdmin */|| $idUtilizadorAtual == $id_gestor_UTC){
								echo "<div class='card_juncao_editar'>";
								if($dsd_1_sem == 0){
									echo "<a class='btn btn-primary' onclick='editarJuncao($id_juncao)' data-toggle='modal' data-target='#editarJuncaoModal' title='Editar Junção' style='width:45px; height:23px; border-radius:23px;'><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i></a>";
								}
								else{
									echo "<a class='btn btn-danger' onclick='semestreBloqueado()' title='A DSD está bloqueada neste semstre' href='javascript:void(0)' style='width:45px; height:25px; border-radius:23px;'><span class='material-icons' style='height:15px; width:15px; margin-left:-8px; margin-top:-6px;'>lock</span></a>";
								}
								echo "</div>";
							}
							echo "<div class='card_juncao_detalhes' data-toggle='modal' data-target='#verDadosJuncaoModal' onclick='verDetalhesJuncao($id_juncao)'>";
								echo "<i class='material-icons' style='vertical-align:middle; margin-right:5px;'>people</i>", $num_turmas, " Turmas";
								if($docente_juncao != ""){
									if($id_docente_juncao == $idUtilizadorAtual){
										echo "<img src='", $imagem_docente_juncao, "' style='width:28px; height:28px; border-radius:50%; margin-left:225px; margin-right:5px; border:2px solid #212529;''><b>", $docente_juncao, "</b>", "<br>";
									}
									else{
										echo "<img src='", $imagem_docente_juncao, "' style='width:28px; height:28px; border-radius:50%; margin-left:225px; margin-right:5px; border:1px solid #212529;''><text>", $docente_juncao, "</text>", "<br>";
									}
								}
								else{
									echo "<br>";
								}
								echo "<i class='material-icons' style='vertical-align:middle; margin-right:5px;'>school</i>", $array_cursos_final, "<br>";
							echo "</div>";
						echo "</div>";
						
						$counter = $counter + 8;
					}
					
					echo "</div>";

				}
				?>
		</div>
		<div class="juncoes_2_sem">
			<h6 align="center"><b>2º Semestre</b></h6>
			<?php
				$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_curso, nome FROM curso WHERE id_utc = $idUTCUtilizador;");
				$statement1->execute();
				$resultado1 = $statement1->get_result();
				while($linha1 = mysqli_fetch_assoc($resultado1)){
					$id_curso = $linha1["id_curso"];
					$nome_curso = $linha1["nome"];
					
					$id_content = $id_curso . "_" .  2;
					$id_content_icone = "icone_" . $id_content;
					
					$array_juncoes_curso = array();
					
					$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao, nome_juncao FROM juncao ORDER BY nome_juncao;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					while($linha2 = mysqli_fetch_assoc($resultado2)){
						$id_juncao = $linha2["id_juncao"];
						$nome_juncao = $linha2["nome_juncao"];
							
						$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
							$num_turmas_juncao = $linha3["COUNT(DISTINCT id_turma)"];	
							
						$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM juncao_componente WHERE id_juncao = $id_juncao");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
							$num_componentes_juncao = $linha4["COUNT(DISTINCT id_componente)"];
							
						$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_juncao = $id_juncao AND id_docente IS NOT NULL;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$num_docente_juncao = $linha5["COUNT(id_docente)"];
							
						if($num_docente_juncao > 0){
							$statement6 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_juncao = $id_juncao;");
							$statement6->execute();
							$resultado6 = $statement6->get_result();
							$linha6 = mysqli_fetch_assoc($resultado6);
								$id_docente_juncao = $linha6["id_docente"];
														
								$statement7 = mysqli_prepare($conn, "SELECT imagem_perfil, nome FROM utilizador WHERE id_utilizador = $id_docente_juncao;");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								$linha7 = mysqli_fetch_assoc($resultado7);
									$imagem_perfil = $linha7["imagem_perfil"];
									$nome_docente_juncao = $linha7["nome"];										
						}
							
							
						$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM juncao_componente WHERE id_juncao = $id_juncao");
						$statement8->execute();
						$resultado8 = $statement8->get_result();
						while($linha8 = mysqli_fetch_assoc($resultado8)){
							$id_componente = $linha8["id_componente"];
								
							$statement9 = mysqli_prepare($conn, "SELECT d.id_curso, d.semestre FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente");
							$statement9->execute();
							$resultado9 = $statement9->get_result();
							$linha9 = mysqli_fetch_assoc($resultado9);
								$id_curso_componente = $linha9["id_curso"];
								$semestre = $linha9["semestre"];
								
								if($id_curso_componente == $id_curso && $semestre == 2){
									if(!in_array($id_juncao,$array_juncoes_curso)){
										array_push($array_juncoes_curso,$id_juncao);
										array_push($array_juncoes_curso,$nome_juncao);
										array_push($array_juncoes_curso,$num_turmas_juncao);
										array_push($array_juncoes_curso,$num_componentes_juncao);
										if($num_docente_juncao > 0){
											array_push($array_juncoes_curso,$nome_docente_juncao);
											array_push($array_juncoes_curso,$imagem_perfil);
											array_push($array_juncoes_curso,$id_docente_juncao);
										}
										else{
											array_push($array_juncoes_curso,"");
											array_push($array_juncoes_curso,"");
											array_push($array_juncoes_curso,"");
										}
										
										$array_cursos_juncao = array();
										
										$statement10 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM juncao_componente WHERE id_juncao = $id_juncao");
										$statement10->execute();
										$resultado10 = $statement10->get_result();
										while($linha10 = mysqli_fetch_assoc($resultado10)){
											$id_comp_temp = $linha10["id_componente"];
													
											$statement11 = mysqli_prepare($conn, "SELECT d.id_curso, d.semestre FROM disciplina d INNER JOIN componente c ON d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_comp_temp");
											$statement11->execute();
											$resultado11 = $statement11->get_result();
											$linha11 = mysqli_fetch_assoc($resultado11);
												$id_curso_temp_2 = $linha11["id_curso"];
												$semestre_temp = $linha11["semestre"];
															
												$statement12 = mysqli_prepare($conn, "SELECT sigla_completa FROM curso WHERE id_curso = $id_curso_temp_2;");
												$statement12->execute();
												$resultado12 = $statement12->get_result();
												$linha12 = mysqli_fetch_assoc($resultado12);
													$sigla_curso = $linha12["sigla_completa"];
														
													if($semestre_temp == 2){
														if(!in_array($sigla_curso,$array_cursos_juncao)){
															array_push($array_cursos_juncao,$sigla_curso);
														}
													}
										}
										array_push($array_juncoes_curso,$array_cursos_juncao);
									}
								}
						}
					}
						
					//print_r($array_juncoes_curso);
					echo "<div class='header_utc' onclick='verJuncoesCurso($id_curso,2)' data-id_curso='$id_curso'>
							<i class='material-icons' id='$id_content_icone' style='float:right; margin-right:10px; margin-top:13px;'>add</i>
							<b>Curso: $nome_curso </b>
						</div>
						<div class='content' id='$id_content'>";
					
					$counter = 0;
					while($counter < sizeof($array_juncoes_curso)){
						$id_juncao = $array_juncoes_curso[$counter];
						$nome_juncao = $array_juncoes_curso[$counter + 1];
						$num_turmas = $array_juncoes_curso[$counter + 2];
						$num_componentes = $array_juncoes_curso[$counter + 3];
						$docente_juncao = $array_juncoes_curso[$counter + 4];
						$imagem_docente_juncao = $array_juncoes_curso[$counter + 5];
						$id_docente_juncao = $array_juncoes_curso[$counter + 6];
						$array_cursos = $array_juncoes_curso[$counter + 7];
						$array_cursos_final = implode(", ",$array_cursos);
							
						if(strlen($docente_juncao) > 17){
							$docente_juncao = substr_replace($docente_juncao, '...', 17, (strlen($docente_juncao) - 17));
						}
						
						$array_areas_juncao = array();
							
						$statement101 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM juncao_componente WHERE id_juncao = $id_juncao");
						$statement101->execute();
						$resultado101 = $statement101->get_result();
						while($linha101 = mysqli_fetch_assoc($resultado101)){
							$id_comp_comp = $linha101["id_componente"];
							
							$statement102 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_comp_comp;");
							$statement102->execute();
							$resultado102 = $statement102->get_result();
							$linha102 = mysqli_fetch_assoc($resultado102);
								$id_disciplina_disciplina = $linha102["id_disciplina"];
								
								
							$statement103 = mysqli_prepare($conn, "SELECT id_area FROM disciplina WHERE id_disciplina = $id_disciplina_disciplina;");
							$statement103->execute();
							$resultado103 = $statement103->get_result();
							$linha103 = mysqli_fetch_assoc($resultado103);
								$id_area_temp = $linha103["id_area"];
								
								if(!in_array($id_area_temp,$array_areas_juncao)){
									array_push($array_areas_juncao,$id_area_temp);
								}
							
						}
						
						echo "<div class='card_juncao'>";
							echo "<div class='card_juncao_imagem' data-toggle='modal' data-target='#verDadosJuncaoModal' onclick='verDetalhesJuncao($id_juncao)'>";
								if($num_componentes > 1){
									echo "<img src='http://localhost/apoio_utc/images/join_laranja.png' title='Esta junção tem turmas de diferentes componentes/UC's' style='width:50px; height:50px; margin-left:10px; margin-top:15px;'>";
								}
								else{
									echo "<img src='http://localhost/apoio_utc/images/join.png' title='Esta junção apenas tem turmas da mesma componente' style='width:50px; height:50px; margin-left:10px; margin-top:15px;'>";
								}
							echo "</div>";
							echo "<div class='card_juncao_titulo' data-toggle='modal' data-target='#verDadosJuncaoModal' onclick='verDetalhesJuncao($id_juncao)'>";
								echo "<b>", $nome_juncao, "</b><br>";
							echo "</div>";
							if(in_array($idAreaUtilizador,$array_areas_juncao) || /*$permAdmin ||*/ $idUtilizadorAtual == $id_gestor_UTC){
								echo "<div class='card_juncao_editar'>";
								if($dsd_2_sem == 0){
									echo "<a class='btn btn-primary' onclick='editarJuncao($id_juncao)' data-toggle='modal' data-target='#editarJuncaoModal' title='Editar Junção' style='width:45px; height:23px; border-radius:23px;'><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i></a>";
								}
								else{
									echo "<a class='btn btn-danger' onclick='semestreBloqueado()' title='A DSD está bloqueada neste semstre' href='javascript:void(0)' style='width:45px; height:25px; border-radius:23px;'><span class='material-icons' style='height:15px; width:15px; margin-left:-8px; margin-top:-6px;'>lock</span></a>";
								}
								echo "</div>";
							}
							echo "<div class='card_juncao_detalhes' data-toggle='modal' data-target='#verDadosJuncaoModal' onclick='verDetalhesJuncao($id_juncao)'>";
								echo "<i class='material-icons' style='vertical-align:middle; margin-right:5px;'>people</i>", $num_turmas, " Turmas";
								if($docente_juncao != ""){
									if($id_docente_juncao == $idUtilizadorAtual){
										echo "<img src='", $imagem_docente_juncao, "' style='width:28px; height:28px; border-radius:50%; margin-left:225px; margin-right:5px; border:2px solid #212529;''><b>", $docente_juncao, "</b>", "<br>";
									}
									else{
										echo "<img src='", $imagem_docente_juncao, "' style='width:28px; height:28px; border-radius:50%; margin-left:225px; margin-right:5px; border:1px solid #212529;''><text>", $docente_juncao, "</text>", "<br>";
									}
								}
								else{
									echo "<br>";
								}
								echo "<i class='material-icons' style='vertical-align:middle; margin-right:5px;'>school</i>", $array_cursos_final, "<br>";
							echo "</div>";
						echo "</div>";
						
						$counter = $counter + 8;
					}
					
					echo "</div>";

				}
				?>
		</div>
	</div>
</div>
</main>

<!-- Modal -->
<div class="modal fade" id="criarJuncaoModal" tabindex="-1" role="dialog" aria-labelledby="tituloCriarJuncaoModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 60%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" align="center" id="tituloCriarJuncaoModal">Criar junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyCriarJuncao" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editarJuncaoModal" tabindex="-1" role="dialog" aria-labelledby="tituloEditarJuncaoModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 60%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" align=center id="tituloEditarJuncaoModal">Editar junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyEditarJuncao" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="verDadosJuncaoModal" tabindex="-1" role="dialog" aria-labelledby="tituloverDadosJuncaoModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 25%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" align=center id="tituloverDadosJuncaoModal">Junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyVerDadosJuncaoModal" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_DSD");
	var li_juncoes = document.getElementById("li_juncoes");
	
	li_DSD.style.background = "#4a6f96";
	li_juncoes.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function verJuncoesCurso(id_curso,semestre){
	//alert("UTC: " + id_utc + " SEM:" + semestre);
	
	var icone = document.getElementById("icone_" + id_curso + "_" + semestre);
	
	var content = document.getElementById(id_curso + "_" + semestre);
	if (content.style.display === "block") {
      content.style.display = "none";
	  icone.innerHTML = "add";
    } else {
      content.style.display = "block";
	  icone.innerHTML = "remove";
    }
}

function verDetalhesJuncao(id_juncao){
	//alert("Ver detalhes juncao: " + id_juncao);
	
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyVerDadosJuncaoModal").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/verTurmasJuncao.php?id=" + id_juncao, true);
    xhttp.send();
}

/*-------------------------------------EDITAR JUNÇÃO-------------------------------------*/

function editarJuncao(id_juncao){
	//alert("Editar juncao: " + id_juncao);
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarJuncao").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/editarJuncao.php?id=" + id_juncao, true);
    xhttp.send();
}

function atualizarNomeJuncao(id_juncao){
	
	const input_nome = document.getElementById("nome_juncao");
	var nome_introduzido = input_nome.value;
	
	const select_docente = document.getElementById("editar_juncao_docente");
	var docente_escolhido = select_docente.value;
	
	if(nome_introduzido.length < 10){
		alert("Introduza um nome válido! (10 caracteres)");
		input_nome.focus();
	}
	else{
		$.ajax ({
			type: "POST",
			url: "processamento/atualizarNomeDocenteJuncao.php", 
			data: {id_juncao: id_juncao, nome: nome_introduzido, id_docente: docente_escolhido},
			success: function(result) {
				location.reload();
				}
			});
	}
}

function removerTurmasJuncao(id_juncao){
	
	const div_turmas = document.getElementById("editar_juncao_remover_turmas");
	
	const array_turmas_total = [];
	const array_turmas_selecionadas = [];
	const array_nomes_turmas_selecionadas = [];
	
	$('#editar_juncao_remover_turmas').find('input').each(function () {
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

function mostrarCursosUTC(id_componente_original,id_juncao){
	
	limparDadosDireita();
	
	//$('#semestre_bloqueado').remove();
	
	const dropdown_utc = document.getElementById("edDSUC_outros_utc");
	var id_utc_escolhida = dropdown_utc.value;
	//https://app.prolific.co/studies/62180c6a9de629e58ba3110b?source=pa
	
	/*
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
				
				const div_uc = document.getElementById("div_turmas_outros_disciplina");
				div_uc.innerHTML += "<span class='material-icons' id='semestre_bloqueado' title='A DSD do " + semestre_bloqueado + "º semestre desta UTC está bloqueada' style='vertical-align:middle; cursor:default;'>lock</span>";
				
			}
			
		}
	}); */
	
	const dropdown_cursos = document.getElementById("edDSUC_outros_curso");
	
	const array_turmas_ja_na_juncao = verTurmasJaNaJuncao();
	
	if(id_utc_escolhida != 0){
		//Mostrar a lista de UC's
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarCursosUTC_editar_juncao.php", 
			data: {id_utc: id_utc_escolhida, id_juncao: id_juncao},
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

function mostrarDisciplinasCurso(id_componente_original,id_juncao){
	
	const div_curso = document.getElementById("edDSUC_outros_curso");
	var id_curso_escolhido = div_curso.value;
	
	const dropdown_disciplinas = document.getElementById("edDSUC_outros_disciplina");
	
	limparDadosDireita_abaixo_curso();
	
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

function mostrarComponentesUC(id_comp,id_juncao){
	
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
		//alert("Ano/sem: " + array);
		const ano = array[0];
		const semestre = array[1];
		
		$.ajax ({
		type: "POST",
		url: "processamento/verTurmasAnoSemestreComp_editar_juncao.php", 
		data: {ano: ano, semestre: semestre, id_curso: id_curso_escolhido, id_componente: id_componente_escolhida, id_juncao: id_juncao},
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

function verTurmasJaNaJuncao(){
	
	const div_turmas_ja_na_juncao = document.getElementById("editar_juncao_remover_turmas");
	
	const array_turmas_ja_na_juncao = [];
	
	$('#editar_juncao_remover_turmas').find('input').each(function () {
		if(this.getAttribute("data_id-turma") != null){
			var id_turma = this.getAttribute("data_id-turma");
			var id_componente = this.getAttribute("data_id-componente");
			array_turmas_ja_na_juncao.push(id_turma);
			array_turmas_ja_na_juncao.push(id_componente);
		}
	});
	
	return array_turmas_ja_na_juncao;
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

function removeOptions(selectElement) {
	var i, L = selectElement.options.length - 1;
	for(i = L; i >= 0; i--) {
		selectElement.remove(i);
	}
}

/*-------------------------------------CRIAR JUNÇÃO-------------------------------------*/

function gerarFormCriarJuncao() {
    var xhttp;    
    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyCriarJuncao").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formCriarJuncao.php", true);
    xhttp.send();
	
	//mostrarUTCsCriar();
}

function mostrarUTCsCriar(){
	
	limparDadosDireitaTotal_criar();
	
	const dropdown_utc = document.getElementById("criar_juncao_utc");
	
	const array_turmas_na_esquerda = verTurmasEsquerda();
	
	//Mostrar a lista de UTC's
	$.ajax ({
		type: "POST",
		url: "processamento/mostrarUTCs_criar_juncao.php", 
		data: {array_turmas_temp: array_turmas_na_esquerda},
		success: function(result) {
			var array = result.split(',');
			//alert("UTC's: " + array);
				
			var vazia = document.createElement('option');
			vazia.value = "0";
			vazia.text = "";
			dropdown_utc.options.add(vazia);
				
			if(array.length > 1){
				for(i = 0; i < array.length; i = i + 2){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i + 1];
					dropdown_utc.options.add(opt);
				} 
			}
		}
	});
	
}

function mostrarCursosUTCCriar(){
	
	limparDadosDireita_criar();
	
	$('#semestre_bloqueado').remove();
	
	const dropdown_utc = document.getElementById("criar_juncao_utc");
	var id_utc_escolhida = dropdown_utc.value;
	
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
				
				const div_uc = document.getElementById("div_criar_juncao_disciplina");
				div_uc.innerHTML += "<span class='material-icons' id='semestre_bloqueado' title='A DSD do " + semestre_bloqueado + "º semestre desta UTC está bloqueada' style='vertical-align:middle; cursor:default;'>lock</span>";
				
			}
			
		}
	});
	
	const dropdown_cursos = document.getElementById("criar_juncao_curso");
	
	const array_turmas_na_esquerda = verTurmasEsquerda();
	
	if(id_utc_escolhida != 0){
		//Mostrar a lista de UC's
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarCursosUTC_criar_juncao.php", 
			data: {id_utc: id_utc_escolhida, array_turmas_temp: array_turmas_na_esquerda},
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

function mostrarDisciplinasCursoCriar(){
	
	limparDadosDireita_abaixo_curso_criar();
	
	const dropdown_utc = document.getElementById("criar_juncao_utc");
	var id_utc_escolhida = dropdown_utc.value;
	
	const dropdown_curso = document.getElementById("criar_juncao_curso");
	var id_curso_escolhido = dropdown_curso.value;
	
	const dropdown_disciplinas = document.getElementById("criar_juncao_disciplina");
	
	const array_turmas_na_esquerda = verTurmasEsquerda();
	
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
						url: "processamento/juncoes/mostrarUCsCurso_criar_juncao_sem_bloqueado.php", 
						data: {id_curso: id_curso_escolhido, array_turmas_temp: array_turmas_na_esquerda, semestre_bloqueado: semestre_bloqueado},
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
				else{
					
					$.ajax ({
						type: "POST",
						url: "processamento/mostrarUCsCurso_criar_juncao.php", 
						data: {id_curso: id_curso_escolhido, array_turmas_temp: array_turmas_na_esquerda},
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
		});
	
	}
	
}

function mostrarComponentesUCCriar(){
	
	limparDadosDireita_abaixo_uc_criar();
	
	const dropdown_uc = document.getElementById("criar_juncao_disciplina");
	var id_uc_escolhida = dropdown_uc.value;
	
	var dropdown_componentes = document.getElementById("criar_juncao_componente");
	
	const array_turmas_na_esquerda = verTurmasEsquerda();
	
	if(id_uc_escolhida != 0){
		//alert("UC: " + id_uc_escolhida + " COMP: " + id_comp);
		$.ajax ({
		type: "POST",
		url: "processamento/mostrarComponentesUC_criar_juncao.php", 
		data: {id_uc: id_uc_escolhida, array_turmas_temp: array_turmas_na_esquerda},
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

function mostrarTurmasCriar(){

	limparDadosDireita_abaixo_comp_criar();
	
	const dropdown_curso = document.getElementById("criar_juncao_curso");
	var id_curso_escolhido = dropdown_curso.value;

	const dropdown_uc = document.getElementById("criar_juncao_disciplina");
	var id_uc_escolhida = dropdown_uc.value;
	
	const dropdown_componente = document.getElementById("criar_juncao_componente");
	var id_componente_escolhida = dropdown_componente.value;
	
	const div_turmas = document.getElementById("div_criar_juncao_turmas");
	
	const array_turmas_na_esquerda = verTurmasEsquerda();
	
	if(id_componente_escolhida != 0){
			
		$.ajax ({
		type: "POST",
		url: "processamento/verAnoSemestreUC.php", 
		data: {id_uc: id_uc_escolhida},
		success: function(result) {
			var array = result.split(',');
			//alert("Ano/sem: " + array);
			const ano = array[0];
			const semestre = array[1];
			
			$.ajax ({
			type: "POST",
			url: "processamento/verTurmasAnoSemestreComp_criar_juncao.php", 
			data: {ano: ano, semestre: semestre, id_curso: id_curso_escolhido, id_componente: id_componente_escolhida, array_turmas_temp: array_turmas_na_esquerda},
			success: function(result) {
				var array = result.split(',');
				//alert("Turmas: " + array);
				
				if(array.length > 1){
						
					for(i = 0; i < array.length; i = i + 8){
						
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
						select_turma.setAttribute("data_id-comp",array[i + 2]);
						select_turma.setAttribute("data_nome-curso",array[i + 3]);
						select_turma.setAttribute("data_sigla-uc",array[i + 4]);
						select_turma.setAttribute("data_sigla-comp",array[i + 5]);
						select_turma.setAttribute("data_ano",array[i + 6]);
						select_turma.setAttribute("data_semestre",array[i + 7]);
						
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

function adicicionarTurmaTempCriarJuncao(){
	
	var div_turmas = document.getElementById("div_criar_juncao_turmas");
	var div_turmas_esquerda = document.getElementById("criar_juncao_turmas_temp");
	
	const array_turmas_selecionadas = [];
	
	$('#div_criar_juncao_turmas').find('input').each(function () {
		if(this.getAttribute("data_id-turma") != null){
			if(this.checked){
				var id_turma = this.getAttribute("data_id-turma");
				var nome_turma = this.getAttribute("data_nome-turma");
				var id_componente = this.getAttribute("data_id-comp");
				var nome_curso = this.getAttribute("data_nome-curso");
				var sigla_uc = this.getAttribute("data_sigla-uc");
				var sigla_comp = this.getAttribute("data_sigla-comp");
				var ano = this.getAttribute("data_ano");
				var semestre = this.getAttribute("data_semestre");
				array_turmas_selecionadas.push(id_turma);
				array_turmas_selecionadas.push(nome_turma);
				array_turmas_selecionadas.push(id_componente);
				array_turmas_selecionadas.push(nome_curso);
				array_turmas_selecionadas.push(sigla_uc);
				array_turmas_selecionadas.push(sigla_comp);
				array_turmas_selecionadas.push(ano);
				array_turmas_selecionadas.push(semestre);
			}
		}
	});
	
	//alert("Turmas selecionadas: " + array_turmas_selecionadas);
	
	if(array_turmas_selecionadas.length == 0){
		alert("Selecione pelo menos uma turma!");
	}
	else{
		for(i = 0; i < array_turmas_selecionadas.length; i = i + 8){
			
			var id_turma = array_turmas_selecionadas[i];
			var nome_turma = array_turmas_selecionadas[i + 1]
			var id_componente = array_turmas_selecionadas[i + 2];
			var nome_curso = array_turmas_selecionadas[i + 3];
			var sigla_uc = array_turmas_selecionadas[i + 4];
			var sigla_comp = array_turmas_selecionadas[i + 5];
			var ano = array_turmas_selecionadas[i + 6];
			var semestre = array_turmas_selecionadas[i + 7];
			
			var img_turmas = document.createElement('i');
			img_turmas.innerHTML = "<i class='material-icons' style='vertical-align:middle;'>people</i>";
			
			var label = document.createElement('label');
			label.style.marginLeft = '5px';
			label.style.marginTop = '-5px';
			label.setAttribute("data_id-turma",id_turma);
			label.setAttribute("data_id-componente",id_componente);
			label.innerHTML = " <b>" + nome_turma + "</b> - " + nome_curso + " (" + sigla_uc + "-" + sigla_comp + ") (" + ano + "ºA/" + semestre + "ºS)";
						
			var paragrafo = document.createElement("br");
						
			div_turmas_esquerda.appendChild(img_turmas);
			div_turmas_esquerda.appendChild(label);
			div_turmas_esquerda.appendChild(paragrafo);
		}
		
		limparDadosDireitaTotal_criar();
		
		mostrarUTCsCriar();
		
		$(function() {
			var temp="0"; 
			$("#criar_juncao_utc").val(temp);
		});
		
	}
	
}

function criarJuncao(){
	
	const array_turmas_selecionadas = [];
	
	const input_nome = document.getElementById("criar_juncao_nome");
	var nome_introduzido = input_nome.value;
	
	const dropdown_docente = document.getElementById("criar_juncao_docente");
	var id_docente_escolhido = dropdown_docente.value;
	
	$('#criar_juncao_turmas_temp').find('label').each(function () {
		if(this.getAttribute("data_id-turma") != null){
			var id_turma = this.getAttribute("data_id-turma");
			var id_componente = this.getAttribute("data_id-componente");
			array_turmas_selecionadas.push(id_turma);
			array_turmas_selecionadas.push(id_componente);
		}
	});
	
	//alert("Turmas selecionadas: " + array_turmas_selecionadas);
	
	if(array_turmas_selecionadas.length < 4){
		alert("Selecione pelo menos duas turmas!");
	}
	else if(nome_introduzido.length == 0){
		alert("Introduza um nome para a junção!");
		input_nome.focus();
	}
	else if(nome_introduzido.length < 10){
		alert("Introduza um nome válido! (10 caracteres)");
		input_nome.focus();
	}
	else{
		//alert("Criar junção!");
		
		$.ajax ({
			type: "POST",
			url: "processamento/criarJuncao.php", 
			data: {array_turmas: array_turmas_selecionadas, nome_juncao: nome_introduzido, id_docente: id_docente_escolhido},
			success: function(result) {
				location.reload();
			}
		});
		
	}
}

function verTurmasEsquerda(){
	
	const div_turmas_temporarias = document.getElementById("criar_juncao_turmas_temp");
	
	const array_turmas_temporarias = [];
	
	$('#criar_juncao_turmas_temp').find('label').each(function () {
		if(this.getAttribute("data_id-turma") != null){
			var id_turma = this.getAttribute("data_id-turma");
			var id_componente = this.getAttribute("data_id-componente");
			array_turmas_temporarias.push(id_turma);
			array_turmas_temporarias.push(id_componente);
		}
	});
	
	return array_turmas_temporarias;
	
}

function limparDadosDireitaTotal_criar(){
	
	const dropdown_utc = document.getElementById("criar_juncao_utc");
	const dropdown_curso = document.getElementById("criar_juncao_curso");
	const dropdown_uc = document.getElementById("criar_juncao_disciplina");
	const dropdown_comp = document.getElementById("criar_juncao_componente");
	
	removeOptions(dropdown_utc);
	removeOptions(dropdown_curso);
	removeOptions(dropdown_uc);
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("div_criar_juncao_turmas");
	
	$('#div_criar_juncao_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('br').each(function () {
		this.remove();
	});
	
}

function limparDadosDireita_criar(){
	
	const dropdown_curso = document.getElementById("criar_juncao_curso");
	const dropdown_uc = document.getElementById("criar_juncao_disciplina");
	const dropdown_comp = document.getElementById("criar_juncao_componente");
	
	removeOptions(dropdown_curso);
	removeOptions(dropdown_uc);
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("div_criar_juncao_turmas");
	
	$('#div_criar_juncao_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('br').each(function () {
		this.remove();
	});
	
}

function limparDadosDireita_abaixo_curso_criar(){
	
	const dropdown_uc = document.getElementById("criar_juncao_disciplina");
	const dropdown_comp = document.getElementById("criar_juncao_componente");
	
	removeOptions(dropdown_uc);
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("div_criar_juncao_turmas");
	
	$('#div_criar_juncao_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('br').each(function () {
		this.remove();
	});
}

function limparDadosDireita_abaixo_uc_criar(){
	
	const dropdown_comp = document.getElementById("criar_juncao_componente");
	
	removeOptions(dropdown_comp);
	
	const div_turmas = document.getElementById("div_criar_juncao_turmas");
	
	$('#div_criar_juncao_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('br').each(function () {
		this.remove();
	});
}

function limparDadosDireita_abaixo_comp_criar(){

	const div_turmas = document.getElementById("div_criar_juncao_turmas");
	
	$('#div_criar_juncao_turmas').find('input').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('label').each(function () {
		this.remove();
	});
	
	$('#div_criar_juncao_turmas').find('br').each(function () {
		this.remove();
	});
}


function removerTurmaJuncao(id_turma,nome_turma,id_juncao){
	//alert("Remover turma: " + id_turma + " da junção: " + id_juncao);
	if(window.confirm("Pretende remover esta turma da junção?")){
		$.ajax ({
			type: "POST",
			url: "processamento/removerTurmaJuncao.php", 
			data: {id_turma: id_turma, id_juncao: id_juncao},
			success: function(result) {
				location.reload();
			},
			error: function(result) {
				alert("Erro ao remover turma: " + result);
			}
		});
	}
}

function semestreBloqueado(){
	alert("A DSD deste semestre está bloqueada. Por favor contacte o coordenador da UTC.");
}

function semestresBloqueados(){
	alert("A DSD está bloqueada em ambos os semestres. Por favor contacte o coordenador da UTC.");
}

</script>


<?php gerarHome2() ?>
