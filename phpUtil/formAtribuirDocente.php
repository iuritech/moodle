<?php
// Formulário de criação de utilizadores

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');

$id_area_utilizador = (int) $_SESSION["area_utilizador"];
$id_utc_utilizador = (int) $_SESSION["utc_utilizador"];

$docente_ja_na_tabela = $_GET["id"];
$id_comp = $_GET["comp"];
$id_turma = $_GET["turma"];
$id_turma_incial = $id_turma;
$id_comp_inicial = $id_comp;
$id_turma_original = $_GET["turma"];
$id_juncao = $_GET["juncao"];

$statement40 = mysqli_prepare($conn, "SELECT id_curso FROM turma WHERE id_turma = $id_turma_incial;");
$statement40->execute();
$resultado40 = $statement40->get_result();
$linha40 = mysqli_fetch_assoc($resultado40);
	$id_curso_final = $linha40["id_curso"];
	
$statement41 = mysqli_prepare($conn, "SELECT id_disciplina FROM componente WHERE id_componente = $id_comp;");
$statement41->execute();
$resultado41 = $statement41->get_result();
$linha41 = mysqli_fetch_assoc($resultado41);
	$id_disciplina = $linha41["id_disciplina"];
	
$statement42 = mysqli_prepare($conn, "SELECT id_area FROM disciplina WHERE id_disciplina = $id_disciplina;");
$statement42->execute();
$resultado42 = $statement42->get_result();
$linha42 = mysqli_fetch_assoc($resultado42);
	$id_area = $linha42["id_area"];
	
$statement43 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $id_area;");
$statement43->execute();
$resultado43 = $statement43->get_result();
$linha43 = mysqli_fetch_assoc($resultado43);
	$nome_area = $linha43["nome"];
	
$statement44 = mysqli_prepare($conn, "SELECT id_curso FROM disciplina WHERE id_disciplina = $id_disciplina;");
$statement44->execute();
$resultado44 = $statement44->get_result();
$linha44 = mysqli_fetch_assoc($resultado44);
	$id_curso = $linha44["id_curso"];
	
$statement45 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
$statement45->execute();
$resultado45 = $statement45->get_result();
$linha45 = mysqli_fetch_assoc($resultado45);
	$id_utc = $linha45["id_utc"];	
	
$statement46 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc;");
$statement46->execute();
$resultado46 = $statement46->get_result();
$linha46 = mysqli_fetch_assoc($resultado46);
	$nome_utc = $linha46["nome_utc"];
