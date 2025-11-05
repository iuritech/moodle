<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utc = $_GET["id_utc"];

?>
<div id="verCursosTodos" class="modal-body" style="height:380px; overflow:auto;">
<?php
	$statement = mysqli_prepare($conn, "SELECT nome, sigla FROM curso WHERE id_utc = $id_utc ORDER BY nome;");
	$statement->execute();
	$resultado = $statement->get_result();
	while($linha = mysqli_fetch_assoc($resultado)){
		$nome_curso = $linha["nome"];
		$sigla_curso = $linha["sigla"];
							
		if(strlen($nome_curso) > 55){
			$temp = explode(" ",$nome_curso);
			$nome_curso = substr($nome_curso,0,1) . ". " . $temp[sizeof($temp) - 1];
		}				
						
		echo "<div style='margin-bottom:-15px; font-size:17px;'>";
		
		echo "<text style='font-weight:500;'><i class='material-icons' style='vertical-align:middle;'>school</i> ", $sigla_curso, "</text> - ", $nome_curso,"<br>";
		
		echo "</div>";
		echo "<br>";
	}

?>
</div>