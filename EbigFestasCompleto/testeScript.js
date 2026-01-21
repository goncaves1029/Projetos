

//     // script.js

// // Carrinho: array de objetos {id, nome, preco, quantidade}
// let carrinho = [];

// // Seletores principais
// const produtos = document.querySelectorAll('.card-body, .cta');
// // const produtos1 = document.querySelectorAll('.card-body');
// const btnsAdicionar = document.querySelectorAll('.adicionar');
// const carrinhoIcone = document.getElementById('carrinho-icone');
// const contadorCarrinho = document.getElementById('contador-carrinho');
// const carrinhoModal = document.getElementById('carrinho-modal');
// const listaCarrinho = document.getElementById('lista-carrinho');
// const fecharCarrinho = document.getElementById('fechar-carrinho');
// const limparCarrinho = document.getElementById('limpar-carrinho');
// const toast = document.getElementById('toast');
// const modalOverlay = document.querySelector('.modal-overlay');

// // Carrega carrinho do localStorage ao iniciar
// function carregarCarrinho() {
//   const salvo = localStorage.getItem('carrinho');
//   carrinho = salvo ? JSON.parse(salvo) : [];
//   atualizarContador();
// }
// carregarCarrinho();

// // Salva carrinho no localStorage
// function salvarCarrinho() {
//   localStorage.setItem('carrinho', JSON.stringify(carrinho));
// }

// // Atualiza contador do ícone do carrinho
// function atualizarContador() {
//   const total = carrinho.reduce((acc, item) => acc + item.quantidade, 0);
//   contadorCarrinho.textContent = total;
//   // Anima badge ao adicionar
//   contadorCarrinho.classList.add('animar');
//   setTimeout(() => contadorCarrinho.classList.remove('animar'), 350);
// }

// // Exibe toast de feedback
// function mostrarToast(msg) {
//   toast.textContent = msg;
//   toast.classList.add('ativo');
//   setTimeout(() => toast.classList.remove('ativo'), 1200);
// }

// // Adiciona item ao carrinho
// function adicionarAoCarrinho(id, nome, preco) {
//   const existente = carrinho.find(item => item.id === id);
//   if (existente) {
//     existente.quantidade += 1;
//   } else {
//     carrinho.push({ id, nome, preco, quantidade: 1 });
//   }
//   salvarCarrinho();
//   atualizarContador();
//   mostrarToast(`"${nome}" adicionado ao carrinho!`);
//   renderizarCarrinho();
// }

// // Remove item do carrinho
// function removerDoCarrinho(id) {
//   carrinho = carrinho.filter(item => item.id !== id);
//   salvarCarrinho();
//   atualizarContador();
//   renderizarCarrinho();
// }

// // Limpa todo o carrinho
// function limparCarrinhoFunc() {
//   carrinho = [];
//   salvarCarrinho();
//   atualizarContador();
//   renderizarCarrinho();
// }

// // Renderiza conteúdo do carrinho no modal
// function renderizarCarrinho() {
//   listaCarrinho.innerHTML = '';
//   if (carrinho.length === 0) {
//     listaCarrinho.innerHTML = '<li>Seu carrinho está vazio.</li>';
//     return;
//   }
//   carrinho.forEach(item => {
//     const li = document.createElement('li');
//     li.innerHTML = `
//     <span class="quantidade">${item.quantidade} x</span>
//       <span class="nome">${item.nome}</span>
//       <button class="remover" aria-label="Remover ${item.nome}" title="Remover">&times;</button>
//     `;
//     li.querySelector('.remover').addEventListener('click', () => removerDoCarrinho(item.id));
//     listaCarrinho.appendChild(li);
//   });
// }

// // Abre modal do carrinho
// function abrirCarrinho() {
//   carrinhoModal.classList.add('ativo');
//   renderizarCarrinho();
//   // Foco no modal para acessibilidade
//   carrinhoModal.focus();
// }

// // Fecha modal do carrinho
// function fecharCarrinhoFunc() {
//   carrinhoModal.classList.remove('ativo');
//   // Retorna foco ao ícone do carrinho
//   carrinhoIcone.focus();
// }

// // Eventos dos botões "Adicionar"
// btnsAdicionar.forEach((btn, idx) => {
//   btn.addEventListener('click', () => {
//     const produto = produtos[idx];
//     const id = produto.dataset.id;
//     const nome = produto.dataset.nome;
//     const preco = Number(produto.dataset.preco);
//     adicionarAoCarrinho(id, nome, preco);
//   });
// });

// // Evento do ícone do carrinho (mouse e teclado)
// carrinhoIcone.addEventListener('click', abrirCarrinho);
// carrinhoIcone.addEventListener('keydown', e => {
//   if (e.key === 'Enter' || e.key === ' ') abrirCarrinho();
// });

// // Eventos do modal
// fecharCarrinho.addEventListener('click', fecharCarrinhoFunc);
// modalOverlay.addEventListener('click', fecharCarrinhoFunc);
// limparCarrinho.addEventListener('click', limparCarrinhoFunc);

// // Acessibilidade: fecha modal com ESC
// carrinhoModal.addEventListener('keydown', e => {
//   if (e.key === 'Escape') fecharCarrinhoFunc();
//   // Focus trap: Tab/Shift+Tab cicla dentro do modal
//   const focusables = carrinhoModal.querySelectorAll('button, [tabindex]:not([tabindex="-1"])');
//   if (e.key === 'Tab') {
//     const first = focusables[0];
//     const last = focusables[focusables.length - 1];
//     if (e.shiftKey && document.activeElement === first) {
//       e.preventDefault();
//       last.focus();
//     } else if (!e.shiftKey && document.activeElement === last) {
//       e.preventDefault();
//       first.focus();
//     }
//   }
// });

