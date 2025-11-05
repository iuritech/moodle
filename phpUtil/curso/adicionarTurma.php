<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_curso = $_GET["id_curso"];

$statement = mysqli_prepare($conn, "SELECT semestres FROM curso WHERE id_curso = $id_curso;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$semestres_curso = $linha["semestres"];
	$anos_curso = $semestres_curso / 2;
	
?>
<div id="adicionarTurma_div_principal" class="modal-body" style="height:190px;">
	<div id="adicionarTurma_dados_principais">
		<text style="font-weight:500; margin-right:50px;">Nome: </text><input type="text" id="adicionarTurma_nome" maxlength=5; style="width:80px;"></input>
		<br>
		<br>
		<text style="font-weight:500; margin-right:65px;">Ano: </text><select id="adicionarTurma_ano" style="width:40px;">
		<?php
			echo "<option value=''></option>";
			$counter_ano = 1;
			while($counter_ano <= $anos_curso){
				echo "<option value='$counter_ano'>$counter_ano</option>";
				$counter_ano += 1;
			}
		?>
		</select>
		<br>
		<br>
		<text style="font-weight:500; margin-right:28px;">Semestre: </text><select id="adicionarTurma_semestre" style="width:40px;">
		<?php
			echo "<option value=''></option>";
			echo "<option value='1'>1</option>";
			echo "<option value='2'>2</option>";
		?>
		</select>
		<br>
		<br>
	</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="adicionarTurma(<?php echo $id_curso; ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Adicionar Turma</b>
    </button>
</div>