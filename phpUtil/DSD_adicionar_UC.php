<?php
// Formulário de criação de junções

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');

$id_utilizador = $_GET["id_docente"];
?>

<style>
body{
}
.section1{
	width:50%;
	float:left;
	height:350px;
	border-right: 1px solid #dbdbdb; 
/*	border: 1px solid #dbdbdb; */
    box-sizing: border-box;
    margin:0;
    padding:0;	
	overflow: auto;
}
.section2{
	width:50%;
	float:left;
	height:60vh;
	margin:0;
	padding:0;
  box-sizing: border-box;
  overflow: auto;
}
</style>

<div class="modal-body">
	<div class="section1">
		<h6>UC's / Componentes / Turmas</h6>
		<div id="adicionar_uc_turmas_temp">
	<!--		<text data_array-turmas="1,2,3" data_id-componente="1" style='margin-left:28px;'>INF1, INF2, INF3</text><br> -->
		</div>
	</div>
	<div class="atribuir_uc_turma_ja_juncao" id="atribuir_uc_turma_ja_juncao">
			<img src="http://localhost/apoio_utc/images/warning.jpg" width="20" height="20">
			<text style="font-size:13px; font-family:comic_sans;"><b>Há turmas juntas com outras turmas. Ao definir o docente nesta(s) turma(s) vai afetar as outras.</b></text>
	</div>
	<div class="section2">
		<h6 align="center">Selecionar UC</h6>
		<div id="criar_juncao_turmas" style="margin-top:20px;">
			<div id='criar_juncao_utc' style="margin-left:10px; margin-top:0px;">
				UTC
				<select id="adicionar_uc_utc" onchange="mostrarCursosUTC_adicionar_uc()" style="width:100px; margin-left:42px;"><?php
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
			</div>
			<div id='criar_juncao_curso' style="margin-left:10px; margin-top:15px">
				Curso
				<select id="adicionar_uc_curso" onchange="mostrarDisciplinasCurso_adicionar_uc()" style="width:200px; margin-left:30px;">
				</select>
			</div>
			<div id='criar_juncao_uc' style="margin-left:10px; margin-top:15px;">
				Disciplina
				<select id="adicionar_uc_disciplina" onchange="mostrarComponentesUC_adicionar_uc()" style="width:200px; margin-left:3px;">
				</select>
			</div>
			<div id='criar_juncao_componente' style="margin-left:10px; margin-top:15px;">
				Comp.
				<select id="adicionar_uc_componente" onchange="mostrarTurmas_adicionar_uc()" style="width:50px; margin-left:25px;">
				</select>
			</div>
			<div id='adicionar_uc_turmas' style="margin-left:10px; margin-top:15px; line-height:12px;">
				Turmas
				<p>
			</div>
			<button type="button" style="border-radius:25px; margin-left:110px; margin-top:8px;" id="adicionarCompTurmasTemp" onclick="verificarErro1_adicionar_uc()" class="btn btn-primary">
				<b>Adicionar</b>
			</button>
		</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="atribuirUCs_adicionar_uc(<?php echo $id_utilizador ?>)" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atribuir UC(s)</b>
    </button>
</div>