<?php
// Página de gestão de turmas para um dado curso

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


// Obter turmas
$id = (int) filter_input(INPUT_GET, 'i');
$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
$nomeCurso = $linha["nome"];

$statement = mysqli_prepare($conn, "SELECT * FROM turma WHERE id_curso = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado1 = $statement->get_result();

?>
<?php gerarHome1() ?>
<script src="js/visTurma.js"></script>
<main>
    <div class="container-fluid">
        <a class="btn btn-primary" href="visCurso.php">Retroceder</a>
        <h2 class="mt-4">Gestão de turmas:<br></h2><h3><b><?php echo $nomeCurso ?></b></h3>
        
                <!-- DataTable -->
                <div class="card shadow mb-4">
                    <div class="card-header"><i class="fas fa-table mr-1"></i><a class="btn btn-light" data-toggle="modal" data-target="#criarTurmaModal" onclick="gerarFormCriarTurma(<?php echo $id ?>)">Adicionar turma</a></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabelaTurmas" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Ano</th>
                                        <th>Semestre</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Ano</th>
                                        <th>Semestre</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
<?php
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $id = (int) $linha1["id_turma"];
    $nome = $linha1["nome"];
    $ano = $linha1["ano"];
    $semestre = $linha1["semestre"];
?>

                                    <tr>
                                        <td><?php echo $nome ?></td>
                                        <td><?php echo $ano ?></td>
                                        <td><?php echo $semestre ?></td>
                                        <td width=65><a class="btn btn-primary" data-toggle="modal" data-target="#editarTurmaModal" onclick="gerarFormEditarTurma(<?php echo $id ?>)">Editar</a></td>
                                        <td width=85><a class="btn btn-danger" href="remTurma.php?i=<?php echo $id ?>">Remover</a></td>
                                    </tr>
<?php
}
?>
                                </tbody>
                            </table>
                            <script>
                                // dataTables jQuery plugin
                                $(document).ready(function () {
                                    $('#tabelaTurmas').DataTable({
                                        responsive: true,
                                        pageLength: 25,
                                        order: [0, 'asc'],
										"language": {
											"lengthMenu": "Ver _MENU_ turmas",
											"zeroRecords": "Nenhum registo encontrado",
											"info": "Página _PAGE_ de _PAGES_",
											"infoEmpty": "Nenhum registo encontrado",
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
<div class="modal fade" id="criarTurmaModal" tabindex="-1" role="dialog" aria-labelledby="tituloCriarTurmaModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 30%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCriarTurmaModal">Adicionar turma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyCriarTurma" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editarTurmaModal" tabindex="-1" role="dialog" aria-labelledby="tituloEditarTurmaModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 30%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEditarTurmaModal">Editar turma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyEditarTurma" class="modal-body">
            </div>
        </div>
    </div>
</div>
<?php gerarHome2() ?>
