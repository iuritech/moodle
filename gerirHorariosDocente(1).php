<?php
session_start();
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

// Segurança: so permite acesso autenticado
if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
    exit;
}

// Obter docentes disponi­veis
$docentes = [];
$res = $conn->query("SELECT id_utilizador, nome FROM utilizador WHERE id_funcao IN (4,5,6) ORDER BY nome");
while ($row = $res->fetch_assoc())
    $docentes[] = $row;

// Obter docentes selecionados (pode ser array)
$id_docentes = isset($_GET['id_docente']) ? (is_array($_GET['id_docente']) ? $_GET['id_docente'] : [$_GET['id_docente']]) : [];
$id_docentes = array_filter($id_docentes, 'is_numeric'); // Sao ids validos

// Obter semestre (opcional)
$semestre = isset($_GET['semestre']) ? intval($_GET['semestre']) : 1;

// Obter horarios (linhas: horas, colunas: dias)
$horarios = [];
$horas_unicas = [];
$dias_semana = ['SEG', 'TER', 'QUA', 'QUI', 'SEX'];
$res = $conn->query("SELECT DISTINCT hora_inicio, hora_fim FROM horario WHERE semestre=$semestre ORDER BY hora_inicio, hora_fim");
while ($row = $res->fetch_assoc()) {
    $horas_unicas[] = [$row['hora_inicio'], $row['hora_fim']];
}

// Inicializar array de horarios mapeados
$horario_map = [];
$horas_map = [
    '08:30-09:30',
    '09:30-10:30',
    '10:30-11:30',
    '11:30-12:30',
    '12:30-13:30',
    '13:30-14:30',
    '14:30-15:30',
    '15:30-16:30',
    '16:30-17:30',
    '17:30-18:30',
    '18:30-19:30'
];

// Obter todos os horÃ¡rios para cruzamento rapido
$res = $conn->query("SELECT * FROM horario WHERE semestre=$semestre");
while ($row = $res->fetch_assoc()) {
    $horario_map[$row['dia_semana']][$row['hora_inicio'] . '-' . $row['hora_fim']] = $row['id_horario'];
}

// Obter aulas para cada docente selecionado
$aulas_por_docente = [];
foreach ($id_docentes as $id_docente) {
    $sql = "SELECT a.id_horario, a.id_componente, a.id_turma, d.nome_uc, tc.nome_tipocomponente, t.nome AS turma, h.hora_inicio, h.hora_fim, h.dia_semana
            FROM aula a
            JOIN componente c ON a.id_componente = c.id_componente
            JOIN disciplina d ON c.id_disciplina = d.id_disciplina
            JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
            LEFT JOIN turma t ON a.id_turma = t.id_turma
            JOIN horario h ON a.id_horario = h.id_horario
            WHERE a.id_docente = $id_docente AND h.semestre = $semestre";
    $res = $conn->query($sql);
    $aulas = [];
    while ($row = $res->fetch_assoc()) {
        $aulas[$row['id_horario']][] = $row;
    }
    $aulas_por_docente[$id_docente] = $aulas;
}

