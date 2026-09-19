<div class="grid" style="grid-template-columns:1fr 1fr">
    <div class="form-group"><label>Nombre del plan *</label><input name="name" value="{{ old('name',$plan->name ?? '') }}" class="form-control" required></div>
    <div class="form-group"><label>Color</label><input type="color" name="color" value="{{ old('color',$plan->color ?? '#7c3aed') }}" class="form-control" style="height:44px;padding:4px"></div>
    <div class="form-group" style="grid-column:1/-1"><label>Descripción</label><input name="description" value="{{ old('description',$plan->description ?? '') }}" class="form-control"></div>
    <div class="form-group"><label>Precio mensual ({{ currency_symbol() }}) *</label><input type="number" step="0.01" name="price_monthly" value="{{ old('price_monthly',$plan->price_monthly ?? 0) }}" class="form-control" required></div>
    <div class="form-group"><label>Precio anual ({{ currency_symbol() }}) *</label><input type="number" step="0.01" name="price_yearly" value="{{ old('price_yearly',$plan->price_yearly ?? 0) }}" class="form-control" required></div>
    <div class="form-group"><label>Máx. socios *</label><input type="number" name="max_members" value="{{ old('max_members',$plan->max_members ?? 100) }}" class="form-control" required></div>
    <div class="form-group"><label>Máx. entrenadores *</label><input type="number" name="max_trainers" value="{{ old('max_trainers',$plan->max_trainers ?? 5) }}" class="form-control" required></div>
    <div class="form-group"><label>Máx. clases *</label><input type="number" name="max_classes" value="{{ old('max_classes',$plan->max_classes ?? 10) }}" class="form-control" required></div>
    <div class="form-group"><label>Orden</label><input type="number" name="sort_order" value="{{ old('sort_order',$plan->sort_order ?? 0) }}" class="form-control"></div>
    <div class="form-group" style="grid-column:1/-1"><label>Características (una por línea)</label>
        <textarea name="features" class="form-control" rows="6">{{ old('features', isset($plan) ? implode("\n", $plan->features_array) : "Hasta X socios\nSoporte por email\nReportes básicos") }}</textarea>
    </div>
    <div class="form-group"><label style="display:flex;gap:8px;align-items:center;font-weight:500"><input type="checkbox" name="is_popular" value="1" @checked(old('is_popular',$plan->is_popular ?? false))> Marcar como "Popular"</label></div>
    <div class="form-group"><label style="display:flex;gap:8px;align-items:center;font-weight:500"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$plan->is_active ?? true))> Plan activo (visible en landing)</label></div>
</div>
