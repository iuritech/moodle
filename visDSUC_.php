<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$idUtilizador = (int) $_SESSION['id'];
$idUtilizadorSessaoAtual = $idUtilizador;
$idAreaUtilizador = (int) $_SESSION['area_utilizador'];

$permAdmin = false;
if(isset($_SESSION['permAdmin'])){
    $permAdmin = true;
}

/*--------------------------------------------------------------------------------------------------*/

$idDisciplina = (int) filter_input(INPUT_GET, 'id');

$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = $idDisciplina");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$nomeDisciplina = $linha["nome_uc"];
$semestre = (int) $linha["semestre"];
$ano = (int) $linha["ano"];
$idCurso = (int) $linha["id_curso"];
$idArea = (int) $linha["id_area"];
$id_curso = $linha["id_curso"];

$statement000 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
$statement000->execute();
$resultado000 = $statement000->get_result();
$linha000 = mysqli_fetch_assoc($resultado000);
	$id_utc_disciplina = $linha000["id_utc"];
	
$statement001 = mysqli_prepare($conn, "SELECT dsd_1_sem, dsd_2_sem FROM utc WHERE id_utc = $id_utc_disciplina;");
$statement001->execute();
$resultado001 = $statement001->get_result();
$linha001 = mysqli_fetch_assoc($resultado001);
	$dsd_1_sem = $linha001["dsd_1_sem"];
	$dsd_2_sem = $linha001["dsd_2_sem"];
	
$statement00 = mysqli_prepare($conn, "SELECT id_responsavel, nome_utc FROM utc WHERE id_utc = $id_utc_disciplina;");
$statement00->execute();
$resultado00 = $statement00->get_result();
$linha00 = mysqli_fetch_assoc($resultado00);

$id_responsavel_UTC_disciplina = $linha00["id_responsavel"];
$nome_utc = $linha00["nome_utc"];

$statement01 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $idArea");
$statement01->execute();
$resultado01 = $statement01->get_result();
$linha01 = mysqli_fetch_assoc($resultado01);
$nome_area = $linha01["nome"];
/*--------------------------------------------------------------------------------------------------*/

$statement = mysqli_prepare($conn, "SELECT u.id_utilizador, u.nome, u.imagem_perfil FROM utilizador u 
										INNER JOIN disciplina d ON u.id_utilizador = d.id_responsavel
											WHERE d.id_disciplina = $idDisciplina;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$idResponsavel = $linha["id_utilizador"];
$nomeResponsavel = $linha["nome"];
$imagemResponsavel = $linha["imagem_perfil"];

/*--------------------------------------------------------------------------------------------------*/

$array_componentes = array();

//Outros docentes que não o responsável que também estão a dar aulas
$statement = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $idDisciplina;");
$statement->execute();
$resultado = $statement->get_result();
while($linha = mysqli_fetch_assoc($resultado)){
	$id_componente = $linha["id_componente"];
	array_push($array_componentes, $id_componente);
}

$array_componentes_final = implode(",",$array_componentes);

$statement2 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT u.id_utilizador), u.imagem_perfil, u.nome FROM utilizador u INNER JOIN aula a 
									ON u.id_utilizador = a.id_Docente WHERE a.id_componente IN ($array_componentes_final)
									AND a.id_docente != $idResponsavel;");
$statement2->execute();
$resultado2 = $statement2->get_result();
$linha2 = mysqli_fetch_assoc($resultado2);

$numOutrosDocentes = $linha2["COUNT(DISTINCT u.id_utilizador)"];
$nomeDocente = $linha2["nome"];
$imagemDocente = $linha2["imagem_perfil"];

/*--------------------------------------------------------------------------------------------------*/

$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = $idCurso");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$nomeCurso = $linha["nome"];
$siglaCurso = $linha["sigla"];

/*--------------------------------------------------------------------------------------------------*/
?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card mb-4">
		<div class="card-body" style="background: url(images/fundo_disciplina_default_final.jpg);">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-left:15px; margin-top:10px;">...</a> / <a href="visDSUC.php"> DSD (Unidades Curriculares)</a> / <a href=""><?php echo $nomeDisciplina ?></a></h6>
			<br><h3 style="margin-left:15px;"><b> <?php echo $nomeDisciplina ?> </b>- <?php echo $siglaCurso ?> (<?php echo $ano ?>ºA/<?php echo $semestre ?>ºS)</h3>
			<img src="<?php echo $imagemResponsavel ?>" style="margin-left:15px; margin-top:15px; width:35px; heigh:35px; border-radius:50%; border:2px solid #212529;"> <h6 style="position:absolute; left:75px; top:145px;"><?php echo $nomeResponsavel?> (responsável)</h6>
			<?php if($numOutrosDocentes > 0){?>
				<h6 style="position:absolute; top:165px; left:115px;">...</h6>
			<?php }?>
			<?php if(($idAreaUtilizador == $idArea) || ($idUtilizadorSessaoAtual == $id_responsavel_UTC_disciplina)) { 
					if($semestre == 1){
						if($dsd_1_sem == 0){?>
				<a class="btn btn-primary" href="edDSUC.php?i=<?php echo $idDisciplina ?>" style='width:101px; border-radius:25px; position: absolute; right: 35px; top:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>
						<?php }
						else{ ?>
						<a class="btn btn-danger" title="A DSD deste semestre está bloqueada" onclick="semestreBloqueado()" href="javascript:void(0)" style='width:101px; border-radius:25px; position: absolute; right: 35px; top:25px;'><span class="material-icons" style="vertical-align:middle;">lock</span>Editar</a>
				<?php	} 
					}
					else{
						if($dsd_2_sem == 0){?>
				<a class="btn btn-primary" href="edDSUC.php?i=<?php echo $idDisciplina ?>" style='width:101px; border-radius:25px; position: absolute; right: 35px; top:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>
						<?php }
						else{ ?>
						<a class="btn btn-danger" title="A DSD deste semestre está bloqueada" onclick="semestreBloqueado()" href="javascript:void(0)" style='width:101px; border-radius:25px; position: absolute; right: 35px; top:25px;'><span class="material-icons" style="vertical-align:middle;">lock</span>Editar</a>
				<?php	} 
				}
			} ?>