// Obter componentes para cada docente (opcional, para lista lateral)
$componentes_por_docente = [];
foreach ($id_docentes as $id_docente) {
    $sql = "SELECT c.id_componente, d.nome_uc, tc.nome_tipocomponente, a.id_turma
        FROM componente c
        JOIN disciplina d ON c.id_disciplina = d.id_disciplina
        JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
        JOIN aula a ON c.id_componente = a.id_componente
        WHERE a.id_docente = $id_docente
        GROUP BY c.id_componente, a.id_turma";


    $res = $conn->query($sql);
    $componentes = [];
    while ($row = $res->fetch_assoc())
        $componentes[] = $row;
    $componentes_por_docente[$id_docente] = $componentes;
}
$ocupados = [];
foreach ($dias_semana as $dia) {
    $ocupados[$dia] = [];
}
?>
<!DOCTYPE html>
<?php gerarHome1() ?>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Horarios Docente</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 7px;
            text-align: center;
            min-width: 120px;
        }

        select {
            border: 2px solid #ddd;
            background: #eee;
            padding: 10px;
            transition: 0.4s;
        }

        select:hover,
        select:focus {
            background: #ddd;
        }

        .h3 {
            background: #ddd;
        }

        .ocupado {
            background: hsl(212, 35%, 33%);
            color: #fff;
            vertical-align: top;
            cursor: move;
        }

        .disponivel {
            background: #f9f9f9;
            cursor: pointer;
        }

        .hora-coluna {
            background: #e6e6e6;
            font-weight: bold;
        }

        .panel {
            margin-bottom: 30px;
        }

        .ui-draggable-dragging {
            opacity: 0.8;
            z-index: 9999;
        }

        .ui-state-hover {
            background: #d4edff !important;
        }

        .disciplina-draggable {
            background: #e6e6e6;
            border: 1px solid #ccc;
            margin-bottom: 8px;
            padding: 8px;
            cursor: move;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .disciplina-draggable:hover {
            background: #d4edff;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

<body>
    <div class="card shadow mb-4">
        <div class="card-body">
            <a href="http://localhost/apoio_utc/home.php">
                <h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador
            </a> / <a href="">Horarios</a> / <a href="">Gerir Horarios</a></h6>
            <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Gerir Horarios Docente</b></h3>
        </div>
        <form method="get">
            <label>Selecionar Docentes: (Ctrl+Clique para selecionar varios)
                <select name="id_docente[]" multiple onchange="this.form.submit()">
                    <?php foreach ($docentes as $d): ?>
                        <option value="<?= $d['id_utilizador'] ?>" <?= in_array($d['id_utilizador'], $id_docentes) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <input type="hidden" name="semestre" value="<?= $semestre ?>">
        </form>
    </div>
    <div style="display:flex;">
        <!-- Lista lateral de disciplinas -->
        <div id="disciplinas-lista" style="width:220px; margin-right:30px;">
            <h4>Disciplinas</h4>
            <?php foreach ($componentes_por_docente[$id_docente] as $c): ?>
                <div class="disciplina-draggable" data-id_componente="<?= $c['id_componente'] ?>"
                    data-id_turma="<?= $c['id_turma'] ?>">

                    <style="background:#e6e6e6; border:1px solid #ccc; margin-bottom:8px; padding:8px; cursor:move;">
                        <b><?= htmlspecialchars($c['nome_uc']) ?></b>
                        (<?= htmlspecialchars($c['nome_tipocomponente']) ?>)
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($id_docentes)): ?>
            <?php foreach ($id_docentes as $idx => $id_docente): ?>
                <?php
                $nome_docente = '';
                foreach ($docentes as $d) {
                    if ($d['id_utilizador'] == $id_docente) {
                        $nome_docente = $d['nome'];
                        break;
                    }
                }
                ?>
                <div class="panel" data-id_docente="<?= $id_docente ?>">

                    <h3 style="margin-left:15px;">Horário de <?= htmlspecialchars($nome_docente) ?></h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <?php foreach ($dias_semana as $dia): ?>
                                    <th><?= $dia ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($horas_unicas as $horaIndex => $hora): ?>
                                <?php if ($horaIndex >= count($horas_unicas) - 3)
                                    break; ?>
                                <tr>
                                    <td class="hora-coluna">
                                        <?= isset($horas_map[$horaIndex]) ? $horas_map[$horaIndex] : $hora[0] . '-' . $hora[1] ?></td>
                                    <?php foreach ($dias_semana as $dia): ?>
                                        <?php
                                        //if (!empty($ocupados[$dia][$hora[0]])) continue;
                                        if (isset($ocupados[$dia][$hora[0]]) && $ocupados[$dia][$hora[0]] === true)
                                            continue;
                                        $chave_horario = $hora[0] . '-' . $hora[1];
                                        $id_horario = isset($horario_map[$dia][$chave_horario]) ? $horario_map[$dia][$chave_horario] : null;
                                        $aulas = $aulas_por_docente[$id_docente] ?? [];

                                        if ($id_horario && isset($aulas[$id_horario])) {
                                            $aula = $aulas[$id_horario][0];
                                            $nome_uc = htmlspecialchars($aula['nome_uc']);
                                            $nome_tipocomponente = htmlspecialchars($aula['nome_tipocomponente']);
                                            $turmas = array_column($aulas[$id_horario], 'turma');
                                            $turmas_str = implode(', ', array_filter($turmas));

                                            // Calcular duração real da aula
                                            $hora_inicio = new DateTime($aula['hora_inicio']);
                                            $hora_fim = new DateTime($aula['hora_fim']);
                                            $duracao = $hora_inicio->diff($hora_fim);
                                            $horas_duracao = $duracao->h + ($duracao->i / 60);

                                            // Determinar quantos blocos de 1 hora a aula ocupa
                                            $blocos = ceil($horas_duracao);

                                            // Marcar os blocos ocupados
                                            for ($i = 0; $i < $blocos; $i++) {
                                                if ($horaIndex + $i < count($horas_unicas)) {
                                                    $ocupados[$dia][$horas_unicas[$horaIndex + $i][0]] = true;
                                                }
                                            }

                                            echo "<td class='ocupado' rowspan='{$blocos}' style='color:#e7e8eb;'
                                                    data-id_componente='{$aula['id_componente']}'
                                                    data-id_horario='{$aula['id_horario']}'
                                                    data-id_turma='{$aula['id_turma']}'
                                                    data-id_docente='{$id_docente}'>
                                                    <b>{$nome_uc}</b><br>
                                                    {$nome_tipocomponente}<br>";
                                            if ($turmas_str)
                                                echo "Turma: {$turmas_str}";
                                            echo "</td>";
                                        } else {
                                            echo "<td class='disponivel' data-id_horario='$id_horario' onclick='atribuirAula($id_docente, $id_horario)'></td>";
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="margin-top:10px; margin-left:15px;">
                        <h4>Disciplinas de <?= htmlspecialchars($nome_docente) ?></h4>
                        <ul style="list-style-type:none; padding-left:0;">
                            <?php foreach ($componentes_por_docente[$id_docente] ?? [] as $c): ?>
                                <li style="margin-bottom:5px;">
                                    <b><?= htmlspecialchars($c['nome_uc']) ?></b>
                                    (<?= htmlspecialchars($c['nome_tipocomponente']) ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="disciplina-draggable" data-id_componente="<?= $c['id_componente'] ?>"
                    data-id_turma="<?= $c['id_turma'] ?>">

                <?php endforeach; ?>
            <?php endif; ?>
    <script>
    $(function() {
        // Torna as disciplinas arrastáveis
        $(".disciplina-draggable").draggable({
            helper: "clone",
            revert: "invalid",
            cursor: "move",
            opacity: 0.8,
            zIndex: 9999,
            appendTo: "body"
        });

        $(".ocupado").draggable({
            helper: "clone",
            revert: "invalid",
            cursor: "move",
            opacity: 0.8,
            zIndex: 9999,
            appendTo: "body"
        });

        // Área para soltar (fora da tabela)
        $("#disciplinas-lista").droppable({
            accept: ".ocupado",
            hoverClass: "ui-state-highlight",
            drop: function(event, ui) {
                var data = {
                    action: 'remove',
                    id_componente: ui.draggable.data("id_componente"),
                    id_horario: ui.draggable.data("id_horario"),
                    id_docente: ui.draggable.data("id_docente"),
                    id_turma: ui.draggable.data("id_turma")
                };
                
                if (confirm("Tem certeza que deseja remover esta aula?")) {
                    atualizarHorario(data);
                }
            }
        });

        // Torna as células disponíveis largáveis
        $(".disponivel").droppable({
            accept: ".disciplina-draggable, .ocupado",
            hoverClass: "ui-state-hover",
            drop: function(event, ui) {
                var id_docente = $(this).closest('.panel').data('id_docente');
                var id_componente = ui.draggable.data("id_componente");
                var id_turma = ui.draggable.data("id_turma"); // já vem do HTML
                var id_horario = $(this).data("id_horario");
                var data= {}; 

                console.log({
                    id_componente: id_componente,
                    id_turma: id_turma,
                    id_horario: id_horario,
                    id_docente: id_docente
                });

                if (!id_horario) {
                    alert("Horário inválido. Não é possível atribuir aula neste slot.");
                    return;
                }

                if(ui.draggable.hasClass("disciplina-draggable")) {
                    data = {
                        action: "add",
                        id_componente: id_componente,
                        id_horario: id_horario,
                        id_docente: id_docente,
                        id_turma: id_turma
                    };
                }
                else if(ui.draggable.hasClass("ocupado")) {
                    data = {
                        action: "move",
                        id_componente: id_componente,
                        id_horario_antigo: ui.draggable.data("id_horario"),
                        id_horario: id_horario,
                        id_turma: id_turma,
                        id_docente: id_docente
                    };
                }

                atualizarHorario(data);
            }
        });
    });

    function atualizarHorario(data) {
        $.post("atualizarHorarioTESTE.php", data, function(response) {
            alert(response);
            location.reload();
        });
    }

    function atribuirAula(id_docente, id_horario) {
        if (!id_horario) {
            alert("Hora não disponivel");
            return;
        }
        var id_componente = prompt("ID do componente a atribuir?");
        if (!id_componente) return;
        var id_turma = prompt("ID da turma (opcional)?");
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "atualizarHorarioTESTE.php");
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            alert(xhr.responseText);
            location.reload();
        };
        xhr.send("id_componente=" + encodeURIComponent(id_componente) +
                "&id_horario=" + encodeURIComponent(id_horario) +
                "&id_docente=" + encodeURIComponent(id_docente) +
                "&id_turma=" + encodeURIComponent(id_turma || ""));
    }
    </script>

</body>

</html>