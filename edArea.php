<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$id_utilizador = $_SESSION['id'];
$id_area = $_GET["id"];

$is_admin = false;
$coordenador_UTC = false;

$statement = mysqli_prepare($conn, "SELECT id_utc, id_area, is_admin FROM utilizador WHERE id_utilizador = $id_utilizador");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc = $linha["id_utc"];
	$is_admin = $linha["is_admin"];
	$id_area_utilizador = $linha["id_area"];

$statement11 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina WHERE id_area = $id_area;");
$statement11->execute();
$resultado11 = $statement11->get_result();
$linha11 = mysqli_fetch_assoc($resultado11);
	$numero_ucs_area = $linha11["COUNT(DISTINCT id_disciplina)"];

$statement12 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_utilizador) FROM utilizador WHERE id_area = $id_area;");
$statement12->execute();
$resultado12 = $statement12->get_result();
$linha12 = mysqli_fetch_assoc($resultado12);
	$numero_docentes_area = $linha12["COUNT(DISTINCT id_utilizador)"];	
	
$opacidade = 1;
if($numero_ucs_area != 0 || $numero_docentes_area != 0){
	$opacidade = 0.5;
}
	
$statement1 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$id_responsavel = $linha1["id_responsavel"];
	if($id_responsavel == $id_utilizador){
		$coordenador_UTC = true;
	}

$statement2 = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = $id_area;");
$statement2->execute();
$resultado2 = $statement2->get_result();
$linha2 = mysqli_fetch_assoc($resultado2);
	$nome_area = $linha2["nome"];
	$nome_completo = $linha2["nome_completo"];
	$imagem_area = $linha2["imagem"];
	$id_utc = $linha2["id_utc"];
	
$statement3 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc;");
$statement3->execute();
$resultado3 = $statement3->get_result();
$linha3 = mysqli_fetch_assoc($resultado3);
	$nome_utc = $linha3["nome_utc"];

