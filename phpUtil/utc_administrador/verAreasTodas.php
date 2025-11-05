<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');
include('../../bd_final.php');

$id_utc = $_GET["id_utc"];

?>
<div id="verAreasTodas" class="modal-body" style="height:315px; overflow:auto;">
<?php

	$statement = mysqli_prepare($conn, "SELECT nome, nome_completo FROM area WHERE id_utc = $id_utc ORDER BY nome;");
	$statement->execute();
	$resultado = $statement->get_result();
	while($linha = mysqli_fetch_assoc($resultado)){
		$nome_area = $linha["nome"];
		$nome_completo_area = $linha["nome_completo"];
							
		if(strlen($nome_completo_area) > 75){
			$temp = explode(" ",$nome_completo_area);
			$nome_completo_area = substr($nome_completo_area,0,1) . ". " . $temp[sizeof($temp) - 1];
		}				
						
		echo "<div style='margin-bottom:-15px; font-size:17px;'>";
		
		echo "<text style='font-weight:500;'><i class='material-icons' style='vertical-align:middle;'>monitor</i> ", $nome_area, "</text> - ", $nome_completo_area, "<br>";
		
		echo "</div>";
		echo "<br>";
	}

?>
</div>