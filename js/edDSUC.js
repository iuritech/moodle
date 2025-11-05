function gerarFormAdicionarDocente(idArea) {
    var xhttp;    
    //alert("Cheguei aqui (docente!");
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyAdicionarDocente").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formAdicionarDocente.php?idArea=" + idArea, true);
    xhttp.send();
	
	var dropdown_docentes = document.getElementById("docentes_dropdown");
	var tabela = document.getElementById("tabelaDSUC");
	var numRows = tabela.rows.length;
	var ids_ja_na_tabela = [];
	
	for (i = 1; i < numRows; i++){
		if(document.getElementById('docente_div_' + i)){
			var id_div = document.getElementById('docente_div_' + i);
			//alert("ID já na tabela: " + id_div.dataset.id_docente);
			ids_ja_na_tabela.push(id_div.dataset.id_docente);
		}
	}
	
	
	$(document).on('show.bs.modal', '#adicionarDocenteModal', function (e) {
		 $('#id_area').val(1);
		 $('#ids_ja').val(ids_ja_na_tabela);
	});
	
	
	//alert("Docentes já na tabela: " + ids_ja_na_tabela.length);
	
	if(ids_ja_na_tabela.length != 0){
		
		/*
		//Adicionar a opção vazia
		var opt = document.createElement('option');
		opt.value = "nada_selecionado";
		opt.text = "";
		dropdown_docentes.options.add(opt);
		*/
		
		//Mostrar a lista de docentes
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarListaDocentes.php", 
			data: {ids_ja: ids_ja_na_tabela},
			success: function(result) {

				var array = result.split(',');
				//alert("DOCENTES: " + array.length);
				
				$.ajax ({
				type: "POST",
				url: "processamento/mostrarListaIdsDocentes.php", 
				data: {ids_ja: ids_ja_na_tabela},
				success: function(result) {
					var array_ids = result.split(',');
					//alert("ID_S DOCENTES: " + array_ids.length);
					
					for(i = 0; i <= array.length; i++){
						var opt = document.createElement('option');
						opt.value = array_ids[i];
						opt.text = array[i];
						dropdown_docentes.options.add(opt);
					}
					
				}
				});
				
			}
		});
		
	}
	
	else{
		/*
		//Adicionar a opção vazia
		var opt = document.createElement('option');
		opt.value = "nada_selecionado";
		opt.text = "";
		dropdown_docentes.options.add(opt);
		*/
		//Mostrar a lista de docentes
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarListaDocentesCompleta.php", 
			data: {},
			success: function(result) {
				var array = result.split(',');
				//alert("DOCENTES: " + array.length);
				
				
				$.ajax ({
					type: "POST",
					url: "processamento/mostrarListaIdsDocentesCompleta.php", 
					data: {},
					success: function(result) {
						var array_ids = result.split(',');
						//alert("ID_S DOCENTES: " + array_ids.length);
						
						for(i = 0; i <= array.length; i++){
							var opt = document.createElement('option');
							opt.value = array_ids[i];
							opt.text = array[i];
							dropdown_docentes.options.add(opt);
						}
						
					}
				});
				
			}
		});
		
	}
	
}

function verDocentesJaNaTabela() {
	alert("TEste123");
}

$(document).on('show.bs.modal', '#adicionarDocenteModal', function (e) {
    alert('works');
});

