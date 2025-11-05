<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utilizador = $_SESSION["id"];

$is_admin = false;

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_utilizador");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc_utilizador = $linha["id_utc"];
	
	if($linha["is_admin"] == 1){
		$is_admin = true;
	}
	
	$statement1 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_utilizador;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_utc_utilizador = $linha1["nome_utc"];

?>
<div id="criarCurso_div_principal" class="modal-body" style="height:460px;">
	<text style="font-weight:500; margin-right:59px;">Nome: </text><input type="text" id="criarCurso_nome" maxlength=50; style="width:200px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:69px;">Sigla: </text><input type="text" id="criarCurso_sigla" maxlength="5"; style="width:70px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:2px;">Tipo de Curso: </text><select id="criarCurso_tipo" style="width:150px;">
	<?php
		echo "<option value=''></option>";
		$statement2 = mysqli_prepare($conn, "SELECT * FROM curso_tipo ORDER BY id_tipo_curso;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_tipo_curso = $linha2["id_tipo_curso"];
			$nome = $linha2["nome"];
			$sigla = $linha2["sigla"];
			echo "<option value='$id_tipo_curso'>$nome ($sigla)</option>";
		}
	?>
	</select>
	<br>
	<br>
	<text style="font-weight:500; margin-right:43px;">Duração: </text><select id="criarCurso_duracao" style="width:40px;">
	<?php
		echo "<option value=''></option>";
		$counter_ano = 1;
		while($counter_ano <= 5){
			echo "<option value='$counter_ano'>$counter_ano</option>";
			$counter_ano += 1;
		}
	?>
	</select> (anos)
	<br>
	<br>
	<text style="font-weight:500; margin-right:7px;">Coordenador: </text><select id="criarCurso_coordenador" style="width:200px;">
	<?php
		if(!$is_admin){
			echo "<option value=''></option>";
			echo "<option value=''>                ($nome_utc_utilizador)</option>";
			$statement2 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc = $id_utc_utilizador AND is_admin = 0 ORDER BY nome;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$id_docente = $linha2["id_utilizador"];
				$nome_docente = $linha2["nome"];
				echo "<option value='$id_docente'>$nome_docente</option>";
			}
			
			echo "<option value=''></option>";
			echo "<option value=''>                   (Outros)</option>";
			$statement3 = mysqli_prepare($conn, "SELECT id_utilizador, nome, id_utc FROM utilizador WHERE id_utc != $id_utc_utilizador ORDER BY id_utc, nome;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			while($linha3 = mysqli_fetch_assoc($resultado3)){
				$id_docente = $linha3["id_utilizador"];
				$nome_docente = $linha3["nome"];
				$id_utc_docente = $linha3["id_utc"];
				
				$statement4 = mysqli_prepare($conn, "SELECT sigla_utc FROM utc WHERE id_utc = $id_utc_docente;");
				$statement4->execute();
				$resultado4 = $statement4->get_result();
				$linha4 = mysqli_fetch_assoc($resultado4);
					$sigla_utc_docente = $linha4["sigla_utc"];
				
				echo "<option value='$id_docente'>$sigla_utc_docente - $nome_docente</option>";
			}
		}
		else{
			
			
		}
	?>
	</select>
	<br>
	<br>
	<text style="font-weight:500; margin-right:61px;">Imagem: </text><br><br>
	<input type="checkbox" id="criarCurso_imagem" checked="true" style="margin-right:5px;"><img src="http://localhost/apoio_utc/images/curso/imagem_curso_default.jpg" style="height:100px; border-radius:20px;">
	<br>
	<br>
</div>
<div class="modal-footer">
    <button type="button" onclick="criarCurso()" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Criar</b>
    </button>
</div>