<?php
session_start();
if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
    exit();
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$conn = mysqli_connect("localhost:3306", "root", "", "apoio_utc_2021_2022");
mysqli_set_charset($conn, "utf8");

// Tipos de entidades disponíveis
$entity_types = ['Docente', 'Turma', 'Sala'];

// Carregar entidades para a combobox
function getEntities($type, $conn) {
    if ($type == "Docente") {
        $sql = "SELECT id_utilizador AS id, nome AS name FROM utilizador WHERE id_funcao >= 4";
    } elseif ($type == "Turma") {
        $sql = "SELECT id_turma AS id, nome AS name FROM turma";
    } elseif ($type == "Sala") {
        $sql = "SELECT id_sala AS id, nome_sala AS name FROM sala";
    } else {
        return [];
    }
    $res = mysqli_query($conn, $sql);
    $arr = [];
    while ($row = mysqli_fetch_assoc($res)) $arr[] = $row;
    return $arr;
}

// Carregar aulas já atribuídas (para o horário)
function getSchedule($type, $id, $conn) {
    $slots = [];
    $dias_map = ['SEG'=>0,'TER'=>1,'QUA'=>2,'QUI'=>3,'SEX'=>4];
    $horas_map = [
        '08:30:00'=>0, '09:30:00'=>1, '10:30:00'=>2, '11:30:00'=>3, '12:30:00'=>4,
        '13:30:00'=>5, '14:30:00'=>6, '15:30:00'=>7, '16:30:00'=>8, '17:30:00'=>9, '18:30:00'=>10
    ];
    if ($type == "Docente") {
        $sql = "SELECT a.id_componente, h.dia_semana, h.hora_inicio, d.nome_uc, tc.nome_tipocomponente, c.id_tipocomponente, c.numero_horas
                FROM aula a
                JOIN horario h ON a.id_horario = h.id_horario
                JOIN componente c ON a.id_componente = c.id_componente
                JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                WHERE a.id_docente = ?";
    } elseif ($type == "Turma") {
        $sql = "SELECT a.id_componente, h.dia_semana, h.hora_inicio, d.nome_uc, tc.nome_tipocomponente, c.id_tipocomponente, c.numero_horas
                FROM aula a
                JOIN horario h ON a.id_horario = h.id_horario
                JOIN componente c ON a.id_componente = c.id_componente
                JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                WHERE a.id_turma = ?";
    } elseif ($type == "Sala") {
        $sql = "SELECT a.id_componente, h.dia_semana, h.hora_inicio, d.nome_uc, tc.nome_tipocomponente, c.id_tipocomponente, c.numero_horas
                FROM aula a
                JOIN horario h ON a.id_horario = h.id_horario
                JOIN componente c ON a.id_componente = c.id_componente
                JOIN disciplina d ON c.id_disciplina = d.id_disciplina
                JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
                WHERE h.id_sala = ?";
    } else {
        return $slots;
    }
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $dia = $dias_map[$row['dia_semana']] ?? null;
        $hora = $horas_map[$row['hora_inicio']] ?? null;
        if ($dia !== null && $hora !== null) {
            $idx = $hora * 5 + $dia;
            $slots[$idx] = [
                'disciplina' => $row['nome_uc'],
                'tipo' => $row['nome_tipocomponente'],
                'tipo_id' => $row['id_tipocomponente'],
                'id_componente' => $row['id_componente'],
                'numero_horas' => $row['numero_horas']
            ];
        }
    }
    return $slots;
}

// Carregar componentes disponíveis para arrastar
function getDraggable($conn) {
    $sql = "SELECT c.id_componente, d.nome_uc, d.abreviacao_uc, 
                   tc.nome_tipocomponente, tc.id_tipocomponente, 
                   c.numero_horas
            FROM componente c
            JOIN disciplina d ON c.id_disciplina = d.id_disciplina
            JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
            WHERE c.id_componente NOT IN (SELECT id_componente FROM aula)
            ORDER BY d.nome_uc, tc.nome_tipocomponente";
    $res = mysqli_query($conn, $sql);
    $arr = [];
    while ($row = mysqli_fetch_assoc($res)) $arr[] = $row;
    return $arr;
}

