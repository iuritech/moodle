<?php
// Página de edição de UTCs

session_start(); 

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$id_utilizador = $_SESSION['id'];
$nome_utilizador = $_SESSION['nome'];

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}

$is_admin = false;
$permUTC = false;

$statement0 = mysqli_prepare($conn, "SELECT is_admin FROM utilizador WHERE id_utilizador = $id_utilizador;");
$statement0->execute();
$resultado0 = $statement0->get_result();
$linha0 = mysqli_fetch_assoc($resultado0);
	$is_admin = $linha0["is_admin"];
	
$statement = mysqli_prepare($conn, "SELECT id_responsavel FROM utc;");
$statement->execute();
$resultado = $statement->get_result();
while($linha = mysqli_fetch_assoc($resultado)){
	$id_responsavel = $linha["id_responsavel"];
		
	if($id_responsavel == $id_utilizador){
		$permUTC = true;
	}
}

if (!$is_admin && !$permUTC) {
    header("Location: index.php");
}

// Obter utc que o utilizador gere
$statement1 = mysqli_prepare($conn, "SELECT * FROM utc WHERE id_responsavel = $id_utilizador;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$id_utc = $linha1["id_utc"];
	$nome_utc = $linha1["nome_utc"];
	$dsd_sem_1 = $linha1["dsd_1_sem"];
	$dsd_sem_2 = $linha1["dsd_2_sem"];
	
	$statement2 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_utilizador;");
	$statement2->execute();
	$resultado2 = $statement2->get_result();
	$linha2 = mysqli_fetch_assoc($resultado2);
		$nome_coordenador = $linha2["nome"];
		$imagem_coordenador = $linha2["imagem_perfil"];
	
		if(strlen($nome_coordenador) > 16){
			$temp = explode(" ",$nome_coordenador);
			$nome_coordenador = substr($nome_coordenador,0,1) . ". " . $temp[sizeof($temp) - 1];
		}
	
	$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_utilizador) FROM utilizador WHERE id_utc = $id_utc;");
	$statement3->execute();
	$resultado3 = $statement3->get_result();
	$linha3 = mysqli_fetch_assoc($resultado3);
		$num_docentes = $linha3["COUNT(DISTINCT id_utilizador)"];
		
	$statement34 = mysqli_prepare($conn, "SELECT COUNT(id_curso) FROM curso WHERE id_utc = $id_utc;");
	$statement34->execute();
	$resultado34 = $statement34->get_result();
	$linha34 = mysqli_fetch_assoc($resultado34);
		$num_cursos_utc = $linha34["COUNT(id_curso)"];

	$num_ucs_1_sem = 0;
	$num_ucs_1_sem_atribuidas = 0;
	$num_ucs_2_sem = 0;
	$num_ucs_2_sem_atribuidas = 0;

	if($num_cursos_utc > 0){
			
		$cursos_utc = array();
			
		$statement4 = mysqli_prepare($conn, "SELECT id_curso FROM curso WHERE id_utc = $id_utc;");
		$statement4->execute();
		$resultado4 = $statement4->get_result();
		while($linha4 = mysqli_fetch_assoc($resultado4)){
			$id_curso = $linha4["id_curso"];
			
			array_push($cursos_utc,$id_curso);
		}
		$cursos_utc_final = implode(",",$cursos_utc);

		$statement5 = mysqli_prepare($conn, "SELECT COUNT(id_disciplina) FROM disciplina WHERE semestre = 1 AND id_curso IN ($cursos_utc_final);");
		$statement5->execute();
		$resultado5 = $statement5->get_result();
		$linha5 = mysqli_fetch_assoc($resultado5);
			$num_ucs_1_sem = $linha5["COUNT(id_disciplina)"];
			
		$ucs_utc = array();
		$ucs_por_atribuir = array();
			
		$statement6 = mysqli_prepare($conn, "SELECT DISTINCT id_disciplina FROM disciplina WHERE semestre = 1 AND id_curso IN ($cursos_utc_final);");
		$statement6->execute();
		$resultado6 = $statement6->get_result();
		while($linha6 = mysqli_fetch_assoc($resultado6)){
			$id_uc = $linha6["id_disciplina"];
			
			array_push($ucs_utc,$id_uc);
			
			$statement7 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_uc;");
			$statement7->execute();
			$resultado7 = $statement7->get_result();
			while($linha7 = mysqli_fetch_assoc($resultado7)){
				$id_componente = $linha7["id_componente"];
				
				$statement8 = mysqli_prepare($conn, "SELECT COUNT(id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente IS NULL;");
				$statement8->execute();
				$resultado8 = $statement8->get_result();
				$linha8 = mysqli_fetch_assoc($resultado8);
					$turmas_sem_docente_componente = $linha8["COUNT(id_turma)"];
					
					if($turmas_sem_docente_componente > 0){
						if(!in_array($id_uc,$ucs_por_atribuir)){
							array_push($ucs_por_atribuir,$id_uc);
						}
					}
			}
		}
		
		$num_ucs_1_sem = sizeof($ucs_utc);
		$num_ucs_1_sem_por_atribuir = sizeof($ucs_por_atribuir);
		$num_ucs_1_sem_atribuidas = $num_ucs_1_sem - $num_ucs_1_sem_por_atribuir;
		
		$statement9 = mysqli_prepare($conn, "SELECT COUNT(id_disciplina) FROM disciplina WHERE semestre = 2 AND id_curso IN ($cursos_utc_final);");
		$statement9->execute();
		$resultado9 = $statement9->get_result();
		$linha9 = mysqli_fetch_assoc($resultado9);
			$num_ucs_2_sem = $linha9["COUNT(id_disciplina)"];
			
		$ucs_utc_2_sem = array();
		$ucs_por_atribuir_2_sem = array();
			
		$statement10 = mysqli_prepare($conn, "SELECT DISTINCT id_disciplina FROM disciplina WHERE semestre = 2 AND id_curso IN ($cursos_utc_final);");
		$statement10->execute();
		$resultado10 = $statement10->get_result();
		while($linha10 = mysqli_fetch_assoc($resultado10)){
			$id_uc = $linha10["id_disciplina"];
			
			array_push($ucs_utc_2_sem,$id_uc);
			
			$statement11 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_uc;");
			$statement11->execute();
			$resultado11 = $statement11->get_result();
			while($linha11 = mysqli_fetch_assoc($resultado11)){
				$id_componente = $linha11["id_componente"];
				
				$statement12 = mysqli_prepare($conn, "SELECT COUNT(id_turma) FROM aula WHERE id_componente = $id_componente AND id_docente IS NULL;");
				$statement12->execute();
				$resultado12 = $statement12->get_result();
				$linha12 = mysqli_fetch_assoc($resultado12);
					$turmas_sem_docente_componente = $linha12["COUNT(id_turma)"];
					
					if($turmas_sem_docente_componente > 0){
						if(!in_array($id_uc,$ucs_por_atribuir_2_sem)){
							array_push($ucs_por_atribuir_2_sem,$id_uc);
						}
					}
			}
		}
		
		$num_ucs_2_sem = sizeof($ucs_utc_2_sem);
		$num_ucs_2_sem_por_atribuir = sizeof($ucs_por_atribuir_2_sem);
		$num_ucs_2_sem_atribuidas = $num_ucs_2_sem - $num_ucs_2_sem_por_atribuir;

	}
