<?php
session_start();
include('ferramentas.php');
include('bd.h');
include('bd_final.php');
require_once('vendor/autoload.php'); // PhpSpreadsheet e Dompdf
require_once('dompdf/dompdf/autoload.inc.php');


use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Segurança: só permite acesso autenticado
if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
    exit;
}

// Obter semestre (opcional)
$semestre_atual = isset($_GET['sem']) ? intval($_GET['sem']) : 1;

// Obter tipo de horário a imprimir (docente, turma, sala)
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Exportar para Excel (PhpSpreadsheet)
if (isset($_GET['excel']) && $tipo && $id) {
    // Cabeçalhos e dados
    $horas_unicas = [
        ['08:30:00', '09:30:00'],
        ['09:30:00', '10:30:00'],
        ['10:30:00', '11:30:00'],
        ['11:30:00', '12:30:00'],
        ['12:30:00', '13:30:00'],
        ['13:30:00', '14:30:00'],
        ['14:30:00', '15:30:00'],
        ['15:30:00', '16:30:00'],
        ['16:30:00', '17:30:00'],
        ['17:30:00', '18:30:00'],
        ['18:30:00', '19:30:00'],
    ];
    $dias_semana = ['SEG','TER','QUA','QUI','SEX'];

    // Buscar aulas conforme o tipo
    if ($tipo == 'docente') {
        $sql = "SELECT a.id_horario, a.id_componente, d.nome_uc, tc.nome_tipocomponente, t.nome AS turma, h.hora_inicio, h.hora_fim, h.dia_semana
                FROM aula a
                JOIN componente c ON a.id_componente = c.id_componente
                JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                LEFT JOIN turma t ON a.id_turma = t.id_turma
                JOIN horario h ON a.id_horario = h.id_horario
                WHERE a.id_docente = $id AND h.semestre = $semestre_atual";
    } elseif ($tipo == 'turma') {
        $sql = "SELECT a.id_horario, a.id_componente, d.nome_uc, tc.nome_tipocomponente, u.nome AS nome_docente, h.hora_inicio, h.hora_fim, h.dia_semana
                FROM aula a
                JOIN componente c ON a.id_componente = c.id_componente
                JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                LEFT JOIN utilizador u ON a.id_docente = u.id_utilizador
                JOIN horario h ON a.id_horario = h.id_horario
                WHERE a.id_turma = $id AND h.semestre = $semestre_atual";
    } elseif ($tipo == 'sala') {
        $sql = "SELECT a.id_componente, h.dia_semana, h.hora_inicio, h.hora_fim, d.nome_uc, tc.nome_tipocomponente, c.id_tipocomponente
                FROM aula a
                JOIN horario h ON a.id_horario = h.id_horario
                JOIN componente c ON a.id_componente = c.id_componente
                JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                WHERE h.id_sala = $id";
    }
    $res = $conn->query($sql);
    // Organizar aulas por dia
    $aulas = [];
    while ($row = $res->fetch_assoc()) {
        $aulas[$row['dia_semana']][] = $row;
    }

    // Criar Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Cabeçalhos
    $sheet->setCellValue('A1', 'Hora');
    $sheet->setCellValue('B1', 'SEG');
    $sheet->setCellValue('C1', 'TER');
    $sheet->setCellValue('D1', 'QUA');
    $sheet->setCellValue('E1', 'QUI');
    $sheet->setCellValue('F1', 'SEX');

    // Preencher linhas
    $linha = 2;
    foreach ($horas_unicas as $hora) {
        $hora_inicio = $hora[0];
        $hora_fim = $hora[1];
        $hora_label = substr($hora_inicio, 0, 5) . " - " . substr($hora_fim, 0, 5);
        $sheet->setCellValue('A' . $linha, $hora_label);
        $coluna = 'B';
        foreach ($dias_semana as $dia) {
            $aula_texto = '';
            if (isset($aulas[$dia])) {
                foreach ($aulas[$dia] as $aula) {
                    $ini = strtotime($aula['hora_inicio']);
                    $fim = strtotime($aula['hora_fim']);
                    $bloco_ini = strtotime($hora_inicio);
                    $bloco_fim = strtotime($hora_fim);
                    if ($ini <= $bloco_ini && $fim >= $bloco_fim) {
                        $aula_texto = $aula['nome_uc'] . " (" . $aula['nome_tipocomponente'] . ")";
                        break;
                    }
                }
            }
            $sheet->setCellValue($coluna . $linha, $aula_texto);
            $coluna++;
        }
        $linha++;
    }
    $linha += 2;
    $sheet->setCellValue('A' . $linha, 'Ano Letivo 24/25');
    $sheet->mergeCells("A{$linha}:F{$linha}");
    $sheet->getStyle("A{$linha}:F{$linha}")->getAlignment()->setHorizontal('center');

    // Download do Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="horario.xlsx"');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// Exportar para PDF (DOMPDF)
if (isset($_GET['pdf']) && $tipo && $id) {
    $ano_letivo = "24/25";
    $html = gerarHorarioHTML($tipo, $id, $semestre_atual, $conn);
    $html .= "<p style='text-align:center; font-weight:bold; margin-top:20px;'>Ano Letivo $ano_letivo</p>";
    
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('horario.pdf');
    exit;
}



// Função para gerar o HTML do horário
function gerarHorarioHTML($tipo, $id, $semestre, $conn) {
    $nome = '';
    $horas_unicas = [
        ['08:30:00', '09:30:00'],
        ['09:30:00', '10:30:00'],
        ['10:30:00', '11:30:00'],
        ['11:30:00', '12:30:00'],
        ['12:30:00', '13:30:00'],
        ['13:30:00', '14:30:00'],
        ['14:30:00', '15:30:00'],
        ['15:30:00', '16:30:00'],
        ['16:30:00', '17:30:00'],
        ['17:30:00', '18:30:00'],
        ['18:30:00', '19:30:00'],
    ];
    $dias_semana = ['SEG','TER','QUA','QUI','SEX'];

    if ($tipo == 'docente') {
        $res = $conn->query("SELECT nome FROM utilizador WHERE id_utilizador = $id");
        $row = $res->fetch_assoc();
        $nome = $row['nome'] ?? '';
    } elseif ($tipo == 'turma') {
        $res = $conn->query("SELECT nome FROM turma WHERE id_turma = $id");
        $row = $res->fetch_assoc();
        $nome = $row['nome'] ?? '';
    } elseif ($tipo == 'sala') {
        $res = $conn->query("SELECT nome_sala FROM sala WHERE id_sala = $id");
        $row = $res->fetch_assoc();
        $nome = $row['nome_sala'] ?? '';
    }

    // Buscar aulas conforme o tipo
    if ($tipo == 'docente') {
        $sql = "SELECT a.id_horario, a.id_componente, d.nome_uc, tc.nome_tipocomponente, t.nome AS turma, h.hora_inicio, h.hora_fim, h.dia_semana
                FROM aula a
                JOIN componente c ON a.id_componente = c.id_componente
                JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                LEFT JOIN turma t ON a.id_turma = t.id_turma
                JOIN horario h ON a.id_horario = h.id_horario
                WHERE a.id_docente = $id AND h.semestre = $semestre";
    } elseif ($tipo == 'turma') {
        $sql = "SELECT a.id_horario, a.id_componente, d.nome_uc, tc.nome_tipocomponente, u.nome AS nome_docente, h.hora_inicio, h.hora_fim, h.dia_semana
                FROM aula a
                JOIN componente c ON a.id_componente = c.id_componente
                JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                LEFT JOIN utilizador u ON a.id_docente = u.id_utilizador
                JOIN horario h ON a.id_horario = h.id_horario
                WHERE a.id_turma = $id AND h.semestre = $semestre";
    } elseif ($tipo == 'sala') {
        $sql = "SELECT a.id_componente, h.dia_semana, h.hora_inicio, h.hora_fim, d.nome_uc, tc.nome_tipocomponente, c.id_tipocomponente
                FROM aula a
                JOIN horario h ON a.id_horario = h.id_horario
                JOIN componente c ON a.id_componente = c.id_componente
                JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                WHERE h.id_sala = $id";
    }
    $res = $conn->query($sql);
    $aulas = [];
    while ($row = $res->fetch_assoc()) {
        $aulas[$row['dia_semana']][] = $row;
    }
    

    $html = "<h2>Horário de " . htmlspecialchars($nome) . ($tipo == 'sala' ? " (Sala)" : ($tipo == 'turma' ? " (Turma)" : " (Docente)")) . "</h2>";
    $html .= "<table border='1' cellspacing='0' cellpadding='5' style='width:100%;'>";
    $html .= "<thead><tr><th>Hora</th>";
    foreach($dias_semana as $dia) $html .= "<th>$dia</th>";
    $html .= "</tr></thead><tbody>";

    $ocupados = [];
    foreach($horas_unicas as $horaIndex => $hora) {
        $hora_inicio = $hora[0];
        $hora_fim = $hora[1];
        $hora_label = substr($hora_inicio, 0, 5) . " - " . substr($hora_fim, 0, 5);
        $html .= "<tr><td><b>$hora_label</b></td>";
        foreach($dias_semana as $dia) {
            if (!empty($ocupados[$dia][$hora_inicio])) continue;

            $aula_encontrada = null;
            $blocos = 0;
            if (isset($aulas[$dia])) {
                foreach ($aulas[$dia] as $aula) {
                    $ini = strtotime($aula['hora_inicio']);
                    $fim = strtotime($aula['hora_fim']);
                    $bloco_ini = strtotime($hora_inicio);
                    $bloco_fim = strtotime($hora_fim);
                    if ($ini <= $bloco_ini && $fim >= $bloco_fim) {
                        $aula_encontrada = $aula;
                        $blocos = 0;
                        for ($i = $horaIndex; $i < count($horas_unicas); $i++) {
                            $bi = strtotime($horas_unicas[$i][0]);
                            $bf = strtotime($horas_unicas[$i][1]);
                            if ($bi >= $ini && $bf <= $fim) {
                                $blocos++;
                                if ($i != $horaIndex) $ocupados[$dia][$horas_unicas[$i][0]] = true;
                            } else {
                                break;
                            }
                        }
                        break;
                    }
                }
            }

            if ($aula_encontrada) {
                $content = htmlspecialchars($aula_encontrada['nome_uc']) . "<br>" . htmlspecialchars($aula_encontrada['nome_tipocomponente']);
                if ($tipo == 'docente' || $tipo == 'turma') {
                    $content .= isset($aula_encontrada['turma']) ? "<br>Turma: " . htmlspecialchars($aula_encontrada['turma']) : '';
                    $content .= isset($aula_encontrada['nome_docente']) ? "<br>Docente: " . htmlspecialchars($aula_encontrada['nome_docente']) : '';
                }
                $html .= "<td rowspan='$blocos'>$content</td>";
            } else {
                $html .= "<td></td>";
            }
        }
        $html .= "</tr>";
    }
    $html .= "</tbody></table>";
    return $html;
    
}


if (isset($_GET['exportar_todos'])) {
    $tipo = $_GET['exportar_todos_tipo'];
    $formato = $_GET['exportar_todos_formato'];
    $semestre_atual = isset($_GET['sem']) ? intval($_GET['sem']) : 1;
    $dias_semana = ['SEG','TER','QUA','QUI','SEX'];
    $horas_unicas = [
        ['08:30:00', '09:30:00'],
        ['09:30:00', '10:30:00'],
        ['10:30:00', '11:30:00'],
        ['11:30:00', '12:30:00'],
        ['12:30:00', '13:30:00'],
        ['13:30:00', '14:30:00'],
        ['14:30:00', '15:30:00'],
        ['15:30:00', '16:30:00'],
        ['16:30:00', '17:30:00'],
        ['17:30:00', '18:30:00'],
        ['18:30:00', '19:30:00'],
    ];

    // Buscar entidades
    if ($tipo == 'docentes') {
        $entidades = $conn->query("SELECT id_utilizador as id, nome FROM utilizador WHERE id_funcao IN (4,5,6) ORDER BY nome");
        $tipo_entidade = 'docente';
    } elseif ($tipo == 'turmas') {
        $entidades = $conn->query("SELECT id_turma as id, nome FROM turma ORDER BY nome");
        $tipo_entidade = 'turma';
    } else {
        $entidades = $conn->query("SELECT id_sala as id, nome_sala as nome FROM sala ORDER BY nome_sala");
        $tipo_entidade = 'sala';
    }

    // Guardar todas as entidades num array
    $entidades_array = [];
    while ($row = $entidades->fetch_assoc()) {
        $entidades_array[] = $row;
    }

    $entidades_com_aulas = [];
foreach ($entidades_array as $entidade) {
    $id_docente = $entidade['id'];
    // Query para contar aulas deste docente neste semestre
    $sql = "SELECT COUNT(*) as total FROM aula a
            JOIN horario h ON a.id_horario = h.id_horario
            WHERE a.id_docente = $id_docente AND h.semestre = $semestre_atual";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    if ($row['total'] > 0) {
        $entidades_com_aulas[] = $entidade;
    }
}


    // --- EXCEL ---
    if ($formato == 'excel') {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $idx = 0;
       foreach ($entidades_com_aulas as $entidade) {
            $sheet = $idx == 0 ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet();
            $sheet->setTitle(substr($entidade['nome'],0,25));
            $sheet->setCellValue('A1', 'Hora');
            $sheet->setCellValue('B1', 'SEG');
            $sheet->setCellValue('C1', 'TER');
            $sheet->setCellValue('D1', 'QUA');
            $sheet->setCellValue('E1', 'QUI');
            $sheet->setCellValue('F1', 'SEX');
            $linha = 2;

            // Query conforme tipo
            if ($tipo_entidade == 'docente') {
                $sql = "SELECT a.id_horario, a.id_componente, d.nome_uc, tc.nome_tipocomponente, t.nome AS turma, h.hora_inicio, h.hora_fim, h.dia_semana
                        FROM aula a
                        JOIN componente c ON a.id_componente = c.id_componente
                        JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                        JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                        LEFT JOIN turma t ON a.id_turma = t.id_turma
                        JOIN horario h ON a.id_horario = h.id_horario
                        WHERE a.id_docente = {$entidade['id']} AND h.semestre = $semestre_atual";
            } elseif ($tipo_entidade == 'turma') {
                $sql = "SELECT a.id_horario, a.id_componente, d.nome_uc, tc.nome_tipocomponente, u.nome AS nome_docente, h.hora_inicio, h.hora_fim, h.dia_semana
                        FROM aula a
                        JOIN componente c ON a.id_componente = c.id_componente
                        JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                        JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                        LEFT JOIN utilizador u ON a.id_docente = u.id_utilizador
                        JOIN horario h ON a.id_horario = h.id_horario
                        WHERE a.id_turma = {$entidade['id']} AND h.semestre = $semestre_atual";
            } else {
                $sql = "SELECT a.id_componente, h.dia_semana, h.hora_inicio, h.hora_fim, d.nome_uc, tc.nome_tipocomponente
                        FROM aula a
                        JOIN horario h ON a.id_horario = h.id_horario
                        JOIN componente c ON a.id_componente = c.id_componente
                        JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                        JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                        WHERE h.id_sala = {$entidade['id']}";
            }
            $res = $conn->query($sql);
            $aulas = [];
            while ($row = $res->fetch_assoc()) {
                $aulas[$row['dia_semana']][] = $row;
            }

            foreach ($horas_unicas as $hora) {
                $hora_inicio = $hora[0];
                $hora_fim = $hora[1];
                $hora_label = substr($hora_inicio, 0, 5) . " - " . substr($hora_fim, 0, 5);
                $sheet->setCellValue('A' . $linha, $hora_label);
                $coluna = 'B';
                foreach ($dias_semana as $dia) {
                    $aula_texto = '';
                    if (isset($aulas[$dia])) {
                        foreach ($aulas[$dia] as $aula) {
                            $ini = strtotime($aula['hora_inicio']);
                            $fim = strtotime($aula['hora_fim']);
                            $bloco_ini = strtotime($hora_inicio);
                            $bloco_fim = strtotime($hora_fim);
                            if ($ini <= $bloco_ini && $fim >= $bloco_fim) {
                                $aula_texto = $aula['nome_uc'] . " (" . $aula['nome_tipocomponente'] . ")";
                                break;
                            }
                        }
                    }
                    $sheet->setCellValue($coluna . $linha, $aula_texto);
                    $coluna++;
                }
                $linha++;
            }
        $linha += 2;
        $sheet->setCellValue('A' . $linha, 'Ano Letivo 24/25');
        $sheet->mergeCells("A{$linha}:F{$linha}");
        $sheet->getStyle("A{$linha}:F{$linha}")->getAlignment()->setHorizontal('center');
        $filename = "horarios_{$tipo}.xlsx";

            $idx++;
        }

        
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // --- PDF ---
    if ($formato == 'pdf') {
    $ano_letivo = "24/25";
    $html = "";
    foreach ($entidades_com_aulas as $entidade) {
        $html .= gerarHorarioHTML($tipo_entidade, $entidade['id'], $semestre_atual, $conn);
        // Adiciona o ano letivo ao fim de cada horário
        $html .= "<p style='text-align:center; font-weight:bold; margin-top:20px;'>Ano Letivo $ano_letivo</p>";
        $html .= "<div style='page-break-after:always;'></div>";
    }
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("horarios_{$tipo}.pdf");
    exit;
}


}

?>
<!DOCTYPE html>
<?php gerarHome1() ?>

<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Horários Múltiplos (Drag & Drop)</title>
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
    <div class="card">
        <h2>Imprimir/Exportar Horário</h2>
        
        <form method="get">
            <input type="hidden" name="sem" value="<?= $semestre_atual ?>">
            <label>Tipo de Horário:
                <select name="tipo" required onchange="this.form.submit()">
                    <option value="">-- Escolha --</option>
                    <option value="docente" <?= (isset($_GET['tipo']) && $_GET['tipo'] == 'docente' ? 'selected' : '') ?>>Docente</option>
                    <option value="turma" <?= (isset($_GET['tipo']) && $_GET['tipo'] == 'turma' ? 'selected' : '') ?>>Turma</option>
                    <option value="sala" <?= (isset($_GET['tipo']) && $_GET['tipo'] == 'sala' ? 'selected' : '') ?>>Sala</option>
                </select>
            </label>
            <label>Selecionar:
                <select name="id" required onchange="this.form.submit()">
                    <option value="">-- Escolha --</option>
                    <?php
                    if (isset($_GET['tipo'])) {
                        if ($_GET['tipo'] == 'docente') {
                            $res = $conn->query("SELECT id_utilizador, nome FROM utilizador WHERE id_funcao IN (4,5,6) ORDER BY nome");
                            while ($row = $res->fetch_assoc()) {
                                $selected = (isset($_GET['id']) && $_GET['id'] == $row['id_utilizador']) ? 'selected' : '';
                                echo "<option value='{$row['id_utilizador']}' $selected>{$row['nome']}</option>";
                            }
                        } elseif ($_GET['tipo'] == 'turma') {
                            $res = $conn->query("SELECT id_turma, nome FROM turma ORDER BY nome");
                            while ($row = $res->fetch_assoc()) {
                                $selected = (isset($_GET['id']) && $_GET['id'] == $row['id_turma']) ? 'selected' : '';
                                echo "<option value='{$row['id_turma']}' $selected>{$row['nome']}</option>";
                            }
                        } elseif ($_GET['tipo'] == 'sala') {
                            $res = $conn->query("SELECT id_sala, nome_sala FROM sala ORDER BY nome_sala");
                            while ($row = $res->fetch_assoc()) {
                                $selected = (isset($_GET['id']) && $_GET['id'] == $row['id_sala']) ? 'selected' : '';
                                echo "<option value='{$row['id_sala']}' $selected>{$row['nome_sala']}</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </label>
            <?php if (isset($_GET['tipo']) && isset($_GET['id'])): ?>
                <button type="submit" name="pdf" value="1">Gerar PDF</button>
                <button type="submit" name="excel" value="1">Exportar para Excel</button>
            <?php endif; ?>
        </form>
        <form method="get" style="margin-bottom: 20px;">
    <label>Exportar todos os horários de:
        <select name="exportar_todos_tipo" required>
            <option value="docentes">Docentes</option>
            <option value="turmas">Turmas</option>
            <option value="salas">Salas</option>
        </select>
    </label>
    <label>Formato:
        <select name="exportar_todos_formato" required>
            <option value="excel">Excel</option>
            <option value="pdf">PDF</option>
        </select>
    </label>
    <button type="submit" name="exportar_todos" value="1">Exportar todos</button>
</form>

        <?php if (isset($_GET['tipo']) && isset($_GET['id'])): ?>
            <div style="margin-top:20px;">
                <h3>Pré-visualização do Horário</h3>
                <?php
                
if (
    isset($_GET['tipo']) && $_GET['tipo'] !== '' &&
    isset($_GET['id']) && $_GET['id'] !== '' && is_numeric($_GET['id'])
)                
                echo gerarHorarioHTML($_GET['tipo'], $_GET['id'], $semestre_atual, $conn);
                ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
