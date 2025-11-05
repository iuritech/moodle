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
<html lang="en">
<head><meta charset="utf-8" />
    <title>Bootstrap 4 TreeView</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<<div class="container-fluid">
        <div id="tree"></div>
    </div>
    <script type="text/javascript">
            $(document).ready(function () {
                $('#tree').tree({
                    uiLibrary: 'bootstrap4',
                    dataSource: 'verDadosJuncoes.php',
                    primaryKey: 'id',
                    imageUrlField: 'flagUrl'
                });
            });
    </script>
</body>
</html>