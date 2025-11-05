<?php
// Página inicial

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('bd.h');
include('ferramentas.php');
include('bd_final.php');

$idUtilizador = $_SESSION['id'];
$idUtilizadorSessaoAtual = $idUtilizador;
$idAreaUtilizador = $_SESSION['area_utilizador'];
$nome = $_SESSION['nome'];
$img_perfil = $_SESSION['img_perfil'];

/*--------------------------------------------------------------------------------------------------*/

$array_funcoes = array();

$statement = mysqli_prepare($conn, "SELECT f.nome FROM funcao f INNER JOIN utilizador u
									ON f.id_funcao = u.id_funcao WHERE u.id_utilizador = $idUtilizador;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);

$nomeFuncao = $linha["nome"];


/*--------------------------------------------------------------------------------------------------*/

?>
<?php gerarHome1() ?>
<main style="padding-top:15px;">
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-body">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-left:15px; margin-top:10px;">Painel do utilizador</a> / </h6>
                <p class="mb-0">
                    <center> <font size = 5> Bem vindo, <b><?php echo $nome ?></b>. </font> </center>
				<img src="<?php echo $img_perfil ?>" align="left" style="width:130px; heigh:130px; border-radius:50%; border:2px solid #212529;">
                </p>
				<p class="mb-0">
				<text style="margin-top:25px;">
                    <b style="margin-left:15px">

					<?php echo $nomeFuncao; ?>
					</b>
				</text>
				</p>
				<p class="mb-0" style="margin-top:10px;" title="UTC">
                    <b style="margin-left:15px;"> <i class="material-icons" style="vertical-align:middle;">menu_book</i> </b><?php
					$statement = mysqli_prepare($conn, "SELECT u.nome_utc FROM utc u INNER JOIN utilizador ul
														ON u.id_utc = ul.id_utc WHERE ul.id_utilizador = $idUtilizador;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
					
					$nome_utc = $linha["nome_utc"];
					?>
					<text style='margin-left:3px'>
					<?php echo $nome_utc; ?>
				</text>
				</p>
                <p class="mb-0" title="Área">
                    <b style="margin-left:15px;"> <i class="material-icons" style="vertical-align:middle;">monitor</i> </b>
				<?php
					$statement = mysqli_prepare($conn, "SELECT a.nome FROM area a INNER JOIN utilizador u ON 
														a.id_area = u.id_area WHERE u.id_utilizador = $idUtilizador;");
					$statement->execute();
					$resultado = $statement->get_result();
					$linha = mysqli_fetch_assoc($resultado);
						$nome_area = $linha["nome"];
				?>
				<text style='margin-left:3px'>
					<?php echo $nome_area; ?>
				</text>
                <br>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-body">
			<h4 style="margin-left:5px; margin-bottom:25px;">Minhas Disciplinas</h4>
				<?php
					
					$arrayComponentes = array();
					
					$statement = mysqli_prepare($conn, "SELECT DISTINCT id_componente FROM aula WHERE id_docente = $idUtilizador");
					$statement->execute();
					$resultado = $statement->get_result();
					while($linha = mysqli_fetch_assoc($resultado)){
						$idComponente = $linha["id_componente"];
						array_push($arrayComponentes,$idComponente);
					}
					
					if(sizeof($arrayComponentes) > 0){
					
					$arrayComponentesFinal = implode(",",$arrayComponentes);
					
					$statement1 = mysqli_prepare($conn, "SELECT DISTINCT id_disciplina from componente WHERE id_componente IN ($arrayComponentesFinal);");
					$statement1->execute();
					$resultado1 = $statement1->get_result();
					while($linha1 = mysqli_fetch_assoc($resultado1)){
						$idDisciplina = $linha1["id_disciplina"];
					
						$statement2 = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_disciplina = $idDisciplina");
						$statement2->execute();
						$resultado2 = $statement2->get_result();
						$linha2 = mysqli_fetch_assoc($resultado2);
						
						$nomeDisciplina = $linha2["nome_uc"];
						$codigoUC = $linha2["codigo_uc"];
						$imgDisciplina = $linha2["imagem"];
						$idCurso = $linha2["id_curso"];
						$idAreaDisciplina = $linha2["id_area"];
						$idResponsavel = $linha2["id_responsavel"];
						
						$statement3 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $idCurso");
						$statement3->execute();
						$resultado3 = $statement3->get_result();
						$linha3 = mysqli_fetch_assoc($resultado3);
						
						$siglaCurso = $linha3["sigla"];
						
						$arrayComponentes = array();
					
						$statement4 = mysqli_prepare($conn, "SELECT c.id_componente FROM componente c INNER JOIN disciplina d ON c.id_disciplina = d.id_disciplina WHERE d.id_disciplina = $idDisciplina;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						while($linha4 = mysqli_fetch_assoc($resultado4)){
							$idComponente = $linha4["id_componente"];
							array_push($arrayComponentes, $idComponente);
						}
						$arrayComponentesFinal = implode(",", $arrayComponentes);
						
						$statement44 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_docente) FROM aula WHERE id_componente IN ($arrayComponentesFinal) AND id_docente != $idResponsavel;");
						$statement44->execute();
						$resultado44 = $statement44->get_result();
						$linha44 = mysqli_fetch_assoc($resultado44);
							$numero_docentes_outros = $linha44["COUNT(DISTINCT id_docente)"];
			
						$arrayComponentesFinal = implode(",", $arrayComponentes);
						
						$statement5 = mysqli_prepare($conn, "SELECT nome, id_utilizador, imagem_perfil FROM utilizador WHERE id_utilizador = $idResponsavel;");
						$statement5->execute();
						$resultado5 = $statement5->get_result();

						?>
						
						<div class="card_UC" id="card_UC"> <a href="visDSUC_.php?id=<?php echo $idDisciplina ?>">
							<img src="<?php if($imgDisciplina != null) {echo $imgDisciplina;} else { echo 'http://localhost/apoio_utc/images/fundo_disciplina_default_final.jpg'; } ?>" alt="" style="width:100%;">
							<div class="container_card_UC">
								<div class="container_card_UC_titulo">
									<h4><b><?php echo $siglaCurso ?> - <?php echo $nomeDisciplina ?></b> (<?php echo $codigoUC ?>)</h4>
								</div>
								<div class="container_card_UC_detalhes">
								<?php while($linha5 = mysqli_fetch_assoc($resultado5)){
									$nomeDocente = $linha5["nome"];
									$idUtilizador = $linha5["id_utilizador"];
									$imgUtilizador = $linha5["imagem_perfil"];
									echo "<img src='$imgUtilizador' style='width:18px; heigh:18px; border-radius: 50%; margin-right: 5px; border:1px solid #212529;'>";
									if($idUtilizadorSessaoAtual == $idResponsavel){
										echo "<b>", $nomeDocente, " </b>(responsável)<br>";
									}
									else{
										echo $nomeDocente, " (responsável)<br>";
									}
										
									if($numero_docentes_outros > 0){
										echo "<img src='http://localhost/apoio_utc/images/perfil_default.jpg' style='width:18px; heigh:18px; border-radius: 50%; margin-right: 5px; border:1px solid #212529;'>...";
									}
									
								} ?></div><div class="container_card_UC_editar"><?php if($idAreaUtilizador == $idAreaDisciplina) {?><a class="btn btn-primary" href="edDSUC.php?i=<?php echo $idDisciplina ?>" style='width:101px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a><?php } ?></div></p>
								<!--<a class='btn btn-primary' style='width:101px; border-radius:25px; float:right; margin-bottom:50px; margin-top:50px;' onclick=''><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a> -->
							</div>
							</a>
						</div>
				
					<?php	}
					}
					else{
						echo "Não tem nenhuma aula!";
					
					}
				?>
            </div>
        </div>
    </div>
</main>
<?php gerarHome2() ?>