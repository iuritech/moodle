<?php
// Página de visualização de distribuição de serviço ordenada por disciplina (DSUC)

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$ano_letivo_temp = explode("_",$_SESSION['bd']);
$ano_letivo = $ano_letivo_temp[2] . "_" . $ano_letivo_temp[3];

$idUtilizador = $_SESSION['id'];
$idUtilizadorSessaoAtual = $idUtilizador;
$idAreaUtilizador = (int) $_SESSION['area_utilizador'];
$permAdmin = false;
$permUTC = false;
$permArea = false;

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = $idUtilizador;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
    $nome_area_utilizador = $linha["nome"];
	$img_perfil = $linha["imagem_perfil"];
	$idUTCUtilizador = $linha["id_utc"];
	$idAreaUtilizador = $linha["id_area"];

$statement000 = mysqli_prepare($conn, "SELECT nome FROM area WHERE id_area = $idAreaUtilizador;");
$statement000->execute();
$resultado000 = $statement000->get_result();
$linha000 = mysqli_fetch_assoc($resultado000);
    $nome_area_utilizador = $linha000["nome"];

$statement00 = mysqli_prepare($conn, "SELECT id_utc FROM utilizador WHERE id_utilizador = $idUtilizador;");
$statement00->execute();
$resultado00 = $statement00->get_result();
$linha00 = mysqli_fetch_assoc($resultado00);
    $id_utc_atual = $linha00["id_utc"];

$statement200 = mysqli_prepare($conn, "SELECT nome_utc FROM utc WHERE id_utc = $id_utc_atual;");
$statement200->execute();
$resultado200 = $statement200->get_result();
$linha200 = mysqli_fetch_assoc($resultado200);
    $nome_utc_atual = $linha200["nome_utc"];

if(isset($_SESSION['permAdmin'])){
    $permAdmin = true;
}
if(isset($_SESSION['permUTC'])){
    $permUTC = true;
}
if(isset($_SESSION['permArea'])){
    $permArea = true;
}
		
$statement00 = mysqli_prepare($conn, "SELECT id_responsavel, sigla_utc FROM utc WHERE id_utc = $idUTCUtilizador;");
$statement00->execute();
$resultado00 = $statement00->get_result();
$linha00 = mysqli_fetch_assoc($resultado00);
	$id_responsavel_UTC_utilizador = $linha00["id_responsavel"];
	$sigla_UTC_utilizador = $linha00["sigla_utc"];
		
?>
<?php gerarHome1() ?>
<main style="padding-top:15px;" onload="javascript:mostrarListaUCs()">

<div id="cover-spin"></div>
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">DSD (Unidades Curriculares)</a></h6>
			<?php if($idUtilizadorSessaoAtual == $id_responsavel_UTC_utilizador){ ?>
			<img src="http://localhost/apoio_utc/images/excel_final.png" class="gerarExcel" onclick="gerarFicheiroExcelDSD(<?php echo $id_utc_atual ?>)" title="Gerar Excel: DSD_UTC_<?php echo $sigla_UTC_utilizador ?>_<?php echo $ano_letivo; ?>" style="position:absolute; right:55px; top:45px; width:70px; height:70px; cursor:pointer;">
			<?php }?>
			<h3 style="margin-left:15px; margin-top:20px;"><b>Unidade Curricular:</b></h3>
			<a href="#" data-toggle='modal' data-target='#visDSUC_UCS_por_atribuir' onclick="verUCSPorAtribuir()" style="position:absolute; right:118px; top:129px;"><b>Resumo...</b></a>
			<select class="form-control" style='width:260px; margin-left:15px;'id='uc_dropdown' name='uc_dropdown' onchange="filtrarTabela()">
				<option value="nada_selecionado"></option></select> <input type=checkbox id="checkboxUCs" checked="true" onchange="filtrarUCs()" style="position:absolute; left: 310px; top: 123px; width: 17px; height: 17px;"><text style="position: absolute; left: 330px; top: 119px;"><b>UC's da minha Área</b></text><br>
					
				<?php
				$statement = mysqli_prepare($conn, "SELECT * FROM curso");
				$statement->execute();
				$resultado1 = $statement->get_result();
				while($linha1 = mysqli_fetch_assoc($resultado1)){
					$idCurso = (int) $linha1["id_curso"];
					$nomeCurso = $linha1["nome"];
					$siglaCurso = $linha1["sigla"];
					$id_utc = $linha1["id_utc"];

					$statement001 = mysqli_prepare($conn, "SELECT dsd_1_sem, dsd_2_sem FROM utc WHERE id_utc = $id_utc;");
					$statement001->execute();
					$resultado001 = $statement001->get_result();
					$linha001 = mysqli_fetch_assoc($resultado001);
						$dsd_1_sem = $linha001["dsd_1_sem"];
						$dsd_2_sem = $linha001["dsd_2_sem"];

					// Obter disciplinas do curso
					$statement = mysqli_prepare($conn, "SELECT * FROM disciplina WHERE id_curso = $idCurso");
					$statement->execute();
					$resultado2 = $statement->get_result();
					while($linha2 = mysqli_fetch_assoc($resultado2)){
						$idDisciplina = (int) $linha2["id_disciplina"];
						$nomeDisciplina = $linha2["nome_uc"];
						$codigoUC = $linha2["codigo_uc"];
						$ano = (int) $linha2["ano"];
						$semestre = (int) $linha2["semestre"];
						$idResponsavel = 0;
						$id_curso = $linha2["id_curso"];
						if(!empty($linha2["id_responsavel"])){
							$idResponsavel = (int) $linha2["id_responsavel"];
						}
						$idArea = (int) $linha2["id_area"];
						$img = $linha2["imagem"];
						
						$statement000 = mysqli_prepare($conn, "SELECT id_utc FROM curso WHERE id_curso = $id_curso;");
						$statement000->execute();
						$resultado000 = $statement000->get_result();
						$linha000 = mysqli_fetch_assoc($resultado000);
							$id_utc_disciplina = $linha000["id_utc"];
						
						$statement00 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc_disciplina;");
						$statement00->execute();
						$resultado00 = $statement00->get_result();
						$linha00 = mysqli_fetch_assoc($resultado00);
							$id_responsavel_utc = $linha00["id_responsavel"];
						
						$arrayComponentes = array();
						
						$statement = mysqli_prepare($conn, "SELECT c.id_componente FROM componente c INNER JOIN disciplina d ON c.id_disciplina = d.id_disciplina WHERE d.id_disciplina = $idDisciplina;");
						$statement->execute();
						$resultado3 = $statement->get_result();
						while($linha3 = mysqli_fetch_assoc($resultado3)){
							$idComponente = $linha3["id_componente"];
							array_push($arrayComponentes, $idComponente);
						}
						$arrayComponentesFinal = implode(",", $arrayComponentes);
						
						$statement = mysqli_prepare($conn, "SELECT nome, id_utilizador, imagem_perfil FROM utilizador WHERE id_utilizador = $idResponsavel;");
						$statement->execute();
						$resultado4 = $statement->get_result();
						
						$statement = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_docente) FROM aula WHERE id_componente IN ($arrayComponentesFinal) AND id_docente != $idResponsavel;");
						$statement->execute();
						$resultado5 = $statement->get_result();
						$linha5 = mysqli_fetch_assoc($resultado5);
							$numDocentesAtribuídos = $linha5["COUNT(DISTINCT id_docente)"];
						
						if(($idAreaUtilizador == $idArea)){
						?>
							
				
				<div class="card_UC" id="card_UC" data-id_area_uc="<?php echo $idArea ?>" data-nome_disciplina="<?php echo $nomeDisciplina ?>"><a href="visDSUC_.php?id=<?php echo $idDisciplina ?>">
					<div class="container_card_UC_imagem"><img src="<?php if($img != null) {echo $img;} else { echo 'http://localhost/apoio_utc/images/fundo_disciplina_default_final.jpg'; } ?>" alt="" style="width:100%;">
					</div>
					<div class="container_card_UC">
						<div class="container_card_UC_titulo">
							<h4><b><?php echo $siglaCurso ?> - <?php echo $nomeDisciplina ?></b> (<?php echo $codigoUC ?>)</h4>
						</div>
						<div class="container_card_UC_detalhes">
						<?php while($linha4 = mysqli_fetch_assoc($resultado4)){
							$nomeDocente = $linha4["nome"];
							$idUtilizador = $linha4["id_utilizador"];
							$imgUtilizador = $linha4["imagem_perfil"];
							echo "<img src='$imgUtilizador' style='width:18px; heigh:18px; border-radius: 50%; margin-right: 5px; border:1px solid #212529;'>";
								if($idResponsavel == $idUtilizadorSessaoAtual) {
									echo "<b>", $nomeDocente, " </b>(responsável)<br>";
								}
								else{
									echo $nomeDocente, " (responsável)<br>";
								}
								
							if($numDocentesAtribuídos > 0){
								echo "<img src='http://localhost/apoio_utc/images/perfil_default.jpg' style='width:18px; heigh:18px; border-radius: 50%; margin-right: 5px; border:1px solid #212529;'>";
								echo "...<br>";
							}
								
						} ?></div><div class="container_card_UC_editar"><?php if(($idAreaUtilizador == $idArea) || ($idUtilizadorSessaoAtual == $id_responsavel_utc)) {
							if($semestre == 1){
								if($dsd_1_sem == 0){ ?>
									<a class="btn btn-primary" href="edDSUC.php?i=<?php echo $idDisciplina ?>" style='width:101px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>
			<?php				}
								else{ ?>
									<a class="btn btn-danger" title="A DSD deste semestre está bloqueada" onclick="semestreBloqueado()" href="javascript:void(0)" style='width:101px; border-radius:25px;'><span class="material-icons" style="vertical-align:middle;">lock</span>Editar</a>
			<?php				}
							}
							else{
								if($dsd_2_sem == 0){ ?>
									<a class="btn btn-primary" href="edDSUC.php?i=<?php echo $idDisciplina ?>" style='width:101px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>
			<?php				}
								else{ ?>
									<a class="btn btn-danger" title="A DSD deste semestre está bloqueada" onclick="semestreBloqueado()" href="javascript:void(0)" style='width:101px; border-radius:25px;'><span class="material-icons" style="vertical-align:middle;">lock</span>Editar</a>
			<?php				}
							}
							?>
							<?php } ?></div></p>
						<!--<a class='btn btn-primary' style='width:101px; border-radius:25px; float:right; margin-bottom:50px; margin-top:50px;' onclick=''><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a> -->
					</div>
					</a>
				</div>
				
						<?php }
						}
				}
				?><!--
				<div class="card_UC"><a href="#">
					<img src="http://localhost/apoio_utc/images/fundo_utc.jpg" alt="Avatar" style="width:100%;">
					<div class="container_card_UC">
						<h4><b>INF - Programação I</b> (6359)</h4>
						<p>Arlindo Silva</p>
					</div>
					<a>
				</div> -->
		</div>
	</div>
