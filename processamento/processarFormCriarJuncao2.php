<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

if(isset($_POST['id_comp']) && isset($_POST['id_turma'])){

	$id_comp = $_POST['id_comp'];
	$id_turma = $_POST['id_turma'];
	$statement = mysqli_prepare($conn, "INSERT INTO componente_turma(id_componente,id_turma) VALUES ($id_comp,$id_turma);");
	$statement->execute();

}