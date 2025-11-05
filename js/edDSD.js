function gerarFormEditarDSD(idUtilizador, idBloco) {
    var xhttp;    
    if (idUtilizador == "" || idBloco  == "" ) {
      document.getElementById("modalBodyEditarDSD").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarDSD").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formEditarDSD.php?i="+idUtilizador+"bloco="+idBloco, true);
    xhttp.send();
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