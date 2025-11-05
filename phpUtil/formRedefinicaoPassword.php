<?php
// Formulário de redefinição de passwords

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$idUtilizador = (int) filter_input(INPUT_GET, 'i');
// Obter dados atuais do utilizador
$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
$statement->bind_param('i', $idUtilizador);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$hash = $linha["password"];


?>
<div class="modal-body">
    <form id="formRedefinicaoPassword" class="user" action="processamento/processarFormRedefinicaoPassword.php?i=<?php echo $idUtilizador ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="novapassword">Nova password:</label>
                <input type="password" class="form-control form-control-user" name="novapassword" id="novapassword">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="novapassword2">Confirme a nova password:</label>
                <input type="password" class="form-control form-control-user" name="novapassword2" id="novapassword2">
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormRedefinirPassword()" class="btn btn-primary btn-lg">
        Redefinir password
    </button>
</div>