if($id_area_utilizador != $id_area && !$coordenador_UTC){
	header("Location: visArea.php");
}

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
    <div class="container-fluid">
		<div class="card shadow mb-4">
			<div class="card-body">
				<a href="visArea.php"><h6 style="margin-top:10px; margin-left:15px;">...</a> / <a href="visAreaDetalhes.php?id=<?php echo $id_area; ?>"><?php echo $nome_area; ?></a> / <a href="">Editar</a></h6>
				<a class='btn btn-secondary' href='javascript:void(0);' data-toggle='modal' data-target='#editarNome' onclick="janelaEditarNome(<?php echo $id_area; ?>)" style='width:180px; height:45px; border-radius:25px; margin-left:15px; margin-top:20px; margin-bottom:5px;'><i class='material-icons' style='vertical-align: middle; float:left; margin-top:4px;'>edit</i><h4><b><?php echo $nome_area ?></b></h4></a>
					<span title="UTC" style="margin-left:50px;">
						<i class="material-icons" style="vertical-align:middle;">menu_book</i><text style="font-weight:300; font-size:17px; cursor:default;"><b> <?php echo $nome_utc; ?></b></text>
					</span>
				</h3>
				<a class='btn btn-danger' href='javascript:void(0);' onclick="removerArea(<?php echo $id_area ?>)" style='position:absolute; top:40px; right:45px; width:120px; height:60px; border-radius:25px; margin-left:15px; margin-top:5px; opacity:<?php echo $opacidade; ?>;'><i class='material-icons' style='vertical-align: middle; line-height:45px; float:left;'>delete_forever</i>Remover Área</a>
				<br>
				<a class='btn btn-secondary' href='javascript:void(0);' data-toggle='modal' data-target='#editarNomeCompleto' onclick="janelaEditarNomeCompleto(<?php echo $id_area; ?>)" style='width:450px; border-radius:25px; margin-left:15px; margin-top:5px;'><i class='material-icons' style='vertical-align: middle; float:left; margin-top:4px;'>edit</i><h5><?php echo $nome_completo; ?></h5></a>
			
				<a class='btn btn-primary' href='javascript:void(0);' data-toggle='modal' data-target='#criarDocente' onclick="janelaCriarDocente(<?php echo $id_area; ?>)" style='width:155px; border-radius:25px; margin-left:135px; margin-top:30px;'><i class='material-icons' style='vertical-align: middle; float:left; margin-right:2px;'>person_add</i>Novo Docente</a>
			
			<div class="curso_detalhes_separador">
			</div>
				
			<div class="container_area_ucs_docentes">
				<div class="area_detalhes_ucs">
					<h4 style="margin-left:10px;"><i class="material-icons" style="vertical-align:middle;">class</i> Unidades Curriculares 
					<a class="btn btn-primary" href="javascript:void(0);" data-toggle='modal' data-target='#atribuirUC' onclick="janelaAtribuirUC(<?php echo $id_area; ?>)" style='width:90px; border-radius:25px; margin-left:15px;'><i class='material-icons' style='vertical-align: middle;'>add_circle</i> UC's</a>
					</h4>
					<?php
						$statement4 = mysqli_prepare($conn, "SELECT DISTINCT * FROM curso WHERE id_utc = $id_utc;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						while($linha4 = mysqli_fetch_assoc($resultado4)){
							$id_curso = $linha4["id_curso"];
							$nome_curso = $linha4["nome"];
							$id_tipo_curso = $linha4["id_tipo_curso"];
								
							$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso_tipo WHERE id_tipo_curso = $id_tipo_curso;");
							$statement5->execute();
							$resultado5 = $statement5->get_result();
							$linha5 = mysqli_fetch_assoc($resultado5);
								$sigla_tipo_curso = $linha5["sigla"];
							
							$statement6 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina WHERE id_curso = $id_curso AND id_area = $id_area;");
							$statement6->execute();
							$resultado6 = $statement6->get_result();
							$linha6 = mysqli_fetch_assoc($resultado6);
								$num_ucs_area_curso = $linha6["COUNT(DISTINCT id_disciplina)"];
							
								if($num_ucs_area_curso > 0){
									echo "<h5 style='margin-left:20px; margin-top:25px;'>", "(", $sigla_tipo_curso, ") ", $nome_curso, "</h5>";
									
									$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_disciplina FROM disciplina WHERE id_curso = $id_curso AND id_area = $id_area ORDER BY ano, semestre, nome_uc;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									while($linha7 = mysqli_fetch_assoc($resultado7)){
										$id_uc_area_curso = $linha7["id_disciplina"];
											
										$statement8 = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = $id_uc_area_curso;");
										$statement8->execute();
										$resultado8 = $statement8->get_result();
										$linha8 = mysqli_fetch_assoc($resultado8);
											$id_uc = $linha8["id_disciplina"];
											$nome_uc = $linha8["nome_uc"];
											$ano_uc = $linha8["ano"];
											$semestre_uc = $linha8["semestre"];
											
											echo "<div class='editar_area_div_uc' onclick='div_uc_selecionada($id_uc)'><input type='checkbox' id='checkbox_$id_uc' style='margin-right:5px; margin-left:5px;'/><text style='margin-right:5px; color: #028fed;'>", $nome_uc, " (", $ano_uc, "ºA/", $semestre_uc, "ºS)", "</text></a></div><br>";
									}
								}
						}
					?>
				</div>
				<div class="editar_area_docentes">
					<h4 style="margin-bottom:15px;"><i class="material-icons" style="vertical-align:middle;">person</i> Docentes 
					<a class="btn btn-primary" href="javascript:void(0);" data-toggle='modal' data-target='#atribuirDocente' onclick="janelaAtribuirDocente(<?php echo $id_area; ?>)" style='width:125px; border-radius:25px; margin-left:15px;'><i class='material-icons' style='vertical-align: middle;'>add_circle</i> Docentes</a>
					</h4>
					<?php
					$statement9 = mysqli_prepare($conn, "SELECT DISTINCT * FROM utilizador WHERE id_area = $id_area ORDER BY nome;");
					$statement9->execute();
					$resultado9 = $statement9->get_result();
					while($linha9 = mysqli_fetch_assoc($resultado9)){
						$id_utilizador_temp = $linha9["id_utilizador"];
						$nome_utilizador_temp = $linha9["nome"];
						$imagem_utilizador_temp = $linha9["imagem_perfil"];
						$funcao_utilizador_temp = $linha9["id_funcao"];
								
						$margem = (90 + strlen($nome_utilizador_temp) * 7) . "px;";
						
						if($coordenador_UTC || ($funcao_utilizador_temp == 6)){
							
							if($id_utilizador_temp == $id_utilizador){
								echo "<div class='editar_area_div_docente' onclick='div_docente_selecionada($id_utilizador_temp)'><input type='checkbox' id='checkbox_docente_$id_utilizador_temp' style='margin-left:5px; margin-right:10px;'/><img src='$imagem_utilizador_temp' style='width:35px; height:35px; border-radius:50%; margin-right:5px; margin-bottom:5px; margin-top:5px; border:1px solid #000000;'><text style='margin-bottom:15px; margin-right:5px; color:#028fed'><b>", $nome_utilizador_temp, "</b></text></div>";
							}
							else{
								echo "<div class='editar_area_div_docente' onclick='div_docente_selecionada($id_utilizador_temp)'><input type='checkbox' id='checkbox_docente_$id_utilizador_temp' style='margin-left:5px; margin-right:10px;'/><img src='$imagem_utilizador_temp' style='width:35px; height:35px; border-radius:50%; margin-right:5px; margin-bottom:5px; margin-top:5px; border:1px solid #000000;'><text style='margin-bottom:15px; margin-right:5px; color:#028fed'>", $nome_utilizador_temp, "</text></div>";
							}
							
							if($coordenador_UTC || ($funcao_utilizador_temp == 6)){
								echo "<img src='http://localhost/apoio_utc/images/editar_2.png' class='editar_area_div_docente_editar' data-toggle='modal' data-target='#editarDocente' onclick='janelaEditarDocente($id_utilizador_temp)' style='margin-left:$margem;'>";
							}
						
						}
						
						else{
							
							if($id_utilizador_temp == $id_utilizador){
								echo "<img src='$imagem_utilizador_temp' style='width:35px; height:35px; border-radius:50%; margin-right:5px; margin-bottom:5px; margin-top:5px; border:1px solid #000000;'><text style='margin-bottom:15px; margin-right:5px; color:#028fed'><b>", $nome_utilizador_temp, "</b></text>";
							}
							else{
								echo "<img src='$imagem_utilizador_temp' style='width:35px; height:35px; border-radius:50%; margin-right:5px; margin-bottom:5px; margin-top:5px; border:1px solid #000000;'><text style='margin-bottom:15px; margin-right:5px; color:#028fed'>", $nome_utilizador_temp, "</text>";
							}
							
						}
						
						echo "<br>";
					}
					?>
				</div>
				<div class="editar_area_dropdown">
					<h5 style="margin-bottom:5px; margin-top:10px;"><i class="material-icons" style="vertical-align:middle;">monitor</i> Selecionar Área: </h5>
					<select id="dropdown_area" onchange="alterarAreaUCsDocentes()" disabled style="width:150px; opacity:0.5; margin-top:5px; margin-left:0px;">
					<?php
						echo "<option value='$id_area'>$nome_area</option>";
						echo "<option value=''></option>";
					
						$statement10 = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area != $id_area AND id_utc = $id_utc ORDER BY nome;");
						$statement10->execute();
						$resultado10 = $statement10->get_result();
						while($linha10 = mysqli_fetch_assoc($resultado10)){
							$id_area = $linha10["id_area"];
							$nome = $linha10["nome"];
							
							echo "<option value='$id_area'>$nome</option>";
						}
					?>
					</select>
				</div>
				</div>
			</div>
		</div>
	</div>