// Carregar componentes já atribuídos ao docente (para painel)
function getDocenteComponentes($conn, $id_docente) {
    $sql = "SELECT 
                c.id_componente,
                d.abreviacao_uc,
                tc.nome_tipocomponente,
                tc.id_tipocomponente,
                c.numero_horas
            FROM aula a
            JOIN componente c ON a.id_componente = c.id_componente
            JOIN disciplina d ON c.id_disciplina = d.id_disciplina
            JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
            WHERE a.id_docente = ?
            GROUP BY c.id_componente
            ORDER BY d.abreviacao_uc, tc.nome_tipocomponente";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_docente);
    $stmt->execute();
    $result = $stmt->get_result();
    $arr = [];
    while ($row = $result->fetch_assoc()) {
        $arr[] = $row;
    }
    return $arr;
}

// Função cor por tipo
function getColorByTipo($tipo_id) {
    switch ($tipo_id) {
        case 1: return '#ff5252'; // Vermelho
        case 2: return '#69f0ae'; // Verde
        case 3: return '#b388ff'; // Roxo
        case 4: return '#ffe082'; // Amarelo
        default: return '#e0e0e0';
    }
}

// Obter preferências do docente/turma
function getPreferencias($type, $id, $conn) {
    if ($type == "Docente") {
        $sql = "SELECT p.preferencia FROM utilizador_preferencia up JOIN preferencias p ON up.id_preferencias = p.id_preferencias WHERE up.id_utilizador = ?";
    } else if ($type == "Turma") {
        $sql = "SELECT p.preferencia FROM preferencias_turma pt JOIN preferencias p ON pt.id_preferencias = p.id_preferencias WHERE pt.id_turma = ?";
    } else {
        return array_fill(0, 55, 1); // Default: tudo permitido
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return explode(',', $row['preferencia']);
    }
    return array_fill(0, 55, 1); // Default: tudo permitido
}
?>
<?php gerarHome1() ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Horários</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        body { background: #fafbfc; font-family: Arial, sans-serif; }
        .main-container { max-width: 1100px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #eee; padding: 32px 38px 28px 38px;}
        h2 { font-size: 1.7em; margin-bottom: 24px; }
        .flex { display: flex; gap: 40px; }
        .horario-tabela { border-collapse: collapse; margin-top: 18px; }
        .horario-tabela th, .horario-tabela td { border: 1px solid #bbb; width: 160px; height: 38px; text-align: center; }
        .horario-tabela th { background: #f2f2f2; }
        .slot-horario { width:100%; height:100%; min-height:38px; border-radius: 4px; }
        .slot-ocupado { font-size: 0.95em; font-weight: bold; color: #222; border-radius: 3px; }
        .draggable-item { margin: 8px 0; padding: 8px 10px; border-radius: 4px; cursor: move; font-size: 0.98em; border: 1px solid #ddd; box-shadow: 0 1px 2px #eee;}
        .draggable-item:hover { box-shadow: 0 2px 8px #ccc; }
        .ui-draggable-dragging { z-index: 9999 !important; }
        .ui-state-hover { background-color: #f0f8ff !important; }
        .painel-itens { min-width: 200px; background: #f8f9fa; border-radius: 8px; padding: 16px 20px; margin-top: 18px; }
        .legenda { margin-top: 24px; }
        .legenda > div { margin-bottom: 8px; font-size: 0.97em; }
        .legenda span { display: inline-block; width: 18px; height: 18px; border-radius: 50%; margin-right: 8px; vertical-align: middle; }
        label { margin-right: 10px; }
        select { margin-right: 18px; padding: 2px 8px; }
        @media (max-width: 900px) {
            .flex { flex-direction: column; }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
<div class="container-fluid" style="padding-top:15px;">
    <div class="card shadow mb-4">
        <div class="card-body">
            <a href="http://localhost/apoio_utc/home.php">
                <h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Horários</a></h6>
            <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Horários</b></h3>
            <form method="post" id="entityForm" style="margin-bottom: 18px; margin-left:15px;">
                <div id="selects-area">
                    <?php
                    // Inicializa arrays de entidades selecionadas
                    $selected_types = $_POST['entity_type'] ?? [''];
                    $selected_ids = $_POST['entity_id'] ?? [''];
                    if (!is_array($selected_types)) $selected_types = [$selected_types];
                    if (!is_array($selected_ids)) $selected_ids = [$selected_ids];
                    $num_horarios = max(count($selected_types), 1);
                    for ($i = 0; $i < $num_horarios; $i++) {
                    ?>
                    <div class="select-bloco" style="margin-bottom: 6px;">
                        <label>Selecionar Entidade:</label>
                        <select name="entity_type[]" onchange="this.form.submit()">
                            <option value="">Selecione um tipo</option>
                            <?php foreach ($entity_types as $type) { ?>
                                <option value="<?= $type ?>" <?= ($selected_types[$i] ?? '') == $type ? 'selected' : '' ?>><?= $type ?></option>
                            <?php } ?>
                        </select>
                        <label>Selecionar Nome:</label>
                        <select name="entity_id[]" onchange="this.form.submit()">
                            <option value="">Selecione uma entidade</option>
                            <?php
                            $tipo_atual = $selected_types[$i] ?? '';
                            if ($tipo_atual) {
                                foreach(getEntities($tipo_atual, $conn) as $e) {
                                    $sel = ($selected_ids[$i] ?? '') == $e['id'] ? 'selected' : '';
                                    echo "<option value='{$e['id']}' $sel>".htmlspecialchars($e['name'])."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php } ?>
                </div>
                <button type="button" id="addHorario" class="btn btn-secondary" style="margin-top:8px;">+ Horario</button>
                <button type="submit" class="btn btn-primary" style="margin-top:8px;">Ver horários</button>
            </form>
            <?php
            $tem_horario = false;
            foreach ($selected_types as $idx => $tipo) {
                if (!empty($tipo) && !empty($selected_ids[$idx])) $tem_horario = true;
            }
            if ($tem_horario) {
                $id = $selected_ids[0] ?? '';
                $draggable = getDocenteComponentes($conn,$id );
            ?>
            <div class="flex" style="margin-left:30px;">
                <?php
                foreach ($selected_types as $idx => $tipo) {
                    $id = $selected_ids[$idx] ?? '';
                    if (empty($tipo) || empty($id)) continue;
                    $schedule = getSchedule($tipo, $id, $conn);
                    $preferencias = getPreferencias($tipo, $id, $conn);
                ?>
                <div>
                    <table class="horario-tabela">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Seg</th>
                                <th>Ter</th>
                                <th>Qua</th>
                                <th>Qui</th>
                                <th>Sex</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
$horas = [
    "08:30-09:30", "09:30-10:30", "10:30-11:30", "11:30-12:30",
    "12:30-13:30", "13:30-14:30", "14:30-15:30", "15:30-16:30",
    "16:30-17:30", "17:30-18:30"];                        
$index = 0;
                        for ($i = 0; $i < 10; $i++) {
                            echo "<tr><td><b>{$horas[$i]}</b></td>";
                            for ($j = 0; $j < 5; $j++) {
                                $slot = $schedule[$index] ?? null;
                                $cor = $slot ? getColorByTipo($slot['tipo_id']) : "#e0e0e0";
                                $isBlocked = ($preferencias[$index] ?? 1) == 0;
                                $slotClass = $isBlocked ? "slot-bloqueado" : "droppable";
                                if ($slot) {
                                    // Aula já atribuída: arrastável
                                    echo "<td>
                                        <div class='slot-horario slot-ocupado $slotClass' data-index='$index' style='background:$cor'>
                                            <div class='draggable-item'
                                                 draggable='true'
                                                 data-componente-id='{$slot['id_componente']}'
                                                 data-horas='{$slot['numero_horas']}'
                                                 data-origem-slot='$index'
                                                 style='background:$cor'>
                                                {$slot['disciplina']}<br>({$slot['tipo']})
                                            </div>
                                        </div>
                                    </td>";
                                } else {
                                    $content = $isBlocked ? "<span style='color:#ff0000'>Bloqueado</span>" : "";
                                    echo "<td>
                                        <div class='slot-horario $slotClass' data-index='$index' style='background:$cor'>$content</div>
                                        
                                    </td>";
                                }
                                $index++;
                            }
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>

<div class="flex" style="margin-left:15px;">
    <!-- ... tabela de horários ... -->
    <div class="painel-itens">
        <b>Itens disponíveis</b>
        <?php foreach ($draggable as $item): ?>
            <div class="draggable-item"
                 draggable="true"
                 style="background:<?= getColorByTipo($item['id_tipocomponente']) ?>; width:<?= 80 + $item['numero_horas'] * 30 ?>px;"
                 data-componente-id="<?= $item['id_componente'] ?>"
                 data-horas="<?= $item['numero_horas'] ?>">
                <?= htmlspecialchars($item['abreviacao_uc']) ?>
                <b>(<?= htmlspecialchars($item['nome_tipocomponente']) ?>)</b>
                <span class="horas"><?= $item['numero_horas'] ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php } ?>

            </div>
        
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    console.log("INICIALIZAÇÃO");

    // Inicializa os itens arrastáveis
$(".draggable-item").draggable({
    helper: "clone",
    revert: "invalid",
    cursor: "move",
    opacity: 0.8,
    zIndex: 100,
    containment: "document"
});

$(".droppable").droppable({
    accept: ".draggable-item",
    hoverClass: "ui-state-hover",
    tolerance : "pointer",
    create: function(){
        console.log("CRIADO");
    },

    drop: function(event, ui) {
        console.log("DROP iniciado");
        var slot = $(this);
        var slotIndex = slot.data("index");
        var componenteId = ui.draggable.data("componente-id");
        var duracao = parseInt(ui.draggable.data("horas"), 10);

        // Verifica se todos os slots necessários estão livres e não bloqueados
        var pode = true;
        var slotsParaOcupar = [];
        for (var i = 0; i < duracao; i++) {
            var idx = slotIndex + i * 5; // Avança para o próximo bloco de hora no mesmo dia
            var slotTarget = $(".slot-horario[data-index='" + idx + "']");
            if (
                slotTarget.length === 0 ||
                //slotTarget.hasClass("slot-bloqueado") ||
                slotTarget.hasClass("slot-ocupado")
            ) {
                pode = true;
                break;
            }
            slotsParaOcupar.push(slotTarget);
        }
        if (!pode) {
            alert("Não pode colocar a aula aqui: bloco ocupado ou bloqueado!");
            return;
        }

        // Atualiza visualmente todos os slots ocupados
        slotsParaOcupar.forEach(function(slotTarget) {
            slotTarget.html(ui.draggable.html());
            slotTarget.css("background", ui.draggable.css("background"));
            slotTarget.addClass("slot-ocupado").removeClass("droppable");
        });

        // AJAX para atualizar o horário na base de dados
        var entidadeTipo = $("select[name='entity_type[]']").first().val();
        var entidadeId = $("select[name='entity_id[]']").first().val();

        $.ajax({
            url: "atualizarHorarios.php",
            type: "POST",
            data: {
                slot_destino: slotIndex,
                id_componente: componenteId,
                id_docente: entidadeTipo === "Docente" ? entidadeId : null,
                id_turma: entidadeTipo === "Turma" ? entidadeId : null
            },
            success: function(response) {
                console.log(response);
               // if (response.debug) {alert(JSON.stringify(response.post_data, null, 2));}
              if (!response.success) {
                    alert(response.message || "Erro ao gravar!");
                    // Reverte visualmente
                    slotsParaOcupar.forEach(function(slotTarget) {
                        slotTarget.html("").css("background", "#e0e0e0")
                            .removeClass("slot-ocupado").addClass("droppable");
                    });
                }
            },
            error: function(xhr, status, error) {
                alert("Erro de comunicação com o servidor");
                slotsParaOcupar.forEach(function(slotTarget) {
                    slotTarget.html("").css("background", "#e0e0e0")
                        .removeClass("slot-ocupado").addClass("droppable");
                });
            }
        });
    }
});

/*$(".slot-horario").droppable({
    accept: ".draggable-item",
    hoverClass: "ui-state-hover",
    drop: function(event, ui) { console.log("DROP TESTE"); 
        
    }
});*/

$(".slot-bloqueado").droppable("disable");

    // Adiciona nova seleção de horário
    document.getElementById('addHorario').onclick = function() {
        var selectsArea = document.getElementById('selects-area');
        var blocos = selectsArea.querySelectorAll('.select-bloco');
        var novo = blocos[0].cloneNode(true);
        novo.querySelectorAll('select').forEach(function(sel) {
            sel.selectedIndex = 0;
        });
        selectsArea.appendChild(novo);
    };
});
</script>
</body>
</html>