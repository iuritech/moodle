<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../../index.php");
}
include('../../bd.h');

$idCurso = (int) filter_input(INPUT_GET, 'curso');
$idBloco = (int) filter_input(INPUT_GET, 'bloco');

?>
<label for="ano">Ano:</label>
<select name="ano" id="ano" onclick="gerarSelectDisciplina(<?php echo $idCurso ?>,this.value, <?php echo $idBloco ?>); gerarSelectComponente(''); gerarCheckTurmas(''); gerarBotaoSubmit('')">
<?php
$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $idCurso);
$statement->execute();
$resultado1 = $statement->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
$semestres = (int) $linha1["semestres"];

$numAnos = ceil( $semestres / 2 );

for( $i = 1; $i <= $numAnos; $i++){
?>
    <option value="<?php echo $i ?>"><?php echo $i ?>º</option>
<?php
}
?>
</select>
