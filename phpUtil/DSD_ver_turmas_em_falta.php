<?php
// Formulário de criação de junções

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');

$id_componente = $_GET["id_componente"];

$statement = mysqli_prepare($conn, "SELECT tc.nome_tipocomponente, c.numero_horas, c.id_disciplina FROM tipo_componente tc
									INNER JOIN componente c ON tc.id_tipocomponente = c.id_tipocomponente
									WHERE c.id_componente = $id_componente;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$nome_componente = $linha["nome_tipocomponente"];
	$numero_horas = $linha["numero_horas"];
	$id_disciplina = $linha["id_disciplina"];

$statement1 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$nome_disciplina = $linha1["abreviacao_uc"];
?>
<div class="modal-body" id="manipular_turmas_turmas">
	<b><?php echo "<i class='material-icons' style='vertical-align:middle; margin-left:-17px; margin-right:3px; margin-bottom:3px;'>class</i>", $nome_disciplina, " - </b>", $nome_componente, " (", $numero_horas, "H)"; ?>
	<br><br>
	<?php
	
		$num_turmas_total = 0;
		$num_turmas_numa_juncao = 0;
		
		$turmas_contabilizadas = array();
		
		$statement2 = mysqli_prepare($conn, "SELECT id_turma FROM aula WHERE id_componente = $id_componente AND id_docente IS NULL;");
		$statement2->execute();
		$resultado2 = $statement2->get_result();
		while($linha2 = mysqli_fetch_assoc($resultado2)){
			$id_turma = $linha2["id_turma"];
			
			if(!in_array($id_turma,$turmas_contabilizadas)){
			
			$num_turmas_total += 1;
			
			$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma AND id_juncao IS NOT NULL;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$num_juncoes_turma = $linha3["COUNT(DISTINCT id_juncao)"];
											 
				$statement4 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma");
				$statement4->execute();
				$resultado4 = $statement4->get_result();
				$linha4 = mysqli_fetch_assoc($resultado4);
					$nomeTurma = $linha4["nome"];
					$anoTurma = $linha4["ano"];
					$semTurma = $linha4["semestre"];
										
					if($num_juncoes_turma > 0){
						$num_turmas_numa_juncao += 1;
						$statement5 = mysqli_prepare($conn, "SELECT id_juncao FROM aula WHERE id_componente = $id_componente AND id_turma = $id_turma;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
						$id_juncao = $linha5["id_juncao"];
						
						$statement56 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao;");
						$statement56->execute();
						$resultado56 = $statement56->get_result();
						$linha56 = mysqli_fetch_assoc($resultado56);
							$num_turmas_total_juncao = $linha56["COUNT(DISTINCT id_turma)"];
									
						echo "<div id='div_$id_juncao'>";
						echo "<input type='checkbox' data_id-componente='$id_componente' data_id-juncao='$id_juncao' onclick='esconderOutrasJuncoes_mais_turmas($id_juncao)' style='margin-right:5px;'>";
						echo "<i class='material-icons' style='vertical-align:middle; margin-bottom:3px; margin-right:5px;'>people</i>";
									
						$counter_turma_juncao = 0;
						$statement6 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_componente AND id_juncao = $id_juncao;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						while($linha6 = mysqli_fetch_assoc($resultado6)){
							$id_turma_temp = $linha6["id_turma"];
							
							$counter_turma_juncao += 1;
							
								$statement7 = mysqli_prepare($conn, "SELECT nome, ano, semestre FROM turma WHERE id_turma = $id_turma_temp");
								$statement7->execute();
								$resultado7 = $statement7->get_result();
								$linha7 = mysqli_fetch_assoc($resultado7);
									$nomeTurma = $linha7["nome"];
									$anoTurma = $linha7["ano"];
									$semTurma = $linha7["semestre"];
								
									echo "<text data_id-turma='$id_turma_temp'><b>", $nomeTurma, "</b></text> ";
									array_push($turmas_contabilizadas,$id_turma_temp);
							
						}
						
						if($counter_turma_juncao != $num_turmas_total_juncao){
							echo "(...) ";
						}
						echo "<br>";
						echo "</div>";
							
	/*											
						$statement6 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
						$statement6->execute();
						$resultado6 = $statement6->get_result();
						$linha6 = mysqli_fetch_assoc($resultado6);
							$num_componentes_diferentes_juncao = $linha6["COUNT(DISTINCT id_componente)"];
											
							echo "<input type='checkbox' data_id-componente='$id_componente' data_id-turma='$id_turma' data_id-juncao='$id_juncao' onclick='esconderOutrasJuncoes_mais_turmas($id_turma)' style='margin-right:5px;'>";
							echo "<i class='material-icons' style='vertical-align:middle; margin-bottom:3px; margin-right:5px;'>people</i><b>", $nomeTurma, "</b> (", $anoTurma, "ºA/", $semTurma, "ºS)";
							
							if($num_componentes_diferentes_juncao == 1){
								echo "<img src='http://localhost/apoio_utc/images/join.png' title='Esta turma está numa junção' style='width:15px; height:15px; margin-left:5px;'> <br>";
							}
							else{
								"<img src='http://localhost/apoio_utc/images/join_laranja.png' title='Esta turma está numa junção com turmas de diferentes componentes/UCs' style='width:15px; height:15px; margin-left:5px;'> <br>";
							} */
						} 
						else{
							echo "<input type='checkbox' data_id-componente='$id_componente' data_id-turma='$id_turma' style='margin-right:5px;'>";
							echo "<i class='material-icons' style='vertical-align:middle; margin-bottom:3px; margin-right:5px;'>people</i><b>", $nomeTurma, "</b><br>";
							array_push($turmas_contabilizadas,$id_turma);
					}
			}
			
		}
		
		if(($num_turmas_numa_juncao == 0 && $num_turmas_total > 1) || ($num_turmas_numa_juncao > 0 && $num_turmas_total > $num_turmas_numa_juncao)){
			echo "<br>";
			echo "<input type='checkbox' id='atribuirTurmas_em_falta_juntar' data_juntar='1' style='margin-left:65px; margin-right:5px;'><b>Juntar</b>";
		}
	?>
	
</div>
<div class="modal-footer">
    <button type="button" onclick="atribuirTurmas_em_falta(<?php echo $id_componente ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atribuir Turmas</b>
    </button>
</div>