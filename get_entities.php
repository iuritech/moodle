<?php
session_start();
if (!isset($_SESSION["sessao"])) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

$conn = mysqli_connect("localhost:3306", "root", "");
if (mysqli_connect_errno()) {
    echo json_encode(['error' => 'Erro ao conectar ao MySQL: ' . mysqli_connect_error()]);
    exit;
}

mysqli_set_charset($conn, 'utf8');
mysqli_select_db($conn, "apoio_utc_2021_2022");

$entity_type = $_POST['entity_type'] ?? '';

if (empty($entity_type)) {
    echo json_encode(['error' => 'Tipo de entidade não especificado']);
    exit;
}

$queries = [
    'Docente' => "SELECT id_utilizador AS id, nome AS name FROM utilizador WHERE id_funcao >= 4",
    'Turma' => "SELECT id_turma AS id, nome AS name FROM turma",
    'Sala' => "SELECT id_sala AS id, nome_sala AS name FROM sala"
];

if (!isset($queries[$entity_type])) {
    echo json_encode(['error' => 'Tipo de entidade inválido']);
    exit;
}

$result = mysqli_query($conn, $queries[$entity_type]);
if (!$result) {
    echo json_encode(['error' => 'Erro na consulta: ' . mysqli_error($conn)]);
    exit;
}

$entities = [];
while ($row = mysqli_fetch_assoc($result)) {
    $entities[] = $row;
}

echo json_encode($entities);
mysqli_close($conn);
?>