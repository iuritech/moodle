<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$idComponente = (int) filter_input(INPUT_GET, 'comp');
$idTurma = (int) filter_input(INPUT_GET, 'turma');

// Pesquisar tipo da componente
$statement = mysqli_prepare($conn, "SELECT * FROM componente INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente WHERE id_componente = ?");
$statement->bind_param('i', $idComponente);
$statement->execute();
$resultado1 = $statement->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
$tipoComponente = $linha1["nome"];

// Pesquisar nome da turma
$statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_turma = ?");
$statement->bind_param('i', $idTurma);
$statement->execute();
$resultado1 = $statement->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
$nomeTurma = $linha1["nome"];

?>
<div class="modal-body">
    <form id="formCriarDSUC" class="user" action="processamento/processarFormCriarDSUC.php" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="componente">Componente:</label>
                <select name="componente" id="componente">
                        <option value="<?php echo $idComponente ?>"><?php echo $idComponente." - ".$tipoComponente ?></option>                                           
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="turma">Turma:</label>
                <select name="turma" id="turma">
                        <option value="<?php echo $idTurma ?>"><?php echo $nomeTurma ?></option>                                           
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <label>Docente:</label><br>
<?php
// Listar docentes de todas as áreas
$statement = mysqli_prepare($conn, "SELECT * FROM utilizador INNER JOIN funcao_utilizador ON utilizador.id_utilizador = funcao_utilizador.id_utilizador WHERE id_funcao = 4");
$statement->execute();
$resultado = $statement->get_result();
$sel = false;
while ($linha = mysqli_fetch_assoc($resultado)) {
    $idUtilizador = (int) $linha["id_utilizador"];
    $nomeUtilizador = $linha["nome"];
    $idArea = (int) $linha["id_area"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = ?");
    $statement->bind_param('i', $idArea);
    $statement->execute();
    $resultado2 = $statement->get_result();
    if(mysqli_num_rows($resultado2)==0){
        $nomeArea = "";
    } else {
        $linha2 = mysqli_fetch_assoc($resultado2);
        $nomeArea = " - ".$linha2["nome"];
    }
?>
                    <input <?php if(!$sel){ echo 'checked="checked"'; } ?>type="radio" name="docente" value="<?php echo $idUtilizador ?>"> <?php echo $nomeUtilizador.$nomeArea ?> <br>
<?php
    $sel = true;
}
?>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormAtribuirComponenteBloco()" class="btn btn-primary btn-lg">
        Atribuir
    </button>
</div>
