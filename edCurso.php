<?php
// Página de gestão de cursos

session_start(); 

if (!isset($_SESSION["sessao"]) || !(isset($_SESSION['permAdmin']) || isset($_SESSION['permUTC']))) {
    header("Location: index.php");
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$id_utilizador = $_SESSION['id'];
$id_curso = $_GET["id"];

$is_admin = false;
$coordenador_UTC = false;

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_utilizador");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc_utilizador = $linha["id_utc"];
	
	if($linha["is_admin"] == 1){
		$is_admin = true;
	}
	
$statement1 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc_utilizador;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$id_responsavel_utc_utilizador = $linha1["id_responsavel"];
	
	if($id_responsavel_utc_utilizador == $id_utilizador){
		$coordenador_UTC = true;
	}
	
if(!$coordenador_UTC){
	header("Location: visCurso.php");
}
	
$statement2 = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = $id_curso;");
$statement2->execute();
$resultado2 = $statement2->get_result();
$linha2 = mysqli_fetch_assoc($resultado2);

	$id_curso = $linha2["id_curso"];			
	$nome_curso = $linha2["nome"];
	$sigla_curso = $linha2["sigla"];
	$semestres_curso = $linha2["semestres"];
	$anos_curso = $semestres_curso / 2;
	$imagem_curso = $linha2["imagem_curso"];
	$id_tipo_curso = $linha2["id_tipo_curso"];
	$id_coordenador = $linha2["id_coordenador"];
	$id_utc_curso = $linha2["id_utc"];
						
	$statement3 = mysqli_prepare($conn, "SELECT nome, imagem_perfil FROM utilizador WHERE id_utilizador = $id_coordenador;");
	$statement3->execute();
	$resultado3 = $statement3->get_result();
	$linha3 = mysqli_fetch_assoc($resultado3);
	
		$nome_coordenador = $linha3["nome"];
		$imagem_coordenador = $linha3["imagem_perfil"];
		
		if(strlen($nome_coordenador) > 20){
			$nome_coordenador = substr_replace($nome_coordenador,"...",(15-strlen($nome_coordenador)));
		}
							
		$statement4 = mysqli_prepare($conn, "SELECT sigla FROM curso_tipo WHERE id_tipo_curso = $id_tipo_curso;");
		$statement4->execute();
		$resultado4 = $statement4->get_result();
		$linha4 = mysqli_fetch_assoc($resultado4);
		
			$sigla_tipo_curso = $linha4["sigla"];
							
			$statement5 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina WHERE id_curso = $id_curso;");
			$statement5->execute();
			$resultado5 = $statement5->get_result();
			$linha5 = mysqli_fetch_assoc($resultado5);
						
				$num_disciplinas_curso = $linha5["COUNT(DISTINCT id_disciplina)"];
							
				$statement6 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM turma WHERE id_curso = $id_curso;");
				$statement6->execute();
				$resultado6 = $statement6->get_result();
				$linha6 = mysqli_fetch_assoc($resultado6);
						
					$num_turmas_curso = $linha6["COUNT(DISTINCT id_turma)"];

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
    <div class="container-fluid">
		<div class="card shadow mb-4">
			<div class="card-body">
				<a href="visCurso.php"><h6 style="margin-top:10px; margin-left:15px;">...</a> / <a href="visCursoDetalhes.php?id=<?php echo $id_curso; ?>"><?php echo $sigla_tipo_curso, " ", $sigla_curso ?></a> / <a href="">Editar</a></h6>
				<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 15px;">(<?php echo $sigla_tipo_curso; ?>) <b><?php echo $nome_curso?></b></h3>
				<a class='btn btn-danger' href='javascript:void(0);' onclick="removerCurso(<?php echo $id_curso ?>)" style='position:absolute; top:40px; right:45px; width:120px; height:60px; border-radius:25px; margin-left:15px; margin-top:5px;'><i class='material-icons' style='vertical-align: middle; line-height:45px; float:left;'>delete_forever</i>Remover Curso</a>
				<br>
				<text style="font-weight:500; margin-left:15px;">Coordenador:</text>
				<a class='btn btn-secondary' href='javascript:void(0);' data-toggle='modal' data-target='#edCoordenadorCurso' onclick="janelaEditarCoordenador(<?php echo $id_curso ?>,'<?php echo $sigla_tipo_curso; ?>','<?php echo $sigla_curso; ?>',<?php echo $id_coordenador ?>)" style='width:180px; height:40px; border-radius:25px; margin-left:15px; margin-top:5px;'><i class='material-icons' style='vertical-align: middle; float:left;'>person_search</i><?php echo $nome_coordenador ?></a>
				<text style="font-weight:500; margin-left:35px;">Duração:</text>
				<i class="material-icons" style="vertical-align:middle;">calendar_today</i><b> <?php echo $anos_curso; ?> anos</b>
				<text style="font-weight:500; margin-left:35px;">Sigla:</text>
				<a class='btn btn-secondary' href='javascript:void(0);' data-toggle='modal' data-target='#edSigla' onclick="janelaEditarSigla(<?php echo $id_curso ?>)" style='width:100px; height:40px; border-radius:25px; margin-left:15px; margin-top:5px;'><i class='material-icons' style='vertical-align: middle; float:left;'>edit</i><?php echo $sigla_curso ?></a>
				
				<div class="curso_detalhes_separador">
				</div>
				
				<div class="container_curso_ucs_turmas">
					<div class="curso_detalhes_ucs">
						<h4><i class="material-icons" style="vertical-align:middle;">class</i> Unidades Curriculares <a class="btn btn-primary" href="javascript:void(0);" data-toggle='modal' data-target='#adicionarUC' onclick="janelaAdicionarUC(<?php echo $id_curso; ?>)" style='width:150px; border-radius:25px; margin-left:15px;'><i class='material-icons' style='vertical-align: middle;'>add_circle</i> Adicionar UC</a></h4>
						<?php $counter_ano = 1;
						while($counter_ano <= $anos_curso){ ?>
							<h5 style="margin-left:15px;"><?php echo $counter_ano ?>º Ano</h5>
							<h6 style="margin-left:30px;">1º Semestre </h6>
								<?php 
									$statement7 = mysqli_prepare($conn, "SELECT DISTINCT * FROM disciplina WHERE id_curso = $id_curso AND ano = $counter_ano AND semestre = 1 ORDER BY nome_uc;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									while($linha7 = mysqli_fetch_assoc($resultado7)){	
										$id_disciplina = $linha7["id_disciplina"];
										$nome_disciplina = $linha7["nome_uc"];
										$codigo_disciplina = $linha7["codigo_uc"];
										?>
										<input type="checkbox" onclick="ucSelecionada(<?php echo $id_disciplina; ?>)" style="margin-right:3px; margin-left:45px;"><a href="javascript:void(0);" data-toggle='modal' data-target='#editarUC' onclick="janelaEditarUC(<?php echo $id_disciplina; ?>,'<?php echo $nome_disciplina; ?>')"><text><?php echo $nome_disciplina, " (", $codigo_disciplina, ") ";?></text></a><br><?php
									}
								?>
							<br>
							<h6 style="margin-left:30px;">2º Semestre </h6>
								<?php
									$statement8 = mysqli_prepare($conn, "SELECT DISTINCT * FROM disciplina WHERE id_curso = $id_curso AND ano = $counter_ano AND semestre = 2 ORDER BY nome_uc;");
									$statement8->execute();
									$resultado8 = $statement8->get_result();
									while($linha8 = mysqli_fetch_assoc($resultado8)){	
										$id_disciplina = $linha8["id_disciplina"];
										$nome_disciplina = $linha8["nome_uc"];
										$codigo_disciplina = $linha8["codigo_uc"];
										?>
										<input type="checkbox" onclick="ucSelecionada(<?php echo $id_disciplina; ?>)" style="margin-right:3px; margin-left:45px;"><a href="javascript:void(0);" data-toggle='modal' data-target='#editarUC' onclick="janelaEditarUC(<?php echo $id_disciplina; ?>,'<?php echo $nome_disciplina; ?>')"><text><?php echo $nome_disciplina, " (", $codigo_disciplina, ") ";?></text></a><br><?php
									}
								?>
							<br><br>
						<?php	$counter_ano += 1;
						}
						?>
					</div>
					<div class="curso_detalhes_turmas">
						<h4><i class="material-icons" style="vertical-align:middle;">people</i> Turmas <a class="btn btn-primary" href="javascript:void(0);" data-toggle='modal' data-target='#adicionarTurma' onclick="janelaAdicionarTurma(<?php echo $id_curso; ?>)" style='width:170px; border-radius:25px; margin-left:15px;'><i class='material-icons' style='vertical-align: middle;'>add_circle</i> Adicionar Turma</a>
						<a class="btn btn-danger" id="botao_remover_turmas" title="Remover Itens" href="javascript:void(0);" onclick="removerUCsTurmas()" style='width:65px; border-radius:25px; margin-left:200px; opacity:0.5;'><i class='material-icons' style='vertical-align: middle; margin-left:-5px; margin-top:-4px;'>delete_forever</i><i>(0)</i></a>
						</h4>
						<?php $counter_ano = 1;
						while($counter_ano <= $anos_curso){ ?>
							<div class="curso_detalhes_turma_ano">
								<h5 style="margin-left:15px;"><?php echo $counter_ano ?>º Ano</h5>
								<div class="curso_detalhes_turma_sem">
									<h6 style="margin-left:30px;">1º Semestre </h6>
										<?php 
											$statement8 = mysqli_prepare($conn, "SELECT DISTINCT * FROM turma WHERE id_curso = $id_curso AND ano = $counter_ano AND semestre = 1 ORDER BY nome;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											while($linha8 = mysqli_fetch_assoc($resultado8)){	
												$id_turma = $linha8["id_turma"];
												$nome_turma = $linha8["nome"];
												?>
												<input type="checkbox" onclick="turmaSelecionada(<?php echo $id_turma; ?>)" style="margin-right:3px; margin-left:45px;"><a href="javascript:void(0);" data-toggle='modal' data-target='#editarTurma' onclick="janelaEditarTurma(<?php echo $id_turma; ?>)"><text><?php echo $nome_turma;?></text></a><br><?php
											}
										?>
								</div>
								<div class="curso_detalhes_turma_sem">
									<h6 style="margin-left:30px;">2º Semestre </h6>
										<?php
											$statement8 = mysqli_prepare($conn, "SELECT DISTINCT * FROM turma WHERE id_curso = $id_curso AND ano = $counter_ano AND semestre = 2 ORDER BY nome;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											while($linha8 = mysqli_fetch_assoc($resultado8)){	
												$id_turma = $linha8["id_turma"];
												$nome_turma = $linha8["nome"];
												?>
												<input type="checkbox" onclick="turmaSelecionada(<?php echo $id_turma; ?>)" style="margin-right:3px; margin-left:45px;"><a href="javascript:void(0);" data-toggle='modal' data-target='#editarTurma' onclick="janelaEditarTurma(<?php echo $id_turma; ?>)"><text><?php echo $nome_turma;?></text></a><br><?php
											}
										?>
								</div>
							</div>
							<br><br>
						<?php	$counter_ano += 1;
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<!-- Modal -->
<div class="modal fade" id="edCoordenadorCurso" tabindex="-1" role="dialog" aria-labelledby="titulo_edCoordenadorCurso" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 20%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_edCoordenadorCurso"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_edCoordenadorCurso" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edSigla" tabindex="-1" role="dialog" aria-labelledby="titulo_edSigla" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 20%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_edSigla"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_edSigla" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="adicionarUC" tabindex="-1" role="dialog" aria-labelledby="titulo_adicionarUC" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 28%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_adicionarUC"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_adicionarUC" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarUC" tabindex="-1" role="dialog" aria-labelledby="titulo_editarUC" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 28%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_editarUC"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_editarUC" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="adicionarTurma" tabindex="-1" role="dialog" aria-labelledby="titulo_adicionarTurma" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 20%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_adicionarTurma"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_adicionarTurma" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarTurma" tabindex="-1" role="dialog" aria-labelledby="titulo_editarTurma" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 20%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_editarTurma"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_editarTurma" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function configurarMenuTopo(){
	var li_curso = document.getElementById("li_CURSO");
	li_curso.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function janelaEditarCoordenador(id_curso,sigla_tipo_curso,sigla_curso,id_coordenador){
  document.getElementById("titulo_edCoordenadorCurso").innerHTML = "Coordenador: " + sigla_tipo_curso + " " + sigla_curso;
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_edCoordenadorCurso").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/curso/alterarCoordenador.php?id_curso=" + id_curso + "&id_coordenador_atual=" + id_coordenador);
  xhttp.send();
}

function alterarCoordenador(){
	
	const coordenador_atual = <?php echo $id_coordenador; ?>;
	const select_docente = document.getElementById("alterarCoordenador");
	const docente_escolhido = select_docente.value;
	
	const id_curso = <?php echo $id_curso; ?>;
	
	if(docente_escolhido == coordenador_atual || docente_escolhido == ""){
		$('#edCoordenadorCurso').modal('hide');
	}
	else{
		$.ajax ({
			type: "POST",
			url: "processamento/curso/alterarCoordenador.php", 
			data: {id_curso: id_curso,id_novo_coordenador: docente_escolhido},
			success: function(result) {
				location.reload();
			},
			error: function(result) {
				alert("Erro ao alterar coordenador: " + result);
			}
		});
	}
}

function janelaEditarSigla(id_curso){
  document.getElementById("titulo_edSigla").innerHTML = "Editar sigla";
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_edSigla").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/curso/editarSigla.php?id_curso=" + id_curso);
  xhttp.send();
}

