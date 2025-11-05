<?php
// Formulário de criação de junções

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');
?>
<div class="modal-body" style='overflow:auto;'>
	<h6 align="center" style="margin-bottom:10px;">Turmas Junção</h6>
	<?php $id_juncao = $_GET["id"];
	
	$statement1 = mysqli_prepare($conn, "SELECT t.nome, t.ano, t.semestre, t.id_curso, a.id_turma, a.id_componente FROM turma t INNER JOIN
										 aula a ON t.id_turma = a.id_turma WHERE a.id_juncao = $id_juncao ORDER BY t.nome;");
	$statement1->execute();
	$resultado1 = $statement1->get_result();
	while($linha1 = mysqli_fetch_array($resultado1)){
						
		$id_turma = $linha1['id_turma'];
		$nome_turma = $linha1['nome'];
		$ano_turma = $linha1['ano'];
		$sem_turma = $linha1['semestre'];
		$id_curso = $linha1['id_curso'];
		$id_componente = $linha1['id_componente'];
						
		$statement2 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		$linha2 = mysqli_fetch_array($resultado2);
							
		$sigla_curso = $linha2['sigla'];
					
		$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
		$statement3->execute();
		$resultado3 = $statement3->get_result();
		$linha3 = mysqli_fetch_array($resultado3);
	
		$id_disciplina = $linha3["id_disciplina"];
		$id_tipocomponente = $linha3["id_tipocomponente"];
						
		$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
		$statement4->execute();
		$resultado4 = $statement4->get_result();
		$linha4 = mysqli_fetch_array($resultado4);
			
		$sigla_disciplina = $linha4["abreviacao_uc"];
							
		$statement5 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
		$statement5->execute();
		$resultado5 = $statement5->get_result();
		$linha5 = mysqli_fetch_array($resultado5);
		
		$sigla_tipocomponente = $linha5["sigla_tipocomponente"];
							
		echo "<i class='material-icons' style='vertical-align:middle;'>people</i><text style='margin-left:5px;'><b>", $nome_turma, "</b> - ", $sigla_curso, " (", $sigla_disciplina, "-", $sigla_tipocomponente, ") (", $ano_turma, "ºA/", $sem_turma, "ºS)</text><br>";
							
	}	?>
</div>