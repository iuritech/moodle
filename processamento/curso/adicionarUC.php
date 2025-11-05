<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../../bd.h');
include('../../bd_final.php');
		
	if(isset($_POST['nome']) && isset($_POST['codigo']) && isset($_POST['abreviacao']) && isset($_POST['ano']) && isset($_POST['semestre']) && isset($_POST['id_area']) && isset($_POST['id_responsavel']) && isset($_POST['id_curso']) && isset($_POST['componentes_introduzidas'])){

		$nome = $_POST['nome'];
		$codigo = $_POST['codigo'];
		$abreviacao = $_POST['abreviacao'];
		$ano = $_POST['ano'];
		$semestre = $_POST['semestre'];
		$id_area = $_POST['id_area'];
		$id_responsavel = $_POST['id_responsavel'];
		$id_curso = $_POST['id_curso'];
		$componentes_introduzidas = $_POST["componentes_introduzidas"];
	
		$statement = mysqli_prepare($conn, "INSERT INTO disciplina(id_disciplina,nome_uc,codigo_uc,abreviacao_uc,ano,semestre,id_responsavel,id_area,id_curso,imagem) VALUES (NULL,'$nome',$codigo,'$abreviacao',$ano,$semestre,$id_responsavel,$id_area,$id_curso,'');");
		$statement->execute();
		
		$id_disciplina = mysqli_insert_id($conn);
		
		$loop_componentes = 0;
		while($loop_componentes < sizeof($componentes_introduzidas)){
				
			$id_tipocomponente = $componentes_introduzidas[$loop_componentes];
			$num_horas = $componentes_introduzidas[$loop_componentes + 2];
				
			echo $id_tipocomponente, " - ", $num_horas, "  /  ";
				
			$statement1 = mysqli_prepare($conn, "INSERT INTO componente(id_componente,id_disciplina,id_tipocomponente,numero_horas) VALUES(NULL,$id_disciplina,$id_tipocomponente,$num_horas);");
			$statement1->execute();
			
			$id_componente = mysqli_insert_id($conn);
			
			$statement2 = mysqli_prepare($conn, "SELECT DISTINCT id_turma FROM turma WHERE ano = $ano AND semestre = $semestre AND id_curso = $id_curso;");
			$statement2->execute();
			$resultado2 = $statement2->get_result();
			while($linha2 = mysqli_fetch_assoc($resultado2)){
				$id_turma = $linha2["id_turma"];
				
				$statement3 = mysqli_prepare($conn, "INSERT INTO aula(id_componente,id_horario,id_turma,id_docente,id_juncao) VALUES($id_componente,NULL,$id_turma,NULL,NULL);");
				$statement3->execute();
			}
			
			$loop_componentes += 3;
		}

	}
?>