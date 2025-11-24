<?php
session_start();
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

estaLogado();

if ($_POST['id_aula'] ||  $_POST['id_sala']){
    $aula = $_POST['id_aula'];
    $sala = $_POST['id_sala'];
    $sql="select id_sala from sala where sigla_sala='$sala'";
    $id_sala=runQuery($conn,$sql)[0]['id_sala'];
    $sql="select id_juncao from aula where id_aula='$aula'";
    $juncao=runQuery($conn,$sql)[0]['id_juncao'];
    if ($juncao){
        $sql = "UPDATE aula SET id_sala = ? WHERE id_juncao=?;" ;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_sala, $juncao);
    }else {
        $sql = "UPDATE aula SET id_sala = ? WHERE id_aula=?;" ;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_sala, $aula);
    }
    if ($stmt->execute()) {
        header("Location: gerirHorarios.php");
    } else {
        echo "Erro" . $conn->error;
    }
}



function runQuery($conn, $sql) {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    // Retorna todas as linhas como array associativo
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>
