<?php
// Obtém o ID da sala a partir da requisição AJAX
$idSala = $_POST['id'];

// Consulta ao banco de dados para obter os dados da sala
$sql = "SELECT * FROM sala WHERE id_sala = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $idSala);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Retorna os dados da sala em formato JSON
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Sala não encontrada']);
} ?>