</div>
          
<!-- Modal -->
<div class="modal fade" id="visDSUC_UCS_por_atribuir" tabindex="-1" role="dialog" aria-labelledby="titulo_visDSUC_UCS_por_atribuir" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 28%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_visDSUC_UCS_por_atribuir">Resumo: UTC <?php echo $nome_utc_atual ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            </script>
            <div id="modalBody_visDSUC_UCS_por_atribuir" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function verUCSPorAtribuir(){
	var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBody_visDSUC_UCS_por_atribuir").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/verUCS_por_atribuir.php");
    xhttp.send();
}

function mostrarListaUCs(){
	
	var li_DSD = document.getElementById("li_DSD");
	var li_DSD_especifica = document.getElementById("li_DSD_DSUC");
	
	li_DSD.style.background = "#4a6f96";
	li_DSD_especifica.style.background = "#4a6f96";
	
	var dropdown_ucs = document.getElementById("uc_dropdown");
	var permAdmin = '<?php echo $permAdmin ?>';
	
	var dropdown_ucs = document.getElementById("uc_dropdown");
	var id_area_utilizador = <?php echo $idAreaUtilizador ?>;
	
	if(!permAdmin){
		//Mostrar a lista de UC's da mesma área do utilizador
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarListaUCsArea.php", 
			data: {id_area: id_area_utilizador},
			success: function(result) {
				var array = result.split(',');
				//alert("UC's: " + array);
				
				for(i = 0; i < array.length; i++){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i];
					dropdown_ucs.options.add(opt);
				} 
		
			}
		});
	}
	else{
		//Mostrar todas as UC's, primeiro da mesma área, depois da mesma UTC e por fim as outras
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarListaUCs.php", 
			data: {},
			success: function(result) {
				var array = result.split(',');
				//alert("UC's: " + array);
				
				for(i = 0; i < array.length; i++){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i];
					dropdown_ucs.options.add(opt);
				} 
		
			}
		});
	}
	
}
window.onload = mostrarCardsUCs;

function gerarFicheiroExcelDSD(id_utc){
	
	//const nomeUTC = 'Informática';
	
	//alert("Gerar ficheiro excel!");
	$('#cover-spin').show(0);
	
	//Gerar o ficheiro
	$.ajax ({
		type: "POST",
		url: "templateDSUC.php",
		data: {id_utc: id_utc},
		success: function(result) {
			
			//Redirecionar para o download
			window.location.href = result;
			
			setTimeout(function(){
					$('#cover-spin').hide();
					}
					,350);
		}
	});
	
}

