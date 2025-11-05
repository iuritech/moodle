<?php
// Página de gestão de cursos

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$id_utilizador = $_SESSION['id'];

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
?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
	<div id="cover-spin"></div>
    <div class="container-fluid">
		<div class="card shadow mb-4">
			<div class="card-body" id="principal">
				<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Cursos</a></h6>
				<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 35px;"><b>Cursos</b>
				<?php if($coordenador_UTC){?>
				<a class="btn btn-primary" href="javascript:void(0);" data-toggle='modal' data-target='#criarCurso' onclick="janelaCriarCurso()" style='width:135px; border-radius:25px; margin-left:15px;'><i class='material-icons' style='vertical-align: middle;'>add_circle</i> Criar Curso</a>
				<?php } ?>
				</h3>
				<h6 style="margin-left:20px;margin-right:5px;">Tipo:
					<select id="filtrarCursos" onchange="filtrarCursos()" style="width:150px;">
					<?php
						echo "<option value=''></option>";
						$statement2 = mysqli_prepare($conn, "SELECT * FROM curso_tipo ORDER BY id_tipo_curso;");
						$statement2->execute();
						$resultado2 = $statement2->get_result();
						while($linha2 = mysqli_fetch_assoc($resultado2)){
							$id_tipo_curso = $linha2["id_tipo_curso"];
							$nome = $linha2["nome"];
							$sigla = $linha2["sigla"];
							echo "<option value='$id_tipo_curso'>$nome ($sigla)</option>";
						}
					?>
					</select>
				</h6>
				<?php 
				if(!$is_admin){
					$statement3 = mysqli_prepare($conn, "SELECT DISTINCT * FROM curso WHERE id_utc = $id_utc_utilizador;");
				}	
				else{
					$statement3 = mysqli_prepare($conn, "SELECT DISTINCT * FROM curso ORDER BY id_curso;");
				}
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					while($linha3 = mysqli_fetch_assoc($resultado3)){
						$id_curso = $linha3["id_curso"];
						
						$nome_curso = $linha3["nome"];
						$sigla_curso = $linha3["sigla"];
						$semestres_curso = $linha3["semestres"];
						$anos_curso = $semestres_curso / 2;
						$imagem_curso = $linha3["imagem_curso"];
						$id_tipo_curso = $linha3["id_tipo_curso"];
						$id_coordenador = $linha3["id_coordenador"];
						$id_utc_curso = $linha3["id_utc"];
						
						$nome_curso_array = explode(" ",$nome_curso);
						
						if(sizeof($nome_curso_array) > 6){
							$nome_curso_array = explode(" ",$nome_curso);
							$nome_curso = "";
							$i = 0;
							while($i < 6){
								$nome_curso = $nome_curso . $nome_curso_array[$i];
								if($i < 5){
									$nome_curso = $nome_curso . " ";
								}
								$i += 1;
							}
							$nome_curso = $nome_curso . " (...)";
						}
						
						$statement4 = mysqli_prepare($conn, "SELECT nome, imagem_perfil FROM utilizador WHERE id_utilizador = $id_coordenador;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						$linha4 = mysqli_fetch_assoc($resultado4);
						
							$nome_coordenador = $linha4["nome"];
							$imagem_coordenador = $linha4["imagem_perfil"];
							
						$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso_tipo WHERE id_tipo_curso = $id_tipo_curso;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
						
							$sigla_tipo_curso = $linha5["sigla"];
							
						$statement6 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina WHERE id_curso = $id_curso;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
						
							$num_disciplinas_curso = $linha6["COUNT(DISTINCT id_disciplina)"];
							
						$statement7 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM turma WHERE id_curso = $id_curso;");
						$statement7->execute();
						$resultado7 = $statement7->get_result();
						$linha7 = mysqli_fetch_assoc($resultado7);
						
							$num_turmas_curso = $linha7["COUNT(DISTINCT id_turma)"];
							
						$statement73 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_curso;");
						$statement73->execute();
						$resultado73 = $statement73->get_result();
						$linha73 = mysqli_fetch_assoc($resultado73);
						
							$nome_utc_curso = $linha73["nome_utc"];
				?>
				<div class="div_curso_pagina" onclick="verDetalhesCurso(<?php echo $id_curso; ?>)">
					<div class="div_curso_pagina_container_titulo_detalhes">
						<div class="div_curso_pagina_titulo">
							<?php if($coordenador_UTC){?>
								<a class='btn btn-primary' href="edCurso.php?id=<?php echo $id_curso; ?>" title='Editar Curso' style='width:45px; height:25px; border-radius:25px; float:left;'><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i></a>
							<?php } ?>
							<?php if($is_admin) { ?>
								<text style='font-size:14px; float:right; margin-right:10px;'><i><?php echo $nome_utc_curso; ?></i></text>
							<?php } ?>
							<h4 style="margin-top:35px;"><?php echo $nome_curso; ?></h4>
							<h6>(<?php echo $sigla_tipo_curso; ?>)</h6>
						</div>
						<div class="div_curso_pagina_detalhes">
							<span>
								<h6 style="margin-top:2px;">Coordenador:</h6>
								<img src="<?php echo $imagem_coordenador ?>" style="width:35px; heigh:35px; border-radius:50%; border:1px solid #212529;">
								<?php echo $nome_coordenador; ?>
							</span>
							<br><br><br>
							<span title='Nº Unidades Curriculares' style="margin-top:50px;"><i class="material-icons" style="vertical-align:middle; margin-right:3px; margin-bottom:2px;">class</i><i>Un. Curric.:   </i><text style="font-weight:500;"><?php echo $num_disciplinas_curso; ?></text></span>
							<br>
							<span title='Nº Turmas'><i class="material-icons" style="vertical-align:middle; margin-right:3px; margin-bottom:2px;">people</i><i>Turmas:        </i><text style="font-weight:500;"><?php echo $num_turmas_curso ?></text></span>
						</div>	
					</div>
					<div class="div_curso_pagina_imagem">
						<img src="<?php echo $imagem_curso ?>" style="min-width:353px; min-height:449px; border-radius:2px;">
					</div>
				</div> <?php
					}?>
			</div>
		</div>
	</div>
</main>

<!-- Modal -->
<div class="modal fade" id="criarCurso" tabindex="-1" role="dialog" aria-labelledby="titulo_criarCurso" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 29%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_criarCurso">Criar curso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_criarCurso" class="modal-body">
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

function verDetalhesCurso(id_curso){
	window.location.href = "visCursoDetalhes.php?id=" + id_curso;
}

function janelaCriarCurso(){
	document.getElementById("titulo_criarCurso").innerHTML = "Criar Curso";
	var xhttp;    
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  document.getElementById("modalBody_criarCurso").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "phpUtil/curso/criarCurso.php");
	xhttp.send();
}

