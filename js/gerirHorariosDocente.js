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
    opacity: 0.8,
    zIndex: 9999,
    appendTo: "body"
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

// function atribuirAula(id_aula, id_horario) {
//     if (!id_horario) {
//         // alert("Hora não disponivel");
//         return;
//     }
//     var id_aula = prompt("ID do aula a atribuir?");
//     if (!id_aula) return;
//     var xhr = new XMLHttpRequest();
//     xhr.open("POST", "atualizarHorarioTESTE.php");
//     xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//     xhr.onload = function() {
//         alert(xhr.responseText);
//         location.reload();
//     };
//     xhr.send("&id_aula=" + encodeURIComponent(id_aula) +
//         "&id_juncao=" + encodeURIComponent(id_juncao) +
//         "&id_horario=" + encodeURIComponent(id_horario));
// }

function atribuir_sala(){
    document.getElementById('caixa-salas').style.display = 'block'
    }

