<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../../index.php");
}
include('../../bd.h');

$idCurso = (int) filter_input(INPUT_GET, 'curso');
$ano = (int) filter_input(INPUT_GET, 'ano');
$idBloco = (int) filter_input(INPUT_GET, 'bloco');

$blocoSelecionado = (int) $_SESSION['bloco'];
// Array com as disciplinas que têm componentes que já foram atribuídas à junção a ser editada
$disciplinasJuncao = array();

$statement = mysqli_prepare($conn, "SELECT * FROM componente "
        . "INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente "
        . "INNER JOIN bloco_componente ON bloco_componente.id_componente = componente.id_componente "
        . "WHERE id_bloco = ?");
$statement->bind_param('i', $blocoSelecionado);
$statement->execute();
$resultado1 = $statement->get_result();
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idDisciplina = (int) $linha1["id_disciplina"];
    array_push($disciplinasJuncao, $idDisciplina);
}

?>
<label for="disciplina">Unidade curricular:</label>
<select name="disciplina" id="disciplina" onclick="gerarSelectComponente(this.value, <?php echo $idBloco ?>); gerarBotaoSubmit(''); gerarCheckTurmas('')">
<?php
$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_curso = ? AND ano = ?");
$statement->bind_param('ii', $idCurso, $ano);
$statement->execute();
$resultado1 = $statement->get_result();
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idDisciplina = (int) $linha1["id_disciplina"];
    $nomeDisciplina = $linha1["nome"];
    
    if(!in_array($idDisciplina, $disciplinasJuncao)){
?>
    <option value="<?php echo $idDisciplina ?>"><?php echo $nomeDisciplina ?></option>
<?php
    }
}
?>
</select>
