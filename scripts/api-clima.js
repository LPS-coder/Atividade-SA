const CLIMA_LOCAL = {
    nome: "Joinville, SC",
    latitude: -26.3044,
    longitude: -48.8487
};

const CLIMA_ATUALIZA_A_CADA_MS = 10 * 60 * 1000; // atualiza sozinho a cada 10 minutos
const CLIMA_TEMPO_LIMITE_MS = 8000;              // desiste da consulta depois de 8 segundos