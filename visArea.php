<?php
// Página de visualização de áreas científicas

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

// Obter utc que o utilizador gere
$id_utilizador = $_SESSION['id'];

$permAdmin = false;
$coordenador_UTC = false;

$statement = mysqli_prepare($conn, "SELECT id_utc, id_area, is_admin FROM utilizador WHERE id_utilizador = $id_utilizador");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc = $linha["id_utc"];
	$permAdmin = $linha["is_admin"];
	$id_area_utilizador = $linha["id_area"];

$statement1 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$id_responsavel = $linha1["id_responsavel"];
	if($id_responsavel == $id_utilizador){
		$coordenador_UTC = true;
	}

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
    <div class="container-fluid">
		<div class="card shadow mb-4">
			<div class="card-body">
				<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Áreas</a></h6>
				<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Áreas</b>
				<?php if($coordenador_UTC){?>
				<a class="btn btn-primary" href="javascript:void(0);" data-toggle='modal' data-target='#criarArea' onclick="janelaCriarArea()" style='width:135px; border-radius:25px; margin-left:15px;'><i class='material-icons' style='vertical-align: middle;'>add_circle</i> Criar Área</a>
				<?php } ?>
				</h3>
                <?php
				$loop = 4;
				if(!$permAdmin){
					$statement2 = mysqli_prepare($conn, "SELECT * FROM area WHERE id_utc = $id_utc ORDER BY nome;");
				}
				else{
					$statement2 = mysqli_prepare($conn, "SELECT * FROM area ORDER BY nome;");
				}
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				while($linha2 = mysqli_fetch_assoc($resultado2)){
					$id_area = $linha2["id_area"];
					$nome = $linha2["nome"]; 
					$nome_completo = $linha2["nome_completo"];
					$imagem = $linha2["imagem"];
					$id_utc_area = $linha2["id_utc"];
					
					$nome_completo_array = explode(" ",$nome_completo);
					
					if(sizeof($nome_completo_array) > 4){
						$nome_completo_array = explode(" ",$nome_completo);
						$nome_completo_temp = "";
						
						$count = 0;
						while($count < 4){
							$nome_completo_temp = $nome_completo_temp . $nome_completo_array[$count];
							
							if($count < 4){
								$nome_completo_temp = $nome_completo_temp . " ";
							}
							
							$count += 1;
						}
						
						$nome_completo_temp = $nome_completo_temp . " (...)";
						$nome_completo = $nome_completo_temp;
					}
					/*
					if(strlen($nome_completo) > 33){
						$nome_completo = substr($nome_completo,0,33 - strlen($nome_completo)) . " (...)";
					}
					*/
					$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina WHERE id_area = $id_area;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$num_ucs = $linha3["COUNT(DISTINCT id_disciplina)"];
						
					$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_utilizador) FROM utilizador WHERE id_area = $id_area;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					$linha4 = mysqli_fetch_assoc($resultado4);
						$num_docentes = $linha4["COUNT(DISTINCT id_utilizador)"];
					
					$statement23 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_area;");
					$statement23->execute();
					$resultado23 = $statement23->get_result();
					$linha23 = mysqli_fetch_assoc($resultado23);
						$nome_utc_area = $linha23["nome_utc"];
					
						if($loop == 4){?>
						<div class="area_cartao" onclick="verDetalhesArea(<?php echo $id_area; ?>)" style="margin-left:80px"><?php	
							$loop = 2;
						} 
						else{ ?>
							<div class="area_cartao" onclick="verDetalhesArea(<?php echo $id_area; ?>)"><?php
							$loop += 1;
						}
					?>
						<div class="area_cartao_imagem">
						<?php
							echo "<img src='$imagem' style='width:151px; height:166px'>";
						?>
						</div>
						<div class="area_cartao_detalhes">
						<?php
							if($permAdmin){
								echo "<text style='font-size: 20px; font-weight: 500; margin-top:10px'>", $nome, "</text><text style='font-size:14px; float:right; margin-right:10px;'><i>", $nome_utc_area, "</i></text>";
							}
							else{
								echo "<text style='font-size: 20px; font-weight: 500; margin-top:10px'>", $nome, "</text>";
							}
							if($coordenador_UTC || ($id_area_utilizador == $id_area)){
						?>
						<a class='btn btn-primary' href="edArea.php?id=<?php echo $id_area; ?>" title='Editar Área' style='width:45px; height:25px; border-radius:25px; float:right; margin-top:0px; margin-right:5px;'><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i></a>
						<?php } ?>
						<br>
						<?php
							echo "<text style='font-size:16px;'><i>", $nome_completo, "</i></text>";
						?>
						</div>
						<div class="area_cartao_detalhes2">
						<?php echo "<i class='material-icons' style='vertical-align:middle;'>class</i><text style='font-size: 16px; font-weight: 405;'>", "UC's: <b>", $num_ucs, "</b>"; ?>
						<br>
						<?php echo "<i class='material-icons' style='vertical-align:middle;'>person</i><text style='font-size: 16px; font-weight: 405;'>", "Docentes: <b>", $num_docentes, "</b>"; ?>
						</div>
					</div>
			<?php	}
				?>
			</div>
		</div>            
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="criarArea" tabindex="-1" role="dialog" aria-labelledby="titulo_criarArea" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 30%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_criarArea">Criar Área</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_criarArea" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function configurarMenuTopo(){
	var li_area = document.getElementById("li_AREA");
	li_area.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function verDetalhesArea(id_area){
	window.location.href = "visAreaDetalhes.php?id=" + id_area;
}

function janelaCriarArea(){
	document.getElementById("titulo_criarArea").innerHTML = "Criar Área";
	var xhttp;    
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  document.getElementById("modalBody_criarArea").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "phpUtil/area/criarArea.php");
	xhttp.send();
}

function criarArea(){

	const input_nome = document.getElementById("criarArea_nome");
	const input_nome_completo = document.getElementById("criarArea_nome_completo");
	const checkbox_imagem = document.getElementById("criarArea_imagem");
	
	const nome_introduzido = input_nome.value;
	const nome_completo = input_nome_completo.value;
	const id_utc = <?php echo $id_utc; ?>;
	const imagem = "http://localhost/apoio_utc/images/area/default.png";
	
	if(nome_introduzido == ""){
		alert("Introduza um nome!");
		input_nome.focus();
	}
	else if(nome_introduzido.length < 2){
		alert("Introduza um nome válido! (pelo menos 2 caracteres)");
		input_nome.focus();
	}
	else if(nome_completo.length < 10){
		alert("Introduza um nome completo válido! (pelo menos 10 caracteres)");
		input_nome_completo.focus();
	}
	else if(!checkbox_imagem.checked){
		alert("Selecione uma imagem!");
		checkbox_imagem.focus();
	}
	else{
		$.ajax ({
			type: "POST",
			url: "processamento/area/criarArea.php", 
			data: {nome_introduzido: nome_introduzido, nome_completo: nome_completo, id_utc: id_utc, imagem: imagem},
			success: function(result) {
				window.location.href = "edArea.php?id=" + result;
			},
			error: function(result) {
				alert("Erro ao adicionar área: " + result);
			}
		});
	}
	
}
</script>

<?php gerarHome2() ?>