</main>

<!-- Modal -->
<div class="modal fade" id="editarNome" tabindex="-1" role="dialog" aria-labelledby="titulo_editarNome" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 18%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_editarNome">Editar nome</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_editarNome" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarNomeCompleto" tabindex="-1" role="dialog" aria-labelledby="titulo_editarNomeCompleto" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 23%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_editarNome">Editar nome completo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_editarNomeCompleto" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="atribuirUC" tabindex="-1" role="dialog" aria-labelledby="titulo_atribuirUC" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 23%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_atribuirUC">Atribuir outra UC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_atribuirUC" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="atribuirDocente" tabindex="-1" role="dialog" aria-labelledby="titulo_atribuirDocente" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 21%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_atribuirDocente">Atribuir outro docente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_atribuirDocente" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="criarDocente" tabindex="-1" role="dialog" aria-labelledby="titulo_criarDocente" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 27%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_criarDocente">Criar Docente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_criarDocente" class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarDocente" tabindex="-1" role="dialog" aria-labelledby="titulo_editarDocente" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 27%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_editarDocente">Editar Docente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_editarDocente" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function janelaCriarDocente(id_area){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_criarDocente").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/area/criarDocente.php?id_area=" + id_area);
  xhttp.send();
}

function criarDocente(){
	
	const input_login = document.getElementById("criarDocente_login");
	const input_password = document.getElementById("criarDocente_password");
	const input_nome = document.getElementById("criarDocente_nome");
	const select_utc = document.getElementById("criarDocente_utc");
	const select_area = document.getElementById("criarDocente_area");
	const select_funcao = document.getElementById("criarDocente_funcao");
	const checkbox_imagem = document.getElementById("criarDocente_imagem");
	
	const login = input_login.value;
	const password = input_password.value;
	const nome = input_nome.value;
	const id_utc = select_utc.value;
	const id_area = select_area.value;
	const id_funcao = select_funcao.value;
	const imagem_selecionada = checkbox_imagem.checked;
	
	if(login.length < 5){
		alert("Introduza um login válido! (pelo menos 5 caracteres)");
		input_login.focus();
	}
	else if(password.length < 5){
		alert("Introduza uma password válida! (pelos menos 5 caracteres)");
		input_password.focus();
	}
	else if(password == login){
		alert("Password não pode ser igual ao login!");
		input_password.focus();
	}
	else if(nome.length < 5){
		alert("Introduza um nome válido! (pelo menos 5 caracteres)");
		input_nome.focus();
	}
	else if(temNumero(nome)){
		alert("Introduza um nome válido! (apenas caracteres)");
		input_nome.focus();
	}
	else if(!imagem_selecionada){
		alert("Selecione uma imagem de perfil!");
		imagem_selecionada.focus();
	}
	else{
		$.ajax ({
			type: "POST",
			url: "processamento/area/verificarLogin.php", 
			data: {login: login},
			success: function(result) {
				if(result == 1){
					alert("Login já existente!");
					input_login.focus();
				}
				else{
					
					$.ajax ({
						type: "POST",
						url: "processamento/area/criarDocente.php", 
						data: {login: login, password: password, nome: nome, id_utc: id_utc, id_area: id_area, id_funcao: id_funcao},
						success: function(result) {
							location.reload();
						}
					});

				}
			}
		});
	}
	
}

