<?php
// Página de visualização das junções das turmas (visJuncoes)

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');

$idUtilizador = (int) $_SESSION['id'];
$permAdmin = false;
$permUTC = false;
$permArea = false;

if(isset($_SESSION['permAdmin'])){
    $permAdmin = true;
}
if(isset($_SESSION['permUTC'])){
    $permUTC = true;
}
if(isset($_SESSION['permArea'])){
    $permArea = true;
}


$idUTCUtilizador = 0;
$idAreaUtilizador = 0;

$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador = ?");
$statement->bind_param('i', $idUtilizador);
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
if(!empty($linha["id_area"])){
    $idAreaUtilizador = (int) $linha["id_area"];
    
    $statement = mysqli_prepare($conn, "SELECT * FROM area WHERE id_area = ?");
    $statement->bind_param('i', $idAreaUtilizador);
    $statement->execute();
    $resultado = $statement->get_result();
    $linha = mysqli_fetch_assoc($resultado);
    $idUTCUtilizador = (int) $linha["id_utc"];
}
?>
<?php gerarHome1() ?>
<script src="js/juncoes.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<main>
    <div class="container-fluid">
	
	<!-- TreeView -->
		<div id="treeview"></div>
		<div class="topo">
        <h2 align="center" class="mt-4">Junções de turmas<br></h2>
        <!-- DataTable -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
				<a class="btn btn-primary" data-toggle="modal" style="border-radius:25px;" data-target="#criarJuncaoModel" onclick="gerarFormCriarJuncao()"><i class="material-icons" style="line-height:50%; margin-right:5px; vertical-align: middle;">join_inner</i>Criar Junção</a></div>
				   <br><table class="table table-striped" id="tabelaDSUC" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nome Junção</th>
								<th>Turmas</th>
								<?php if( $permAdmin ){ ?>
									<th width='194px'></th>
								<?php } ?>    
                            </tr>
                        </thead>
                        <tbody>
