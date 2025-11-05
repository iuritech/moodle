<?php
// Formulário de edição de utilizadores

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$id = (int) filter_input(INPUT_GET, 'i');
// Obter dados atuais do utilizador
$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$idUtilizador = (int) $linha["id_utilizador"];
$nome = $linha["nome"];
$login = $linha["login"];
$idAreaUtilizador = (int) $linha["id_area"];

if(!empty($idAreaUtilizador)){
    $statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = ?");
    $statement->bind_param('i', $idAreaUtilizador);
    $statement->execute();
    $resultado = $statement->get_result();
    $linha = mysqli_fetch_assoc($resultado);
    $idUTCUtilizador = (int) $linha["id_utc"];
}

$permAdmin = false;
$permUTC = false;
$permArea = false;

// Obter permissões atribuídas ao utilizador
mysqli_free_result($resultado);
$statement = mysqli_prepare($conn, "SELECT * FROM funcao_utilizador WHERE id_utilizador = ? AND id_funcao < 4");
$statement->bind_param('i', $idUtilizador);
$statement->execute();
$resultado = $statement->get_result();
while($linha = mysqli_fetch_assoc($resultado)){
    $funcao = (int) $linha['id_funcao'];
    switch($funcao){
        case 1:
            $permAdmin = true;
            break;
        case 2:
            $permUTC = true;
            break;
        case 3:
            $permArea = true;
            break;
    }
}

// Obter cargo atribuido ao utilizador
mysqli_free_result($resultado);
$statement = mysqli_prepare($conn, "SELECT * FROM funcao_utilizador WHERE id_utilizador = ? AND id_funcao > 3");
$statement->bind_param('i', $idUtilizador);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$cargo = 0;
if(mysqli_num_rows($resultado)>0){
    $cargo = (int) $linha['id_funcao'];
}

?>
<div class="modal-body">
    <a class="btn btn-primary" data-toggle="modal" data-target="#redefinirPasswordModal" onclick="gerarFormRedefinirPassword(<?php echo $id ?>)">Redefinir password</a>
    <div class="card-body">
        <form id="formEditarUtilizador" class="user" action="processamento/processarFormEditarUtilizador.php?i=<?php echo $idUtilizador ?>" method="post">
            <div class="form-group row">
                <div class="col-sm-6">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" class="form-control form-control-user" name="nome" id="nome" value="<?php echo $nome ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <label for="nome">Login:</label>
                    <input type="text" class="form-control form-control-user" name="login" id="login" value="<?php echo $login ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <label for="utc">UTC:</label>
                    <select name="utc" id="utc" onchange="gerarSelectArea(this.value)">
                        <option></option>
<?php
$statement = mysqli_prepare($conn, "SELECT * FROM utc");
$statement->execute();
$resultado1 = $statement->get_result();
if(mysqli_num_rows($resultado1)>0){
    while($linha1 = mysqli_fetch_assoc($resultado1)){
        $idUTC = (int) $linha1["id_utc"];
        $nomeUTC = $linha1["nome"];
        $selected = false;
        if(!empty($idUTCUtilizador)){
            $selected = true;
        }
    
?>
                        <option value="<?php echo $idUTC ?>"<?php if($selected){ echo " selected";} ?>><?php echo $nomeUTC ?></option>
<?php
    }
}

?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <div id="selectArea">
<?php
if(!empty($idAreaUtilizador)){
?>
                        <label for="area">Área:</label>
                        <select name="area" id="area">
                            <option value="0"></option>
<?php
    $statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_utc = ?");
    $statement->bind_param('i', $idUTCUtilizador);
    $statement->execute();
    $resultado1 = $statement->get_result();
    while($linha1 = mysqli_fetch_assoc($resultado1)){
        $idArea = (int) $linha1["id_area"];
        $nomeArea = $linha1["nome"];
    
?>
                            <option<?php if($idAreaUtilizador == $idArea) echo " selected" ?> value="<?php echo $idArea ?>"><?php echo $nomeArea ?></option>
<?php
    }
?>
                        </select>
<?php
}
?>
                    </div>
                </div>
            </div>
            <div class="form-group" id="permissoes">
                Permissões de utilizador: <br>

                <input type="checkbox" id="admin" name="admin" value="1"<?php if($permAdmin) echo " checked" ?>>
                <label for="vehicle1">Administrador</label><br>
                <input type="checkbox" id="gestorUTC" name="gestorUTC" value="1"<?php if($permUTC) echo " checked" ?>>
                <label for="vehicle1">Gestor UTC</label><br>
                <input type="checkbox" id="gestorArea" name="gestorArea" value="1"<?php if($permArea) echo " checked" ?>>
                <label for="vehicle1">Gestor Área</label><br>
            </div>
            <div class="form-group" id="cargo">
                Categoria de utilizador: <br>

                <input <?php if($cargo == 4) echo "checked " ?>type="radio" id="profadj" name="cargo" value="4">
                <label for="profadj">Prof. Adjunto</label><br>
                <input <?php if($cargo == 5) echo "checked " ?>type="radio" id="profcoord" name="cargo" value="5">
                <label for="profcoord">Prof. Coordenador</label><br>
                <input <?php if($cargo == 6) echo "checked " ?>type="radio" id="assistenteconv" name="cargo" value="6">
                <label for="assistenteconv">Assistente Convidado</label>
            </div>
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormEditarUtilizador()" class="btn btn-primary btn-lg">
        Editar
    </button>
</div>