function atualizarSigla(sigla_atual){
	
	const input_sigla = document.getElementById("edSiglaIntroduzida");
	const sigla_introduzida = input_sigla.value;
	
	const id_curso = <?php echo $id_curso; ?>;
	
	if(sigla_introduzida == sigla_atual){
		$('#edSigla').modal('hide');
	}
	else{
		if(sigla_introduzida.length == 0 || sigla_introduzida.length == 1){
			alert("Introduza uma sigla válida! (pelo menos dois caracteres)");
			input_sigla.focus();
		}
		else if(!/^[a-zA-Z]+$/.test(sigla_introduzida)){
			alert("Sigla apenas pode conter caracteres! (A - Z)");
			input_sigla.focus();
		}
		else{
			$.ajax ({
				type: "POST",
				url: "processamento/curso/atualizarSigla.php", 
				data: {id_curso: id_curso, sigla_introduzida: sigla_introduzida},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao atualizar sigla: " + result);
				}
			});
								
		}
	}
	
}

function removerCurso(id_curso){
	if(window.confirm("Tem a certeza que pretende eliminar este curso? (Irá eliminar todas as turmas e disciplinas associadas)")){
		$.ajax ({
			type: "POST",
			url: "processamento/curso/removerCurso.php", 
			data: {id_curso: id_curso},
			success: function(result) {
				window.location.href = "http://localhost/apoio_utc/visCurso.php";
			},
			error: function(result) {
				alert("Erro ao remover curso: " + result);
			}
		});
	}
}

