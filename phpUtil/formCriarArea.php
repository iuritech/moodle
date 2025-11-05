<?php
// Formulário de criação de áreas científicas

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
?>
<div class="modal-body">
    <form id="formCriarArea" class="user" action="processamento/processarFormCriarArea.php" method="post">
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
// Buscar gestores de área
$tabela = "funcao_utilizador"; 
$query = "SELECT * FROM $tabela WHERE id_funcao = 3";
$resultado1 = mysqli_query($conn, $query);
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $id = (int) $linha1["id_utilizador"];
    
    $tabela = "utilizador";
    $query = "SELECT * FROM $tabela WHERE id_utilizador = $id";
    $resultado2 = mysqli_query($conn, $query);
    
    $linha2 = mysqli_fetch_assoc($resultado2);
    $nome_responsavel = $linha2["nome"];

?>
                    <option value="<?php echo $id ?>"><?php echo $nome_responsavel ?></option>
<?php
}
?>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormCriarArea()" class="btn btn-primary btn-lg">
        Criar
    </button>
</div>