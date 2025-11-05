<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../../index.php");
}
include('../../bd.h');

$idCurso = (int) filter_input(INPUT_GET, 'curso');

if($idCurso == 0){
    exit();
}
// Buscar informacao do curso
$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $idCurso);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$nomeCurso = "";
if(mysqli_num_rows($resultado)){
    $nomeCurso = $linha["nome"];
}

// Buscar UTCs do curso
$statement = mysqli_prepare($conn, "SELECT * FROM curso_utc WHERE id_curso = ?");
$statement->bind_param('i', $idCurso);
$statement->execute();
$resultado1 = $statement->get_result();
$numUTC = mysqli_num_rows($resultado1);
?>
<div class="modal-header">
    <h5 class="modal-title" id="tituloGerirUTCsModal">UTCs atribuídas ao curso de <?php echo $nomeCurso ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <a class="btn btn-primary" data-toggle="modal" data-target="#atribuirUTCCursoModal" onclick="gerarFormAtribuirUTCCurso(<?php echo $idCurso ?>)">Adicionar UTC</a>
    <div class="card-body">
        <table class="table table-bordered" id="tabelaCursos" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Nome UTC</th>
                    <th>Sigla</th>
                    <th>Responsável</th>
<?php
if($numUTC > 1){
?>
                    <th></th>
<?php
}
?>
                </tr>
            </thead>
            <tbody>
<?php
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idUTC = (int) $linha1["id_utc"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM utc WHERE id_utc = ?");
    $statement->bind_param('i', $idUTC);
    $statement->execute();
    $resultado2 = $statement->get_result();
    $linha2 = mysqli_fetch_assoc($resultado2);
    $idUTC = (int) $linha2["id_utc"];
    $nomeUTC = $linha2["nome"];
    $siglaUTC = $linha2["sigla"];
    $idResponsavel = (int) $linha2["id_responsavel"];
    $nomeResponsavel = "";
    
    // Caso haja um responsável pela UTC, descobre nome do mesmo
    if(!empty($idResponsavel)){
        $statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
        $statement->bind_param('i', $idResponsavel);
        $statement->execute();
        $resultado2 = $statement->get_result();
        $linha2 = mysqli_fetch_assoc($resultado2);
        $nomeResponsavel = $linha2["nome"];
    }
?>

                <tr>
                    <td><?php echo $nomeUTC ?></td>
                    <td><?php echo $siglaUTC ?></td>
                    <td><?php echo $nomeResponsavel ?></td>
<?php
    if($numUTC > 1){
?>
                    <td><a class="btn btn-danger" onclick="removerUTC(<?php echo $idCurso ?>, <?php echo $idUTC ?>)">Remover</a></td>
<?php
    }
?>
                </tr>
<?php
}
?>
            </tbody>
        </table>
    </div>
</div>