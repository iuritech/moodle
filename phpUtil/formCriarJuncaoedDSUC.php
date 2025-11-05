<?php
// Formulário de criação de utilizadores

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');

$id_area_utilizador = (int) $_SESSION["area_utilizador"];
$id_utc_utilizador = (int) $_SESSION["utc_utilizador"];

$id_turma = $_GET["id_turma"];
$id_componente = $_GET["id_comp"];
$id_componente_final = $id_componente;

$statement0 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
$statement0->execute();
$resultado0 = $statement0->get_result();
$linha0 = mysqli_fetch_assoc($resultado0);
	$nome_turma = $linha0["nome"];
	
	$statement1 = mysqli_prepare($conn, "SELECT d.abreviacao_uc, c.id_tipocomponente FROM disciplina d INNER JOIN componente c 
										ON d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_uc = $linha1["abreviacao_uc"];
		$id_tipocomponente = $linha1["id_tipocomponente"];
		
	$statement2 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente 
										WHERE id_tipocomponente = $id_tipocomponente;");
	$statement2->execute();
	$resultado2 = $statement2->get_result();
	$linha2 = mysqli_fetch_assoc($resultado2);
		$sigla_tipocomponente = $linha2["sigla_tipocomponente"];	

?>
<div class="modal-body"> 
	<h6 style="margin-left:-10px;">Turma escolhida</h6><text style="margin-left:-10px;">
	<?php 
		echo "<b>", $nome_turma, " </b>(", $nome_uc, " - ", $sigla_tipocomponente, ")";
	?>
	</text>
	<br>
	<div id="criar_juncao_turmas" style="margin-top:20px;">
		<div id='criar_juncao_utc' style="width:100%; margin-left:-10px; margin-top:0px;">
			<h6>Adicionar Turmas</h6>
			UTC
			<select id="utc_caso_2" onchange="mostrarCursosUTC2(<?php echo $id_turma ?>,<?php echo $id_componente_final ?>)" style="width:100px; margin-left:42px;"><?php
			echo "<option value='0'></option>";
			$statement50 = mysqli_prepare($conn, "SELECT id_utc, nome_utc FROM utc ORDER BY nome_utc;");
			$statement50->execute();
			$resultado50 = $statement50->get_result();
			while($linha50 = mysqli_fetch_assoc($resultado50)){
				$id_utc = $linha50["id_utc"];
				$nome_utc = $linha50["nome_utc"];
				
				echo "<option value='$id_utc'>$nome_utc</option>";
			}
				?>
			</select>
		</div>
		<div id='criar_juncao_curso' style="width:100%; margin-left:-10px; margin-top:15px">
			Curso
			<select id="curso_caso_2" onchange="mostrarDisciplinasCurso2(<?php echo $id_turma ?>,<?php echo $id_componente_final ?>)" style="width:200px; margin-left:30px;">
			</select>
		</div>
		<div id='criar_juncao_uc' style="width:100%; margin-left:-10px; margin-top:15px;">
			Disciplina
			<select id="disciplina_caso_2" onchange="mostrarComponentesUC2(<?php echo $id_turma ?>,<?php echo $id_componente_final ?>)" style="width:200px; margin-left:3px;">
			</select>
		</div>
		<div id='criar_juncao_componente' style="width:100%; margin-left:-10px; margin-top:15px;">
			Comp.
			<select id="componente_caso_2" onchange="mostrarTurmas2(<?php echo $id_turma ?>,<?php echo $id_componente_final ?>)" style="width:50px; margin-left:25px;">
			</select>
		</div>
		<div id='turmas_caso_2' style="width:100%; margin-left:-10px; margin-top:15px; line-height:12px;">
			Turmas
			<p>
			<!--		<button type="button" style="border-radius:25px;" id="adicionarTurmaTemp" onclick="verificarErro1()" class="btn btn-primary">
								<b>Adicionar</b>
							</button>-->
		</div>
	</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="verificarErro1Novo(<?php echo $id_turma ?>,<?php echo $id_componente ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
		<i class="material-icons" style="vertical-align:middle;">upload</i>Criar Junção
    </button>
</div>