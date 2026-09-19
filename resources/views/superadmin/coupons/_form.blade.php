<div class="grid" style="grid-template-columns:1fr 1fr">
    <div class="form-group"><label>Código *</label><input name="code" value="{{ old('code',$coupon->code ?? '') }}" class="form-control" style="text-transform:uppercase" placeholder="EJ: BIENVENIDA20" required></div>
    <div class="form-group"><label>Descripción</label><input name="description" value="{{ old('description',$coupon->description ?? '') }}" class="form-control"></div>
    <div class="form-group"><label>Tipo *</label>
        <select name="type" class="form-control" required>
            <option value="percent" @selected(old('type',$coupon->type ?? 'percent')==='percent')>Porcentaje (%)</option>
            <option value="fixed" @selected(old('type',$coupon->type ?? '')==='fixed')>Monto fijo ($)</option>
        </select>
    </div>
    <div class="form-group"><label>Valor *</label><input type="number" step="0.01" name="value" value="{{ old('value',$coupon->value ?? '') }}" class="form-control" required></div>
    <div class="form-group"><label>Aplica al plan</label>
        <select name="saas_plan_id" class="form-control">
            <option value="">Todos los planes</option>
            @foreach($plans as $p)<option value="{{ $p->id }}" @selected(old('saas_plan_id',$coupon->saas_plan_id ?? '')==$p->id)>{{ $p->name }}</option>@endforeach
        </select>
    </div>
    <div class="form-group"><label>Máximo de usos</label><input type="number" name="max_uses" value="{{ old('max_uses',$coupon->max_uses ?? '') }}" class="form-control" placeholder="Vacío = ilimitado"></div>
    <div class="form-group"><label>Vence</label><input type="date" name="expires_at" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" class="form-control"></div>
    <div class="form-group"><label style="display:flex;gap:8px;align-items:center;font-weight:500;margin-top:30px"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$coupon->is_active ?? true))> Cupón activo</label></div>
</div>