function criarCurso(){
	
	const input_nome = document.getElementById("criarCurso_nome");
	const input_sigla = document.getElementById("criarCurso_sigla");
	const select_tipo = document.getElementById("criarCurso_tipo");
	const select_duracao = document.getElementById("criarCurso_duracao");
	const select_coordenador = document.getElementById("criarCurso_coordenador");
	const checkbox_imagem = document.getElementById("criarCurso_imagem");
	
	const nome = input_nome.value;
	const sigla = input_sigla.value;
	const tipo_curso = select_tipo.value;
	const duracao = select_duracao.value;
	const coordenador = select_coordenador.value;
	const imagem = "https://i.ibb.co/4Kfz5SP/service-6.jpg";
	const utc = <?php echo $id_utc_utilizador; ?>;
	
	if(nome.length < 5){
		alert("Introduza um nome válido! (pelo menos 5 caracteres)");
		input_nome.focus();
	}
	else if(!isNaN(nome)){
		alert("Introduza um nome válido! (apenas caracteres)");
		input_nome.focus();
	}
	else if(sigla.length == 0 || sigla.length == 1){
		alert("Introduza uma sigla válida! (pelo menos 2 caracteres)");
		input_sigla.focus();
	}
	else if(tipo_curso == ""){
		alert("Selecione um tipo de curso!");
		select_tipo.focus();
	}
	else if(duracao == ""){
		alert("Selecione uma duração!");
		select_duracao.focus();
	}
	else if(coordenador == ""){
		alert("Selecione um coordenador!");
		select_coordenador.focus();
	}
	else if(!checkbox_imagem.checked){
		alert("Selecione uma imagem para o curso!");
		checkbox_imagem.focus();
	}
	else{
		$.ajax ({
			type: "POST",
			url: "processamento/curso/criarCurso.php", 
			data: {nome: nome, sigla: sigla, tipo_curso: tipo_curso, duracao: duracao, coordenador: coordenador, imagem: imagem, utc: utc},
			success: function(result) {
				window.location.href = "edCurso.php?id=" + result;
			},
			error: function(result) {
				alert("Erro ao remover turma(s): " + result);
			}
		});
	}
	
}