function filtrarTabela() {
	
	var dropdown_ucs = document.getElementById("uc_dropdown");
	var dropdown_valor = dropdown_ucs.value;
	
	//alert("Filtar: " + dropdown_valor);
	
	$('#cover-spin').show(0);
	
	var uc_escolhida = dropdown_valor.substr(dropdown_valor.lastIndexOf('-') + 1).trim();
	//alert("Filtrar: " + uc_escolhida);
	
	var sigla_curso_escolhido = dropdown_valor.substr(0,dropdown_valor.lastIndexOf('(')).trim();
	//alert("Sigla curso escolhido: " + sigla_curso_escolhido);
	
	const card_body = document.getElementsByClassName("card-body")[0];
	var cartoes = document.getElementsByClassName("card_UC");
	
	if(document.getElementById("checkboxUCs").checked){
		if(Boolean(<?php echo $permAdmin ?>)){
			if(dropdown_valor != "nada_selecionado"){
				//Mostrar a disciplina específica
				
				apagarCartoes();
				
				$.ajax ({
				type: "POST",
				url: "processamento/verDadosDisciplinaCartao.php", 
				dataType: "json",
				data: {sigla_curso: sigla_curso_escolhido, uc: uc_escolhida},
				success: function(result) {
					//alert("Resultado: " + result); 
					
					var array_final_json = JSON.stringify(result);
					var array_final = array_final_json.split(",");
						
					var array_final_primeiro = array_final[0].replace("[","");
					var array_final_ultimo = array_final[array_final.length - 1].replace("]","");
					array_final[0] = array_final_primeiro;
					array_final[array_final.length - 1] = array_final_ultimo;
					
					var array_final_sigla_curso = array_final[3].replaceAll('"','');
					array_final[3] = array_final_sigla_curso;
						
					var array_final_nome_uc = array_final[4].replaceAll('"','');
					array_final[4] = array_final_nome_uc;
						
					var array_final_nome_disciplina = array_final[4].replaceAll('"','');
		
					const array_final_nome_responsavel = array_final[9].replaceAll('"','');
					const array_final_nome_responsavel_final = array_final_nome_responsavel.replace("[","");
					array_final[9] = array_final_nome_responsavel_final;
						
					const array_final_imagem_responsavel = array_final[10].replaceAll('"','');
					const array_final_imagem_responsavel_final = array_final_imagem_responsavel.replace("]","");
					array_final[10] = array_final_imagem_responsavel_final;
		
					//alert("array_final: " + array_final);
					
						//Criar o cartão da disciplina escolhida
						card_body.appendChild(criarCartao(array_final[0],array_final[1],array_final[2],array_final[3],array_final[4],array_final[5],
																array_final[6],array_final[7],array_final[8], array_final[9], array_final[10], array_final[11], array_final[12], array_final[13], array_final[14]));
						
					
					setTimeout(function(){;
					$('#cover-spin').hide();
					}
					,350);
			
				}
			});
				
			}
			else{
				apagarCartoes();
				mostrarTodasDisciplinas();
				setTimeout(function(){;
				$('#cover-spin').hide();
				}
				,2500);
			}
		}
		else{
		if(dropdown_valor != "nada_selecionado"){
			//Apagar os cartões todos e mostrar apenas o da disciplina escolhida e se apenas for da minha àrea
			
			for(i = 0; i < cartoes.length; i++){
				cartoes[i].style.display = "none";
			}
			
			//alert("Sigla_curso: " + sigla_curso_escolhido + " UC: " + uc_escolhida);
			
			$.ajax ({
				type: "POST",
				url: "processamento/verDadosDisciplinaCartao.php", 
				dataType: "json",
				data: {sigla_curso: sigla_curso_escolhido, uc: uc_escolhida},
				success: function(result) {
					//alert("Resultado: " + result); 
					var array_final_json = JSON.stringify(result);
					var array_final = array_final_json.split(",");
						
					var array_final_primeiro = array_final[0].replace("[","");
					var array_final_ultimo = array_final[array_final.length - 1].replace("]","");
					array_final[0] = array_final_primeiro;
					array_final[array_final.length - 1] = array_final_ultimo;
					
					var array_final_sigla_curso = array_final[3].replaceAll('"','');
					array_final[3] = array_final_sigla_curso;
						
					var array_final_nome_uc = array_final[4].replaceAll('"','');
					array_final[4] = array_final_nome_uc;
						
					var array_final_nome_disciplina = array_final[4].replaceAll('"','');
		
					const array_final_nome_responsavel = array_final[9].replaceAll('"','');
					const array_final_nome_responsavel_final = array_final_nome_responsavel.replace("[","");
					array_final[9] = array_final_nome_responsavel_final;
						
					const array_final_imagem_responsavel = array_final[10].replaceAll('"','');
					const array_final_imagem_responsavel_final = array_final_imagem_responsavel.replace("]","");
					array_final[10] = array_final_imagem_responsavel_final;
					
					//alert("array_final: " + array_final);
					
					
					const id_area_disciplina = array_final[0];
					const id_area_utilizador = <?php echo $idAreaUtilizador ?>;
					
					if(id_area_disciplina == id_area_utilizador){
						//Criar o cartão da disciplina escolhida
						card_body.appendChild(criarCartao(array_final[0],array_final[1],array_final[2],array_final[3],array_final[4],array_final[5],
																array_final[6],array_final[7],array_final[8], array_final[9], array_final[10], array_final[11], array_final[12], array_final[13], array_final[14]));
						
					}
					setTimeout(function(){;
					$('#cover-spin').hide();
					}
					,350);
			
				}
			});
			
		
		}
		else{
			//Apagar os cartões todos e mostrar todas as disciplinas da minha àrea
			apagarCartoes();
			
			mostrarTodasDisciplinasArea();
				
			setTimeout(function(){;
			$('#cover-spin').hide();
			}
			,2500);
		}
		}
	}
	else{
		if(dropdown_valor != "nada_selecionado"){
			//Apagar os cartões todos e mostrar apenas o da disciplina escolhida
			
			for(i = 0; i < cartoes.length; i++){
				cartoes[i].style.display = "none";
			}
			
			$.ajax ({
				type: "POST",
				url: "processamento/verDadosDisciplinaCartao.php", 
				dataType: "json",
				data: {sigla_curso: sigla_curso_escolhido, uc: uc_escolhida},
				success: function(result) {
					//alert("Resultado: " + result); 
					
					var array_final_json = JSON.stringify(result);
					var array_final = array_final_json.split(",");
						
					var array_final_primeiro = array_final[0].replace("[","");
					var array_final_ultimo = array_final[array_final.length - 1].replace("]","");
					array_final[0] = array_final_primeiro;
					array_final[array_final.length - 1] = array_final_ultimo;
					
					var array_final_sigla_curso = array_final[3].replaceAll('"','');
					array_final[3] = array_final_sigla_curso;
					
					var array_final_nome_uc = array_final[4].replaceAll('"','');
					array_final[4] = array_final_nome_uc;
						
					var array_final_nome_disciplina = array_final[4].replaceAll('"','');
		
					const array_final_nome_responsavel = array_final[9].replaceAll('"','');
					const array_final_nome_responsavel_final = array_final_nome_responsavel.replace("[","");
					array_final[9] = array_final_nome_responsavel_final;
						
					const array_final_imagem_responsavel = array_final[10].replaceAll('"','');
					const array_final_imagem_responsavel_final = array_final_imagem_responsavel.replace("]","");
					array_final[10] = array_final_imagem_responsavel_final;
					
					//alert("array_final: " + array_final);
					
					//Criar o cartão da disciplina escolhida
					card_body.appendChild(criarCartao(array_final[0],array_final[1],array_final[2],array_final[3],array_final[4],array_final[5],
															array_final[6],array_final[7],array_final[8], array_final[9], array_final[10], array_final[11], array_final[12], array_final[13], array_final[14]));
					
					setTimeout(function(){;
					$('#cover-spin').hide();
					}
					,350);
			
				}
			});

		}
		else{
			//Apagar os cartões todos e mostrar todas as disciplinas
			mostrarTodasDisciplinas();
				
			setTimeout(function(){;
			$('#cover-spin').hide();
			}
			,2500);
		}
	}
	
	/*
	if(document.getElementById("checkboxUCs").checked && uc_escolhida != "nada_selecionado"){
		//Mostrar a disciplina escolhida mas apenas se for da mesma àrea do utilizador
		apagarCartoes();
		$.ajax ({
			type: "POST",
			url: "processamento/verDadosDisciplinaCartao.php", 
			data: {id_area_utilizador: <?php echo $idAreaUtilizador ?>, disciplina: uc_escolhida, curso: sigla_curso_escolhido},
			success: function(result) {
				
			}
		});
	}
	else if(document.getElementById("checkboxUCs").checked && uc_escolhida == "nada_selecionado"){
		//Mostrar disciplinas apenas da minha àrea, como na primeira vez que entramos na página
		apagarCartoes();
	}
	else if(!document.getElementById("checkboxUCs").checked && uc_escolhida != "nada_selecionado"){
		//Mostrar uma disciplina específica, mas sem nos preocuparmos se está na nossa àrea ou não
		$.ajax ({
			type: "POST",
			url: "processamento/verDadosDisciplinaCartao.php", 
			data: {id_area_utilizador: null, disciplina: uc_escolhida, curso: sigla_curso_escolhido},
			success: function(result) {
				
			}
		});
	}
	else if(!document.getElementById("checkboxUCs").checked && uc_escolhida == "nada_selecionado"){
		//Apagar todas as disciplinas
		mostrarTodasDisciplinas();
	}*/
	/*
	var cartoes = document.getElementsByClassName("card_UC");
	
	for(i = 0; i < cartoes.length; i++){
		if(cartoes[i].getAttribute("data-nome_disciplina") != uc_escolhida){
			cartoes[i].style.display = "none";
		}
	}
	
	setTimeout(function(){;
	$('#cover-spin').hide();
	}
	,350);
	*/
	/*
	
	$.ajax ({
		type: "POST",
		url: "processamento/verNomeCompletoCurso.php", 
		data: {sigla: sigla_curso_escolhido},
		success: function(result) {
			var curso_escolhido = result;
			//alert("UC: " + curso_escolhido);
			
			if(uc_escolhida == "nada_selecionado"){
				tabela.search("").draw(); 
			}
			else{
				//tabela.search(uc_escolhida).draw(); 
				tabela.columns([0]).search(curso_escolhido).draw();
				tabela.columns([1]).search(uc_escolhida).draw();
			}
	
		}
	});
	*/
}