/*
function atualizarAreas(){
	
	const select_utc = document.getElementById("criarDocente_utc");
	const utc_selecionada = select_utc.value;
	
	const select_area = document.getElementById("criarDocente_area");
	
	removeOptions(select_area);
	
	$.ajax ({
		type: "POST",
		url: "processamento/area/verAreasUTC.php", 
		data: {id_utc: utc_selecionada},
		success: function(result) {
			result_final = result.split(",");
			
			if(result_final.length > 1){
				for(i = 0; i < result_final.length; i = i + 2){
					const opcao = document.createElement("option");
					opcao.value = result_final[i];
					opcao.innerHTML = result_final[i + 1];
					
					select_area.appendChild(opcao);
				}
			}
		},
		error: function(result) {
			alert("Erro ao visualizar áreas da UTC: " + result);
		}
	});
			
}
*/

function temNumero(string) {
  return /\d/.test(string);
}

function janelaEditarDocente(id_docente){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_editarDocente").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/area/editarDocente.php?id_docente=" + id_docente);
  xhttp.send();
}

function atualizarDocente(id_docente, login_atual,password_atual,nome_atual,id_utc_atual,id_area_atual,id_funcao_atual){
	
	const input_login = document.getElementById("editarDocente_login");
	const input_password = document.getElementById("editarDocente_password");
	const input_nome = document.getElementById("editarDocente_nome");
	const input_utc = document.getElementById("editarDocente_utc");
	const select_area = document.getElementById("editarDocente_area");
	const select_funcao = document.getElementById("editarDocente_funcao");
	
	const login = input_login.value;
	const password = input_password.value;
	const nome = input_nome.value;
	const id_utc = input_utc.value;
	const id_area = select_area.value;
	const id_funcao = select_funcao.value;
	
	if(login == login_atual && password == password_atual && nome == nome_atual && id_utc == id_utc_atual && id_area == id_area_atual && id_funcao == id_funcao_atual){
		$('#editarDocente').modal('hide');
	}
	else{
		if(login.length < 5){
			alert("Introduza um login válido! (pelo menos 5 caracteres)");
			input_login.focus();
		}
		else if(password.length < 5){
			alert("Introduza uma password válida! (pelos menos 5 caracteres)");
			input_password.focus();
		}
		else if(password == login){
			alert("Password não pode ser igual ao login!");
			input_password.focus();
		}
		else if(nome.length < 5){
			alert("Introduza um nome válido! (pelo menos 5 caracteres)");
			input_nome.focus();
		}
		else if(temNumero(nome)){
			alert("Introduza um nome válido! (apenas caracteres)");
			input_nome.focus();
		}
		else{
			
			$.ajax ({
				type: "POST",
				url: "processamento/area/atualizarDocente.php", 
				data: {id_docente: id_docente, login: login, password: password, nome: nome, id_area: id_area, id_funcao: id_funcao},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao atualizar dados do docente: " + result);
				}
			});
			
		}
	}
}

