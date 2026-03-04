<html>
<body>
<h2>Pagina de teste</h2>
<?php
session_start();
include('ferramentas.php');
include('bd.h');
include('bd_final.php');


$sql="select * from sala";
$salas = runQuery($conn,$sql);

foreach ($salas as $s){
    print $s["id_sala"].' '.$s["nome_sala"];
    echo "<br>";
}
function sobreposto(){
    return;
}

// função para correr qualquer instrução sql evita repetição de código
function runQuery($conn, $sql) {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return null;
    }
    // Retorna todas as linhas como array associativo
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>
</body>
</html>
