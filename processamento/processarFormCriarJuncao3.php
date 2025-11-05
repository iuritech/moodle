<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

if(isset($_POST['id_comp']) && isset($_POST['id_turma']) && isset($_POST['id_junc'])){

	$id_comp = $_POST['id_comp'];
	$id_turma = $_POST['id_turma'];
	$id_juncao = $_POST['id_junc'];
	
	$sql = "UPDATE `componente_turma` SET `id_juncao` = '$id_juncao' AND `id_docente` = '$id_juncao' WHERE `componente_turma`.`id_componente` = '$id_comp' AND `componente_turma`.`id_turma` = '$id_turma';";
	
	$statement = mysqli_prepare($conn, $sql);
	$statement->execute();

}