function janelaAdicionarUC(id_curso){
  document.getElementById("titulo_adicionarUC").innerHTML = "Adicionar UC";
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_adicionarUC").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/curso/adicionarUC.php?id_curso=" + id_curso);
  xhttp.send();
}

const dados_principais = [];
const componentes_introduzidas = [];
var esta_pagina_componentes = false;

function adicionarUC_componentes(){
	
	//Armazenar os dados principais introduzidos num array
	const input_nome = document.getElementById("adicionarUC_nome");
	const input_codigo = document.getElementById("adicionarUC_codigo");
	const input_abreviacao = document.getElementById("adicionarUC_abreviacao");
	const input_ano = document.getElementById("adicionarUC_ano");
	const input_semestre = document.getElementById("adicionarUC_semestre");
	const input_area = document.getElementById("adicionarUC_area");
	const input_responsavel = document.getElementById("adicionarUC_responsavel");
	
	const nome = input_nome.value;
	const codigo = input_codigo.value;
	const abreviacao = input_abreviacao.value;
	const ano = input_ano.value;
	const semestre = input_semestre.value;
	const id_area = input_area.value;
	const id_responsavel = input_responsavel.value;
	
	dados_principais[0] = nome;
	dados_principais[1] = codigo;
	dados_principais[2] = abreviacao;
	dados_principais[3] = ano;
	dados_principais[4] = semestre;
	dados_principais[5] = id_area;
	dados_principais[6] = id_responsavel;
	
	
	//Mostrar a janela dos componentes
	$('#adicionarUC_dados_principais').remove();
	const div_principal = document.getElementById("adicionarUC_div_principal");
	
	var div_componentes = document.createElement('div');
	div_componentes.id = "adicionarUC_componentes";
	
	var botao_voltar = document.createElement('img');
	botao_voltar.setAttribute("src","http://localhost/apoio_utc/images/curso/voltar_dados_principais.png");
	botao_voltar.setAttribute("onclick","adicionarUC_dados_principais()");
	botao_voltar.style.height = '30px';
	botao_voltar.style.width = '30px';
	botao_voltar.style.borderRadius = '50%';
	botao_voltar.style.cursor = 'pointer';
	
	div_componentes.appendChild(botao_voltar);
	
	var br = document.createElement('br');
	
	div_componentes.appendChild(br);
	
	var titulo_componentes = document.createElement('h6');
	titulo_componentes.innerHTML = "Componentes: ";
	titulo_componentes.style.marginLeft = "10px";
	titulo_componentes.style.marginTop = "30px";
	
	div_componentes.appendChild(titulo_componentes);
	
	var select_componente = document.createElement('select');
	select_componente.id = "adicionarUC_select_componente";
	select_componente.style.width = "185px";
	select_componente.style.marginLeft = "10px";
	
		var opcao_componente_vazia = document.createElement('option');
		opcao_componente_vazia.value = "";
		opcao_componente_vazia.innerHTML = "";
		
		select_componente.appendChild(opcao_componente_vazia);
		
		$.ajax ({
			type: "POST",
			url: "processamento/curso/verTiposComponentesDisponiveis.php", 
			data: {},
			success: function(result) {
				var array_componentes = result.split(",");
				
				for(i = 0; i < array_componentes.length; i = i + 3){
					var opcao_componente = document.createElement('option');
					opcao_componente.value = array_componentes[i];
					opcao_componente.innerHTML = array_componentes[i + 1] + " (" + array_componentes[i + 2] + ")";
					
					select_componente.appendChild(opcao_componente);
				}
				
				div_componentes.appendChild(select_componente);
				
				var botao_adicionar_componente = document.createElement('img');
				botao_adicionar_componente.setAttribute("src","http://localhost/apoio_utc/images/curso/adicionar_componente.png");
				botao_adicionar_componente.setAttribute("onclick","adicionarComponenteTemporaria()");
				botao_adicionar_componente.style.width = "30px";
				botao_adicionar_componente.style.height = "30px";
				botao_adicionar_componente.style.borderRadius = "50%";
				botao_adicionar_componente.style.marginLeft = "10px";
				botao_adicionar_componente.style.marginBottom = "2px";
				botao_adicionar_componente.style.cursor = "pointer";
				
				div_componentes.appendChild(botao_adicionar_componente);
				
				div_componentes.innerHTML += "<br><br>";
				
				var titulo_carga_horaria = document.createElement('h6');
				titulo_carga_horaria.innerHTML = "Horas: ";
				titulo_carga_horaria.style.marginLeft = "10px";
				
				div_componentes.appendChild(titulo_carga_horaria);
				
				var select_horas = document.createElement('select');
				select_horas.id = "adicionarUC_select_componente_horas";
				select_horas.style.width = "40px";
				select_horas.style.marginLeft = "10px";
				
				var opcao_hora_vazia = document.createElement('option');
				opcao_hora_vazia.value = "";
				opcao_hora_vazia.innerHTML = "";
				
				select_horas.appendChild(opcao_hora_vazia);
				
				for(i = 1; i < 5.5; i = i + 0.5){
					var opcao_horas = document.createElement('option');
					opcao_horas.value = i;
					
					opcao_horas.innerHTML = i;
									
					select_horas.appendChild(opcao_horas);
				}
				
				div_componentes.appendChild(select_horas);
				
				var titulo_horas = document.createElement('text');
				titulo_horas.innerHTML = " H";
				
				div_componentes.appendChild(titulo_horas);
				
				var div_componentes_temp = document.createElement('div');
				div_componentes_temp.id = "adicionarUC_componentes_temp";
				div_componentes_temp.style.marginTop = "15px";
				div_componentes_temp.style.borderTop = "1px solid #1a1919";
				div_componentes_temp.style.padding = "10px";
				
				for(i = 0; i < componentes_introduzidas.length; i = i + 3){
					div_componentes_temp.innerHTML += componentes_introduzidas[i + 1] + " - <i>" + componentes_introduzidas[i + 2] + "H</i>";
					div_componentes_temp.innerHTML += "<br>";
				}
				
				div_componentes.appendChild(div_componentes_temp);
				
				div_principal.appendChild(div_componentes);

				esta_pagina_componentes = true;
			}
		});
		
}

