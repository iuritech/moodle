<?php
// Página de gestão de utilizadores

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
if(!isset($_SESSION['permAdmin'])){
    header("Location: index.php");
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
		<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Utilizadores</a></h6>
		<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 15px;"><b>Utilizadores</b>
		<a class="btn btn-primary" data-toggle="modal" data-target="#criarUtilizador" onclick="janelaCriarUtilizador()" style="border-radius:25px; margin-left:20px;"><i class="material-icons" style="line-height:50%; margin-right:5px; vertical-align: middle;">person_add</i><b>Utilizador</b></a>
		</h3>
		
		<div class="table-responsive" style="width:97%; margin-left:15px;">
			<br><table class="table table-striped" id="tabelaUtilizadores" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Nome</th>
						<th width='100px'>UTC</th>
                        <th>Área</th>
                        <th>Login</th>
                        <th width='280px'>Permissões / Categoria</th>
                     
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nome</th>
						<th>UTC</th>
                        <th>Área</th>
                        <th>Login</th>
                        <th>Permissões / Categoria</th>
                        
					</tr>
				</tfoot>
                <tbody>
<?php
$statement = mysqli_prepare($conn, "SELECT * FROM utilizador ORDER BY nome;");
$statement->execute();
$resultado = $statement->get_result();
while($linha = mysqli_fetch_assoc($resultado)){
	$id_docente = $linha["id_utilizador"];
	$nome_docente = $linha["nome"];
	$imagem_docente = $linha["imagem_perfil"];
	$login_docente = $linha["login"];
	$password_docente = $linha["password"];
	$id_utc_docente = $linha["id_utc"];
	$id_area_docente = $linha["id_area"];
	$id_funcao_docente = $linha["id_funcao"];
	$is_admin = $linha["is_admin"];
	
	$statement1 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_docente;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_utc_docente = $linha1["nome_utc"];
		
	$statement2 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area_docente;");
	$statement2->execute();
	$resultado2 = $statement2->get_result();
	$linha2 = mysqli_fetch_assoc($resultado2);
		$nome_area_docente = $linha2["nome"];
		
	$statement3 = mysqli_prepare($conn, "SELECT nome FROM funcao WHERE id_funcao = $id_funcao_docente;");
	$statement3->execute();
	$resultado3 = $statement3->get_result();
	$linha3 = mysqli_fetch_assoc($resultado3);
		$nome_funcao_docente = $linha3["nome"];
	
?>
					<tr>
						<td><img src="<?php echo $imagem_docente; ?>" style="width:40px; heigh:40px; border-radius:50%; border:1px solid #212529;"><text><?php echo "  <b><u>", $nome_docente, "</u></b>" ?></text><?php if($is_admin == 1) { echo "<text style='margin-left:10px;'><i>(Admin)</i></text>"; } ?></td>
                        <td><?php echo $nome_utc_docente; ?></td>
						<td><?php echo $nome_area_docente; ?></td>
                        <td><?php echo "<i>", $login_docente; "</i>" ?></td>
                        <td><?php echo $nome_funcao_docente ?>
							<a class='btn btn-primary' href="javascript:void(0)" data-toggle="modal" data-target="#editarUtilizador" onclick="janelaEditarDocente(<?php echo $id_docente; ?>)" title='Editar utilizador' style='width:45px; height:25px; border-radius:25px; float:right;'><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i></a>
						</td>
                        
					</tr>
<?php
}
?>
				</tbody>
			</table>
            <script>
				// dataTables jQuery plugin
                $(document).ready(function () {
                            $('#tabelaUtilizadores').DataTable({
                                responsive: true,
                                pageLength: 25,

								
                                order: [0, 'asc'],
								"language": {
									"lengthMenu": "Ver _MENU_ utilizadores por página",
									"zeroRecords": "Nenhum registo encontrado",
									"info": "Página _PAGE_ de _PAGES_",
									"infoEmpty": "Nenhum registo encontrado",
									"infoFiltered": "(pesquisa de _MAX_ registos totais)",
									"search":         "Procurar:",
									"paginate": {
										"first":      "Primeiro",
										"last":       "Último",
										"next":       "Próximo",
										"previous":   "Anterior"
									},
								}		
                            });
                        });
                    </script>    
                </div>
            </div>
        </div>
            
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="criarUtilizador" tabindex="-1" role="dialog" aria-labelledby="titulo_criarUtilizador" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 27%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_criarUtilizador">Criar utilizador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_criarUtilizador" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editarUtilizador" tabindex="-1" role="dialog" aria-labelledby="titulo_editarUtilizador" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 27%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_editarUtilizador">Editar utilizador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_editarUtilizador" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function configurarMenuTopo(){
	var li_util = document.getElementById("li_UTIL");
	li_util.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function janelaCriarUtilizador(){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_criarUtilizador").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/administrador/criarDocente.php");
  xhttp.send();	
}

