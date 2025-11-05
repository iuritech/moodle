<?php
// Processamento do formulário de login

include('../bd.h');

session_start(); 

if (isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
    exit();
}

$query = mysqli_select_db($conn,"apoioutc_ano_atual");

$statement0 = mysqli_prepare($conn, "SELECT ano_atual FROM ano_atual;");
$statement0->execute();
$resultado0 = $statement0->get_result();
$linha0 = mysqli_fetch_assoc($resultado0);
	$bd_atual = $linha0["ano_atual"];

/* $bd_atual = "apoio_utc_2021_2022"; */
$query = mysqli_select_db($conn,$bd_atual);

// Valores do formulário
$loginForm = filter_input(INPUT_POST, 'login');
/////////////////////////////////////////////////
$passwordForm = filter_input(INPUT_POST, 'password');

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE login = '$loginForm'");
$statement->execute();
$resultado = $statement->get_result();

if(mysqli_num_rows($resultado)==0){
    echo"<script language='javascript' type='text/javascript'>alert('Utilizador não existente!');window.location.href='../index.php'</script>";
    exit();
}
$linha = mysqli_fetch_assoc($resultado);

$idUtilizador = (int) $linha["id_utilizador"];
$hash = $linha["password"];
$nome = $linha["nome"];
$img_perfil = $linha["imagem_perfil"];
$idArea = $linha["id_area"];
$idUTC = $linha["id_utc"];

// Verificação da password
if(!($passwordForm === $hash)){
    echo"<script language='javascript' type='text/javascript'>alert('Credenciais inválidas!');window.location.href='../index.php'</script>";
    exit();
}

// Iniciar sessao
$_SESSION["sessao"] = '1';

// Variáveis de sessão para averiguar o utilizador
$_SESSION['id'] = $idUtilizador;
$_SESSION['nome'] = $nome;
$_SESSION['img_perfil'] = $img_perfil;
$_SESSION['area_utilizador'] = $idArea;
$_SESSION['utc_utilizador'] = $idUTC;
$_SESSION['bd'] = $bd_atual;
$_SESSION['semestre'] = 2;

$statement2 = mysqli_prepare($conn, "SELECT is_admin FROM utilizador WHERE id_utilizador = $idUtilizador");
$statement2->execute();
$resultado2 = $statement2->get_result();
$linha2 = mysqli_fetch_assoc($resultado2);
	$is_admin = $linha2["is_admin"];
		
	if($is_admin == 1){
		$_SESSION['permAdmin'] = 1;
	}
	
$statement3 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc;");
$statement3->execute();
$resultado3 = $statement3->get_result();
while($linha3 = mysqli_fetch_assoc($resultado3)){
	$id_responsavel = $linha3["id_responsavel"];
		
	if($id_responsavel == $idUtilizador){
		$_SESSION['permUTC'] = 1;
	}
}

// Redirecionar para a página inicial	
header("Location: ../home.php");
