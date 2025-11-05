<?php
// Formulário de criação de junções

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');

$id_utilizador_atual = $_SESSION['id'];
$statement = mysqli_prepare($conn, "SELECT id_utc FROM utilizador WHERE id_utilizador = $id_utilizador_atual;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_array($resultado);

$id_utc = $linha["id_utc"];

$statement1 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_array($resultado1);

$nome_utc = $linha1["nome_utc"];
?>
<div class="modal-body" style="height:430px;">
	<div class="criar_juncao_nome">
		<h6 style="margin-bottom:10px;">Nome</h6>
		<input type="text" class="form-control form-control-user" name="criar_juncao_nome" id="criar_juncao_nome" style="margin-bottom:15px;" style="width:250px; margin-left:10px;">
	</div>
	<div class="criar_juncao_docente">
		<h6 style="margin-bottom:10px;">Docente</h6>
		<select id="criar_juncao_docente" style="width:200px; margin-left:3px;"><?php
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
		?></select>
	</div>
	<div class="criar_juncao_turmas_temp" id="criar_juncao_turmas_temp" style="height:210px; overflow:auto;">
		<h6 style="margin-bottom:10px;">Turmas Junção</h6>
		<!--<label data_id-turma="1" data_id-componente="5">TESTE123</label>-->
	</div>
	<div class='criar_juncao_criar'>
		<button class="btn btn-primary" style="border-radius:25px;" id="criar_juncao_criar" onclick="criarJuncao()">
			<i class="material-icons" style="line-height:50%; margin-right:5px; vertical-align: middle;">upload</i>
			<b>Criar Junção</b>
		</button>
	</div>
	<div class="criar_juncao_adicionar_turmas">
		<h6>Adicionar Turmas</h6>
		UTC
		<select id="criar_juncao_utc" onchange="mostrarCursosUTCCriar()" style="width:100px; margin-left:42px;">
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
		<div id='div_criar_juncao_curso' style="width:100%; margin-top:15px;">
			Curso
			<select id="criar_juncao_curso" onchange="mostrarDisciplinasCursoCriar()" style="width:200px; margin-left:30px;">
				<option value='0'></option>
			</select>
		</div>
		<div id='div_criar_juncao_disciplina' style="width:100%; margin-top:15px;">
			Disciplina
			<select id="criar_juncao_disciplina" onchange="mostrarComponentesUCCriar()" style="width:200px; margin-left:3px;">
				<option value='0'></option>
			</select>
		</div>
		<div id='div_criar_juncao_componente' style="width:100%; margin-top:15px;">
			Comp.
			<select id="criar_juncao_componente" onchange="mostrarTurmasCriar()" style="width:50px; margin-left:25px;">
				<option value='0'></option>
			</select>
		</div>
		<div id='div_criar_juncao_turmas' style="width:100%; margin-top:15px; line-height:12px;">
			Turmas
			<p>
		</div>
		<div id='div_criar_juncao_botao'>
			<button class="btn btn-primary" style="margin-left:125px; border-radius:25px;" id="adicionarTurmaTemp" onclick="adicicionarTurmaTempCriarJuncao()">
				<b>Adicionar</b>
			</button>
		</div>
	</div>
</div>