function atualizarAreas(){
	
	const select_utc = document.getElementById("criarDocente_utc");
	const select_area = document.getElementById("criarDocente_area");
	
	const id_utc_selecionada = select_utc.value;
	
	$.ajax ({
		type: "POST",
		url: "processamento/administrador/verAreasUTC.php", 
		data: {id_utc: id_utc_selecionada},
		success: function(result) {
			const areas = result.split(",");
			
			removeOptions(select_area);
			
			if(areas.length > 1){
				for(i = 0; i < areas.length; i = i + 2){
					var opcao = document.createElement("option");
					opcao.value = areas[i];
					opcao.innerHTML = areas[i + 1];
					
					select_area.appendChild(opcao);
				}
			}
			
		},
		error: function(result) {
			alert("Erro ao ver áreas da UTC: " + result);
		}
	});
	
}

function criarDocente(){
	
	const input_login = document.getElementById("criarDocente_login");
	const input_password = document.getElementById("criarDocente_password");
	const input_nome = document.getElementById("criarDocente_nome");
	const select_utc = document.getElementById("criarDocente_utc");
	const select_area = document.getElementById("criarDocente_area");
	const select_funcao = document.getElementById("criarDocente_funcao");
	const checkbox_admin = document.getElementById("criarDocente_admin");
	const checkbox_horarios = document.getElementById("criarDocente_horarios");
	const checkbox_imagem = document.getElementById("criarDocente_imagem");
	
	const login = input_login.value;
	const password = input_password.value;
	const nome = input_nome.value;
	const id_utc = select_utc.value;
	const id_area = select_area.value;
	const id_funcao = select_funcao.value;
	var is_admin = 0;
	if(checkbox_admin.checked){
		is_admin = 1;
	}
	
	var perm_horarios = 0;
	if(checkbox_horarios.checked){
		perm_horarios = 1;
	}
	
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
	else if(!checkbox_imagem.checked){
		alert("Selecione uma imagem de perfil!");
		checkbox_imagem.focus();
	}
	else{
		
		$.ajax ({
			type: "POST",
			url: "processamento/administrador/criarDocente.php", 
			data: {login: login, password: password, nome: nome, id_utc: id_utc, id_area: id_area, id_funcao: id_funcao, is_admin: is_admin, perm_horarios: perm_horarios},
			success: function(result) {
				location.reload();
			},
			error: function(result) {
				alert("Erro ao criar docente: " + result);
			}
		});
		
	}
	
}

function janelaEditarDocente(id_docente){
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_editarUtilizador").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/administrador/editarDocente.php?id_docente=" + id_docente);
  xhttp.send();	
}

function atualizarDocente(id_docente, login_atual,password_atual,nome_atual,id_utc_atual,id_area_atual,id_funcao_atual,is_admin_atual,horarios_atual){
	
	const input_login = document.getElementById("editarDocente_login");
	const input_password = document.getElementById("editarDocente_password");
	const input_nome = document.getElementById("editarDocente_nome");
	const select_utc = document.getElementById("editarDocente_utc");
	const select_area = document.getElementById("editarDocente_area");
	const select_funcao = document.getElementById("editarDocente_funcao");
	const checkbox_admin = document.getElementById("editarDocente_admin");
	const checkbox_horarios = document.getElementById("editarDocente_horarios");
	
	const login = input_login.value;
	const password = input_password.value;
	const nome = input_nome.value;
	const id_utc = select_utc.value;
	const id_area = select_area.value;
	const id_funcao = select_funcao.value;
	var is_admin = 0;
	if(checkbox_admin.checked){
		is_admin = 1;
	}
	
	var horarios = 0;
	if(checkbox_horarios.checked){
		horarios = 1;
	}
	
	if(login == login_atual && password == password_atual && nome == nome_atual && id_utc == id_utc_atual && id_area == id_area_atual && id_funcao == id_funcao_atual && is_admin == is_admin_atual && horarios == horarios_atual){
		$('#editarUtilizador').modal('hide');
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
				url: "processamento/administrador/atualizarDocente.php", 
				data: {id_docente: id_docente, login: login, password: password, nome: nome, id_utc: id_utc, id_area: id_area, id_funcao: id_funcao, is_admin: is_admin, perm_horarios: horarios},
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
	const select_area = document.getElementById("editarDocente_area");
	
	const id_utc_selecionada = select_utc.value;
	
	$.ajax ({
		type: "POST",
		url: "processamento/administrador/verAreasUTC.php", 
		data: {id_utc: id_utc_selecionada},
		success: function(result) {
			const areas = result.split(",");
			
			removeOptions(select_area);
			
			if(areas.length > 1){
				for(i = 0; i < areas.length; i = i + 2){
					var opcao = document.createElement("option");
					opcao.value = areas[i];
					opcao.innerHTML = areas[i + 1];
					
					select_area.appendChild(opcao);
				}
			}
			
		},
		error: function(result) {
			alert("Erro ao ver áreas da UTC: " + result);
		}
	});
	
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
						url: "processamento/administrador/removerDocente.php", 
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

function temNumero(string) {
	return /\d/.test(string);
}

function removeOptions(selectElement) {
	var i, L = selectElement.options.length - 1;
	for(i = L; i >= 0; i--) {
		selectElement.remove(i);
	}
}
</script>
<?php gerarHome2() ?>
