<?php
// painel-acessos.php (UI bonita) - funciona com painel-api.php
@ini_set('display_errors','0'); @ini_set('log_errors','1');
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel de Acessos - Monitoramento em Tempo Real</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{
    font-family:'Segoe UI',Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72 0%,#2a5298 100%);
    color:#333; min-height:100vh; padding:20px;
  }
  .container{
    max-width:1400px;margin:0 auto;background:rgba(255,255,255,.95);
    border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,.3); overflow:hidden;
  }
  .header{
    background:linear-gradient(135deg,#2c3e50 0%,#3498db 100%);
    color:#fff; padding:20px; text-align:center;
  }
  .header h1{font-size:2.2em;margin-bottom:6px}
  .stats{
    display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:15px; padding:20px; background:#f8f9fa;
  }
  .stat-card{
    background:#fff; padding:20px; border-radius:10px; text-align:center;
    box-shadow:0 3px 10px rgba(0,0,0,.08); border-left:4px solid #3498db;
  }
  .stat-card.google{ border-left-color: #4285f4; }
  .stat-card.facebook{ border-left-color: #ffc107; }
  .stat-card.br{ border-left-color: #27ae60; }
  .stat-card.internacional{ border-left-color: #e74c3c; }
  .stat-number{font-size:2.2em;font-weight:800;color:#2c3e50}
  .stat-label{color:#7f8c8d;font-size:.9em;text-transform:uppercase;letter-spacing:1px}

  .controls{
    padding:15px 20px; background:#34495e; display:flex; flex-wrap:wrap;
    gap:10px; align-items:center;
  }
  .controls button{
    padding:10px 20px; border:none; border-radius:6px; cursor:pointer;
    font-weight:700; transition:.2s; color:#fff;
  }
  .btn-play{background:#27ae60} .btn-pause{background:#e74c3c} .btn-clear{background:#f39c12}
  .controls button:hover{transform:translateY(-2px)}
  .sound-control{display:flex;align-items:center;gap:8px;color:#fff}
  .auto-refresh{display:flex;align-items:center;gap:10px;color:#fff;margin-left:auto}
  .refresh-indicator{width:10px;height:10px;border-radius:50%;background:#27ae60;animation:pulse 1s infinite}
  @keyframes pulse{0%{opacity:1}50%{opacity:.5}100%{opacity:1}}

  .access-list{max-height:600px; overflow-y:auto;}
  .access-item{
    display:grid; grid-template-columns: 160px 1fr 380px 150px 220px;
    gap:15px; padding:14px 20px; border-bottom:1px solid #ecf0f1; align-items:center;
    transition:background .3s;
  }
  .access-item:hover{background:#f8f9fa}
  .access-item.new{background:#d4edda; animation:highlight 1.4s ease}
  .access-item.google-bot{background:#e8f4fd; border-left:4px solid #4285f4;}
  .access-item.facebook-bot{background:#fff9e6; border-left:4px solid #ffc107;}
  @keyframes highlight{0%{background:#d4edda}100%{background:transparent}}
  .timestamp{color:#7f8c8d;font-size:.9em}
  .ip-address{font-family:ui-monospace,Consolas,monospace;font-weight:700;color:#2c3e50;overflow:hidden;text-overflow:ellipsis}
  .country{display:flex;align-items:center;gap:8px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .flag{font-size:1.3em}
  .type-br{color:#27ae60;font-weight:700}
  .type-internacional{color:#e74c3c;font-weight:700}
  .type-googlebot{color:#4285f4;font-weight:700; background:#e8f4fd; padding:2px 8px; border-radius:4px;}
  .type-facebookbot{color:#e67e22;font-weight:700; background:#fff9e6; padding:2px 8px; border-radius:4px;}
  .org{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#2c3e50}

  /* Bot badges */
  .bot-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 6px;border-radius:4px;font-size:0.8em;font-weight:600;margin-left:8px;}
  .google-badge{background:#4285f4;color:white;}
  .facebook-badge{background:#ffc107;color:#333;}

  /* responsivo */
  @media (max-width:1100px){
    .access-item{grid-template-columns: 140px 1fr 1fr 120px}
    .org{display:none}
  }
  @media (max-width:800px){
    .access-item{grid-template-columns: 130px 1fr 1fr}
    .access-item div:last-child{display:none}
  }
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>🔍 Painel de Monitoramento de Acessos</h1>
      <p>Monitoramento em tempo real dos acessos ao seu site</p>
    </div>

    <div class="stats">
      <div class="stat-card"><div class="stat-number" id="totalAcessos">0</div><div class="stat-label">Total de Acessos</div></div>
      <div class="stat-card br"><div class="stat-number" id="acessosBR">0</div><div class="stat-label">Acessos do Brasil</div></div>
      <div class="stat-card internacional"><div class="stat-number" id="acessosInternacional">0</div><div class="stat-label">Acessos Internacionais</div></div>
      <div class="stat-card google"><div class="stat-number" id="acessosGooglebot">0</div><div class="stat-label">Googlebots</div></div>
      <div class="stat-card facebook"><div class="stat-number" id="acessosFacebookbot">0</div><div class="stat-label">Facebook Bots</div></div>
    </div>

    <div class="controls">
      <button class="btn-play" onclick="startMonitoring()">▶️ Iniciar Monitoramento</button>
      <button class="btn-pause" onclick="stopMonitoring()">⏸️ Pausar</button>
      <button class="btn-clear" onclick="clearLog()">🗑️ Limpar Lista</button>

      <div class="sound-control">
        <label><input type="checkbox" id="soundEnabled" checked> 🔊 Som de Notificação</label>
      </div>

      <div class="auto-refresh">
        <div class="refresh-indicator" id="refreshIndicator"></div>
        <span>Atualização automática: <span id="refreshStatus">Ativa</span></span>
      </div>
    </div>

    <div class="access-list" id="accessList">
      <div class="access-item" style="text-align:center;padding:20px;color:#7f8c8d;">Carregando acessos...</div>
    </div>
  </div>

<script>
  // ========= Config =========
  const API = 'painel-api.php';        // mesma pasta do painel
  const REFRESH_MS = 2000;             // 2s

  // ========= Estado =========
  let isMonitoring = false;
  let timer = null;
  let lastSince = 0;
  // CORREÇÃO: Armazena apenas pelo IP para evitar duplicação
  const store = new Map();

  // ========= Util =========
  function getFlagEmoji(countryCode){
    if(!countryCode || countryCode === 'UN') return '🌐';
    const codePoints = countryCode.toUpperCase().split('').map(c=>127397 + c.charCodeAt());
    return String.fromCodePoint(...codePoints);
  }
  function pad(n){return String(n).padStart(2,'0')}
  function formatDateFromEpochSec(sec){
    const d = new Date(sec*1000);
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
  }
  function isTodayEpochSec(sec){
    const d = new Date(sec*1000);
    const now = new Date();
    return d.getFullYear()===now.getFullYear() && d.getMonth()===now.getMonth() && d.getDate()===now.getDate();
  }
  // CORREÇÃO: Usa apenas IP como chave para evitar duplicação
  function rowKey(ip){ return ip }

  // ========= Som (alto e agudo, estilo "mensagem") =========
  function playNotification(){
    if(!document.getElementById('soundEnabled').checked) return;
    try{
      const ctx = new (window.AudioContext||window.webkitAudioContext)();
      const now = ctx.currentTime;

      // Dois toques curtos em frequências altas (~iPhone vibe, sem usar arquivo)
      function ping(freq, start, dur, vol){
        const o = ctx.createOscillator();
        const g = ctx.createGain();
        o.type = 'triangle';           // timbre brilhante
        o.frequency.setValueAtTime(freq, start);
        g.gain.setValueAtTime(0.0001, start);
        g.gain.exponentialRampToValueAtTime(vol, start + 0.02);
        g.gain.exponentialRampToValueAtTime(0.0001, start + dur);
        o.connect(g); g.connect(ctx.destination);
        o.start(start); o.stop(start + dur + 0.02);
      }
      // sequência (dois "pings" ascendentes)
      ping(1300, now + 0.00, 0.16, 0.6);
      ping(1600, now + 0.18, 0.18, 0.6);
    }catch(e){}
  }

  // ========= DOM =========
  const elList = document.getElementById('accessList');
  const elTotal = document.getElementById('totalAcessos');
  const elBR = document.getElementById('acessosBR');
  const elNBR = document.getElementById('acessosInternacional');
  const elGoogle = document.getElementById('acessosGooglebot');
  const elFacebook = document.getElementById('acessosFacebookbot');
  const elRefreshIndicator = document.getElementById('refreshIndicator');
  const elRefreshStatus = document.getElementById('refreshStatus');

  function recalcStats(){
    let total=0, br=0, nbr=0, google=0, facebook=0;
    for(const [,r] of store){
      total++;
      if((r.country||'').toUpperCase()==='BR') br++; else nbr++;
      if(r.is_google) google++;
      if(r.is_facebook) facebook++;
    }
    elTotal.textContent = total;
    elBR.textContent = br;
    elNBR.textContent = nbr;
    elGoogle.textContent = google;
    elFacebook.textContent = facebook;
  }

  function renderEntries(entries){
    // mais recentes primeiro
    entries.sort((a,b)=> b.mtime - a.mtime);
    let newCount = 0;

    for(const r of entries){
      const key = rowKey(r.ip);
      
      // CORREÇÃO: Se já existe o IP, atualiza apenas se for mais recente
      if(store.has(key)) {
        const existing = store.get(key);
        if(r.mtime > existing.mtime) {
          // Atualiza com dados mais recentes
          store.set(key, r);
          // Não incrementa newCount pois não é novo, só atualizado
        }
        continue;
      }
      
      // CORREÇÃO: Adiciona apenas se for realmente novo
      store.set(key, r);
      newCount++;

      const isBR = (String(r.country||'').toUpperCase()==='BR');
      const isGoogle = !!r.is_google;
      const isFacebook = !!r.is_facebook;
      
      let tipoClass = '';
      let tipoText = '';
      let itemClass = 'access-item new';
      
      if (isGoogle) {
        tipoClass = 'type-googlebot';
        tipoText = 'Googlebot';
        itemClass += ' google-bot';
      } else if (isFacebook) {
        tipoClass = 'type-facebookbot';
        tipoText = 'Facebook Bot';
        itemClass += ' facebook-bot';
      } else {
        tipoClass = isBR ? 'type-br' : 'type-internacional';
        tipoText = isBR ? 'Brasil' : 'Internacional';
      }
      
      const flag = getFlagEmoji(r.country);

      // CORREÇÃO: Formata a localização exatamente como no print
      let locationText = '';
      if (r.country && r.city && r.region) {
        locationText = `${r.country} ${r.country_name || ''} - ${r.city}, ${r.region}`;
      } else if (r.country && r.city) {
        locationText = `${r.country} ${r.country_name || ''} - ${r.city}`;
      } else if (r.country && r.region) {
        locationText = `${r.country} ${r.country_name || ''} - ${r.region}`;
      } else if (r.country) {
        locationText = `${r.country} ${r.country_name || ''}`;
      } else {
        locationText = 'Localização desconhecida';
      }

      // Remove espaços extras e formata corretamente
      locationText = locationText.replace(/\s+/g, ' ').trim();

      const div = document.createElement('div');
      div.className = itemClass;
      div.innerHTML = `
        <div class="timestamp">${formatDateFromEpochSec(r.mtime)}</div>
        <div class="ip-address" title="${r.ip}">
          ${r.ip}
          ${isGoogle ? '<span class="bot-badge google-badge">🔍 Google</span>' : ''}
          ${isFacebook ? '<span class="bot-badge facebook-badge">👤 Facebook</span>' : ''}
        </div>
        <div class="country" title="${locationText}">
          <span class="flag">${flag}</span>
          ${locationText}
        </div>
        <div class="${tipoClass}">${tipoText}</div>
        <div class="org" title="${r.org||''}">${r.org || '—'}</div>
      `;
      elList.prepend(div);
    }

    if(newCount>0) playNotification();
    recalcStats();
  }

  async function fetchTick(){
    try{
      const url = `${API}?since=${lastSince}&limit=200&enrich=1&_t=${Date.now()}`;
      const res = await fetch(url,{cache:'no-store'});
      if(!res.ok) throw new Error('HTTP '+res.status);
      const j = await res.json();

      if(Array.isArray(j.entries) && j.entries.length){
        renderEntries(j.entries);
        const maxM = j.entries.reduce((m,e)=>Math.max(m, e.mtime||0), lastSince);
        if(maxM>lastSince) lastSince = maxM;
      }
    }catch(e){
      // silencioso
    }
  }

  async function firstLoad(){
    elList.innerHTML = '<div class="access-item" style="text-align:center;padding:20px;color:#7f8c8d;">Carregando acessos...</div>';
    try{
      const res = await fetch(`${API}?limit=200&enrich=1&_t=${Date.now()}`, {cache:'no-store'});
      const j = await res.json();
      elList.innerHTML = '';
      if(Array.isArray(j.entries)){
        renderEntries(j.entries);
        lastSince = j.entries.reduce((m,e)=>Math.max(m, e.mtime||0), 0);
      }
    }catch(e){
      elList.innerHTML = '<div class="access-item" style="text-align:center;padding:20px;color:#e74c3c;">Erro ao carregar dados.</div>';
    }
  }

  // ========= Controles =========
  function startMonitoring(){
    if(isMonitoring) return;
    isMonitoring = true;
    elRefreshStatus.textContent = 'Ativa'; elRefreshIndicator.style.background = '#27ae60';
    fetchTick(); // dispara logo
    timer = setInterval(fetchTick, REFRESH_MS);
  }
  function stopMonitoring(){
    if(!isMonitoring) return;
    isMonitoring = false;
    elRefreshStatus.textContent = 'Pausada'; elRefreshIndicator.style.background = '#e74c3c';
    clearInterval(timer); timer = null;
  }
  function clearLog(){
    if(!confirm('Limpar a lista exibida? (não apaga os caches)')) return;
    store.clear();
    elList.innerHTML = '';
    lastSince = 0;
    recalcStats();
  }

  // ========= Boot =========
  document.addEventListener('DOMContentLoaded', async ()=>{
    await firstLoad();
    startMonitoring();
  });
</script>
</body>
</html>
