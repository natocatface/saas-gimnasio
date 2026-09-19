<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\RegisterGymController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GymClassController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\GymProfileController;
use App\Http\Controllers\Portal\MemberAuthController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\Billing\BillingSettingController;
use App\Http\Controllers\Billing\ElectronicDocumentController;
use App\Http\Controllers\SuperAdmin\SupportController as SaSupport;
use App\Http\Controllers\SuperAdmin\DashboardController as SaDashboard;
use App\Http\Controllers\SuperAdmin\GymController as SaGym;
use App\Http\Controllers\SuperAdmin\SaasPlanController as SaPlan;
use App\Http\Controllers\SuperAdmin\SubscriptionController as SaSubscription;
use App\Http\Controllers\SuperAdmin\ImpersonationController;
use App\Http\Controllers\SuperAdmin\ReportController as SaReport;
use App\Http\Controllers\SuperAdmin\CouponController as SaCoupon;
use App\Http\Controllers\SuperAdmin\AnnouncementController as SaAnnouncement;
use App\Http\Controllers\SuperAdmin\SettingController as SaSetting;

/*
|--------------------------------------------------------------------------
| Público (landing + auto-registro de gimnasios)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/registrar-gimnasio', [RegisterGymController::class, 'show'])->name('register.gym');
Route::post('/registrar-gimnasio', [RegisterGymController::class, 'store'])->name('register.gym.store');

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get');

// Salir de la impersonación (siempre accesible para volver al Super Admin)
Route::middleware('auth')->post('/impersonation/leave', [ImpersonationController::class, 'leave'])->name('impersonation.leave');

/*
|--------------------------------------------------------------------------
| Portal del Socio (guard 'member')
|--------------------------------------------------------------------------
*/
Route::prefix('socio')->group(function () {
    Route::get('/login', [MemberAuthController::class, 'showLogin'])->name('portal.login');
    Route::post('/login', [MemberAuthController::class, 'login'])->name('portal.login.post');
    Route::post('/logout', [MemberAuthController::class, 'logout'])->name('portal.logout');

    Route::middleware('auth:member')->group(function () {
        Route::get('/', [PortalController::class, 'index'])->name('portal.index');
        Route::post('/clases/{class}/inscribir', [PortalController::class, 'enroll'])->name('portal.enroll');
        Route::delete('/clases/{enrollment}', [PortalController::class, 'unenroll'])->name('portal.unenroll');
    });
});

