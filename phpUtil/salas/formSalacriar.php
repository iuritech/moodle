<?php
session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utilizador = $_SESSION["id"];

// Verificar se é admin
$is_admin = false;
$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_utilizador");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

if ($linha["is_admin"] == 1) {
    $is_admin = true;
}

?>
<div id="criarSala_div_principal" class="modal-body" style="height:300px;">
    <text style="font-weight:500; margin-right:20px;">Nome da Sala: </text>
    <input type="text" id="criarSala_nome" maxlength="50" style="width:200px;"></input>
    <br><br>

    <text style="font-weight:500; margin-right:37px;">Bloco: </text>
    <select id="criarSala_bloco" style="width:50px;">
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <!-- Adicione mais blocos conforme necessário -->
    </select>
    <br><br>
</div>

<div class="modal-footer">
    <button type="button" onclick="criarSala()" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Criar</b>
    </button>
</div>
