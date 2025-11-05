<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('../bd.h');
include('../../bd_final.php');

$idBloco = (int) filter_input(INPUT_GET, 'i');
$idDisciplina = (int) filter_input(INPUT_GET, 'disc');

?>
<div class="modal-body">
    <form id="formAtribuirComponenteBloco" class="user" method="post">
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="bloco">Bloco:</label>
                <select name="bloco" id="bloco">
                        <option value="<?php echo $idBloco ?>"><?php echo $idBloco ?></option>                                           
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="curso">Curso:</label>
                <select name="curso" id="curso" onclick="gerarSelectAno(this.value, <?php echo $idBloco ?>); gerarSelectDisciplina('',''); gerarSelectComponente(''); gerarCheckTurmas(''); gerarBotaoSubmit('')">
<?php

// Array com os cursos que já estão associados ao bloco
$cursosBloco = array();

// Pesquisar componentes associados ao bloco e adicionar o curso dos mesmos ao array cursosBloco
$statement = mysqli_prepare($conn, "SELECT * FROM componente "
        . "INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente "
        . "INNER JOIN bloco_componente ON bloco_componente.id_componente = componente.id_componente "
        . "WHERE id_bloco = ?");
$statement->bind_param('i', $idBloco);
$statement->execute();
$resultado1 = $statement->get_result();
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idDisciplina = (int) $linha1["id_disciplina"];
    
    // Pesquisar disciplina
    $statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = ?");
    $statement->bind_param('i', $idDisciplina);
    $statement->execute();
    $resultado2 = $statement->get_result();
    $linha2 = mysqli_fetch_assoc($resultado2);
    $idCurso = (int) $linha2["id_curso"];
    
    array_push($cursosBloco, $idCurso);
}


if(isset($_SESSION['permAdmin'])) {
    $statement = mysqli_prepare($conn, "SELECT * FROM curso");
    $statement->execute();
    $resultado1 = $statement->get_result();
    while($linha1 = mysqli_fetch_assoc($resultado1)){
        $idCurso = $linha1["id_curso"];
        $nomeCurso = $linha1["nome"];
        $semestres = $linha1["semestres"];
        
        if(!in_array($idCurso, $cursosBloco)){
?>
                    <option value="<?php echo $idCurso ?>"><?php echo $nomeCurso ?></option>     
<?php
        }
    }
    
} else {
    $idUtilizador = (int) $_SESSION['id'];
    $statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
    $statement->bind_param('i', $idUtilizador);
    $statement->execute();
    $resultado1 = $statement->get_result();
    $linha1 = mysqli_fetch_assoc($resultado1);
    $idArea = (int) $linha1["id_area"]; // Área do utilizador
    
    $statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = ?");
    $statement->bind_param('i', $idArea);
    $statement->execute();
    $resultado1 = $statement->get_result();
    $linha1 = mysqli_fetch_assoc($resultado1);
    $idUTC = (int) $linha1["id_utc"]; // UTC do utilizador
    
    $statement = mysqli_prepare($conn, "SELECT * FROM curso_utc WHERE id_utc = ?");
    $statement->bind_param('i', $idUTC);
    $statement->execute();
    $resultado1 = $statement->get_result();
    while($linha1 = mysqli_fetch_assoc($resultado1)){
        $idCurso = (int) $linha1["id_curso"];
        $statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
        $statement->bind_param('i', $idCurso);
        $statement->execute();
        $resultado2 = $statement->get_result();
        $linha2 = mysqli_fetch_assoc($resultado2);
        $nomeCurso = $linha2["nome"];
        $semestres = $linha2["semestres"];
?>
                    <option value="<?php echo $idCurso ?>"><?php echo $nomeCurso ?></option>
<?php
    }
}

?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <div id="selectAno">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <div id="selectDisciplina">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <div id="selectComponente">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <div id="checkTurmas">
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <div id="botaoSubmit">
    </div>
</div>
