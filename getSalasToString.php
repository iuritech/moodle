<?php
session_start();

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

if (!isset($_GET['id_componente'])) {
    exit;
}

$idComponente = (int)$_GET['id_componente'];

echo get_salas_to_string($conn, $idComponente);