function mostrarCardsUCs(){
	//alert("Configurar as cards!");
	//Se for admin mostrar todas as cards, caso contrário mostrar as cards da mesma àrea
	$('#cover-spin').show(0);
	setTimeout(function(){;
		$('#cover-spin').hide();
		mostrarListaUCs();
		}
		,350);
}

function editarDSUC(idUC){
	window.location = "edDSUC.php?i=" + idUC;
}

function filtrarUCs(){
	
	$('#cover-spin').show(0);
	
	var permAdmin = '<?php echo $permAdmin ?>';
	var checkbox = document.getElementById("checkboxUCs");
	
	/*--------------------------------FILTRAGEM DROPDOWN UC's---------------------------------*/
	
	var dropdown_ucs = document.getElementById("uc_dropdown");
	var id_area_utilizador = <?php echo $idAreaUtilizador ?>;
	var id_utc_utilizador = <?php echo $idUTCUtilizador ?>;
	
	//Limpar as opções anteriores
	removerOpcoes(dropdown_ucs);
   
	if(checkbox.checked && !permAdmin){
		//Mostrar a lista de UC's da mesma área do utilizador
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarListaUCsArea.php", 
			data: {id_area: id_area_utilizador},
			success: function(result) {
				var array = result.split(',');
				//alert("UC's: " + array);
				
				var vazia = document.createElement('option');
				vazia.value = "nada_selecionado";
				vazia.text = "";
				dropdown_ucs.options.add(vazia);
				
				for(i = 0; i < array.length; i++){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i];
					dropdown_ucs.options.add(opt);
				} 
		
			}
		});
	}
	else{
		//Mostrar todas as UC's, primeiro da mesma área, depois da mesma UTC e por fim as outras
		//Mostrar a lista de UC's da mesma área do utilizador
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarListaUCsArea.php", 
			data: {id_area: id_area_utilizador},
			success: function(result) {
				var array = result.split(',');
				//alert("UC's: " + array);
				
				var vazia = document.createElement('option');
				vazia.value = "nada_selecionado";
				vazia.text = "";
				dropdown_ucs.options.add(vazia);
				
				var area = document.createElement('option');
				area.value = "nada_selecionado";
				area.text = "-------" + "->" + "<?php echo $nome_area_utilizador ?>";
				dropdown_ucs.options.add(area);
				
				for(i = 0; i < array.length; i++){
					var opt = document.createElement('option');
					opt.value = array[i];
					opt.text = array[i];
					dropdown_ucs.options.add(opt);
				} 
		
				var vazia2 = document.createElement('option');
				vazia2.value = "nada_selecionado";
				vazia2.text = "";
				dropdown_ucs.options.add(vazia2);
		
				var utc = document.createElement('option');
				utc.value = "nada_selecionado";
				utc.text = "-------" + "->" + "<?php echo $nome_utc_atual ?>";
				dropdown_ucs.options.add(utc);
		
				//UTC's
				$.ajax ({
					type: "POST",
					url: "processamento/mostrarListaUCsUTC.php", 
					data: {id_area: id_area_utilizador, id_utc: id_utc_utilizador},
					success: function(result) {
						//alert("Result_ " + result);
						var array_utc = result.split(',');
						for(i = 0; i < array_utc.length; i++){
							var opt = document.createElement('option');
							opt.value = array_utc[i];
							opt.text = array_utc[i];
							dropdown_ucs.options.add(opt);
						} 
						
						var vazia3 = document.createElement('option');
						vazia3.value = "nada_selecionado";
						vazia3.text = "";
						dropdown_ucs.options.add(vazia3);
						
						var outras = document.createElement('option');
						outras.value = "nada_selecionado";
						outras.text = "--" + "->OUTRAS";
						dropdown_ucs.options.add(outras);
								
						
						//Outras UTC's
						$.ajax ({
							type: "POST",
							url: "processamento/mostrarListaUCsOutras.php", 
							data: {id_area: id_area_utilizador, id_utc: id_utc_utilizador},
							success: function(result) {
								//alert("Result_ " + result);
								var array_outros = result.split(',');
								
								for(i = 0; i < array_outros.length; i++){
									var opt = document.createElement('option');
									opt.value = array_outros[i];
									opt.text = array_outros[i];
									dropdown_ucs.options.add(opt);
								} 
								
							}
						});
						
					}
				});
				
			}
		});
	}

	/*---------------------------------------FILTRAGEM----------------------------------------*/

	var cartoes = document.getElementsByClassName("card_UC");
	//alert("Nº de cartoes: " + cartoes.length);
	
	if(checkbox.checked && !permAdmin){
		//Mostrar apenas UC's da minha àrea
		
		for(i = 0; i < cartoes.length; i++){
			if(cartoes[i].getAttribute("data-id_area_uc") != <?php echo $idAreaUtilizador ?>){
				cartoes[i].style.display = "none";
			}
		}
		setTimeout(function(){;
		$('#cover-spin').hide();
		}
		,350);
	}
	else{
		
		mostrarTodasDisciplinas();
				
		//$('#card_UC').append(criarCartao(5));
		setTimeout(function(){;
		$('#cover-spin').hide();
		}
		,2500);
	}
	
	/*---------------------------------------FILTRAGEM----------------------------------------*/
	/*
	setTimeout(function(){;
		$('#cover-spin').hide();
		}
		,2000); */
}

