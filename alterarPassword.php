<?php
// Página de alteração de password

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');

?>
<?php gerarHome1() ?>
<script src="js/alterarPassword.js"></script>
<main>
    <div class="container-fluid">
        <h1 class="mt-4">Alteração de password</h1>
        
        <form id="formRedefinicaoPassword" class="user" action="processamento/processarFormRedefinicaoPasswordSessao.php" method="post">
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="passwordatual">Password atual:</label>
                <input type="password" class="form-control form-control-user" name="passwordatual" id="passwordatual">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="novapassword">Nova password:</label>
                <input type="password" class="form-control form-control-user" name="novapassword" id="novapassword">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="novapassword2">Confirme a nova password:</label>
                <input type="password" class="form-control form-control-user" name="novapassword2" id="novapassword2">
            </div>
        </div>
    </form>
        
    <button type="button" onclick="javascript:verificarFormRedefinirPassword()" class="btn btn-primary btn-lg">
        Alterar password
    </button>
            
    </div>
</main>

<?php gerarHome2() ?>
