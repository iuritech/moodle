<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utc = $_GET["id_utc"];

$responsaveis_utc = array();

$statement = mysqli_prepare($conn, "SELECT * FROM utc WHERE id_utc = $id_utc;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_utc = $linha["nome_utc"];
	$sigla_utc = $linha["sigla_utc"];
	$id_responsavel = $linha["id_responsavel"];
	$dsd_1_sem = $linha["dsd_1_sem"];
	$dsd_2_sem = $linha["dsd_2_sem"];
	
	$statement1 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_responsavel;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_responsavel = $linha1["nome"];

$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_responsavel FROM utc;");
$statement2->execute();
$resultado2 = $statement2->get_result();
while($linha2 = mysqli_fetch_assoc($resultado2)){
	$id_responsavel = $linha2["id_responsavel"];
			
	array_push($responsaveis_utc,$id_responsavel);
}

$responsaveis_utc_final = implode(",",$responsaveis_utc);

?>
<div id="editarUTC" class="modal-body" style="height:190px;">
	<text style="font-weight:500; margin-right:50px;">Nome: </text><input type="text" id="editarUTC_nome"  value="<?php echo $nome_utc; ?>" maxlength=50; style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:59px;">Sigla: </text><input type="text" id="editarUTC_sigla" value="<?php echo $sigla_utc; ?>" maxlength=5; style="width:50px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:5px;">Responsável: </text><select id="editarUTC_responsavel" style="width:180px;">
	<?php
	
		echo "<option value='$id_responsavel'>$sigla_utc - $nome_responsavel</option>";
		
		echo "<option value=''></option>";
	
		$statement3 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador NOT IN ($responsaveis_utc_final) ORDER BY id_utc, nome;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		while($linha3 = mysqli_fetch_assoc($resultado3)){
			$id_docente = $linha3["id_utilizador"];
			$nome_docente = $linha3["nome"];
			$id_utc_docente = $linha3["id_utc"];
			
			$statement4 = mysqli_prepare($conn, "SELECT sigla_utc FROM UTC WHERE id_utc = $id_utc_docente;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$sigla_utc_docente = $linha4["sigla_utc"];
			
			echo "<option value='$id_docente'>$sigla_utc_docente - $nome_docente</option>";
		}
		
	?>
	</select>
	<a class="btn btn-danger" href="javascript:void(0)" onclick="eliminarUTC(<?php echo $id_utc; ?>)" style="width:120px; height:45px; border-radius:25px; margin-top:60px;"><i class="material-icons" style="margin-top:3px; float:left;">delete_forever</i><text style="float:left; margin-left:5px; margin-top:3px;"><b>Eliminar</b></text></a>
</div>
<div class="modal-footer">
    <button type="button" onclick="atualizarUTC(<?php echo $id_utc; ?>,'<?php echo $nome_utc; ?>','<?php echo $sigla_utc; ?>',<?php echo $id_responsavel; ?>,<?php echo $dsd_1_sem; ?>,<?php echo $dsd_2_sem; ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atualizar</b>
    </button>
</div>