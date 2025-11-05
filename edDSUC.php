<?php
// Página de elaboração de distribuição de serviço para uma dada disciplina

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$idUtilizador = (int) $_SESSION['id'];
$idUtilizadorSessaoAtual = $idUtilizador;

$statement = mysqli_prepare($conn, "SELECT id_area FROM utilizador WHERE id_utilizador = $idUtilizador;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$idAreaUtilizador = $linha["id_area"];

$permAdmin = false;
if(isset($_SESSION['permAdmin'])){
    $permAdmin = true;
}

/*--------------------------------------------------------------------------------------------------*/

$id = (int) filter_input(INPUT_GET, 'i');

$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$idDisciplina = (int) $linha["id_disciplina"];
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
	
	if($semestre == 1){
		if($dsd_1_sem == 1){
			header("Location: visDSUC.php");
		}
	}
	else{
		if($dsd_2_sem == 1){
			header("Location: visDSUC.php");
		}
	}
	
$statement00 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc_disciplina;");
$statement00->execute();
$resultado00 = $statement00->get_result();
$linha00 = mysqli_fetch_assoc($resultado00);

$id_responsavel_UTC_disciplina = $linha00["id_responsavel"];

if($idAreaUtilizador != $idArea && ($idUtilizadorSessaoAtual != $id_responsavel_UTC_disciplina)){
	header("Location: visDSUC.php");
}

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

if(strlen($nomeResponsavel) > 20){
	$nomeResponsavel = substr_replace($nomeResponsavel,"...",(15-strlen($nomeResponsavel)));
}

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
<script src="js/edDSUC.js"></script>
<div id="cover-spin"></div>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card mb-4">
		<div class="card-body" style="background: url(images/fundo_disciplina_default_final.jpg);">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-left:15px; margin-top:10px;"><a href="visDSUC.php"> ...</a> / <a href="visDSUC_.php?id=<?php echo $idDisciplina ?>"><?php echo $nomeDisciplina ?></a> / <a href=""><?php echo "Editar" ?></a></h6>
			<br><h3 style="margin-left:15px;"><b> <?php echo $nomeDisciplina ?> </b>- <?php echo $siglaCurso ?> (<?php echo $ano ?>ºA/<?php echo $semestre ?>ºS)</h3>
			<a class='btn btn-secondary' href='#' data-toggle='modal' data-target='#edDSUCresponsavel' onclick='gerarFormMudarResponsável(<?php echo $idResponsavel ?>,<?php echo $id ?>)' style='width:180px; height:40px; border-radius:25px; margin-left:15px; margin-top:5px;'><i class='material-icons' style='vertical-align: middle; float:left;'>person_search</i><?php echo $nomeResponsavel ?></a>
			<h6 style="position:absolute; left:225px; top:137px;"> (responsável)</h6>
			<?php if($numOutrosDocentes > 0){?>
				<h6 style="position:absolute; top:165px; left:125px;">...</h6>
			<?php }?>
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
									$statement = mysqli_prepare($conn, "SELECT COUNT(u.id_utilizador), u.id_utilizador, u.nome, u.imagem_perfil, a.id_juncao FROM utilizador u INNER JOIN aula a ON 
																		u.id_utilizador = a.id_docente WHERE a.id_componente = $componentes_array[$i] 
																		AND a.id_turma = $idTurma");
									$statement->execute();
									$resultado3 = $statement->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$id_juncao = $linha3["id_juncao"];
									
									if($linha3["COUNT(u.id_utilizador)"] > 0){
										$idDocente = $linha3["id_utilizador"];
										$nomeDocente = $linha3["nome"];		
										$imgPerfil = $linha3["imagem_perfil"];
										echo "<a class='btn btn-secondary' href='#' data-toggle='modal' data-target='#edDSUCdocente' onclick='gerarFormAtribuirDocente($idDocente,$componentes_array[$i],$idTurma, $id_juncao)' style='width:180px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle; float:left;'>person_search</i>$nomeDocente</a>";
									}
									else{
										echo "<a class='btn btn-primary' href='#' data-toggle='modal' data-target='#edDSUCdocente' onclick='gerarFormAtribuirDocente2($componentes_array[$i],$idTurma, $id_juncao)' style='width:48px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle; float:middle;'>person_search</i></a>";
										//echo "<a class='btn btn-secondary' href='#' style='width:180px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle; float:left;'>person_search</i><i>Atribuir...</i></a>";
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
											echo "<img src='http://localhost/apoio_utc/images/join_laranja.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' onclick='verDadosJuncao($idJuncao)' class='juncao' title='Esta turma está numa junção com turmas de diferentes componentes/UC's' style='width:20px; height:20px; margin-left:10px; cursor:pointer;'>";
										}
										else{
											//Junção apenas de turmas na mesma componente
											echo "<img src='http://localhost/apoio_utc/images/join.png' data-toggle='modal' data-target='#visDSD_ver_dados_juncao' onclick='verDadosJuncao($idJuncao)' class='juncao' title='Esta turma está numa junção'  style='width:20px; height:20px; margin-left:10px; cursor:pointer;'>";
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
<div class="modal fade" id="edDSUCresponsavel" tabindex="-1" role="dialog" aria-labelledby="titulo_edDSUC_responsavel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 20%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_edDSUC_responsavel">Alterar Responsável</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            </script>
            <div id="modalBody_edDSUC_responsavel" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edDSUCdocente" tabindex="-1" role="dialog" aria-labelledby="tituloEditarDSUCdocenteModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 20%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEditarDSUCdocenteModal">Atribuir Docente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <script>
                function verificarFormEditar() {

                    document.getElementById("formEditarDSUC").submit();
                }
            </script>
            <div id="modalBodyEditarDSUCdocente" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="visDSD_ver_dados_juncao" tabindex="-1" role="dialog" aria-labelledby="titulo_visDSD_ver_dados_juncao" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 50%;" role="document">
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

<!-- Modal -->
<div class="modal fade" id="edDSUC_criar_juncao" tabindex="-1" role="dialog" aria-labelledby="tituloEditarDSUC_criar_juncaoModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 27%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEditarDSUC_criar_juncaoModal">Criar Junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <script>
            </script>
            <div id="modalBodyEditarDSUC_criar_juncao" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function gerarFormMudarResponsável(id_responsavel,id_disciplina){
	var xhttp;    
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("modalBody_edDSUC_responsavel").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "phpUtil/formMudarResponsavel.php?id_responsavel=" + id_responsavel + "&id_disciplina=" + id_disciplina);
	xhttp.send();
}
function gerarFormCriarJuncao(id_juncao, id_turma, id_comp){
	//alert("Adicionar outros docentes que não: " + id_juncao + " na COMP:" + id_comp + " na TURMA: " + id_turma);
	$('#edDSUCdocente').modal('hide');
	$('#edDSUC_criar_juncao').modal('show');
	if(id_juncao == null){
		id_juncao = 0;
	}
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarDSUC_criar_juncao").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formCriarJuncaoedDSUC.php?id_juncao=" + id_juncao + "&id_turma=" + id_turma + "&id_comp=" + id_comp);
    xhttp.send();
}

function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_DSD");
	li_DSD.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function gerarFormAtribuirDocente(id_docente, id_comp, id_turma, id_juncao){
	//alert("Adicionar outros docentes que não: " + id_docente + " na COMP:" + id_comp + " na TURMA: " + id_turma);
	if(id_juncao == null){
		id_juncao = 0;
	}
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarDSUCdocente").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formAtribuirDocente.php?id=" + id_docente + "&comp=" + id_comp + "&turma=" + id_turma + "&juncao=" + id_juncao);
    xhttp.send();
	
	$(document).on('show.bs.modal', '#edDSUCdocente', function (e) {
/*		var dropdown_docentes = $(e.currentTarget).find("select[name='edDSUCatribuirDocente']");
		var opt = document.createElement('option');
		dropdown_docentes.append("<option=value='teste123'>Teste123</option>");
		dropdown_docentes.append("<option=value='teste124'>Teste124</option>");
		dropdown_docentes.append("<option=value='teste125'>Teste125</option>"); */
	});
}
function gerarFormAtribuirDocente2(id_comp, id_turma, id_juncao){
	if(id_juncao == null){
		id_juncao = 0;
	}
	//alert("Adicionar outros docentes que não: " + id_docente + " na COMP:" + id_comp + " na TURMA: " + id_turma);
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarDSUCdocente").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formAtribuirDocente.php?id=" + 0 + "&comp=" + id_comp + "&turma=" + id_turma + "&juncao=" + id_juncao);
    xhttp.send();
	
	$(document).on('show.bs.modal', '#edDSUCdocente', function (e) {
/*		var dropdown_docentes = $(e.currentTarget).find("select[name='edDSUCatribuirDocente']");
		var opt = document.createElement('option');
		dropdown_docentes.append("<option=value='teste123'>Teste123</option>");
		dropdown_docentes.append("<option=value='teste124'>Teste124</option>");
		dropdown_docentes.append("<option=value='teste125'>Teste125</option>"); */
	});
}