?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<?php if($is_admin){ ?>
				<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">...</a> / <a href="visUTC.php">UTC's (admin)</a> / <a href="">UTC <?php echo $nome_utc ?> </a></h6>
			<?php }else{ ?>
				<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">UTC <?php echo $nome_utc ?> </a></h6>
			<?php } ?>
			<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 15px;">UTC - <b><?php echo $nome_utc ?></b></h3>
			<br>
			<span style="position:absolute; top:75px; right:105px;">
				<text style="font-weight:360; font-size:1.25rem; margin-left:25px; margin-right:5px;">Coordenador: </text><img src="<?php echo $imagem_coordenador; ?>" style="width:40px; height:40px; margin-right:5px; border-radius:50%; border:1px solid #000000;"><text style="font-size:16px;"><b><?php echo $nome_coordenador; ?></b></text>
			</span>
			
			<div class="coordenador_utc_container_total">
			
			<text style="font-size:1.5rem; line-height:1.2; font-weight:500; margin-bottom:0.5rem; margin-left:25px; margin-bottom:10px;">Docentes (<?php echo $num_docentes; ?>)</text>
			<a class='btn btn-primary' href='javascript:void(0);' data-toggle='modal' data-target='#criarDocente' onclick="janelaCriarDocente(<?php echo $id_utc; ?>)" style='width:125px; border-radius:25px; margin-left:25px; margin-bottom:5px;'><i class='material-icons' style='vertical-align: middle; float:left; margin-right:2px;'>person_add</i> Docente</a>
			<div class="coordenador_utc_docentes">
				<?php
					
					$loop = 0;
						
					echo "<div class='coordenador_utc_docentes_container'>";
						
					$statement4 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utc = $id_utc ORDER BY nome;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					while($linha4 = mysqli_fetch_assoc($resultado4)){
						$id_docente = $linha4["id_utilizador"];
						$nome_docente = $linha4["nome"];
						$imagem_docente = $linha4["imagem_perfil"];
						$id_area_docente = $linha4["id_area"];
						$id_funcao = $linha4["id_funcao"];
						$is_admin = $linha4["is_admin"];
							
						if(strlen($nome_docente) > 16){
							$temp = explode(" ",$nome_docente);
							$nome_docente = substr($nome_docente,0,1) . ". " . $temp[sizeof($temp) - 1];
						}							
						
						$funcao = "";
						if($id_funcao == 4){
							$funcao = "**";
						}
						else if($id_funcao == 5){
							$funcao = "*";
						}
						else if($id_funcao == 6){
							$funcao = "***";
						}
						
						echo "<div style='margin-bottom:-15px; font-size:17px;'>";
						
						if($is_admin == 1){
							echo "<img src='$imagem_docente' style='width:35px; height:35px; margin-right:4px; border-radius:50%; border:1px solid #000000;'><a href='javascript:void(0)' data-toggle='modal' data-target='#editarDocente' onclick='janelaEditarDocente($id_docente)'><u>$nome_docente</u></a> $funcao";
						}
						else if($id_docente == $id_utilizador){
							echo "<img src='$imagem_docente' style='width:35px; height:35px; margin-right:4px; border-radius:50%; border:1px solid #000000;'><a href='javascript:void(0)' data-toggle='modal' data-target='#editarDocente' onclick='janelaEditarDocente($id_docente)'><b>$nome_docente</a> $funcao</b>";
						}
						else{
							echo "<img src='$imagem_docente' style='width:35px; height:35px; margin-right:4px; border-radius:50%; border:1px solid #000000;'><a href='javascript:void(0)' data-toggle='modal' data-target='#editarDocente' onclick='janelaEditarDocente($id_docente)'>$nome_docente</a> $funcao";
						}
						echo "</div>";
						echo "<br>";
						
						$loop = $loop + 1;
						
						if((($loop % 2) == 0)){
							echo "</div>";
							echo "<div class='coordenador_utc_docentes_container'>";
						}
						if($loop == 9){
							echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#verDocentesTodos' onclick='janelaVerTodosDocentes($id_utc,$id_utilizador)' style='margin-left:45px;'><i>Ver todos...</i></a>";
							break;	
						}
					}
					
					echo "</div>";
				?>
			</div>
				<div style="width:146px; height:110px; float:left; margin-right:65px; border-bottom:1px solid #000000;">
					<text style="font-style:italic; font-size:14px;">Prof. Adjunto - **</text>
					<br>
					<text style="font-style:italic; font-size:14px;">Prof. Coordenador. - *</text>
					<br>
					<text style="margin-bottom:10px; font-style:italic; font-size:14px;">Prof. Assist. Conv. - ***</text>
					<br>
					<text style="margin-bottom:10px; font-style:italic; font-size:14px;">Admin - ___</text>
					<br>
				</div>
			<div class="coordenador_utc_dsd">
				<h4 style="margin-left:25px; margin-bottom:20px;">Distribuição de Serviço Docente</h4>
				
				<?php
					if($dsd_sem_1 == 0){
				?>
				<div class="coordenador_utc_dsd_sem_aberto" onclick="bloquear1sem(<?php echo $id_utc; ?>)" style="background: #3bff6f; border: 1px solid #29a64a; cursor:pointer;">
					<h5>1º Semestre <span class="material-icons" title="A DSD do 1º semestre está desbloqueada" style="cursor:pointer;">lock_open</span></h5>
					<span title='<?php echo $num_ucs_1_sem_atribuidas; ?> de <?php echo $num_ucs_1_sem; ?> unidades curriculares têm a DSD totalmente feita' style="cursor: default;"><i class="material-icons" style="vertical-align:middle;">class</i><text style="font-weight:500;">UC's: </text> <b><?php echo $num_ucs_1_sem_atribuidas; ?></b> / <b><?php echo $num_ucs_1_sem; ?></b></span>
				<?php }
					else{ ?>
				<div class="coordenador_utc_dsd_sem_fechado" onclick="desbloquear1sem(<?php echo $id_utc; ?>)" style="background: #ff4d4d; border: 1px solid #b53636; cursor:pointer;">
					<h5>1º Semestre <span class="material-icons" title="A DSD do 1º semestre está bloqueada" style="cursor:pointer;">lock</span></h5>
					<span title='<?php echo $num_ucs_1_sem_atribuidas; ?> de <?php echo $num_ucs_1_sem; ?> unidades curriculares têm a DSD totalmente feita' style="cursor: default;"><i class="material-icons" style="vertical-align:middle;">class</i><text style="font-weight:500;">UC's: </text> <b><?php echo $num_ucs_1_sem_atribuidas; ?></b> / <b><?php echo $num_ucs_1_sem; ?></b></span>
				<?php	} ?>
				</div>
			
				<?php
					if($dsd_sem_2 == 0){
				?>
				<div class="coordenador_utc_dsd_sem_aberto" onclick="bloquear2sem(<?php echo $id_utc; ?>)" style="background: #3bff6f; border: 1px solid #29a64a; cursor:pointer">
					<h5>2º Semestre <span class="material-icons" title="A DSD do 2º semestre está desbloqueada" style="cursor:pointer;">lock_open</span></h5>
					<span title='<?php echo $num_ucs_2_sem_atribuidas; ?> de <?php echo $num_ucs_2_sem; ?> unidades curriculares têm a DSD totalmente feita' style="cursor: default;"><i class="material-icons" style="vertical-align:middle;">class</i><text style="font-weight:500;">UC's: </text> <b><?php echo $num_ucs_2_sem_atribuidas; ?></b> / <b><?php echo $num_ucs_2_sem; ?></b></span>
			<?php } 
					else{ ?>
					<div class="coordenador_utc_dsd_sem_fechado" onclick="desbloquear2sem(<?php echo $id_utc; ?>)" style="background: #ff4d4d; border: 1px solid #b53636; cursor:pointer">
						<h5>2º Semestre <span class="material-icons" title="A DSD do 2º semestre está bloqueada" style="cursor:pointer;">lock</span></h5>
						<span title='<?php echo $num_ucs_2_sem_atribuidas; ?> de <?php echo $num_ucs_2_sem; ?> unidades curriculares têm a DSD totalmente feita' style="cursor: default;"><i class="material-icons" style="vertical-align:middle;">class</i><text style="font-weight:500;">UC's: </text> <b><?php echo $num_ucs_2_sem_atribuidas; ?></b> / <b><?php echo $num_ucs_2_sem; ?></b></span>
				<?php	}?>
				</div>
				
			</div>
			
			<div class="coordenador_utc_cursos">
				<text style="font-size:1.5rem; font-weight:500; line-height:1.2; margin-right:10px;">Cursos</text> <a href="javascript:void(0)" onclick="verDadosCompletosCursos()"><i>Ver mais...</i></a>
				<br>
				<br>
				<?php
					$statement5 = mysqli_prepare($conn, "SELECT id_tipo_curso, nome FROM curso_tipo;");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					while($linha5 = mysqli_fetch_assoc($resultado5)){
						$id_tipo_curso = $linha5["id_tipo_curso"];
						$nome_tipo_curso = $linha5["nome"];
						
						$statement6 = mysqli_prepare($conn, "SELECT COUNT(id_curso) FROM curso WHERE id_tipo_curso = $id_tipo_curso AND id_utc = $id_utc;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$num_cursos_categoria = $linha6["COUNT(id_curso)"];
							
						echo "<i class='material-icons' style='vertical-align:middle; margin-left:10px; margin-right:5px;'>school</i><text style='font-weight:500; line-height:1.2;'>", $nome_tipo_curso, ": (", $num_cursos_categoria, ")</text><br>";
						
						/*
						$statement6 = mysqli_prepare($conn, "SELECT nome FROM curso WHERE id_tipo_curso = $id_tipo_curso AND id_utc = $id_utc ORDER BY nome");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						while($linha6 = mysqli_fetch_assoc($resultado6)){
							$nome_curso = $linha6["nome"];
							
							echo $nome_curso, ", ";
						} */
					}
				?>
			</div>
			
			<div class="coordenador_utc_areas">
				<text style="font-size:1.5rem; font-weight:500; line-height:1.2; margin-right:10px;">Áreas</text> <a href="javascript:void(0)" onclick="verDadosAreasCompletas()"><i>Ver mais...</i></a>
				<br>
				<br>
				<?php
					$loop_area = 0;
					
					$statement7 = mysqli_prepare($conn, "SELECT id_area, nome FROM area WHERE id_utc = $id_utc;");
					$statement7->execute();
					$resultado7 = $statement7->get_result();
					while($linha7 = mysqli_fetch_assoc($resultado7)){
						$id_area = $linha7["id_area"];
						$nome_area = $linha7["nome"];
						
						$statement8 = mysqli_prepare($conn, "SELECT COUNT(id_utilizador) FROM utilizador WHERE id_utc = $id_utc AND id_area = $id_area;");
						$statement8->execute();
						$resultado8 = $statement8->get_result();
						$linha8 = mysqli_fetch_assoc($resultado8);
							$num_docentes_area = $linha8["COUNT(id_utilizador)"];
						
						echo "<i class='material-icons' style='vertical-align:middle; margin-left:10px; margin-right:5px;'>monitor</i><text style='font-weight:500; line-height:1.2;'>", $nome_area, ": </text>", $num_docentes_area, " docentes<br>";
					
					$loop_area += 1;
					
					if($loop_area == 4){
						echo "<text style='font-weight:500; line-height:1.2; margin-left:15px;'>...</text>";
						break;
					}
				}
				?>
			</div>
			
			</div>
			
		</div>
	</div>
</div>
</main>

<!-- Modal -->
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

<!-- Modal -->
<div class="modal fade" id="verDocentesTodos" tabindex="-1" role="dialog" aria-labelledby="titulo_verDocentesTodos" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 22%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_verDocentesTodos">Docentes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_verDocentesTodos" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function configurarMenuTopo(){
	var li_utc = document.getElementById("li_UTC");
	li_utc.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function janelaCriarDocente(id_utc){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_criarDocente").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/utc_coordenador/criarDocente.php?id_utc=" + id_utc);
  xhttp.send();
}

function janelaVerTodosDocentes(id_utc,id_utilizador){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_verDocentesTodos").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/utc_coordenador/verDocentesTodos.php?id_utc=" + id_utc + "&id_utilizador=" + id_utilizador);
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
						url: "processamento/utc_coordenador/criarDocente.php", 
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

function janelaEditarDocente(id_docente){
  $('#verDocentesTodos').modal('hide');
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_editarDocente").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/utc_coordenador/editarDocente.php?id_docente=" + id_docente);
  xhttp.send();
}

