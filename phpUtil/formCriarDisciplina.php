<?php
// Formulário de criação de disciplinas

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

// Id curso
$id = (int) filter_input(INPUT_GET, 'i');

$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$numSemestres = (int) $linha["semestres"];
?>
<div class="modal-body">
    <form id="formCriarDisciplina" class="user" action="processamento/processarFormCriarDisciplina.php?i=<?php echo $id ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control form-control-user" name="nome" id="nome">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="codigoUC">Código Unidade Curricular:</label>
                <input type="number" class="form-control form-control-user" name="codigoUC" id="codigoUC">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="sigla">Sigla:</label>
                <input type="text" class="form-control form-control-user" name="sigla" id="sigla">
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
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>                                           
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
                    <option value="1">1</option>
                    <option value="2">2</option>                                           
                </select>

            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="area">Área:</label>
                <select name="area" id="area" onchange="gerarSelectResponsavel(this.value)">
                    <option></option>
<?php
// Buscar áreas
$statement = mysqli_prepare($conn, "SELECT * FROM area");
$statement->execute();
$resultado = $statement->get_result();

while($linha = mysqli_fetch_assoc($resultado)){
    $id = (int) $linha["id_area"];
    $nomeArea = $linha["nome"];

?>
                    <option value="<?php echo $id ?>"><?php echo $nomeArea ?></option>
<?php
}
?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <div id="selectResponsavel">
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormCriarDisciplina()" class="btn btn-primary btn-lg">
        Criar
    </button>
</div>