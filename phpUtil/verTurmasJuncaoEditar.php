<?php
// Formulário de criação de junções

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');
?>
<div class="modal-body">
	<div class="DSD_editar_juncao_remover_turmas" id="DSD_editar_juncao_remover_turmas">
	<h6 align="center" style="margin-bottom:10px;">Turmas Junção</h6>
		<?php $id_juncao = $_GET["id"];
		$statement0 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao;");
		$statement0->execute();
		$resultado0 = $statement0->get_result();
		$linha0 = mysqli_fetch_array($resultado0);
			$num_turmas_juncao = $linha0["COUNT(DISTINCT id_turma)"];
				
		$statement51 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_juncao = $id_juncao;");
		$statement51->execute();
		$resultado51 = $statement51->get_result();
		$linha51 = mysqli_fetch_assoc($resultado51);
			$id_componente_final = $linha51["id_componente"];
						
			$statement1 = mysqli_prepare($conn, "SELECT t.nome, t.ano, t.semestre, t.id_curso, a.id_turma FROM turma t INNER JOIN
												 aula a ON t.id_turma = a.id_turma WHERE a.id_juncao = $id_juncao ORDER BY t.nome;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			while($linha1 = mysqli_fetch_array($resultado1)){
						
				$id_turma = $linha1['id_turma'];
				$nome_turma = $linha1['nome'];
				$ano_turma = $linha1['ano'];
				$sem_turma = $linha1['semestre'];
				$id_curso = $linha1['id_curso'];
						
				$statement2 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
				$statement2->execute();
				$resultado2 = $statement2->get_result();
				$linha2 = mysqli_fetch_array($resultado2);
							
					$sigla_curso = $linha2['sigla'];
							
					$statement3 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_turma = $id_turma AND id_juncao = $id_juncao;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_array($resultado3);
							
					$id_componente = $linha3['id_componente'];
					
					$statement4 = mysqli_prepare($conn, "SELECT d.abreviacao_uc, c.id_tipocomponente FROM disciplina d INNER JOIN componente c 
														ON d.id_disciplina = c.id_disciplina WHERE c.id_componente = $id_componente;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					$linha4 = mysqli_fetch_assoc($resultado4);
						$nome_uc = $linha4["abreviacao_uc"];
						$id_tipocomponente = $linha4["id_tipocomponente"];
								
						$statement5 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente 
															WHERE id_tipocomponente = $id_tipocomponente;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$sigla_tipocomponente = $linha5["sigla_tipocomponente"];	
							
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
			<?php } ?>
	</div>
	<div id="DSD_editar_juncao_adicionar_turmas">
		<h6>Adicionar Turmas</h6>
		UTC
		<select id="edDSUC_outros_utc" onchange="mostrarCursosUTC(<?php echo $id_juncao ?>)" style="width:100px; margin-left:42px;"><?php
		echo "<option value='0'></option>";
		$statement50 = mysqli_prepare($conn, "SELECT id_utc, nome_utc FROM utc ORDER BY nome_utc;");
		$statement50->execute();
		$resultado50 = $statement50->get_result();
		while($linha50 = mysqli_fetch_assoc($resultado50)){
			$id_utc = $linha50["id_utc"];
			$nome_utc = $linha50["nome_utc"];
					
			echo "<option value='$id_utc'>$nome_utc</option>";
		}
		?>
		</select>
			<div id='div_turmas_outros_curso' style="margin-top:15px;">
				Curso
				<select id="edDSUC_outros_curso" onchange="mostrarDisciplinasCurso(<?php echo $id_juncao ?>)" style="width:200px; margin-left:30px;">
					<option value='0'></option>
				</select>
			</div>
			<div id='div_turmas_outros_disciplina' style="margin-top:15px;">
				Disciplina
				<select id="edDSUC_outros_disciplina" onchange="mostrarComponentesUC(<?php echo $id_juncao ?>)" style="width:200px; margin-left:3px;">
					<option value='0'></option>
				</select>
			</div>
			<div id='div_turmas_outros_componente' style="margin-top:15px;">
				Comp.
				<select id="edDSUC_outros_componente" onchange="mostrarTurmas(<?php echo $id_juncao ?>)" style="width:50px; margin-left:25px;">
					<option value='0'></option>
				</select>
			</div>
			<div id='div_turmas_outros_turmas' style="margin-top:15px; line-height:12px; overflow:auto;">
				Turmas
				<p>
			<!--		<button type="button" style="border-radius:25px;" id="adicionarTurmaTemp" onclick="verificarErro1()" class="btn btn-primary">
								<b>Adicionar</b>
							</button>-->
			</div>
	</div>
	<div id='div_turmas_outros_botao'>
		<button class="btn btn-primary" style="margin-left:420px; border-radius:25px;" id="adicionarTurmaTemp" onclick="verificarErro1(<?php echo $id_juncao ?>)">
			<b>Adicionar</b>
		</button>
	</div>
</div>