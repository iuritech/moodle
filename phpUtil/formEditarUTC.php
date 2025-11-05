<?php
// Formulário de edição de unidades técnico-científicas

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$id = (int) filter_input(INPUT_GET, 'i');

// Obter dados da UTC
$statement = mysqli_prepare($conn, "SELECT * FROM utc WHERE id_utc = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();

$linha = mysqli_fetch_assoc($resultado);
$id_utc = $linha["id_utc"];
$nome = $linha["nome_utc"];
$sigla = $linha["sigla_utc"];
$idResponsavel = (int) $linha["id_responsavel"];
?>
<div class="modal-body">
    <form id="formEditarUTC" class="user" action="processamento/processarFormEditarUTC.php?i=<?php echo $id ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control form-control-user" name="nome" id="nome" value="<?php echo $nome ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="nome">Sigla:</label>
                <input type="text" class="form-control form-control-user" name="sigla" id="sigla" value="<?php echo $sigla ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="responsavel">Responsável:</label>
					<?php echo $id, " / ", $idResponsavel?>
                <select name="responsavel" id="responsavel">
                    <option></option>
<?php
$query = "SELECT * FROM utilizador WHERE id_utc = $id AND id_utilizador != $idResponsavel ORDER BY nome;";
$resultado1 = mysqli_query($conn, $query);
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $id_utilizador = (int) $linha1["id_utilizador"];
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
    <button type="button" onclick="javascript:verificarFormEditarUTC()" class="btn btn-primary btn-lg">
        Editar
    </button>
</div>