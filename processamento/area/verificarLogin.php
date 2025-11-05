<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['login'])){

		$login = $_POST['login'];
	
		$statement = mysqli_prepare($conn, "SELECT COUNT(login) FROM utilizador WHERE login = '$login';");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$login_existente = $linha["COUNT(login)"];
			
			if($login_existente == 1){
				echo 1;
			}
			else{
				echo 0;
			}
		
	}
?>