<?php
$statement = mysqli_prepare($conn, "SELECT id_juncao, nome_juncao FROM juncao;");
$statement->execute();
$resultado1 = $statement->get_result();
while($linha1 = mysqli_fetch_assoc($resultado1)){
	$id = (int) $linha1['id_juncao'];
	$nomeJuncao = $linha1['nome_juncao'];
?>
                            <tr>
                                <td width=375><?php echo $nomeJuncao ?></td>
								<td width=375><?php //echo "<b>", $nome_turma, "</b>","(", $ano, "º/", $sem, "º)", / ", $id_bloco;
								
									$statement2 = mysqli_prepare($conn,"SELECT t.nome, t.ano, t.semestre, ct.id_componente FROM turma t 
																				INNER JOIN componente_turma ct ON t.id_turma = ct.id_turma 
																				INNER JOIN juncao_componente jc ON ct.id_componente = jc.id_componente
																				INNER JOIN juncao j ON jc.id_juncao = j.id_juncao WHERE j.id_juncao = $id;");
									$statement2->execute();
									$resultado2 = $statement2->get_result();
									while($linha2 = mysqli_fetch_assoc($resultado2)){
										$nome_turma = $linha2["nome"];
										$ano_turma = $linha2["ano"];
										$sem_turma = $linha2["semestre"];
										
										$id_componente = $linha2["id_componente"];
										
										$statement3 = mysqli_prepare($conn, "SELECT d.abreviacao_uc FROM disciplina d
																			  INNER JOIN componente c ON d.id_disciplina = c.id_disciplina
																			  INNER JOIN componente_turma ct ON c.id_componente = ct.id_componente;");
										$statement3->execute();
										$resultado3 = $statement3->get_result();
										while($linha3 = mysqli_fetch_assoc($resultado3)){
											$abreviacao_uc = $linha3["abreviacao_uc"];
										}
										
										$statement4 = mysqli_prepare($conn, "SELECT tc.sigla_tipocomponente FROM tipo_componente tc
																			  INNER JOIN componente c ON tc.id_tipocomponente = c.id_tipocomponente
																			  INNER JOIN componente_turma ct ON c.id_componente = ct.id_componente WHERE ct.id_componente = $id;");
										$statement4->execute();
										$resultado4 = $statement4->get_result();
										while($linha4 = mysqli_fetch_assoc($resultado4)){
											$sigla_tipocomponente = $linha4["sigla_tipocomponente"];
										}
										
										echo "<b>", $nome_turma, "(", $ano_turma, "º/", $sem_turma, "º)</b> - ", $abreviacao_uc, "-", $sigla_tipocomponente,"<br>"; 
											
									}	
										
							?></td>
                                <?php if( $permAdmin ){ 
									echo "<td width=65 align='center'><a class='btn btn-primary' href='edJuncao.php?i=$id' style='width:101px; border-radius:25px;'><i class='material-icons' style='vertical-align: middle;'>edit_note</i>Editar</a>";
									echo "<a class='btn btn-danger' onclick='removerJuncao($id)' style='margin-left: 10px; width:101px; border-radius: 25px;'><i class='material-icons' style='vertical-align: middle;'>delete_outline</i>Apagar</a></td>";
								}
								?>
                            </tr>
<?php
}
?>
                        </tbody>
                    </table>
                    <script>
                        // dataTables jQuery plugin
                        $(document).ready(function () {
                            $('#tabelaDSUC').DataTable({
                                responsive: true,
                                pageLength: 25,
                                order: [0, 'asc'],
								"language": {
									"lengthMenu": "Ver _MENU_ entradas",
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
<div class="modal fade" id="criarJuncaoModel" tabindex="-1" role="dialog" aria-labelledby="tituloCriarJuncaoModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" align="center" id="tituloCriarJuncaoModal">Criar junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyCriarJuncao" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editarJuncaoModel" tabindex="-1" role="dialog" aria-labelledby="tituloEditarJuncaoModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 30%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEditarJuncaoModal">Editar junção</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalBodyEditarJuncao" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script language="javascript">
function configurarCursosDisciplinas(curso_dropdown,disciplina_dropdown) {

	var tabela = document.getElementById("tabelaTurmasComponentes");
	var botaoAdicionar = document.getElementById("adicionarTurmaTemp");

	esconderTabela();
	
	//Limpar as opções anteriores
	removeOptions(document.getElementById('disciplina_dropdown'));
	
	//verificarExisteEntradaTabelaEsquerda();

	var curso_escolhido = curso_dropdown.value;
	
	console.log(curso_escolhido);
	
	if(curso_escolhido != "nada_selecionado"){
		$.ajax ({
			type: "POST",
			url: "phpUtil/verDisciplinasCursoEscolhido.php", 
			data: {curso: curso_escolhido},
			success: function(result) {
				disciplinas = result.split(",");
				console.log(disciplinas);
				createOption(disciplina_dropdown, "", "nada");
				for(i = 0; i < disciplinas.length; i++){
					createOption(disciplina_dropdown, disciplinas[i], disciplinas[i]);
				}
			}
		});
	}
	
    function createOption(curso_dropdown, text, value) {
        var opt = document.createElement('option');
        opt.value = value;
        opt.text = text;
        curso_dropdown.options.add(opt);
    }
	
	function removeOptions(selectElement) {
	   var i, L = selectElement.options.length - 1;
	   for(i = L; i >= 0; i--) {
		  selectElement.remove(i);
	   }
   }
   
   function mostrarTabela() {
	   tabela.style.visibility="visible";
	   botaoAdicionar.style.visibility="visible";
   }
   
   function esconderTabela() {
	   tabela.style.visibility="hidden";
	   tabela.innerHTML="";
	   botaoAdicionar.style.visibility="hidden";
   }
   
}

function processarUc() {
		
	var tabela = document.getElementById("tabelaTurmasComponentes");
	var botaoAdicionar = document.getElementById("adicionarTurmaTemp");
		
	var curso_escolhido = curso_dropdown.value;
	var disciplina_esc = disciplina_dropdown.value;
	console.log(curso_escolhido);
	console.log(disciplina_esc);
		
	esconderTabela();	
	
	if(disciplina_esc != "nada"){
		mostrarTabela();
		populacionarTabela();
	}
		
	function mostrarTabela() {
		tabela.style.visibility="visible";
		botaoAdicionar.style.visibility="visible";
	}
   
	function esconderTabela() {
		tabela.style.visibility="hidden";
		tabela.innerHTML="";
		botaoAdicionar.style.visibility="hidden";
	}
   
	function populacionarTabela(disciplina) {

		$.ajax ({
			type: "POST",
			url: "phpUtil/verTurmasDisciplina.php", 
			data: {uc: disciplina_esc , curso: curso_escolhido},
			success: function(result) {
				turmas = result.split(",");
				console.log(turmas);
				//console.log(disciplina_esc);
				
				$.ajax ({
					type: "POST",
					url: "processamento/verIdsTurmasDisciplina.php", 
					data: {uc: disciplina_esc , curso: curso_escolhido},
					success: function(result3) {
						ids_turmas = result3.split(",");	
				
						$.ajax ({
							type: "POST",
							url: "phpUtil/verComponentesDisciplina.php", 
							data: {uc: disciplina_esc , curso: curso_escolhido},
							success: function(result2) {
								componentes = result2.split(",");
								console.log(componentes);
								
								$.ajax ({
									type: "POST",
									url: "processamento/verIdsComponentesDisciplina.php", 
									data: {uc: disciplina_esc , curso: curso_escolhido},
									success: function(result4) {
										ids_componentes = result4.split(",");
										console.log(ids_componentes);
								
										criarTabela(turmas, ids_turmas, componentes, ids_componentes);
									}
								});
							}
						});
						
				//populacionarTurmas(turmas);
					}
				});
			}
		});
	   
	}

	function criarTabela(listaTurmas, listaIdTurmas, listaComponentes, listaIdComponentes) {
	   
		var tableBody = document.getElementById("tabelaTurmasComponentes").getElementsByTagName('tbody')[0];;
		var numTurmas = listaTurmas.length;
		var numComponentes = listaComponentes.length;
		//console.log(listaTurmas);
		//console.log(numComponentes);
		
		var cabecalho = tabela.insertRow();
		var turmas = cabecalho.insertCell(0);
		turmas.innerHTML = "Turmas";
		turmas.style.fontFamily = "Arial";
		for(i = 0; i < numComponentes; i++){
				var cell = cabecalho.insertCell(-1);
				cell.innerHTML = "Comp.";
				cell.style.fontFamily = "Arial";
		}
		
		for(i = 0; i < numTurmas; i++){
			
			//VERIFICAR SE ESTA TURMA JÁ TEM ALGUMA COMPONENTE ADICIONADA À TABELA DA ESQUERDA
			
			//VERIFICAR SE ALGUMA COMPONENTE/TURMA JÁ ESTÁ NUMA JUNÇÃO
	
			var entrada = tabela.insertRow();
			var cell0 = entrada.insertCell(-1);
			cell0.innerHTML = "<div id='TURMA_POS:" + i + "' data-id_turma='" + listaIdTurmas[i] + "'><b>" + listaTurmas[i] + "</b></div>";	
			
			for(j = 0; j < numComponentes; j++){
				var cell1 = entrada.insertCell(-1);
				cell1.innerHTML = "<div id='COMP_POS:" + j + "' data-id_comp='" + listaIdComponentes[j] + "'><input type='checkbox' align='left' onclick='bloquearOutrasComponentes(" + i + "," + j + ")'>" + "  <text>" + listaComponentes[j] + "</text>";
				//console.log(listaTurmas[i] + "_" + listaComponentes[j]);
				cell1.style.background = "rgb(245,245,245)";
				$.ajax ({
					type: "POST",
					url: "processamento/verificarJaEstaNumaJuncao.php", 
					data: {idTurma: listaIdTurmas[i], idComp: listaIdComponentes[j]},
					success: function(result) {
						if(result == 1){
							alert("TESTE: " + i + " " + j);
						}
					}
				});
			}
			
		}
	}

}

function bloquearOutrasComponentes(x,y) {
		
	console.log("LIMPAR");
	//alert("Linha: " + x + " / Coluna: " + y);
		
	var tabela = document.getElementById("tabelaTurmasComponentes"); 
	var numRows = document.getElementById("tabelaTurmasComponentes").rows.length;
	var numColunas = document.getElementById("tabelaTurmasComponentes").rows[0].cells.length;
	var count = 0;
	
	for(i = 1; i < numRows; i++){
		
		for(j = 1; j < numColunas; j++){
			
			//alert(tabela.rows[i].cells[j].innerHTML);
			
			if(i - 1 == x && j - 1 == y){
				//alert("i: " + (i-1) + " x : " + x + " / j: " + (j-1) + " y: " + y);
				var comp = tabela.rows[i].cells[j].getInnerHTML();
				const comp_final = /<text>(.*?)<\/text>/g.exec(comp);
				
				if(tabela.rows[i].cells[j].getElementsByTagName('input')[0].checked==true){
					//alert("SELECIONADO");
					
					for(j = 1; j < numColunas; j++){
						var comp2 = tabela.rows[i].cells[j].getInnerHTML();
						const comp_final2 = /<text>(.*?)<\/text>/g.exec(comp2);
						if(comp_final2[1] != comp_final[1]){
							//alert("Row: " + (i - 1) + " / Col: " + (j - 1) + " tem comp. diferente!");
							tabela.rows[i].cells[j].getElementsByTagName('input')[0].style.visibility="hidden";
						}
					}
				}
				else{
					//alert("DESCELECIONADO: " + comp_final[1]);
					
					var contagem = 0;
					
					for(k = 1; k < numColunas; k++){
						if(tabela.rows[i].cells[k].getElementsByTagName('input')[0].checked==true){
							contagem = contagem + 1;
						}
					}
					
					if(contagem >= 1){
						//alert("MAIS QUE UM SELECIONADO");
					}
					else{
						for(k = 1; k < numColunas; k++){
							if(tabela.rows[i].cells[k].getElementsByTagName('input')[0].checked==false){
								//alert("Row: " + (i - 1) + " / Col: " + (k - 1) + " VOLTAR!");
								tabela.rows[i].cells[k].getElementsByTagName('input')[0].style.visibility="visible";
							}
						}
					}
					
					for(g = 1; g < numColunas; g++){
						var comp4 = tabela.rows[i].cells[g].getInnerHTML();
						const comp_final4 = /<text>(.*?)<\/text>/g.exec(comp4);
						if(comp_final4[1] == comp_final[1] && contagem == 1){
							tabela.rows[i].cells[g].getElementsByTagName('input')[0].style.visibility="visible";
						}
					}
					
					var aindaTem = false;

				}
				
			}

		}
				
	}

}
	
function adicionarTurmaTemporaria(turma, id_turma, nomeComp, id_comp) {
	
	$('#cover-spin').show(0);
	
	var tabelaEsquerda = document.getElementById("tabelaEsquerda");
	var tabela = document.getElementById("tabelaTurmasComponentes"); 
	var numRows = document.getElementById("tabelaTurmasComponentes").rows.length;
	var curso_escolhido = curso_dropdown.value;
	var disciplina_esc = disciplina_dropdown.value;
	
	var teste = tabelaEsquerda.insertRow();
	var curso_cell = teste.insertCell();
	
	//VER O Nº DE QUADRADINHOS CLICADOS, ESTE TEM DE SER O Nº DE COLUNAS A ADICIONAR NA COLUNA DA ESQUERDA
	console.log("ADICIONAR TURMA");
	
	var turma_cell = teste.insertCell();
	turma_cell.innerHTML = "<b data-id_turma_esq='" + id_turma + "' data-id_comp_esq='" + id_comp + "'><text>" + turma + "</text></b>";
	
	$.ajax ({
		type: "POST",
			url: "phpUtil/verCursoEscolhido.php", 
				data: {curso: curso_escolhido},
				success: function(result) {
					cur = result.split(",");
					curso_cell.innerHTML = cur;
					
					var anoSem_cell = teste.insertCell(-1);
					
					$.ajax ({
						type: "POST",
						url: "phpUtil/verAnoSemDisciplina.php", 
						data: {disciplina: disciplina_esc},
						success: function(result) {
							a_s = result.split(",");
							console.log(a_s);
							anoSem_cell.innerHTML = a_s;
							
							var uc_cell = teste.insertCell(-1);
							
							$.ajax ({
								type: "POST",
								url: "phpUtil/verSiglaUC.php", 
								data: {disciplina: disciplina_esc},
								success: function(result) {
									sigla = result;
									uc_cell.innerHTML = sigla;
									
									var comp_cell = teste.insertCell(-1);
									comp_cell.innerHTML = "<text>" + nomeComp + "</text>"; 
									
									var apagar_cell = teste.insertCell(-1);
									apagar_cell.innerHTML = "<td class='noBorder'><img src='apagar_row2.jpg' class='lixo' onclick='apagarLinha(this)' width='30' height='30'></td>";
									apagar_cell.width="30";
									verificarErro2();
																			
								}
							});
								
						}
					});
					
				}
	});
	
}

function apagarLinha(linha) {

	$('#cover-spin').show(0);

	setTimeout(function(){
	var p = linha.parentNode.parentNode;
    p.parentNode.removeChild(p);
	verificarErro2();
	}
		,500);

}

//ERRO 1 - Verificar se ao tentar adicionar uma componente à tabela da esquerda
//		   o utilizador selecionou pelo menos uma componente na tabela da direita
function verificarErro1() {
	
	var tabela = document.getElementById("tabelaTurmasComponentes"); 
	var botaoAdicionar = document.getElementById("adicionarTurmaTemp"); 
	var numRows = document.getElementById("tabelaTurmasComponentes").rows.length;
	var numColunas = document.getElementById("tabelaTurmasComponentes").rows[0].cells.length;
	var count = 0;
	
	//alert("ROWS: " + numRows + " COL: " + numColunas);
	
		for(i = 1; i < numRows; i++){
			
			for(j = 1; j < numColunas; j++){
			
				//alert(tabela.rows[i].cells[j].innerHTML);
				
				if(tabela.rows[i].cells[j].getElementsByTagName('input')[0].checked==true){
					var comp = tabela.rows[i].cells[j].getInnerHTML();
					var comp_final = /<text>(.*?)<\/text>/g.exec(comp);
					var comp_element = document.getElementById("COMP_POS:" + (j-1));;
					var id_comp_final = comp_element.getAttribute('data-id_comp');
					
					var turma = tabela.rows[i].cells[0].getInnerHTML().trim();
					const turma_final = /<b>(.*?)<\/b>/g.exec(turma);
					var turma_element = document.getElementById("TURMA_POS:" + (i-1));
					var id_turma_final = turma_element.getAttribute('data-id_turma');
					//alert("Hola: " + i + "/" + j + "<br>" + comp_final);
					//alert("Turma:" + turma_final[1] + " ID:" + id_turma_final);
					//alert("Comp:" + comp_final[1].trim() + " ID:" + id_comp_final.trim()); 
					adicionarTurmaTemporaria(turma_final[1], id_turma_final, comp_final[1], id_comp_final);
					count = count + 1;
				}
			
			}
				
		}
		if (count == 0){
			corrigirErro1();
		}
		else{
			tabela.style.visibility="hidden";
			tabela.innerHTML="";
			botaoAdicionar.style.visibility="hidden";
		}
	
}

function corrigirErro1() {
	
	window.alert("Selecione uma componente!");
	
	console.log("ERRO 1");
	
}

//ERRO 2 - Junção de componentes diferentes
function verificarErro2() {

	$('#cover-spin').hide();

	var tabela = document.getElementById("tabelaEsquerda"); 
	var numRows = document.getElementById("tabelaEsquerda").rows.length;
	var numColunas = document.getElementById("tabelaEsquerda").rows[0].cells.length;

	var comp_original = "";
	
	var div_erro2 = document.getElementById("erro2img_msg");
	
	comp_original = tabela.rows[1].cells[4].getInnerHTML();
	var num_erros = 0;
	for(i = 1; i < numRows; i++){
		
		var comp = tabela.rows[i].cells[4].getInnerHTML();
		
		//VERIFICAR SE O ORIGINAL(LINHA 1) ESTÁ SUBLINHADO, SE ESTIVER MUDAR PARA NORMAL
		if(comp_original.indexOf("img") != -1){
			const final = /<b>(.*?)<\/b>/g.exec(comp_original);
			tabela.rows[1].cells[4].innerHTML = final[1];
			
			//E VERIFICAR SE OUTRAS ENTRADAS JÁ ESTÃO BEM
			const temp = /<b>(.*?)<\/b>/g.exec(comp);
			if(i > 1 && temp != null && temp[1] == final[1]){
				tabela.rows[i].cells[4].innerHTML = temp[1];
			}
		}

		//Verificar as outras linhas
		if(comp != comp_original){
			num_erros = num_erros + 1;
		}
		if(comp != comp_original && (comp.indexOf("img") == -1)){
			div_erro2.style.visibility = "visible";
			tabela.rows[i].cells[4].innerHTML = "<b>" + comp + "</b>" + " " +  "<img src='erro2.jpg' width='15' height='15'>";
		}
				
	}
	$('#cover-spin').hide(0);
	//alert("ERROS: " + num_erros);
	if(num_erros >= 1){
		div_erro2.style.visibility = "visible";
	}
	else{
		div_erro2.style.visibility = "hidden";
	}
	
}

function criarJuncao() {
	
	var nomeJuncao = document.getElementById("nomeJuncao").value.trim();
	var tabela = document.getElementById("tabelaEsquerda"); 
	var numRows = document.getElementById("tabelaEsquerda").rows.length;
	var numColunas = document.getElementById("tabelaEsquerda").rows[0].cells.length;
	
	var count = 0;
	$('#tabelaEsquerda').find('tr').each(function () {   
		var row = $(this);
		if (row.find()) {
			//found=true;
			count = count + 1;
			console.log("Nº ROWS:" + count);
			// Do your job here
		}
		else{
			console.log("Nº ROWS: " + count);
			//alert "NOthing found"
			//found=false;
		};
	});
	
	if(nomeJuncao.length == 0){
		window.alert("Introduza um nome para a junção!");
		document.getElementById("nomeJuncao").focus();
	}
	else if(nomeJuncao.length < 5){
		window.alert("Introduza um nome válido! (5 caracteres)");
		document.getElementById("nomeJuncao").focus();
	}
	else if (count == 1 || count == 2){
		window.alert("Junção tem de ter pelo menos duas componentes!");
		document.getElementById("tabelaEsquerda").focus();
	}
	else{
		alert("TOTAL: " + count);
		
		var componentes_lista = [];
		var turmas_lista = [];
		
		//Ver componentes
		for(i = 1; i < numRows; i++){
			
			curso_original = tabela.rows[i].cells[0].getInnerHTML();
			turma_original = tabela.rows[i].cells[1].getInnerHTML();
			const turma_final = /<text>(.*?)<\/text>/g.exec(turma_original);
			ano_sem_original = tabela.rows[i].cells[2].getInnerHTML();
			uc_original = tabela.rows[i].cells[3].getInnerHTML();
			comp_original = tabela.rows[i].cells[4].getInnerHTML();
			const comp_final = /<text>(.*?)<\/text>/g.exec(comp_original);
		
			//alert("Curso: " + curso_original + " Turma: " + turma_final[1] + " A/S: " + ano_sem_original + " UC: " + uc_original + " Comp: " + comp_final[1].trim());
			
			//Ir procurar o ID da turma
			var turma_final_id = /data-id_turma_esq="(.*?)"/.exec(turma_original);
			turmas_lista.push(turma_final_id[1]);  
			//alert("TURMA_REGEX: " + turma_final_id[1]);
			
			//Ir procurar o ID deste componente
			var comp_final_id = /data-id_comp_esq="(.*?)"/.exec(turma_original);
			componentes_lista.push(comp_final_id[1]);
			//alert("COMP_REGEX: " + comp_final_id[1]);
		}
		
		var componentes_json = JSON.stringify(componentes_lista);
		var turmas_json = JSON.stringify(turmas_lista);
		
		$.ajax ({
			type: "POST",
			url: "processamento/processarFormCriarJuncao.php", 
			data: {nome_juncao: nomeJuncao, componentes: componentes_json, turmas: turmas_json},
			success: function(result) {
				alert("Junção criada com sucesso!" + result);
				location.reload();
			}
		});
		
		/*
		var componentes_lista = ['1','7'];
		var turmas_lista = ['1','2','3','4'];
		var componentes_json = JSON.stringify(componentes_lista);
		var turmas_json = JSON.stringify(turmas_lista);
		
		$.ajax ({
			type: "POST",
			url: "processamento/processarFormCriarJuncao.php", 
			data: {nome_juncao: nomeJuncao, componentes: componentes_json, turmas: turmas_json},
			success: function(result) {
				alert("Junção criada com sucesso!" + result);
				location.reload();
			}
		});
		*/
	}
	
}

function removerJuncao(idJuncao) {
	
	var confirm = window.confirm("Pretende remover esta junção?");
	
	if(confirm == true){
		console.log("APAGAR");
		
		$.ajax ({
			type: "POST",
			url: "processamento/removerJuncaoNOVA.php", 
			data: {id: idJuncao},
				success: function(result) {
					window.alert(result);
					console.log(result);
					location.reload();
				}
		});		
		
	}
	else{
		console.log("NÃO APAGAR");
	}
	
}
</script>


<?php gerarHome2() ?>
