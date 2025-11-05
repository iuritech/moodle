<?php
// Página de gestão de UTCs

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
if(!isset($_SESSION['permAdmin'])){
    header("Location: index.php");
}

$id_utilizador = $_SESSION["id"];

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
		<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">UTC's (Admin)</a></h6>
			<h3 style="margin-left:15px; margin-top:25px;"><b>Unidades Técnico-Científicas</b> <a class="btn btn-primary" data-toggle="modal" data-target="#criarUTC" style="border-radius:25px; margin-left:15px;" onclick="gerarFormCriarUTC()"><i class="material-icons" style="vertical-align: middle;">add_circle</i><b> UTC</b></a></h3>
		
			<?php
				
				$utcs = array();
				
				$loop = 0;
			
				$statement = mysqli_prepare($conn, "SELECT COUNT(id_utc) FROM utc WHERE id_responsavel = $id_utilizador;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$utc_responsavel_utilizador = $linha["COUNT(id_utc)"];
					
				if($utc_responsavel_utilizador > 0){
					$statement = mysqli_prepare($conn, "SELECT * FROM utc WHERE id_responsavel = $id_utilizador;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);		
						$id_utc = $linha["id_utc"];
						$nome_utc = $linha["nome_utc"]; 
						$id_coordenador = $linha["id_responsavel"];
						
					$statement1 = mysqli_prepare($conn, "SELECT nome, imagem_perfil FROM utilizador WHERE id_utilizador = $id_coordenador;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$nome_coordenador = $linha1["nome"];
						$imagem_coordenador = $linha1["imagem_perfil"];
						
					$statement2 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_utilizador) FROM utilizador WHERE id_utc = $id_utc;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$num_docentes = $linha2["COUNT(DISTINCT id_utilizador)"];
						
					$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_curso) FROM curso WHERE id_utc = $id_utc;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$num_cursos = $linha3["COUNT(DISTINCT id_curso)"];
						
					$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_area) FROM area WHERE id_utc = $id_utc;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					$linha4 = mysqli_fetch_assoc($resultado4);
						$num_areas = $linha4["COUNT(DISTINCT id_area)"]; ?>
						
						<div class="card_utc" style="margin-top:30px; cursor:pointer; background:#ededed;">
						<div class="card_utc_1" onclick="coordenadorUTC()">
							<i class="material-icons" style="font-size:80px; margin-left:35px; margin-top:25px;">menu_book</i>
						</div>
						<div class="card_utc_2">
							<div class="card_utc_2_dados_iniciais" onclick="coordenadorUTC()">
								<?php
									echo "<h3 style='margin-top:25px; margin-bottom:25px;'>",$nome_utc,"</h3>";
									echo "<text style='font-weight:360; line-height:1.2; font-size:1.05rem; margin-top:95px; margin-right:5px; margin-left:15px;'>Coordenador:</text>";
									echo "<text style='font-weight:500; line-height:1.2; '><b>", $nome_coordenador, "</b></text>";
								?>
							</div>
							<div class="card_utc_2_docentes" onclick="coordenadorUTC()">
								<i class="material-icons" style="font-size:30px;">people</i><text style="font-size:1.1rem; font-weight:500; line-height:1.2; margin-top:-5px; margin-left:5px;">Docentes</text> (<?php echo $num_docentes; ?>)
								<br>
								<?php
									$loop_utilizador = 0;
									$statement5 = mysqli_prepare($conn, "SELECT DISTINCT * FROM utilizador WHERE id_utc = $id_utc ORDER BY nome;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									while($linha5 = mysqli_fetch_assoc($resultado5)){
										$id_utilizador = $linha5["id_utilizador"];			
										$nome_utilizador = $linha5["nome"];
										
										if(strlen($nome_utilizador) > 16){
											$temp = explode(" ",$nome_utilizador);
											$nome_utilizador = substr($nome_utilizador,0,1) . ". " . $temp[sizeof($temp) - 1];
										}
										
										if($id_utilizador == $id_coordenador){
											echo "<text style='font-size:15px;'><b>", $nome_utilizador, "</b></text><br>";
										}
										else{
											echo "<text style='font-size:15px;'>", $nome_utilizador, "</text><br>";
										}
										
										$loop_utilizador += 1;
										if($loop_utilizador == 3){
											echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#verDocentesTodos' onclick='janelaListaDocentes($id_utc)' style='margin-left:40px;'><i>Ver mais...</i></a>";
											//echo "<text style='font-size:15px;'>...</text>";
											break;
										}
									}
								?>
							</div>
							<div class="card_utc_2_cursos" onclick="coordenadorUTC()">
								<i class="material-icons" style="font-size:30px;">school</i><text style="font-size:1.1rem; font-weight:500; line-height:1.2; margin-bottom:15px; margin-left:5px;">Cursos</text> (<?php echo $num_cursos; ?>)
								<br>
								<?php
									$loop_curso = 0;
									$statement6 = mysqli_prepare($conn, "SELECT DISTINCT * FROM curso WHERE id_utc = $id_utc ORDER BY nome;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									while($linha6 = mysqli_fetch_assoc($resultado6)){
										$sigla_curso = $linha6["sigla"];
										
										echo "<text style='font-size:15px;'>", $sigla_curso, "</text><br>";
										
										$loop_curso += 1;
										if($loop_curso == 3){
											echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#verCursosTodos' onclick='janelaListaCursos($id_utc)' style='margin-left:40px;'><i>Ver mais...</i></a>";
											break;
										}
									}
								?>
							</div>
							<div class="card_utc_2_areas" onclick="coordenadorUTC()">
								<i class="material-icons" style="font-size:30px;">monitor</i><text style="font-size:1.1rem; font-weight:500; line-height:1.2; margin-top:-45px; margin-left:5px;">Áreas</text> (<?php echo $num_areas; ?>)
								<br>
								<?php
									$loop_area = 0;
									$statement7 = mysqli_prepare($conn, "SELECT DISTINCT * FROM area WHERE id_utc = $id_utc ORDER BY nome;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									while($linha7 = mysqli_fetch_assoc($resultado7)){
										$nome_area = $linha7["nome"];
										
										echo "<text style='font-size:15px;'>", $nome_area, "</text><br>";
										
										$loop_area += 1;
										if($loop_area == 3){
											echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#verAreasTodas' onclick='janelaListaAreas($id_utc)' style='margin-left:40px;'><i>Ver mais...</i></a>";
											break;
										}
									}
								?>
							</div>
							<a class="btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#editarUTC" onclick="gerarFormEditarUTC(<?php echo $id_utc; ?>)" style='width:45px; height:26px; border-radius:25px; float:right; margin-top:10px; margin-right:10px;'><i class='material-icons' style='margin-left:-3px; margin-top:-6px;'>settings</i></a>
						</div>
					</div>
						
				<?php	
				$loop += 1;
				array_push($utcs,$id_utc);
				
				}
				$utcs_final = implode(",",$utcs);
				if(sizeof($utcs) > 0){
				$statement = mysqli_prepare($conn, "SELECT DISTINCT * FROM utc WHERE id_utc NOT IN ($utcs_final) ORDER BY nome_utc;");
				}
				else{
				$statement = mysqli_prepare($conn, "SELECT DISTINCT * FROM utc ORDER BY nome_utc;");
				}
				$statement->execute();
				$resultado = $statement->get_result();
				while($linha = mysqli_fetch_assoc($resultado)){
					$id_utc = $linha["id_utc"];
					$nome_utc = $linha["nome_utc"]; 
					$id_coordenador = $linha["id_responsavel"];
					
					$statement1 = mysqli_prepare($conn, "SELECT nome, imagem_perfil FROM utilizador WHERE id_utilizador = $id_coordenador;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					$linha1 = mysqli_fetch_assoc($resultado1);
						$nome_coordenador = $linha1["nome"];
						$imagem_coordenador = $linha1["imagem_perfil"];
						
					$statement2 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_utilizador) FROM utilizador WHERE id_utc = $id_utc;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					$linha2 = mysqli_fetch_assoc($resultado2);
						$num_docentes = $linha2["COUNT(DISTINCT id_utilizador)"];
						
					$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_curso) FROM curso WHERE id_utc = $id_utc;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$num_cursos = $linha3["COUNT(DISTINCT id_curso)"];
						
					$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_area) FROM area WHERE id_utc = $id_utc;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					$linha4 = mysqli_fetch_assoc($resultado4);
						$num_areas = $linha4["COUNT(DISTINCT id_area)"];
					
					if($loop == 0){ 
					?>
					<div class="card_utc" style="margin-top:30px;">
					<?php } else{ ?>
					<div class="card_utc">
					<?php } ?>
						<div class="card_utc_1">
							<i class="material-icons" style="font-size:80px; margin-left:35px; margin-top:25px;">menu_book</i>
						</div>
						<div class="card_utc_2">
							<div class="card_utc_2_dados_iniciais">
								<?php
									echo "<h3 style='margin-top:25px; margin-bottom:25px;'>",$nome_utc,"</h3>";
									echo "<text style='font-weight:360; line-height:1.2; font-size:1.05rem; margin-top:95px; margin-right:5px; margin-left:15px;'>Coordenador:</text>";
									echo "<text style='font-weight:500; line-height:1.2; '><b>", $nome_coordenador, "</b></text>";
								?>
							</div>
							<div class="card_utc_2_docentes">
								<i class="material-icons" style="font-size:30px;">people</i><text style="font-size:1.1rem; font-weight:500; line-height:1.2; margin-top:-5px; margin-left:5px;">Docentes</text> (<?php echo $num_docentes; ?>)
								<br>
								<?php
									$loop_utilizador = 0;
									$statement5 = mysqli_prepare($conn, "SELECT DISTINCT * FROM utilizador WHERE id_utc = $id_utc ORDER BY nome;");
									$statement5->execute();
									$resultado5 = $statement5->get_result();
									while($linha5 = mysqli_fetch_assoc($resultado5)){
										$id_utilizador = $linha5["id_utilizador"];			
										$nome_utilizador = $linha5["nome"];
										
										if(strlen($nome_utilizador) > 16){
											$temp = explode(" ",$nome_utilizador);
											$nome_utilizador = substr($nome_utilizador,0,1) . ". " . $temp[sizeof($temp) - 1];
										}
										
										if($id_utilizador == $id_coordenador){
											echo "<text style='font-size:15px;'><b>", $nome_utilizador, "</b></text><br>";
										}
										else{
											echo "<text style='font-size:15px;'>", $nome_utilizador, "</text><br>";
										}
										
										$loop_utilizador += 1;
										if($loop_utilizador == 3){
											echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#verDocentesTodos' onclick='janelaListaDocentes($id_utc)' style='margin-left:40px;'><i>Ver mais...</i></a>";
											//echo "<text style='font-size:15px;'>...</text>";
											break;
										}
									}
								?>
							</div>
							<div class="card_utc_2_cursos">
								<i class="material-icons" style="font-size:30px;">school</i><text style="font-size:1.1rem; font-weight:500; line-height:1.2; margin-bottom:15px; margin-left:5px;">Cursos</text> (<?php echo $num_cursos; ?>)
								<br>
								<?php
									$loop_curso = 0;
									$statement6 = mysqli_prepare($conn, "SELECT DISTINCT * FROM curso WHERE id_utc = $id_utc ORDER BY nome;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									while($linha6 = mysqli_fetch_assoc($resultado6)){
										$sigla_curso = $linha6["sigla"];
										
										echo "<text style='font-size:15px;'>", $sigla_curso, "</text><br>";
										
										$loop_curso += 1;
										if($loop_curso == 3){
											echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#verCursosTodos' onclick='janelaListaCursos($id_utc)' style='margin-left:40px;'><i>Ver mais...</i></a>";
											break;
										}
									}
								?>
							</div>
							<div class="card_utc_2_areas">
								<i class="material-icons" style="font-size:30px;">monitor</i><text style="font-size:1.1rem; font-weight:500; line-height:1.2; margin-top:-45px; margin-left:5px;">Áreas</text> (<?php echo $num_areas; ?>)
								<br>
								<?php
									$loop_area = 0;
									$statement7 = mysqli_prepare($conn, "SELECT DISTINCT * FROM area WHERE id_utc = $id_utc ORDER BY nome;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									while($linha7 = mysqli_fetch_assoc($resultado7)){
										$nome_area = $linha7["nome"];
										
										echo "<text style='font-size:15px;'>", $nome_area, "</text><br>";
										
										$loop_area += 1;
										if($loop_area == 3){
											echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#verAreasTodas' onclick='janelaListaAreas($id_utc)' style='margin-left:40px;'><i>Ver mais...</i></a>";
											break;
										}
									}
								?>
							</div>
							<a class="btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#editarUTC" onclick="gerarFormEditarUTC(<?php echo $id_utc; ?>)" style='width:45px; height:26px; border-radius:25px; float:right; margin-top:10px; margin-right:10px;'><i class='material-icons' style='margin-left:-3px; margin-top:-6px;'>settings</i></a>
						</div>
					</div>
		<?php	$loop += 1;
				}
			?>
				
		</div>              
    </div>
