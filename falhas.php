<html>
<body>
<h2>Pagina de Sobrepostos</h2>
<?php

/* session_start(); */
/* include('ferramentas.php'); */
/* include('bd.h'); */
/* include('bd_final.php'); */
/* // função para correr qualquer instrução sql evita repetição de código */
/* function runQuery($conn, $sql) { */
/*     $stmt = $conn->prepare($sql); */
/*     $stmt->execute(); */
/*     $result = $stmt->get_result(); */
/*     if ($result->num_rows === 0) { */
/*         return null; */
/*     } */
/*     // Retorna todas as linhas como array associativo */
/*     return $result->fetch_all(MYSQLI_ASSOC); */
/* } */

function sobrepostos($conn){
    $sql="select a.id_aula,a.id_horario,a.id_juncao,a.id_docente,a.id_sala,c.numero_horas,s.sigla_sala,u.nome as nome_docente,t.nome as nome_turma,d.nome_uc,a.id_turma 
        from aula a 
        join componente c on a.id_componente = c.id_componente 
        join disciplina d ON c.id_disciplina = d.id_disciplina
        join utilizador u on u.id_utilizador = a.id_docente
        join sala s on s.id_sala = a.id_sala
        join turma t on t.id_turma = a.id_turma
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
                foreach ($sobrepostas as $s){
                    print "A disciplina: ".$a["nome_uc"]." está sobreposta no docente: ".$a["nome_docente"]." turmas: ".$s["nome_turma"]." / ".$a["nome_turma"]."<br>";
                }

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
                    foreach ($sobrepostas as $s){
                        print "A disciplina: ".$a["nome_uc"]." está sobreposta na sala: ".$a["sigla_sala"]." turmas: ".$s["nome_turma"]." / ".$a["nome_turma"]."<br>";
                    }

                /* aulas sobrepostas das turmas */
                $sql="select a.id_horario, s.sigla_sala, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao from aula a
                    join utilizador u on u.id_utilizador = a.id_docente
                    join sala s on s.id_sala = a.id_sala
                    join turma t on t.id_turma = a.id_turma
                    where id_horario between $horario and $Hfim and id_aula <> $id and a.id_turma = $turma ";
                    $sobrepostas = runQuery($conn,$sql);
                    if ($sobrepostas)
                        foreach ($sobrepostas as $s){
                            print "A disciplina: ".$a["nome_uc"]." está sobreposta na turma: ".$a["nome_turma"]."<br>";
                        }
        }
}

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
    return false;
   
}




function docente_max_horas($conn){
$sql = "select * from utilizador";
$docentes = runQuery($conn,$sql);
foreach ($docentes as $docente){
    $nome = $docente["nome"];
    $id = $docente["id_utilizador"];
    $sql = "select sum(numero_horas) as aulas, dia_semana, id_docente 
        from (select id_aula, a.id_docente, dia_semana, numero_horas from aula a
        join horario h on h.id_horario = a.id_horario
        join componente c on a.id_componente = c.id_componente 
        where a.id_docente = $id
        GROUP BY COALESCE(id_juncao, id_aula)
        ) as t
        group by dia_semana";
    $aulas_dia = runQuery($conn,$sql);
    if ($aulas_dia)
        foreach ($aulas_dia as $aulas)
            if ($aulas["aulas"] >7)
                echo ($aulas["aulas"]."H ".$aulas["dia_semana"]." ".$nome."<br>");
}
}

function turma_max_horas($conn){
$sql = "select * from turma";
$turmas = runQuery($conn,$sql);
foreach ($turmas as $turma){
    $nome = $turma["nome"];
    $id = $turma["id_turma"];
    $sql = "select sum(numero_horas) as aulas, dia_semana, id_turma 
        from (select id_aula, a.id_turma, dia_semana, numero_horas from aula a
        join horario h on h.id_horario = a.id_horario
        join componente c on a.id_componente = c.id_componente 
        where a.id_turma = $id
        ) as t
        group by dia_semana";
    $aulas_dia = runQuery($conn,$sql);
    if ($aulas_dia)
        foreach ($aulas_dia as $aulas)
            if ($aulas["aulas"] >8)
                echo ($aulas["aulas"]."H ".$aulas["dia_semana"]." ".$nome."<br>");
}
}

function docente_sem_almoco($conn){
    $docentes= runQuery($conn,"select * from utilizador");
    $dias_semana= ["SEG","TER","QUA","QUI","SEX"]; 
    foreach($docentes as $docente)
        foreach($dias_semana as $dia_s){
            $id=$docente["id_utilizador"];
            $nome=$docente["nome"];
            $sql ="select * from aula a 
                join horario h on h.id_horario = a.id_horario 
                join componente c on a.id_componente = c.id_componente 
                where hora_inicio between '09:30:00' and '13:30:00' 
                and id_docente= $id 
                and dia_semana = '$dia_s'";
            $aulas = runQuery($conn,$sql);
            if ($aulas)
                echo $aulas[0]["hora_inicio"]." ".$aulas[0]["numero_horas"]."<br>";
        }
}

function aula_sem_sala($conn){
    $sql="select a.id_aula,a.id_horario,a.id_juncao,a.id_docente,a.id_sala,t.nome as nome_turma,d.nome_uc,a.id_turma 
        from aula a 
        join componente c on a.id_componente = c.id_componente 
        join disciplina d ON c.id_disciplina = d.id_disciplina
        join turma t on t.id_turma = a.id_turma
        where a.id_sala is null
";
    $aulas= runQuery($conn,$sql);
    if($aulas)
        foreach ($aulas as $a){
            $turma= $a["nome_turma"];
            $nome_uc= $a["nome_uc"];
            echo "A Uc $nome_uc da turma $turma não tem sala marcada <br>";
        }
}

function aula_sem_docente($conn){
    $sql="select a.id_aula,a.id_horario,a.id_juncao,a.id_docente,a.id_sala,t.nome as nome_turma,d.nome_uc,a.id_turma 
        from aula a 
        join componente c on a.id_componente = c.id_componente 
        join disciplina d ON c.id_disciplina = d.id_disciplina
        join turma t on t.id_turma = a.id_turma
        where a.id_docente is null
";
    $aulas= runQuery($conn,$sql);
    if($aulas)
        foreach ($aulas as $a){
            $turma= $a["nome_turma"];
            $nome_uc= $a["nome_uc"];
            $id= $a["id_aula"];
            echo "A Uc $nome_uc da turma $turma não tem Docente atribuido <br>";
        }
}


function aula_sem_horario($conn){
    $sql="select a.id_aula,a.id_horario,a.id_juncao,a.id_docente,a.id_sala,t.nome as nome_turma,d.nome_uc,a.id_turma 
        from aula a 
        join componente c on a.id_componente = c.id_componente 
        join disciplina d ON c.id_disciplina = d.id_disciplina
        join turma t on t.id_turma = a.id_turma
        where a.id_horario = 0
";
    $aulas= runQuery($conn,$sql);
    if($aulas)
        foreach ($aulas as $a){
            $turma= $a["nome_turma"];
            $nome_uc= $a["nome_uc"];
            $id= $a["id_aula"];
            echo "A Uc $nome_uc da turma $turma não tem hórario atribuido <br>";
        }
}


?>
</body>
</html>