function atualizarAreasEditar(){
	
	const select_utc = document.getElementById("editarDocente_utc");
	const utc_selecionada = select_utc.value;
	
	const select_area = document.getElementById("editarDocente_area");
	
	removeOptions(select_area);
	
	$.ajax ({
		type: "POST",
		url: "processamento/utc_coordenador/verAreasUTC.php", 
		data: {id_utc: utc_selecionada},
		success: function(result) {
			result_final = result.split(",");
			
			if(result_final.length > 1){
				for(i = 0; i < result_final.length; i = i + 2){
					const opcao = document.createElement("option");
					opcao.value = result_final[i];
					opcao.innerHTML = result_final[i + 1];
					
					select_area.appendChild(opcao);
				}
			}
		},
		error: function(result) {
			alert("Erro ao visualizar áreas da UTC: " + result);
		}
	});
			
}

function removerDocente(id_docente){
	
	if(window.confirm("Tem a certeza que pretende eliminar este docente? (As aulas/junções de turmas ficarão sem nenhum docente associado)")){
		
		$.ajax ({
			type: "POST",
			url: "processamento/area/verificarDocenteAssociado.php", 
			data: {id_docente: id_docente},
			success: function(result) {
				
				if(result[0] != 0){
					alert("Não foi possível eliminar o docente. Motivo: o docente é responsável por uma ou mais unidades curriculares");
				}
				else if(result[2] != 0){
					alert("Não foi possível eliminar o docente. Motivo: o docente é coordenador de um ou mais cursos");
				}
				else{
					$.ajax ({
						type: "POST",
						url: "processamento/area/removerDocente.php", 
						data: {id_docente: id_docente},
						success: function(result) {
							location.reload();
						},
						error: function(result) {
							alert("Erro ao remover docente: " + result);
						}
					});
				}
				
			},
			error: function(result) {
				alert("Erro ao verificar dados do docente: " + result);
			}
		});
		
	}
	
}

function configurarMenuTopo(){
	var li_curso = document.getElementById("li_AREA");
	li_curso.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function janelaEditarNome(id_area){
  document.getElementById("titulo_editarNome").innerHTML = "Editar nome";
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_editarNome").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/area/editarNome.php?id_area=" + id_area);
  xhttp.send();
}

