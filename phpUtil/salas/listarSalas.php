<?php
include('../../bd.h');
include('../../bd_final.php');

$query = "SELECT * FROM sala";
$result = $conn->query($query);

echo "<table class='table'>";
echo "<thead><tr><th>ID</th><th>Nome</th><th>Bloco</th><th>Ações</th></tr></thead>";
echo "<tbody>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id_sala']}</td>";
    echo "<td>{$row['nome_sala']}</td>";
    echo "<td>{$row['bloco_sala']}</td>";
    echo "<td>";
    echo "<button class='btn btn-info' onclick='editarSala({$row['id_sala']})'>Editar</button> ";
    echo "<button class='btn btn-danger' onclick='removerSala({$row['id_sala']})'>Excluir</button>";
    echo "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
?>
