<?php

include('bd.h');

// Verificar a conexão
if (!$conn) {
    die('Erro ao conectar ao banco de dados: ' . mysqli_connect_error());
}

// Verificar se os parâmetros foram enviados
if (isset($_POST['salas'], $_POST['componente'])) {
    $salasSelecionadas = $_POST['salas']; 
    $componente = (int)$_POST['componente'];

    // Validar os dados recebidos
    if ($componente <= 0) {
        echo json_encode(['success' => false, 'message' => 'Componente inválido fornecido.']);
        exit;
    }

    // Obter todas as salas atualmente associadas ao componente
    $querySalasExistentes = "SELECT id_sala FROM sala_componente_disponivel WHERE id_componente = ?";
    $stmt = mysqli_prepare($conn, $querySalasExistentes);
    mysqli_stmt_bind_param($stmt, 'i', $componente);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $salasExistentes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $salasExistentes[] = (int)$row['id_sala'];
    }

    // Salas a adicionar
    $salasAdicionar = array_diff($salasSelecionadas, $salasExistentes);

    // Salas a remover
    $salasRemover = array_diff($salasExistentes, $salasSelecionadas);

    // Inserir novas associações
    foreach ($salasAdicionar as $id_sala) {
        $id_sala = (int)$id_sala;
        $queryInsert = "INSERT INTO sala_componente_disponivel (id_componente, id_sala) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $queryInsert);
        mysqli_stmt_bind_param($stmt, 'ii', $componente, $id_sala);
        mysqli_stmt_execute($stmt);
    }

    // Remover associações desmarcadas
    foreach ($salasRemover as $id_sala) {
        $id_sala = (int)$id_sala;
        $queryDelete = "DELETE FROM sala_componente_disponivel WHERE id_componente = ? AND id_sala = ?";
        $stmt = mysqli_prepare($conn, $queryDelete);
        mysqli_stmt_bind_param($stmt, 'ii', $componente, $id_sala);
        mysqli_stmt_execute($stmt);
    }

    echo json_encode(['success' => true, 'message' => 'Salas atualizadas com sucesso.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Dados insuficientes. Certifique-se de enviar salas e componente.']);
}
