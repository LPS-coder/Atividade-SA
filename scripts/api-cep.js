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

async function consultarCep(cep) {
    cancelarConsultaCep(); // se ainda havia uma consulta antiga rodando, ela é cancelada
 
    const controle = new AbortController();
    cepConsulta = controle;
 
    let estourouTempo = false;
    const temporizador = setTimeout(function () {
        estourouTempo = true;
        controle.abort();
    }, CEP_TEMPO_LIMITE_MS);
 
    mostrarMensagemCep("carregando", "Consultando CEP...");
 
    try {
        const resposta = await fetch("https://viacep.com.br/ws/" + cep + "/json/", { signal: controle.signal });
 
        // A ViaCEP responde 400 quando o formato do CEP é inválido
        if (resposta.status === 400) {
            if (cepConsulta !== controle) return;
 
            limparEnderecoCep();
            mostrarMensagemCep("erro", "CEP inválido. Informe 8 números.");
            return;
        }
 
        if (!resposta.ok) {
            throw new Error("A ViaCEP respondeu com status " + resposta.status);
        }
 
        const dados = await resposta.json();
 
        if (cepConsulta !== controle) return; // já existe uma consulta mais nova: ignora esta
 
        // Formato certo, mas o CEP não existe na base: a ViaCEP devolve { "erro": "true" }
        if (dados.erro) {
            limparEnderecoCep();
            mostrarMensagemCep("erro", "CEP não encontrado. Confira os números ou preencha o endereço manualmente.");
            return;
        }
 
        preencherEnderecoCep(dados);
 
        // CEP "geral" de cidade pequena vem sem logradouro/bairro: a pessoa completa na mão
        if (dados.logradouro) {
            mostrarMensagemCep("sucesso", "CEP encontrado.");
        } else {
            mostrarMensagemCep("sucesso", "CEP encontrado. Ele é geral da cidade: preencha o logradouro e o bairro.");
        }
 
        // Leva o cursor para o próximo campo que a pessoa precisa preencher
        const proximo = dados.logradouro ? cepCampo("numero") : cepCampo("logradouro");
        if (proximo && document.activeElement === cepCampo("cep")) {
            proximo.focus();
        }
    } catch (falha) {
        if (cepConsulta !== controle) return; // cancelada de propósito: não é falha
 
        console.error("Falha ao consultar o CEP:", falha);
        limparEnderecoCep();
        mostrarMensagemCep(
            "falha",
            estourouTempo
                ? "A consulta demorou demais. Tente de novo ou preencha o endereço manualmente."
                : "Não foi possível consultar o CEP agora (sem conexão ou serviço fora do ar). Preencha o endereço manualmente."
        );
    } finally {
        clearTimeout(temporizador);
 
        if (cepConsulta === controle) {
            cepConsulta = null;
        }
    }
}