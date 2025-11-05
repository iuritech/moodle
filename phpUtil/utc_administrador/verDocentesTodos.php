<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utc = $_GET["id_utc"];
$id_utilizador = $_SESSION["id"];

$statement = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_coordenador_utc = $linha["id_responsavel"];

?>
<div id="verDocentesTodos" class="modal-body" style="height:480px; overflow:auto;">
<?php
	$statement2 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utc = $id_utc ORDER BY nome;");
	$statement2->execute();
	$resultado2 = $statement2->get_result();
	while($linha2 = mysqli_fetch_assoc($resultado2)){
		$id_docente = $linha2["id_utilizador"];
		$nome_docente = $linha2["nome"];
		$imagem_docente = $linha2["imagem_perfil"];
		$id_area_docente = $linha2["id_area"];
		$id_funcao = $linha2["id_funcao"];
		$is_admin = $linha2["is_admin"];
							
		if(strlen($nome_docente) > 16){
			$temp = explode(" ",$nome_docente);
			$nome_docente = substr($nome_docente,0,1) . ". " . $temp[sizeof($temp) - 1];
		}				
						
		echo "<div style='margin-bottom:-15px; font-size:17px;'>";
		
		if($is_admin == 1){
			echo "<img src='$imagem_docente' style='width:35px; height:35px; margin-right:4px; border-radius:50%; border:1px solid #000000;'><text style='font-weight:450;'>$nome_docente<b></text><i> (admin)</i></b>";
		}
		else if($id_docente == $id_coordenador_utc){
			echo "<img src='$imagem_docente' style='width:35px; height:35px; margin-right:4px; border-radius:50%; border:1px solid #000000;'><text style='font-weight:450;'><b>$nome_docente</b></text>";
		}
		else{
			echo "<img src='$imagem_docente' style='width:35px; height:35px; margin-right:4px; border-radius:50%; border:1px solid #000000;'><text style='font-weight:450;'>$nome_docente</text>";
		}
		echo "</div>";
		echo "<br>";
	}

?>
</div>