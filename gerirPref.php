<?php
session_start();  
if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
    exit();
}

include('ferramentas.php'); 
include('bd.h'); 
include('bd_final.php');

$permAdmin = false;
$permEditar = false;

$conn = mysqli_connect("localhost:3306", "root", "");
if (mysqli_connect_errno()) {
    echo "Erro ao conectar ao MySQL: " . mysqli_connect_error();
    exit;
}

mysqli_set_charset($conn, 'utf8'); 
mysqli_select_db($conn, "apoio_utc_2021_2022");

$user_id = $_SESSION["id"];

// Tipos de entidades disponíveis
$entity_types = ['Docente', 'Turma', 'Sala'];

// Obter o tipo de entidade e ID selecionados
$entity_type = $_POST['entity_type'] ?? $_SESSION['entity_type'] ?? '';
$entity_id = $_POST['entity_id'] ?? $_SESSION['entity_id'] ?? '';

if (!empty($entity_type)) {
    $_SESSION['entity_type'] = $entity_type;
}
if (!empty($entity_id)) {
    $_SESSION['entity_id'] = $entity_id;
}

// Carregar preferências existentes
$saved_schedule = array_fill(0, 50, '0'); // Inicializa com 50 zeros
if (!empty($entity_type) && !empty($entity_id)) {
    $table_map = [
        'Docente' => ['table' => 'utilizador_preferencia', 'id_column' => 'id_utilizador'],
        'Turma' => ['table' => 'preferencias_turma', 'id_column' => 'id_turma'],
        'Sala' => ['table' => 'preferencia_sala', 'id_column' => 'id_sala']
    ];

    if (isset($table_map[$entity_type])) {
        $table = $table_map[$entity_type]['table'];
        $id_column = $table_map[$entity_type]['id_column'];

        $query = "SELECT p.preferencia 
                  FROM $table e 
                  JOIN preferencias p ON e.id_preferencias = p.id_preferencias 
                  WHERE e.$id_column = ?";
        
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "i", $entity_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                $preferencia = $row['preferencia'];
                $saved_schedule = explode(',', $preferencia);
                echo "<!-- Preferências carregadas para $entity_type ID $entity_id: " . htmlspecialchars($preferencia) . " -->";
            } else {
                echo "<!-- Nenhuma preferência encontrada para $entity_type ID $entity_id. Usando valores padrão (todos 0). -->";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Erro ao preparar consulta de preferências: " . mysqli_error($conn) . "<br>";
        }
    }
}

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($entity_type) && !empty($entity_id) && isset($_POST['schedule'])) {
    $schedule = $_POST['schedule'];
    if (!is_array($schedule)) {
        $schedule = explode(',', $schedule);
    }
    echo "<!-- Valores recebidos em schedule: " . htmlspecialchars(implode(',', $schedule)) . " -->";
    $schedule_string = implode(',', $schedule);

    if (empty($schedule_string) || count($schedule) != 50) {
        echo "Erro: Preferências inválidas ou incompletas! Recebido: " . count($schedule) . " valores.<br>";
        exit;
    }

    $table_map = [
        'Docente' => ['table' => 'utilizador_preferencia', 'id_column' => 'id_utilizador'],
        'Turma' => ['table' => 'preferencias_turma', 'id_column' => 'id_turma'],
        'Sala' => ['table' => 'preferencia_sala', 'id_column' => 'id_sala']
    ];

    if (!isset($table_map[$entity_type])) {
        echo "Erro: Tipo de entidade inválido: " . htmlspecialchars($entity_type) . "<br>";
        exit;
    }

    $table = $table_map[$entity_type]['table'];
    $id_column = $table_map[$entity_type]['id_column'];

    // Verificar se a entidade já tem preferências
    $check_query = "SELECT id_preferencias FROM $table WHERE $id_column = ?";
    if ($stmt = mysqli_prepare($conn, $check_query)) {
        mysqli_stmt_bind_param($stmt, "i", $entity_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Atualizar preferências existentes
            $id_preferencia = $row['id_preferencias'];
            $update_query = "UPDATE preferencias SET preferencia = ? WHERE id_preferencias = ?";
            if ($stmt_update = mysqli_prepare($conn, $update_query)) {
                mysqli_stmt_bind_param($stmt_update, "si", $schedule_string, $id_preferencia);
                if (mysqli_stmt_execute($stmt_update)) {
                    echo "Preferências atualizadas com sucesso para $entity_type ID $entity_id!<br>";
                } else {
                    echo "Erro ao atualizar as preferências: " . mysqli_error($conn) . "<br>";
                    exit;
                }
                mysqli_stmt_close($stmt_update);
            } else {
                echo "Erro ao preparar a atualização: " . mysqli_error($conn) . "<br>";
                exit;
            }
        } else {
            // Inserir novas preferências
            $insert_query = "INSERT INTO preferencias (preferencia) VALUES (?)";
            if ($stmt_insert = mysqli_prepare($conn, $insert_query)) {
                mysqli_stmt_bind_param($stmt_insert, "s", $schedule_string);
                if (mysqli_stmt_execute($stmt_insert)) {
                    $id_preferencia = mysqli_insert_id($conn);
                    echo "<!-- Novo registro criado em preferencias com ID $id_preferencia -->";

                    // Associar à entidade
                    $insert_entity_query = "INSERT INTO $table ($id_column, id_preferencias) VALUES (?, ?)";
                    if ($stmt_entity = mysqli_prepare($conn, $insert_entity_query)) {
                        mysqli_stmt_bind_param($stmt_entity, "ii", $entity_id, $id_preferencia);
                        if (mysqli_stmt_execute($stmt_entity)) {
                            echo "Novas preferências salvas com sucesso para $entity_type ID $entity_id!<br>";
                        } else {
                            echo "Erro ao associar preferências à $entity_type: " . mysqli_error($conn) . "<br>";
                            exit;
                        }
                        mysqli_stmt_close($stmt_entity);
                    } else {
                        echo "Erro ao preparar inserção em $table: " . mysqli_error($conn) . "<br>";
                        exit;
                    }
                } else {
                    echo "Erro ao inserir preferências: " . mysqli_error($conn) . "<br>";
                    exit;
                }
                mysqli_stmt_close($stmt_insert);
            } else {
                echo "Erro ao preparar inserção em preferencias: " . mysqli_error($conn) . "<br>";
                exit;
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Erro ao preparar verificação: " . mysqli_error($conn) . "<br>";
        exit;
    }

    // Forçar recarregamento com os mesmos parâmetros
    header("Location: " . $_SERVER['PHP_SELF'] . "?entity_type=" . urlencode($entity_type) . "&entity_id=" . urlencode($entity_id));
    exit;
}

function getColor($value) {
    switch ($value) {
        case '0': return 'gray';
        case '1': return 'red';
        case '2': return 'yellow';
        case '3': return 'green';
        default: return 'gray';
    }
}
?>

<style>
.button-container { display: flex; gap: 11px; margin-bottom: 16px; }
.color-button { flex: 1; max-width: 110px; text-align: center; padding: 10px; font-weight: bold; border: 1px solid black; cursor: pointer; }
.melhor { background-color: green; color: black; }
.pior { background-color: red; color: black; }
.bom { background-color: yellow; color: black; }
.impossivel { background-color: gray; color: black; }
.time-slot { width: 100px; height: 25px; background-color: gray; border: 1px solid black; cursor: pointer; }
</style>

<?php gerarHome1() ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Preferências</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="container-fluid" style="padding-top:15px;">
    <div class="card shadow mb-4">
        <div class="card-body">
            <a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Horários</a> / <a href="">Gerir Preferências</a></h6>
            <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Gerir Preferências</b></h3>
        </div> 
        <form method="post" id="entityForm">
            <label for="entity_type">Selecionar Tipo:</label>
            <select name="entity_type" id="entity_type" onchange="updateEntityList()">
                <option value="">Selecione um tipo</option>
                <?php foreach ($entity_types as $type) { ?>
                    <option value="<?php echo $type; ?>" <?php echo ($entity_type == $type ? 'selected' : ''); ?>>
                        <?php echo $type; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="entity_id">Selecionar Entidade:</label>
            <select name="entity_id" id="entity_id" onchange="this.form.submit()">
                <option value="">Selecione uma entidade</option>
                <!-- Opções serão preenchidas dinamicamente via AJAX -->
            </select>
        </form>

        <?php if (!empty($entity_type) && !empty($entity_id)) { ?>
            <div class="button-container">
                <div class="color-button melhor" onclick="setSelectedColor('green')">Melhor</div>
                <div class="color-button pior" onclick="setSelectedColor('red')">Pior</div>
                <div class="color-button bom" onclick="setSelectedColor('yellow')">Bom</div>
                <div class="color-button impossivel" onclick="setSelectedColor('gray')">Impossível</div>
            </div>
            <form method="post" id="scheduleForm">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Segunda</th>
                            <th>Terça</th>
                            <th>Quarta</th>
                            <th>Quinta</th>
                            <th>Sexta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $horas = ["08:30 - 09:30", "09:30 - 10:30", "10:30 - 11:30", "11:30 - 12:30", "12:30 - 13:30", "13:30 - 14:30", "14:30 - 15:30", "15:30 - 16:30", "16:30 - 17:30", "17:30 - 18:30"];
                        $dias = ["Seg", "Ter", "Qua", "Qui", "Sex"];

                        $index = 0;
                        foreach ($horas as $hIndex => $hora) {
                            echo "<tr>";
                            echo "<td><b>$hora</b></td>";
                            foreach ($dias as $dia) {
                                $selected = $saved_schedule[$index] ?? '0';
                                $color = getColor($selected);
                                echo "<td>
                                    <div class='time-slot' data-index='$index' style='background-color: $color;' onclick='changeColor(this)'></div>
                                </td>";
                                $index++;
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <input type="hidden" name="schedule" id="schedule" value="<?php echo implode(',', $saved_schedule); ?>">
                <input type="hidden" name="entity_type" value="<?php echo htmlspecialchars($entity_type); ?>">
                <input type="hidden" name="entity_id" value="<?php echo htmlspecialchars($entity_id); ?>">
                <button type="submit" class="btn btn-primary">Salvar Preferências</button>
            </form>
        <?php } ?>
    </div>
</div>

<script>
let selectedColor = 'gray';

function setSelectedColor(color) {
    selectedColor = color;
}

function changeColor(element) {
    element.style.backgroundColor = selectedColor;
    updateHiddenInput();
}

function updateHiddenInput() {
    let values = [];
    document.querySelectorAll('.time-slot').forEach(slot => {
        let color = slot.style.backgroundColor;
        values.push(getValueFromColor(color));
    });
    console.log("Valores a enviar: ", values);
    document.getElementById('schedule').value = values.join(',');
}

function getValueFromColor(color) {
    switch (color) {
        case 'gray': return 0;
        case 'red': return 1;
        case 'yellow': return 2;
        case 'green': return 3;
        default: return 0;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(updateHiddenInput, 0);
    updateEntityList(); // Carregar a lista de entidades ao carregar a página
});

let isMouseDown = false;
document.addEventListener('mousedown', function() { isMouseDown = true; });
document.addEventListener('mouseup', function() { isMouseDown = false; });
document.querySelectorAll('.time-slot').forEach(slot => {
    slot.addEventListener('mousedown', function() { changeColor(this); });
    slot.addEventListener('mouseover', function() { if (isMouseDown) { changeColor(this); } });
});
document.getElementById('scheduleForm').addEventListener('submit', function() { updateHiddenInput(); });

// Função para atualizar a segunda combobox via AJAX
function updateEntityList() {
    const entityType = document.getElementById('entity_type').value;
    const entityIdSelect = document.getElementById('entity_id');
    
    // Limpar a segunda combobox
    entityIdSelect.innerHTML = '<option value="">Selecione uma entidade</option>';

    if (!entityType) return;

    // Fazer uma requisição AJAX para buscar as entidades
    $.ajax({
        url: 'get_entities.php',
        type: 'POST',
        data: { entity_type: entityType },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error('Erro:', response.error);
                return;
            }
            response.forEach(entity => {
                const option = document.createElement('option');
                option.value = entity.id;
                option.text = entity.name;
                if (entity.id == '<?php echo $entity_id; ?>') {
                    option.selected = true;
                }
                entityIdSelect.appendChild(option);
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição AJAX:', error);
        }
    });
}
</script>

</body>
</html>