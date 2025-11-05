function gerarFormCriarArea() {
    var xhttp;    
    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyCriarArea").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formCriarArea.php", true);
    xhttp.send();
}

function gerarFormEditarArea(id) {
    var xhttp;    
    if (id == "") {
      document.getElementById("modalBodyEditarArea").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarArea").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formEditarArea.php?i="+id, true);
    xhttp.send();
}

function verificarFormCriarArea() {
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
    
    document.getElementById("formCriarArea").submit();
}

function verificarFormEditarArea() {
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
    
    document.getElementById("formEditarArea").submit();
}