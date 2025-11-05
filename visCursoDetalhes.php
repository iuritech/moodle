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
						
	$statement3 = mysqli_prepare($conn, "SELECT nome, imagem_perfil FROM utilizador WHERE id_utilizador = $id_coordenador;");
	$statement3->execute();
	$resultado3 = $statement3->get_result();
	$linha3 = mysqli_fetch_assoc($resultado3);
	
		$nome_coordenador = $linha3["nome"];
		$imagem_coordenador = $linha3["imagem_perfil"];
							
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
				<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">...</a> / <a href="visCurso.php">Cursos</a> / <a href=""><?php echo $sigla_tipo_curso, " ", $sigla_curso ?></a></h6>
				<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 15px;">(<?php echo $sigla_tipo_curso; ?>) <b><?php echo $nome_curso?></b></h3>
				<br>
				<text style="font-weight:500; margin-left:15px;">Coordenador:</text>
				<img src="<?php echo $imagem_coordenador ?>" style="width:35px; heigh:35px; margin-left:10px; border-radius:50%; border:1px solid #212529;">
				<?php echo $nome_coordenador; ?>
				<text style="font-weight:500; margin-left:35px;">Duração:</text>
				<i class="material-icons" style="vertical-align:middle;">calendar_today</i><b> <?php echo $anos_curso; ?> anos</b>
				<?php if(isset($_SESSION['permUTC'])){ ?>
				<a class="btn btn-primary" href="edCurso.php?id=<?php echo $id_curso; ?>" style='width:101px; border-radius:25px; position: absolute; right: 35px; top:25px;'><i class='material-icons' style='vertical-align: middle;'>settings</i>Configurar</a>
			<?php } ?>
				<div class="curso_detalhes_separador">
				</div>
				
				<div class="container_curso_ucs_turmas">
					<div class="curso_detalhes_ucs">
						<h4><i class="material-icons" style="vertical-align:middle;">class</i> Unidades Curriculares </h4>
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
										<a href="javascript:void(0);" data-toggle='modal' data-target='#visDetalhesUC' onclick="verDetalhesUC(<?php echo $id_disciplina; ?>,'<?php echo $nome_disciplina; ?>')"><text style="margin-left:45px;"><?php echo $nome_disciplina, " (", $codigo_disciplina, ") ";?></text></a><br><?php
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
										<a href="javascript:void(0);" data-toggle='modal' data-target='#visDetalhesUC' onclick="verDetalhesUC(<?php echo $id_disciplina; ?>,'<?php echo $nome_disciplina; ?>')"><text style="margin-left:45px;"><?php echo $nome_disciplina, " (", $codigo_disciplina, ") ";?></text></a><br><?php
									}
								?>
							<br><br>
						<?php	$counter_ano += 1;
						}
						?>
					</div>
					<div class="area_detalhes_docentes">
						<h4><i class="material-icons" style="vertical-align:middle;">people</i> Docentes </h4>
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
												<a href="javascript:void(0);" onclick="verDetalhesTurma(<?php echo $id_turma; ?>)"><text style="margin-left:45px;"><?php echo $nome_turma;?></text></a><br><?php
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
												<a href="javascript:void(0);"><text style="margin-left:45px;"><?php echo $nome_turma;?></text></a><br><?php
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
	var li_curso = document.getElementById("li_CURSO");
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
