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

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Horários - <b>Salas</b></a></h6>
			<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 35px;"><b>Salas</b></h3>
			
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
			
			<br>

			<div class="bloco_sala" style="margin-left:54px;">
				<h4 align="center">Bloco A</h4>
				<?php
					$statement1 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'A' ORDER BY nome_sala;");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					while($linha1 = mysqli_fetch_assoc($resultado1)){
						$id_sala = $linha1["id_sala"];
						$nome_sala = $linha1["nome_sala"];
						
						echo "<div class='sala' onclick='verHorarioSala($id_sala)'><i class='material-icons' style='vertical-align:middle; font-size:30px; margin-right:15px;'>meeting_room</i><b>", $nome_sala, "</b></div>";
					}				
				?>
			</div>
			
			<div class="bloco_sala">
				<h4 align="center">Bloco B</h4>
				<?php
					$statement2 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'B' ORDER BY nome_sala;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					while($linha2 = mysqli_fetch_assoc($resultado2)){
						$id_sala = $linha2["id_sala"];
						$nome_sala = $linha2["nome_sala"];
						
						echo "<div class='sala' onclick='verHorarioSala($id_sala)'><i class='material-icons' style='vertical-align:middle; font-size:30px; margin-right:15px;'>meeting_room</i><b>", $nome_sala, "</b></div>";
					}				
				?>
			</div>
			
			<div class="bloco_sala">
				<h4 align="center">Bloco C</h4>
				<?php
					$statement3 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'C' ORDER BY nome_sala;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					while($linha3 = mysqli_fetch_assoc($resultado3)){
						$id_sala = $linha3["id_sala"];
						$nome_sala = $linha3["nome_sala"];
						
						echo "<div class='sala' onclick='verHorarioSala($id_sala)'><i class='material-icons' style='vertical-align:middle; font-size:30px; margin-right:15px;'>meeting_room</i><b>", $nome_sala, "</b></div>";
					}				
				?>
			</div>
			
			<div class="bloco_sala">
				<h4 align="center">Bloco D</h4>
				<?php
					$statement4 = mysqli_prepare($conn, "SELECT id_sala, nome_sala FROM sala WHERE bloco_sala = 'D' ORDER BY nome_sala;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					while($linha4 = mysqli_fetch_assoc($resultado4)){
						$id_sala = $linha4["id_sala"];
						$nome_sala = $linha4["nome_sala"];
						
						echo "<div class='sala' onclick='verHorarioSala($id_sala)'><i class='material-icons' style='vertical-align:middle; font-size:30px; margin-right:15px;'>meeting_room</i><b>", $nome_sala, "</b></div>";
					}				
				?>
			</div>

		</div>    
	</div>
</div>

</main>
<script language="javascript">
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_HORARIOS");
	var li_DSD_especifico = document.getElementById("li_HORARIOS_SALAS");
	
	li_DSD.style.background = "#4a6f96";
	li_DSD_especifico.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

var sem_atual = <?php echo $semestre_atual; ?>

function alterarSemestre(semestre){
	
	if(semestre != sem_atual){
		window.location.href = "visHorariosSala.php?sem=" + semestre;
	}
	
}

function verHorarioSala(id_sala){
	window.location.href = "visHorariosSala_.php?id_sala=" + id_sala + "&sem=" + sem_atual;
}
</script>
<?php gerarHome2() ?>
