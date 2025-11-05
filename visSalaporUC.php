<?php
// Página de visualização das atribuições das salas (Atribuição)

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

if (isset($_SESSION['permAdmin'])) {
    $permAdmin = true;
}
if (isset($_SESSION['permUTC'])) {
    $permUTC = true;
}
if (isset($_SESSION['permArea'])) {
    $permArea = true;
}

$idUTCUtilizador = 0;
$idAreaUtilizador = 0;

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
$statement->bind_param('i', $idUtilizador);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
if (!empty($linha["id_area"])) {
    $idAreaUtilizador = (int) $linha["id_area"];

    $statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = ?");
    $statement->bind_param('i', $idAreaUtilizador);
    $statement->execute();
    $resultado = $statement->get_result();
    $linha = mysqli_fetch_assoc($resultado);
    $idUTCUtilizador = (int) $linha["id_utc"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Salas</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php gerarHome1() ?>

    <main style="padding-top:15px;">
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <a href="http://localhost/apoio_utc/home.php">
                        <h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador
                    </a> / <a href="">Salas</a> / <a href="">Atribuição de Salas</a></h6>
                    <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Atribuição de Salas</b></h3>
                </div>
            </div>
        </div>

        <!-- Tabela -->
        <div class="table-responsive" style="width:97%; margin-left:15px;">
            <table class="table table-striped" id="tabelaSalas" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Ano</th>
                        <th>Disciplina</th>
                        <th>ID Disciplina</th>
                        <th>Turma</th>
                        <th>Tipo</th>
                        <th>Salas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "
                    SELECT 
                    d.id_disciplina,
                    d.nome_uc AS disciplina,
                    tc.nome_tipocomponente AS tipo,
                    t.nome AS turma,
                    c.id_componente,
                    cur.nome AS curso,
                    d.ano AS ano,
                    s.sigla_sala AS sala_atual,
                    sca.id_sala AS id_sala_atribuida
                    FROM componente c
                    JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                    JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                    JOIN aula a ON c.id_componente = a.id_componente
                    JOIN turma t ON a.id_turma = t.id_turma
                    JOIN curso cur ON d.id_curso = cur.id_curso
                    LEFT JOIN sala_componente_disponivel sca ON c.id_componente = sca.id_componente
                    LEFT JOIN sala s ON sca.id_sala = s.id_sala;";

                    $statement = mysqli_prepare($conn, $query);

                    if ($statement) {
                        $statement->execute();
                        $resultado = $statement->get_result();

                        while ($linha = $resultado->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($linha['curso']); ?></td>
                                <td><?php echo htmlspecialchars($linha['ano']); ?></td>
                                <td><?php echo htmlspecialchars($linha['disciplina']); ?></td>
                                <td><?php echo htmlspecialchars($linha['id_disciplina']); ?></td>
                                <td><?php echo htmlspecialchars($linha['turma']); ?></td>
                                <td><?php echo htmlspecialchars($linha['tipo']); ?></td>
                                <td><button class="btn btn-primary btn-edit-sala" data-id_componente="<?php echo htmlspecialchars($linha['id_componente']); ?>"
                                        title="Editar Sala"><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i>
                                    </button></td>

                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='8'>ERRO</td></tr>";
                    }
                    ?>
                </tbody>

                <!-- Modal -->
                <div class="modal fade" id="editarSalaModal" tabindex="-1" role="dialog" aria-labelledby="editarSalaLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarSalaLabel">Editar Sala</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formEditarSala">
                                    <div class="form-group">
                                        <label>Selecione as salas</label>
                                        <div id="checkboxContainer">
                                            <?php
                                            // Consultar as salas disponíveis
                                            $query = "SELECT id_sala, sigla_sala FROM sala";
                                            $result = mysqli_query($conn, $query); // Executa a consulta

                                            if ($result) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<div class="form-check">';
                                                    echo '<input class="form-check-input" type="checkbox" name="salas[]" value="' . htmlspecialchars($row['id_sala']) . '" id="sala_' . htmlspecialchars($row['id_sala']) . '">';
                                                    echo '<label class="form-check-label" for="sala_' . htmlspecialchars($row['id_sala']) . '">' . htmlspecialchars($row['sigla_sala']) . '</label>';
                                                    echo '</div>';
                                                }
                                            } else {
                                                echo '<p>Erro ao carregar salas.</p>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <input type="hidden" id="componenteSelecionado" name="componenteSelecionado">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" onclick="salvarSala()">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        const idComponente = $(this).data('id_componente');

                        $('.btn-edit-sala').on('click', function() {
                            const idComponente = $(this).closest('tr').find('td:nth-child(4)').text().trim(); // procura o ID no TD correto
                            $('#componenteSelecionado').val(idComponente); // Define o valor no campo oculto do modal
                            carregarSalasDisponiveis(idComponente);
                            $('#editarSalaModal').modal('show'); // Exibe o modal
                        });

                    });

                    let salasSelecionadasAntes = [];
                    // Ao abrir o modal, carregue as salas já atribuídas
                    $('.btn-edit-sala').on('click', function() {
                        const idComponente = $(this).data('id_componente');
                        $('#componenteSelecionado').val(idComponente);

                        // Limpar o estado anterior
                        salasSelecionadasAntes = [];
                        carregarSalasDisponiveis(idComponente);
                        $('#editarSalaModal').modal('show');
                    });

                    function carregarSalasDisponiveis(idComponente) {
                        $.ajax({
                            url: 'getSala.php',
                            method: 'GET',
                            data: {
                                id_componente: idComponente
                            },
                            success: function(response) {
                                try {
                                    const salas = JSON.parse(response);
                                    const checkboxContainer = $('#checkboxContainer');
                                    checkboxContainer.empty();

                                    salas.forEach(function(sala) {
                                        const isChecked = sala.atribuida ? 'checked' : '';
                                        const checkboxHTML = `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="salas[]" 
                                   value="${sala.id_sala}" 
                                   id="sala_${sala.id_sala}" ${isChecked}>
                            <label class="form-check-label" for="sala_${sala.id_sala}">
                                ${sala.nome_sala}
                            </label>
                        </div>
                    `;
                                        checkboxContainer.append(checkboxHTML);
                                    });
                                } catch (error) {
                                    console.error('Erro ao interpretar a resposta:', error);
                                    alert('Erro ao carregar as salas disponíveis.');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Erro ao carregar salas:', error);
                                alert('Erro ao conectar-se ao servidor.');
                            }
                        });
                    }

                    function salvarSala() {
                        const salasSelecionadasAtuais = [];
                        const componenteSelecionado = $('#componenteSelecionado').val();

                        // Captura as salas selecionadas
                        $('input[name="salas[]"]:checked').each(function() {
                            salasSelecionadasAtuais.push($(this).val());
                        });

                        // Validação 
                        if (!componenteSelecionado || salasSelecionadasAtuais.length === 0) {
                            alert('Erro: Certifique-se de selecionar pelo menos uma sala e um componente.');
                            return;
                        }

                        // Envia os dados via AJAX
                        $.ajax({
                            url: 'atualizasalas.php',
                            method: 'POST',
                            data: {
                                salas: salasSelecionadasAtuais,
                                componente: componenteSelecionado
                            },
                            success: function(response) {
                                try {
                                    const result = JSON.parse(response);
                                    if (result.success) {
                                        alert(result.message);
                                        $('#editarSalaModal').modal('hide');
                                    } else {
                                        alert(result.message);
                                    }
                                } catch (error) {
                                    console.error('Erro ao interpretar a resposta:', error);
                                    alert('Erro inesperado no servidor.');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Erro ao salvar salas:', error);
                                alert('Erro ao conectar-se ao servidor.');
                            }
                        });
                    }
                </script>
                <script>
                    // dataTables jQuery plugin
                    $(document).ready(function() {
                        $('#tabelaSalas').DataTable({
                            responsive: true,
                            pageLength: 25,


                            order: [0, 'asc'],

                            "language": {
                                "lengthMenu": "Ver _MENU_ salas por página",
                                "zeroRecords": "Nenhum registo encontrado",
                                "info": "Página _PAGE_ de _PAGES_",
                                "infoEmpty": "Nenhum registo encontrado",
                                "infoFiltered": "(pesquisa de _MAX_ registos totais)",
                                "search": "Filtrar:",
                                "paginate": {
                                    "first": "Primeiro",
                                    "last": "Último",
                                    "next": "Próximo",
                                    "previous": "Anterior"
                                },
                            }
                        });
                    });
                </script>
                <?php gerarHome2() ?>
    </main>

</body>

</html>