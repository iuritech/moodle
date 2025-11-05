<?php
include('bd.h'); 

if (isset($_GET['id_componente'])) {
    $idComponente = (int) $_GET['id_componente'];

    // ver as salas e ver quais estão atribuídas
    $query = "SELECT s.id_sala, s.sigla_sala, 
                     CASE WHEN sca.id_sala IS NOT NULL THEN 1 ELSE 0 END AS atribuida
              FROM sala s
              LEFT JOIN sala_componente_disponivel sca ON s.id_sala = sca.id_sala AND sca.id_componente = ?";

    if ($stmt = mysqli_prepare($conn, $query)) {
        $stmt->bind_param('i', $idComponente);
        $stmt->execute();
        $result = $stmt->get_result();

        $salas = [];
        while ($row = $result->fetch_assoc()) {
            $salas[] = [
                'id_sala' => $row['id_sala'],
                'nome_sala' => $row['sigla_sala'],
                'atribuida' => (bool)$row['atribuida']
            ];
        }
        echo json_encode($salas);
    } else {
        echo json_encode(['error' => 'ERRO']);
    }
}
?>