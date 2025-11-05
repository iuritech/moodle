<?php
// Página de visualização de distribuição de serviço ordenada por docente (DSD)

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$id_utilizador_atual = $_SESSION["id"];
$semestre_atual = $_GET["sem"];

$statement = mysqli_prepare($conn, "SELECT id_utc, is_admin FROM utilizador WHERE id_utilizador = $id_utilizador_atual;");
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
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Horários - <b>Docentes</b></a></h6>
			<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 35px;"><b>Docentes</b></h3>
			
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
			
			$loop = 0;
			
			if($is_admin == 0){
				$statement1 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utc = $id_utc ORDER BY nome;");
			}
			else{
				$statement1 = mysqli_prepare($conn, "SELECT * FROM utilizador ORDER BY nome;");
			}
				$statement1->execute();
				$resultado1 = $statement1->get_result();
				while($linha1 = mysqli_fetch_assoc($resultado1)){
					$id_utilizador = $linha1["id_utilizador"];
					$nome_docente = $linha1["nome"];
					$imagem_perfil_utilizador = $linha1["imagem_perfil"];

					$nome_docente_temp = explode(" ",$nome_docente);
					
					if((strlen($nome_docente) > 14) || (sizeof($nome_docente_temp) > 2)){							
						$nome_temp = mb_substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
						$nome_docente = $nome_temp;
					}

					if(($loop == 0) || ($loop % 6 == 0)){ ?>
						<div class="horario_docente" onclick="verHorarioDocente(<?php echo $id_utilizador; ?>)" style="margin-left:90px;">
	<?php				}
					else{ ?>
						<div class="horario_docente" onclick="verHorarioDocente(<?php echo $id_utilizador; ?>)">
	<?php				} ?>
						<div class="horario_docente_imagem">
							<img src="<?php echo $imagem_perfil_utilizador; ?>" style="width:40px; height:40px; border-radius:50%; margin-left:5px; margin-top:5px; border:2px solid #000000;">
						</div>
						<div class="horario_docente_detalhes">
							
								<?php if(strlen($nome_docente) > 14){ ?>
							
								<h6 style="margin-top:5px;"><?php echo $nome_docente; ?></h6>
								
								<?php } else { ?>
								
								<h6 style="margin-top:15px;"><?php echo $nome_docente; ?></h6>
								
								<?php } ?>
						
						</div>
					</div>
				
				<?php
				
				$loop += 1;
				}				
				?>

		</div>    
	</div>
</div>

</main>
<script language="javascript">
function configurarMenuTopo(){
	var li_DSD = document.getElementById("li_HORARIOS");
	var li_DSD_especifico = document.getElementById("li_HORARIOS_DOCENTES");
	
	li_DSD.style.background = "#4a6f96";
	li_DSD_especifico.style.background = "#4a6f96";
}
window.onload = configurarMenuTopo();

var sem_atual = <?php echo $semestre_atual; ?>

function alterarSemestre(semestre){
	
	if(semestre != sem_atual){
		window.location.href = "visHorariosDocente.php?sem=" + semestre;
	}
	
}

function verHorarioDocente(id_docente){
	window.location.href = "visHorariosDocente_.php?id_docente=" + id_docente + "&sem=" + sem_atual;
}
</script>
<?php gerarHome2() ?>
