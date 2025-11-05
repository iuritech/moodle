<?php
// Formulário de edição de turmas

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$id = (int) filter_input(INPUT_GET, 'i');
// Obter dados do curso
$statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_turma = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();

$linha = mysqli_fetch_assoc($resultado);
$nome = $linha["nome"];
$ano = $linha["ano"];
$semestre = $linha["semestre"];
$idCurso = $linha["id_curso"];

$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $idCurso);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$numSemestres = (int) $linha["semestres"];
?>
<div class="modal-body">
    <form id="formEditarTurma" class="user" action="processamento/processarFormEditarTurma.php?i=<?php echo $id ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control form-control-user" name="nome" id="nome" value="<?php echo $nome ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="ano">Ano:</label>
                <select name="ano" id="ano">
<?php
$numAnos = ceil( $numSemestres / 2 );

for( $i = 1; $i <= $numAnos; $i++){
?>
                    <option <?php if($ano == $i){ echo "selected"; }?> value="<?php echo $i ?>"><?php echo $i ?></option>                                           
<?php
}
?>
                </select>

            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="semestre">Semestre:</label>
                <select name="semestre" id="semestre">
                    <option <?php if($semestre == 1){ echo "selected"; }?> value="1">1</option>
                    <option <?php if($semestre == 2){ echo "selected"; }?> value="2">2</option>                                           
                </select>

            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormEditarTurma()" class="btn btn-primary btn-lg">
        Editar
    </button>
</div>