function adicionarComponenteTemporaria(){
	
	const select_componente = document.getElementById("adicionarUC_select_componente");
	const componente_selecionada = select_componente.value;
	const temp = $("#adicionarUC_select_componente option:selected");
	const nome_componente_selecionada = temp.text();
	
	const select_hora = document.getElementById("adicionarUC_select_componente_horas");
	const hora_selecionada = select_hora.value;
	
	const div_componentes_temp = document.getElementById("adicionarUC_componentes_temp");
	
	if(componente_selecionada == ""){
		alert("Selecione uma componente!");
		select_componente.focus();
	}
	else if(hora_selecionada == ""){
		alert("Selecione uma carga horária!");
		select_hora.focus();
	}
	else{
		div_componentes_temp.innerHTML += nome_componente_selecionada + " - <i>" + hora_selecionada + "H</i>";
		div_componentes_temp.innerHTML += "<br>";
		select_componente.selectedIndex = 0;
		select_hora.selectedIndex = 0;
		componentes_introduzidas.push(componente_selecionada);
		componentes_introduzidas.push(nome_componente_selecionada);
		componentes_introduzidas.push(hora_selecionada);
	}
	
}

function adicionarUC_dados_principais(){
	
	$('#adicionarUC_componentes').remove();
	
	const div_principal = document.getElementById("adicionarUC_div_principal");
	
	var div_dados_principais = document.createElement('div');
	div_dados_principais.id = "adicionarUC_dados_principais";
	
	var texto_nome = document.createElement('text');
	texto_nome.innerHTML = "Nome:";
	texto_nome.style.fontWeight = "500";
	texto_nome.style.marginRight = "50px";
	
	var input_nome = document.createElement('input');
	input_nome.type = "text";
	if(dados_principais[0] != null){
		input_nome.value = dados_principais[0];
	}
	input_nome.id = "adicionarUC_nome";
	input_nome.maxlength = "50";
	input_nome.style.width = "200px";
	
	var br1 = document.createElement('br');
	var br2 = document.createElement('br');
	
	div_dados_principais.appendChild(texto_nome);
	div_dados_principais.appendChild(input_nome);
	div_dados_principais.appendChild(br1);
	div_dados_principais.appendChild(br2);
	
	/*-------------------------------------------------*/
	
	var texto_codigo = document.createElement('text');
	texto_codigo.innerHTML = "Código:";
	texto_codigo.style.fontWeight = "500";
	texto_codigo.style.marginRight = "42px";
	
	var input_codigo = document.createElement('input');
	input_codigo.type = "text";
	if(dados_principais[1] != null){
		input_codigo.value = dados_principais[1];
	}
	input_codigo.id = "adicionarUC_codigo";
	input_codigo.maxlength = "5";
	input_codigo.style.width = "60px";
	
	var br3 = document.createElement('br');
	var br4 = document.createElement('br');
	
	div_dados_principais.appendChild(texto_codigo);
	div_dados_principais.appendChild(input_codigo);
	div_dados_principais.appendChild(br3);
	div_dados_principais.appendChild(br4);
	
	/*-------------------------------------------------*/
	
	var texto_abreviacao = document.createElement('text');
	texto_abreviacao.innerHTML = "Abreviação:";
	texto_abreviacao.style.fontWeight = "500";
	texto_abreviacao.style.marginRight = "14px";
	
	var input_abreviacao = document.createElement('input');
	input_abreviacao.type = "text";
	if(dados_principais[2] != null){
		input_abreviacao.value = dados_principais[2];
	}
	input_abreviacao.id = "adicionarUC_abreviacao";
	input_abreviacao.maxlength = "5";
	input_abreviacao.style.width = "70px";
	
	var br5 = document.createElement('br');
	var br6 = document.createElement('br');
	
	div_dados_principais.appendChild(texto_abreviacao);
	div_dados_principais.appendChild(input_abreviacao);
	div_dados_principais.appendChild(br5);
	div_dados_principais.appendChild(br6);
		
	/*-------------------------------------------------*/
	
	var texto_ano = document.createElement('text');
	texto_ano.innerHTML = "Ano:";
	texto_ano.style.fontWeight = "500";
	texto_ano.style.marginRight = "65px";
	
	var select_ano = document.createElement('select');
	select_ano.id = "adicionarUC_ano";
	select_ano.style.width = "40px";
	
		var opcao_ano_vazia = document.createElement('option');
		opcao_ano_vazia.value = "";
		opcao_ano_vazia.innerHTML = "";
	
		select_ano.appendChild(opcao_ano_vazia);
	
		const num_anos_curso = <?php echo $anos_curso; ?>;
		for(i = 1; i <= num_anos_curso; i++){
			var opcao_ano = document.createElement('option');
			opcao_ano.value = i;
			opcao_ano.innerHTML = i;
			
			select_ano.appendChild(opcao_ano);
		}
		
	if(dados_principais[3] != null){
		select_ano.selectedIndex = dados_principais[3];
	}
	
	var br7 = document.createElement('br');
	var br8 = document.createElement('br');
	
	div_dados_principais.appendChild(texto_ano);
	div_dados_principais.appendChild(select_ano);
	div_dados_principais.appendChild(br7);
	div_dados_principais.appendChild(br8);
	
	/*-------------------------------------------------*/
	
	var texto_semestre = document.createElement('text');
	texto_semestre.innerHTML = "Semestre:";
	texto_semestre.style.fontWeight = "500";
	texto_semestre.style.marginRight = "28px";
	
	var select_semestre = document.createElement('select');
	select_semestre.id = "adicionarUC_semestre";
	select_semestre.style.width = "40px";
	
		var opcao_semestre_vazia = document.createElement('option');
		opcao_semestre_vazia.value = "";
		opcao_semestre_vazia.innerHTML = "";
	
		var opcao_semestre1 = document.createElement('option');
		opcao_semestre1.value = 1;
		opcao_semestre1.innerHTML = "1";
		
		var opcao_semestre2 = document.createElement('option');
		opcao_semestre2.value = 2;
		opcao_semestre2.innerHTML = "2";
		
		select_semestre.appendChild(opcao_semestre_vazia);
		select_semestre.appendChild(opcao_semestre1);
		select_semestre.appendChild(opcao_semestre2);
	
	if(dados_principais[4] != null){
		select_semestre.selectedIndex = dados_principais[4];
	}
	
	var br9 = document.createElement('br');
	var br10 = document.createElement('br');
	
	div_dados_principais.appendChild(texto_semestre);
	div_dados_principais.appendChild(select_semestre);
	div_dados_principais.appendChild(br9);
	div_dados_principais.appendChild(br10);
	
	/*-------------------------------------------------*/
	
	var texto_area = document.createElement('text');
	texto_area.innerHTML = "Área:";
	texto_area.style.fontWeight = "500";
	texto_area.style.marginRight = "61px";
	
	var select_area = document.createElement('select');
	select_area.id = "adicionarUC_area";
	select_area.style.width = "100px";
	
		var opcao_area_vazia = document.createElement('option');
		opcao_area_vazia.value = "";
		opcao_area_vazia.innerHTML = "";
		
		select_area.appendChild(opcao_area_vazia);
		
		$.ajax ({
			type: "POST",
			url: "processamento/curso/verAreasDisponiveis.php", 
			data: {},
			success: function(result) {
				const array_areas = result.split(",");
				
				var loop_opcoes_areas = 1;
				for(i = 0; i < array_areas.length; i = i + 2){
					var opcao_area = document.createElement('option');
					opcao_area.value = array_areas[i];
					opcao_area.innerHTML = array_areas[i + 1];
					
					select_area.appendChild(opcao_area);
					
					if(dados_principais[5] != null){
						if(array_areas[i] == dados_principais[5]){
							select_area.selectedIndex = loop_opcoes_areas;
						}
					}
					
					loop_opcoes_areas += 1;
				}
				
				var br11 = document.createElement('br');
				var br12 = document.createElement('br');
				
				div_dados_principais.appendChild(texto_area);
				div_dados_principais.appendChild(select_area);
				div_dados_principais.appendChild(br11);
				div_dados_principais.appendChild(br12);
					
				/*-------------------------------------------------*/
				
				var texto_responsavel = document.createElement('text');
				texto_responsavel.innerHTML = "Responsável:";
				texto_responsavel.style.fontWeight = "500";
				texto_responsavel.style.marginRight = "5px";
				
				var select_responsavel = document.createElement('select');
				select_responsavel.id = "adicionarUC_responsavel";
				select_responsavel.style.width = "200px";
				
					var opcao_responsavel_vazia = document.createElement('option');
					opcao_responsavel_vazia.value = "";
					opcao_responsavel_vazia.innerHTML = "";
					
					select_responsavel.appendChild(opcao_responsavel_vazia);
					
					const id_utc_curso = <?php echo $id_utc_curso; ?>;
					
					$.ajax ({
						type: "POST",
						url: "processamento/curso/verDocentesDisponiveisUTC.php", 
						data: {id_utc_curso: id_utc_curso},
						success: function(result) {
							//alert("Result: " + result);
							const array_docentes = result.split(",");
							
							var opcao_responsavel_nome_utc = document.createElement('option');
							opcao_responsavel_nome_utc.value = "";
							opcao_responsavel_nome_utc.innerHTML = "            (" + array_docentes[0] + ")";
							
							select_responsavel.appendChild(opcao_responsavel_nome_utc);
							
							var loop_opcoes_docentes = 2;
							for(i = 1; i < array_docentes.length; i = i + 2){
								var opcao_responsavel = document.createElement('option');
								opcao_responsavel.value = array_docentes[i];
								opcao_responsavel.innerHTML = array_docentes[i + 1];
								
								select_responsavel.appendChild(opcao_responsavel);
								
								if(dados_principais[6] != null){
									if(array_docentes[i] == dados_principais[6]){
										select_responsavel.selectedIndex = loop_opcoes_docentes;
									}
								}
								
								loop_opcoes_docentes += 1;
							}
					
							$.ajax ({
								type: "POST",
								url: "processamento/curso/verDocentesDisponiveisUTC_outra.php", 
								data: {id_utc_curso: id_utc_curso},
								success: function(result) {
									const array_docentes_outros = result.split(",");
									
									var opcao_responsavel_vazia_outra = document.createElement('option');
									opcao_responsavel_vazia_outra.value = "";
									opcao_responsavel_vazia_outra.innerHTML = "";
													
									select_responsavel.appendChild(opcao_responsavel_vazia_outra);
													
									var opcao_responsavel_nome_utc_outra = document.createElement('option');
									opcao_responsavel_nome_utc_outra.value = "";
									opcao_responsavel_nome_utc_outra.innerHTML = "               (Outros)";
									
									select_responsavel.appendChild(opcao_responsavel_nome_utc_outra);
					
									loop_opcoes_docentes += 3;
					
									for(i = 0; i < array_docentes_outros.length; i = i + 2){
										var opcao_responsavel_outro = document.createElement('option');
										opcao_responsavel_outro.value = array_docentes_outros[i];
										opcao_responsavel_outro.innerHTML = array_docentes_outros[i + 1];
										
										select_responsavel.appendChild(opcao_responsavel_outro);
										
										if(dados_principais[6] != null){
											if(array_docentes_outros[i] == dados_principais[6]){
												select_responsavel.selectedIndex = loop_opcoes_docentes;
											}
										}
										
										loop_opcoes_docentes += 1;
									}
					
									var br13 = document.createElement('br');
									var br14 = document.createElement('br');
									
									div_dados_principais.appendChild(texto_responsavel);
									div_dados_principais.appendChild(select_responsavel);
									div_dados_principais.appendChild(br13);
									div_dados_principais.appendChild(br14);	
									
									/*-------------------------------------------------*/
									
									var texto_componentes = document.createElement('text');
									texto_componentes.innerHTML = "Componentes (" + (componentes_introduzidas.length / 3) + ") : ";
									texto_componentes.id = "adicionarUC_texto";
									texto_componentes.style.fontWeight = "500";
									texto_componentes.style.marginRight = "5px";
									/*
									var opcao_componentes_1 = document.createElement('input');
									opcao_componentes_1.type = "checkbox";
									opcao_componentes_1.id = "checkbox_opcao_1";
									opcao_componentes_1.value = 1;
									opcao_componentes_1.setAttribute("onclick","opcao_predefinida_selecionada('checkbox_opcao_1')");
									opcao_componentes_1.style.marginTop = "5px";
									opcao_componentes_1.style.marginLeft = "10px";
									
									var opcao_componentes_1_texto = document.createElement('text');
									opcao_componentes_1_texto.innerHTML = " TP (<i>2H</i>) + P (<i>3H</i>)";
									
									var opcao_componentes_2 = document.createElement('input');
									opcao_componentes_2.type = "checkbox";
									opcao_componentes_2.id = "checkbox_opcao_2";
									opcao_componentes_1.value = 2;
									opcao_componentes_1.setAttribute("onclick","opcao_predefinida_selecionada('checkbox_opcao_2')");
									opcao_componentes_2.style.marginTop = "5px";
									opcao_componentes_2.style.marginLeft = "10px";
									
									var opcao_componentes_2_texto = document.createElement('text');
									opcao_componentes_2_texto.innerHTML = " TP (<i>2H</i>) + TP (<i>2H</i>)";
									*/
									var imagem_ver_componentes = document.createElement('img');;
									imagem_ver_componentes.setAttribute("src","http://localhost/apoio_utc/images/curso/ver_componentes.png");
									imagem_ver_componentes.setAttribute("onclick","adicionarUC_componentes()");
									imagem_ver_componentes.style.height = '30px';
									imagem_ver_componentes.style.width = '30px';
									imagem_ver_componentes.style.borderRadius = '50%';
									imagem_ver_componentes.style.cursor = 'pointer';
									
									var br17 = document.createElement('br');
									
									var checkbox_1 = document.createElement('input');
									checkbox_1.type = "checkbox";
									checkbox_1.id = "pre-definido_1";
									if(opcao_1_selecionada){
										checkbox_1.setAttribute("checked",true);
									}
									checkbox_1.setAttribute("onchange","componentePreDefinido1()");
									checkbox_1.style.marginLeft = "10px";
									checkbox_1.style.marginRight = "5px";
									checkbox_1.style.verticalAlign = "middle";
									
									var texto_checkbox_1 = document.createElement('text');
									texto_checkbox_1.innerHTML = "<b>TP</b> (<i>2H</i>) + <b>P</b> (<i>3H</i>)";
									
									var br18 = document.createElement('br');
									
									var checkbox_2 = document.createElement('input');
									checkbox_2.type = "checkbox";
									checkbox_2.id = "pre-definido_2";
									if(opcao_2_selecionada){
										checkbox_2.setAttribute("checked",true);
									}
									checkbox_2.setAttribute("onchange","componentePreDefinido2()");
									checkbox_2.style.marginLeft = "10px";
									checkbox_2.style.marginRight = "5px";
									checkbox_2.style.verticalAlign = "middle";
									
									var texto_checkbox_2 = document.createElement('text');
									texto_checkbox_2.innerHTML = "<b>TP</b> (<i>2H</i>) + <b>TP</b> (<i>2H</i>)";
									
									div_dados_principais.appendChild(texto_componentes);
									div_dados_principais.appendChild(imagem_ver_componentes);
			/*						div_dados_principais.innerHTML += "<br>";
									div_dados_principais.appendChild(opcao_componentes_1);
									div_dados_principais.appendChild(opcao_componentes_1_texto);
									div_dados_principais.innerHTML += "<br>";
									div_dados_principais.appendChild(opcao_componentes_2);
									div_dados_principais.appendChild(opcao_componentes_2_texto);
									div_dados_principais.innerHTML += "<br>"; */
									div_dados_principais.appendChild(br17);
									div_dados_principais.appendChild(checkbox_1);
									div_dados_principais.appendChild(texto_checkbox_1);
									div_dados_principais.appendChild(br18);	
									div_dados_principais.appendChild(checkbox_2);
									div_dados_principais.appendChild(texto_checkbox_2);
										
									div_principal.appendChild(div_dados_principais);
									
									esta_pagina_componentes = false;
								}
							});
								
						}
					});
										
			}
		});
	
}

