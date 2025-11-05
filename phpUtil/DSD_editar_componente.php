<?php
// Formulário de criação de junções

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');
 
$id_componente = $_GET["id_componente"];
$id_docente = $_GET["id_docente"];

//Ver a área e UTC da disciplina
$statement00 = mysqli_prepare($conn, "SELECT d.id_area, d.id_curso FROM disciplina d INNER JOIN componente c ON 
										d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente;");
$statement00->execute();
$resultado00 = $statement00->get_result();
$linha00 = mysqli_fetch_assoc($resultado00);
	$id_area = $linha00["id_area"];
	$id_curso = $linha00["id_curso"];
	
	$statement01 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
	$statement01->execute();
	$resultado01 = $statement01->get_result();
	$linha01 = mysqli_fetch_assoc($resultado01);
		$id_utc = $linha01["id_utc"];
		
	$statement02 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
	$statement02->execute();
	$resultado02 = $statement02->get_result();
	$linha02 = mysqli_fetch_assoc($resultado02);
		$nome_docente_original = $linha02["nome"];
		
	$statement03 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
	$statement03->execute();
	$resultado03 = $statement03->get_result();
	$linha03 = mysqli_fetch_assoc($resultado03);
		$nome_area = $linha03["nome"];
		
	$statement04 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc;");
	$statement04->execute();
	$resultado04 = $statement04->get_result();
	$linha04 = mysqli_fetch_assoc($resultado04);
		$nome_utc = $linha04["nome_utc"];

?>
<div class="modal-body_1"> 
    <div class="card-body">
	<h6 align="center">Docente</h6>
	<select style='width:200px; margin-left:15px;'id='alterar_docente_componente' name='alterar_docente_componente'">
		<option value="nada_selecionado"><?php echo $nome_docente_original ?></option>
		<option value="nada_selecionado"></option>
		<option value="nada_selecionado">----------<?php echo $nome_area ?>----------</option>
		<?php 
			$statement = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $id_docente AND id_area = $id_area AND id_utc = $id_utc ORDER BY nome;");
			$statement->execute();
			$resultado = $statement->get_result();
			while($linha = mysqli_fetch_assoc($resultado)){
				$id_utilizador = $linha["id_utilizador"];
				$nome = $linha["nome"];
				echo "<option value='$id_utilizador'>$nome</option>";	
			}
			
			echo "<option value='nada_selecionado'></option>";	
			echo "<option value='nada_selecionado'>--------$nome_utc--------</option>";
			
			$statement03 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $id_docente AND id_area != $id_area AND id_utc = $id_utc ORDER BY nome;");
			$statement03->execute();
			$resultado03 = $statement03->get_result();
			while($linha03 = mysqli_fetch_assoc($resultado03)){
				$id_utilizador = $linha03["id_utilizador"];
				$nome = $linha03["nome"];
				echo "<option value='$id_utilizador'>$nome</option>";	
			}
			
			echo "<option value='nada_selecionado'></option>";	
			echo "<option value='nada_selecionado'>----------Outros----------</option>";
			
			$statement04 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $id_docente AND id_area != $id_area AND id_utc != $id_utc ORDER BY nome;");
			$statement04->execute();
			$resultado04 = $statement04->get_result();
			while($linha04 = mysqli_fetch_assoc($resultado04)){
				$id_utilizador = $linha04["id_utilizador"];
				$nome = $linha04["nome"];
				echo "<option value='$id_utilizador'>$nome</option>";	
			}
			
		?>
	</select>
	<br>
	
	<?php
			$temp = 0;
	$statement1 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_docente = $id_docente;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$num_juncoes_comp_docente = $linha1["COUNT(DISTINCT id_juncao)"];
		if($num_juncoes_comp_docente > 0){
			
			
			$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_componente AND id_docente = $id_docente AND id_juncao IS NOT NULL;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$id_juncao = $linha2["id_juncao"];
				
				$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				$linha3 = mysqli_fetch_assoc($resultado3);
					$num_componentes_diferentes_juncao = $linha3["COUNT(DISTINCT id_componente)"];
					
					if($num_componentes_diferentes_juncao >= 2){
						$temp = 1;
					}
			}
		}
		if($temp == 1){
	?>
		<div class="atribuir_docente_ja_juncao" id="atribuir_docente_ja_juncao" style="visibility:visible; margin-top:10px;">
				<img src="http://localhost/apoio_utc/images/warning.jpg" width="20" height="20">
				<text style="font-size:13px; font-family:comic_sans;"><b>Há turmas juntas com turmas de outras componentes. Ao alterar o docente aqui vai alterar também nessas outras turmas.</b></text>
			</div>
		<?php } ?>
	</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="alterarDocenteComponente(<?php echo $id_componente ?>, <?php echo $id_docente ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
		Alterar Docente
    </button>
</div>