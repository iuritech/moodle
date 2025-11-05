<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_uc = $_GET["id_uc"];

$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = $id_uc;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_uc = $linha["nome_uc"];
	$codigo_uc = $linha["codigo_uc"];
	$abreviacao_uc = $linha["abreviacao_uc"];
	$id_area = $linha["id_area"];
	$id_responsavel = $linha["id_responsavel"];
	$id_curso = $linha["id_curso"];
	
	$statement1 = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = $id_uc;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_uc = $linha1["nome_uc"];
		
	$statement2 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
	$statement2->execute();
	$resultado2 = $statement2->get_result();
	$linha2 = mysqli_fetch_assoc($resultado2);
		$id_utc_curso = $linha2["id_utc"];
		
	$statement3 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_curso;");
	$statement3->execute();
	$resultado3 = $statement3->get_result();
	$linha3 = mysqli_fetch_assoc($resultado3);
		$nome_utc_curso = $linha3["nome_utc"];

?>
<div id="editarUC_div_principal" class="modal-body" style="height:400px;">
	<div id="editarUC_dados_principais">
	<text style="font-weight:500; margin-right:50px;">Nome: </text><input type="text" id="editarUC_nome" value="<?php echo $nome_uc; ?>" maxlength=50; style="width:200px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:42px;">Código: </text><input type="text" id="editarUC_codigo" value="<?php echo $codigo_uc; ?>" maxlength="5" style="width:60px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:14px;">Abreviação: </text><input type="text" id="editarUC_abreviacao" value="<?php echo $abreviacao_uc; ?>" maxlength="5"; style="width:70px;"></input>
	<br>
	<br>
	<text style="font-weight:500; margin-right:61px;">Área: </text><select id="editarUC_area" style="width:100px;">
	<?php
		$statement4 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
		$statement4->execute();
		$resultado4 = $statement4->get_result();
		$linha4 = mysqli_fetch_assoc($resultado4);
			$nome_area = $linha4["nome"];
			echo "<option value='$id_area'>$nome_area</option>";
			
			echo "<option value=''></option>";
			
			$statement5 = mysqli_prepare($conn, "SELECT id_area, nome FROM area WHERE id_area != $id_area AND id_utc = $id_utc_curso ORDER BY nome;");
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
	<text style="font-weight:500; margin-right:5px;">Responsável: </text><select id="editarUC_responsavel" style="width:200px;">
	<?php
		$statement6 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_responsavel;");
		$statement6->execute();
		$resultado6 = $statement6->get_result();
		$linha6 = mysqli_fetch_assoc($resultado6);
			$nome_docente = $linha6["nome"];
			echo "<option value='$id_responsavel'>$nome_docente</option>";
		
			echo "<option value=''></option>";
			echo "<option value=''>            ($nome_utc_curso)</option>";
		
			$statement7 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc = $id_utc_curso AND id_utilizador != $id_responsavel ORDER BY nome;");
			$statement7->execute();
			$resultado7 = $statement7->get_result();
			while($linha7 = mysqli_fetch_assoc($resultado7)){
				$id_docente = $linha7["id_utilizador"];
				$nome_docente = $linha7["nome"];
				echo "<option value='$id_docente'>$nome_docente</option>";
			}
			
			echo "<option value=''></option>";
			echo "<option value=''>               (Outros)</option>";
			
			$statement8 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc != $id_utc_curso ORDER BY nome;");
			$statement8->execute();
			$resultado8 = $statement8->get_result();
			while($linha8 = mysqli_fetch_assoc($resultado8)){
				$id_docente = $linha8["id_utilizador"];
				$nome_docente = $linha8["nome"];
				echo "<option value='$id_docente'>$nome_docente</option>";
			}
	?>
	</select>
	<br>
	<br>
	<?php
	$statement9 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM componente WHERE id_disciplina = $id_uc;");
	$statement9->execute();
	$resultado9 = $statement9->get_result();
	$linha9 = mysqli_fetch_assoc($resultado9);
		$numero_componentes = $linha9["COUNT(DISTINCT id_componente)"];
	?>
	<div style="height:105px; overflow:auto;">
	<text style="font-weight:500; margin-right:5px;">Componentes (<?php echo $numero_componentes ?>) : </text><br>
		<?php
		$statement10 = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_uc;");
		$statement10->execute();
		$resultado10 = $statement10->get_result();
		while($linha10 = mysqli_fetch_assoc($resultado10)){
			$id_componente = $linha10["id_componente"];
			
			$statement11 = mysqli_prepare($conn, "SELECT tc.nome_tipocomponente, tc.sigla_tipocomponente FROM tipo_componente tc INNER JOIN componente c ON tc.id_tipocomponente = c.id_tipocomponente WHERE c.id_componente = $id_componente;");
			$statement11->execute();
			$resultado11 = $statement11->get_result();
			$linha11 = mysqli_fetch_assoc($resultado11);
				$nome_tipocomponente = $linha11["nome_tipocomponente"];
				$sigla_tipocomponente = $linha11["sigla_tipocomponente"];
				
				echo "<text style='margin-left:15px; line-height:15px;'><i>$nome_tipocomponente ($sigla_tipocomponente)</i></text><br>";
		}
		?>
		</div>
	</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="atualizarUC(<?php echo $id_uc; ?>,'<?php echo $nome_uc; ?>','<?php echo $codigo_uc; ?>','<?php echo $abreviacao_uc; ?>',<?php echo $id_area; ?>,<?php echo $id_responsavel; ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atualizar</b>
    </button>
</div>