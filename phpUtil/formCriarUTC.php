<?php
// Formulário de criação de unidades técnico-científicas

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');
?>
<div class="modal-body">
    <form id="formCriarUTC" class="user" action="processamento/processarFormCriarUTC.php" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control form-control-user" name="nome" id="nome">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="nome">Sigla:</label>
                <input type="text" class="form-control form-control-user" name="sigla" id="sigla">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="responsavel">Responsável:</label>
                <select name="responsavel" id="responsavel">
                    <option></option>
<?php
// Buscar gestores de UTC
$query = "SELECT * FROM utilizador ORDER BY nome;";
$resultado1 = mysqli_query($conn, $query);
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $id_utilizador = $linha1["id_utilizador"];
    $nome_utilizador = $linha1["nome"];
?>
                    <option value="<?php echo $id_utilizador ?>"><?php echo $nome_utilizador ?></option>
<?php
}
?>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormCriarUTC()" class="btn btn-light btn-lg">
        Criar
    </button>
</div>