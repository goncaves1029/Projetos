<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% TESTE %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
 <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% TESTE %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Presença</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function toggleCultoExtra(sel){
            document.getElementById('culto_extra').disabled = (sel.value !== 'outro');
            if(sel.value !== 'outro') document.getElementById('culto_extra').value = '';
        }
    </script>
</head>
<body>
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@teste@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
 

    <head>

    <div class="fab">
  <button class="main">
  </button>
  <ul>
    <li>
      <label for="opcao1">Resgistros</label>
       <a href="login.php"></a>
      <button id="opcao1" onclick="window.location.href='manage.php'">
      ⎈
      </button>
    </li>
    <!-- <li>
      <label for="opcao2">Opção 2</label>
      <button id="opcao2" onclick="window.location.href='login.php'">
      ⎗
      </button>
    </li>
   
    <li>
      </button>
      <label for="opcao4">Opção 4</label>
      <button id="opcao4" onclick="window.location.href='login.php'">
      ☏
       </button> -->
    </li>
  </ul>
</div>

 <script>function toggleFAB(fab){
	if(document.querySelector(fab).classList.contains('show')){
  	document.querySelector(fab).classList.remove('show');
  }else{
  	document.querySelector(fab).classList.add('show');
  }
}

document.querySelector('.fab .main').addEventListener('click', function(){
	toggleFAB('.fab');
});

document.querySelectorAll('.fab ul li button').forEach((item)=>{
	item.addEventListener('click', function(){
		toggleFAB('.fab');
	});
});</script>

 </head>

<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@teste@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ --> 
   
    <h1>Lagoinha Macaíba</h1>

    <form method="post" action="resultado.php">
        <h2>Culto</h2>
        <label>Culto:</label>
        <select name="culto" onchange="toggleCultoExtra(this)" required>
            <option value="">Selecione...</option>
            <option value="Domingo Manhã">Domingo Manhã</option>
            <option value="Domingo Noite">Domingo Noite</option>
            <option value="Culto Fé">Culto Fé</option>
            <option value="outro">Outro (especifique)</option>
        </select>
        <input type="text" id="culto_extra" name="culto_extra" placeholder="Digite o culto ou evento" disabled>
        <br><br>

        <h3>Data: <input type="date" name="data" required> Hora: <input type="time" name="hora" required></h3>

        <h2>Pessoas</h2>
        <label>Adultos:</label>
        <input type="number" name="adultos" min="0" required><br><br>

        <label>Crianças/Templo:</label>
        <input type="number" name="criancas_templo" min="0" required><br><br>

        <label>Kids Baby:</label>
        <input type="number" name="kids_baby" min="0" required><br><br>

        <label>Kids:</label>
        <input type="number" name="kids" min="0" required><br><br>

        <h2>Voluntários</h2>
        <label>Link:</label>
        <input type="number" name="link" min="0" required><br><br>

        <label>Louvor:</label>
        <input type="number" name="louvor" min="0" required><br><br>

        <label>Comunicação:</label>
        <input type="number" name="comunicacao" min="0" required><br><br>

        <label>Voluntários Gerais:</label>
        <input type="number" name="voluntarios" min="0" required><br><br>

        <label>Pastores:</label>
        <input type="number" name="pastor" min="0" required><br><br>

        <label>Pregador (nomes separados por vírgula ou número):</label>
        <input type="text" name="pregador" required><br><br>

        <h2>Estacionamento</h2>
        <label>Carros:</label>
        <input type="number" name="carros" min="0" required><br><br>

        <label>Motos:</label>
        <input type="number" name="motos" min="0" required><br><br>

        <label>Bicicletas:</label>
        <input type="number" name="bicicletas" min="0" required><br><br>

        <button type="submit">Registrar</button>
    </form>
</body>
</html>