function atualizarDocente(id_docente, login_atual,password_atual,nome_atual,id_utc_atual,id_area_atual,id_funcao_atual){
	
	const input_login = document.getElementById("editarDocente_login");
	const input_password = document.getElementById("editarDocente_password");
	const input_nome = document.getElementById("editarDocente_nome");
	const select_utc = document.getElementById("editarDocente_utc");
	const select_area = document.getElementById("editarDocente_area");
	const select_funcao = document.getElementById("editarDocente_funcao");
	
	const login = input_login.value;
	const password = input_password.value;
	const nome = input_nome.value;
	const id_utc = select_utc.value;
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
				url: "processamento/utc_coordenador/atualizarDocente.php", 
				data: {id_docente: id_docente, login: login, password: password, nome: nome, id_utc: id_utc, id_area: id_area, id_funcao: id_funcao},
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

/*
function atualizarAreas(){
	
	const select_utc = document.getElementById("criarDocente_utc");
	const utc_selecionada = select_utc.value;
	
	const select_area = document.getElementById("criarDocente_area");
	
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
*/

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

function temNumero(string) {
  return /\d/.test(string);
}

function removerDocente(id_docente){
	
	if(window.confirm("Tem a certeza que pretende eliminar este docente? (As aulas/junções de turmas ficarão sem nenhum docente associado)")){
		
		$.ajax ({
			type: "POST",
			url: "processamento/utc_coordenador/verificarDocenteAssociado.php", 
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
						url: "processamento/utc_coordenador/removerDocente.php", 
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

/*----------------------DSD----------------------*/

function bloquear1sem(id_utc){
	//alert("Bloquear 1º semestre!");
	$.ajax ({
		type: "POST",
		url: "processamento/utc_coordenador/bloquear_DSD_sem_1.php", 
		data: {id_utc: id_utc},
		success: function(result) {
			location.reload();
		},
		error: function(result) {
			alert("Erro ao bloquear DSD do 1º semestre: " + result);
		}
	});
}

function desbloquear1sem(id_utc){
	//alert("Desbloquear 1º semestre!");
	$.ajax ({
		type: "POST",
		url: "processamento/utc_coordenador/debloquear_DSD_sem_1.php", 
		data: {id_utc: id_utc},
		success: function(result) {
			location.reload();
		},
		error: function(result) {
			alert("Erro ao debloquear DSD do 1º semestre: " + result);
		}
	});
}

function bloquear2sem(id_utc){
	//alert("Bloquear 2º semestre!");
	$.ajax ({
		type: "POST",
		url: "processamento/utc_coordenador/bloquear_DSD_sem_2.php", 
		data: {id_utc: id_utc},
		success: function(result) {
			location.reload();
		},
		error: function(result) {
			alert("Erro ao bloquear DSD do 2º semestre: " + result);
		}
	});
}

function desbloquear2sem(id_utc){
	//alert("Desbloquear 2º semestre!");
	$.ajax ({
		type: "POST",
		url: "processamento/utc_coordenador/debloquear_DSD_sem_2.php", 
		data: {id_utc: id_utc},
		success: function(result) {
			location.reload();
		},
		error: function(result) {
			alert("Erro ao debloquear DSD do 2º semestre: " + result);
		}
	});
}

function verDadosCompletosCursos(){
	location.href = "http://localhost/apoio_utc/visCurso.php";
}

function verDadosAreasCompletas(){
	location.href = "http://localhost/apoio_utc/visArea.php";
}

function removeOptions(selectElement) {
	var i, L = selectElement.options.length - 1;
	for(i = L; i >= 0; i--) {
		selectElement.remove(i);
	}
}
</script>
<?php gerarHome2() ?>