/*
|--------------------------------------------------------------------------
| Panel del Gimnasio (multi-tenant) — auth + tenant
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'tenant'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('members', MemberController::class);

    // Rutinas y progreso del socio
    Route::post('/members/{member}/routines', [RoutineController::class, 'store'])->name('routines.store');
    Route::delete('/routines/{routine}', [RoutineController::class, 'destroy'])->name('routines.destroy');
    Route::post('/members/{member}/measurements', [MeasurementController::class, 'store'])->name('measurements.store');
    Route::delete('/measurements/{measurement}', [MeasurementController::class, 'destroy'])->name('measurements.destroy');
    Route::resource('plans', PlanController::class);
    Route::resource('payments', PaymentController::class);
    Route::get('/payments/{payment}/recibo-pdf', [PaymentController::class, 'receiptPdf'])->name('payments.receipt.pdf');
    Route::get('/clases/horario', [GymClassController::class, 'schedule'])->name('classes.schedule');
    Route::post('/classes/{class}/enroll', [GymClassController::class, 'enroll'])->name('classes.enroll');
    Route::delete('/classes/{class}/enroll/{enrollment}', [GymClassController::class, 'unenroll'])->name('classes.unenroll');
    Route::resource('classes', GymClassController::class);
    Route::resource('trainers', TrainerController::class);

    Route::resource('attendance', AttendanceController::class);
    Route::post('/attendance/{id}/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');

    Route::resource('inventory', InventoryController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/members', [ReportController::class, 'members'])->name('reports.members');
    Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Mantenimiento del sistema (respaldo / restauración / reseteo) — solo admin
    Route::get('/respaldos', [BackupController::class, 'index'])->name('backup.index');
    Route::get('/respaldos/descargar', [BackupController::class, 'download'])->name('backup.download');
    Route::get('/respaldos/{file}/archivo', [BackupController::class, 'downloadFile'])->name('backup.file');
    Route::delete('/respaldos/{file}', [BackupController::class, 'destroyFile'])->name('backup.destroy');
    Route::post('/respaldos/restaurar', [BackupController::class, 'restore'])->name('backup.restore');
    Route::post('/respaldos/resetear', [BackupController::class, 'reset'])->name('backup.reset');

    // Facturación electrónica SUNAT (Perú) — solo admin
    Route::get('/facturacion-electronica/config', [BillingSettingController::class, 'edit'])->name('sunat.settings.edit');
    Route::post('/facturacion-electronica/config', [BillingSettingController::class, 'update'])->name('sunat.settings.update');
    Route::post('/facturacion-electronica/probar', [BillingSettingController::class, 'test'])->name('sunat.settings.test');
    Route::get('/comprobantes', [ElectronicDocumentController::class, 'index'])->name('sunat.docs.index');
    Route::get('/comprobantes/nuevo', [ElectronicDocumentController::class, 'create'])->name('sunat.docs.create');
    Route::post('/comprobantes', [ElectronicDocumentController::class, 'store'])->name('sunat.docs.store');
    Route::get('/comprobantes/{document}', [ElectronicDocumentController::class, 'show'])->name('sunat.docs.show');
    Route::post('/comprobantes/{document}/emitir', [ElectronicDocumentController::class, 'emit'])->name('sunat.docs.emit');
    Route::get('/comprobantes/{document}/nota', [ElectronicDocumentController::class, 'createNote'])->name('sunat.docs.note.create');
    Route::post('/comprobantes/{document}/nota', [ElectronicDocumentController::class, 'storeNote'])->name('sunat.docs.note.store');
    Route::get('/comprobantes/{document}/xml', [ElectronicDocumentController::class, 'downloadXml'])->name('sunat.docs.xml');
    Route::get('/comprobantes/{document}/cdr', [ElectronicDocumentController::class, 'downloadCdr'])->name('sunat.docs.cdr');
    Route::get('/comprobantes/{document}/pdf', [ElectronicDocumentController::class, 'representacion'])->name('sunat.docs.pdf');

    // Onboarding (bienvenida tras registro)
    Route::get('/bienvenida', [OnboardingController::class, 'index'])->name('onboarding');

    // Facturación del gimnasio
    Route::get('/facturacion', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/facturacion/cambiar-plan', [BillingController::class, 'changePlan'])->name('billing.change');
    Route::get('/facturacion/recibo/{subscription}', [ReceiptController::class, 'show'])->name('billing.receipt');

    // Estado de cuenta (prueba vencida / suspendido)
    Route::get('/cuenta/bloqueada', [AccountController::class, 'blocked'])->name('account.blocked');

    // Gestión del equipo (staff del gimnasio)
    Route::resource('staff', StaffController::class)->except(['show']);

    // Marca del gimnasio (logo + color)
    Route::get('/mi-gimnasio', [GymProfileController::class, 'edit'])->name('gym.profile.edit');
    Route::post('/mi-gimnasio', [GymProfileController::class, 'update'])->name('gym.profile.update');

    // Perfil del usuario
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/perfil', [ProfileController::class, 'update'])->name('profile.update');

    // Exportaciones (CSV / Excel)
    Route::get('/export/socios', [ExportController::class, 'members'])->name('export.members');
    Route::get('/export/pagos', [ExportController::class, 'payments'])->name('export.payments');

    // Notificaciones
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');

    // Soporte (lado gimnasio)
    Route::get('/soporte', [SupportController::class, 'index'])->name('support.index');
    Route::get('/soporte/nuevo', [SupportController::class, 'create'])->name('support.create');
    Route::post('/soporte', [SupportController::class, 'store'])->name('support.store');
    Route::get('/soporte/{ticket}', [SupportController::class, 'show'])->name('support.show');
    Route::post('/soporte/{ticket}/responder', [SupportController::class, 'reply'])->name('support.reply');
    Route::post('/soporte/{ticket}/cerrar', [SupportController::class, 'close'])->name('support.close');
});

/*
|--------------------------------------------------------------------------
| Panel Super Admin (dueño del SaaS) — auth + superadmin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/', [SaDashboard::class, 'index'])->name('dashboard');

        // Gestión de gimnasios
        Route::get('/gyms', [SaGym::class, 'index'])->name('gyms.index');
        Route::get('/gyms/create', [SaGym::class, 'create'])->name('gyms.create');
        Route::post('/gyms', [SaGym::class, 'store'])->name('gyms.store');
        Route::get('/gyms/{gym}', [SaGym::class, 'show'])->name('gyms.show');
        Route::get('/gyms/{gym}/edit', [SaGym::class, 'edit'])->name('gyms.edit');
        Route::put('/gyms/{gym}', [SaGym::class, 'update'])->name('gyms.update');
        Route::patch('/gyms/{gym}/status', [SaGym::class, 'setStatus'])->name('gyms.status');
        Route::post('/gyms/{gym}/impersonate', [ImpersonationController::class, 'start'])->name('gyms.impersonate');
        Route::delete('/gyms/{gym}', [SaGym::class, 'destroy'])->name('gyms.destroy');

        // Planes SaaS
        Route::get('/plans', [SaPlan::class, 'index'])->name('plans.index');
        Route::get('/plans/create', [SaPlan::class, 'create'])->name('plans.create');
        Route::post('/plans', [SaPlan::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [SaPlan::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}', [SaPlan::class, 'update'])->name('plans.update');
        Route::delete('/plans/{plan}', [SaPlan::class, 'destroy'])->name('plans.destroy');

        // Reportes y analítica
        Route::get('/reports', [SaReport::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [SaReport::class, 'exportCsv'])->name('reports.export');

        // Soporte
        Route::get('/support', [SaSupport::class, 'index'])->name('support.index');
        Route::get('/support/{ticket}', [SaSupport::class, 'show'])->name('support.show');
        Route::post('/support/{ticket}/reply', [SaSupport::class, 'reply'])->name('support.reply');
        Route::patch('/support/{ticket}/status', [SaSupport::class, 'setStatus'])->name('support.status');

        // Comunicados masivos
        Route::get('/announcements', [SaAnnouncement::class, 'create'])->name('announcements.create');
        Route::post('/announcements', [SaAnnouncement::class, 'send'])->name('announcements.send');

        // Cupones
        Route::get('/coupons', [SaCoupon::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create', [SaCoupon::class, 'create'])->name('coupons.create');
        Route::post('/coupons', [SaCoupon::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}/edit', [SaCoupon::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}', [SaCoupon::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [SaCoupon::class, 'destroy'])->name('coupons.destroy');

        // Suscripciones / cobros
        Route::get('/subscriptions', [SaSubscription::class, 'index'])->name('subscriptions.index');
        Route::get('/subscriptions/create', [SaSubscription::class, 'create'])->name('subscriptions.create');
        Route::post('/subscriptions', [SaSubscription::class, 'store'])->name('subscriptions.store');
        Route::patch('/subscriptions/{subscription}/cancel', [SaSubscription::class, 'cancel'])->name('subscriptions.cancel');
        Route::get('/subscriptions/{subscription}/recibo', [ReceiptController::class, 'show'])->name('subscriptions.receipt');

        // Configuración del sistema (moneda global)
        Route::get('/configuracion', [SaSetting::class, 'index'])->name('settings.index');
        Route::post('/configuracion', [SaSetting::class, 'update'])->name('settings.update');
    });
