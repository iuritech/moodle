<?php
// Formulário de edição de disciplinas

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');

$id = (int) filter_input(INPUT_GET, 'i');

// Obter dados do curso
$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();

$linha = mysqli_fetch_assoc($resultado);
$nome = $linha["nome_uc"];
$codigoUC = (int) $linha["codigo_uc"];
$sigla = $linha["abreviacao_uc"];
$ano = (int) $linha["ano"];
$semestre = (int) $linha["semestre"];
$idResponsavel = $linha["id_responsavel"];
$idArea = $linha["id_area"];
$idCurso = $linha["id_curso"];

$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $idCurso);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$numSemestres = (int) $linha["semestres"];
?>
<div class="modal-body">
    <form id="formEditarDisciplina" class="user" action="processamento/processarFormEditarDisciplina.php?i=<?php echo $id ?>" method="post">
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control form-control-user" name="nome" id="nome" value="<?php echo $nome ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="codigoUC">Código Unidade Curricular:</label>
                <input type="number" class="form-control form-control-user" name="codigoUC" id="codigoUC" value="<?php echo $codigoUC ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="nome">Sigla:</label>
                <input type="text" class="form-control form-control-user" name="sigla" id="sigla" value="<?php echo $sigla ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="ano">Ano:</label>
                <select name="ano" id="ano">
<?php
$numAnos = ceil( $numSemestres / 2 );

for( $i = 1; $i <= $numAnos; $i++){
?>
                    <option <?php if($ano == $i){ echo "selected"; }?> value="<?php echo $i ?>"><?php echo $i ?></option>                                           
<?php
}
?>
                </select>

            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="semestre">Semestre:</label>
                <select name="semestre" id="semestre">
                    <option <?php if($semestre == 1){ echo "selected"; }?> value="1">1</option>
                    <option <?php if($semestre == 2){ echo "selected"; }?> value="2">2</option>                                           
                </select>

            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <label for="area">Área:</label>
                <select name="area" id="area" onchange="gerarSelectResponsavel(this.value)">
                    <option value="0"></option>
<?php
// Buscar áreas
$statement = mysqli_prepare($conn, "SELECT * FROM area");
$statement->execute();
$resultado = $statement->get_result();

while($linha = mysqli_fetch_assoc($resultado)){
    $id = (int) $linha["id_area"];
    $nomeArea = $linha["nome"];

?>
                    <option <?php if($id == $idArea){ echo "selected"; }?> value="<?php echo $id ?>"><?php echo $nomeArea ?></option>
<?php
}
?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <div id="selectResponsavel">
<?php
if(!empty($idArea)){
?>
                    <label for="responsavel">Responsável:</label>
                    <select name="responsavel" id="responsavel">
                        <option value="0"></option>
<?php
    // Buscar ids de utilizadores que pertençam à área da disciplina
    $statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_area = ?");
    $statement->bind_param('i', $idArea);
    $statement->execute();
    $resultado = $statement->get_result();
    while($linha = mysqli_fetch_assoc($resultado)){
        $id = (int) $linha["id_utilizador"];
        $nomeResponsavel = $linha["nome"];

?>
                        <option <?php if($id == $idResponsavel){ echo "selected"; }?> value="<?php echo $id ?>"><?php echo $nomeResponsavel ?></option>
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
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="javascript:verificarFormEditarDisciplina()" class="btn btn-primary btn-lg">
        Editar
    </button>
</div>