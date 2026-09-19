@extends('layouts.superadmin')
@section('title','Comunicados')
@section('page-title','Comunicados masivos')
@section('breadcrumb','Envía un anuncio a los gimnasios')

@section('content')
<div class="grid" style="grid-template-columns:1.3fr 1fr;align-items:start;gap:20px">
    <div class="card">
        <form method="POST" action="{{ route('superadmin.announcements.send') }}" id="annForm">
            @csrf
            <div class="form-group"><label>Título *</label><input name="title" value="{{ old('title') }}" class="form-control" placeholder="Ej: Nueva función disponible" required></div>
            <div class="form-group"><label>Mensaje *</label><textarea name="body" class="form-control" rows="5" placeholder="Escribe el comunicado..." required>{{ old('body') }}</textarea></div>

            <div class="grid" style="grid-template-columns:1fr 1fr">
                <div class="form-group"><label>Destinatarios *</label>
                    <select name="target" id="targetSel" class="form-control" required>
                        <option value="all">Todos los gimnasios</option>
                        <option value="plan">Solo un plan</option>
                    </select>
                </div>
                <div class="form-group" id="planWrap" style="display:none"><label>Plan</label>
                    <select name="plan_id" class="form-control">
                        @foreach($plans as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label>Color</label><input type="color" name="color" value="#ec4899" class="form-control" style="height:44px;padding:4px"></div>
            </div>

            <button class="btn btn-primary" onclick="return confirm('¿Enviar este comunicado?')"><i class="fas fa-paper-plane"></i> Enviar comunicado</button>
        </form>
    </div>

    <div class="card">
        <h3 style="font-size:15px;font-weight:700;margin-bottom:12px">Vista previa</h3>
        <div style="display:flex;gap:11px;padding:13px;border:1px solid var(--border);border-radius:12px;background:#faf9fd">
            <span id="pvIcon" style="width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;background:#ec4899"><i class="fas fa-bullhorn"></i></span>
            <div>
                <div id="pvTitle" style="font-weight:600;font-size:13.5px">Título del comunicado</div>
                <div id="pvBody" style="font-size:12.5px;color:var(--muted);margin-top:2px">El mensaje aparecerá aquí…</div>
            </div>
        </div>
        <p style="font-size:13px;color:var(--muted);margin-top:16px"><i class="fas fa-circle-info"></i> El comunicado llega a la campana de notificaciones de cada gimnasio seleccionado. Llegará a <strong>{{ $gymCount }}</strong> gimnasio(s) si eliges "Todos".</p>
    </div>
</div>

<script>
    const ts=document.getElementById('targetSel'), pw=document.getElementById('planWrap');
    ts.addEventListener('change',()=>pw.style.display = ts.value==='plan' ? 'block':'none');
    const f=document.getElementById('annForm');
    f.title.addEventListener('input',e=>document.getElementById('pvTitle').textContent=e.target.value||'Título del comunicado');
    f.body.addEventListener('input',e=>document.getElementById('pvBody').textContent=e.target.value||'El mensaje aparecerá aquí…');
    f.color.addEventListener('input',e=>document.getElementById('pvIcon').style.background=e.target.value);
</script>
@endsection
