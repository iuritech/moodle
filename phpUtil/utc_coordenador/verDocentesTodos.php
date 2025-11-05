<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utc = $_GET["id_utc"];
$id_utilizador = $_GET["id_utilizador"];

?>
<div id="verDocentesTodos" class="modal-body" style="height:480px; overflow:auto;">
<?php
	
	$statement4 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utc = $id_utc ORDER BY nome;");
	$statement4->execute();
	$resultado4 = $statement4->get_result();
	while($linha4 = mysqli_fetch_assoc($resultado4)){
		$id_docente = $linha4["id_utilizador"];
		$nome_docente = $linha4["nome"];
		$imagem_docente = $linha4["imagem_perfil"];
		$id_area_docente = $linha4["id_area"];
		$id_funcao = $linha4["id_funcao"];
		$is_admin = $linha4["is_admin"];
							
		if(strlen($nome_docente) > 16){
			$temp = explode(" ",$nome_docente);
			$nome_docente = substr($nome_docente,0,1) . ". " . $temp[sizeof($temp) - 1];
		}							
						
		$funcao = "";
		if($id_funcao == 4){
			$funcao = "**";
		}
		else if($id_funcao == 5){
			$funcao = "*";
		}
		else if($id_funcao == 6){
			$funcao = "***";
		}
						
		echo "<div style='margin-bottom:-15px; font-size:17px;'>";
		
		if($is_admin == 1){
			echo "<img src='$imagem_docente' style='width:35px; height:35px; margin-right:4px; border-radius:50%; border:1px solid #000000;'><a href='javascript:void(0)' data-toggle='modal' data-target='#editarDocente' onclick='janelaEditarDocente($id_docente)'>$nome_docente</a> $funcao <b><i>(admin)</i></b>";
		}
		else if($id_docente == $id_utilizador){
			echo "<img src='$imagem_docente' style='width:35px; height:35px; margin-right:4px; border-radius:50%; border:1px solid #000000;'><a href='javascript:void(0)' data-toggle='modal' data-target='#editarDocente' onclick='janelaEditarDocente($id_docente)'><b>$nome_docente</a> $funcao</b>";
		}
		else{
			echo "<img src='$imagem_docente' style='width:35px; height:35px; margin-right:4px; border-radius:50%; border:1px solid #000000;'><a href='javascript:void(0)' data-toggle='modal' data-target='#editarDocente' onclick='janelaEditarDocente($id_docente)'>$nome_docente</a> $funcao ";
		}
		echo "</div>";
		echo "<br>";
	}

?>
</div>