<?php
include('../../bd.h');
include('../../bd_final.php');

// Receber dados JSON do JavaScript
$data = json_decode(file_get_contents("php://input"), true);

// Validação
if (empty($data['nomeSala']) || empty($data['blocoSala'])) {
    echo "Nome e Bloco são obrigatórios!";
    exit;
}

$nomeSala = $data['nomeSala'];
$blocoSala = $data['blocoSala'];

// Inserção no banco de dados
$query = "INSERT INTO sala (nome_sala, bloco_sala) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ss', $nomeSala, $blocoSala);

if (mysqli_stmt_execute($stmt)) {
    echo "Sala criada com sucesso!";
} else {
    echo "Erro ao criar sala: " . mysqli_error($conn);
}
?>