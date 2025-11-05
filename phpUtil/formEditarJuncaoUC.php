<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');

$idBloco = (int) filter_input(INPUT_GET, 'i');
$idDisciplina = (int) filter_input(INPUT_GET, 'disc');

$_SESSION['bloco'] = $idBloco;

// Pesquisar bloco
$statement = mysqli_prepare($conn, "SELECT * FROM juncao WHERE id_bloco = ?");
$statement->bind_param('i', $idBloco);
$statement->execute();
$resultado1 = $statement->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
$idJuncao = (int) $linha1["id_juncao"];
$nomeJuncao = $linha1["nome"];


$unico = false;
// Pesquisar componentes
$statement = mysqli_prepare($conn, "SELECT * FROM componente "
        . "INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente "
        . "INNER JOIN bloco_componente ON bloco_componente.id_componente = componente.id_componente "
        . "WHERE id_bloco = ?");
$statement->bind_param('i', $idBloco);
$statement->execute();
$resultado1 = $statement->get_result();
if(mysqli_num_rows($resultado1)==1){
    $unico = true;
}
?>
<div class="modal-body">
    <form id="formEditarJuncao" class="user" action="processamento/processarFormEditarJuncaoUC.php?i=<?php echo $idDisciplina ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-2">
                <select name="id" id="id">
                        <option value="<?php echo $idJuncao ?>"><?php echo "Junção ".$idJuncao ?></option>                                           
                </select>
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control form-control-user" value="<?php echo $nomeJuncao ?>" name="juncao" id="juncao">
            </div>
            <div class="col-sm-4">
                <button type="button" onclick="javascript:verificarFormEditarJuncao()" class="btn btn-primary btn-lg">
                    Editar
                </button>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="tabelaComponentes">Componentes:</label>
                <table class="table table-bordered" id="tabelaComponentes" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Componente</th>
                            <th>Unidade curricular</th>
                            <th>Tipo</th>
<?php
//if(!$unico){
?>
                            <th></th>
<?php
//}
?>
                        </tr>
                    </thead>
                    <tbody>
<?php
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idComponente = (int) $linha1["id_componente"];
    $tipoComponente = $linha1["nome"];
    $idDisciplina = (int) $linha1["id_disciplina"];
    
    // Pesquisar disciplina
    $statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = ?");
    $statement->bind_param('i', $idDisciplina);
    $statement->execute();
    $resultado2 = $statement->get_result();
    $linha2 = mysqli_fetch_assoc($resultado2);
    $nomeDisciplina = $linha2["nome"];
    $idCurso = (int) $linha2["id_curso"];
    $anoDisciplina = (int) $linha2["ano"];
    $semestreDisciplina = (int) $linha2["semestre"];
    
    // Pesquisar curso
    $statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
    $statement->bind_param('i', $idCurso);
    $statement->execute();
    $resultado2 = $statement->get_result();
    $linha2 = mysqli_fetch_assoc($resultado2);
    $nomeCurso = $linha2["nome"];
?>
                        <tr>
                            <td><?php echo "ID ".$idComponente ?></td>
                            <td><?php echo $nomeDisciplina." - ".$nomeCurso." (".$anoDisciplina."º Ano, ".$semestreDisciplina."º Sem)" ?></td>
                            <td><?php echo $tipoComponente ?></td>
                            <td><a class="btn btn-danger" onclick="removerComponente(<?php echo $idComponente ?>, <?php echo $idDisciplina ?>, <?php echo $idBloco ?>)">Remover</a></td>
                        </tr>
<?php
}
?>
                    </tbody>
                </table>
                <a class="btn btn-primary" data-toggle="modal" data-target="#adicionarComponenteModal" onclick="gerarFormAtribuirComponenteBloco(<?php echo $idBloco ?>,<?php echo $idDisciplina ?>)">Adicionar outras turmas</a>
            </div>
            <div class="col-sm-1">
            </div>
            <div class="col-sm-5">
                <label for="tabelaTurmas">Turmas:</label>
                <table class="table table-bordered" id="tabelaTurmas" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Curso</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$turmasAtribuidas = array();
$turmasNaoAtribuidas = array();

class Turma{
    public $idTurma;
    public $designacao;
    public $curso;
    public $idDisciplina;
}

// Pesquisar componentes
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
    $anoDisciplina = (int) $linha2["ano"];
    $semestreDisciplina = (int) $linha2["semestre"];

    // Pesquisar turmas
    $statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_curso = ? AND ano = ? AND semestre = ?");
    $statement->bind_param('iii', $idCurso, $anoDisciplina, $semestreDisciplina);
    $statement->execute();
    $resultado2 = $statement->get_result();
    while($linha2 = mysqli_fetch_assoc($resultado2)){
        $idTurma = (int) $linha2["id_turma"];
        $nomeTurma = $linha2["nome"];
        $anoTurma = (int) $linha2["ano"];
        $semestreTurma = (int) $linha2["semestre"];
        $idCurso = (int) $linha2["id_curso"];

        // Pesquisar curso
        $statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
        $statement->bind_param('i', $idCurso);
        $statement->execute();
        $resultado3 = $statement->get_result();
        $linha3 = mysqli_fetch_assoc($resultado3);
        $nomeCurso = $linha3["nome"];
        
        $turma = new Turma();
        $turma->designacao = $nomeTurma." (".$anoTurma."º Ano, ".$semestreTurma."º Sem)";
        $turma->curso = $nomeCurso;
        $turma->idTurma = $idTurma;
        $turma->idDisciplina = $idDisciplina;
        
        $turmaAtribuida = false;
        $statement = mysqli_prepare($conn, "SELECT * FROM turma "
            . "INNER JOIN bloco_turma ON turma.id_turma = bloco_turma.id_turma "
            . "WHERE id_bloco = ?");
        $statement->bind_param('i', $idBloco);
        $statement->execute();
        $resultado3 = $statement->get_result();
        while($linha3 = mysqli_fetch_assoc($resultado3)){
            $idTurmaAtribuida = $linha3["id_turma"];

            if($idTurmaAtribuida == $idTurma){
                $turmaAtribuida = true;
                break;
            }
        }
        
        if($turmaAtribuida == true){
            array_push($turmasAtribuidas, $turma);
        } else {
            array_push($turmasNaoAtribuidas, $turma);
        }
    }
}
foreach($turmasAtribuidas as $turma){
?>                    
                        <tr>
                            <td><?php echo $turma->designacao ?></td>
                            <td><?php echo $turma->curso ?></td>
                            <td><a class="btn btn-danger" onclick="removerTurma(<?php echo $idBloco ?>,<?php echo $turma->idTurma ?>,<?php echo $turma->idDisciplina ?>)">Remover</a></td>
                        </tr>
<?php
}

foreach($turmasNaoAtribuidas as $turma){
?>                    
                        <tr>
                            <td><?php echo $turma->designacao ?></td>
                            <td><?php echo $turma->curso ?></td>
                            <td><a class="btn btn-success" onclick="adicionarTurma(<?php echo $idBloco ?>,<?php echo $turma->idTurma ?>,<?php echo $turma->idDisciplina ?>)">Adicionar</a></td>
                        </tr>
<?php
}
?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:removerJuncao(<?php echo $idJuncao ?>)" class="btn btn-danger btn-lg">
        Eliminar junção
    </button>
</div>