function adicionarDocente() {
	//alert("Adicionar docente");
	var dropdown_docentes = document.getElementById("dropdown_docentes");
	dropdown_docentes.style.visibility = "visible";
	
	var tabela = document.getElementById("tabelaDSUC");
	
	var numRows = tabela.rows.length;
	
	var ids_ja_na_tabela = [];
	
	for (i = 1; i < numRows; i++){
		if(document.getElementById('docente_div_' + i)){
			var id_div = document.getElementById('docente_div_' + i);
			alert("ID já na tabela: " + id_div.dataset.id_docente);
			ids_ja_na_tabela.push(id_div.dataset.id_docente);
		}
	}
	
	if(ids_ja_na_tabela.length != 0){
	
		if(dropdown_docentes.length == 0) {
		
		//Adicionar a opção vazia
		var opt = document.createElement('option');
		opt.value = "nada_selecionado";
		opt.text = "";
		dropdown_docentes.options.add(opt);
		
		//Mostrar a lista de docentes
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarListaDocentes.php", 
			data: {ids_ja: ids_ja_na_tabela},
			success: function(result) {

				var array = result.split(',');
				alert("DOCENTES: " + array.length);
				
				
				$.ajax ({
				type: "POST",
				url: "processamento/mostrarListaIdsDocentes.php", 
				data: {ids_ja: ids_ja_na_tabela},
				success: function(result) {
					var array_ids = result.split(',');
					alert("ID_S DOCENTES: " + array_ids.length);
					
					for(i = 0; i <= array.length; i++){
						var opt = document.createElement('option');
						opt.value = array_ids[i];
						opt.text = array[i];
						dropdown_docentes.options.add(opt);
					}
					
				}
				});
				
			}
		});
		
		}
		/*
		for(i = 0; i <= 30; i++){
			var opt = document.createElement('option');
			opt.value = "teste";
			opt.text = "Isto e um teste muito longo!!!";
			dropdown_docentes.options.add(opt);
		}  */
	}
	else{
		
		if(dropdown_docentes.length == 0) {
		//alert("Não há ninguém na tabela!");
		
		//Adicionar a opção vazia
		var opt = document.createElement('option');
		opt.value = "nada_selecionado";
		opt.text = "";
		dropdown_docentes.options.add(opt);
		
		//Mostrar a lista de docentes
		$.ajax ({
			type: "POST",
			url: "processamento/mostrarListaDocentesCompleta.php", 
			data: {},
			success: function(result) {
				var array = result.split(',');
				alert("DOCENTES: " + array.length);
				
				
				$.ajax ({
					type: "POST",
					url: "processamento/mostrarListaIdsDocentesCompleta.php", 
					data: {},
					success: function(result) {
						var array_ids = result.split(',');
						alert("ID_S DOCENTES: " + array_ids.length);
						
						for(i = 0; i <= array.length; i++){
							var opt = document.createElement('option');
							opt.value = array_ids[i];
							opt.text = array[i];
							dropdown_docentes.options.add(opt);
						}
						
					}
				});
				
			}
		});
		}
	}
	
}

function adicionarDocenteFinal(id){
$('#cover-spin').show(0);
	//alert("Adicionar docente: " + id);
	
	var tabelaDSUC = document.getElementById("tabelaDSUC");
	var numRows = tabelaDSUC.rows.length;
	var numComponentes = tabelaDSUC.rows[0].cells.length - 1;
	
	var novaRow = tabelaDSUC.insertRow(-1);
	var cellDocente = novaRow.insertCell();
	
	$.ajax ({
		type: "POST",
		url: "processamento/verNomeImgUtilizador.php", 
		data: {id: id},
		success: function(result) {
			var nome = /(.*?),/.exec(result);
			var imagem = result.substr(result.indexOf(",") + 1);
			//alert("Nome: " + nome[1] + " Imagem: " + imagem);
			cellDocente.innerHTML = "<div id='docente_div_" + Number(id) + "' data-id_docente='" + Number(id) + "'><a onclick='mudarDocente()' style='cursor:pointer;'><img src='" + imagem + "' style='width:35px; heigh:35px; border-radius: 50%;'> <b>" + nome[1] + "</b></a>" + 
							"<a href='#' onclick='removerRow(" + Number(id) + ")'><i class='material-icons' style='font-size: 20px; cursor: pointer; color: #ff2424; width: 10px; heigh: 10px; margin-left: 3px; line-height:50%; vertical-align: middle;'>remove_circle_outline</i></a></div>";
			for(i = 1; i <= numComponentes; i++){
				var cellComponente = novaRow.insertCell();
				cellComponente.innerHTML = "";
			}
			
			$('#adicionarDocenteModal').modal('hide');
			
			setTimeout(function(){;
			$('#cover-spin').hide();
			}
			,500);
		}
	});
	/*
	for(i = 1; i <= numComponentes; i++){
		var cellComponente = novaRow.insertCell();
		cellComponente.innerHTML = "TESTECOMP";
	} */
}