function filtrarCursos(){
	
	const select_tipo_curso = document.getElementById("filtrarCursos");
	const tipo_curso_selecionado = select_tipo_curso.value;
		
	if(tipo_curso_selecionado == ""){
		alert("Mostrar todos");
	}
	else{
		//alert("Filtrar");
		$('#cover-spin').show(0);
		$( ".card-body .div_curso_pagina" ).remove(); 
		$.ajax ({
			type: "POST",
			url: "processamento/curso/verDadosCartaoCurso.php", 
			data: {id_tipo_curso: tipo_curso_selecionado},
			success: function(result) {
				//alert("Resultado: " + result);
				const array = result.split(",");

				const div_pagina_principal = document.getElementById("principal");
				
				for(i = 0; i < array.length; i = i + 10){
					var id_curso = array[i];
					var is_admin = array[i + 1];
					var coordenador_UTC = array[i + 2];
					var nome_curso = array[i + 3];
					var sigla_tipo_curso = array[i + 4];
					var imagem_coordenador = array[i + 5];
					var nome_coordenador = array[i + 6];
					var num_disciplinas_curso = array[i + 7];
					var num_turmas_curso = array[i + 8];
					var imagem_curso = array[i + 9];
					
					var nome_curso_temp = nome_curso.split(" ");
					
					if(nome_curso_temp.length > 6){
						var nome_curso_final = "";
						for(j = 0; j < 6; j++){
							nome_curso_final += nome_curso_temp[j];
							if(j < 5){
								nome_curso_final += " ";
							}
						}
						nome_curso_final += " (...)";
						nome_curso = nome_curso_final;
					}
					
					if(coordenador_UTC == 1){
						div_pagina_principal.innerHTML += "<div class='div_curso_pagina' onclick='verDetalhesCurso(" + id_curso + ")'>"
					 + "<div class='div_curso_pagina_container_titulo_detalhes'>"
					 + "<div class='div_curso_pagina_titulo'>"
						+  "<a class='btn btn-primary' href='edCurso.php?id=" + id_curso + "' title='Editar Curso' style='width:45px; height:25px; border-radius:25px; float:left;'><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i></a>"
						+  "<h4 style='margin-top:35px;'>" + nome_curso + "</h4>"
						+  "<h6>( " + sigla_tipo_curso + ")</h6>"
						+  "</div>"
						+	"<div class='div_curso_pagina_detalhes'>"
						+		"<span>"
						+			"<h6 style='margin-top:2px;'>Coordenador:</h6>"
						+			"<img src='" + imagem_coordenador + "' style='width:35px; heigh:35px; margin-right:4px; border-radius:50%; border:1px solid #212529;'>"
									+ nome_coordenador 
						+		"</span>"
						+		"<br><br><br>"
						+		"<span title='Nº Unidades Curriculares' style='margin-top:50px;'><i class='material-icons' style='vertical-align:middle; margin-right:3px; margin-bottom:2px;'>class</i><i>Un. Curric.:   </i><text style='font-weight:500;'>" + num_disciplinas_curso + "</text></span>"
						+		"<br>"
						+		"<span title='Nº Turmas'><i class='material-icons' style='vertical-align:middle; margin-right:3px; margin-bottom:2px;'>people</i><i>Turmas:        </i><text style='font-weight:500;'>" + num_turmas_curso + "</text></span>"
						+	"</div>"
						+ "</div>"
						+ "<div class='div_curso_pagina_imagem'>"
						+	"<img src='" + imagem_curso + "' style='min-width:353px; min-height:449px; border-radius:2px;'>"
						 + "</div>";
					}
					else{
						div_pagina_principal.innerHTML += "<div class='div_curso_pagina' onclick='verDetalhesCurso(" + id_curso + ")'>"
					 + "<div class='div_curso_pagina_container_titulo_detalhes'>"
					 + "<div class='div_curso_pagina_titulo'>"
						+  "<h4 style='margin-top:35px;'>" + nome_curso + "</h4>"
						+  "<h6>( " + sigla_tipo_curso + ")</h6>"
						+  "</div>"
						+	"<div class='div_curso_pagina_detalhes'>"
						+		"<span>"
						+			"<h6 style='margin-top:2px;'>Coordenador:</h6>"
						+			"<img src='" + imagem_coordenador + "' style='width:35px; heigh:35px; margin-right:4px; border-radius:50%; border:1px solid #212529;'>"
									+ nome_coordenador 
						+		"</span>"
						+		"<br><br><br>"
						+		"<span title='Nº Unidades Curriculares' style='margin-top:50px;'><i class='material-icons' style='vertical-align:middle; margin-right:3px; margin-bottom:2px;'>class</i><i>Un. Curric.:   </i><text style='font-weight:500;'>" + num_disciplinas_curso + "</text></span>"
						+		"<br>"
						+		"<span title='Nº Turmas'><i class='material-icons' style='vertical-align:middle; margin-right:3px; margin-bottom:2px;'>people</i><i>Turmas:        </i><text style='font-weight:500;'>" + num_turmas_curso + "</text></span>"
						+	"</div>"
						+ "</div>"
						+ "<div class='div_curso_pagina_imagem'>"
						+	"<img src='" + imagem_curso + "' style='min-width:353px; min-height:449px; border-radius:2px;'>"
						 + "</div>";
					}
					 
					setTimeout(function(){;
						$('#cover-spin').hide();
						}
					,350);
				
				}
				
				select_tipo_curso.selectedIndex = 1;
				//location.reload();
			},
			error: function(result) {
				alert("Erro ao ver dados curso(s): " + result);
			}
		});
	}
		
}
</script>

<?php gerarHome2() ?>
