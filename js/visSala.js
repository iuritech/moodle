function criarSala() {
    const nome_sala = document.getElementById("criarSala_nome").value;
    const bloco_sala = document.getElementById("criarSala_bloco").value;

    if (nome_sala === "") {
        alert("Por favor, insira o nome da sala.");
        return;
    }

    fetch("processamento/criarSala.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ nome_sala, bloco_sala }),
    })
        .then((response) => response.text())
        .then((result) => {
            alert(result);
            location.reload(); // Atualiza a página ou lista de salas
        })
        .catch((error) => {
            console.error("Erro ao criar a sala:", error);
            alert("Erro ao criar a sala. Tente novamente.");
        });
}