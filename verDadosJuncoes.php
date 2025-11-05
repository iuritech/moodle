<?php

//Ler os dados das junções na BD

session_start();

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$idUtilizador = (int) $_SESSION['id'];

$statement = mysqli_prepare($conn, "SELECT * FROM juncao E INNER JOIN bloco_turma B ON E.id_bloco = B.id_bloco");
$statement->execute();
$resultado = $statement->get_result();
while($linha = mysqli_fetch_array($resultado))
{
		$sub_data["id_juncao"] = $linha["id_juncao"];
		$sub_data["nome"] = $linha["nome"];
		$sub_data["id_bloco"] = $linha["id_bloco"];
		$sub_data["id_turma"] = $linha["id_turma"];
		$data[] = $sub_data;
}
/*
while($linha = mysqli_fetch_assoc($resultado)){
	if(!empty($linha["id_juncao"])){
		$sub_data["id_juncao"] = $linha["id_juncao"];
		$sub_data["nome"] = $linha["nome"];
		$sub_data["id_bloco"] = $linha["id_bloco"];
		$sub_data["id_turma"] = $linha["id_turma"];
		$data[] = $sub_data;
	}	
}

//Loop de todos os blocos
$statement2 = mysqli_prepare($conn, "SELECT * FROM bloco_turma");
$statement2->execute();
$resultado2 = $statement2->get_result();
while($linha2 = mysqli_fetch_assoc($resultado2)){
	if(!empty($linha2["id_bloco"])){
		$sub_data2["id_bloco"] = $linha2["id_bloco"];
		$sub_data2["id_turma"] = $linha2["id_turma"];
		$data2[] = $sub_data2;
	}	
}

foreach($data as $key => &$value){
	$output[$value["id_juncao"]] = &$value;
}

foreach($data as $key => &$value){
	if($value['id_bloco'] && isset($output[$value['id_bloco']])){
		$output[$value['id_bloco']]['nodes'][] = &$value;
	}
}
*/

echo json_encode($data);
/*
echo '<pre>';
print_r($data);
echo '</pre>';

echo '<pre>';
print_r($data2);
echo '</pre>';
*/

?>

