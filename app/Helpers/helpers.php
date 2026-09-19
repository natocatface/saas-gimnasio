<?php
/**
 * Helpers globales de la aplicación.
 * Registrados en App\Providers\AppServiceProvider::register().
 */

use App\Models\Setting;

if (! function_exists('gym_settings_all')) {
    /**
     * Devuelve TODOS los settings del gimnasio actual como array key=>value.
     * Se cachea por request para no consultar la BD en cada llamada.
     */
    function gym_settings_all(): array
    {
        if (isset($GLOBALS['__gym_settings_cache']) && is_array($GLOBALS['__gym_settings_cache'])) {
            return $GLOBALS['__gym_settings_cache'];
        }
        try {
            // El modelo Setting tiene el scope de tenant: solo trae los del gym actual.
            $GLOBALS['__gym_settings_cache'] = Setting::pluck('value', 'key')->toArray();
        } catch (\Throwable $e) {
            $GLOBALS['__gym_settings_cache'] = [];
        }
        return $GLOBALS['__gym_settings_cache'];
    }
}

if (! function_exists('gym_setting')) {
    /**
     * Lee un setting del gimnasio actual con valor por defecto.
     */
    function gym_setting(string $key, $default = null)
    {
        $all = gym_settings_all();
        $val = $all[$key] ?? null;
        return ($val === null || $val === '') ? $default : $val;
    }
}

if (! function_exists('gym_settings_forget')) {
    /** Limpia la caché (úsalo tras guardar settings en el mismo request). */
    function gym_settings_forget(): void
    {
        unset($GLOBALS['__gym_settings_cache']);
    }
}

/* ===================================================================== */
/*  Configuración GLOBAL del sistema (nivel SaaS, gymnasium_id = NULL)    */
/*  La define el Super Admin y se aplica a TODO el sistema.              */
/* ===================================================================== */

if (! function_exists('saas_settings_all')) {
    /** Devuelve TODOS los settings globales (gymnasium_id NULL) como array. */
    function saas_settings_all(): array
    {
        if (isset($GLOBALS['__saas_settings_cache']) && is_array($GLOBALS['__saas_settings_cache'])) {
            return $GLOBALS['__saas_settings_cache'];
        }
        try {
            $GLOBALS['__saas_settings_cache'] = \Illuminate\Support\Facades\DB::table('settings')
                ->whereNull('gymnasium_id')
                ->pluck('value', 'key')
                ->toArray();
        } catch (\Throwable $e) {
            $GLOBALS['__saas_settings_cache'] = [];
        }
        return $GLOBALS['__saas_settings_cache'];
    }
}

if (! function_exists('saas_setting')) {
    /** Lee un setting global con valor por defecto. */
    function saas_setting(string $key, $default = null)
    {
        $val = saas_settings_all()[$key] ?? null;
        return ($val === null || $val === '') ? $default : $val;
    }
}

if (! function_exists('saas_settings_forget')) {
    /** Limpia la caché de settings globales. */
    function saas_settings_forget(): void
    {
        unset($GLOBALS['__saas_settings_cache']);
    }
}

if (! function_exists('currency_setting')) {
    /**
     * Resuelve una clave de moneda con prioridad:
     *   1) Configuración GLOBAL del sistema (Super Admin)
     *   2) Configuración del gimnasio actual (compatibilidad)
     *   3) Valor por defecto
     */
    function currency_setting(string $key, $default = null)
    {
        $global = saas_setting($key, null);
        if ($global !== null && $global !== '') {
            return $global;
        }
        return gym_setting($key, $default);
    }
}

if (! function_exists('currency_symbol')) {
    /** Símbolo de moneda vigente (ej. "$", "S/", "€"). */
    function currency_symbol(): string
    {
        return (string) currency_setting('currency_symbol', '$');
    }
}

if (! function_exists('currency_position')) {
    /** Posición del símbolo: "before" | "after". */
    function currency_position(): string
    {
        return currency_setting('currency_position', 'before') === 'after' ? 'after' : 'before';
    }
}

if (! function_exists('currency_code')) {
    /** Código ISO de la moneda (ej. "USD", "PEN", "EUR"). */
    function currency_code(): string
    {
        return (string) currency_setting('currency', 'USD');
    }
}

if (! function_exists('money')) {
    /**
     * Formatea un monto según la configuración del gimnasio:
     *  - currency_symbol      (ej. "$", "S/", "€")
     *  - currency_position    (before | after)
     *  - decimals             (n.º de decimales, def. 2)
     *  - thousands_sep        (",", ".", " " o "")
     *  - decimal_sep          (".", ",")
     *
     * @param float|int|string|null $amount
     * @param int|null  $decimals  Sobrescribe los decimales configurados.
     * @param bool      $withSymbol Incluir el símbolo de moneda.
     */
    function money($amount, ?int $decimals = null, bool $withSymbol = true): string
    {
        $symbol   = currency_setting('currency_symbol', '$');
        $position = currency_setting('currency_position', 'before');
        $dec      = $decimals ?? (int) currency_setting('decimals', 2);

        $thousands = currency_setting('thousands_sep', ',');
        $decimal   = currency_setting('decimal_sep', '.');

        // Permitir tokens legibles desde el formulario
        $map = ['coma' => ',', 'punto' => '.', 'espacio' => ' ', 'ninguno' => '', 'none' => '', 'space' => ' '];
        $thousands = $map[$thousands] ?? $thousands;
        $decimal   = $map[$decimal]   ?? $decimal;
        if ($decimal === '') {
            $decimal = '.';
        }

        $num = number_format((float) $amount, max(0, $dec), $decimal, $thousands);

        if (! $withSymbol) {
            return $num;
        }

        return $position === 'after'
            ? $num . ' ' . $symbol
            : $symbol . $num;
    }
}

if (! function_exists('tax_rate')) {
    /** Tasa de impuesto configurada (porcentaje, ej. 18.0). */
    function tax_rate(): float
    {
        return (float) gym_setting('tax_rate', 0);
    }
}
