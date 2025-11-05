<?php
session_start();
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

// Segurança: só permite acesso autenticado
if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
    exit;
}

// Obter turmas disponíveis
$turmas = [];
$res = $conn->query("SELECT id_turma, nome FROM turma ORDER BY nome");
while ($row = $res->fetch_assoc()) $turmas[] = $row;

// Obter turmas selecionadas (pode ser array)
$id_turmas = isset($_GET['id_turma']) ? (is_array($_GET['id_turma']) ? $_GET['id_turma'] : [$_GET['id_turma']]) : [];
$id_turmas = array_filter($id_turmas, 'is_numeric'); // Só IDs válidos

// Obter semestre (opcional)
$semestre = isset($_GET['semestre']) ? intval($_GET['semestre']) : 1;

// Obter horários únicos (linhas: horas, colunas: dias)
$horas_unicas = [];
$dias_semana = ['SEG','TER','QUA','QUI','SEX'];
$res = $conn->query("SELECT DISTINCT hora_inicio, hora_fim FROM horario WHERE semestre=$semestre ORDER BY hora_inicio, hora_fim");
while ($row = $res->fetch_assoc()) {
    $horas_unicas[] = [$row['hora_inicio'], $row['hora_fim']];
}

// Mapear horários para cada dia/hora
$horario_map = [];
$horas_map = [
    '08:30-09:30', '09:30-10:30', '10:30-11:30', '11:30-12:30', '12:30-13:30',
    '13:30-14:30', '14:30-15:30', '15:30-16:30', '16:30-17:30', '17:30-18:30', '18:30-19:30'
];
$res = $conn->query("SELECT * FROM horario WHERE semestre=$semestre");
while ($row = $res->fetch_assoc()) {
    $horario_map[$row['dia_semana']][$row['hora_inicio'].'-'.$row['hora_fim']] = $row['id_horario'];
}

// Obter aulas para cada turma selecionada
$aulas_por_turma = [];
foreach ($id_turmas as $id_turma) {
    $sql = "SELECT a.id_horario, c.id_componente, d.nome_uc, tc.nome_tipocomponente, h.hora_inicio, h.hora_fim, h.dia_semana, u.nome
            FROM aula a
            JOIN componente c ON a.id_componente = c.id_componente
            JOIN disciplina d ON c.id_disciplina = d.id_disciplina
            JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
            JOIN turma t ON a.id_turma = t.id_turma
            JOIN horario h ON a.id_horario = h.id_horario
            JOIN utilizador u ON a.id_docente = u.id_utilizador
            WHERE a.id_turma = $id_turma AND h.semestre = $semestre";
    $res = $conn->query($sql);
    $aulas = [];
    while ($row = $res->fetch_assoc()) {
        $aulas[$row['id_horario']][] = $row;
    }
    $aulas_por_turma[$id_turma] = $aulas;
}

