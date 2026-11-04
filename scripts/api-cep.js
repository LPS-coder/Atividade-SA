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