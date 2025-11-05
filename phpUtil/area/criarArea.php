<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utilizador = $_SESSION["id"];

$is_admin = false;

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_utilizador");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc_utilizador = $linha["id_utc"];
	
	if($linha["is_admin"] == 1){
		$is_admin = true;
	}
	
	$statement1 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_utilizador;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_utc_utilizador = $linha1["nome_utc"];

?>
<div id="criarArea" class="modal-body" style="height:290px;">
	<text style="font-weight:500; margin-right:81px;">Nome: </text><input type="text" id="criarArea_nome" maxlength=5; style="width:100px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:5px;">Nome Completo: </text><input type="text" id="criarArea_nome_completo" maxlength=200; style="width:200px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:61px;">Imagem: </text><br><br>
	<input type="checkbox" id="criarArea_imagem" checked="true" style="margin-right:5px;"><img src="http://localhost/apoio_utc/images/area/default.png" style="height:100px; border-radius:20px;">
</div>
<div class="modal-footer">
    <button type="button" onclick="criarArea()" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Criar</b>
    </button>
</div>