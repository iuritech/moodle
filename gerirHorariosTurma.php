
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

// Obter docentes disponíveis
$turma = [];
$res = $conn->query("SELECT id_turma, nome FROM turma");
while ($row = $res->fetch_assoc()) $turma[] = $row;

// Obter docente selecionado
$id_turmas = isset($_GET['id_turma']) ? intval($_GET['id_turma']) : 0;

// Obter semestre (opcional)
$semestre = isset($_GET['semestre']) ? intval($_GET['semestre']) : 1;

// Obter horários únicos (linhas: horas, colunas: dias)
$horarios = [];
$horas_unicas = [];
$dias_semana = ['SEG','TER','QUA','QUI','SEX'];
$res = $conn->query("SELECT DISTINCT hora_inicio, hora_fim FROM horario WHERE semestre=$semestre ORDER BY hora_inicio, hora_fim");
while ($row = $res->fetch_assoc()) {
    $horas_unicas[] = [$row['hora_inicio'], $row['hora_fim']];
}

// Inicializar array de horários mapeados
$horario_map = [];
$horas_map = [
    '08:30-09:30', '09:30-10:30', '10:30-11:30', '11:30-12:30', '12:30-13:30',
    '13:30-14:30', '14:30-15:30', '15:30-16:30', '16:30-17:30', '17:30-18:30', '18:30-19:30'
];

// Obter todos os horários para cruzamento rápido
$res = $conn->query("SELECT * FROM horario WHERE semestre=$semestre");
while ($row = $res->fetch_assoc()) {
    $horario_map[$row['dia_semana']][$row['hora_inicio'].'-'.$row['hora_fim']] = $row['id_horario'];
}

// Obter aulas da turma
$aulas = [];
if ($id_turmas) {
    $sql = "SELECT a.id_horario, c.id_componente, d.nome_uc, tc.nome_tipocomponente, h.hora_inicio, h.hora_fim, h.dia_semana, u.nome
FROM aula a
JOIN componente c ON a.id_componente = c.id_componente
JOIN disciplina d ON c.id_disciplina = d.id_disciplina
JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
JOIN turma t ON a.id_turma = t.id_turma
JOIN horario h ON a.id_horario = h.id_horario
JOIN utilizador u ON a.id_docente= u.id_utilizador
WHERE a.id_turma = $id_turmas AND h.semestre = $semestre";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $aulas[$row['id_horario']][] = $row;
    }
}

// Obter componentes/disciplina do docente
$componentes = [];
if ($id_turmas) {
    $sql = "SELECT c.id_componente, d.nome_uc, tc.nome_tipocomponente
            FROM componente c
            JOIN disciplina d ON c.id_disciplina = d.id_disciplina
            JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
            WHERE c.id_componente IN (SELECT id_componente FROM aula WHERE id_turma = $id_turmas)
            GROUP BY c.id_componente";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) $componentes[] = $row;
}

// Inicializar array para controlar células ocupadas por rowspan
$ocupados = [];
foreach ($dias_semana as $dia) {
    $ocupados[$dia] = [];
}
?>
<!DOCTYPE html>
<?php gerarHome1() ?>
<main style="padding-top:15px; height:790px; width:1600px;">
<html lang="pt">
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerir Horário Turma</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 7px; text-align: center; min-width:120px; }
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
        .h3 { background: #ddd;}
        .ocupado { background:hsl(212,35%,33%); vertical-align: top; }
        .disponivel { background: #f9f9f9; cursor: pointer; }
        .hora-coluna { background: #e6e6e6; font-weight: bold; }
    </style>
</head>
<body>
     <div class="card shadow mb-4">
            <div class="card-body">
                <a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Horarios</a> / <a href="">Gerir Horarios</a></h6>
                <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Gerir Horário da Turma </b></h3>
            </div>
        
    <form method="get">
        <label>Selecionar Turma:
            <select name="id_turma" onchange="this.form.submit()">
    <option value="">-- Escolha --</option>
    <?php foreach ($turma as $t): ?>
        <option value="<?= $t['id_turma'] ?>" <?= $id_turmas == $t['id_turma'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($t['nome']) ?>
        </option>
    <?php endforeach; ?>
</select>
            <input type="hidden" name="semestre" value="<?= $semestre ?>">
        </label>

    </form>
    </div>
    <?php if ($id_turmas): ?>
        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <?php foreach($dias_semana as $dia): ?>
                        <th><?= $dia ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($horas_unicas as $horaIndex => $hora): ?>
                        <?php if ($horaIndex >= count($horas_unicas) - 3) break; ?>
                    <tr>
                        <td class="hora-coluna"><?= isset($horas_map[$horaIndex]) ? $horas_map[$horaIndex] : $hora[0].'-'.$hora[1] ?></td>
                        <?php foreach($dias_semana as $dia): ?>
                            <?php
                            // Verificar se esta célula já foi ocupada por um rowspan anterior
                            if (!empty($ocupados[$dia][$hora[0]])) {
                                continue;
                            }

                            $chave_horario = $hora[0].'-'.$hora[1];
                            $id_horario = isset($horario_map[$dia][$chave_horario]) ? $horario_map[$dia][$chave_horario] : null;
                            
                            if ($id_horario && isset($aulas[$id_horario])) {
                                $aula = $aulas[$id_horario][0];
                                $nome_uc = htmlspecialchars($aula['nome_uc']);
                                $nome_tipocomponente = htmlspecialchars($aula['nome_tipocomponente']);
                                $nome_doc = htmlspecialchars($aula['nome']);
                                $turmas = array_column($aulas[$id_horario], 'turma');
                                $turmas_str = implode(', ', array_filter($turmas));

                                // Calcular rowspan baseado na duração
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

                                echo "<td class='ocupado' rowspan='{$blocos}'<text style= 'color:#e7e8eb;'>";
                                echo "<b>{$nome_uc}</b><br>";
                                echo "{$nome_tipocomponente}<br>";
                                echo "<b>Professor:</b><br>{$nome_doc}<br>";
                                if ($turmas_str) echo "Turma: {$turmas_str}";
                                echo "</td>";
                            } else {
                                echo "<td class='disponivel' data-id_horario='$id_horario'</td>";
                            }
                            ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3>Disciplinas</h3>
        <?php 
        $turmas_por_componente = [];
        foreach ($aulas as $aulas_horario) {
            foreach ($aulas_horario as $aula) {
                $id_componente = $aula['id_componente'];
                if ($turma && !in_array($turma, $turmas_por_componente[$id_componente] ?? [])) {
                    $turmas_por_componente[$id_componente][] = $turma;
                }
            }
        }

        // Adicionar as turmas ao array de componentes
        foreach ($componentes as &$c) {
            $id_componente = $c['id_componente'];
            $c['turmas'] = $turmas_por_componente[$id_componente] ?? [];
        }
        unset($c); 
        ?>
        <ul class="list-group">
            <?php foreach ($componentes as $c): ?>
                <li class="list-group-item">
                    <b><?= htmlspecialchars($c['nome_uc']) ?></b> 
                    (<?= htmlspecialchars($c['nome_tipocomponente']) ?>) 
                    <?php if (!empty($c['id_turma'])): ?>
                        (Turmas: <?= implode(', ', $c['turmas']) ?>)
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <script>
        function atribuirAula(id_docente, id_horario) {
            if (!id_horario) {
                alert("Horário não disponível para atribuição.");
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
    <?php endif; ?>
</body>
</html>
