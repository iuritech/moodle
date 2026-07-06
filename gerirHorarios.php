<?php
session_start();
include('ferramentas.php');
include('bd.h');
include('bd_final.php');
include('falhas.php');

/* validar sobrepostos e preferencias impossiveis */
/* adicionar array nas aulas com os id_horario de movimentos validos */

estaLogado();

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

//pesquisar salas definidas para uma determinada componente
function salas_componente($conn,$idAula){
         $sql="
        select s.sigla_sala
        from aula a
        join sala_componente_disponivel sc on sc.id_componente = a.id_componente
        join sala s on sc.id_sala = s.id_sala
        where a.id_aula = $idAula";
        $salas=runQuery($conn,$sql);
        // se uma componente nao tiver salas atribuidas mostra as salas todas
        if(!$salas){
    return "['sem salas atribuidas']";
    }
                    $salas_str="[";
        foreach ($salas as $s){
            $salas_str=$salas_str."'".$s['sigla_sala']."', ";
        }
        return $salas_str=$salas_str."]";
        
    }

    #procura a sigla_sala para a aula destinada
    function get_sigla($conn,$id_sala){
        $sql="select sigla_sala from sala where id_sala='$id_sala'";
        $sigla_sala=runQuery($conn,$sql)[0]['sigla_sala'];
        return htmlspecialchars($sigla_sala);
    }
    function get_docente($conn, $id_docente){
        $sql="select nome from utilizador where id_utilizador = $id_docente ";
        $nome_docente=runQuery($conn,$sql)[0]['nome'];
        return htmlspecialchars($nome_docente);
    }


// procurar preferencias
// preferencia_sala id_sala
// preferencias_turma id_turma
// utilizador_preferencia id_utilizador
function getPref($conn, $id, $tabela, $atributo) {
    $preferencia = "";
   $query = "SELECT p.preferencia
        FROM $tabela e
        JOIN preferencias p ON e.id_preferencias = p.id_preferencias
        WHERE e.$atributo = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($preferencia);
        if ($stmt->fetch()) {
            $stmt->close();
            return explode(',', $preferencia);
        }
        $stmt->close();
    }
    // Se não houver preferências, devolve preferencia default
     /* preferencias default */
    if ($atributo == "id_utilizador")
        $sql="select * from preferencias where id_preferencias = 1;";
    if ($atributo == "id_sala")
        $sql="select * from preferencias where id_preferencias = 2;";
    if ($atributo == "id_turma")
        $sql="select * from preferencias where id_preferencias = 3;";
    return explode(',',runQuery($conn, $sql)[0]["preferencia"]); 
}

# verifica os docentes/turmas/salas que estao selecionadas
function selecionado($id, $id_gets){
    foreach ($id_gets as $ids)
        if ($id == $ids)
            return true;
    return false; 
}

/* Lista lateral de disciplinas */
function imprime_lista_lateral($conn,$id,$atributo){
 $sql = "SELECT a.id_aula, c.id_componente, d.nome_uc, tc.nome_tipocomponente, a.id_juncao
        FROM aula a
        JOIN componente c ON a.id_componente = c.id_componente
        JOIN disciplina d ON c.id_disciplina = d.id_disciplina
        JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
        WHERE a.$atributo = $id and a.id_horario =0
        GROUP BY COALESCE(id_juncao, id_aula);
";?>
        <details>
          <summary>UCs</summary>
        <div style="width:200px; min-height: 50px; background-color: lightgray;" id="disciplinas-lista">
            <?php
            if ($componentes_lista = runQuery($conn,$sql))
                foreach ($componentes_lista as $c){ ?>
                    <div class="disciplina-draggable" data-id_aula="<?= $c['id_aula'] ?>" data-id_juncao="<?= $c['id_juncao'] ?>">
                        <style="background:#e6e6e6; border:1px solid #ccc; margin-bottom:8px; padding:8px; cursor:move;">
                            <b><?= htmlspecialchars($c['nome_uc']) ?></b>
                            (<?= htmlspecialchars($c['nome_tipocomponente']) ?>)
                    </div>
        <?php }  ?>
        </div>
        </details>
<?php }

