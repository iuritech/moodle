<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$id = (int) filter_input(INPUT_GET, 'i');
$idDisciplina = (int) filter_input(INPUT_GET, 'disc');

$statement = mysqli_prepare($conn, "SELECT * FROM bloco WHERE id_bloco = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$idBloco = (int) $linha["id_bloco"];
$idDocenteAtual = (int) $linha["id_docente"];

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
$statement->bind_param('i', $idDocenteAtual);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$nomeDocente = $linha["nome"];
?>
<div class="modal-body">
    <form id="formEditarDSUC" class="user" action="processamento/processarFormEditarDSUC.php?i=<?php echo $idDisciplina ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="bloco">Bloco:</label>
                <select name="bloco" id="bloco">
                        <option value="<?php echo $idBloco ?>"><?php echo "Bloco ".$idBloco ?></option>                                           
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <label>Docente:</label><br>
<?php
// Listar docentes de todas as áreas
$statement = mysqli_prepare($conn, "SELECT * FROM utilizador INNER JOIN funcao_utilizador ON utilizador.id_utilizador = funcao_utilizador.id_utilizador WHERE id_funcao = 4");
$statement->execute();
$resultado = $statement->get_result();
while ($linha = mysqli_fetch_assoc($resultado)) {
    $idUtilizador = (int) $linha["id_utilizador"];
    $nomeUtilizador = $linha["nome"];
    $idArea = (int) $linha["id_area"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = ?");
    $statement->bind_param('i', $idArea);
    $statement->execute();
    $resultado2 = $statement->get_result();
    if(mysqli_num_rows($resultado2)==0){
        $nomeArea = "";
    } else {
        $linha2 = mysqli_fetch_assoc($resultado2);
        $nomeArea = " - ".$linha2["nome"];
    }
?>
                <input <?php if($idUtilizador == $idDocenteAtual){ echo 'checked="checked"'; } ?>type="radio" name="docente" value="<?php echo $idUtilizador ?>"> <?php echo $nomeUtilizador.$nomeArea ?> <br>
<?php
}
?>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:removerBloco(<?php echo $idBloco ?>)" class="btn btn-danger btn-lg">
        Eliminar bloco
    </button>
    <button type="button" onclick="javascript:verificarFormEditar()" class="btn btn-primary btn-lg">
        Guardar
    </button>
</div>