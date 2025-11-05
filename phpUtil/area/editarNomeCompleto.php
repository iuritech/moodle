<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_area = $_GET["id_area"];

$statement = mysqli_prepare($conn, "SELECT nome_completo FROM area WHERE id_area = $id_area;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_completo = $linha["nome_completo"];
	
?>
<div class="modal-body">
	<text style="font-weight:500">Nome Completo: </text><input type="text" id="edNomeCompletoArea" maxlength="255" value="<?php echo $nome_completo; ?>" style="width:240px; margin-top:5px;"></input>
</div>
<div class="modal-footer">
    <button type="button" onclick="atualizarNomeCompleto(<?php echo $id_area; ?>,'<?php echo $nome_completo; ?>')" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atualizar</b>
    </button>
</div>