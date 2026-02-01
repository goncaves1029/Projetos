// srciptUber.js
const MEDIA_KM_POR_LITRO = 27;

const litrosEl = document.getElementById('litros');
const valorTotalEl = document.getElementById('valorTotal');
const kmInicialEl = document.getElementById('kmInicial');
const kmFinalEl = document.getElementById('kmFinal');
const diariaEl = document.getElementById('diaria');
const dataEl = document.getElementById('data');

const calcularBtn = document.getElementById('calcular');
const salvarBtn = document.getElementById('salvar');
const limparBtn = document.getElementById('limpar');
const mensagensEl = document.getElementById('mensagens');

const kmRodadosEl = document.getElementById('kmRodados');
const consumoEl = document.getElementById('consumo');
const precoLitroEl = document.getElementById('precoLitro');
const gastoEl = document.getElementById('gasto');
const lucroEl = document.getElementById('lucro');
const textoResumoEl = document.getElementById('textoResumo');

// Lê parâmetro da URL (ex.: ?editId=123)
function getUrlParam(name){
  const params = new URLSearchParams(window.location.search);
  return params.get(name);
}

let currentEditId = null;

// Converte entrada que pode usar vírgula ou ponto para número
function parseNumber(value){
  if (value === null || value === undefined) return NaN;
  const normalized = String(value).trim().replace(/\s+/g,'').replace(',', '.');
  if (normalized === '') return NaN;
  const n = Number(normalized);
  return isFinite(n) ? n : NaN;
}

function formatBR(value){
  if (value === null || value === undefined || isNaN(value)) return '-';
  return Number(value).toFixed(2).replace('.', ',');
}

function limparResultados(){
  mensagensEl.textContent = '';
  kmRodadosEl.textContent = '-';
  consumoEl.textContent = '-';
  precoLitroEl.textContent = '-';
  gastoEl.textContent = '-';
  lucroEl.textContent = '-';
  textoResumoEl.textContent = 'Nenhum cálculo realizado ainda.';
}

function validarEntradas(){
  const erros = [];
  const litros = parseNumber(litrosEl.value);
  const valorTotal = parseNumber(valorTotalEl.value);
  const kmInicial = parseNumber(kmInicialEl.value);
  const kmFinal = parseNumber(kmFinalEl.value);
  const diaria = parseNumber(diariaEl.value);
  const data = dataEl.value || null;

  if (!isNaN(litros) && litros < 0) erros.push('Quantidade de combustível não pode ser negativa.');
  if (!isNaN(valorTotal) && valorTotal < 0) erros.push('Valor total do combustível não pode ser negativo.');
  if (!isNaN(kmInicial) && kmInicial < 0) erros.push('Km inicial não pode ser negativo.');
  if (!isNaN(kmFinal) && kmFinal < 0) erros.push('Km final não pode ser negativo.');
  if (!isNaN(diaria) && diaria < 0) erros.push('Diária da Uber não pode ser negativa.');

  if (isNaN(litros)) erros.push('Informe a quantidade de combustível em litros.');
  if (isNaN(valorTotal)) erros.push('Informe o valor total do combustível.');
  if (isNaN(kmInicial)) erros.push('Informe o km inicial.');
  if (isNaN(kmFinal)) erros.push('Informe o km final.');
  if (isNaN(diaria)) erros.push('Informe a diária da Uber.');

  if (!isNaN(kmInicial) && !isNaN(kmFinal) && kmFinal < kmInicial){
    erros.push('Km final deve ser maior ou igual ao km inicial.');
  }

  if (!isNaN(litros) && litros <= 0) erros.push('Litros abastecidos deve ser maior que zero.');
  if (!isNaN(valorTotal) && valorTotal <= 0) erros.push('Valor total do combustível deve ser maior que zero.');

  return {erros, litros, valorTotal, kmInicial, kmFinal, diaria, data};
}