function mudarDocente(id){
	alert("Mudar docente: " + id);
		
}

function removerRow(idDocente){
	$('#cover-spin').show(0);
/*	alert("REMOVER DOCENTE COM ID: " + idDocente); 
	var p = row.parentNode.parentNode;
	p.parentNode.removeChild(p); */
	var div = document.getElementById("docente_div_" + idDocente);
	$('#docente_div_' + idDocente).closest('tr').remove(); 
	//document.getElementsByTagName("tr")[row].remove();
	setTimeout(function(){;
	$('#cover-spin').hide();
	}
		,500); 
}

function gerarFormAdicionarTurma(contador_vertical,contador_horizontal, idComponente) {
    var xhttp;    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyAdicionarTurma").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formAdicionarTurma.php?vertical=" + contador_vertical + "&horizontal=" + contador_horizontal + "&idComp=" + idComponente, true);
    xhttp.send();
}

function mostrarListaTurmas(vertical, horizontal, comp){
	alert("Mostrar turmas: " + vertical + " horizontal: " + horizontal);
	
	var tabela = document.getElementById("tabelaDSUC");
	var numComponentes = tabela.rows[0].cells.length - 1;
	
	var dropdown_curso = document.getElementById("curso_dropdown");
	var id_curso_escolhido = dropdown_curso.value;
	
	var dropdown_turmas = document.getElementById("turmas_dropdown");
	
	//Limpar as opções anteriores
	var i, L = dropdown_turmas.options.length - 1;
		for(i = L; i >= 0; i--) {
			dropdown_turmas.remove(i);
	}
	
	var opt = document.createElement('option');
	opt.value = "nada_selecionado";
	opt.text = "";
	dropdown_turmas.options.add(opt);
	
	//Ir buscar os ID's das turmas que já estão na table cell
	
	//Nº turmas
	var div_contador = document.getElementById("contador_" + comp);
	var num_turmas = div_contador.getAttribute("data_num-turmas");
	if(!num_turmas){
		num_turmas = 0;
	}
	alert("Nº turmas: " + num_turmas);
	
	if(num_turmas != 0){
		var turmas_ja_na_tabela = [];
		var id_max = 0;
		//Ir buscar o ID máximo de uma turma para depois poder is buscar o div da turma na table cell
		$.ajax ({
			type: "POST",
			url: "processamento/verIdsTurmaMax.php", 
			data: {},
			success: function(result) {
				id_max = result;
				
				for(j = 1; j <= id_max; j++){
					if(document.getElementById("td_turmas_" + comp + "_" + j)){
						alert("Turma: " + j);
						var div_turma = document.getElementById("td_turmas_" + comp + "_" + j);
						var id_turma = div_turma.getAttribute("data-id_turma");
						turmas_ja_na_tabela.push(id_turma);
					}
				}
				
				
				//Ir buscar uma lista com todos os ID's das turmas excepto os do array "turmas_ja_na_tabela[]"
				$.ajax ({
					type: "POST",
					url: "processamento/verIdsTurmasRestantes.php", 
					data: {ids_ja_na_tabela: turmas_ja_na_tabela, id_curso: id_curso_escolhido},
					success: function(result) {
						var array_ids_restantes = result.split(',');
						alert("Turmas restantes: " + result);
						
						
						//Ir buscar a mesma lista só que com os nomes das turmas
						$.ajax ({
							type: "POST",
							url: "processamento/verNomesTurmasRestantes.php", 
							data: {ids_ja_na_tabela: turmas_ja_na_tabela, id_curso: id_curso_escolhido},
							success: function(result) {
								var array_nomes_restantes = result.split(',');
								alert("Turmas restantes: " + result);
								
								for(i = 0; i < array_nomes_restantes.length; i++){
									var opt = document.createElement('option');
									opt.value = array_ids_restantes[i];
									opt.text = array_nomes_restantes[i];
									dropdown_turmas.options.add(opt);
								}
							}
						});
						
					}
				});
				
			}
		});
	}
	else{
		//Ir buscar uma lista com todos os ID's das turmas excepto os do array "turmas_ja_na_tabela[]"
			$.ajax ({
				type: "POST",
				url: "processamento/verIdsTurmas_Curso.php", 
				data: {id_curso: id_curso_escolhido},
				success: function(result) {
					var array_ids_restantes = result.split(',');
					alert("Turmas restantes: " + result);
												
					//Ir buscar a mesma lista só que com os nomes das turmas
					$.ajax ({
						type: "POST",
						url: "processamento/verNomesTurmas_Curso.php", 
						data: {id_curso: id_curso_escolhido},
						success: function(result) {
							var array_nomes_restantes = result.split(',');
							alert("Turmas restantes: " + result);
								
							for(i = 0; i < array_nomes_restantes.length; i++){
								var opt = document.createElement('option');
								opt.value = array_ids_restantes[i];
								opt.text = array_nomes_restantes[i];
								dropdown_turmas.options.add(opt);
							}
						}
					});
					
				}
			});
	}
	
}

