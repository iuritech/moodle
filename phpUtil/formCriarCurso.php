<?php
// Formulário de criação de cursos

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
?>
<div class="modal-body">
    <form id="formCriarCurso" class="user" action="processamento/processarFormCriarCurso.php" method="post">
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
            <div class="col-sm-3">
                <label for="nome">Duração (Semestres):</label>
                <input type="number" class="form-control form-control-user" name="semestres" id="semestres">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="utc">UTC:</label>
                <select name="utc" id="utc">
                    <option></option>
<?php
if(isset($_SESSION['permAdmin'])){
    $statement = mysqli_prepare($conn, "SELECT * FROM utc");
    $statement->execute();
    $resultado1 = $statement->get_result();
    while($linha1 = mysqli_fetch_assoc($resultado1)){
        $idUTC = (int) $linha1["id_utc"];
        $nomeUTC = $linha1["nome"];
?>
                    <option value="<?php echo $idUTC ?>"><?php echo $nomeUTC ?></option>
<?php
    }
}
?>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormCriarCurso()" class="btn btn-light btn-lg">
        Criar
    </button>
</div>