function atualizarNome(id_area,nome_atual){
	
	const input_nome = document.getElementById("edNomeArea");
	const nome_introduzido = input_nome.value;
	
	if(nome_atual == nome_introduzido){
		$('#editarNome').modal('hide');
	}
	else{
		if(nome_introduzido.length < 2){
			alert("Introduza um nome válido! (pelo menos 2 caracteres)");
			input_nome.focus();
		}
		else{
			//alert("Atualizar!");
			$.ajax ({
				type: "POST",
				url: "processamento/area/atualizarNome.php", 
				data: {id_area: id_area, nome_introduzido: nome_introduzido},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao atualizar nome: " + result);
				}
			});
		}
	}
	
}

function janelaEditarNomeCompleto(id_area){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_editarNomeCompleto").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/area/editarNomeCompleto.php?id_area=" + id_area);
  xhttp.send();
}

function atualizarNomeCompleto(id_area,nome_atual){
	
	const input_nome = document.getElementById("edNomeCompletoArea");
	const nome_introduzido = input_nome.value;
	
	if(nome_atual == nome_introduzido){
		$('#editarNomeCompleto').modal('hide');
	}
	else{
		if(nome_introduzido.length < 10){
			alert("Introduza um nome válido! (pelo menos 10 caracteres)");
			input_nome.focus();
		}
		else{
			//alert("Atualizar!");
			$.ajax ({
				type: "POST",
				url: "processamento/area/atualizarNomeCompleto.php", 
				data: {id_area: id_area, nome_introduzido: nome_introduzido},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao atualizar nome completo: " + result);
				}
			});
		}
	}
	
}

const ucs_selecionadas = [];
const docentes_selecionados = [];

function uc_selecionada(id_uc){
	const select_area = document.getElementById("dropdown_area");
	
	var ja_esta_array = false;
	
	for(i = 0; i < ucs_selecionadas.length; i++){
		if(ucs_selecionadas[i] == id_uc){
			ja_esta_array = true;
		}
	}
	
	if(ja_esta_array){
		const posicao = ucs_selecionadas.indexOf(id_uc);
		
		ucs_selecionadas.splice(posicao,1);
	}
	else{
		ucs_selecionadas.push(id_uc);
	}
	
	if(ucs_selecionadas.length > 0 || docentes_selecionados.length > 0){
		select_area.style.opacity = "1";
		$('#dropdown_area').attr('disabled', false);
	}
	else{
		select_area.style.opacity = "0.5";
		$('#dropdown_area').attr('disabled', true);
	}
		
}

function docente_selecionado(id_docente){
	
	const select_area = document.getElementById("dropdown_area");
	
	var ja_esta_array = false;
	
	for(i = 0; i < docentes_selecionados.length; i++){
		if(docentes_selecionados[i] == id_docente){
			ja_esta_array = true;
		}
	}
	
	if(ja_esta_array){
		const posicao = docentes_selecionados.indexOf(id_docente);
		
		docentes_selecionados.splice(posicao,1);
	}
	else{
		docentes_selecionados.push(id_docente);
	}
	
	if(ucs_selecionadas.length > 0 || docentes_selecionados.length > 0){
		select_area.style.opacity = "1";
		$('#dropdown_area').attr('disabled', false);
	}
	else{
		select_area.style.opacity = "0.5";
		$('#dropdown_area').attr('disabled', true);
	}
		
}

function alterarAreaUCsDocentes(){
	
	const select_area = document.getElementById("dropdown_area");
	const id_area = select_area.value;
	
	if(id_area != ''){
		
		if(ucs_selecionadas == 0 && docentes_selecionados != 0){
			if(window.confirm("Tem a certeza que pretende alterar a área do(s) docente(s) selecionado(s)?")){
				$.ajax ({
					type: "POST",
					url: "processamento/area/atualizarAreaDocentes.php", 
					data: {id_area: id_area, docentes_selecionados: docentes_selecionados},
					success: function(result) {
						location.reload();
					},
					error: function(result) {
						alert("Erro ao atualizar área do(s) docente(s): " + result);
					}
				});
			}
			else{
				location.reload();
			}
		}
		else if(ucs_selecionadas != 0 && docentes_selecionados == 0){
			if(window.confirm("Tem a certeza que pretende alterar a área da(s) UC(s) selecionada(s)?")){
				$.ajax ({
					type: "POST",
					url: "processamento/area/atualizarAreaUCs.php", 
					data: {id_area: id_area, ucs_selecionadas: ucs_selecionadas},
					success: function(result) {
						location.reload();
					},
					error: function(result) {
						alert("Erro ao atualizar área da(s) UC(s): " + result);
					}
				});
			}
			else{
				location.reload();
			}
		}
		else{
			if(window.confirm("Tem a certeza que pretende alterar a área das UC's e docentes selecionados?")){
				$.ajax ({
					type: "POST",
					url: "processamento/area/atualizarAreaUCsDocentes.php", 
					data: {id_area: id_area, ucs_selecionadas: ucs_selecionadas, docentes_selecionados: docentes_selecionados},
					success: function(result) {
						location.reload();
					},
					error: function(result) {
						alert("Erro ao atualizar àrea das UC's/docentes: " + result);
					}
				});
			}
			else{
				location.reload();
			}
		}
		
	}
	
}