</div>
</main>

<!-- Modal -->
<div class="modal fade" id="criarUTC" tabindex="-1" role="dialog" aria-labelledby="titulo_criarUTC" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 24%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_criarUTC">Criar UTC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_criarUTC" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editarUTC" tabindex="-1" role="dialog" aria-labelledby="titulo_editarUTC" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 26%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_editarUTC">Editar UTC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_editarUTC" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="verDocentesTodos" tabindex="-1" role="dialog" aria-labelledby="titulo_verDocentesTodos" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 25%;" role="document">
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

<!-- Modal -->
<div class="modal fade" id="verCursosTodos" tabindex="-1" role="dialog" aria-labelledby="titulo_verCursosTodos" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 33%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_verCursosTodos">Cursos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_verCursosTodos" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="verAreasTodas" tabindex="-1" role="dialog" aria-labelledby="titulo_verAreasTodas" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 33%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_verAreasTodas">Áreas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_verAreasTodas" class="modal-body">
            </div>
        </div>
    </div>
</div>


<script language="javascript">
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_UTC");
	li_DSD.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function gerarFormCriarUTC(){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_criarUTC").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/utc_administrador/criarUTC.php");
  xhttp.send();
}

function criarUTC(){
	
	const input_nome = document.getElementById("criarUTC_nome");
	const input_sigla = document.getElementById("criarUTC_sigla");
	const select_responsavel = document.getElementById("criarUTC_responsavel");
	
	const nome_introduzido = input_nome.value;
	const sigla_introduzida = input_sigla.value;
	const responsavel_selecionado = select_responsavel.value;
	
	if(nome_introduzido.length < 5){
		alert("Introduza um nome válido! (pelo menos 5 caracteres)");
		input_nome.focus();
	}
	else if(sigla_introduzida.length < 2){
		alert("Introduza uma sigla válida! (pelo menos 2 caracteres)");
		input_sigla.focus();
	}
	else{
		
		$.ajax ({
			type: "POST",
			url: "processamento/utc_administrador/verificarNomeSiglaUsados.php", 
			data: {nome_introduzido: nome_introduzido, sigla_introduzida: sigla_introduzida},
			success: function(result) {
				result_final = result.split(",");
				
				if(result_final[0] == 1 && result_final[1] == 0){
					alert("Nome da UTC em uso! Por favor introduza outro");
					input_nome.focus();
				}
				else if(result_final[0] == 0 && result_final[1] == 1){
					alert("Sigla da UTC em uso! Por favor introduza outra");
					input_sigla.focus();
				}
				else if(result_final[0] == 1 && result_final[1] == 1){
					alert("Nome e sigla da UTC em uso! Por favor introduza outros dados");
					input_nome.focus();
				}
				else{
					
					$.ajax ({
						type: "POST",
						url: "processamento/utc_administrador/criarUTC.php", 
						data: {nome: nome_introduzido, sigla: sigla_introduzida, id_responsavel: responsavel_selecionado},
						success: function(result) {
							location.reload();
						},
						error: function(result) {
							alert("Erro ao criar UTC: " + result);
						}
					});
					
				}
				
			},
			error: function(result) {
				alert("Erro ao aceder aos dados: " + result);
			}
		});
		
	}
	
}

