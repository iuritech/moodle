<?php
// Formulário de criação de junções

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
	$nome_disciplina = $linha["nome_uc"];
	$id_responsavel = $linha["id_responsavel"];
	$id_area = $linha["id_area"];
	
	$statement1 = mysqli_prepare($conn, "SELECT nome, imagem_perfil, id_area FROM utilizador WHERE id_utilizador = $id_responsavel;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
		$nome_responsavel = $linha1["nome"];
		$imagem_perfil = $linha1["imagem_perfil"];
		$id_area_responsavel = $linha1["id_area"];
		
		$statement2 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area_responsavel;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_assoc($resultado2);
			$nome_area_responsavel = $linha2["nome"];
			
			$statement3 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$nome_area_uc = $linha3["nome"];

?>
<div class="modal-body">
	<span style="line-height:2.5">
		<text style="font-weight:500;">Responsável: </text><img src="<?php echo $imagem_perfil; ?>" style="width:35px; heigh:35px; margin-left:10px; margin-right:5px; border-radius:50%; border:1px solid #212529;"><?php echo $nome_responsavel; ?><i> (<?php echo $nome_area_responsavel; ?>)</i>
	</span>
	<br>
	<span style="line-height:2.5">
		<text style="font-weight:500;">Área: </text><i><?php echo $nome_area_uc; ?></i>
	</span>
	<br>
	<span style="line-height:2.5">
		<text style="font-weight:500;">Componentes: </text>
	</span>
	<br>
	<?php 
	$statement4 = mysqli_prepare($conn, "SELECT DISTINCT id_componente, id_tipocomponente, numero_horas FROM componente WHERE id_disciplina = $id_uc ORDER BY id_tipocomponente;");
	$statement4->execute();
	$resultado4 = $statement4->get_result();
	while($linha4 = mysqli_fetch_assoc($resultado4)){
		$id_componente = $linha4["id_componente"];
		$id_tipocomponente = $linha4["id_tipocomponente"];
		$numero_horas = $linha4["numero_horas"];
					
		$statement5 = mysqli_prepare($conn, "SELECT nome_tipocomponente, sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
		$statement5->execute();
		$resultado5 = $statement5->get_result();
		$linha5 = mysqli_fetch_assoc($resultado5);
			$nome_tipocomponente = $linha5["nome_tipocomponente"];
			$sigla_tipocomponente = $linha5["sigla_tipocomponente"];
						
			echo "<text style='margin-left:15px;'>", $nome_tipocomponente, "(", $sigla_tipocomponente, "): ", $numero_horas,"H</text><br>";
		}
	?>
</div>