function adicionarUC(){
	
	if(esta_pagina_componentes){
		adicionarUC_dados_principais();
	}
	
	const input_nome = document.getElementById("adicionarUC_nome");
	const input_codigo = document.getElementById("adicionarUC_codigo");
	const input_abreviacao = document.getElementById("adicionarUC_abreviacao");
	const input_ano = document.getElementById("adicionarUC_ano");
	const input_semestre = document.getElementById("adicionarUC_semestre");
	const input_area = document.getElementById("adicionarUC_area");
	const input_responsavel = document.getElementById("adicionarUC_responsavel");
		
	const nome = input_nome.value;
	const codigo = input_codigo.value;
	const abreviacao = input_abreviacao.value;
	const ano = input_ano.value;
	const semestre = input_semestre.value;
	const id_area = input_area.value;
	const id_responsavel = input_responsavel.value;
	const id_curso = <?php echo $id_curso; ?>;
		
	var codigo_ja_existe = false;
		
	$.ajax ({
		type: "POST",
		url: "processamento/curso/verificarCodigoUsado.php", 
		data: {codigo: codigo},
		success: function(result) {
			if(result == 1){
				codigo_ja_existe = true;
			}
			if(nome.length < 10){
				alert("Introduza um nome válido! (pelo menos 10 caracteres)");
				input_nome.focus();
			}	
			
			else if(codigo.length == 0){
				alert("Introduza um código curricular!");
				input_codigo.focus();
			}
			
			else if(Boolean(codigo_ja_existe)){
				alert("Código curricular já usado!");
				input_codigo.focus();
			}
				
			else if((codigo % 1) != 0){
				alert("Introduzia um código curricular válido! (apenas números!)");
				input_codigo.focus();
			}
				
			else if(abreviacao.length < 2){
				alert("Introduza uma abreviacao válida! (pelo menos 2 caracteres)");
				input_abreviacao.focus();
			}
			
			else if(ano == ""){
				alert("Escolha um ano!");
				input_ano.focus();
			}
			else if(semestre == ""){
				alert("Escolha um semestre!");
				input_semestre.focus();
			}
				
			else if(id_area == ""){
				alert("Escolha uma área científica!");
				input_area.focus();
			}
				
			else if(id_responsavel == ""){
				alert("Escolha um responsável!");
				input_responsavel.focus();
			}
			else if(componentes_introduzidas.length == 0){
				alert("Introduza pelo menos um componente!");
				if(!esta_pagina_componentes){
					adicionarUC_componentes();
				}
			}
			else{
				//alert("Criar UC!");
				$.ajax ({
					type: "POST",
					url: "processamento/curso/adicionarUC.php", 
					data: {nome: nome, codigo: codigo, abreviacao: abreviacao, ano: ano, semestre: semestre, id_area: id_area, id_responsavel: id_responsavel, id_curso: id_curso, componentes_introduzidas: componentes_introduzidas},
					success: function(result) {
						location.reload();
					},
					error: function(result) {
						alert("Erro ao adicionar UC: " + result);
					}
				});
				
			}
		}
	});		
	
}

