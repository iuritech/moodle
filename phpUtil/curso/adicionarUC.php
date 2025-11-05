<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_curso = $_GET["id_curso"];

$statement = mysqli_prepare($conn, "SELECT id_utc, semestres FROM curso WHERE id_curso = $id_curso;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc_curso = $linha["id_utc"];
	$semestres_curso = $linha["semestres"];
	$anos_curso = $semestres_curso / 2;
	
	$statement0 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_curso;");
	$statement0->execute();
	$resultado0 = $statement0->get_result();
	$linha0 = mysqli_fetch_assoc($resultado0);
		$nome_utc_curso = $linha0["nome_utc"];
?>
<div id="adicionarUC_div_principal" class="modal-body" style="height:460px;">
	<div id="adicionarUC_dados_principais">
	<text style="font-weight:500; margin-right:50px;">Nome: </text><input type="text" id="adicionarUC_nome" maxlength=50; style="width:200px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:42px;">Código: </text><input type="text" id="adicionarUC_codigo" maxlength="5" style="width:60px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:14px;">Abreviação: </text><input type="text" id="adicionarUC_abreviacao" maxlength="5"; style="width:70px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:65px;">Ano: </text><select id="adicionarUC_ano" style="width:40px;">
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
	<text style="font-weight:500; margin-right:28px;">Semestre: </text><select id="adicionarUC_semestre" style="width:40px;">
	<?php
		echo "<option value=''></option>";
		echo "<option value='1'>1</option>";
		echo "<option value='2'>2</option>";
	?>
	</select>
	<br>
	<br>
	<text style="font-weight:500; margin-right:61px;">Área: </text><select id="adicionarUC_area" style="width:100px;">
	<?php
		echo "<option value=''></option>";
		$statement1 = mysqli_prepare($conn, "SELECT id_area, nome FROM area WHERE id_utc = $id_utc_curso ORDER BY nome;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			$id_area = $linha1["id_area"];
			$nome_area = $linha1["nome"];
			echo "<option value='$id_area'>$nome_area</option>";
	}
	?>
	</select>
	<br>
	<br>
	<text style="font-weight:500; margin-right:5px;">Responsável: </text><select id="adicionarUC_responsavel" style="width:200px;">
	<?php
		echo "<option value=''></option>";
		echo "<option value=''>            ($nome_utc_curso)</option>";
		$statement2 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc = $id_utc_curso ORDER BY nome;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_docente = $linha2["id_utilizador"];
			$nome_docente = $linha2["nome"];
			echo "<option value='$id_docente'>$nome_docente</option>";
		}
		
		echo "<option value=''></option>";
		echo "<option value=''>               (Outros)</option>";
		$statement3 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc != $id_utc_curso ORDER BY nome;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		while($linha3 = mysqli_fetch_assoc($resultado3)){
			$id_docente = $linha3["id_utilizador"];
			$nome_docente = $linha3["nome"];
			echo "<option value='$id_docente'>$nome_docente</option>";
		}
	?>
	</select>
	<br>
	<br>
	<text id="adicionarUC_texto" style="font-weight:500; margin-right:5px;">Componentes (0) : </text><img src="http://localhost/apoio_utc/images/curso/ver_componentes.png" onclick="adicionarUC_componentes()" style="height:30px; width:30px; border-radius:50%; cursor:pointer;">
	<br>
	<input type="checkbox" id="pre-definido_1" onchange="componentePreDefinido1()" style="margin-left:10px; margin-right:5px; vertical-align:middle;"><b>TP</b> (<i>2H</i>) + <b>P</b> (<i>3H</i>)
	<br>
	<input type="checkbox" id="pre-definido_2" onchange="componentePreDefinido2()" style="margin-left:10px; margin-right:5px; vertical-align:middle;"><b>TP</b> (<i>2H</i>) + <b>TP</b> (<i>2H</i>)
	</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="adicionarUC()" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Adicionar UC</b>
    </button>
</div>