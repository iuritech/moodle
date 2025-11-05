<?php
// Página de visualização das junções das turmas (visJuncoes)

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$idUtilizador = (int) $_SESSION['id'];
$permAdmin = false;
$permUTC = false;
$permArea = false;
$coordenador_UTC = false;

if(isset($_SESSION['permAdmin'])){
    $permAdmin = true;
}
if(isset($_SESSION['permUTC'])){
    $permUTC = true;
}
if(isset($_SESSION['permArea'])){
    $permArea = true;
}


$idUTCUtilizador = 0;
$idAreaUtilizador = 0;

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
$statement->bind_param('i', $idUtilizador);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
if(!empty($linha["id_area"])){
    $idAreaUtilizador = (int) $linha["id_area"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = ?");
    $statement->bind_param('i', $idAreaUtilizador);
    $statement->execute();
    $resultado = $statement->get_result();
    $linha = mysqli_fetch_assoc($resultado);
    $idUTCUtilizador = (int) $linha["id_utc"];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Salas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .menu-container {
            width: 300px;
            margin: 50px auto;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }
        .menu-header {
            background-color: #007BFF;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
        }
        .menu-item {
            display: block;
            padding: 15px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .menu-item:hover {
            background-color: #f1f1f1;
        }
        .menu-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>  
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <?php gerarHome1() ?>
    <div class="menu-container">
        <div class="menu-header">Menu de Salas</div>
  
        <?php
            // Definir as opções do menu
            $menu_options = [
                "Gerir Salas" => "gerirsalas.php",
                "Consultar Salas" => "visSalaporUC.php",
                "Ver Salas por Turma" => "salasTurma.php",
                "Ver Salas por Docente" => "salasDocente.php"
            ];

            // Exibir as opções
            foreach ($menu_options as $label => $link) {
                echo "<a href='$link' class='menu-item'>$label</a>";
            }
        ?>
    </div>
</body>
</html>
