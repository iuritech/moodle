<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_area = $_GET["id_area"];

$statement = mysqli_prepare($conn, "SELECT id_utc FROM area WHERE id_area = $id_area;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc = $linha["id_utc"];

$statement1 = mysqli_prepare($conn, "SELECT id_area, nome FROM area WHERE id_utc = $id_utc AND id_area != $id_area ORDER BY nome;");
$statement1->execute();
$resultado1 = $statement1->get_result();
?>
<div class="modal-body">
	<text style="font-weight:500">Unidade Curricular: </text><br>
	<select id="select_outras_ucs" style='width:250px;'>
	<?php
		while($linha1 = mysqli_fetch_assoc($resultado1)){
			$id_area_temp = $linha1["id_area"];
			$nome_area_temp = $linha1["nome"];
			
			echo "<option value=''></option>";
			
			echo "<option value=''>           ($nome_area_temp)</option>";
			
			$statement2 = mysqli_prepare($conn, "SELECT id_disciplina, nome_uc, id_curso FROM disciplina WHERE id_area = $id_area_temp ORDER BY id_curso, nome_uc;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$id_disciplina = $linha2["id_disciplina"];
				$nome_disciplina = $linha2["nome_uc"];
				$id_curso = $linha2["id_curso"];
				
				$statement3 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				$linha3 = mysqli_fetch_assoc($resultado3);
					$sigla_curso = $linha3["sigla"];
			
					echo "<option value='$id_disciplina'>$sigla_curso - $nome_disciplina</option>";
			}
		}
	?>
	</select>
</div>
<div class="modal-footer">
    <button type="button" onclick="atribuirOutraUC(<?php echo $id_area; ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atribuir</b>
    </button>
</div>