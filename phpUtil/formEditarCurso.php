<?php
// Formulário de edição de cursos

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$id = (int) filter_input(INPUT_GET, 'i');
// Obter dados do curso
$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();

$linha = mysqli_fetch_assoc($resultado);
$nome = $linha["nome"];
$sigla = $linha["sigla"];
$semestres = $linha["semestres"];
?>
<div class="modal-body">
    <form id="formEditarCurso" class="user" action="processamento/processarFormEditarCurso.php?i=<?php echo $id ?>" method="post">
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
            <div class="col-sm-3">
                <label for="nome">Duração (Semestres):</label>
                <input type="text" class="form-control form-control-user" name="semestres" id="semestres" value="<?php echo $semestres ?>">
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormEditarCurso()" class="btn btn-primary btn-lg">
        Editar
    </button>
</div>