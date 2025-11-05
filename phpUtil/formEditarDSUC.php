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
$idDocente = (int) $linha["id_docente"];

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
$statement->bind_param('i', $idDocente);
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
            <div class="col-sm-6">
                <label for="docente">Docente:</label>
                <select name="docente" id="docente">
<?php
// Para buscar docentes das áreas associadas às componentes associadas ao bloco
$areas = array();

$statement = mysqli_prepare($conn, "SELECT * FROM bloco_componente WHERE id_bloco = ?");
$statement->bind_param('i', $idBloco);
$statement->execute();
$resultado = $statement->get_result();
while ($linha = mysqli_fetch_assoc($resultado)) {
    $idComponente = (int) $linha["id_componente"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM componente INNER JOIN disciplina ON componente.id_disciplina = disciplina.id_disciplina INNER JOIN area ON disciplina.id_area = area.id_area WHERE id_componente = ?");
    $statement->bind_param('i', $idComponente);
    $statement->execute();
    $resultado2 = $statement->get_result();
    $linha2 = mysqli_fetch_assoc($resultado2);
    $idArea = (int) $linha2["id_area"];
    
    array_push($areas, $idArea);
}

// Array a indicar as áreas cujos professores já foram listados
$areasListadas = array();
foreach ($areas as $areaArray) {
    if(!in_array($areaArray, $areasListadas)){
        $statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_area = ?");
        $statement->bind_param('i', $areaArray);
        $statement->execute();
        $resultado = $statement->get_result();
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $idUtilizador = (int) $linha["id_utilizador"];
            $nomeUtilizador = $linha["nome"];
?>
                    <option <?php if($idDocente == $idUtilizador){ echo "selected"; } ?> value="<?php echo $idUtilizador ?>"><?php echo $nomeUtilizador ?></option>
<?php
        }
    }
    array_push($areasListadas, $areaArray);
}
?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <a class="btn btn-primary" onclick="gerarFormEditarDSUCSelExp(<?php echo $idBloco ?>, <?php echo $idDisciplina ?>)">Selecionar outro docente</a>
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