//atribuir sala a uma determinada aula
if (isset($_POST['id_aula']) &&  isset($_POST['id_sala'])){
    $aula = $_POST['id_aula'];
    $sala = $_POST['id_sala'];
    $sql="select id_sala from sala where sigla_sala='$sala'";
    $id_sala=runQuery($conn,$sql)[0]['id_sala'];
    $sql="select id_juncao from aula where id_aula='$aula'";
    $juncao=runQuery($conn,$sql)[0]['id_juncao'];
    if ($juncao){
        $sql = "UPDATE aula SET id_sala = ? WHERE id_juncao=?;" ;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_sala, $juncao);
    }else {
        $sql = "UPDATE aula SET id_sala = ? WHERE id_aula=?;" ;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_sala, $aula);
    }
    if ($stmt->execute()) {
        $url = basename($_SERVER['PHP_SELF']) . '?' . http_build_query($_GET);
        header("Location: ".$url);
        
    } else {
        echo "Erro" . $conn->error;
    }
}

// Obter docentes disponiveis
$sql = "SELECT * FROM utilizador WHERE id_funcao IN (4,5,6) ORDER BY nome";
$docentes = runQuery($conn,$sql);
// Obter turmas disponiveis
$sql = "select * from turma";
$turmas = runQuery($conn,$sql);

// Obter salas disponiveis
$sql = "select * from sala";
$salas = runQuery($conn,$sql);

// Obter docentes selecionados (pode ser array)
$id_docentes = isset($_GET['id_docente']) ? (is_array($_GET['id_docente']) ? $_GET['id_docente'] : [$_GET['id_docente']]) : [];
$id_docentes = array_filter($id_docentes, 'is_numeric'); // Sao ids validos

// Obter turmas selecionadas (pode ser array)
$id_turmas = isset($_GET['id_turma']) ? (is_array($_GET['id_turma']) ? $_GET['id_turma'] : [$_GET['id_turma']]) : [];
$id_turmas = array_filter($id_turmas, 'is_numeric'); // Sao ids validos

// Obter salas selecionadas (pode ser array)
$id_salas = isset($_GET['id_sala']) ? (is_array($_GET['id_sala']) ? $_GET['id_sala'] : [$_GET['id_sala']]) : [];
$id_salas = array_filter($id_salas, 'is_numeric'); // Sao ids validos

// Obter semestre (opcional)
$semestre = isset($_GET['semestre']) ? intval($_GET['semestre']) : 1;

// Obter aulas 
function get_aulas($conn,$atributo,$id){
    $sql = "SELECT a.id_horario, a.id_componente, d.abreviacao_uc, tc.sigla_tipocomponente, h.hora_inicio, h.hora_fim, h.dia_semana, c.numero_horas, a.id_aula, a.id_juncao, a.id_sala, a.id_docente, t.nome
        FROM aula a
        JOIN componente c ON a.id_componente = c.id_componente
        JOIN disciplina d ON c.id_disciplina = d.id_disciplina
        JOIN tipo_componente tc ON c.id_tipocomponente = tc.id_tipocomponente
        JOIN horario h ON a.id_horario = h.id_horario
        join turma t on a.id_turma=t.id_turma
        WHERE a.$atributo = $id";
    $res = $conn->query($sql);
    $aulas = [];
    while ($row = $res->fetch_assoc()) {
        $aulas[$row['id_horario']][] = $row;
    }
    return $aulas;
}

?>
<!DOCTYPE html>
<?php gerarHome1() ?>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Horarios </title>
    <link rel="stylesheet" href="css/gerirDocente.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="js/gerirHorariosDocente.js"></script>
</head>

<body>

    <div class="card shadow mb-4">
        <div class="card-body">
            <a href="http://localhost/home.php">
                <h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador
            </a> / <a href="">Horarios</a> / <a href="">Gerir Horarios</a></h6>
            <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Gerir Horarios</b></h3>
        </div>

<!-- divisoes de erros de horario -->
<div class="falhas" style="display:flex;">
<details>
<summary>Erros de horario</summary>
<div style"display:flex" class="dropdown-content">
<?php