function div_uc_selecionada(id_uc){
	
	const checkbox = document.getElementById("checkbox_" + id_uc);
	if(checkbox.checked){
		$('#checkbox_' + id_uc).attr('checked', false);
	}
	else{
		$('#checkbox_' + id_uc).attr('checked', true);
	}
	uc_selecionada(id_uc);
	//alert(checkbox.checked);
	
}

function div_docente_selecionada(id_docente){
	
	const checkbox = document.getElementById("checkbox_docente_" + id_docente);
	if(checkbox.checked){
		$('#checkbox_docente_' + id_docente).attr('checked', false);
	}
	else{
		$('#checkbox_docente_' + id_docente).attr('checked', true);
	}
	docente_selecionado(id_docente);
	
}

function janelaAtribuirUC(id_area){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_atribuirUC").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/area/atribuirOutrasUCs.php?id_area=" + id_area);
  xhttp.send();
}

function atribuirOutraUC(id_area){

	const select_uc = document.getElementById("select_outras_ucs");
	const uc_selecionada = select_uc.value;
	
	if(uc_selecionada == ""){
		$('#atribuirUC').modal('hide');
	}
	else{
		$.ajax ({
			type: "POST",
			url: "processamento/area/atribuirOutraUC.php", 
			data: {id_area: id_area, uc_selecionada: uc_selecionada},
			success: function(result) {
				location.reload();
			},
			error: function(result) {
				alert("Erro ao atualizar àrea da outra UC: " + result);
			}
		});
	}
	
}

function janelaAtribuirDocente(id_area){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_atribuirDocente").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/area/atribuirOutroDocente.php?id_area=" + id_area);
  xhttp.send();
}

function atribuirOutroDocente(id_area){
	
	const select_docente = document.getElementById("select_outros_docentes");
	const docente_selecionado = select_docente.value;
	
	if(docente_selecionado == ""){
		$('#atribuirDocente').modal('hide');
	}
	else{
		$.ajax ({
			type: "POST",
			url: "processamento/area/atribuirOutroDocente.php", 
			data: {id_area: id_area, docente_selecionado: docente_selecionado},
			success: function(result) {
				location.reload();
			},
			error: function(result) {
				alert("Erro ao atualizar àrea do outro docente: " + result);
			}
		});
	}
	
}

function removerArea(id_area){
	
	const num_ucs_area = <?php echo $numero_ucs_area; ?>;
	const num_docentes_area = <?php echo $numero_docentes_area; ?>;
	
	if(num_ucs_area != 0 || num_docentes_area != 0){
		alert("Existem unidades curriculares/docentes associados a esta área!");
	}
	else{
		if(window.confirm("Tem a certeza que pretende remover esta área?")){
			$.ajax ({
				type: "POST",
				url: "processamento/area/removerArea.php", 
				data: {id_area: id_area},
				success: function(result) {
					window.location.href = "visAreas.php";
				},
				error: function(result) {
					alert("Erro ao remover área: " + result);
				}
			});
		}
	}
	
}

function removeOptions(selectElement) {
	var i, L = selectElement.options.length - 1;
	for(i = L; i >= 0; i--) {
		selectElement.remove(i);
	}
}
</script>

<?php gerarHome2() ?>
