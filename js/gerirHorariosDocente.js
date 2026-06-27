$(function() {
    // Torna as disciplinas arrastáveis
    $(".disciplina-draggable").draggable({
    helper: "clone",
        revert: "invalid",
        cursor: "move",
        opacity: 0.8,
        zIndex: 9999,
        appendTo: "body"
});

$(".ocupado").draggable({
    helper: "clone",
    revert: "invalid",
    cursor: "move",
    opacity: 0.3,
    zIndex: 9999,
    appendTo: "body",


        start: function () {

    let painel = $(this).closest(".panel");

    painel.find(".disponivel").addClass("horas-verdes");

    let horasAmarelas = $(this).attr("data-horas_amarelas");
    if (horasAmarelas) {
        horasAmarelas = JSON.parse(horasAmarelas.replace(/'/g, '"'));

        horasAmarelas.forEach(function(idHorario) {
            painel.find(".disponivel[data-id_horario='" + idHorario + "']")
                  .addClass("horas-amarelas");
        });
    }

    let horasInvalidas = $(this).attr("data-horas_invalidas");
    if (horasInvalidas) {
        horasInvalidas = JSON.parse(horasInvalidas.replace(/'/g, '"'));

        horasInvalidas.forEach(function(idHorario) {
            painel.find(".disponivel[data-id_horario='" + idHorario + "']")
                  .addClass("horas-invalidas");
        });
    }
},

stop: function () {

    let painel = $(this).closest(".panel");

    painel.find(".disponivel").removeClass("horas-amarelas");
    painel.find(".disponivel").removeClass("horas-invalidas");
    painel.find(".disponivel").removeClass("horas-verdes");
}

});


// Área para soltar (fora da tabela)
$("#disciplinas-lista").droppable({
accept: ".ocupado",
    hoverClass: "ui-state-highlight",
    drop: function(event, ui) {
        var data = {
        action: 'remove',
            id_aula: ui.draggable.data("id_aula"),
            id_juncao: ui.draggable.data("id_juncao"),
    };
            atualizarHorario(data);
    }
});


// Torna as células disponíveis largáveis
$(".disponivel").droppable({
accept: ".disciplina-draggable, .ocupado",
    hoverClass: "ui-state-hover",
    drop: function(event, ui) {
        var id_docente = $(this).closest('.panel').data('id_docente');
        var id_aula = ui.draggable.data("id_aula");
        var id_juncao = ui.draggable.data("id_juncao");
        var id_horario = $(this).data("id_horario");
        var data= {}; 

        console.log({
            id_docente: id_docente,
            id_aula: id_aula,
            id_horario: id_horario,
            id_juncao: id_juncao,
        });

        if (!id_horario) {
            alert("Horário inválido. Não é possível atribuir aula neste slot.");
            return;
        }
        var pref = $(this).data("pref"); // valor da preferência do slot

        // if (pref === 0) {
        //     if (!confirm("Aviso: O docente marcou este horário como IMPOSSÍVEL. Deseja continuar?")) {
        //         return; // cancela a ação
        //     }
        // } else if (pref === 1) {
        //     if (!confirm("Aviso: O docente não gosta de ter aulas neste horário. Deseja continuar?")) {
        //         return; // cancela a ação
        //     }
        // }

        if(ui.draggable.hasClass("disciplina-draggable")) {
            data = {
            action: "add",
                id_aula: id_aula,
                id_horario: id_horario,
                id_juncao: id_juncao,
        };
        }
        else if(ui.draggable.hasClass("ocupado")) {
            data = {
            action: "move",
                id_aula: id_aula,
                id_docente: id_docente,
                id_horario: id_horario,
                id_juncao: id_juncao,
        };
        }

        atualizarHorario(data);
    }
});
});

function atualizarHorario(data) {
    $.post("atualizarHorarioTESTE.php", data, function(response) {
        // caso dê erro decomentar este alerta para ver o erro
         // alert(response);
        location.reload();
    });
}

let text = "";
function atribuir_sala(id_aula,salas){
    document.getElementById('caixa_salas').style.display = 'block';
    console.log(salas);
    console.log(id_aula);
    text = "<input type=hidden name=id_aula value="+id_aula+">";
    salas.forEach(myFunction);
    document.getElementById('conteudo_salas').innerHTML = text;
    if (salas==""){
        document.getElementById('caixa_salas').style.display = 'none';
    }
}

function myFunction(item, index) {
    text+= " <input type=radio id='"+item+"' name=id_sala value='"+item+"'> <label for=id_sala>"+item+"</label><br> ";


}