/* funcoes para detetar falhas nos horarios */
sobrepostos($conn);
docente_max_horas($conn);
turma_max_horas($conn);
aulas_sem_sala($conn);
aula_sem_docente($conn);
aula_sem_horario($conn);
docente_sem_almoco($conn);
turma_sem_almoco($conn);
erro_pref_docente($conn);
erro_pref_sala($conn);
erro_pref_turma($conn);

?>
</div>
</div>
</details>
Erros encontrados: <?=$GLOBALS["n_erros"]?>

        <!--mostrar salas-->
        <div id="caixa_salas" style="display:none;" >
            <form method="post" style="width:300px; " class="salas">
                  <summary>Escolha a sala</summary>
                <button type="button" id="fechar_salas" onclick="$('#caixa_salas').hide()">X</button>
                <div id="conteudo_salas" class="dropdown-content" style="border:1px solid #ccc; padding:10px; margin-top:10px;">
                </div>
                    <input type="submit" value="Submit">
            </form>
        </div>


    <div style="display: flex; flex-wrap: wrap; justify-content: center;">

<?php
if (!empty($id_docentes))
    foreach ($id_docentes as $idx => $id_docente){
        #pesquisa o nome do docente
        $sql="select nome from utilizador where id_utilizador='$id_docente'";
        $nome_docente=runQuery($conn,$sql)[0]['nome'];
        $aulas = get_aulas($conn,'id_docente', $id_docente) ?? [];
        $preferencias = getPref($conn, $id_docente,"utilizador_preferencia","id_utilizador");
?>
    <div class="caixas" style="display:flex;">
                <div class="panel" id="docente_<?=$id_docente?> " data-id_docente="<?= $id_docente ?>">
                    <h3 style="margin-left:15px;">Horário de <?= htmlspecialchars($nome_docente) ?></h3>
        <?= imprime_horario($conn,$aulas,$preferencias); ?>
        <?= imprime_lista_lateral($conn,$id_docente,"id_docente"); ?>
                </div>
    </div>
                <?php } ?>

<?php
if (!empty($id_turmas))
    foreach ($id_turmas as $idx => $id_turma){
        #pesquisa o nome da turma
        $sql="select nome from turma where id_turma='$id_turma'";
        $nome_turma=runQuery($conn,$sql)[0]['nome'];
        $aulas = get_aulas($conn,'id_turma', $id_turma) ?? [];
        $preferencias = getPref($conn, $id_turma,"preferencias_turma","id_turma");
?>
        <div class="caixas" style="display:flex;">
                    <div class="panel" id="turma_<?=$id_turma?>" data-id_turma="<?= $id_turma ?>">
                        <h3 style="margin-left:15px;">Horário de <?= htmlspecialchars($nome_turma) ?></h3>
            <?= imprime_horario($conn,$aulas,$preferencias); ?>
            <?= imprime_lista_lateral($conn,$id_turma,"id_turma"); ?>
                    </div>
        </div>
<?php } ?>
                
<?php
if (!empty($id_salas))
    foreach ($id_salas as $idx => $id_sala){
        #pesquisa o nome da sala
        $sql="select nome_sala from sala where id_sala='$id_sala'";
        $nome_sala=runQuery($conn,$sql)[0]['nome_sala'];
        $aulas = get_aulas($conn,'id_sala', $id_sala) ?? [];
        $preferencias = getPref($conn, $id_sala,"preferencia_sala","id_sala");
?>
        <div class="caixas" style="display:flex;">
                    <div class="panel" id="sala_<?=$id_sala?>" data-id_sala="<?= $id_sala ?>">
                        <h3 style="margin-left:15px;">Horário de <?= htmlspecialchars($nome_sala) ?></h3>
            <?= imprime_horario($conn,$aulas,$preferencias); ?>
                    </div>
        </div>
<?php } ?>

