<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../../index.php");
}
include('../../bd.h');

$idUTC = (int) filter_input(INPUT_GET, 'utc');

if($idUTC == 0){
    exit();
}
?>
<label for="area">Área:</label>
<select name="area" id="area">
    <option value="0"></option>
<?php
$statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_utc = ?");
$statement->bind_param('i', $idUTC);
$statement->execute();
$resultado1 = $statement->get_result();
if(mysqli_num_rows($resultado1)>0){
    while($linha1 = mysqli_fetch_assoc($resultado1)){
        $idArea = $linha1["id_area"];
        $nomeArea = $linha1["nome"];

?>
    <option value="<?php echo $idArea ?>"><?php echo $nomeArea ?></option>
<?php
    }
}
?>
</select>