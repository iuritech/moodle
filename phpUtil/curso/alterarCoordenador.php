<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_curso = $_GET["id_curso"];
$id_coordenador_atual = $_GET["id_coordenador_atual"];

$statement = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_coordenador_atual;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_coordenador_atual = $linha["nome"];
	
	$statement1 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$id_utc_curso = $linha1["id_utc"];
	
		$statement2 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_curso;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_assoc($resultado2);
			$nome_utc_curso = $linha2["nome_utc"];
	
		$statement3 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $id_coordenador_atual AND id_utc = $id_utc_curso AND is_admin = 0 ORDER BY nome;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		while($linha3 = mysqli_fetch_assoc($resultado3)){
			$id_docente = $linha3["id_utilizador"];
			$nome_docente = $linha3["nome"];
		}

?>
<div class="modal-body">
	<select id="alterarCoordenador" style="width:200px;" onchange="alterarCoordenador()">
		<?php
			echo "<option value='$id_coordenador_atual'>$nome_coordenador_atual</option>";
			echo "<option value=''></option>";
			echo "<option value=''>                ($nome_utc_curso)</option>";
			
			$statement4 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $id_coordenador_atual AND id_utc = $id_utc_curso ORDER BY nome;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			while($linha4 = mysqli_fetch_assoc($resultado4)){
				$id_docente = $linha4["id_utilizador"];
				$nome_docente = $linha4["nome"];
				echo "<option value='$id_docente'>$nome_docente</option>";
			}
			
			echo "<option value=''></option>";
			echo "<option value=''>                   (Outros)</option>";
			$statement5 = mysqli_prepare($conn, "SELECT id_utilizador, nome, id_utc FROM utilizador WHERE id_utilizador != $id_coordenador_atual AND id_utc != $id_utc_curso ORDER BY id_utc, nome;");
			$statement5->execute();
			$resultado5 = $statement5->get_result();
			while($linha5 = mysqli_fetch_assoc($resultado5)){
				$id_docente = $linha5["id_utilizador"];
				$nome_docente = $linha5["nome"];
				$id_utc_docente = $linha5["id_utc"];
				
				$statement6 = mysqli_prepare($conn, "SELECT sigla_utc FROM utc WHERE id_utc = $id_utc_docente;");
				$statement6->execute();
				$resultado6 = $statement6->get_result();
				$linha6 = mysqli_fetch_assoc($resultado6);
					$sigla_utc_docente = $linha6["sigla_utc"];
				
				echo "<option value='$id_docente'>$sigla_utc_docente - $nome_docente</option>";
			}
		?>
		
	</select>
</div>