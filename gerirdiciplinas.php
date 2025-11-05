<?php
session_start();

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$idUtilizador = (int) $_SESSION['id'];
$permAdmin = false;
$permUTC = false;
$permArea = false;
$coordenador_UTC = false;

if(isset($_SESSION['permAdmin'])){
    $permAdmin = true;
}
if(isset($_SESSION['permUTC'])){
    $permUTC = true;
}
if(isset($_SESSION['permArea'])){
    $permArea = true;
}

$sql = "SELECT * from utilizador";

$result = $conn->query($sql);
$docentes = $result->fetchAll(PDO::FETCH_ASSOC);



?>
<style>
.table th {
    background-color: rgb(57, 80, 117); /* Cor de fundo verde */
    color: white; /* Cor do texto branca */
    text-align: center; /* Alinhamento central */
}

</style>

<?php gerarHome1() ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Salas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    
</head>
<body>
<div class="container-fluid" style="padding-top:15px;">
        <div class="card shadow mb-4">
            <div class="card-body">
                <a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Salas</a> / <a href="">Gerir Salas</a></h6>
                <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Consultar Salas</b></h3>
            </div>
        </div><?php
        foreach($docentes as $docente) {
            echo"<h3>{$docente['nome']}</h3>"
            $stmt = " 
                    SELECT d.nome_uc 
                    FROM disciplina d 
                    JOIN utilizador_utc uu ON d.id_diciplina=uu.id_disciplina 
                    WHERE uu.id_utilizador=?;";
            $result = $conn->query($stmt);
            $disciplinas = $result->fetchAll(PDO::FETCH_ASSOC);


        }
   ?> </div>
    </div>

    <!-- Modal para Adicionar/Editar Sala -->
    <div class="modal fade" id="salaModal" tabindex="-1" role="dialog" aria-labelledby="salaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="salaForm" method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="salaModalLabel">Adicionar Sala</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_sala" id="id_sala">
                        <div class="form-group">
                            <label for="sigla_sala">Sigla da Sala</label>
                            <input type="text" class="form-control" id="sigla_sala" name="sigla_sala" required>
                        </div>
                        <div class="form-group">
                            <label for="nome_sala">Nome da Sala</label>
                            <input type="text" class="form-control" id="nome_sala" name="nome_sala" required>
                        </div>
                        <div class="form-group">
                            <label for="bloco_sala">Bloco</label>
                            <select class="form-control" id="bloco_sala" name="bloco_sala" required>
                            <?php
                            $sql = "SELECT bloco_sala FROM sala GROUP BY bloco_sala";
                            $bloco_sala = $conn->query($sql);
                            while ($bloco = $bloco_sala->fetch_assoc()) {
                                $selected = $bloco['bloco_sala'] == $row['bloco_sala'] ? 'selected' : '';
                                echo "<option value='{$bloco['bloco_sala']}' $selected>{$bloco['bloco_sala']}</option>";
                            }
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                        <label for="utc_sala">UTC</label>
                        <select class="form-control" id="utc_sala" name="utc_sala" required>
                        <?php
                              $sql = "SELECT id_utc, nome_utc FROM utc";
                              $utcs = $conn->query($sql);
                              while ($utc = $utcs->fetch_assoc()) {
                              echo "<option value='{$utc['id_utc']}'>{$utc['nome_utc']}</option>";
                               }
                               ?>
                 </select>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="salaSubmitBtn">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function adicionarSala() {
            document.getElementById('salaForm').reset();
            document.getElementById('salaModalLabel').textContent = 'Adicionar Sala';
            document.getElementById('salaSubmitBtn').name = 'add_sala';
        }

        function editarSala(id, sigla, nome, bloco) {
         document.getElementById('id_sala').value = id;
         document.getElementById('sigla_sala').value = sigla;
         document.getElementById('nome_sala').value = nome;
         document.getElementById('bloco_sala').value = bloco;
         document.getElementById('salaModalLabel').textContent = 'Editar Sala';
         document.getElementById('salaSubmitBtn').name = 'edit_sala';
    $('#salaModal').modal('show');
}

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
