@extends('layouts.app')

@section('title', $member->full_name)
@section('page-title', 'Detalle del Socio')
@section('breadcrumb')
<a href="{{ route('members.index') }}">Socios</a>
<span class="sep">/</span>
<span class="current">{{ $member->full_name }}</span>
@endsection

@section('content')
<div class="grid-2 mb-4" style="align-items:start;">

    <!-- Profile Card -->
    <div class="card">
        <div class="card-body" style="text-align:center; padding:36px 24px;">
            <div class="member-avatar" style="width:80px;height:80px;font-size:28px;margin:0 auto 16px;">
                {{ $member->initials }}
            </div>
            <h2 style="font-size:22px; font-weight:700; margin-bottom:4px;">{{ $member->full_name }}</h2>
            <p class="text-muted text-sm">{{ $member->code }}</p>
            <div style="margin:16px 0;">
                @php $statusMap = ['activo'=>'badge-success','vencido'=>'badge-danger','inactivo'=>'badge-gray','suspendido'=>'badge-warning']; @endphp
                <span class="badge {{ $statusMap[$member->status] ?? 'badge-gray' }}" style="font-size:13px;padding:6px 16px;">
                    {{ ucfirst($member->status) }}
                </span>
            </div>
            @if($member->plan)
                <span class="badge badge-purple" style="font-size:13px;padding:6px 16px;">
                    {{ $member->plan->name }}
                </span>
            @endif
            <div style="margin-top:24px; display:flex; gap:10px; justify-content:center;">
                <a href="{{ route('members.edit', $member) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('payments.create') }}?member_id={{ $member->id }}" class="btn btn-sm" style="background:var(--success);color:white;">
                    <i class="fas fa-credit-card"></i> Pago
                </a>
            </div>
        </div>

        <div style="border-top:1px solid var(--border); padding:20px 24px;">
            <h4 style="font-size:12px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Información Personal</h4>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div class="d-flex align-center gap-3">
                    <i class="fas fa-envelope" style="width:18px;color:var(--primary);"></i>
                    <span class="text-sm">{{ $member->email ?? 'No registrado' }}</span>
                </div>
                <div class="d-flex align-center gap-3">
                    <i class="fas fa-phone" style="width:18px;color:var(--primary);"></i>
                    <span class="text-sm">{{ $member->phone ?? 'No registrado' }}</span>
                </div>
                <div class="d-flex align-center gap-3">
                    <i class="fas fa-birthday-cake" style="width:18px;color:var(--primary);"></i>
                    <span class="text-sm">{{ $member->birth_date ? $member->birth_date->format('d/m/Y') : 'No registrado' }}</span>
                </div>
                <div class="d-flex align-center gap-3">
                    <i class="fas fa-venus-mars" style="width:18px;color:var(--primary);"></i>
                    <span class="text-sm">{{ $member->gender === 'M' ? 'Masculino' : ($member->gender === 'F' ? 'Femenino' : ($member->gender ?? '—')) }}</span>
                </div>
                @if($member->address)
                <div class="d-flex align-center gap-3">
                    <i class="fas fa-map-marker-alt" style="width:18px;color:var(--primary);"></i>
                    <span class="text-sm">{{ $member->address }}</span>
                </div>
                @endif
            </div>
        </div>

        @if($member->emergency_contact)
        <div style="border-top:1px solid var(--border); padding:20px 24px; background:#fefce8;">
            <h4 style="font-size:12px; font-weight:700; color:var(--warning); text-transform:uppercase; letter-spacing:1px; margin-bottom:12px;">
                <i class="fas fa-exclamation-triangle"></i> Contacto de Emergencia
            </h4>
            <div class="text-sm fw-600">{{ $member->emergency_contact }}</div>
            <div class="text-sm text-muted">{{ $member->emergency_phone }}</div>
        </div>
        @endif
    </div>

    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Membership Info -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-id-card"></i> Membresía</div>
            </div>
            <div class="card-body">
                <div class="grid-2" style="gap:16px;">
                    <div style="background:var(--primary-light);border-radius:12px;padding:16px;">
                        <div class="text-xs text-muted fw-600 mb-2">INICIO</div>
                        <div class="fw-700">{{ $member->membership_start ? $member->membership_start->format('d/m/Y') : '—' }}</div>
                    </div>
                    <div style="background:#fee2e2;border-radius:12px;padding:16px;">
                        <div class="text-xs text-muted fw-600 mb-2">VENCIMIENTO</div>
                        <div class="fw-700">{{ $member->membership_end ? $member->membership_end->format('d/m/Y') : '—' }}</div>
                    </div>
                </div>
                @if($member->membership_end)
                @php $daysLeft = now()->diffInDays($member->membership_end, false); @endphp
                <div style="margin-top:16px;">
                    <div class="d-flex justify-between text-sm mb-2">
                        <span class="text-muted">Días restantes</span>
                        <span class="fw-700 {{ $daysLeft > 7 ? '' : 'text-danger' }}">{{ max(0,$daysLeft) }} días</span>
                    </div>
                    @if($member->membership_start)
                    @php
                        $total = $member->membership_start->diffInDays($member->membership_end);
                        $used  = $member->membership_start->diffInDays(now());
                        $pct   = $total > 0 ? min(100, round($used/$total*100)) : 0;
                    @endphp
                    <div class="progress">
                        <div class="progress-bar" style="width:{{ $pct }}%; background: {{ $daysLeft <= 7 ? 'var(--danger)' : 'linear-gradient(90deg,var(--primary),var(--accent))' }};"></div>
                    </div>
                    <div class="text-xs text-muted mt-2">{{ $pct }}% del período utilizado</div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-credit-card"></i> Pagos Recientes</div>
                <a href="{{ route('payments.create') }}?member_id={{ $member->id }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Pago
                </a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Plan</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($member->payments->take(5) as $p)
                        <tr>
                            <td class="text-sm">{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
                            <td class="text-sm">{{ optional($p->plan)->name ?? '—' }}</td>
                            <td class="fw-700">{{ money($p->amount,2) }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($p->payment_method) }}</span></td>
                            <td>
                                <span class="badge {{ $p->status==='pagado'?'badge-success':($p->status==='pendiente'?'badge-warning':'badge-danger') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-secondary);">Sin pagos registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Attendance Stats -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-fingerprint"></i> Asistencia</div>
            </div>
            <div class="card-body">
                <div class="grid-3" style="gap:12px;">
                    <div style="text-align:center;background:var(--body-bg);border-radius:12px;padding:16px;">
                        <div class="stat-value" style="font-size:24px;">{{ $member->attendance->count() }}</div>
                        <div class="stat-label">Total visitas</div>
                    </div>
                    <div style="text-align:center;background:var(--body-bg);border-radius:12px;padding:16px;">
                        <div class="stat-value" style="font-size:24px;">{{ $member->attendance->filter(fn($a) => $a->check_in && \Carbon\Carbon::parse($a->check_in)->isSameMonth(now()))->count() }}</div>
                        <div class="stat-label">Este mes</div>
                    </div>
                    <div style="text-align:center;background:var(--body-bg);border-radius:12px;padding:16px;">
                        <div class="stat-value" style="font-size:24px;">{{ $member->attendance->filter(fn($a) => $a->check_in && \Carbon\Carbon::parse($a->check_in)->gte(now()->subDays(7)))->count() }}</div>
                        <div class="stat-label">Esta semana</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rutina de Entrenamiento -->
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-dumbbell"></i> Rutina de Entrenamiento</div></div>
            <div class="card-body">
                @forelse($member->routines as $r)
                    <div style="border:1px solid var(--border);border-radius:12px;padding:14px;margin-bottom:12px">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                            <div><strong>{{ $r->title }}</strong> @if($r->trainer)<span class="text-xs text-muted">· {{ $r->trainer->name }}</span>@endif</div>
                            <form method="POST" action="{{ route('routines.destroy',$r) }}" onsubmit="return confirm('¿Eliminar rutina?')">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm" style="color:var(--danger)"><i class="fas fa-trash"></i></button></form>
                        </div>
                        @if($r->description)<div class="text-sm text-muted" style="margin-bottom:8px">{{ $r->description }}</div>@endif
                        @if(!empty($r->exercises))
                        <table style="width:100%;font-size:13px">
                            <thead><tr><th style="text-align:left">Ejercicio</th><th>Series</th><th>Reps</th><th style="text-align:left">Notas</th></tr></thead>
                            <tbody>
                                @foreach($r->exercises as $ex)
                                <tr><td>{{ $ex['name'] ?? '' }}</td><td style="text-align:center">{{ $ex['sets'] ?? '' }}</td><td style="text-align:center">{{ $ex['reps'] ?? '' }}</td><td class="text-muted">{{ $ex['notes'] ?? '' }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                @empty
                    <div class="text-sm text-muted" style="margin-bottom:14px">Este socio aún no tiene rutina asignada.</div>
                @endforelse

                <details style="margin-top:8px">
                    <summary style="cursor:pointer;font-weight:600;font-size:14px;color:var(--primary)"><i class="fas fa-plus"></i> Asignar nueva rutina</summary>
                    <form method="POST" action="{{ route('routines.store',$member) }}" style="margin-top:12px;display:flex;flex-direction:column;gap:10px">
                        @csrf
                        <input name="title" class="form-control" placeholder="Título (ej: Fuerza - Tren superior)" required>
                        <select name="trainer_id" class="form-control">
                            <option value="">Entrenador (opcional)</option>
                            @foreach($trainers as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                        </select>
                        <input name="description" class="form-control" placeholder="Descripción (opcional)">
                        <textarea name="exercises_text" class="form-control" rows="5" placeholder="Un ejercicio por línea:&#10;Press banca | 4 | 10 | descanso 90s&#10;Sentadilla | 4 | 12 |"></textarea>
                        <div class="text-xs text-muted">Formato: <code>Nombre | series | reps | notas</code></div>
                        <div><button class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Guardar rutina</button></div>
                    </form>
                </details>
            </div>
        </div>

        <!-- Progreso / Medidas -->
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fas fa-chart-line"></i> Progreso</div></div>
            <div class="card-body">
                @if($member->measurements->count() >= 2)
                    <div style="height:220px;margin-bottom:16px"><canvas id="progressChart"></canvas></div>
                @endif

                <div class="table-container" style="margin-bottom:14px">
                    <table style="font-size:13px">
                        <thead><tr><th>Fecha</th><th>Peso</th><th>% Grasa</th><th>Cintura</th><th>Pecho</th><th></th></tr></thead>
                        <tbody>
                            @forelse($member->measurements->sortByDesc('measured_on') as $mm)
                            <tr>
                                <td>{{ $mm->measured_on->format('d/m/Y') }}</td>
                                <td>{{ $mm->weight ? $mm->weight.' kg' : '—' }}</td>
                                <td>{{ $mm->body_fat ? $mm->body_fat.'%' : '—' }}</td>
                                <td>{{ $mm->waist ? $mm->waist.' cm' : '—' }}</td>
                                <td>{{ $mm->chest ? $mm->chest.' cm' : '—' }}</td>
                                <td style="text-align:right"><form method="POST" action="{{ route('measurements.destroy',$mm) }}" onsubmit="return confirm('¿Eliminar medición?')">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm" style="color:var(--danger)"><i class="fas fa-trash"></i></button></form></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;padding:18px;color:var(--text-secondary)">Sin mediciones registradas</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <details>
                    <summary style="cursor:pointer;font-weight:600;font-size:14px;color:var(--primary)"><i class="fas fa-plus"></i> Registrar medición</summary>
                    <form method="POST" action="{{ route('measurements.store',$member) }}" style="margin-top:12px;display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
                        @csrf
                        <div><label class="text-xs">Fecha</label><input type="date" name="measured_on" class="form-control" value="{{ now()->format('Y-m-d') }}" required></div>
                        <div><label class="text-xs">Peso (kg)</label><input type="number" step="0.1" name="weight" class="form-control"></div>
                        <div><label class="text-xs">% Grasa</label><input type="number" step="0.1" name="body_fat" class="form-control"></div>
                        <div><label class="text-xs">Cintura (cm)</label><input type="number" step="0.1" name="waist" class="form-control"></div>
                        <div><label class="text-xs">Pecho (cm)</label><input type="number" step="0.1" name="chest" class="form-control"></div>
                        <div><label class="text-xs">Brazo (cm)</label><input type="number" step="0.1" name="arm" class="form-control"></div>
                        <div style="grid-column:1/-1"><button class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Guardar medición</button></div>
                    </form>
                </details>
            </div>
        </div>
    </div>
</div>

@if($member->measurements->count() >= 2)
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    const mData = @json($member->measurements->map(fn($m)=>['d'=>$m->measured_on->format('d/m'),'w'=>$m->weight,'f'=>$m->body_fat])->values());
    new Chart(document.getElementById('progressChart'), {
        type:'line',
        data:{ labels:mData.map(x=>x.d),
            datasets:[
                {label:'Peso (kg)', data:mData.map(x=>x.w), borderColor:'#7c3aed', backgroundColor:'rgba(124,58,237,.1)', tension:.4, borderWidth:3, pointRadius:4, yAxisID:'y'},
                {label:'% Grasa', data:mData.map(x=>x.f), borderColor:'#ec4899', backgroundColor:'rgba(236,72,153,.1)', tension:.4, borderWidth:2, pointRadius:3, yAxisID:'y1'}
            ]},
        options:{ maintainAspectRatio:false, interaction:{intersect:false,mode:'index'},
            plugins:{legend:{labels:{boxWidth:12,font:{size:11}}}},
            scales:{ y:{position:'left',title:{display:true,text:'kg'}}, y1:{position:'right',grid:{drawOnChartArea:false},title:{display:true,text:'%'}} } }
    });
</script>
@endif
@endsection
