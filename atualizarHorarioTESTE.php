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
$id_aula = intval($_POST['id_aula'] ?? 0);
$id_horario = intval($_POST['id_horario'] ?? 0);
$id_juncao = intval($_POST['id_juncao'] ?? 0);

// Validação básica
/* if (!$id_aula || !$id_horario) { */
/*     die(json_encode(['success' => false, 'message' => 'Dados em falta.'])); */
/* } */

function muda_aula($id_horario,$id_aula,$id_juncao,$conn){
    if ($id_juncao > 0){
            $sql = "UPDATE aula SET id_horario = ? WHERE id_juncao=?;" ;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_horario, $id_juncao);
        }else {
            $sql = "UPDATE aula SET id_horario = ? WHERE id_aula=?;" ;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_horario, $id_aula);
        }
        if ($stmt->execute()) {
            echo "sucesso!";
        } else {
            echo "Erro" . $conn->error;
        }
}
try {
    if ($action === 'add') {

        /* // Verifica se já existe uma aula igual */
        /* $sql_check = "SELECT * FROM aula WHERE id_horario=?"; */
        /* $stmt_check = $conn->prepare($sql_check); */
        /* $stmt_check->bind_param("i", $id_horario); */
        /* $stmt_check->execute(); */
        /* $result = $stmt_check->get_result(); */
        /* if ($result->num_rows > 0) { */
        /*     throw new Exception("Já existe uma aula neste horário para este docente."); */
        /* } */

        // Insere a nova aula
        muda_aula($id_horario,$id_aula,$id_juncao,$conn);
        


    } elseif ($action === 'remove') {
        // Remove a aula
        muda_aula(0,$id_aula,$id_juncao,$conn);

    } elseif ($_POST['action'] == 'move') {
        
        /* // Verifique se o novo horário está livre */
        /* $sql_check = "SELECT * FROM aula WHERE id_docente=? and id_horario=?"; */
        /* $stmt = $conn->prepare($sql_check); */
        /* $stmt->bind_param("ii", $id_docente, $id_horario); */
        /* $stmt->execute(); */
        /* if ($stmt->get_result()->num_rows > 0) { */
        /*     throw new Exception("Já existe uma aula neste horário para este docente."); */
        /* } */

        // Fazer troca
        muda_aula($id_horario,$id_aula,$id_juncao,$conn);
        
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
