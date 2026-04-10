<html>
<body>
<h2>Pagina de teste</h2>
<?php
session_start();
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

function sobreposto($conn,$id_aula){
     $sql="select a.id_aula,a.id_horario,a.id_juncao,a.id_docente,a.id_sala,c.numero_horas,s.sigla_sala,u.nome as nome_docente,t.nome as nome_turma,d.nome_uc,a.id_turma 
        from aula a 
        join componente c on a.id_componente = c.id_componente 
        join disciplina d ON c.id_disciplina = d.id_disciplina
        join utilizador u on u.id_utilizador = a.id_docente
        join sala s on s.id_sala = a.id_sala
        join turma t on t.id_turma = a.id_turma
        where id_aula = $id_aula
";

    $aulas = runQuery($conn,$sql);

    if ($aulas)
        foreach ($aulas as $a){
            $id = $a["id_aula"];
            $horario = $a["id_horario"];
            $Hfim = $a["id_horario"]+$a["numero_horas"]-1;
            $juncao = $a["id_juncao"];
            $docente = $a["id_docente"];
            $sala = $a["id_sala"];
            $turma = $a["id_turma"];

            /* aulas sobrepostas nos docentes */
            if ($docente)
                if ($juncao)
                    $sql="select a.id_horario, s.sigla_sala, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao from aula a
                    join utilizador u on u.id_utilizador = a.id_docente
                    join sala s on s.id_sala = a.id_sala
                    join turma t on t.id_turma = a.id_turma
                    where id_horario between $horario and $Hfim and id_aula <> $id and id_docente = $docente and id_juncao <> $juncao
                    or id_horario between $horario and $Hfim and id_aula <> $id and id_docente = $docente and id_juncao is null ";
                else
                    $sql="select a.id_horario, s.sigla_sala, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao from aula a
                    join utilizador u on u.id_utilizador = a.id_docente
                    join sala s on s.id_sala = a.id_sala
                    join turma t on t.id_turma = a.id_turma
                    where id_horario between $horario and $Hfim and id_aula <> $id and id_docente = $docente ";
                        $sobrepostas = runQuery($conn,$sql);
                        if ($sobrepostas)
                    return true;

            /* aulas sobrepostas nas salas */
            if ($sala)
                if ($juncao)
                    $sql="select a.id_horario, s.sigla_sala, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao from aula a
                    join utilizador u on u.id_utilizador = a.id_docente
                    join sala s on s.id_sala = a.id_sala
                    join turma t on t.id_turma = a.id_turma
                    where id_horario between $horario and $Hfim and id_aula <> $id and a.id_sala = $sala and id_juncao <> $juncao
                    or id_horario between $horario and $Hfim and id_aula <> $id and a.id_sala = $sala and id_juncao is null ";
            else
                $sql="select a.id_horario, s.sigla_sala, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao from aula a
                join utilizador u on u.id_utilizador = a.id_docente
                join sala s on s.id_sala = a.id_sala
                join turma t on t.id_turma = a.id_turma
                where id_horario between $horario and $Hfim and id_aula <> $id and a.id_sala = $sala ";
                $sobrepostas = runQuery($conn,$sql);
                if ($sobrepostas)
                    return true;

                /* aulas sobrepostas das turmas */
                $sql="select a.id_horario, s.sigla_sala, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao from aula a
                    join utilizador u on u.id_utilizador = a.id_docente
                    join sala s on s.id_sala = a.id_sala
                    join turma t on t.id_turma = a.id_turma
                    where id_horario between $horario and $Hfim and id_aula <> $id and a.id_turma = $turma ";
                    $sobrepostas = runQuery($conn,$sql);
                    if ($sobrepostas)
                        return true;
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
