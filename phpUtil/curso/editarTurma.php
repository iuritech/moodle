<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_turma = $_GET["id_turma"];

$statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_turma = $id_turma;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_turma = $linha["nome"];
	$ano = $linha["ano"];
	$semestre = $linha["semestre"];
	$id_curso = $linha["id_curso"];
	
	$statement1 = mysqli_prepare($conn, "SELECT semestres FROM curso WHERE id_curso = $id_curso;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$semestres_curso = $linha1["semestres"];
		$anos_curso = $semestres_curso / 2;
?>
<div id="editarTurma_div_principal" class="modal-body" style="height:100px;">
	<div id="editarTurma_dados_principais">
		<text style="font-weight:500; margin-right:50px;">Nome: </text><input type="text" id="editarTurma_nome" value="<?php echo $nome_turma; ?>" maxlength=5; style="width:80px;"></input>
		<br>
		<br>
	</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="atualizarTurma(<?php echo $id_turma; ?>,'<?php echo $nome_turma; ?>')" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atualizar</b>
    </button>
</div>