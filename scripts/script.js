const form = document.getElementById("loginForm");
const erro = document.getElementById("erro");

form.addEventListener("submit", function(event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;

    if (email === "" || senha === "") {
        erro.style.display = "block";
        erro.innerText = "Preencha todos os campos!";
    } 
    else if (email === "admin@gmail.com" && senha === "1234") {
        erro.style.display = "none";

        window.location.href = "dashboard.html";
    } 
    else {
        erro.style.display = "block";
        erro.innerText = "E-mail ou senha incorretos!";
    }
});

                     //PARTE OTTO//
 function setActive(e, el) {
    e.preventDefault();
    document.querySelectorAll('#sidebar .nav-link').forEach(l => {
      l.classList.remove('active');
      l.classList.add('text-white');
    });
    el.classList.add('active');
    el.classList.remove('text-white');
  }
 
  function gerarId() {
    return Math.random().toString(16).slice(2, 10);
  }
 
  function badgePorTipo(tipo) {
    const map = {
      'Velocidade':  'text-bg-primary',
      'Temperatura': 'text-bg-warning',
      'Energia':     'text-bg-success',
      'Falha':       'text-bg-danger',
      'Localização': 'text-bg-secondary',
    };
    return map[tipo] || 'text-bg-secondary';
  }
 
  function cadastrarSensor() {
    const nome     = document.getElementById('sensorNome').value.trim();
    const loc      = document.getElementById('sensorLocalizacao').value.trim();
    const tipo     = document.getElementById('sensorTipo').value;
    const tremSel  = document.getElementById('sensorTrem');
    const trem     = tremSel.options[tremSel.selectedIndex]?.text || '';
 
    if (!nome || !loc || !trem || tremSel.value === '') {
      alert('Preencha todos os campos do sensor antes de cadastrar.');
      return;
    }
 
    const id = gerarId();
    const badge = badgePorTipo(tipo);
    const tbody = document.getElementById('sensorsTable');
 
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td class="px-4 td-id">${id}</td>
      <td class="px-3">${nome}</td>
      <td class="px-3">${loc}</td>
      <td class="px-3"><span class="badge ${badge}">${tipo}</span></td>
      <td class="px-3">${trem.split(' (')[0]}</td>
      <td class="px-3"><button class="btn btn-sm btn-link delete-btn p-0" onclick="excluirSensor(this)"><i class="bi bi-trash"></i></button></td>
    `;
 
    tbody.appendChild(tr);
 
    document.getElementById('sensorNome').value = '';
    document.getElementById('sensorLocalizacao').value = '';
    document.getElementById('sensorTipo').value = 'Velocidade';
    document.getElementById('sensorTrem').value = '';
 
    tr.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
 
  function cadastrarTrem() {
    const nome   = document.getElementById('tremNome').value.trim();
    const codigo = document.getElementById('tremCodigo').value.trim();
    const modelo = document.getElementById('tremModelo').value.trim();
 
    if (!nome || !codigo || !modelo) {
      alert('Preencha todos os campos do trem antes de cadastrar.');
      return;
    }
 
    const select = document.getElementById('sensorTrem');
    const opt = document.createElement('option');
    opt.value = nome;
    opt.textContent = `${nome} (${codigo})`;
    select.appendChild(opt);
 
    document.getElementById('tremNome').value = '';
    document.getElementById('tremCodigo').value = '';
    document.getElementById('tremModelo').value = '';
 
    alert(`Trem "${nome} (${codigo})" cadastrado com sucesso e disponível no seletor de sensores!`);
  }
 
  function excluirSensor(btn) {
    const row = btn.closest('tr');
    row.style.transition = 'opacity 0.2s';
    row.style.opacity = '0';
    setTimeout(() => row.remove(), 200);
  }