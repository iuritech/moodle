<?php

//Gera as interfaces da página home até ao início do conteudo da página
function gerarHome1() {
	
//include('bd.h');
    
$permAdmin = false;
$permUTC = false;
$permHorarios = false;
   
$id_utilizador = $_SESSION["id"];
	
$conn = mysqli_connect("localhost:3306", "root", "");
if(!$conn)
{
	echo "Erro ao conectar ao MySQL.";
	exit;
}

$query = mysqli_select_db($conn,"apoioutc_ano_atual");

$statement0 = mysqli_prepare($conn, "SELECT ano_atual FROM ano_atual;");
$statement0->execute();
$resultado0 = $statement0->get_result();
$linha0 = mysqli_fetch_assoc($resultado0);
	$ano_atual_temp = explode("_",$linha0["ano_atual"]);
	$ano_atual_bd = $ano_atual_temp[2] . "/" . $ano_atual_temp[3];

// Selecionar a base de dados
$query = mysqli_select_db($conn,$_SESSION['bd']);

// UTF-8 para todos os dados obtidos da BD
mysqli_set_charset($conn,'utf8');
	
$statement = mysqli_prepare($conn, "SELECT is_admin FROM utilizador WHERE id_utilizador = $id_utilizador");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$is_admin = $linha["is_admin"];
		
	if($is_admin == 1){
		$permAdmin = true;
	}
	
$statement2 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc;");
$statement2->execute();
$resultado2 = $statement2->get_result();
while($linha2 = mysqli_fetch_assoc($resultado2)){
	$id_responsavel = $linha2["id_responsavel"];
	
	if($id_responsavel == $id_utilizador){
		$permUTC = true;
	}
}

$statement3 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc;");
$statement3->execute();
$resultado3 = $statement3->get_result();
while($linha3 = mysqli_fetch_assoc($resultado3)){
	$id_responsavel = $linha3["id_responsavel"];
	
	if($id_responsavel == $id_utilizador){
		$permUTC = true;
	}
}

$statement4 = mysqli_prepare($conn, "SELECT perm_horarios FROM utilizador WHERE id_utilizador = $id_utilizador");
$statement4->execute();
$resultado4 = $statement4->get_result();
while($linha4 = mysqli_fetch_assoc($resultado4)){
	$perm_horarios = $linha4["perm_horarios"];
	
	if($perm_horarios == 1){
		$permHorarios = true;
	}
}

$anos_letivos = array();	

$result = mysqli_query($conn,"SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA 
WHERE SCHEMA_NAME LIKE 'apoio_utc%' ORDER BY SCHEMA_NAME DESC;"); 
while ($row = mysqli_fetch_array($result)) { 
	$nome_bd = $row[0];
	
	$tmp = explode("_",$nome_bd);
	
	$nome_ano = $tmp[2] . "/" . $tmp[3];
	
	$ano_letivo = $nome_bd.substr($nome_bd,9,9);
	
	array_push($anos_letivos,$nome_bd);
	array_push($anos_letivos,$nome_ano);
}

$ano_letivo_atual_tmp = explode("_",$_SESSION["bd"]);
$ano_letivo_atual = $ano_letivo_atual_tmp[2] . "/" . $ano_letivo_atual_tmp[3];
	
$semestre_atual = $_SESSION['semestre'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>ApoioUTC</title>
		<link rel="shortcut icon" type="image/jpg" href="images/fav_icon.png"/>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/chart.js/Chart.min.js"></script>        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	</head>
    <body>
	<div id="cover-spin"></div>
		<div class="dropdown">
			<nav>
				<a class="nav-home" align="center" href="home.php"><b>ApoioUTC</b></a>
				<ul>
				<?php
				if($ano_letivo_atual != $ano_atual_bd){ ?>
				<!-- <li id='li_ANO'><a href="javascript:void(0)"><i class="material-icons" style="vertical-align:middle; color:#c9bfff;">calendar_month</i> <text style='color:#c9bfff;'><?php echo $ano_letivo_atual; ?></text></a> -->
				<li id='li_ANO'><a href="javascript:void(0)"><i class="material-icons" style="vertical-align:middle; color:#9c9c9c;">calendar_month</i> <b><text style='color:#9c9c9c;'><?php echo $ano_letivo_atual; ?></text></b></a>
				<?php } else{ ?>
				<li id='li_ANO'><a href="javascript:void(0)"><i class="material-icons" style="vertical-align:middle;">calendar_month</i> <b><?php echo $ano_letivo_atual; ?></b></a>
				<?php } ?>
				<ul>
				<?php
					$loop = 0;
					while($loop < sizeof($anos_letivos)){
						$nome_bd = $anos_letivos[$loop];
						$ano_letivo = $anos_letivos[$loop + 1];
						
						if($ano_letivo != $ano_letivo_atual) {
							if($ano_letivo != $ano_atual_bd){
						?>
						<li id='' style='width:110px;'><a href="processamento/alterarBD.php?nome_bd=<?php echo $nome_bd ?>"> <b><text style='color:#9c9c9c;'><?php echo $ano_letivo; ?></text></b></a></li>
					<?php	}
							else{ ?>
						<li id='' style='width:110px;'><a href="processamento/alterarBD.php?nome_bd=<?php echo $nome_bd ?>"> <b><?php echo $ano_letivo; ?></b></a></li>	
					<?php	}	
						}
					$loop += 2;
					} ?>
<!--					<li id='li_ANO_2020_2021' style='width:110px;'><a href="#"> 2020/2021</a></li>
					<li id='li_ANO_2019_2020' style='width:110px;'><a href="#"> 2019/2020</a></li> -->
					<?php if($permAdmin) { ?>
					<!--	<li id='' style='width:110px;'><a href="#"><i class="material-icons" style="vertical-align:middle;">radio_button_unchecked</i> <i>Ano atual</i></a></li> -->
						<li id='' style='width:110px;'><a href="#" data-toggle='modal' data-target='#anoLetivo' onclick="janelaCurso()"><i class="material-icons" style="vertical-align:middle;">add</i> <i>Ano letivo</i></a></li>	
					<?php } ?>
				</ul>
				<?php if ($permAdmin) { ?>
					<li id='li_UTIL'><a href="visUtil.php"><i class="material-icons" style="vertical-align:middle;">people</i> <text style="font-weight:500;">Utilizadores</text></a></li>
					<li id='li_UTC'><a href="visUTC.php"><i class="material-icons" style="vertical-align:middle;">menu_book</i> <text style="font-weight:500;">UTC's</text></a></li>
					<li id='li_CURSO'><a href="visCurso.php"><i class="material-icons" style="vertical-align:middle;">school</i> <text style="font-weight:500;">Cursos</text></a></li>
					<li id='li_AREA'><a href="visArea.php"><i class="material-icons" style="vertical-align:middle;">monitor</i> <text style="font-weight:500;">Áreas</text></a></li>
					<li id='li_HORARIOS'><a href="#"><i class="material-icons" style="vertical-align:middle;">schedule</i> <text style="font-weight:500;">Salas</text></a>
					<ul>						
						<li id='li_HORARIOS_CONFIGURAR' style='width:135px;'><a href="gerirsalas.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">settings</i> <i>Gerir <text style="font-weight:500;"></text></i></a></li>
						<li id='li_HORARIOS_SALAS' style='width:135px;'><a href="visSalaporUC.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">meeting_room</i> <i>Atribuição <text style="font-weight:500;"></text></i></a></li>
					</ul>
					</li>					<li id='li_DSD'><a href="#"><i class="material-icons" style="vertical-align:middle;">ballot</i> <text style="font-weight:500;">Distribuição de serviço docente</text></a>
					<ul>
						<li id='li_DSD_DSUC' style='width:180px;'><a href="visDSUC.php"><i class="material-icons" style="vertical-align:middle;">class</i> <i>Por <text style="font-weight:500;">Disciplina</text></i></a></li>
						<li id='li_DSD_DSD' style='width:180px;'><a href="visDSD.php"><i class="material-icons" style="vertical-align:middle;">contacts</i> <i>Por <text style="font-weight:500;">Docente</text></i></a></li>
						<li id='li_juncoes' style='width:180px;'><a href="visJuncoes.php"><i class="material-icons" style="vertical-align:middle;">join_inner</i> <i>Junções de Turmas</i></a></li>
					</ul>
					</li>
					<li id='li_HORARIOS'><a href="javascript:void(0)"><i class="material-icons" style="vertical-align:middle;">schedule</i> <text style="font-weight:500;">Horários</text></a>
					<ul>
						<li id='li_HORARIOS_SALAS' style='width:135px;'><a href="visHorariosSala.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">meeting_room</i> <i>Por <text style="font-weight:500;">Sala</text></i></a></li>
						<li id='li_HORARIOS_DOCENTES' style='width:135px;'><a href="visHorariosDocente.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">contacts</i> <i>Por <text style="font-weight:500;">Docente</text></i></a></li>
						<li id='li_HORARIOS_TURMAS' style='width:135px;'><a href="visHorariosTurma.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">people</i> <i>Por <text style="font-weight:500;">Turma</text></i></a></li>
						<li id='li_HORARIOS_CONFIGURAR' style='width:135px;'><a href="visHorariosConfigurar.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">settings</i> <i>Configurar <text style="font-weight:500;"></text></i></a></li>
					</ul>
					</li>
				<?php } else if ($permUTC) { ?>
					<li id='li_UTC' style='margin-left:55px;'><a href="coordUTC.php"><i class="material-icons" style="vertical-align:middle;">menu_book</i> <text style="font-weight:500;">UTC</text></a></li>
					<li id='li_CURSO'><a href="visCurso.php"><i class="material-icons" style="vertical-align:middle;">school</i> <text style="font-weight:500;">Cursos</text></a></li>
					<li id='li_AREA'><a href="visArea.php"><i class="material-icons" style="vertical-align:middle;">monitor</i> <text style="font-weight:500;">Áreas</text></a></li>
					<li id='li_DSD'><a href="#"><i class="material-icons" style="vertical-align:middle;">ballot</i> <text style="font-weight:500;">Distribuição de serviço docente</text></a>
					<ul>
						<li id='li_DSD_DSUC' style='width:180px;'><a href="visDSUC.php"><i class="material-icons" style="vertical-align:middle;">class</i> <i>Por <text style="font-weight:500;">Disciplina</text></i></a></li>
						<li id='li_DSD_DSD' style='width:180px;'><a href="visDSD.php"><i class="material-icons" style="vertical-align:middle;">contacts</i> <i>Por <text style="font-weight:500;">Docente</text></i></a></li>
						<li id='li_juncoes' style='width:180px;'><a href="visJuncoes.php"><i class="material-icons" style="vertical-align:middle;">join_inner</i> <i>Junções de Turmas</i></a></li>
					</ul>
					</li>
					<li id='li_HORARIOS'><a href="javascript:void(0)"><i class="material-icons" style="vertical-align:middle;">schedule</i> <text style="font-weight:500;">Horários</text></a>
					<ul>
						<li id='li_HORARIOS_SALAS' style='width:135px;'><a href="visHorariosSala.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">meeting_room</i> <i>Por <text style="font-weight:500;">Sala</text></i></a></li>
						<li id='li_HORARIOS_DOCENTES' style='width:135px;'><a href="visHorariosDocente.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">contacts</i> <i>Por <text style="font-weight:500;">Docente</text></i></a></li>
						<li id='li_HORARIOS_TURMAS' style='width:135px;'><a href="visHorariosTurma.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">people</i> <i>Por <text style="font-weight:500;">Turma</text></i></a></li>
						<li id='li_HORARIOS_CONFIGURAR' style='width:135px;'><a href="visHorariosConfigurar.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">settings</i> <i>Configurar <text style="font-weight:500;"></text></i></a></li>
				</ul>
					</li>
				<?php } else { ?>
					<li id='li_CURSO' style='margin-left:50px;'><a href="visCurso.php"><i class="material-icons" style="vertical-align:middle;">school</i> <text style="font-weight:500;">Cursos</text></a></li>
					<li id='li_AREA'><a href="visArea.php"><i class="material-icons" style="vertical-align:middle;">monitor</i> <text style="font-weight:500;">Áreas</text></a></li>
					<li id='li_DSD'><a href="#"><i class="material-icons" style="vertical-align:middle;">ballot</i> <text style="font-weight:500;">Distribuição de serviço docente</text></a>
					<ul>
						<li id='li_DSD_DSUC' style='width:180px;'><a href="visDSUC.php"><i class="material-icons" style="vertical-align:middle;">class</i> <i>Por <text style="font-weight:500;">Disciplina</text></i></a></li>
						<li id='li_DSD_DSD' style='width:180px;'><a href="visDSD.php"><i class="material-icons" style="vertical-align:middle;">contacts</i> <i>Por <text style="font-weight:500;">Docente</text></i></a></li>
						<li id='li_juncoes' style='width:180px;'><a href="visJuncoes.php"><i class="material-icons" style="vertical-align:middle;">join_inner</i> <i>Junções de Turmas</i></a></li>
					</ul>
					</li>
					<li id='li_HORARIOS'><a href="javascript:void(0)"><i class="material-icons" style="vertical-align:middle;">schedule</i> <text style="font-weight:500;">Horários</text></a>
					<ul>
						<li id='li_HORARIOS_SALAS' style='width:135px;'><a href="visHorariosSala.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">meeting_room</i> <i>Por <text style="font-weight:500;">Sala</text></i></a></li>
						<li id='li_HORARIOS_DOCENTES' style='width:135px;'><a href="visHorariosDocente.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">contacts</i> <i>Por <text style="font-weight:500;">Docente</text></i></a></li>
						<li id='li_HORARIOS_TURMAS' style='width:135px;'><a href="visHorariosTurma.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">people</i> <i>Por <text style="font-weight:500;">Turma</text></i></a></li>
						<?php if($permHorarios) {?>
						<li id='li_HORARIOS_CONFIGURAR' style='width:135px;'><a href="visHorariosConfigurar.php?sem=<?php echo $semestre_atual; ?>"><i class="material-icons" style="vertical-align:middle;">settings</i> <i>Configurar <text style="font-weight:500;"></text></i></a></li>
						<?php } ?>
					</ul>
					</li>
				<?php } ?>
					<!-- Navbar
					<ul class="navbar-nav ml-auto ml-md-0" style="width:25px;">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="alterarPassword.php">Alterar password</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="processamento/logout.php">Logout</a>
							</div>
						</li>
					</ul> -->
				</ul>
				<ul style="float:right; margin-right:3px;">
					<li><a href="processamento/logout.php"><div class="pulse"></div><img src="<?php echo $_SESSION['img_perfil']; ?>" style="width: 35px; heigth: 35px; border-radius:50%; margin-bottom:6px; border:0.15rem solid #ffffff;"></a></li>
				</ul>
			</nav>
        </div>
			<?php
}

//Gera as interfaces da página home desde o fim do conteudo da página até ao footer
function gerarHome2() { ?>
    <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Projecto ApoioUTC 2024</div>
                            <div>
                                &middot;
                                Em desenvolvimento
                            </div>
                        </div>
                    </div>
                </footer>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        
        <script src="vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    </body>
	
	<div class="modal fade" id="anoLetivo" tabindex="-1" role="dialog" aria-labelledby="titulo_anoLetivo" aria-hidden="true">
		<div class="modal-dialog" style="max-width: 24%;" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="titulo_anoLetivo"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div id="modalBody_anoLetivo" class="modal-body">
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="criarAnoLetivo" tabindex="-1" role="dialog" aria-labelledby="titulo_criarAnoLetivo" aria-hidden="true">
		<div class="modal-dialog" style="max-width: 22%;" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="titulo_criarAnoLetivo">Docentes</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div id="modalBody_criarAnoLetivo" class="modal-body">
				</div>
			</div>
		</div>
	</div>
	
</html>
<script language="javascript">
function janelaCurso(){
  document.getElementById("titulo_anoLetivo").innerHTML = "Ano Letivo";
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_anoLetivo").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/administrador/anoLetivo.php");
  xhttp.send();
}

function mostrarDadosCopiarDadosAnteriores(){
	
	const container_janela = document.getElementById("ano_letivo");
	const checkbox_copiar_dados = document.getElementById("copiar_dados_anteriores");
	const dados_anteriores_container = document.getElementById("dados_anteriores_container");
	
	const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
	const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
	const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
	const checkbox_areas = document.getElementById("dados_anteriores_areas");
	const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
	const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
	const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
	const checkbox_horarios = document.getElementById("dados_anteriores_horarios");
		
	if(checkbox_copiar_dados.checked){
		container_janela.style.height = "430px";
		dados_anteriores_container.style.display = "inline-block";
		
		checkbox_utcs.checked = true;
		checkbox_docentes.checked = true;
		checkbox_cursos.checked = true;
		checkbox_areas.checked = true;
		checkbox_ucs.checked = true;
		checkbox_turmas.checked = true;
		checkbox_dsd.checked = true;
		checkbox_horarios.checked = true;
	}
	else{
		container_janela.style.height = "260px";
		dados_anteriores_container.style.display = "none";
		
		checkbox_utcs.checked = false;
		checkbox_docentes.checked = false;
		checkbox_cursos.checked = false;
		checkbox_areas.checked = false;
		checkbox_ucs.checked = false;
		checkbox_turmas.checked = false;
		checkbox_dsd.checked = false;
		checkbox_horarios.checked = false;
	}
}

function atualizarOpcoesDadosAnteriores_UTC(){
	
	const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
	const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
	const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
	const checkbox_areas = document.getElementById("dados_anteriores_areas");
	const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
	const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
	const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
	const checkbox_horarios = document.getElementById("dados_anteriores_horarios");
		
	if(checkbox_utcs.checked){
		checkbox_areas.checked = true;
		checkbox_docentes.checked = true;
	}
	else{
		checkbox_utcs.checked = false;
		checkbox_docentes.checked = false;
		checkbox_cursos.checked = false;
		checkbox_areas.checked = false;
		checkbox_ucs.checked = false;
		checkbox_turmas.checked = true;
		checkbox_dsd.checked = false;
		checkbox_horarios.checked = false;
	}
	
	atualizarOpcoesDadosAnteriores_cursos();
	atualizarOpcoesDadosAnteriores_areas();
	atualizarOpcoesDadosAnteriores_docentes();
	atualizarOpcoesDadosAnteriores_ucs();
	atualizarOpcoesDadosAnteriores_turmas();
	atualizarOpcoesDadosAnteriores_dsd();
	atualizarOpcoesDadosAnteriores_horarios();
	
}

function atualizarOpcoesDadosAnteriores_cursos(){
	
	const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
	const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
	const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
	const checkbox_areas = document.getElementById("dados_anteriores_areas");
	const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
	const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
	const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
	const checkbox_horarios = document.getElementById("dados_anteriores_horarios");
		
	if(checkbox_cursos.checked){
		checkbox_utcs.checked = true;
		checkbox_areas.checked = true;
		checkbox_docentes.checked = true;
	}
	else{
		checkbox_ucs.checked = false;
		checkbox_turmas.checked = false;
		checkbox_dsd.checked = false;
		checkbox_horarios.checked = false;
	}

	atualizarOpcoesDadosAnteriores_UTC();
	atualizarOpcoesDadosAnteriores_areas();
	atualizarOpcoesDadosAnteriores_docentes();
	atualizarOpcoesDadosAnteriores_ucs();
	atualizarOpcoesDadosAnteriores_turmas();
	atualizarOpcoesDadosAnteriores_dsd();
	atualizarOpcoesDadosAnteriores_horarios();
	
}

function atualizarOpcoesDadosAnteriores_areas(){
	
	const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
	const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
	const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
	const checkbox_areas = document.getElementById("dados_anteriores_areas");
	const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
	const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
	const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
	const checkbox_horarios = document.getElementById("dados_anteriores_horarios");
		
	if(checkbox_areas.checked){
		checkbox_utcs.checked = true;
		checkbox_docentes.checked = true;
	}
	else{
		checkbox_docentes.checked = false;
		checkbox_ucs.checked = false;
		checkbox_dsd.checked = false;
		checkbox_horarios.checked = false;
	}
	
	atualizarOpcoesDadosAnteriores_UTC();
	atualizarOpcoesDadosAnteriores_cursos();
	atualizarOpcoesDadosAnteriores_docentes();
	atualizarOpcoesDadosAnteriores_ucs();
	atualizarOpcoesDadosAnteriores_turmas();
	atualizarOpcoesDadosAnteriores_dsd();
	atualizarOpcoesDadosAnteriores_horarios();
	
}

function atualizarOpcoesDadosAnteriores_docentes(){
	
	const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
	const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
	const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
	const checkbox_areas = document.getElementById("dados_anteriores_areas");
	const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
	const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
	const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
	const checkbox_horarios = document.getElementById("dados_anteriores_horarios");
		
	if(checkbox_docentes.checked){
		checkbox_utcs.checked = true;
		checkbox_areas.checked = true;
	}
	else{
		checkbox_utcs.checked = false;
		checkbox_cursos.checked = false;
		checkbox_areas.checked = false;
		checkbox_ucs.checked = false;
		checkbox_turmas.checked = false;
		checkbox_dsd.checked = false;
		checkbox_horarios.checked = false;
	}
	
	atualizarOpcoesDadosAnteriores_UTC();
	atualizarOpcoesDadosAnteriores_cursos();
	atualizarOpcoesDadosAnteriores_areas();
	atualizarOpcoesDadosAnteriores_ucs();
	atualizarOpcoesDadosAnteriores_turmas();
	atualizarOpcoesDadosAnteriores_dsd();
	atualizarOpcoesDadosAnteriores_horarios();
	
}

function atualizarOpcoesDadosAnteriores_ucs(){
	
	const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
	const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
	const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
	const checkbox_areas = document.getElementById("dados_anteriores_areas");
	const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
	const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
	const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
	const checkbox_horarios = document.getElementById("dados_anteriores_horarios");
		
	if(checkbox_ucs.checked){
		checkbox_utcs.checked = true;
		checkbox_areas.checked = true;
		checkbox_cursos.checked = true;
		checkbox_docentes.checked = true;
	}
	else{
		checkbox_dsd.checked = false;
		checkbox_horarios.checked = false;
	}
	
	atualizarOpcoesDadosAnteriores_UTC();
	atualizarOpcoesDadosAnteriores_cursos();
	atualizarOpcoesDadosAnteriores_areas();
	atualizarOpcoesDadosAnteriores_docentes();
	atualizarOpcoesDadosAnteriores_turmas();
	atualizarOpcoesDadosAnteriores_dsd();
	atualizarOpcoesDadosAnteriores_horarios();
	
}

function atualizarOpcoesDadosAnteriores_turmas(){
	
	const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
	const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
	const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
	const checkbox_areas = document.getElementById("dados_anteriores_areas");
	const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
	const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
	const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
	const checkbox_horarios = document.getElementById("dados_anteriores_horarios");

	if(checkbox_turmas.checked){
		checkbox_utcs.checked = true;
		checkbox_cursos.checked = true;
		checkbox_areas.checked = true;
		checkbox_docentes.checked = true;
	}
		
	else{
		checkbox_dsd.checked = false;
		checkbox_horarios.checked = false;
	}

	atualizarOpcoesDadosAnteriores_UTC();
	atualizarOpcoesDadosAnteriores_cursos();
	atualizarOpcoesDadosAnteriores_areas();
	atualizarOpcoesDadosAnteriores_docentes();
	atualizarOpcoesDadosAnteriores_ucs();
	atualizarOpcoesDadosAnteriores_dsd();
	atualizarOpcoesDadosAnteriores_horarios();
	
}

function atualizarOpcoesDadosAnteriores_dsd(){
	
	const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
	const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
	const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
	const checkbox_areas = document.getElementById("dados_anteriores_areas");
	const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
	const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
	const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
	const checkbox_horarios = document.getElementById("dados_anteriores_horarios");
		
	if(checkbox_dsd.checked){
		checkbox_utcs.checked = true;
		checkbox_areas.checked = true;
		checkbox_cursos.checked = true;
		checkbox_docentes.checked = true;
		checkbox_ucs.checked = true;
		checkbox_turmas.checked = true;
	}
	else{
		checkbox_horarios.checked = false;
	}
	
	atualizarOpcoesDadosAnteriores_UTC();
	atualizarOpcoesDadosAnteriores_cursos();
	atualizarOpcoesDadosAnteriores_areas();
	atualizarOpcoesDadosAnteriores_docentes();
	atualizarOpcoesDadosAnteriores_ucs();
	atualizarOpcoesDadosAnteriores_turmas();
	atualizarOpcoesDadosAnteriores_horarios();
	
}

function atualizarOpcoesDadosAnteriores_horarios(){
	
	const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
	const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
	const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
	const checkbox_areas = document.getElementById("dados_anteriores_areas");
	const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
	const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
	const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
	const checkbox_horarios = document.getElementById("dados_anteriores_horarios");
		
	if(checkbox_horarios.checked){
		checkbox_utcs.checked = true;
		checkbox_areas.checked = true;
		checkbox_cursos.checked = true;
		checkbox_docentes.checked = true;
		checkbox_ucs.checked = true;
		checkbox_dsd.checked = true;
		checkbox_turmas.checked = true;
	}
	
	atualizarOpcoesDadosAnteriores_UTC();
	atualizarOpcoesDadosAnteriores_cursos();
	atualizarOpcoesDadosAnteriores_areas();
	atualizarOpcoesDadosAnteriores_docentes();
	atualizarOpcoesDadosAnteriores_ucs();
	atualizarOpcoesDadosAnteriores_turmas();
	atualizarOpcoesDadosAnteriores_dsd();
	
}

function criarAnoLetivo(){
	
	if(window.confirm("Tem a certeza que pretende criar um novo ano letivo?")){
	
		const checkbox_copiar_dados = document.getElementById("copiar_dados_anteriores");
		const checkbox_utcs = document.getElementById("dados_anteriores_utcs");
		const checkbox_docentes = document.getElementById("dados_anteriores_docentes");
		const checkbox_cursos = document.getElementById("dados_anteriores_cursos");
		const checkbox_areas = document.getElementById("dados_anteriores_areas");
		const checkbox_ucs = document.getElementById("dados_anteriores_ucs");
		const checkbox_turmas = document.getElementById("dados_anteriores_turmas");
		const checkbox_dsd = document.getElementById("dados_anteriores_dsd");
		const checkbox_horarios = document.getElementById("dados_anteriores_horarios");
		
		var array_opcoes = [];
		
		array_opcoes.push(checkbox_copiar_dados.checked);
		array_opcoes.push(checkbox_utcs.checked);
		array_opcoes.push(checkbox_docentes.checked);
		array_opcoes.push(checkbox_cursos.checked);
		array_opcoes.push(checkbox_areas.checked);
		array_opcoes.push(checkbox_ucs.checked);
		array_opcoes.push(checkbox_turmas.checked);
		array_opcoes.push(checkbox_dsd.checked);
		array_opcoes.push(checkbox_horarios.checked);
		
		$('#anoLetivo').modal('hide');
		
		$('#cover-spin').show(0);
		
		$.ajax ({
			type: "POST",
			url: "processamento/administrador/criarAnoLetivo.php", 
			data: {array_opcoes: array_opcoes},
			success: function(result) {
				$('#cover-spin').hide();
				//alert(result);
				alert("Ano letivo criado com sucesso!");
				location.reload();  
			}
		});
		
	}
	
}

function atualizarAnoLetivo(ano_atual){
	
	const select_ano_letivo = document.getElementById("select_ano_letivo");
	const nome_bd_selecionada = select_ano_letivo.value;
	
	var tmp = nome_bd_selecionada.split("_");
	const ano_selecionado = tmp[2] + "/" + tmp[3];
	
	if(ano_selecionado == ano_atual){
		$('#anoLetivo').modal('hide');
	}
	else{
		$.ajax ({
			type: "POST",
			url: "processamento/administrador/atualizarAnoLetivo.php", 
			data: {nome_bd_selecionada: nome_bd_selecionada},
			success: function(result) {
				location.reload();
			}
		});
	}
}

</script>
    <?php
}
?>