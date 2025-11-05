<?php
// Página de visualização e gestão de componentes para uma dada disciplina

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

$id = (int) filter_input(INPUT_GET, 'i');

$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = ?");
$statement->bind_param('i', $id);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$idDisciplina = (int) $linha["id_disciplina"];
$nomeDisciplina = $linha["nome_uc"];
$idCurso = (int) $linha["id_curso"];

$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $idCurso);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$idCurso = $linha["id_curso"];
$nomeCurso = $linha["nome"];

?>
<?php gerarHome1() ?>
<main>
    <div class="container-fluid">
        <a class="btn btn-primary" href="visDisciplina.php?i=<?php echo $idCurso ?>">Retroceder</a>
        <h2 class="mt-4">Componentes: <br></h2><h3> <b> <?php echo $nomeDisciplina ?> </b> - <b> <?php echo $nomeCurso ?> </b> </h3>
        
        <!-- DataTable -->
        <div class="card shadow mb-4">
            <div class="card-header"><i class="fas fa-table mr-1"></i><button type="button" class="btn btn-light" data-toggle="modal" data-target="#criarComponenteModal">Adicionar componente</button></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabelaComponentes" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Nº horas (semanal)</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
<?php
// Obter componentes
$statement = mysqli_prepare($conn, "SELECT * FROM componente INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente WHERE id_disciplina = ?");
$statement->bind_param('i', $idDisciplina);
$statement->execute();
$resultado1 = $statement->get_result();
while($linha1 = mysqli_fetch_assoc($resultado1)){
    
    $idComponente = $linha1["id_componente"];
    $nomeTipo = $linha1["nome_tipocomponente"];
    $numeroHoras = $linha1["numero_horas"];
?>
                            <tr>
                                <td><?php echo $idComponente ?></td>
                                <td><?php echo $nomeTipo ?></td>
                                <td><?php echo $numeroHoras; echo 'H'?></td>
                                <td width=65><a class="btn btn-primary" data-toggle="modal" data-target="#editarComponenteModal" onclick="gerarFormEditarComponente(<?php echo $idComponente ?>)">Editar</a></td>
                                <script>
                                    function gerarFormEditarComponente(id) {
                                      var xhttp;    
                                      if (id == "") {
                                        document.getElementById("modalBodyEditarComponente").innerHTML = "";
                                        return;
                                      }
                                      xhttp = new XMLHttpRequest();
                                      xhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                          document.getElementById("modalBodyEditarComponente").innerHTML = this.responseText;
                                        }
                                      };
                                      xhttp.open("GET", "phpUtil/formEditarComponente.php?i="+id, true);
                                      xhttp.send();
                                    }
                                </script>
                                <td width=85><a class="btn btn-danger" href="remComponente.php?i=<?php echo $idComponente ?>">Remover</a></td>
                            </tr>
<?php
}
?>
                                
                        </tbody>
                    </table>
                    <script>
                        // dataTables jQuery plugin
                        $(document).ready(function () {
                            $('#tabelaComponentes').DataTable({
                                responsive: true,
                                pageLength: 10,
                                order: [0, 'asc'],
								"language": {
									"lengthMenu": "Ver _MENU_ componentes",
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
<div class="modal fade" id="criarComponenteModal" tabindex="-1" role="dialog" aria-labelledby="tituloCriarComponenteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCriarComponenteModal">Criar componente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <script>
                    function verificarForm() {
                        
                        document.getElementById("formCriarComponente").submit();
                    }
                </script>
                <form id="formCriarComponente" class="user" action="processamento/processarFormCriarComponente.php" method="post">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="disciplina">Unidade curricular:</label>
                            <select name="disciplina" id="disciplina">
                                    <option value="<?php echo $idDisciplina ?>"><?php echo $nomeDisciplina ?></option>                                           
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="tipo">Tipo:</label>
                            <select name="tipo" id="tipo">
<?php
// Buscar tipos de componentes
$statement = mysqli_prepare($conn, "SELECT * FROM tipo_componente");
$statement->execute();
$resultado = $statement->get_result();

while ($linha = mysqli_fetch_assoc($resultado)) {
    $idTipo = (int) $linha["id_tipocomponente"];
    $tipo = $linha["nome_tipocomponente"];
?>
                                <option value="<?php echo $idTipo ?>"><?php echo $tipo ?></option>
<?php
}
?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="horas">Número de horas:</label>
                            <select name="horas" id="horas">
                                <option value="1">1</option>
                                <option selected value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>
                    
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" onclick="javascript:verificarForm()" class="btn btn-primary btn-lg">
                    Criar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editarComponenteModal" tabindex="-1" role="dialog" aria-labelledby="tituloEditarComponenteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEditarComponenteModal">Editar componente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <script>
                function verificarFormEditar() {

                    document.getElementById("formEditarComponente").submit();
                }
            </script>
            <div id="modalBodyEditarComponente" class="modal-body">
            </div>
        </div>
    </div>
</div>

<?php gerarHome2() ?>
