<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../../index.php");
}
include('../../bd.h');

$idArea = (int) filter_input(INPUT_GET, 'area');

if($idArea == 0){
    exit();
}
?>
<label for="responsavel">Responsável:</label>
<select name="responsavel" id="responsavel">
    <option value="0"></option>
<?php

// Buscar ids de utilizadores que pertençam à área da disciplina
$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_area = ?");
$statement->bind_param('i', $idArea);
$statement->execute();
$resultado = $statement->get_result();
while($linha = mysqli_fetch_assoc($resultado)){
    $id = (int) $linha["id_utilizador"];
    $nomeResponsavel = $linha["nome"];
?>
    <option value="<?php echo $id ?>"><?php echo $nomeResponsavel ?></option>
<?php
}
?>
</select>