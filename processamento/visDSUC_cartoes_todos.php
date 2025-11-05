<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}

include('../bd.h');
include('../bd_final.php');

$string_DIV_array = array();
		$string_DIV = "123123";
			
		array_push($string_DIV_array,$string_DIV);

$List = implode(",",$string_DIV_array);
return $string_DIV_array;

?>