function gerarFormEditarUTC(id_utc){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_editarUTC").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/utc_administrador/editarUTC.php?id_utc=" + id_utc);
  xhttp.send();
}

function atualizarUTC(id_utc,nome_atual,sigla_atual,id_responsavel_atual,dsd_1_sem_atual,dsd_2_sem_atual){
	
	const input_nome = document.getElementById("editarUTC_nome");
	const input_sigla = document.getElementById("editarUTC_sigla");
	const select_responsavel = document.getElementById("editarUTC_responsavel");
	const checkbox_dsd_1_sem = document.getElementById("editarUTC_dsd_1_sem");
	const checkbox_dsd_2_sem = document.getElementById("editarUTC_dsd_2_sem");
	
	const nome_introduzido = input_nome.value;
	const sigla_introduzida = input_sigla.value;
	const id_responsavel_selecionado = select_responsavel.value;
	var dsd_1_sem = 0;
	var dsd_2_sem = 0;
	/*
	if(checkbox_dsd_1_sem.checked){
		dsd_1_sem = 1;
	}
	
	if(checkbox_dsd_2_sem.checked){
		dsd_2_sem = 1;
	}
	*/
	if(nome_introduzido == nome_atual && sigla_introduzida == sigla_atual && id_responsavel_selecionado == id_responsavel_atual/* && dsd_1_sem == dsd_1_sem_atual && dsd_2_sem == dsd_2_sem_atual*/){
		$('#editarUTC').modal('hide');
	}
	else{
		if(nome_introduzido.length < 5){
			alert("Introduza um nome válido! (pelo menos 5 caracteres)");
			input_nome.focus();
		}
		else if(sigla_introduzida.length < 2){
			alert("Introduza uma sigla válida! (pelo menos 2 caracteres)");
			input_sigla.focus();
		}
		else{
			
			$.ajax ({
				type: "POST",
				url: "processamento/utc_administrador/atualizarUTC.php", 
				data: {id_utc: id_utc, nome_introduzido: nome_introduzido, sigla_introduzida: sigla_introduzida, id_responsavel_selecionado: id_responsavel_selecionado},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao atualizar dados da UTC: " + result);
				}
			});
			
		}
	}
	
}

