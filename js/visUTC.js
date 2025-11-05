function gerarFormCriarUTC() {
    var xhttp;    
    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyCriarUTC").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formCriarUTC.php", true);
    xhttp.send();
}

function gerarFormEditarUTC(id) {
    var xhttp;    
    if (id == "") {
      document.getElementById("modalBodyEditarUTC").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarUTC").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formEditarUTC.php?i="+id, true);
    xhttp.send();
}

function verificarFormCriarUTC() {
    var nome = document.getElementById("nome").value;
    var sigla = document.getElementById("sigla").value;
    
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
    
    document.getElementById("formCriarUTC").submit();
}

function verificarFormEditarUTC() {
    document.getElementById("formEditarUTC").submit();
}