function removerOpcoes(elemento) {
	var i, L = elemento.options.length - 1;
		for(i = L; i >= 0; i--) {
		  elemento.remove(i);
	}
}

function apagarCartoes(){
	var cartoes = document.getElementsByClassName("card_UC");
	for(i = 0; i < cartoes.length; i++){
		cartoes[i].style.display = "none";
	} 
}

function mostrarTodasDisciplinas(){
	
	$.ajax ({
		type: "POST",
		url: "processamento/verNumeroDisciplinasTotal.php", 
		data: {},
		success: function(result) {
			var array_result = result.split(",");
			
			var array_primeiro = array_result[0].replace("[","");
			var array_ultimo = array_result[array_result.length - 1].replace("]","");
			
			array_result[0] = array_primeiro;
			array_result[array_result.length - 1] = array_ultimo;
			
			const card_body = document.getElementsByClassName("card-body")[0];
			var cartoes = document.getElementsByClassName("card_UC");
	
			apagarCartoes();
			
			let request_queue = [];
			
			for(i = 0; i < array_result.length; i++){
				
				request_queue.push({
					type: "POST",
					url: "processamento/verDadosDisciplina.php", 
					dataType: "json",
					data: {id_disciplina: array_result[i]},
					success: function(result) {
						var array_final_json = JSON.stringify(result);
						var array_final = array_final_json.split(",");
						
						var array_final_primeiro = array_final[0].replace("[","");
						var array_final_ultimo = array_final[array_final.length - 1].replace("]","");
						array_final[0] = array_final_primeiro;
						array_final[array_final.length - 1] = array_final_ultimo;
						
						var array_final_sigla_curso = array_final[3].replaceAll('"','');
						array_final[3] = array_final_sigla_curso;
									
						var array_final_nome_disciplina = array_final[4].replaceAll('"','');
						array_final[4] = array_final_nome_disciplina;
						/*
						var array_docentes_final = array_final[9].split(",");
						array_final[9] = array_docentes_final[0] + array_docentes_final[1];
						*/
						//alert("ARRAY_FINAL: " + array_final[10]);
										
						const array_final_nome_responsavel = array_final[9].replaceAll('"','');
						const array_final_nome_responsavel_final = array_final_nome_responsavel.replace("[","");
						array_final[9] = array_final_nome_responsavel_final;
									
						const array_final_imagem_responsavel = array_final[10].replaceAll('"','');
						const array_final_imagem_responsavel_final = array_final_imagem_responsavel.replace("]","");
						array_final[10] = array_final_imagem_responsavel_final;
									
						//alert("TESTE: " + Boolean(array_final[11] != "null"));
								
						//alert("TESTE" + id_disciplina);
								
						card_body.appendChild(criarCartao(array_final[0],array_final[1],array_final[2],array_final[3],array_final[4],array_final[5],
															array_final[6],array_final[7],array_final[8], array_final[9], array_final[10], array_final[11], array_final[12], array_final[13], array_final[14]));
									
				//alert("DISCIPLINA : " + array_final[4] + " DADOS: " + array_final);
						},
					complete: function() {
						// After this request finishes, whether it succeeds or fails, take the next request and execute it.
						let next_request = request_queue.pop();
						if(next_request) {
							$.ajax(next_request);
						}
					}
				});
							
				//criarCartaoDisciplina(array_result[i]);
				
			}
			
			$.ajax(request_queue.pop());
			$.ajax(request_queue.pop());
			
		}
	});
		
}

function criarCartaoDisciplina(i){
	
	const card_body = document.getElementsByClassName("card-body")[0];
	var id_disciplina = i;
	
	//alert("Criar cartão para a disciplina: " + id_disciplina);
	
	$.ajax ({
		type: "POST",
		url: "processamento/verDadosDisciplina.php", 
		dataType: "json",
		data: {id_disciplina: id_disciplina},
		success: function(result) {
			var array_final_json = JSON.stringify(result);
			var array_final = array_final_json.split(",");
			
			var array_final_primeiro = array_final[0].replace("[","");
			var array_final_ultimo = array_final[array_final.length - 1].replace("]","");
			array_final[0] = array_final_primeiro;
			array_final[array_final.length - 1] = array_final_ultimo;
			
			var array_final_sigla_curso = array_final[3].replaceAll('"','');
			array_final[3] = array_final_sigla_curso;
						
			var array_final_nome_disciplina = array_final[4].replaceAll('"','');
			array_final[4] = array_final_nome_disciplina;
			/*
			var array_docentes_final = array_final[9].split(",");
			array_final[9] = array_docentes_final[0] + array_docentes_final[1];
			*/
			//alert("ARRAY_FINAL: " + array_final[10]);
							
			const array_final_nome_responsavel = array_final[9].replaceAll('"','');
			const array_final_nome_responsavel_final = array_final_nome_responsavel.replace("[","");
			array_final[9] = array_final_nome_responsavel_final;
						
			const array_final_imagem_responsavel = array_final[10].replaceAll('"','');
			const array_final_imagem_responsavel_final = array_final_imagem_responsavel.replace("]","");
			array_final[10] = array_final_imagem_responsavel_final;
						
			const array_final_nome_docente = array_final[11].replaceAll('"','');
			array_final[11] = array_final_nome_docente;
						
			//alert("TESTE: " + Boolean(array_final[11] != "null"));
					
			alert("TESTE" + id_disciplina);
					
			card_body.appendChild(criarCartao(array_final[0],array_final[1],array_final[2],array_final[3],array_final[4],array_final[5],
												array_final[6],array_final[7],array_final[8], array_final[9], array_final[10], array_final[11], array_final[12], array_final[13], array_final[14]));
						
			//alert("DISCIPLINA : " + array_final[4] + " DADOS: " + array_final);
		}
	});
	
}

