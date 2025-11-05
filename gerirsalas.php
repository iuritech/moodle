<?php
session_start();

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
    exit();
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

// Recuperar salas organizadas por bloco
$sql = "SELECT sala.*, utc.* 
        FROM sala 
        LEFT JOIN sala_utc ON sala.id_sala = sala_utc.id_sala 
        LEFT JOIN utc ON sala_utc.id_utc = utc.id_utc 
        ORDER BY sala.bloco_sala, sala.nome_sala, sala.sigla_sala";

$result = $conn->query($sql);

$salasPorBloco = [];
while ($row = $result->fetch_assoc()) {
    $bloco = $row['bloco_sala'];
    if (!isset($salasPorBloco[$bloco])) {
        $salasPorBloco[$bloco] = [];
    }
    $salasPorBloco[$bloco][] = $row;
}

// Adicionar sala
if (isset($_POST['add_sala'])) {
    $nome_sala = $_POST['nome_sala'];
    $sigla_sala = $_POST['sigla_sala'];
    $bloco_sala = $_POST['bloco_sala'];
    $id_utc = $_POST['utc_sala'];


    $sql_sala = "INSERT INTO sala (nome_sala, sigla_sala, bloco_sala) VALUES ('$nome_sala','$sigla_sala', '$bloco_sala')";
    $conn->query($sql_sala);

    $id_sala = $conn->insert_id;

    $sql_rel = "INSERT INTO sala_utc (id_sala, id_utc) VALUES ($id_sala, $id_utc)";
    $conn->query($sql_rel);


    header("Location: gerirSalas.php");
    exit();
}

// Editar sala
if (isset($_POST['edit_sala'])) {
    $id_sala = intval($_POST['id_sala']); // Converte para número inteiro
    $sigla_sala = $_POST['sigla_sala'];
    $nome_sala = $_POST['nome_sala'];
    $bloco_sala = $_POST['bloco_sala'];
    $id_utc = $_POST['utc_sala'];

    // Atualizar os dados da sala
    $sql_sala = "UPDATE sala SET nome_sala='$nome_sala', sigla_sala ='$sigla_sala', bloco_sala='$bloco_sala' WHERE id_sala=$id_sala";
    $conn->query($sql_sala);

    // Verificar se já existe um relacionamento na tabela sala_utc
    $sql_check_rel = "SELECT * FROM sala_utc WHERE id_sala = $id_sala";
    $result = $conn->query($sql_check_rel);

    if ($result->num_rows > 0) {
        // Atualizar a relação existente
        $sql_update_rel = "UPDATE sala_utc SET id_utc = $id_utc WHERE id_sala = $id_sala";
        $conn->query($sql_update_rel);
    } else {
        // Inserir nova relação se não existir
        $sql_insert_rel = "INSERT INTO sala_utc (id_sala, id_utc) VALUES ($id_sala, $id_utc)";
        $conn->query($sql_insert_rel);
    }

    header("Location: gerirSalas.php");
    exit();
}


// Apagar sala
if (isset($_GET['delete_sala'])) {
    $id_sala = $_GET['delete_sala'];

    $sql = "DELETE FROM sala WHERE id_sala=$id_sala";
    $conn->query($sql);
    header("Location: gerirSalas.php");
    exit();
}
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
        </div>
        <!-- Botão para Adicionar Sala -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#salaModal" onclick="adicionarSala()">Adicionar Sala</button>

        <!-- Listagem de Salas por Bloco -->
        <?php foreach ($salasPorBloco as $bloco => $salas): ?>
            <div class="table-wrapper">
                <h3>Bloco <?= htmlspecialchars($bloco) ?></h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome da Sala</th>
                            <th>UTC</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salas as $sala): ?>
                            <tr>
                                <td><?= htmlspecialchars($sala['sigla_sala']) ?></td>
                                <td><?= htmlspecialchars($sala['nome_sala']) ?></td>
                                <td><?= htmlspecialchars($sala['nome_utc']) ?></td>
                                <td>
                                    <a class="btn btn-primary"  onclick="editarSala('<?= htmlspecialchars($sala['id_sala']) ?>','<?= htmlspecialchars($sala['sigla_sala']) ?>', '<?= htmlspecialchars($sala['nome_sala']) ?>', '<?= htmlspecialchars($sala['bloco_sala']) ?>')"><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i></a>
                                    <a  href="?delete_sala=<?= $sala['id_sala'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja apagar esta sala?')"><i class='material-icons' style='width:20px; height:19px; line-height:16px; float:left;'>delete</i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
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
