<?php
session_start();
if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
    exit();
}

include('bd.h');
include('bd_final.php');

$conn = mysqli_connect("localhost:3306", "root", "", "apoio_utc_2021_2022");
mysqli_set_charset($conn, "utf8");

// Receber dados via POST
$slot = isset($_POST['slot']) ? intval($_POST['slot']) : null;
$componente_id = isset($_POST['componente_id']) ? intval($_POST['componente_id']) : null;
$entidade_tipo = isset($_POST['entidade_tipo']) ? $_POST['entidade_tipo'] : '';
$entidade_id = isset($_POST['entidade_id']) ? intval($_POST['entidade_id']) : null;

// Validar dados recebidos
if ($slot === null || $componente_id === null || empty($entidade_tipo) || $entidade_id === null) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

// Converter slot para dia da semana e hora
$dia_index = $slot % 5;
$hora_index = floor($slot / 5);

$dias = ['SEG', 'TER', 'QUA', 'QUI', 'SEX'];
$dia_semana = $dias[$dia_index];

$horas = ['08:30:00', '09:30:00', '10:30:00', '11:30:00', '12:30:00', 
          '13:30:00', '14:30:00', '15:30:00', '16:30:00', '17:30:00'];
$hora_inicio = $horas[$hora_index];
$hora_fim = date('H:i:s', strtotime($hora_inicio) + 3600); // +1 hora

// Verificar se já existe horário para esse dia/hora
$query_horario = "SELECT id_horario FROM horario WHERE dia_semana = ? AND hora_inicio = ?";
$stmt = mysqli_prepare($conn, $query_horario);
mysqli_stmt_bind_param($stmt, "ss", $dia_semana, $hora_inicio);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id_horario);

if (mysqli_stmt_fetch($stmt)) {
    // Horário já existe, usar o existente
    mysqli_stmt_close($stmt);
} else {
    // Criar novo horário
    mysqli_stmt_close($stmt);
    $insert_horario = "INSERT INTO horario (dia_semana, hora_inicio, hora_fim, semestre) VALUES (?, ?, ?, 1)";
    $stmt = mysqli_prepare($conn, $insert_horario);
    mysqli_stmt_bind_param($stmt, "sss", $dia_semana, $hora_inicio, $hora_fim);
    
    if (!mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => false, 'message' => 'Erro ao criar horário: ' . mysqli_error($conn)]);
        exit;
    }
    
    $id_horario = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
}

// Inserir aula conforme tipo de entidade
if ($entidade_tipo == 'Docente') {
    $query_aula = "INSERT INTO aula (id_componente, id_horario, id_turma, id_docente) VALUES (?, ?, 1, ?)";
    $stmt = mysqli_prepare($conn, $query_aula);
    mysqli_stmt_bind_param($stmt, "iii", $componente_id, $id_horario, $entidade_id);
} elseif ($entidade_tipo == 'Turma') {
    $query_aula = "INSERT INTO aula (id_componente, id_horario, id_turma) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query_aula);
    mysqli_stmt_bind_param($stmt, "iii", $componente_id, $id_horario, $entidade_id);
} elseif ($entidade_tipo == 'Sala') {
    // Primeiro atualiza o horário com a sala
    $query_update = "UPDATE horario SET id_sala = ? WHERE id_horario = ?";
    $stmt_update = mysqli_prepare($conn, $query_update);
    mysqli_stmt_bind_param($stmt_update, "ii", $entidade_id, $id_horario);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);
    
    // Depois insere a aula (com turma padrão)
    $query_aula = "INSERT INTO aula (id_componente, id_horario, id_turma) VALUES (?, ?, 1)";
    $stmt = mysqli_prepare($conn, $query_aula);
    mysqli_stmt_bind_param($stmt, "ii", $componente_id, $id_horario);
} else {
    echo json_encode(['success' => false, 'message' => 'Tipo de entidade inválido']);
    exit;
}

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao criar aula: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