function mostrarTodasDisciplinasArea(){
	
	$.ajax ({
		type: "POST",
		url: "processamento/verNumeroDisciplinasTotal.php", 
		data: {},
		success: function(result) {
			var array_result = result.split(",");
			
			var array_primeiro = array_result[0].replace("[","");
			var array_ultimo = array_result[array_result.length - 1].replace("]","");
			
			array_result[0] = array_primeiro;
			array_result[array_result.length - 1] = array_ultimo;
	
			const card_body = document.getElementsByClassName("card-body")[0];
			var cartoes = document.getElementsByClassName("card_UC");
	
			let request_queue = [];
	
			for(i = 0; i < array_result.length; i++){
				
				request_queue.push({
					type: "POST",
					url: "processamento/verDadosDisciplina.php", 
					dataType: "json",
					data: {id_disciplina: array_result[i]},
					success: function(result) {
						var array_final_json = JSON.stringify(result);
						var array_final = array_final_json.split(",");
						
						//alert("Final: " + array_final);
						
						var array_final_primeiro = array_final[0].replace("[","");
						var array_final_ultimo = array_final[array_final.length - 1].replace("]","");
						array_final[0] = array_final_primeiro;
						array_final[array_final.length - 1] = array_final_ultimo;
									
						var array_final_sigla_curso = array_final[3].replaceAll('"','');
						array_final[3] = array_final_sigla_curso;
									
						var array_final_nome_disciplina = array_final[4].replaceAll('"','');
						array_final[4] = array_final_nome_disciplina;
						/*
						var array_docentes_final = array_final[9].split(",");
						array_final[9] = array_docentes_final[0] + array_docentes_final[1];
						*/
						//alert("ARRAY_FINAL: " + array_final[10]);
											
						const array_final_nome_responsavel = array_final[9].replaceAll('"','');
						const array_final_nome_responsavel_final = array_final_nome_responsavel.replace("[","");
						array_final[9] = array_final_nome_responsavel_final;
									
						const array_final_imagem_responsavel = array_final[10].replaceAll('"','');
						const array_final_imagem_responsavel_final = array_final_imagem_responsavel.replace("]","");
						array_final[10] = array_final_imagem_responsavel_final;
									

						//alert("TESTE: " + Boolean(array_final[11] != "null"));
										
						if(array_final[0] == <?php echo $idAreaUtilizador ?>){
									
							card_body.appendChild(criarCartao(array_final[0],array_final[1],array_final[2],array_final[3],array_final[4],array_final[5],
											array_final[6],array_final[7],array_final[8], array_final[9], array_final[10], array_final[11], array_final[12], array_final[13], array_final[14]));
						}
					},
					complete: function() {
						// After this request finishes, whether it succeeds or fails, take the next request and execute it.
						let next_request = request_queue.pop();
						if(next_request) {
							$.ajax(next_request);
						}
					}
				});
							
				//alert("DISCIPLINA : " + array_final[4] + " DADOS: " + array_final);
			}
				
			$.ajax(request_queue.pop());
			$.ajax(request_queue.pop());
			
		}
	});
		
}

function appendDisciplina(id_disciplina){
	
	const card_body = document.getElementsByClassName("card-body")[0];
	var cartoes = document.getElementsByClassName("card_UC");
	
	$.ajax ({
		type: "POST",
		url: "processamento/verDadosDisciplina.php", 
		dataType: "json",
		data: {id_disciplina: id_disciplina},
		success: function(result) {
			var array_final_json = JSON.stringify(result);
			var array_final = array_final_json.split(",");
			
			//alert("Final: " + array_final);
			
			var array_final_primeiro = array_final[0].replace("[","");
			var array_final_ultimo = array_final[array_final.length - 1].replace("]","");
			array_final[0] = array_final_primeiro;
			array_final[array_final.length - 1] = array_final_ultimo;
						
			var array_final_sigla_curso = array_final[3].replaceAll('"','');
			array_final[3] = array_final_sigla_curso;
						
			var array_final_nome_disciplina = array_final[4].replaceAll('"','');
			array_final[4] = array_final_nome_disciplina;
			/*
			var array_docentes_final = array_final[9].split(",");
			array_final[9] = array_docentes_final[0] + array_docentes_final[1];
			*/
			//alert("ARRAY_FINAL: " + array_final[10]);
								
			const array_final_nome_responsavel = array_final[9].replaceAll('"','');
			const array_final_nome_responsavel_final = array_final_nome_responsavel.replace("[","");
			array_final[9] = array_final_nome_responsavel_final;
						
			const array_final_imagem_responsavel = array_final[10].replaceAll('"','');
			const array_final_imagem_responsavel_final = array_final_imagem_responsavel.replace("]","");
			array_final[10] = array_final_imagem_responsavel_final;
						

			//alert("TESTE: " + Boolean(array_final[11] != "null"));
							
			if(array_final[0] == <?php echo $idAreaUtilizador ?>){
						
			card_body.appendChild(criarCartao(array_final[0],array_final[1],array_final[2],array_final[3],array_final[4],array_final[5],
								array_final[6],array_final[7],array_final[8], array_final[9], array_final[10], array_final[11], array_final[12], array_final[13], array_final[14]));
			}
			//alert("DISCIPLINA : " + array_final[4] + " DADOS: " + array_final);
		}
	});
	
}

function criarCartao(id_area, id_disciplina, imagem_fundo, sigla_curso, nome_disciplina, codigo_uc,
						id_area_utilizador, id_area, perm_admin, nome_responsavel, imagem_responsavel, 
						numero_outros_docentes, id_responsavel, id_responsavel_utc_disciplina, semestre_uc){
		/*						
		alert("AREA: " + id_area + " ID_DISCIPLINA: " + id_disciplina + " IMG: " + imagem_fundo + " SIGLA_CURSO: " + sigla_curso + 
				" DISCIPLINA: " + nome_disciplina + " CODIGO_UC: " + codigo_uc + " AREA_UTILIZADOR " + id_area_utilizador + " AREA_UC: " + 
				id_area + " PERM_ADMIN: " + perm_admin + " RESPONSAVEL: " + nome_responsavel + " IMG_RESPONSAVEL: " + imagem_responsavel +
				" OUTROS DOCENTES: " + numero_outros_docentes + " ID_RESPONSAVEL: " + id_responsavel + " ID_RESPONSAVEL_UTC_UC: " + id_responsavel_utc_disciplina);
								*/
		const dsd_1_sem = <?php echo $dsd_1_sem; ?>;
		const dsd_2_sem = <?php echo $dsd_2_sem; ?>;
				
		const cartao = document.createElement("div");
		cartao.className = "card_UC";
		cartao.id = "card_UC";
		cartao.setAttribute("data-id_area_uc", id_area);
		cartao.setAttribute("data-nome_disciplina", nome_disciplina);
		
		var a_href = document.createElement("a");
		a_href.href = "visDSUC_.php?id=" + id_disciplina;
		cartao.appendChild(a_href);
		
		var img = document.createElement("img");
		img.setAttribute("src", "http://localhost/apoio_utc/images/fundo_disciplina_default_final.jpg");
		img.setAttribute("alt", "");
		img.setAttribute("width", "100%");
		a_href.appendChild(img);
		
			const container_card_uc = document.createElement("div");
			container_card_uc.className = "container_card_UC";
			//container_card_uc.innerText = "teste";
			
				const container_card_uc_titulo = document.createElement("div");
				container_card_uc_titulo.className = "container_card_UC_titulo";
				
				const h4 = document.createElement("h4");
				h4.innerHTML = "<b>" + sigla_curso + " - " + nome_disciplina + " </b/>(" + codigo_uc + ")";
				container_card_uc_titulo.appendChild(h4);
				
				const container_card_uc_detalhes = document.createElement("div");
				container_card_uc_detalhes.className = "container_card_UC_detalhes";
				
				var id_utilizador = <?php echo $idUtilizadorSessaoAtual ?>;
				if(id_utilizador == id_responsavel){
					var string_docentes = "<img src=" + imagem_responsavel + " style='width:18px; heigh:18px; border-radius: 50%; margin-right: 5px; border:1px solid #212529;'><b>" + nome_responsavel + " </b>(responsável)<br>";
				}
				else{
				var string_docentes = "<img src=" + imagem_responsavel + " style='width:18px; heigh:18px; border-radius: 50%; margin-right: 5px; border:1px solid #212529;'>" + nome_responsavel + " (responsável)<br>";
				}
				if(numero_outros_docentes > 0){
					string_docentes = string_docentes + "<img src='http://localhost/apoio_utc/images/perfil_default.jpg' style='width:18px; heigh:18px; border-radius: 50%; margin-right: 5px; border:1px solid #212529;'>...";
				}
				
				container_card_uc_detalhes.innerHTML = string_docentes;

	/*			<?php while($linha4 = mysqli_fetch_assoc($resultado4)){
						$nomeDocente = $linha4["nome"];
						$idUtilizador = $linha4["id_utilizador"];
						$imgUtilizador = $linha4["imagem_perfil"];
						echo "<img src='$imgUtilizador' style='width:18px; heigh:18px; border-radius: 50%; margin-right: 5px;'>";
						if($idUtilizador == $idResponsavel){
							echo $nomeDocente, " (responsável)<br>";
						}
						else{
							echo $nomeDocente, "<br>";
						}
					} ?> */
				
				const container_card_uc_editar = document.createElement("div");
				container_card_uc_editar.className = "container_card_UC_editar";
				/*		var botaoEditar = document.createElement("a");
					botaoEditar.setAttribute("class", "btn btn-primary");
					botaoEditar.setAttribute("href", "edDSUC.php?i=" + id_disciplina);
					botaoEditar.setAttribute("width", "101px");
					botaoEditar.style.css = "width: 101px; border-radius: 25px;";//setAttribute("border-radius" , "25px");
						
						var icone = document.createElement("icon");
						icone.setAttribute("class", "material-icons");
						icone.setAttribute("vertical-align", "middle");
						icone.innerText = "edit_note";
						botaoEditar.appendChild(icone);
						botaoEditar.innerText = "Editar";
						
				//container_card_uc_editar.appendChild(botaoEditar);
				*/
				//alert("SEM: " + semestre_uc + " DSD: " + dsd_1_sem + " - " + dsd_2_sem);
				
				if(Boolean(id_area == id_area_utilizador || Boolean(<?php echo $idUtilizadorSessaoAtual ?> == id_responsavel_utc_disciplina))){
					if(semestre_uc == 1){
						if(dsd_1_sem == 0){
							container_card_uc_editar.innerHTML = "<a class='btn btn-primary' href='edDSUC.php?i= " + id_disciplina + " + ' style='width:101px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>";
						}
						else{
							container_card_uc_editar.innerHTML = "<a class='btn btn-danger' title='A DSD deste semestre está bloqueada' onclick='semestreBloqueado()' href='javascript:void(0)' style='width:101px; border-radius:25px;'><span class='material-icons' style='vertical-align:middle;'>lock</span>Editar</a>";
						}
					}
					else{
						if(dsd_2_sem == 0){
							container_card_uc_editar.innerHTML = "<a class='btn btn-primary' href='edDSUC.php?i= " + id_disciplina + " + ' style='width:101px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>";
						}
						else{
							container_card_uc_editar.innerHTML = "<a class='btn btn-danger' title='A DSD deste semestre está bloqueada' onclick='semestreBloqueado()' href='javascript:void(0)' style='width:101px; border-radius:25px;'><span class='material-icons' style='vertical-align:middle;'>lock</span>Editar</a>";
						}
					}
				}
				
				container_card_uc.append(container_card_uc_titulo);
				container_card_uc.append(container_card_uc_detalhes);
				container_card_uc.append(container_card_uc_editar);
				
				const paragrafo = document.createTextNode("<p>");
				
				//container_card_uc.appendChild(paragrafo);
				
			a_href.append(container_card_uc);
		
		cartao.appendChild(a_href);
	
		return cartao;
	}
	
