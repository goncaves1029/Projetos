document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('img-modal');
  const modalImg = document.getElementById('modal-img');
  const closeBtn = modal.querySelector('.close');
  const imgs = document.querySelectorAll('.fotogaleryhome');

  imgs.forEach(img => {
    img.addEventListener('click', () => {
      modalImg.src = img.src;
      modal.classList.add('open');
      modal.setAttribute('aria-hidden', 'false');
    });
  });

  // fechar no X
  closeBtn.addEventListener('click', () => {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
  });

  // fechar clicando fora da imagem
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('open');
      modal.setAttribute('aria-hidden', 'true');
    }
  });

  // fechar com ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      modal.classList.remove('open');
      modal.setAttribute('aria-hidden', 'true');
    }
  });

});

//  Chamar  barra lateral
function openSidebar() {
  document.getElementById("mySidebar").style.width = "250px";
    
  }

function closeSidebar() {
  document.getElementById("mySidebar").style.width = "0px";

}