<div class="caixas" style="display:flex;">
    <form method="get" class="docentes">
        <details>
        <summary>Adicionar tabela</summary>
            <div style="display: flex; flex-wrap: wrap;">
                <div style"display:flex" class="dropdown-content">
                    <?php foreach ($docentes as $d){ ?>
                        <input type="checkbox" id="<?= $d['id_utilizador'] ?>" name="id_docente[]" value="<?= $d['id_utilizador'] ?>"
                        <?php if (selecionado($d['id_utilizador'],$id_docentes))echo 'checked="checked"' ?> >
                        <label for="id_docente[]"><?= htmlspecialchars($d['nome']) ?></label><br>
                    <?php  } ?>
                </div>
                <div style"display:flex" class="dropdown-content">
                    <?php foreach ($turmas as $d){ ?>
                        <input type="checkbox" id="<?= $d['id_turma'] ?>" name="id_turma[]" value="<?= $d['id_turma'] ?>"
                        <?php if (selecionado($d['id_turma'],$id_turmas))echo 'checked="checked"' ?> >
                        <label for="id_turma[]"><?= htmlspecialchars($d['nome']) ?></label><br>
                    <?php } ?>
                </div>
                <div style"display:flex" class="dropdown-content">
                    <?php foreach ($salas as $d){ ?>
                        <input type="checkbox" id="<?= $d['id_sala'] ?>" name="id_sala[]" value="<?= $d['id_sala'] ?>"
                        <?php if (selecionado($d['id_sala'],$id_salas))echo 'checked="checked"' ?> >
                        <label for="id_sala[]"><?= htmlspecialchars($d['nome_sala']) ?></label><br>
                    <?php } ?>
                </div>
            </div>
            <input type="radio" id="sem1" name="semestre" value="1" checked="checked">
            <label for="sem1">Semestre 1</label><br>
            <input type="radio" id="sem2" name="semestre" value="2">
            <label for="sem2">Semestre 2</label><br>
            <input type="submit" value="Submit">
        </details>
    </form>
</div>

</div>
</body>

</html>
<?php

function set_turma($aulas){
    $juncao = $aulas[0]['id_juncao'];
    if ($juncao > 0){
        foreach ($aulas as $a){
            $nomes_turmas[] = $a['nome'];
        }
        return implode(",", $nomes_turmas);
    }else
    return $aulas[0]['nome'];
}
function set_nomeuc($aulas){
    $juncao = $aulas[0]['id_juncao'];
    if ($juncao > 0){
        foreach ($aulas as $a){
            $nomes_ucs[] = $a['abreviacao_uc'];
        }
        $nomes = array_values(array_unique($nomes_ucs));
        return implode(",", $nomes);
    }else
    return $aulas[0]['abreviacao_uc'];
}

