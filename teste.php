<html>
<body>
<h2>Pagina de teste</h2>
<?php
session_start();
include('ferramentas.php');
include('bd.h');
include('bd_final.php');


$sql="select * from aula a join componente c on a.id_componente = c.id_componente ";
$aulas = runQuery($conn,$sql);

if ($aulas)
    foreach ($aulas as $a){
        $id = $a["id_aula"];
        $horario = $a["id_horario"];
        $Hfim = $a["id_horario"]+$a["numero_horas"];
        $juncao = $a["id_juncao"];
        $docente = $a["id_docente"];
        $sala = $a["id_sala"];
        if ($docente)
            if ($juncao)
            $sql="select * from aula where id_horario between $horario and $Hfim and id_aula <> $id and id_docente = $docente and id_juncao <> $juncao ";
            else
            $sql="select * from aula where id_horario between $horario and $Hfim and id_aula <> $id and id_docente = $docente ";
        echo $sql."<br>";
        $sobrepostas = runQuery($conn,$sql);
        if ($sobrepostas)
            foreach ($sobrepostas as $s){
                print $a["id_aula"]. " ".$a["id_sala"]. " ".$a["id_horario"]." ".$a["id_docente"]." ".$a["id_turma"]." ".$juncao."<br>";
                print $s["id_aula"]. " ".$s["id_sala"]. " ".$s["id_horario"]." ".$a["id_docente"]." ".$s["id_turma"]." ".$s["id_juncao"]."<br><br>";
            }
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