function calcular(){
  mensagensEl.textContent = '';
  const v = validarEntradas();

  if (v.erros.length){
    mensagensEl.innerHTML = v.erros.join(' ');
    return null;
  }

  const kmRodados = v.kmFinal - v.kmInicial;
  const consumoEstimado = kmRodados / MEDIA_KM_POR_LITRO;
  const precoPorLitro = v.valorTotal / v.litros;
  const gastoCombustivel = consumoEstimado * precoPorLitro;
  const lucroLiquido = v.diaria - gastoCombustivel;

  kmRodadosEl.textContent = formatBR(kmRodados);
  consumoEl.textContent = formatBR(consumoEstimado);
  precoLitroEl.textContent = formatBR(precoPorLitro);
  gastoEl.textContent = formatBR(gastoCombustivel);
  lucroEl.textContent = formatBR(lucroLiquido);

  const dataTexto = v.data ? v.data : '—';
  const resumoLinhas = [
    `Data: ${dataTexto}`,
    `Km inicial: ${v.kmInicial}`,
    `Km final: ${v.kmFinal}`,
    `Quilômetros rodados: ${formatBR(kmRodados)} km`,
    `Litros abastecidos: ${formatBR(v.litros)} L`,
    `Valor total do combustível: R$ ${formatBR(v.valorTotal)}`,
    `Preço por litro: R$ ${formatBR(precoPorLitro)}`,
    `Consumo estimado: ${formatBR(consumoEstimado)} L`,
    `Gasto com combustível: R$ ${formatBR(gastoCombustivel)}`,
    `Diária da Uber: R$ ${formatBR(v.diaria)}`,
    `Lucro líquido do dia: R$ ${formatBR(lucroLiquido)}`
  ];

  textoResumoEl.innerHTML = resumoLinhas.join('<br>');

  return {
    data: v.data || new Date().toISOString().slice(0,10),
    litros: v.litros,
    valor_total: v.valorTotal,
    km_inicial: v.kmInicial,
    km_final: v.kmFinal,
    km_rodados: kmRodados,
    consumo_estimado: consumoEstimado,
    preco_por_litro: precoPorLitro,
    gasto_combustivel: gastoCombustivel,
    diaria: v.diaria,
    lucro_liquido: lucroLiquido
  };
}

function salvarRegistro(){
  const resultado = calcular();
  if (!resultado) return;

  const url = currentEditId ? 'update.php' : 'insert.php';
  const payload = currentEditId ? { id: currentEditId, ...resultado } : resultado;

  fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(res => res.text())
  .then(msg => {
    mensagensEl.style.color = '#16a34a';
    mensagensEl.textContent = msg;
    currentEditId = null;
    history.replaceState(null, '', window.location.pathname);
    setTimeout(() => mensagensEl.textContent = '', 2500);
  });
}

function carregarParaEdicao(id){
  fetch(`get.php?id=${id}`)
    .then(res => res.json())
    .then(reg => {
      if (!reg) {
        mensagensEl.style.color = '#d9534f';
        mensagensEl.textContent = 'Registro para edição não encontrado.';
        setTimeout(()=>{ mensagensEl.textContent = ''; mensagensEl.style.color = ''; }, 2500);
        return;
      }
      dataEl.value = reg.data || '';
      litrosEl.value = reg.litros !== undefined ? String(reg.litros).replace('.', ',') : '';
      valorTotalEl.value = reg.valor_total !== undefined ? String(reg.valor_total).replace('.', ',') : '';
      kmInicialEl.value = reg.km_inicial || '';
      kmFinalEl.value = reg.km_final || '';
      diariaEl.value = reg.diaria !== undefined ? String(reg.diaria).replace('.', ',') : '';
      calcular();
      currentEditId = id;
      mensagensEl.style.color = '#0b6efd';
      mensagensEl.textContent = 'Modo edição ativado. Ao salvar, o registro será atualizado.';
      setTimeout(()=>{ mensagensEl.textContent = ''; mensagensEl.style.color = ''; }, 3000);
    });
}

function limpar(){
  litrosEl.value = '';
  valorTotalEl.value = '';
  kmInicialEl.value = '';
  kmFinalEl.value = '';
  diariaEl.value = '';
  dataEl.value = '';
  currentEditId = null;
  history.replaceState(null, '', window.location.pathname);
  limparResultados();
}

// Eventos
calcularBtn.addEventListener('click', calcular);
salvarBtn.addEventListener('click', salvarRegistro);
limparBtn.addEventListener('click', limpar);

  document.getElementById('form').addEventListener('submit', function(e){
  e.preventDefault();
  calcular();
});