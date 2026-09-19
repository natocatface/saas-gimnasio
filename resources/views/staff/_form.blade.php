@push('styles')
<style>
    .sform{background:#fff;border:1px solid var(--border);border-radius:16px;padding:26px;max-width:620px}
    .sform .fg{margin-bottom:16px}
    .sform label{display:block;font-size:13px;font-weight:600;margin-bottom:6px}
    .sform input,.sform select{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit}
    .sform input:focus,.sform select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(124,58,237,.12)}
    .r2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
</style>
@endpush

<div class="r2">
    <div class="fg"><label>Nombre completo *</label><input name="name" value="{{ old('name',$staff->name ?? '') }}" required></div>
    <div class="fg"><label>Teléfono</label><input name="phone" value="{{ old('phone',$staff->phone ?? '') }}"></div>
    <div class="fg"><label>Correo electrónico *</label><input type="email" name="email" value="{{ old('email',$staff->email ?? '') }}" required></div>
    <div class="fg"><label>Rol *</label>
        <select name="role" required>
            @foreach($roles as $k=>$label)<option value="{{ $k }}" @selected(old('role',$staff->role ?? 'trainer')===$k)>{{ $label }}</option>@endforeach
        </select>
    </div>
    <div class="fg" style="grid-column:1/-1">
        <label>{{ isset($staff) ? 'Nueva contraseña (dejar vacío para no cambiar)' : 'Contraseña *' }}</label>
        <input type="password" name="password" {{ isset($staff) ? '' : 'required' }} placeholder="Mínimo 6 caracteres">
    </div>
</div>
