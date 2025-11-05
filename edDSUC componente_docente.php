<?php
// Página de elaboração de distribuição de serviço para uma dada disciplina

session_start(); 

if (!isset($_SESSION["sessao"])) {
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
$semestre = (int) $linha["semestre"];
$ano = (int) $linha["ano"];
$idCurso = (int) $linha["id_curso"];


//echo $idDisciplina;
$statement = mysqli_prepare($conn, "SELECT * FROM curso WHERE id_curso = ?");
$statement->bind_param('i', $idCurso);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$nomeCurso = $linha["nome"];

?>
<?php gerarHome1() ?>
<script src="js/edDSUC.js"></script>
<main style="padding-top:5px;">
<div class="card shadow mb-4">
            <div class="card-body">
        <a href="visDSUC.php"><h6 style="margin-left:15px; margin-top:10px;">Unidades Curriculares</a> / <a href=""><?php echo $nomeDisciplina ?></a></h6>
        <br><h3 style="margin-left:15px;"><b> <?php echo $nomeDisciplina ?> </b>- <?php echo $nomeCurso ?> (<?php echo $semestre ?>º Sem/<?php echo $ano ?>º Ano)</h3>
        
<?php
// Obter componentes
$statement = mysqli_prepare($conn, "SELECT * FROM componente INNER JOIN tipo_componente ON componente.id_tipocomponente = tipo_componente.id_tipocomponente WHERE id_disciplina = ? ORDER BY id_componente, tipo_componente.id_tipocomponente");
$statement->bind_param('i', $idDisciplina);
$statement->execute();
$resultado1 = $statement->get_result();
?>
        <!-- DataTable -->
                <div class="table-responsive">
                    <table border="1" cellpadding="6" id="tabelaDSUC" width="100%" style="text-align:center">
                        <thead>
                            <tr>
                                <th style="text-align:center;"><a href="#" onclick=adicionarDocente() ><i class="material-icons" style="line-height:50%; margin-right:5px; vertical-align: middle; position:absolute; left:30px; top: 136px;">person_add_alt</i></a><select id="dropdown_docentes" style="width: 90px; visibility:hidden; position:absolute; left: 60px; top:130px;"></select>Docente</th>
<?php
while($linha1 = mysqli_fetch_assoc($resultado1)){
    $idComponente = $linha1["id_componente"];
    $nomeTipo = $linha1["nome_tipocomponente"];
    $numeroHoras = $linha1["numero_horas"];
?>
                                <th style="text-align:center;"><?php echo $nomeTipo ?> (<?php echo $numeroHoras ?>H)</th>
<?php
}
?>
                            </tr>
                        </thead>
                        <tbody>
<?php
$statement = mysqli_prepare($conn, "SELECT * FROM utilizador d INNER JOIN componente_docente cd ON d.id_utilizador = cd.id_docente WHERE cd.id_componente = $idComponente");
$statement->execute();
$resultado2 = $statement->get_result();
while($linha2 = mysqli_fetch_assoc($resultado2)){
	$img = $linha2["imagem_perfil"];
	$nomeDocente = $linha2["nome"];
    $idDocente = (int) $linha2["id_docente"];
?>
                            <tr>
                                <td><img src="<?php echo $img ?>" style="width:35px; heigh:35px; border-radius: 50%;"> <b><?php echo $nomeDocente ?></b></td>
<?php

    mysqli_data_seek($resultado1, 0);
    while($linha1 = mysqli_fetch_assoc($resultado1)){
        $idComponente = $linha1["id_componente"];

        $statement = mysqli_prepare($conn, "SELECT t.nome, t.id_turma FROM turma t INNER JOIN componente_turma ct ON t.id_turma = ct.id_turma 
												INNER JOIN componente_docente cd ON ct.id_componente = cd.id_componente
													WHERE cd.id_componente = $idComponente AND cd.id_docente = $idDocente;");
        $statement->execute();
        $resultado3 = $statement->get_result();
        
		?>
                                <td><a href="#"><i class="material-icons" style="vertical-align: middle; position:relative; float:left; top:50%; bottom:50%;">add_circle_outline</i></a><?php
		
        if(mysqli_num_rows($resultado3)==0){
?>
            </td>
            
<?php
        } else {
            while($linha3 = mysqli_fetch_assoc($resultado3)){
                $nomeTurma = $linha3["nome"];
				$idTurma = $linha3["id_turma"];
?>
                                <?php echo "<text>", $nomeTurma, "</text><a onclick=removerTurma($idTurma)><i class='material-icons' style='font-size: 20px; cursor: pointer; color: #ff2424; width: 10px; heigh: 10px; margin-left: 3px; line-height:50%; margin-right:5px; vertical-align: middle;'>remove_circle_outline</i></a>", "<br>";

            }
			?></td><?php
        }
    }
}
?>								
                            </tr>
                        </tbody>
                    </table>

            <!--        <script>
                        // dataTables jQuery plugin
                        $(document).ready(function () {
                            $('#tabelaDSUC').DataTable({
                                responsive: true,
																 bPaginate: false,
    bLengthChange: false,
    bFilter: true,
    bInfo: false,
    bAutoWidth: false,
                                pageLength: 10,
                                order: [0, 'asc'],
								"language": {
									"lengthMenu": "Ver _MENU_ turmas",
									"zeroRecords": "Nenhuma turma encontrada",
									"info": "Página _PAGE_ de _PAGES_",
									"infoEmpty": "Nenhuma turma encontrada",
									"infoFiltered": "(pesquisa de _MAX_ registos totais)",
									"search":         "Procurar:",
									"paginate": {
										"first":      "Primeiro",
										"last":       "Último",
										"next":       "Próximo",
										"previous":   "Anterior"
									}
								}
                            });
                        });
                    </script>  --> 
                </div>
            </div>
        </div>
        
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="criarDSUCModal" tabindex="-1" role="dialog" aria-labelledby="tituloCriarDSUCModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCriarDSUCModal">Atribuir bloco</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
                    <span aria-hidden="true">&times;</span>
            </div>
            <script>
                function verificarFormAtribuirComponenteBloco() {

                    document.getElementById("formCriarDSUC").submit();
                }
            </script>
            <div id="modalBodyCriarDSUC" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editarDSUCModal" tabindex="-1" role="dialog" aria-labelledby="tituloEditarDSUCModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEditarDSUCModal">Editar docente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <script>
                function verificarFormEditar() {

                    document.getElementById("formEditarDSUC").submit();
                }
            </script>
            <div id="modalBodyEditarDSUC" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="adicionarComponenteModal" tabindex="-1" role="dialog" aria-labelledby="tituloAdicionarComponenteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloAdicionarComponenteModal">Adicionar outras turmas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyAdicionarComponente">
            </div>
        </div>
    </div>
</div>

<?php gerarHome2() ?>
