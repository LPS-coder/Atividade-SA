const CLIMA_LOCAL = {
    nome: "Joinville, SC",
    latitude: -26.3044,
    longitude: -48.8487
};

const CLIMA_ATUALIZA_A_CADA_MS = 10 * 60 * 1000; // atualiza sozinho a cada 10 minutos
const CLIMA_TEMPO_LIMITE_MS = 8000;              // desiste da consulta depois de 8 segundos

const CLIMA_CODIGOS = {
    0:  { texto: "Céu limpo",                    icone: "bi-sun-fill",                  iconeNoite: "bi-moon-stars-fill", nivel: "ok" },
    1:  { texto: "Predominantemente limpo",      icone: "bi-cloud-sun-fill",            iconeNoite: "bi-cloud-moon-fill", nivel: "ok" },
    2:  { texto: "Parcialmente nublado",         icone: "bi-cloud-sun-fill",            iconeNoite: "bi-cloud-moon-fill", nivel: "ok" },
    3:  { texto: "Nublado",                      icone: "bi-cloud-fill",                nivel: "ok" },
    45: { texto: "Neblina",                      icone: "bi-cloud-fog2-fill",           nivel: "atencao" },
    48: { texto: "Neblina com geada",            icone: "bi-cloud-fog2-fill",           nivel: "atencao" },
    51: { texto: "Garoa fraca",                  icone: "bi-cloud-drizzle-fill",        nivel: "atencao" },
    53: { texto: "Garoa moderada",               icone: "bi-cloud-drizzle-fill",        nivel: "atencao" },
    55: { texto: "Garoa intensa",                icone: "bi-cloud-drizzle-fill",        nivel: "atencao" },
    56: { texto: "Garoa congelante fraca",       icone: "bi-cloud-sleet-fill",          nivel: "risco" },
    57: { texto: "Garoa congelante intensa",     icone: "bi-cloud-sleet-fill",          nivel: "risco" },
    61: { texto: "Chuva fraca",                  icone: "bi-cloud-rain-fill",           nivel: "atencao" },
    63: { texto: "Chuva moderada",               icone: "bi-cloud-rain-fill",           nivel: "atencao" },
    65: { texto: "Chuva forte",                  icone: "bi-cloud-rain-heavy-fill",     nivel: "risco" },
    66: { texto: "Chuva congelante fraca",       icone: "bi-cloud-sleet-fill",          nivel: "risco" },
    67: { texto: "Chuva congelante forte",       icone: "bi-cloud-sleet-fill",          nivel: "risco" },
    71: { texto: "Neve fraca",                   icone: "bi-cloud-snow-fill",           nivel: "atencao" },
    73: { texto: "Neve moderada",                icone: "bi-cloud-snow-fill",           nivel: "atencao" },
    75: { texto: "Neve forte",                   icone: "bi-cloud-snow-fill",           nivel: "risco" },
    77: { texto: "Grãos de neve",                icone: "bi-snow2",                     nivel: "atencao" },
    80: { texto: "Pancadas de chuva fracas",     icone: "bi-cloud-rain-fill",           nivel: "atencao" },
    81: { texto: "Pancadas de chuva moderadas",  icone: "bi-cloud-rain-fill",           nivel: "atencao" },
    82: { texto: "Pancadas de chuva fortes",     icone: "bi-cloud-rain-heavy-fill",     nivel: "risco" },
    85: { texto: "Pancadas de neve fracas",      icone: "bi-cloud-snow-fill",           nivel: "atencao" },
    86: { texto: "Pancadas de neve fortes",      icone: "bi-cloud-snow-fill",           nivel: "risco" },
    95: { texto: "Tempestade",                   icone: "bi-cloud-lightning-rain-fill", nivel: "risco" },
    96: { texto: "Tempestade com granizo fraco", icone: "bi-cloud-hail-fill",           nivel: "risco" },
    99: { texto: "Tempestade com granizo forte", icone: "bi-cloud-hail-fill",           nivel: "risco" }
};

const CLIMA_NIVEIS = {
    ok:      { classe: "badge bg-success",           texto: "Favorável à operação" },
    atencao: { classe: "badge bg-warning text-dark", texto: "Atenção à operação" },
    risco:   { classe: "badge bg-danger",            texto: "Risco à operação" },
    nenhum:  { classe: "badge bg-secondary",         texto: "Sem avaliação" }
};

let climaCarregando = false; // evita duas consultas ao mesmo tempo
let climaTemDados = false;   // já mostramos algum dado real da API?
