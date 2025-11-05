<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../../index.php");
}
include('../../bd.h');

$idComponente = (int) filter_input(INPUT_GET, 'comp');
$idBloco = (int) filter_input(INPUT_GET, 'bloco');

?>
<label for="turmas">Turmas a adicionar:</label>
<div class="form-group" id="turmas">
    
    <?php
    // Conjunto de turmas que já estão a ter aulas no bloco
    $turmasAtribuidas = array();
    
    $statement = mysqli_prepare($conn, "SELECT * FROM bloco_turma WHERE id_bloco = ?");
    $statement->bind_param('i', $idBloco);
    $statement->execute();
    $resultado1 = $statement->get_result();
    if(!mysqli_num_rows($resultado1)==0){
        while($linha1 = mysqli_fetch_assoc($resultado1)){
            $idTurmaAtribuida = (int) $linha1["id_turma"];

            array_push($turmasAtribuidas, $idTurmaAtribuida);
        }
    }
    
    // Listar as turmas do curso a que a componente selecionada pertence
    $statement = mysqli_prepare($conn, "SELECT * FROM componente WHERE id_componente = ?");
    $statement->bind_param('i', $idComponente);
    $statement->execute();
    $resultado1 = $statement->get_result();
    $linha1 = mysqli_fetch_assoc($resultado1);
    $idDisciplina = (int) $linha1["id_disciplina"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM componente WHERE id_componente = ?");
    $statement->bind_param('i', $idComponente);
    $statement->execute();
    $resultado1 = $statement->get_result();
    $linha1 = mysqli_fetch_assoc($resultado1);
    $idDisciplina = (int) $linha1["id_disciplina"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = ?");
    $statement->bind_param('i', $idDisciplina);
    $statement->execute();
    $resultado1 = $statement->get_result();
    $linha1 = mysqli_fetch_assoc($resultado1);
    $idCurso = (int) $linha1["id_curso"];
    $anoDisciplina = (int) $linha1["ano"];
    $semestreDisciplina = (int) $linha1["semestre"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_curso = ? AND ano = ? AND semestre = ?");
    $statement->bind_param('iii', $idCurso, $anoDisciplina, $semestreDisciplina);
    $statement->execute();
    $resultado1 = $statement->get_result();
    $iTurma = 1;
    while($linha1 = mysqli_fetch_assoc($resultado1)){
        $idTurma = (int) $linha1["id_turma"];
        
        if(!in_array($idTurma, $turmasAtribuidas)){
            $nomeTurma = $linha1["nome"];


    ?>
    <input type="checkbox" id="turma<?php echo $iTurma ?>" name="turma" value="<?php echo $idTurma ?>">
    <label for="turma<?php echo $iTurma ?>"><?php echo $nomeTurma ?></label><br>
    <?php
        $iTurma += 1;
        }
    }
    ?>
</div>