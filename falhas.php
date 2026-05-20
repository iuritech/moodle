<html>
<body>
<h2>Pagina de falhas</h2>
<?php

/* esta está dificil */
/* aparecer a aula que esta por baixo da sobreposta */

    /* feito */ 
/* contar numero de erros na lista */
/* trigger pref default */
/* adicionar campo preferencia global na página de editar preferencias */

$n_erros = 0;
function sobrepostos($conn){
    $sql="select a.id_aula,a.id_horario,a.id_juncao,a.id_docente,a.id_sala,c.numero_horas,u.nome as nome_docente,t.nome as nome_turma,d.nome_uc,a.id_turma, h.hora_inicio, h.dia_semana
        from aula a 
        join componente c on a.id_componente = c.id_componente 
        join disciplina d ON c.id_disciplina = d.id_disciplina
        join utilizador u on u.id_utilizador = a.id_docente
        join horario h on h.id_horario = a.id_horario
        join turma t on t.id_turma = a.id_turma
";
    $aulas = runQuery($conn,$sql);
    if ($aulas)
        foreach ($aulas as $a){
            $id_aula = $a["id_aula"];
            /* aulas sobrepostas nos docentes */
            if ($sobrepostas = docente_sobreposta($conn,$id_aula)){
                foreach ($sobrepostas as $s)
                    $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
                    print "A disciplina: ".$a["nome_uc"]." está sobreposta no docente: ".$a["nome_docente"]." turmas: ".$s["nome_turma"]." / ".$a["nome_turma"]." na: ".$a["dia_semana"]." ás: ".$a["hora_inicio"]."<br>";
            }
            /* aulas sobrepostas nas salas */
            if ($sobrepostas = sala_sobreposta($conn,$id_aula)){
                foreach ($sobrepostas as $s)
                    $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
                    print "A disciplina: ".$a["nome_uc"]." está sobreposta na sala: ".$s["sigla_sala"]." turmas: ".$s["nome_turma"]." / ".$a["nome_turma"]." na: ".$a["dia_semana"]." ás: ".$a["hora_inicio"]."<br>";
            }
            /* aulas sobrepostas das turmas */
            if ($sobrepostas = turma_sobreposta($conn,$id_aula)){
                foreach ($sobrepostas as $s)
                    $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
                    print "A disciplina: ".$a["nome_uc"]." está sobreposta na turma: ".$a["nome_turma"]." na: ".$a["dia_semana"]." ás: ".$a["hora_inicio"]."<br>";
            }
        }
}

function pesquia_aula($conn,$id_aula){
    $sql="select a.id_aula,a.id_horario,a.id_juncao,a.id_docente,a.id_sala,c.numero_horas,t.nome as nome_turma,d.nome_uc,a.id_turma 
        from aula a 
        join componente c on a.id_componente = c.id_componente 
        join disciplina d ON c.id_disciplina = d.id_disciplina
        join turma t on t.id_turma = a.id_turma
        where id_aula = $id_aula
";
    $aula = runQuery($conn,$sql);
    return $aula[0];
};

function docente_sobreposta($conn,$id_aula){
    $aula = pesquia_aula($conn,$id_aula);
    $id = $aula["id_aula"];
    $horario = $aula["id_horario"];
    $Hfim = $aula["id_horario"]+$aula["numero_horas"]-1;
    $juncao = $aula["id_juncao"];
    $docente = $aula["id_docente"];
    /* aulas sobrepostas nos docentes */
    if ($docente){
        if ($juncao)
            $sql="select a.id_horario, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao from aula a
            join utilizador u on u.id_utilizador = a.id_docente
            join turma t on t.id_turma = a.id_turma
            where id_horario between $horario and $Hfim and id_aula <> $id and id_docente = $docente and id_juncao <> $juncao
            or id_horario between $horario and $Hfim and id_aula <> $id and id_docente = $docente and id_juncao is null ";
    else
        $sql="select a.id_horario, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao from aula a
        join utilizador u on u.id_utilizador = a.id_docente
        join turma t on t.id_turma = a.id_turma
        where id_horario between $horario and $Hfim and id_aula <> $id and id_docente = $docente ";
        $sobrepostas = runQuery($conn,$sql);
        if ($sobrepostas){
            return $sobrepostas;
        }
    }
    return false;
};

