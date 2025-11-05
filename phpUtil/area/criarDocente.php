<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utilizador = $_SESSION["id"];
$id_area = $_GET["id_area"];

$is_admin = false;

$statement = mysqli_prepare($conn, "SELECT nome, id_utc FROM area WHERE id_area = $id_area;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_area = $linha["nome"];
	$id_utc = $linha["id_utc"];
	
	$statement1 = mysqli_prepare($conn, "SELECT nome_utc, id_responsavel FROM utc WHERE id_utc = $id_utc;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_utc = $linha1["nome_utc"];
		$id_coordenador = $linha1["id_responsavel"];
		
?>
<div id="criarDocente" class="modal-body" style="height:480px;">
	<text style="font-weight:500; margin-right:84px;">Login: </text><input type="text" id="criarDocente_login" maxlength=50; style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:57px;">Password: </text><input type="text" id="criarDocente_password" maxlength=50; style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:5px;">Nome Completo: </text><input type="text" id="criarDocente_nome" maxlength=50; style="width:150px;"></input>
	<br>
	<br>
	<?php if($id_utilizador == $id_coordenador) { ?>
	<text style="font-weight:500; margin-right:95px;">UTC: </text><select id="criarDocente_utc" disabled style="width:115px;">
	<?php
		echo "<option value='$id_utc'>$nome_utc</option>";
		$statement4 = mysqli_prepare($conn, "SELECT id_utc, nome_utc FROM utc WHERE id_utc != $id_utc ORDER BY nome_utc;");
		$statement4->execute();
		$resultado4 = $statement4->get_result();
		while($linha4 = mysqli_fetch_assoc($resultado4)){
			$id_utc_temp = $linha4["id_utc"];
			$nome_utc_temp = $linha4["nome_utc"];
			
			echo "<option value='$id_utc_temp'>$nome_utc_temp</option>";
		}
	?>
	<?php }
	else{ ?>
		<text style="font-weight:500; margin-right:95px;">UTC: </text><select id="criarDocente_utc" disabled style="width:115px;">
		<?php
			echo "<option value='$id_utc'>$nome_utc</option>";
		?>
	<?php } ?>
	</select>
	<br>
	<br>
	<?php if($id_utilizador == $id_coordenador) { ?>
	<text style="font-weight:500; margin-right:91px;">Área: </text><select id="criarDocente_area" style="width:80px;">
	<?php
		echo "<option value='$id_area'>$nome_area</option>";
		
		$statement2 = mysqli_prepare($conn, "SELECT id_area, nome FROM area WHERE id_area != $id_area AND id_utc = $id_utc ORDER BY nome;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_area_temp = $linha2["id_area"];
			$nome_area_temp = $linha2["nome"];
			
			echo "<option value='$id_area_temp'>$nome_area_temp</option>";
		}
	?>
	</select>
	<?php } else { ?>
	<text style="font-weight:500; margin-right:91px;">Área: </text><select id="criarDocente_area" disabled style="width:80px;">
	<?php
		echo "<option value='$id_area'>$nome_area</option>";
	?>
	</select>
	<?php } ?>
	<br>
	<br>
	<?php if($id_utilizador == $id_coordenador) { ?>
	<text style="font-weight:500; margin-right:73px;">Função: </text><select id="criarDocente_funcao" style="width:120px;">
	<?php
		if($id_utilizador == $id_coordenador){
			$statement2 = mysqli_prepare($conn, "SELECT id_funcao, nome FROM funcao WHERE id_funcao != 1 AND id_funcao != 2 AND id_funcao != 3;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$id_funcao = $linha2["id_funcao"];
				$nome_funcao = $linha2["nome"];
				
				echo "<option value='$id_funcao'>$nome_funcao</option>";
			}
		}
		else{
			echo "<option value='6'>Assist. Convidado</option>";
		}
	?>
	<?php }
	else {	?>
		<text style="font-weight:500; margin-right:73px;">Função: </text><select id="criarDocente_funcao" disabled style="width:120px;">
		<?php
			echo "<option value='6'>Assist. Convidado</option>";
		?>
	<?php } ?>
	</select>
	<br>
	<br>
	<text style="font-weight:500; margin-right:61px;">Imagem: </text>
	<br>
	<input type="checkbox" id="criarDocente_imagem" checked="true" style="margin-right:5px; margin-top:25px;"><img src="http://localhost/apoio_utc/images/perfil_default.jpg" style="height:100px; border-radius:50%; margin-top:10px;">
</div>
<div class="modal-footer">
    <button type="button" onclick="criarDocente()" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Criar</b>
    </button>
</div>