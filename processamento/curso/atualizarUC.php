<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_uc']) && isset($_POST['nome_introduzido']) && isset($_POST['codigo_introduzido']) && isset($_POST['abreviacao_introduzida']) && isset($_POST['area_introduzida']) && isset($_POST['responsavel_introduzido'])){

		$id_uc = $_POST['id_uc'];
		$nome_introduzido = $_POST['nome_introduzido'];
		$codigo_introduzido = $_POST['codigo_introduzido'];
		$abreviacao_introduzida = $_POST['abreviacao_introduzida'];
		$area_introduzida = $_POST['area_introduzida'];
		$responsavel_introduzido = $_POST['responsavel_introduzido'];
		
		$statement = mysqli_prepare($conn, "UPDATE disciplina SET nome_uc = '$nome_introduzido' WHERE id_disciplina = $id_uc;");
		$statement->execute();		
		
		$statement1 = mysqli_prepare($conn, "UPDATE disciplina SET codigo_uc = $codigo_introduzido WHERE id_disciplina = $id_uc;");
		$statement1->execute();	

		$statement2 = mysqli_prepare($conn, "UPDATE disciplina SET abreviacao_uc = '$abreviacao_introduzida' WHERE id_disciplina = $id_uc;");
		$statement2->execute();	

		$statement3 = mysqli_prepare($conn, "UPDATE disciplina SET id_area = $area_introduzida WHERE id_disciplina = $id_uc;");
		$statement3->execute();		
		
		$statement4 = mysqli_prepare($conn, "UPDATE disciplina SET id_responsavel = $responsavel_introduzido WHERE id_disciplina = $id_uc;");
		$statement4->execute();	
	
	}
?>