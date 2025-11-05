<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_area = $_GET["id_area"];

$statement = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_area = $linha["nome"];
	
?>
<div class="modal-body">
	<text style="font-weight:500">Nome: </text><input type="text" id="edNomeArea" maxlength="5" value="<?php echo $nome_area?>" style="width:70px; margin-left:5px;"></input>
</div>
<div class="modal-footer">
    <button type="button" onclick="atualizarNome(<?php echo $id_area; ?>,'<?php echo $nome_area; ?>')" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atualizar</b>
    </button>
</div>