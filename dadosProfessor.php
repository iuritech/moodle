<?php
include('bd.h');
include('bd_final.php');

// Obter o ID do professor da URL
$idProfessor = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Verificação básica para garantir que o ID do professor foi passado
if ($idProfessor == 0) {
    echo json_encode(["error" => "ID do professor não fornecido"]);
    exit;
}

// Simulação de dados de salas (substitua isso por dados reais, se necessário)
$salas = ["Sala A", "Sala B", "Sala C", "Sala D"];
$presencas = [rand(50, 100), rand(50, 100), rand(50, 100), rand(50, 100)];

// Calcular porcentagens com base no total
$total = array_sum($presencas);
$porcentagens = array_map(function ($valor) use ($total) {
    return round(($valor / $total) * 100, 2);
}, $presencas);

// Retornar os dados como JSON
echo json_encode([
    "salas" => $salas,
    "porcentagens" => $porcentagens
]);
?>