function adicionarTurma(vertical, horizontal, comp){
	
	var dropdown_turma = document.getElementById("turmas_dropdown");
	var id_turma_escolhida = dropdown_turma.value;
	
	alert("Adicionar turma " + id_turma_escolhida + " à componente " + comp + " VERT: " + vertical + " HORIZ: " + horizontal);
	
	//Ir ver a table cell para adicionar a turma
	var tabela = document.getElementById("tabelaDSUC");
	var numRows = tabela.rows.length;
	var numComponentes = tabela.rows[0].cells.length - 1;
	
	for(i = 1; i < numRows; i++){
	
		for(j = 1; j <= numComponentes; j++) {
				
			alert("Row: " + i + " Col: " + j + " " + tabela.rows[i].cells[j].innerHTML);
				
		}
		
	}
	
}

function removerTurma(comp, i){
	$('#cover-spin').show(0);
	alert("Remover turma div: " + comp + " " + i);
	document.getElementById("td_turmas_" + comp + "_" + i).remove();
	
	//Atualizar o contador de turmas
	var div_contador = document.getElementById("contador_" + comp);
	var num_turmas_antigo = div_contador.getAttribute("data_num-turmas");
	div_contador.setAttribute("data_num-turmas", (num_turmas_antigo - 1));
	
	setTimeout(function(){;
	$('#cover-spin').hide();
	}
		,500);
}

