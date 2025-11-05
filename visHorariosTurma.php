<?php
// Página de visualização de distribuição de serviço ordenada por docente (DSD)

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$semestre_atual = $_GET["sem"];

$id_utilizador_atual = $_SESSION["id"];

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_utilizador_atual;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc = $linha["id_utc"];
	$is_admin = $linha["is_admin"];

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Horários - <b>Turmas</b></a></h6>
			<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 35px;"><b>Turmas</b></h3>
			
			<br>
			
			<div class="horarios_sala_sem" style="position:absolute; left:110px; top:65px;">
				<?php if($semestre_atual == 1){ ?>
				<div class="horarios_sem_1_atual" id="botao_1_sem" onclick="alterarSemestre(1)">
					<text style="line-height:45px; font-size:17px; font-weight:500; margin-left:8px;">1º Semestre</text>
				</div>
				<?php }else{ ?>
				<div class="horarios_sem_1" id="botao_1_sem" onclick="alterarSemestre(1)">
					<text style="line-height:45px; font-size:17px; font-weight:500; margin-left:8px;">1º Semestre</text>
				</div>
				<?php } ?>
				<?php if($semestre_atual == 2){ ?>
				<div class="horarios_sem_2_atual" id="botao_2_sem" onclick="alterarSemestre(2)">
					<text style="line-height:45px; font-size:17px; font-weight:500; margin-left:8px;">2º Semestre</text>
				</div>
				<?php }else{ ?>
				<div class="horarios_sem_2" id="botao_2_sem" onclick="alterarSemestre(2)">
					<text style="line-height:45px; font-size:17px; font-weight:500; margin-left:8px;">2º Semestre</text>
				</div>
				<?php } ?>
			</div>
			
			<?php
			if($is_admin == 0){
			
				$statement2 = mysqli_prepare($conn, "SELECT id_curso, nome, semestres FROM curso WHERE id_utc = $id_utc;");
				
			}
			else{
				
				$statement2 = mysqli_prepare($conn, "SELECT id_curso, nome, semestres FROM curso ORDER BY id_utc;");
				
			}
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				while($linha2 = mysqli_fetch_assoc($resultado2)){
					$id_curso = $linha2["id_curso"];
					$nome_curso = $linha2["nome"];
					$semestres_curso = $linha2["semestres"];
					
					$anos_curso = $semestres_curso / 2;
			?>
			<div class="horarios_turmas_cursos" style="margin-left:25px;">
			<?php echo "<i class='material-icons' style='vertical-align:middle;'>school</i><text style='font-size:20px; font-weight:500; margin-left:5px;'>", $nome_curso, "</text><br>"; 
			
			$loop_ano = 1;
			while($loop_ano <= $anos_curso){
				
				echo "<div class='horarios_turmas_cursos_ano'><text style='font-weight:550;'><i>", $loop_ano, "º Ano: </i></text><br>";
				
				$statement3 = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_curso = $id_curso AND ano = $loop_ano AND semestre = $semestre_atual;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				while($linha3 = mysqli_fetch_assoc($resultado3)){
					$id_turma = $linha3["id_turma"];
					$nome_turma = $linha3["nome"];
				
					echo "<div class='turma' onclick=verHorarioTurma($id_turma)><i class='material-icons' style='vertical-align:middle; margin-left:5px;'>people</i><text style='margin-left:5px;'>", $nome_turma, "</text></div>";
				}
				
				$loop_ano += 1;
				
				echo "</div>";
			}
			
			?>
			</div>
		<?php	}
			?>

		</div>    
	</div>
</div>

</main>
<script language="javascript">
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_HORARIOS");
	var li_DSD_especifico = document.getElementById("li_HORARIOS_TURMAS");
	
	li_DSD.style.background = "#4a6f96";
	li_DSD_especifico.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

const sem_atual = <?php echo $semestre_atual; ?>

function alterarSemestre(semestre){
	
	if(semestre != sem_atual){
		window.location.href = "visHorariosTurma.php?sem=" + semestre;
	}
	
}

function verHorarioTurma(id_turma){
	window.location.href = "visHorariosTurma_.php?id_turma=" + id_turma + "&sem=" + sem_atual;
}
</script>
<?php gerarHome2() ?>
