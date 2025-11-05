<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utilizador = $_SESSION["id"];
$id_docente = $_GET["id_docente"];

$is_admin = false;

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_docente;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_docente = $linha["id_utilizador"];
	$login_docente = $linha["login"];
	$password_docente = $linha["password"];
	$nome_docente = $linha["nome"];
	$id_utc = $linha["id_utc"];
	$id_area = $linha["id_area"];
	$id_funcao = $linha["id_funcao"];
	
	$statement1 = mysqli_prepare($conn, "SELECT nome_utc, id_responsavel FROM utc WHERE id_utc = $id_utc;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_utc = $linha1["nome_utc"];
		$id_coordenador = $linha1["id_responsavel"];
		
	$statement2 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
	$statement2->execute();
	$resultado2 = $statement2->get_result();
	$linha2 = mysqli_fetch_assoc($resultado2);
		$nome_area = $linha2["nome"];
	
	$statement3 = mysqli_prepare($conn, "SELECT nome FROM funcao WHERE id_funcao = $id_funcao;");
	$statement3->execute();
	$resultado3 = $statement3->get_result();
	$linha3 = mysqli_fetch_assoc($resultado3);
		$nome_funcao = $linha3["nome"];
		
?>
<div id="editarDocente" class="modal-body" style="height:320px;">
	<text style="font-weight:500; margin-right:84px;">Login: </text><input type="text" id="editarDocente_login" maxlength=50; value="<?php echo $login_docente; ?>" style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:57px;">Password: </text><input type="text" id="editarDocente_password" maxlength=50; value="<?php echo $password_docente; ?>" style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:5px;">Nome Completo: </text><input type="text" id="editarDocente_nome" maxlength=50; value="<?php echo $nome_docente; ?>" style="width:150px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:95px;">UTC: </text><select id="editarDocente_utc" disabled style="width:115px;">
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
	</select>
	<br>
	<br>
	<text style="font-weight:500; margin-right:91px;">Área: </text><select id="editarDocente_area" style="width:80px;">
	<?php
		echo "<option value='$id_area'>$nome_area</option>";
		
		$statement5 = mysqli_prepare($conn, "SELECT id_area, nome FROM area WHERE id_area != $id_area AND id_utc = $id_utc ORDER BY nome;");
		$statement5->execute();
		$resultado5 = $statement5->get_result();
		while($linha5 = mysqli_fetch_assoc($resultado5)){
			$id_area_temp = $linha5["id_area"];
			$nome_area_temp = $linha5["nome"];
			
			echo "<option value='$id_area_temp'>$nome_area_temp</option>";
		}
	?>
	</select>
	<br>
	<br>
	<?php
		if($id_utilizador == $id_coordenador){
	?>
	<text style="font-weight:500; margin-right:73px;">Função: </text><select id="editarDocente_funcao" style="width:120px;">
		<?php } else{ ?>
	<text style="font-weight:500; margin-right:73px;">Função: </text><select id="editarDocente_funcao" disabled style="width:120px;">	
		<?php }
		
			echo "<option value='$id_funcao'>$nome_funcao</option>";
		
			$statement5 = mysqli_prepare($conn, "SELECT id_funcao, nome FROM funcao WHERE id_funcao != 1 AND id_funcao != 2 AND id_funcao != 3 AND id_funcao != $id_funcao;");
			$statement5->execute();
			$resultado5 = $statement5->get_result();
			while($linha5 = mysqli_fetch_assoc($resultado5)){
				$id_funcao_temp = $linha5["id_funcao"];
				$nome_funcao_temp = $linha5["nome"];
				
				echo "<option value='$id_funcao_temp'>$nome_funcao_temp</option>";
			}
		?>
	</select>
	
	<?php if((($id_utilizador == $id_coordenador) || ($id_funcao == 6)) && ($id_docente != $id_utilizador)){ ?>
		<a class="btn btn-danger" href="javascript:void(0)" onclick="removerDocente(<?php echo $id_docente; ?>)" style="width:130px; height:38px; border-radius:25px; margin-top:40px;"><i class="material-icons" style="margin-top:0px; float:left;">person_remove</i><text style="float:left; margin-left:10px; margin-bottom:10px;"><b>Remover</b></text></a>
		
	<?php
	}
	?>
</div>
<div class="modal-footer">
    <button type="button" onclick="atualizarDocente(<?php echo $id_docente; ?>,'<?php echo $login_docente; ?>','<?php echo $password_docente; ?>','<?php echo $nome_docente; ?>',<?php echo $id_utc; ?>,<?php echo $id_area; ?>,<?php echo $id_funcao; ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atualizar</b>
    </button>
</div>