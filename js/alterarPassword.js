function verificarFormRedefinirPassword() {
    var passwordatual = document.getElementById("passwordatual").value;
    var novapassword = document.getElementById("novapassword").value;
    var novapassword2 = document.getElementById("novapassword2").value;
    
    if( passwordatual == "" ){
        alert("O campo 'Password Atual' não pode ficar vazio");
        return;
    }
    
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
    
    if( novapassword.length > 50 ){
        alert("A nova password não deve exceder os 255 carateres");
        return;
    }
    
    document.getElementById("formRedefinicaoPassword").submit();
}