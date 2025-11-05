<?php
// Formulário de criação de utilizadores

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');
?>
<style>
padding: 0px;
</style>
<div class="modal-body_1"> 
    <div class="card-body">
        <div class="form-group row">
		<h6>Curso</h6>
			<select class="form-control" style="width:270px;" id='curso_dropdown' name='curso_dropdown' onchange="mostrarListaTurmas(<?php echo $_GET['vertical']; ?>,<?php echo $_GET['horizontal']; ?>, <?php echo $_GET['idComp'] ?>)">
				<option value="nada_selecionado"></option>
				<?php 
					$statement = mysqli_prepare($conn, "SELECT id_curso, nome FROM curso;");
					$statement->execute();
					$resultado = $statement->get_result();
					while($linha = mysqli_fetch_assoc($resultado)){
						$id_curso = $linha["id_curso"];
						$nome = $linha["nome"];
						echo "<option value='$id_curso'>$nome</option>";	
					}
				?>
			</select>
		
		<br>
		<h6 style="margin-top:15px;">Turmas (Ano/Sem)</h6>
			<select class="form-control" style='width:250px;'id='turmas_dropdown' name='turmas_dropdown' onchange="adicionarTurma(<?php echo $_GET['vertical']; ?>,<?php echo $_GET['horizontal']; ?>, <?php echo $_GET['idComp']; ?>)"></select>
        <br><br>
		</div>
	</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormAdicionarTurma()" class="btn btn-light btn-lg" style="border-radius:50px;">
        Adicionar
    </button>
</div>