<?php
// Formulário de criação de junções

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');

$id_juncao = $_GET["id"];
$id_utilizador_atual = $_SESSION['id'];

$statement000 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
$statement000->execute();
$resultado000 = $statement000->get_result();
$linha000 = mysqli_fetch_assoc($resultado000);
	$num_componentes_juncao = $linha000["COUNT(DISTINCT id_componente)"];

if($num_componentes_juncao > 0){
	
	$statement00 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_juncao = $id_juncao;");
	$statement00->execute();
	$resultado00 = $statement00->get_result();
	$linha00 = mysqli_fetch_assoc($resultado00);
		$id_componente_final = $linha00["id_componente"];
		
	$statement001 = mysqli_prepare($conn, "SELECT COUNT(id_docente) FROM aula WHERE id_juncao = $id_juncao;");
	$statement001->execute();
	$resultado001 = $statement001->get_result();
	$linha001 = mysqli_fetch_assoc($resultado001);
		$count_id_docente = $linha001["COUNT(id_docente)"];
	
		if($count_id_docente > 0){
	
			$statement01 = mysqli_prepare($conn, "SELECT id_docente FROM aula WHERE id_juncao = $id_juncao;");
			$statement01->execute();
			$resultado01 = $statement01->get_result();
			$linha01 = mysqli_fetch_assoc($resultado01);
				$id_docente = $linha01["id_docente"];

			$statement02 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
			$statement02->execute();
			$resultado02 = $statement02->get_result();
			$linha02 = mysqli_fetch_assoc($resultado02);
				$nome_docente = $linha02["nome"];	
				
		}
		
}	

$statement03 = mysqli_prepare($conn, "SELECT id_utc FROM utilizador WHERE id_utilizador = $id_utilizador_atual;");
$statement03->execute();
$resultado03 = $statement03->get_result();
$linha03 = mysqli_fetch_array($resultado03);

$id_utc = $linha03["id_utc"];

$statement04 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc;");
$statement04->execute();
$resultado04 = $statement04->get_result();
$linha04 = mysqli_fetch_array($resultado04);

