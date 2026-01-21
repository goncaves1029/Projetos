// function alerts (){
//     let resposta = confirm( "Apenas Administradore tem acesso a essa Página. Deseja Continuar?");
//     if(resposta){
//         window.location.href = "pagina-admin.html";
//     }else{
//        alert("Acesso Negado.")
//     }
// }
// // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

// // Exemplo estático: horários ocupados por data
// const horariosIndisponiveis = {

// };


// document.getElementById("data").addEventListener("change", function () {
//   const dataSelecionada = this.value;
//   const selectHora = document.getElementById("hora");
//   const opcoes = selectHora.options;

//   // Salva o texto original na primeira vez
//   for (let i = 0; i < opcoes.length; i++) {
//     if (!opcoes[i].dataset.originalText) {
//       opcoes[i].dataset.originalText = opcoes[i].text;
//     }
//     opcoes[i].disabled = false;
//     opcoes[i].text = opcoes[i].dataset.originalText;
//   }

//   // Desativa os horários ocupados e marca como "Indisponível"
//   if (horariosIndisponiveis[dataSelecionada]) {
//     horariosIndisponiveis[dataSelecionada].forEach(hora => {
//       for (let i = 0; i < opcoes.length; i++) {
//         if (opcoes[i].value === hora) {
//           opcoes[i].disabled = true;
//           opcoes[i].text = `${opcoes[i].dataset.originalText} (Indisponível)`;
//         }
//       }
//     });
//   }
// });

// // 222222222222222222222222222222222222222222222222222222222222222222222222
// document.getElementById("form-agendamento").addEventListener("submit", function(e) {
//   const nome = document.getElementById("nome").value.trim();
//   const servico = document.getElementById("servico").value;
//   const data = document.getElementById("data").value;
//   const hora = document.getElementById("hora").value;

//   if (!nome || !servico || !data || !hora) {
//     e.preventDefault();
//     alert("⚠️ Por favor, preencha todos os campos antes de enviar.");
//   }
// });






// // 333333333333333333333333333333333333333333333333333333333333333333333333333333333333
// document.getElementById("data").addEventListener("change", function () {
//   const dataSelecionada = this.value;
//   const horaSelect = document.getElementById("hora");

//   if (!dataSelecionada) return;

//   fetch(`horarios.php?data=${dataSelecionada}`)
//     .then(response => {
//       if (!response.ok) {
//         throw new Error("Erro na resposta do servidor");
//       }
//       return response.json();
//     })
//     .then(horarios => {
//       horaSelect.innerHTML = '<option value="">Selecione</option>';
//       if (horarios.length === 0) {
//         const option = document.createElement("option");
//         option.textContent = "Nenhum horário disponível";
//         option.disabled = true;
//         horaSelect.appendChild(option);
//         return;
//       }
//       horarios.forEach(hora => {
//         const option = document.createElement("option");
//         option.value = hora;
//         option.textContent = hora;
//         horaSelect.appendChild(option);
//       });
//     })
//     .catch(error => {
//       console.error("notnull:", error);
//       horaSelect.innerHTML = '<option value="">Erro ao carregar</option>';
//     });


// const horarios = document.querySelectorAll('.horario');

// const select = document.querySelector('#seletor-horarios');
// const opcoes = select.querySelectorAll('option');

// opcoes.forEach(opcao => {
//   if (opcao.dataset.status === 'indisponivel') {
//     opcao.remove(); // Remove todas as opções indisponíveis
//   }
// });


// let alerta = "Ese horario já está oculpado";

// });


// Carrega horários disponíveis ao selecionar uma data
document.getElementById("data").addEventListener("change", function () {
  const dataSelecionada = this.value;
  const horaSelect = document.getElementById("hora");

  if (!dataSelecionada) return;

  // fetch(`https://sua-api.com/horarios?data=${dataSelecionada}`)



  // fetch(`http://localhost/barbearia/teste2.0/cronograma/horarios.php?data=${dataSelecionada}`)
  //   .then(response => {
  //     if (!response.ok) throw new Error("Erro na resposta do servidor");
  //     return response.json();


fetch(`https://rom-ducorte.byethost13.com/api/agendamentos.php?data=${dataSelecionada}`)
  .then(response => response.json())
  .then(dados => {
    console.log(dados);
    // Atualize a interface com os dados recebidos
  })
  .catch(error => console.error("Erro ao buscar agendamentos:", error));



    })
    .then(horarios => {
      horaSelect.innerHTML = '<option value="">Selecione</option>';
      if (horarios.length === 0) {
        const option = document.createElement("option");
        option.textContent = "Nenhum horário disponível";
        option.disabled = true;
        horaSelect.appendChild(option);
        return;
      }
      horarios.forEach(hora => {
        const option = document.createElement("option");
        option.value = hora;
        option.textContent = hora;
        horaSelect.appendChild(option);
      });
    })
    .catch(error => {
      console.error("Erro ao carregar horários:", error);
      horaSelect.innerHTML = '<option value="">Erro ao carregar</option>';
    });
});

// Envia o agendamento via API
document.getElementById("form-agendamento").addEventListener("submit", function (e) {
  e.preventDefault();

  const dados = {
    nome: document.getElementById("nome").value.trim(),
    servico: document.getElementById("servico").value,
    data: document.getElementById("data").value,
    hora: document.getElementById("hora").value
  };

  if (!dados.nome || !dados.servico || !dados.data || !dados.hora) {
    alert("⚠️ Por favor, preencha todos os campos.");
    return;
  }
// ########

  fetch("http://localhost/barbearia/teste2.0/cronograma/agendar.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json"
  },
  body: JSON.stringify(dados)
})
  
  // ########
  .then(response => {
    if (!response.ok) throw new Error("Erro ao agendar");
    return response.json();
  })
  .then(resposta => {
    alert("✅ Agendamento realizado com sucesso!");
  })
  .catch(error => {
    console.error("Erro:", error);
    alert("❌ Falha ao agendar. Tente novamente.");
  });
});