/*----------------------EDITAR UC----------------------*/
function janelaEditarUC(id_uc,nome_uc){
	document.getElementById("titulo_editarUC").innerHTML = "Editar UC: " + nome_uc;
	var xhttp;    
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  document.getElementById("modalBody_editarUC").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "phpUtil/curso/editarUC.php?id_uc=" + id_uc);
	xhttp.send();
}

function atualizarUC(id_uc,nome_atual,codigo_atual,abreviacao_atual,area_atual,responsavel_atual){
	
	const input_nome = document.getElementById("editarUC_nome");
	const input_codigo = document.getElementById("editarUC_codigo");
	const input_abreviacao = document.getElementById("editarUC_abreviacao");
	const select_area = document.getElementById("editarUC_area");
	const select_responsavel = document.getElementById("editarUC_responsavel");
	
	const nome_introduzido = input_nome.value;
	const codigo_introduzido = input_codigo.value;
	const abreviacao_introduzida = input_abreviacao.value;
	const area_introduzida = select_area.value;
	const responsavel_introduzido = select_responsavel.value;
	
	var codigo_ja_existe = false;
	
	/*
	alert(nome_atual + " - " + nome_introduzido);
	alert(codigo_atual + " - " + codigo_introduzido);
	alert(abreviacao_atual + " - " + abreviacao_introduzida);
	alert(area_atual + " - " + area_introduzida);
	alert(responsavel_atual + " - " + responsavel_introduzido);
	*/
	
	$.ajax ({
		type: "POST",
		url: "processamento/curso/verificarCodigoUsado.php", 
		data: {codigo: codigo_introduzido},
		success: function(result) {
			if(result == 1 && codigo_introduzido != codigo_atual){
				codigo_ja_existe = true;
			}
			
			if(nome_introduzido == nome_atual && codigo_introduzido == codigo_atual && abreviacao_introduzida == abreviacao_atual && area_introduzida == area_atual && responsavel_introduzido == responsavel_atual){
				$('#editarUC').modal('hide');
			}
			else if(nome_introduzido == ""){
				alert("Introduza um nome!");
				input_nome.focus;
			}
			else if(nome_introduzido.length < 10){
				alert("Introduza um nome válido! (pelo menos 10 caracteres)");
				input_nome.focus();
			}
			else if(codigo_introduzido == ""){
				alert("Introduza um código curricular!");
				input_codigo.focus;
			}
			else if(Boolean(codigo_ja_existe)){
				alert("Código curricular já usado!");
				input_codigo.focus();
			}
				
			else if((codigo_introduzido % 1) != 0){
				alert("Introduzia um código curricular válido! (apenas números!)");
				input_codigo.focus();
			}
			else if(abreviacao_introduzida == ""){
				alert("Introduza uma abreviacão!");
				input_abreviacao.focus;
			}
			else if(abreviacao_introduzida.length < 2){
				alert("Introduza uma abreviacao válida! (pelo menos 2 caracteres)");
				input_abreviacao.focus();
			}
			else if(area_introduzida == ""){
				alert("Selecione uma área!");
				select_area.focus;
			}
			else if(responsavel_introduzido == ""){
				alert("Selecione um responsável!");
				select_responsavel.focus;
			}
			else{
				alert("Atualizar!");
				$.ajax ({
					type: "POST",
					url: "processamento/curso/atualizarUC.php", 
					data: {id_uc: id_uc, nome_introduzido: nome_introduzido, codigo_introduzido: codigo_introduzido, abreviacao_introduzida: abreviacao_introduzida, area_introduzida: area_introduzida, responsavel_introduzido: responsavel_introduzido},
					success: function(result) {
						alert("Resultado: " + result);
						//location.reload();
					},
					error: function(result) {
						alert("Erro ao atualizar UC: " + result);
					}
				});
			}
		}
	});
	
}

