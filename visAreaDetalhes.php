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

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
    <div class="container-fluid">
		<div class="card shadow mb-4">
			<div class="card-body">
				<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">...</a> / <a href="visArea.php">Áreas</a> / <a href=""><?php echo $nome_area ?></a></h6>
				<h3 style="margin-left:15px; margin-top:20px; margin-bottom:5px;"><b><?php echo $nome_area?></b>
					<span title="UTC" style="margin-left:50px;">
						<i class="material-icons" style="vertical-align:middle;">menu_book</i><text style="font-weight:300; font-size:17px; cursor:default;"><b> <?php echo $nome_utc; ?></b></text>
					</span>
				</h3>
				<br>
				
				<?php if($coordenador_UTC || $id_area_utilizador == $id_area){ ?>
				<a class="btn btn-primary" href="edArea.php?id=<?php echo $id_area; ?>" style='width:101px; border-radius:25px; position: absolute; right: 35px; top:25px;'><i class='material-icons' style='vertical-align: middle;'>settings</i>Configurar</a>
			<?php } ?>
			<?php
				echo "<h5 style='margin-left:15px; margin-top:-5px;'>",$nome_completo, "</h5>";
			?>
			<div class="curso_detalhes_separador">
			</div>
				
				<div class="container_area_ucs_docentes">
					<div class="area_detalhes_ucs">
						<h4 style="margin-left:10px;"><i class="material-icons" style="vertical-align:middle;">class</i> Unidades Curriculares </h4>
						<?php
							$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina WHERE id_area = $id_area;");
							$statement4->execute();
							$resultado4 = $statement4->get_result();
							$linha4 = mysqli_fetch_assoc($resultado4);
								$num_ucs_area_ = $linha4["COUNT(DISTINCT id_disciplina)"];
								
							if($num_ucs_area_ == 0){
								echo "<text style='margin-left:20px; line-height:45px;'>Não existe numa unidade curricular nesta área!</text>";
							}
							else{
								$statement5 = mysqli_prepare($conn, "SELECT DISTINCT * FROM curso WHERE id_utc = $id_utc;");
								$statement5->execute();
								$resultado5 = $statement5->get_result();
								while($linha5 = mysqli_fetch_assoc($resultado5)){
									$id_curso = $linha5["id_curso"];
									$nome_curso = $linha5["nome"];
									$id_tipo_curso = $linha5["id_tipo_curso"];
									
									$statement6 = mysqli_prepare($conn, "SELECT sigla FROM curso_tipo WHERE id_tipo_curso = $id_tipo_curso;");
									$statement6->execute();
									$resultado6 = $statement6->get_result();
									$linha6 = mysqli_fetch_assoc($resultado6);
										$sigla_tipo_curso = $linha6["sigla"];
								
									$statement7 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina WHERE id_curso = $id_curso AND id_area = $id_area;");
									$statement7->execute();
									$resultado7 = $statement7->get_result();
									$linha7 = mysqli_fetch_assoc($resultado7);
										$num_ucs_area_curso = $linha7["COUNT(DISTINCT id_disciplina)"];
								
										if($num_ucs_area_curso > 0){
											echo "<h5 style='margin-left:20px; margin-top:25px;'>", "(", $sigla_tipo_curso, ") ", $nome_curso, "</h5>";
											
											$statement8 = mysqli_prepare($conn, "SELECT DISTINCT id_disciplina FROM disciplina WHERE id_curso = $id_curso AND id_area = $id_area ORDER BY ano, semestre, nome_uc;");
											$statement8->execute();
											$resultado8 = $statement8->get_result();
											while($linha8 = mysqli_fetch_assoc($resultado8)){
												$id_uc_area_curso = $linha8["id_disciplina"];
												
												$statement9 = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = $id_uc_area_curso;");
												$statement9->execute();
												$resultado9 = $statement9->get_result();
												$linha9 = mysqli_fetch_assoc($resultado9);
													$nome_uc = $linha9["nome_uc"];
													$ano_uc = $linha9["ano"];
													$semestre_uc = $linha9["semestre"];
													
													echo "<a href='javascript:void(0);'><text style='margin-left:30px;'>", $nome_uc, " (", $ano_uc, "ºA/", $semestre_uc, "ºS)", "</text></a><br>";
											}
										}
								}
							}
						?>
					</div>
					<div class="curso_detalhes_turmas">
						<h4 style="margin-bottom:15px;"><i class="material-icons" style="vertical-align:middle;">person</i> Docentes </h4>
						<?php
						$statement10 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_utilizador) FROM utilizador WHERE id_area = $id_area ORDER BY nome;");
						$statement10->execute();
						$resultado10 = $statement10->get_result();
						$linha10 = mysqli_fetch_assoc($resultado10);
							$num_docentes_area = $linha10["COUNT(DISTINCT id_utilizador)"];
							
						if($num_docentes_area == 0){
							echo "<text style='margin-left:15px; line-height:25px;'>Não existe nenhum docente nesta área!</text>";
						}
						else{
							$statement11 = mysqli_prepare($conn, "SELECT DISTINCT * FROM utilizador WHERE id_area = $id_area ORDER BY nome;");
							$statement11->execute();
							$resultado11 = $statement11->get_result();
							while($linha11 = mysqli_fetch_assoc($resultado11)){
								$id_utilizador_temp = $linha11["id_utilizador"];
								$nome_utilizador_temp = $linha11["nome"];
								$imagem_utilizador_temp = $linha11["imagem_perfil"];
									
								if($id_utilizador_temp == $id_utilizador){
									echo "<img src='$imagem_utilizador_temp' style='width:35px; height:35px; border-radius:50%; margin-left:10px; margin-right:5px; margin-bottom:10px; border:1px solid #000000;'><a href='javascript:void(0);'><text style='margin-bottom:15px;'><b>", $nome_utilizador_temp, "</b></text></a>";
								}
								else{
									echo "<img src='$imagem_utilizador_temp' style='width:35px; height:35px; border-radius:50%; margin-left:10px; margin-right:5px; margin-bottom:10px; border:1px solid #000000;'><a href='javascript:void(0);'><text style='margin-bottom:15px;'>", $nome_utilizador_temp, "</text></a>";
								}
								echo "<br>";
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
<div class="modal fade" id="visDetalhesUC" tabindex="-1" role="dialog" aria-labelledby="titulo_visDetalhesUC" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 30%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_visDetalhesUC_Modal">Detalhes UC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBody_visDetalhesUC" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function configurarMenuTopo(){
	var li_curso = document.getElementById("li_AREA");
	li_curso.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

function verDetalhesUC(id_disciplina,nome_disciplina){
  document.getElementById("titulo_visDetalhesUC_Modal").innerHTML = "UC: " + nome_disciplina;
  var xhttp;    
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
	  document.getElementById("modalBody_visDetalhesUC").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "phpUtil/curso/verDetalhesUC.php?id_uc=" + id_disciplina);
  xhttp.send();
}
</script>

<?php gerarHome2() ?>
