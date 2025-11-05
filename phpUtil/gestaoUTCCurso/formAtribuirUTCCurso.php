<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../../index.php");
}
include('../../bd.h');

$idCurso = (int) filter_input(INPUT_GET, 'curso');

// Pesquisar disciplina
$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $idCurso);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$nomeCurso = $linha["nome"];

// Array com as UTC que já estão associadas ao curso
$utcsCurso = array();

// Pesquisar componentes associados ao bloco e adicionar o curso dos mesmos ao array cursosBloco
$statement = mysqli_prepare($conn, "SELECT * FROM curso_utc WHERE id_curso = ?");
$statement->bind_param('i', $idCurso);
$statement->execute();
$resultado1 = $statement->get_result();
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idUTC = (int) $linha1["id_utc"];
    
    array_push($utcsCurso, $idUTC);
}
?>
<div class="modal-body">
    <form id="formAtribuirUTCCurso" class="user" method="post">
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="curso">Curso:</label>
                <select name="curso" id="curso">
                        <option value="<?php echo $idCurso ?>"><?php echo $nomeCurso ?></option>                                           
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="utc">UTC:</label>
                <select name="utc" id="utc">
<?php
$statement = mysqli_prepare($conn, "SELECT * FROM utc");
$statement->execute();
$resultado1 = $statement->get_result();
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idUTC = (int) $linha1["id_utc"];
    $nomeUTC = $linha1["nome"];
    
    if(!in_array($idUTC, $utcsCurso)){
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
    <button type="button" onclick="submeterFormAtribuirUTCCurso()" class="btn btn-primary btn-lg">
        Adicionar
    </button>
</div>
