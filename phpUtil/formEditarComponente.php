<?php
// Formulário de edição de componentes

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$id = (int) filter_input(INPUT_GET, 'i');

$statement = mysqli_prepare($conn, "SELECT * FROM componente INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente WHERE id_componente = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$idTipoComponente = $linha["id_tipocomponente"];
$numeroHoras = $linha["numero_horas"];
$idDisciplina = $linha["id_disciplina"];

$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = ?");
$statement->bind_param('i', $idDisciplina);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$nomeDisciplina = $linha["nome_uc"];
?>
<div class="modal-body">
    <form id="formEditarComponente" class="user" action="processamento/processarFormEditarComponente.php?i=<?php echo $id ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="disciplina">Unidade curricular:</label>
                <select name="disciplina" id="disciplina">
                        <option value="<?php echo $idDisciplina ?>"><?php echo $nomeDisciplina ?></option>                                           
                </select>

            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo">
<?php
// Buscar tipos de componentes
$statement = mysqli_prepare($conn, "SELECT * FROM tipo_componente");
$statement->execute();
$resultado = $statement->get_result();

while ($linha = mysqli_fetch_assoc($resultado)) {
    $idTipo = (int) $linha["id_tipocomponente"];
    $tipo = $linha["nome_tipocomponente"];
?>
                    <option <?php if($idTipoComponente == $idTipo){ echo "selected"; } ?> value="<?php echo $idTipo ?>"><?php echo $tipo ?></option>
<?php
}
?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="horas">Número de horas:</label>
                <select name="horas" id="horas">
                    <option <?php if($numeroHoras == 1){ echo "selected"; } ?> value="1">1</option>
                    <option <?php if($numeroHoras == 2){ echo "selected"; } ?> value="2">2</option>
                    <option <?php if($numeroHoras == 3){ echo "selected"; } ?> value="3">3</option>
                    <option <?php if($numeroHoras == 4){ echo "selected"; } ?> value="4">4</option>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormEditar()" class="btn btn-primary btn-lg">
        Editar
    </button>
</div>