function sala_sobreposta($conn,$id_aula){
    $aula = pesquia_aula($conn,$id_aula);
    $id = $aula["id_aula"];
    $horario = $aula["id_horario"];
    $Hfim = $aula["id_horario"]+$aula["numero_horas"]-1;
    $juncao = $aula["id_juncao"];
    $sala = $aula["id_sala"];
    /* aulas sobrepostas nas salas */
    if ($sala){
        if ($juncao)
            $sql="select a.id_horario, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao, s.sigla_sala from aula a
            join utilizador u on u.id_utilizador = a.id_docente
            join turma t on t.id_turma = a.id_turma
        join sala s on s.id_sala = a.id_sala
            where id_horario between $horario and $Hfim and id_aula <> $id and a.id_sala = $sala and id_juncao <> $juncao
            or id_horario between $horario and $Hfim and id_aula <> $id and a.id_sala = $sala and id_juncao is null ";
    else
        $sql="select a.id_horario, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao, s.sigla_sala from aula a
        join utilizador u on u.id_utilizador = a.id_docente
        join turma t on t.id_turma = a.id_turma
        join sala s on s.id_sala = a.id_sala
        where id_horario between $horario and $Hfim and id_aula <> $id and a.id_sala = $sala ";
        $sobrepostas = runQuery($conn,$sql);
        if ($sobrepostas){
            return $sobrepostas;
        }
    }
    return false;

};

function turma_sobreposta($conn,$id_aula){
    $aula = pesquia_aula($conn,$id_aula);
    $id = $aula["id_aula"];
    $horario = $aula["id_horario"];
    $Hfim = $aula["id_horario"]+$aula["numero_horas"]-1;
    $turma = $aula["id_turma"];
    /* aulas sobrepostas das turmas */
    $sql="select a.id_horario, u.nome as nome_docente, t.nome as nome_turma, a.id_juncao from aula a
        join utilizador u on u.id_utilizador = a.id_docente
        join turma t on t.id_turma = a.id_turma
        where id_horario between $horario and $Hfim and id_aula <> $id and a.id_turma = $turma ";
    $sobrepostas = runQuery($conn,$sql);
    if ($sobrepostas){
        return $sobrepostas;
    }
    return false;
   
};

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
                if ($aulas["aulas"] >7){
                    $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
                    echo ($aulas["aulas"]."H ".$aulas["dia_semana"]." ".$nome."<br>");
                }
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
                if ($aulas["aulas"] >8){
                    $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
                    echo ($aulas["aulas"]."H ".$aulas["dia_semana"]." ".$nome."<br>");
                }
    }
}

function aulas_sem_sala($conn){
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
            $id= $a["id_aula"];
            $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
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
            $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
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
            $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
            echo "A Uc $nome_uc da turma $turma não tem hórario atribuido <br>";
        }
}

function meiodia_ocupado($aulas){
    foreach ($aulas as $a){
        $hora = $a["hora_inicio"];
        $n_horas = $a["numero_horas"];
        if ($hora == "12:30:00")
            return true;
        if ($hora == "11:30:00" and $n_horas > 1)
            return true;
        if ($hora == "10:30:00" and $n_horas > 2)
            return true;
        if ($hora == "09:30:00" and $n_horas > 3)
            return true;
    }
    return false;
}

function uma_h_ocupado($aulas){
    foreach ($aulas as $a){
        $hora = $a["hora_inicio"];
        $n_horas = $a["numero_horas"];
        if ($hora == "13:30:00")
            return true;
        if ($hora == "12:30:00" and $n_horas > 1)
            return true;
        if ($hora == "11:30:00" and $n_horas > 2)
            return true;
        if ($hora == "10:30:00" and $n_horas > 3)
            return true;
    }
    return false;
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
                if (meiodia_ocupado($aulas))
                    if (uma_h_ocupado($aulas)){
                        $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
                        echo "Docente $nome sem almoço na $dia_s <br>";
                    }
        }
}

function turma_sem_almoco($conn){
    $turma= runQuery($conn,"select * from turma");
    $dias_semana= ["SEG","TER","QUA","QUI","SEX"]; 
    foreach($turma as $turma)
        foreach($dias_semana as $dia_s){
            $id=$turma["id_turma"];
            $nome=$turma["nome"];
            $sql ="select * from aula a 
                join horario h on h.id_horario = a.id_horario 
                join componente c on a.id_componente = c.id_componente 
                where hora_inicio between '09:30:00' and '13:30:00' 
                and id_turma= $id 
                and dia_semana = '$dia_s'";
            $aulas = runQuery($conn,$sql);
            if ($aulas)
                if (meiodia_ocupado($aulas))
                    if (uma_h_ocupado($aulas)){
                        $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
                        echo "Turma $nome sem almoço na $dia_s <br>";
                    }
        }
}

function matriz_preferencias($conn){
    $sql = "select id_horario from horario order by hora_inicio,id_horario";
    $horario = runQuery($conn,$sql);
    $contador = 0;
    foreach ($horario as $h){
        $matriz[$h["id_horario"]] = $contador;
        $contador = $contador +1;
    }
    return $matriz;
}