/*----------------------TURMAS----------------------*/

function janelaAdicionarTurma(id_curso){
	document.getElementById("titulo_adicionarTurma").innerHTML = "Adicionar Turma";
	var xhttp;    
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  document.getElementById("modalBody_adicionarTurma").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "phpUtil/curso/adicionarTurma.php?id_curso=" + id_curso);
	xhttp.send();
}

function adicionarTurma(id_curso){
  
	const input_nome = document.getElementById("adicionarTurma_nome");
	const nome_introduzido = input_nome.value;
	const primeira_letra = nome_introduzido.substr(nome_introduzido,0,1);
	
	const select_ano = document.getElementById("adicionarTurma_ano");
	const ano_selecionado = select_ano.value;
	
	const select_semestre = document.getElementById("adicionarTurma_semestre");
	const semestre_selecionado = select_semestre.value;
	
	if(nome_introduzido == ""){
		alert("Introduza um nome!");
		input_nome.focus();
	}
	else if(nome_introduzido.length < 3){
		alert("Introduza um nome válido! (pelo menos 3 caracteres)");
		input_nome.focus();
	}
	else if(ano_selecionado == ""){
		alert("Introduza um ano!");
		select_ano.focus();
	}
	else if(semestre_selecionado == ""){
		alert("Introduza um semestre!");
		select_semestre.focus();
	}
	else{
		$.ajax ({
			type: "POST",
			url: "processamento/curso/adicionarTurma.php", 
			data: {nome: nome_introduzido, ano: ano_selecionado, semestre: semestre_selecionado, id_curso: id_curso},
			success: function(result) {
				location.reload();
			},
			error: function(result) {
				alert("Erro ao adicionar turma: " + result);
			}
		});
	}
	
}

function janelaEditarTurma(id_turma){
	document.getElementById("titulo_editarTurma").innerHTML = "Editar Turma";
	var xhttp;    
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  document.getElementById("modalBody_editarTurma").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "phpUtil/curso/editarTurma.php?id_turma=" + id_turma);
	xhttp.send();
}

