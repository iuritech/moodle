<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$idBloco = (int) filter_input(INPUT_GET, 'i');
$idDisciplina = (int) filter_input(INPUT_GET, 'disc');

?>
<div class="modal-body">
    <form id="formCriarJuncao" class="user" action="processamento/processarFormCriarJuncaoUC.php?i=<?php echo $idDisciplina ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="bloco">Bloco:</label>
                <select name="bloco" id="bloco">
                        <option value="<?php echo $idBloco ?>"><?php echo "Bloco ".$idBloco ?></option>                                           
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="nome">Defina um nome para a junção:</label>
                <input type="text" class="form-control form-control-user" name="nome" id="nome">
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormCriarJuncao()" class="btn btn-primary btn-lg">
        Criar
    </button>
</div>