function eliminarUTC(id_utc){
	
	if(window.confirm("Tem a certeza que pretende eliminar esta UTC? (Irá remover tudo associado incluindo docentes, turma, cursos, áreas, unidades curriculares, junções, ...)")){
		if(window.confirm("Tem mesmo a certeza que quer eliminar esta UTC?")){
			
			$.ajax ({
				type: "POST",
				url: "processamento/utc_administrador/eliminarUTC.php", 
				data: {id_utc: id_utc},
				success: function(result) {
					location.reload();
				},
				error: function(result) {
					alert("Erro ao eliminar UTC: " + result);
				}
			});
			
		}
	}
	
}

function janelaListaDocentes(id_utc){
	$.ajax ({
		type: "POST",
		url: "processamento/utc_administrador/verNomeUTC.php", 
		data: {id_utc: id_utc},
		success: function(result) {
			
			const nome_utc = result;
			document.getElementById("titulo_verDocentesTodos").innerHTML = "Docentes: UTC " + nome_utc;
			var xhttp;    
			xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				  document.getElementById("modalBody_verDocentesTodos").innerHTML = this.responseText;
				}
			};
				
		    xhttp.open("GET", "phpUtil/utc_administrador/verDocentesTodos.php?id_utc=" + id_utc);
		    xhttp.send();
		
		},
		error: function(result) {
			alert("Erro ao verificar nome da UTC: " + result);
		}
	});	
}