function resumo_verDisciplinasUTC(id_utc){
	//alert("Ver resumo das UC's da UTC: " + id_utc);
	/*
	const titulos_utc = document.getElementsByClassName("titulo_utc");
	for(i = 0; i < titulos_utc.length; i++){
		var id_utc_temp = titulos_utc[i].getAttribute("data-id_utc");
		if(id_utc_temp == id_utc){
			titulos_utc[i].style.backgroundColor = "#e6e6e6";
		}
		else{
			titulos_utc[i].style.backgroundColor = "#fafafa";
		}
	}
	*/
	const div_principal = document.getElementById("listagem_cursos");
	
	limpar_disciplinas_anterior();
	
	var cursos = document.createElement("h5");
	cursos.innerHTML = "Cursos";
	var paragrafo_1 = document.createElement("br");
	
	div_principal.appendChild(cursos);
	div_principal.appendChild(paragrafo_1);
	
	$.ajax ({
		type: "POST",
		url: "processamento/verCursosPorAtribuirUTC.php", 
		data: {id_utc: id_utc},
		dataType: "json",
		success: function(result) {
			//var array = result.split(',');
			//alert("Result " + result);
			
			for(i = 0; i < result.length; i = i + 5){
				var para = document.createElement('br');
				var bold = document.createElement('strong');
				var curso = document.createTextNode(result[i + 1] + " (" + result[i + 2] + "/" + result[i + 3] + ")");
				bold.appendChild(curso);
				
				if(result[i + 2] != result[i + 3]){
					var a = document.createElement("a");
					a.href = "#";
					a.title = 'Curso: ' + result[i + 4];
					a.setAttribute("onclick","verUCSPorAtribuirUC(" + result[i] + "," + id_utc + ")");
					a.style.marginTop = "20px";
					a.appendChild(bold);
					a.appendChild(para);
					div_principal.appendChild(a);
				}
				else{
					var text = document.createElement('text');
					text.appendChild(bold);
					text.appendChild(para);
					div_principal.appendChild(text);
				}
				
			}
		}
	});
	
	//echo "<b>", $sigla_curso, " ", (sizeof($array_disciplinas_total) - sizeof($array_disciplinas_por_atribuir)), "/", sizeof($array_disciplinas_total), "</b><br>";
	
	/*
	const div_informatica = document.getElementById("div_informatica");
	const div_civil = document.getElementById("div_civil");
	
	if(id_utc == 1){
		div_civil.remove();
		div_informatica.style.visibility = "visible";
	}
	else if(id_utc == 2){
		div_informatica.remove();
		div_civil.style.visibility = "visible";
	} */
}

function limpar_utc_anterior(){	
	var cursos = document.getElementsByClassName("div_utc");
	for(i = 0; i < cursos.length; i++){
		cursos[i].remove();
	} 
}

function limpar_cursos_anterior(){
	$('#listagem_cursos').find('h5').each(function () {
		this.remove();
	});
	$('#listagem_cursos').find('br').each(function () {
		this.remove();
	});
	$('#listagem_cursos').find('a').each(function () {
		this.remove();
	});
	$('#listagem_cursos').find('b').each(function () {
		this.remove();
	});
	$('#listagem_cursos').find('text').each(function () {
		this.remove();
	});
	/*
	var paragrafos = document.getElementsByClassName("div_curso_br");
	var h6 = document.getElementsByClassName("div_curso_h6");
	var disciplinas = document.getElementsByClassName("div_curso");
	while(disciplinas.length > 0){
		disciplinas[0].parentNode.removeChild(disciplinas[0]);
	}
	while(paragrafos.length > 0){
		paragrafos[0].parentNode.removeChild(paragrafos[0]);
	}
	while(h6.length > 0){
		h6[0].parentNode.removeChild(h6[0]);
	}*/
}

function limpar_disciplinas_anterior(){
	$('#listagem_cursos').find('img').each(function () {
		this.remove();
	});
	$('#listagem_cursos').find('h6').each(function () {
		this.remove();
	});
	$('#listagem_cursos').find('a').each(function () {
		this.remove();
	});
	$('#listagem_cursos').find('b').each(function () {
		this.remove();
	});
	$('#listagem_cursos').find('button').each(function () {
		this.remove();
	});
	$('#listagem_cursos').find('br').each(function () {
		this.remove();
	});
	$('#listagem_cursos').empty();
}

