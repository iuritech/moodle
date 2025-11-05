<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../../bd_final.php');

$idComponente = (int) filter_input(INPUT_GET, 'comp');
$idTurma = (int) filter_input(INPUT_GET, 'turma');

// Pesquisar tipo da componente
$statement = mysqli_prepare($conn, "SELECT * FROM componente INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente WHERE id_componente = ?");
$statement->bind_param('i', $idComponente);
$statement->execute();
$resultado1 = $statement->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
$tipoComponente = $linha1["nome_tipocomponente"];

// Pesquisar nome da turma
$statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_turma = ?");
$statement->bind_param('i', $idTurma);
$statement->execute();
$resultado1 = $statement->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
$nomeTurma = $linha1["nome"];

?>
<div class="modal-body">
    <form id="formCriarDSUC" class="user" action="processamento/processarFormCriarDSUC.php" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="componente">Componente:</label>
                <select name="componente" id="componente">
                        <option value="<?php echo $idComponente ?>"><?php echo $idComponente." - ".$tipoComponente ?></option>                                           
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="turma">Turma:</label>
                <select name="turma" id="turma">
                        <option value="<?php echo $idTurma ?>"><?php echo $nomeTurma ?></option>                                           
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="docente">Docente:</label>
                <select name="docente" id="docente">
<?php
// Determinar área da componente
$statement = mysqli_prepare($conn, "SELECT * FROM componente INNER JOIN disciplina ON componente.id_disciplina = disciplina.id_disciplina INNER JOIN area ON disciplina.id_area = area.id_area WHERE id_componente = ?");
$statement->bind_param('i', $idComponente);
$statement->execute();
$resultado1 = $statement->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
$idArea = (int) $linha1["id_area"];

// Listar docentes dessa área
$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_area = ?");
$statement->bind_param('i', $idArea);
$statement->execute();
$resultado = $statement->get_result();
while ($linha = mysqli_fetch_assoc($resultado)) {
    $idUtilizador = (int) $linha["id_utilizador"];
    $nomeUtilizador = $linha["nome"];
?>
                    <option value="<?php echo $idUtilizador ?>"><?php echo $nomeUtilizador ?></option>
<?php
}
?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <a class="btn btn-primary" onclick="gerarFormCriarDSUCSelExp(<?php echo $idComponente ?>,<?php echo $idTurma ?>)">Docente de outra área</a>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormAtribuirComponenteBloco()" class="btn btn-primary btn-lg">
        Atribuir
    </button>
</div>
