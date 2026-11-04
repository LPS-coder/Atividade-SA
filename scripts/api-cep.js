const CEP_CAMPOS = {
    cep: "cep",
    logradouro: "logradouro",
    bairro: "bairro",
    cidade: "cidade",
    uf: "uf",
    numero: "numero",         
    mensagem: "cep-mensagem" 
};

const CEP_TEMPO_LIMITE_MS = 8000; 

const CEP_ESTILOS = {
    carregando: { cor: "text-secondary", icone: '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' },
    sucesso:    { cor: "text-success",   icone: '<i class="bi bi-check-circle-fill me-1"></i>' },
    erro:       { cor: "text-danger",    icone: '<i class="bi bi-x-circle-fill me-1"></i>' },
    falha:      { cor: "text-danger",    icone: '<i class="bi bi-wifi-off me-1"></i>' }
};

let cepConsulta = null;           
let cepPreenchidoPelaApi = false; 

function cepCampo(chave) {
    return document.getElementById(CEP_CAMPOS[chave]);
}

function cepApenasNumeros(texto) {
    return texto.replace(/\D/g, "");
}

function cepComMascara(numeros) {
    return numeros.length > 5 ? numeros.slice(0, 5) + "-" + numeros.slice(5) : numeros;
}

// Mostra o aviso abaixo do campo, pinta a borda do CEP e libera/bloqueia o envio do formulário.

function mostrarMensagemCep(tipo, texto) {
    const campo = cepCampo("cep");
    const mensagem = cepCampo("mensagem");

    campo.classList.remove("is-valid", "is-invalid");
    if (tipo === "sucesso") campo.classList.add("is-valid");
    if (tipo === "erro") campo.classList.add("is-invalid");

// "erro" (CEP inválido ou não encontrado) e "carregando" impedem o envio do formulário.

    if (tipo === "erro") {
        campo.setCustomValidity(texto);
    } else if (tipo === "carregando") {
        campo.setCustomValidity("Aguarde a consulta do CEP terminar.");
    } else {
        campo.setCustomValidity("");
    }

    if (!mensagem) {
        return;
    }

    const estilo = CEP_ESTILOS[tipo];

    if (!estilo) {
        mensagem.className = "form-text";
        mensagem.textContent = "";
        return;
    }

    mensagem.className = "form-text " + estilo.cor;
    mensagem.innerHTML = estilo.icone;
    mensagem.appendChild(document.createTextNode(texto));
}

function preencherEnderecoCep(dados) {
    const valores = {
        logradouro: dados.logradouro,
        bairro: dados.bairro,
        cidade: dados.localidade, // a ViaCEP chama a cidade de "localidade"
        uf: dados.uf
    };

    Object.keys(valores).forEach(function (chave) {
        const campo = cepCampo(chave);

        if (campo) {
            campo.value = valores[chave] || "";
        }
    });

    cepPreenchidoPelaApi = true;
}

// Só apaga o endereço se ele tiver sido preenchido pela API (não mexe no que a pessoa digitou)
function limparEnderecoCep() {
    if (!cepPreenchidoPelaApi) {
        return;
    }

    ["logradouro", "bairro", "cidade", "uf"].forEach(function (chave) {
        const campo = cepCampo(chave);

        if (campo) {
            campo.value = "";
        }
    });

    cepPreenchidoPelaApi = false;
}

function cancelarConsultaCep() {
    if (cepConsulta) {
        cepConsulta.abort();
        cepConsulta = null;
    }
}