// Obter componentes/disciplinas de cada turma
$componentes_por_turma = [];
foreach ($id_turmas as $id_turma) {
    $sql = "SELECT c.id_componente, d.nome_uc, tc.nome_tipocomponente
            FROM componente c
            JOIN disciplina d ON c.id_disciplina = d.id_disciplina
            JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
            WHERE c.id_componente IN (SELECT id_componente FROM aula WHERE id_turma = $id_turma)
            GROUP BY c.id_componente";
    $res = $conn->query($sql);
    $componentes = [];
    while ($row = $res->fetch_assoc()) $componentes[] = $row;
    $componentes_por_turma[$id_turma] = $componentes;
}
?>
<!DOCTYPE html>
<?php gerarHome1() ?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Horário das Turmas (Drag & Drop)</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 7px; text-align: center; min-width:120px; }
        select { border: 2px solid #ddd; background: #eee; padding: 10px; transition: 0.4s; }
        select:hover, select:focus { background: #ddd; }
        .h3 { background: #ddd; }
        .ocupado { background:hsl(212,35%,33%); color: #fff; vertical-align: top; cursor: move; }
        .disponivel { background: #f9f9f9; cursor: pointer; }
        .hora-coluna { background: #e6e6e6; font-weight: bold; }
        .panel { margin-bottom: 30px; }
        .ui-draggable-dragging { opacity: 0.8; z-index: 9999; }
        .ui-state-hover { background: #d4edff !important; }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <div class="card shadow mb-4">
        <div class="card-body">
            <a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Horários</a> / <a href="">Gerir Horários</a></h6>
            <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Gerir Horário das Turmas</b></h3>
        </div>
        <form method="get">
            <label>Selecionar Turmas: (Ctrl+Clique para selecionar várias)
                <select name="id_turma[]" multiple onchange="this.form.submit()">
                    <?php foreach ($turmas as $t): ?>
                        <option value="<?= $t['id_turma'] ?>" <?= in_array($t['id_turma'], $id_turmas) ? 'selected' : '' ?>>
                        
                            <?= htmlspecialchars($t['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <input type="hidden" name="semestre" value="<?= $semestre ?>">
        </form>
    </div>
    <?php if (!empty($id_turmas)): ?>
        <?php foreach ($id_turmas as $id_turma): ?>
            <?php
            $nome_turma = '';
            foreach ($turmas as $t) {
                if ($t['id_turma'] == $id_turma) {
                    $nome_turma = $t['nome'];
                    break;
                }
            }
            ?>
            <div class="panel">
                <h3 style="margin-left:15px;">Horário da Turma <?= htmlspecialchars($nome_turma) ?></h3>
                <table class="horario-turma" data-id-turma="<?= $id_turma ?>">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <?php foreach($dias_semana as $dia): ?>
                                <th><?= $dia ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ocupados = [];
                        foreach ($dias_semana as $dia) $ocupados[$dia] = [];
                        ?>
                        <?php foreach($horas_unicas as $horaIndex => $hora): ?>
                            <?php if ($horaIndex >= count($horas_unicas) - 3) break; ?>
                            <tr>
                                <td class="hora-coluna"><?= isset($horas_map[$horaIndex]) ? $horas_map[$horaIndex] : $hora[0].'-'.$hora[1] ?></td>
                                <?php foreach($dias_semana as $dia): ?>
                                    <?php
                                    if (!empty($ocupados[$dia][$hora[0]])) continue;
                                    $chave_horario = $hora[0].'-'.$hora[1];
                                    $id_horario = isset($horario_map[$dia][$chave_horario]) ? $horario_map[$dia][$chave_horario] : null;
                                    $aulas = $aulas_por_turma[$id_turma] ?? [];
                                    ?>
                                    <?php if ($id_horario && isset($aulas[$id_horario])): ?>
                                        <?php
                                        $aula = $aulas[$id_horario][0];
                                        $nome_uc = htmlspecialchars($aula['nome_uc']);
                                        $nome_tipocomponente = htmlspecialchars($aula['nome_tipocomponente']);
                                        $nome_docente = htmlspecialchars($aula['nome']);
                                        $turmas = array_column($aulas[$id_horario], 'turma');
                                        $turmas_str = implode(', ', array_filter($turmas));
                                        $hora_inicio = strtotime($aula['hora_inicio']);
                                        $hora_fim = strtotime($aula['hora_fim']);
                                        $blocos = 1;
                                        for ($i = $horaIndex + 1; $i < count($horas_unicas); $i++) {
                                            $proxima_hora = strtotime($horas_unicas[$i][0]);
                                            if ($proxima_hora < $hora_fim) {
                                                $blocos++;
                                                $ocupados[$dia][$horas_unicas[$i][0]] = true;
                                            } else {
                                                break;
                                            }
                                        }
                                        ?>
                                        <td class="ocupado" rowspan="<?= $blocos ?>"
                                            data-id-horario="<?= $id_horario ?>"
                                            data-id-turma="<?= $id_turma ?>"
                                            data-id-componente="<?= $aula['id_componente'] ?>"
                                            data-id-docente="<?= $aula['id_docente'] ?? '' ?>"
                                            style="color:#fff;">
                                            <b><?= $nome_uc ?></b><br>
                                            <?= $nome_tipocomponente ?><br>
                                            <b>Professor:</b><br><?= $nome_docente ?><br>
                                            <?php if ($turmas_str): ?>
                                                Turma: <?= $turmas_str ?>
                                            <?php endif; ?>
                                        </td>
                                    <?php else: ?>
                                        <td class="disponivel"
                                            data-id-horario="<?= $id_horario ?>"
                                            data-id-turma="<?= $id_turma ?>"
                                            onclick="atribuirAula(<?= $id_turma ?>, <?= $id_horario ?>)">
                                        </td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="margin-top:10px; margin-left:15px;">
                    <h4>Disciplinas da Turma <?= htmlspecialchars($nome_turma) ?></h4>
                    <ul style="list-style-type:none; padding-left:0;">
                        <?php foreach ($componentes_por_turma[$id_turma] ?? [] as $c): ?>
                            <li style="margin-bottom:5px;">
                                <b><?= htmlspecialchars($c['nome_uc']) ?></b>
                                (<?= htmlspecialchars($c['nome_tipocomponente']) ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <script>
    $(function() {
        // Tornar aulas ocupadas arrastáveis
        $(".ocupado").draggable({
            helper: "clone",
            revert: "invalid",
            cursor: "move",
            opacity: 0.8,
            zIndex: 9999,
            appendTo: "body"
        });
        // Tornar células disponíveis largáveis
        $(".disponivel").droppable({
            accept: ".ocupado",
            hoverClass: "ui-state-hover",
            drop: function(event, ui) {
                var origem = $(ui.draggable);
                var destino = $(this);
                var id_componente = origem.data("id-componente");
                var id_horario = destino.data("id-horario");
                var id_turma_destino = destino.data("id-turma");
                var id_docente = origem.data("id-docente") || prompt("ID do docente (opcional)?");

                // AJAX para atualizar a base de dados
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "atualizarHorarioTESTE.php");
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    alert(xhr.responseText);
                    location.reload();
                };
                xhr.send("id_componente=" + encodeURIComponent(id_componente) +
                        "&id_horario=" + encodeURIComponent(id_horario) +
                        "&id_turma=" + encodeURIComponent(id_turma_destino) +
                        "&id_docente=" + encodeURIComponent(id_docente || ""));
            }
        });
    });
    function atribuirAula(id_turma, id_horario) {
        if (!id_horario) {
            alert("Horário não disponível para atribuição.");
            return;
        }
        var id_componente = prompt("ID do componente a atribuir?");
        if (!id_componente) return;
        var id_docente = prompt("ID do docente (opcional)?");
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "atualizarHorarioTESTE.php");
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            alert(xhr.responseText);
            location.reload();
        };
        xhr.send("id_componente=" + encodeURIComponent(id_componente) +
                "&id_horario=" + encodeURIComponent(id_horario) +
                "&id_turma=" + encodeURIComponent(id_turma) +
                "&id_docente=" + encodeURIComponent(id_docente || ""));
    }
    </script>
</body>
</html>
