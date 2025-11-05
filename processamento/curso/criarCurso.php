<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['nome']) && isset($_POST['sigla']) && isset($_POST['tipo_curso']) && isset($_POST['duracao']) && isset($_POST['coordenador']) && isset($_POST['imagem']) && isset($_POST['utc'])){

		$nome = $_POST['nome'];
		$sigla_temp = $_POST['sigla'];
		$sigla = strtoupper($sigla_temp);
		$tipo_curso = $_POST['tipo_curso'];
		$duracao = $_POST['duracao'];
		$coordenador = $_POST['coordenador'];
		$imagem = $_POST['imagem'];
		$utc = $_POST['utc'];
		
		$statement = mysqli_prepare($conn, "SELECT nome, sigla FROM curso_tipo WHERE id_tipo_curso = $tipo_curso;");
		$statement->execute();
		$resultado = $statement->get_result();
		$linha = mysqli_fetch_assoc($resultado);
			$nome_tipo_curso = $linha["nome"];
			$sigla_tipo_curso = $linha["sigla"];
		
		$nome_completo_temp = "curso " . $nome_tipo_curso . " em " . $nome;
		$nome_completo = strtoupper($nome_completo_temp);
		
		$sigla_completa = $sigla_tipo_curso . $sigla;
		
		$semestres = $duracao * 2;
	
		$statement = mysqli_prepare($conn, "INSERT INTO curso(id_curso,nome,nome_completo,sigla,sigla_completa,semestres,imagem_curso,id_utc,id_tipo_curso,id_coordenador) 
											VALUES (NULL,'$nome','$nome_completo','$sigla','$sigla_completa',$semestres,'$imagem',$utc,$tipo_curso,$coordenador)");
		$statement->execute();
		
		$id_curso = mysqli_insert_id($conn);
		
		echo $id_curso;
	}
?>