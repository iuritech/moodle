<?php
// Formulário de edição de unidades técnico-científicas

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$id = (int) filter_input(INPUT_GET, 'i');

// Obter dados da área
$statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();

$linha = mysqli_fetch_assoc($resultado);
$nome = $linha["nome"];
$sigla = $linha["sigla"];
$id_responsavel = $linha["id_responsavel"];
?>
<div class="modal-body">
    <form id="formEditarArea" class="user" action="processamento/processarFormEditarArea.php?i=<?php echo $id ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control form-control-user" name="nome" id="nome" value="<?php echo $nome ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">
                <label for="nome">Sigla:</label>
                <input type="text" class="form-control form-control-user" name="sigla" id="sigla" value="<?php echo $sigla ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="responsavel">Responsável:</label>
                <select name="responsavel" id="responsavel">
                    <option></option>
<?php
// Buscar ids de gestores de área
$tabela = "funcao_utilizador"; 
$query = "SELECT * FROM $tabela WHERE id_funcao = 3";
$resultado2 = mysqli_query($conn, $query);
while($linha2 = mysqli_fetch_assoc($resultado2)){
    $id = (int) $linha2["id_utilizador"];
    
    $tabela = "utilizador";
    $query = "SELECT * FROM $tabela WHERE id_utilizador = $id";
    $resultado3 = mysqli_query($conn, $query);
    
    $linha3 = mysqli_fetch_assoc($resultado3);
    $nome_responsavel = $linha3["nome"];
?>
                    <option <?php if($id == $id_responsavel){ echo "selected"; }?> value="<?php echo $id ?>"><?php echo $nome_responsavel ?></option>
<?php
}
?>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormEditarArea()" class="btn btn-primary btn-lg">
        Editar
    </button>
</div>