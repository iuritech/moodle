<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$id = (int) filter_input(INPUT_GET, 'i');
$idBloco = (int) filter_input(INPUT_GET, 'bloco');

?>
<div class="modal-body">
    
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:removerBloco(<?php echo $idBloco ?>)" class="btn btn-danger btn-lg">
        Eliminar bloco
    </button>
</div>
