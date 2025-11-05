function gerarFormCriarJuncao() {
    var xhttp;    
    
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyCriarJuncao").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formCriarJuncao.php", true);
    xhttp.send();
}

function gerarFormEditarJuncao(id) {
    var xhttp;    
    if (id == "") {
      document.getElementById("modalBodyEditarJuncao").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyEditarJuncao").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formEditarJuncao.php?i="+id, true);
    xhttp.send();
}


function carregarDados() {
	
	alert("Carregar dados!");
}


function gerarSelectArea(idUTC) {
    var xhttp;    
    if (idUTC == "" || idUTC === 0) {
      document.getElementById("selectArea").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("selectArea").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/definicaoAreaUtilizador/selectArea.php?utc="+idUTC, true);
    xhttp.send();
}

function gerarFormRedefinirPassword(idUtilizador) {
    var xhttp;    
    if (idUtilizador == "") {
      document.getElementById("modalBodyRedefinirPassword").innerHTML = "";
      return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("modalBodyRedefinirPassword").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "phpUtil/formRedefinicaoPassword.php?i="+idUtilizador, true);
    xhttp.send();
}

function verificarFormCriarUtilizador() {
    var nome = document.getElementById("nome").value;
    var login = document.getElementById("login").value;
    var password1 = document.getElementById("password1").value;
    var password2 = document.getElementById("password2").value;
    
    if( nome.length > 50 ){
        alert("O campo Nome não pode exceder 50 carateres");
        return;
    }
    
    if( nome.length < 1 ){
        alert("O campo Nome não pode ficar vazio");
        return;
    }
    
    if( login.length > 50 ){
        alert("O campo Login não pode exceder 50 carateres");
        return;
    }
    
    if( login.length < 1 ){
        alert("O campo Login não pode ficar vazio");
        return;
    }
    
    if( password1.length < 1 ){
        alert("O campo Password não pode ficar vazio");
        return;
    }
    
    if( password1.length > 255 ){
        alert("A password não deve exceder os 255 carateres");
        return;
    }
    
    if( password1 !== password2){
        alert("As passwords não são idênticas.");
        return;
    }
    
    document.getElementById("formCriarUtilizador").submit();
}

function verificarFormEditarUtilizador() {
    var nome = document.getElementById("nome").value;
    var login = document.getElementById("login").value;
    
    if( nome.length > 50 ){
        alert("O campo Nome não pode exceder 50 carateres");
        return;
    }
    
    if( nome.length < 1 ){
        alert("O campo Nome não pode ficar vazio");
        return;
    }
    
    if( login.length > 50 ){
        alert("O campo Login não pode exceder 50 carateres");
        return;
    }
    
    if( login.length < 1 ){
        alert("O campo Login não pode ficar vazio");
        return;
    }
    
    document.getElementById("formEditarUtilizador").submit();
}

function verificarFormRedefinirPassword() {
    var novapassword = document.getElementById("novapassword").value;
    var novapassword2 = document.getElementById("novapassword2").value;
    
    if( novapassword == "" ){
        alert("O campo 'Nova Password' não pode ficar vazio");
        return;
    }
    
    if( novapassword2 == "" ){
        alert("O campo 'Confirme a nova password' não pode ficar vazio");
        return;
    }
    

    if( novapassword !== novapassword2){
        alert("As novas passwords não são idênticas.");
        return;
    }
    
    if( novapassword.length > 255 ){
        alert("A nova password não deve exceder os 255 carateres");
        return;
    }
    
    document.getElementById("formRedefinicaoPassword").submit();
}