$nome_utc = $linha04["nome_utc"];
?>
<div class="modal-body">
	<div class="editar_juncao_nome">
		<h6 style="margin-bottom:10px;">Nome</h6>
		<?php 
			$statement0 = mysqli_prepare($conn, "SELECT nome_juncao FROM juncao WHERE id_juncao = $id_juncao");
			$statement0->execute();
			$resultado0 = $statement0->get_result();
			$linha0 = mysqli_fetch_array($resultado0);
				$nome_juncao = $linha0["nome_juncao"];
		?>
		<input type="text" class="form-control form-control-user" name="nome_juncao" id="nome_juncao" value="<?php echo $nome_juncao ?>" title="<?php echo $nome_juncao ?>" style="margin-bottom:15px;" style="width:250px; margin-left:10px;">
	</div>
	<div class="editar_juncao_docente">
		<h6 style="margin-bottom:10px;">Docente</h6>
		<select id="editar_juncao_docente" style="width:200px; margin-left:3px;"><?php
		if($count_id_docente > 0 && $num_componentes_juncao > 0){
			echo "<option value='$id_docente'>$nome_docente</option>";
			echo "<option value='0'></option>";
			echo "<option value='0'>-------->$nome_utc</option>";
			$statement2 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc = $id_utc AND id_utilizador != $id_docente ORDER BY nome;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_array($resultado2)){
				$id_utilizador = $linha2["id_utilizador"];
				$nome_utilizador = $linha2["nome"];
				echo "<option value='$id_utilizador'>$nome_utilizador</option>";
			}
			
			echo "<option value='0'></option>";
			echo "<option value='0'>-------->Outros</option>";
			$statement3 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc != $id_utc AND id_utilizador != $id_docente ORDER BY nome;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			while($linha3 = mysqli_fetch_array($resultado3)){
				$id_utilizador = $linha3["id_utilizador"];
				$nome_utilizador = $linha3["nome"];
				echo "<option value='$id_utilizador'>$nome_utilizador</option>";
			}
		}
		else{
			echo "<option value='0'></option>";
			echo "<option value='0'>-------->$nome_utc</option>";
			$statement2 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc = $id_utc ORDER BY nome;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_array($resultado2)){
				$id_utilizador = $linha2["id_utilizador"];
				$nome_utilizador = $linha2["nome"];
				echo "<option value='$id_utilizador'>$nome_utilizador</option>";
			}
			
			echo "<option value='0'></option>";
			echo "<option value='0'>-------->Outros</option>";
			$statement3 = mysqli_prepare($conn, "SELECT id_utilizador, nome FROM utilizador WHERE id_utc != $id_utc ORDER BY nome;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			while($linha3 = mysqli_fetch_array($resultado3)){
				$id_utilizador = $linha3["id_utilizador"];
				$nome_utilizador = $linha3["nome"];
				echo "<option value='$id_utilizador'>$nome_utilizador</option>";
			}
		}
		?></select>
		<button class="btn btn-primary" style="margin-left:5px; border-radius:25px;" onclick="atualizarNomeJuncao(<?php echo $id_juncao ?>)">
			<i class="material-icons" style="line-height:50%; margin-right:5px; vertical-align: middle;">update</i><b>Atualizar</b>
		</button>
	</div>
	<div class="editar_juncao_remover_turmas" id="editar_juncao_remover_turmas" style="height:220px; overflow:auto;">
		<h6 style="margin-bottom:10px;">Turmas Junção</h6>
			<?php 
			$statement1 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			$linha1 = mysqli_fetch_array($resultado1);
				$num_turmas_juncao = $linha1["COUNT(DISTINCT id_turma)"];
			
			$statement2 = mysqli_prepare($conn, "SELECT t.nome, t.ano, t.semestre, t.id_curso, a.id_turma FROM turma t INNER JOIN
												 aula a ON t.id_turma = a.id_turma WHERE a.id_juncao = $id_juncao ORDER BY t.nome;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_array($resultado2)){
						
				$id_turma = $linha2['id_turma'];
				$nome_turma = $linha2['nome'];
				$ano_turma = $linha2['ano'];
				$sem_turma = $linha2['semestre'];
				$id_curso = $linha2['id_curso'];
						
				$statement3 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
				$statement3->execute();
				$resultado3 = $statement3->get_result();
				$linha3 = mysqli_fetch_array($resultado3);
							
				$sigla_curso = $linha3['sigla'];
					
				$statement4 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_turma = $id_turma AND id_juncao = $id_juncao;");
				$statement4->execute();
				$resultado4 = $statement4->get_result();
				$linha4 = mysqli_fetch_array($resultado4);
							
				$id_componente = $linha4['id_componente'];
							
				$statement5 = mysqli_prepare($conn, "SELECT d.abreviacao_uc, c.id_tipocomponente FROM disciplina d INNER JOIN componente c 
													ON d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente;");
				$statement5->execute();
				$resultado5 = $statement5->get_result();
				$linha5 = mysqli_fetch_assoc($resultado5);
					$nome_uc = $linha5["abreviacao_uc"];
					$id_tipocomponente = $linha5["id_tipocomponente"];
								
				$statement6 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente 
													WHERE id_tipocomponente = $id_tipocomponente;");
				$statement6->execute();
				$resultado6 = $statement6->get_result();
				$linha6 = mysqli_fetch_assoc($resultado6);
					$sigla_tipocomponente = $linha6["sigla_tipocomponente"];	
					
				if($num_turmas_juncao > 2){
					echo "<input type='checkbox' data_id-turma='$id_turma' data_id-componente='$id_componente' data_nome-turma='$nome_turma' data_id-juncao='$id_juncao' style='margin-left:5px; margin-right:5px;'>";
				}					
				echo "<b>", $nome_turma, "</b> - ", $sigla_curso, " (", $nome_uc, "-", $sigla_tipocomponente, ") (", $ano_turma, "ºA/", $sem_turma, "ºS)<br>";
						
			}
			?>
			<?php if($num_turmas_juncao > 2){ ?>
				<button class="btn btn-danger" style="margin-left:5px; margin-top:10px; border-radius:25px;" onclick="removerTurmasJuncao(<?php echo $id_juncao ?>)">
					<i class="material-icons" style="line-height:50%; margin-right:5px; vertical-align: middle;">delete</i><b>Remover Turmas</b>
				</button>
			<?php } else{ ?>
				<button class="btn btn-danger" style="margin-left:5px; margin-top:10px; border-radius:25px;" onclick="eliminarJuncao(<?php echo $id_juncao ?>)">
					<i class="material-icons" style="line-height:50%; margin-right:5px; vertical-align: middle;">delete</i><b>Eliminar Junção</b>
				</button>
	<?php		} ?>
	</div>
	<div class="editar_juncao_adicionar_turmas">
		<h6>Adicionar Turmas</h6>
		UTC
		<select id="edDSUC_outros_utc" onchange="mostrarCursosUTC(<?php echo $id_componente_final ?>,<?php echo $id_juncao ?>)" style="width:100px; margin-left:42px;">
		<?php
		echo "<option value='0'></option>";
		$statement7 = mysqli_prepare($conn, "SELECT id_utc, nome_utc FROM utc ORDER BY nome_utc;");
		$statement7->execute();
		$resultado7 = $statement7->get_result();
		while($linha7 = mysqli_fetch_assoc($resultado7)){
			$id_utc = $linha7["id_utc"];
			$nome_utc = $linha7["nome_utc"];
					
			echo "<option value='$id_utc'>$nome_utc</option>";
		}
		?>
		</select>
		<div id='div_turmas_outros_curso' style="width:100%; margin-top:15px;">
			Curso
			<select id="edDSUC_outros_curso" onchange="mostrarDisciplinasCurso(<?php echo $id_componente_final ?>,<?php echo $id_juncao ?>)" style="width:200px; margin-left:30px;">
				<option value='0'></option>
			</select>
		</div>
		<div id='div_turmas_outros_disciplina' style="width:100%; margin-top:15px;">
			Disciplina
			<select id="edDSUC_outros_disciplina" onchange="mostrarComponentesUC(<?php echo $id_componente_final ?>,<?php echo $id_juncao ?>)" style="width:200px; margin-left:3px;">
				<option value='0'></option>
			</select>
		</div>
		<div id='div_turmas_outros_componente' style="width:100%; margin-top:15px;">
			Comp.
			<select id="edDSUC_outros_componente" onchange="mostrarTurmas(<?php echo $id_juncao ?>)" style="width:50px; margin-left:25px;">
				<option value='0'></option>
			</select>
		</div>
		<div id='div_turmas_outros_turmas' style="width:100%; margin-top:15px; line-height:12px; overflow:auto;">
			Turmas
			<p>
		</div>
		<div id='div_turmas_outros_botao'>
			<button class="btn btn-primary" style="margin-left:125px; border-radius:25px;" id="adicionarTurmaTemp" onclick="verificarErro1(<?php echo $id_juncao ?>)">
				<b>Adicionar</b>
			</button>
		</div>
	</div>
</div>