?>
<div class="modal-body_1"> 
    <div class="card-body">
        <div class="form-group row">
		<h6>Docente</h6>
		<select id="edDSUCatribuirDocente" style="width:200px;">
			<?php echo $docente_ja_na_tabela, " / ", $id_comp, " / ", $id_turma ?>
			<?php 
			if($docente_ja_na_tabela != 0){
				
				$statement00 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $docente_ja_na_tabela;");
				$statement00->execute();
				$resultado00 = $statement00->get_result();
				$linha00 = mysqli_fetch_assoc($resultado00);
					$nomeDocente = $linha00["nome"];
				
					echo "<option value='$docente_ja_na_tabela'>$nomeDocente</option>";
					echo "<option value='0'></option>";
					echo "<option value='0'>-------->$nome_area</option>";
					
				
				$statement = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $docente_ja_na_tabela AND id_area = $id_area AND id_utc = $id_utc ORDER BY nome;");
				$statement->execute();
				$resultado = $statement->get_result();
				while($linha = mysqli_fetch_assoc($resultado)){
					$id_utilizador = $linha["id_utilizador"];
					$nome = $linha["nome"];
					echo "<option value='$id_utilizador'>$nome</option>";	
				}
				
				echo "<option value='0'></option>";
				echo "<option value='0'>-------->$nome_utc</option>";
				
				$statement = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $docente_ja_na_tabela AND id_area != $id_utc AND id_utc = $id_utc_utilizador ORDER BY nome;");
				$statement->execute();
				$resultado = $statement->get_result();
				while($linha = mysqli_fetch_assoc($resultado)){
					$id_utilizador = $linha["id_utilizador"];
					$nome = $linha["nome"];
					echo "<option value='$id_utilizador'>$nome</option>";	
				}
				
				echo "<option value='0'></option>";
				echo "<option value='0'>-------->Restantes</option>";
				
				$statement = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $docente_ja_na_tabela AND id_area != $id_area AND id_utc != $id_utc ORDER BY nome;");
				$statement->execute();
				$resultado = $statement->get_result();
				while($linha = mysqli_fetch_assoc($resultado)){
					$id_utilizador = $linha["id_utilizador"];
					$nome = $linha["nome"];
					echo "<option value='$id_utilizador'>$nome</option>";	
				}
				
			}
			else{
				
				echo "<option value='0'></option>";
				echo "<option value='0'>-------->$nome_area</option>";
					
				
				$statement = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $docente_ja_na_tabela AND id_area = $id_area AND id_utc = $id_utc ORDER BY nome;");
				$statement->execute();
				$resultado = $statement->get_result();
				while($linha = mysqli_fetch_assoc($resultado)){
					$id_utilizador = $linha["id_utilizador"];
					$nome = $linha["nome"];
					echo "<option value='$id_utilizador'>$nome</option>";	
				}
				
				echo "<option value='0'></option>";
				echo "<option value='0'>-------->$nome_utc</option>";
				
				$statement = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $docente_ja_na_tabela AND id_area != $id_area AND id_utc = $id_utc ORDER BY nome;");
				$statement->execute();
				$resultado = $statement->get_result();
				while($linha = mysqli_fetch_assoc($resultado)){
					$id_utilizador = $linha["id_utilizador"];
					$nome = $linha["nome"];
					echo "<option value='$id_utilizador'>$nome</option>";	
				}
				
				echo "<option value='0'></option>";
				echo "<option value='0'>-------->Restantes</option>";
				
				$statement = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utilizador != $docente_ja_na_tabela AND id_area != $id_area AND id_utc != $id_utc ORDER BY nome;");
				$statement->execute();
				$resultado = $statement->get_result();
				while($linha = mysqli_fetch_assoc($resultado)){
					$id_utilizador = $linha["id_utilizador"];
					$nome = $linha["nome"];
					echo "<option value='$id_utilizador'>$nome</option>";	
				}
			}
			?>
		</select>
		</div>
		<?php
			$statement2 = mysqli_prepare($conn, "SELECT COUNT(id_juncao) FROM aula WHERE id_componente = $id_comp AND id_turma = $id_turma;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			$linha2 = mysqli_fetch_assoc($resultado2);
			//Esta turma está junta com outras turmas. Ao alterar o docente nesta turma vai afetar as outras.
			$numJuncoes = $linha2["COUNT(id_juncao)"];
			$num_juncoes_inicial = $numJuncoes;
			if($numJuncoes > 0){ ?>
			<div class="atribuir_docente_ja_juncao" id="atribuir_docente_ja_juncao" style="visibility:visible; margin-top:10px;">
				<img src="http://localhost/apoio_utc/images/warning.jpg" width="20" height="20">
				<text style="font-size:13px; font-family:comic_sans;"><b>Esta turma está junta com outras turmas. Ao alterar o docente nesta turma vai afetar as outras.</b></text>
			</div>
		<?php		}
			?>
		<?php if($numJuncoes == 0) { ?>
		<div id='div_juncoes' style="width:100%; margin-left:-10px; margin-top:15px;">
		<h6>Junções</h6>
			<?php 
			
			$statement0 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_turma != $id_turma AND id_juncao IS NULL;");
			$statement0->execute();
			$resultado0 = $statement0->get_result();
			$linha0 = mysqli_fetch_assoc($resultado0);
				$numTurmas = $linha0["COUNT(DISTINCT id_turma)"];
			
			if($id_juncao == 0){
			
			$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp;");
			$statement->execute();
			$resultado = $statement->get_result();
			$linha = mysqli_fetch_assoc($resultado);
				$numJuncoes = $linha["COUNT(DISTINCT id_juncao)"];
				
				if($numJuncoes > 0){
						
					$i = 0;	
						
					$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_juncao IS NOT NULL;");
					$statement2->execute();
					$resultado2 = $statement2->get_result();
					while($linha2 = mysqli_fetch_assoc($resultado2)){
						$id_juncao = $linha2["id_juncao"];
						
						echo "<input type='checkbox' class='checkbox_turmas_a_juntar' data-linha='$i' data-id_juncao='$id_juncao' data-id_turma='0' onclick='bloquearOutrasJuncoes($id_juncao)' style='margin-right:5px;'><b>";
						
						$statement23 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao;");
						$statement23->execute();
						$resultado23 = $statement23->get_result();
						$linha23 = mysqli_fetch_assoc($resultado23);
							$num_turmas_juncao = $linha23["COUNT(DISTINCT id_turma)"];
						
						$counter_t = 0;
						
						$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp AND id_juncao = $id_juncao;");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						while($linha3 = mysqli_fetch_assoc($resultado3)){
							$id_turma = $linha3["id_turma"];
							
							$statement4 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
							$statement4->execute();
							$resultado4 = $statement4->get_result();
							$linha4 = mysqli_fetch_assoc($resultado4);
								$nome_turma = $linha4["nome"];
						
							$counter_t += 1;
							
							echo $nome_turma, " ";
							
						}
						
						if($counter_t != $num_turmas_juncao){
							echo " ...";
						}
						
						echo "</b></input><br>";
						
						$i = $i + 1;
						
					}
					
					//Ver se para além das junções existe alguma turma isolada
						if($numTurmas > 0){
							$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp AND id_turma != $id_turma_original AND id_juncao IS NULL ORDER BY id_turma;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							while($linha2 = mysqli_fetch_assoc($resultado2)){
								$id_turma = $linha2["id_turma"];
								
								$statement3 = mysqli_prepare($conn, "SELECT nome, id_curso FROM turma WHERE id_turma = $id_turma;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$nome_turma = $linha3["nome"];
									$id_curso = $linha3["id_curso"];
									
									if($id_curso == $id_curso_final){
								
								echo "<input type='checkbox' class='checkbox_turmas_a_juntar' data-linha='$i' data-id_juncao='0' data-id_turma='$id_turma' data-id_componente='$id_comp' onclick='bloquearOutrasComponentes(0,$id_turma)' style='margin-right:5px;'><b>";
								echo $nome_turma;
								echo "</b></input><br>";
								
									}
								
								$i = $i + 1;
							}
								
						}
						
				}
				else{
					//Ver se há alguma turma
					$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_turma != $id_turma_original;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$numTurmas = $linha["COUNT(DISTINCT id_turma)"];
							
						if($numTurmas > 0){
							
							$i = 0;
							
							$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp AND id_turma != $id_turma_original ORDER BY id_turma;");
							$statement2->execute();
							$resultado2 = $statement2->get_result();
							while($linha2 = mysqli_fetch_assoc($resultado2)){
								$id_turma = $linha2["id_turma"];
									
								$statement3 = mysqli_prepare($conn, "SELECT nome, id_curso FROM turma WHERE id_turma = $id_turma;");
								$statement3->execute();
								$resultado3 = $statement3->get_result();
								$linha3 = mysqli_fetch_assoc($resultado3);
									$nome_turma = $linha3["nome"];
									$id_curso = $linha3["id_curso"];
									
									if($id_curso == $id_curso_final){
							
									echo "<input type='checkbox' class='checkbox_turmas_a_juntar' data-id_componente='$id_comp' data-linha='$i' data-id_juncao='0' data-id_turma='$id_turma' onclick='bloquearOutrasComponentes(0,$id_turma)' style='margin-right:5px;'><b>", $nome_turma, " ";
							
									echo "</b></input><br>";	
									
									}
									
									$i = $i + 1;
							}
							
						}
							
					}
			}
			else{
				$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_juncao) FROM aula WHERE id_componente = $id_comp AND id_juncao != $id_juncao AND id_juncao IS NOT NULL;");
				$statement->execute();
				$resultado = $statement->get_result();
				$linha = mysqli_fetch_assoc($resultado);
					$numJuncoes = $linha["COUNT(DISTINCT id_juncao)"];
					
					if($numJuncoes > 0){
							
						$i = 0;
							
						$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_juncao FROM aula WHERE id_componente = $id_comp AND id_juncao != $id_juncao AND id_juncao IS NOT NULL;");
						$statement2->execute();
						$resultado2 = $statement2->get_result();
						while($linha2 = mysqli_fetch_assoc($resultado2)){
							$id_juncao = $linha2["id_juncao"];
							
							echo "<input type='checkbox' class='checkbox_turmas_a_juntar' data-linha='$i' data-id_juncao='$id_juncao' data-id_turma='0' onclick='bloquearOutrasJuncoes($id_juncao)' style='margin-right:5px;'><b>";
							
							$statement23 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao;");
							$statement23->execute();
							$resultado23 = $statement23->get_result();
							$linha23 = mysqli_fetch_assoc($resultado23);
								$num_turmas_juncao = $linha23["COUNT(DISTINCT id_turma)"];
							
							$counter_t = 0;
							
							$statement3 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componete = $id_comp AND id_juncao = $id_juncao;");
							$statement3->execute();
							$resultado3 = $statement3->get_result();
							while($linha3 = mysqli_fetch_assoc($resultado3)){
								$id_turma = $linha3["id_turma"];
								
								$statement4 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
								$statement4->execute();
								$resultado4 = $statement4->get_result();
								$linha4 = mysqli_fetch_assoc($resultado4);
									$nome_turma = $linha4["nome"];
							
								echo $nome_turma, " ";
								
								$counter_t += 1;
							}
							
							if($counter_t != $num_turmas_juncao){
								echo " ...";
							}
							
							echo "</b></input><br>";
							$i = $i + 1;
						}
						
						//Ver se para além das junções existe alguma turma isolada
							if($numTurmas > 0){
								$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp AND id_turma != $id_turma_original AND id_juncao IS NULL ORDER BY id_turma;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								while($linha2 = mysqli_fetch_assoc($resultado2)){
									$id_turma = $linha2["id_turma"];
									
									$statement3 = mysqli_prepare($conn, "SELECT nome, id_curso FROM turma WHERE id_turma = $id_turma;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$nome_turma = $linha3["nome"];
										$id_curso = $linha3["id_curso"];
									
									if($id_curso == $id_curso_final){
									
									echo "<input type='checkbox' class='checkbox_turmas_a_juntar' data-id_componente='$id_comp' data-linha='$i' data-id_juncao='0' data-id_turma='$id_turma' onclick='bloquearOutrasComponentes(0,$id_turma)' style='margin-right:5px;'><b>";
									echo $nome_turma;
									echo "</b></input><br>";
									
									}
									
									$i = $i + 1;
									
								}
									
							}
							
					}
					else{
						//Ver se há alguma turma
						$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_componente = $id_comp AND id_turma != $id_turma_original AND id_juncao IS NULL;");
						$statement->execute();
						$resultado = $statement->get_result();
						$linha = mysqli_fetch_assoc($resultado);
							$numTurmas = $linha["COUNT(DISTINCT id_turma)"];
								
							if($numTurmas > 0){
								
								$i = 0;
								
								$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_componente = $id_comp AND id_turma != $id_turma_original AND id_juncao IS NULL ORDER BY id_turma;");
								$statement2->execute();
								$resultado2 = $statement2->get_result();
								while($linha2 = mysqli_fetch_assoc($resultado2)){
									$id_turma = $linha2["id_turma"];
										
									$statement3 = mysqli_prepare($conn, "SELECT nome, id_curso FROM turma WHERE id_turma = $id_turma;");
									$statement3->execute();
									$resultado3 = $statement3->get_result();
									$linha3 = mysqli_fetch_assoc($resultado3);
										$nome_turma = $linha3["nome"];
										$id_curso = $linha3["id_curso"];
									
									if($id_curso == $id_curso_final){
								
										echo "<input type='checkbox' class='checkbox_turmas_a_juntar' data-id_componente='$id_comp'data-linha='$i' data-id_juncao='0' data-id_turma='$id_turma' onclick='bloquearOutrasComponentes(0,$id_turma)' style='margin-right:5px;'><b>", $nome_turma, " ";
								
										echo "</b></input><br>";	
										
									}
										$i = $i + 1;
								}
								
							}
								
					}
				
			}
				?>
		</div>
		<?php } ?>
	</div>
	<?php if($num_juncoes_inicial == 0) { ?>
	<h6 onclick="gerarFormCriarJuncao(0,<?php echo $id_turma_incial ?>,<?php echo $id_comp_inicial ?>)" style="margin-left:100px; cursor:pointer;">Outras turmas...</h6>
	<?php } ?>
</div>
<div class="modal-footer">
    <button type="button" onclick="atribuirDocente(<?php echo $id_comp ?>, <?php echo $id_turma_original ?>,<?php echo $docente_ja_na_tabela ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
		Atribuir
    </button>
</div>
<script language="javascript">
function mostrarListaDocentes(){
	
	var dropdown_docentes = document.getElementById("edDSUCatribuirDocente");
	
	var opt = document.createElement('option');
	opt.value = "teste123";
	opt.text = "teste123";
	dropdown_docentes.options.add(opt);
	
	/*
	//Mostrar a lista de UC's
	$.ajax ({
		type: "POST",
		url: "processamento/mostrarListaUCs.php", 
		data: {},
		success: function(result) {
			var array = result.split(',');
			//alert("UC's: " + array);
			
			for(i = 0; i <= array.length; i++){
				var opt = document.createElement('option');
				opt.value = array[i];
				opt.text = array[i];
				dropdown_docentes.options.add(opt);
			} 
	
		}
	});
	*/
}
window.onload = mostrarListaDocentes;
</script>