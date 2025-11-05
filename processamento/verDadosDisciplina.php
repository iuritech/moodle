<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

$idAreaUtilizador = (int) $_SESSION['area_utilizador'];

$permAdmin = false;

if(isset($_POST['id_disciplina'])){

	$id_disciplina = $_POST['id_disciplina'];
	
	$array_dados = array();
	
	$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = $id_disciplina;");
	$statement->execute();
	$resultado1 = $statement->get_result();
	$linha1 = mysqli_fetch_assoc($resultado1);
	
		$idArea = $linha1["id_area"];
		$idDisciplina = $linha1["id_disciplina"];
		$imgDisciplina = $linha1["imagem"];
		$idCurso = $linha1["id_curso"];
		$id_curso = $linha1["id_curso"];
		$semestre = $linha1["semestre"];
		
		$statement000 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
		$statement000->execute();
		$resultado000 = $statement000->get_result();
		$linha000 = mysqli_fetch_assoc($resultado000);
			$id_utc_disciplina = $linha000["id_utc"];
					
		$statement00 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc_disciplina;");
		$statement00->execute();
		$resultado00 = $statement00->get_result();
		$linha00 = mysqli_fetch_assoc($resultado00);
			$id_responsavel_utc_disciplina = $linha00["id_responsavel"];
		
		array_push($array_dados,$idArea);
		array_push($array_dados,$idDisciplina);
		array_push($array_dados,$imgDisciplina);
		
	$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = $idCurso;");
	$statement->execute();
	$resultado2 = $statement->get_result();
	$linha2 = mysqli_fetch_assoc($resultado2);
	
		$siglaCurso = $linha2["sigla"];
		
		array_push($array_dados,$siglaCurso);
		
		$nomeDisciplina = $linha1["nome_uc"];
		$codigoUc = $linha1["codigo_uc"];
		
		array_push($array_dados,$nomeDisciplina);
		array_push($array_dados,$codigoUc);
		
		$id_responsavel_uc = $linha1["id_responsavel"];
		
	/*	//Ir ver os docentes desta disciplina e colocÃ¡-los num array
		$array_docentes = array();
		
		$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_responsavel_uc;");
		$statement->execute();
		$resultado3 = $statement->get_result();
		$linha3 = mysqli_fetch_assoc($resultado3);
	
			$nomeResponsavel = $linha3["nome"];
			$imgResponsavel = $linha3["imagem_perfil"];
			array_push($array_docentes,$nomeResponsavel);
			array_push($array_docentes,$imgResponsavel);
			
			array_push($array_dados,$array_docentes);
				*/
	
		array_push($array_dados,$idAreaUtilizador);
		
		$idAreaDisciplina = $linha1["id_area"];
		
		array_push($array_dados,$idAreaDisciplina);
		array_push($array_dados,$permAdmin);
		
		$array_docentes = array();
		
		$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $id_responsavel_uc;");
		$statement->execute();
		$resultado3 = $statement->get_result();
		$linha3 = mysqli_fetch_assoc($resultado3);
	
			$nomeResponsavel = $linha3["nome"];
			$imgResponsavel = $linha3["imagem_perfil"];
			array_push($array_docentes,$nomeResponsavel);
			array_push($array_docentes,$imgResponsavel);
			
			array_push($array_dados,$array_docentes);
			
			$array_componentes = array();
			
		$statement = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM componente WHERE id_disciplina = $id_disciplina;");
		$statement->execute();
		$resultado4 = $statement->get_result();
		while($linha4 = mysqli_fetch_assoc($resultado4)){
			$id_componente = $linha4["id_componente"];
			array_push($array_componentes,$id_componente);
		}
			
		$array_componentes_final = implode(",",$array_componentes);
			
		//Ver número de docentes que nao o responsável
		$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_docente) FROM aula WHERE id_componente IN ($array_componentes_final) AND id_docente != $id_responsavel_uc;");
		$statement->execute();
		$resultado5 = $statement->get_result();
		$linha5 = mysqli_fetch_assoc($resultado5);
			$numero_docentes_outros = $linha5["COUNT(DISTINCT id_docente)"];
			array_push($array_dados,$numero_docentes_outros);
			
			array_push($array_dados,$id_responsavel_uc);
			
			array_push($array_dados,$id_responsavel_utc_disciplina);
			
			array_push($array_dados,$semestre);
	/*
		$array_componentes = array();
	
		//Ver restantes professores
		$statement = mysqli_prepare($conn, "SELECT id_componente FROM componente WHERE id_disciplina = $idDisciplina ORDER BY id_componente;");
		$statement->execute();
		$resultado4 = $statement->get_result();
		while($linha4 = mysqli_fetch_assoc($resultado4)){
		
			$id_componente = $linha4["id_componente"];
			array_push($array_componentes,$id_componente);
		
		}
		
		$array_componentes_final = implode(",",$array_componentes);
	
		$statement = mysqli_prepare($conn, "SELECT u.nome, u.imagem_perfil FROM utilizador u
											INNER JOIN aula a ON u.id_utilizador = a.id_docente
											WHERE a.id_componente IN ($array_componentes_final) AND a.id_docente != $id_responsavel_uc;");
		$statement->execute();
		$resultado5 = $statement->get_result();
		$linha5 = mysqli_fetch_assoc($resultado5);
	
			$nomeDocente = $linha5["nome"];
			$imgDocente = $linha5["imagem_perfil"];
			array_push($array_dados,$nomeDocente);
			array_push($array_dados,$imgDocente);
	*/
	echo json_encode($array_dados);

}