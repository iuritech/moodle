<?php
session_start();
header('Content-Type: application/json');

include('../../bd.h');
include('../../bd_final.php');

$postData = $_POST;
echo json_encode([
    'debug' => true,
    'message' => 'Dados recebidos',
    'post_data' => $postData,
]);
exit;

$conn = mysqli_connect("localhost:3306", "root", "", "apoio_utc_2021_2022");
mysqli_set_charset($conn, "utf8");

// Receber dados do AJAX
$slot_destino = isset($_POST['slot_destino']) ? intval($_POST['slot_destino']) : null;
echo "estou aqui";
$id_componente = isset($_POST['id_componente']) ? intval($_POST['id_componente']) : null;
echo "estou aqui";

$id_docente = isset($_POST['id_docente']) ? intval($_POST['id_docente']) : null;
echo "estou aqui";

$id_turma = isset($_POST['id_turma']) ? intval($_POST['id_turma']) : null;
echo "estou aqui";


if ($slot_destino === null || $id_componente === null || ($id_docente === null && $id_turma === null)) {

    echo json_encode(['success' => false, 'message' => 'Dados em falta']);
    exit;
}

// Descobre a duração da aula (1h, 2h, etc)
$stmt = $conn->prepare("SELECT numero_horas FROM componente WHERE id_componente = ?");
$stmt->bind_param("i", $id_componente);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$duracao = intval($row['numero_horas']); // 1 ou 2

// Mapear slot para dia_semana e hora_inicio/hora_fim
$dias = ['SEG', 'TER', 'QUA', 'QUI', 'SEX'];
$horas_inicio = [
    "08:30:00", "09:30:00", "10:30:00", "11:30:00", "12:30:00",
    "13:30:00", "14:30:00", "15:30:00", "16:30:00", "17:30:00", "18:30:00"
];
$horas_fim = [
    "09:30:00", "10:30:00", "11:30:00", "12:30:00", "13:30:00",
    "14:30:00", "15:30:00", "16:30:00", "17:30:00", "18:30:00", "19:30:00"
];

$hora_idx = floor($slot_destino / 5);
$dia_idx = $slot_destino % 5;
$dia_semana = $dias[$dia_idx];

// Verifica se todos os slots necessários existem
$ids_horario = [];
for ($i = 0; $i < $duracao; $i++) {
    $h_idx = $hora_idx + $i;
    if ($h_idx >= count($horas_inicio)) {
        echo json_encode(['success' => false, 'message' => 'Slot fora do horário']);
        exit;
    }
    $hora_inicio = $horas_inicio[$h_idx];
    $hora_fim = $horas_fim[$h_idx];
$stmt = $conn->prepare("SELECT id_horario FROM horario WHERE dia_semana = ? AND (TIME_FORMAT(?, '%H:%i:%s') < TIME_FORMAT(hora_fim, '%H:%i:%s') AND TIME_FORMAT(?, '%H:%i:%s') > TIME_FORMAT(hora_inicio, '%H:%i:%s'))");
    $stmt->bind_param("sss", $dia_semana, $hora_inicio, $hora_fim);
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if ($row) {
        echo json_encode(['success' => false, 'message' => 'Slot de horário não encontrado para ' . $hora_fim ]);
        exit;
    }
    $ids_horario[] = $row['id_horario'];
}

// Antes de inserir, remove todas as aulas anteriores deste componente/docente/turma
$stmt = $conn->prepare("DELETE FROM aula WHERE id_componente = ? AND (id_docente = ? OR ? IS NULL) AND (id_turma = ? OR ? IS NULL)");
$stmt->bind_param("iiiii", $id_componente, $id_docente, $id_docente, $id_turma, $id_turma);
$stmt->execute();

// Insere as novas atribuições para cada slot/hora
foreach ($ids_horario as $id_horario) {
    $stmt2 = $conn->prepare("INSERT INTO aula (id_componente, id_horario, id_turma, id_docente) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiii", $id_componente, $id_horario, $id_turma, $id_docente);
    $stmt2->execute();
}

echo json_encode(['success' => true, 'message' => 'Horário atualizado com sucesso']);
exit;
?>