function verUCSPorAtribuirUC(id_curso,id_utc){
	//alert("UTC: " + id_utc);
	//alert("Ver UC's com docentes por atribuir: " + id_curso);
	
	//limpar_utc_anterior();
	limpar_cursos_anterior();
	
	const div_principal = document.getElementById("listagem_cursos");
	
	$.ajax ({
		type: "POST",
		url: "processamento/verUCsPorAtribuirCurso.php", 
		data: {id_curso: id_curso},
		dataType: "json",
		success: function(result) {
			//var array = result.split(',');
			//alert("Result " + result);
			
			const paragrafo  = document.createElement("br");
			paragrafo.className = "div_curso_br";
			
			if(result.length > 0){
			
				const botao_voltar = document.createElement("img");
				botao_voltar.src = "http://localhost/apoio_utc/images/voltar3.png";
				botao_voltar.title= "Voltar";
				botao_voltar.className = "botao_voltar";
				botao_voltar.style.width = "40px";
				botao_voltar.style.height = "40px";
				botao_voltar.setAttribute("onclick","resumo_verDisciplinasUTC(" + id_utc + ")");
				
				const paragrafo2  = document.createElement("br");
				paragrafo2.className = "div_curso_br";
				
				//div_principal.appendChild(paragrafo); 
				div_principal.appendChild(botao_voltar); 
				div_principal.appendChild(paragrafo2); 
			
				const titulo_1sem = document.createElement("h6");
				titulo_1sem.className = "div_curso_h6";
				titulo_1sem.innerHTML = "1º Semestre";
				titulo_1sem.style.marginTop = "15px";
				//div_principal.appendChild(paragrafo); 
				div_principal.appendChild(titulo_1sem); 
				
				for(i = 0; i < result.length; i = i + 8){
					const div_curso = document.createElement("div");
					div_curso.className = "div_curso";
					div_curso.style.marginTop = "20px";
					
					const div_nome_uc  = document.createElement("div");
					div_nome_uc.className = "div_nome_uc";
					
					var a = document.createElement("a");
					a.href = "visDSUC_.php?id=" + result[i];
					a.title = "Visualizar DSD";
					var para = document.createElement('br');
					var bold = document.createElement('strong');
					var disciplina = document.createTextNode(result[i + 1] + " (" + result[i + 2] + ")");
					bold.appendChild(disciplina);
					a.appendChild(bold);
					div_nome_uc.appendChild(a);
					
					var id_area_uc = result[i + 5];
					var id_responsavel_utc_disciplina = result[i + 6];
					
					const semestre_uc = result[i + 7];
					
					const dsd_1_sem = <?php echo $dsd_1_sem; ?>;
					const dsd_2_sem = <?php echo $dsd_2_sem; ?>;
					
					//div_curso.appendChild(para);
					//div_principal.appendChild(div_curso); 
					
					const div_botao_editar = document.createElement("div");
					div_botao_editar.className = "div_botao_editar";
					
					if((<?php echo $idAreaUtilizador ?> == id_area_uc) || (Boolean(<?php echo $idUtilizadorSessaoAtual ?> == id_responsavel_utc_disciplina))){
						if(dsd_1_sem == 0){
							div_botao_editar.innerHTML += "<a class='btn btn-primary' href='edDSUC.php?i=" + result[i] + "' title='Editar DSD' style='margin-left:5px; width:45px; height:25px; border-radius:25px;'><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i></a>";
						}
						else{
							div_botao_editar.innerHTML = "<a class='btn btn-danger' title='A DSD deste semestre está bloqueada' onclick='semestreBloqueado()' href='javascript:void(0)' style='margin-left:5px; width:45px; height:25px; border-radius:25px;'><span class='material-icons' style='width:15px; height:15px; line-height:13px; float:left; margin-left:-2px;'>lock</span></a>";
						}
					}
					
					div_curso.appendChild(div_nome_uc);
					div_curso.appendChild(div_botao_editar);
					
					div_principal.appendChild(div_curso);
					div_principal.innerHTML += "<br>";
				}
			
			}
			
				$.ajax ({
					type: "POST",
					url: "processamento/verUCsPorAtribuirCurso2.php", 
					data: {id_curso: id_curso},
					dataType: "json",
					success: function(result2) {
						//var array = result.split(',');
						//alert("Result " + result);
						if(result2.length > 0){
							
							const titulo_2sem = document.createElement("h6");
							titulo_2sem.className = "div_curso_h6";
							titulo_2sem.innerHTML = "2º Semestre";
							titulo_2sem.style.marginTop = "25px";
							titulo_2sem.style.float = "left";
							div_principal.appendChild(paragrafo); 
							div_principal.appendChild(titulo_2sem); 
							
							for(i = 0; i < result2.length; i = i + 8){
								const div_curso = document.createElement("div");
								div_curso.className = "div_curso";
								div_curso.style.marginTop = "20px";
								
								const div_nome_uc  = document.createElement("div");
								div_nome_uc.className = "div_nome_uc";

								var a = document.createElement("a");
								a.href = "visDSUC_.php?id=" + result2[i];
								var para = document.createElement('br');
								var bold = document.createElement('strong');
								var disciplina = document.createTextNode(result2[i + 1] + " (" + result2[i + 2] + ")");
								bold.appendChild(disciplina);
								a.appendChild(bold);
								div_nome_uc.appendChild(a);
								
								var id_area_uc = result2[i + 5];
								var id_responsavel_utc_disciplina = result2[i + 6];
				
								const semestre_uc = result[i + 7];
					
								const dsd_1_sem = <?php echo $dsd_1_sem; ?>;
								const dsd_2_sem = <?php echo $dsd_2_sem; ?>;
					
								const div_botao_editar = document.createElement("div");
								div_botao_editar.className = "div_botao_editar";
								
								if((<?php echo $idAreaUtilizador ?> == id_area_uc) || (Boolean(<?php echo $idUtilizadorSessaoAtual ?> == id_responsavel_utc_disciplina))){
									if(dsd_2_sem == 0){
										div_botao_editar.innerHTML += "<a class='btn btn-primary' href='edDSUC.php?i=" + result2[i] + "' title='Editar DSD' style='margin-left:5px; width:45px; height:25px; border-radius:25px;'><i class='material-icons' style='width:15px; height:15px; line-height:13px; float:left;'>edit_note</i></a>";
									}
									else{
										div_botao_editar.innerHTML = "<a class='btn btn-danger' title='A DSD deste semestre está bloqueada' onclick='semestreBloqueado()' href='javascript:void(0)' style='margin-left:5px; width:45px; height:25px; border-radius:25px;'><span class='material-icons' style='width:15px; height:15px; line-height:13px; float:left; margin-left:-2px;'>lock</span></a>";
									}
								}
								
								div_curso.appendChild(div_nome_uc);
								div_curso.appendChild(div_botao_editar);
								div_principal.innerHTML += "<br>";
								
								div_principal.appendChild(div_curso); 
							}
							
						}
					}
				});
		}
	});
	
}

function semestreBloqueado(){
	alert("A DSD deste semestre está bloqueada. Por favor contacte o coordenador da UTC.");
}
</script>
</main>


<?php gerarHome2() ?>
