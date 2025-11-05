<?php
// Formulário de criação de junções

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');
?>

<style>
body{
}
.section1{
	width:50%;
	float:left;
	height:60vh;
	border-right: 1px solid #dbdbdb; 
    box-sizing: border-box;
    margin:0;
    padding:0;	
}
.section2{
	width:50%;
	float:left;
	height:60vh;
	margin:0;
	padding:0;
  box-sizing: border-box;
}
.lixo:hover {
    cursor: pointer;
}
</style>

<style>
#firstList{
 width:200px;   
}
</style>

<style>
#rcorners2 {
  border-radius: 25px;
  border: 2px solid #73AD21;
  padding: 20px;
  width: 200px;
  height: 150px;
}
</style>

<div class="modal-body">
    <form id="formEditarJuncao" class="user" action="processamento/processarFormEditarJuncao.php" method="post">
	<div id="cover-spin"></div>
	<div class="form-group row" style="overflow:auto">
		<div class="section1">
			<div class="form-group row">
				<div class="col-sm-6">
					<h6>Nome Junção:</h6>
					<input type="text" class="form-control form-control-user" name="nome" id="nomeJuncao">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-6" onload="createTable()">
				<br>
					<h6>Turmas:</h6>
						<TABLE id="tabelaEsquerda" width="350px" border="1" cellpadding="6" style="text-align:center">  
						   <TR>  
								<TD width="50">Curso</TD>  
								<TD width="50">Turma</TD>  
								<TD width="40">A/S</TD>  
								<TD width="40">UC</TD>
								<TD width="40">Componente</TD>
								<TD>
								</TD>
						   </TR>  
						</TABLE>  
				</div>
			</div>
			<div class="erro2img_msg" id="erro2img_msg" style="line-height:1%; visibility:hidden; margin-top:10px;">
				<img src="erro2.jpg" alt="Italian Trulli" width="25" height="25">
				<b><font size="2">Está a tentar juntar componentes diferentes</font></b>
			</div>
		</div>
		<div style="overflow:auto" class="section2">
			<h6 align="center">Adicionar Turmas</h6>
				<div id="tree-container">
					<div class="col-md-4">
						<form>
							<h6>Curso</h6>
								<select class="form-control" style='width:200px;'id='curso_dropdown' name='curso_dropdown' onchange="configurarCursosDisciplinas(this,document.getElementById('disciplina_dropdown'))">
									<option value="nada_selecionado"></option>
							<?php 
								$statement = mysqli_prepare($conn, "SELECT nome FROM curso;");
								$statement->execute();
								$resultado = $statement->get_result();
								while($linha = mysqli_fetch_assoc($resultado)){
									$nome = $linha["nome"];
									echo "<option value='$nome'>$nome</option>";	
								}
							?>
							</select>
							<h6><br><br>Disciplina</h6>
								<select class="form-control" style='width:200px;' onchange="processarUc()" id='disciplina_dropdown' name='disciplina_dropdown'>
								</select>
						</form>
						<br>

						<div class="col-md-4" id="colocar_tabela_aqui" style="text-align:center">
							<TABLE id="tabelaTurmasComponentes" style="visibility:hidden;" width="450px" border="1">  
								<tbody id="tabelaTurmasComponentesBody">
								
								</tbody>
							</TABLE>  
						</div>
						<br>
						  <button type="button" style="visibility:hidden; border-radius:25px;" id="adicionarTurmaTemp" onclick="verificarErro1()" class="btn btn-primary">
							<b>Adicionar</b>
						</button>
					</div>
				</div>
		</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="criarJuncao()" class="btn btn-light btn-lg" style="border-radius:50px;">
        <b>Atualizar</b>
    </button>
</div>