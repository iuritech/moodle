<?php

define("NOME_BD", "apoio_utc_2021_2022");
define("USER_BD", "root");
define("PASS_BD", "");

$hostname_conn = "localhost";
$database_conn = NOME_BD;
$username_conn = USER_BD;
$password_conn = PASS_BD;

// Conectar ao servidor MySQL
$conn = mysqli_connect($hostname_conn,$username_conn,$password_conn);
if(!$conn)
{
   echo "Erro ao conectar ao MySQL.";
   exit;
}

// Selecionar a base de dados
$query = mysqli_select_db($conn,$database_conn);

// UTF-8 para todos os dados obtidos da BD
mysqli_set_charset($conn,'utf8');

?>
