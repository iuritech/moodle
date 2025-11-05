<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');
		
	if(isset($_POST['id_turma']) && isset($_POST['id_juncao'])){

		$id_turma = $_POST['id_turma'];
		$id_juncao = $_POST['id_juncao'];
		
		$id_turma_extra;

		//Ver se existem mais turmas na junção
		$statement1 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao AND id_turma != $id_turma;");
		$statement1->execute();
		$resultado1 = $statement1->get_result();
		$linha1 = mysqli_fetch_assoc($resultado1);
			$num_turmas_extra = $linha1["COUNT(DISTINCT id_turma)"];
		
		if($num_turmas_extra == 1){
			//Remover também a junção nesta turma
			$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM aula WHERE id_juncao = $id_juncao AND id_turma != $id_turma;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			$linha2 = mysqli_fetch_assoc($resultado2);
				$id_turma_extra = $linha2["id_turma"];
				//mysqli_query($conn, "UPDATE aula SET id_juncao = NULL WHERE id_turma = $id_turma_extra AND id_juncao = $id_juncao;"
		
				mysqli_query($conn, "UPDATE aula SET id_juncao = NULL WHERE id_juncao = $id_juncao;");
				$statement = mysqli_prepare($conn, "DELETE FROM juncao WHERE id_juncao = $id_juncao;");
				$statement->execute();
				
				$statement2 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao;");
				$statement2->execute();		
		}
		else{
		//Atualizar as componentes na tabela aula
		//$statement2 = mysqli_prepare($conn, "UPDATE aula SET id_docente = NULL WHERE id_componente IN (array_componentes_final) AND id_docente = $id_docente;");
			
			$statement3 = mysqli_prepare($conn, "SELECT id_componente FROM aula WHERE id_turma = $id_turma AND id_juncao = $id_juncao;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$id_componente_turma = $linha3["id_componente"];
			
			//Ver se apenas há uma componente deste tipo na tabela componente_turma, se sim remover também nesta tabela
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM aula WHERE id_juncao = $id_juncao AND id_componente = $id_componente_turma;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);
				$num_turmas = (int) $linha4["COUNT(DISTINCT id_turma)"];
				if($num_turmas == 1){
					$statement5 = mysqli_prepare($conn, "DELETE FROM juncao_componente WHERE id_juncao = $id_juncao AND id_componente = $id_componente_turma;");
					$statement5->execute();	
				}
				
			mysqli_query($conn, "UPDATE aula SET id_juncao = NULL WHERE id_turma = $id_turma AND id_juncao = $id_juncao;");
		}
	}
?>