// // Inicializa renderização do carrinho
// renderizarCarrinho();


// script.js

// Carrinho: array de objetos {id, nome, preco, quantidade}
let carrinho = [];

// Seletores principais
const produtos = document.querySelectorAll('.card-body, .cta');
const btnsAdicionar = document.querySelectorAll('.adicionar');
const carrinhoIcone = document.getElementById('carrinho-icone');
const contadorCarrinho = document.getElementById('contador-carrinho');
const carrinhoModal = document.getElementById('carrinho-modal');
const listaCarrinho = document.getElementById('lista-carrinho');
const fecharCarrinho = document.getElementById('fechar-carrinho');
const limparCarrinho = document.getElementById('limpar-carrinho');
const toast = document.getElementById('toast');
const modalOverlay = document.querySelector('.modal-overlay');

// Carrega carrinho do localStorage ao iniciar
function carregarCarrinho() {
  const salvo = localStorage.getItem('carrinho');
  carrinho = salvo ? JSON.parse(salvo) : [];
  atualizarContador();
}
carregarCarrinho();

// Salva carrinho no localStorage
function salvarCarrinho() {
  localStorage.setItem('carrinho', JSON.stringify(carrinho));
}

// Atualiza contador do ícone do carrinho
function atualizarContador() {
  const total = carrinho.reduce((acc, item) => acc + item.quantidade, 0);
  contadorCarrinho.textContent = total;
  // Anima badge ao adicionar
  contadorCarrinho.classList.add('animar');
  setTimeout(() => contadorCarrinho.classList.remove('animar'), 350);
}

// Exibe toast de feedback
function mostrarToast(msg) {
  toast.textContent = msg;
  toast.classList.add('ativo');
  setTimeout(() => toast.classList.remove('ativo'), 1200);
}

// Adiciona item ao carrinho (com limite de quantidade)
function adicionarAoCarrinho(id, nome, preco) {
  const existente = carrinho.find(item => item.id === id);
  const limite = 1; // máximo permitido por produto

  if (existente) {
    if (existente.quantidade < limite) {
      existente.quantidade += 1;
      mostrarToast(`"${nome}" adicionado ao carrinho!`);
    } else {
      mostrarToast(`Limite de ${limite} unidades para "${nome}" atingido!`);
    }
  } else {
    carrinho.push({ id, nome, preco, quantidade: 1 });
    mostrarToast(`"${nome}" adicionado ao carrinho!`);
  }

  salvarCarrinho();
  atualizarContador();
  renderizarCarrinho();
}

// Remove item do carrinho
function removerDoCarrinho(id) {
  carrinho = carrinho.filter(item => item.id !== id);
  salvarCarrinho();
  atualizarContador();
  renderizarCarrinho();
}

// Limpa todo o carrinho
function limparCarrinhoFunc() {
  carrinho = [];
  salvarCarrinho();
  atualizarContador();
  renderizarCarrinho();
}

// Renderiza conteúdo do carrinho no modal
function renderizarCarrinho() {
  listaCarrinho.innerHTML = '';
  if (carrinho.length === 0) {
    listaCarrinho.innerHTML = '<li>Seu carrinho está vazio.</li>';
    return;
  }
  carrinho.forEach(item => {
    const li = document.createElement('li');
    li.innerHTML = `
    <span class="quantidade">${item.quantidade}</span>
      <span class="nome">${item.nome}</span>
      <button class="remover" aria-label="Remover ${item.nome}" title="Remover">&times;</button>
    `;
    li.querySelector('.remover').addEventListener('click', () => removerDoCarrinho(item.id));
    listaCarrinho.appendChild(li);
  });
}

// Abre modal do carrinho
function abrirCarrinho() {
  carrinhoModal.classList.add('ativo');
  renderizarCarrinho();
  carrinhoModal.focus();
}

// Fecha modal do carrinho
function fecharCarrinhoFunc() {
  carrinhoModal.classList.remove('ativo');
  carrinhoIcone.focus();
}

// Eventos dos botões "Adicionar"
btnsAdicionar.forEach((btn, idx) => {
  btn.addEventListener('click', () => {
    const produto = produtos[idx];
    const id = produto.dataset.id;
    const nome = produto.dataset.nome;
    const preco = Number(produto.dataset.preco);
    adicionarAoCarrinho(id, nome, preco);
  });
});

// Evento do ícone do carrinho (mouse e teclado)
carrinhoIcone.addEventListener('click', abrirCarrinho);
carrinhoIcone.addEventListener('keydown', e => {
  if (e.key === 'Enter' || e.key === ' ') abrirCarrinho();
});

// Eventos do modal
fecharCarrinho.addEventListener('click', fecharCarrinhoFunc);
modalOverlay.addEventListener('click', fecharCarrinhoFunc);
limparCarrinho.addEventListener('click', limparCarrinhoFunc);

// Acessibilidade: fecha modal com ESC e mantém foco dentro
carrinhoModal.addEventListener('keydown', e => {
  if (e.key === 'Escape') fecharCarrinhoFunc();
  const focusables = carrinhoModal.querySelectorAll('button, [tabindex]:not([tabindex="-1"])');
  if (e.key === 'Tab') {
    const first = focusables[0];
    const last = focusables[focusables.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }
});

// Inicializa renderização do carrinho
renderizarCarrinho();