function atualizarTurma(id_turma,nome_antigo,ano_antigo,semestre_antigo){
	
	const input_nome = document.getElementById("editarTurma_nome");
	const nome_introduzido = input_nome.value;
	
	if(nome_introduzido == nome_antigo){
		$('#editarTurma').modal('hide');
	}
	else{
		//alert("Atualizar!");
		$.ajax ({
			type: "POST",
			url: "processamento/curso/atualizarTurma.php", 
			data: {id_turma: id_turma, nome_introduzido: nome_introduzido},
			success: function(result) {
				location.reload();
			},
			error: function(result) {
				alert("Erro ao atualizar turma: " + result);
			}
		});
	}
	
}

const ucs_selecionadas = [];
const turmas_selecionadas = [];

function ucSelecionada(id_uc){
	
	const botao_remover = document.getElementById("botao_remover_turmas");
	var ja_esta_array = false;
	
	for(i = 0; i < ucs_selecionadas.length; i++){
		if(ucs_selecionadas[i] == id_uc){
			ja_esta_array = true;
		}
	}
	
	if(ja_esta_array){
		const novo_array = [];
		
		const posicao = ucs_selecionadas.indexOf(id_uc);
		
		ucs_selecionadas.splice(posicao,1);
	}
	else{
		ucs_selecionadas.push(id_uc);
	}
	
	if(ucs_selecionadas.length > 0 || turmas_selecionadas.length > 0){
		var total = ucs_selecionadas.length + turmas_selecionadas.length;
		botao_remover.style.opacity = "1";
		botao_remover.innerHTML = "<i class='material-icons' style='vertical-align: middle; margin-left:-5px; margin-top:-4px;'>delete_forever</i>" + "<i>(" + total + ")</i>";
	}
	else{
		botao_remover.style.opacity = "0.5";
		botao_remover.innerHTML = "<i class='material-icons' style='vertical-align: middle; margin-left:-5px; margin-top:-4px;'>delete_forever</i>" + "<i>(0)</i>";
	}
	
}

function turmaSelecionada(id_turma){
	
	const botao_remover = document.getElementById("botao_remover_turmas");
	var ja_esta_array = false;
	
	for(i = 0; i < turmas_selecionadas.length; i++){
		if(turmas_selecionadas[i] == id_turma){
			ja_esta_array = true;
		}
	}
	
	if(ja_esta_array){
		const novo_array = [];
		
		const posicao = turmas_selecionadas.indexOf(id_turma);
		
		turmas_selecionadas.splice(posicao,1);
	}
	else{
		turmas_selecionadas.push(id_turma);
	}
	
	if(turmas_selecionadas.length > 0 || ucs_selecionadas.length > 0){
		var total = ucs_selecionadas.length + turmas_selecionadas.length;
		botao_remover.style.opacity = "1";
		botao_remover.innerHTML = "<i class='material-icons' style='vertical-align: middle; margin-left:-5px; margin-top:-4px;'>delete_forever</i>" + "<i>(" + total + ")</i>";
	}
	else{
		botao_remover.style.opacity = "0.5";
		botao_remover.innerHTML = "<i class='material-icons' style='vertical-align: middle; margin-left:-5px; margin-top:-4px;'>delete_forever</i>" + "<i>(0)</i>";
	}
	
}

function removerUCsTurmas(){
	
	if(ucs_selecionadas.length == 0 && turmas_selecionadas.length == 0){
		alert("Selecione pelo menos uma UC/turma!");
	}
	else{
		if(window.confirm("Tem a certeza que pretende remover a(s) UC(s)/turma(s) selecionada(s)? (Irá remover todos os dados incluíndo junções,...)")){
			$.ajax ({
				type: "POST",
				url: "processamento/curso/removerUCsTurmas.php", 
				data: {ucs_selecionadas: ucs_selecionadas, turmas_selecionadas: turmas_selecionadas},
				success: function(result) {
					//alert("Resultado: " + result);
					location.reload();
				},
				error: function(result) {
					alert("Erro ao remover UC(s)/turma(s): " + result);
				}
			});
		}
	}
}

function removerTurmas(){
	
	if(turmas_selecionadas.length == 0){
		alert("Selecione pelo menos uma turma!");
	}
	else{
		if(window.confirm("Tem a certeza que pretende remover a(s) turma(s) selecionada(s)? (Irá remover todos os dados incluíndo junções,...)")){
			$.ajax ({
				type: "POST",
				url: "processamento/curso/removerTurmas.php", 
				data: {turmas_selecionadas: turmas_selecionadas},
				success: function(result) {
					//alert("Resultado: " + result);
					location.reload();
				},
				error: function(result) {
					alert("Erro ao remover turma(s): " + result);
				}
			});
		}
	}
	
}

var opcao_1_selecionada = false;
var opcao_2_selecionada = false;

function componentePreDefinido1(){
	
	const texto_componentes = document.getElementById("adicionarUC_texto");
	const checkbox = document.getElementById("pre-definido_1");
	const checkbox_2 = document.getElementById("pre-definido_2");
	
	texto_componentes.innerHTML = "Componentes (0) : ";
	opcao_1_selecionada = false; 
	opcao_2_selecionada = false;
	
	componentes_introduzidas.length = 0;
	
	if(checkbox.checked){
		componentes_introduzidas.push(2);
		componentes_introduzidas.push("Teórico-Prática(TP)");
		componentes_introduzidas.push(2);
		
		componentes_introduzidas.push(3);
		componentes_introduzidas.push("Prática(TP)");
		componentes_introduzidas.push(3);
		
		opcao_1_selecionada = true;
		
		$("#pre-definido_2").prop('checked', false);
		
		texto_componentes.innerHTML = "Componentes (2) : ";
	}
	
}

function componentePreDefinido2(){
	
	const texto_componentes = document.getElementById("adicionarUC_texto");
	const checkbox = document.getElementById("pre-definido_1");
	const checkbox_2 = document.getElementById("pre-definido_2");
	
	texto_componentes.innerHTML = "Componentes (0) : ";
	opcao_1_selecionada = false;
	opcao_2_selecionada = false; 
	
	componentes_introduzidas.length = 0;
	
	if(checkbox_2.checked){
		componentes_introduzidas.push(2);
		componentes_introduzidas.push("Teórico-Prática(TP)");
		componentes_introduzidas.push(2);
		
		componentes_introduzidas.push(2);
		componentes_introduzidas.push("Teórico-Prática(TP)");
		componentes_introduzidas.push(2);
		
		opcao_2_selecionada = true;
		
		$("#pre-definido_1").prop('checked', false);
		
		texto_componentes.innerHTML = "Componentes (2) : ";
	}
	
}

</script>

<?php gerarHome2() ?>
