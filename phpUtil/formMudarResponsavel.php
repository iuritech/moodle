<?php
// Formulário de criação de utilizadores

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');

$id_responsavel = $_GET["id_responsavel"];
$id_disciplina = $_GET["id_disciplina"];

$statement0 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_responsavel;");
$statement0->execute();
$resultado0 = $statement0->get_result();
$linha0 = mysqli_fetch_assoc($resultado0);
	$nome_responsavel = $linha0["nome"];

//Ir buscar a àrea e UTC da disciplina
$statement = mysqli_prepare($conn, "SELECT id_area, id_curso FROM disciplina WHERE id_disciplina = $id_disciplina");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_area_disciplina = $linha["id_area"];
	$id_curso_disciplina = $linha["id_curso"];
	
	$statement1 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area_disciplina;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_area = $linha1["nome"];
		
	$statement2 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso_disciplina;");
	$statement2->execute();
	$resultado2 = $statement2->get_result();
	$linha2 = mysqli_fetch_assoc($resultado2);
		$id_utc_disciplina = $linha2["id_utc"];
		
	$statement3 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_disciplina;");
	$statement3->execute();
	$resultado3 = $statement3->get_result();
	$linha3 = mysqli_fetch_assoc($resultado3);
		$nome_utc = $linha3["nome_utc"];
?>
<div class="modal-body_1"> 
    <div class="card-body">
        <div class="form-group row">
		<h6>Docente</h6>
		<select id="edDSUC_mudar_responsavel" style="width:200px;">
			<option value='nada_selecionado'><?php echo $nome_responsavel ?></option>	
			<option value='nada_selecionado'></option>	
			<option value='nada_selecionado'>--------><?php echo $nome_area ?></option>
			<?php 
				$utilizadores_contabilizados = array();
				$statement2 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador != $id_responsavel AND 
													id_area = $id_area_disciplina AND id_utc = $id_utc_disciplina ORDER BY nome;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				while($linha2 = mysqli_fetch_assoc($resultado2)){
					$id_utilizador = $linha2["id_utilizador"];
					$nome_utilizador = $linha2["nome"];
					
					echo "<option value='" . $id_utilizador . "'>" . $nome_utilizador . "</option>";
					array_push($utilizadores_contabilizados,$id_utilizador);
				}
			?>
			
			<option value='nada_selecionado'></option>	
			<option value='nada_selecionado'>--------><?php echo $nome_utc ?></option>
			
			<?php 
				$statement3 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador != $id_responsavel AND
													id_area != $id_area_disciplina AND id_utc = $id_utc_disciplina ORDER BY nome;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				while($linha3 = mysqli_fetch_assoc($resultado3)){
					$id_utilizador = $linha3["id_utilizador"];
					$nome_utilizador = $linha3["nome"];
					
					echo "<option value='" . $id_utilizador . "'>" . $nome_utilizador . "</option>";
					array_push($utilizadores_contabilizados,$id_utilizador);
				}
			?>
			
			<option value='nada_selecionado'></option>	
			<option value='nada_selecionado'>--------Outros--------</option>
			
			<?php 
				$statement4 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador != $id_responsavel AND
													id_area != $id_area_disciplina AND id_utc != $id_utc_disciplina ORDER BY nome;");
				$statement4->execute();
				$resultado4 = $statement4->get_result();
				while($linha4 = mysqli_fetch_assoc($resultado4)){
					$id_utilizador = $linha4["id_utilizador"];
					$nome_utilizador = $linha4["nome"];
					
					echo "<option value='" . $id_utilizador . "'>" . $nome_utilizador . "</option>";
					array_push($utilizadores_contabilizados,$id_utilizador);
				}
			?>
			
		</select>
		</div>
	</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="mudarResponsavel()" class="btn btn-light btn-lg" style="border-radius:50px;">
		Atribuir
    </button>
</div>