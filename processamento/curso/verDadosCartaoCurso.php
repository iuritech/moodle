<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['id_tipo_curso'])){
		
		$id_tipo_curso = $_POST['id_tipo_curso'];
		$id_utilizador = $_SESSION['id'];
		
		$array = array();
		
		$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_tipo_curso = $id_tipo_curso;");
		$statement->execute();
		$resultado = $statement->get_result();
		while($linha = mysqli_fetch_assoc($resultado)){
			$id_curso = $linha["id_curso"];
			$nome_curso = $linha["nome"];
			$sigla_curso = $linha["sigla"];
			$id_coordenador = $linha["id_coordenador"];
			$id_utc_curso = $linha["id_utc"];
			$imagem_curso = $linha["imagem_curso"];
		
			$statement1 = mysqli_prepare($conn, "SELECT nome, imagem_perfil FROM utilizador WHERE id_utilizador = $id_coordenador;");
			$statement1->execute();
			$resultado1 = $statement1->get_result();
			$linha1 = mysqli_fetch_assoc($resultado1);
				$imagem_perfil_coordenador = $linha1["imagem_perfil"];
				$nome_coordenador = $linha1["nome"];
				
			$statement2 = mysqli_prepare($conn, "SELECT sigla FROM curso_tipo WHERE id_tipo_curso = $id_tipo_curso;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			$linha2 = mysqli_fetch_assoc($resultado2);
				$sigla_tipo_curso = $linha2["sigla"];
		
			$statement3 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_disciplina) FROM disciplina WHERE id_curso = $id_curso;");
			$statement3->execute();
			$resultado3 = $statement3->get_result();
			$linha3 = mysqli_fetch_assoc($resultado3);
				$num_disciplinas_curso = $linha3["COUNT(DISTINCT id_disciplina)"];
							
			$statement4 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_turma) FROM turma WHERE id_curso = $id_curso;");
			$statement4->execute();
			$resultado4 = $statement4->get_result();
			$linha4 = mysqli_fetch_assoc($resultado4);			
				$num_turmas_curso = $linha4["COUNT(DISTINCT id_turma)"];
		
			$is_admin = 0;
			$coordenador_UTC = 0;

			$statement5 = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_utilizador");
			$statement5->execute();
			$resultado5 = $statement5->get_result();
			$linha5 = mysqli_fetch_assoc($resultado5);
				$id_utc_utilizador = $linha5["id_utc"];
				
				if($linha5["is_admin"] == 1){
					$is_admin = 1;
				}
				
			$statement6 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc_utilizador;");
			$statement6->execute();
			$resultado6 = $statement6->get_result();
			$linha6 = mysqli_fetch_assoc($resultado6);
				$id_responsavel_utc_utilizador = $linha6["id_responsavel"];
				
				if($id_responsavel_utc_utilizador == $id_utilizador){
					$coordenador_UTC = 1;
				}
		
				array_push($array,$id_curso);
				array_push($array,$is_admin);
				array_push($array,$coordenador_UTC);
				array_push($array,$nome_curso);
				array_push($array,$sigla_tipo_curso);
				array_push($array,$imagem_perfil_coordenador);
				array_push($array,$nome_coordenador);
				array_push($array,$num_disciplinas_curso);
				array_push($array,$num_turmas_curso);
				array_push($array,$imagem_curso);
		}
		
		$array_final = implode(",",$array);
		print_r($array_final);
	}
?>