function mudarResponsavel(){
	
	const dropdown_docente = document.getElementById("edDSUC_mudar_responsavel");
	var docente_escolhido = dropdown_docente.value;
	
	if(docente_escolhido != "nada_selecionado"){
		$.ajax ({
			type: "POST",
			url: "processamento/mudarResponsavelUC.php", 
			data: {id_uc: <?php echo $id ?>, id_novo_docente: docente_escolhido},
			success: function(result) {
				if(result == "Sucesso"){
					location.reload();
				}
				else{
					alert("Erro ao alterar responsável: " + result);
				}
			},
			error: function(result) {
				alert("Erro ao alterar responsável: " + result);
			}
		});
	}
	else{
		$('#edDSUCresponsavel').modal('hide');
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
/*
function removerTurmaJuncao(id_turma,id_juncao){
	//alert("Remover turma: " + id_turma + " da junção: " + id_juncao);
	if(window.confirm("Pretende remover a turma da junção?")){
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

function dispersarJuncao(id_juncao){
	if(window.confirm("Pretende acabar com esta junção?")){
		$.ajax ({
			type: "POST",
			url: "processamento/removerTurmasJuncao.php", 
			data: {id_juncao: id_juncao},
			success: function(result) {
				location.reload();
			},
			error: function(result) {
				alert("Erro ao remover junção: " + result);
			}
		});
	}
}
*/
function mostrarListaDocentes(){

	var dropdown_docentes = document.getElementById("edDSUCatribuirDocente");
	
	var opt = document.createElement('option');
	opt.value = "teste123";
	opt.text = "teste123";
	dropdown_docentes.options.add(opt);
	
	/*
	//Mostrar a lista de UC's
	$.ajax ({
		type: "POST",
		url: "processamento/mostrarListaUCs.php", 
		data: {},
		success: function(result) {
			var array = result.split(',');
			//alert("UC's: " + array);
			
			for(i = 0; i <= array.length; i++){
				var opt = document.createElement('option');
				opt.value = array[i];
				opt.text = array[i];
				dropdown_docentes.options.add(opt);
			} 
	
		}
	});
	*/
}

function bloquearOutrasComponentes(id_juncao,id_turma){
	//alert("TESTE123");
	
	const div_juncoes = document.getElementsByClassName("div_juncoes")[0];
	var juncoes = document.getElementsByClassName("checkbox_turmas_a_juntar");
	
	//var checkbox = document.getElementById
	
	for(i = 0; i < juncoes.length; i++){
		if(juncoes[i].getAttribute("data-id_juncao") != 0){
			if(juncoes[i].checked){
				//Bloquear outras junções
				for(j = 0; j < juncoes.length; j++){
					if((juncoes[j].getAttribute("data-id_juncao") != 0) && (juncoes[i] != juncoes[j])){
						juncoes[j].style.visibility = "hidden";
					}
				}
			}
			else{
				//Mostrar outras junções
				for(j = 0; j < juncoes.length; j++){
					if((juncoes[j].getAttribute("data-id_juncao") != 0) && (juncoes[i] != juncoes[j])){
						juncoes[j].style.visibility = "visible";
					}
				}
			}
		}
	}
	
}

function bloquearOutrasJuncoes(id_juncao){
	
	const div_juncoes = document.getElementsByClassName("div_juncoes")[0];
	var juncoes = document.getElementsByClassName("checkbox_turmas_a_juntar");
	
	for(i = 0; i < juncoes.length; i++){
		if(juncoes[i].getAttribute("data-id_juncao") == id_juncao){
			if(juncoes[i].checked){
				//Bloquear outras junções
				for(j = 0; j < juncoes.length; j++){
					if((juncoes[j].getAttribute("data-id_juncao") != 0) && (juncoes[j].getAttribute("data-id_juncao") != id_juncao)){
						juncoes[j].style.visibility = "hidden";
					}
				}
			}
			else{
				//Mostrar outras junções
				for(j = 0; j < juncoes.length; j++){
					if((juncoes[j].getAttribute("data-id_juncao") != 0) && (juncoes[j].getAttribute("data-id_juncao") != id_juncao)){
						juncoes[j].style.visibility = "visible";
					}
				}
			}
		}
	}

}

function atribuirDocente(id_componente, id_turma, id_docente_original){	
	
	const dropdown_docente = document.getElementById("edDSUCatribuirDocente");
	var id_docente_escolhido = dropdown_docente.value;
	
	const card_body = document.getElementsByClassName("card-body")[0];
	var cartoes = document.getElementsByClassName("card_UC");
	
	const div_juncoes = document.getElementsByClassName("div_juncoes")[0];
	var juncoes = document.getElementsByClassName("checkbox_turmas_a_juntar");
	
	var num_juncoes_selecionadas = 0;
	
	for(i = 0; i < juncoes.length; i++){
		if(juncoes[i].checked){
			num_juncoes_selecionadas = num_juncoes_selecionadas + 1;
		}
	}
	
	if(num_juncoes_selecionadas > 0){
		//alert("Juntar à junção/turma!");
		
		var id_juncao_a_adicionar = 0;
		const array_ids_turmas = [];
		
		for(i = 0; i < juncoes.length; i++){
			if(juncoes[i].checked){
				if(juncoes[i].getAttribute("data-id_juncao") != 0){
					//Adicionar esta turma (e outras se tiverem selecionadas) à junção existente
					id_juncao_a_adicionar = juncoes[i].getAttribute("data-id_juncao");
					
					
				}
				else{
					array_ids_turmas.push(juncoes[i].getAttribute("data-id_turma"));
					array_ids_turmas.push(juncoes[i].getAttribute("data-id_componente"));
					//Juntar com a(s) turma(s) selecionada(s)
					
				}
			}
		}
		
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
	else{
		if(id_docente_original == id_docente_escolhido){
			$('#edDSUCdocente').modal('hide');
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

/*----------------------------------------CASO 2----------------------------------------*/

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
			while(nome_juncao.length < 10){
				nome_juncao = window.prompt("Introduza um nome válido! (10 caracteres)");
			}
		}
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
</script>

<?php gerarHome2() ?>