function erro_pref_docente($conn){
    $sql = "select * from utilizador;";
    $docentes = runQuery($conn,$sql);
    $atributo = "id_utilizador";
    $tabela = "utilizador_preferencia";
    foreach ($docentes as $docente){
        $id = $docente["id_utilizador"];
        $sql ="select *
            from aula a
            join utilizador u on u.id_utilizador = a.id_docente
            join componente c on c.id_componente = a.id_componente
            join disciplina d on d.id_disciplina = c.id_disciplina
            where id_docente =$id
            GROUP BY COALESCE(id_juncao, id_aula)
";
        $aulas = runQuery($conn,$sql);
        if ($aulas)
        foreach ($aulas as $a){
            $nome = $a["nome"];
            $disciplina = $a["abreviacao_uc"];
            $erro = erro_preferencia($conn,$id,$tabela,$atributo,$a);
            if ($erro)
                echo "O $nome tem a disciplina $disciplina na preferencia $erro <br>";
        }
    }
}


function erro_pref_sala($conn){
    $sql = "select * from sala;";
    $salas = runQuery($conn,$sql);
    $atributo = "id_sala";
    $tabela = "preferencia_sala";
    foreach ($salas as $sala){
        $id = $sala["id_sala"];
        $sql ="select *
            from aula a
            join sala s on s.id_sala = a.id_sala
            join componente c on c.id_componente = a.id_componente
            join disciplina d on d.id_disciplina = c.id_disciplina
            where a.id_sala =$id
            GROUP BY COALESCE(id_juncao, id_aula)
";
        $aulas = runQuery($conn,$sql);
        if ($aulas)
        foreach ($aulas as $a){
            $nome = $a["nome_sala"];
            $disciplina = $a["abreviacao_uc"];
            $erro = erro_preferencia($conn,$id,$tabela,$atributo,$a);
            if ($erro)
                echo "A sala $nome tem a disciplina $disciplina na preferencia $erro <br>";
        }
    }
}

function erro_pref_turma($conn){
    $sql = "select * from turma;";
    $turmas = runQuery($conn,$sql);
    $atributo = "id_turma";
    $tabela = "preferencias_turma";
    foreach ($turmas as $turma){
        $id = $turma["id_turma"];
        $sql ="select *
            from aula a
            join componente c on c.id_componente = a.id_componente
            join disciplina d on d.id_disciplina = c.id_disciplina
            where id_turma =$id
";
        $aulas = runQuery($conn,$sql);
        if ($aulas)
        foreach ($aulas as $a){
            $nome = $turma["nome"];
            $disciplina = $a["abreviacao_uc"];
            $erro = erro_preferencia($conn,$id,$tabela,$atributo,$a);
            if ($erro)
                echo "A turma $nome tem a disciplina $disciplina na preferencia $erro <br>";
        }
    }
}

function erro_preferencia($conn,$id,$tabela,$atributo, $aula){
    //cor baseada na preferência
    $frase[0] = 'Impossível';
    $frase[1] = 'Mau';
    $frase[2] = 'Bom';
    $matriz = matriz_preferencias($conn);
    $pref = getPref($conn,$id,$tabela,$atributo);
    if ($aula["id_horario"]>0){
        $horario = $aula["id_horario"];
        $hfim = $horario + $aula["numero_horas"] - 1 ;
        for ($i = $horario; $i <= $hfim; $i++)
            $preferencia = $pref[$matriz[$i]];
        if ($preferencia < 1){
            $GLOBALS["n_erros"]=$GLOBALS["n_erros"]+1;
            return $frase[$preferencia];
        }
    }
    return false;
}

function erro_pref_visual($conn, $aula){
    $aulas = runQuery($conn,"select * from aula a
        join componente c on c.id_componente = a.id_componente
        join disciplina d on d.id_disciplina = c.id_disciplina
        where id_aula = $aula");
    $aula = $aulas[0];
    /* verifica se a aula esta na fora de preferencia para o docente */
    if ($aula["id_docente"]){
        $atributo = "id_utilizador";
        $tabela = "utilizador_preferencia";
        $id = $aula["id_docente"];
        if (erro_preferencia($conn,$id,$tabela,$atributo,$aula))
            return true;
    }
    /* verifica se a aula esta na fora de preferencia para a turma */
    if ($aula["id_turma"]){
        $atributo = "id_turma";
        $tabela = "preferencias_turma";
        $id = $aula["id_turma"];
        if (erro_preferencia($conn,$id,$tabela,$atributo,$aula))
            return true;
    }
    /* verifica se a aula esta na fora de preferencia para a sala */
    if ($aula["id_sala"]){
        $atributo = "id_sala";
        $tabela = "preferencia_sala";
        $id = $aula["id_sala"];
        if (erro_preferencia($conn,$id,$tabela,$atributo,$aula))
            return true;
    }
    return false;
}

?>
</body>
</html>
