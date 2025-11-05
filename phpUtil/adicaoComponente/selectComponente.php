<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../../index.php");
}
include('../../bd.h');

$idDisciplina = (int) filter_input(INPUT_GET, 'disc');
$idBloco = (int) filter_input(INPUT_GET, 'bloco');

?>
<label for="componente">Componente:</label>
<select name="componente" id="componente" onclick="gerarCheckTurmas(this.value); gerarBotaoSubmit(1)" >
<?php
// Verificar se a componente já pertençe à junção

// Conjunto de componentes já atribuídas ao bloco
$componentesAtribuidas = array();
$statement = mysqli_prepare($conn, "SELECT * FROM bloco_componente WHERE id_bloco = ?");
$statement->bind_param('i', $idBloco);
$statement->execute();
$resultado1 = $statement->get_result();
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idComponenteAtribuida = (int) $linha1["id_componente"];

    array_push($componentesAtribuidas, $idComponenteAtribuida);
}

$statement = mysqli_prepare($conn, "SELECT * FROM componente "
        . "INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente "
        . "WHERE id_disciplina = ?");
$statement->bind_param('i', $idDisciplina);
$statement->execute();
$resultado1 = $statement->get_result();
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idComponente = (int) $linha1["id_componente"];
    $nomeComponente = $linha1["nome"];
    
    if(!in_array($idComponente, $componentesAtribuidas)){
?>
    <option value="<?php echo $idComponente ?>"><?php echo $idComponente." - ".$nomeComponente ?></option>
<?php
    }
}
?>
</select>
