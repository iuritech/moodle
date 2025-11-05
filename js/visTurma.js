function gerarFormCriarTurma(id) {
    var xhttp;  
    if (id == "") {
      document.getElementById("modalBodyEditarTurma").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyCriarTurma").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formCriarTurma.php?i="+id, true);
    xhttp.send();
}

function gerarFormEditarTurma(id) {
    var xhttp;    
    if (id == "") {
      document.getElementById("modalBodyEditarTurma").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarTurma").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formEditarTurma.php?i="+id, true);
    xhttp.send();
}

function verificarFormCriarTurma() {
    var nome = document.getElementById("nome").value;
    
    if( nome.length > 50 ){
        alert("O campo Nome não pode exceder 50 carateres");
        return;
    }
    
    if( nome.length < 1 ){
        alert("O campo Nome não pode ficar vazio");
        return;
    }
    
    document.getElementById("formCriarTurma").submit();
}

function verificarFormEditarTurma() {
    var nome = document.getElementById("nome").value;
    
    if( nome.length > 50 ){
        alert("O campo Nome não pode exceder 50 carateres");
        return;
    }
    
    if( nome.length < 1 ){
        alert("O campo Nome não pode ficar vazio");
        return;
    }
    
    document.getElementById("formEditarTurma").submit();
}