function configurarDisciplinas(comp) {
	
	//alert("Cheguei aqui");
	
	var dropdown_curso = document.getElementById("curso_dropdown");
	var id_curso_escolhido = dropdown_curso.value;
	
	var dropdown_turmas = document.getElementById("turmas_dropdown");
	
	//Limpar as opções anteriores
	var i, L = dropdown_turmas.options.length - 1;
	for(i = L; i >= 0; i--) {
		dropdown_turmas.remove(i);
	}
	
	//Adicionar a opção vazia
	var opt = document.createElement('option');
	opt.value = "nada_selecionado";
	opt.text = "";
	dropdown_turmas.options.add(opt);
	
	alert("ID Curso escolhido: " + id_curso_escolhido);
	
	var turmas_ja_na_tabela = [];
	var id_max = 0;
	//Ir buscar o ID máximo de uma turma para depois poder is buscar o div da turma na table cell
	$.ajax ({
		type: "POST",
		url: "processamento/verIdsTurmaMax.php", 
		data: {},
		success: function(result) {
			id_max = result;
			
			for(j = 1; j <= id_max; j++){
				if(document.getElementById("td_turmas_" + 2 + "_" + j) != null){
					alert("Turma: " + j);
					var div_turma = document.getElementById("td_turmas_" + 2 + "_" + j);
					var id_turma = div_turma.getAttribute("data-id_turma");
					turmas_ja_na_tabela.push(id_turma);
				}
			}
			
			
			//Ir buscar uma lista com todos os ID's das turmas excepto os do array "turmas_ja_na_tabela[]"
			$.ajax ({
				type: "POST",
				url: "processamento/verIdsTurmasRestantes.php", 
				data: {ids_ja_na_tabela: turmas_ja_na_tabela},
				success: function(result) {
					var array_ids_restantes = result.split(',');
					alert("Turmas restantes: " + result);
					
					
					//Ir buscar a mesma lista só que com os nomes das turmas
					$.ajax ({
						type: "POST",
						url: "processamento/verNomesTurmasRestantes.php", 
						data: {ids_ja_na_tabela: turmas_ja_na_tabela, id_curso: id_curso_escolhido},
						success: function(result2) {
							var array_nomes_restantes = result2.split(',');
							alert("Turmas restantes: " + result2);
							
							for(i = 0; i < array_nomes_restantes.length; i++){
								var opt = document.createElement('option');
								opt.value = array_ids_restantes[i];
								opt.text = array_nomes_restantes[i];
								dropdown_turmas.options.add(opt);
							}
						}
					});
					
				}
			});
			
		}
	});
		
}
/*
function submeterFormAtribuirComponenteBloco() {
    var bloco = $("#bloco").val();
    var componente = $("#componente").val();
    var arrayTurmas = [];
    
    var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
    for (var i = 0; i < checkboxes.length; i++) {
        arrayTurmas.push(checkboxes[i].value);
    }
    var jsonStringTurmas = JSON.stringify(arrayTurmas);
    
    
    $.ajax({
        type: "POST",
        data: {bloco: bloco, componente: componente, arrayTurmas: jsonStringTurmas},
        url: "processamento/processarFormAtribuirComponenteBloco.php",
        async: false,
        success: function (msg) {
            $('#adicionarComponenteModal').modal('hide');
            var disciplina = findGetParameter("i");
            gerarFormEditarJuncao(bloco, disciplina);
        }
    });
}

function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    var items = location.search.substr(1).split("&");
    for (var index = 0; index < items.length; index++) {
        tmp = items[index].split("=");
        if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
    }
    return result;
}

function refresh() {    
    setTimeout(function () {
        location.reload()
    }, 100);
}

function gerarFormAtribuirComponenteBloco(idBloco, idDisciplina) {
    var xhttp;    
    if (idBloco == "" || idDisciplina == "") {
      document.getElementById("modalBodyAdicionarComponente").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyAdicionarComponente").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formAtribuirComponenteBloco.php?i="+idBloco+"&disc="+idDisciplina, true);
    xhttp.send();
}

function gerarSelectAno(idCurso, idBloco) {
    var xhttp;    
    if (idCurso == "" || idBloco == "") {
      document.getElementById("selectAno").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("selectAno").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/adicaoComponente/selectAno.php?curso="+idCurso+"&bloco="+idBloco, true);
    xhttp.send();
}

function gerarSelectDisciplina(idCurso, ano, idBloco) {
    var xhttp;
    if (idCurso == "" || ano == "") {
      document.getElementById("selectDisciplina").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("selectDisciplina").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/adicaoComponente/selectDisciplina.php?curso="+idCurso+"&ano="+ano+"&bloco="+idBloco, true);
    xhttp.send();
}

function gerarSelectComponente(idDisciplina, idBloco) {
    var xhttp;
    if (idDisciplina == "") {
      document.getElementById("selectComponente").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("selectComponente").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/adicaoComponente/selectComponente.php?disc="+idDisciplina+"&bloco="+idBloco, true);
    xhttp.send();
}

function gerarCheckTurmas(idComponente) {
    var xhttp;
    if (idComponente == "") {
      document.getElementById("checkTurmas").innerHTML = "";
      return;
    }
    var idBloco = $("#bloco").val();
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("checkTurmas").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/adicaoComponente/checkTurmas.php?comp="+idComponente+"&bloco="+idBloco, true);
    xhttp.send();
}

function gerarBotaoSubmit(visivel) {
    var xhttp;
    if (visivel == "") {
      document.getElementById("botaoSubmit").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("botaoSubmit").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/adicaoComponente/botaoSubmit.php", true);
    xhttp.send();
}

function adicionarTurma(idBloco, idTurma, idDisciplina) {
    var xhttp;    
    if (idBloco == "" || idTurma == "" || idDisciplina == "") {
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
            if(xhttp.status === 200) {
                alert('Turma adicionada');
            } else {
                alert('Código Erro: ' +  xhttp.status);
                alert('Mensagem Erro: ' + xhttp.statusText);
            }
        }
    }
    xhttp.open("GET", "processamento/atribuirTurmaBloco.php?bloco="+idBloco+"&turma="+idTurma, true);
    xhttp.send();

    gerarFormEditarJuncao(idBloco, idDisciplina);
}

function removerTurma(idBloco, idTurma, idDisciplina) {
    var xhttp;    
    if (idBloco == "" || idTurma == "" || idDisciplina == "") {
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
            if(xhttp.status === 200) {
                alert('Turma removida');
            } else {
                alert('Código Erro: ' +  xhttp.status);
                alert('Mensagem Erro: ' + xhttp.statusText);
            }
        }
    }
    xhttp.open("GET", "processamento/removerTurmaBloco.php?bloco="+idBloco+"&turma="+idTurma, true);
    xhttp.send();

    gerarFormEditarJuncao(idBloco, idDisciplina);
}

function removerComponente(idComponente, idDisciplina, idBloco) {
    var xhttp;    
    if (idComponente == "" || idDisciplina == "" || idBloco == "") {
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
            if(xhttp.status === 200) {
                var jsonResponse = xhttp.responseText;
                if (jsonResponse === "remover"){
                    refresh();
                    return;
                }
                alert('Turmas removidas');
                
                
            } else {
                alert('Código Erro: ' +  xhttp.status);
                alert('Mensagem Erro: ' + xhttp.statusText);
            }
        }
    }
    xhttp.open("GET", "processamento/removerComponenteBloco.php?bloco="+idBloco+"&componente="+idComponente, true);
    xhttp.send();
    
    gerarFormEditarJuncao(idBloco, idDisciplina);
}

function removerBloco(idBloco) {
    var xhttp;    
    if (idBloco == "") {
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
            if(xhttp.status === 200) {
                refresh();
            } else {
                alert('Código Erro: ' +  xhttp.status);
                alert('Mensagem Erro: ' + xhttp.statusText);
            }
        }
    }
    xhttp.open("GET", "processamento/removerBloco.php?bloco="+idBloco, true);
    xhttp.send();
}

function removerJuncao(idJuncao) {
    var xhttp;    
    if (idJuncao == "") {
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
            if(xhttp.status === 200) {
                refresh();
            } else {
                alert('Código Erro: ' +  xhttp.status);
                alert('Mensagem Erro: ' + xhttp.statusText);
            }
        }
    }
    xhttp.open("GET", "processamento/removerJuncao.php?juncao="+idJuncao, true);
    xhttp.send();
}
*/