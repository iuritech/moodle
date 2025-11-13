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
            id_componente: ui.draggable.data("id_componente"),
            id_horario: ui.draggable.data("id_horario"),
            id_docente: ui.draggable.data("id_docente"),
            id_turma: ui.draggable.data("id_turma")
    };

        if (confirm("Tem certeza que deseja remover esta aula?")) {
            atualizarHorario(data);
        }
    }
});

// Torna as células disponíveis largáveis
$(".disponivel").droppable({
accept: ".disciplina-draggable, .ocupado",
    hoverClass: "ui-state-hover",
    drop: function(event, ui) {
        var id_docente = $(this).closest('.panel').data('id_docente');
        var id_componente = ui.draggable.data("id_componente");
        var id_turma = ui.draggable.data("id_turma"); // já vem do HTML
        var id_horario = $(this).data("id_horario");
        var data= {}; 

        console.log({
        id_componente: id_componente,
            id_turma: id_turma,
            id_horario: id_horario,
            id_docente: id_docente
        });

        // if (!id_horario) {
        //     alert("Horário inválido. Não é possível atribuir aula neste slot.");
        //     return;
        // }
        // var pref = $(this).data("pref"); // valor da preferência do slot

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
                id_componente: id_componente,
                id_horario: id_horario,
                id_docente: id_docente,
                id_turma: id_turma
        };
        }
        else if(ui.draggable.hasClass("ocupado")) {
            data = {
            action: "move",
                id_componente: id_componente,
                id_horario_antigo: ui.draggable.data("id_horario"),
                id_horario: id_horario,
                id_turma: id_turma,
                id_docente: id_docente
        };
        }

        atualizarHorario(data);
    }
});
});

function atualizarHorario(data) {
    $.post("atualizarHorarioTESTE.php", data, function(response) {
         alert(response);
        location.reload();
    });
}

function atribuirAula(id_docente, id_horario) {
    if (!id_horario) {
        alert("Hora não disponivel");
        return;
    }
    var id_componente = prompt("ID do componente a atribuir?");
    if (!id_componente) return;
    var id_turma = prompt("ID da turma (opcional)?");
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "atualizarHorarioTESTE.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        alert(xhr.responseText);
        location.reload();
    };
    xhr.send("id_componente=" + encodeURIComponent(id_componente) +
        "&id_horario=" + encodeURIComponent(id_horario) +
        "&id_docente=" + encodeURIComponent(id_docente) +
        "&id_turma=" + encodeURIComponent(id_turma || ""));
}


