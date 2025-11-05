<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utilizador = $_SESSION["id"];

$is_admin = false;

$statement = mysqli_prepare($conn, "SELECT is_admin FROM utilizador WHERE id_utilizador = $id_utilizador;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$is_admin_utilizador = $linha["is_admin"];
	
	if ($is_admin_utilizador == 0) {
		header("Location: ../index.php");
	}
	
$id_1_utc = 0;
	
?>
<div id="criarDocente" class="modal-body" style="height:580px;">
	<text style="font-weight:500; margin-right:84px;">Login: </text><input type="text" id="criarDocente_login" maxlength=50; style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:57px;">Password: </text><input type="text" id="criarDocente_password" maxlength=50; style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:5px;">Nome Completo: </text><input type="text" id="criarDocente_nome" maxlength=50; style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:95px;">UTC: </text><select id="criarDocente_utc" onchange="atualizarAreas()" style="width:115px;">
	<?php
		$loop_utc = 0;
		$statement1 = mysqli_prepare($conn, "SELECT id_utc, nome_utc FROM utc ORDER BY nome_utc;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			$id_utc_temp = $linha1["id_utc"];
			$nome_utc_temp = $linha1["nome_utc"];
			
			if($loop_utc == 0){
				$id_1_utc = $id_utc_temp;
			}
			
			echo "<option value='$id_utc_temp'>$nome_utc_temp</option>";
			
			$loop_utc += 1;
		}
	?>
	</select>
	<br>
	<br>
	<text style="font-weight:500; margin-right:91px;">Área: </text><select id="criarDocente_area" style="width:80px;">
	<?php
		$statement2 = mysqli_prepare($conn, "SELECT id_area, nome FROM area WHERE id_utc = $id_1_utc ORDER BY nome;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_area = $linha2["id_area"];
			$nome_area = $linha2["nome"];
			
			echo "<option value='$id_area'>$nome_area</option>";
		}
	
	?>
	</select>
	<br>
	<br>
	<text style="font-weight:500; margin-right:73px;">Função: </text><select id="criarDocente_funcao" style="width:120px;">
	<?php
		$statement2 = mysqli_prepare($conn, "SELECT id_funcao, nome FROM funcao WHERE id_funcao != 1 AND id_funcao != 2 AND id_funcao != 3;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_funcao = $linha2["id_funcao"];
			$nome_funcao = $linha2["nome"];
			
			echo "<option value='$id_funcao'>$nome_funcao</option>";
		}
	?>
	</select>
	<br>
	<br>
	<text style="font-weight:500; margin-right:73px;">Admin: </text><input type="checkbox" id="criarDocente_admin" style="margin-left:5px;">
	<br>
	<br>
	<text style="font-weight:500; margin-right:59px;">Horários: </text><input type="checkbox" id="criarDocente_horarios" style="margin-left:5px;">
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