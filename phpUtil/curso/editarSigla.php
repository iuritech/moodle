<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_curso = $_GET["id_curso"];

$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = $id_curso;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_curso = $linha["nome"];
	$sigla_curso = $linha["sigla"];
	$sigla_completa_curso = $linha["sigla_completa"];
	
	$sigla_completa_curso_temp = explode(".",$sigla_completa_curso);
	
	$sigla_tipo_curso = $sigla_completa_curso_temp[0];
	$sigla_atual = $sigla_completa_curso_temp[1];
?>
<div class="modal-body">
	<text style="font-weight:500">Sigla: </text><?php echo $sigla_tipo_curso, "."; ?><input type="text" id="edSiglaIntroduzida" maxlength="5" value="<?php echo $sigla_atual?>" style="width:70px; margin-left:5px;"></input>
</div>
<div class="modal-footer">
    <button type="button" onclick="atualizarSigla('<?php echo $sigla_atual ?>')" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atualizar</b>
    </button>
</div>