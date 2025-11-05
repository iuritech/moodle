<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$responsaveis_utc = array();

$statement = mysqli_prepare($conn, "SELECT DISTINCT id_responsavel FROM utc;");
$statement->execute();
$resultado = $statement->get_result();
while($linha = mysqli_fetch_assoc($resultado)){
	$id_responsavel = $linha["id_responsavel"];
			
	array_push($responsaveis_utc,$id_responsavel);
}

$responsaveis_utc_final = implode(",",$responsaveis_utc);

?>
<div id="criarUTC" class="modal-body" style="height:235px;">
	<text style="font-weight:500; margin-right:50px;">Nome: </text><input type="text" id="criarUTC_nome" maxlength=50; style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:59px;">Sigla: </text><input type="text" id="criarUTC_sigla" maxlength=5; style="width:50px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:5px;">Responsável: </text><select id="criarUTC_responsavel" style="width:150px;">
	<?php
		$statement2 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador NOT IN ($responsaveis_utc_final) ORDER BY id_utc, nome;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_docente = $linha2["id_utilizador"];
			$nome_docente = $linha2["nome"];
			$id_utc_docente = $linha2["id_utc"];
			
			$statement3 = mysqli_prepare($conn, "SELECT sigla_utc FROM UTC WHERE id_utc = $id_utc_docente;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$sigla_utc_docente = $linha3["sigla_utc"];
			
			echo "<option value='$id_docente'>$sigla_utc_docente - $nome_docente</option>";
		}
		
	?>
	</select>
	<br>
	<div style="margin-top:10px;"><img src="http://localhost/apoio_utc/images/warning.jpg" style="width:20px; height:20px; margin-right:5px;"><text style="font-size:14px;"><i>A UTC do docente selecionado irá ser alterada para esta nova</i></text></div>
	<br>
	<!--
	<text style="font-weight:500;">Bloquear DSD 1º semestre: </text>
		<input type="checkbox" id="criarUTC_dsd_1_sem" style="margin-left:5px; vertical-align:middle;">
	<br>
	<br>
	<text style="font-weight:500;">Bloquear DSD 2º semestre: </text>
		<input type="checkbox" id="criarUTC_dsd_2_sem" style="margin-left:5px; vertical-align:middle;"> -->
</div>
<div class="modal-footer">
    <button type="button" onclick="criarUTC()" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Criar</b>
    </button>
</div>