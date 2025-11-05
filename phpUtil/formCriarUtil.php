<?php
// Formulário de criação de utilizadores

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
?>
<style>
body{
}
.section1{
	width:100%;
	float:left;
	height:60vh;
    box-sizing: border-box;
    margin:0;
    padding:0;	
}
</style>
<div class="modal-body">
    <form id="formCriarUtilizador" class="user" action="processamento/processarFormCriarUtilizador.php" method="post" style="overflow:auto">
        <div class="form-group row">
            <div class="section1">
				<div class="form-group row">
					<label for="nome">Nome Completo:</label>
					<input type="text" class="form-control form-control-user" name="nome" id="nome">
				</div>
				<div class="form-group row">
					<label for="login">Login:</label>
					<input type="text" class="form-control form-control-user" name="login" id="login">
				</div>
				<div class="form-group row">
					<label for="password1">Password:</label>
					<input type="text" class="form-control form-control-user" name="password1" id="password1">
				</div>
				<div class="form-group row">
					<label for="password2">Confirme password:</label>
					<input type="text" class="form-control form-control-user" name="password2" id="password2">
				</div>
		<div class="form-group row">
                <label for="utc">UTC: </label>
                <select name="utc" id="utc" onchange="gerarSelectArea(this.value)" style="margin-left:10px;">
                    <option></option>
					<?php
					$statement = mysqli_prepare($conn, "SELECT * FROM utc");
					$statement->execute();
					$resultado1 = $statement->get_result();
						while($linha1 = mysqli_fetch_assoc($resultado1)){
							$idUTC = (int) $linha1["id_utc"];
							$nomeUTC = $linha1["nome_utc"];
					?>
                    <option value="<?php echo $idUTC ?>"><?php echo $nomeUTC ?></option>
					<?php
						}
					?>
                </select>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <div id="selectArea">
                </div>
            </div>
        </div>
        <div class="form-group" id="permissoes">
            Permissões de utilizador: <br>

            <input type="checkbox" id="admin" name="admin" value="1">
            <label for="admin">Administrador</label><br>
            <input type="checkbox" id="gestorUTC" name="gestorUTC" value="1">
            <label for="gestorUTC">Gestor UTC</label><br>
            <input type="checkbox" id="gestorArea" name="gestorArea" value="1">
            <label for="gestorArea">Gestor Área</label><br>
        </div>
        <div class="form-group" id="cargo">
            Categoria de utilizador: <br>

            <input checked type="radio" id="profadj" name="cargo" value="4">
            <label for="profadj">Prof. Adjunto</label><br>
            <input type="radio" id="profcoord" name="cargo" value="5">
            <label for="profcoord">Prof. Coordenador</label><br>
            <input type="radio" id="assistenteconv" name="cargo" value="6">
            <label for="assistenteconv">Assistente Convidado</label>
        </div>
    </form>
</div>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormCriarUtilizador()" class="btn btn-light btn-lg">
        Criar
    </button>
</div>