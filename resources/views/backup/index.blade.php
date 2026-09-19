@extends('layouts.app')

@section('title', 'Mantenimiento del Sistema')
@section('page-title', 'Mantenimiento del Sistema')
@section('breadcrumb')<span class="current">Respaldos</span>@endsection

@push('styles')
<style>
    .mt-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;align-items:start}
    .mt-full{grid-column:1 / -1}

    /* Franja de estadísticas */
    .stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:22px}
    .stat-box{background:var(--card-bg,#fff);border:1px solid var(--border);border-radius:16px;padding:18px 20px;
        display:flex;align-items:center;gap:14px;box-shadow:0 1px 2px rgba(0,0,0,.04)}
    .stat-box .ic{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;
        font-size:19px;color:#fff;flex-shrink:0}
    .stat-box .val{font-size:22px;font-weight:800;line-height:1;color:var(--text-primary)}
    .stat-box .lbl{font-size:12px;color:var(--text-secondary);margin-top:4px;font-weight:600;text-transform:uppercase;letter-spacing:.4px}

    /* Encabezado de tarjeta con acento */
    .mt-card{position:relative;overflow:hidden}
    .mt-card .card-title small{display:block;font-size:12px;font-weight:500;color:var(--text-secondary);margin-top:4px;text-transform:none;letter-spacing:0}

    .hint{font-size:13px;color:var(--text-secondary);line-height:1.55}
    .hint b{color:var(--text-primary)}

    /* Zona de peligro */
    .danger-card{border:1px solid #fecaca !important;background:linear-gradient(180deg,#fff,#fff7f7)}
    .danger-card .card-title{color:#b91c1c}
    .danger-card .card-title i{color:#dc2626 !important}

    .confirm-input{font-family:monospace;letter-spacing:2px;text-transform:uppercase}

    .drop-zone{border:2px dashed var(--border);border-radius:14px;padding:26px;text-align:center;cursor:pointer;
        transition:.15s;background:var(--body-bg)}
    .drop-zone:hover{border-color:var(--primary);background:var(--primary-light)}
    .drop-zone i{font-size:30px;color:var(--primary);margin-bottom:8px;display:block}
    .drop-zone .fname{font-weight:700;color:var(--text-primary);margin-top:8px;font-size:14px}

    .chk-row{display:flex;align-items:flex-start;gap:10px;padding:11px 14px;border:1px solid var(--border);
        border-radius:12px;margin-bottom:10px;cursor:pointer;transition:.15s;background:#fff}
    .chk-row:hover{border-color:#f59e0b;background:#fffbeb}
    .chk-row input{margin-top:2px;width:17px;height:17px;accent-color:#dc2626}
    .chk-row .t{font-weight:700;font-size:13px;color:var(--text-primary)}
    .chk-row .d{font-size:12px;color:var(--text-secondary);margin-top:2px}

    /* Tabla de respaldos */
    .bk-table{width:100%;border-collapse:collapse;font-size:13.5px}
    .bk-table th{text-align:left;padding:10px 12px;color:var(--text-secondary);font-size:11px;text-transform:uppercase;
        letter-spacing:.5px;border-bottom:1px solid var(--border)}
    .bk-table td{padding:12px;border-bottom:1px solid var(--border);color:var(--text-primary)}
    .bk-table tr:last-child td{border-bottom:none}
    .bk-file{display:flex;align-items:center;gap:9px;font-weight:600}
    .bk-file i{color:var(--primary)}
    .empty-bk{text-align:center;padding:34px;color:var(--text-secondary)}
    .empty-bk i{font-size:34px;opacity:.4;display:block;margin-bottom:10px}

    @media(max-width:900px){.mt-grid{grid-template-columns:1fr}.stat-strip{grid-template-columns:repeat(2,1fr)}}
</style>
@endpush

@section('content')

@if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}
    </div>
@endif

<style>
    .sys-hero{position:relative;overflow:hidden;border-radius:20px;padding:22px 26px;color:#fff;margin-bottom:20px;
        background:var(--primary,#7c3aed);
        background:linear-gradient(135deg,color-mix(in srgb,var(--primary,#7c3aed) 78%,#000),var(--primary,#7c3aed));
        box-shadow:0 18px 40px -18px color-mix(in srgb,var(--primary,#7c3aed) 60%,transparent);display:flex;align-items:center;gap:16px}
    .sys-hero::after{content:"";position:absolute;right:-40px;top:-40px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.08)}
    .sys-hero .ic{width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;position:relative;z-index:1}
    .sys-hero h2{margin:0;font-size:20px;font-weight:800;position:relative;z-index:1}
    .sys-hero p{margin:5px 0 0;opacity:.92;font-size:13px;position:relative;z-index:1;max-width:640px;line-height:1.5}
</style>
<div class="sys-hero">
    <div class="ic"><i class="fas fa-database"></i></div>
    <div><h2>Mantenimiento del Sistema</h2><p>Copias de seguridad, restauración y reseteo de los datos de tu gimnasio. Solo afecta a tu gimnasio.</p></div>
</div>

{{-- ─────────── Datos de TU gimnasio ─────────── --}}
<div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;font-size:13.5px;color:var(--text-secondary)">
    <i class="fas fa-shield-halved" style="color:var(--primary)"></i>
    Estás gestionando únicamente los datos de <b style="color:var(--text-primary)">{{ $stats['gym'] }}</b>. Otros gimnasios del sistema no se ven afectados.
</div>
<div class="stat-strip">
    <div class="stat-box">
        <div class="ic" style="background:linear-gradient(135deg,#7c3aed,#a855f7)"><i class="fas fa-users"></i></div>
        <div><div class="val">{{ number_format($stats['socios']) }}</div><div class="lbl">Socios</div></div>
    </div>
    <div class="stat-box">
        <div class="ic" style="background:linear-gradient(135deg,#10b981,#34d399)"><i class="fas fa-credit-card"></i></div>
        <div><div class="val">{{ number_format($stats['pagos']) }}</div><div class="lbl">Pagos</div></div>
    </div>
    <div class="stat-box">
        <div class="ic" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8)"><i class="fas fa-dumbbell"></i></div>
        <div><div class="val">{{ number_format($stats['clases']) }}</div><div class="lbl">Clases</div></div>
    </div>
    <div class="stat-box">
        <div class="ic" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)"><i class="fas fa-user-tie"></i></div>
        <div><div class="val">{{ number_format($stats['entrenadores']) }}</div><div class="lbl">Entrenadores</div></div>
    </div>
</div>

<div class="mt-grid">

    {{-- ═══════════ 1) COPIA DE SEGURIDAD ═══════════ --}}
    <div class="card mt-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-cloud-download-alt"></i>
                Copia de Seguridad
                <small>Descarga un respaldo (.sql) con los datos de tu gimnasio.</small>
            </div>
        </div>
        <div class="card-body">
            <p class="hint" style="margin-bottom:18px">
                Genera un archivo <b>.sql</b> con los datos de <b>tu gimnasio</b> (socios, pagos, clases,
                asistencia, inventario y más). Guárdalo en un lugar seguro; podrás restaurarlo cuando lo necesites.
            </p>
            <a href="{{ route('backup.download') }}" class="btn btn-primary">
                <i class="fas fa-download"></i> Generar y descargar respaldo
            </a>
        </div>
    </div>

    {{-- ═══════════ 2) RESTAURAR ═══════════ --}}
    <div class="card mt-card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-cloud-upload-alt"></i>
                Restaurar Copia
                <small>Sube un respaldo de TU gimnasio para reemplazar tus datos.</small>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('backup.restore') }}" enctype="multipart/form-data" id="restoreForm">
                @csrf
                <label class="drop-zone" id="dropZone">
                    <i class="fas fa-file-upload"></i>
                    <div>Haz clic para seleccionar un archivo <b>.sql</b></div>
                    <div class="fname" id="fileName" style="display:none"></div>
                    <input type="file" name="backup_file" accept=".sql" id="backupFile" style="display:none" required>
                </label>

                <div class="alert alert-warning" style="margin:16px 0 12px">
                    <i class="fas fa-exclamation-triangle"></i>
                    Solo se aceptan respaldos <b>de tu propio gimnasio</b>. Esto <b>reemplaza</b> tus datos actuales; haz una copia antes.
                </div>

                <div class="form-group">
                    <label class="form-label">Escribe <b>RESTAURAR</b> para confirmar</label>
                    <input type="text" name="confirm" class="form-control confirm-input" placeholder="RESTAURAR" autocomplete="off" required>
                </div>

                <button type="submit" class="btn btn-outline" style="border-color:#f59e0b;color:#b45309"
                        onclick="return confirm('¿Restaurar la base de datos? Se sobrescribirán los datos actuales.')">
                    <i class="fas fa-history"></i> Restaurar base de datos
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════ 3) RESPALDOS GUARDADOS ═══════════ --}}
    <div class="card mt-full">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-archive"></i> Respaldos Guardados</div>
        </div>
        <div class="card-body" style="padding:8px 8px 4px">
            @if($backups->isEmpty())
                <div class="empty-bk">
                    <i class="fas fa-box-open"></i>
                    Aún no hay respaldos generados en el servidor.
                </div>
            @else
                <table class="bk-table">
                    <thead>
                        <tr><th>Archivo</th><th>Tamaño</th><th>Fecha</th><th style="text-align:right">Acciones</th></tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $b)
                        <tr>
                            <td><div class="bk-file"><i class="fas fa-file-code"></i> {{ $b['name'] }}</div></td>
                            <td>{{ $b['size'] }}</td>
                            <td>{{ $b['date'] }}</td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('backup.file', $b['name']) }}" class="btn btn-ghost btn-sm" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form method="POST" action="{{ route('backup.destroy', $b['name']) }}" style="display:inline"
                                      onsubmit="return confirm('¿Eliminar este respaldo?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-ghost btn-sm" style="color:var(--danger)" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- ═══════════ 4) ZONA DE PELIGRO — RESETEAR ═══════════ --}}
    <div class="card danger-card mt-full">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-radiation"></i>
                Resetear mis datos
                <small>Deja tu gimnasio en cero para empezar de nuevo.</small>
            </div>
        </div>
        <div class="card-body">
            <p class="hint" style="margin-bottom:16px">
                Elimina los <b>datos operativos de tu gimnasio</b> (socios, pagos, clases, asistencia, inventario, rutinas,
                mediciones, entrenadores, notificaciones y tickets). <b>Solo afecta a tu gimnasio</b>; ningún otro cliente
                del sistema se ve alterado, y <b>tu cuenta y tu acceso se conservan siempre.</b>
                Esta acción <b>no se puede deshacer</b>: genera un respaldo antes.
            </p>

            <form method="POST" action="{{ route('backup.reset') }}" id="resetForm">
                @csrf
                <div style="max-width:640px">
                    <label class="chk-row">
                        <input type="checkbox" name="wipe_plans" value="1">
                        <span><span class="t">Borrar también mis planes de membresía</span>
                        <span class="d">Elimina los planes/tarifas de tu gimnasio.</span></span>
                    </label>
                    <label class="chk-row">
                        <input type="checkbox" name="wipe_settings" value="1">
                        <span><span class="t">Borrar también mi configuración</span>
                        <span class="d">Restablece la configuración de tu gimnasio (datos, impuestos, etc.).</span></span>
                    </label>
                </div>

                <div class="form-group" style="max-width:360px;margin-top:16px">
                    <label class="form-label">Escribe <b>RESETEAR</b> para confirmar</label>
                    <input type="text" name="confirm" class="form-control confirm-input" placeholder="RESETEAR" autocomplete="off" required>
                </div>

                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('¿Seguro que quieres RESETEAR el sistema? Esta acción no se puede deshacer.')">
                    <i class="fas fa-trash-alt"></i> Resetear sistema
                </button>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('backupFile');
        const name  = document.getElementById('fileName');
        const zone  = document.getElementById('dropZone');
        if (input) {
            input.addEventListener('change', function () {
                if (input.files.length) {
                    name.textContent = input.files[0].name;
                    name.style.display = 'block';
                    zone.style.borderColor = 'var(--primary)';
                }
            });
        }
    })();
</script>
@endpush
