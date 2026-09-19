{{-- ============================================================
     Chatbot FAQ para visitantes (landing pública)
     - 100% local, sin API ni costo. Respuestas por reglas.
     - Usa los datos reales de $plans para precios y planes.
     ============================================================ --}}
@php
    // Preparamos los datos de planes para el JS (precios siempre reales)
    $botPlans = ($plans ?? collect())->map(fn($p) => [
        'name'    => $p->name,
        'monthly' => (float) $p->price_monthly,
        'yearly'  => (float) $p->price_yearly,
        'members' => (int) $p->max_members,
        'popular' => (bool) $p->is_popular,
    ])->values();
@endphp

<style>
    /* ── Burbuja flotante ── */
    .gbot-launcher{position:fixed;bottom:24px;right:24px;z-index:9999;width:62px;height:62px;border-radius:50%;
        background:linear-gradient(135deg,#7c3aed,#ec4899);border:none;cursor:pointer;color:#fff;font-size:26px;
        box-shadow:0 8px 24px rgba(124,58,237,.45);display:flex;align-items:center;justify-content:center;
        transition:transform .2s, box-shadow .2s;}
    .gbot-launcher:hover{transform:translateY(-3px) scale(1.05);box-shadow:0 12px 30px rgba(236,72,153,.55);}
    .gbot-launcher .gbot-dot{position:absolute;top:6px;right:8px;width:12px;height:12px;background:#34d399;border:2px solid #fff;border-radius:50%;}

    /* ── Ventana ── */
    .gbot-window{position:fixed;bottom:100px;right:24px;z-index:9999;width:380px;max-width:calc(100vw - 32px);
        height:560px;max-height:calc(100vh - 140px);background:#fff;border-radius:20px;overflow:hidden;
        box-shadow:0 24px 60px rgba(15,10,30,.32);display:none;flex-direction:column;
        font-family:'Inter',sans-serif;animation:gbotIn .25s ease;}
    .gbot-window.open{display:flex;}
    @keyframes gbotIn{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}

    .gbot-header{background:linear-gradient(135deg,#1e1045,#4c1d95,#7c3aed);color:#fff;padding:18px 20px;display:flex;align-items:center;gap:12px;}
    .gbot-header .av{width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:22px;}
    .gbot-header h4{font-size:16px;font-weight:700;margin:0;}
    .gbot-header .st{font-size:12px;color:rgba(255,255,255,.75);display:flex;align-items:center;gap:6px;margin-top:2px;}
    .gbot-header .st::before{content:'';width:8px;height:8px;background:#34d399;border-radius:50%;display:inline-block;}
    .gbot-header .gbot-close{margin-left:auto;background:none;border:none;color:#fff;font-size:20px;cursor:pointer;opacity:.8;}
    .gbot-header .gbot-close:hover{opacity:1;}

    .gbot-body{flex:1;overflow-y:auto;padding:18px;background:#f8f7fc;display:flex;flex-direction:column;gap:12px;}
    .gbot-msg{max-width:84%;padding:11px 14px;border-radius:14px;font-size:14px;line-height:1.55;white-space:pre-line;}
    .gbot-msg.bot{background:#fff;color:#1f2937;border:1px solid #ece9f6;border-bottom-left-radius:4px;align-self:flex-start;box-shadow:0 1px 2px rgba(0,0,0,.03);}
    .gbot-msg.user{background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border-bottom-right-radius:4px;align-self:flex-end;}
    .gbot-msg a{color:#7c3aed;font-weight:600;}
    .gbot-msg.user a{color:#fff;text-decoration:underline;}

    .gbot-chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:2px;}
    .gbot-chip{background:#fff;border:1.5px solid #d8d2f0;color:#6d28d9;font-size:13px;font-weight:600;
        padding:8px 14px;border-radius:99px;cursor:pointer;transition:all .15s;}
    .gbot-chip:hover{background:#7c3aed;color:#fff;border-color:#7c3aed;}

    .gbot-foot{padding:12px;border-top:1px solid #eee;background:#fff;display:flex;gap:8px;}
    .gbot-foot input{flex:1;border:1.5px solid #e5e7eb;border-radius:99px;padding:11px 16px;font-size:14px;outline:none;font-family:inherit;}
    .gbot-foot input:focus{border-color:#7c3aed;}
    .gbot-foot button{width:44px;height:44px;border-radius:50%;border:none;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;font-size:16px;cursor:pointer;flex-shrink:0;}
    .gbot-foot button:hover{opacity:.92;}

    .gbot-body::-webkit-scrollbar{width:6px;}
    .gbot-body::-webkit-scrollbar-thumb{background:#d8d2f0;border-radius:3px;}

    @media(max-width:480px){
        .gbot-window{right:8px;bottom:88px;height:calc(100vh - 110px);}
        .gbot-launcher{bottom:16px;right:16px;}
    }
</style>

<!-- Burbuja -->
<button class="gbot-launcher" id="gbotLauncher" aria-label="Abrir chat de ayuda">
    <i class="fas fa-comment-dots"></i>
    <span class="gbot-dot"></span>
</button>

<!-- Ventana de chat -->
<div class="gbot-window" id="gbotWindow" role="dialog" aria-label="Asistente GymSaaS">
    <div class="gbot-header">
        <div class="av">🤖</div>
        <div>
            <h4>Asistente GymSaaS</h4>
            <div class="st">En línea · responde al instante</div>
        </div>
        <button class="gbot-close" id="gbotClose" aria-label="Cerrar">&times;</button>
    </div>
    <div class="gbot-body" id="gbotBody"></div>
    <div class="gbot-foot">
        <input type="text" id="gbotInput" placeholder="Escribe tu pregunta..." autocomplete="off">
        <button id="gbotSend" aria-label="Enviar"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
(function(){
    const PLANS = @json($botPlans);
    const URL_REGISTRO = "{{ route('register.gym') }}";
    const URL_LOGIN    = "{{ route('login') }}";

    const launcher = document.getElementById('gbotLauncher');
    const win      = document.getElementById('gbotWindow');
    const body     = document.getElementById('gbotBody');
    const input    = document.getElementById('gbotInput');
    const sendBtn  = document.getElementById('gbotSend');
    const closeBtn = document.getElementById('gbotClose');
    let greeted = false;

    const money = n => @json(currency_symbol()) + Number(n).toLocaleString('es-MX');

    // ── Respuestas por tema ──
    function answerPlanes(){
        if(!PLANS.length) return "Tenemos varios planes mensuales y anuales. Escríbeme \"prueba\" para empezar gratis 30 días.";
        let t = "💳 Estos son nuestros planes:\n";
        PLANS.forEach(p=>{
            t += `\n• ${p.name}${p.popular?' ⭐':''}: ${money(p.monthly)}/mes — o ${money(p.yearly)}/año\n   Hasta ${p.members>=999999?'socios ilimitados':p.members+' socios'}`;
        });
        t += "\n\nTodos incluyen 30 días de prueba gratis. ¿Quieres comenzar? 👉 <a href='"+URL_REGISTRO+"'>Crear cuenta</a>";
        return t;
    }
    function answerFunciones(){
        return "⚙️ Con GymSaaS Pro puedes gestionar:\n\n"+
            "• 👥 Socios y membresías (vencimientos y alertas)\n"+
            "• 💳 Pagos, recibos y facturación\n"+
            "• 📅 Clases grupales e inscripciones\n"+
            "• 🏋️ Entrenadores y especialidades\n"+
            "• ✅ Control de asistencia (check-in/out)\n"+
            "• 📊 Reportes e indicadores en tiempo real\n"+
            "• 📦 Inventario de equipos\n\n"+
            "¿Te muestro los precios? Escribe \"planes\".";
    }
    function answerPrueba(){
        return "🚀 ¡Empezar es muy fácil!\n\n"+
            "1. Crea tu cuenta con el nombre de tu gimnasio\n"+
            "2. Configura planes, entrenadores y clases\n"+
            "3. Registra a tus socios y ¡listo!\n\n"+
            "Tienes 30 días gratis, sin tarjeta.\n👉 <a href='"+URL_REGISTRO+"'>Comenzar prueba gratis</a>";
    }
    function answerContacto(){
        return "📩 Estamos para ayudarte:\n\n"+
            "• ¿Ya tienes cuenta? <a href='"+URL_LOGIN+"'>Inicia sesión</a>\n"+
            "• ¿Nuevo? <a href='"+URL_REGISTRO+"'>Regístrate gratis</a>\n"+
            "• Dentro de la plataforma tienes un módulo de Soporte para abrir tickets.\n\n"+
            "Cuéntame qué necesitas y te oriento.";
    }
    function answerDefault(){
        return "No estoy seguro de haber entendido 🤔. Puedo ayudarte con:\n\n"+
            "• Planes y precios\n• Funciones del sistema\n• Cómo empezar la prueba gratis\n• Contacto y soporte\n\n"+
            "Toca un botón o reformula tu pregunta.";
    }

    // ── Motor de reglas (coincidencia por palabras clave) ──
    function route(text){
        const t = text.toLowerCase();
        const has = (...w)=>w.some(x=>t.includes(x));
        if(has('precio','plan','costo','cuánto','cuanto','cuesta','tarifa','mensual','anual','pago men')) return answerPlanes();
        if(has('funcion','función','sirve','hace','caracter','qué es','que es','incluye','módulo','modulo','asistencia','clase','inventario','reporte','socio')) return answerFunciones();
        if(has('prueba','gratis','empezar','comenzar','registr','crear cuenta','demo','probar','iniciar')) return answerPrueba();
        if(has('contacto','contactar','soporte','ayuda','hablar','correo','email','tel','whats','agendar')) return answerContacto();
        if(has('hola','buenas','buenos','hey','qué tal','que tal')) return "¡Hola! 👋 Soy el asistente de GymSaaS Pro. ¿En qué te ayudo hoy?";
        if(has('gracias','genial','perfecto','ok')) return "¡Con gusto! 😊 Si quieres, puedo mostrarte los planes o ayudarte a empezar la prueba gratis.";
        return answerDefault();
    }

    // ── UI helpers ──
    function scrollDown(){ body.scrollTop = body.scrollHeight; }
    function addMsg(html, who){
        const d=document.createElement('div');
        d.className='gbot-msg '+who;
        d.innerHTML=html;
        body.appendChild(d);
        scrollDown();
    }
    function addChips(items){
        const wrap=document.createElement('div');
        wrap.className='gbot-chips';
        items.forEach(it=>{
            const c=document.createElement('button');
            c.className='gbot-chip';
            c.textContent=it.label;
            c.onclick=()=>{ handleUser(it.label, it.intent); };
            wrap.appendChild(c);
        });
        body.appendChild(wrap);
        scrollDown();
    }
    function botReply(text){
        setTimeout(()=>addMsg(text,'bot'), 250);
    }

    const MENU = [
        {label:'💳 Planes y precios', intent:'planes'},
        {label:'⚙️ Funciones', intent:'funciones'},
        {label:'🚀 Probar gratis', intent:'prueba'},
        {label:'📩 Contacto', intent:'contacto'},
    ];

    function intentAnswer(intent){
        switch(intent){
            case 'planes': return answerPlanes();
            case 'funciones': return answerFunciones();
            case 'prueba': return answerPrueba();
            case 'contacto': return answerContacto();
            default: return null;
        }
    }

    function handleUser(text, intent){
        addMsg(text.replace(/</g,'&lt;'),'user');
        const ans = intent ? intentAnswer(intent) : route(text);
        botReply(ans);
        setTimeout(()=>addChips(MENU), 500);
    }

    function greet(){
        if(greeted) return;
        greeted = true;
        addMsg("¡Hola! 👋 Soy el asistente virtual de <b>GymSaaS Pro</b>.\nRespondo tus dudas sobre la plataforma. ¿Qué te gustaría saber?", 'bot');
        addChips(MENU);
    }

    function openWin(){ win.classList.add('open'); greet(); setTimeout(()=>input.focus(),100); }
    function closeWin(){ win.classList.remove('open'); }

    launcher.addEventListener('click', ()=> win.classList.contains('open') ? closeWin() : openWin());
    closeBtn.addEventListener('click', closeWin);
    sendBtn.addEventListener('click', ()=>{
        const v=input.value.trim(); if(!v) return; input.value=''; handleUser(v);
    });
    input.addEventListener('keydown', e=>{
        if(e.key==='Enter'){ const v=input.value.trim(); if(!v) return; input.value=''; handleUser(v); }
    });
})();
</script>
