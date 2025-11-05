function gerarFormCriarDisciplina(id) {
    var xhttp;  
    if (id == "") {
      document.getElementById("modalBodyEditarDisciplina").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyCriarDisciplina").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formCriarDisciplina.php?i="+id, true);
    xhttp.send();
}

function gerarFormEditarDisciplina(id) {
    var xhttp;    
    if (id == "") {
      document.getElementById("modalBodyEditarDisciplina").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarDisciplina").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formEditarDisciplina.php?i="+id, true);
    xhttp.send();
}

function gerarSelectResponsavel(idArea) {
    var xhttp;    
    if (idArea == "" || idArea === 0) {
      document.getElementById("selectResponsavel").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("selectResponsavel").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/definicaoResponsavelDisciplina/selectResponsavel.php?area="+idArea, true);
    xhttp.send();
}

function verificarFormCriarDisciplina() {
    var nome = document.getElementById("nome").value;
    var codigoUC = document.getElementById("codigoUC").value;
    var sigla = document.getElementById("sigla").value;
    
    if( nome.length > 50 ){
        alert("O campo Nome não pode exceder 50 carateres");
        return;
    }
    
    if( nome.length < 1 ){
        alert("O campo Nome não pode ficar vazio");
        return;
    }
    
    if( codigoUC == "" ){
        alert("O campo Código Unidade Curricular não pode ficar vazio");
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
    
    document.getElementById("formCriarDisciplina").submit();
}

function verificarFormEditarDisciplina() {
    var nome = document.getElementById("nome").value;
    var codigoUC = document.getElementById("codigoUC").value;
    var sigla = document.getElementById("sigla").value;
    
    if( nome.length > 50 ){
        alert("O campo Nome não pode exceder 50 carateres");
        return;
    }
    
    if( nome.length < 1 ){
        alert("O campo Nome não pode ficar vazio");
        return;
    }
    
    if( codigoUC == "" ){
        alert("O campo Código Unidade Curricular não pode ficar vazio");
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
    
    document.getElementById("formEditarDisciplina").submit();
}