function janelaListaCursos(id_utc){
	
	$.ajax ({
		type: "POST",
		url: "processamento/utc_administrador/verNomeUTC.php", 
		data: {id_utc: id_utc},
		success: function(result) {
			const nome_utc = result;
			
			document.getElementById("titulo_verCursosTodos").innerHTML = "Cursos: UTC " + nome_utc;
			var xhttp;    
			xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				  document.getElementById("modalBody_verCursosTodos").innerHTML = this.responseText;
				}
			};
			xhttp.open("GET", "phpUtil/utc_administrador/verCursosTodos.php?id_utc=" + id_utc);
			xhttp.send();
		},
		error: function(result) {
			alert("Erro ao verificar noma da UTC: " + result);
		}
	});
 
}

function janelaListaAreas(id_utc){
	
	$.ajax ({
		type: "POST",
		url: "processamento/utc_administrador/verNomeUTC.php", 
		data: {id_utc: id_utc},
		success: function(result) {
			const nome_utc = result;
			
			document.getElementById("titulo_verAreasTodas").innerHTML = "Áreas: UTC " + nome_utc;
			var xhttp;    
			xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				  document.getElementById("modalBody_verAreasTodas").innerHTML = this.responseText;
				}
			};
			xhttp.open("GET", "phpUtil/utc_administrador/verAreasTodas.php?id_utc=" + id_utc);
			xhttp.send();
		},
		error: function(result) {
			alert("Erro ao verificar noma da UTC: " + result);
		}
	});
 
}

function coordenadorUTC(){
	window.location.href = "coordUTC.php";
}

</script>

<?php gerarHome2() ?>