function imprime_horario($conn,$aulas,$preferencias){


// Inicializar array de horarios mapeados
$horario_map = [];
$horas_map = [
    '08:30-09:30',
    '09:30-10:30',
    '10:30-11:30',
    '11:30-12:30',
    '12:30-13:30',
    '13:30-14:30',
    '14:30-15:30',
    '15:30-16:30',
    '16:30-17:30',
    '17:30-18:30'

];

// Obter todos os horÃ¡rios para cruzamento rapido
$res = $conn->query("SELECT * FROM horario");
while ($row = $res->fetch_assoc()) {
    $horario_map[$row['dia_semana']][$row['hora_inicio'] . '-' . $row['hora_fim']] = $row['id_horario'];
}

//cor baseada na preferência
$cores = [
    0 => '#bdbdbd', // Impossível
    1 => '#ffcccc', // Mau
    2 => '#ffff99', // Bom
    3 => '#b2ffb2'  // Ótimo
];

// Obter horarios (linhas: horas, colunas: dias)
$dias_semana = ['SEG', 'TER', 'QUA', 'QUI', 'SEX'];
$horas_unicas = [];
$res = $conn->query("SELECT DISTINCT hora_inicio, hora_fim FROM horario ORDER BY hora_inicio, hora_fim");
while ($row = $res->fetch_assoc()) {
    $horas_unicas[] = [$row['hora_inicio'], $row['hora_fim']];
}

?>
                    <table>
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <?php foreach ($dias_semana as $dia){ ?>
                                    <th><?= $dia ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
<?php 

    //monitorizar os slots por linha
    $slots_linha = [];
foreach ($dias_semana as $dia) { //controlo preciso de quantos slots cada aula ocupa
    $slots_linha[$dia] = array_fill(0, count($horas_unicas), 0);
}


foreach ($horas_unicas as $horaIndex => $hora){
?>
    <tr>
        <td class="hora-coluna">
            <?= isset($horas_map[$horaIndex]) ? $horas_map[$horaIndex] : $hora[0] . '-' . $hora[1] ?>
        </td>

<?php foreach ($dias_semana as $dia){
if ($slots_linha[$dia][$horaIndex] > 0) {
    $slots_linha[$dia][$horaIndex]--;
    continue;
}

$chave_horario = $hora[0] . '-' . $hora[1];
$id_horario = $horario_map[$dia][$chave_horario] ?? null;

if ($id_horario && isset($aulas[$id_horario])) {
    $aula = $aulas[$id_horario][0];
    $nome_uc = set_nomeuc($aulas[$id_horario]);
    $turma = set_turma($aulas[$id_horario]);
    $nome_tipocomponente = htmlspecialchars($aula['sigla_tipocomponente']);
    $idAula=$aula['id_aula'];
    $id_sala=$aula['id_sala'];
    $id_docente=$aula['id_docente'];
    if ($id_docente)
        $nome_docente = get_docente($conn,$id_docente);
    else 
        $nome_docente = "Sem docente";
    $salas_aula=salas_componente($conn,$idAula);
    #se a aula ja tiver uma sala definida pesquisa e atribui á variavel $sala

    #procura a sigla_sala da aula
    $sigla_sala="";
    if($id_sala)
        $sigla_sala=get_sigla($conn,$id_sala);
    


    // Cálculo da duração
    $blocos = $aula['numero_horas'];
    // Marca os próximos slots como ocupados
    for ($i = 1; $i < $blocos; $i++) {
        if (($horaIndex + $i) < count($horas_unicas)) {
            $slots_linha[$dia][$horaIndex + $i] = $blocos - $i;
        }
    }

    
?>


<td class="ocupado" 
    rowspan="<?= $blocos ?>"
    data-id_aula="<?= $aula['id_aula'] ?>" 
    data-id_horario="<?= $aula['id_horario'] ?>"
    data-id_docente="<?= $id_docente ?>"
    data-id_juncao="<?= $aula['id_juncao'] ?>"
    data-salas="<?= $salas_aula ?>"
    <?php if ($id_sala){ ?>
        data-horas_amarelas="<?= slots_amarelos($conn,$idAula)?>"
    <?php } ?>
    <?php if ($id_docente and slots_vermelhos($conn,$idAula)){ ?>
        data-horas_invalidas="<?= slots_vermelhos($conn,$idAula)?>"
    <?php } ?>
    onclick="atribuir_sala(<?= $idAula.",". $salas_aula ?>)"
    <?php if(docente_sobreposta($conn,$aula['id_aula']) or turma_sobreposta($conn,$aula['id_aula']) or !$id_docente or erro_pref_visual($conn,$idAula)) echo "style='background: hsl(2, 35%, 33%);'"; ?>
    <?php if(sala_sobreposta($conn,$aula['id_aula']) or !$id_sala) echo "style='background: hsl(60, 55%, 53%);'"; ?>
    style="color:#e7e8eb;">
    <b><?= $nome_uc ?></b><br>
    <?= $nome_tipocomponente ?><br>
    <?= $nome_docente ?><br>
    <?= $turma ?><br>
    <?= $sigla_sala ?><br>


</td>

<?php } else { 

//slots disponiveis
$idx = $horaIndex * count($dias_semana) + array_search($dia, $dias_semana);
$pref = $preferencias[$idx] ?? 0;

?>

                            <td class="disponivel" 
                                data-id_horario="<?= $id_horario ?>" 
                                data-pref="<?= $pref ?>"
                                style="background-color:<?= $cores[$pref] ?>;">
                                <?= $id_horario ?>
                            </td>
                    <?php }} ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>