<!--			<i class="material-icons" style="vertical-align:middle; position:absolute; top:100px; left:640px;">menu_book</i>
			<text style="vertical-align:middle; position:absolute; top:100px; left:670px;"><?php echo $nome_utc ?></text>
			<i class="material-icons" style="vertical-align:middle; position:absolute; top:130px; left:640px;">monitor</i>
			<text style="vertical-align:middle; position:absolute; top:130px; left:670px;"><?php echo $nome_area ?></text> -->
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="card mb-4">
		<div class="card-body">
			<table border="1" cellpadding="15" id="tabelaDSUC" width="100%" style="text-align:center; table-layout: fixed;">
				<thead>
					<tr>
						<th width=220>Turmas</th>
						<?php
						$numComponentes = 0;
						$componentes_array = array();
						// Obter componentes
						$statement = mysqli_prepare($conn, "SELECT * FROM componente INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente WHERE id_disciplina = $idDisciplina ORDER BY id_componente, tipo_componente.id_tipocomponente");
						$statement->execute();
						$resultado1 = $statement->get_result();
						while($linha1 = mysqli_fetch_assoc($resultado1)){
							$numComponentes = $numComponentes + 1;
							$idComponente = $linha1["id_componente"];
							$nomeTipo = $linha1["nome_tipocomponente"];
							$numeroHoras = $linha1["numero_horas"];
							array_push($componentes_array,$idComponente);
								
							?><th><?php echo $nomeTipo ?> (<?php echo $numeroHoras ?>H) </th><?php	
						}
						$componentes_array_final = implode(",",$componentes_array);
						?>
					</tr>
				</thead>
				<tbody>
					<?php
					// Obter turmas do mesmo ano e sem da disciplina
					$statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $idCurso;");
					$statement->execute();
					$resultado2 = $statement->get_result();
					while($linha2 = mysqli_fetch_assoc($resultado2)){
						$idTurma = $linha2["id_turma"];
						$nomeTurma = $linha2["nome"];
						?><tr>
							<td><?php echo "<text style='font-family:sans-serif;'>", "<i class='material-icons' style='vertical-align:middle; margin-right:3px;'>people</i><b>", $nomeTurma, "</b></text>" ?></td>
							<?php $i = 0; while($i < $numComponentes){?>
								<td><?php 
									$statement = mysqli_prepare($conn, "SELECT COUNT(u.nome), u.nome, u.id_utilizador, u.imagem_perfil FROM utilizador u INNER JOIN aula a ON 
																		u.id_utilizador = a.id_docente WHERE a.id_componente = $componentes_array[$i] 
																		AND a.id_turma = $idTurma");
									$statement->execute();
									$resultado3 = $statement->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);

									if($linha3["COUNT(u.nome)"] > 0){
										$id_utilizador = $linha3["id_utilizador"];
										$nomeDocente = $linha3["nome"];		
										$imgPerfil = $linha3["imagem_perfil"];
										if($id_utilizador == $idUtilizadorSessaoAtual){
											echo "<img src='$imgPerfil' style='width:35px; height:35px; border-radius:50%; margin-right:5px; border:2px solid #212529;'>", "<text style='font-family:sans-serif; font-size:18px;'><b>", $nomeDocente, "</b></text>";	
										}
										else{
											echo "<img src='$imgPerfil' style='width:35px; height:35px; border-radius:50%; margin-right:5px; border:2px solid #212529;'>", "<text style='font-family:sans-serif; font-size:18px;'>", $nomeDocente, "</text>";	
										}
									}
									else{
										echo "<i style='opacity:50%;'>Nenhum docente atribuído...</i>";
									}
									
									//Verificar se existe alguma junção
									$statement = mysqli_prepare($conn, "SELECT id_juncao, COUNT(id_juncao) FROM aula WHERE id_componente = $componentes_array[$i] AND id_turma = $idTurma");
									$statement->execute();
									$resultado4 = $statement->get_result();
									$linha4 = mysqli_fetch_assoc($resultado4);
								
									if($linha4["COUNT(id_juncao)"] != 0){
										$idJuncao = $linha4["id_juncao"];
										
										$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $idJuncao;");
										$statement->execute();
										$resultado5 = $statement->get_result();
										$linha5 = mysqli_fetch_assoc($resultado5);
										$numComponentesDiferentes = $linha5["COUNT(DISTINCT id_componente)"];
										
										if($numComponentesDiferentes > 1){
											echo "<img src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' onclick='verDadosJuncao($idJuncao)' class='juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UC's' style='width:18px; height:18px; margin-left:10px; cursor:pointer;'>";
										}
										else{
											//Junção apenas de turmas na mesma componente
											echo "<img src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' onclick='verDadosJuncao($idJuncao)' class='juncao' title='Esta turma está numa junção' style='width:18px; height:18px; margin-left:10px; cursor:pointer;'>";
										}
									}
								
							?>	</td>
							<?php	$i = $i + 1;
							}
							?>
						</tr><?php } ?>
				</tbody>
			</table>
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
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_DSD");
	li_DSD.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

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

function semestreBloqueado(){
	alert("A DSD deste semestre está bloqueada. Por favor contacte o coordenador da UTC.");
}
</script>
<?php gerarHome2() ?>