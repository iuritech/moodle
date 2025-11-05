function gerarFormCriarCurso() {
    var xhttp;    
    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyCriarCurso").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formCriarCurso.php", true);
    xhttp.send();
}

function gerarFormEditarCurso(id) {
    var xhttp;    
    if (id == "") {
      document.getElementById("modalBodyEditarCurso").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarCurso").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formEditarCurso.php?i="+id, true);
    xhttp.send();
}

function gerarFormGerirUTCs(idCurso) {
    var xhttp;    
    if (idCurso == "") {
      document.getElementById("modalContentGerirUTCs").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalContentGerirUTCs").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/gestaoUTCCurso/tabelaUTCs.php?curso="+idCurso, true);
    xhttp.send();
}

function gerarFormAtribuirUTCCurso(idCurso) {
    var xhttp;    
    if (idCurso == "") {
      document.getElementById("modalBodyAtribuirUTCCurso").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyAtribuirUTCCurso").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/gestaoUTCCurso/formAtribuirUTCCurso.php?curso="+idCurso, true);
    xhttp.send();
}

function submeterFormAtribuirUTCCurso() {
    var curso = $("#curso").val();
    var utc = $("#utc").val();
    
    $.ajax({
        type: "POST",
        data: {curso: curso, utc: utc},
        url: "processamento/processarFormAtribuirUTCCurso.php",
        async: false,
        success: function (msg) {
            $('#atribuirUTCCursoModal').modal('hide');
            gerarFormGerirUTCs(curso);
        }
    });
}

function removerUTC(idCurso, idUTC) {
    var xhttp;    
    if (idCurso == "" || idUTC == "") {
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(xhttp.readyState === 4) {
            if(xhttp.status === 200) {
                alert('UTC removida');
            } else {
                alert('Código Erro: ' +  xhttp.status);
                alert('Mensagem Erro: ' + xhttp.statusText);
            }
        }
    }
    xhttp.open("GET", "processamento/removerUTCCurso.php?curso="+idCurso+"&utc="+idUTC, true);
    xhttp.send();
    
    gerarFormGerirUTCs(idCurso);
}

function verificarFormCriarCurso() {
    var nome = document.getElementById("nome").value;
    var sigla = document.getElementById("sigla").value;
    var semestres = document.getElementById("semestres").value;
    
    if( nome.length > 50 ){
        alert("O campo Nome não pode exceder 50 carateres");
        return;
    }
    
    if( nome.length < 1 ){
        alert("O campo Nome não pode ficar vazio");
        return;
    }
    
    if( sigla.length > 50 ){
        alert("O campo Sigla não pode exceder 50 carateres");
        return;
    }
    
    if( sigla.length < 1 ){
        alert("O campo Sigla não pode ficar vazio");
        return;
    }
    
    if( semestres > 20 ){
        alert("O campo Semestres não pode exceder um valor de 20");
        return;
    }
    
    if( semestres < 1 ){
        alert("O campo Semestres não pode ter um valor inferior a 1");
        return;
    }
    
    document.getElementById("formCriarCurso").submit();
}

function verificarFormEditarCurso() {
    var nome = document.getElementById("nome").value;
    var sigla = document.getElementById("sigla").value;
    var semestres = document.getElementById("semestres").value;
    
    if( nome.length > 50 ){
        alert("O campo Nome não pode exceder 50 carateres");
        return;
    }
    
    if( nome.length < 1 ){
        alert("O campo Nome não pode ficar vazio");
        return;
    }
    
    if( sigla.length > 50 ){
        alert("O campo Sigla não pode exceder 50 carateres");
        return;
    }
    
    if( sigla.length < 1 ){
        alert("O campo Sigla não pode ficar vazio");
        return;
    }
    
    if( semestres > 20 ){
        alert("O campo Semestres não pode exceder um valor de 20");
        return;
    }
    
    if( semestres < 1 ){
        alert("O campo Semestres não pode ter um valor inferior a 1");
        return;
    }
    
    document.getElementById("formEditarCurso").submit();
}

function refresh() {    
    setTimeout(function () {
        location.reload()
    }, 100);
}