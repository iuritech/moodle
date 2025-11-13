<?php
session_start();

include('bd.h');
include('bd_final.php');

$conn = mysqli_connect("localhost:3306", "root", "", "apoio_utc_2021_2022");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão']);
    exit;
}

// Recebe dados do AJAX
$action = $_POST['action'] ?? '';
$id_docente = intval($_POST['id_docente'] ?? 0);
$id_horario = intval($_POST['id_horario'] ?? 0);
$id_componente = intval($_POST['id_componente'] ?? 0);
$id_turma = intval($_POST['id_turma'] ?? 0);

// Validação básica
if (!$id_docente || !$id_horario || !$id_componente) {
    die(json_encode(['success' => false, 'message' => 'Dados em falta.']));
}

try {
    if ($action === 'add') {
        // Verifica se já existe uma aula igual
        $sql_check = "SELECT * FROM aula WHERE id_horario=? AND id_docente=?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $id_horario, $id_docente);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("Já existe uma aula neste horário para este docente.");
        }

        // Insere a nova aula
        /* $sql = "INSERT INTO aula (id_componente, id_horario, id_turma, id_docente) VALUES (?, ?, ?, ?)"; */
        $sql = "UPDATE aula SET id_horario = ? WHERE id_componente=? AND id_docente=? AND id_turma=?;" ;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $id_horario, $id_componente, $id_docente, $id_turma);

        if ($stmt->execute()) {
            echo "Aula atribuída com sucesso!";
        } else {
            echo "Erro ao atribuir aula: " . $conn->error;
        }
    } elseif ($action === 'remove') {
        // Remove a aula
        /* $sql = "DELETE FROM aula WHERE id_componente=? AND id_horario=? AND id_docente=? AND id_turma=?"; */ 
        $sql = "UPDATE aula SET id_horario = 0 WHERE id_componente=? AND id_horario=? AND id_docente=? AND id_turma=?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $id_componente, $id_horario, $id_docente, $id_turma);

        if ($stmt->execute()) {
            echo "Aula removida com sucesso!";
        } else {
            echo "Erro ao remover aula: " . $conn->error;
        }
    } elseif ($_POST['action'] == 'move') {
        // Verifique se o novo horário está livre
        $sql_check = "SELECT * FROM aula WHERE id_horario=? AND id_docente=?";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("ii", $id_horario, $id_docente);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Já existe uma aula neste horário para este docente.");
        }

        // Fazer troca
        $update_sql = "UPDATE aula SET id_horario = ? WHERE id_componente = ? AND id_horario = ? AND id_docente = ? AND id_turma = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("iiiii", $id_horario, $id_componente, $_POST['id_horario_antigo'], $id_docente, $id_turma);

        if ($stmt->execute()) {
            echo "Aula movida com sucesso!";
        } else {
            echo "Erro ao mover aula: " . $conn->error;
        }
    } else {
        throw new Exception("Ação inválida.");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($stmt))
        $stmt->close();
    if (isset($stmt_check))
        $stmt_check->close();
    $conn->close();
}
?>
