<?php
// Página de visualização de disciplinas para um dado curso

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
if(!(isset($_SESSION['permAdmin']) || isset($_SESSION['permUTC']))){
    header("Location: index.php");
}

include('ferramentas.php');
include('bd.h');
include('bd_final.php');

// Obter nome do curso
$id = (int) filter_input(INPUT_GET, 'i');
$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$nomeCurso = $linha["nome"];

// Obter disciplinas do curso
$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_curso = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado1 = $statement->get_result();

?>
<?php gerarHome1() ?>
<script src="js/visDisciplina.js"></script>
<main>
    <div class="container-fluid">
        <a class="btn btn-primary" href="visCurso.php">Retroceder</a>
        <h2 class="mt-4">Gestão de unidades curriculares:<br></h2><h3><b><?php echo $nomeCurso ?></b></h3>
        
                <!-- DataTable -->
                <div class="card shadow mb-4">
                    <div class="card-header"><i class="fas fa-table mr-1"></i><a class="btn btn-light" data-toggle="modal" data-target="#criarDisciplinaModal" onclick="gerarFormCriarDisciplina(<?php echo $id ?>)">Adicionar unidade curricular</a></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabelaDisciplinas" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Código Unidade Curricular</th>
                                        <th>Sigla</th>
                                        <th>Ano</th>
                                        <th>Semestre</th>
                                        <th>Responsável</th>
                                        <th>Área</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Código Unidade Curricular</th>
                                        <th>Sigla</th>
                                        <th>Ano</th>
                                        <th>Semestre</th>
                                        <th>Responsável</th>
                                        <th>Área</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
<?php
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $id = (int) $linha1["id_disciplina"];
    $codigoUC = (int) $linha1["codigo_uc"];
    $nome = $linha1["nome_uc"];
    $sigla = $linha1["abreviacao_uc"];
    $ano = $linha1["ano"];
    $semestre = $linha1["semestre"];
    $idResponsavel = $linha1["id_responsavel"];
    $nomeResponsavel = "";
    $idArea = $linha1["id_area"];
?>

                                    <tr>
                                        <td><?php echo $nome ?></td>
                                        <td><?php echo $codigoUC ?></td>
                                        <td><?php echo $sigla ?></td>
                                        <td><?php echo $ano ?></th>
                                        <td><?php echo $semestre ?></td>
<?php

    // Caso haja um responsável pela disciplina
    if(!empty($idResponsavel)){
        $query = "SELECT * FROM utilizador WHERE id_utilizador = $idResponsavel";
    $resultado2 = mysqli_query($conn, $query);
    $linha2 = mysqli_fetch_assoc($resultado2);
    $nomeResponsavel = $linha2["nome"];
    }
    
?>
                                        <td><?php echo $nomeResponsavel ?></td>
<?php
    $nomeArea = "";
    if(!empty($idArea)){
        $query = "SELECT * FROM area WHERE id_area = $idArea";
        $resultado3 = mysqli_query($conn, $query);
        $linha3 = mysqli_fetch_assoc($resultado3);
        if(mysqli_num_rows($resultado3)>0){
            $nomeArea = $linha3["nome"];
        }
    }
?>                                        
                                        <td><?php echo $nomeArea ?></td>
                                        <td width=120><a class="btn btn-primary" href="visComp.php?i=<?php echo $id ?>">Componentes</a></td>
                                        <td width=65><a class="btn btn-primary" data-toggle="modal" data-target="#editarDisciplinaModal" onclick="gerarFormEditarDisciplina(<?php echo $id ?>)">Editar</a></td>
                                        <td width=85><a class="btn btn-danger" href="remDisciplina.php?i=<?php echo $id ?>">Remover</a></td>
                                    </tr>
<?php
}
?>
                                </tbody>
                            </table>
                            <script>
                                // dataTables jQuery plugin
                                $(document).ready(function () {
                                    $('#tabelaDisciplinas').DataTable({
                                        responsive: true,
                                        pageLength: 25,
                                        order: [0, 'asc'],
										"language": {
											"lengthMenu": "Ver _MENU_ disciplinas",
											"zeroRecords": "Nenhuma disciplina encontrada",
											"info": "Página _PAGE_ de _PAGES_",
											"infoEmpty": "Nenhuma disciplina encontrada",
											"infoFiltered": "(pesquisa de _MAX_ registos totais)",
											"search":         "Procurar:",
											"paginate": {
												"first":      "Primeiro",
												"last":       "Último",
												"next":       "Próximo",
												"previous":   "Anterior"
											},
										}
                                    });
                                });
                            </script>    
                        </div>
                    </div>
                </div>
                
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="criarDisciplinaModal" tabindex="-1" role="dialog" aria-labelledby="tituloCriarDisciplinaModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 30%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCriarDisciplinaModal">Adicionar unidade curricular</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyCriarDisciplina" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editarDisciplinaModal" tabindex="-1" role="dialog" aria-labelledby="tituloEditarDisciplinaModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 30%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEditarDisciplinaModal">Editar unidade curricular</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyEditarDisciplina" class="modal-body">
            </div>
        </div>
    </div>
</div>
<?php gerarHome2() ?>
